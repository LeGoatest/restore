<?php

namespace App\Models;

class Block extends BaseModel
{
    public function findByHandle($handle)
    {
        $stmt = $this->db->prepare("SELECT * FROM blocks WHERE handle = ?");
        $stmt->execute([$handle]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
