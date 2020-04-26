<?php

    function addVagon($codigo, $tipo, $tren){
        //Comprobar que el tren no supere el máximo de vagones permitidos por su tipo
        $correcto = checkVagonesMax($tren, false, true);
        if( !$correcto ){
            return false;
        }
        $query = "INSERT INTO vagon (codigo, activo, tipo, tren) VALUES ('" . $codigo . "', 0, " . $tipo . ", '" . $tren . "');";
        return modificarBBDD($query);
    }

    function deleteVagon($codigo){
        //Borrar asientos del vagón
        $result = getAsientoPorVagon($codigo, false);
        while( $row = $result->fetch_assoc() ){
            deleteAsiento($row["fila"], $row["letra"], $row["vagon"]);
        }
        //Borrar vagón
        $query = "DELETE FROM vagon WHERE codigo = '" . $codigo . "';";
        modificarBBDD($query);
        //Obtener el tren del vagón
        $result = getVagonPorCodigo($codigo);
        $tren = $result->fetch_assoc()["tren"];
        //Obtener los vagones activos del tren
        $vagones_activos = getVagonPorTren($tren);
        if( $vagones_activos->num_rows < 1 ){
            desactivarTren($tren);
        }
        return true;
    }

    function updateTipoVagon($codigo, $tipo){
        //Comprobar que el número de asientos no supera el máximo del nuevo tipo de vagón
        $correcto = checkAsientosMax($codigo, $tipo);
        if( !$correcto ){
            return false;
        }
        $query = "UPDATE vagon SET tipo = " . $tipo . " WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function updateTrenVagon($codigo, $tren){
        //Comprobar que el nuevo tren no supere el máximo de vagones permitidos por su tipo
        $correcto = checkVagonesMax($tren, false, true);
        if( !$correcto ){
            return false;
        }
        $query = "UPDATE vagon SET tren = " . $tren . " WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function activarVagon($codigo){
        $query = "UPDATE vagon SET activo = 1 WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function desactivarVagon($codigo){
        //Desactivar vagón
        $query = "UPDATE vagon SET activo = 0 WHERE codigo = '" . $codigo . "';";
        $correcto = modificarBBDD($query);
        if( !$correcto ){
            return false;
        }
        //Obtener el tren del vagón
        $result = getVagonPorCodigo($codigo);
        $tren = $result->fetch_assoc()["tren"];
        //Obtener los vagones activos del tren
        $vagones_activos = getVagonPorTren($tren);
        if( $vagones_activos->num_rows < 1 ){
            desactivarTren($tren);
        }
        return true;
    }

    function checkAsientosMax($vagon, $tipo_nuevo = false, $asiento_extra = false){
        if( !$tipo_nuevo ){
            //Obtener tipo actual del vagón
            $tipo_vagon = false;
            $result = getVagonPorCodigo($vagon);
            $tipo_vagon = $result->fetch_assoc()["tipo"];
            if( !$tipo_vagon ){
                return false;
            }
        } else {
            $tipo_vagon = $tipo_nuevo;
        }
        //Obtener asientos máximos del tipo de vagón
        $asientos_max = false;
        $result = getTipoVagonPorCodigo($tipo_vagon);
        $asientos_max = $result->fetch_assoc()["asientos_max"];
        if( !$asientos_max ){
            return false;
        }
        //Obtener asientos del vagón
        $result = getAsientoPorVagon($vagon, false);
        $asientos = 0;
        if( $result != false ){
            $asientos = $result->num_rows;
        }
        if( $asiento_extra ){
            $asientos++;
        }
        if( $asientos_max < $asientos ){
            return false;
        }
        return true;
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