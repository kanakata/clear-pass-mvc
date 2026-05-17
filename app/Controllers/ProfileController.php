<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;

class ProfileController extends Controller
{
    public function show(): void
    {
        $this->requireAuth();
        $user = $this->auth();
        $flash = $this->getFlash();
        $full = (new User())->find((int)$user['id']);
        $this->view('shared.profile', compact('user', 'full', 'flash'));
    }
    public function update(): void
    {
        $this->requireAuth();
        $this->security->verifyCsrf();
        $user = $this->auth();
        $model = new User();
        $data = ['business_name' => $this->security->sanitizeString($this->input('business_name', '')), 'phone' => $this->security->sanitizeString($this->input('phone', ''), 20), 'location' => $this->security->sanitizeString($this->input('location', '')), 'bio' => $this->security->sanitizeString($this->input('bio', ''), 500), 'updated_at' => date('Y-m-d H:i:s')];
        if (!empty($_FILES['avatar']['name'])) {
            $v = $this->security->validateUpload($_FILES['avatar']);
            if ($v['valid']) {
                $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
                $fn = $this->security->safeFilename($_FILES['avatar']['name']) . '.' . $ext;
                move_uploaded_file($_FILES['avatar']['tmp_name'], ROOT . '/storage/uploads/' . $fn);
                $data['avatar'] = $fn;
            }
        }
        $model->updateProfile((int)$user['id'], $data);
        $_SESSION['user']['business_name'] = $data['business_name'];
        if (isset($data['avatar'])) $_SESSION['user']['avatar'] = $data['avatar'];
        $this->setFlash('success', 'Profile updated.');
        $this->redirect(BASE_URL . '/profile');
    }
}
