<?php

namespace App\Models;

use Core\Model;

class Order extends Model
{
    protected string $table = 'orders';
    public function createOrder(array $od, array $items): string
    {
        $this->db->beginTransaction();
        try {
            $oid = $this->create($od);
            foreach ($items as $it) {
                $this->db->insert("INSERT INTO order_items (order_id,product_id,quantity,unit_price,subtotal) VALUES (?,?,?,?,?)", [$oid, $it['product_id'], $it['quantity'], $it['unit_price'], $it['subtotal']]);
                $this->db->execute("UPDATE products SET stock_quantity=stock_quantity-? WHERE id=? AND stock_quantity>=?", [$it['quantity'], $it['product_id'], $it['quantity']]);
            }
            $this->db->commit();
            return $oid;
        } catch (\Throwable $e) {
            $this->db->rollback();
            throw $e;
        }
    }
    public function getOrdersForHotel(int $id): array
    {
        return $this->db->fetchAll("SELECT o.*,u.business_name AS farmer_name FROM orders o JOIN users u ON u.id=o.farmer_id WHERE o.hotel_id=? ORDER BY o.created_at DESC", [$id]);
    }
    public function getOrdersForFarmer(int $id): array
    {
        return $this->db->fetchAll("SELECT o.*,u.business_name AS hotel_name FROM orders o JOIN users u ON u.id=o.hotel_id WHERE o.farmer_id=? ORDER BY o.created_at DESC", [$id]);
    }
    public function getOrderWithItems(int $id): array|false
    {
        $o = $this->db->fetchOne("SELECT o.*,h.business_name AS hotel_name,h.email AS hotel_email,f.business_name AS farmer_name,f.email AS farmer_email FROM orders o JOIN users h ON h.id=o.hotel_id JOIN users f ON f.id=o.farmer_id WHERE o.id=?", [$id]);
        if (!$o) return false;
        $o['items'] = $this->db->fetchAll("SELECT oi.*,p.name AS product_name,p.unit FROM order_items oi JOIN products p ON p.id=oi.product_id WHERE oi.order_id=?", [$id]);
        return $o;
    }
    public function updateStatus(int $id, string $s): int
    {
        return $this->db->execute("UPDATE orders SET status=?,updated_at=NOW() WHERE id=?", [$s, $id]);
    }
    public function getStats(int $uid, string $role): array
    {
        $field = $role === 'hotel' ? 'hotel_id' : 'farmer_id';
        $r = $this->db->fetchOne("SELECT COUNT(*) AS total_orders,SUM(CASE WHEN status='completed' THEN total_amount ELSE 0 END) AS total_revenue,SUM(CASE WHEN status='pending' THEN 1 ELSE 0 END) AS pending_orders,SUM(CASE WHEN status='completed' THEN 1 ELSE 0 END) AS completed_orders FROM orders WHERE $field=?", [$uid]);
        return $r ?: [];
    }
}
