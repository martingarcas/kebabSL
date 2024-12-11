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

		// Si no se reciben datos
		if (empty($data)) {
			http_response_code(400); // Si no hay datos en la solicitud
			return json_encode(['error' => 'No se recibieron datos.']);
		}

		// Definir las reglas de validación para los campos
		$camposRequeridos = [
			'nombre'      => 'Requerido',
			'contrasenna' => 'Requerido',
			'dni'         => 'Requerido|Dni',
			'email'       => 'Requerido|Email',
			'localidad'   => 'Requerido',
			'calle'       => 'Requerido',
			'numero'      => 'Requerido'
		];

		// Instanciamos el validador
		$validator = new Validator();

		// Array para almacenar los errores
		$errores = [];

		// Recorrer los datos recibidos para encontrar el campo a validar
		$campoValidado = null;
		foreach ($data as $campo => $valor) {
			if (isset($camposRequeridos[$campo])) {
				$campoValidado = $campo;
				break; // Cuando encontramos el primer campo válido, lo asignamos y salimos del bucle
			}
		}

		// Si no encontramos un campo válido, devolver un error
		if (!$campoValidado) {
			http_response_code(400);
			return json_encode(['error' => 'No se encontró un campo válido para validar.']);
		}

		// Validar el campo encontrado
		// En el primer parametro obtengo el array con el nombre y el valor que me llega
		// En el segundo parámetro para hacer una validación individual obtengo como clave el nombre del campo y le aplico la regla que pertenezca a esa campo en camposRequeridos
		$erroresCampo = $validator->validarCampos([$campoValidado => $data[$campoValidado]], [$campoValidado => $camposRequeridos[$campoValidado]]);

		// Si hay errores en la validación del campo, agregarlos al array de errores
		if (!empty($erroresCampo)) {
			$errores[$campoValidado] = $erroresCampo[$campoValidado];
		}

		// Validación extra para correo electrónico
		if ($campoValidado === 'email' && empty($errores['email'])) {
			$repoUser = new RepoUser();
			if ($validator->validarDuplicado('email', $data['email'], $repoUser)) {
				$errores['email'] = 'El correo electrónico ya está registrado.';
			}
		}

		// Validación extra para DNI
		if ($campoValidado === 'dni' && empty($errores['dni'])) {
			$repoUser = new RepoUser();
			if ($validator->validarDuplicado('dni', $data['dni'], $repoUser)) {
				$errores['dni'] = 'El DNI ya está registrado.';
			}
		}

		// Si hay errores, devolverlos con código 400
		if (count($errores) > 0) {
			http_response_code(400);  // Error si hay validaciones fallidas
			return json_encode(['errores' => $errores]);
		}

		// Si no hay errores, devolver el éxito con código 200
		http_response_code(200);  // OK si todo es correcto
		return json_encode(['success' => 'Campo validado correctamente.']);
	}


	/**
	 * Método para procesar la validación de los datos recibidos en el formulario
	 */
//	public function procesarValidacion($data) {
//		// Agregar encabezado para indicar que la respuesta es JSON
//		header('Content-Type: application/json');
//		$validator = new Validator(); // Instanciamos el validador
//
//		// Definir los campos requeridos para la validación
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
//		// Validar los campos obligatorios
//		$errores = $validator->validarCampos($data, $camposRequeridos);
//
//		$repoUser = new RepoUser();
//
//		// Validar si el correo electrónico ya está registrado
//		if (empty($errores['email']) && $validator->validarDuplicado('email', $data['email'], $repoUser)) {
//			$errores['email'] = 'El correo electrónico ya está registrado.';
//		}
//
//		// Validar si el DNI ya está registrado
//		if (empty($errores['dni']) && $validator->validarDuplicado('dni', $data['dni'], $repoUser)) {
//			$errores['dni'] = 'El DNI ya está registrado.';
//		}
//
//		// Si hay errores, devolvemos la respuesta con los errores encontrados
//		if (count($errores) > 0) {
//			http_response_code(400); // Código HTTP 400 para errores de validación
//			return json_encode(['errores' => $errores]);
//		}
//
//		// Si todo es válido, devolvemos un mensaje de éxito
//		http_response_code(200); // Código HTTP 200, indicando que los datos son válidos y listos para ser procesados
//		return json_encode(['success' => 'Formulario válido y listo para ser procesado.']);
//	}

	/**
	 * Método para procesar el registro del usuario (creación de usuario y dirección)
	 */
	public function registrarUsuario($data) {
		$validator = new Validator(); // Instanciamos el validador

		// Validamos los campos requeridos antes de registrar al usuario
		$validator = $this->procesarValidacion($data);
		// Si la validación no fue exitosa, devolvemos los errores
		if (strpos($validator, '"errores"') !== false) {
			http_response_code(400); // Código HTTP 400 en caso de errores de validación
			return $validator;
		}

		// Asignamos los valores a las variables, verificando si están presentes en los datos
		$nombre 	= $data['nombre'] ?? '';
		$apellido1 	= $data['apellido1'] ?? null;
		$apellido2 	= $data['apellido2'] ?? null;
		$telefono 	= $data['telefono'] ?? null;
		$foto 		= $data['foto'] ?? null;
		$monedero 	= $data['monedero'] ?? 0; // Valor predeterminado para el monedero
		$carrito 	= $data['carrito'] ?? '[]'; // Asignamos un arreglo vacío por defecto

		// Encriptamos la contraseña
		$data['contrasenna'] = password_hash($data['contrasenna'], PASSWORD_BCRYPT);

		// Crear el objeto Usuario con los datos recibidos
		$usuario = new Usuario(null,
			$nombre, $apellido1, $apellido2,
			$data['contrasenna'], $telefono, $data['email'],
			$data['dni'], $foto, $monedero, $carrito,
			'cliente' // El rol de usuario
		);

		// Guardar el usuario en la base de datos
		$repoUser = new RepoUser();
		$usuarioRegistrado 	= $repoUser->create($usuario);
//		$repoUser->create($usuario);
		$usuarioId 	= $usuarioRegistrado->getId();
//		$usuarioId 	= $usuario->getId();

		if (!$usuarioId) {
			http_response_code(500); // Error en la creación del usuario
			return json_encode(['error' => 'Algo ha fallado, inténtelo de nuevo.', 'redirect_url' => '/register']);
		}

		// Crear la dirección del usuario
		$direccion = new Direccion($data['localidad'], $data['calle'], $data['numero'], 1);
		$repoDireccion = new RepoDireccion();
		$direccionCreada = $repoDireccion->create($direccion, $usuarioId);

		// Verificar si la dirección fue creada correctamente
		if (!$direccionCreada) {
			http_response_code(404); // Error en la creación de la dirección
			return json_encode([
				'success' => false,
				'message' => 'Algo ha fallado, inténtelo de nuevo.',
				'redirect_url' => '/register'
			]);
		}

		// Si todo fue exitoso, respondemos con los datos necesarios para la redirección
		http_response_code(201); // Código HTTP 201 indicando que el recurso fue creado
		return json_encode([
			'success' => true,
			'message' => "El usuario {$data['email']} ha sido registrado con éxito.",
//			'id' => $usuario->getId(),
			'redirect_url' => '/login' // URL a la que se redirige al usuario después del registro
		]);
	}

}
