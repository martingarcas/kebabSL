<?php

	use App\Controllers\HomeController;
	use App\Controllers\LoginController;
	use App\Controllers\PruebaController;
	use App\Controllers\RegisterController;

	//TODO cambiar a parámetro opcional
	$routes = [
		'' 				=> [HomeController::class, 'index', 'home'],
		'prueba' 		=> [PruebaController::class, 'index', 'prueba'],
		'register' 		=> [RegisterController::class, 'index', 'register'],
		'register-post' => [RegisterController::class, 'createUser', 'home'],
		'login' 		=> [LoginController::class, 'index', 'login'],
		'login-post' 	=> [LoginController::class, 'loginUser', 'home'],
	];

	return $routes;

?>