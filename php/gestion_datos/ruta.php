<?php

    function addRuta($codigo, $codigos_estacion, $tiempos_llegada){
        if( !$codigos_estacion || count($codigos_estacion) < 2 ){
            return false;
        }
        $tipo_ruta = calcularTipoRuta($codigos_estacion);
        $queryRuta = "INSERT INTO ruta (codigo, activo, tipo) VALUES ('" . $codigo . "',1 ," . $tipo_ruta . ");";
        $correcto = modificarBBDD($queryRuta);
        if( !$correcto ){
            return false;
        }
        for( $i = 0; $i < count($codigos_estacion); $i++ ){
            $queryRutaEstacion = "INSERT INTO ruta_estacion (ruta, estacion, orden, tiempo_llegada) VALUES ('" . $codigo . "'," . $codigos_estacion[$i] . " ," . ($i + 1) . " ," . $tiempos_llegada[$i] . " );";
            $correcto = modificarBBDD($queryRutaEstacion);
            if( !$correcto ){
                return false;
            }
        }
        return true;
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

    function activarRuta($codigo){
        $query = "UPDATE ruta SET activo = 1 WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function desactivarRuta($codigo){
        //TODO: desactivar líneas que contengan esta ruta.
        $query = "UPDATE ruta SET activo = 0 WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function addEstacionToRuta($ruta, $estacion, $orden, $tiempo_llegada){
        $result = getEstacionesPorRuta($ruta);
        if( !$result ){
            return false;
        }
        $estaciones_ruta = array();
        while( $row = $result->fetch_assoc() ){
            array_push($estaciones_ruta, $row);
        }
        $codigos_estacion = array();
        foreach( $estaciones_ruta as $estacion_ruta ){
            array_push($codigos_estacion, $estacion_ruta["codigo_estacion"]);
        }
        array_push($codigos_estacion, $estacion);
        $nuevo_tipo_ruta = calcularTipoRuta($codigos_estacion);
        $queryAddEstacion = "INSERT INTO ruta_estacion (ruta, estacion, orden, tiempo_llegada) VALUES ('" . $ruta . "', " . $estacion . ", " . $orden . ", " . $tiempo_llegada . ");";
        updateTipoRuta($ruta, $nuevo_tipo_ruta);
        // Si no hay otra estación en ese orden, se añade.
        if( !array_key_exists(($orden - 1), $estaciones_ruta) ){
            return modificarBBDD($queryAddEstacion);
        }
        // Si hay otra estación en ese orden, se modifica el orden y luego se añade
        for( $i = (count($estaciones_ruta) - 1); $i > ($orden - 2); $i-- ){
            $nuevo_orden = ($estaciones_ruta[$i]["orden"] + 1);
            $queryUpdateOrden = "UPDATE ruta_estacion SET orden = " . $nuevo_orden . " WHERE ruta = '" . $estaciones_ruta[$i]["codigo_ruta"] . "' AND orden = " . $estaciones_ruta[$i]["orden"] . ";";
            modificarBBDD($queryUpdateOrden);
        }
        return modificarBBDD($queryAddEstacion);
    }

    function deleteEstacionFromRuta($ruta, $orden){
        // Obtener estaciones de la ruta
        $result = getEstacionesPorRuta($ruta);
        if( !$result || $result->num_rows < 3 ){
            return false;
        }
        $estaciones_ruta = array();
        while( $row = $result->fetch_assoc() ){
            array_push($estaciones_ruta, $row);
        }
        // Obtener última estación
        $ultima_estacion = 0;
        foreach( $estaciones_ruta as $estacion_ruta ){
            $ultima_estacion = ($estacion_ruta["orden"] > $ultima_estacion ? $estacion_ruta["orden"] : $ultima_estacion);
        }
        // Borrar estación
        $query = "DELETE FROM ruta_estacion WHERE ruta = '" . $ruta . "' AND orden = " . $orden . ";";
        modificarBBDD($query);
        // Calcular nuevo tipo de ruta
        $result = getEstacionesPorRuta($ruta);
        $estaciones_ruta = array();
        while( $row = $result->fetch_assoc() ){
            array_push($estaciones_ruta, $row);
        }
        $codigos_estacion = array();
        foreach( $estaciones_ruta as $estacion_ruta ){
            array_push($codigos_estacion, $estacion_ruta["codigo_estacion"]);
        }
        $nuevo_tipo_ruta = calcularTipoRuta($codigos_estacion);
        updateTipoRuta($ruta, $nuevo_tipo_ruta);
        // Si la estación borrada no era la última, modificar el orden de las estaciones
        if( $orden <= $ultima_estacion ){
            for( $i = ($orden - 1); $i < count($estaciones_ruta); $i++ ){
                $nuevo_orden = ($estaciones_ruta[$i]["orden"] - 1);
                $queryUpdateOrden = "UPDATE ruta_estacion SET orden = " . $nuevo_orden . " WHERE ruta = '" . $estaciones_ruta[$i]["codigo_ruta"] . "' AND orden = " . $estaciones_ruta[$i]["orden"] . ";";
                modificarBBDD($queryUpdateOrden);
            }
        }
        return true;
    }

    function calcularDuracionRuta($ruta, $orden_origen = false, $orden_destino = false){
        $result = getEstacionesPorOrigenDestino($ruta, $orden_origen, $orden_destino);
        $duracion = 0;
        while( $row = $result->fetch_assoc() ){
            $duracion += $row["tiempo_llegada"];
        }
        return $duracion;
    }

    function updateTipoRuta($ruta, $nuevo_tipo_ruta){
        $query = "UPDATE ruta SET tipo = " . $nuevo_tipo_ruta . " WHERE codigo = '" . $ruta . "';";
        return modificarBBDD($query);
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

    function getRutasPorEstacion($estacion){
        $query = "SELECT r.codigo AS codigo, r.activo AS activo, r.tipo AS codigo_tipo, tr.nombre AS tipo FROM ruta AS r INNER JOIN tipo_ruta AS tr ON r.tipo = tr.codigo INNER JOIN ruta_estacion AS re ON r.codigo = re.ruta INNER JOIN estacion AS e ON re.estacion = e.codigo WHERE re.estacion = '" . $estacion . "' GROUP BY r.codigo ORDER BY r.codigo;";
        return consultarBBDD($query);
    }

    function getEstacionesPorRuta($ruta, $activos = true){
        if( $activos ){
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