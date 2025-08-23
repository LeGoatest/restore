<?php

namespace App\Controllers;

class SystemBuilderController extends BaseController
{
    public function index()
    {
        $this->render('system-builder');
    }

    public function createBlockForm()
    {
        $this->render('create-block-form');
    }

    public function createBlueprintForm()
    {
        $this->render('create-blueprint-form');
    }
}
