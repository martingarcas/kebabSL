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
                echo "Carne: " 		. $kebab['nombre'] . " ";
                echo "Verdura: " 	. $kebab['foto'] . " ";
                echo "Salsa: " 		. $kebab['precio'] . " ";
                echo "<br>";
            }
        }
    }

?>