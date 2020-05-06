<?php

    function addPais($nombre){
        $query = "INSERT INTO pais (nombre) VALUES ('" . $nombre . "');";
        return modificarBBDD($query);
    }

    function deletePais($codigo){
        $query = "DELETE FROM pais WHERE codigo = " . $codigo . ";";
        return modificarBBDD($query);
    }

    function updateNombrePais($codigo, $nombre){
        $query = "UPDATE pais SET nombre = '" . $nombre . "' WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function getAllPais(){
        $query = "SELECT * FROM pais ORDER BY nombre;";
        return consultarBBDD($query);
    }

    function getPaisPorCodigo($codigo){
        $query = "SELECT * FROM pais WHERE codigo = '" . $codigo . "';";
        return consultarBBDD($query);
    }

    function getPaisPorNombre($nombre){
        $query = "SELECT * FROM pais WHERE nombre LIKE '" . $nombre . "%' ORDER BY nombre;";
        return consultarBBDD($query);
    }

?>
