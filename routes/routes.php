<?php

use App\Api\ApiContact;
use App\Api\ApiIngrediente;
use App\Api\ApiKebab;
use App\Api\ApiRegister;
use App\Api\ApiUser;
use App\Controllers\ContactController;
use App\Controllers\HomeController;
use App\Controllers\AuthController;

$routes = [
	'' 					=> [HomeController::class, 'index', 'home'],
	'login' 			=> [AuthController::class, 'showLoginForm', 'login'],
	'login-post' 		=> [AuthController::class, 'loginUser', 'home'],
	'register' 			=> [AuthController::class, 'showRegisterForm', 'register'],
	'register-post' 	=> [AuthController::class, 'registerUser', 'home'],
	'logout' 			=> [AuthController::class, 'logoutUser', 'home'],
	'apiRegister'   	=> [ApiRegister::class, 'handleRequest', null],
	'ingredientes'  	=> [AuthController::class, 'showIngredientes', 'ingredientes'],
	'apiIngrediente' 	=> [ApiIngrediente::class, 'handleRequest', null],
	'kebabs'  			=> [AuthController::class, 'showKebabs', 'kebabs'],
	'apiKebab' 			=> [ApiKebab::class, 'handleRequest', null],
	'contacto' 			=> [ContactController::class, 'index', 'contacto'],
	'apiContact' 		=> [ApiContact::class, 'handleRequest', 'null'],
	'profile' 			=> [AuthController::class, 'showProfile', 'profile'],
	'apiUser' 			=> [ApiUser::class, 'handleRequest', 'null'],
];

return $routes;

?>
