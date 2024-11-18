<?php

namespace App\Api;

use App\Utils\Logger;
use App\Repositorios\RepoUser;

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
		header('Content-Type: application/json');

		// Obtener usuario autenticado usando Logger
		$usuario = Logger::obtenerUsuario();

		if (!$usuario) {
			http_response_code(401); // No autorizado
			return json_encode(['success' => false, 'error' => 'No hay una sesión activa.']);
		}

		// Actualizar los datos del usuario
		if (isset($data['nombre'])) {
			$usuario->setNombre($data['nombre']);
		}
		if (isset($data['apellido1'])) {
			$usuario->setApellido1($data['apellido1']);
		}
		if (isset($data['apellido2'])) {
			$usuario->setApellido2($data['apellido2']);
		}
		if (isset($data['email'])) {
			$usuario->setEmail($data['email']);
		}
		if (isset($data['telefono'])) {
			$usuario->setTelefono($data['telefono']);
		}
		if (isset($data['dni'])) {
			$usuario->setDni($data['dni']);
		}
		if (isset($data['monedero'])) {
			$usuario->setMonedero($data['monedero']);
		}

		// Guardar cambios en la base de datos
		$this->repoUser->update($usuario);

		// Responder con éxito
		http_response_code(200);
		return json_encode(['success' => true, 'message' => 'Usuario actualizado correctamente.']);
	}
}
