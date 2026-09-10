<?php

namespace App\Controllers;

class Easteregg extends BaseController
{
    public function mybestie()
    {
        $session = session();
        if (! $session->get('secret_authed')) {
            return redirect()->to('easteregg/secret');
        }

        return view('easteregg/mybestie');
    }

    public function secret()
    {
        $session = session();
        $correct = 'chleoryn';

        $method = strtolower($this->request->getMethod());
        if ($method === 'post') {
            $pw = trim((string) $this->request->getPost('password'));
            if ($pw === $correct) {
                $session->set('secret_authed', true);
                return redirect()->to('easteregg/mybestie');
            }

            return view('easteregg/secret_password', ['error' => 'Password salah']);
        }

        // GET: if already authed, redirect to quiz
        if ($session->get('secret_authed')) {
            return redirect()->to('easteregg/mybestie');
        }

        return view('easteregg/secret_password');
    }
}
