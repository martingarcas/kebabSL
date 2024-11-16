<?php

namespace App\Models;

class Alergeno {


	private $id;
	private $nombre;
	private $foto;

	public function __construct($id, $nombre, $foto) {

		$this->id       = $id ?? null;
		$this->nombre  	= $nombre;
		$this->foto  	= $foto;
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

	public function getAsArray() {

		return [
			'id' => $this->getId(),
			'nombre' => $this->getNombre(),
			'foto' => $this->getFoto(),
		];
	}
}

?>