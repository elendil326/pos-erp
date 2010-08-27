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
ApplicationComprasProveedor.prototype._BANDERA = false;
ApplicationComprasProveedor.prototype._BANDERATOGGLES = false;
ApplicationComprasProveedor.prototype.toggleBtns = [];
ApplicationComprasProveedor.prototype.compraItems = [];


ApplicationComprasProveedor.prototype._init = function( idProveedor ){
	
	this.comprarPanel( idProveedor );
	this.providerId = idProveedor.id_proveedor;
};


/*
	Metodo que devuelve un panel principal de tipo Carousel para desplegar
*/

ApplicationComprasProveedor.prototype.comprarPanel = function( idProveedor ){

	//por si se esta realizando una compra y se pica en otra aplicacion se reestablece el arreglo de los items
	//para no generar errores y no se queden items almacenados para futuras compras
	this.compraItems.length = 0;
	
	var buscar = [{
		xtype: 'button',
		text: 'Regresar',
		ui: 'back',
		id:'regresarComprasProveedor',
		handler:	function( ){
						ApplicationComprasProveedor.currentInstance.toggleBtns.length = 0;
						ApplicationComprasProveedor.currentInstance.compraItems.length = 0;
						ApplicationComprasProveedor.currentInstance.pesoArpilla = 0;
						sink.Main.ui.setCard( ApplicationProveedores.currentInstance.mainCard, { type: 'slide', direction: 'right' } );
						
					}
				
		}];

	var agregar = [{
		xtype: 'button',
		text: 'Comprar',
		ui: 'action',
		handler: function(){
				
				
			}
		}];		

        var dockedItems = [ new Ext.Toolbar({
            ui: 'dark',
            dock: 'bottom',
            items: buscar.concat({xtype:'spacer'}).concat(agregar)
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
					//cls: 'ApplicationComprasProveedor-pesoEmbarque'
				}
				,
				
				{

				xtype: 'panel',
				title: 'panelLeft',
				id: 'proveedorProductos_SucursalContainer',
				cls: 'ApplicationComprasProveedor-proveedorProductos_Sucursal',
				items:[{ html: '<div id="proveedorProductos_Sucursal" class="ApplicationComprasProveedor-proveedorProductos_Sucursal2"></div>' }]
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
				//console.log("EL ID DEL PROVEEDOR SELEC ES: "+ApplicationCompras.currentInstance.idProveedor);
				if(!ApplicationComprasProveedor.currentInstance._BANDERA){
					console.log("SI ES FALSO DEBO DE ENTRAR A SURTIR POR KGS (TOÑO MODE)");
					Ext.getCmp("pesoEmbarque").show();
					
				}
			}
		}
	});
	
	
	/*
		Se llama a funcion q ejecuta ajax para llenar con html las divs del panel compras
	*/
	this.llenarPanelCompras( idProveedor ); 
	
	
	
	
	/*
	
	*/
	var carousel = new Ext.Carousel({
		defaults:{cls: 'ApplicationClientes-mainPanel'},
		items: [{
			scroll: 'vertical',
			xtype: 'panel',
			title: 'customerDetails',
			id: 'compraProveedorPanel',
			items: [panelCompras],
			
		}, {
			//scroll: 'vertical',
			xtype: 'panel',
			title: 'ventas',
			id: 'nuevoProductoProveedor',
			items: [ {id:'datosCliente'},{id: 'customerHistorialSlide' }],
			
		}],
	});
	
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
	
	if( !ApplicationComprasProveedor.currentInstance._BANDERATOGGLES ){
		return;
	}
	
	id = idToggle.substring(6);
	
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
	}
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
	Lanza una llamada al servidor y trae los productos que surte el proveedor
	a la sucursal para generar el html y asi los renderea al panel principal 
	en su capa de la izquierda
*/
ApplicationComprasProveedor.prototype.llenarPanelCompras = function( idProveedor ){
	
	Ext.regModel('productosSucursal', {

	});

	var productosSucursal = new Ext.data.Store({
		model: 'productosSucursal'
	});	
	
	POS.AJAXandDECODE({
			action: '1211',
			id_proveedor: idProveedor.id_proveedor
			},
			function (datos){//mientras responda
				if(!datos.success){
					POS.aviso("ERROR",""+datos.reason);
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
					Ext.get("proveedorProductos_Sucursal").update("<div class='ApplicationComprasProveedor-itemsBox'>" + html +"</div>");
					
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
				cost 		: datos.datos[0].precio,//el precio de compra
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
