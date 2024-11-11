<?php

	use App\Controllers\HomeController;
	use App\Controllers\PruebaController;
	use App\Controllers\RegisterController;

	$routes = [
		'' 			=> [HomeController::class, 'index', 'home'],
		'prueba' 	=> [PruebaController::class, 'index', 'prueba'],
		'register' 	=> [RegisterController::class, 'index', 'register'],
	];

	return $routes;

?>