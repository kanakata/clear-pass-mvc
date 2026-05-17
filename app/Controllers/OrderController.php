<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\{Order, Product};

class OrderController extends Controller
{
    private Order $model;
    public function __construct()
    {
        parent::__construct();
        $this->model = new Order();
    }
    public function index(): void
    {
        $this->requireAuth();
        $user = $this->auth();
        $orders = $user['role'] === 'hotel' ? $this->model->getOrdersForHotel((int)$user['id']) : $this->model->getOrdersForFarmer((int)$user['id']);
        $flash = $this->getFlash();
        $this->view('shared.orders', compact('user', 'orders', 'flash'));
    }
    public function show(string $id): void
    {
        $this->requireAuth();
        $user = $this->auth();
        $order = $this->model->getOrderWithItems((int)$id);
        if (!$order) {
            http_response_code(404);
            $this->view('shared.404');
            return;
        }
        if ((int)$order['hotel_id'] !== (int)$user['id'] && (int)$order['farmer_id'] !== (int)$user['id']) {
            http_response_code(403);
            $this->view('shared.403');
            return;
        }
        $this->view('shared.order_detail', compact('user', 'order'));
    }
    public function place(): void
    {
        $this->requireRole('hotel');
        $this->security->verifyCsrf();
        $user = $this->auth();
        $pid = (int)$this->input('product_id', 0);
        $qty = (int)$this->input('quantity', 0);
        $pm = new Product();
        $product = $pm->find($pid);
        if (!$product || $qty < ($product['min_order'] ?? 1)) {
            $this->setFlash('error', 'Invalid order.');
            $this->redirect(BASE_URL . '/marketplace');
        }
        if ($product['stock_quantity'] < $qty) {
            $this->setFlash('error', 'Insufficient stock.');
            $this->redirect(BASE_URL . '/marketplace/' . $pid);
        }
        $sub = $qty * $product['price_per_unit'];
        $notes = $this->security->sanitizeString($this->input('notes', ''), 500);
        $oid = $this->model->createOrder(['hotel_id' => $user['id'], 'farmer_id' => $product['farmer_id'], 'total_amount' => $sub, 'status' => 'pending', 'notes' => $notes, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')], [['product_id' => $pid, 'quantity' => $qty, 'unit_price' => $product['price_per_unit'], 'subtotal' => $sub]]);
        $pm->invalidateFarmerCache((int)$product['farmer_id']);
        $this->setFlash('success', 'Order placed!');
        $this->redirect(BASE_URL . '/orders/' . $oid);
    }
    public function updateStatus(string $id): void
    {
        $this->requireRole('farmer', 'admin');
        $this->security->verifyCsrf();
        $user = $this->auth();
        $order = $this->model->find((int)$id);
        $status = $this->security->sanitizeString($this->input('status', ''));
        $allowed = ['confirmed', 'processing', 'shipped', 'completed', 'cancelled'];
        if ($order && (int)$order['farmer_id'] === (int)$user['id'] && in_array($status, $allowed, true)) {
            $this->model->updateStatus((int)$id, $status);
            $this->setFlash('success', 'Status updated.');
        }
        $this->redirect(BASE_URL . '/orders');
    }
}
