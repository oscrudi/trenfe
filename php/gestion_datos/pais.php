<?php

    require "../db.php";

    function addPais($nombre){
        $query = "INSERT INTO pais (nombre) VALUES ('" . $nombre . "');";
        return consultarBBDD($query);
    }

    function deletePais($codigo){
        $query = "DELETE FROM pais WHERE codigo = " . $codigo . ";";
        return consultarBBDD($query);
    }

    function updateNombrePais($codigo, $nombre){
        $query = "UPDATE pais SET nombre = '" . $nombre . "' WHERE codigo = '" . $codigo . "';";
        return consultarBBDD($query);
    }

?>