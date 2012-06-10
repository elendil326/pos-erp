<?php


class BuyingCartComponent /* extends CartComponent */ implements GuiComponent
{
    function __construct()
    {
	
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
		    'Ext.state.*',
		    'Ext.selection.CellModel',
		]);

		var grid;

		/* ********************************************************
	     /\  \         /\  \         /\  \         /\  \          ___        /\  \         /\  \    
	    /::\  \       /::\  \       /::\  \       /::\  \        /\  \       \:\  \       /::\  \   
	   /:/\:\  \     /:/\:\  \     /:/\:\  \     /:/\:\  \       \:\  \       \:\  \     /:/\:\  \  
	  /:/  \:\  \   /::\~\:\  \   /::\~\:\  \   /::\~\:\  \      /::\__\      /::\  \   /:/  \:\  \ 
	 /:/__/ \:\__\ /:/\:\ \:\__\ /:/\:\ \:\__\ /:/\:\ \:\__\  __/:/\/__/     /:/\:\__\ /:/__/ \:\__\
	 \:\  \  \/__/ \/__\:\/:/  / \/_|::\/:/  / \/_|::\/:/  / /\/:/  /       /:/  \/__/ \:\  \ /:/  /
	  \:\  \            \::/  /     |:|::/  /     |:|::/  /  \::/__/       /:/  /       \:\  /:/  / 
	   \:\  \           /:/  /      |:|\/__/      |:|\/__/    \:\__\       \/__/         \:\/:/  /  
	    \:\__\         /:/  /       |:|  |        |:|  |       \/__/                      \::/  /   
	     \/__/         \/__/         \|__|         \|__|                                   \/__/ 
		 * ******************************************************** */
		var actualizar_carrito = function(){

			console.group("ACTUALIZAR CARRITO");

			console.log("Actualizando el carrito");



			// 
			// Calcular precios e importes
			//		
			var carrito_store_count = carrito_store.count();
			var subtotal = 0;
			var tarifaActual = 1;

			//tarifaActual = Ext.get("tarifa_seleccionada").getValue();
			//console.log("la tarifa actual es :" + tarifaActual);


			for (var i=0; i < carrito_store_count; i++) {

				var p = carrito_store.getAt(i);

				 console.log(i + " : " + p.get("precio") + "," + p.get("cantidad"), p);
				 
				var this_importe  =parseFloat( p.get("precio") ) * parseFloat( p.get("cantidad") ) ;
				
				subtotal += this_importe;
				 
				p.set("importe", this_importe);
				
			};


			data  = retriveData();
			
			Ext.get("carrito_subtotal").update(Ext.util.Format.usMoney( data.subtotal ));
			Ext.get("carrito_impuesto").update("+"  + Ext.util.Format.usMoney( data.impuesto ));
			Ext.get("carrito_total").update(Ext.util.Format.usMoney( data.subtotal + data.impuesto ));
			Ext.get("carrito_descuento").update( "-" + Ext.util.Format.usMoney( data.descuento ));			


			// 
			// Actualizar la tabla de productos 
			// seleccionados
			//
			grid.getView().refresh();
			
			
			

			console.groupEnd();
		}



		/* ****************************************************************************************************************
		      ___           ___                   ___           ___           ___           ___           ___     
		     /\  \         /\__\      ___        /\  \         /\__\         /\  \         /\  \         /\  \    
		    /::\  \       /:/  /     /\  \      /::\  \       /::|  |        \:\  \       /::\  \       /::\  \   
		   /:/\:\  \     /:/  /      \:\  \    /:/\:\  \     /:|:|  |         \:\  \     /:/\:\  \     /:/\ \  \  
		  /:/  \:\  \   /:/  /       /::\__\  /::\~\:\  \   /:/|:|  |__       /::\  \   /::\~\:\  \   _\:\~\ \  \ 
		 /:/__/ \:\__\ /:/__/     __/:/\/__/ /:/\:\ \:\__\ /:/ |:| /\__\     /:/\:\__\ /:/\:\ \:\__\ /\ \:\ \ \__\
		 \:\  \  \/__/ \:\  \    /\/:/  /    \:\~\:\ \/__/ \/__|:|/:/  /    /:/  \/__/ \:\~\:\ \/__/ \:\ \:\ \/__/
		  \:\  \        \:\  \   \::/__/      \:\ \:\__\       |:/:/  /    /:/  /       \:\ \:\__\    \:\ \:\__\  
		   \:\  \        \:\  \   \:\__\       \:\ \/__/       |::/  /     \/__/         \:\ \/__/     \:\/:/  /  
		    \:\__\        \:\__\   \/__/        \:\__\         /:/  /                     \:\__\        \::/  /   
		     \/__/         \/__/                 \/__/         \/__/                       \/__/         \/__/                                                                                                                                                                                                
		   ****************************************************************************************************************  */

		var cliente_seleccionado = null;
		var seleccion_de_cliente = function(a,c){

			cliente_seleccionado = c[0];

			console.log("Proveedor seleccionado", cliente_seleccionado);

			Ext.get("buscar_cliente_01").enableDisplayMode('block').hide();
			var pphtml = "<h3 style='margin:0px'>Compra de <a target=\"_blank\" href='clientes.ver.php?cid="+cliente_seleccionado.get("id_usuario")+"'>" + cliente_seleccionado.get("nombre") + "</a></h3>";

			if( cliente_seleccionado.get("rfc") !== null )
				pphtml += "<p>" + cliente_seleccionado.get("rfc") + "</p>";

			pphtml += "<br><div class='POS Boton' onClick='buscar_cliente()'  >Cambiar de proveedor</div>";

			Ext.get("buscar_cliente_02").update(pphtml).show();

			actualizar_carrito();	
		};



		/* ********************************************************
	      ___           ___           ___           ___           ___     
	     /\__\         /\  \         /\__\         /\  \         /\  \    
	    /:/  /        /::\  \       /::|  |        \:\  \       /::\  \   
	   /:/  /        /:/\:\  \     /:|:|  |         \:\  \     /:/\:\  \  
	  /:/__/  ___   /::\~\:\  \   /:/|:|  |__       /::\  \   /::\~\:\  \ 
	  |:|  | /\__\ /:/\:\ \:\__\ /:/ |:| /\__\     /:/\:\__\ /:/\:\ \:\__\
	  |:|  |/:/  / \:\~\:\ \/__/ \/__|:|/:/  /    /:/  \/__/ \/__\:\/:/  /
	  |:|__/:/  /   \:\ \:\__\       |:/:/  /    /:/  /           \::/  / 
	   \::::/__/     \:\ \/__/       |::/  /     \/__/            /:/  /  
	    ~~~~          \:\__\         /:/  /                      /:/  /   
	                   \/__/         \/__/                       \/__/ 
		 * ******************************************************** */
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
		var tipo_de_compra = "contado";

		var seleccion_tipo_de_compra = function(tipo){
			switch(tipo){
				case "credito" :
					console.log("seleccion_tipo_de_compra(credito)");
					//validar_venta_a_credito(cliente_seleccionado, carrito_store );
					tipo_de_compra = tipo;
				break;

				case "contado" :
					console.log("seleccion_tipo_de_compra(contado)");			
					tipo_de_compra = tipo;
				break;

				default:
					throw new Exception( "seleccion_tipo_de_compra(): tipo invalido" );
			}
		}

		var buscar_cliente = function(){

			cliente_seleccionado = null;

			Ext.get("buscar_cliente_02").enableDisplayMode('block').hide();

			Ext.get("buscar_cliente_01").show();

			actualizar_carrito();

		}



		
		function AddProduct( name ){

			var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';
			

            var form = Ext.widget('form', {
                layout: {
                    type: 'vbox',
                    align: 'stretch'
                },
                border: false,
                bodyPadding: 10,

                fieldDefaults: {
                    labelAlign: 'top',
                    labelWidth: 100,
                    labelStyle: 'font-weight:bold'
                },
                items: [{
                    xtype: 'fieldcontainer',
                    fieldLabel: 'Detalles del producto',
                    labelStyle: 'font-weight:bold;padding:0',
                    layout: 'hbox',
                    defaultType: 'textfield',

                    fieldDefaults: {
                        labelAlign: 'top'
                    },

                    items: [{
                        flex: 1,
                        name: 'codigo_producto',
                        afterLabelTextTpl: required,
                        fieldLabel: 'Codigo',
                        allowBlank: false
                    }, {
                        flex: 2,
                        name: 'nombre_producto',
                        afterLabelTextTpl: required,
                        fieldLabel: 'Nombre del producto',
                        allowBlank: false,
                        value: name,
                        margins: '0 0 0 5'
                    }]
                }, {
                    xtype: 'textfield',
                    fieldLabel: 'Metodo costeo',
                    name : "metodo_costeo",
                    afterLabelTextTpl: required,
                    allowBlank: false
                }, {
                    xtype: 'textfield',
                    name: 'id_unidad_compra',
                    fieldLabel: 'Unidad compra',
                    afterLabelTextTpl: required,
                    allowBlank: false
                }, {
                    xtype: 'textfield',
                    name : "codigo_de_barras",
                    fieldLabel: 'Codigo de barras',
                    afterLabelTextTpl: required,
                    allowBlank: true
                }],

                buttons: [{
                    text: 'Cancelar',
                    handler: function() {
                        
                        this.up('window').destroy();
                    }
                }, {
                    text: 'Guardar producto',
                    handler: function() {
                        if (this.up('form').getForm().isValid()) {
                        	
                        	var params = this.up("form").getValues();
                        	
                        	params.activo = 1;
                        	params.compra_en_mostrador = 0;
                        	
							var options = {
								callback: function(){
									Ext.getCmp("nuevo_producto_quick").destroy();
                        			//Ext.MessageBox.alert('Thank you!', 'Your inquiry has been sent. We will respond as soon as possible.');	
                        		}
							};
							
                        	POS.API.POST("api/producto/nuevo", params, options);

                        }


                        
                    }
                }]
            });

            Ext.widget('window', {
            	id : "nuevo_producto_quick",
                title: 'Nuevo Producto',
                closeAction: 'destroy',
                width: 500,
                height: 300,
                layout: 'fit',
                resizable: false,
                modal: true,
                items: form
            }).show();
        

		}


		var seleccionar_producto = function( a, p ){
			
			if( p[0].get("id_producto") == -99 ){
				AddProduct( p[0].get("query")  );
				return;
			}
			console.group("SELECCION DE PRODUCTO");

			console.log( "Seleccionando producto", p );

			//ponerle cantidad inicial de 1
			console.log("cantidad inicial de 1");
			
			p[0].set("cantidad", 1);

			//agregar produco al store
			carrito_store.add( p[0] );

			console.groupEnd();

			actualizar_carrito();
		}

		var tipo_de_pago_seleccionado = "efectivo";

		var seleccion_de_tarifa = function(id_tarifa){

			console.log("Tarifa seleccionada:" + id_tarifa);
			actualizar_carrito();
		}

		var carrito_store;

		var retriveData = function(){

				//
				// crear un objeto con los productos
				//
				var detalle_de_venta = [];

				//
				// 
				// 

				var carrito_store_count = carrito_store.count();
				var subtotal = 0;


				for (var i=0; i < carrito_store_count; i++) {

					var p = carrito_store.getAt(i);
 
					
					subtotal +=  parseFloat( p.get("precio") )  * parseFloat( p.get("cantidad") ) ;
					

					detalle_de_venta.push({
						id_producto : p.get("id_producto"),
						cantidad 	: p.get("cantidad"),
						precio		: p.get("precio"),
						lote		: p.get("lote"),
						descuento	: 0,
						impuesto	: 0,
						retencion	: 0,
						id_unidad	: p.get("id_unidad")
					});

				};


				//
				// 
				// 
				
				var impuesto = 0, descuento = 0;
				
				descuento = Ext.get("descuento_seleccionado_val").getValue();
				
				var dAplicado = (descuento/100) * subtotal;
				
				
				
				<?php
					//listar impuestos
					$i = ImpuestosController::Lista();
					$iLista = $i["resultados"];
					$impuestos_to = 0;
					//carrito_impuesto
					foreach ($iLista as $imp) {
						$impuestos_to = $imp->getMontoPorcentaje();
					}

					echo "impuesto = subtotal * " . $impuestos_to . ";";

				?>				
				
				
				ventaObj = {
					retencion 			: 0,
					descuento 			: dAplicado,
					impuesto 			: impuesto,
					subtotal			: subtotal,
					total 				: subtotal + impuesto,
					tipo_compra 		: tipo_de_compra,
					id_sucursal			: null,
					detalle 			: Ext.JSON.encode( detalle_de_venta ),
					id_empresa			: empresa_seleccionada
				};


				if(cliente_seleccionado == null){
					ventaObj.id_usuario_compra = null;
				}else{
					ventaObj.id_usuario_compra	= cliente_seleccionado.get("id_usuario");
				}


				return ventaObj;
		}


		var doCompra = function (){
			
			console.log("doComptra() called");
			
			var ventaObj = retriveData();
			
			console.log("ventaObj=", ventaObj);
			
			ventaObj.es_cotizacion = false;



			//
			// Enviar al API
			// 
			POS.API.POST(
				"api/compras/nueva/", 
				ventaObj,
				{
					callback : function(r){
						if(r.status === "ok"){
							window.location = "compras.detalle.php?cid=" + r.id_compra + "&last_action=ok";
							

						}else{
							console.error(r);
							Ext.MessageBox.alert("Nueva compra", "Algo salio mal.");
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


		var empresa_seleccionada = null;

		var seleccionar_empresa = function( empresa ){
			//sucursalStore
			console.log(empresa.get("id_empresa") + " seleccionada...");

			empresa_seleccionada = empresa.get("id_empresa");

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
					url : '../api/proveedor/lista/',
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
		        renderTo: "BuyingCartComponent_001",
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
						"select" : function(a,b,c){
							//console.log("LS:" , a,b,c);
							seleccionar_producto(a,b,c);
						}
						
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

			        fields: [
						{name: 'id_unidad_medida', 				mapping: 'id_unidad_medida'},
						{name: 'id_categoria_unidad_medida', 	mapping: 'id_categoria_unidad_medida'},
						{name: 'descripcion', 					mapping: 'descripcion'},
						{name: 'abreviacion', 					mapping: 'abreviacion'},
						{name: 'tipo_unidad_medida', 			mapping: 'tipo_unidad_medida'},
						{name: 'factor_conversion', 			mapping: 'factor_conversion'},
						{name: 'activa', 						mapping: 'activa'},
			        ]
			    });
			
			  var   unidadMedida = Ext.create('Ext.data.Store', {
			        pageSize: 10,
			        model: 'UnidadMedida'
			    });		


				/** *****************************************************************
				  * /Unidades
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
			           { name: 'cantidad',  			type: 'float'},
					   { name: 'importe',  				type: 'float'},
					   { name: 'lote',  				type: 'string'}
			        ],
					listeners : {
						datachanged : actualizar_carrito
					}
			    });

			    // create the Grid
			    grid = Ext.create('Ext.grid.Panel', {
			        store: carrito_store,
					plugins: [cellEditing],
										bodyCls: 'foo',
			        stateful: true,
			        stateId: 'stateGridCompra',
			        columns: [
			            {
			                text     : 'Codigo producto',
			                width    : 95,
			                sortable : false,
			                dataIndex: 'codigo_producto'
			            },
			            {
			                text     : 'Nombre',
			                flex     : 1,
			                sortable : true,
			                dataIndex: 'nombre_producto'
			            },
			            {
			                text     : 'Cantidad',
							width	 : 60,
							dataIndex: 'cantidad',
			                sortable : false,
							renderer : function(x){
								if(x === undefined)  x = 1;
								return x + '';
							},
							field: {
				                xtype: 'numberfield'
				            }

			            },
			            {
			                text     : 'Unidad',
			                width    : 75,
			                dataIndex: 'id_unidad',
				            field : {
				            	xtype : "combobox",
				            	typeAhead: true,
				                triggerAction: 'all',
				                selectOnTab: true,
				                store: unidadMedida,
				                lazyRender: true,
				                listClass: 'x-combo-list-small',
				                displayField: "abreviacion",
				                listConfig: {
				                	loadingText: 'Buscando...',
				                	
				                	// Custom rendering template for each item
				                	getInnerTpl: function(a,b,c) {
										return "<p style='margin:0px'>{abreviacion}</p>";
				                	}
				            	},
				            }//field
			            },
			            {
			                text     : 'Precio',
			                sortable : true,
			                dataIndex: 'precio',
							renderer : Ext.util.Format.usMoney,
							field: {
				                xtype: 'numberfield'
				            }
			            },
			            {
			                text     : 'Lote',
			                width    : 75,
			                dataIndex: 'lote',
				            field : {
				            	xtype : "combobox",
				            	typeAhead: true,
				                triggerAction: 'all',
				                selectOnTab: true,
				                store: lotes,
				                lazyRender: true,
				                listClass: 'x-combo-list-small',
				                displayField: "folio",
				                listConfig: {
				                	loadingText: 'Buscando...',
				                	
				                	// Custom rendering template for each item
				                	getInnerTpl: function(a,b,c) {
										return "<p style='margin:0px'>{folio}</p>";
				                	}
				            	},
				            }//field
			            },
			            {
			                text     : 'Importe',
			                dataIndex: 'importe',
			                width    : 75,
			                sortable : true,
							renderer : Ext.util.Format.usMoney
			            }
			            
			        ],
			        height: 350,
			        width: "100%",
			        renderTo: 'carrito_de_compras_grid',
			        viewConfig: {
			            stripeRows: false
			        }
			    });		

			/** *****************************************************************
			  * CARRITO
			  *
			  * ***************************************************************** */

		}); /* Ext.onReady */


	</script>
				<h2>Nueva compra</h2>

				<table border="0" style="width: 100%" class="">
					<tr id="SeleccionDeCliente">
						<td colspan="4">
							<div id="buscar_cliente_01">
								<p style="margin-bottom: 0px;">Proveedor</p>
								<div style="margin-bottom: 15px;" id="ShoppingCartComponent_002"><!-- clientes --></div>				
							</div>
							<div id="buscar_cliente_02" style="display:none; margin-bottom: 0px"></div>						
						</td>
					</tr>



					<tr>
						
						<td id="SeleccionDeSucursal">

							Empresa que compra:
							<div >
							<?php
					        $empresas = EmpresaDAO::getAll();
					        if (sizeof($empresas) == 0) {
								?><div style="color:gray; font-size:9px">[No hay empresas]</div><?php
					        } else if (sizeof($empresas) > 10) {
								$selector_de_suc = new EmpresaSelectorComponent();
								$selector_de_suc->addJsCallback("seleccionar_empresa");
								$selector_de_suc->renderCmp();
            
					        } else {
						
								?><script type="text/javascript" charset="utf-8">
									empresa_seleccionada = <?php echo$empresas[0]->getIdEmpresa(); ?>;
								</script><?php
								
								?><select id="empresa_seleccionada" onChange="seleccionar_empresa(this.value)" ><?php
            					
					            for ($i = 0; $i < sizeof($empresas); $i++) {
					                echo "<option value=\"" . $empresas[$i]->getIdEmpresa() . "\" >" . $empresas[$i]->getRazonSocial() . "</option>";
					            }
								?></select><?php
            
					        }
        					?>						
							</div>

						</td>

						<td id="SeleccionDeTipoDeVenta">
							Tipo de compra:
							<div >
								<select onChange="seleccion_tipo_de_compra(this.value)">
									<option value="contado" selected>Contado</option>
									<option value="credito">Credito</option>
								</select>
							</div>
						</td>
						<td id="SeleccionDeImpuestos">
							Tipo de tarifa:
							<div >
								<select id="tarifa_seleccionada" onChange="seleccion_de_tarifa(this.value)" >
									<?php
        
        $tarifas = TarifaDAO::obtenerTarifasActuales("venta");
        for ($i = 0; $i < sizeof($tarifas); $i++) {
            echo "<option value=\"" . $tarifas[$i]["id_tarifa"] . "\" >" . $tarifas[$i]["nombre"] . "</option>";
        }
?>
								</select>
							</div>
						</td>
						<td id="SeleccionDeTipoDePago">
							
								<div >
										
								</div>
						</td>										
					</tr>

					<tr>
						<td id="SeleccionDeDescuento">
							Descuento:
							<input type="text" id="descuento_seleccionado_val" onchange="actualizar_carrito()" value="0" >
							<select id="descuento_seleccionado_tipo" onChange="actualizar_carrito()">
								<option value="porciento">%</option>
				<!--				<option value="MXN">MXN</option> -->
							</select>
						</td>										
					</tr>

				</table>

				<div id="BuyingCartComponent_001"><!-- buscar productos --></div>

				<div id="carrito_de_compras" style="margin: 5px auto;">
					<div id="carrito_de_compras_grid"></div>
				</div>


				<table>
					<tr>
						<td>Subtotal</td>
						<td id="carrito_subtotal"></td>
					</tr>
					
					<tr>
						<td>Descuento</td>
						<td id="carrito_descuento"></td>
					</tr>
					
					<tr>
						<td>Impuesto</td>
						<td id="carrito_impuesto"></td>
					</tr>
					<tr>
						<td>Total</td>
						<td id="carrito_total"></td>
					</tr>				
				</table>

				<div class="POS Boton" onClick="cancelarVenta()">Cancelar</div>
				<!--
				<div class="POS Boton" onClick="doCotizar()">Solo cotizar</div>
			-->
				<div class="POS Boton OK" onClick="doCompra()">Comprar</div>

			<?php
    }
       
}

class ShoppingCartComponent extends CartComponent implements GuiComponent
{
    function __construct()
    {
    }
}
