<?php

    function addTren($codigo, $tipo){
        $query = "INSERT INTO tren (codigo, tipo, activo) VALUES ('" . $codigo . "', " . $tipo . ", 0);";
        return modificarBBDD($query);
    }

    function deleteTren($codigo){
        //Borrar vagones del tren
        $result = getVagonPorTren($codigo, false);
        while( $row = $result->fetch_assoc() ){
            deleteVagon($row["codigo"]);
        }
        //Borrar tren
        $query = "DELETE FROM tren WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function updateTipoTren($codigo, $tipo){
        //Comprobar que el numero de vagones del tren no supera el máximo del nuevo tipo
        $correcto = checkVagonesMax($codigo, $tipo);
        if( !$correcto ){
            return false;
        }
        $query = "UPDATE tren SET tipo = " . $tipo . " WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function activarTren($codigo){
        $query = "UPDATE tren SET activo = 1 WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function desactivarTren($codigo){
        //TODO: desactivar línea que lo contenga
        $query = "UPDATE tren SET activo = 0 WHERE codigo = '" . $codigo . "';";
        return modificarBBDD($query);
    }

    function checkVagonesMax($tren, $tipo_nuevo = false, $vagon_extra = false){
        if( !$tipo_nuevo ){
            //Obtener tipo actual del tren
            $tipo_tren = false;
            $result = getTrenPorCodigo($tren);
            $tipo_tren = $result->fetch_assoc()["tipo"];
            if( !$tipo_tren ){
                return false;
            }
        } else {
            $tipo_tren = $tipo_nuevo;
        }
        //Obtener vagones máximos del tipo de tren
        $vagones_max = false;
        $result = getTipoTrenPorCodigo($tipo_tren);
        $vagones_max = $result->fetch_assoc()["vagones_max"];
        if( !$vagones_max ){
            return false;
        }
        //Obtener vagones del tren
        $result = getVagonPorTren($tren, false);
        $vagones = 0;
        if( $result != false ){
            $vagones = $result->num_rows;
        }
        if( $vagon_extra ){
            $vagones++;
        }
        if( $vagones_max < $vagones ){
            return false;
        }
        return true;
    }

    function printTren($result) {
        $output = "";
        while( $row = $result->fetch_assoc() ){
            $output .= "Código: " . $row["codigo"] . " - Tipo Tren: " . $row["tipo"] . " - Activo: " . $row["activo"] . "<br>";
        }
        return $output;
    }

    function getAllTren($activo = true){
        if( $activo ){
            $query = "SELECT codigo, tipo, activo FROM tren WHERE activo = 1 ORDER BY codigo;";
        }else{
            $query = "SELECT codigo, tipo, activo FROM tren ORDER BY codigo;";
        }
        return consultarBBDD($query);
    }

    function getTrenPorCodigo($codigo){
        $query = "SELECT codigo, tipo, activo FROM tren WHERE codigo = '" . $codigo . "';";
        return consultarBBDD($query);
    }

    function getTrenPorTipo($tipo, $activo = true){
        if( $activo ){
            $query = "SELECT codigo, tipo, activo FROM tren WHERE tipo = " . $tipo . " AND activo = 1 ORDER BY codigo;";
        } else {
            $query = "SELECT codigo, tipo, activo FROM tren WHERE tipo = " . $tipo . " ORDER BY codigo;";
        }
        return consultarBBDD($query);
    }

?>