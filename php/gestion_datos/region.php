<?php

    function addRegion($nombre, $pais){
        $query = "INSERT INTO region (nombre, codigo_pais) VALUES ('" . $nombre . "', '" . $pais . "');";
        return modificarBBDD($query);
    }

    function deleteRegion($codigo){
        $query = "DELETE FROM region WHERE codigo = " . $codigo . ";";
        return modificarBBDD($query);
    }

    function updateNombreRegion($codigo, $nombre){
        $query = "UPDATE region SET nombre = '" . $nombre . "' WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function printRegion($result){
        $output = "";
        while( $row = $result->fetch_assoc() ){
            $output .= "Código: " . $row["codigo"] . " - Nombre: " . $row["nombre"] . " - Código país: " . $row["codigo_pais"] . " - País: " . $row["pais"] . "<br>";
        }
        return $output;
    }

    function getAllRegion(){
        $query = "SELECT r.codigo AS codigo, r.nombre AS nombre, r.codigo_pais AS codigo_pais, p.nombre AS pais FROM region AS r INNER JOIN pais AS p ON r.codigo_pais = p.codigo ORDER BY r.nombre;";
        $result = consultarBBDD($query);
        if( $result ){
            return printRegion($result);
        } else {
            return false;
        }
    }

    function getRegionPorCodigo($codigo){
        $query = "SELECT r.codigo AS codigo, r.nombre AS nombre, r.codigo_pais AS codigo_pais, p.nombre AS pais FROM region AS r INNER JOIN pais AS p ON r.codigo_pais = p.codigo WHERE r.codigo = '" . $codigo . "' ORDER BY r.nombre;";
        $result = consultarBBDD($query);
        if( $result ){
            return printRegion($result);
        } else {
            return false;
        }
    }

    function getRegionPorPais($pais){
        $query = "SELECT r.codigo AS codigo, r.nombre AS nombre, r.codigo_pais AS codigo_pais, p.nombre AS pais FROM region AS r INNER JOIN pais AS p ON r.codigo_pais = p.codigo WHERE r.codigo_pais = '" . $pais . "' ORDER BY r.nombre;";
        $result = consultarBBDD($query);
        if( $result ){
            return printRegion($result);
        } else {
            return false;
        }
    }

    function getRegionPorNombre($nombre){
        $query = "SELECT r.codigo AS codigo, r.nombre AS nombre, r.codigo_pais AS codigo_pais, p.nombre AS pais FROM region AS r INNER JOIN pais AS p ON r.codigo_pais = p.codigo WHERE r.nombre LIKE '" . $nombre . "%' ORDER BY r.nombre;";
        $result = consultarBBDD($query);
        if( $result ){
            return printRegion($result);
        } else {
            return false;
        }
    }

?>