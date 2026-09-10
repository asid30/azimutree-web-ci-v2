<?php

namespace App\Controllers;

use App\Models\ArchiveModel;
use App\Models\ArchiveGroupModel;
use App\Models\ClusterLocationModel;
use App\Models\EmailVerificationModel;
use App\Models\PasswordResetModel;
use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class RepositoriData extends BaseController
{
    private string $uploadPath = WRITEPATH . 'uploads/archives';

    public function index(): string
    {
        $archiveGroupModel = new ArchiveGroupModel();
        $user = $this->currentUser();

        return view('repositori_data', [
            'archiveGroups'      => $archiveGroupModel->publicList(),
            'ownedArchiveGroups' => $user ? $archiveGroupModel->ownedList((int) $user['id']) : [],
            'locations'    => (new ClusterLocationModel())->options(),
            'user'         => $user,
            'today'        => $this->todayDate(),
        ]);
    }

    public function archiveDetail(int $id)
    {
        $archiveGroupModel = new ArchiveGroupModel();
        $group = $archiveGroupModel->find($id);

        if (! $group) {
            throw PageNotFoundException::forPageNotFound('Arsip tidak ditemukan.');
        }

        $owner = (new UserModel())->find($group['user_id']);

        return view('archive_detail', [
            'archiveGroup' => $group,
            'owner'        => $owner,
            'files'        => (new ArchiveModel())->filesByGroup($id),
            'user'         => $this->currentUser(),
        ]);
    }

    public function publicProfile(string $username)
    {
        $profileUser = (new UserModel())->where('username', $username)->first();

        if (! $profileUser) {
            throw PageNotFoundException::forPageNotFound('Profile publik tidak ditemukan.');
        }

        return view('profile_public', [
            'profileUser' => $profileUser,
            'publicContacts' => $this->decodePublicContacts($profileUser['public_contact'] ?? null),
            'archiveCount' => (new ArchiveGroupModel())->where('user_id', $profileUser['id'])->countAllResults(),
            'fileCount' => (new ArchiveModel())->where('user_id', $profileUser['id'])->countAllResults(),
        ]);
    }

    public function profile()
    {
        $user = $this->requireUser();

        if (! $user) {
            return redirect()->to('/repositori-data')->with('error', 'Silakan login untuk membuka profile.');
        }

        $profileUser = (new UserModel())->find($user['id']);

        return view('profile', [
            'user' => $profileUser,
            'publicContacts' => $this->decodePublicContacts($profileUser['public_contact'] ?? null),
            'emailVerified' => $this->isEmailVerified((int) $profileUser['id']),
            'archiveCount' => (new ArchiveGroupModel())->where('user_id', $user['id'])->countAllResults(),
            'fileCount' => (new ArchiveModel())->where('user_id', $user['id'])->countAllResults(),
        ]);
    }

    public function updateProfile()
    {
        $user = $this->requireUser();

        if (! $user) {
            return redirect()->to('/repositori-data')->with('error', 'Silakan login untuk mengubah profile.');
        }

        $currentProfile = (new UserModel())->find($user['id']);

        $rules = [
            'name'           => 'required|max_length[150]',
            'username'       => 'required|max_length[100]|is_unique[users.username,id,' . $user['id'] . ']',
            'email'          => 'required|valid_email|max_length[150]|is_unique[users.email,id,' . $user['id'] . ']',
            'institution'    => 'permit_empty|max_length[150]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/repositori-data/profile')->with('error', implode(' ', $this->validator->getErrors()));
        }

        $publicContacts = $this->normalizePublicContacts($this->request->getPost('public_contact'));

        if (count($publicContacts) > 3) {
            return redirect()->to('/repositori-data/profile')->with('error', 'Kontak publik maksimal 3 item.');
        }

        foreach ($publicContacts as $contact) {
            if (mb_strlen($contact) > 150) {
                return redirect()->to('/repositori-data/profile')->with('error', 'Setiap kontak publik maksimal 150 karakter.');
            }
        }

        $data = [
            'id'             => $user['id'],
            'name'           => trim((string) $this->request->getPost('name')),
            'username'       => trim((string) $this->request->getPost('username')),
            'email'          => trim((string) $this->request->getPost('email')),
            'institution'    => trim((string) $this->request->getPost('institution')) ?: null,
            'public_contact' => $publicContacts === [] ? null : json_encode($publicContacts),
        ];

        (new UserModel())->skipValidation(true)->save($data);

        session()->set([
            'repo_username' => $data['username'],
            'repo_email'    => $data['email'],
        ]);

        if ($currentProfile && $currentProfile['email'] !== $data['email']) {
            $freshUser = (new UserModel())->find($user['id']);
            $this->createAndSendOtp($freshUser);
            session()->set('repo_email_verified', false);

            return redirect()->to('/repositori-data/profile')->with('success', 'Profile berhasil diperbarui. Email baru perlu diverifikasi.');
        }

        return redirect()->to('/repositori-data/profile')->with('success', 'Profile berhasil diperbarui.');
    }

    public function updatePassword()
    {
        $user = $this->requireUser();

        if (! $user) {
            return redirect()->to('/repositori-data')->with('error', 'Silakan login untuk mengubah password.');
        }

        $rules = [
            'current_password' => 'required',
            'password'         => 'required|min_length[8]|max_length[255]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/repositori-data/profile')->with('error', implode(' ', $this->validator->getErrors()));
        }

        $userModel = new UserModel();
        $record = $userModel->find($user['id']);

        if (! $record || ! password_verify((string) $this->request->getPost('current_password'), $record['password'])) {
            return redirect()->to('/repositori-data/profile')->with('error', 'Password saat ini tidak sesuai.');
        }

        $userModel->save([
            'id'       => $user['id'],
            'username' => $record['username'],
            'email'    => $record['email'],
            'password' => (string) $this->request->getPost('password'),
        ]);

        return redirect()->to('/repositori-data/profile')->with('success', 'Password berhasil diperbarui.');
    }

    public function admin()
    {
        $admin = $this->requireAdmin();

        if (! $admin) {
            return redirect()->to('/repositori-data')->with('error', 'Menu admin hanya dapat diakses administrator.');
        }

        return view('admin', [
            'user' => $admin,
            'users' => $this->adminUserList(),
            'archiveGroups' => $this->adminArchiveGroupList(),
            'fileCount' => (new ArchiveModel())->countAllResults(),
        ]);
    }

    public function adminCreateUser()
    {
        if (! $this->requireAdmin()) {
            return redirect()->to('/repositori-data')->with('error', 'Akses admin diperlukan.');
        }

        $rules = [
            'name' => 'required|max_length[150]',
            'username' => 'required|max_length[100]|is_unique[users.username]',
            'email' => 'required|valid_email|max_length[150]|is_unique[users.email]',
            'password' => 'required|min_length[8]|max_length[255]',
            'institution' => 'permit_empty|max_length[150]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/repositori-data/admin')->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $publicContacts = $this->normalizePublicContacts($this->request->getPost('public_contact'));

        if (count($publicContacts) > 3) {
            return redirect()->to('/repositori-data/admin')->withInput()->with('error', 'Kontak publik maksimal 3 item.');
        }

        $userId = (new UserModel())->insert([
            'name' => trim((string) $this->request->getPost('name')),
            'username' => trim((string) $this->request->getPost('username')),
            'email' => trim((string) $this->request->getPost('email')),
            'password' => (string) $this->request->getPost('password'),
            'institution' => trim((string) $this->request->getPost('institution')) ?: null,
            'public_contact' => $publicContacts === [] ? null : json_encode(array_slice($publicContacts, 0, 3)),
            'role' => 'user',
        ], true);

        $this->markEmailVerified((int) $userId);

        return redirect()->to('/repositori-data/admin')->with('success', 'User berhasil dibuat.');
    }

    public function adminEditUser(int $id)
    {
        $admin = $this->requireAdmin();

        if (! $admin) {
            return redirect()->to('/repositori-data')->with('error', 'Akses admin diperlukan.');
        }

        $managedUser = (new UserModel())->find($id);

        if (! $managedUser) {
            return redirect()->to('/repositori-data/admin')->with('error', 'User tidak ditemukan.');
        }

        if (($managedUser['role'] ?? 'user') === 'admin') {
            return redirect()->to('/repositori-data/admin')->with('error', 'Akun admin tidak dapat diubah melalui kelola user.');
        }

        return view('admin_user_edit', [
            'user' => $admin,
            'managedUser' => $managedUser,
            'publicContacts' => $this->decodePublicContacts($managedUser['public_contact'] ?? null),
            'emailVerified' => $this->isEmailVerified($id),
            'archiveCount' => (new ArchiveGroupModel())->where('user_id', $id)->countAllResults(),
        ]);
    }

    public function adminUpdateUser(int $id)
    {
        $admin = $this->requireAdmin();

        if (! $admin) {
            return redirect()->to('/repositori-data')->with('error', 'Akses admin diperlukan.');
        }

        $target = (new UserModel())->find($id);

        if (! $target) {
            return redirect()->to('/repositori-data/admin')->with('error', 'User tidak ditemukan.');
        }

        if (($target['role'] ?? 'user') === 'admin') {
            return redirect()->to('/repositori-data/admin')->with('error', 'Akun admin tidak dapat diubah melalui kelola user.');
        }

        $rules = [
            'name' => 'required|max_length[150]',
            'username' => 'required|max_length[100]|is_unique[users.username,id,' . $id . ']',
            'email' => 'required|valid_email|max_length[150]|is_unique[users.email,id,' . $id . ']',
            'institution' => 'permit_empty|max_length[150]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/repositori-data/admin/users/' . $id . '/edit')->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $publicContacts = $this->normalizePublicContacts($this->request->getPost('public_contact'));

        if (count($publicContacts) > 3) {
            return redirect()->to('/repositori-data/admin/users/' . $id . '/edit')->withInput()->with('error', 'Kontak publik maksimal 3 item.');
        }

        $data = [
            'id' => $id,
            'name' => trim((string) $this->request->getPost('name')),
            'username' => trim((string) $this->request->getPost('username')),
            'email' => trim((string) $this->request->getPost('email')),
            'institution' => trim((string) $this->request->getPost('institution')) ?: null,
            'public_contact' => $publicContacts === [] ? null : json_encode(array_slice($publicContacts, 0, 3)),
            'role' => 'user',
        ];

        (new UserModel())->skipValidation(true)->save($data);

        if ((int) $admin['id'] === $id) {
            session()->set([
                'repo_username' => $data['username'],
                'repo_email' => $data['email'],
                'repo_role' => $data['role'],
            ]);
        }

        if ($target['email'] !== $data['email']) {
            $this->markEmailUnverified($id);
        }

        return redirect()->to('/repositori-data/admin/users/' . $id . '/edit')->with('success', 'User berhasil diperbarui.');
    }

    public function adminUpdateUserPassword(int $id)
    {
        if (! $this->requireAdmin()) {
            return redirect()->to('/repositori-data')->with('error', 'Akses admin diperlukan.');
        }

        $target = (new UserModel())->find($id);

        if (! $target) {
            return redirect()->to('/repositori-data/admin')->with('error', 'User tidak ditemukan.');
        }

        if (($target['role'] ?? 'user') === 'admin') {
            return redirect()->to('/repositori-data/admin')->with('error', 'Password admin tidak dapat diubah melalui kelola user.');
        }

        $rules = ['password' => 'required|min_length[8]|max_length[255]'];

        if (! $this->validate($rules)) {
            return redirect()->to('/repositori-data/admin/users/' . $id . '/edit')->with('error', implode(' ', $this->validator->getErrors()));
        }

        (new UserModel())->skipValidation(true)->save([
            'id' => $id,
            'password' => (string) $this->request->getPost('password'),
        ]);

        return redirect()->to('/repositori-data/admin/users/' . $id . '/edit')->with('success', 'Password user berhasil diperbarui.');
    }

    public function adminVerifyUser(int $id)
    {
        if (! $this->requireAdmin()) {
            return redirect()->to('/repositori-data')->with('error', 'Akses admin diperlukan.');
        }

        if (! (new UserModel())->find($id)) {
            return redirect()->to('/repositori-data/admin')->with('error', 'User tidak ditemukan.');
        }

        $this->markEmailVerified($id);

        $redirect = $this->request->getPost('redirect_to') === 'edit'
            ? '/repositori-data/admin/users/' . $id . '/edit'
            : '/repositori-data/admin';

        return redirect()->to($redirect)->with('success', 'Email user berhasil diverifikasi.');
    }

    public function adminLoginAsUser(int $id)
    {
        $admin = $this->requireAdmin();

        if (! $admin) {
            return redirect()->to('/repositori-data')->with('error', 'Akses admin diperlukan.');
        }

        $target = (new UserModel())->find($id);

        if (! $target) {
            return redirect()->to('/repositori-data/admin')->with('error', 'User tidak ditemukan.');
        }

        if (($target['role'] ?? 'user') === 'admin') {
            return redirect()->to('/repositori-data/admin')->with('error', 'Tidak perlu login sebagai akun admin sendiri.');
        }

        session()->set('repo_impersonator_admin_id', (int) $admin['id']);
        $this->loginUser($target);

        return redirect()->to('/repositori-data')->with('success', 'Anda sedang login sebagai ' . $target['username'] . '.');
    }

    public function adminStopImpersonation()
    {
        $adminId = session('repo_impersonator_admin_id');

        if (! $adminId) {
            return redirect()->to('/repositori-data');
        }

        $admin = (new UserModel())->find($adminId);
        session()->remove('repo_impersonator_admin_id');

        if ($admin) {
            $this->loginUser($admin);
        }

        return redirect()->to('/repositori-data/admin')->with('success', 'Kembali sebagai administrator.');
    }

    public function adminDeleteUser(int $id)
    {
        $admin = $this->requireAdmin();

        if (! $admin) {
            return redirect()->to('/repositori-data')->with('error', 'Akses admin diperlukan.');
        }

        if ((int) $admin['id'] === $id) {
            return redirect()->to('/repositori-data/admin')->with('error', 'Administrator tidak dapat menghapus akun sendiri.');
        }

        $target = (new UserModel())->find($id);

        if (! $target) {
            return redirect()->to('/repositori-data/admin')->with('error', 'User tidak ditemukan.');
        }

        if (($target['role'] ?? 'user') === 'admin') {
            return redirect()->to('/repositori-data/admin')->with('error', 'Akun admin tidak dapat dihapus.');
        }

        $groups = (new ArchiveGroupModel())->where('user_id', $id)->findAll();

        foreach ($groups as $group) {
            $this->removeArchiveGroupFilesAndRows((int) $group['id']);
            (new ArchiveGroupModel())->delete((int) $group['id']);
        }

        (new UserModel())->delete($id);

        return redirect()->to('/repositori-data/admin')->with('success', 'User berhasil dihapus.');
    }

    public function adminDeleteArchiveGroup(int $groupId)
    {
        if (! $this->requireAdmin()) {
            return redirect()->to('/repositori-data')->with('error', 'Akses admin diperlukan.');
        }

        $archiveGroupModel = new ArchiveGroupModel();
        $archiveGroup = $archiveGroupModel->find($groupId);

        if (! $archiveGroup) {
            return redirect()->to('/repositori-data/admin')->with('error', 'Arsip tidak ditemukan.');
        }

        $this->removeArchiveGroupFilesAndRows($groupId);
        $archiveGroupModel->delete($groupId);

        return redirect()->to('/repositori-data/admin')->with('success', 'Arsip berhasil dihapus.');
    }

    public function register()
    {
        if ($this->currentUser()) {
            return redirect()->to('/repositori-data');
        }

        return view('register');
    }

    public function storeRegister()
    {
        if ($this->currentUser()) {
            return redirect()->to('/repositori-data');
        }

        $rules = [
            'email'            => 'required|valid_email|max_length[150]|is_unique[users.email]',
            'username'         => 'required|max_length[100]|is_unique[users.username]',
            'password'         => 'required|min_length[8]|max_length[255]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/repositori-data/register')
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $userModel = new UserModel();
        $userId = $userModel->insert([
            'name'     => trim((string) $this->request->getPost('username')),
            'username' => trim((string) $this->request->getPost('username')),
            'email'    => trim((string) $this->request->getPost('email')),
            'password' => (string) $this->request->getPost('password'),
        ], true);

        $user = $userModel->find($userId);
        $sent = $this->createAndSendOtp($user);

        $this->loginUser($user, false);

        return redirect()->to('/repositori-data/verifikasi-email')->with(
            $sent ? 'success' : 'error',
            $sent
                ? 'Registrasi berhasil. Kode OTP sudah dikirim ke email Anda.'
                : 'Registrasi berhasil, tapi email OTP belum bisa dikirim. Coba kirim ulang dari halaman verifikasi.'
        );
    }

    public function verifyEmail()
    {
        $user = $this->requireUser();

        if (! $user) {
            return redirect()->to('/repositori-data')->with('error', 'Silakan login untuk verifikasi email.');
        }

        $record = $this->emailVerificationFor((int) $user['id']);

        if ($record && (int) $record['verified'] === 1) {
            return redirect()->to('/repositori-data')->with('success', 'Email Anda sudah terverifikasi.');
        }

        return view('verify_email', [
            'user' => $user,
            'verification' => $record,
        ]);
    }

    public function confirmEmailOtp()
    {
        $user = $this->requireUser();

        if (! $user) {
            return redirect()->to('/repositori-data')->with('error', 'Silakan login untuk verifikasi email.');
        }

        $otp = trim((string) $this->request->getPost('otp'));

        if (! preg_match('/^\d{6}$/', $otp)) {
            return redirect()->to('/repositori-data/verifikasi-email')->with('error', 'Kode OTP harus berisi 6 digit angka.');
        }

        $record = $this->emailVerificationFor((int) $user['id']);

        if (! $record || ! $record['otp_hash']) {
            return redirect()->to('/repositori-data/verifikasi-email')->with('error', 'Kode OTP belum tersedia. Silakan kirim ulang OTP.');
        }

        if ($record['expires_at'] && strtotime($record['expires_at']) < time()) {
            return redirect()->to('/repositori-data/verifikasi-email')->with('error', 'Kode OTP sudah kedaluwarsa. Silakan kirim ulang OTP.');
        }

        if (! password_verify($otp, $record['otp_hash'])) {
            return redirect()->to('/repositori-data/verifikasi-email')->with('error', 'Kode OTP tidak sesuai.');
        }

        (new EmailVerificationModel())->update($record['id'], [
            'verified'    => 1,
            'otp_hash'    => null,
            'expires_at'  => null,
            'verified_at' => date('Y-m-d H:i:s'),
        ]);

        session()->set('repo_email_verified', true);

        return redirect()->to('/repositori-data')->with('success', 'Email berhasil diverifikasi.');
    }

    public function resendEmailOtp()
    {
        $sessionUser = $this->requireUser();

        if (! $sessionUser) {
            return redirect()->to('/repositori-data')->with('error', 'Silakan login untuk mengirim ulang OTP.');
        }

        $user = (new UserModel())->find($sessionUser['id']);
        $record = $this->emailVerificationFor((int) $user['id']);

        if ($record && (int) $record['verified'] === 1) {
            return redirect()->to('/repositori-data')->with('success', 'Email Anda sudah terverifikasi.');
        }

        $cooldown = $this->remainingCooldown($record['last_sent_at'] ?? null);

        if ($cooldown > 0) {
            return redirect()->to('/repositori-data/verifikasi-email')->with('error', 'Tunggu ' . $cooldown . ' detik sebelum kirim ulang OTP.');
        }

        $sent = $this->createAndSendOtp($user);

        return redirect()->to('/repositori-data/verifikasi-email')->with(
            $sent ? 'success' : 'error',
            $sent ? 'Kode OTP baru sudah dikirim.' : 'Kode OTP gagal dikirim. Periksa konfigurasi email atau coba lagi nanti.'
        );
    }

    public function skipEmailVerification()
    {
        if (! $this->requireUser()) {
            return redirect()->to('/repositori-data')->with('error', 'Silakan login terlebih dahulu.');
        }

        return redirect()->to('/repositori-data')->with('success', 'Verifikasi email dilewati untuk sementara.');
    }

    public function forgotPassword()
    {
        if ($this->currentUser()) {
            return redirect()->to('/repositori-data');
        }

        return view('forgot_password');
    }

    public function sendPasswordResetOtp()
    {
        if ($this->currentUser()) {
            return redirect()->to('/repositori-data');
        }

        $identity = trim((string) $this->request->getPost('identity'));

        if ($identity === '') {
            return redirect()->to('/repositori-data/lupa-password')->with('error', 'Email atau username wajib diisi.');
        }

        $user = (new UserModel())
            ->groupStart()
            ->where('email', $identity)
            ->orWhere('username', $identity)
            ->groupEnd()
            ->first();

        if (! $user) {
            return redirect()->to('/repositori-data/lupa-password')->with('error', 'Akun tidak ditemukan.');
        }

        $verification = $this->emailVerificationFor((int) $user['id']);

        if ($verification && (int) $verification['verified'] !== 1) {
            return redirect()->to('/repositori-data/lupa-password')->with('error', 'Email akun ini belum terverifikasi, reset password belum bisa digunakan.');
        }

        session()->set('repo_password_reset_user_id', (int) $user['id']);

        $lastReset = (new PasswordResetModel())
            ->where('user_id', $user['id'])
            ->where('used_at IS NULL', null, false)
            ->orderBy('id', 'DESC')
            ->first();
        $cooldown = $this->remainingCooldown($lastReset['last_sent_at'] ?? null);

        if ($cooldown > 0) {
            return redirect()->to('/repositori-data/reset-password')->with('error', 'Tunggu ' . $cooldown . ' detik sebelum kirim ulang OTP.');
        }

        $sent = $this->createAndSendPasswordResetOtp($user);

        return redirect()->to('/repositori-data/reset-password')->with(
            $sent ? 'success' : 'error',
            $sent ? 'Kode reset password sudah dikirim ke email Anda.' : 'Kode reset password belum bisa dikirim. Coba lagi nanti.'
        );
    }

    public function resetPassword()
    {
        if ($this->currentUser()) {
            return redirect()->to('/repositori-data');
        }

        $resetUserId = session('repo_password_reset_user_id');

        if (! $resetUserId) {
            return redirect()->to('/repositori-data/lupa-password')->with('error', 'Mulai dari form lupa password terlebih dahulu.');
        }

        $user = (new UserModel())->find($resetUserId);

        if (! $user) {
            session()->remove('repo_password_reset_user_id');
            return redirect()->to('/repositori-data/lupa-password')->with('error', 'Akun reset tidak ditemukan.');
        }

        return view('reset_password', ['user' => $user]);
    }

    public function updateForgottenPassword()
    {
        if ($this->currentUser()) {
            return redirect()->to('/repositori-data');
        }

        $resetUserId = session('repo_password_reset_user_id');

        if (! $resetUserId) {
            return redirect()->to('/repositori-data/lupa-password')->with('error', 'Mulai dari form lupa password terlebih dahulu.');
        }

        $rules = [
            'otp'              => 'required|regex_match[/^\d{6}$/]',
            'password'         => 'required|min_length[8]|max_length[255]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/repositori-data/reset-password')->with('error', implode(' ', $this->validator->getErrors()));
        }

        $resetModel = new PasswordResetModel();
        $record = $resetModel
            ->where('user_id', $resetUserId)
            ->where('used_at IS NULL', null, false)
            ->orderBy('id', 'DESC')
            ->first();

        if (! $record || strtotime($record['expires_at']) < time()) {
            return redirect()->to('/repositori-data/reset-password')->with('error', 'Kode reset password sudah kedaluwarsa.');
        }

        if (! password_verify((string) $this->request->getPost('otp'), $record['otp_hash'])) {
            return redirect()->to('/repositori-data/reset-password')->with('error', 'Kode OTP tidak sesuai.');
        }

        $userModel = new UserModel();
        $user = $userModel->find($resetUserId);

        $userModel->save([
            'id'       => $user['id'],
            'name'     => $user['name'] ?: $user['username'],
            'username' => $user['username'],
            'email'    => $user['email'],
            'password' => (string) $this->request->getPost('password'),
        ]);

        $resetModel->update($record['id'], ['used_at' => date('Y-m-d H:i:s')]);
        session()->remove('repo_password_reset_user_id');

        return redirect()->to('/repositori-data')->with('success', 'Password berhasil diperbarui. Silakan login kembali.');
    }

    public function login()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/repositori-data')->with('error', 'Username/email dan password wajib diisi.');
        }

        $identity = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel
            ->groupStart()
            ->where('username', $identity)
            ->orWhere('email', $identity)
            ->groupEnd()
            ->first();

        if (! $user || ! $this->passwordMatches($password, $user, $userModel)) {
            return redirect()->to('/repositori-data')->with('error', 'Login gagal. Periksa username/email dan password.');
        }

        $this->loginUser($user);

        return redirect()->to('/repositori-data')->with('success', 'Login berhasil.');
    }

    public function logout()
    {
        session()->remove(['repo_user_id', 'repo_username', 'repo_email', 'repo_email_verified', 'repo_role', 'repo_impersonator_admin_id']);

        return redirect()->to('/repositori-data')->with('success', 'Anda sudah logout.');
    }

    public function createArchiveGroup()
    {
        $user = $this->requireUser();

        if (! $user) {
            return redirect()->to('/repositori-data')->with('error', 'Silakan login untuk menambah arsip.');
        }

        $rules = [
            'archive_name' => [
                'label' => 'Nama arsip',
                'rules' => 'required|max_length[150]',
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/repositori-data')
                ->with('error', implode(' ', $this->validator->getErrors()))
                ->with('repo_active_tab', 'manage');
        }

        $archiveName = trim((string) $this->request->getPost('archive_name'));
        $existingArchive = db_connect()
            ->query(
                'SELECT id FROM archive_groups WHERE user_id = ? AND LOWER(name) = ? LIMIT 1',
                [(int) $user['id'], strtolower($archiveName)]
            )
            ->getRowArray();

        if ($existingArchive) {
            return redirect()->to('/repositori-data')
                ->with('error', 'Nama arsip sudah digunakan. Gunakan nama arsip lain.')
                ->with('repo_active_tab', 'manage');
        }

        (new ArchiveGroupModel())->insert([
            'name'    => $archiveName,
            'user_id' => $user['id'],
        ]);

        return redirect()->to('/repositori-data')
            ->with('success', 'Arsip berhasil ditambahkan.')
            ->with('repo_active_tab', 'manage');
    }

    public function uploadFileForm(int $groupId)
    {
        $user = $this->requireUser();

        if (! $user) {
            return redirect()->to('/repositori-data')->with('error', 'Silakan login untuk upload file.');
        }

        $archiveGroup = (new ArchiveGroupModel())->findOwned($groupId, (int) $user['id']);

        if (! $archiveGroup) {
            return redirect()->to('/repositori-data')
                ->with('error', 'Arsip tidak ditemukan atau bukan milik Anda.')
                ->with('repo_active_tab', 'manage');
        }

        return view('archive_upload', [
            'archiveGroup' => $archiveGroup,
            'today'        => $this->todayDate(),
        ]);
    }

    public function deleteArchiveGroup(int $groupId)
    {
        $user = $this->requireUser();

        if (! $user) {
            return redirect()->to('/repositori-data')->with('error', 'Silakan login untuk menghapus arsip.');
        }

        $archiveGroupModel = new ArchiveGroupModel();
        $archiveGroup = $archiveGroupModel->findOwned($groupId, (int) $user['id']);

        if (! $archiveGroup) {
            return redirect()->to('/repositori-data')
                ->with('error', 'Arsip tidak ditemukan atau bukan milik Anda.')
                ->with('repo_active_tab', 'manage');
        }

        $this->removeArchiveGroupFilesAndRows($groupId);
        $archiveGroupModel->delete($groupId);

        return redirect()->to('/repositori-data')
            ->with('success', 'Arsip berhasil dihapus.')
            ->with('repo_active_tab', 'manage');
    }

    public function uploadFile(int $groupId)
    {
        $user = $this->requireUser();

        if (! $user) {
            return redirect()->to('/repositori-data')->with('error', 'Silakan login untuk upload file.');
        }

        $archiveGroup = (new ArchiveGroupModel())->findOwned($groupId, (int) $user['id']);

        if (! $archiveGroup) {
            return redirect()->to('/repositori-data')
                ->with('error', 'Arsip tidak ditemukan atau bukan milik Anda.')
                ->with('repo_active_tab', 'manage');
        }

        $rules = [
            'cluster_code' => [
                'label' => 'Kode klaster',
                'rules' => 'required|integer|greater_than[0]',
            ],
            'taken_date' => [
                'label' => 'Tanggal diambil',
                'rules' => 'required|valid_date[Y-m-d]',
            ],
            'archive_file' => [
                'label' => 'File Excel',
                'rules' => 'uploaded[archive_file]|max_size[archive_file,10240]|ext_in[archive_file,xls,xlsx]',
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/repositori-data/arsip/' . $groupId . '/upload')
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        if ((string) $this->request->getPost('taken_date') > $this->todayDate()) {
            return redirect()->to('/repositori-data/arsip/' . $groupId . '/upload')
                ->withInput()
                ->with('error', 'Tanggal diambil tidak boleh melebihi hari ini.');
        }

        $clusterCode = (int) $this->request->getPost('cluster_code');

        $file = $this->request->getFile('archive_file');

        if (! $file || ! $file->isValid()) {
            return redirect()->to('/repositori-data/arsip/' . $groupId . '/upload')
                ->withInput()
                ->with('error', 'File tidak valid.');
        }

        if (! is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0775, true);
        }

        $storedName = $file->getRandomName();
        $file->move($this->uploadPath, $storedName);

        $archiveModel = new ArchiveModel();
        $archiveModel->insert([
            'archive_group_id'  => $archiveGroup['id'],
            'cluster_code'      => $clusterCode,
            'name'              => 'CL' . $clusterCode,
            'filename'          => $storedName,
            'original_filename' => $file->getClientName(),
            'cl_location'       => $archiveGroup['name'],
            'cl_location_id'    => null,
            'uploaded_by'       => $user['username'],
            'user_id'           => $user['id'],
            'upload_date'       => $this->request->getPost('taken_date') . ' 00:00:00',
        ]);

        return redirect()->to('/repositori-data/arsip/' . $archiveGroup['id'])
            ->with('success', 'File berhasil diunggah.');
    }

    public function upload()
    {
        return redirect()->to('/repositori-data')
            ->with('error', 'Silakan pilih arsip terlebih dahulu sebelum upload file.')
            ->with('repo_active_tab', 'manage');
    }

    public function download(int $id)
    {
        $archive = (new ArchiveModel())->find($id);

        if (! $archive) {
            throw PageNotFoundException::forPageNotFound('File arsip tidak ditemukan.');
        }

        $path = $this->uploadPath . DIRECTORY_SEPARATOR . $archive['filename'];

        if (! is_file($path)) {
            throw PageNotFoundException::forPageNotFound('File arsip tidak tersedia di server.');
        }

        return $this->response->download($path, null)->setFileName($archive['original_filename']);
    }

    public function delete(int $id)
    {
        $user = $this->requireUser();

        if (! $user) {
            return redirect()->to('/repositori-data')->with('error', 'Silakan login untuk menghapus file.');
        }

        $archiveModel = new ArchiveModel();
        $archive = $archiveModel->findOwnedArchive($id, (int) $user['id']);

        if (! $archive) {
            return redirect()->to('/repositori-data')->with('error', 'File tidak ditemukan atau bukan milik Anda.');
        }

        $path = $this->uploadPath . DIRECTORY_SEPARATOR . $archive['filename'];

        if (is_file($path)) {
            unlink($path);
        }

        $archiveModel->delete($id);

        $redirectTo = ! empty($archive['archive_group_id'])
            ? '/repositori-data/arsip/' . $archive['archive_group_id']
            : '/repositori-data';

        return redirect()->to($redirectTo)
            ->with('success', 'File berhasil dihapus.')
            ->with('repo_active_tab', 'manage');
    }

    private function currentUser(): ?array
    {
        $userId = session('repo_user_id');

        if (! $userId) {
            return null;
        }

        $record = (new UserModel())->find($userId);

        if (! $record) {
            session()->remove(['repo_user_id', 'repo_username', 'repo_email', 'repo_email_verified', 'repo_role', 'repo_impersonator_admin_id']);

            return null;
        }

        session()->set([
            'repo_username' => $record['username'],
            'repo_email' => $record['email'],
            'repo_role' => $record['role'] ?? 'user',
        ]);

        return [
            'id'       => (int) $record['id'],
            'name'     => $record['name'] ?? null,
            'username' => (string) $record['username'],
            'email'    => (string) $record['email'],
            'institution' => $record['institution'] ?? null,
            'public_contact' => $record['public_contact'] ?? null,
            'role'     => $record['role'] ?? 'user',
            'is_admin' => ($record['role'] ?? 'user') === 'admin',
            'impersonator_admin_id' => session('repo_impersonator_admin_id') ? (int) session('repo_impersonator_admin_id') : null,
            'email_verified' => (bool) session('repo_email_verified'),
        ];
    }

    private function requireUser(): ?array
    {
        return $this->currentUser();
    }

    private function requireAdmin(): ?array
    {
        $user = $this->currentUser();

        if (! $user || ($user['role'] ?? 'user') !== 'admin') {
            return null;
        }

        return $user;
    }

    private function passwordMatches(string $password, array $user, UserModel $userModel): bool
    {
        if (password_verify($password, $user['password'])) {
            return true;
        }

        if (password_get_info($user['password'])['algoName'] !== 'unknown') {
            return false;
        }

        if (! hash_equals($user['password'], $password)) {
            return false;
        }

        $userModel->save([
            'id'       => $user['id'],
            'username' => $user['username'],
            'password' => $password,
            'email'    => $user['email'],
        ]);

        return true;
    }

    private function normalizePublicContacts($contacts): array
    {
        if (! is_array($contacts)) {
            $contacts = [$contacts];
        }

        $normalized = [];

        foreach ($contacts as $contact) {
            $contact = trim((string) $contact);

            if ($contact === '') {
                continue;
            }

            $normalized[] = $contact;
        }

        return array_values($normalized);
    }

    private function decodePublicContacts(?string $contacts): array
    {
        $contacts = trim((string) $contacts);

        if ($contacts === '') {
            return [];
        }

        $decoded = json_decode($contacts, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $this->normalizePublicContacts($decoded);
        }

        return [$contacts];
    }

    private function loginUser(array $user, ?bool $verified = null): void
    {
        if ($verified === null) {
            $record = $this->emailVerificationFor((int) $user['id']);
            $verified = $record ? (int) $record['verified'] === 1 : true;
        }

        session()->set([
            'repo_user_id'        => (int) $user['id'],
            'repo_username'       => $user['username'],
            'repo_email'          => $user['email'],
            'repo_role'           => $user['role'] ?? 'user',
            'repo_email_verified' => $verified,
        ]);
    }

    private function adminArchiveGroupList(): array
    {
        return (new ArchiveGroupModel())
            ->select('archive_groups.id, archive_groups.name, archive_groups.user_id, users.name AS owner_name, users.username AS owner_username, COUNT(archives.id) AS file_count, MAX(archives.upload_date) AS latest_upload')
            ->join('users', 'users.id = archive_groups.user_id', 'left')
            ->join('archives', 'archives.archive_group_id = archive_groups.id', 'left')
            ->groupBy('archive_groups.id, archive_groups.name, archive_groups.user_id, users.name, users.username')
            ->orderBy('archive_groups.updated_at', 'DESC')
            ->findAll();
    }

    private function adminUserList(): array
    {
        $users = (new UserModel())
            ->select('users.id, users.name, users.username, users.email, users.institution, users.public_contact, users.role, users.created_at, COUNT(archive_groups.id) AS archive_count')
            ->join('archive_groups', 'archive_groups.user_id = users.id', 'left')
            ->groupBy('users.id, users.name, users.username, users.email, users.institution, users.public_contact, users.role, users.created_at')
            ->orderBy('users.created_at', 'DESC')
            ->findAll();

        $verificationModel = new EmailVerificationModel();

        foreach ($users as &$user) {
            $verification = $verificationModel->where('user_id', $user['id'])->first();
            $user['email_verified'] = ! $verification || (int) $verification['verified'] === 1;
            $user['public_contacts'] = $this->decodePublicContacts($user['public_contact'] ?? null);
        }

        return $users;
    }

    private function markEmailVerified(int $userId): void
    {
        $verificationModel = new EmailVerificationModel();
        $record = $verificationModel->where('user_id', $userId)->first();
        $data = [
            'user_id' => $userId,
            'verified' => 1,
            'otp_hash' => null,
            'expires_at' => null,
            'verified_at' => date('Y-m-d H:i:s'),
        ];

        if ($record) {
            $verificationModel->update($record['id'], $data);

            return;
        }

        $verificationModel->insert($data);
    }

    private function markEmailUnverified(int $userId): void
    {
        $verificationModel = new EmailVerificationModel();
        $record = $verificationModel->where('user_id', $userId)->first();
        $data = [
            'user_id' => $userId,
            'verified' => 0,
            'otp_hash' => null,
            'expires_at' => null,
            'verified_at' => null,
        ];

        if ($record) {
            $verificationModel->update($record['id'], $data);

            return;
        }

        $verificationModel->insert($data);
    }

    private function removeArchiveGroupFilesAndRows(int $groupId): void
    {
        $archiveModel = new ArchiveModel();
        $files = $archiveModel->where('archive_group_id', $groupId)->findAll();

        foreach ($files as $file) {
            $path = $this->uploadPath . DIRECTORY_SEPARATOR . $file['filename'];

            if (is_file($path)) {
                unlink($path);
            }
        }

        $archiveModel->where('archive_group_id', $groupId)->delete();
    }

    private function emailVerificationFor(int $userId): ?array
    {
        return (new EmailVerificationModel())->where('user_id', $userId)->first();
    }

    private function isEmailVerified(int $userId): bool
    {
        $record = $this->emailVerificationFor($userId);

        return ! $record || (int) $record['verified'] === 1;
    }

    private function createAndSendOtp(array $user): bool
    {
        $otp = (string) random_int(100000, 999999);
        $verificationModel = new EmailVerificationModel();
        $record = $verificationModel->where('user_id', $user['id'])->first();
        $data = [
            'user_id'    => (int) $user['id'],
            'verified'   => 0,
            'otp_hash'   => password_hash($otp, PASSWORD_DEFAULT),
            'expires_at' => date('Y-m-d H:i:s', time() + 1800),
            'last_sent_at' => date('Y-m-d H:i:s'),
        ];

        if ($record) {
            $verificationModel->update($record['id'], $data);
        } else {
            $verificationModel->insert($data);
        }

        return $this->sendOtpEmail($user['email'], $user['username'], $otp);
    }

    private function createAndSendPasswordResetOtp(array $user): bool
    {
        $otp = (string) random_int(100000, 999999);

        (new PasswordResetModel())->insert([
            'user_id'      => (int) $user['id'],
            'otp_hash'     => password_hash($otp, PASSWORD_DEFAULT),
            'expires_at'   => date('Y-m-d H:i:s', time() + 1800),
            'last_sent_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->sendPasswordResetEmail($user['email'], $user['username'], $otp);
    }

    private function sendOtpEmail(string $to, string $username, string $otp): bool
    {
        $email = service('email');
        $email->initialize([
            'protocol'   => env('email.protocol', 'smtp'),
            'SMTPHost'   => env('email.SMTPHost'),
            'SMTPUser'   => env('email.SMTPUser'),
            'SMTPPass'   => env('email.SMTPPass'),
            'SMTPPort'   => (int) env('email.SMTPPort', 465),
            'SMTPCrypto' => env('email.SMTPCrypto', 'ssl'),
            'mailType'   => env('email.mailType', 'html'),
            'charset'    => env('email.charset', 'UTF-8'),
            'wordWrap'   => (bool) env('email.wordWrap', true),
        ]);

        $email->setFrom(env('email.fromEmail', 'no.reply.sp@azimutree.my.id'), env('email.fromName', 'Azimutree'));
        $email->setTo($to);
        $email->setSubject('Kode OTP Verifikasi Email Azimutree');
        $email->setMessage(view('emails/otp_verification', [
            'username' => $username,
            'otp'      => $otp,
            'verifyUrl' => base_url('repositori-data/verifikasi-email'),
            'siteUrl'   => base_url('repositori-data'),
        ]));

        return $email->send(false);
    }

    private function sendPasswordResetEmail(string $to, string $username, string $otp): bool
    {
        $email = service('email');
        $email->initialize([
            'protocol'   => env('email.protocol', 'smtp'),
            'SMTPHost'   => env('email.SMTPHost'),
            'SMTPUser'   => env('email.SMTPUser'),
            'SMTPPass'   => env('email.SMTPPass'),
            'SMTPPort'   => (int) env('email.SMTPPort', 465),
            'SMTPCrypto' => env('email.SMTPCrypto', 'ssl'),
            'mailType'   => env('email.mailType', 'html'),
            'charset'    => env('email.charset', 'UTF-8'),
            'wordWrap'   => (bool) env('email.wordWrap', true),
        ]);

        $email->setFrom(env('email.fromEmail', 'no.reply.sp@azimutree.my.id'), env('email.fromName', 'Azimutree'));
        $email->setTo($to);
        $email->setSubject('Kode Reset Password Azimutree');
        $email->setMessage(view('emails/password_reset', [
            'username' => $username,
            'otp'      => $otp,
            'resetUrl' => base_url('repositori-data/reset-password'),
            'siteUrl'  => base_url('repositori-data'),
        ]));

        return $email->send(false);
    }

    private function remainingCooldown(?string $lastSentAt): int
    {
        if (! $lastSentAt) {
            return 0;
        }

        $remaining = 60 - (time() - strtotime($lastSentAt));

        return max(0, $remaining);
    }

    private function todayDate(): string
    {
        return (new \DateTimeImmutable('today', new \DateTimeZone('Asia/Jakarta')))->format('Y-m-d');
    }
}
