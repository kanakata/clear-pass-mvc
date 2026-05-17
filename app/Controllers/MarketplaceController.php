<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\{Product, User};

class MarketplaceController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $pm = new Product();
        $page = max(1, (int)$this->input('page', 1));
        $filters = ['category' => $this->security->sanitizeString($this->input('category', '')), 'search' => $this->security->sanitizeString($this->input('search', '')), 'min_price' => $this->security->sanitizeFloat($this->input('min_price', '')), 'max_price' => $this->security->sanitizeFloat($this->input('max_price', ''))];
        $pp = 12;
        $products = $pm->getMarketplaceListings($filters, $page, $pp);
        $total = $pm->countMarketplace($filters);
        $pages = (int)ceil($total / $pp);
        $user = $this->auth();
        $flash = $this->getFlash();
        $categories = $this->cache->remember('product_categories', fn() => $pm->db->fetchAll("SELECT DISTINCT category FROM products WHERE is_active=1 ORDER BY category"), 300);
        $this->view('index', compact('products', 'total', 'page', 'pages', 'filters', 'categories', 'user', 'flash'));
    }
    public function show(string $id): void
    {
        $this->requireAuth();
        $pm = new Product();
        $product = $pm->find((int)$id);
        if (!$product) {
            http_response_code(404);
            $this->view('404');
            return;
        }
        $farmer = (new User())->find((int)$product['farmer_id']);
        $user = $this->auth();
        $this->view('show', compact('product', 'farmer', 'user'));
    }
}
