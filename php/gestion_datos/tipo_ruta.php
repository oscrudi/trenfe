<?php

    function printTipoRuta($result) {
        $output = "";
        while( $row = $result->fetch_assoc() ){
            $output .= "Código: " . $row["codigo"] . " - Nombre: " . $row["nombre"] . "<br>";
        }
        return $output;
    }

    function getAllTipoRuta(){
        $query = "SELECT codigo, nombre FROM tipo_ruta ORDER BY codigo;";
        return consultarBBDD($query);
    }

    function getTipoRutaPorCodigo($codigo){
        $query = "SELECT codigo, nombre FROM tipo_ruta WHERE codigo = '" . $codigo . "';";
        return consultarBBDD($query);
    }

?>