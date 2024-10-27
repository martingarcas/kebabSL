<?php
namespace App\Controllers;

use App\Models\User;
use League\Plates\Engine;

class HomeController {

    protected $templates;

    public function __construct() {

        $this->templates = new Engine('../resources/views');
    }

    public function index() {

        $userModel = new User();
        $users = $userModel->getUsers();
        echo $this->templates->render('home', ['users' => $users]);
    }
}
