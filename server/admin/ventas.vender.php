<?php


	require_once("model/sucursal.dao.php");
    require_once('model/autorizacion.dao.php');
	require_once("controller/clientes.controller.php");
	require_once("controller/sucursales.controller.php");
	require_once("controller/inventario.controller.php");
	require_once('controller/clientes.controller.php');
	require_once('model/cliente.dao.php');
	
	$productos = InventarioDAO::getAll();
	
	$iMaestro = listarInventarioMaestro(150, POS_SOLO_ACTIVOS) ;

?>
<style>
    table{
		font-size: 11px;
    }

	input{
		width: 50px;
	}
</style>
<script>

/** **********************************************
  *
  *		DETALLES DE PRODUCTOS 
  *
  * ********************************************** */
	
	var Producto = function( id_producto ){
		
		var id_producto,
			descripcion,
			escala,
			tratamiento,
			agrupacion,
			agrupacionTam,
			activo,
			precio_por_agrupacion,
			productos = [];

		this.getDescripcion = function(){
			return descripcion;
		}
		
		this.isTratable = function (){
			return tratamiento !== null;
		}
		
		this.isAgrupable = function (){
			return agrupacion !== null;
		}
		
		this.getAgrupacionTam = function (){
			return agrupacionTam;
		}
		
		this.getAgrupacionDescCorta = function (tiempo){
			if(tiempo == "singular"){
				return "Arp";
			}
			
			return "Arps"
		}
		
		this.getAgrupacionDesc = function (){
			return agrupacion;
		}
		
		this.isPrecioPorGrupo = function(){
			return precio_por_agrupacion;
		}
		
		this.getEscalaCorta = function(tiempo){
			
			if(tiempo == "singular"){
				switch(escala){
			        case "kilogramo" : return "Kg";
			        case "pieza" : return "Pz";        
			        case "unidad" : return "Unidad";               
			    }
			}
			
			switch(escala){
		        case "kilogramo" : return "Kgs";
		        case "pieza" : return "Pzs";        
		        case "unidad" : return "Uds";               
		    }
		}
		
		this.getEscala = function (){
			return escala;
		}
		
		
		//registrar los productos
		<?php
		foreach($productos as $p){
			echo "productos.push({$p});";
		}
		?>
		
		
		var __init = function(pid){
			//buscar este producto en la estructura de productos
			var found = false, p_index;
			for ( p_index = productos.length - 1; p_index >= 0; p_index--){
				if(productos[p_index].id_producto == id_producto){
					found = true;
					break;
				}
			}
			
			if(!found){
				return null;
			}

			//normalizar los campos 
			id_producto 	= parseInt(pid,10);
			descripcion 	= productos[p_index].descripcion;
			escala 			= productos[p_index].escala;
			tratamiento 	= productos[p_index].tratamiento;
			agrupacion 		= productos[p_index].agrupacion;
			agrupacionTam	= parseFloat(productos[p_index].agrupacionTam);
			activo			= productos[p_index].activo === "1";
			precio_por_agrupacion = productos[p_index].precio_por_agrupacion === "1"; 
			
		}
		
		__init(id_producto);
	};




/** **********************************************
  *
  *		INVENTARIO MAESTRO
  *
  * ********************************************** */
	var InventarioMaestro = function(){
		
		var MasterGrid,
			store;
		var inventario_maestro_extjs;
		var original_store;
		
		this.buscarCompra = function (id_compra, id_producto){

			var index = store.findBy( function(r){

				if( r.get("id_compra_proveedor") == id_compra
					&& r.get("id_producto") == id_producto
					)
					return true;
				else
					return false;
			} );
			
			return store.getAt(index);
		}
		

		
		var createMasterGrid = function (){
			<?php 
				echo " inventario_maestro_extjs = " . json_encode( $iMaestro ) . ";";
			?>
			
			var inventario_formateado = [  ];

			for (var i = inventario_maestro_extjs.length - 1; i >= 0; i--){
				inventario_formateado[i] = [
					inventario_maestro_extjs[i].arpillas,
					inventario_maestro_extjs[i].calidad,
					inventario_maestro_extjs[i].chofer,
					inventario_maestro_extjs[i].costo_flete,
					inventario_maestro_extjs[i].existencias,
					inventario_maestro_extjs[i].existencias_procesadas,
					inventario_maestro_extjs[i].fecha,
					inventario_maestro_extjs[i].fecha_origen,
					inventario_maestro_extjs[i].folio,
					inventario_maestro_extjs[i].id_compra_proveedor,
					inventario_maestro_extjs[i].id_producto,
					inventario_maestro_extjs[i].id_proveedor,
					inventario_maestro_extjs[i].kg,
					inventario_maestro_extjs[i].marca_camion,
					inventario_maestro_extjs[i].medida,
					inventario_maestro_extjs[i].merma_por_arpilla,
					inventario_maestro_extjs[i].modelo_camion,
					inventario_maestro_extjs[i].numero_de_viaje,
					inventario_maestro_extjs[i].peso_origen,
					inventario_maestro_extjs[i].peso_por_arpilla,
					inventario_maestro_extjs[i].peso_recibido,
					inventario_maestro_extjs[i].placas_camion,
					inventario_maestro_extjs[i].precio_por_kg,
					inventario_maestro_extjs[i].producto_desc,
					inventario_maestro_extjs[i].producto_tratamiento,
					inventario_maestro_extjs[i].productor,
					inventario_maestro_extjs[i].sitio_descarga,
					inventario_maestro_extjs[i].sitio_descarga_desc,
					inventario_maestro_extjs[i].total_origen,
					inventario_maestro_extjs[i].variedad,
					inventario_maestro_extjs[i].agrupacion,
					inventario_maestro_extjs[i].agrupacionTam								
				];

			};
			
			
			store = new Ext.data.ArrayStore({
		        fields: [
					{ name : 'arpillas', 				type : 'float' },
					{ name : 'calidad', 				type : 'string' },
					{ name : 'chofer', 					type : 'string' },
					{ name : 'costo_flete', 			type : 'float' },
					{ name : 'existencias', 			type : 'float' },
					{ name : 'existencias_procesadas', 	type : 'float' },
					{ name : 'fecha', 					type : 'date', dateFormat: 'j/m/y' },
					{ name : 'fecha_origen', 			type : 'date', dateFormat: 'Y-m-d' },
					{ name : 'folio', 					type : 'string' },
					{ name : 'id_compra_proveedor', 	type : 'int' },
					{ name : 'id_producto', 			type : 'int' },
					{ name : 'id_proveedor', 			type : 'int' },
					{ name : 'kg', 						type : 'float' },
					{ name : 'marca_camion', 			type : 'string' },
					{ name : 'medida', 					type : 'string' },
					{ name : 'merma_por_arpilla', 		type : 'float' },
					{ name : 'modelo_camion', 			type : 'string' },
					{ name : 'numero_de_viaje', 		type : 'string' },
					{ name : 'peso_origen', 			type : 'float' },
					{ name : 'peso_por_arpilla', 		type : 'float' },
					{ name : 'peso_recibido', 			type : 'float' },
					{ name : 'placas_camion', 			type : 'string' },
					{ name : 'precio_por_kg', 			type : 'float' },
					{ name : 'producto_desc', 			type : 'string' },
					{ name : 'producto_tratamiento', 	type : 'string' },
					{ name : 'productor', 				type : 'string' },
					{ name : 'sitio_descarga', 			type : 'int' },
					{ name : 'sitio_descarga_desc', 	type : 'string' },
					{ name : 'total_origen', 			type : 'float' },
					{ name : 'variedad', 				type : 'string' },
					{ name : 'agrupacion', 				type : 'string' },
					{ name : 'agrupacionTam', 			type : 'float' }						
		        ]
			});
			original_store = new Ext.data.ArrayStore({
		        fields: [
					{ name : 'arpillas', 				type : 'float' },
					{ name : 'calidad', 				type : 'string' },
					{ name : 'chofer', 					type : 'string' },
					{ name : 'costo_flete', 			type : 'float' },
					{ name : 'existencias', 			type : 'float' },
					{ name : 'existencias_procesadas', 	type : 'float' },
					{ name : 'fecha', 					type : 'date', dateFormat: 'j/m/y' },
					{ name : 'fecha_origen', 			type : 'date', dateFormat: 'Y-m-d' },
					{ name : 'folio', 					type : 'string' },
					{ name : 'id_compra_proveedor', 	type : 'int' },
					{ name : 'id_producto', 			type : 'int' },
					{ name : 'id_proveedor', 			type : 'int' },
					{ name : 'kg', 						type : 'float' },
					{ name : 'marca_camion', 			type : 'string' },
					{ name : 'medida', 					type : 'string' },
					{ name : 'merma_por_arpilla', 		type : 'float' },
					{ name : 'modelo_camion', 			type : 'string' },
					{ name : 'numero_de_viaje', 		type : 'string' },
					{ name : 'peso_origen', 			type : 'float' },
					{ name : 'peso_por_arpilla', 		type : 'float' },
					{ name : 'peso_recibido', 			type : 'float' },
					{ name : 'placas_camion', 			type : 'string' },
					{ name : 'precio_por_kg', 			type : 'float' },
					{ name : 'producto_desc', 			type : 'string' },
					{ name : 'producto_tratamiento', 	type : 'string' },
					{ name : 'productor', 				type : 'string' },
					{ name : 'sitio_descarga', 			type : 'int' },
					{ name : 'sitio_descarga_desc', 	type : 'string' },
					{ name : 'total_origen', 			type : 'float' },
					{ name : 'variedad', 				type : 'string' },
					{ name : 'agrupacion', 				type : 'string' },
					{ name : 'agrupacionTam', 			type : 'float' }						
		        ]
		});
		
			store.loadData(inventario_formateado);
			original_store.loadData(inventario_formateado );
			
			 // create the Grid
			    MasterGrid = new Ext.grid.GridPanel({
		        store: store,
				header : false,
		        columns: [
			        {
		                header   : 'Fecha', 
		                width    : 75, 
		                sortable : true, 
		                renderer : Ext.util.Format.dateRenderer('d/m/Y'),  
		                dataIndex: 'fecha'
		            },
		            {
		                header   : 'Remision', 
		                width    : 75, 
		                sortable : true, 
		                dataIndex: 'folio'
		            },	
		            {
		                id       :'descripcion',
		                header   : 'Producto', 
		                width    : 180, 
		                sortable : true, 
		                dataIndex: 'producto_desc'
		            },
		            {
		                header   : 'Variedad', 
		                width    : 85, 
		                sortable : true, 
		                dataIndex: 'variedad'
		            },	
		            {
		                header   : 'Promedio', 
		                width    : 85, 
		                sortable : true, 
						align 	 : "right",	
						renderer : function(n, c, row){
						//de que producto estoy hablando ?
							var p = new Producto(row.data.id_producto);	

							if(p === null) return "-";

							if(p.isTratable()){
								return n.toFixed(4);								
							}else{
								return "-";
							}

						},
		                dataIndex: 'peso_por_arpilla'
		            },	
		            {
		                header   : 'Merma', 
		                width    : 85, 
		                sortable : true, 
						hidden	 : true,
		                dataIndex: 'merma_por_arpilla'
		            },
		            {
		                header   : 'peso_origen', 
		                width    : 85, 
		                sortable : true, 
						hidden: true,
		                dataIndex: 'peso_origen'
		            },
		            {
		                header   : 'Costo', 
		                width    : 85, 
		                sortable : true, 
						hidden: true,	
						renderer : 'usMoney',
		                dataIndex: 'precio_por_kg'
		            },			
		            {
		                header   : 'productor', 
		                width    : 85, 
		                sortable : true, 
						hidden: true,	
		                dataIndex: 'productor'
		            },
		            {
		                header   : 'Originales', 
		                width    : 150, 
		                sortable : true, 
						align 	 : "right",
		                renderer : function (n, a, row ){
			
							//de que producto estoy hablando ?
							var p = new Producto(row.data.id_producto),
								v ;	
							
							if(p === null) return "-";
							
							
							if(p.isAgrupable()){
								
								if(p.isTratable()){
									//si hay tratamiento, estoy hablando
									//de originales, asi que es el peso por arpilla
									v = (parseFloat( n / row.data.peso_por_arpilla )).toFixed(2) ;
								}else{
									//no hay tratamiento
									v = (parseFloat( n / p.getAgrupacionTam() )).toFixed(2) ;
								}
								v += " " 
								+  p.getAgrupacionDescCorta()
								+"&nbsp;(<i>" + n.toFixed(2) 
								+ " " 
								+  p.getEscalaCorta()
								+ "</i>)";
							}else{
								v = n.toFixed(2) + " " +  p.getEscalaCorta();
							}

							if(n < 0){
								return ( "<span style='color:red'>" + v +"</span>" );
							}else{
								return v;
							}

						},
		                dataIndex: 'existencias'
		            },
		            {
		                header   : 'Procesadas', 
		                width    : 150, 
						align 	 : "right",	
		                sortable : true, 
		                renderer : function (n, a, row ){
			
							//de que producto estoy hablando ?
							var p = new Producto(row.data.id_producto),
								v;	
							
							if(p === null) return "-";
							
							
							if(!p.isTratable()){
								//no hay tratamiento, no deben existir las procesadas
								return "-";
							}
								
							if(p.isAgrupable()){
								//si hay tratamiento, estoy hablando
								//de procesadas
								v = (parseFloat( n / p.getAgrupacionTam() )).toFixed(2) 
								+  " " 
								+  p.getAgrupacionDescCorta()
								+ "&nbsp;(<i>" + n.toFixed(2) 
								+ " " 
								+  p.getEscalaCorta()
								+ "</i>)";
							}else{
								v = " " 
								+ n.toFixed(2) 
								+ " " 
								+  p.getEscalaCorta();
							}
	

							if(n < 0){
								return ( "<span style='color:red'>" + v +"</span>" );
							}else{
								return v;
							}

						},
		                dataIndex: 'existencias_procesadas'
		            },	
		            {
		                header   : 'sitio_descarga_desc', 
		                width    : 95, 
		                sortable : true, 
						hidden: true,	
		                dataIndex: 'sitio_descarga_desc'
		            }
		        ],
		        stripeRows: true,
		        autoExpandColumn: 'descripcion',
		        height: "auto",
				minHeight : 300,
		        width: "100%",
		        stateful: false,
		        stateId: 'inventario-maestro-state',
				listeners : {
					"rowclick" : function (grid, rowIndex, e){
						var datos = grid.getStore().getAt( rowIndex );
						
						composicionActual.agregarProducto( 
							datos.get("id_compra_proveedor" ), 
							datos.get("id_producto") );
					}
				}

		    });//create master grid
		
		    MasterGrid.render('inventario_maestro');
		}
		
		
		this.tomarProducto = function(id_compra, id_producto, cantidad, procesada){

			if(isNaN(cantidad)){
				cantidad = 0;
			}
			
			//buscar la compra
			compra = inventarioMaestro.buscarCompra( id_compra, id_producto );
			
			//store con lo que se muestra en el grid
			var gridRow = MasterGrid.getStore().findBy(function(r){
				 return (r.get("id_compra_proveedor") == id_compra && r.get("id_producto") == id_producto);
			}, this);
			var row = MasterGrid.getStore().getAt( gridRow );

			//store con las cantidades originales
			var o_index = original_store.findBy(function(r){
				 return (r.get("id_compra_proveedor") == id_compra && r.get("id_producto") == id_producto);
			}, this);
			var orignal_row = original_store.getAt(o_index);

			//ahora a editar el store del grid,
			//primero hay que saber si el producto es
			//agrupable, para tomar por grupos y no 
			//por unidades
			
			var p = new Producto(id_producto);
			var units ;
			
			if(p.isAgrupable()){
				
				if(p.isTratable()){
					//si hay tratamiento,
					if(procesada){
						//esta procesada, usar el promedio por agrupacion
						units = parseFloat( cantidad * p.getAgrupacionTam() ) ;
					}else{
						//es orginal, usar el promedio de la compta
						units = parseFloat( cantidad * compra.get("peso_por_arpilla") ) ;						
					}

				}else{
					//no hay tratamiento
					units = parseFloat( cantidad * p.getAgrupacionTam() );
				}

			}else{
				//no hay agrupacion
				units = cantidad;
			}
			
			
			if(procesada){
				//las tomare procesadas
				row.set("existencias_procesadas"	, orignal_row.get("existencias_procesadas") - units);
				row.set("existencias"				, orignal_row.get("existencias"));
	        }else{
				//las tomare originales
				row.set("existencias_procesadas"	, orignal_row.get("existencias_procesadas"));
				row.set("existencias"				, orignal_row.get("existencias") - units);			
	        }
	

		}
		
		createMasterGrid();
	}

/** **********************************************
  *
  *		COMPOSICIONES DEL PRODUCTO
  *
  * ********************************************** */
	var Composicion = function( id_producto ){
		
		var composicion_actual = [],
			producto_a_componer ;
		
		var iniciarComposicion = function(){
			console.log("Iniciando composicion");
			
			//ocultar la tabla de productos
			jQuery("#lista_de_productos").slideUp();
			
			//mostrar el inventario maestro
			jQuery("#inventario_maestro").slideDown();
			
			//mostar la tabla de composiciones
			jQuery("#composicion").slideDown();

		}
		
		this.quitarProducto = function ( id_compra, id_producto ){
			console.log("Removiendo producto");
			var index = null,
				i,
				id;
			id = id_compra + "-" + id_producto + "-composicion";
			
			jQuery("#"+ id ).animate(
				{ opacity: 0.0 }, 
				250, 
				function(){
					//when done
					jQuery("#" +  id).remove();
				}
			);
	        

	        //buscar esa composicion el arreglo
	        for ( i = composicion_actual.length - 1; i >= 0; i--){
	            if( composicion_actual[i].id_compra === id_compra 
	                    && composicion_actual[i].id_producto === id_producto){
	                        index = i;
	                        break;
	                    }
	        }

	        if(composicion_actual[index].cantidad != 0){
	            this.doMath( id_compra, id_producto, "cantidad", 0 );
	        }

	        composicion_actual.splice(index,1);
		}
		
		
		
		this.doMath = function( id_compra, id_producto, campo, valor ){
			console.log("Doing some math !");
			
			//buscar esta compra
			var compra = inventarioMaestro.buscarCompra( id_compra, id_producto );

			//buscar esta composicion
			var found = false;
			
			for (var c_index=0; c_index < composicion_actual.length; c_index++) {
				if( composicion_actual[c_index].id_producto == compra.get("id_producto")
					&& composicion_actual[c_index].id_compra == compra.get("id_compra_proveedor")){
						found = true;
						break;
					}
			};
			
			if(!found) return;
			
			var composicion = composicion_actual[c_index];
			
			switch( campo ){
				case "cantidad" :
					//voy a cambiar la cantidad
					composicion.cantidad = parseFloat(valor);
					inventarioMaestro.tomarProducto( id_compra, id_producto, composicion.cantidad, composicion.procesada );
	            break;

				case "proc" : 
					composicion.procesada = valor;
					inventarioMaestro.tomarProducto( id_compra, id_producto, composicion.cantidad, composicion.procesada );
				break;

				case "precio" :
					if(isNaN(valor) || valor.length == 0){
						valor = 0;
					}
					composicion.precio = parseFloat(valor);
				break;

				case "descuento":
					if(isNaN(valor) || valor.length == 0){
						valor = 0;
					}
					composicion.descuento = parseFloat(valor);
				break;
		    }
		

			
		
			//calcular el descuento correcto, si se agrupa este producto, entonces el 
			//descuento es en unidades por grupo, de lo contrario es en unidades nomas
			var descuento = 0;
			var p = new Producto(id_producto);
			if(p.isAgrupable()){
				
				var grupos;
				
				if(p.isTratable()){
					if(composicion.procesada){
						//procesada !
						descuento =  composicion.descuento * composicion.cantidad;
					
					}else{
						//original
						descuento =  composicion.descuento * composicion.cantidad;
						
					}
				}else{
					//no es tratable
					descuento = p.getAgrupacionTam() * composicion.descuento;
				}
			}else{
				descuento = composicion.descuento;
			}
			
			
	
			var p = new Producto(composicion.id_producto);
			var cantidad_en_unidades = 0;
			if(p.isAgrupable()){

				var grupos;

				if(p.isTratable()){
					if(composicion.procesada){
						//procesada !
						cantidad_en_unidades = composicion.cantidad * p.getAgrupacionTam();

					}else{
						//original
						cantidad_en_unidades = composicion.cantidad * compra.get( "peso_por_arpilla" );						
					}


				}else{
					cantidad_en_unidades = p.getAgrupacionTam() * composicion.cantidad;
				}
			}else{
				cantidad_en_unidades = composicion.cantidad;
			}
			
			composicion.descuento_en_unidades = descuento;
			composicion.importe = composicion.precio * (cantidad_en_unidades - descuento);
			
	        jQuery( "#" + id_compra + "-" + id_producto + "-importe" ).val( cf( composicion.importe ) );

			//calcular totales 
			var totales = {
	            peso_real 			: 0.0,
	            peso_a_cobrar		: 0.0,
	            importe_por_unidad 	: 0.0,
	            importe_total 		: 0.0
	        };

	        for (i = composicion_actual.length - 1; i >= 0; i--){
		
				var p = new Producto(composicion_actual[i].id_producto);
				var peso_real = 0;
				if(p.isAgrupable()){

					var grupos;

					if(p.isTratable()){
						if(composicion_actual[i].procesada){
							//procesada !
							peso_real = composicion_actual[i].cantidad * p.getAgrupacionTam();

						}else{
							//original
							var compra = inventarioMaestro.buscarCompra( 
									composicion_actual[i].id_compra, 
									composicion_actual[i].id_producto 
								);
							peso_real = composicion_actual[i].cantidad * compra.get( "peso_por_arpilla" );						
						}


					}else{
						peso_real = p.getAgrupacionTam() * composicion_actual[i].cantidad;
					}
				}else{
					peso_real = composicion_actual[i].cantidad;
				}
		
				totales.peso_real += parseFloat(peso_real);
	            totales.peso_a_cobrar += parseFloat(peso_real - composicion_actual[i].descuento_en_unidades);
	            totales.importe_total += parseFloat( composicion_actual[i].importe );
	        }
			
			
	        totales.importe_por_unidad = totales.importe_total / totales.peso_a_cobrar;
	
		
	        jQuery("#compuesto-peso-real").html 		( totales.peso_real.toFixed(4) );
	        jQuery("#compuesto-peso-a-cobrar").html 	( totales.peso_a_cobrar.toFixed(4) );
	        jQuery("#compuesto-importe-por-unidad").html( cf(totales.importe_por_unidad) );
	        jQuery("#compuesto-importe-total").html		( cf(totales.importe_total ) );
		
		};
		

		
		this.agregarProducto = function(id_compra, id_producto){

			//buscar esta venta en el inventario maestro
			var compra = inventarioMaestro.buscarCompra( id_compra, id_producto );
			
			var found = false;
			
			//revisar que no tenya ya este producto agregado
			for (var c_index=0; c_index < composicion_actual.length; c_index++) {
				if( composicion_actual[c_index].id_producto == compra.get("id_producto")
					&& composicion_actual[c_index].id_compra == compra.get("id_compra_proveedor")){
						found = true;
						break;
					}
			};
			
			if(found){
				//ya existe esta composicion hacer un highlight
				// y salir sin hacer nada
				
				jQuery("#"+id_compra+"-"+id_producto+"-composicion").animate(
					{ opacity: 0.25 }, 
					250, 
					function(){
						jQuery("#"+id_compra+"-"+id_producto+"-composicion").animate(
							{ opacity: 1 }, 
							250
						);
					}
				);
				
				return;
			}
			
			var producto = new Producto(id_producto);

	        var costo_flete = 0;

	        if( parseFloat (compra.get("costo_flete") ) != 0){
	             costo_flete = parseFloat (compra.get("costo_flete")) / parseFloat (compra.get("peso_origen"));
	        }



			
			var precio = parseFloat(compra.get("precio_por_kg")) + parseFloat(costo_flete);
			
			//crear un nuevo objeto de composicion
			composicion_actual.push({
				id_producto : id_producto,
				id_compra 	: id_compra,
	            cantidad    : 0,
	            descuento   : 0,
	            procesada   : false,
	            precio      : precio,
				precio_original : precio

			});
		
			
			
	       var html = "";

	        html += td( "<img onClick='composicionActual.quitarProducto(" + id_compra + "," + id_producto + ")' src='../media/icons/close_16.png'>" );
	        html += td( compra.get("folio" ) );
	        html += td( producto.getDescripcion() );

	        var keyup = "onkeyup='composicionActual.doMath(" + id_compra + "," + id_producto + ", this.name, this.value )'";
	        var click = "onClick='composicionActual.doMath(" + id_compra + "," + id_producto + ", this.name, this.value )'";
	        var escala;
	        var escala_descuento;
	
			if(producto.isAgrupable()){
				//se puede agrupar, mostrar la agrupacion
				escala = producto.getAgrupacionDescCorta();
				escala_descuento = producto.getEscala() + "s por " + producto.getAgrupacionDesc();
			}else{
				//no se puede agrupar, mostar la escala
				escala = producto.getEscalaCorta();
				escala_descuento = producto.getEscala() + "s";
			}

	        html += td( "<input name='cantidad' "+keyup+" value='0' type='text'>&nbsp;" + escala);

			if(producto.isTratable()){
				//se puede procesar
				html += td( "<input style='width: 100px' name='proc' "+click+" type='checkbox'> " );   
				//revisar existencias procesadas
				//html += td( "<input style='width: 100px'  type='checkbox' disabled> " );
			}else{
				//no se puede procesar
				html += td( "<input type='hidden'><i>-</i>", "align=center" );       
			}

			if(producto.isPrecioPorGrupo()){
	        	html += td( "<input name='precio'     value='"+ precio + "' " +keyup+"    type='text'> por " 
					+ producto.getAgrupacionDescCorta("singular") );				
			}else{
				if(precio == 0){

				}
	        	html += td( "<input name='precio'     value='"+ precio + "' " +keyup+"    type='text'> por "
						+ producto.getEscalaCorta("singular") );				
			}

	        html += td( "<input name='descuento'  value='0'  "   +keyup+"    type='text'>&nbsp;" + escala_descuento);
	        html += td( "<input id='" +id_compra+"-"+ id_producto+ "-importe'   type='text' style='width:150px' disabled>" );




	        jQuery("#composicion_tabla").after( tr(html, "id='" + id_compra + "-" + id_producto + "-composicion'") );			
			

		}
		
		
		this.commitMix = function( ){
	        var c, total_qty = 0, i;

	        //revisar que todo concuerde
	        for ( i = composicion_actual.length - 1; i >= 0; i--){

	            c = composicion_actual[i];
	            total_qty += parseFloat( c.cantidad - c.descuento );

				var el = c.id_compra + "-" + c.id_producto +  "-composicion" ;

	            if((c.cantidad - c.descuento) <= 0 ){
	                error( "Hay productos sin cantidad", " El producto " + c.desc + " tiene una cantidad de cero.", el);
	                return;
	            }

	    	}
	
	        if(composicion_actual.length == 0){
	            error("No ha agregado ningun producto", "El producto debe conmponerse de por lo menos un producto. Agregue un producto de su inventario maestro para continuar.");
	            return;
	        }

	        if(total_qty == 0){
	            error("El peso total es cero", "No puede componer un producto a surtir cuando el peso total es igual a cero. ");
	            return;
	        }

	        composicionesTerminadas.push({
	            composicion : composicion_actual,
	            producto : id_producto,
	            procesado : (jQuery("#compuesto-procesado:checked").length == 1)
	        });

	        jQuery("#lista_de_productos").slideDown('fast', function (){

	            jQuery('#inventario_maestro').slideUp();
	            	jQuery('#composicion').slideUp('fast', function (){
            	});     
	        });

	        renderFinalShip();
		}
		
		var renderFinalShip = function (){
			console.log("rendering final ship !");
			/*
			
		    var global_qty = 0, global_qty_real = 0, global_importe = 0, global_cost = 0;

		    var html = '<table style="width: 100%">';
		    html += '<tr align=left>'
		        + '<th></th>'
		        + '<th>Producto</th>'
		        + '<th>Peso real</th>'
		        + '<th>Peso a cobrar</th>'      
		        + '<th>Composicion</th>'
				+ '<th>Costo</th>'
		        + '<th>Importe</th>'
				+ '<th>Rendimiento</th>';

		    for (var i=0; i < composiciones.length; i++) {

		        jQuery("#producto-" + composiciones[i].producto ).css("text-decoration", "line-through");
		        jQuery("#producto-" + composiciones[i].producto ).fadeTo(250, .25);

		        desc = inventario.getProductoDesc( composiciones[i].producto );

		        var total_qty = 0;
		        var total_qty_with_desc = 0;        
		        var total_money = 0;
		        var composition = '';
		        var costo_total = 0;        


		        for (var j = composiciones[i].items.length - 1; j >= 0; j--){
		            total_qty += composiciones[i].items[j].cantidad  ;
		            total_qty_with_desc += composiciones[i].items[j].cantidad   - composiciones[i].items[j].descuento ;         
		            total_money += ( composiciones[i].items[j].cantidad - composiciones[i].items[j].descuento ) * composiciones[i].items[j].precio ;
					costo_total += composiciones[i].items[j].precio_original * composiciones[i].items[j].cantidad ;

		            composition += "<b>"+ composiciones[i].items[j].desc 
								+ "</b>&nbsp;" 
								+ (composiciones[i].items[j].procesada ? "Procesada" : "Original")
								+ "<br>"
								+ composiciones[i].items[j].cantidad.toFixed(2)  + getEscalaCorta( composiciones[i].items[j].escala )
		                        + "<b> - </b>" 
								+ composiciones[i].items[j].descuento.toFixed(2) + getEscalaCorta( composiciones[i].items[j].escala )+ " desc."
		                        + "<br>";
		        }

		        var color = i % 2 == 0 ? 'style="background-color: #D7EAFF"' : "";

		        html += tr(
							td( "<img src='../media/icons/basket_close_32.png' onClick='composicionTabla.rollbackMixIndex("+i+")'>" )
		                    + td( desc.descripcion )
		                    + td( total_qty.toFixed(4) + getEscalaCorta( desc.escala ) )
		                    + td( total_qty_with_desc.toFixed(4) + " " + getEscalaCorta( desc.escala ) )     
		                    + td( composition)
		                    + td( cf(costo_total))
		                    + td( cf(total_money)) 
							+ td( cf(total_money-costo_total)), color)

		        global_qty += total_qty;
		        global_qty_real += total_qty_with_desc;
		        global_importe += total_money;
				global_cost += costo_total;
		    };



			html += tr(
		                  td( "Totales", "style='padding-top: 10px'" )
		                + td( "")
		                + td( global_qty.toFixed(2) )
		                + td( global_qty_real.toFixed(2) )     
		                + td( "")
		                + td( cf(global_cost.toFixed(4)))
		                + td( cf(global_importe.toFixed(4) ) )
		                + td( cf(global_importe.toFixed(4) - global_cost.toFixed(4)) , 
							(global_importe.toFixed(4) - global_cost.toFixed(4)) < 0 ? "style='color:red;'" : "style='color:green;'" ),

		                "style='border-top: 1px solid #3F8CE9; font-size: 13px;'");

		    html += '</html>';

		    jQuery("#FinalShipTabla").html(html);
		    jQuery("#FinalShip").fadeIn();

		    jQuery("#compuesto-peso-real").html 		( 0 );
		    jQuery("#compuesto-peso-a-cobrar").html 	( 0 );
		    jQuery("#compuesto-importe-por-unidad").html( cf(0) );
		    jQuery("#compuesto-importe-total").html 	( cf(0) );
		*/
		}
		
		var __init = function (pid){
			//reiniciar el arreglo, por cualquier cosa
			composicion_actual = [];
			producto_a_componer = pid;
			iniciarComposicion();
		}
		
		__init(id_producto);
	}

/** **********************************************
  *
  *		MAIN
  *
  * ********************************************** */

	jQuery(document).ready(function(){
		console.log("Iniciando...");
		
		inventarioMaestro = new InventarioMaestro();
		
	});

	/*
	 *	GLOBALS
	 */
	var inventarioMaestro,
		composicionActual,
		composicionesTerminadas = [];
		
</script>








<!--
	Seleccion de producto
-->
<div id="lista_de_productos">
	<h2>&iquest; Que producto desea agregar en esta venta ?</h2>
		<?php
		echo "<table border=0 style='width: 100%; font-size: 14px; cursor: pointer;'>";
			echo "<tr>";
			for($a = 0; $a < sizeof($productos); $a++){
				
				if($a % 5 == 0){
					echo "</tr><tr>";
				}
				?>
					<td class='rounded' id='producto-<?php echo $productos[$a]->getIdProducto() ?>'
						onClick='composicionActual = new Composicion( <?php echo $productos[$a]->getIdProducto() ?> )' 
						onmouseover="this.style.backgroundColor = '#D7EAFF'" 
						onmouseout="this.style.backgroundColor = 'white'"> 
						<img style='float:left;' src='../media/icons/basket_add_32.png'>
						<?php echo $productos[$a]->getDescripcion() ?>
					</td>
				<?php

			}
			echo "</tr>";
		echo "</table>";
		?>
</div>




<!-- 
	Inventario Maestro
 -->
<div id="inventario_maestro" style="display: none;">
	<h2>Inventario Maestro</h2>
	<h3>&iquest; Como se conformara este producto ?</h3>
	<div id="InventarioMaestroTabla"></div>
 	<div id="inventario-maestro-grid" style="padding: 5px;"></div> 	
</div>




<!-- 
	Totales de la composicion
 -->
<div id="composicion" style="display: none;">
<h2>Composicion del producto</h2>

	<div >
		<table style="width:100%">
        <tr id="composicion_tabla">
			<td></td>
			<td>Remision</td>
			<td>Producto</td>
			<td>Cantidad</td>
			<td>Procesado</td>
			<td>Precio</td>
			<td>Descuento</td>
			<td>Importe</td>    
        </tr>
        </table>
	</div>

	<h2>Detalles del producto a vender</h2>
	<table >
		<tr><td>Enviar producto procesado</td><td>
			<input style="width: 100px; margin: 5px;" id="compuesto-procesado" type="checkbox">
		</td></tr>		
		<tr><td>Peso real</td><td>
			<div style="width: 100px; margin: 5px;" id="compuesto-peso-real" >0.00</div>

		</td></tr>
		<tr><td>Peso a cobrar</td><td>
			<div style="width: 100px; margin: 5px;" id="compuesto-peso-a-cobrar" >0.00</div>
		
		</td></tr>		
		<tr><td>Importe por unidad</td><td>
			<div style="width: 100px; margin: 5px;" id="compuesto-importe-por-unidad" >$0.00</div>
			
		</td></tr>
		<tr><td>Importe total por este producto</td><td>
			<div style="width: 100px; margin: 5px;" id="compuesto-importe-total" >$0.00</div>

			
		</td></tr>
	</table>
	<h4 >
		<input type="button" value="Aceptar" onclick="composicionActual.commitMix()">
		<input type="button" value="Cancelar" onclick="composicionActual.rollbackMix()">
	</h4>
</div>