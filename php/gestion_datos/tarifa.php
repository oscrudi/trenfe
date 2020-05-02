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

    function calcularActivoTarifa($codigo){
        $result = getTarifaPorCodigo($codigo);
        $tarifa = $result->fetch_assoc();
        $fecha_actual = strtotime(date('Y/m/d', time()));
        if( strtotime($tarifa["fecha_inicio"]) > $fecha_actual || strtotime($tarifa["fecha_fin"]) < $fecha_actual ){
            return false;
        } elseif( $tarifa["activo_lunes"] == true ){
            return true;
        } elseif( $tarifa["activo_martes"] == true ){
            return true;
        } elseif( $tarifa["activo_miercoles"] == true ){
            return true;
        } elseif( $tarifa["activo_jueves"] == true ){
            return true;
        } elseif( $tarifa["activo_viernes"] == true ){
            return true;
        } elseif( $tarifa["activo_sabado"] == true ){
            return true;
        } elseif( $tarifa["activo_domingo"] == true ){
            return true;
        }
        return false;
    }

    function getTarifasActivas(){
        $result = getAllTarifa();
        $tarifas = array();
        while( $row = $result->fetch_assoc() ){
            if( calcularActivoTarifa($row["codigo"]) ){
                array_push($tarifas, $row);
            }
        }
        return $tarifas;
    }

    function getTarifasPorRequisitos($dia = null, $tramo_horario = null, $tipo_ruta = null, $tipo_tren = null, $tipo_vagon = null, $tipo_asiento = null){
        // Obtener tarifas activas
        $tarifas_activas = getTarifasActivas();
        // Obtener las tarifas activas que cumplan los requisitos
        $tarifas = array();
        foreach( $tarifas_activas as $tarifa ){
            if( $tarifa["tramo_horario"] == $tramo_horario || $tarifa["tramo_horario"] == null ){
                if( $tarifa["tipo_ruta"] == $tipo_ruta || $tarifa["tipo_ruta"] == null ){
                    if( $tarifa["tipo_tren"] == $tipo_tren || $tarifa["tipo_tren"] == null ){
                        if( $tarifa["tipo_vagon"] == $tipo_vagon || $tarifa["tipo_vagon"] == null ){
                            if( $tarifa["tipo_asiento"] == $tipo_asiento || $tarifa["tipo_asiento"] == null ){
                                array_push($tarifas, $tarifa);
                            }
                        }
                    }
                }
            }
        }
        // Obtener las tarifas que estén activas el día indicado
        if( $dia != null ){
            $dia_activo = "";
            switch ($dia) {
                case 1:
                    $dia_activo = "activo_lunes";
                    break;
                case 2:
                    $dia_activo = "activo_martes";
                    break;
                case 3:
                    $dia_activo = "activo_miercoles";
                    break;
                case 4:
                    $dia_activo = "activo_jueves";
                    break;
                case 5:
                    $dia_activo = "activo_viernes";
                    break;
                case 6:
                    $dia_activo = "activo_sabado";
                    break;
                case 7:
                    $dia_activo = "activo_domingo";
                    break;
            }
            $tarifas_dia = array();
            foreach( $tarifas as $tarifa ){
                if( $tarifa[$dia_activo] == true ){
                    array_push($tarifas_dia, $tarifa);
                }
            }
            if( count($tarifas_dia) > 0 ){
                return $tarifas_dia;
            }
            return false;
        }
        if( count($tarifas) > 0 ){
            return $tarifas;
        }
        return false;
    }

    function getTarifaMasCara($tarifas, $num_paradas){
        if( count($tarifas) == 0 ){
            return false;
        }
        $tarifa_mas_cara = $tarifas[0];
        foreach( $tarifas as $tarifa ){
            $precio_mas_caro = $tarifa_mas_cara["precio_base"] + ( $tarifa_mas_cara["incremento_precio"] * $num_paradas );
            $precio_nuevo = $tarifa["precio_base"] + ( $tarifa["incremento_precio"] * $num_paradas );
            if( $precio_mas_caro < $precio_nuevo ){
                $tarifa_mas_cara = $tarifa;
            }
        }
        return $tarifa_mas_cara;
    }

    function printTarifa($result) {
        $output = "";
        while( $row = $result->fetch_assoc() ){
            $output .= "Código: " . $row["codigo"] . " - Nombre: " . $row["nombre"] . " - Fecha Inicio: " . $row["fecha_inicio"] . " - Fecha Fin: " . $row["fecha_fin"] . " - Precio Base: " . $row["precio_base"] . " - Incremento Precio: " . $row["incremento_precio"] . " - Tramo Horario: " . $row["tramo_horario"] . " - Tipo Ruta: " . $row["tipo_ruta"] . " - Tipo Tren: " . $row["tipo_tren"] . " - Tipo Vagón: " . $row["tipo_vagon"] . " - Tipo Asiento: " . $row["tipo_asiento"] . " - Activo Lunes: " . $row["activo_lunes"] . " - Activo Martes: " . $row["activo_martes"] . " - Activo Miércoles: " . $row["activo_miercoles"] . " - Activo Jueves: " . $row["activo_jueves"] . " - Activo Viernes: " . $row["activo_viernes"] . " - Activo Sábado: " . $row["activo_sabado"] . " - Activo Domingo: " . $row["activo_domingo"] . "<br>";
        }
        return $output;
    }

    function getAllTarifa(){
        $query = "SELECT * FROM tarifa ORDER BY codigo;";
        return consultarBBDD($query);
    }

    function getTarifaPorCodigo($codigo){
        $query = "SELECT * FROM tarifa WHERE codigo = '" . $codigo . "';";
        return consultarBBDD($query);
    }

?>