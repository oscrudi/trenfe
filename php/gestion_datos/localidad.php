<?php

    function addLocalidad($nombre, $provincia){
        $query = "INSERT INTO localidad (nombre, codigo_provincia) VALUES ('" . $nombre . "', '" . $provincia . "');";
        return modificarBBDD($query);
    }

    function deleteLocalidad($codigo){
        $query = "DELETE FROM localidad WHERE codigo = " . $codigo . ";";
        return modificarBBDD($query);
    }

    function updateNombreLocalidad($codigo, $nombre){
        $query = "UPDATE localidad SET nombre = '" . $nombre . "' WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function printLocalidad($result){
        $output = "";
        while( $row = $result->fetch_assoc() ){
            $output .= "Código: " . $row["codigo"] . " - Nombre: " . $row["nombre"] . " - Código provincia: " . $row["codigo_provincia"] . " - Provincia: " . $row["provincia"] . " - Código región: " . $row["codigo_region"] . " - Región: " . $row["region"] . " - Código país: " . $row["codigo_pais"] . " - País: " . $row["pais"] . "<br>";
        }
        return $output;
    }

    function getAllLocalidad(){
        $query = "SELECT l.codigo AS codigo, l.nombre AS nombre, l.codigo_provincia AS codigo_provincia, pr.nombre AS provincia, pr.codigo_region AS codigo_region, r.nombre AS region, r.codigo_pais AS codigo_pais, p.nombre AS pais FROM localidad AS l INNER JOIN provincia AS pr ON l.codigo_provincia = pr.codigo INNER JOIN region AS r ON pr.codigo_region = r.codigo INNER JOIN pais as p ON r.codigo_pais = p.codigo ORDER BY l.nombre;";
        return consultarBBDD($query);
    }

    function getLocalidadPorCodigo($codigo){
        $query = "SELECT l.codigo AS codigo, l.nombre AS nombre, l.codigo_provincia AS codigo_provincia, pr.nombre AS provincia, pr.codigo_region AS codigo_region, r.nombre AS region, r.codigo_pais AS codigo_pais, p.nombre AS pais FROM localidad AS l INNER JOIN provincia AS pr ON l.codigo_provincia = pr.codigo INNER JOIN region AS r ON pr.codigo_region = r.codigo INNER JOIN pais as p ON r.codigo_pais = p.codigo WHERE l.codigo = '" . $codigo . "' ORDER BY l.nombre;";
        return consultarBBDD($query);
    }

    function getLocalidadPorProvincia($provincia){
        $query = "SELECT l.codigo AS codigo, l.nombre AS nombre, l.codigo_provincia AS codigo_provincia, pr.nombre AS provincia, pr.codigo_region AS codigo_region, r.nombre AS region, r.codigo_pais AS codigo_pais, p.nombre AS pais FROM localidad AS l INNER JOIN provincia AS pr ON l.codigo_provincia = pr.codigo INNER JOIN region AS r ON pr.codigo_region = r.codigo INNER JOIN pais as p ON r.codigo_pais = p.codigo WHERE l.codigo_provincia = '" . $provincia . "' ORDER BY l.nombre;";
        return consultarBBDD($query);
    }

    function getLocalidadPorRegion($region){
        $query = "SELECT l.codigo AS codigo, l.nombre AS nombre, l.codigo_provincia AS codigo_provincia, pr.nombre AS provincia, pr.codigo_region AS codigo_region, r.nombre AS region, r.codigo_pais AS codigo_pais, p.nombre AS pais FROM localidad AS l INNER JOIN provincia AS pr ON l.codigo_provincia = pr.codigo INNER JOIN region AS r ON pr.codigo_region = r.codigo INNER JOIN pais as p ON r.codigo_pais = p.codigo WHERE pr.codigo_region = '" . $region . "' ORDER BY l.nombre;";
        return consultarBBDD($query);
    }

    function getLocalidadPorPais($pais){
        $query = "SELECT l.codigo AS codigo, l.nombre AS nombre, l.codigo_provincia AS codigo_provincia, pr.nombre AS provincia, pr.codigo_region AS codigo_region, r.nombre AS region, r.codigo_pais AS codigo_pais, p.nombre AS pais FROM localidad AS l INNER JOIN provincia AS pr ON l.codigo_provincia = pr.codigo INNER JOIN region AS r ON pr.codigo_region = r.codigo INNER JOIN pais as p ON r.codigo_pais = p.codigo WHERE r.codigo_pais = '" . $pais . "' ORDER BY l.nombre;";
        return consultarBBDD($query);
    }

    function getLocalidadPorNombre($nombre){
        $query = "SELECT l.codigo AS codigo, l.nombre AS nombre, l.codigo_provincia AS codigo_provincia, pr.nombre AS provincia, pr.codigo_region AS codigo_region, r.nombre AS region, r.codigo_pais AS codigo_pais, p.nombre AS pais FROM localidad AS l INNER JOIN provincia AS pr ON l.codigo_provincia = pr.codigo INNER JOIN region AS r ON pr.codigo_region = r.codigo INNER JOIN pais as p ON r.codigo_pais = p.codigo WHERE l.nombre LIKE '" . $nombre . "%' ORDER BY l.nombre;";
        return consultarBBDD($query);
    }

?>