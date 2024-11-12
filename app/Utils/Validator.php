<?php

namespace App\Utils;

class Validator {
	private $errores;
	private $etiquetas;

	public function __construct() {
		$this->errores = [];
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
	private function obtenerEtiqueta($campo) {
		return isset($this->etiquetas[$campo]) ? $this->etiquetas[$campo] : $campo;
	}

	// Método para validar múltiples campos
	public function validarCampos($data, $camposRequeridos) {
		$errores = [];

		foreach ($camposRequeridos as $campo => $validaciones) {
			$validacionesArray = explode('|', $validaciones);
			foreach ($validacionesArray as $validacion) {
				$mensaje = $this->$validacion($campo, $data);
				if ($mensaje !== true) {
					$errores[$campo] = $mensaje;
					break;
				}
			}
		}

		return $errores;
	}

	// Validación de campo requerido
	public function Requerido($campo, $data) {
		if (empty($data[$campo])) {
			return $this->obtenerEtiqueta($campo) . " es obligatorio/a";
		}
		return true;
	}

	// Validación de email
	public function Email($campo, $data) {
		if (!filter_var($data[$campo], FILTER_VALIDATE_EMAIL)) {
			return $this->obtenerEtiqueta($campo) . " debe ser un email válido";
		}
		return true;
	}

	// Validación de DNI
	public function Dni($campo, $data) {
		$letras = "TRWAGMYFPDXBNJZSQVHLCKE";
		if (preg_match("/^[0-9]{8}[a-zA-Z]{1}$/", $data[$campo]) == 1) {
			$numero = substr($data[$campo], 0, 8);
			$letra = strtoupper(substr($data[$campo], 8, 1));
			if ($letras[$numero % 23] == $letra) {
				return true;
			} else {
				return $this->obtenerEtiqueta($campo) . " tiene una letra no válida";
			}
		} else {
			return $this->obtenerEtiqueta($campo) . " no es un DNI válido";
		}
	}

	// Método para validar duplicados, debe delegar la validación al repositorio
	public function validarDuplicado($campo, $valor, $repositorio) {
		return $repositorio->existePorCampo($campo, $valor);
	}

	public function obtenerErrores() {
		return $this->errores;
	}

	public function validacionPasada() {
		return count($this->errores) === 0;
	}

	public function imprimirError($campo) {
		return isset($this->errores[$campo]) ? '<span class="error_mensaje">' . $this->errores[$campo] . '</span>' : '';
	}
}

?>
