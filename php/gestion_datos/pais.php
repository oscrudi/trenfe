<?php

    function addPais($nombre){
        $query = "INSERT INTO pais (nombre) VALUES ('" . $nombre . "');";
        return modificarBBDD($query);
    }

    function deletePais($codigo){
        $query = "DELETE FROM pais WHERE codigo = " . $codigo . ";";
        return modificarBBDD($query);
    }

    function updateNombrePais($codigo, $nombre){
        $query = "UPDATE pais SET nombre = '" . $nombre . "' WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function printPais($result){
        $output = "";
        while( $row = $result->fetch_assoc() ){
            $output .= "Código: " . $row["codigo"] . " - Nombre: " . $row["nombre"] . "<br>";
        }
        return $output;
    }

    function getAllPais(){
        $query = "SELECT p.codigo AS codigo, p.nombre AS nombre FROM pais AS p ORDER BY p.nombre;";
        $result = consultarBBDD($query);
        if( $result ){
            return printPais($result);
        } else {
            return false;
        }
    }

    function getPaisPorCodigo($codigo){
        $query = "SELECT p.codigo AS codigo, p.nombre AS nombre FROM pais AS p WHERE p.codigo = '" . $codigo . "' ORDER BY p.nombre;";
        $result = consultarBBDD($query);
        if( $result ){
            return printPais($result);
        } else {
            return false;
        }
    }

    function getPaisPorNombre($nombre){
        $query = "SELECT * FROM pais WHERE nombre LIKE '" . $nombre . "%' ORDER BY nombre;";
        $result = consultarBBDD($query);
        if( $result ){
            return printPais($result);
        } else {
            return false;
        }
    }

?>