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
        $query = "SELECT codigo, nombre FROM pais ORDER BY nombre;";
        return consultarBBDD($query);
    }

    function getPaisPorCodigo($codigo){
        $query = "SELECT codigo, nombre FROM pais WHERE codigo = '" . $codigo . "' ORDER BY nombre;";
        return consultarBBDD($query);
    }

    function getPaisPorNombre($nombre){
        $query = "SELECT codigo, nombre FROM pais WHERE nombre LIKE '" . $nombre . "%' ORDER BY nombre;";
        return consultarBBDD($query);
    }

?>