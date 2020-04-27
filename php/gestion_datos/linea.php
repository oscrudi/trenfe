<?php

    function addLinea($codigo, $ruta, $tren, $hora_salida){
        $query = "INSERT INTO linea (codigo, ruta, tren, hora_salida) VALUES ('" . $codigo . "', '" . $ruta . "', '" . $tren . "', " . $hora_salida . ");";
        return modificarBBDD($query);
    }

    function deleteLinea($codigo){
        //TODO: comprobar billetes.
        $query = "DELETE FROM linea WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function desactivarLinea($codigo){
        $dias_activos = array(false, false, false, false, false, false, false);
        return updateDiasActivosLinea($codigo, $dias_activos);
    }

    function updateDiasActivosLinea($codigo, $dias_activos){
        //Si la línea está desactivada, comprobar que su ruta y tren estén activos antes de activarla.
        $linea_activa = calcularActivoLinea($codigo);
        if( !$linea_activa ){
            $result = getRutaPorCodigo($ruta);
            $ruta_activa = $result->fetch_assoc()["activo"];
            if( !$ruta_activa ){
                return false;
            }
            $result = getTrenPorCodigo($tren);
            $tren_activo = $result->fetch_assoc()["activo"];
            if( !$tren_activo ){
                return false;
            }
        }
        if( is_array($dias_activos) && count($dias_activos) == 7 ){
            $query = "UPDATE linea SET lunes_activo = '" . $dias_activos[0] . "', martes_activo = '" . $dias_activos[1] . "', miercoles_activo = '" . $dias_activos[2] . "', jueves_activo = '" . $dias_activos[3] . "', viernes_activo = '" . $dias_activos[4] . "', sabado_activo = '" . $dias_activos[5] . "', domingo_activo = '" . $dias_activos[6] . "' WHERE codigo = '" . $codigo . "';";
            return modificarBBDD($query);
        }
        return false;
    }

    function updateRutaLinea($codigo, $ruta){
        $result = getRutaPorCodigo($ruta);
        $ruta_activa = $result->fetch_assoc()["activo"];
        if( !$ruta_activa ){
            desactivarLinea($codigo);
        }
        $query = "UPDATE linea SET ruta = '" . $ruta . "' WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function updateTrenLinea($codigo, $tren){
        $result = getTrenPorCodigo($tren);
        $tren_activo = $result->fetch_assoc()["activo"];
        if( !$tren_activo ){
            desactivarLinea($codigo);
        }
        $query = "UPDATE linea SET tren = '" . $tren . "' WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function updateHoraSalidaLinea($codigo, $hora_salida){
        $query = "UPDATE linea SET hora_salida = " . $hora_salida . " WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function calcularActivoLinea($codigo){
        $result = getLineaPorCodigo($codigo);
        $linea = $result->fetch_assoc();
        if( $linea["activo_lunes"] == true ){
            return true;
        } elseif( $linea["activo_martes"] == true ){
            return true;
        } elseif( $linea["activo_miercoles"] == true ){
            return true;
        } elseif( $linea["activo_jueves"] == true ){
            return true;
        } elseif( $linea["activo_viernes"] == true ){
            return true;
        } elseif( $linea["activo_sabado"] == true ){
            return true;
        } elseif( $linea["activo_domingo"] == true ){
            return true;
        }
        return false;
    }

    function printLinea($result) {
        $output = "";
        while( $row = $result->fetch_assoc() ){
            $output .= "Código: " . $row["codigo"] . " - Ruta: " . $row["ruta"] . " - Tren: " . $row["tren"] . " - Hora Salida: " . $row["hora_salida"] . " - Activo Lunes: " . $row["activo_lunes"] . " - Activo Martes: " . $row["activo_martes"] . " - Activo Miércoles: " . $row["activo_miercoles"] . " - Activo Jueves: " . $row["activo_jueves"] . " - Activo Viernes: " . $row["activo_viernes"] . " - Activo Sábado: " . $row["activo_sabado"] . " - Activo Domingo: " . $row["activo_domingo"] . "<br>";
        }
        return $output;
    }

    function getAllLinea(){
        $query = "SELECT codigo, ruta, tren, hora_salida, activo_lunes, activo_martes, activo_miercoles, activo_jueves, activo_viernes, activo_sabado, activo_domingo FROM linea ORDER BY codigo;";
        return consultarBBDD($query);
    }

    function getLineaPorCodigo($codigo){
        $query = "SELECT codigo, ruta, tren, hora_salida, activo_lunes, activo_martes, activo_miercoles, activo_jueves, activo_viernes, activo_sabado, activo_domingo FROM linea WHERE codigo = '" . $codigo . "';";
        return consultarBBDD($query);
    }

    function getLineaPorRuta($ruta){
        $query = "SELECT codigo, ruta, tren, hora_salida, activo_lunes, activo_martes, activo_miercoles, activo_jueves, activo_viernes, activo_sabado, activo_domingo FROM linea WHERE ruta = '" . $ruta . "';";
        return consultarBBDD($query);
    }

    function getLineaPorTren($tren){
        $query = "SELECT codigo, ruta, tren, hora_salida, activo_lunes, activo_martes, activo_miercoles, activo_jueves, activo_viernes, activo_sabado, activo_domingo FROM linea WHERE tren = '" . $tren . "';";
        return consultarBBDD($query);
    }

    function calcularHoraPorEstacion($linea, $estacion){
        //TODO: obtener hora a la que pasa por la estacion. Puede pasar 2 veces por la misma estación
    }

?>