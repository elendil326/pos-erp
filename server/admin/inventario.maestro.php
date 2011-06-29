<?php


require_once("controller/inventario.controller.php");
require_once("controller/sucursales.controller.php");

require_once('model/actualizacion_de_precio.dao.php');
require_once('model/compra_proveedor.dao.php');





$productos = InventarioDAO::getAll();


?>
<script>
function detalle_inventario(id){
	window.location = "inventario.php?action=detalle&id="+ id;
}
</script>
<div  >
	<h2>Productos</h2>
		<?php
		echo "<table border=0 style='width: 100%; font-size: 14px; cursor: pointer;'>";
			echo "<tr>";
			for($a = 0; $a < sizeof($productos); $a++){
				
				//buscar su precio sugerido actual
				$act = new ActualizacionDePrecio();
				$act->setIdProducto( $productos[$a]->getIdProducto() );
				$res = ActualizacionDePrecioDAO::search($act, "fecha", "desc");
				
				if(sizeof($res) == 0){
					$lastOne = new ActualizacionDePrecio();
					$lastOne->setPrecioVenta(0);
					$lastOne->setPrecioCompra(0);					
				}else{
					$lastOne = $res[0];					
				}

				if($a % 5 == 0){
					echo "</tr><tr>";
				}
				
				echo "<td class='prod rounded' id='producto-" . $productos[$a]->getIdProducto() . "'  onClick='detalle_inventario( " .  $productos[$a]->getIdProducto() . " )' >";
				echo "<img style='float:left;' src='../media/icons/basket_32.png'><br><div align=center ><b>" .  $productos[$a]->getDescripcion() . "</b></div>";
				echo "<div align=right style='padding-right:20px'>";
				echo " A la venta " . moneyFormat($lastOne->getPrecioVenta()) .  "<br>";
				if( POS_COMPRA_A_CLIENTES ){
					echo " A la compra " . moneyFormat($lastOne->getPrecioCompra()) ;
				}				
				echo "</div>";
				echo "</td>";
			}
			echo "</tr>";
		echo "</table>";
		?>
</div>
<style>
.prod {
    background:#fff;
    color:#333;
    text-decoration:none;
    padding:5px 10px;
	border:1px solid #fff;


    /* Add the transition properties! */
   	-webkit-transition-property: background-color, color, border; 
    -webkit-transition-duration: 300ms;

    /* you can control the acceleration curve here */
    -webkit-transition-timing-function: ease-in-out; 
}

.prod:hover {
    background:#D7EAFF;
    color:#000;
    border:1px solid #3F8CE9;
}
</style>
<?php
	##########################################################
	#	INVENTARIO MAESTRO PARA NO MULTISUCURSALES
	##########################################################
	if(!POS_MULTI_SUCURSAL){
		
		//buscar todos los productos en detalle inventario de la unica sucursal, que en este caso es la cero
		$inventario_simple = array();
		
		foreach( $productos as $p ){
			$d = DetalleInventarioDAO::getByPK( $p->getIdProducto(), 0 );
			if($d){
				//si existe el producto
				array_push( $inventario_simple, array_merge( $p->asArray(), $d->asArray() ) );
			}
		}

		$header = array( 
			"id_producto"=> "",
			"descripcion" => "Producto",
			"existencias"=> "Existencias actuales");

		function renderExistencias($n, $row){
			return $n . " " . $row["escala"] . "s";
		}

		$tabla = new Tabla( $header, $inventario_simple );
		$tabla->addNoData("El inventario maestro se encuentra vacio.");
		$tabla->addColRender("existencias", "renderExistencias");
		echo "<h2>Inventario Maestro</h2>";
		$tabla->render();
	
		return;
	}



	##########################################################
	#	INVENTARIO MAESTRO PARA MULTISUCURSALES
	##########################################################

	$iMaestro = listarInventarioMaestro(200, POS_SOLO_ACTIVOS) ;
	$iMaestroTerminados = listarInventarioMaestro(50, POS_SOLO_VACIOS) ;



?>



<script>
	jQuery("#MAIN_TITLE").html("Inventario Maestro");
	
	var inventario = <?php echo json_encode( $iMaestro ); ?>,
		inventarioAgotado = <?php echo json_encode( $iMaestroTerminados ); ?>,
		myData;
	
	Ext.onReady(function(){
	    Ext.QuickTips.init();

	    // NOTE: This is an example showing simple state management. During development,
	    // it is generally best to disable state management as dynamically-generated ids
	    // can change across page loads, leading to unpredictable results.  The developer
	    // should ensure that stable state ids are set for stateful components in real apps.    
	
		// uncomment this on deployment !!!
	    // Ext.state.Manager.setProvider(new Ext.state.CookieProvider());

		var inventario_formateado = [  ];

		var inventario_agotado_formateado = [];
		
		for (var i = inventario.length - 1; i >= 0; i--){
			inventario_formateado[i] = [
				inventario[i].arpillas,
				inventario[i].calidad,
				inventario[i].chofer,
				inventario[i].costo_flete,
				inventario[i].existencias,
				inventario[i].existencias_procesadas,
				inventario[i].fecha,
				inventario[i].fecha_origen,
				inventario[i].folio,
				inventario[i].id_compra_proveedor,
				inventario[i].id_producto,
				inventario[i].id_proveedor,
				inventario[i].kg,
				inventario[i].marca_camion,
				inventario[i].medida,
				inventario[i].merma_por_arpilla,
				inventario[i].modelo_camion,
				inventario[i].numero_de_viaje,
				inventario[i].peso_origen,
				inventario[i].peso_por_arpilla,
				inventario[i].peso_recibido,
				inventario[i].placas_camion,
				inventario[i].precio_por_kg,
				inventario[i].producto_desc,
				inventario[i].producto_tratamiento,
				inventario[i].productor,
				inventario[i].sitio_descarga,
				inventario[i].sitio_descarga_desc,
				inventario[i].total_origen,
				inventario[i].variedad,
				inventario[i].agrupacion,
				inventario[i].agrupacionTam								
			];
		};
		
		for (var i = inventarioAgotado.length - 1; i >= 0; i--){
			inventario_agotado_formateado[i] = [
				inventarioAgotado[i].arpillas,
				inventarioAgotado[i].calidad,
				inventarioAgotado[i].chofer,
				inventarioAgotado[i].costo_flete,
				inventarioAgotado[i].existencias,
				inventarioAgotado[i].existencias_procesadas,
				inventarioAgotado[i].fecha,
				inventarioAgotado[i].fecha_origen,
				inventarioAgotado[i].folio,
				inventarioAgotado[i].id_compra_proveedor,
				inventarioAgotado[i].id_producto,
				inventarioAgotado[i].id_proveedor,
				inventarioAgotado[i].kg,
				inventarioAgotado[i].marca_camion,
				inventarioAgotado[i].medida,
				inventarioAgotado[i].merma_por_arpilla,
				inventarioAgotado[i].modelo_camion,
				inventarioAgotado[i].numero_de_viaje,
				inventarioAgotado[i].peso_origen,
				inventarioAgotado[i].peso_por_arpilla,
				inventarioAgotado[i].peso_recibido,
				inventarioAgotado[i].placas_camion,
				inventarioAgotado[i].precio_por_kg,
				inventarioAgotado[i].producto_desc,
				inventarioAgotado[i].producto_tratamiento,
				inventarioAgotado[i].productor,
				inventarioAgotado[i].sitio_descarga,
				inventarioAgotado[i].sitio_descarga_desc,
				inventarioAgotado[i].total_origen,
				inventarioAgotado[i].variedad,
				inventarioAgotado[i].agrupacion,
				inventarioAgotado[i].agrupacionTam								
			];

		};


	    // create the data store
	    function getNewStore () {
			return new Ext.data.ArrayStore({
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
		}
		
		
	 	var store = getNewStore(),
			storeAgotado = getNewStore();
	
	
	
		function toSmallUnit( unit ){
			switch(unit){
				case "kilogramo" : return "Kgs";
				case "pieza" : return "Pzas";
				case "arpilla" : return "Arps";
				case "cajas" : return "Cjs";
				case "bulto" : return "Blts";				
			}
		}

	    // manually load local data
	    store.loadData(inventario_formateado);
		storeAgotado.loadData(inventario_agotado_formateado);
		
	    // create the Grid
	    var grid = new Ext.grid.GridPanel({
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
	                header   : 'productor', 
	                width    : 85, 
	                sortable : true, 
					hidden: false,	
	                dataIndex: 'productor'
	            },	
	            {
	                id       :'descripcion',
	                header   : 'Producto', 
	                width    : 120, 
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
						if(row.get("producto_tratamiento")){
							//con tratamiento !
							return n.toFixed(4);

						}else{
							//sin tratamiento
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
	                dataIndex: 'precio_por_kg',
					renderer : function(n,a,row){
						return '$' + ((n + (row.get("costo_flete") / row.get("peso_recibido"))).toFixed(2));
					}
	            },			
	            {
	                header   : 'Originales', 
	                width    : 150, 
	                sortable : true, 
					align 	 : "right",
					 renderer : function (n, a, row ){                                                       

							if(row.data.agrupacion.length > 0){
								//si hay agrupacion

								if(row.get("producto_tratamiento")){
									//con tratamiento !
									/*return  (parseFloat( n / row.data.peso_por_arpilla )).toFixed(2) + " " +  toSmallUnit(row.data.agrupacion)
									+"&nbsp;(<i>" + n.toFixed(2) + " " +  toSmallUnit(row.data.medida) + "</i>)";*/
                                                                        return  ( parseFloat( ( n - row.data.existencias_procesadas) / row.data.peso_por_arpilla )).toFixed(2) + " " +  toSmallUnit(row.data.agrupacion)
									+"&nbsp;(<i>" + n.toFixed(2) + " " +  toSmallUnit(row.data.medida) + "</i>)";

								}else{
									//sin tratamiento
									console.log(row.get("producto_desc"), row);
									console.log("sin tratamiento");			

									console.log(n, row.get("agrupacionTam"), parseFloat( n / row.get("agrupacionTam") ));
									return  (parseFloat( n / row.get("agrupacionTam") )).toFixed(2) + " " +  toSmallUnit(row.data.agrupacion)
									+"&nbsp;(<i>" + n.toFixed(2) + " " +  toSmallUnit(row.data.medida) + "</i>)";

								}

							}else{
								//no hay agrupacion
								return n.toFixed(2) + " " +  toSmallUnit(row.data.medida);
							}

						},
	                dataIndex: 'existencias'
	            },
	            {
	                header   : 'Procesadas', 
	                width    : 150, 
					align 	 : "right",	
	                sortable : true, 
	                renderer : function(n,a,row){
						if(isNaN(n)){
							return "-";
						}else{

								if(row.data.agrupacion.length > 0){
									//si hay agrupacion
									return  (parseFloat( n / row.data.agrupacionTam )).toFixed(2) + " " +  toSmallUnit(row.data.agrupacion)
									+"&nbsp;(<i>" + n.toFixed(2) + " " +  toSmallUnit(row.data.medida) + "</i>)";
								}else{
									//no hay agrupacion
									return n.toFixed(2) + " " +  toSmallUnit(row.data.medida);
								}


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
	        height: 350,
			minHeight : 300,
	        width: "100%",
			frame : false,
			header: false,
	        // title: 'Array Grid',
	        // config options for stateful behavior
	        stateful: false,
	        stateId: 'grid',
			listeners : {
				"rowclick" : function (grid, rowIndex, e){
					var datos = grid.getStore().getAt( rowIndex );
					window.location = "inventario.php?action=detalleCompra&compra=" + datos.get("id_compra_proveedor") + "&producto=" + datos.get("id_producto");
				}
			}
			
	    });


		var gridAgotado = new Ext.grid.GridPanel({
	        store: storeAgotado,
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
					renderer : function(folio){
						return folio.strike();
					},
	                dataIndex: 'folio'
	            },	
	            {
	                id       :'descripcion',
	                header   : 'Producto', 
	                width    : 120, 
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
					renderer : function(n, c){
						return n.toFixed(4);
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
					renderer : function(n,a,row){
						return '$' + ((n + (row.get("costo_flete") / row.get("peso_recibido"))).toFixed(2));
					},
	                dataIndex: 'precio_por_kg'
	            },			
	            {
	                header   : 'productor', 
	                width    : 85, 
	                sortable : true, 
	                dataIndex: 'productor'
	            },
	            {
	                header   : 'Originales', 
	                width    : 150, 
	                sortable : true, 
					hidden : true,
					align 	 : "right",
	                renderer : function (n, a, row ){

						if(row.data.agrupacion.length > 0){
							//si hay agrupacion

							if(row.get("producto_tratamiento")){
								//con tratamiento !
								return  (parseFloat( n / row.data.peso_por_arpilla )).toFixed(2) + " " +  toSmallUnit(row.data.agrupacion)
								+"&nbsp;(<i>" + n.toFixed(2) + " " +  toSmallUnit(row.data.medida) + "</i>)";

							}else{
								//sin tratamiento
								console.log(row.get("producto_desc"), row);
								console.log("sin tratamiento");			
												
								console.log(n, row.get("agrupacionTam"), parseFloat( n / row.get("agrupacionTam") ));
								return  (parseFloat( n / row.get("agrupacionTam") )).toFixed(2) + " " +  toSmallUnit(row.data.agrupacion)
								+"&nbsp;(<i>" + n.toFixed(2) + " " +  toSmallUnit(row.data.medida) + "</i>)";

							}

						}else{
							//no hay agrupacion
							return n.toFixed(2) + " " +  toSmallUnit(row.data.medida);
						}

					}, 
	                dataIndex: 'existencias'
	            },
	            {
	                header   : 'Procesadas', 
	                width    : 150, 
					align 	 : "right",	
	                sortable : true, 
					hidden : true,
	                renderer : function(n,a,row){
						if(isNaN(n)){
							return "-";
						}else{

								if(row.data.agrupacion.length > 0){
									//si hay agrupacion
									return  (parseFloat( n / row.data.agrupacionTam )).toFixed(2) + " " +  toSmallUnit(row.data.agrupacion)
									+"&nbsp;(<i>" + n.toFixed(2) + " " +  toSmallUnit(row.data.medida) + "</i>)";
								}else{
									//no hay agrupacion
									return n.toFixed(2) + " " +  toSmallUnit(row.data.medida);
								}


						}
					}, 
	                dataIndex: 'existencias_procesadas'
	            },	
	            {
	                header   : 'sitio_descarga_desc', 
	                width    : 95, 
	                sortable : true, 	
	                dataIndex: 'sitio_descarga_desc'
	            }
	        ],
	        stripeRows: true,
	        autoExpandColumn: 'descripcion',
	        height: 350,
			minHeight : 200,
	        width: "100%",
			frame : false,
			header: false,
	        // title: 'Array Grid',
	        // config options for stateful behavior
	        stateful: true,
	        stateId: 'gridAgotado',
                listeners : {
				"rowclick" : function (grid, rowIndex, e){
					var datos = grid.getStore().getAt( rowIndex );
					//window.location = "inventario.php?action=detalleCompra&compra=" + datos.get("id_compra_proveedor") + "&producto=" + datos.get("id_producto");
                                        
					window.location = "inventario.php?action=fragmentacion&id_compra_proveedor="+datos.get("id_compra_proveedor");
				}
			}
			
	    });


	    // render the grid to the specified div in the page
	    grid.render('inventario-maestro-grid');
	    gridAgotado.render('inventario-maestro-agotados-grid');	
	});


</script>


<h2>Embarques activos de proveedores</h2>
 <div id="inventario-maestro-grid" style="padding: 5px;"></div> 

<h2>Embarques agotados</h2> 
<div id="inventario-maestro-agotados-grid" style="padding: 5px;"></div> 