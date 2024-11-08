<?php

	namespace App\Models;
	
	class Pedido {

		private $id;
		private $fecha;
		private $estado;
		private $precio;
		private $direccion;
		private Usuario $usuario;
		private LineaPedido $linea_pedido;

		public function __construct($id = null, $fecha, $estado, $precio, $direccion, $usuario) {

			$this->id       	= $id ?? null;
			$this->fecha  		= $fecha;
			$this->estado  		= $estado;
			$this->precio 		= $precio;
			$this->direccion 	= $direccion;
			$this->usuario		= $usuario;
		}

		public function getId() {

			return $this->id;
		}

		public function getFecha() {

			return $this->fecha;
		}

		public function setFecha($fecha) {

			$this->fecha = $fecha;
		}

		public function getEstado() {

			return $this->estado;
		}

		public function setEstado($estado) {

			$this->estado = $estado;
		}

		public function getPrecio() {

			return $this->precio;
		}

		public function setPrecio($precio) {

			$this->precio = $precio;
		}

		public function getDireccion() {

			return $this->direccion;
		}

		public function setDireccion($direccion) {

			$this->direccion = $direccion;
		}

		public function getUsuario() {

			return $this->usuario;
		}
	
	}

?>