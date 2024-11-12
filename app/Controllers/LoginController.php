<?php

namespace App\Controllers;

use League\Plates\Engine;

class LoginController {
	protected $templates;

	public function __construct() {
		$this->templates = new Engine('../resources/views');
	}

	public function index() {
		session_start();
		$message = $_SESSION['flash_message'] ?? null;
		unset($_SESSION['flash_message']);
		echo $this->templates->render('login', ['message' => $message]);
	}
}
