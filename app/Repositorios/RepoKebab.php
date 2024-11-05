<?php

    //$base = $_SERVER['DOCUMENT_ROOT'] ."/localhost/clasesCoches";
    //require_once "$base/Modelo/Coche.php";
	namespace App\Repositorios;

    class RepoKebab implements RepoCrud {

        private $con;

        public function __construct($con) {

            $this->con = $con;
        }

        public function create($obj) {

            $nuevoKebab = [];

            /*foreach ($obj as $valor) {
                
                $nuevoKebab [] = $valor;
            }*/

			$id 	= $nuevoKebab['id'];
			$nombre = $nuevoKebab['nombre'];
			$foto 	= $nuevoKebab['foto'];
			$precio = $nuevoKebab['precio'];


            $stm = $this->con->prepare("INSERT INTO kebab (id, nombre, foto, precio) VALUES (:id, :nombre, :foto, :precio)");
            $stm->execute(['id' => $id, 'nombre' => $nombre, 'foto' => $foto, 'precio' => $precio]);
            

        }

        public function getById($id) {

            $stm = $this->con->prepare("SELECT * FROM kebab WHERE id = :id");
            $stm->execute(['id' => $id]);

            $kebab          = null;
            $registroKebab  = $stm->fetch();

            if ($registroKebab) {

                $kebab          = new Kebab();
                $kebab->id      = $registroKebab["id"];
                $kebab->nombre 	= $registroKebab["carne"];
                $kebab->foto 	= $registroKebab["verdura"];
                $kebab->precio  = $registroKebab["salsa"];
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

            /*foreach ($obj as $valor) {
                
                $nuevoKebab [] = $valor;
            }*/

            $id 	= $nuevoKebab['id'];
            $nombre = $nuevoKebab['nombre'];
            $foto 	= $nuevoKebab['foto'];
			$precio = $nuevoKebab['precio'];

            $stm = $this->con->prepare("UPDATE kebab SET id = :id, nombre = :nombre, foto = :foto, precio = :precio WHERE id = :id");
            $stm->execute(['id' => $id, 'nombre' => $nombre, 'foto' => $foto, 'precio' => $precio]);

//            return 'correcta / no';
        }

        public function delete($id) {
            
            $stm = $this->con->prepare("DELETE FROM kebab WHERE id = :id");

            $stm->execute(['id' => $id]);

            $count = $stm->rowCount();

            return $count;

        }

    }

?>