<?php

    const HASH = PASSWORD_DEFAULT;
    const COST = 10;

    function addUsuario($dni, $nombre, $apellido_primero, $apellido_segundo, $genero, $fecha_nacimiento, $telefono, $email, $contrasena, $nivel_permisos){
        $query = "INSERT INTO usuario (dni, nombre, apellido_primero, apellido_segundo, genero, fecha_nacimiento, telefono, email, contrasena, nivel_permisos, activo) VALUES ('" . $dni . "', '" . $nombre . "', '" . $apellido_primero . "', '" . $apellido_segundo . "', '" . $genero . "', " . $fecha_nacimiento . ", '" . $telefono . "', '" . $email . "', '" . $contrasena . "', " . $nivel_permisos . ", 1);";
        return modificarBBDD($query);
    }

    function deleteUsuario($dni){
        $query = "DELETE FROM usuario WHERE dni = " . $dni . ";";
        return modificarBBDD($query);
    }

    function updateContrasenaUsuario($dni, $contrasena) {
        $password = password_hash($contrasena, HASH, ['cost' => COST]);
        $query = "UPDATE usuario SET contrasena = '" . $password . "' WHERE dni = '" . $dni . "';";
        return modificarBBDD($query);
    }

    function updateNombreUsuario($dni, $nombre = false, $apellido_primero = false, $apellido_segundo = false){
        if( !$nombre && !$apellido_primero && !$apellido_segundo ){
            return true;
        }
        $query = "UPDATE usuario SET ";
        if( $nombre != false ){
            $query .= "nombre = '" . $nombre . "' ";
        }
        if( $apellido_primero != false ){
            if( $nombre != false ){
                $query .= ", ";
            }
            $query .= "apellido_primero = '" . $apellido_primero . "' ";
        }
        if( $apellido_segundo != false ){
            if( $nombre != false || $apellido_primero != false ){
                $query .= ", ";
            }
            $query .= "apellido_segundo = '" . $apellido_segundo . "' ";
        }
        $query .= "WHERE dni = '" . $dni . "';";
        return modificarBBDD($query);
    }

    function updateGeneroUsuario($dni, $genero){
        $query = "UPDATE usuario SET genero = '" . $genero . "' WHERE dni = '" . $dni . "';";
        return modificarBBDD($query);
    }

    function updateFechaNacimientoUsuario($dni, $fecha_nacimiento){
        $query = "UPDATE usuario SET fecha_nacimiento = " . $fecha_nacimiento . " WHERE dni = '" . $dni . "';";
        return modificarBBDD($query);
    }

    function updateTelefonoUsuario($dni, $telefono){
        $query = "UPDATE usuario SET telefono = '" . $telefono . "' WHERE dni = '" . $dni . "';";
        return modificarBBDD($query);
    }

    function updateEmailUsuario($dni, $email){
        $query = "UPDATE usuario SET email = '" . $email . "' WHERE dni = '" . $dni . "';";
        return modificarBBDD($query);
    }

    function updateNivelPermisos($dni, $nivel_permisos){
        $query = "UPDATE usuario SET nivel_permisos = '" . $nivel_permisos . "' WHERE dni = '" . $dni . "';";
        return modificarBBDD($query);
    }

    function activarUsuario($dni){
        $query = "UPDATE usuario SET activo = 1 WHERE dni = '" . $dni . "';";
        return modificarBBDD($query);
    }

    function desactivarUsuario($dni){
        //TODO: check billetes
        $query = "UPDATE usuario SET activo = 0 WHERE dni = '" . $dni . "';";
        return modificarBBDD($query);
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
