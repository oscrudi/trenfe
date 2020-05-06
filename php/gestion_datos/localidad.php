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
        $query = "SELECT * FROM localidad ORDER BY nombre;";
        return consultarBBDD($query);
    }

    function getLocalidadPorCodigo($codigo){
        $query = "SELECT * FROM localidad WHERE codigo = '" . $codigo . "';";
        return consultarBBDD($query);
    }

    function getLocalidadPorProvincia($provincia){
        $query = "SELECT * FROM localidad WHERE codigo_provincia = '" . $provincia . "' ORDER BY nombre;";
        return consultarBBDD($query);
    }

    function getLocalidadPorRegion($region){
        $query = "SELECT l.* FROM localidad AS l INNER JOIN provincia AS p ON l.codigo_provincia = p.codigo WHERE p.codigo_region = '" . $region . "' ORDER BY l.nombre;";
        return consultarBBDD($query);
    }

    function getLocalidadPorPais($pais){
        $query = "SELECT l.* FROM localidad AS l INNER JOIN provincia AS p ON l.codigo_provincia = p.codigo INNER JOIN region AS r ON p.codigo_region = r.codigo WHERE r.codigo_pais = '" . $pais . "' ORDER BY l.nombre;";
        return consultarBBDD($query);
    }

    function getLocalidadPorNombre($nombre){
        $query = "SELECT * FROM localidad WHERE nombre LIKE '" . $nombre . "%' ORDER BY nombre;";
        return consultarBBDD($query);
    }

?>
