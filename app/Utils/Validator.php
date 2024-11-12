<?php

namespace App\Utils;

class Validator {
	private $errores;
	private $etiquetas;

	public function __construct()
	{
		$this->errores = [];
		// Mapeo de campos a etiquetas amigables
		$this->etiquetas = [
			'nombre'      => 'El nombre',
			'apellido1'   => 'El primer apellido',
			'apellido2'   => 'El segundo apellido',
			'email'       => 'El correo electrónico',
			'dni'         => 'El dni',
			'calle'       => 'La calle',
			'numero'      => 'El número de calle',
			'contrasenna' => 'La contraseña',
			'telefono'    => 'El teléfono',
			'foto'        => 'La foto',
			'monedero'    => 'El monedero',
			'carrito'     => 'El carrito'
		];
	}

	// Método para obtener el nombre amigable de un campo
	private function obtenerEtiqueta($campo)
	{
		return isset($this->etiquetas[$campo]) ? $this->etiquetas[$campo] : $campo;
	}

	/**
	 * Comprueba si está vacío
	 *
	 * @param string $campo
	 * @return boolean|string
	 */
	public function Requerido($campo)
	{
		if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
			return $this->obtenerEtiqueta($campo) . " es obligatorio/a";
		}
		return true;
	}

	/**
	 * Método que comprueba que el campo es un valor entero
	 * y de manera opcional un rango de valores
	 *
	 * @param string $campo
	 * @param int $min
	 * @param int $max
	 * @return boolean|string
	 */
	public function EnteroRango($campo, $min = PHP_INT_MIN, $max = PHP_INT_MAX)
	{
		if (!filter_var($_POST[$campo], FILTER_VALIDATE_INT, ["options" => ["min_range" => $min, "max_range" => $max]])) {
			return $this->obtenerEtiqueta($campo) . " debe ser un entero entre $min y $max";
		}
		return true;
	}

	/**
	 * Método que comprueba el número de caracteres de la cadena
	 * entre un mínimo y un máximo
	 *
	 * @param string $campo
	 * @param int $max
	 * @param int $min
	 * @return boolean|string
	 */
	public function CadenaRango($campo, $max, $min = 0)
	{
		if (!(strlen($_POST[$campo]) > $min && strlen($_POST[$campo]) < $max)) {
			return $this->obtenerEtiqueta($campo) . " debe tener entre $min y $max caracteres";
		}
		return true;
	}

	/**
	 * Comprueba si el campo es un email válido
	 *
	 * @param string $campo
	 * @return boolean|string
	 */
	public function Email($campo)
	{
		if (!filter_var($_POST[$campo], FILTER_VALIDATE_EMAIL)) {
			return $this->obtenerEtiqueta($campo) . " debe ser un email válido";
		}
		return true;
	}

	/**
	 * Validación del DNI español
	 *
	 * @param string $campo
	 * @return boolean|string
	 */
	public function Dni($campo)
	{
		$letras = "TRWAGMYFPDXBNJZSQVHLCKE";
		if (preg_match("/^[0-9]{8}[a-zA-Z]{1}$/", $_POST[$campo]) == 1) {
			$numero = substr($_POST[$campo], 0, 8);
			$letra = strtoupper(substr($_POST[$campo], 8, 1));
			if ($letras[$numero % 23] == $letra) {
				return true;
			} else {
				return $this->obtenerEtiqueta($campo) . " tiene una letra no válida";
			}
		} else {
			return $this->obtenerEtiqueta($campo) . " no es un DNI válido";
		}
	}

	/**
	 * Comprueba si el campo cumple una expresión regular (patrón)
	 *
	 * @param string $campo
	 * @param string $patron
	 * @return boolean|string
	 */
	public function Patron($campo, $patron)
	{
		if (!preg_match($patron, $_POST[$campo])) {
			return $this->obtenerEtiqueta($campo) . " no cumple el patrón $patron";
		}
		return true;
	}

	/**
	 * Ejecuta una función que será la encargada de validar el campo
	 *
	 * @param string $campo
	 * @param callable $funcion
	 * @param string $mensaje
	 * @return boolean|string
	 */
	public function ValidaConFuncion($campo, $funcion, $mensaje)
	{
		if (!call_user_func($funcion)) {
			return $mensaje;
		}
		return true;
	}

	/**
	 * Comprueba si hay errores
	 *
	 * @return boolean
	 */
	public function ValidacionPasada()
	{
		return count($this->errores) == 0;
	}

	/**
	 * Devuelve el mensaje de error
	 *
	 * @param string $campo
	 * @return string
	 */
	public function ImprimirError($campo)
	{
		return isset($this->errores[$campo]) ? '<span class="error_mensaje">' . $this->errores[$campo] . '</span>' : '';
	}

	public function getValor($campo)
	{
		return isset($_POST[$campo]) ? $_POST[$campo] : '';
	}

	public function getSelected($campo, $valor)
	{
		return isset($_POST[$campo]) && $_POST[$campo] == $valor ? 'selected' : '';
	}

	public function getChecked($campo, $valor)
	{
		return isset($_POST[$campo]) && $_POST[$campo] == $valor ? 'checked' : '';
	}
}

?>
