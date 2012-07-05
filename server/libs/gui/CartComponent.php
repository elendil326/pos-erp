<?php



abstract class CartComponent
{
	
	protected $cartType ; /* 'compra' , 'venta' */ 


    function __construct()
    {
	
    }

	
	protected function printOnReadyJs(){
		?>
		
	
	

			/** *****************************************************************
			  * LOTES
			  *
			  * ***************************************************************** */
			Ext.define("Lote", {
		        extend: 'Ext.data.Model',
		        proxy: {
		            type: 'ajax',
					url : '../api/almacen/lote/buscar/',
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
					{name: 'id_lote', 				mapping: 'id_lote'},
					{name: 'id_almacen', 			mapping: 'id_almacen'},
					{name: 'folio', 				mapping: 'folio'},
		        ]
		    });

		  var   lotes = Ext.create('Ext.data.Store', {
		        pageSize: 10,
		        model: 'Lote'
		    });		

		   
			/** *****************************************************************
			  * /LOTES
			  *
			  * ***************************************************************** */




		/** *****************************************************************
		  * Unidades
		  *
		  * ***************************************************************** */
		Ext.define("UnidadMedida", {
	        extend: 'Ext.data.Model',
	        proxy: {
	            type: 'ajax',
				url : '../api/producto/udm/unidad/buscar/',
				extraParams : {
					auth_token : Ext.util.Cookies.get("at")
				},
	            reader: {
	                type: 'json',
	                root: 'resultados',
	                totalProperty: 'numero_de_resultados'
	            }
	        },
			idProperty : 'id_unidad_medida',
	        fields: [
				{name: 'id_unidad_medida', 				mapping: 'id_unidad_medida', type : 'int' },
				{name: 'id_categoria_unidad_medida', 	mapping: 'id_categoria_unidad_medida'},
				{name: 'descripcion', 					mapping: 'descripcion'},
				{name: 'abreviacion', 					mapping: 'abreviacion'},
				{name: 'tipo_unidad_medida', 			mapping: 'tipo_unidad_medida'},
				{name: 'factor_conversion', 			mapping: 'factor_conversion'},
				{name: 'activa', 						mapping: 'activa'},
	        ]
	    });
	
		unidadMedida = Ext.create('Ext.data.Store', {
		        pageSize: 10,
		        model: 'UnidadMedida',
				autoLoad: true
		    });		


			/** *****************************************************************
			  * /Unidades
			  *
			  * ***************************************************************** */
			
			
			
			
			
			/** *****************************************************************
			  * CLIENTES
			  *
			  * ***************************************************************** */
		    Ext.define("Cliente", {
		        extend: 'Ext.data.Model',
		        proxy: {
		            type: 'ajax',
					<?php
						switch($this->cartType){
							case "compra":
							echo "url : '../api/proveedor/lista/',";
							break;
							
						
							case "venta":
							echo "url : '../api/cliente/buscar/',";
							break;
						}
					?>

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
					{name: 'id_unidad_compra', 		mapping: 'id_unidad_compra'},					
					{name: 'metodo_costeo', 		mapping: 'metodo_costeo'},
					{name: 'nombre_producto', 		mapping: 'nombre_producto'},
					{name: 'peso_producto', 		mapping: 'peso_producto'},
					{name: 'precio',				mapping: 'precio'},
					{name: 'tarifas',				mapping: 'tarifas'},			
					{name: 'existencias',			mapping: 'existencias'},
					{name: 'cantidad' 				/* not in the original response */ },
					{name: 'query' 					/* not in the original response */ },
					{name: 'lote' 					/* not in the original response */ }
		        ]
		    });

		    pdts = Ext.create('Ext.data.Store', {
		        pageSize: 10,
		        model: 'Producto'
		    });		

		    Ext.create('Ext.panel.Panel', {
		        renderTo: "CartComponent_002",
		        title: '',
		        width: '100%',
		        bodyPadding: 10,
		        layout: 'anchor',

		        items: [{
		            xtype: 'combo',
		            store: pdts,
		            displayField: 'title',
		            id : "seleccion_producto_cart",
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
		        }]
		    });/* Ext.create */
			/** *****************************************************************
			  * /PRODUCTOS
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
		<?php
	}
	
	    
}
