<?php

namespace App\Models;

use Core\Model;

class Message extends Model
{
    protected string $table = 'messages';
    public function getInbox(int $uid): array
    {
        return $this->db->fetchAll(
            "SELECT m.*,s.business_name AS sender_name,s.avatar AS sender_avatar,s.role AS sender_role FROM messages m JOIN users s ON s.id=m.sender_id WHERE m.recipient_id=? ORDER BY m.created_at DESC",
            [$uid]
        );
    }
    public function unreadCount(int $uid): int
    {
        $r = $this->db->fetchOne("SELECT COUNT(*) AS cnt FROM messages WHERE recipient_id=? AND is_read=0", [$uid]);
        return (int)($r['cnt'] ?? 0);
    }
}
