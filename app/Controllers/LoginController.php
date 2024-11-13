<?php

namespace App\Controllers;

use App\Repositorios\RepoUser;
use App\Utils\FlashMessage;
use App\Utils\Validator;
use App\Utils\Logger; // Agregar la clase Logger
use League\Plates\Engine;

class LoginController {
	protected $templates;

	public function __construct() {
		$this->templates = new Engine('../resources/views');
	}

	public function loginUser() {
		// Si el usuario ya está logueado, redirigirlo al dashboard o inicio
		if (Logger::estaLogueado()) {
			header('Location: /');  // Redirige al dashboard o página principal
			exit();
		}

		// Crear una instancia de la clase Validator
		$validator = new Validator();

		// Array donde almacenar los datos del formulario
		$data = [
			'email'       => $_POST['email'] ?? '',
			'contrasenna' => $_POST['contrasenna'] ?? '',
		];

		// Definir las reglas de validación
		$camposRequeridos = [
			'email'       => 'Requerido',
			'contrasenna' => 'Requerido',
		];

		// Validar los campos requeridos
		$errores = $validator->validarCampos($data, $camposRequeridos);

		// Instanciar los repositorios solo cuando se necesiten
		$repoUser = new RepoUser();

		// Si no hay errores en el email y en la contraseña, comprobar la validez de los datos
		if (empty($errores['email']) && empty($errores['contrasenna'])) {

			// Verificar si el email está registrado
			if (!$validator->validarDuplicado('email', $data['email'], $repoUser)) {
				// Si el email no está registrado, redirigir con mensaje flash
				FlashMessage::setMessage('Los datos introducidos no son válidos.', 'error');
				header('Location: /login');
				exit();
			}

			// Buscar el usuario por su email
			$usuario = $repoUser->findByEmail($data['email']);  // Método que debe devolver el usuario según su email

			// Si la contraseña no coincide
			if (!password_verify($data['contrasenna'], $usuario->getContrasenna())) {
				// Redirigir con mensaje flash si la contraseña no coincide
				FlashMessage::setMessage('Los datos introducidos no son válidos.', 'error');
				header('Location: /login');
				exit();
			}

			// Si la autenticación es correcta, iniciar sesión
			Logger::login($usuario);  // Usar el método login para almacenar al usuario en la sesión
		}

		// Si hay errores, devolverlos al formulario
		if (count($errores) > 0) {
			echo $this->templates->render('login', [
				'errores' => $errores,
				'data' => $data // Pasar los datos para mantenerlos en los campos
			]);
			return; // Detener la ejecución
		}

		// Redirigir al usuario a su página de inicio o dashboard
		header('Location: /');
		exit();
	}

	public function index() {
		// Obtener el mensaje flash
		$message = FlashMessage::getMessage();

		// Renderizar la vista con el mensaje flash si existe
		echo $this->templates->render('login', ['message' => $message]);
	}
}

?>
