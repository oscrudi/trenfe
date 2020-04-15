<?php

    require "../db.php";

    function addLocalidad($nombre, $provincia){
        $query = "INSERT INTO localidad (nombre, provincia) VALUES ('" . $nombre . "', '" . $provincia . "');";
        return consultarBBDD($query);
    }

    function deleteLocalidad($codigo){
        $query = "DELETE FROM localidad WHERE codigo = " . $codigo . ";";
        return consultarBBDD($query);
    }

    function updateNombreLocalidad($codigo, $nombre){
        $query = "UPDATE localidad SET nombre = '" . $nombre . "' WHERE codigo = '" . $codigo . "';";
        return consultarBBDD($query);
    }

?>