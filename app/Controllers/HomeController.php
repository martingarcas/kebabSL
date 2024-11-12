<?php

	namespace App\Controllers;

	use App\Models\User;
	use App\Repositorios\RepoUser;
	use League\Plates\Engine;

class HomeController {

		protected $templates;

		public function __construct() {
			$this->templates = new Engine('../resources/views'); // Ajusta la ruta a tus vistas
		}

		public function index($view) {

			$repoUser = new RepoUser();
			$usuario = $repoUser->getById(1);

			// Pasar los kebabs y los usuarios a la vista
			echo $this->templates->render($view, ['usuario' => $usuario]);
		}


		public function editKebap () {

			// Entra petición, la recibe tu router -> llama al controlador correspondiente ->
			// realiza la acción, persiste si hace falta datos y devuelve los datos o una confirmación de la acción. lo que haga flta.


		}
	}

?>
