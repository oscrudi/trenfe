<?php

    require "../db.php";

    function addEstacion($nombre, $localidad){
        $query = "INSERT INTO estacion (nombre, localidad, activo) VALUES ('" . $nombre . "', '" . $localidad . "', 1);";
        return consultarBBDD($query);
    }

    function deleteEstacion($codigo){
        $query = "DELETE FROM estacion WHERE codigo = " . $codigo . ";";
        return consultarBBDD($query);
    }

    function updateNombreEstacion($codigo, $nombre){
        $query = "UPDATE estacion SET nombre = '" . $nombre . "' WHERE codigo = '" . $codigo . "';";
        return consultarBBDD($query);
    }

    function activarEstacion($codigo){
        $query = "UPDATE estacion SET activo = 1 WHERE codigo = '" . $codigo . "';";
        return consultarBBDD($query);
    }

    function desactivarEstacion($codigo){
        //TODO: desactivar rutas sin estaciones activas, sin estacion de origen y sin estacion de destino.
        $query = "UPDATE estacion SET activo = 0 WHERE codigo = '" . $codigo . "';";
        return consultarBBDD($query);
    }

?>