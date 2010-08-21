ApplicationFacturaVentas = function( id_venta , id_cliente ){

	if(DEBUG){
		console.log("ApplicationFacturaVentas: construyendo");
	}
	
	ApplicationFacturaVentas.currentInstance = this;
	this._init( id_venta , id_cliente );
	
};


ApplicationFacturaVentas.prototype.facturaVenta = null;

ApplicationFacturaVentas.prototype.toggleBtns = [];

ApplicationFacturaVentas.prototype.cantidadesProd = [];

ApplicationFacturaVentas.prototype.detalleFactura = null;

ApplicationFacturaVentas.prototype.facturarTodos = false;

ApplicationFacturaVentas.prototype.subtotFact = 0;

ApplicationFacturaVentas.prototype.ivaFact = 0;

ApplicationFacturaVentas.prototype.totFact = 0;

ApplicationFacturaVentas.prototype._init = function( id_venta , id_cliente ){
	
	ApplicationFacturaVentas.prototype.detalleFactura = [];
	ApplicationFacturaVentas.currentInstance.facturarPanel( id_venta , id_cliente);
	
};



/*------------------------------------------------------------
	PANEL QUE MUESTRA LOS DETALLES DE LA VENTA PARA PODER FACTURAR
--------------------------------------------------------------*/
ApplicationFacturaVentas.prototype.facturarPanel = function( id_venta , id_cliente ){
	/*
		elementos de la toolbar
	*/
	var buscar = [{
		xtype: 'button',
		text: 'Regresar',
		ui: 'back',
		id:'regresarFacturaVentas',
		handler:	function( ){
			//var detallesCte = ApplicacionClientes.currentInstance.addClientDetailsPanel( ApplicacionClientes.currentInstance.clienteSeleccionado ); 

						sink.Main.ui.setCard( ApplicacionClientes.currentInstance.ClientesList, { type: 'slide', direction: 'right'} );
						//console.log("Voy a a regresar con el wey seleccionado que es: ");
						//console.log( ApplicacionClientes.currentInstance.clienteSeleccionado );
					}
				
		}];

		var agregar = [{
			xtype: 'button',
			text: 'Imprimir Factura',
			ui: 'action',
			handler: function(){
					var jsonItems = Ext.util.JSON.encode( ApplicationFacturaVentas.currentInstance.detalleFactura );
					
					POS.AJAXandDECODE({
					action: '1801',
					id_venta: id_venta,
					jsonItems: jsonItems,
					todos: ApplicationFacturaVentas.currentInstance.facturarTodos
					},
					function (datos){//mientras responda
						if(!datos.success){
							POS.aviso("ERROR",""+datos.reason);
						}
						console.log(datos.datos);
					},
					function (){//no responde
						POS.aviso("ERROR","NO SE PUDO MOSTRAR DATOS DEL CLIENTE,  ERROR EN LA CONEXION :(");	
					}
				);//AJAXandDECODE 1004
					
				}
			}];		

        var dockedItems = [ new Ext.Toolbar({
            ui: 'dark',
            dock: 'bottom',
            items: buscar.concat({xtype:'spacer'}).concat(agregar)
        })];
	
	/*
		Panel principal
	*/
	var panel = new Ext.Panel({
		id: 'facturarVenta',
		scroll: 'vertical',
		dockedItems: dockedItems,
		items: [
				{
				id: 'datosClienteFactura',
				html: '' 
				},
				{
					xtype: 'toggle',
					id : 'facturarTodos',
					label: 'TODOS:',
					//value: 1,
					listeners: 
					{
						change: function(  ){
							if( ApplicationFacturaVentas.currentInstance.detalleFactura.length > 0 ){
							var tipo = Ext.getCmp("facturarTodos").getValue();
							var n = ApplicationFacturaVentas.currentInstance.toggleBtns.length;
							
							if( tipo == 1){
								
							
								for( b= 0; b < (n);  b++){
			
									ApplicationFacturaVentas.currentInstance.toggleBtns[b].setValue(1);
	
									ApplicationFacturaVentas.currentInstance.detalleFactura[b].facturar = true;
									
									ApplicationFacturaVentas.currentInstance.detalleFactura[b].cantidad = ApplicationFacturaVentas.currentInstance.cantidadesProd[ b ].cantidad;
									
									Ext.get("cantidadFact_"+b).dom.value = ApplicationFacturaVentas.currentInstance.cantidadesProd[ b ].cantidad;
									Ext.get("subtotFact_"+b).update("$ "+ (  ApplicationFacturaVentas.currentInstance.cantidadesProd[ b ].cantidad *  ApplicationFacturaVentas.currentInstance.cantidadesProd[ b ].precio ) );
								}
						
								Ext.get("totFactura").update("$ "+ ( ApplicationFacturaVentas.currentInstance.calculaTotalFactura() ) );
								ApplicationFacturaVentas.currentInstance.facturarTodos = true;
								
							}else{
								
								ApplicationFacturaVentas.currentInstance.facturarTodos = false;
							}//else
							}
						}
					}
				},
				{
				id: 'detallesFactura',
				html: ''
				},
				{
				id: 'subtotalesFact',
				html: "<div class='ApplicationClientes-item' >"
						+"<div class='pagado'>SUBTOT:</div><div class='abonar' id='subtotVta'></div>"
						+"<div class='pagado'>IVA: </div><div class='abonar' id='ivaVta'></div>"
						+"<div class='pagado'>TOTAL: </div><div class='abonar' id='totVta'></div>"
						+ "<div class='subtotal'>&nbsp;</div>"
						+"<div class='pagado'>TOTAL FACTURA: </div><div class='abonar' id='totFactura'></div>"
						+"</div>"
				}
				]
		});
	
	Ext.regModel('facturaModel', {

		});

		var detalleFacturaStore = new Ext.data.Store({
			model: 'facturaModel'
		});	
		
		POS.AJAXandDECODE({
			action: '1004',
			id: id_cliente
			},
			function (datos){//mientras responda
				if(!datos.success){
					POS.aviso("ERROR",""+datos.reason);
				}
				html ="";
				html2 ="";
				for(var obj in datos.datos){
					for ( var propiedad in datos.datos[obj] ){
						if( typeof(datos.datos[obj][propiedad]) != "function" ) {
							//html += datos.datos[obj][propiedad] +"<br>"; //imprime unicamente los valores						
							html2 += propiedad +" : "+datos.datos[obj][propiedad]+"<br>";
						}
					}
				}
				Ext.get("datosClienteFactura").update(html+html2);
			},
			function (){//no responde
				POS.aviso("ERROR","NO SE PUDO MOSTRAR DATOS DEL CLIENTE,  ERROR EN LA CONEXION :(");	
			}
		);//AJAXandDECODE 1004
				
		
		POS.AJAXandDECODE({
			action: '1402',
			id_venta: id_venta
			},
			function (datos){//mientras responda
				if(!datos.success){
					POS.aviso("ERROR",""+datos.reason);
				}
				
				
				detalleFacturaStore.loadData( datos.datos );
				
				var html = "";
					html += 
						"<div class='ApplicationClientes-item' >" 
							+ "<div class='vendedor'>PRODUCTO</div>" 
							+ "<div class='sucursal'>CANTIDAD</div>"  
							+ "<div class='subtotal'>PRECIO</div>" 
							+ "<div class='subtotal'>SUBTOTAL</div>"
							+ "<div class='subtotal'>&nbsp;</div>"
							+ "<div class='sucursal'>CANTIDAD FACTURAR</div>"
							+ "<div class='subtotal'>SUBTOTAL FACTURAR</div>"
						+ "</div>";
								
					for( a = 0; a < detalleFacturaStore.getCount(); a++ ){
					
						html += "<div class='ApplicationClientes-item' >" 
						+ "<div class='vendedor'>" + detalleFacturaStore.data.items[a].denominacion +"</div>" 
						+ "<div class='sucursal'>"+ detalleFacturaStore.data.items[a].cantidad +"</div>"
						
						
						+ "<div class='subtotal'>$ "+ detalleFacturaStore.data.items[a].precio+"</div>"
						+ "<div class='subtotal'>$ "+ detalleFacturaStore.data.items[a].subtotal +"</div>"
						+ "<div class='subtotal'>&nbsp;</div>"
						+ "<div class='sucursal'>" //this.htmlCart_items[a].description 
						+ "<INPUT TYPE='text' id='cantidadFact_"+a+"' SIZE='5' VALUE='"+detalleFacturaStore.data.items[a].cantidad+"' onchange='ApplicationFacturaVentas.currentInstance.cambiarCantidadFacturar("+a+",this.value, cantidadFact_"+a+")' class='description'>" 						
						+"</div>"
						
						+"<div class='pagado' id ='subtotFact_"+a+"'>$ "+ detalleFacturaStore.data.items[a].subtotal +"</div>"
						+ "<div class='vendedor' id='prod_"+detalleFacturaStore.data.items[a].id_producto+"'></div>"
						+ "</div>";
						
						ApplicationFacturaVentas.currentInstance.cantidadesProd.push( {
							cantidad: parseFloat( detalleFacturaStore.data.items[a].cantidad),
							precio : parseFloat( detalleFacturaStore.data.items[a].precio )
							});
						
											
						ApplicationFacturaVentas.currentInstance.detalleFactura.push({
							id:			parseInt( detalleFacturaStore.data.items[a].id_producto ),
							facturar:	true,
							cantidad:	parseFloat( detalleFacturaStore.data.items[a].cantidad )
						});
					}//fin for 
				
					//imprimir el html
					Ext.get("detallesFactura").update("<div class='ApplicationClientes-itemsBox'>" + html +"</div>");
					for( a = 0; a < detalleFacturaStore.getCount(); a++ ){
						fact = new Ext.form.Toggle({
							id: 'fProd_'+detalleFacturaStore.data.items[a].id_producto,
							renderTo: 'prod_'+detalleFacturaStore.data.items[a].id_producto,
							value: 1,
							
							listeners: {
							
								
								change:	function() {
										ApplicationFacturaVentas.currentInstance.facturaProducto ( this.getId() );
									}
							}
						});
						ApplicationFacturaVentas.currentInstance.toggleBtns.push( fact );
					}
					
			},
			function (){//no responde
				POS.aviso("ERROR","NO SE PUDO MOSTRAR DATOS DEL CLIENTE,  ERROR EN LA CONEXION :(");	
			}
		);//AJAXandDECODE 
	
	
	POS.AJAXandDECODE({
			action: '1407',
			id_venta: id_venta
			},
			function (datos){//mientras responda
				if(!datos.success){
					POS.aviso("ERROR",""+datos.reason);
				}
				
				Ext.get("subtotVta").update("$ "+ (datos.datos[0].subtotal) +"");
				Ext.get("ivaVta").update("$ "+ (datos.datos[0].iva) +"");
				Ext.get("totVta").update("$ "+ (datos.datos[0].total) +"");
				Ext.get("totFactura").update("$ "+ (datos.datos[0].total) +"");
				
				ApplicationFacturaVentas.currentInstance.subtotFact = parseFloat(datos.datos[0].subtotal);
				ApplicationFacturaVentas.currentInstance.ivaFact = parseFloat(datos.datos[0].iva);
				ApplicationFacturaVentas.currentInstance.totFact = parseFloat(datos.datos[0].total);
				
			},
			function (){//no responde
				POS.aviso("ERROR","NO SE PUDO CONSULTAR LA CABECERA DE LA VENTA,  ERROR EN LA CONEXION :(");	
			}
		);//AJAXandDECODE 1004
	
	
	
	this.facturaVenta = panel;
};//fin facturarPanel


ApplicationFacturaVentas.prototype.facturaProducto = function(  ban ){
	id = ban.substring(6);
	valor = Ext.getCmp(ban).getValue();

	dim = ApplicationFacturaVentas.currentInstance.detalleFactura.length;
		
	if( valor === 0){

		ApplicationFacturaVentas.currentInstance.facturarTodos = false;
				console.log("Ya puse facturar todos en false");
		Ext.getCmp("facturarTodos").setValue( false );
		
		for( i=0; i < dim; i++){
			if ( ApplicationFacturaVentas.currentInstance.detalleFactura[i].id == id ){
				ApplicationFacturaVentas.currentInstance.detalleFactura[i].facturar = false;

			}
		}
		
	}else{
		
		for( i=0; i < dim; i++){
			if ( ApplicationFacturaVentas.currentInstance.detalleFactura[i].id == id ){
				ApplicationFacturaVentas.currentInstance.detalleFactura[i].facturar = true;
			}
		}		
	}
	
	Ext.get("totFactura").update("$ "+ ( ApplicationFacturaVentas.currentInstance.calculaTotalFactura() ) );
};//fin facturaProducto


/*
	VERIFICA SI LA CANTIDAD A FACTURAR ES CORRECTA
	Cada vez que se cambie en un text box la cantidad entra aqui 
*/
ApplicationFacturaVentas.prototype.cambiarCantidadFacturar = function( indice , cantidad , idCaja ){

	if( isNaN(cantidad) )
	{
		idCaja.value = ""+ApplicationFacturaVentas.currentInstance.cantidadesProd[ indice ].cantidad;
		return;
	}
	
	canti = parseFloat(cantidad);
	
	if( canti >  ApplicationFacturaVentas.currentInstance.cantidadesProd[ indice ].cantidad || canti <= 0){
		
		POS.aviso("ERROR", "- NO PUEDE FACTURAR UNA CANTIDAD MAYOR DE LA QUE VENDIO PARA ESTE PRODUCTO <br>- DEBE FACTURAR UNA CANTIDAD MAYOR A 0");
		ApplicationFacturaVentas.currentInstance.detalleFactura[ indice ].cantidad = ApplicationFacturaVentas.currentInstance.cantidadesProd[ indice ].cantidad;
		
		idCaja.value = ""+ApplicationFacturaVentas.currentInstance.cantidadesProd[ indice ].cantidad;
		Ext.get("subtotFact_"+indice).update("$ "+( ApplicationFacturaVentas.currentInstance.cantidadesProd[ indice ].cantidad * ApplicationFacturaVentas.currentInstance.cantidadesProd[ indice ].precio ) );
		Ext.get("totFactura").update("$ "+ ( ApplicationFacturaVentas.currentInstance.calculaTotalFactura() ) );
		return;
	}
	
	ApplicationFacturaVentas.currentInstance.detalleFactura[ indice ].cantidad = canti;
	subtotF = canti * ApplicationFacturaVentas.currentInstance.cantidadesProd[ indice ].precio;
	Ext.get("subtotFact_"+indice).update("$ "+subtotF);
	
	Ext.get("totFactura").update("$ "+ ( ApplicationFacturaVentas.currentInstance.calculaTotalFactura() ) );
	
	ApplicationFacturaVentas.currentInstance.facturarTodos = false;
	Ext.getCmp("facturarTodos").setValue(0);
}; //fin cambiarCantidadFacturar




ApplicationFacturaVentas.prototype.calculaTotalFactura = function(){ 

	dimension = ApplicationFacturaVentas.currentInstance.detalleFactura.length;
	totF = 0;
	for (q = 0; q < dimension; q++){
		
		if( ApplicationFacturaVentas.currentInstance.detalleFactura[q].facturar == true){
			totF += ApplicationFacturaVentas.currentInstance.detalleFactura[q].cantidad * ApplicationFacturaVentas.currentInstance.cantidadesProd[q].precio;
		}
	}
	
	return totF;
};