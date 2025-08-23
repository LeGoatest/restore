<?php

namespace App\Models;

class Document extends BaseModel
{
    public function findBySlug($slug)
    {
        $stmt = $this->db->prepare("SELECT * FROM documents WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
