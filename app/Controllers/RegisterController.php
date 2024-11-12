<?php

namespace App\Controllers;

use App\Api\ApiUser;
use App\Models\Direccion;
use App\Models\Usuario;
use App\Repositorios\Conexion;
use App\Repositorios\RepoDireccion;
use App\Repositorios\RepoUser;
use App\Utils\Validator;
use League\Plates\Engine;
use App\Utils\Validacion;  // Incluir la clase Validacion

class RegisterController {

	protected $templates;

	public function __construct() {
		$this->templates = new Engine('../resources/views');
	}

	public function createUser() {
		// Crear una instancia de la clase Validacion
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

		// Realizar las validaciones
		$errores = [];

		// Validar cada campo con los métodos de la clase Validacion
		if (($mensaje = $validator->Requerido('nombre')) !== true) {
			$errores['nombre'] = $mensaje;
		}

		if (($mensaje = $validator->Requerido('contrasenna')) !== true) {
			$errores['contrasenna'] = $mensaje;
		}

		if (($mensaje = $validator->Requerido('dni')) !== true || ($mensaje = $validator->Dni('dni')) !== true) {
			$errores['dni'] = $mensaje;
		}

		if (($mensaje = $validator->Requerido('email')) !== true || ($mensaje = $validator->Email('email')) !== true) {
			$errores['email'] = $mensaje;
		}

		if (($mensaje = $validator->Requerido('calle')) !== true) {
			$errores['calle'] = $mensaje;
		}

		if (($mensaje = $validator->Requerido('numero')) !== true) {
			$errores['numero'] = $mensaje;
		}

		// Si hay errores, devolverlos
		if (count($errores) > 0) {
			echo $this->templates->render('register', [
				'errores' => $errores,
				'data' => $data // Pasar los datos para mantenerlos en los campos
			]);
			return; // Detener la ejecución
		}

		// Si no hay errores, continuar con el registro (como lo hacías antes)
		try {
			// Cifrar la contraseña antes de almacenarla
			$data['contrasenna'] = password_hash($data['contrasenna'], PASSWORD_BCRYPT);

			// Crear los repositorios para usuario y dirección
			$repoUser = new RepoUser();
			$repoDireccion = new RepoDireccion();

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

			if (!$repoUser->existeUsuario($data['email'], $data['dni'])) {
				// Insertar el usuario en la base de datos
				$repoUser->create($usuario);
				$usuarioId = $usuario->getId();

				// Si no se obtuvo un ID de usuario, lanzar una excepción
				if (!$usuarioId) {
					throw new \PDOException("No se pudo obtener el ID del usuario.");
				}

				// Crear el objeto Dirección y asociarlo al usuario
				$direccion = new Direccion($data['calle'], $data['numero'], 1);

				// Insertar la dirección en la base de datos
				$repoDireccion->create($direccion, $usuarioId);

				// Establecer un mensaje flash en la sesión
				session_start();
				$_SESSION['flash_message'] = "Usuario {$data['nombre']} con DNI {$data['dni']} ha sido creado con éxito.";

				// Redirigir al login
				header('Location: /login');
				exit();

			} else {
				echo "El usuario ya existe";
			}

		} catch (\Exception $e) {
			// Si ocurre un error, devolver un mensaje de error
			return ['status' => 'error', 'message' => $e->getMessage()];
		}
	}

	public function index($view) {
		echo $this->templates->render($view);
	}
}


?>