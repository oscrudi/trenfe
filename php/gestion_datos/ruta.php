<?php

    function addRuta($codigo, $codigos_estacion, $tiempos_llegada){
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

    function addEstacionARuta($ruta, $estacion, $orden, $tiempo_llegada){
        $result = getEstacionesPorRuta($ruta);
        if( !$result ){
            return false;
        }
        $estaciones_ruta = array();
        while( $row = $result->fetch_assoc() ){
            array_push($estaciones_ruta, $row);
        }
        if( !$estaciones_ruta[$orden - 1] ){
            //TODO:Insertar nueva estación
            return true;
        }
        for( $i = ($orden - 1); $i < count($estaciones_ruta); $i++ ){
            //TODO:Modificar orden de estación
        }
        //TODO:Insertar nueva estación
        return true;
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
            $output .= "Código: " . $row["codigo"] . " - Activo: " . $row["activo"] . " - Código Tipo: " . $row["codigo_tipo"] . " - Tipo: " . $row["tipo"] . " - Código Estación: " . $row["codigo_estacion"] . " - Estación: " . $row["estacion"] . " - Orden: " . $row["orden"] . " - Tiempo llegada: " . $row["tiempo_llegada"] . "<br>";
        }
        return $output;
    }

    function getAllRuta(){
        $query = "SELECT r.codigo AS codigo, r.activo AS activo, r.tipo AS codigo_tipo, tr.nombre AS tipo FROM ruta AS r INNER JOIN tipo_ruta AS tr ON r.tipo = tr.codigo ORDER BY r.codigo;";
        return consultarBBDD($query);
    }

    function getEstacionesPorRuta($ruta){
        $query = "SELECT r.codigo AS codigo, r.activo AS activo, r.tipo AS codigo_tipo, tr.nombre AS tipo, re.estacion AS codigo_estacion, re.orden AS orden, re.tiempo_llegada AS tiempo_llegada, e.nombre AS estacion FROM ruta AS r INNER JOIN tipo_ruta AS tr ON r.tipo = tr.codigo INNER JOIN ruta_estacion AS re ON r.codigo = re.ruta INNER JOIN estacion AS e ON re.estacion = e.codigo WHERE r.codigo = '" . $ruta . "' ORDER BY re.orden;";
        return consultarBBDD($query);
    }

?>