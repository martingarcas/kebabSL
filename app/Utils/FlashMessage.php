<?php

namespace App\Utils;

class FlashMessage {

	// Asegura que la sesión esté iniciada antes de usarla
	private static function iniciaSesion() : void {
		if (session_status() === PHP_SESSION_NONE) {
			session_start(); // Inicia sesión solo si no está activa
		}
	}

	// Establece un mensaje en la sesión
	public static function setMessage($message, $type = 'success') {
		self::iniciaSesion();  // Llamar a la función para asegurarse de que la sesión esté iniciada
		$_SESSION['flash_message'] = [
			'message' => $message,
			'type' => $type
		];
	}

	// Obtiene el mensaje de la sesión y lo elimina
	public static function getMessage() {
		self::iniciaSesion();  // Llamar a la función para asegurarse de que la sesión esté iniciada
		if (isset($_SESSION['flash_message'])) {
			$message = $_SESSION['flash_message'];
			unset($_SESSION['flash_message']);  // Eliminar el mensaje de la sesión
			return $message;
		}
		return null;
	}

	// Verifica si hay un mensaje de flash en la sesión
	public static function hasMessage() {
		self::iniciaSesion();  // Llamar a la función para asegurarse de que la sesión esté iniciada
		return isset($_SESSION['flash_message']);
	}
}

?>
