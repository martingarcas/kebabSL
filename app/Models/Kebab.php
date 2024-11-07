<?php

	namespace App\Models;

    class Kebab {

        private $id;
		private $nombre;
		private $foto;
		private $precio;
		private $ingredientes = [];

        public function __construct($nombre, $foto, $precio) {

            $this->nombre  	= $nombre;
            $this->foto  	= $foto;
            $this->precio   = $precio;
        }

        public function crearKebab($id, $nombre, $foto, $precio, $ingredientes) {

			$this->id  				= $id;
			$this->nombre  			= $nombre;
			$this->foto  			= $foto;
			$this->precio 			= $precio;
			$this->ingredientes[] 	= $ingredientes;
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

		public function getIngredientes() {

        	return $this->ingredientes;
		}

		public function setIngredientes($ingredientes) {

			foreach ($ingredientes as $ingrediente) {

				if (!$this->existeIngrediente($ingrediente)) {

					$this->agregarIngrediente($ingrediente);
				}
			}
		}

		public function existeIngrediente(Ingrediente $ingrediente) {

			$clave = $ingrediente->getId();

			return isset($this->ingredientes[$clave]);
		}

		public function agregarIngrediente(Ingrediente $ingrediente) {

        	if (!$this->existeIngrediente($ingrediente)) {

				$clave = $ingrediente->getId();
				$this->ingredientes[$clave] = $ingrediente;
			}
		}

		public function borrarIngrediente(Ingrediente $ingrediente) {

			if ($this->existeIngrediente($ingrediente)) {

				$clave = $ingrediente->getId();
				unset($this->ingredientes[$clave]);
			}
		}

	}

?>