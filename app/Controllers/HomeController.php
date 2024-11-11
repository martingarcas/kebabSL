<?php

	namespace App\Controllers;

	use App\Models\User;
	use League\Plates\Engine;

class HomeController {

		protected $templates;

		public function __construct() {
			$this->templates = new Engine('../resources/views'); // Ajusta la ruta a tus vistas
		}

		public function index($view) {

			// Verificar si el parámetro 'success' está presente en la URL
			$successMessage = null;
			if (isset($_GET['success']) && $_GET['success'] == 'true') {
				$successMessage = '¡Usuario registrado con éxito! Ahora puedes iniciar sesión.';
			}

			// Crear una instancia del modelo User
			$userModel = new User();

			// Obtener todos los usuarios
			$users = $userModel->getUsers();

			// Pasar los kebabs y los usuarios a la vista
			echo $this->templates->render($view, ['successMessage' => $successMessage, 'users' => $users]);
		}


		public function editKebap () {

			// Entra petición, la recibe tu router -> llama al controlador correspondiente ->
			// realiza la acción, persiste si hace falta datos y devuelve los datos o una confirmación de la acción. lo que haga flta.


		}
	}

?>
