<?php

	namespace App\Controllers;

	use App\Repositorios\RepoKebab;
	use App\Repositorios\Conexion;
	use App\Models\Kebab;
	use App\Models\User;

class HomeController {

		public function index() {
			// Crear una instancia del repositorio de kebabs
			$repoKebab = new RepoKebab(Conexion::getConection());

			// Crear un nuevo objeto Kebab
//			$nuevoKebab = new Kebab(2, 'Tenera', 'Lechuga, tomate, queso', 'Yogur');
//			$repoKebab->create($nuevoKebab);  // Pasar el objeto Kebab directamente

			// Obtener todos los kebabs
			$kebabs = $repoKebab->getAll();

			// Crear una instancia del modelo User
			$userModel = new User();

			// Obtener todos los usuarios
			$users = $userModel->getUsers();

			// Pasar los kebabs y los usuarios a la vista
			echo $this->render('home', ['kebabs' => $kebabs, 'users' => $users]);
		}

		private function render($view, $data) {
			// Lógica para renderizar la vista
			$template = new \League\Plates\Engine('../resources/views'); // Ajusta la ruta a tus vistas
			echo $template->render($view, $data);
		}
	}

?>
