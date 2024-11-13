<?php

namespace App\Controllers;

use League\Plates\Engine;
use App\Utils\FlashMessage;

class HomeController {
	protected $templates;

	public function __construct() {
		// Inicialización del motor de plantillas
		$this->templates = new Engine('../resources/views');  // Ajusta la ruta a tus vistas
	}

	public function index($view) {
		// Obtener el mensaje flash si existe
		$message = FlashMessage::getMessage();
		// Renderizar la vista pasando el mensaje flash
		echo $this->templates->render($view, ['message' => $message]);
	}
}
