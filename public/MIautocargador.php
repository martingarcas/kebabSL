<?php

	// Definimos la ruta base del proyecto "C:/xampp/htdocs/kebabSL"
	$base = dirname(__DIR__);

	// Registramos el autocargador
	spl_autoload_register(function ($clase) use ($base) {
		// Convertimos el namespace en una ruta de archivo
		$ruta = str_replace('\\', '/', $clase);

		// Construimos la ruta completa utilizando la ruta base
		$rutaCompleta = $base . '/' . $ruta . '.php';

		// Cargamos la clase si el archivo existe
		if (file_exists($rutaCompleta)) {

			require_once $rutaCompleta;

		} else {

			throw new Exception("No se pudo cargar la clase: $clase");
		}
	});
?>