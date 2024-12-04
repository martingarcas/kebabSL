<?php

	namespace App\Models;

    class Kebab {

		private $id;
		private $nombre;
		private $foto;
		private $precio;
		private $ingredientes;

		public function __construct($id, $nombre, $foto, $precio, $ingredientes = []) {
			$this->id 		= $id;
			$this->nombre 	= $nombre;
			$this->foto 	= $foto;
			$this->precio 	= $precio;
			// Asegurar que $ingredientes siempre sea un array
			$this->ingredientes = is_array($ingredientes) ? $ingredientes : [];
		}

		// Método getter para obtener el id
		public function getId() {
			return $this->id;
		}

		// Método setter para asignar el id (necesario para asignar el id después de la inserción)
		public function setId($id) {
			$this->id = $id;
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

		public function getIngredientesAsArray() {
			$ingredientes = [];

			foreach ($this->ingredientes as $ingrediente) {
				if(!$ingrediente) {
					continue;
				}

				if(is_array($ingrediente)) {
					$ingredientes[] = $ingrediente;
					continue;
				}

				if($ingrediente instanceof Ingrediente) {
					$ingrediente = $ingrediente->getAsArray();
					$ingredientes[$ingrediente['id']] = $ingrediente;
				}
			}

			return $ingredientes;
		}

		public function setIngredientes($ingredientes) {
			if (!is_array($ingredientes)) {
				return;
			}

			foreach ($ingredientes as $ingrediente) {
				if ($ingrediente instanceof Ingrediente && !$this->existeIngrediente($ingrediente)) {
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

		public function getAsArray() {
			return [
				'id' => $this->getId(),
				'nombre' => $this->getNombre(),
				'foto' => $this->getFoto(),
				'precio' => $this->getPrecio(),
				'ingredientes' => $this->getIngredientesAsArray(),
			];
		}

	}

?>