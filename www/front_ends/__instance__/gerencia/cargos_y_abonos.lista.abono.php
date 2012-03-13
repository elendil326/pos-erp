<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

        $page->addComponent( new TitleComponent( "Lista de Abonos" ) );
		$page->addComponent( new MessageComponent( "Lista los abonos realizados" ) );
		
   		$page->addComponent( new TitleComponent( "Abonos a venta", 2 ) );

        list($abonos_compra, $abonos_venta, $abonos_prestamo) = CargosYAbonosController::ListaAbono(
            $compra = true, 
            $prestamo = true, 
            $venta = true, 
            $cancelado = null, 
            $fecha_actual = null, 
            $fecha_maxima = null, 
            $fecha_minima = null, 
            $id_caja = null, 
            $id_compra = null, 
            $id_empresa = null, 
            $id_prestamo = null, 
            $id_sucursal = null, 
            $id_usuario = null, 
            $id_venta = null, 
            $monto_igual_a = null, 
            $monto_mayor_a = null, 
            $monto_menor_a = null, 
            $orden = null
        );

		$tabla_abonos_venta = new TableComponent( 
			array(
				"id_abono_venta" => "ID",
                "id_venta" => "Venta",
                "id_sucursal" => "Sucursal",
                "monto" => "Monto",
                "id_caja" => "Caja",
                "id_deudor" => "Deudor",
                "id_receptor" => "Recibio",
                "nota" => "Nota",
                "fecha" => "Fecha",
                "tipo_de_pago" => "Pago"/*,
                "cancelado" => "Cancelado",
                "motivo_cancelacion" => "Motivo",*/
			),
			$abonos_venta
		);
                

        function nombre_deudor($id_usuario, $obj){
            return UsuarioDAO::getByPK($id_usuario)->getNombre();
        }

        function formatMonto($monto, $obj){

            setlocale(LC_MONETARY, 'es_MX');

            $monto = money_format('%(#10.2n', $monto) . "\n";

            if($obj["cancelado"] == 1){
                return "<font style = \"color:red; display:inline;\" >$" . $monto . "</font>";
            }

            return "<font style = \"display:inline;\" >$" . $monto . "</font>";
        }

        $tabla_abonos_venta->addColRender("id_deudor", "nombre_deudor");
        $tabla_abonos_venta->addColRender("id_receptor", "nombre_deudor");
        $tabla_abonos_venta->addColRender("monto", "formatMonto");        
		$tabla_abonos_venta->addOnClick( "id_abono_venta", "(function(a){ alert('ok'); })" );
		
			
		$page->addComponent( $tabla_abonos_venta );



		$page->render();
