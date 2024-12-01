<?php
require_once './Miautocargador.php';
require_once '../vendor/autoload.php';

use App\Utils\Logger;
session_start(); // Necesario para trabajar con $_SESSION
// Iniciar sesión al comienzo del flujo de ejecución de la aplicación
Logger::iniciaSesion();

// Cargar las rutas
$routes = require_once '../routes/routes.php';

// Obtener el valor de 'menu' de la URL a través del $_GET, o usar la ruta por defecto
$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Verificar si la ruta existe en el archivo de rutas
if (array_key_exists($path, $routes)) {
	$controllerMethod = $routes[$path];

	// Verificar si la clase es una API (nombre de clase termina con 'Api')
	if (class_exists($controllerMethod[0]) && method_exists($controllerMethod[0], 'handleRequest')) {
		// Comprobar si la clase es una API
		$controllerClass = $controllerMethod[0];
		$controller = new $controllerClass();

		// Llamar al método 'handleRequest' de la API y pasar los datos POST
		echo $controller->handleRequest($_POST);
		exit();  // Terminamos el flujo aquí después de procesar la API
	}

	// Para otras rutas (controladores que no son API), sigue el flujo normal
	list($controllerClass, $method, $view) = $controllerMethod;

	// Crear una instancia del controlador y llamar al método
	$controller = new $controllerClass();
	$controller->$method($view);
} else {
	// Si la ruta no existe, devuelve un error 404
	header("HTTP/1.0 404 Not Found");
	echo "404 Not Found"; // Aquí puedes incluir una vista personalizada
	exit;
}
