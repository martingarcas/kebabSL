<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;

$routes = [
	'' 				=> [HomeController::class, 'index', 'home'],
	'login' 		=> [AuthController::class, 'showLoginForm', 'login'],
	'login-post' 	=> [AuthController::class, 'loginUser', 'home'],
	'register' 		=> [AuthController::class, 'showRegisterForm', 'register'],
	'register-post' => [AuthController::class, 'registerUser', 'home'],
	'logout' 		=> [AuthController::class, 'logoutUser', 'home'],
	'apiLogin'      => 'ApiLogin.php',  // Redirigir a un archivo específico, en este caso a la API de login
];

return $routes;

?>
