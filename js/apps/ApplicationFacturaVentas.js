
ApplicationFacturaVentas = function( id_venta , id_cliente ){

	if(DEBUG){
		console.log("ApplicationFacturaVentas: construyendo");
	}
	
	ApplicationFacturaVentas.currentInstance = this;
	this._init( id_venta , id_cliente );
	
};


ApplicationFacturaVentas.prototype.facturaVenta = null;

ApplicationFacturaVentas.prototype.toggleBtns = [];

ApplicationFacturaVentas.prototype.detalleFactura = null;

ApplicationFacturaVentas.prototype.facturarTodos = true;


ApplicationFacturaVentas.prototype._init = function( id_venta , id_cliente ){
	ApplicationFacturaVentas.prototype.detalleFactura = [];
	ApplicationFacturaVentas.currentInstance.facturarPanel( id_venta , id_cliente);
	
};


/*------------------------------------------------------------
	PANEL QUE MUESTRA LOS DETALLES DE LA VENTA PARA PODER FACTURAR
--------------------------------------------------------------*/
ApplicationFacturaVentas.prototype.facturarPanel = function( id_venta , id_cliente ){
	

	var panel = new Ext.Panel({
		id: 'facturarVenta',
		scroll: 'vertical',
		items: [
				{
				id: 'datosClienteFactura',
				html: '' 
				},
				{
					xtype: 'toggle',
					id : 'facturarTodos',
					label: 'FACTURAR TODOS: ',
					value: 1,
					listeners: 
					{
						change: function(  ){
							if(ApplicationFacturaVentas.currentInstance.detalleFactura.length > 0){
							var tipo = Ext.getCmp("facturarTodos").getValue();
							var n = ApplicationFacturaVentas.currentInstance.toggleBtns.length;
							//console.log("--->n:",n);
							if( tipo == 1){
								
								//console.log("tamaño:",ApplicationFacturaVentas.currentInstance.detalleFactura.length);
	//							console.log("0:",ApplicationFacturaVentas.currentInstance.detalleFactura[0]);						
								
		//						console.log("facturar:",ApplicationFacturaVentas.currentInstance.detalleFactura[0].facturar);
								
								/*for(var xxx in ApplicationFacturaVentas.currentInstance.detalleFactura){
									console.log("xxx",xxx);
									console.log("detalleFactura[xxx]",ApplicationFacturaVentas.currentInstance.detalleFactura[xxx]);
								}*/
								console.log(ApplicationFacturaVentas.currentInstance.toggleBtns)
								console.log(ApplicationFacturaVentas.currentInstance.detalleFactura)
								console.log(n)
								for( b= 0; b < (n);  b++){
			//						console.log("---i:",i);
									ApplicationFacturaVentas.currentInstance.toggleBtns[b].setValue(1);
									
									//console.log( "vuelta",  i, b);
									//console.log( ApplicationFacturaVentas.currentInstance.detalleFactura[i]);
									
									try{
									    ApplicationFacturaVentas.currentInstance.detalleFactura[b].facturar = true;
									}catch(e)
									{console.warn(b,ApplicationFacturaVentas.currentInstance.detalleFactura[b] );}

								}
								ApplicationFacturaVentas.currentInstance.facturarTodos = true;
								
							}else{
								/*for( i =0; i< dim; i++){
									ApplicationFacturaVentas.currentInstance.toggleBtns[i].setValue(0);
									ApplicationFacturaVentas.currentInstance.detalleFactura[i].facturar = false;
								}*/
								ApplicationFacturaVentas.currentInstance.facturarTodos = false;
							}//else
							}
						}
					}
				},
				{
				id: 'detallesFactura',
				html: ''
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
						+ "</div>";
								
					for( a = 0; a < detalleFacturaStore.getCount(); a++ ){
					
						html += "<div class='ApplicationClientes-item' >" 
						+ "<div class='vendedor'>" + detalleFacturaStore.data.items[a].denominacion +"</div>" 
						+ "<div class='sucursal'>"+ detalleFacturaStore.data.items[a].cantidad +"</div>" 
						+ "<div class='subtotal'>$ "+ detalleFacturaStore.data.items[a].precio+"</div>"
						+ "<div class='subtotal'>$ "+ detalleFacturaStore.data.items[a].subtotal +"</div>"
						+ "<div class='vendedor' id='prod_"+detalleFacturaStore.data.items[a].id_producto+"'></div>"
						+ "</div>";
						
						ApplicationFacturaVentas.currentInstance.detalleFactura.push({
							id:			detalleFacturaStore.data.items[a].id_producto,
							facturar:	true,
							cantidad:	detalleFacturaStore.data.items[a].cantidad
						});
					}
								
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
		);//AJAXandDECODE 1105
	
	this.facturaVenta = panel;
};//fin facturarPanel


ApplicationFacturaVentas.prototype.facturaProducto = function(  ban ){
	id = ban.substring(6);
	valor = Ext.getCmp(ban).getValue();
	//POS.aviso("qqq","Id del producto : "+id+" se va a facturar? "+valor+" el id del componente es: "+ban);
	dim = ApplicationFacturaVentas.currentInstance.detalleFactura.length;
		
	if( valor === 0){

		ApplicationFacturaVentas.currentInstance.facturarTodos = false;
				console.log("Ya puse facturar todos en false");
		Ext.getCmp("facturarTodos").setValue( false );
		
		for( i=0; i < dim; i++){
			if ( ApplicationFacturaVentas.currentInstance.detalleFactura[i].id == id ){
				ApplicationFacturaVentas.currentInstance.detalleFactura[i].facturar = false;
				//console.log("ApplicationFacturaVentas.currentInstance.detalleFactura[i].facturar:",ApplicationFacturaVentas.currentInstance.detalleFactura[i].facturar);
			}
		}
		
	}else{
		
		for( i=0; i < dim; i++){
			if ( ApplicationFacturaVentas.currentInstance.detalleFactura[i].id == id ){
				ApplicationFacturaVentas.currentInstance.detalleFactura[i].facturar = true;
			}
		}		
	}
};//fin facturaProducto