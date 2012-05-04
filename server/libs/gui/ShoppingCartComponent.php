<?php


/**
 * ShoppingCartComponent
 * 
 * ShoppingCartComponent es un componente para crear carros de compras
 * facilmente, incluye una caja de busqueda para seleccionar productos
 * o bien.
 *
 * 
 * Hay unas cuantas cosas a considerar:
 * Hay que seleccionar un cliente o una caja comun.
 * Los productos pueden ser computestos, servicios, u otras cosas.
 * Hay otras caracteristicas como "PAPA FIANA" en los productos.
 * Considerar el promedio, o procesadas y originales y todo eso.
 * Manejar Remisiones.
 * Fecha.
 * Cambio de precios.
 * 
 * 
 * 
 * 
 * */
class ShoppingCartComponent implements GuiComponent {

	/**
	 * 
	 * 
	 * 
	 * */
	

	function __construct(){
		
	}

	

	function renderCmp()
	{
		?>
<script>

	Ext.require([
	    'Ext.data.*',
	    'Ext.form.*',
	    'Ext.grid.*',
	    'Ext.util.*',
	    'Ext.state.*'	
	]);
	
	var actualizar_carrito = function(){
		
		console.log("Actualizando el carrito");
		
		// 
		// Actualizar la tabla de productos 
		// seleccionados
		//
		grid.getView().refresh();
		


		// 
		// Calcular precios e importes
		//		
		var carrito_store_count = carrito_store.count();
		var subtotal = 0;
		var tarifaActual = 1;
		
		if(cliente_seleccionado !== null){
			tarifaActual = cliente_seleccionado.get("id_tarifa_venta");
		}

		for (var i=0; i < carrito_store_count; i++) {
			
			var p = carrito_store.getAt(i);
			
			var tarifasProducto = p.get("tarifas");

			for (var j=0; j < tarifasProducto.length; j++) {
				if(tarifasProducto[j].id_tarifa == tarifaActual){
						subtotal += parseFloat( tarifasProducto[j].precio ) * parseFloat( p.get("cantidad") ) ;
						break;
				}
			}
		};
		
		Ext.get("carrito_subtotal").update(Ext.util.Format.usMoney( subtotal ));
		Ext.get("carrito_total").update(Ext.util.Format.usMoney( subtotal ));
		
		
		
		// 
		// Buscar existencias
		//
		if(sucursal_seleccionada !== null){
			//
			// si hay una sucursal seleccionada
			// podemos calcular si hay existencias
			//
			for (var i=0; i < carrito_store_count; i++) {

				var p = carrito_store.getAt(i);

				var existencias = p.get("existencias");
				
				
				
				var found_existencias = false;
				
				for (var ei=0; ei < existencias.length; ei++) {
					//
					// buscar la sucursal que
					// tengo seleccionada
					//
					if( existencias[ei].id_sucursal == sucursal_seleccionada ){
						
						console.log(existencias[ei].id_sucursal,  sucursal_seleccionada)
						
						found_existencias = true;
						
						if( p.get("cantidad") > existencias[ei].cantidad ){
							
							console.warn("se necesitan" + p.get("cantidad") + " pero solo tengo "+ existencias[ei].cantidad);
						}else{
							console.log("quiero " + p.get("cantidad") + " y tengo "+ existencias[ei].cantidad);							
						}
						break;
					}
				}
				
				
				if(found_existencias === false){
					console.warn("No hay ningun tipo de existencias");
				}

				
			};
		}
	}


	
	var cliente_seleccionado = null;
	var seleccion_de_cliente = function(a,c){
		
		cliente_seleccionado = c[0];
		
		console.log("Cliente seleccionado", cliente_seleccionado);
		
		Ext.get("buscar_cliente_01").enableDisplayMode('block').hide();
		var pphtml = "<h3 style='margin:0px'>Venta para <a target=\"_blank\" href='clientes.ver.php?cid="+cliente_seleccionado.get("id_usuario")+"'>" + cliente_seleccionado.get("nombre") + "</a></h3>";

		if( cliente_seleccionado.get("rfc") !== null )
			pphtml += "<p>" + cliente_seleccionado.get("rfc") + "</p>";
					
		pphtml += "<br><div class='POS Boton' onClick='buscar_cliente()'  >Buscar otro cliente</div>";
		
		Ext.get("buscar_cliente_02").update(pphtml).show();

		actualizar_carrito();	
	};
	
	
	
	var validar_venta_a_credito = function (clienteStore, carrito){
		
		if(clienteStore === null){
			//no hay cliente seleccionado
			Ext.get("SeleccionDeCliente").highlight();
			return false;
		}
		
		var vac = clienteStore.get("limite_credito");
		
		if((vac === null)||( parseFloat(vac) === 0) ){
			//no tiene ventas a credito
			Ext.MessageBox.alert("Nueva venta", "El cliente "+clienteStore.get("nombre") + " no tiene ventas a credito.");
			return false;			
		}
		
		
		return true;
	}
	
	
	
	//
	// Valor default de venta
	// 
	var tipo_de_venta = "contado";
	
	var seleccion_tipo_de_venta = function(tipo){
		switch(tipo){
			case "credito" :
				console.log("seleccion_tipo_de_venta(credito)");
				validar_venta_a_credito(cliente_seleccionado, carrito_store );
				
			break;
			
			case "contado" :
				console.log("seleccion_tipo_de_venta(contado)");			
			break;
			
			default:
				throw new Exception( "seleccion_tipo_de_venta(): tipo invalido" );
		}
	}
	
	var buscar_cliente = function(){
		
		cliente_seleccionado = null;
		
		Ext.get("buscar_cliente_02").enableDisplayMode('block').hide();
		
		Ext.get("buscar_cliente_01").show();
		
		actualizar_carrito();

	}
	




	var seleccionar_producto = function( a, p ){
		
		console.log( "Seleccionando producto", p );
		
		//ponerle cantidad inicial de 1
		p[0].set("cantidad", 1);
		
		//agregar produco al store
		carrito_store.add( p[0] );
		
		actualizar_carrito();
	}
	
	var tipo_de_pago_seleccionado = "efectivo";
	

	
	var carrito_store;

	
	var doVenta = function (){
		
		//
		// Validar los datos
		// 
		if( sucursal_seleccionada === undefined || sucursal_seleccionada === null ){
			
			Ext.get("SeleccionDeSucursal").highlight();
			return;
			
		}

		
		//
		// crear un objeto con los productos
		//
		var detalle_de_venta = [];
		
		//
		// 
		// 
		for (var i=0; i < carrito_store.count(); i++) {

			var p = carrito_store.getAt(i);
			detalle_de_venta.push({
				id_producto : p.get("id_producto"),
				cantidad 	: p.get("cantidad"),
				precio		: 2,
				descuento	: 0,
				impuesto	: 0,
				retencion	: 0,
				id_unidad	: 1
			});
		}
		
		//
		// 
		// 
		ventaObj = {
			retencion 			: 0,
			descuento 			: 0,
			impuesto 			: 0,
			subtotal			: 5,
			total 				: 5,
			tipo_venta 			: "contado",
			id_comprador_venta	: cliente_seleccionado.get("id_usuario"),
			id_sucursal			: sucursal_seleccionada,
			detalle_venta 		: Ext.JSON.encode( detalle_de_venta )
		};
		
		//
		// Enviar al API
		// 
		POS.API.POST(
			"api/ventas/nueva/", 
			ventaObj,
			{
				callback : function(r){
					if(r.status === "ok"){
						window.location = "ventas.detalle.php?vid=" + r.id_venta + "&last_action=ok";
					}else{
						console.error(r);
						Ext.MessageBox.alert("Nueva venta", "Algo salio mal.");
					}
				}
			});
		
	}
	
	
	var cellEditing = Ext.create('Ext.grid.plugin.CellEditing', {
	        clicksToEdit: 1,
	        listeners: {
	            edit: function(){
	                // refresh summaries
	                actualizar_carrito();
	            }
	        }
	});
	
	
	var sucursal_seleccionada = null;
	
	var seleccionar_sucursal = function( sucursalStore ){
		//sucursalStore
		console.log(sucursalStore.get("id_sucursal") + " seleccionada...");
		
		sucursal_seleccionada = sucursalStore.get("id_sucursal");
		
		actualizar_carrito();
	};
	

	
	Ext.onReady(function(){



		/** *****************************************************************
		  * CLIENTES
		  *
		  * ***************************************************************** */
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
	        renderTo: "ShoppingCartComponent_002",
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
	        }]
	    });/* Ext.create */

		/** *****************************************************************
		  * /CLIENTES
		  *
		  * ***************************************************************** */		
		
		

		/** *****************************************************************
		  * PRODUCTOS
		  *
		  * ***************************************************************** */
	    Ext.define("Producto", {
	        extend: 'Ext.data.Model',
	        proxy: {
	            type: 'ajax',
				url : '../api/producto/buscar/',
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
				{name: 'activo', 				mapping: 'activo'},
				{name: 'codigo_de_barras', 		mapping: 'codigo_de_barras'},
				{name: 'codigo_producto', 		mapping: 'codigo_producto'},
				{name: 'compra_en_mostrador', 	mapping: 'compra_en_mostrador'},
				{name: 'control_de_existencia', mapping: 'control_de_existencia'},
				{name: 'costo_estandar', 		mapping: 'costo_estandar'},
				{name: 'costo_extra_almacen', 	mapping: 'costo_extra_almacen'},
				{name: 'descripcion', 			mapping: 'descripcion'},
				{name: 'foto_del_producto', 	mapping: 'foto_del_producto'},
				{name: 'garantia', 				mapping: 'garantia'},
				{name: 'id_producto', 			mapping: 'id_producto'},
				{name: 'id_unidad', 			mapping: 'id_unidad'},
				{name: 'metodo_costeo', 		mapping: 'metodo_costeo'},
				{name: 'nombre_producto', 		mapping: 'nombre_producto'},
				{name: 'peso_producto', 		mapping: 'peso_producto'},
				{name: 'precio',				mapping: 'precio'},
				{name: 'tarifas',				mapping: 'tarifas'},			
				{name: 'existencias',			mapping: 'existencias'},
				{name: 'cantidad' 				/* not in the original response */ }
	        ]
	    });
	
	    pdts = Ext.create('Ext.data.Store', {
	        pageSize: 10,
	        model: 'Producto'
	    });		
		
	    Ext.create('Ext.panel.Panel', {
	        renderTo: "ShoppingCartComponent_001",
	        title: '',
	        width: '100%',
	        bodyPadding: 10,
	        layout: 'anchor',

	        items: [{
	            xtype: 'combo',
	            store: pdts,
	            displayField: 'title',
	            typeAhead: true,
	            hideLabel: true,
				emptyText : "Buscando por descripcion, nombre o codigo de barras.",
	            hideTrigger:false,
	            anchor: '100%',
				listeners :{
					"select" : seleccionar_producto
				},
	            listConfig: {
	                loadingText: 'Buscando...',
	                emptyText: 'No se encontraron productos.',

	                // Custom rendering template for each item
	                getInnerTpl: function(a,b,c) {
						var html = "";
						html += "<h3 style='margin:0px'>{nombre_producto}</h3>";
						html += "<p>{descripcion}</p>";
						html += "{precio}";
	                    return html;
	                }
	            },
	            pageSize: 10
	        }, {
	            xtype: 'component',
	            style: 'margin-top:10px',
	            html: 'Buscando por descripcion, nombre o codigo de barras.'
	        }]
	    });/* Ext.create */
		/** *****************************************************************
		  * /PRODUCTOS
		  *
		  * ***************************************************************** */
		
		
		
		
		
		
		
		
		/** *****************************************************************
		  * CARRITO
		  *
		  * ***************************************************************** */


		    // create the data store
		    carrito_store = Ext.create('Ext.data.ArrayStore', {
		        fields: [
		           { name: 'id_producto',			type: 'int'},
		           { name: 'codigo_producto',     	type: 'int'},
		           { name: 'nombre_producto',     	type: 'string'},
		           { name: 'descripcion',  			type: 'string'},
		           { name: 'precio',  				type: 'float'},
		           { name: 'cantidad',  			type: 'float'}
		        ],
				listeners : {
					datachanged : actualizar_carrito
				}
		    });

		    // create the Grid
		    var grid = Ext.create('Ext.grid.Panel', {
		        store: carrito_store,
				plugins: [cellEditing],
		        stateful: true,
		        stateId: 'stateGrid',
		        columns: [
		            {
		                text     : 'codigo_producto',
		                width    : 75,
		                sortable : false,
		                dataIndex: 'codigo_producto'
		            },
		            {
		                text     : 'nombre_producto',
		                flex     : 1,
		                sortable : true,
		                dataIndex: 'nombre_producto'
		            },
		            {
		                text     : 'cantidad',
						dataIndex: 'cantidad',
		                sortable : false,
						renderer : function(x){
							if(x === undefined)  x = 1;
							return x + ' Qty';
						},
						field: {
			                xtype: 'numberfield'
			            }
						
		            },		
		            {
		                text     : 'Precio',
		                flex     : 1,
		                sortable : true,
		                dataIndex: 'tarifas',
						renderer : function(tarifasArray){
							/* ***** **** ***** 
								tarifasArray tiene las tarifas para 
								este producto solo hay que ver que cliente
								esta seleccionado para mostrar la adecuada
							***** **** ***** */
							if(cliente_seleccionado == null){
								return Ext.util.Format.usMoney( 0 );								
							}
							
							var tf = cliente_seleccionado.get("id_tarifa_venta");
							
							for (var i=0; i < tarifasArray.length; i++) {
								if(tarifasArray[i].id_tarifa == tf){
									return Ext.util.Format.usMoney( tarifasArray[i].precio );
								}
							}
							
							return "X";
						}
		            },		
		            {
		                text     : 'Importe',
		                width    : 75,
		                sortable : true,
						renderer : function(a,b,producto){

							if(cliente_seleccionado == null){
								return Ext.util.Format.usMoney( producto.get("cantidad") );
							}
							
							var tf = cliente_seleccionado.get("id_tarifa_venta");
							var tarifasArray = producto.get("tarifas");
							
							for (var i=0; i < tarifasArray.length; i++) {
								if(tarifasArray[i].id_tarifa == tf){
									return Ext.util.Format.usMoney( 
										parseFloat(tarifasArray[i].precio ) * parseFloat(producto.get("cantidad")) );
								}
							};
						}
		            }
		        ],
		        height: 350,
		        width: "100%",
		        renderTo: 'carrito_de_compras_grid',
		        viewConfig: {
		            stripeRows: true
		        }
		    });		
		
		/** *****************************************************************
		  * CARRITO
		  *
		  * ***************************************************************** */
		
	}); /* Ext.onReady */
		

</script>
			<h2>Nueva venta</h2>

			<table border="0" style="width: 100%">
				<tr id="SeleccionDeCliente">
					<td colspan="4">
						<div id="buscar_cliente_01">
							<p style="margin-bottom: 0px;">Buscar cliente</p>
							<div style="margin-bottom: 15px;" id="ShoppingCartComponent_002"><!-- clientes --></div>				
						</div>
						<div id="buscar_cliente_02" style="display:none; margin-bottom: 0px"></div>						
					</td>
				</tr>
				<tr>
					<td id="SeleccionDeSucursal">
						Sucursal:
						<div >
							<?php
								$selector_de_suc = new SucursalSelectorComponent();
								$selector_de_suc->addJsCallback("seleccionar_sucursal");
								$selector_de_suc->renderCmp();
							?>						
						</div>
					</td>
					<td id="SeleccionDeTipoDeVenta">
						Tipo de venta:
						<div >
							<select onChange="seleccion_tipo_de_venta(this.value)">
								<option value="contado" selected>Contado</option>
								<option value="credito">Credito</option>
							</select>
						</div>
					</td>
					<td id="SeleccionDeImpuestos">
						Impuestos que aplicaran a esta venta:
						<div >
	
						</div>
					</td>
					<td id="SeleccionDeTipoDePago">
						Tipo de pago:
						<div >
							<select name="" onChange="tipo_de_pago_seleccionado = this.value">
								<option value="efectivo">efectivo</option>
								<option value="cheque">cheque</option>
							</select>
						</div>
					</td>										
				</tr>
				<tr>
					<td id="SeleccionDeDescuento">
						Descuento:
						<input type="text" name="some_name" value="" id="">
						<select name="" id="">
							<option value="porciento">%</option>
							<option value="pesos">pesos</option>
						</select>
					</td>										
				</tr>
				
			</table>

	

			



			
			<div id="ShoppingCartComponent_001"><!-- buscar productos --></div>


			<div id="carrito_de_compras" style="margin: 5px auto;">
				<div id="carrito_de_compras_grid"></div>
			</div>
			
			
			<table>
				<tr>
					<td>Subtotal</td>
					<td id="carrito_subtotal"></td>
				</tr>
				<tr>
					<td>Total</td>
					<td id="carrito_total"></td>
				</tr>				
			</table>
			
			<div class="POS Boton" onClick="cancelarVenta()">Cancelar</div>
			<div class="POS Boton" onClick="doVenta()">Vender</div>
			
		<?php
	}
}


