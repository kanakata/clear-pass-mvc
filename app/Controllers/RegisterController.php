<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;

class RegisterController extends Controller
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

            if (!$this->security->checkRateLimit('register', $this->security->getIp())) {

                $this->setFlash('error', 'Registration limit reached.');
                $this->view('auth.register', ['flash' => $this->getFlash()]);
                return;
            }

            $role = $this->input('role', '');
            $bname = $this->security->sanitizeString($this->input('business_name', ''));
            $email = $this->security->sanitizeEmail($this->input('email', ''));
            $pass = $this->input('password', '');
            $conf = $this->input('password_confirm', '');
            $phone = $this->security->sanitizeString($this->input('phone', ''), 20);
            $loc = $this->security->sanitizeString($this->input('location', ''));
            $errors = [];

            if (!in_array($role, ['hotel', 'farmer'], true)) $errors[] = 'Invalid role.';

            if (strlen($bname) < 3) $errors[] = 'Business name too short.';

            if (!$email) $errors[] = 'Valid email required.';

            if ($pass !== $conf) $errors[] = 'Passwords do not match.';

            $errors = array_merge($errors, $this->security->validatePasswordStrength($pass));

            if ($this->um->findByEmail($email)) $errors[] = 'Email already registered.';

            if ($errors) {

                $this->setFlash('error', implode(' | ', $errors));
                $this->view('auth.register', ['flash' => $this->getFlash(), 'old' => $_POST]);
                return;
            }

            $id = $this->um->createUser([
                'email' => $email,
                'password' => $this->security->hashPassword($pass),
                'business_name' => $bname,
                'role' => $role,
                'phone' => $phone,
                'location' => $loc,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            session_regenerate_id(true);

            $u = $this->um->find((int)$id);

            $_SESSION['user'] = [
                'id' => $u['id'],
                'email' => $u['email'],
                'business_name' => $u['business_name'],
                'role' => $u['role'],
                'avatar' => $u['avatar'] ?? null
            ];

            $this->redirect('/dashboard');
        }
        $this->view('register', ['flash' => $this->getFlash()]);
    }
    public function logout(): void
    {
        $this->security->destroySession();
    }
}
