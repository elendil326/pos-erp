<?php

class ClienteSelectorComponent implements GuiComponent{
	
	
	
	private $_js_callback;
	
	
	
	
	public function addJsCallback($_js_callback){
		$this->_js_callback = $_js_callback;
	}
	
	
	//
	// 
	// 
	public function renderCmp(){

        ?>
		
		<script>

        var cliente_seleccionado = null;

        var seleccion_de_cliente = function(a,c){
		
		    cliente_seleccionado = c[0];
		
		    console.log("Cliente seleccionado", cliente_seleccionado);
		
		    Ext.get("buscar_cliente_01").enableDisplayMode('block').hide();
		    var pphtml = "<h3 style='margin:0px'>Usuario : <a target=\"_blank\" href='clientes.ver.php?cid="+cliente_seleccionado.get("id_usuario")+"'>" + cliente_seleccionado.get("nombre") + "</a></h3>";

		    if( cliente_seleccionado.get("rfc") !== null )
			    pphtml += "<p>" + cliente_seleccionado.get("rfc") + "</p>";

		    //pphtml += "<br><div class='POS Boton' onClick='alert(\"dd\")'>Seleccionar Usuario</div>";
		
		    Ext.get("buscar_cliente_02").update(pphtml).show();

	    };

      	Ext.onReady(function(){				

	        Ext.define("Cliente", {
	            extend: 'Ext.data.Model',
	            proxy: {
	                type: 'ajax',
				    url : '../api/cliente/buscar/',
				    extraParams : {
					    auth_token : Ext.util.Cookies.get("at")
				    },
	                reader: {
	                    type: 'json',
	                    root: 'resultados',
	                    totalProperty: 'numero_de_resultados'
	                }
	            },

	            fields: [

				    {name: 'activo',		 		mapping: 'activo'},
				    {name: 'codigo_usuario', 		mapping: 'codigo_usuario'},
				    {name: 'comision_ventas', 		mapping: 'comision_ventas'},
				    {name: 'consignatario', 		mapping: 'consignatario'},
				    {name: 'correo_electronico', 	mapping: 'correo_electronico'},
				    {name: 'cuenta_bancaria', 		mapping: 'cuenta_bancaria'},
				    {name: 'cuenta_de_mensajeria', 	mapping: 'cuenta_de_mensajeria'},
				    {name: 'curp', 					mapping: 'curp'},
				    {name: 'denominacion_comercial', mapping: 'denominacion_comercial'},
				    {name: 'descuento', 			mapping: 'descuento'},
				    {name: 'dia_de_pago', 			mapping: 'dia_de_pago'},
				    {name: 'dia_de_revision', 		mapping: 'dia_de_revision'},
				    {name: 'dias_de_credito', 		mapping: 'dias_de_credito'},
				    {name: 'dias_de_embarque', 		mapping: 'dias_de_embarque'},
				    {name: 'facturar_a_terceroserceros', 	mapping: 'facturar_a_terceros'},
				    {name: 'fecha_alta', 			mapping: 'fecha_alta'},
				    {name: 'fecha_asignacion_rol', 	mapping: 'fecha_asignacion_rol'},
				    {name: 'fecha_baja', 			mapping: 'fecha_baja'},
				    {name: 'id_clasificacion_cliente', 		mapping: 'id_clasificacion_cliente'},
				    {name: 'id_clasificacion_proveedor', 	mapping: 'id_clasificacion_proveedor'},
				    {name: 'id_direccion', 					mapping: 'id_direccion'},
				    {name: 'id_direccion_alterna', 			mapping: 'id_direccion_alterna'},
				    {name: 'id_moneda', 					mapping: 'id_moneda'},
				    {name: 'id_rol', 						mapping: 'id_rol'},
				    {name: 'id_sucursal', 					mapping: 'id_sucursal'},
				    {name: 'id_tarifa_compra', 				mapping: 'id_tarifa_compra'},
				    {name: 'id_tarifa_venta', 				mapping: 'id_tarifa_venta'},
				    {name: 'id_usuario', 					mapping: 'id_usuario'},
				    {name: 'intereses_moratorios', 			mapping: 'intereses_moratorios'},
				    {name: 'last_login',					mapping: 'last_login'},
				    {name: 'limite_credito', 				mapping: 'limite_credito'},
				    {name: 'mensajeria',					mapping: 'mensajeria'},
				    {name: 'nombre', 						mapping: 'nombre'},
				    {name: 'pagina_web', 					mapping: 'pagina_web'},
				    {name: 'representante_legal', 			mapping: 'representante_legal'},
				    {name: 'rfc', 							mapping: 'rfc'},
				    {name: 'salario', 						mapping: 'salario'},
				    {name: 'saldo_del_ejercicio', 			mapping: 'saldo_del_ejercicio'},
				    {name: 'tarifa_compra_obtenida', 		mapping: 'tarifa_compra_obtenida'},
				    {name: 'tarifa_venta_obtenida', 		mapping: 'tarifa_venta_obtenida'},
				    {name: 'telefono_personal1', 			mapping: 'telefono_personal1'},
				    {name: 'telefono_personal2', 			mapping: 'telefono_personal2'},
				    {name: 'tiempo_entrega', 				mapping: 'tiempo_entrega'},
				    {name: 'ventas_a_credito', 				mapping: 'ventas_a_credito'}
	            ]
	        });
	
	        ds = Ext.create('Ext.data.Store', {
	            pageSize: 10,
	            model: 'Cliente'
	        });

	        Ext.create('Ext.panel.Panel', {
	            renderTo: "ClienteSelectorComponent_001",
	            title: '',
	            width: '100%',
	            bodyPadding: 10,
	            layout: 'anchor',

	            items: [{
				    listeners :{
					    "select" : seleccion_de_cliente
				    },
	                xtype: 'combo',
	                store: ds,
	                displayField: 'title',
	                typeAhead: true,
	                hideLabel: true,
	                hideTrigger:false,
	                anchor: '100%',

	                listConfig: {
	                    loadingText: 'Buscando...',
	                    emptyText: 'No se encontraron clientes.',

	                    // Custom rendering template for each item
	                     getInnerTpl: function() {
		                        return '<p>{nombre}</p>{rfc}';
		                    }
	                },
	                pageSize: 10
	            }],

                buttons: [{
                        text: 'Cancelar',
                        handler: function() {

                        }
                    }, {
			            text: 'Seleccionar este usuario',
			            handler: function() {
						    <?php echo $this->_js_callback; ?>
                        }
                }]
	        });

        });//onReady

        </script>
					
        <table border="0" style="width: 100%">
			<tr id="SeleccionDeCliente">
				<td colspan="4">
					<div id="buscar_cliente_01">
						<p style="margin-bottom: 0px;">Seleccione un usuario</p>
						<div style="margin-bottom: 15px;" id="ShoppingCartComponent_002"><!-- clientes --></div>				
					</div>
					<div id="buscar_cliente_02" style="display:none; margin-bottom: 0px"></div>						
				</td>
			</tr>
        </table>

		<div id="ClienteSelectorComponent_001"><!-- buscar productos --></div>		        

		<?php

	}//renderCmp
	
}
