<?php

namespace App\Controllers;

use App\Models\User;
use App\Repositorios\RepoUser;
use App\Utils\Logger;
use League\Plates\Engine;

class HomeController {

	protected $templates;

	public function __construct() {
		$this->templates = new Engine('../resources/views'); // Ajusta la ruta a tus vistas
	}

	public function index($view) {
		// Obtener los detalles del usuario desde el Logger
		$usuario = Logger::obtenerUsuario();  // Obtiene el usuario desde Logger

		if ($usuario['usuario'] !== null) {
			// Si está logueado, obtener los detalles del usuario usando el email
			$repoUser = new RepoUser();
			$usuarioDetails = $repoUser->findByEmail($usuario['usuario']);
			echo $this->templates->render($view, ['usuario' => $usuarioDetails]);  // Pasa los detalles del usuario a la vista
		} else {
			// Si no está logueado, mostrar la vista con el mensaje de invitado
			echo $this->templates->render($view, ['usuario' => null]);  // Puede ser null o un mensaje como 'invitado'
		}
	}
}
