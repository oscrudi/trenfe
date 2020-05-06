<?php

    function addRegion($nombre, $pais){
        $query = "INSERT INTO region (nombre, codigo_pais) VALUES ('" . $nombre . "', '" . $pais . "');";
        return modificarBBDD($query);
    }

    function deleteRegion($codigo){
        $query = "DELETE FROM region WHERE codigo = " . $codigo . ";";
        return modificarBBDD($query);
    }

    function updateNombreRegion($codigo, $nombre){
        $query = "UPDATE region SET nombre = '" . $nombre . "' WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function getAllRegion(){
        $query = "SELECT * FROM region ORDER BY nombre;";
        return consultarBBDD($query);
    }

    function getRegionPorCodigo($codigo){
        $query = "SELECT * FROM region WHERE codigo = '" . $codigo . "';";
        return consultarBBDD($query);
    }

    function getRegionPorPais($pais){
        $query = "SELECT * FROM region WHERE codigo_pais = '" . $pais . "' ORDER BY nombre;";
        return consultarBBDD($query);
    }

    function getRegionPorNombre($nombre){
        $query = "SELECT * FROM region WHERE nombre LIKE '" . $nombre . "%' ORDER BY nombre;";
        return consultarBBDD($query);
    }

?>
