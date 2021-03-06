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
       	<?php if(is_null($this->_js_callback)){ ?>
        var seleccion_de_cliente_otro = function(){
        	Ext.get("buscar_cliente_02").update("").hide();
			Ext.get("ClienteSelectorComponent_001").show();	
			cliente_seleccionado = null;
        }
       	<?php } ?>

        var seleccion_de_cliente = function(a,c){
		
		    cliente_seleccionado = c[0];
		
		    //console.log("Cliente seleccionado", cliente_seleccionado);
		
		    

		    <?php if(is_null($this->_js_callback)){ ?>
			    Ext.get("buscar_cliente_01").enableDisplayMode('block').hide();
			    var pphtml = "<h3 style='margin:0px'><a target=\"_blank\" href='clientes.ver.php?cid="+cliente_seleccionado.get("id_usuario")+"'>" + cliente_seleccionado.get("nombre") + " " + ( cliente_seleccionado.get("rfc") !== null ? " - " + cliente_seleccionado.get("rfc") : "") + "</a></h3>";
			    pphtml += "<a href='JAVASCRIPT:seleccion_de_cliente_otro();'>otro</a>";
				Ext.get("buscar_cliente_02").update(pphtml).show();
				Ext.get("ClienteSelectorComponent_001").hide();

			<?php } else { 
				echo $this->_js_callback . ".call( null, cliente_seleccionado);"; 
				
			} ?>
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
	            bodyPadding: 8,
				height: "49px",
	            layout: 'anchor',

	            items: [{
				    listeners :{
					    "select" : seleccion_de_cliente
				    },
	                xtype: 'combo',
	                store: ds,
	                emptyText : "Busque un cliente",
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
		                        return '<div><b>{nombre}</b></div>{rfc}';
		                    }
	                },
	                pageSize: 10
	            }]

                
	        });

        });//onReady

        </script>
		<div style="">

        <table border="0" style="width: 100%" >
			<tr id="SeleccionDeCliente">
				<td colspan="4">
					<div id="buscar_cliente_01" style="display:none">
						<p style="margin-bottom: 0px;"></p>
						<div style="margin-bottom: 15px;" id="ShoppingCartComponent_002"><!-- clientes --></div>				
					</div>
					<div id="buscar_cliente_02" style="display:none; margin-bottom: 0px"></div>						
				</td>
			</tr>
        </table>

		<div id="ClienteSelectorComponent_001"><!-- buscar clientes --></div>		        
		&nbsp;<bR>
		</div>
		<?php

	}//renderCmp
	
}
