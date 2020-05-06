<?php

    function addTipoAsiento($nombre){
        $query = "INSERT INTO tipo_asiento (nombre) VALUES ('" . $nombre . "');";
        return modificarBBDD($query);
    }

    function deleteTipoAsiento($codigo){
        $query = "DELETE FROM tipo_asiento WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function updateNombreTipoAsiento($codigo, $nombre){
        $query = "UPDATE tipo_asiento SET nombre = '" . $nombre . "' WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function getAllTipoAsiento(){
        $query = "SELECT * FROM tipo_asiento ORDER BY nombre;";
        return consultarBBDD($query);
    }

    function getTipoAsientoPorCodigo($codigo){
        $query = "SELECT * FROM tipo_asiento WHERE codigo = '" . $codigo . "';";
        return consultarBBDD($query);
    }

?>
