<?php

	namespace App\Models;

	class Direccion {

		private $id;
		private $calle;
		private $numero;
		private $activa;

		public function __construct($id = null, $calle, $numero, $activa) {

			$this->id       = $id ?? null;
			$this->calle  	= $calle;
			$this->numero  	= $numero;
			$this->activa  	= $activa;
		}

		public function getId() {

			return $this->id;
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

		public function setActiva($activa) {

			$this->activa = $activa;

		}

	}

?>