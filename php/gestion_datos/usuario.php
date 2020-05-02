<?php

    define("SALT_CHARS", "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()");

    function generarSalt(){
        $salt = "";
        $caracteres = str_split(SALT_CHARS);
        shuffle($caracteres);
        $claves = array_rand($caracteres, 22);
        foreach( $claves as $clave ){
            $salt .= $caracteres[$clave];
        }
        return $salt;
    }

    function addUsuario($dni, $nombre, $apellido_primero, $apellido_segundo, $genero, $fecha_nacimiento, $telefono, $email, $public_salt, $contrasena, $nivel_permisos){
        $private_salt = generarSalt();
        //TODO: hash contrasena con private_salt (contrasena viene hasheada con public_salt)
        $query = "INSERT INTO usuario (dni, nombre, apellido_primero, apellido_segundo, genero, fecha_nacimiento, telefono, email, public_salt, private_salt, contrasena, nivel_permisos, activo) VALUES ('" . $dni . "', '" . $nombre . "', '" . $apellido_primero . "', '" . $apellido_segundo . "', '" . $genero . "', " . $fecha_nacimiento . ", '" . $telefono . "', '" . $email . "', '" . $public_salt . "', '" . $private_salt . "', '" . $contrasena . "', " . $nivel_permisos . ", 1);";
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

?>