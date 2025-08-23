<?php

namespace App\Models;

class User extends BaseModel
{
    public function attempt($username, $password)
    {
        $stmt = $this->db->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            return true;
        }

        return false;
    }
}
