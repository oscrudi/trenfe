<?php

    function addTipoCliente($nombre, $porcentaje_descuento){
        $query = "INSERT INTO tipo_cliente (nombre, porcentaje_descuento, activo) VALUES ('" . $nombre . "', " . $porcentaje_descuento . ", 0);";
        return modificarBBDD($query);
    }

    function deleteTipoCliente($codigo){
        $query = "DELETE FROM tipo_cliente WHERE codigo = " . $codigo . ";";
        return modificarBBDD($query);
    }

    function activarTipoCliente($codigo){
        $query = "UPDATE tipo_cliente SET activo = 1 WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function desactivarTipoCliente($codigo){
        $query = "UPDATE tipo_cliente SET activo = 0 WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function getAllTipoCliente($activo = true){
        if( !$activo ){
            $query = "SELECT * FROM tipo_cliente ORDER BY nombre;";
        } else {
            $query = "SELECT * FROM tipo_cliente WHERE activo = 1 ORDER BY nombre;";
        }
        return consultarBBDD($query);
    }

    function getTipoClientePorCodigo($codigo){
        $query = "SELECT * FROM tipo_cliente WHERE codigo = '" . $codigo . "';";
        return consultarBBDD($query);
    }

?>
