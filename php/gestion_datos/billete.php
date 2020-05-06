<?php

    function addBillete($cliente, $linea, $orden_origen, $orden_destino, $fecha_salida, $asiento, $tipo_cliente, $codigo_descuento = null){
        $precio = calcularPrecio($linea, $orden_origen, $orden_destino, $fecha_salida, $asiento, $tipo_cliente, $codigo_descuento);
        $query = "INSERT INTO billete (cliente, linea, orden_origen, orden_destino, fecha_salida, tipo_cliente, vagon, fila, letra, codigo_descuento, precio) VALUES ('" . $cliente . "', '" . $linea . "', " . $orden_origen . ", " . $orden_destino . ", " . $fecha_salida . ", " . $tipo_cliente . ", '" . $asiento["vagon"] . "', " . $asiento["fila"] . ", '" . $asiento["letra"] . "', '" . $codigo_descuento . "', " . $precio . ");";
        return modificarBBDD($query);
    }

    function deleteBillete($codigo){
        $query = "DELETE FROM billete WHERE codigo = " . $codigo . ";";
        return modificarBBDD($query);
    }

    function calcularPrecio($codigo_linea, $orden_origen, $orden_destino, $fecha_salida, $asiento, $codigo_tipo_cliente, $codigo_descuento = null){
        $result = getAsientoPorCodigo($asiento["fila"], $asiento["letra"], $asiento["vagon"]);
        $asiento = $result->fetch_assoc();
        $tipo_asiento = $asiento["tipo"];

        $result = getVagonPorCodigo($asiento["vagon"]);
        $vagon = $result->fetch_assoc();
        $tipo_vagon = $vagon["tipo"];

        $result = getTrenPorCodigo($vagon["tren"]);
        $tren = $result->fetch_assoc();
        $tipo_tren = $tren["tipo"];

        $result = getLineaPorCodigo($codigo_linea);
        $linea = $result->fetch_assoc();
        $result = getRutaPorCodigo($linea["ruta"]);
        $tipo_ruta = $result->fetch_assoc()["tipo"];

        $hora = calcularHoraLlegadaPorOrden($codigo_linea, $orden_origen);
        $tramo_horario = getTramoHorarioPorHora($hora);

        $dia = date('w', strtotime($fecha_salida));

        $tarifas = getTarifasPorRequisitos($dia, $tramo_horario, $tipo_ruta, $tipo_tren, $tipo_vagon, $tipo_asiento);
        $num_paradas = $orden_destino - $orden_origen;
        $tarifa_final = getTarifaMasCara($tarifas, $num_paradas);
        $precio_tarifa = $tarifa_final["precio_base"] + ( $tarifa_final["incremento_precio"] * $num_paradas );
        $precio_final = $precio_tarifa;

        $result = getTipoClientePorCodigo($codigo_tipo_cliente);
        $tipo_cliente = $result->fetch_assoc();
        $descuento_tipo_cliente = $precio_tarifa * ($tipo_cliente["porcentaje_descuento"] / 100);
        $precio_final -= $descuento_tipo_cliente;

        if( $codigo_descuento != null ){
            $result = getCodigoDescuentoPorCodigo($codigo_descuento);
            $codigo_descuento = $result->fetch_assoc();
            $descuento_codigo_descuento = $precio_tarifa * ($codigo_descuento["porcentaje_descuento"] / 100);
            $precio_final -= $descuento_codigo_descuento;
        }

        return $precio_final;
    }

    function getAllBillete(){
        $query = "SELECT * FROM billete ORDER BY fecha_salida;";
        return consultarBBDD($query);
    }

    function getBilletePorCodigo($codigo){
        $query = "SELECT * FROM billete WHERE codigo = '" . $codigo . "';";
        return consultarBBDD($query);
    }

    function getBilletePorCliente($cliente){
        $query = "SELECT * FROM billete WHERE cliente = '" . $cliente . "' ORDER BY fecha_salida;";
        return consultarBBDD($query);
    }

    function getBilletePorLinea($linea){
        $query = "SELECT * FROM billete WHERE linea = '" . $linea . "' ORDER BY fecha_salida;";
        return consultarBBDD($query);
    }

    function getBilletePorTipoCliente($tipo_cliente){
        $query = "SELECT * FROM billete WHERE tipo_cliente = '" . $tipo_cliente . "' ORDER BY fecha_salida;";
        return consultarBBDD($query);
    }

?>
