<?php

	use App\Controllers\HomeController;
	use App\Controllers\PruebaController;
	use App\Controllers\ContactoController;

	$routes = [
		'' 			=> [HomeController::class, 'index', 'home'],
		'prueba' 	=> [PruebaController::class, 'index', 'prueba'],
		'contacto' 	=> [ContactoController::class, 'index', 'contacto'],
	];

	return $routes;

?>