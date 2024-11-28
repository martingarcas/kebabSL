<?php

namespace App\Api;

use App\Utils\Logger;
use App\Repositorios\RepoUser;
use App\Utils\Validator;

class ApiUser {

	private $repoUser;

	public function __construct() {
		$this->repoUser = new RepoUser();
	}

	public function handleRequest($data) {
		header('Content-Type: application/json');

		if (!isset($data['action'])) {
			http_response_code(400);
			return json_encode(['error' => 'Acción no especificada.']);
		}

		switch ($data['action']) {
			case 'loadUser':
				return $this->cargarUsuarioAutenticado();
			case 'updateUser':
				return $this->actualizarUsuario($data);
			default:
				http_response_code(400);
				return json_encode(['error' => 'Acción no válida.']);
		}
	}

	public function cargarUsuarioAutenticado() {
		header('Content-Type: application/json');

		// Obtener usuario autenticado usando Logger
		$usuario = Logger::obtenerUsuario();

		if (!$usuario) {
			http_response_code(401); // No autorizado
			return json_encode(['success' => false, 'error' => 'No hay una sesión activa.']);
		}

		// Responder con los datos del usuario
		http_response_code(200);
		return json_encode([
			'success' => true,
			'usuario' => [
				'id'        => $usuario->getId(),
				'nombre'    => $usuario->getNombre(),
				'apellido1' => $usuario->getApellido1(),
				'apellido2' => $usuario->getApellido2(),
				'email'     => $usuario->getEmail(),
				'telefono'  => $usuario->getTelefono(),
				'dni'       => $usuario->getDni(),
				'monedero'  => $usuario->getMonedero(),
				'foto'      => $usuario->getFoto(),
				'rol'       => $usuario->getRol()
			]
		]);
	}

	public function actualizarUsuario($data) {

		// Obtener el ID del usuario autenticado desde la sesión
		$usuario = Logger::obtenerUsuario(); // Obtener el objeto Usuario desde la sesión
		if (!$usuario) {
			http_response_code(401); // Unauthorized
			return json_encode(['error' => 'No se ha encontrado un usuario autenticado.']);
		}
//		$idUsuario = $data['id']; // Obtener el ID del usuario autenticado
		$idUsuario = $usuario->getId(); // Obtener el ID del usuario autenticado

		if (!$idUsuario) {
			return json_encode(['error' => $usuario->getId()]);
		}

		// Verificar si se ha subido una foto
		if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
			// Procesar la foto solo si se ha subido correctamente
			$foto = $_FILES['foto'];
			$directorioDestino = $_SERVER['DOCUMENT_ROOT'] . '/img/perfil/'; // Asegúrate de que la carpeta 'perfil' exista
			$nombreOriginal = basename($foto['name']);
			$rutaDestino = $directorioDestino . $nombreOriginal;

			// Comprobar si ya existe el archivo
			if (file_exists($rutaDestino)) {
				$i = 1;
				$ext = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
				$nombreArchivo = pathinfo($nombreOriginal, PATHINFO_FILENAME) . '-' . $i . '.' . $ext;
				$rutaDestino = $directorioDestino . $nombreArchivo;
			} else {
				$nombreArchivo = $nombreOriginal;
			}

			// Mover el archivo al directorio de destino
			if (!move_uploaded_file($foto['tmp_name'], $rutaDestino)) {
				http_response_code(400);
				return json_encode(['error' => 'Error al guardar la imagen.']);
			}

			// Ruta relativa a la imagen para almacenar en la base de datos
			$fotoUrl = '/img/perfil/' . $nombreArchivo;

			// Actualizar la base de datos con la nueva URL de la foto
			$usuarioRepositorio = new RepoUser();
			$actualizacionExitosa = $usuarioRepositorio->actualizarCampo($idUsuario, 'foto', $fotoUrl);

			if ($actualizacionExitosa) {
				http_response_code(201); // OK
				return json_encode([
					'success' => true,
					'message' => 'Foto de perfil actualizada correctamente.',
					'fotoUrl' => $fotoUrl
				]);
			} else {
				http_response_code(500); // Internal Server Error
				return json_encode(['error' => 'No se pudo actualizar la foto de perfil.']);
			}
		}

		// Inicializar la clase Validator (asumiendo que ya tienes una instancia lista)
		$validator = new Validator();

		// Recoger el campo y el valor enviado desde el front
		$campo = $data['campo'] ?? null;
		$valor = $data['valor'] ?? null;

		// Verificar si el campo es válido y está en la lista permitida
		$etiquetasPermitidas = $validator->getEtiquetas();
		if (!$campo || !array_key_exists($campo, $etiquetasPermitidas)) {
			http_response_code(400); // Bad Request
			return json_encode(['error' => 'El campo enviado no es válido.']);
		}

		// Validaciones genéricas y específicas según el campo
		$resultado = $validator->RequeridoCampo($campo, $valor);
		if ($resultado !== true) {
			http_response_code(422); // Unprocessable Entity
			return json_encode(['error' => $resultado]);
		}

		// Validación para 'email'
		if ($campo === 'email') {
			// Primero validamos el formato del email
			$resultado = $validator->validarEmail($campo, $valor);
			if ($resultado !== true) {
				http_response_code(422); // Unprocessable Entity
				return json_encode(['error' => $resultado]);
			}

			// Verificar si el email ya está en uso (validación de duplicado)
			$repositorio = new RepoUser(); // Suponiendo que tienes un repositorio de usuarios
			$existeEmail = $validator->validarDuplicado($campo, $valor, $repositorio);
			if ($existeEmail) {
				http_response_code(422); // Unprocessable Entity
				return json_encode(['error' => 'El correo electrónico ya está registrado.']);
			}
		}

		// Validación para 'dni'
		if ($campo === 'dni') {
			// Primero validamos el formato del dni
			$resultado = $validator->validarDni($campo, $valor);

			if ($resultado !== true) {
				http_response_code(422); // Unprocessable Entity
				return json_encode(['error' => $resultado]);
			}

			// Verificar si el dni ya está en uso (validación de duplicado)
			$repositorio 	= new RepoUser(); // Suponiendo que tienes un repositorio de usuarios
			$existeDni 		= $validator->validarDuplicado($campo, $valor, $repositorio);

			if ($existeDni) {
				http_response_code(422); // Unprocessable Entity
				return json_encode(['error' => 'El DNI ya está registrado.']);
			}
		}

		// Aquí procederíamos con la actualización del campo en la base de datos
		$usuarioRepositorio 	= new RepoUser();
		$actualizacionExitosa 	= $usuarioRepositorio->actualizarCampo($idUsuario, $campo, $valor);

		if ($actualizacionExitosa) {
			// Respuesta de éxito si todo ha ido bien
			http_response_code(201); // OK
			return json_encode([
				'success' => true,
				'valorActualizado' => $valor,
				'message' => $campo . ' actualizado correctamente.'
			]);

		} else {
			// Si no se ha actualizado nada
			http_response_code(500); // Internal Server Error
			return json_encode(['error' => 'No se pudo actualizar el ' . $campo . '.']);
//			return json_encode(['error' => $usuario->getId()]);
		}

	}
}
