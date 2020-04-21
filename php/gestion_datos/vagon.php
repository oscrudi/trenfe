<?php

    function addVagon($codigo, $tipo, $tren){
        $query = "INSERT INTO vagon (codigo, activo, tipo, tren) VALUES ('" . $codigo . "', 0, " . $tipo . ", " . $tren . ");";
        return modificarBBDD($query);
    }

    function deleteVagon($codigo){
        $query = "DELETE FROM vagon WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function updateTipoVagon($codigo, $tipo){
        //TODO: Comprobar que el numero de asientos no supera el máximo del tipo
        $query = "UPDATE vagon SET tipo = " . $tipo . " WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function updateTrenVagon($codigo, $tren){
        $query = "UPDATE vagon SET tren = " . $tren . " WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function activarVagon($codigo){
        $query = "UPDATE vagon SET activo = 1 WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function desactivarVagon($codigo){
        //TODO: comprobar si el tren que lo contiene no tiene más vagones activos
        $query = "UPDATE vagon SET activo = 0 WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function printVagon($result) {
        $output = "";
        while( $row = $result->fetch_assoc() ){
            $output .= "Código: " . $row["codigo"] . " - Tipo Vagón: " . $row["tipo"] . " - Tren: " . $row["tren"] . " - Activo: " . $row["activo"] . "<br>";
        }
        return $output;
    }

    function getAllVagon($activo = true){
        if( $activo ){
            $query = "SELECT codigo, tipo, tren, activo FROM vagon WHERE activo = 1 ORDER BY codigo;";
        }else{
            $query = "SELECT codigo, tipo, tren, activo FROM vagon ORDER BY codigo;";
        }
        return consultarBBDD($query);
    }

    function getVagonPorCodigo($codigo){
        $query = "SELECT codigo, tipo, tren, activo FROM vagon WHERE codigo = '" . $codigo . "';";
        return consultarBBDD($query);
    }

    function getVagonPorTipo($tipo, $activo = true){
        if( $activo ){
            $query = "SELECT codigo, tipo, tren, activo FROM vagon WHERE tipo = " . $tipo . " AND activo = 1 ORDER BY codigo;";
        } else {
            $query = "SELECT codigo, tipo, tren, activo FROM vagon WHERE tipo = " . $tipo . " ORDER BY codigo;";
        }
        return consultarBBDD($query);
    }

    function getVagonPorTren($tren, $activo = true){
        if( $activo ){
            $query = "SELECT codigo, tipo, tren, activo FROM vagon WHERE tren = " . $tren . " AND activo = 1 ORDER BY codigo;";
        } else {
            $query = "SELECT codigo, tipo, tren, activo FROM vagon WHERE tren = " . $tren . " ORDER BY codigo;";
        }
        return consultarBBDD($query);
    }

?>