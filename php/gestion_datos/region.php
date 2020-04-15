<?php

    require "../db.php";

    function addRegion($nombre, $pais){
        $query = "INSERT INTO region (nombre, pais) VALUES ('" . $nombre . "', '" . $pais . "');";
        return consultarBBDD($query);
    }

    function deleteRegion($codigo){
        $query = "DELETE FROM region WHERE codigo = " . $codigo . ";";
        return consultarBBDD($query);
    }

    function updateNombreRegion($codigo, $nombre){
        $query = "UPDATE region SET nombre = '" . $nombre . "' WHERE codigo = '" . $codigo . "';";
        return consultarBBDD($query);
    }

?>