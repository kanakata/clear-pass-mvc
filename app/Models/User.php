<?php

namespace App\Models;

use Core\Model;

class User extends Model
{
    protected string $table = 'users';
    public function findByEmail(string $email): array|false
    {
        return $this->cache->remember("user_email:$email", function () use ($email) {
            return $this->db->fetchOne("SELECT * FROM users WHERE email=? AND is_active=1", [$email]);
        }, 60);
    }
    public function createUser(array $data): string
    {
        $id = $this->create($data);
        $this->cache->delete("user_email:{$data['email']}");
        return $id;
    }
    public function updateProfile(int $id, array $data): int
    {
        $rows = $this->update($id, $data);
        $u = $this->find($id);
        if ($u) $this->cache->delete("user_email:{$u['email']}");
        return $rows;
    }
}
