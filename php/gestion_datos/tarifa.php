<?php

    function addTarifa($nombre = false, $fecha_inicio, $fecha_fin, $precio_base, $incremento_precio){
        if( !$nombre ){
            $query = "INSERT INTO tarifa (fecha_inicio, fecha_fin, precio_base, incremento_precio) VALUES (" . $fecha_inicio . ", " . $fecha_fin . ", " . $precio_base . ", " . $incremento_precio . ");";
        } else {
            $query = "INSERT INTO tarifa (nombre, fecha_inicio, fecha_fin, precio_base, incremento_precio) VALUES ('" . $nombre . "', " . $fecha_inicio . ", " . $fecha_fin . ", " . $precio_base . ", " . $incremento_precio . ");";
        }
        return modificarBBDD($query);
    }

    function deleteTarifa($codigo){
        $query = "DELETE FROM tarifa WHERE codigo = '" . $codigo . "';";
    }

    function updateTramoHorarioTarifa($codigo, $tramo_horario = null){
        $query = "UPDATE tarifa SET tramo_horario = " . $tramo_horario . " WHERE codigo = " . $codigo . ";";
        return modificarBBDD($query);
    }

    function updateTipoRutaTarifa($codigo, $tipo_ruta = null){
        $query = "UPDATE tarifa SET tipo_ruta = " . $tipo_ruta . " WHERE codigo = " . $codigo . ";";
        return modificarBBDD($query);
    }

    function updateTipoTrenTarifa($codigo, $tipo_tren = null){
        $query = "UPDATE tarifa SET tipo_tren = " . $tipo_tren . " WHERE codigo = " . $codigo . ";";
        return modificarBBDD($query);
    }

    function updateTipoVagonTarifa($codigo, $tipo_vagon = null){
        $query = "UPDATE tarifa SET tipo_vagon = " . $tipo_vagon . " WHERE codigo = " . $codigo . ";";
        return modificarBBDD($query);
    }

    function updateTipoAsientoTarifa($codigo, $tipo_asiento = null){
        $query = "UPDATE tarifa SET tipo_asiento = " . $tipo_asiento . " WHERE codigo = " . $codigo . ";";
        return modificarBBDD($query);
    }

    function updateFechasTarifa($codigo, $fecha_inicio = false, $fecha_fin = false){
        if( !$fecha_inicio && !$fecha_fin ){
            return false;
        }
        if( !$fecha_inicio ){
            $query = "UPDATE tarifa SET fecha_fin = " . $fecha_fin . " WHERE codigo = " . $codigo . ";";
        } elseif( !$fecha_fin ){
            $query = "UPDATE tarifa SET fecha_inicio = " . $fecha_inicio . " WHERE codigo = " . $codigo . ";";
        } else {
            $query = "UPDATE tarifa SET fecha_inicio = " . $fecha_inicio . ", fecha_fin = " . $fecha_fin . " WHERE codigo = " . $codigo . ";";
        }
        return modificarBBDD($query);
    }

    function updatePrecioBaseTarifa($codigo, $precio_base){
        $query = "UPDATE tarifa SET precio_base = " . $precio_base . " WHERE codigo = " . $codigo . ";";
        return modificarBBDD($query);
    }

    function updateIncrementoPrecioTarifa($codigo, $incremento_precio){
        $query = "UPDATE tarifa SET incremento_precio = " . $incremento_precio . " WHERE codigo = " . $codigo . ";";
        return modificarBBDD($query);
    }

    function updateNombreTarifa($codigo, $nombre = null){
        if( $nombre === null ){
            $query = "UPDATE tarifa SET nombre = null WHERE codigo = " . $codigo . ";";
        } else {
            $query = "UPDATE tarifa SET nombre = " . $nombre . " WHERE codigo = " . $codigo . ";";
        }
        return modificarBBDD($query);
    }

    function desactivarTarifa($codigo){
        $dias_activos = array(false, false, false, false, false, false, false);
        return updateDiasActivosTarifa($codigo, $dias_activos);
    }

    function updateDiasActivosTarifa($codigo, $dias_activos){
        if( is_array($dias_activos) && count($dias_activos) == 7 ){
            $query = "UPDATE tarifa SET activo_lunes = '" . $dias_activos[0] . "', activo_martes = '" . $dias_activos[1] . "', activo_miercoles = '" . $dias_activos[2] . "', activo_jueves = '" . $dias_activos[3] . "', activo_viernes = '" . $dias_activos[4] . "', activo_sabado = '" . $dias_activos[5] . "', activo_domingo = '" . $dias_activos[6] . "' WHERE codigo = '" . $codigo . "';";
            return modificarBBDD($query);
        }
        return false;
    }

    function calcularPrecioTarifa($linea, $fecha_salida, $hora_salida, $estacion_origen, $estacion_destino){
        //TODO: orden o estacion? o directamente numParadas?

    }

?>