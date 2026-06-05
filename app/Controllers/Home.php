<?php
declare(strict_types=1);

namespace App\Controllers;
use App\Core\Controller\Legacy\ControllerTemplateLegacy;

class Home extends ControllerTemplateLegacy
{
    public function index(): string
    {
        return view('login');
    }
}