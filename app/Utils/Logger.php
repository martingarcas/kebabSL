<?php

namespace App\Utils;

use App\Models\Usuario;

class Logger {

	/**
	 * Función que inicia sesión, si no está activa.
	 *
	 * @return void
	 */
	public static function iniciaSesion() : void {
		if (session_status() === PHP_SESSION_NONE) {
			session_start();  // Inicia sesión solo si no está activa
		}
	}

	/**
	 * Función que destruye la sesión completamente (destruye las variables y cierra la sesión).
	 *
	 * @return void
	 */
	public static function cierraSesion() : void {
		session_destroy();  // Destruye la sesión (se borran todas las variables y la sesión)
	}

	/**
	 * Función que inicia sesión y asigna el objeto 'Usuario' completo a la sesión.
	 *
	 * @param Usuario $usuario
	 *
	 * @return void
	 */
	public static function login(Usuario $usuario) : void {
		self::iniciaSesion();  // Asegúrate de que la sesión esté iniciada

		// Serializa el objeto Usuario antes de guardarlo en la sesión
		$_SESSION['user'] = serialize($usuario);  // Serializamos el objeto

		// Agrega un var_dump para verificar si el objeto se ha serializado correctamente
//		var_dump($_SESSION['user']);  // Verifica que se trata de una cadena serializada
	}

	// Método para actualizar los datos del usuario en la sesión
	public static function actualizarSesion(Usuario $usuario) : void {
		self::iniciaSesion();  // Asegurarse de que la sesión esté iniciada
		$_SESSION['user'] = serialize($usuario);  // Volver a serializar el objeto actualizado
	}




	/**
	 * Función que cierra sesión, eliminando las variables de sesión.
	 *
	 * @return void
	 */
	public static function logout() : void {
		session_unset();  // Elimina todas las variables de sesión
		self::cierraSesion();  // Llama a cierraSesion para destruir la sesión completamente
	}

	/**
	 * Función que devuelve si el usuario está logueado.
	 *
	 * @return bool
	 */
	public static function estaLogueado() : bool {
		return isset($_SESSION['user']);  // Verifica si la clave 'user' existe en la sesión
	}

	/**
	 * Función que lee un parámetro de la sesión.
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public static function leerSesion(string $key) : ?string {
		return $_SESSION[$key] ?? null;  // Devuelve el valor de la variable de sesión o null si no existe
	}

	/**
	 * Función que escribe una variable en la sesión si está abierta.
	 *
	 * @param string $key
	 * @param string $valor
	 *
	 * @return void
	 */
	public static function escribirSesion(string $key, string $valor) : void {
		$_SESSION[$key] = $valor;  // Asigna el valor a la variable de sesión
	}

	/**
	 * Función que obtiene la información del usuario completo si está logueado.
	 *
	 * @return Usuario|null
	 */
// Logger.php

	public static function obtenerUsuario(): ?Usuario {
		// Verifica si el usuario está guardado en la sesión
		if (isset($_SESSION['user'])) {
			// Deserializa el objeto Usuario desde la sesión
			return unserialize($_SESSION['user']);
		}

		// Si no está en la sesión, devuelve null
		return null;
	}


}

?>
