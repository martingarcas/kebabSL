<?php

	use App\Controllers\HomeController;
	use App\Controllers\PruebaController;
	use App\Controllers\ContactoController;

	$routes = [
		'' 			=> [HomeController::class, 'index'],
		'prueba' 	=> [PruebaController::class, 'index'],
		'contacto' 	=> [ContactoController::class, 'index'],
	];

	return $routes;

?>