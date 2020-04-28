<?php

    function updateNombreTramoHorario($codigo, $nombre){
        $query = "UPDATE tramo_horario SET nombre = '" . $nombre . "' WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function printTramoHorario($result) {
        $output = "";
        while( $row = $result->fetch_assoc() ){
            $output .= "Código: " . $row["codigo"] . " - Nombre: " . $row["nombre"] . " - Hora Inicio: " . $row["hora_inicio"] . " - Hora Fin: " . $row["hora_fin"] . "<br>";
        }
        return $output;
    }

    function getAllTramoHorario(){
        $query = "SELECT codigo, nombre, hora_inicio, hora_fin FROM tramo_horario ORDER BY hora_inicio;";
        return consultarBBDD($query);
    }

    function getTramoHorarioPorCodigo($codigo){
        $query = "SELECT codigo, nombre, hora_inicio, hora_fin FROM tramo_horario WHERE codigo = '" . $codigo . "';";
        return consultarBBDD($query);
    }

    function getTramoHorarioPorHora($hora){
        $query = "SELECT codigo, nombre, hora_inicio, hora_fin FROM tramo_horario WHERE hora_inicio <= " . $hora . " AND hora_fin >= " . $hora . ";";
        return consultarBBDD($query);
    }

?>