<?php

    function addEstacion($nombre, $localidad){
        $query = "INSERT INTO estacion (nombre, codigo_localidad, activo) VALUES ('" . $nombre . "', '" . $localidad . "', 1);";
        return modificarBBDD($query);
    }

    function deleteEstacion($codigo){
        //Obtener las rutas que contengan la estación
        $result = getRutasPorEstacion($codigo, false);
        $rutas = array();
        while( $row = $result->fetch_assoc() ){
            array_push($rutas, $row["codigo"]);
        }
        //Borrar la estación de las rutas
        foreach( $rutas as $ruta ){
            $result = getEstacionesPorRuta($ruta);
            while( $row = $result->fetch_assoc() ){
                if( $row["codigo_estacion"] == $codigo ){
                    deleteEstacionFromRuta($ruta, $row["orden"]);
                }
            }
        }
        //Borrar la estación
        $query = "DELETE FROM estacion WHERE codigo = " . $codigo . ";";
        return modificarBBDD($query);
    }

    function updateNombreEstacion($codigo, $nombre){
        $query = "UPDATE estacion SET nombre = '" . $nombre . "' WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function activarEstacion($codigo){
        $query = "UPDATE estacion SET activo = 1 WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function desactivarEstacion($codigo){
        //Desactivar la estación
        $query = "UPDATE estacion SET activo = 0 WHERE codigo = '" . $codigo . "';";
        $correcto = modificarBBDD($query);
        if( !$correcto ){
            return false;
        }
        //Desactivar las rutas que no tengan suficientes estaciones activas
        $result = getRutasPorEstacion($codigo);
        $rutas = array();
        while( $row = $result->fetch_assoc() ){
            array_push($rutas, $row["codigo"]);
        }
        foreach( $rutas as $ruta ){
            $estaciones = getEstacionesPorRuta($ruta);
            if( $estaciones->num_rows < 2 ){
                desactivarRuta($ruta);
            }
        }
    }

    function printEstacion($result) {
        $output = "";
        while( $row = $result->fetch_assoc() ){
            $output .= "Código: " . $row["codigo"] . " - Nombre: " . $row["nombre"] . " - Activo: " . $row["activo"] . " - Código localidad: " . $row["codigo_localidad"] . "<br>";
        }
        return $output;
    }

    function getAllEstacion($activo = true){
        if( $activo ){
            $query = "SELECT codigo, nombre, activo, codigo_localidad FROM estacion WHERE activo = 1 ORDER BY nombre;";
        } else {
            $query = "SELECT codigo, nombre, activo, codigo_localidad FROM estacion ORDER BY nombre;";
        }
        return consultarBBDD($query);
    }

    function getEstacionPorCodigo($codigo){
        $query = "SELECT codigo, nombre, activo, codigo_localidad FROM estacion WHERE codigo = '" . $codigo . "' ORDER BY nombre;";
        return consultarBBDD($query);
    }

    function getEstacionPorLocalidad($localidad, $activo = true){
        if( $activo ){
            $query = "SELECT codigo, nombre, activo, codigo_localidad FROM estacion WHERE codigo_localidad = '" . $localidad . "' AND activo = 1 ORDER BY nombre;";
        } else {
            $query = "SELECT codigo, nombre, activo, codigo_localidad FROM estacion WHERE codigo_localidad = '" . $localidad . "' ORDER BY nombre;";
        }
        return consultarBBDD($query);
    }

    function getEstacionPorProvincia($provincia, $activo = true){
        if( $activo ){
            $query = "SELECT e.codigo AS codigo, e.nombre AS nombre, e.activo AS activo, e.codigo_localidad AS codigo_localidad FROM estacion AS e INNER JOIN localidad AS l ON e.codigo_localidad = l.codigo WHERE l.codigo_provincia = '" . $provincia . "' AND e.activo = 1 ORDER BY e.nombre;";
        } else {
            $query = "SELECT e.codigo AS codigo, e.nombre AS nombre, e.activo AS activo, e.codigo_localidad AS codigo_localidad FROM estacion AS e INNER JOIN localidad AS l ON e.codigo_localidad = l.codigo WHERE l.codigo_provincia = '" . $provincia . "' ORDER BY e.nombre;";
        }
        return consultarBBDD($query);
    }

    function getEstacionPorRegion($region, $activo = true){
        if( $activo ){
            $query = "SELECT e.codigo AS codigo, e.nombre AS nombre, e.activo AS activo, e.codigo_localidad AS codigo_localidad FROM estacion AS e INNER JOIN localidad AS l ON e.codigo_localidad = l.codigo INNER JOIN provincia AS pr ON l.codigo_provincia = pr.codigo WHERE pr.codigo_region = '" . $region . "' AND e.activo = 1 ORDER BY e.nombre;";
        } else {
            $query = "SELECT e.codigo AS codigo, e.nombre AS nombre, e.activo AS activo, e.codigo_localidad AS codigo_localidad FROM estacion AS e INNER JOIN localidad AS l ON e.codigo_localidad = l.codigo INNER JOIN provincia AS pr ON l.codigo_provincia = pr.codigo WHERE pr.codigo_region = '" . $region . "' ORDER BY e.nombre;";
        }
        return consultarBBDD($query);
    }

    function getEstacionPorPais($pais, $activo = true){
        if( $activo ){
            $query = "SELECT e.codigo AS codigo, e.nombre AS nombre, e.activo AS activo, e.codigo_localidad AS codigo_localidad FROM estacion AS e INNER JOIN localidad AS l ON e.codigo_localidad = l.codigo INNER JOIN provincia AS pr ON l.codigo_provincia = pr.codigo INNER JOIN region AS r ON pr.codigo_region = r.codigo WHERE r.codigo_pais = '" . $pais . "' AND activo = 1 ORDER BY e.nombre;";
        } else {
            $query = "SELECT e.codigo AS codigo, e.nombre AS nombre, e.activo AS activo, e.codigo_localidad AS codigo_localidad FROM estacion AS e INNER JOIN localidad AS l ON e.codigo_localidad = l.codigo INNER JOIN provincia AS pr ON l.codigo_provincia = pr.codigo INNER JOIN region AS r ON pr.codigo_region = r.codigo WHERE r.codigo_pais = '" . $pais . "' ORDER BY e.nombre;";
        }
        return consultarBBDD($query);
    }

    function getEstacionPorNombre($nombre, $activo = true){
        if( $activo ){
            $query = "SELECT codigo, nombre, activo, codigo_localidad FROM estacion WHERE nombre LIKE '" . $nombre . "%' AND activo = 1 ORDER BY nombre;";
        } else {
            $query = "SELECT codigo, nombre, activo, codigo_localidad FROM estacion WHERE nombre LIKE '" . $nombre . "%' ORDER BY nombre;";
        }
        return consultarBBDD($query);
    }

?>