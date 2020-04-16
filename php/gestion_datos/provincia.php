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

    function printProvincia($result){
        $output = "";
        while( $row = $result->fetch_assoc() ){
            $output .= "Código: " . $row["codigo"] . " - Nombre: " . $row["nombre"] . " - Código región: " . $row["codigo_region"] . " - Región: " . $row["region"] . " - Código país: " . $row["codigo_pais"] . " - País: " . $row["pais"] . "<br>";
        }
        return $output;
    }

    function getAllProvincia(){
        $query = "SELECT pr.codigo AS codigo, pr.nombre AS nombre, pr.codigo_region AS codigo_region, r.nombre AS region, r.codigo_pais AS codigo_pais, p.nombre AS pais FROM provincia AS pr INNER JOIN region AS r ON pr.codigo_region = r.codigo INNER JOIN pais as p ON r.codigo_pais = p.codigo ORDER BY pr.nombre;";
        $result = consultarBBDD($query);
        if( $result ){
            return printProvincia($result);
        } else {
            return false;
        }
    }

    function getProvinciaPorCodigo($codigo){
        $query = "SELECT pr.codigo AS codigo, pr.nombre AS nombre, pr.codigo_region AS codigo_region, r.nombre AS region, r.codigo_pais AS codigo_pais, p.nombre AS pais FROM provincia AS pr INNER JOIN region AS r ON pr.codigo_region = r.codigo INNER JOIN pais as p ON r.codigo_pais = p.codigo WHERE pr.codigo = '" . $codigo . "' ORDER BY pr.nombre;";
        $result = consultarBBDD($query);
        if( $result ){
            return printProvincia($result);
        } else {
            return false;
        }
    }

    function getProvinciaPorRegion($region){
        $query = "SELECT pr.codigo AS codigo, pr.nombre AS nombre, pr.codigo_region AS codigo_region, r.nombre AS region, r.codigo_pais AS codigo_pais, p.nombre AS pais FROM provincia AS pr INNER JOIN region AS r ON pr.codigo_region = r.codigo INNER JOIN pais as p ON r.codigo_pais = p.codigo WHERE r.codigo = '" . $region . "' ORDER BY pr.nombre;";
        $result = consultarBBDD($query);
        if( $result ){
            return printProvincia($result);
        } else {
            return false;
        }
    }

    function getProvinciaPorPais($pais){
        $query = "SELECT pr.codigo AS codigo, pr.nombre AS nombre, pr.codigo_region AS codigo_region, r.nombre AS region, r.codigo_pais AS codigo_pais, p.nombre AS pais FROM provincia AS pr INNER JOIN region AS r ON pr.codigo_region = r.codigo INNER JOIN pais as p ON r.codigo_pais = p.codigo WHERE p.codigo = '" . $pais . "' ORDER BY pr.nombre;";
        $result = consultarBBDD($query);
        if( $result ){
            return printProvincia($result);
        } else {
            return false;
        }
    }

    function getProvinciaPorNombre($nombre){
        $query = "SELECT pr.codigo AS codigo, pr.nombre AS nombre, pr.codigo_region AS codigo_region, r.nombre AS region, r.codigo_pais AS codigo_pais, p.nombre AS pais FROM provincia AS pr INNER JOIN region AS r ON pr.codigo_region = r.codigo INNER JOIN pais as p ON r.codigo_pais = p.codigo WHERE pr.nombre LIKE '" . $nombre . "%' ORDER BY pr.nombre;";
        $result = consultarBBDD($query);
        if( $result ){
            return printProvincia($result);
        } else {
            return false;
        }
    }

?>