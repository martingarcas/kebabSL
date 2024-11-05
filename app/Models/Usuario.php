<?php

	namespace App\Models;

	class Usuario {

		private $id;
		private $nombre;
		private $apellido1;
		private $apellido2;
		private $contrasenna;
		private $telefono;
		private $email;
		private $foto;
		private $monedero;
		private $carrito;

		public function __construct($id, $nombre, $apellido1, $apellido2, $contrasenna, $telefono, $email, $foto, $monedero, $carrito) {

			$this->id       	= $id;
			$this->nombre 		= $nombre;
			$this->apellido1 	= $apellido1;
			$this->apellido2 	= $apellido2;
			$this->contrasenna 	= $contrasenna;
			$this->telefono 	= $telefono;
			$this->email 		= $email;
			$this->foto 		= $foto;
			$this->monedero 	= $monedero;
			$this->carrito 		= $carrito;
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

		public function getApellido1() {

			return $this->apellido1;
		}

		public function setApellido1($apellido1) {

			$this->apellido1 = $apellido1;
		}

		public function getApellido2() {

			return $this->apellido2;
		}

		public function setApellido2($apellido2) {

			$this->apellido2 = $apellido2;
		}

		public function getContrasenna() {

			return $this->contrasenna;
		}

		public function setContrasenna($contrasenna) {

			$this->contrasenna = $contrasenna;
		}

		public function getTelefono() {

			return $this->telefono;
		}

		public function setTelefono($telefono) {

			$this->telefono = $telefono;
		}

		public function getEmail() {

			return $this->email;
		}

		public function setEmail($email) {

			$this->email = $email;
		}

		public function getFoto() {

			return $this->foto;
		}

		public function setFoto($foto) {

			$this->foto = $foto;
		}

		public function getMonedero() {

			return $this->monedero;
		}

		public function setMonedero($monedero) {

			$this->monedero = $monedero;
		}
		public function getCarrito() {

			return $this->carrito;
		}

		public function setCarrito($carrito) {

			$this->carrito = $carrito;
		}

	}

?>