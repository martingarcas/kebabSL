<?php

namespace App\Models;

class Ingrediente {

	private $id;
	private $nombre;
	private $foto;
	private $precio;
	private $alergenos;

	public function __construct($id, $nombre, $foto, $precio, $alergenos = []) {
		$this->id = $id;
		$this->nombre = $nombre;
		$this->foto = $foto;
		$this->precio = $precio;
		// Asegurar que $alergenos siempre sea un array
		$this->alergenos = is_array($alergenos) ? $alergenos : [];
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

	public function getAlergenos() {
		return $this->alergenos;
	}

	public function getAlergenosAsArray() {
		$alergenos = [];

		foreach ($this->alergenos as $alergeno) {
			if(!$alergeno) {
				continue;
			}

			if(is_array($alergeno)) {
				$alergenos[] = $alergeno;
				continue;
			}

			if($alergeno instanceof Alergeno) {
				$alergeno = $alergeno->getAsArray();
				$alergenos[$alergeno['id']] = $alergeno;
			}
		}

		return $alergenos;
	}

	public function setAlergenos($alergenos) {
		if (!is_array($alergenos)) {
			return;
		}

		foreach ($alergenos as $alergeno) {
			if ($alergeno instanceof Alergeno && !$this->existeAlergeno($alergeno)) {
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

	public function getAsArray() {
		return [
			'id' => $this->getId(),
			'nombre' => $this->getNombre(),
			'foto' => $this->getFoto(),
			'precio' => $this->getPrecio(),
			'alergenos' => $this->getAlergenosAsArray(),
		];
	}

}


?>