<?php

	namespace App\Models; // Asegúrate de que esto esté aquí.
	use App\Repositorios\iMuestra; // Asegúrate de importar la interfaz correctamente

    class Kebab implements iMuestra{

        public $id;
        public $carne;
        public $verdura;
        public $salsa;

        public function __construct($id, $carne, $verdura, $salsa) {

            $this->id       = $id;
            $this->carne  	= $carne;
            $this->verdura  = $verdura;
            $this->salsa    = $salsa;
        }

        public function muestra() {
            
            return "[{$this->id}] - Kebab de {$this->carne} con: {$this->verdura} y con salsa {$this->salsa}.";
        }

    }
?>