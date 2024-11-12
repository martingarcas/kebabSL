<?php

namespace App\Controllers;

use App\Api\ApiUser;
use App\Models\Direccion;
use App\Models\Usuario;
use App\Repositorios\RepoUser;
use App\Repositorios\RepoDireccion;
use App\Utils\FlashMessage;
use App\Utils\Logger;
use League\Plates\Engine;
use App\Utils\Validator;

class RegisterController {

	protected $templates;

	public function __construct() {
		$this->templates = new Engine('../resources/views');
	}

	public function createUser() {
		// Crear una instancia de la clase Validator
		$validator = new Validator();

		// Array donde almacenar los datos del formulario
		$data = [
			'nombre'      => $_POST['nombre'] ?? '',
			'apellido1'   => $_POST['apellido1'] ?? null,
			'apellido2'   => $_POST['apellido2'] ?? null,
			'email'       => $_POST['email'] ?? '',
			'dni'         => $_POST['dni'] ?? '',
			'calle'       => $_POST['calle'] ?? '',
			'numero'      => $_POST['numero'] ?? '',
			'contrasenna' => $_POST['contrasenna'] ?? '',
			'telefono'    => $_POST['telefono'] ?? null,
			'foto'        => $_POST['foto'] ?? null,
			'monedero'    => $_POST['monedero'] ?? null,
			'carrito'     => $_POST['carrito'] ?? null,
		];

		// Definir las reglas de validación
		$camposRequeridos = [
			'nombre' 		=> 'Requerido',
			'contrasenna' 	=> 'Requerido',
			'dni' 			=> 'Requerido|Dni',
			'email' 		=> 'Requerido|Email',
			'calle' 		=> 'Requerido',
			'numero' 		=> 'Requerido'
		];

		// Validar los campos
		$errores = $validator->validarCampos($data, $camposRequeridos);

		// Instanciar los repositorios solo cuando se necesiten
		$repoUser 		= new RepoUser();
		$repoDireccion 	= new RepoDireccion();

		// Verificar si el email ya está registrado, solo si no hay errores previos en el campo
		 if (empty($errores['email'])) {
			 if ($validator->validarDuplicado('email', $data['email'], $repoUser)) {
				 $errores['email'] = 'El correo electrónico ya está registrado.';
			 }
		 }

		 // Verificar si el dni ya está registrado, solo si no hay errores previos en el campo
        if (empty($errores['dni'])) {
			if ($validator->validarDuplicado('dni', $data['dni'], $repoUser)) {
				$errores['dni'] = 'El DNI ya está registrado.';
			}
		}

		// Si hay errores, devolverlos
		if (count($errores) > 0) {

			echo $this->templates->render('register', [
				'errores' => $errores,
				'data' => $data // Pasar los datos para mantenerlos en los campos
			]);
			return; // Detener la ejecución
		}

		// Si no hay errores, continuar con el registro
		try {
			// Cifrar la contraseña antes de almacenarla
			$data['contrasenna'] = password_hash($data['contrasenna'], PASSWORD_BCRYPT);

			// Crear el objeto Usuario
			$usuario = new Usuario(
				$data['nombre'],         // nombre
				$data['apellido1'],      // apellido1 (opcional)
				$data['apellido2'],      // apellido2 (opcional)
				$data['contrasenna'],    // contrasenna (obligatorio)
				$data['telefono'],       // telefono (opcional)
				$data['email'],          // email (obligatorio)
				$data['dni'],            // dni (obligatorio)
				$data['foto'],           // foto (opcional)
				$data['monedero'],       // monedero (opcional)
				$data['carrito']         // carrito (opcional)
			);

			// Insertar el usuario en la base de datos
			$repoUser->create($usuario);
			$usuarioId = $usuario->getId();

			// Si no se obtuvo un ID de usuario, retornar un error
			if ($usuarioId === null) {
				throw new \Exception("No se pudo crear el usuario.");
			}

			// Crear el objeto Dirección
			$direccion = new Direccion($data['calle'], $data['numero'], 1);
			$repoDireccion->create($direccion, $usuarioId);

			// Usamos FlashMessage para el mensaje de éxito
			FlashMessage::setMessage("Usuario {$data['nombre']} con DNI {$data['dni']} ha sido creado con éxito.");

			// Redirigir al login
			header('Location: /login');
			exit();

		} catch (\Exception $e) {
			echo "Error: " . $e->getMessage();
		}
	}

	public function index($view) {
		// Verificar si el usuario está logueado
		var_dump(Logger::estaLogueado());
		if (Logger::estaLogueado()) {

			// Si está logueado, obtener el usuario desde la sesión
			$usuarioEmail = Logger::leerSesion('user');

			if ($usuarioEmail) {
				$repoUser = new RepoUser();
				$usuarioDetails = $repoUser->findByEmail($usuarioEmail);
				echo $this->templates->render($view, ['usuario' => $usuarioDetails]);
			}
		} else {
			// Si no está logueado, mostrar la vista sin los detalles del usuario
			echo $this->templates->render($view);
		}
//		echo $this->templates->render($view);
	}
}

?>
