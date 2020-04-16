<?php

    function addEstacion($nombre, $localidad){
        $query = "INSERT INTO estacion (nombre, codigo_localidad, activo) VALUES ('" . $nombre . "', '" . $localidad . "', 1);";
        return modificarBBDD($query);
    }

    function deleteEstacion($codigo){
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
        //TODO: desactivar rutas sin estaciones activas, sin estacion de origen y sin estacion de destino.
        $query = "UPDATE estacion SET activo = 0 WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function printEstacion($result) {
        $output = "";
        while( $row = $result->fetch_assoc() ){
            $output .= "Código: " . $row["codigo"] . " - Nombre: " . $row["nombre"] . " - Activo: " . $row["activo"] . " - Código localidad: " . $row["codigo_localidad"] . " - Localidad: " . $row["localidad"] . " - Código provincia: " . $row["codigo_provincia"] . " - Provincia: " . $row["provincia"] . " - Código región: " . $row["codigo_region"] . " - Región: " . $row["region"] . " - Código país: " . $row["codigo_pais"] . " - País: " . $row["pais"] . "<br>";
        }
        return $output;
    }

    function getAllEstacion(){
        $query = "SELECT e.codigo AS codigo, e.nombre AS nombre, e.activo AS activo, e.codigo_localidad AS codigo_localidad, l.nombre AS localidad, l.codigo_provincia AS codigo_provincia, pr.nombre AS provincia, pr.codigo_region AS codigo_region, r.nombre AS region, r.codigo_pais AS codigo_pais, p.nombre AS pais FROM estacion AS e INNER JOIN localidad AS l ON e.codigo_localidad = l.codigo INNER JOIN provincia AS pr ON l.codigo_provincia = pr.codigo INNER JOIN region AS r ON pr.codigo_region = r.codigo INNER JOIN pais as p ON r.codigo_pais = p.codigo ORDER BY e.nombre;";
        $result = consultarBBDD($query);
        if( $result ){
            return printEstacion($result);
        } else {
            return false;
        }
    }

    function getEstacionPorCodigo($codigo){
        $query = "SELECT e.codigo AS codigo, e.nombre AS nombre, e.activo AS activo, e.codigo_localidad AS codigo_localidad, l.nombre AS localidad, l.codigo_provincia AS codigo_provincia, pr.nombre AS provincia, pr.codigo_region AS codigo_region, r.nombre AS region, r.codigo_pais AS codigo_pais, p.nombre AS pais FROM estacion AS e INNER JOIN localidad AS l ON e.codigo_localidad = l.codigo INNER JOIN provincia AS pr ON l.codigo_provincia = pr.codigo INNER JOIN region AS r ON pr.codigo_region = r.codigo INNER JOIN pais as p ON r.codigo_pais = p.codigo WHERE e.codigo = '" . $codigo . "' ORDER BY e.nombre;";
        $result = consultarBBDD($query);
        if( $result ){
            return printEstacion($result);
        } else {
            return false;
        }
    }

    function getEstacionPorLocalidad($localidad){
        $query = "SELECT e.codigo AS codigo, e.nombre AS nombre, e.activo AS activo, e.codigo_localidad AS codigo_localidad, l.nombre AS localidad, l.codigo_provincia AS codigo_provincia, pr.nombre AS provincia, pr.codigo_region AS codigo_region, r.nombre AS region, r.codigo_pais AS codigo_pais, p.nombre AS pais FROM estacion AS e INNER JOIN localidad AS l ON e.codigo_localidad = l.codigo INNER JOIN provincia AS pr ON l.codigo_provincia = pr.codigo INNER JOIN region AS r ON pr.codigo_region = r.codigo INNER JOIN pais as p ON r.codigo_pais = p.codigo WHERE e.codigo_localidad = '" . $localidad . "' ORDER BY e.nombre;";
        $result = consultarBBDD($query);
        if( $result ){
            return printEstacion($result);
        } else {
            return false;
        }
    }

    function getEstacionPorProvincia($provincia){
        $query = "SELECT e.codigo AS codigo, e.nombre AS nombre, e.activo AS activo, e.codigo_localidad AS codigo_localidad, l.nombre AS localidad, l.codigo_provincia AS codigo_provincia, pr.nombre AS provincia, pr.codigo_region AS codigo_region, r.nombre AS region, r.codigo_pais AS codigo_pais, p.nombre AS pais FROM estacion AS e INNER JOIN localidad AS l ON e.codigo_localidad = l.codigo INNER JOIN provincia AS pr ON l.codigo_provincia = pr.codigo INNER JOIN region AS r ON pr.codigo_region = r.codigo INNER JOIN pais as p ON r.codigo_pais = p.codigo WHERE l.codigo_provincia = '" . $provincia . "' ORDER BY e.nombre;";
        $result = consultarBBDD($query);
        if( $result ){
            return printEstacion($result);
        } else {
            return false;
        }
    }

    function getEstacionPorRegion($region){
        $query = "SELECT e.codigo AS codigo, e.nombre AS nombre, e.activo AS activo, e.codigo_localidad AS codigo_localidad, l.nombre AS localidad, l.codigo_provincia AS codigo_provincia, pr.nombre AS provincia, pr.codigo_region AS codigo_region, r.nombre AS region, r.codigo_pais AS codigo_pais, p.nombre AS pais FROM estacion AS e INNER JOIN localidad AS l ON e.codigo_localidad = l.codigo INNER JOIN provincia AS pr ON l.codigo_provincia = pr.codigo INNER JOIN region AS r ON pr.codigo_region = r.codigo INNER JOIN pais as p ON r.codigo_pais = p.codigo WHERE pr.codigo_region = '" . $region . "' ORDER BY e.nombre;";
        $result = consultarBBDD($query);
        if( $result ){
            return printEstacion($result);
        } else {
            return false;
        }
    }

    function getEstacionPorPais($pais){
        $query = "SELECT e.codigo AS codigo, e.nombre AS nombre, e.activo AS activo, e.codigo_localidad AS codigo_localidad, l.nombre AS localidad, l.codigo_provincia AS codigo_provincia, pr.nombre AS provincia, pr.codigo_region AS codigo_region, r.nombre AS region, r.codigo_pais AS codigo_pais, p.nombre AS pais FROM estacion AS e INNER JOIN localidad AS l ON e.codigo_localidad = l.codigo INNER JOIN provincia AS pr ON l.codigo_provincia = pr.codigo INNER JOIN region AS r ON pr.codigo_region = r.codigo INNER JOIN pais as p ON r.codigo_pais = p.codigo WHERE r.codigo_pais = '" . $pais . "' ORDER BY e.nombre;";
        $result = consultarBBDD($query);
        if( $result ){
            return printEstacion($result);
        } else {
            return false;
        }
    }

    function getEstacionPorNombre($nombre){
        $query = "SELECT e.codigo AS codigo, e.nombre AS nombre, e.activo AS activo, e.codigo_localidad AS codigo_localidad, l.nombre AS localidad, l.codigo_provincia AS codigo_provincia, pr.nombre AS provincia, pr.codigo_region AS codigo_region, r.nombre AS region, r.codigo_pais AS codigo_pais, p.nombre AS pais FROM estacion AS e INNER JOIN localidad AS l ON e.codigo_localidad = l.codigo INNER JOIN provincia AS pr ON l.codigo_provincia = pr.codigo INNER JOIN region AS r ON pr.codigo_region = r.codigo INNER JOIN pais as p ON r.codigo_pais = p.codigo WHERE e.nombre LIKE '" . $nombre . "%' ORDER BY e.nombre;";
        $result = consultarBBDD($query);
        if( $result ){
            return printEstacion($result);
        } else {
            return false;
        }
    }

    function getEstacionPorActivo($activo){
        $query = "SELECT e.codigo AS codigo, e.nombre AS nombre, e.activo AS activo, e.codigo_localidad AS codigo_localidad, l.nombre AS localidad, l.codigo_provincia AS codigo_provincia, pr.nombre AS provincia, pr.codigo_region AS codigo_region, r.nombre AS region, r.codigo_pais AS codigo_pais, p.nombre AS pais FROM estacion AS e INNER JOIN localidad AS l ON e.codigo_localidad = l.codigo INNER JOIN provincia AS pr ON l.codigo_provincia = pr.codigo INNER JOIN region AS r ON pr.codigo_region = r.codigo INNER JOIN pais as p ON r.codigo_pais = p.codigo WHERE e.activo = '" . $activo . "' ORDER BY e.nombre;";
        $result = consultarBBDD($query);
        if( $result ){
            return printEstacion($result);
        } else {
            return false;
        }
    }

?>