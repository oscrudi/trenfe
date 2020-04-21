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

    function printTipoAsiento($result) {
        $output = "";
        while( $row = $result->fetch_assoc() ){
            $output .= "Código: " . $row["codigo"] . " - Nombre: " . $row["nombre"] . "<br>";
        }
        return $output;
    }

    function getAllTipoAsiento(){
        $query = "SELECT codigo, nombre FROM tipo_asiento ORDER BY nombre;";
        return consultarBBDD($query);
    }

    function getTipoAsientoPorCodigo($codigo){
        $query = "SELECT codigo, nombre FROM tipo_asiento WHERE codigo = '" . $codigo . "';";
        return consultarBBDD($query);
    }

?>