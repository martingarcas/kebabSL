<?php


	namespace App\Models;


	class LineaPedido {

		private $id;
		private $cantidad;
		private $ingredientes;
		private $descripción;

		public function __construct($id = null, $cantidad, $estado, $descripción, $pedido) {

			$this->id       	= $id ?? null;
			$this->cantidad  	= $cantidad;
			$this->estado  		= $estado;
			$this->precio 		= $precio;
			$this->direccion 	= $direccion;
		}

	}

?>