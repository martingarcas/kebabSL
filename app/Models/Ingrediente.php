<?php

	namespace App\Models;

	class Ingrediente {

		private $id;
		private $nombre;
		private $foto;
		private $precio;
		private $alergenos = [];

		public function __construct($id, $nombre, $foto, $precio) {

			$this->id       = $id;
			$this->nombre  	= $nombre;
			$this->foto  	= $foto;
			$this->precio   = $precio;
		}

		public function getId() {

			return $this->id;
		}

		public function getNombre() {

			return $this->nombre;
		}

		public function setNombre($nombre) {

			$this->nombre = $nombre;
		}

		public function getFoto() {

			return $this->foto;
		}

		public function setFoto($foto) {

			$this->foto = $foto;
		}

		public function getPrecio() {

			return $this->precio;
		}

		public function setPrecio($precio) {

			$this->precio = $precio;
		}

		public function getAlergenos() {

			return $this->alergeno;
		}

		public function existeAlergeno(Alergeno $alergeno) {

			$clave = $alergeno->getId();

			return isset($this->alergeno[$clave]);
		}

		public function agregarAlergeno(Alergeno $alergeno) {

			if (!$this->existeAlergeno($alergeno)) {

				$clave = $alergeno->getId();
				$this->alergeno[$clave] = $alergeno;
			}
		}

		public function borrarAlergeno(Alergeno $alergeno) {

			if ($this->existeAlergeno($alergeno)) {

				$clave = $alergeno->getId();
				unset($this->alergeno[$clave]);
			}
		}

	}

?>