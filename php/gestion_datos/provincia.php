<?php

    function addProvincia($nombre, $region){
        $query = "INSERT INTO provincia (nombre, codigo_region) VALUES ('" . $nombre . "', '" . $region . "');";
        return modificarBBDD($query);
    }

    function deleteProvincia($codigo){
        $query = "DELETE FROM provincia WHERE codigo = " . $codigo . ";";
        return modificarBBDD($query);
    }

    function updateNombreProvincia($codigo, $nombre){
        $query = "UPDATE provincia SET nombre = '" . $nombre . "' WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function getAllProvincia(){
        $query = "SELECT * FROM provincia ORDER BY nombre;";
        return consultarBBDD($query);
    }

    function getProvinciaPorCodigo($codigo){
        $query = "SELECT * FROM provincia WHERE codigo = '" . $codigo . "';";
        return consultarBBDD($query);
    }

    function getProvinciaPorRegion($region){
        $query = "SELECT * FROM provincia WHERE codigo_region = '" . $region . "' ORDER BY nombre;";
        return consultarBBDD($query);
    }

    function getProvinciaPorPais($pais){
        $query = "SELECT p.* FROM provincia AS p INNER JOIN region AS r ON p.codigo_region = r.codigo WHERE r.codigo_pais = '" . $pais . "' ORDER BY p.nombre;";
        return consultarBBDD($query);
    }

    function getProvinciaPorNombre($nombre){
        $query = "SELECT * FROM provincia WHERE nombre LIKE '" . $nombre . "%' ORDER BY nombre;";
        return consultarBBDD($query);
    }

?>
