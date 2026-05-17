<?php

namespace App\Models;

use Core\Model;

class Product extends Model
{
    protected string $table = 'products';
    public function getMarketplaceListings(array $f = [], int $page = 1, int $pp = 12): array
    {
        $page = max(1, $page);
        $off = ($page - 1) * $pp;
        $where = ['p.is_active=1'];
        $params = [];
        if (!empty($f['category'])) {
            $where[] = 'p.category=?';
            $params[] = $f['category'];
        }
        if (!empty($f['search'])) {
            $where[] = '(p.name LIKE ? OR p.description LIKE ?)';
            $params[] = '%' . $f['search'] . '%';
            $params[] = '%' . $f['search'] . '%';
        }
        if (!empty($f['min_price'])) {
            $where[] = 'p.price_per_unit>=?';
            $params[] = (float)$f['min_price'];
        }
        if (!empty($f['max_price'])) {
            $where[] = 'p.price_per_unit<=?';
            $params[] = (float)$f['max_price'];
        }
        $ws = 'WHERE ' . implode(' AND ', $where);
        $ck = 'marketplace:' . md5(json_encode($f) . $page);
        return $this->cache->remember($ck, function () use ($ws, $params, $pp, $off) {
            return $this->db->fetchAll("SELECT p.*,u.business_name AS farmer_name,u.location AS farmer_location,u.avatar AS farmer_avatar FROM products p JOIN users u ON u.id=p.farmer_id $ws ORDER BY p.created_at DESC LIMIT $pp OFFSET $off", $params);
        }, 60);
    }
    public function countMarketplace(array $f = []): int
    {
        $where = ['p.is_active=1'];
        $params = [];
        if (!empty($f['category'])) {
            $where[] = 'p.category=?';
            $params[] = $f['category'];
        }
        if (!empty($f['search'])) {
            $where[] = '(p.name LIKE ? OR p.description LIKE ?)';
            $params[] = '%' . $f['search'] . '%';
            $params[] = '%' . $f['search'] . '%';
        }
        $ws = 'WHERE ' . implode(' AND ', $where);
        $r = $this->db->fetchOne("SELECT COUNT(*) as cnt FROM products p JOIN users u ON u.id=p.farmer_id $ws", $params);
        return (int)($r['cnt'] ?? 0);
    }
    public function getFarmerProducts(int $fid): array
    {
        return $this->cache->remember("farmer_products:$fid", function () use ($fid) {
            return $this->db->fetchAll("SELECT * FROM products WHERE farmer_id=? ORDER BY created_at DESC", [$fid]);
        }, 90);
    }
    public function invalidateFarmerCache(int $fid): void
    {
        $this->cache->delete("farmer_products:$fid");
        foreach (glob(ROOT . '/storage/cache/*.cache') as $cf) {
            if (@file_get_contents($cf) && str_contains(@file_get_contents($cf), 'marketplace')) @unlink($cf);
        }
    }
}
