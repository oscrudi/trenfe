<?php

    function getAllTipoRuta(){
        $query = "SELECT * FROM tipo_ruta ORDER BY codigo;";
        return consultarBBDD($query);
    }

    function getTipoRutaPorCodigo($codigo){
        $query = "SELECT * FROM tipo_ruta WHERE codigo = '" . $codigo . "';";
        return consultarBBDD($query);
    }

?>
