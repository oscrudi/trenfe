<?php

    require "../db.php";

    function addProvincia($nombre, $region){
        $query = "INSERT INTO provincia (nombre, region) VALUES ('" . $nombre . "', '" . $region . "');";
        return consultarBBDD($query);
    }

    function deleteProvincia($codigo){
        $query = "DELETE FROM provincia WHERE codigo = " . $codigo . ";";
        return consultarBBDD($query);
    }

    function updateNombreProvincia($codigo, $nombre){
        $query = "UPDATE provincia SET nombre = '" . $nombre . "' WHERE codigo = '" . $codigo . "';";
        return consultarBBDD($query);
    }

?>