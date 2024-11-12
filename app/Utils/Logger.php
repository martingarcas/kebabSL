<?php

namespace App\Utils;

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
	 * Función que inicia sesión y asigna valor a 'user' en la sesión.
	 *
	 * @param string $usuario
	 *
	 * @return void
	 */
	public static function login(string $usuario) : void {
		self::iniciaSesion();  // Asegura que la sesión esté iniciada
		$_SESSION['user'] = $usuario;  // Almacena el usuario en la sesión
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
	 * Función que obtiene la información del usuario si está logueado.
	 *
	 * @return array
	 */
	public static function obtenerUsuario() : ?array {
		self::iniciaSesion();  // Asegúrate de que la sesión está iniciada
		if (self::estaLogueado()) {
			// Si está logueado, obtenemos la información del usuario
			$usuarioEmail = self::leerSesion('user');
			return ['usuario' => $usuarioEmail];
		} else {
			// Si no está logueado, retornar null
			return null;
		}
	}
}

?>
