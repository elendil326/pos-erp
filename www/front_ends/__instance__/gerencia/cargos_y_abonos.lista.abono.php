<?php 


	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();

    $page->addComponent( new TitleComponent( "Lista de Abonos" ) );
	$page->addComponent( new MessageComponent( "Lista los abonos realizados" ) );	

    $page->partialRender();

?>

    <form name = "filtro" id = "filtro" style = "margin-top:10px; margin-bottom:20px; position:relative; float:left; width:100%; background:rgb(237,239,244);">
        <div>
            <input class="POS Boton OK" style = "position:relative; float:right;" type = "button" value = "Aceptar" onClick = "nuevaOrdenServicio()" />
        </div>
    </form>

    <form name = "lista_abono" id = "lista_abono">
        <table width = 100% border = 0 >
            <tr>
                <td><label>Fecha Entrega</label></td>
                <td><div id = "render_date">&nbsp;</div></td>
                <td>
                    <label>Servicio</label>
                </td>
                <td>
                    <select name = "id_servicio" id = "id_servicio" onChange = "formatForm()" >
                        <?php
        
                            $options = "<option value = null>-------</option>";

                            foreach(ServicioDAO::getAll() as $servicio){
                                $options .= "<option value = \"{$servicio->getIdServicio()}-{$servicio->getMetodoCosteo()}-{$servicio->getCostoEstandar()}-{$servicio->getPrecio()}\">{$servicio->getNombreServicio()}</option>";
                            }

                            echo $options;
            
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label>Precio</label></td>
                <td><input type = "text" disabled = true name = "precio" id = "precio" value = "" /></td>
                <td><label>Adelanto</label></td>
                <td><input type = "text" name = "adelanto" id = "adelanto"value = "" /></td>
            <tr>
                <td><label>Descripci&oacuten</label></td>
                <td><textarea style = "width:100%; height = 100%;" name = "descripcion" id = "descripcion"></textarea></td>
            </tr>
            <tr>
                <td colspan = "4"  align="right" style = "border-width:0px; background:#EDEFF4;">
                    <input class="POS Boton OK" style = "position:relative; float:right;" type = "button" value = "Aceptar" onClick = "nuevaOrdenServicio()" /> <input class="POS Boton" style = "position:relative; float:right;" type = "reset" value = "Cancelar" /> <input style = "display:none;" name = "id_cliente" id = "id_cliente" type = "hidden" value = ""/>
                </td>
            </tr>
        </table>
    </form>

    <script>

        var fecha_entrega = Ext.create('Ext.form.field.Date', {
            name : 'fecha_entrega',         
            style : {
                marginTop : '-10px'
            },
            anchor: '100%',
            name: 'fecha',
            value: new Date(),  // defaults to today           
            renderTo: "render_date"
        });

        function asignaCliente(record){
            Ext.get('id_cliente').dom.value = record.get('id_usuario');   
        }

        function formatForm(){

            Ext.get('precio').dom.disabled = true;
            Ext.get('precio').dom.value = "";

            var option = Ext.get('id_servicio').dom.options[Ext.get('id_servicio').dom.options.selectedIndex].value.split("-");

            if( option[1] == "variable" ){
                Ext.get('precio').dom.value = "";
                Ext.get('precio').dom.disabled = false;
            }

            if( option[1] == "costo" ){
                Ext.get('precio').dom.value = option[2];
            }

            if( option[1] == "precio" ){
                Ext.get('precio').dom.value = option[3];
            }

        }

        function nuevaOrdenServicio(){

    
            var option = Ext.get('id_servicio').dom.options[Ext.get('id_servicio').dom.options.selectedIndex].value.split("-");

            var fecha = fecha_entrega.getRawValue().split("/");

            POS.API.POST(
                "api/servicios/orden/nueva/", 
                {
                    "id_cliente" : Ext.get('id_cliente').getValue(),
                    "id_servicio" : option[0] ,
                    "adelanto" : Ext.get('adelanto').dom.value,
                    //"cliente_reporta" : ,
                    //"condiciones_de_recepcion" :  ,
                    "descripcion" :  Ext.get('descripcion').getValue(),
                    "fecha_entrega" :  Math.round((new Date( fecha[2], fecha[0], fecha[1] )).getTime() / 1000),
                    //"fotografia" :  "",
                    "precio" :  Ext.get('precio').getValue()
                }, 
                {

                    callback : function(a){ 

                        window.onbeforeunload = function(){}

                        window.location = "servicios.lista.orden.php"; 

                    }
                }
            );
        }
        
    </script>

    <?php

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

            return "<font title = \"Ir a detalles del usuario\" style = \"cursor:pointer;\" onClick = \"(function(){ window.location = 'clientes.ver.php?cid={$id_usuario}'; })();\" >" . UsuarioDAO::getByPK($id_usuario)->getNombre() . "</font>";
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
            return "<font title = \"Ir a detalle venta\" style = \"cursor:pointer;\" onClick = \"(function(){ window.location = 'ventas.detalle.php?vid={$id_venta}'; })();\" >{$id_venta}</font>";
        }

        function descripcion_sucursal($id_sucursal){

            if( ! SucursalDAO::getByPK($id_sucursal)){
                return "";
            }

            return "<font title = \"Ir a sucursal\" style = \"cursor:pointer;\" onClick = \"(function(){ window.location = 'sucursales.ver.php?sid={$id_sucursal}'; })();\" >" . SucursalDAO::getByPK($id_sucursal)->getRazonSocial() . "</font>";
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
