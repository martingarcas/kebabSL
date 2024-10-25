<?php

spl_autoload_register(function ($clase) {
    $ruta = str_replace('\\', '/', $clase);
    $rutaCompleta = $_SERVER['DOCUMENT_ROOT'] . '/kebabSL/' . $ruta . '.php';

    if (file_exists($rutaCompleta)) {
        include $rutaCompleta;
    } else {
        throw new Exception("No se pudo cargar la clase: $clase");
    }
});