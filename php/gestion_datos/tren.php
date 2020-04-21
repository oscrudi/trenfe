<?php

    function addTren($codigo, $tipo){
        $query = "INSERT INTO tren (codigo, tipo, activo) VALUES ('" . $codigo . "', " . $tipo . ", 0);";
        return modificarBBDD($query);
    }

    function deleteTren($codigo){
        $query = "DELETE FROM tren WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function updateTipoTren($codigo, $tipo){
        //TODO: Comprobar que el numero de vagones no supera el máximo del tipo
        $query = "UPDATE tren SET tipo = " . $tipo . " WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function activarTren($codigo){
        $query = "UPDATE tren SET activo = 1 WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function desactivarTren($codigo){
        //TODO: desactivar línea que lo contenga
        $query = "UPDATE tren SET activo = 0 WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function printTren($result) {
        $output = "";
        while( $row = $result->fetch_assoc() ){
            $output .= "Código: " . $row["codigo"] . " - Tipo Tren: " . $row["tipo"] . " - Activo: " . $row["activo"] . "<br>";
        }
        return $output;
    }

    function getAllTren($activo = true){
        if( $activo ){
            $query = "SELECT codigo, tipo, activo FROM tren WHERE activo = 1 ORDER BY codigo;";
        }else{
            $query = "SELECT codigo, tipo, activo FROM tren ORDER BY codigo;";
        }
        return consultarBBDD($query);
    }

    function getTrenPorCodigo($codigo){
        $query = "SELECT codigo, tipo, activo FROM tren WHERE codigo = '" . $codigo . "';";
        return consultarBBDD($query);
    }

    function getTrenPorTipo($tipo, $activo = true){
        if( $activo ){
            $query = "SELECT codigo, tipo, activo FROM tren WHERE tipo = " . $tipo . " AND activo = 1 ORDER BY codigo;";
        } else {
            $query = "SELECT codigo, tipo, activo FROM tren WHERE tipo = " . $tipo . " ORDER BY codigo;";
        }
        return consultarBBDD($query);
    }

?>