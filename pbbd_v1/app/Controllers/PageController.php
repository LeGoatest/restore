<?php

namespace App\Controllers;

class PageController extends BaseController
{
    public function dashboard()
    {
        $this->render('dashboard');
    }

    public function editor()
    {
        $this->render('editor');
    }
}
