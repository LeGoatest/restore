<?php

namespace App\Controllers;

use App\Models\Document;

class DocumentController extends BaseController
{
    public function view($slug)
    {
        $document = new Document();
        $this->render('document', ['document' => $document->findBySlug($slug)]);
    }

    public function editForm($id)
    {
        $document = new Document();
        $this->render('edit-form', ['document' => $document->find($id)]);
    }
}
