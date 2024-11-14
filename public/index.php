<?php
require_once './Miautocargador.php';
require_once '../vendor/autoload.php';

use App\Utils\Logger;

// Iniciar sesión al comienzo del flujo de ejecución de la aplicación
Logger::iniciaSesion();

// Cargar las rutas
$routes = require_once '../routes/routes.php';

// Obtener el valor de 'menu' de la URL a través del $_GET, o usar la ruta por defecto
$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Aquí añadimos un control para ver si la ruta corresponde a la API
if (array_key_exists($path, $routes)) {
	$controllerMethod = $routes[$path];

	// Si la ruta es la de la API, incluimos directamente el archivo ApiLogin.php
	if ($controllerMethod == 'ApiLogin.php') {
		require_once '../app/Api/ApiLogin.php';
		exit();  // Terminamos el flujo aquí, ya que la API ya se ha procesado
	}

} else {
	// Si la ruta no existe, devuelve un error 404
	header("HTTP/1.0 404 Not Found");
	echo "404 Not Found"; // Aquí incluir vista personalizada
	exit;
}

// Determinar el controlador y el método a utilizar
list($controllerClass, $method, $view) = $controllerMethod;

// Crear una instancia del controlador y llamada al método
$controller = new $controllerClass();
$controller->$method($view);
?>
