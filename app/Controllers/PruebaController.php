<?php
namespace App\Controllers;

use League\Plates\Engine;

class PruebaController {
    protected $templates;

    public function __construct() {
        $this->templates = new Engine('../resources/views');
    }

    public function index() {
        echo $this->templates->render('prueba');
    }
}