<?php

    function addRuta($codigo){
        $query = "INSERT INTO ruta (codigo, activo, tipo) VALUES ('" . $codigo . "', 0, null);";
        return modificarBBDD($query);
    }

    function deleteRuta($codigo){
        $queryDeleteRutaEstacion = "DELETE FROM ruta_estacion WHERE ruta = '" . $codigo . "';";
        $correcto = modificarBBDD($queryDeleteRutaEstacion);
        if( !$correcto ){
            return false;
        }
        $queryDeleteRuta = "DELETE FROM ruta WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($queryDeleteRuta);
    }

    function activarRuta($codigo){
        $estaciones_activas = getEstacionesPorRuta($codigo);
        if( $estaciones_activas->num_rows < 2 ){
            return false;
        }
        $query = "UPDATE ruta SET activo = 1 WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function desactivarRuta($codigo){
        //TODO: desactivar líneas que contengan esta ruta.
        $query = "UPDATE ruta SET activo = 0 WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function updateTipoRuta($codigo){
        $estaciones = getEstacionesPorRuta($codigo, false);
        $nuevo_tipo_ruta = null;
        if( $estaciones ){
            $codigos_estacion = array();
            while( $row = $estaciones->fetch_assoc() ){
                array_push($codigos_estacion, $row["codigo_estacion"]);
            }
            if( count($codigos_estacion) > 0 ){
                $nuevo_tipo_ruta = calcularTipoRuta($codigos_estacion);
            }
        }
        $query = "UPDATE ruta SET tipo = " . $nuevo_tipo_ruta . " WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function addEstacionToRuta($ruta, $estacion, $orden, $tiempo_llegada){
        //Se obtiene la última estación
        $ultima_estacion = calcularUltimaEstacionRuta($ruta);
        //Si la nueva estación va después de la última se añade y se actualiza el tipo de ruta
        if( $orden > $ultima_estacion ){
            $orden = ($ultima_estacion + 1);
            $query = "INSERT INTO ruta_estacion (ruta, estacion, orden, tiempo_llegada) VALUES ('" . $ruta . "', " . $estacion . ", " . $orden . ", " . $tiempo_llegada . ");";
            $correcto = modificarBBDD($query);
            if( !$correcto ){
                return false;
            }
            return updateTipoRuta($ruta);
        }
        //Si la nueva estación va antes de la última se modifica el orden de las existentes, se añade la nueva y se actualiza el tipo de ruta
        $result = getEstacionesPorRuta($ruta, false);
        if( !$result ){
            return false;
        }
        $estaciones_ruta = array();
        while( $row = $result->fetch_assoc() ){
            array_push($estaciones_ruta, $row);
        }
        for( $i = (count($estaciones_ruta) - 1); $i > ($orden - 2); $i-- ){
            $nuevo_orden = ($estaciones_ruta[$i]["orden"] + 1);
            $queryUpdateOrden = "UPDATE ruta_estacion SET orden = " . $nuevo_orden . " WHERE ruta = '" . $estaciones_ruta[$i]["codigo_ruta"] . "' AND orden = " . $estaciones_ruta[$i]["orden"] . ";";
            modificarBBDD($queryUpdateOrden);
        }
        $correcto = modificarBBDD($queryAddEstacion);
        if( !$correcto ){
            return false;
        }
        return updateTipoRuta($ruta);
    }

    function deleteEstacionFromRuta($ruta, $orden){
        //Se obtiene la última estación
        $ultima_estacion = calcularUltimaEstacionRuta($ruta);
        //Se elimina la estación
        $query = "DELETE FROM ruta_estacion WHERE ruta = '" . $ruta . "' AND orden = " . $orden . ";";
        $correcto = modificarBBDD($query);
        if( !$correcto ){
            return false;
        }
        //Si la ruta no tiene suficientes estaciones activas, se desactiva
        $estaciones_activas = getEstacionesPorRuta($ruta);
        if( !$estaciones_activas || $estaciones_activas->num_rows < 2 ){
            desactivarRuta($ruta);
        }
        //Se actualiza el tipo de ruta
        updateTipoRuta($ruta);
        //Si la estación eliminada era la última, se acaba
        if( $orden == $ultima_estacion ){
            return true;
        }
        //Si la estación no era la última, se actualiza el orden de las siguientes
        $result = getEstacionesPorRuta($ruta, false);
        $estaciones_ruta = array();
        while( $row = $result->fetch_assoc() ){
            array_push($estaciones_ruta, $row);
        }
        for( $i = ($orden - 1); $i < count($estaciones_ruta); $i++ ){
            $nuevo_orden = ($estaciones_ruta[$i]["orden"] - 1);
            $queryUpdateOrden = "UPDATE ruta_estacion SET orden = " . $nuevo_orden . " WHERE ruta = '" . $estaciones_ruta[$i]["codigo_ruta"] . "' AND orden = " . $estaciones_ruta[$i]["orden"] . ";";
            modificarBBDD($queryUpdateOrden);
        }
        return true;
    }

    function calcularTipoRuta($codigos_estacion){
        $codigos_estacion = array_unique($codigos_estacion);
        $geografia_estaciones = array();
        foreach( $codigos_estacion as $codigo_estacion ){
            $result = getEstacionPorCodigo($codigo_estacion);
            while( $row = $result->fetch_assoc() ){
                array_push($geografia_estaciones, $row);
            }
        }
        $localidad = $geografia_estaciones[0]["codigo_localidad"];
        foreach( $geografia_estaciones as $geografia_estacion ){
            if( $geografia_estacion["codigo_localidad"] != $localidad ){
                $localidad = false;
                break;
            }
        }
        if( $localidad ){
            return 1;
        }
        $provincia = $geografia_estaciones[0]["codigo_provincia"];
        foreach( $geografia_estaciones as $geografia_estacion ){
            if( $geografia_estacion["codigo_provincia"] != $provincia ){
                $provincia = false;
                break;
            }
        }
        if( $provincia ){
            return 2;
        }
        $region = $geografia_estaciones[0]["codigo_region"];
        foreach( $geografia_estaciones as $geografia_estacion ){
            if( $geografia_estacion["codigo_region"] != $region ){
                $region = false;
                break;
            }
        }
        if( $region ){
            return 3;
        }
        $pais = $geografia_estaciones[0]["codigo_pais"];
        foreach( $geografia_estaciones as $geografia_estacion ){
            if( $geografia_estacion["codigo_pais"] != $pais ){
                $pais = false;
                break;
            }
        }
        if( $pais ){
            return 4;
        }
        return 5;
    }

    function calcularDuracionRuta($ruta, $orden_origen = false, $orden_destino = false){
        $result = getEstacionesPorOrigenDestino($ruta, $orden_origen, $orden_destino);
        $duracion = 0;
        while( $row = $result->fetch_assoc() ){
            $duracion += $row["tiempo_llegada"];
        }
        return $duracion;
    }

    function calcularUltimaEstacionRuta($ruta, $activo = false){
        if( $activo ){
            $query = "SELECT re.orden AS orden FROM ruta_estacion AS re INNER JOIN estacion AS e ON re.estacion = e.codigo WHERE re.ruta = '" . $ruta . "' AND e.activo = 1 ORDER BY re.orden DESC LIMIT 1;";
        } else {
            $query = "SELECT orden FROM ruta_estacion WHERE ruta = '" . $ruta . "' ORDER BY orden DESC LIMIT 1;";
        }
        $result = consultarBBDD($query);
        if( !$result ){
            return false;
        }
        $ultima_estacion = false;
        while( $row = $result->fetch_assoc() ){
            $ultima_estacion = $row["orden"];
        }
        return $ultima_estacion;
    }

    function printRuta($result) {
        $output = "";
        while( $row = $result->fetch_assoc() ){
            $output .= "Código: " . $row["codigo"] . " - Activo: " . $row["activo"] . " - Código Tipo: " . $row["codigo_tipo"] . " - Tipo: " . $row["tipo"] . "<br>";
        }
        return $output;
    }

    function printRutaEstaciones($result) {
        $output = "";
        while( $row = $result->fetch_assoc() ){
            $output .= "Código Estación: " . $row["codigo_estacion"] . " - Estación: " . $row["estacion"] . " - Orden: " . $row["orden"] . " - Tiempo llegada: " . $row["tiempo_llegada"] . " - Estación Activo: " . $row["estacion_activo"] . "<br>";
        }
        return $output;
    }

    function getAllRuta($activo = true){
        if( $activo ){
            $query = "SELECT r.codigo AS codigo, r.activo AS activo, r.tipo AS codigo_tipo, tr.nombre AS tipo FROM ruta AS r INNER JOIN tipo_ruta AS tr ON r.tipo = tr.codigo WHERE r.activo = 1 ORDER BY r.codigo;";
        } else {
            $query = "SELECT r.codigo AS codigo, r.activo AS activo, r.tipo AS codigo_tipo, tr.nombre AS tipo FROM ruta AS r INNER JOIN tipo_ruta AS tr ON r.tipo = tr.codigo ORDER BY r.codigo;";
        }
        return consultarBBDD($query);
    }

    function getRutaPorCodigo($codigo){
        $query = "SELECT r.codigo AS codigo, r.activo AS activo, r.tipo AS codigo_tipo, tr.nombre AS tipo FROM ruta AS r INNER JOIN tipo_ruta AS tr ON r.tipo = tr.codigo WHERE r.codigo = '" . $codigo . "';";
        return consultarBBDD($query);
    }

    function getRutasPorTipo($tipo_ruta, $activo = true){
        if( $activo ){
            $query = "SELECT r.codigo AS codigo, r.activo AS activo, r.tipo AS codigo_tipo, tr.nombre AS tipo FROM ruta AS r INNER JOIN tipo_ruta AS tr ON r.tipo = tr.codigo WHERE tr.codigo = " . $tipo_ruta . " AND r.activo = 1 ORDER BY r.codigo;";
        } else {
            $query = "SELECT r.codigo AS codigo, r.activo AS activo, r.tipo AS codigo_tipo, tr.nombre AS tipo FROM ruta AS r INNER JOIN tipo_ruta AS tr ON r.tipo = tr.codigo WHERE tr.codigo = " . $tipo_ruta . " ORDER BY r.codigo;";
        }
        return consultarBBDD($query);
    }

    function getRutasPorEstacion($estacion, $activo = true){
        if( $activo ){
            $query = "SELECT r.codigo AS codigo, r.activo AS activo, r.tipo AS codigo_tipo, tr.nombre AS tipo FROM ruta AS r INNER JOIN tipo_ruta AS tr ON r.tipo = tr.codigo INNER JOIN ruta_estacion AS re ON r.codigo = re.ruta INNER JOIN estacion AS e ON re.estacion = e.codigo WHERE re.estacion = '" . $estacion . "' AND r.activo = 1 GROUP BY r.codigo ORDER BY r.codigo;";
        } else {
            $query = "SELECT r.codigo AS codigo, r.activo AS activo, r.tipo AS codigo_tipo, tr.nombre AS tipo FROM ruta AS r INNER JOIN tipo_ruta AS tr ON r.tipo = tr.codigo INNER JOIN ruta_estacion AS re ON r.codigo = re.ruta INNER JOIN estacion AS e ON re.estacion = e.codigo WHERE re.estacion = '" . $estacion . "' GROUP BY r.codigo ORDER BY r.codigo;";
        }
        return consultarBBDD($query);
    }

    function getEstacionesPorRuta($ruta, $activo = true){
        if( $activo ){
            $query = "SELECT re.estacion AS codigo_estacion, re.orden AS orden, re.tiempo_llegada AS tiempo_llegada, e.nombre AS estacion, e.activo AS estacion_activo FROM ruta AS r INNER JOIN tipo_ruta AS tr ON r.tipo = tr.codigo INNER JOIN ruta_estacion AS re ON r.codigo = re.ruta INNER JOIN estacion AS e ON re.estacion = e.codigo WHERE r.codigo = '" . $ruta . "' AND e.activo = 1 ORDER BY re.orden;";
        } else {
            $query = "SELECT re.estacion AS codigo_estacion, re.orden AS orden, re.tiempo_llegada AS tiempo_llegada, e.nombre AS estacion, e.activo AS estacion_activo FROM ruta AS r INNER JOIN tipo_ruta AS tr ON r.tipo = tr.codigo INNER JOIN ruta_estacion AS re ON r.codigo = re.ruta INNER JOIN estacion AS e ON re.estacion = e.codigo WHERE r.codigo = '" . $ruta . "' ORDER BY re.orden;";
        }
        return consultarBBDD($query);
    }

    function getEstacionesPorOrigenDestino($ruta, $orden_origen = false, $orden_destino = false){
        if( $orden_origen && $orden_origen > 0 && $orden_destino && $orden_destino > 1 ){
            $query = "SELECT re.estacion AS codigo_estacion, re.orden AS orden, re.tiempo_llegada AS tiempo_llegada, e.nombre AS estacion, e.activo AS estacion_activo FROM ruta AS r INNER JOIN tipo_ruta AS tr ON r.tipo = tr.codigo INNER JOIN ruta_estacion AS re ON r.codigo = re.ruta INNER JOIN estacion AS e ON re.estacion = e.codigo WHERE r.codigo = '" . $ruta . "' AND re.orden >= " . $orden_origen . " AND re.orden <= " . $orden_destino . " ORDER BY re.orden;";
        } else if( $orden_origen && $orden_origen > 0 ){
            $query = "SELECT re.estacion AS codigo_estacion, re.orden AS orden, re.tiempo_llegada AS tiempo_llegada, e.nombre AS estacion, e.activo AS estacion_activo FROM ruta AS r INNER JOIN tipo_ruta AS tr ON r.tipo = tr.codigo INNER JOIN ruta_estacion AS re ON r.codigo = re.ruta INNER JOIN estacion AS e ON re.estacion = e.codigo WHERE r.codigo = '" . $ruta . "' AND re.orden >= " . $orden_origen . " ORDER BY re.orden;";
        } else if( $orden_destino && $orden_destino > 1 ){
            $query = "SELECT re.estacion AS codigo_estacion, re.orden AS orden, re.tiempo_llegada AS tiempo_llegada, e.nombre AS estacion, e.activo AS estacion_activo FROM ruta AS r INNER JOIN tipo_ruta AS tr ON r.tipo = tr.codigo INNER JOIN ruta_estacion AS re ON r.codigo = re.ruta INNER JOIN estacion AS e ON re.estacion = e.codigo WHERE r.codigo = '" . $ruta . "' AND re.orden <= " . $orden_destino . " ORDER BY re.orden;";
        } else {
            return getEstacionesPorRuta($ruta, false);
        }
        return consultarBBDD($query);
    }

?>