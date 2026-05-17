<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\{Message, User};

class MessageController extends Controller
{
    private Message $model;
    public function __construct()
    {
        parent::__construct();
        $this->model = new Message();
    }
    public function inbox(): void
    {
        $this->requireAuth();
        $user = $this->auth();
        $messages = $this->model->getInbox((int)$user['id']);
        $flash = $this->getFlash();
        $this->view('messages', compact('user', 'messages', 'flash'));
    }
    public function send(): void
    {
        $this->requireAuth();
        $this->security->verifyCsrf();
        $user = $this->auth();
        $rid = (int)$this->input('recipient_id', 0);
        $sub = $this->security->sanitizeString($this->input('subject', ''));
        $body = $this->security->sanitizeString($this->input('body', ''), 2000);
        if (!$rid || strlen($body) < 5) {
            $this->setFlash('error', 'Message body required.');
            $this->redirect('/messages');
        }
        $rec = (new User())->find($rid);
        if (!$rec) {
            $this->setFlash('error', 'Recipient not found.');
            $this->redirect('/messages');
        }
        $this->model->create(['sender_id' => $user['id'], 'recipient_id' => $rid, 'subject' => $sub ?: 'No subject', 'body' => $body, 'is_read' => 0, 'created_at' => date('Y-m-d H:i:s')]);
        $this->setFlash('success', 'Message sent!');
        $this->redirect('/messages');
    }
}
