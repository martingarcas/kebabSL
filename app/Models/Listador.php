<?php

    /*class Listador {

        public static function listar($animales) {

            var_dump($animales);

            foreach ($animales as $animal) {

                if ($animal instanceof iMuestra) {

                    echo $animal->muestra() . '<br>';
                }
            }
        }
    }*/
namespace App\Models;

    class Listador {

        public static function listar($kebabs) {

            // Recorremos el array de kebabs
            foreach ($kebabs as $kebab) {

                // Mostramos la información de cada animal
                echo "ID: " 		. $kebab['id'] . " ";
                echo "Carne: " 		. $kebab['carne'] . " ";
                echo "Verdura: " 	. $kebab['verdura'] . " ";
                echo "Salsa: " 		. $kebab['salsa'] . " ";
                echo "<br>";
            }
        }
    }

?>