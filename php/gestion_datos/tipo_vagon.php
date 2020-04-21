<?php

    function addTipoVagon($nombre, $asientos_max, $carga_max){
        $query = "INSERT INTO tipo_vagon (nombre, asientos_max, carga_max) VALUES ('" . $nombre . "', " . $asientos_max . ", " . $carga_max . ");";
        return modificarBBDD($query);
    }

    function deleteTipoVagon($codigo){
        $query = "DELETE FROM tipo_vagon WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function updateNombreTipoVagon($codigo, $nombre){
        $query = "UPDATE tipo_vagon SET nombre = '" . $nombre . "' WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function updateAsientosMaxTipoVagon($codigo, $asientos_max){
        $query = "UPDATE tipo_vagon SET asientos_max = " . $asientos_max . " WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function updateCargaMaxTipoVagon($codigo, $carga_max){
        $query = "UPDATE tipo_vagon SET carga_max = " . $carga_max . " WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function printTipoVagon($result) {
        $output = "";
        while( $row = $result->fetch_assoc() ){
            $output .= "Código: " . $row["codigo"] . " - Nombre: " . $row["nombre"] . " - Asientos Máx.: " . $row["asientos_max"] . " - Carga Máx.: " . $row["carga_max"] . "<br>";
        }
        return $output;
    }

    function getAllTipoVagon(){
        $query = "SELECT codigo, nombre, asientos_max, carga_max FROM tipo_vagon ORDER BY nombre;";
        return consultarBBDD($query);
    }

    function getTipoVagonPorCodigo($codigo){
        $query = "SELECT codigo, nombre, asientos_max, carga_max FROM tipo_vagon WHERE codigo = '" . $codigo . "';";
        return consultarBBDD($query);
    }

    function getTipoVagonPorAsientos($asientos_min = null, $asientos_max = null){
        if( $asientos_min && $asientos_min > 0 && $asientos_max && $asientos_max > 0 ){
            $query = "SELECT codigo, nombre, asientos_max, carga_max FROM tipo_vagon WHERE asientos_max >= " . $asientos_min . " AND asientos_max <= " . $asientos_max . " ORDER BY nombre;";
        }elseif( $asientos_min && $asientos_min > 0 ){
            $query = "SELECT codigo, nombre, asientos_max, carga_max FROM tipo_vagon WHERE asientos_max >= " . $asientos_min . " ORDER BY nombre;";
        }elseif( $asientos_max && $asientos_max > 0 ){
            $query = "SELECT codigo, nombre, asientos_max, carga_max FROM tipo_vagon WHERE asientos_max <= " . $asientos_max . " ORDER BY nombre;";
        }else{
            return getAllTipoVagon();
        }
        return consultarBBDD($query);
    }

    function getTipoVagonPorCarga($carga_min = null, $carga_max = null){
        if( $carga_min && $carga_min > 0 && $carga_max && $carga_max > 0 ){
            $query = "SELECT codigo, nombre, asientos_max, carga_max FROM tipo_vagon WHERE carga_max >= " . $carga_min . " AND carga_max <= " . $carga_max . " ORDER BY nombre;";
        }elseif( $carga_min && $carga_min > 0 ){
            $query = "SELECT codigo, nombre, asientos_max, carga_max FROM tipo_vagon WHERE carga_max >= " . $carga_min . " ORDER BY nombre;";
        }elseif( $carga_max && $carga_max > 0 ){
            $query = "SELECT codigo, nombre, asientos_max, carga_max FROM tipo_vagon WHERE carga_max <= " . $carga_max . " ORDER BY nombre;";
        }else{
            return getAllTipoVagon();
        }
        return consultarBBDD($query);
    }

?>