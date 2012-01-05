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


	var cliente_seleccionado = null;
	var seleccion_de_cliente = function(a,c){
		cliente_seleccionado = c[0];
		
		console.log("Cliente seleccionado", cliente_seleccionado);
		
		Ext.get("buscar_cliente_01").enableDisplayMode('block').hide();
		var pphtml = "<h2 style='margin-bottom:0px'>Venta para <a href='clientes.ver.php?cid="+cliente_seleccionado.get("id_cliente")+"'>" + cliente_seleccionado.get("nombre") + "</a></h2>";

		if( cliente_seleccionado.get("rfc") !== null )
			pphtml += "<p>" + cliente_seleccionado.get("rfc") + "</p>";
					
		pphtml += "<br><div class='POS Boton' onClick='buscar_cliente()'  >Buscar otro cliente</div>";
		
		Ext.get("buscar_cliente_02").update(pphtml).show();
	};
	
	var buscar_cliente = function(){
		Ext.get("buscar_cliente_02").enableDisplayMode('block').hide();
		Ext.get("buscar_cliente_01").show();

	}
	

	var seleccionar_producto = function( a, p ){
		
		console.log( "Seleccionando producto", p );
		//al seleccionar el producto
		//agergarlo al store del carrito
		carrito_store.add( p[0] );
	}
	
	
	
	var carrito_store;

	
	var doVenta = function (){
		
		obj = {
			retencion : 0,
			descuento : 0,
			tipo_venta : "contado",
			impuesto : 0,
			subtotal: 5,
			total : 5,
			id_comprador_venta: 5,
			id_sucursal: 9,
			detalle_venta : Ext.JSON.encode( [{
				id_producto : 5,
				cantidad : 1,
				id_almacen : 0,
				precio: 5,
				descuento: 0,
				impuesto: 0,
				retencion: 0,
				id_unidad: 1
			}] )
		};
		
		POS.API.GET("api/ventas/nueva/", obj, function(r){
			console.log(r);
		});
		
	}
	
	
	
	
	
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
					auth_token : Ext.util.Cookies.get("a_t")
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
				{name: 'facturar_a_terceros', 	mapping: 'facturar_a_terceros'},
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
					auth_token : Ext.util.Cookies.get("a_t")
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
				{name: 'tarifas',				mapping: 'tarifas'}				
					
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
	            hideTrigger:false,
	            anchor: '100%',
				listeners :{
					"select" : seleccionar_producto
				},
	            listConfig: {
	                loadingText: 'Buscando...',
	                emptyText: 'No se encontraron productos.',

	                // Custom rendering template for each item
	                getInnerTpl: function() {
	                    return '<h3>{nombre_producto}-{id_producto}</h3>';
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
		           { name: 'precio',  				type: 'float'}
		        ]
		    });

		    // create the Grid
		    var grid = Ext.create('Ext.grid.Panel', {
		        store: carrito_store,
		        stateful: true,
		        stateId: 'stateGrid',
		        columns: [
		            {
		                text     : 'id_producto',
						width	 : 75,
		                sortable : false,
		                dataIndex: 'id_producto'
		            },
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
		                sortable : false,
						renderer : function(){
							return '<input type="text" >';
						}
		            },		
		            {
		                text     : 'tarifas',
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
								return 0;
							}
							var tf = cliente_seleccionado.get("id_tarifa_venta");
							
							for (var i=0; i < tarifasArray.length; i++) {
								console.log(tarifasArray[i].id_tarifa, tf)
								if(tarifasArray[i].id_tarifa == tf){
									return tarifasArray[i].precio;
								}
							};
							
							return "X";
						}
		            },		
		            {
		                text     : 'descripcion',
		                width    : 75,
		                sortable : true,
		                dataIndex: 'descripcion'
		            }
		        ],
		        height: 350,
		        width: "100%",
		        renderTo: 'grid-example',
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

			<div id="buscar_cliente_01">
				<p style="margin-bottom: 0px;">Buscar cliente</p>
				<div style="margin-bottom: 15px;" id="ShoppingCartComponent_002"><!-- clientes --></div>				
			</div>
			
			<div id="buscar_cliente_02" style="display:none; margin-bottom: 10px">
			</div>


			<p style="margin-bottom: 0px;">Buscar productos</p>				
			<div id="ShoppingCartComponent_001"><!-- buscar productos --></div>
			
			<select name="some_name" id="some_name">
				<option>tarifas</option>
				<option>tarifas3</option>
				
			</select>
			
			<h2 style="margin-bottom:0px">Esta venta</h2>
			<div id="carrito_de_compras" style="margin: 5px auto;">
				<div id="grid-example">
					
				</div>
				
			</div>
			<div class="POS Boton" onClick="cancelarVenta()">Cancelar</div>
			<div class="POS Boton" onClick="doVenta()">Vender</div>
			
		<?php
	}
}


