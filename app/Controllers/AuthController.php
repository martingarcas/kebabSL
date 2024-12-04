<?php

namespace App\Controllers;

use App\Models\Direccion;
use App\Models\Usuario;
use App\Repositorios\RepoUser;
use App\Repositorios\RepoDireccion;
use App\Utils\FlashMessage;
use App\Utils\Validator;
use App\Utils\Logger;
use League\Plates\Engine;

class AuthController {
	protected $templates;

	public function __construct() {
		$this->templates = new Engine('../resources/views');
	}

	// Renderizar vista de login
	public function showLoginForm($view) {
		// Obtener el mensaje flash
		$message = FlashMessage::getMessage();

		$usuario = Logger::obtenerUsuario();

		if ($usuario) {
			http_response_code(403); // Código HTTP 403: Prohibido
			FlashMessage::setMessage("ACCESO DENEGADO.", 'error');
			header('Location: /');
		}
		// Renderizar la vista con el mensaje flash si existe
		echo $this->templates->render($view, ['message' => $message]);
	}

	// Renderizar vista de registro
	public function showRegisterForm($view) {
		$message = FlashMessage::getMessage();

		$usuario = Logger::obtenerUsuario();

		if ($usuario) {
			http_response_code(403); // Código HTTP 403: Prohibido
			FlashMessage::setMessage("ACCESO DENEGADO.", 'error');
			header('Location: /');
		}

		echo $this->templates->render($view, ['message' => $message]);
	}

	// Iniciar sesión del usuario
	public function loginUser() {
		if (Logger::estaLogueado()) {
			header('Location: /');
			exit();
		}

		$validator = new Validator();

		$data = [
			'email'       => $_POST['email'] ?? '',
			'contrasenna' => $_POST['contrasenna'] ?? '',
		];

		$camposRequeridos = [
			'email'       => 'Requerido',
			'contrasenna' => 'Requerido',
		];

		$errores = $validator->validarCampos($data, $camposRequeridos);
		$repoUser = new RepoUser();

		if (empty($errores['email']) && empty($errores['contrasenna'])) {
			if (!$validator->validarDuplicado('email', $data['email'], $repoUser)) {
				FlashMessage::setMessage('Los datos introducidos no son válidos.', 'error');
				header('Location: /login');
				exit();
			}

			$usuario = $repoUser->findByEmail($data['email']);
			if (!password_verify($data['contrasenna'], $usuario->getContrasenna())) {
				FlashMessage::setMessage('Los datos introducidos no son válidos.', 'error');
				header('Location: /login');
				exit();
			}

			// Verifica si "Recuérdame" está marcado
			if (isset($_POST['recuerdame'])) {
				setcookie('remember_email', $data['email'], time() + 3600 * 24 * 30, '/'); // Válido por 30 días
				setcookie('remember_password', $data['contrasenna'], time() + 3600 * 24 * 30, '/');
			}

			Logger::login($usuario);
			FlashMessage::setMessage("Inicio de sesión exitoso. ¡Bienvenido!", 'success');
			header('Location: /');
			exit();
		}

		if (count($errores) > 0) {
			echo $this->templates->render('login', [
				'errores' => $errores,
				'data' => $data
			]);
			return;
		}
	}

	// Registrar usuario
//	public function registerUser() {
//		$validator = new Validator();
//		$data = [
//			'nombre'      => $_POST['nombre'] ?? '',
//			'apellido1'   => $_POST['apellido1'] ?? null,
//			'apellido2'   => $_POST['apellido2'] ?? null,
//			'email'       => $_POST['email'] ?? '',
//			'dni'         => $_POST['dni'] ?? '',
//			'localidad'   => $_POST['localidad'] ?? '',
//			'calle'       => $_POST['calle'] ?? '',
//			'numero'      => $_POST['numero'] ?? '',
//			'contrasenna' => $_POST['contrasenna'] ?? '',
//			'telefono'    => $_POST['telefono'] ?? null,
//			'foto'        => $_POST['foto'] ?? null,
//			'monedero'    => $_POST['monedero'] ?? null,
//			'carrito'     => $_POST['carrito'] ?? null,
//			'rol'         => 'administrador',
//		];
//
//		$camposRequeridos = [
//			'nombre'      => 'Requerido',
//			'contrasenna' => 'Requerido',
//			'dni'         => 'Requerido|Dni',
//			'email'       => 'Requerido|Email',
//			'localidad'   => 'Requerido',
//			'calle'       => 'Requerido',
//			'numero'      => 'Requerido'
//		];
//
//		$errores = $validator->validarCampos($data, $camposRequeridos);
//		$repoUser = new RepoUser();
//
//		if (empty($errores['email']) && $validator->validarDuplicado('email', $data['email'], $repoUser)) {
//			$errores['email'] = 'El correo electrónico ya está registrado.';
//		}
//
//		if (empty($errores['dni']) && $validator->validarDuplicado('dni', $data['dni'], $repoUser)) {
//			$errores['dni'] = 'El DNI ya está registrado.';
//		}
//
//		if (count($errores) > 0) {
//			echo $this->templates->render('register', [
//				'errores' => $errores,
//				'data' => $data
//			]);
//			return;
//		}
//
//		try {
//			$data['contrasenna'] = password_hash($data['contrasenna'], PASSWORD_BCRYPT);
//			$usuario = new Usuario(
//				$data['nombre'], $data['apellido1'], $data['apellido2'],
//				$data['contrasenna'], $data['telefono'], $data['email'],
//				$data['dni'], $data['foto'], $data['monedero'], $data['carrito'],
//				$data['rol']
//			);
//
//			$repoUser->create($usuario);
//			$usuarioId = $usuario->getId();
//
//			if ($usuarioId === null) {
//				throw new \Exception("No se pudo crear el usuario.");
//			}
//
//			$direccion = new Direccion($data['localidad'], $data['calle'], $data['numero'], 1);
//			$repoDireccion = new RepoDireccion();
//			$repoDireccion->create($direccion, $usuarioId);
//
//			// Mensaje de éxito personalizado
//			FlashMessage::setMessage("El usuario {$data['email']} ha sido registrado con éxito.", 'success');
//			header('Location: /login');
//			exit();
//
//		} catch (\Exception $e) {
//			echo "Error: " . $e->getMessage();
//		}
//	}

	// Cerrar sesión del usuario
	public function logoutUser() {
		// Cerrar la sesión del usuario
		Logger::logout();

		// Establecer el mensaje flash de éxito
		FlashMessage::setMessage("Sesión cerrada con éxito.", 'success');

		// Redirigir a la página principal
		header('Location: /');
		exit();
	}

	// Renderizar vista de ingredientes
	public function showIngredientes($view) {
		// Obtener el mensaje flash
		$message = FlashMessage::getMessage();
		// Verificar si el usuario está logueado y es administrador
		$usuario = Logger::obtenerUsuario();

		if (!$usuario || $usuario->getRol() !== 'administrador') {
			http_response_code(403); // Código HTTP 403: Prohibido
			FlashMessage::setMessage("ACCESO DENEGADO.", 'error');
			header('Location: /');
		}
		// Renderizar la vista con el mensaje flash si existe
		echo $this->templates->render($view, ['message' => $message]);
	}

	// Renderizar vista de kebabs
	public function showKebabs($view) {
		// Obtener el mensaje flash
		$message = FlashMessage::getMessage();
		// Verificar si el usuario está logueado y es administrador
		$usuario = Logger::obtenerUsuario();

		if (!$usuario || $usuario->getRol() !== 'administrador') {
			http_response_code(403); // Código HTTP 403: Prohibido
			FlashMessage::setMessage("ACCESO DENEGADO.", 'error');
			header('Location: /');
		}
		// Renderizar la vista con el mensaje flash si existe
		echo $this->templates->render($view, ['message' => $message]);
	}

	// Renderizar vista de login
	public function showProfile($view) {
		// Obtener el mensaje flash
		$message = FlashMessage::getMessage();
		$usuario = Logger::obtenerUsuario();
		// Renderizar la vista con el mensaje flash si existe
		echo $this->templates->render($view, ['message' => $message]);
	}

	// Renderizar vista de kebabs
	public function showMenuKebabs($view) {
		// Renderizar la vista con el mensaje flash si existe
		echo $this->templates->render($view);
	}

}
?>