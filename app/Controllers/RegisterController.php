<?php

	namespace App\Controllers;

	use App\Repositorios\Conexion;
	use App\Repositorios\RepoDireccion;
	use App\Repositorios\RepoUser;
	use League\Plates\Engine;

	class RegisterController {

		protected $templates;

		public function __construct() {
			$this->templates = new Engine('../resources/views');
		}

		public function createUser() {


		}

		public function index($view) {
			echo $this->templates->render($view);
		}
	}

?>