<?php

    function addCodigoDescuento($codigo, $porcentaje_descuento){
        $query = "INSERT INTO codigo_descuento (codigo, porcentaje_descuento, activo) VALUES ('" . $codigo . "', " . $porcentaje_descuento . ", 0);";
        return modificarBBDD($query);
    }

    function deleteCodigoDescuento($codigo){
        $query = "DELETE FROM codigo_descuento WHERE codigo = " . $codigo . ";";
        return modificarBBDD($query);
    }

    function activarCodigoDescuento($codigo){
        $query = "UPDATE codigo_descuento SET activo = 1 WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function desactivarCodigoDescuento($codigo){
        $query = "UPDATE codigo_descuento SET activo = 0 WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function getAllCodigoDescuento($activo = true){
        if( !$activo ){
            $query = "SELECT * FROM codigo_descuento ORDER BY codigo;";
        } else {
            $query = "SELECT * FROM codigo_descuento WHERE activo = 1 ORDER BY codigo;";
        }
        return consultarBBDD($query);
    }

    function getCodigoDescuentoPorCodigo($codigo){
        $query = "SELECT * FROM codigo_descuento WHERE codigo = '" . $codigo . "';";
        return consultarBBDD($query);
    }

?>
