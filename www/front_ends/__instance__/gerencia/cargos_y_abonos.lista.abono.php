<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

        $page->addComponent( new TitleComponent( "Lista de Abonos" ) );
		$page->addComponent( new MessageComponent( "Lista los abonos realizados" ) );	

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


        function nombre_deudor($id_usuario, $obj){

            if( ! UsuarioDAO::getByPK($id_usuario) ){
                return "";
            }

            return "<font style = \"cursor:pointer;\" onClick = \"(function(){ window.location = 'clientes.ver.php?cid={$id_usuario}'; })();\" >" . UsuarioDAO::getByPK($id_usuario)->getNombre() . "</font>";
        }

        function formatMonto($monto, $obj){

            $monto =  "$&nbsp;<b>" . number_format( (float)$monto, 2 ) . "</b>";		

            if($obj["cancelado"] == 1){
                return "<font style = \"color:red; display:inline;\" >" . $monto . "</font>";
            }

            return "<font style = \"display:inline;\" >" . $monto . "</font>";
        }

        function toDate($fecha){
            return date( "d/m/y h:i:s A", strtotime($fecha) );
        }

        function descripcion_caja($id_caja){

            if($caja = CajaDAO::getByPK($id_caja) ){
                return $caja->getDescripcion();
            }

            return "";
        }

        function detalle_venta($id_venta){
            return "<font style = \"cursor:pointer;\" onClick = \"(function(){ window.location = 'ventas.detalle.php?vid={$id_venta}'; })();\" >{$id_venta}</font>";
        }

        function descripcion_sucursal($id_sucursal){

            if( ! SucursalDAO::getByPK($id_sucursal)){
                return "";
            }

            return "<font style = \"cursor:pointer;\" onClick = \"(function(){ window.location = 'sucursales.ver.php?sid={$id_sucursal}'; })();\" >" . SucursalDAO::getByPK($id_sucursal)->getRazonSocial() . "</font>";
        }

        //ABONOS A VENTA    

   		$page->addComponent( new TitleComponent( "Abonos a venta", 2 ) );

		$tabla_abonos_venta = new TableComponent( 
			array(
				"id_abono_venta" => "ID",
                "id_venta" => "Venta",
                "id_sucursal" => "Sucursal",
                "monto" => "Monto",
                "id_caja" => "Caja",
                "id_deudor" => "Deudor",
                "id_receptor" => "Recibio",
                //"nota" => "Nota",
                "fecha" => "Fecha",
                "tipo_de_pago" => "Pago"/*,
                "cancelado" => "Cancelado",
                "motivo_cancelacion" => "Motivo",*/
			),
			$abonos_venta
		);
                        

        $tabla_abonos_venta->addColRender("id_deudor", "nombre_deudor");
        $tabla_abonos_venta->addColRender("id_receptor", "nombre_deudor");
        $tabla_abonos_venta->addColRender("monto", "formatMonto");        
        $tabla_abonos_venta->addColRender("fecha", "toDate");        
        $tabla_abonos_venta->addColRender("id_caja","descripcion_caja");
        $tabla_abonos_venta->addColRender("id_venta","detalle_venta");
		//$tabla_abonos_venta->addOnClick( "id_venta", "(function(a){ alert('okoooo'); })" );
		
			
		$page->addComponent( $tabla_abonos_venta );

        
        //ABONOS A COMPRA

   		$page->addComponent( new TitleComponent( "Abonos a compra", 2 ) );

        $tabla_abonos_compra = new TableComponent( 
			array(
				"id_abono_compra" => "ID",
                "id_compra" => "Compra",
                "id_sucursal" => "Sucursal",
                "monto" => "Monto",
                "id_caja" => "Caja",
                "id_deudor" => "Deudor",
                "id_receptor" => "Recibio",
                //"nota" => "Nota",
                "fecha" => "Fecha",
                "tipo_de_pago" => "Pago"/*,
                "cancelado" => "Cancelado",
                "motivo_cancelacion" => "Motivo",*/
			),
			$abonos_compra
		);
                        

       

        $tabla_abonos_compra->addColRender("id_deudor", "nombre_deudor");
        $tabla_abonos_compra->addColRender("id_receptor", "nombre_deudor");
        $tabla_abonos_compra->addColRender("monto", "formatMonto");        
        $tabla_abonos_compra->addColRender("fecha", "toDate");        
        $tabla_abonos_compra->addColRender("id_caja","descripcion_caja");
		//$tabla_abonos_compra->addOnClick( "id_abono_venta", "(function(a){ alert('ok'); })" );
		
			
		$page->addComponent( $tabla_abonos_compra );


        //ABONOS A PRESTAMO

   		$page->addComponent( new TitleComponent( "Abonos a prestamo", 2 ) );

        $tabla_abonos_prestamo = new TableComponent( 
			array(
				"id_abono_prestamo" => "ID",
                "id_prestamo" => "Prestamo",
                "id_sucursal" => "Sucursal",
                "monto" => "Monto",
                "id_caja" => "Caja",
                "id_deudor" => "Deudor",
                "id_receptor" => "Recibio",
                //"nota" => "Nota",
                "fecha" => "Fecha",
                "tipo_de_pago" => "Pago"/*,
                "cancelado" => "Cancelado",
                "motivo_cancelacion" => "Motivo",*/
			),
			$abonos_prestamo
		);                           

        $tabla_abonos_prestamo->addColRender("id_deudor", "nombre_deudor");
        $tabla_abonos_prestamo->addColRender("id_receptor", "nombre_deudor");
        $tabla_abonos_prestamo->addColRender("monto", "formatMonto");        
        $tabla_abonos_prestamo->addColRender("fecha", "toDate");        
        $tabla_abonos_prestamo->addColRender("id_caja","descripcion_caja");
		//$tabla_abonos_prestamo->addOnClick( "id_abono_venta", "(function(a){ alert('ok'); })" );
					
		$page->addComponent( $tabla_abonos_prestamo );




		$page->render();
