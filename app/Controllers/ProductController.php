<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    private Product $model;
    public function __construct()
    {
        parent::__construct();
        $this->model = new Product();
    }
    public function index(): void
    {
        $this->requireRole('farmer', 'admin');
        $user = $this->auth();
        $products = $this->model->getFarmerProducts((int)$user['id']);
        $flash = $this->getFlash();
        $this->view('farmer.products', compact('user', 'products', 'flash'));
    }
    public function create(): void
    {
        $this->requireRole('farmer', 'admin');
        $user = $this->auth();
        $flash = $this->getFlash();
        $this->view('farmer.product_form', compact('user', 'flash'));
    }
    public function store(): void
    {
        $this->requireRole('farmer', 'admin');
        $this->security->verifyCsrf();
        $user = $this->auth();
        $errors = [];
        $name = $this->security->sanitizeString($this->input('name', ''));
        $desc = $this->security->sanitizeString($this->input('description', ''), 1000);
        $cat = $this->security->sanitizeString($this->input('category', ''));
        $price = $this->security->sanitizeFloat($this->input('price_per_unit', 0));
        $stock = $this->security->sanitizeInt($this->input('stock_quantity', 0));
        $unit = $this->security->sanitizeString($this->input('unit', ''));
        $min = $this->security->sanitizeInt($this->input('min_order', 1));
        if (strlen($name) < 2) $errors[] = 'Product name required.';
        if (!$price || $price <= 0) $errors[] = 'Valid price required.';
        if ($stock === false || $stock < 0) $errors[] = 'Valid stock required.';
        $img = null;
        if (!empty($_FILES['image']['name'])) {
            $v = $this->security->validateUpload($_FILES['image']);
            if (!$v['valid']) $errors = array_merge($errors, $v['errors']);
            else {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $fn = $this->security->safeFilename($_FILES['image']['name']) . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], ROOT . '/storage/uploads/' . $fn);
                $img = $fn;
            }
        }
        if ($errors) {
            $this->setFlash('error', implode(' | ', $errors));
            $this->redirect(BASE_URL . '/farmer/products/create');
        }
        $this->model->create(['farmer_id' => $user['id'], 'name' => $name, 'description' => $desc, 'category' => $cat, 'price_per_unit' => $price, 'stock_quantity' => $stock, 'unit' => $unit, 'min_order' => $min ?: 1, 'image' => $img, 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')]);
        $this->model->invalidateFarmerCache((int)$user['id']);
        $this->setFlash('success', 'Product listed!');
        $this->redirect(BASE_URL . '/farmer/products');
    }
    public function edit(string $id): void
    {
        $this->requireRole('farmer', 'admin');
        $user = $this->auth();
        $product = $this->model->find((int)$id);
        if (!$product || (int)$product['farmer_id'] !== (int)$user['id']) {
            http_response_code(403);
            $this->view('shared.403');
            return;
        }
        $flash = $this->getFlash();
        $this->view('farmer.product_form', compact('user', 'product', 'flash'));
    }
    public function update(string $id): void
    {
        $this->requireRole('farmer', 'admin');
        $this->security->verifyCsrf();
        $user = $this->auth();
        $product = $this->model->find((int)$id);
        if (!$product || (int)$product['farmer_id'] !== (int)$user['id']) {
            http_response_code(403);
            return;
        }
        $data = ['name' => $this->security->sanitizeString($this->input('name', '')), 'description' => $this->security->sanitizeString($this->input('description', ''), 1000), 'category' => $this->security->sanitizeString($this->input('category', '')), 'price_per_unit' => (float)$this->input('price_per_unit', 0), 'stock_quantity' => (int)$this->input('stock_quantity', 0), 'unit' => $this->security->sanitizeString($this->input('unit', '')), 'min_order' => (int)$this->input('min_order', 1), 'updated_at' => date('Y-m-d H:i:s')];
        $this->model->update((int)$id, $data);
        $this->model->invalidateFarmerCache((int)$user['id']);
        $this->setFlash('success', 'Product updated.');
        $this->redirect(BASE_URL . '/farmer/products');
    }
    public function delete(string $id): void
    {
        $this->requireRole('farmer', 'admin');
        $this->security->verifyCsrf();
        $user = $this->auth();
        $product = $this->model->find((int)$id);
        if ($product && (int)$product['farmer_id'] === (int)$user['id']) {
            $this->model->delete((int)$id);
            $this->model->invalidateFarmerCache((int)$user['id']);
        }
        $this->setFlash('success', 'Product removed.');
        $this->redirect(BASE_URL . '/farmer/products');
    }
}
