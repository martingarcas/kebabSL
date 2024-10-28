<?php

	namespace App\Repositorios;


    interface RepoCrud {

        public function create($obj);

        public function getById($id);

        public function getAll();

        public function update($obj);

        public function delete($id);
    }

?>