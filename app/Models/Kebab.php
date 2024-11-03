<?php

	namespace App\Models; // Asegúrate de que esto esté aquí.
	use App\Repositorios\iMuestra; // Asegúrate de importar la interfaz correctamente

    class Kebab implements iMuestra{

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

		}

        public function getNombre() {

        	return $this->nombre;
		}

		public function setNombre($nombre) {

        	$this->nombre = $nombre;
		}

		public function getFoto() {

		}

		public function setFoto() {

		}

		public function getPrecio() {

		}

		public function setPrecio() {

		}

        public function muestra() {
            
            return "[{$this->id}] - Kebab de {$this->nombre} con: {$this->foto} y con salsa {$this->precio}.";
        }

    }
?>