<?php

	namespace App\Models;

	class Direccion {

		private $id;
		private $localidad;
		private $calle;
		private $numero;
		private $activa;

		public function __construct($localidad, $calle, $numero, $activa) {

			$this->localidad 	= $localidad;
			$this->calle 		= $calle;
			$this->numero  		= $numero;
			$this->activa  		= $activa;
		}

		public function crearDireccion($id, $localidad, $calle, $numero, $activa) {

			$this->id 			= $id;
			$this->localidad 	= $localidad;
			$this->calle 		= $calle;
			$this->numero 		= $numero;
			$this->activa 		= $activa;
		}

		public function getId() {

			return $this->id;
		}

		public function getLocalidad() {

			return $this->localidad;
		}

		public function setLocalidad($localidad) {

			$this->localidad = $localidad;
		}

		public function getCalle() {

			return $this->calle;
		}

		public function setCalle($calle) {

			$this->calle = $calle;
		}

		public function getNumero() {

			return $this->numero;
		}

		public function setNumero($numero) {

			$this->numero = $numero;
		}

		public function getActiva() {

			return $this->activa;
		}

		public function setActiva($activa) {

			$this->activa = $activa;

		}

	}

?>