<?php

	namespace App\Models;

	class Ingrediente {

		private $id;
		private $nombre;
		private $foto;
		private $precio;

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

	}

?>