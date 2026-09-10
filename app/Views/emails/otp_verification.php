<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kode OTP Azimutree</title>
</head>
<body style="margin:0;background:#f4f7f6;font-family:Arial,sans-serif;color:#0f1724">
    <div style="max-width:520px;margin:0 auto;padding:28px 18px">
        <div style="background:#ffffff;border-radius:10px;padding:24px;border:1px solid #dfe7e3">
            <h2 style="margin:0 0 10px;color:#0f1724">Verifikasi Email Azimutree</h2>
            <p style="margin:0 0 16px;line-height:1.5">Halo <?= esc($username) ?>, gunakan kode OTP berikut untuk verifikasi email akun repositori data Anda.</p>
            <div style="font-size:30px;letter-spacing:8px;font-weight:700;text-align:center;background:#ecfdf5;color:#047857;border-radius:8px;padding:16px;margin:18px 0">
                <?= esc($otp) ?>
            </div>
            <p style="margin:0 0 16px;line-height:1.5;color:#475569">Kode OTP <?= esc($otp) ?> berlaku selama 30 menit. Abaikan email ini jika Anda tidak membuat akun di Azimutree.</p>
            <table role="presentation" align="center" cellpadding="0" cellspacing="0" style="margin:0 auto 12px">
                <tr>
                    <td align="center" bgcolor="#34d399" style="border-radius:8px">
                        <a href="<?= esc($verifyUrl) ?>" style="display:block;background:#34d399;color:#07221a;text-decoration:none;font-weight:700;border-radius:8px;padding:10px 14px">Buka Halaman Verifikasi</a>
                    </td>
                </tr>
            </table>
            <p style="margin:0;line-height:1.5;color:#64748b;word-break:break-word">Repositori: <a href="<?= esc($siteUrl) ?>" style="color:#047857;word-break:break-word"><?= esc($siteUrl) ?></a></p>
        </div>
    </div>
</body>
</html>
