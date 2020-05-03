<?php

    function addUsuario($dni, $nombre, $apellido_primero, $apellido_segundo, $genero, $fecha_nacimiento, $telefono, $email, $contrasena, $nivel_permisos){
        $query = "INSERT INTO usuario (dni, nombre, apellido_primero, apellido_segundo, genero, fecha_nacimiento, telefono, email, contrasena, nivel_permisos, activo) VALUES ('" . $dni . "', '" . $nombre . "', '" . $apellido_primero . "', '" . $apellido_segundo . "', '" . $genero . "', " . $fecha_nacimiento . ", '" . $telefono . "', '" . $email . "', '" . $contrasena . "', " . $nivel_permisos . ", 1);";
        return modificarBBDD($query);
    }

    function deleteUsuario($dni){
        $query = "DELETE FROM usuario WHERE dni = " . $dni . ";";
        return modificarBBDD($query);
    }

    function updateNombreUsuario($dni, $nombre){
        $query = "UPDATE usuario SET nombre = '" . $nombre . "' WHERE dni = '" . $dni . "';";
        return modificarBBDD($query);
    }

    //TODO: updates

    function activarUsuario($dni){
        $query = "UPDATE usuario SET activo = 1 WHERE dni = '" . $dni . "';";
        return modificarBBDD($query);
    }

    function desactivarUsuario($dni){
        //TODO: check billetes
        $query = "UPDATE usuario SET activo = 0 WHERE dni = '" . $dni . "';";
        return modificarBBDD($query);
    }

    function printUsuario($result){
        //TODO
        $output = "";
        while( $row = $result->fetch_assoc() ){
            $output .= "Código: " . $row["codigo"] . " - Nombre: " . $row["nombre"] . " - Activo: " . $row["activo"] . "<br>";
        }
        return $output;
    }

    function getAllUsuario($activo = false){
        if( !$activo ){
            $query = "SELECT * FROM usuario ORDER BY dni;";
        } else {
            $query = "SELECT * FROM usuario WHERE activo = 1 ORDER BY dni;";
        }
        return consultarBBDD($query);
    }

    function getUsuarioPorDNI($dni){
        $query = "SELECT * FROM usuario WHERE dni = '" . $dni . "';";
        return consultarBBDD($query);
    }

    function getUsuarioPorEmail($email){
        $query = "SELECT * FROM usuario WHERE email = '" . $email . "';";
        return consultarBBDD($query);
    }

?>