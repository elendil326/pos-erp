ApplicationComprasProveedor = function( idProveedor ){
	if(DEBUG){
		console.log("ApplicationComprasProveedor: construyendo");
	}
	
	this._init( idProveedor );
	
	ApplicationComprasProveedor.currentInstance = this;
	
	return this;
};


ApplicationComprasProveedor.prototype.surtir = null;
ApplicationComprasProveedor.prototype.providerId = null;
ApplicationComprasProveedor.prototype._BANDERA = false;//false = toño mode
ApplicationComprasProveedor.prototype._BANDERATOGGLES = false;
ApplicationComprasProveedor.prototype._BANDERATOGGLES2 = false;
ApplicationComprasProveedor.prototype.toggleBtns = [];
ApplicationComprasProveedor.prototype.toggleBtns2 = [];
ApplicationComprasProveedor.prototype.compraItems = [];
ApplicationComprasProveedor.prototype.inventarioItems = [];
ApplicationComprasProveedor.prototype.nombreProv = "";

ApplicationComprasProveedor.prototype._init = function( idProveedor ){
	
	this.comprarPanel( idProveedor.id_proveedor );
	this.providerId = idProveedor.id_proveedor;
	this.nombreProv = idProveedor.nombre;
};


/*
	Metodo que devuelve un panel principal de tipo Carousel para desplegar
*/

ApplicationComprasProveedor.prototype.comprarPanel = function( idProveedor ){

	//por si se esta realizando una compra y se pica en otra aplicacion se reestablece el arreglo de los items
	//para no generar errores y no se queden items almacenados para futuras compras
	this.compraItems.length = 0;
	this.inventarioItems.length = 0;
	this.toggleBtns.length = 0;
	this.toggleBtns2.length = 0;
	this.pesoArpilla = 0;
	this.totalArpillas = 0;
	
	var buscar = [{
		xtype: 'button',
		text: 'Regresar',
		ui: 'back',
		id:'regresarComprasProveedor',
		handler:	function( ){
						ApplicationComprasProveedor.currentInstance.toggleBtns.length = 0;
						ApplicationComprasProveedor.currentInstance.toggleBtns2.length = 0;
						ApplicationComprasProveedor.currentInstance.compraItems.length = 0;
						ApplicationComprasProveedor.currentInstance.inventarioItems.length = 0;
						ApplicationComprasProveedor.currentInstance.pesoArpilla = 0;
						ApplicationComprasProveedor.currentInstance.totalArpillas = 0;
						
						sink.Main.ui.setCard( ApplicationProveedores.currentInstance.mainCard, { type: 'slide', direction: 'right' } );
						
					}
				
		}];

	var agregar = [{
		xtype: 'button',
		text: 'Comprar',
		ui: 'action',
		id: '_btnComprarProducto',
		handler: function(){
				
				ApplicationComprasProveedor.currentInstance.doComprar();
			}
		}];
	
	var agregarInventario = [{
		xtype: 'button',
		text: 'Agregar Producto',
		ui: 'action',
		id: '_btnAgregarProducto',
		
		handler: function(){
				
				ApplicationComprasProveedor.currentInstance.do_agregarInventario();
			}
		}];

        var dockedItems = [ new Ext.Toolbar({
            ui: 'dark',
            dock: 'bottom',
            items: buscar.concat({xtype:'spacer'}).concat(agregar).concat(agregarInventario)
        })];

	
	
	
	var panelCompras = new Ext.Panel({
		id: 'surtir_proveedor',
		//scroll: 'vertical',
		//dockedItems: dockedItems,
		items: [
				{
				id: 'embarqueDatos',
				html: '',
				items:[
					{
					xtype: 'textfield',
					label: 'Peso en Kgs del Embarque:',
					id: 'pesoEmbarque',
					cls: 'ApplicationComprasProveedor-pesoEmbarque',
					value: 0,
					hidden : true,
					listeners: {
						change: function(){
								
						ApplicationComprasProveedor.currentInstance.pesoEmbarque();
									
								
						}//function
					}//listener
					}//item textfield
				]
				},
				{
					id:'arpillasDatos',
					html: "<div class='ApplicationComprasProveedor-pesoEmbarque' >" 
							+ "<div class='ApplicationComprasProveedor-numeroArpillas'>Numero Arpillas: </div>" 
							
							+ "<div id='totalArps' class='ApplicationComprasProveedor-totalArpillas'>0</div>" 
							
							+ "<div class='ApplicationComprasProveedor-PesoArpilla'>Peso por Arpilla: </div>" 
							
							+ "<div id='pesoArpillas' class= 'ApplicationComprasProveedor-pesoArpillas'>0</div>" 
							
						+ "</div>",
					hidden : true
					//cls: 'ApplicationComprasProveedor-pesoEmbarque'
				}
				,
				
				{

				xtype: 'panel',
				title: 'panelLeft',
				id: 'proveedorProductos_SucursalContainer',
				cls: 'ApplicationComprasProveedor-proveedorProductos_Sucursal',
				items:[{ html: '<div id="proveedorProductos_Sucursal" class="ApplicationComprasProveedor-proveedorProductos_Sucursal2"><div class="ApplicationComprasProveedor-itemsBox" id="productosProvSucursal"></div>' }]
				}
				,
				{
				scroll: 'vertical',
				xtype: 'panel',
				title: 'panelRi',
				cls:'ApplicationComprasProveedor-proveedorProductos_Sucursal',
				items:[{
				id: 'totales_Compras',  
				html: "<div id = 'totalesCompra' class='ApplicationComprasProveedor-proveedorProductos_Sucursal2' >"
						+"</div>"
				}]
				}
				],
		listeners: {
			afterrender: function(){

				if(!ApplicationComprasProveedor.currentInstance._BANDERA){
					console.log("SI ES FALSO DEBO DE ENTRAR A SURTIR POR KGS (TOÑO MODE)");
					Ext.getCmp("pesoEmbarque").show();
					Ext.getCmp("arpillasDatos").show();
					
				}
			}
		}
	});
	
	
	/*
		Se llama a funcion q ejecuta ajax para llenar con html las divs del panel compras
	*/
	if( !this._BANDERA ){
		this.llenarPanelComprasXarpillas( idProveedor ); 
	}else{
		this.llenarPanelComprasXpiezas( idProveedor ); 
	}
	
	
	
	/*
	
	*/
	var carousel = new Ext.Carousel({
		defaults:{cls: 'ApplicationClientes-mainPanel'},
		items: [{
			scroll: 'vertical',
			xtype: 'panel',
			title: 'panel_compra_Proveedor',
			id: 'compraProveedorPanel',
			items: [panelCompras],
			listeners:{
				activate: function(){
					Ext.getCmp("_btnComprarProducto").show();
					Ext.getCmp("_btnAgregarProducto").hide();
				}
			}
		}, {
			//scroll: 'vertical',
			xtype: 'panel',
			scroll: 'vertical',
			title: 'producto_proveedor',
			id: 'nuevoProductoProveedor',
			items: [ {
					id:'listaProductosAgregar',
					html: '<div id="Productos-Proveedor" ></div>',
					
					}
				],
			listeners:{
				activate: function(){
					Ext.getCmp("_btnComprarProducto").hide();
					Ext.getCmp("_btnAgregarProducto").show();
				}
			}//fin listeners
			
		}],
	});
	
	/*
		Se llama a la funcion que renderea el html del panel con los productos que ofrece el proveedor
	*/
	this.llenarPanelProductosProveedor( idProveedor );
	
	
	var panelP = new Ext.Panel({
		dockedItems: dockedItems,
		layout: {
			type: 'vbox',
			align: 'stretch'
		},
		defaults: {
	      flex: 1
		},
		items: [carousel]
	});
	
	/*
	*/
	this.surtir = panelP;

};


/*
	Se dispara cada vez que un Toggle de un producto es cambiado de valor
	para seleccionar y agregarlo a la compra o eliminarlo de la compra
*/
ApplicationComprasProveedor.prototype.elegirProducto = function ( idToggle ){
	console.log("******* 4) Esto es el handler del toggle renderado");
	if( !ApplicationComprasProveedor.currentInstance._BANDERATOGGLES ){
		return;
	}
	console.log("Si ejectuo el metodo elegirProducto");
	id = idToggle.substring(6);
	
	if( !ApplicationComprasProveedor.currentInstance._BANDERA ){//toño mode
		
		
		if ( Ext.getCmp("CProd_"+id).getValue() == 1){
			document.getElementById("NoArpProducto_"+id).disabled = false;
			document.getElementById("KgsMenosProducto_"+id).disabled = false;
			document.getElementById("precioKgProducto_"+id).disabled = false;
			
			document.getElementById("NoArpProducto_"+id).value = 0;
			document.getElementById("KgsMenosProducto_"+id).value = 0;
			document.getElementById("precioKgProducto_"+id).value = 0;
			
			ApplicationComprasProveedor.currentInstance.agregarProductoCompra( id );
			
		}else{
			document.getElementById("NoArpProducto_"+id).disabled = true;
			document.getElementById("KgsMenosProducto_"+id).disabled = true;
			document.getElementById("precioKgProducto_"+id).disabled = true;
			
			document.getElementById("NoArpProducto_"+id).value = '';
			document.getElementById("KgsMenosProducto_"+id).value = '';
			document.getElementById("precioKgProducto_"+id).value = '';
			
			if( Ext.get("subtotalesProd_"+id) ){
				Ext.get("subtotalesProd_"+id).remove();
				
				ApplicationComprasProveedor.currentInstance.compras_doDeleteProduct( id );
			}
		}//fin else
		
		
	}else{
				
		if ( Ext.getCmp("CProd_"+id).getValue() == 1){
			document.getElementById("cantidadProducto_"+id).disabled = false;
			
			document.getElementById("cantidadProducto_"+id).value = 0;
			ApplicationComprasProveedor.currentInstance.compras_doAddProduct( id );
						
		}else{
			document.getElementById("cantidadProducto_"+id).disabled = true;
			document.getElementById("cantidadProducto_"+id).value = '';
			Ext.get("subtotProducto_"+id).update("$ 0");
			ApplicationComprasProveedor.currentInstance.compras_doDeleteProduct( id );
			
		}//fin else
		
	}//fin else ._BANDERA
};


/*
	Se dispara cada vez que un Toggle de un producto que ofrece el proveedor 
	pero aun no surte es cambiado de valor
	para seleccionar y agregarlo al inventario de la sucursal
*/
ApplicationComprasProveedor.prototype.elegirProductoInventario = function ( idToggle ){
	
	if( !ApplicationComprasProveedor.currentInstance._BANDERATOGGLES2 ){
		return;
	}
	
	id = idToggle.substring(8);
	
	
	if ( Ext.getCmp("agregar_"+id).getValue() == 1){
		document.getElementById("precioVenta_"+id).disabled = false;
		document.getElementById("stockMin_"+id).disabled = false;
		
		document.getElementById("stockMin_"+id).value = 0;
		document.getElementById("precioVenta_"+id).value = 0;
		
		denominacion = Ext.get("nuevoProd_"+id).getHTML()
		
		ApplicationComprasProveedor.currentInstance.inventario_doAddProduct( id , denominacion );
						
	}else{
		document.getElementById("precioVenta_"+id).disabled = true;
		document.getElementById("stockMin_"+id).disabled = true;
		
		document.getElementById("stockMin_"+id).value = '';
		document.getElementById("precioVenta_"+id).value = '';
		
		ApplicationComprasProveedor.currentInstance.inventario_doDeleteProduct( id );
			
	}//fin else
		
	
};

/*
	Agrega a la div de la derecha el producto seleccinado y lo agrega ala
	lista de items (arreglo) 
*/
ApplicationComprasProveedor.prototype.agregarProductoCompra = function( id_producto ){
	
	if(!Ext.get("subtotalesProd_"+id_producto)){
	
		var denominacion = document.getElementById("nombreProdComprar_"+id_producto).innerText;
		
		var producto = document.createElement("div");
			producto.id = "subtotalesProd_"+id_producto;
			
		var codigo = "<div class='ApplicationComprasProveedor-item' >" 
					+ "<div class='producto_nombre'>" + denominacion +"</div>" 
					
					+ "<div class='cabecera-subtotales-compra' id = 'pesoArpPagar_"+id_producto+"' >0"  
					+"</div>"
					
					+ "<div class='cabecera-subtotales-compra' id = 'KgsPagarProducto_"+id_producto+"' >0"  
					+"</div>"
							
					+ "<div class='cabecera-subtotales-compra' id = 'precioPorKgProducto_"+id_producto+"'>$ 0" 
					+"</div>"
							
							
					+ "<div class='cabecera-subtotales-compra' id = 'subtotalProducto_"+id_producto+"' >$ 0" 
					+"</div>"
				+"</div>";
		producto.innerHTML = codigo;		
		document.getElementById("totalesCompra").appendChild( producto );
		
		ApplicationComprasProveedor.currentInstance.compras_doAddProduct( id_producto );
	}
};

/*
	Agrega a la div abajo de la lista de productos que surte el proveedor a esta sucursal
*/
ApplicationComprasProveedor.prototype.htmlItemInventario = function(  ){
	
	items = ApplicationComprasProveedor.currentInstance.inventarioItems;
	ApplicationComprasProveedor.currentInstance._BANDERATOGGLES = false;	
	console.log("-----> 3) htmlItemInventario: se van a renderear: "+items.length+" items");
		
		
	for( a = 0; a < items.length; a++ ){
		
		if(!Ext.get("nombreProdComprar_"+items[a].id)){
			
			var producto = document.createElement("div");
			producto.className = 'ApplicationComprasProveedor-item';//
			var codigo = "";		
			codigo += ""
				+ "<div class='producto_nombre' id='nombreProdComprar_"+items[a].id+"'>" + items[a].denominacion +"</div>" 
						
				+ "<div class='caja_texto'>" 
				+ "<INPUT TYPE='text' id='NoArpProducto_"+items[a].id+"' SIZE='5'  onchange='ApplicationComprasProveedor.currentInstance.sumarArp("+items[a].id+",this.value, NoArpProducto_"+items[a].id+")' class='description' disabled='true'>" 
				+"</div>"
						
				+ "<div class='caja_texto'>" 
				+ "<INPUT TYPE='text' id='KgsMenosProducto_"+items[a].id+"' SIZE='5'  onchange='ApplicationComprasProveedor.currentInstance.revisarCantidadKg("+items[a].id+",this.value, KgsMenosProducto_"+items[a].id+")' class='description' disabled='true'>" 
				+"</div>"
						
						
				+ "<div class='caja_texto'>" 
				+ "<INPUT TYPE='text' id='precioKgProducto_"+items[a].id+"' SIZE='5'  onchange='ApplicationComprasProveedor.currentInstance.revisarCantidaPrecio("+items[a].id+",this.value, precioKgProducto_"+items[a].id+")' class='description' disabled='true'>" 
				+"</div>"
						
						
				+ "<div class = 'ApplicationComprasProveedor-toggle' id = 'Buy_prod_"+items[a].id+"'></div>";
						
			//	+ "</div>";
						
				producto.innerHTML = codigo;		
				document.getElementById("productosProvSucursal").appendChild( producto );
				
				Ext.get("nuevoItemInventario_"+items[a].id).remove();
		}
	}//fin for 
		
		
		//console.log(ApplicationComprasProveedor.currentInstance.toggleBtns);
	for( hh = 0; hh < items.length; hh++ ){
		if(Ext.get("nombreProdComprar_"+items[hh].id)){
			console.log("ENTRE A RENDEREAR ESTA MIERDA");
			buyProduct = new Ext.form.Toggle({
				id: 'CProd_'+items[hh].id,
				renderTo: 'Buy_prod_'+items[hh].id,
					listeners: {
						change:	function() {
								
							ApplicationComprasProveedor.currentInstance.elegirProducto( this.getId() );
						}
					}//listeners
			});

			ApplicationComprasProveedor.currentInstance.toggleBtns.push( buyProduct );
		}//fin if
	}//fin for hh
	//console.log("AHORA:");
	//console.log(ApplicationComprasProveedor.currentInstance.toggleBtns);
	
	ApplicationComprasProveedor.currentInstance._BANDERATOGGLES = true;	
	ApplicationComprasProveedor.currentInstance.inventarioItems.length = 0;
	

	POS.aviso("Compras Proveedor","SE HAN AGREGADO NUEVOS PRODUCTOS AL INVENTARIO DE ESTA SUCURSAL");
};

/*
	Lanza una llamada al servidor y trae los productos que surte el proveedor
	a la sucursal para generar el html y asi los renderea al panel principal 
	en su capa de la izquierda (Vender en modalidad por arpillas Toño Mode)
*/
ApplicationComprasProveedor.prototype.llenarPanelComprasXarpillas = function( idProveedor ){
	
	Ext.regModel('productosSucursal', {

	});

	var productosSucursal = new Ext.data.Store({
		model: 'productosSucursal'
	});	
	
	POS.AJAXandDECODE({
			action: '1211',
			id_proveedor: idProveedor
			},
			function (datos){//mientras responda
				if(!datos.success){
					POS.aviso("ERROR",""+datos.reason);
					return;
				}
				
				
				productosSucursal.loadData( datos.datos );
				
				
				var html = "";
					html += 
						"<div class='ApplicationComprasProveedor-item' >" 
							+ "<div class='producto_nombre'>PRODUCTO</div>" 
							
							+ "<div class='cabecera-compra'>No. Arpillas</div>" 
							
							+ "<div class='cabecera-compra'>Kgs Menos</div>" 
							
							+ "<div class='cabecera-compra'>Precio x Kg</div>" 
							
						+ "</div>";
								
					for( a = 0; a < productosSucursal.getCount(); a++ ){
					
						html += "<div class='ApplicationComprasProveedor-item' >" 
						+ "<div class='producto_nombre' id='nombreProdComprar_"+productosSucursal.data.items[a].id_producto+"'>" + productosSucursal.data.items[a].denominacion +"</div>" 
						
						+ "<div class='caja_texto'>" 
						+ "<INPUT TYPE='text' id='NoArpProducto_"+productosSucursal.data.items[a].id_producto+"' SIZE='5'  onchange='ApplicationComprasProveedor.currentInstance.sumarArp("+productosSucursal.data.items[a].id_producto+",this.value, NoArpProducto_"+productosSucursal.data.items[a].id_producto+")' class='description' disabled='true'>" 
						+"</div>"
						
						+ "<div class='caja_texto'>" 
						+ "<INPUT TYPE='text' id='KgsMenosProducto_"+productosSucursal.data.items[a].id_producto+"' SIZE='5'  onchange='ApplicationComprasProveedor.currentInstance.revisarCantidadKg("+productosSucursal.data.items[a].id_producto+",this.value, KgsMenosProducto_"+productosSucursal.data.items[a].id_producto+")' class='description' disabled='true'>" 
						+"</div>"
						
						
						+ "<div class='caja_texto'>" 
						+ "<INPUT TYPE='text' id='precioKgProducto_"+productosSucursal.data.items[a].id_producto+"' SIZE='5'  onchange='ApplicationComprasProveedor.currentInstance.revisarCantidaPrecio("+productosSucursal.data.items[a].id_producto+",this.value, precioKgProducto_"+productosSucursal.data.items[a].id_producto+")' class='description' disabled='true'>" 
						+"</div>"
						
						
						+ "<div class = 'ApplicationComprasProveedor-toggle' id = 'Buy_prod_"+productosSucursal.data.items[a].id_producto+"'></div>"
						
						+ "</div>";
						
						
					}//fin for 
				
					//imprimir el html
					Ext.get("productosProvSucursal").update("" + html +"</div>");
					
					Ext.get("totalesCompra").update("<div class='ApplicationComprasProveedor-item' >" 
							+ "<div class='producto_nombre'>PRODUCTO</div>" 
							
							+ "<div class='cabecera-subtotales-compra' >Peso Arp</div>"
							
							+ "<div class='cabecera-subtotales-compra'>Kgs a Pagar</div>" 
							
							+ "<div class='cabecera-subtotales-compra'>Precio x Kg</div>" 
							
							+ "<div class='cabecera-subtotales-compra'>Subtotal</div>" 
							
						+ "</div>");
					
					for( hh = 0; hh < productosSucursal.getCount(); hh++ ){
						buyProduct = new Ext.form.Toggle({
							id: 'CProd_'+productosSucursal.data.items[hh].id_producto,
							renderTo: 'Buy_prod_'+productosSucursal.data.items[hh].id_producto,
							listeners: {
								change:	function() {
									
										ApplicationComprasProveedor.currentInstance.elegirProducto( this.getId() );
								}
							}//listeners
						});
						ApplicationComprasProveedor.currentInstance.toggleBtns.push( buyProduct );
					}
					ApplicationComprasProveedor.currentInstance._BANDERATOGGLES = true;	
			},
			function (){//no responde
				POS.aviso("ERROR","NO SE PUDO MOSTRAR DATOS DEL CLIENTE,  ERROR EN LA CONEXION :(");	
			}
		);//AJAXandDECODE
};

/*
	Lanza una llamada al servidor y trae los productos que surte el proveedor
	a la sucursal para generar el html y asi los renderea al panel principal 
	en su capa de la izquierda (Vender en modalidad por piezas General Mode)
*/
ApplicationComprasProveedor.prototype.llenarPanelComprasXpiezas = function( idProveedor ){
	
	Ext.regModel('productosSucursal', {

	});

	var productosSucursal = new Ext.data.Store({
		model: 'productosSucursal'
	});	
	
	POS.AJAXandDECODE({
			action: '1211',
			id_proveedor: idProveedor
			},
			function (datos){//mientras responda
				if(!datos.success){
					POS.aviso("ERROR",""+datos.reason);
					return;
				}
				
				
				productosSucursal.loadData( datos.datos );
				
				
				var html = "";
					html += 
						"<div class='ApplicationComprasProveedor-item' >" 
							+ "<div class='producto_nombre'>PRODUCTO</div>" 
							
							+ "<div class='cabecera-compra'>PRECIO</div>" 
							
							+ "<div class='cabecera-compra'>CANTIDAD</div>" 
							
							+ "<div class='cabecera-compra'>SUBTOTAL</div>" 
						+ "</div>";
					
					for( a = 0; a < productosSucursal.getCount(); a++ ){
						
						html += "<div class='ApplicationComprasProveedor-item' >" 
						+ "<div class='producto_nombre' id='nombreProdComprar_"+productosSucursal.data.items[a].id_producto+"'>" + productosSucursal.data.items[a].denominacion +"</div>" 
						
						+ "<div class='cabecera-subtotales-compra'>$ "+ 
							productosSucursal.data.items[a].precio
							
						+"</div>"
						
						+ "<div class='caja_texto'>" 
						+ "<INPUT TYPE='text' id='cantidadProducto_"+productosSucursal.data.items[a].id_producto+"' SIZE='5'  onchange='ApplicationComprasProveedor.currentInstance.sumaCantidad("+productosSucursal.data.items[a].id_producto+",this.value, cantidadProducto_"+productosSucursal.data.items[a].id_producto+")' disabled='true'>" 
						+"</div>"
						
						
						+ "<div class='cabecera-subtotales-compra' id= 'subtotProducto_"+productosSucursal.data.items[a].id_producto+"' >$ 0" 
						
						+"</div>"
						
						
						+ "<div class = 'ApplicationComprasProveedor-toggle' id = 'Buy_prod_"+productosSucursal.data.items[a].id_producto+"'></div>"
						
						+ "</div>";
						
						
					}//fin for 
				
					//imprimir el html
					Ext.get("proveedorProductos_Sucursal").update("<div class='ApplicationComprasProveedor-itemsBox'>" + html +"</div>");
					
					Ext.get("totalesCompra").update("<div class='ApplicationComprasProveedor-item' >" 
							+ "<div class='cabecera-subtotales-compra'>SUBTOTAL:</div>"						
							+ "<div class='cabecera-subtotales-compra' id ='subtotal_compraProducto'>$ 0</div>"
							
							+ "<div class='cabecera-subtotales-compra' >IVA:</div>" 
							+ "<div class='cabecera-subtotales-compra' id= 'iva_compraProducto'>$ 0</div>" 
							
							+ "<div class='cabecera-subtotales-compra'>TOTAL:</div>" 
							+ "<div class='cabecera-subtotales-compra' id='total_compraProducto'>$ 0</div>" 
							
						+ "</div>");
					
					
					for( hh = 0; hh < productosSucursal.getCount(); hh++ ){
						buyProduct = new Ext.form.Toggle({
							id: 'CProd_'+productosSucursal.data.items[hh].id_producto,
							renderTo: 'Buy_prod_'+productosSucursal.data.items[hh].id_producto,
							listeners: {
								change:	function() {
									
										ApplicationComprasProveedor.currentInstance.elegirProducto( this.getId() );
								}
							}//listeners
						});
						ApplicationComprasProveedor.currentInstance.toggleBtns.push( buyProduct );
					}
					ApplicationComprasProveedor.currentInstance._BANDERATOGGLES = true;	
			},
			function (){//no responde
				POS.aviso("ERROR","NO SE PUDO MOSTRAR DATOS DEL CLIENTE,  ERROR EN LA CONEXION :(");	
			}
		);//AJAXandDECODE
};

/*
	Lanza una llamada al servidor y trae los productos que ofrece el proveedor
	y aun no se venden en la sucursal, genera el html y asi los renderea al panel del carrusel
	que esta a la izquierda del panel principal
*/
ApplicationComprasProveedor.prototype.llenarPanelProductosProveedor = function( idProveedor ){
	
	Ext.regModel('productosProveedor', {

	});

	var nuevosProductos = new Ext.data.Store({
		model: 'productosProveedor'
	});	
	
	POS.AJAXandDECODE({
			action: '1221',
			id_proveedor: idProveedor
			},
			function (datos){//mientras responda
				if(!datos.success){
					POS.aviso("ERROR",""+datos.reason);
					return;
				}
				
				
				nuevosProductos.loadData( datos.datos );
				
				
				var html = "";
					html += 
						"<div class='ApplicationComprasProveedor-item' >" 
							+ "<div class='producto_nombre'>PRODUCTO</div>" 
							
							+ "<div class='cabecera-compra'>PRECIO PROVEEDOR</div>" 
							
							+ "<div class='cabecera-compra'>PRECIO VENTA</div>" 
							
							+ "<div class='cabecera-compra'>STOCK MINIMO</div>" 
							
						+ "</div>";
								
					for( a = 0; a < nuevosProductos.getCount(); a++ ){
					
						html += "<div class='ApplicationComprasProveedor-item' id ='nuevoItemInventario_"+nuevosProductos.data.items[a].id_producto+"'>" 
						+ "<div class='producto_nombre' id='nuevoProd_"+nuevosProductos.data.items[a].id_producto+"'>" + nuevosProductos.data.items[a].denominacion +"</div>" 
						
						+ "<div class='cabecera-compra'>" 
						+ nuevosProductos.data.items[a].precio
						+"</div>"
						
						+ "<div class='caja_texto'>" 
						+ "<INPUT TYPE='text' id='precioVenta_"+nuevosProductos.data.items[a].id_producto+"' SIZE='5'  onchange='ApplicationComprasProveedor.currentInstance.precioVenta("+nuevosProductos.data.items[a].id_producto+",this.value, precioVenta_"+nuevosProductos.data.items[a].id_producto+")' class='description' disabled='true'>" 
						+"</div>"
						
						
						+ "<div class='caja_texto'>" 
						+ "<INPUT TYPE='text' id='stockMin_"+nuevosProductos.data.items[a].id_producto+"' SIZE='5'  onchange='ApplicationComprasProveedor.currentInstance.stockMin("+nuevosProductos.data.items[a].id_producto+",this.value, stockMin_"+nuevosProductos.data.items[a].id_producto+")' class='description' disabled='true'>" 
						+"</div>"
						
						
						+ "<div class = 'ApplicationComprasProveedor-toggle' id = 'add_prod_"+nuevosProductos.data.items[a].id_producto+"'></div>"
						
						+ "</div>";
						
						
					}//fin for 
				
					//imprimir el html
					Ext.get("Productos-Proveedor").update("<div class='ApplicationComprasProveedor-itemsBox'>" + html +"</div>");
					
				
					
					for( hh = 0; hh < nuevosProductos.getCount(); hh++ ){
						addProduct = new Ext.form.Toggle({
							id: 'agregar_'+nuevosProductos.data.items[hh].id_producto,
							renderTo: 'add_prod_'+nuevosProductos.data.items[hh].id_producto,
							listeners: {
								change:	function() {
									
										  ApplicationComprasProveedor.currentInstance.elegirProductoInventario( this.getId() );
								}
							}//listeners
						});
						ApplicationComprasProveedor.currentInstance.toggleBtns2.push( addProduct );
					}
					ApplicationComprasProveedor.currentInstance._BANDERATOGGLES2 = true;	
			},
			function (){//no responde
				POS.aviso("ERROR","NO SE PUDO MOSTRAR LISTA DE PRODUCTOS DEL PROVEEDOR,  ERROR EN LA CONEXION :(");	
			}
		);//AJAXandDECODE
};

/*
	Lanza peticion al servidor para ver si el producto q se intenta comprar
	esta disponible y si esta lo agrega al arreglo de items por comprar, considera
	el modo de compra para ello debido a q la estructura del arreglo varia con el 
	modo de compra
*/

ApplicationComprasProveedor.prototype.compras_doAddProduct = function ( id_producto )
{
	
	
	
	
	//buscar si este producto existe (vender un producto que exista en la sucursal)
	POS.AJAXandDECODE({
			action: '1210',
			id_producto : id_producto,
			id_proveedor: ApplicationComprasProveedor.currentInstance.providerId,
		}, 
		function (datos){
			
			//ya llego el request con los datos si existe o no	
			if(!datos.success){
				POS.aviso("Error", datos.reason);
				return;
			}

			if(!ApplicationComprasProveedor.currentInstance._BANDERA){
			//crear el item
			var item = {//si es false entra a toño mode
				id 			: parseInt(datos.datos[0].id_producto),
				name 		: datos.datos[0].nombre,
				description : datos.datos[0].denominacion,
				cost 		: parseFloat(datos.datos[0].precio_venta),
				existencias : parseFloat(datos.datos[0].existencias),
				kgR			: 0,
				nA			: 0,
				prKg		: 0,
				//htmlCompra	: "",
				kgTot		: 0,
				pesoArp		: 0,
				subtot		: 0
			};
			}else{//si es true entra a modo surtir normal
				var item = {
				id 			: datos.datos[0].id_producto,
				name 		: datos.datos[0].nombre,
				description : datos.datos[0].denominacion,
				cost 		: parseFloat(datos.datos[0].precio),//el precio de compra
				existencias : datos.datos[0].existencias,
				cantidad	: 0,
				subtot		: 0
			};
			}
			//agregarlo al carrito
			ApplicationComprasProveedor.currentInstance.compraItems.push( item );
			
			

		},
		function (){
			POS.aviso("Error", "Error en la conexion, porfavor intente de nuevo.");
		}
	);
	
};

/*
	agrega item al carrito de productos por agregar al inventario 
*/

ApplicationComprasProveedor.prototype.inventario_doAddProduct = function ( id_producto , denominacion )
{
	var item = {
		id 			: id_producto,
		precioVenta	: 0,
		stockMin : 0,
		denominacion: denominacion
	};
		
			//agregarlo al carrito
	ApplicationComprasProveedor.currentInstance.inventarioItems.push( item );
		
	
};

/*
	Elimina un producto de la lista de items por comprar
*/

ApplicationComprasProveedor.prototype.compras_doDeleteProduct = function( id_producto ){
	
	
	for( f = 0; f < ApplicationComprasProveedor.currentInstance.compraItems.length;  f++){
		
		if( ApplicationComprasProveedor.currentInstance.compraItems[ f ].id == id_producto ){
			//item already in cart
			ApplicationComprasProveedor.currentInstance.compraItems.splice( f , 1);
			break;
		}
	}
	
};

/*
	Elimina un producto de la lista de items de prodcutos por agregar al inventario
*/

ApplicationComprasProveedor.prototype.inventario_doDeleteProduct = function( id_producto ){
	
	
	for( f = 0; f < ApplicationComprasProveedor.currentInstance.inventarioItems.length;  f++){
		
		if( ApplicationComprasProveedor.currentInstance.inventarioItems[ f ].id == id_producto ){
			//item already in cart
			ApplicationComprasProveedor.currentInstance.inventarioItems.splice( f , 1);
			break;
		}
	}
	
};


/*---------------------------------------------------
	PROPIEDADES DE LA CLASE QUE SON USADAS UNICAMENTE PARA MODO DE COMPRA
	POR KGS 
-----------------------------------------------------*/
ApplicationComprasProveedor.prototype.pesoArpilla = 0;
ApplicationComprasProveedor.prototype.totalArpillas = 0;

/*---------------------------------------------------------
	METODO QUE SE USA UNICAMENTE PARA MODO DE COMPRA POR KGS (TOÑO MODE)
	SIRVE PARA IR SUMANDO EL NUMERO DE ARPILLAS E IR CALCULANDO LOS TOTALES Y REFRESCANDO
	EL HTML
-----------------------------------------------------------*/

ApplicationComprasProveedor.prototype.sumarArp = function( idProd ,  arpillas , cajaId ){
	
	var indiceItem = 0;
	
	for( f = 0; f < ApplicationComprasProveedor.currentInstance.compraItems.length;  f++){
		
		if( ApplicationComprasProveedor.currentInstance.compraItems[ f ].id == idProd ){
				//item already in cart
				indiceItem = f;
	
				break;
			}
	}//fin for
	
	if( !isNaN(arpillas) && arpillas > 0 )
	{
		ApplicationComprasProveedor.currentInstance.compraItems[ indiceItem ].nA = parseFloat(arpillas);
		
	}
	if ( arpillas === null || arpillas =='' || arpillas < 0 || isNaN(arpillas) ){
		cajaId.value = "0";
		
		ApplicationComprasProveedor.currentInstance.compraItems[ indiceItem ].nA = 0;
		document.getElementById( cajaId.id ).focus();

	}//fin else
	
	var totArp=0;
	
	for(i=0; i < ApplicationComprasProveedor.currentInstance.compraItems.length; i++){
		totArp += parseFloat(ApplicationComprasProveedor.currentInstance.compraItems[i].nA);
	}
	//se saca el peso de las arpillas con el peso del embarque entre el numero total de arpillas de la compra
	ApplicationComprasProveedor.currentInstance.pesoArpilla = Ext.getCmp("pesoEmbarque").getValue(true) / totArp;
	
	Ext.get("totalArps").update("" + totArp);
	Ext.get("pesoArpillas").update(""+ApplicationComprasProveedor.currentInstance.pesoArpilla.toFixed(2));


	//el peso de la arpilla a pagar de cada clasificacion a comprar se saca con el peso de la arpilla menos los Kilogramos que se le restan (Kgr: que vendria siendo la merma de que trae esa clasificacion).
			
	ApplicationComprasProveedor.currentInstance.compraItems[ indiceItem ].pesoArp = ApplicationComprasProveedor.currentInstance.pesoArpilla - ApplicationComprasProveedor.currentInstance.compraItems[ indiceItem ].kgR;
			
	//Los kilogramos de cada clasificacion que se van a pagar (kgTot) se saca restandole al peso de la arpilla los kilogramos restados de la clasificacion y esto se multiplica por el numero de arpillas de esa clasificacion
			
	ApplicationComprasProveedor.currentInstance.compraItems[ indiceItem ].kgTot = (ApplicationComprasProveedor.currentInstance.pesoArpilla - ApplicationComprasProveedor.currentInstance.compraItems[ indiceItem ].kgR) * ApplicationComprasProveedor.currentInstance.compraItems[ indiceItem ].nA;
			
	KgTot = parseFloat(ApplicationComprasProveedor.currentInstance.compraItems[ indiceItem ].kgTot);
	Ext.get("KgsPagarProducto_"+idProd).update(""+KgTot.toFixed(2));
	
	ApplicationComprasProveedor.currentInstance.totalArpillas = totArp;
	
	//cada vez que se cambia el numero de arpillas para un producto cambia el peso de la arpilla por eso se debe de cambiar el peso de arpilla de cada producto de la lista asi como su subtotal y numero de arpillas
	for(i=0; i < ApplicationComprasProveedor.currentInstance.compraItems.length; i++){
		
		ApplicationComprasProveedor.currentInstance.compraItems[i].kgTot = (ApplicationComprasProveedor.currentInstance.pesoArpilla - ApplicationComprasProveedor.currentInstance.compraItems[ i ].kgR) * ApplicationComprasProveedor.currentInstance.compraItems[ i ].nA;
		
		ApplicationComprasProveedor.currentInstance.compraItems[i].subtot = ApplicationComprasProveedor.currentInstance.compraItems[ i ].kgTot * ApplicationComprasProveedor.currentInstance.compraItems[ i ].prKg;
		
		ApplicationComprasProveedor.currentInstance.compraItems[i].pesoArp = ApplicationComprasProveedor.currentInstance.pesoArpilla - ApplicationComprasProveedor.currentInstance.compraItems[ i ].kgR;
		
		
		idProd = ApplicationComprasProveedor.currentInstance.compraItems[i].id;
		precio = parseFloat(ApplicationComprasProveedor.currentInstance.compraItems[i].prKg);
		subtot = parseFloat(ApplicationComprasProveedor.currentInstance.compraItems[i].subtot);
		kgtot = parseFloat(ApplicationComprasProveedor.currentInstance.compraItems[i].kgTot);
		pesoArpPagar = parseFloat(ApplicationComprasProveedor.currentInstance.compraItems[i].pesoArp);
		
		Ext.get("precioPorKgProducto_"+idProd+"").update("$ "+precio.toFixed(2));
		Ext.get("subtotalProducto_"+idProd+"").update("$ "+ subtot.toFixed(2));
		Ext.get("KgsPagarProducto_"+idProd).update(""+kgtot.toFixed(2));
		Ext.get("pesoArpPagar_"+idProd).update(""+pesoArpPagar.toFixed(2));
		
	}//fin for

};


/*---------------------------------------------------------
	METODO QUE SE USA UNICAMENTE PARA MODO DE COMPRA POR KGS (TOÑO MODE)
	SIRVE PARA IR SUMANDO EL NUMERO DE ARPILLAS E IR CALCULANDO LOS TOTALES Y REFRESCANDO
	EL HTML
-----------------------------------------------------------*/

ApplicationComprasProveedor.prototype.pesoEmbarque = function( ){

	var embarque = parseFloat( Ext.getCmp("pesoEmbarque").getValue() );
	var totArp=0;
	
	for(i=0; i < ApplicationComprasProveedor.currentInstance.compraItems.length; i++){
		totArp += parseFloat(ApplicationComprasProveedor.currentInstance.compraItems[i].nA);
	}
	
	if( !isNaN(embarque) && embarque > 0) 
	{
		if ( ApplicationComprasProveedor.currentInstance.compraItems.length == 0 ){
			return;
		}
		//se saca el peso de las arpillas con el peso del embarque entre el numero total de arpillas de la compra
		ApplicationComprasProveedor.currentInstance.pesoArpilla = Ext.getCmp("pesoEmbarque").getValue(true) / totArp;
		
	}else{
		Ext.getCmp("pesoEmbarque").setValue(0);
		
		ApplicationComprasProveedor.currentInstance.pesoArpilla = 0;
		
	}//fin else
	
	
	
	
	Ext.get("totalArps").update("" + totArp);
	Ext.get("pesoArpillas").update(""+ApplicationComprasProveedor.currentInstance.pesoArpilla.toFixed(2));

	ApplicationComprasProveedor.currentInstance.totalArpillas = totArp;
	
	//cada vez que se cambia el peso del embarque cambia el peso de la arpilla por eso se debe de cambiar el peso de arpilla de cada producto de la lista asi como su subtotal y numero de arpillas
	for(i=0; i < ApplicationComprasProveedor.currentInstance.compraItems.length; i++){
		
		ApplicationComprasProveedor.currentInstance.compraItems[i].kgTot = (ApplicationComprasProveedor.currentInstance.pesoArpilla - ApplicationComprasProveedor.currentInstance.compraItems[ i ].kgR) * ApplicationComprasProveedor.currentInstance.compraItems[ i ].nA;
		
		ApplicationComprasProveedor.currentInstance.compraItems[i].subtot = ApplicationComprasProveedor.currentInstance.compraItems[ i ].kgTot * ApplicationComprasProveedor.currentInstance.compraItems[ i ].prKg;
		
		ApplicationComprasProveedor.currentInstance.compraItems[i].pesoArp = ApplicationComprasProveedor.currentInstance.pesoArpilla - ApplicationComprasProveedor.currentInstance.compraItems[ i ].kgR;
		
		idProd = ApplicationComprasProveedor.currentInstance.compraItems[i].id;
		precio = parseFloat(ApplicationComprasProveedor.currentInstance.compraItems[i].prKg);
		subtot = parseFloat(ApplicationComprasProveedor.currentInstance.compraItems[i].subtot);
		kgtot =  parseFloat(ApplicationComprasProveedor.currentInstance.compraItems[i].kgTot);
		pesoArpPagar = parseFloat(ApplicationComprasProveedor.currentInstance.compraItems[i].pesoArp);
		
		Ext.get("precioPorKgProducto_"+idProd+"").update("$ "+precio.toFixed(2));
		Ext.get("subtotalProducto_"+idProd+"").update("$ "+ subtot.toFixed(2));
		Ext.get("KgsPagarProducto_"+idProd).update(""+kgtot.toFixed(2));
		Ext.get("pesoArpPagar_"+idProd).update(""+pesoArpPagar.toFixed(2));
	}//fin for

};


/*-------------------------------------------------------------------
	METODO QUE SE USA UNICAMENTE PARA MODO DE COMPRA POR KGS (TOÑO MODE)
	SIRVE PARA IR VALIDANDO LA CANTIDAD DE KGS DEL PRODUCTO E IR CALCULANDO LOS TOTALES Y REFRESCANDO
	EL HTML
---------------------------------------------------------------------*/

ApplicationComprasProveedor.prototype.revisarCantidadKg = function( idProd ,  kgsRebajados , cajaId ){
	
	var indiceItem = 0;
	
	for( f = 0; f < ApplicationComprasProveedor.currentInstance.compraItems.length;  f++){
		
		if( ApplicationComprasProveedor.currentInstance.compraItems[ f ].id == idProd ){
				//item already in cart
				indiceItem = f;
	
				break;
			}
	}//fin for

	if(!isNaN(kgsRebajados) && kgsRebajados > 0 )
	{

		ApplicationComprasProveedor.currentInstance.compraItems[ indiceItem ].kgR = kgsRebajados;
	}
	if ( kgsRebajados === null || kgsRebajados =='' || kgsRebajados < 0 || isNaN(kgsRebajados) ){
		cajaId.value = "0";
		ApplicationComprasProveedor.currentInstance.compraItems[ indiceItem ].kgR = 0;
		document.getElementById( cajaId.id ).focus();
	}
	ApplicationComprasProveedor.currentInstance.compraItems[ indiceItem ].kgTot = (ApplicationComprasProveedor.currentInstance.pesoArpilla - ApplicationComprasProveedor.currentInstance.compraItems[ indiceItem ].kgR) * ApplicationComprasProveedor.currentInstance.compraItems[ indiceItem ].nA;
	
	
	ApplicationComprasProveedor.currentInstance.compraItems[indiceItem].pesoArp = ApplicationComprasProveedor.currentInstance.pesoArpilla - ApplicationComprasProveedor.currentInstance.compraItems[ indiceItem ].kgR;
	
	ApplicationComprasProveedor.currentInstance.compraItems[ indiceItem ].subtot = ApplicationComprasProveedor.currentInstance.compraItems[ indiceItem ].prKg * ApplicationComprasProveedor.currentInstance.compraItems[ indiceItem ].kgTot;
	
	kgTot = parseFloat(ApplicationComprasProveedor.currentInstance.compraItems[indiceItem].kgTot);
	Ext.get("KgsPagarProducto_"+idProd).update(""+kgTot.toFixed(2));
	
	subtotal = parseFloat(ApplicationComprasProveedor.currentInstance.compraItems[indiceItem].subtot);
	Ext.get("subtotalProducto_"+idProd).update("$ "+subtotal.toFixed(2));
	
	pesoARP = parseFloat(ApplicationComprasProveedor.currentInstance.compraItems[indiceItem].pesoArp);
	Ext.get("pesoArpPagar_"+idProd).update(""+pesoARP.toFixed(2));

};


ApplicationComprasProveedor.prototype.revisarCantidaPrecio = function( idProd ,  precio , cajaId ){
	
	var indiceItem = 0;
	
	for( f = 0; f < ApplicationComprasProveedor.currentInstance.compraItems.length;  f++){
		
		if( ApplicationComprasProveedor.currentInstance.compraItems[ f ].id == idProd ){
				//item already in cart
				indiceItem = f;
	
				break;
			}
	}//fin for
	
	if( !isNaN(precio) && precio > 0 )
	{
		ApplicationComprasProveedor.currentInstance.compraItems[ indiceItem ].prKg = precio;
		ApplicationComprasProveedor.currentInstance.compraItems[ indiceItem ].subtot = precio * ApplicationComprasProveedor.currentInstance.compraItems[ indiceItem ].kgTot;
		
		price = parseFloat(precio);
		Ext.get("precioPorKgProducto_"+idProd+"").update("$ "+price.toFixed(2));
		
		subtotalP = (precio * (parseFloat(Ext.get("KgsPagarProducto_"+idProd).getHTML())));
		Ext.get("subtotalProducto_"+idProd+"").update("$ "+subtotalP.toFixed(2) );
		
	}
	if ( precio === null || precio =='' || precio < 0 || isNaN(precio) ){
		cajaId.value = "0";
		Ext.get("precioPorKgProducto_"+idProd+"").update("$ 0");
		Ext.get("subtotalProducto_"+idProd+"").update("$ 0");
		ApplicationComprasProveedor.currentInstance.compraItems[ indiceItem ].prKg = 0;
		document.getElementById( cajaId.id ).focus();
	}

};

ApplicationComprasProveedor.prototype.precioVenta = function( idProd ,  precio , cajaId ){
	
	var indiceItem = 0;
	
	for( f = 0; f < ApplicationComprasProveedor.currentInstance.inventarioItems.length;  f++){
		
		if( ApplicationComprasProveedor.currentInstance.inventarioItems[ f ].id == idProd ){
				//item already in cart
				indiceItem = f;
	
				break;
			}
	}//fin for
	
	if( !isNaN(precio) && precio > 0 )
	{
		ApplicationComprasProveedor.currentInstance.inventarioItems[ indiceItem ].precioVenta = precio;
		
				
	}
	if ( precio === null || precio =='' || precio < 0 || isNaN(precio) ){
		cajaId.value = "0";
		
		ApplicationComprasProveedor.currentInstance.inventarioItems[ indiceItem ].precioVenta = 0;
		document.getElementById( cajaId.id ).focus();
	}

};

ApplicationComprasProveedor.prototype.stockMin = function( idProd ,  stockM , cajaId ){
	
	var indiceItem = 0;
	
	for( f = 0; f < ApplicationComprasProveedor.currentInstance.inventarioItems.length;  f++){
		
		if( ApplicationComprasProveedor.currentInstance.inventarioItems[ f ].stockMin == stockM ){
				//item already in cart
				indiceItem = f;
	
				break;
			}
	}//fin for
	
	if( !isNaN(stockM) && stockM > 0 )
	{
		ApplicationComprasProveedor.currentInstance.inventarioItems[ indiceItem ].stockMin = stockM;
		
				
	}
	if ( stockM === null || stockM =='' || stockM < 0 || isNaN(stockM) ){
		cajaId.value = "0";
		
		ApplicationComprasProveedor.currentInstance.inventarioItems[ indiceItem ].stockMin = 0;
		document.getElementById( cajaId.id ).focus();
	}

};


ApplicationComprasProveedor.prototype.do_agregarInventario = function ()
{
	
	if(DEBUG){
		console.log("ApplicationCompras: do_agregarInventario called....");
	}
	
	ban = false;
	ban2= false;
	
	items = ApplicationComprasProveedor.currentInstance.inventarioItems;
	console.log("-----> 1) do_agregarInventario en inventario items hay: "+items.length+" elementos");
	for( a = 0; a < items.length;  a++){
		
		if(items[a].precioVenta == 0 )
		ban=true;
		if(items[a].stockMin == 0)
		ban2=true;
	}
		

		
	if(items.length == 0){
		POS.aviso("Agregar Productos", "Agregue primero al menos un producto para poder Agregar al inventario.");
		return;
	}
			
	if( ban ){
		POS.aviso("Agregar Productos", "Llenar las columnas 'Precio Venta' de todos los productos agregados con un numero mayor a 0");
		return;
	}
		
	if( ban2 ){
		POS.aviso("Agregar Productos", "Revisar en las columnas 'Stock Minimo' de cada producto que tenga un valor y que ese valor sea mayor '0'");
		return;
	}
	
	ApplicationComprasProveedor.currentInstance.do_agregarItemInventario();
};


ApplicationComprasProveedor.prototype.do_agregarItemInventario = function(){
	console.log("------> 2) AJAX do_agregarItemInventario estos son los items q se mandan: ");
	var jsonItems = Ext.util.JSON.encode(ApplicationComprasProveedor.currentInstance.inventarioItems);
	console.log(jsonItems);

	POS.AJAXandDECODE({
			action: '1220',//insertar compra
			jsonItems : jsonItems,
		}, 
		function (datos){
			
			//ya llego el request con los datos si existe o no	
			if(!datos.success){
				POS.aviso("Error", ""+datos.reason);
				return;
			}

			ApplicationComprasProveedor.currentInstance.htmlItemInventario();
		},
		function (){
			POS.aviso("Error", "Error en la conexion, porfavor intente de nuevo.");
		}
	);
	///fin ajaxazo


};

/*--------------------------------------------------------------
	METODO QUE SE USA UNICAMENTE PARA MODO DE COMPRA POR CANIIDAD(GENERAL MODE)
	SIRVE PARA IR VALIDANDO LAS CANTIDADES E IR CALCULANDO LOS TOTALES Y REFRESCANDO
	EL HTML
----------------------------------------------------------------*/

ApplicationComprasProveedor.prototype.sumaCantidad = function( idProd ,  cantidad , cajaId ){
	
	var indiceItem = 0;
	
	for( f = 0; f < ApplicationComprasProveedor.currentInstance.compraItems.length;  f++){
		
		if( ApplicationComprasProveedor.currentInstance.compraItems[ f ].id == idProd ){
				//item already in cart
				indiceItem = f;
	
				break;
			}
	}//fin for
	
	if(!isNaN(cantidad)  && cantidad > 0 )
	{
		
		ApplicationComprasProveedor.currentInstance.compraItems[ indiceItem ].cantidad = cantidad;
		
		ApplicationComprasProveedor.currentInstance.compraItems[ indiceItem ].subtot = ApplicationComprasProveedor.currentInstance.compraItems[ indiceItem ].cantidad * ApplicationComprasProveedor.currentInstance.compraItems[ indiceItem ].cost;
		
		Ext.get("subtotProducto_"+idProd).update("$ "+ApplicationComprasProveedor.currentInstance.compraItems[ indiceItem ].subtot.toFixed(2) );
		
	}
	if ( cantidad === null || cantidad =='' || cantidad < 0 || isNaN(cantidad) ){
		cajaId.value = "0";
		ApplicationComprasProveedor.currentInstance.compraItems[ indiceItem ].cantidad = 0;
		ApplicationComprasProveedor.currentInstance.compraItems[ indiceItem ].subtot = 0;
		Ext.get("subtotProducto_"+idProd).update("$ 0");
	}
	
	var subtotal_compra=0;
	
	for(i=0; i < ApplicationComprasProveedor.currentInstance.compraItems.length; i++){
		subtotal_compra += parseFloat( ApplicationComprasProveedor.currentInstance.compraItems[i].subtot );
	}
	var iva_compra = subtotal_compra * 0;
	
	Ext.get("subtotal_compraProducto").update("$ "+subtotal_compra.toFixed(2) );
	Ext.get("iva_compraProducto").update("$ "+ iva_compra.toFixed(2) );
	Ext.get("total_compraProducto").update("$ "+ (subtotal_compra + iva_compra).toFixed(2) );
	

};


/* ------------------------------------------------------------------------------------
		vender, VALIDA LA COMPRA (SEGUN SEA EL METODO DE COMPRA), Y SI TODO ESTA BIEN, 
		HACE EL LLAMADO	AL METODO QUE SE COMUNICA CON EL SERVER
   ------------------------------------------------------------------------------------ */


ApplicationComprasProveedor.prototype.doComprar = function ()
{
	
	if(DEBUG){
		console.log("ApplicationCompras: doComprar called....");
	}
	
	
	items = ApplicationComprasProveedor.currentInstance.compraItems;

	if(!ApplicationComprasProveedor.currentInstance._BANDERA){//validacion en toño mode

		var subtotal = 0;
		var total = 0;
		var ban=false;
		var ban2=false;
		
		for( a = 0; a < items.length;  a++){
			subtotal += parseFloat( items[a].subtot );
			if(items[a].nA == 0 )
			ban=true;
			if(items[a].prKg == 0)
			ban2=true;
		}
		
		total = subtotal;
		
		if(items.length == 0){
				POS.aviso("Comprar Productos", "1) Agregue primero al menos un producto para poder comprar.");
				return;
		}
			if(Ext.getCmp("pesoEmbarque").getValue() == "0"){
				POS.aviso("Comprar Productos", "2) Colocar un numero mayor a 0 en el campo 'Peso en Kg del Embarque'");
				return;
			}
			if(ApplicationComprasProveedor.currentInstance.totalArpillas == 0 || ban){
				POS.aviso("Comprar Productos", "3) Llenar las columnas No. Arpillas de todos los productos agregados con un numero mayor a 0");
				return;
			}
			if(ApplicationComprasProveedor.currentInstance.totalArpillas > Ext.getCmp("pesoEmbarque").getValue()){
				POS.aviso("Comprar Productos", "4) El valor de 'Peso en Kg del Embarque' debe ser mayor al de 'Numero de Arpillas'");
				return;
			}
			
			if(total < 1 || ban2){
				POS.aviso("Comprar Productos", "5) Revisar en las columnas 'Kg Menos' de cada producto que tenga un valor y que ese valor no sea mayor o igual al 'Peso por Arpilla' <br>6) Revisar en las columnas 'Precio x Kg' de cada producto que el valor sea mayor '0'");
				return;
			}
			if(ApplicationComprasProveedor.currentInstance.pesoArpilla > Ext.getCmp("pesoEmbarque").getValue()){
				POS.aviso("Comprar Productos", "* El valor de 'Peso en Kg del Embarque' debe ser mayor al de 'Peso por Arpilla'");
				return;
			}
		
		newPanel = ApplicationComprasProveedor.currentInstance.doComprarPanel();
		sink.Main.ui.setCard( newPanel, 'slide' );
		
	}else{//VALIDACION GENERAL MODE
		var subtotal = 0;
		var total = 0;
		var ban=false;

		for( a = 0; a < items.length;  a++){
			subtotal += items[a].subtot;
			if(items[a].cantidad == 0 )
			ban=true;
			
		}
		
		total = subtotal;
		
		if(items.length == 0){
				POS.aviso("Comprar Productos", "1) Agregue primero al menos un producto para poder comprar.");
				return;
		}
		if(total < 1 || ban){
			POS.aviso("Comprar Productos", "2) Revisar en las columnas 'Cantidad' de cada producto que el valor sea mayor '0'");
			return;
		}
		
		newPanel = ApplicationComprasProveedor.currentInstance.doComprarPanel();
		sink.Main.ui.setCard( newPanel, 'slide' );		
	}
	
};


/*------------------------------------------------------
	MUESTRA EL PANEL CON EL SUBTOTAL IVA Y TOTAL DE LA VENTA
	Y SE DEFINE SI ES DE CONTADO O A CREDITO
--------------------------------------------------------*/

ApplicationComprasProveedor.prototype.doComprarPanel = function ()
{
	
	
	var subtotal = 0;
	var total = 0;
	var iva=0;
	for( a = 0; a < ApplicationComprasProveedor.currentInstance.compraItems.length;  a++){
		subtotal += parseFloat( ApplicationComprasProveedor.currentInstance.compraItems[a].subtot );
	}
	
	total = subtotal;
	iva = 0 * total; //0 porque el no incluye iva
	return new Ext.form.FormPanel({
	//tipo de scroll
    scroll: 'none',

	baseCls: "ApplicationCompras-mainPanel",

	//toolbar
	dockedItems: [new Ext.Toolbar({
        ui: 'dark',
        dock: 'bottom',
        items: [{
				xtype:'button', 
				text:'Regresar',
				ui: 'back',
				handler: function(){
					sink.Main.ui.setCard( ApplicationComprasProveedor.currentInstance.surtir, { type: 'slide', direction: 'right' } );
				}
			},{
				xtype:'button', 
				text:'Cancelar Compra',
				handler: function(){
					//reestablecer todo para una nueva venta
					
					ApplicationComprasProveedor.currentInstance.toggleBtns.length = 0;
					ApplicationComprasProveedor.currentInstance.toggleBtns2.length = 0;
					ApplicationComprasProveedor.currentInstance.compraItems.length = 0;
					ApplicationComprasProveedor.currentInstance.inventarioItems.length = 0;
					ApplicationComprasProveedor.currentInstance._BANDERATOGGLES = false;
					ApplicationComprasProveedor.currentInstance._BANDERATOGGLES2 = false;
					
					//se regresa al panel donde se realiza la compra
					sink.Main.ui.setCard( ApplicationComprasProveedor.currentInstance.surtir, { type: 'slide', direction: 'right' } );
					
					//segun el modo de compra se actualiza todo desde el inicio para una nueva compra
					if(!ApplicationComprasProveedor.currentInstance._BANDERA){
						
						ApplicationComprasProveedor.currentInstance.pesoArpilla = 0;
						ApplicationComprasProveedor.currentInstance.totalArpillas = 0;
						
						ApplicationComprasProveedor.currentInstance.llenarPanelComprasXarpillas( ApplicationComprasProveedor.currentInstance.providerId );
						
						Ext.getCmp("pesoEmbarque").setValue(0);						
						Ext.get("totalArps").update("0");
						Ext.get("pesoArpillas").update("0");
						
					}else{
						
						ApplicationComprasProveedor.currentInstance.llenarPanelComprasXpiezas( ApplicationComprasProveedor.currentInstance.providerId );
					}
										
					
				}//handler
				
			},{
				xtype:'spacer'
			},{
				xtype:'button', 
				ui: 'action',
				text:'Registrar Compra',
				handler: ApplicationComprasProveedor.currentInstance.doCompraLogic

			}]
    })],
	
	//items del formpanel
    items: [{

        xtype: 'fieldset',				
        title: 'Compra a proveedor: <b>'+ApplicationComprasProveedor.currentInstance.nombreProv+"</b>",
		baseCls: "ApplicationCompras-ventaListaPanel",
        items: [{
	        	xtype: 'textfield',
	        	label: 'SubTotal',
				value : subtotal
       		},{
		        xtype: 'textfield',
		        label: 'IVA',
				value : iva
	      	},{
			    xtype: 'textfield',
			    label: 'Total',
				value : total
		    },
			{
				//Ext.getCmp("tipoCompra").setValue(0); Ext.getCmp("tipoCompra").getValue(true); --> 1 o 0
				xtype: 'toggle',
                id : 'tipoCompra',
                label: 'Compra a Credito',
				value: 1
				
            }
			]
		}]
	});
};



/*-----------------------------------------------------------------
	SE ENVIA PETICION AL SERVER PARA REGISTRAR LA COMPRA EN LA BD
-------------------------------------------------------------------*/

ApplicationComprasProveedor.prototype.doCompraLogic = function ()
{
	//insercion en la BD				 
	var jsonItems = Ext.util.JSON.encode(ApplicationComprasProveedor.currentInstance.compraItems);
	console.log(jsonItems);
	
	var tipoCom = Ext.getCmp("tipoCompra").getValue(true) + 1;
	var tipoCompra ="";
	
	if( tipoCom == 1 ){
		tipoCompra = "contado";
	}else{
		tipoCompra = "credito";
	}
	
	
	POS.AJAXandDECODE({
			action: '1201',//insertar compra
			jsonItems : jsonItems,
			id_proveedor : ApplicationComprasProveedor.currentInstance.providerId,//aqui el id del proveedor
			tipo_compra : tipoCompra,
			modo_compra	: ApplicationComprasProveedor.currentInstance._BANDERA //false toño mode, true general mode
		}, 
		function (datos){
			
			//ya llego el request con los datos si existe o no	
			if(!datos.success){
				POS.aviso("Mostrador", ""+datos.reason);
				return;
			}
			
			
		},
		function (){
			POS.aviso("Error", "Error en la conexion, porfavor intente de nuevo.");
		}
	);
	///fin ajaxazo
	var thksPanel = ApplicationComprasProveedor.currentInstance.compras_doGraciasPanel();
	sink.Main.ui.setCard( thksPanel, 'fade' );
};


/*-----------------------------------------------------------
	SE REALIZÓ LA COMPRA Y MUESTRA PANEL FINAL
-------------------------------------------------------------*/
ApplicationComprasProveedor.prototype.compras_doGraciasPanel = function ()
{
	//reestablecer todo para una nueva venta
	ApplicationComprasProveedor.currentInstance.toggleBtns.length = 0;
	ApplicationComprasProveedor.currentInstance.toggleBtns2.length = 0;
	ApplicationComprasProveedor.currentInstance.compraItems.length = 0;
	ApplicationComprasProveedor.currentInstance.inventarioItems.length = 0;
	ApplicationComprasProveedor.currentInstance._BANDERATOGGLES = false;
	ApplicationComprasProveedor.currentInstance._BANDERATOGGLES2 = false;
	
	return new Ext.form.FormPanel({
	//tipo de scroll
    scroll: 'none',

	baseCls: "ApplicationVender-mainPanel",

	//toolbar
	dockedItems: [new Ext.Toolbar({
        ui: 'dark',
        dock: 'bottom',
        items: [{
				xtype:'button', 
				text:'Regresar',
				ui: 'back',
				handler: function(){
					
					//reestablecer todo para una nueva venta				
					Ext.get("proveedorProductos_Sucursal").update("");
					Ext.get("totalesCompra").update("");
					
					sink.Main.ui.setCard( ApplicationProveedores.currentInstance.mainCard, { type: 'slide', direction: 'right' } );
					
				}//handler
			},{
				xtype:'spacer'
			},{
				xtype:'button', 
				ui: 'action',
				text:'Comprar a mismo Proveedor',
				handler: function(){
					
				
									
					//se regresa al panel donde se realiza la compra
					sink.Main.ui.setCard( ApplicationComprasProveedor.currentInstance.surtir, { type: 'slide', direction: 'right' } );
					
					//segun el modo de compra se actualiza todo desde el inicio para una nueva compra
					if(!ApplicationComprasProveedor.currentInstance._BANDERA){
						
						Ext.getCmp("pesoEmbarque").setValue(0);						
						Ext.get("totalArps").update("0");
						Ext.get("pesoArpillas").update("0");
						
						ApplicationComprasProveedor.currentInstance.pesoArpilla = 0;
						ApplicationComprasProveedor.currentInstance.totalArpillas = 0;
						
						ApplicationComprasProveedor.currentInstance.llenarPanelComprasXarpillas( ApplicationComprasProveedor.currentInstance.providerId );
												
					}else{
						
						ApplicationComprasProveedor.currentInstance.llenarPanelComprasXpiezas( ApplicationComprasProveedor.currentInstance.providerId );
					}
					
				}//handler
			}]
    })],
	
	//items del formpanel
    items: [{
			html : 'COMPRA REGISTRADA !',
			cls  : 'gracias',
			baseCls: "ApplicationCompras-ventaListaPanel",
		}]
	});
};
