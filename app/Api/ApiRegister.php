<?php

namespace App\Api;

use App\Models\Direccion;
use App\Models\Usuario;
use App\Repositorios\RepoDireccion;
use App\Repositorios\RepoUser;
use App\Utils\Validator;

class ApiRegister {

	public function handleRequest($data) {
		header('Content-Type: application/json');

		// Decidir qué método invocar según el valor de 'action'
		if (isset($data['action'])) {
			switch ($data['action']) {
				case 'validate':
					return $this->procesarValidacion($data);
				case 'register':
					return $this->registrarUsuario($data);
				default:
					http_response_code(400);
					return json_encode(['error' => 'Acción no válida.']);
			}
		} else {
			http_response_code(400);
			return json_encode(['error' => 'Acción no especificada.']);
		}
	}

	/**
	 * Método para procesar la validación de los datos recibidos en el formulario
	 */
	public function procesarValidacion($data) {
		// Agregar encabezado para indicar que la respuesta es JSON
		header('Content-Type: application/json');
		$validator = new Validator(); // Instanciamos el validador

		// Definir los campos requeridos para la validación
		$camposRequeridos = [
			'nombre'      => 'Requerido',
			'contrasenna' => 'Requerido',
			'dni'         => 'Requerido|Dni',
			'email'       => 'Requerido|Email',
			'localidad'   => 'Requerido',
			'calle'       => 'Requerido',
			'numero'      => 'Requerido'
		];

		// Validar los campos obligatorios
		$errores = $validator->validarCampos($data, $camposRequeridos);

		$repoUser = new RepoUser();

		// Validar si el correo electrónico ya está registrado
		if (empty($errores['email']) && $validator->validarDuplicado('email', $data['email'], $repoUser)) {
			$errores['email'] = 'El correo electrónico ya está registrado.';
		}

		// Validar si el DNI ya está registrado
		if (empty($errores['dni']) && $validator->validarDuplicado('dni', $data['dni'], $repoUser)) {
			$errores['dni'] = 'El DNI ya está registrado.';
		}

		// Si hay errores, devolvemos la respuesta con los errores encontrados
		if (count($errores) > 0) {
			http_response_code(400); // Código HTTP 400 para errores de validación
			return json_encode(['errores' => $errores]);
		}

		// Si todo es válido, devolvemos un mensaje de éxito
		http_response_code(200); // Código HTTP 200, indicando que los datos son válidos y listos para ser procesados
		return json_encode(['success' => 'Formulario válido y listo para ser procesado.']);
	}

	/**
	 * Método para procesar el registro del usuario (creación de usuario y dirección)
	 */
	public function registrarUsuario($data) {
		$validator = new Validator(); // Instanciamos el validador

		// Validamos los campos requeridos antes de registrar al usuario
		$validacion = $this->procesarValidacion($data);
		// Si la validación no fue exitosa, devolvemos los errores
		if (strpos($validacion, '"errores"') !== false) {
			http_response_code(400); // Código HTTP 400 en caso de errores de validación
			return $validacion;
		}

		// Asignamos los valores a las variables, verificando si están presentes en los datos
		$nombre = $data['nombre'] ?? '';
		$apellido1 = $data['apellido1'] ?? null;
		$apellido2 = $data['apellido2'] ?? null;
		$telefono = $data['telefono'] ?? null;
		$foto = $data['foto'] ?? null;
		$monedero = $data['monedero'] ?? 0; // Valor predeterminado para el monedero
		$carrito = $data['carrito'] ?? '[]'; // Asignamos un arreglo vacío por defecto

		// Encriptamos la contraseña
		$data['contrasenna'] = password_hash($data['contrasenna'], PASSWORD_BCRYPT);

		// Crear el objeto Usuario con los datos recibidos
		$usuario = new Usuario(
			$nombre, $apellido1, $apellido2,
			$data['contrasenna'], $telefono, $data['email'],
			$data['dni'], $foto, $monedero, $carrito,
			'administrador' // El rol de usuario
		);

		// Guardar el usuario en la base de datos
		$repoUser = new RepoUser();
		$repoUser->create($usuario);
		$usuarioId = $usuario->getId();

		if ($usuarioId === null) {
			http_response_code(500); // Error en la creación del usuario
			return json_encode(['error' => 'No se pudo crear el usuario.']);
		}

		// Crear la dirección del usuario
		$direccion = new Direccion($data['localidad'], $data['calle'], $data['numero'], 1);
		$repoDireccion = new RepoDireccion();
		$repoDireccion->create($direccion, $usuarioId);

		// Si todo fue exitoso, respondemos con los datos necesarios para la redirección
		http_response_code(201); // Código HTTP 201 indicando que el recurso fue creado
		return json_encode([
			'success' => true,
			'message' => "El usuario {$data['email']} ha sido registrado con éxito.",
			'redirect_url' => '/login' // URL a la que se redirige al usuario después del registro
		]);
	}


}
