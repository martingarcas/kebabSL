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
	}
}
