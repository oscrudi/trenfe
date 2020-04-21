<?php

    function addAsiento($fila, $letra, $grupo = null, $tipo, $vagon){
        if( $grupo ){
            $query = "INSERT INTO asiento (fila, letra, grupo, activo, tipo, vagon) VALUES (" . $fila . ", '" . $letra . "', " . $grupo . ", 1, " . $tipo . ", " . $vagon . ");";
        }else{
            $query = "INSERT INTO asiento (fila, letra, activo, tipo, vagon) VALUES (" . $fila . ", '" . $letra . "', 1, " . $tipo . ", " . $vagon . ");";
        }
        return modificarBBDD($query);
    }

    function deleteAsiento($fila, $letra){
        $query = "DELETE FROM asiento WHERE fila = " . $fila . " AND letra = '" . $letra . "';";
        return modificarBBDD($query);
    }

    function updateTipoAsiento($fila, $letra, $tipo){
        $query = "UPDATE asiento SET tipo = " . $tipo . " WHERE fila = " . $fila . " AND letra = '" . $letra . "';";
        return modificarBBDD($query);
    }

    function updateVagonAsiento($fila, $letra, $vagon){
        $query = "UPDATE asiento SET vagon = " . $vagon . " WHERE fila = " . $fila . " AND letra = '" . $letra . "';";
        return modificarBBDD($query);
    }

    function updateGrupoAsiento($fila, $letra, $grupo = null){
        $query = "UPDATE asiento SET grupo = " . $grupo . " WHERE fila = " . $fila . " AND letra = '" . $letra . "';";
        return modificarBBDD($query);
    }

    function activarAsiento($fila, $letra){
        $query = "UPDATE asiento SET activo = 1 WHERE fila = " . $fila . " AND letra = '" . $letra . "';";
        return modificarBBDD($query);
    }

    function desactivarAsiento($fila, $letra){
        //TODO: comprobar si el vagón que lo contiene no tiene más asientos activos
        $query = "UPDATE asiento SET activo = 0 WHERE fila = " . $fila . " AND letra = '" . $letra . "';";
        return modificarBBDD($query);
    }

    function printAsiento($result) {
        $output = "";
        while( $row = $result->fetch_assoc() ){
            $output .= "Fila: " . $row["fila"] . " - Letra: " . $row["letra"] . " - Tipo Asiento: " . $row["tipo"] . " - Vagón: " . $row["vagon"] . " - Activo: " . $row["activo"] . " - Grupo: " . $row["grupo"] . "<br>";
        }
        return $output;
    }

    function getAllAsiento($activo = true){
        if( $activo ){
            $query = "SELECT fila, letra, grupo, tipo, vagon, activo FROM asiento WHERE activo = 1 ORDER BY fila, letra;";
        }else{
            $query = "SELECT fila, letra, grupo, tipo, vagon, activo FROM asiento ORDER BY fila, letra;";
        }
        return consultarBBDD($query);
    }

    function getAsientoPorCodigo($fila, $letra){
        $query = "SELECT fila, letra, grupo, tipo, vagon, activo FROM asiento WHERE fila = " . $fila . " AND letra = '" . $letra . "';";
    }

    function getAsientoPorFila($fila, $activo = true){
        if( $activo ){
            $query = "SELECT fila, letra, grupo, tipo, vagon, activo FROM asiento WHERE fila = " . $fila . " AND activo = 1 ORDER BY letra;";
        } else {
            $query = "SELECT fila, letra, grupo, tipo, vagon, activo FROM asiento WHERE fila = " . $fila . " ORDER BY letra;";
        }
        return consultarBBDD($query);
    }

    function getAsientoPorTipo($tipo, $activo = true){
        if( $activo ){
            $query = "SELECT fila, letra, grupo, tipo, vagon, activo FROM asiento WHERE tipo = " . $tipo . " AND activo = 1 ORDER BY fila, letra;";
        } else {
            $query = "SELECT fila, letra, grupo, tipo, vagon, activo FROM asiento WHERE tipo = " . $tipo . " ORDER BY fila, letra;";
        }
        return consultarBBDD($query);
    }

    function getAsientoPorGrupo($grupo, $activo = true){
        if( $activo ){
            $query = "SELECT fila, letra, grupo, tipo, vagon, activo FROM asiento WHERE grupo = " . $grupo . " AND activo = 1 ORDER BY fila, letra;";
        } else {
            $query = "SELECT fila, letra, grupo, tipo, vagon, activo FROM asiento WHERE grupo = " . $grupo . " ORDER BY fila, letra;";
        }
        return consultarBBDD($query);
    }

    function getAsientoPorVagon($vagon, $activo = true){
        if( $activo ){
            $query = "SELECT fila, letra, grupo, tipo, vagon, activo FROM asiento WHERE vagon = " . $vagon . " AND activo = 1 ORDER BY fila, letra;";
        } else {
            $query = "SELECT fila, letra, grupo, tipo, vagon, activo FROM asiento WHERE vagon = " . $vagon . " ORDER BY fila, letra;";
        }
        return consultarBBDD($query);
    }

    function getAsientoPorTren($tren, $activo = true){
        if( $activo ){
            $result_vagones = getVagonPorTren($tren);
            $vagones = array();
            while( $row_vagones = $result_vagones->fetch_assoc() ){
                array_push($vagones, $row_vagones["codigo"]);
            }
            $asientos = array();
            foreach( $vagones as $vagon ){
                $result_asientos = getAsientoPorVagon($vagon);
                while( $row_asientos = $result_asientos->fetch_assoc() ){
                    array_push($asientos, $row_asientos);
                }
            }
        } else {
            $result_vagones = getVagonPorTren($tren, false);
            $vagones = array();
            while( $row_vagones = $result_vagones->fetch_assoc() ){
                array_push($vagones, $row_vagones["codigo"]);
            }
            $asientos = array();
            foreach( $vagones as $vagon ){
                $result_asientos = getAsientoPorVagon($vagon, false);
                while( $row_asientos = $result_asientos->fetch_assoc() ){
                    array_push($asientos, $row_asientos);
                }
            }
        }
        return $asientos;
    }

?>