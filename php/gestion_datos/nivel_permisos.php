<?php

    function addNivelPermisos($nombre){
        $query = "INSERT INTO nivel_permisos (nombre) VALUES ('" . $nombre . "');";
        return modificarBBDD($query);
    }

    function deleteNivelPermisos($codigo){
        $query = "DELETE FROM nivel_permisos WHERE codigo = " . $codigo . ";";
        return modificarBBDD($query);
    }

    function updateNombreNivelPermisos($codigo, $nombre){
        $query = "UPDATE nivel_permisos SET nombre = '" . $nombre . "' WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function activarNivelPermisos($codigo){
        $query = "UPDATE nivel_permisos SET activo = 1 WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function desactivarNivelPermisos($codigo){
        $query = "UPDATE nivel_permisos SET activo = 0 WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function addPermisoNivelPermisos($nivel_permisos, $permiso){
        $query = "INSERT INTO permiso_nivel_permisos (nivel_permisos, permiso) VALUES (" . $nivel_permisos . ", " . $permiso . ");";
        return modificarBBDD($query);
    }

    function deletePermisoNivelPermisos($nivel_permisos, $permiso){
        $query = "DELETE FROM permiso_nivel_permisos WHERE nivel_permisos = " . $nivel_permisos . " AND permiso = " . $permiso . ";";
        return modificarBBDD($query);
    }

    function getAllNivelPermisos($activo = false){
        if( !$activo ){
            $query = "SELECT * FROM nivel_permisos ORDER BY nombre;";
        } else {
            $query = "SELECT * FROM nivel_permisos WHERE activo = 1 ORDER BY nombre;";
        }
        return consultarBBDD($query);
    }

    function getNivelPermisosPorCodigo($codigo){
        $query = "SELECT * FROM nivel_permisos WHERE codigo = '" . $codigo . "';";
        return consultarBBDD($query);
    }

    function getNivelPermisosPorNombre($nombre, $activo = false){
        if( !$activo ){
            $query = "SELECT * FROM nivel_permisos WHERE nombre LIKE '" . $nombre . "%' ORDER BY nombre;";
        } else {
            $query = "SELECT * FROM nivel_permisos WHERE nombre LIKE '" . $nombre . "%' AND activo = 1 ORDER BY nombre;";
        }
        return consultarBBDD($query);
    }

    function getNivelPermisosPorPermiso($permiso){
        $query = "SELECT np.* FROM nivel_permisos AS np INNER JOIN permiso_nivel_permisos AS pnp ON np.codigo = pnp.nivel_permisos WHERE pnp.permiso = '" . $permiso . "';";
        return consultarBBDD($query);
    }

    function getPermisosPorNivelPermisos($nivel_permisos){
        $query = "SELECT p.* FROM permiso AS p INNER JOIN permiso_nivel_permisos AS pnp ON p.codigo = pnp.permiso WHERE pnp.nivel_permisos = '" . $nivel_permisos . "';";
        return consultarBBDD($query);
    }

?>
