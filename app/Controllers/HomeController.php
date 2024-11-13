<?php

namespace App\Controllers;

use League\Plates\Engine;

class HomeController {

	protected $templates;

	public function __construct() {
		// Inicialización del motor de plantillas
		$this->templates = new Engine('../resources/views');  // Ajusta la ruta a tus vistas
	}

	public function index($view) {
		// Renderizar directamente la vista sin necesidad de verificar la sesión en el controlador
		echo $this->templates->render($view);
	}
}
