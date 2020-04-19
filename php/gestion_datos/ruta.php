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
        $query = "DELETE FROM ruta WHERE codigo = " . $codigo . ";";
        return modificarBBDD($query);
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
        // Si no hay otra estación en ese orden, se añade.
        if( !array_key_exists(($orden - 1), $estaciones_ruta) ){
            updateTipoRuta($ruta, $nuevo_tipo_ruta);
            return modificarBBDD($queryAddEstacion);
        }
        // Si hay otra estación en ese orden, se modifica el orden y luego se añade
        for( $i = (count($estaciones_ruta) - 1); $i > ($orden - 2); $i-- ){
            $nuevo_orden = ($estaciones_ruta[$i]["orden"] + 1);
            $queryUpdateOrden = "UPDATE ruta_estacion SET orden = " . $nuevo_orden . " WHERE ruta = '" . $estaciones_ruta[$i]["codigo_ruta"] . "' AND estacion = " . $estaciones_ruta[$i]["codigo_estacion"] . " AND orden = " . $estaciones_ruta[$i]["orden"] . ";";
            modificarBBDD($queryUpdateOrden);
        }
        updateTipoRuta($ruta, $nuevo_tipo_ruta);
        return modificarBBDD($queryAddEstacion);
    }

    function deleteEstacionFromRuta($ruta, $estacion, $orden){
        $result = getEstacionesPorRuta($ruta);
        if( !$result || $result->num_rows < 3 ){
            return false;
        }
        $estaciones_ruta = array();
        while( $row = $result->fetch_assoc() ){
            array_push($estaciones_ruta, $row);
        }
        $ultima_estacion = 0;
        foreach( $estaciones_ruta as $estacion_ruta ){
            $ultima_estacion = ($estacion_ruta["orden"] > $ultima_estacion ? $estacion_ruta["orden"] : $ultima_estacion);
        }
        $query = "DELETE FROM ruta_estacion WHERE ruta = '" . $ruta . "' AND estacion = " . $estacion . " AND orden = " . $orden . ";";
        modificarBBDD($query);
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
        if( $orden <= $ultima_estacion ){
            for( $i = ($orden - 1); $i < count($estaciones_ruta); $i++ ){
                $nuevo_orden = ($estaciones_ruta[$i]["orden"] - 1);
                $queryUpdateOrden = "UPDATE ruta_estacion SET orden = " . $nuevo_orden . " WHERE ruta = '" . $estaciones_ruta[$i]["codigo_ruta"] . "' AND estacion = " . $estaciones_ruta[$i]["codigo_estacion"] . " AND orden = " . $estaciones_ruta[$i]["orden"] . ";";
                modificarBBDD($queryUpdateOrden);
            }
        }
        return true;
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
            $output .= "Código: " . $row["codigo_ruta"] . " - Activo: " . $row["activo"] . " - Código Tipo: " . $row["codigo_tipo"] . " - Tipo: " . $row["tipo"] . " - Código Estación: " . $row["codigo_estacion"] . " - Estación: " . $row["estacion"] . " - Orden: " . $row["orden"] . " - Tiempo llegada: " . $row["tiempo_llegada"] . "<br>";
        }
        return $output;
    }

    function getAllRuta(){
        $query = "SELECT r.codigo AS codigo, r.activo AS activo, r.tipo AS codigo_tipo, tr.nombre AS tipo FROM ruta AS r INNER JOIN tipo_ruta AS tr ON r.tipo = tr.codigo ORDER BY r.codigo;";
        return consultarBBDD($query);
    }

    function getEstacionesPorRuta($ruta){
        $query = "SELECT r.codigo AS codigo_ruta, r.activo AS activo, r.tipo AS codigo_tipo, tr.nombre AS tipo, re.estacion AS codigo_estacion, re.orden AS orden, re.tiempo_llegada AS tiempo_llegada, e.nombre AS estacion FROM ruta AS r INNER JOIN tipo_ruta AS tr ON r.tipo = tr.codigo INNER JOIN ruta_estacion AS re ON r.codigo = re.ruta INNER JOIN estacion AS e ON re.estacion = e.codigo WHERE r.codigo = '" . $ruta . "' ORDER BY re.orden;";
        return consultarBBDD($query);
    }

?>