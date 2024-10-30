<?php

	namespace App\Controllers;

	use App\Repositorios\RepoKebab;
	use App\Repositorios\Conexion;
	use App\Models\Kebab;
	use App\Models\User;
	use League\Plates\Engine;

class HomeController {

		protected $templates;

		public function __construct() {
			$this->templates = new Engine('../resources/views'); // Ajusta la ruta a tus vistas
		}

		public function index($view) {
			// Crear una instancia del repositorio de kebabs
			$repoKebab = new RepoKebab(Conexion::getConection());

			// Obtener todos los kebabs
			$kebabs = $repoKebab->getAll();

			// Crear una instancia del modelo User
			$userModel = new User();

			// Obtener todos los usuarios
			$users = $userModel->getUsers();

			// Pasar los kebabs y los usuarios a la vista
			echo $this->templates->render($view, ['kebabs' => $kebabs, 'users' => $users]);
		}
	}

?>
