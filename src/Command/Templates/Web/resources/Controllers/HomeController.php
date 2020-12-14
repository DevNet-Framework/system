<?php

namespace Application\Controllers;

use Artister\DevNet\Mvc\Controller;
use Artister\DevNet\Mvc\IActionResult;

class HomeController extends Controller
{
    public function index() : IActionResult
    {
        return $this->view('home/index');
    }
}