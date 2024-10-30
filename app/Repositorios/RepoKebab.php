<?php

    //$base = $_SERVER['DOCUMENT_ROOT'] ."/localhost/clasesCoches";
    //require_once "$base/Modelo/Coche.php";
	namespace App\Repositorios; // Asegúrate de que esto esté aquí.

    class RepoKebab implements RepoCrud {

        private $con;

        public function __construct($con) {

            $this->con = $con;
        }

        public function create($obj) {

            $nuevoKebab = [];

            foreach ($obj as $valor) {
                
                $nuevoKebab [] = $valor;
            }

            $id         = $nuevoKebab[0];
            $carne 		= $nuevoKebab[1];
            $verdura 	= $nuevoKebab[2];
            $salsa      = $nuevoKebab[3];

            $stm = $this->con->prepare("INSERT INTO kebab (id, carne, verdura, salsa) VALUES (:id, :carne, :verdura, :salsa)");
            $stm->execute(['id' => $id, 'carne' => $carne, 'verdura' => $verdura, 'salsa' => $salsa]);
            

        }

        public function getById($id) {

            $stm = $this->con->prepare("SELECT * FROM kebab WHERE id = :id");
            $stm->execute(['id' => $id]);

            $kebab          = null;
            $registroKebab  = $stm->fetch();

            if ($registroKebab) {

                $kebab          = new Kebab();
                $kebab->id      = $registroKebab["id"];
                $kebab->carne 	= $registroKebab["carne"];
                $kebab->verdura = $registroKebab["verdura"];
                $kebab->salsa   = $registroKebab["salsa"];
            }

            return $kebab;
        }

        public function getAll() {

            $stm = $this->con->prepare("SELECT * FROM kebab");
            $stm->execute();

            $kebabs = $stm->fetchAll();
                  
            return $kebabs;
            
        }

        public function update($obj) {

            $nuevoKebab = [];

            foreach ($obj as $valor) {
                
                $nuevoKebab [] = $valor;
            }

            $id         = $nuevoKebab[0];
            $carne 		= $nuevoKebab[1];
            $verdura 	= $nuevoKebab[2];
            $salsa      = $nuevoKebab[3];

            $stm = $this->con->prepare("UPDATE kebab SET id = :id, carne = :carne, verdura = :verdura, salsa = :salsa WHERE id = :id");
            $stm->execute(['id' => $id, 'carne' => $carne, 'verdura' => $verdura, 'salsa' => $salsa]);
        }

        public function delete($id) {
            
            $stm = $this->con->prepare("DELETE FROM kebab WHERE id = :id");

            $stm->execute(['id' => $id]);

            $count = $stm->rowCount();

            return $count;

        }

    }

?>