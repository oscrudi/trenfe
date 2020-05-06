<?php

    function updateNombreTramoHorario($codigo, $nombre){
        $query = "UPDATE tramo_horario SET nombre = '" . $nombre . "' WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function getAllTramoHorario(){
        $query = "SELECT * FROM tramo_horario ORDER BY hora_inicio;";
        return consultarBBDD($query);
    }

    function getTramoHorarioPorCodigo($codigo){
        $query = "SELECT * FROM tramo_horario WHERE codigo = '" . $codigo . "';";
        return consultarBBDD($query);
    }

    function getTramoHorarioPorHora($hora){
        $query = "SELECT * FROM tramo_horario WHERE hora_inicio <= " . $hora . " AND hora_fin >= " . $hora . " ORDER BY hora_inicio;";
        return consultarBBDD($query);
    }

?>
