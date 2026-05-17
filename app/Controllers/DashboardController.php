<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\{Order, Message, Product};

class DashboardController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $user = $this->auth();
        $om = new Order();
        $mm = new Message();
        $stats = $om->getStats((int)$user['id'], $user['role']);
        $unread = $mm->unreadCount((int)$user['id']);
        if ($user['role'] === 'farmer') {
            $pm = new Product();
            $rp = $pm->getFarmerProducts((int)$user['id']);
            $ro = $om->getOrdersForFarmer((int)$user['id']);
            $this->view('farmer.dashboard', compact('user', 'stats', 'unread', 'rp', 'ro'));
        } else {
            $ro = $om->getOrdersForHotel((int)$user['id']);

            $this->view('dashboard', compact('user', 'stats', 'unread', 'ro'));
        }
    }
}
