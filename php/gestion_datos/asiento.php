<?php

    function addAsiento($fila, $letra, $grupo = null, $tipo, $vagon){
        $correcto = checkAsientosMax($vagon, false, true);
        if( !$correcto ){
            return false;
        }
        $query = "INSERT INTO asiento (fila, letra, grupo, activo, tipo, vagon) VALUES (" . $fila . ", '" . $letra . "', " . $grupo . ", 0, " . $tipo . ", '" . $vagon . "');";
        return modificarBBDD($query);
    }

    function deleteAsiento($fila, $letra, $vagon){
        $query = "DELETE FROM asiento WHERE fila = " . $fila . " AND letra = '" . $letra . "' AND vagon = '" . $vagon . "';";
        modificarBBDD($query);
        $asientos_activos = getAsientoPorVagon($vagon);
        if( $asientos_activos->num_rows < 1 ){
            desactivarVagon($vagon);
        }
        return true;
    }

    function updateTipoAsiento($fila, $letra, $vagon, $tipo){
        $query = "UPDATE asiento SET tipo = " . $tipo . " WHERE fila = " . $fila . " AND letra = '" . $letra . "' AND vagon = '" . $vagon . "';";
        return modificarBBDD($query);
    }

    function updateGrupoAsiento($fila, $letra, $vagon, $grupo = null){
        $query = "UPDATE asiento SET grupo = " . $grupo . " WHERE fila = " . $fila . " AND letra = '" . $letra . "' AND vagon = '" . $vagon . "';";
        return modificarBBDD($query);
    }

    function activarAsiento($fila, $letra, $vagon){
        $query = "UPDATE asiento SET activo = 1 WHERE fila = " . $fila . " AND letra = '" . $letra . "' AND vagon = '" . $vagon . "';";
        return modificarBBDD($query);
    }

    function desactivarAsiento($fila, $letra){
        $query = "UPDATE asiento SET activo = 0 WHERE fila = " . $fila . " AND letra = '" . $letra . "' AND vagon = '" . $vagon . "';";
        modificarBBDD($query);
        $asientos_activos = getAsientoPorVagon($vagon);
        if( $asientos_activos->num_rows < 1 ){
            desactivarVagon($vagon);
        }
        return true;
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

    function getAsientoPorCodigo($fila, $letra, $vagon){
        $query = "SELECT fila, letra, grupo, tipo, vagon, activo FROM asiento WHERE fila = " . $fila . " AND letra = '" . $letra . "' AND vagon = '" . $vagon . "';";
    }

    function getAsientoPorFila($fila, $vagon, $activo = true){
        if( $activo ){
            $query = "SELECT fila, letra, grupo, tipo, vagon, activo FROM asiento WHERE fila = " . $fila . " AND vagon = '" . $vagon . "' AND activo = 1 ORDER BY letra;";
        } else {
            $query = "SELECT fila, letra, grupo, tipo, vagon, activo FROM asiento WHERE fila = " . $fila . " AND vagon = '" . $vagon . "' ORDER BY letra;";
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

    function getAsientoPorGrupo($grupo, $vagon, $activo = true){
        if( $activo ){
            $query = "SELECT fila, letra, grupo, tipo, vagon, activo FROM asiento WHERE grupo = " . $grupo . " AND vagon = '" . $vagon . "' AND activo = 1 ORDER BY fila, letra;";
        } else {
            $query = "SELECT fila, letra, grupo, tipo, vagon, activo FROM asiento WHERE grupo = " . $grupo . " AND vagon = '" . $vagon . "' ORDER BY fila, letra;";
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
            //Obtener vagones activos del tren
            $result_vagones = getVagonPorTren($tren);
            $vagones = array();
            while( $row_vagones = $result_vagones->fetch_assoc() ){
                array_push($vagones, $row_vagones["codigo"]);
            }
            //Obtener asientos activos de los vagones
            $asientos = array();
            foreach( $vagones as $vagon ){
                $result_asientos = getAsientoPorVagon($vagon);
                while( $row_asientos = $result_asientos->fetch_assoc() ){
                    array_push($asientos, $row_asientos);
                }
            }
        } else {
            //Obtener vagones del tren
            $result_vagones = getVagonPorTren($tren, false);
            $vagones = array();
            while( $row_vagones = $result_vagones->fetch_assoc() ){
                array_push($vagones, $row_vagones["codigo"]);
            }
            //Obtener asientos de los vagones
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

    function getGruposPorVagon($vagon, $activo = false){
        if( $activo ){
            $query = "SELECT grupo FROM asiento WHERE vagon = " . $vagon . " AND activo = 1 GROUP BY grupo ORDER BY grupo;";
        } else {
            $query = "SELECT grupo FROM asiento WHERE vagon = " . $vagon . " GROUP BY grupo ORDER BY grupo;";
        }
        return consultarBBDD($query);
    }

?>