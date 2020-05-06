<?php

    function addTipoTren($nombre, $vagones_max){
        $query = "INSERT INTO tipo_tren (nombre, vagones_max) VALUES ('" . $nombre . "', " . $vagones_max . ");";
        return modificarBBDD($query);
    }

    function deleteTipoTren($codigo){
        $query = "DELETE FROM tipo_tren WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function updateNombreTipoTren($codigo, $nombre){
        $query = "UPDATE tipo_tren SET nombre = '" . $nombre . "' WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function updateVagonesMaxTipoTren($codigo, $vagones_max){
        $query = "UPDATE tipo_tren SET vagones_max = " . $vagones_max . " WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function getAllTipoTren(){
        $query = "SELECT * FROM tipo_tren ORDER BY nombre;";
        return consultarBBDD($query);
    }

    function getTipoTrenPorCodigo($codigo){
        $query = "SELECT * FROM tipo_tren WHERE codigo = '" . $codigo . "';";
        return consultarBBDD($query);
    }

    function getTipoTrenPorVagones($vagones_min = null, $vagones_max = null){
        if( $vagones_min && $vagones_min > 0 && $vagones_max && $vagones_max > 0 ){
            $query = "SELECT * FROM tipo_tren WHERE vagones_max >= " . $vagones_min . " AND vagones_max <= " . $vagones_max . " ORDER BY nombre;";
        }elseif( $vagones_min && $vagones_min > 0 ){
            $query = "SELECT * FROM tipo_tren WHERE vagones_max >= " . $vagones_min . " ORDER BY nombre;";
        }elseif( $vagones_max && $vagones_max > 0 ){
            $query = "SELECT * FROM tipo_tren WHERE vagones_max <= " . $vagones_max . " ORDER BY nombre;";
        }else{
            return getAllTipoTren();
        }
        return consultarBBDD($query);
    }

?>
