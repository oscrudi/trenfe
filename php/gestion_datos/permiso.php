<?php

    function updateNombrePermiso($codigo, $nombre){
        $query = "UPDATE permiso SET nombre = '" . $nombre . "' WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function printPermiso($result){
        $output = "";
        while( $row = $result->fetch_assoc() ){
            $output .= "Código: " . $row["codigo"] . " - Nombre: " . $row["nombre"] . "<br>";
        }
        return $output;
    }

    function getAllPermiso(){
        $query = "SELECT * FROM permiso ORDER BY nombre;";
        return consultarBBDD($query);
    }

    function getPermisoPorCodigo($codigo){
        $query = "SELECT * FROM permiso WHERE codigo = '" . $codigo . "';";
        return consultarBBDD($query);
    }

    function getPermisoPorNombre($nombre){
        $query = "SELECT * FROM permiso WHERE nombre LIKE '" . $nombre . "%' ORDER BY nombre;";
        return consultarBBDD($query);
    }

?>