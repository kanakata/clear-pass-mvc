<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;

class LandingController extends Controller
{
    private User $um;
    public function __construct()
    {
        parent::__construct();
        $this->um = new User();
    }
    public function show(): void
    {

        //if ($this->auth()) $this->redirect('/dashboard');

        if ($this->isPost()) {

            $this->security->verifyCsrf();

            $ip = $this->security->getIp();

            if (!$this->security->checkRateLimit('login', $ip)) {

                $this->setFlash('error', 'Too many attempts. Wait 15 minutes.');
                $this->view('auth.login', ['flash' => $this->getFlash()]);
                return;
            }
            $email = $this->security->sanitizeEmail($this->input('email', ''));
            $password = $this->input('password', '');
            if (!$email) {

                $this->setFlash('error', 'Invalid email.');
                $this->view('auth.login', ['flash' => $this->getFlash()]);
                return;
            }
            $user = $this->um->findByEmail($email);
            if (!$user || !$this->security->verifyPassword($password, $user['password'])) {

                $rem = $this->security->getRemainingAttempts('login', $ip);
                $this->setFlash('error', "Invalid credentials. $rem attempts left.");
                $this->view('auth.login', ['flash' => $this->getFlash()]);
                return;
            }
            if (!$user['is_active']) {
                $this->setFlash('error', 'Account suspended.');
                $this->view('auth.login', ['flash' => $this->getFlash()]);
                return;
            }
            session_regenerate_id(true);
            $_SESSION['user'] = ['id' => $user['id'], 'email' => $user['email'], 'business_name' => $user['business_name'], 'role' => $user['role'], 'avatar' => $user['avatar']];
            $this->um->update((int)$user['id'], ['last_login' => date('Y-m-d H:i:s')]);
            $this->redirect('/dashboard');
        }

        $this->view('landing', ['flash' => $this->getFlash()]);
    }

    public function logout(): void
    {
        $this->security->destroySession();
    }
}
