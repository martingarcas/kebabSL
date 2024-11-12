<?php

namespace App\Repositorios; // Asegúrate de que esto esté aquí.
use PDO;
use PDOException;

class Conexion {

	private static $conexion = null;

	public static function getConection() {
		// Verificamos si la conexión ya está establecida
		if (self::$conexion == null) {
			try {
				// Establecemos las opciones para la conexión
				$opciones = array(
					PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", // Configuración para usar UTF-8
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Configuración para manejar excepciones
					PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Configuración para el modo de recuperación de datos
				);

				// Creamos la conexión a la base de datos
				self::$conexion = new PDO('mysql:host=localhost;dbname=kebabsl', 'root', 'martin', $opciones);

			} catch (PDOException $e) {
				// Si hay un error, lanzamos una excepción con el mensaje
				throw new PDOException('Error de conexión: ' . $e->getMessage());
			}
		}

		return self::$conexion;
	}
}
