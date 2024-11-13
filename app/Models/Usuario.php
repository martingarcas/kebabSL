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
		private $dni;
		private $foto;
		private $monedero;
		private $carrito;
		private $rol;
		private $direcciones = [];
		private $alergenos = [];

		public function __construct($nombre, $apellido1 = null, $apellido2 = null, $contrasenna, $telefono = null, $email, $dni, $foto = null, $monedero = null, $carrito = null, $rol) {

			$this->nombre 		= $nombre;
			$this->apellido1 	= $apellido1;
			$this->apellido2 	= $apellido2;
			$this->contrasenna 	= $contrasenna;
			$this->telefono 	= $telefono;
			$this->email 		= $email;
			$this->dni 			= $dni;
			$this->foto 		= $foto;
			$this->monedero 	= $monedero;
			$this->carrito 		= $carrito;
			$this->rol 			= $rol;
		}

		public function crearUsuario($id, $nombre, $apellido1, $apellido2, $contrasenna, $telefono, $email, $dni, $foto, $monedero, $carrito, $direcciones, $alergenos) {

			$this->id 			= $id;
			$this->nombre 		= $nombre;
			$this->apellido1 	= $apellido1;
			$this->apellido2 	= $apellido2;
			$this->contrasenna 	= $contrasenna;
			$this->telefono 	= $telefono;
			$this->email 		= $email;
			$this->dni 			= $dni;
			$this->foto 		= $foto;
			$this->monedero 	= $monedero;
			$this->carrito 		= $carrito;
			$this->direcciones 	= $direcciones;
			$this->alergenos 	= $alergenos;
		}

		public function getId() {

			return $this->id;
		}

		public function setId($id) {

			$this->id = $id;
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

		public function getDni() {

			return $this->dni;
		}

		public function setDni($dni) {

			$this->dni = $dni;
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

		public function getRol() {

			return $this->rol;
		}

		public function setRol($rol) {

			$this->rol = $rol;
		}

		public function getDirecciones() {

			return $this->direcciones;
		}

		public function setDirecciones($direcciones) {

			foreach ($direcciones as $direccion) {

				if (!$this->existeDireccion($direccion)) {

					$this->agregarDireccion($direccion);
				}
			}
		}

		public function existeDireccion(Direccion $direccion) {

			$clave = $direccion->getId();

			return isset($this->direcciones[$clave]);
		}

		public function agregarDireccion(Direccion $direccion) {

			if ($direccion->getActiva()) {

				$this->cambiarActiva($direccion);
			}

			if (!$this->existeDireccion($direccion)) {

				$clave = $direccion->getId();
				$this->direcciones[$clave] = $direccion;
			}
		}

		public function borrarDireccion(Direccion $direccion) {

			if ($this->existeDireccion($direccion)) {

				$clave = $direccion->getId();
				unset($this->direcciones[$clave]);
			}
		}

		public function cambiarActiva(Direccion $activaDireccion) {

			if ($this->existeDireccion($activaDireccion)) {

				$activaDireccion->setActiva(true);

				foreach ($this->direcciones as $direccion) {

					if ($direccion->getId() != $activaDireccion->getId()) {

						$direccion->setActiva(false);
					}
				}
			}
		}

		public function getAlergenos() {

			return $this->alergenos;
		}

		public function setAlergenos($alergenos) {

			foreach ($alergenos as $alergeno) {

				if (!$this->existeAlergeno($alergeno)) {

					$this->agregarAlergeno($alergeno);
				}
			}
		}

		public function existeAlergeno(Alergeno $alergeno) {

			$clave = $alergeno->getId();

			return isset($this->alergenos[$clave]);
		}

		public function agregarAlergeno(Alergeno $alergeno) {

			if (!$this->existeAlergeno($alergeno)) {

				$clave = $alergeno->getId();
				$this->alergenos[$clave] = $alergeno;
			}
		}

		public function borrarAlergeno(Alergeno $alergeno) {

			if ($this->existeAlergeno($alergeno)) {

				$clave = $alergeno->getId();
				unset($this->alergenos[$clave]);
			}
		}

	}

?>