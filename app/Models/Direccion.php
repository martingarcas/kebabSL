<?php

	namespace App\Models;

	class Direccion {

		private $id;
		private $calle;
		private $numero;

		public function __construct($id, $calle, $numero) {

			$this->id       = $id;
			$this->calle  	= $calle;
			$this->numero  	= $numero;
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

	}

?>