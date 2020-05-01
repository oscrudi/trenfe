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
            $query = "UPDATE linea SET activo_lunes = '" . $dias_activos[0] . "', activo_martes = '" . $dias_activos[1] . "', activo_miercoles = '" . $dias_activos[2] . "', activo_jueves = '" . $dias_activos[3] . "', activo_viernes = '" . $dias_activos[4] . "', activo_sabado = '" . $dias_activos[5] . "', activo_domingo = '" . $dias_activos[6] . "' WHERE codigo = '" . $codigo . "';";
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

    function calcularHoraLlegadaPorEstacion($codigo_linea, $codigo_estacion){
        $result = getLineaPorCodigo($codigo_linea);
        $linea = $result->fetch_assoc();
        $ruta = $linea["ruta"];
        $result = getEstacionesPorRuta($ruta);
        $ordenes_estacion = array();
        while( $row = $result->fetch_assoc() ){
            if( $row["codigo_estacion"] == $codigo_estacion ){
                array_push($ordenes_estacion, $row["orden"]);
            }
        }
        $hora_salida = $linea["hora_salida"];
        $horas_llegada = array();
        foreach( $ordenes_estacion as $orden_estacion ){
            $tiempo_llegada = calcularDuracionRuta($ruta, false, $orden_estacion);
            $hora_llegada = date("H:i:s", strtotime("+".$tiempo_llegada." minutes", strtotime($hora_salida)));
            array_push($horas_llegada, $hora_llegada);
        }
        return $horas_llegada;
    }

?>