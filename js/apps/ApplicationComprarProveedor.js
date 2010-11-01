

ApplicationComprarProveedor = function(config){

	if(DEBUG){
		console.log("'new' ApplicationComprarProveedor : construyendo");
	}
			
	this.defaultMode = null; // 1 = indica qeu el modo de venta es el default, 0 = modo de venta personalizado para cada negocio.
			
	this.providerName = null; //nombre del proveedor
	this.providerId = null; //id del proveedor
	
	this.cardBuyProduct = null;//panel que muestra los productos que puede comprar al proveedor
	this.cardAddProduct = null;//panel que muestra los productos que puede agregar al mostrador
	
	this._init(config);//inicia la construccion del objeto 
	
	return this;
};


/**
* Inicializa el objeto y lo construye
*/
ApplicationComprarProveedor.prototype._init = function(config){

    this.defaultMode = config.mode;
    this.providerName = config.name;
	this.providerId = config.id;
	
	this.cardBuyProduct = this.buildCardBuyProduct();
	
	this.cardAddProduct = this.buildCardAddProduct();
	
    
}//_init



/**
* Regresa un panel que muestra los productos que alguna vez se han comprado al proveedor, ademas
* puede seleccionarlos para realizar una compra en ese momento
*/
ApplicationComprarProveedor.prototype.buildCardBuyProduct = function(){

    var card = null;

    return card;

}//buildCardBuyProduct



/**
* Regresa un panel que muestra los productos que no estan disponibles a la venta en esa sucursal
* dando la posibilidad de agregarlos al catalogo
*/
ApplicationComprarProveedor.prototype.buildCardAddProduct = function(){

    var card = null;

    return card;

}//buildCardBuyProduct


/*
	Metodo que devuelve un panel principal de tipo Carousel para desplegar
*/

ApplicationComprarProveedor.prototype.comprarPanel = function( idProveedor ){

	//por si se esta realizando una compra y se pica en otra aplicacion se reestablece el arreglo de los items
	//para no generar errores y no se queden items almacenados para futuras compras
	this.compraItems.length = 0;
	this.inventarioItems.length = 0;
	this.toggleBtns.length = 0;
	this.toggleBtns2.length = 0;
	this.pesoArpilla = 0;
	this.totalArpillas = 0;

	if( !this.btn_regresar_comprarPanel )
	{
        
		this.btn_regresar_comprarPanel = [{
			xtype: 'button',
			text: 'Regresar',
			ui: 'back',
			id:'regresarComprasProveedor',
			handler : function( ){

			    ApplicationComprarProveedor.currentInstance.toggleBtns.length = 0;
				ApplicationComprarProveedor.currentInstance.toggleBtns2.length = 0;
				ApplicationComprarProveedor.currentInstance.compraItems.length = 0;
				ApplicationComprarProveedor.currentInstance.inventarioItems.length = 0;
				ApplicationComprarProveedor.currentInstance.pesoArpilla = 0;
				ApplicationComprarProveedor.currentInstance.totalArpillas = 0;
							
				/*Ext.get("productosProvSucursal").update("");*/
				Ext.get("totalesCompra").update("");
				Ext.get("Productos-Proveedor").update("");
							
				sink.Main.ui.setCard(ApplicationProveedores.currentInstance.mainCard, {type : 'slide', direction : 'right' });
							
			}					
        }];
        
	}//if !this.btn_regresar_comprarPanel
	
	
	if(!this.btn_comprar_comprarPanel)
	{
		
		this.btn_comprar_comprarPanel = [{
			xtype: 'button',
			text: 'Comprar',
			ui: 'action',
			id: '_btnComprarProducto',
			disabled: true,
			handler: function(){					
			    ApplicationComprarProveedor.currentInstance.doComprar();
			}
		}];
			
	}//if !this.btn_comprar_comprarPanel
	
	if( !this.btn_agregarProducto_comprarPanel )
	{
	
		this.btn_agregarProducto_comprarPanel = [{
			xtype: 'button',
			text: 'Agregar Producto',
			ui: 'action',
			id: '_btnAgregarProducto',
			disabled: true,
			handler: function(){								
					ApplicationComprarProveedor.currentInstance.do_agregarInventario();
			}			
		}];
		
	}//if !this.btn_agregarProducto_comprarPanel
	
	if(!this.dockedItems)
	{
    
        this.dockedItems = [ new Ext.Toolbar({
            ui: 'dark',
            dock: 'bottom',
            items: this.btn_regresar_comprarPanel.concat({xtype:'spacer'}).concat(this.btn_comprar_comprarPanel).concat(this.btn_agregarProducto_comprarPanel)
        })];
	
	}//if !this.dockedItems
	
	
	if(!this.panelCompras)
	{	

		this.panelCompras = new Ext.Panel({
			id: 'surtir_proveedor',			
			items: [
				{
				    id: 'embarqueDatos',
				    style: {
                        height: '40px'
                    },
					html: '',
					items:[
						{
						    xtype: 'textfield',
						    label: 'No. Kgs:',
						    id: 'pesoEmbarque',
						    fieldClass: 'ApplicationComprarProveedor-pesoEmbarque',
						    styleHtmlContent: 'ApplicationComprarProveedor-pesoEmbarque',
						    styleHtmlCls: 'ApplicationComprarProveedor-pesoEmbarque',
						    cls: 'ApplicationComprarProveedor-pesoEmbarque',
						    maxHeight:35,
						    height:30,
						    /*style: {
                                width: '48%',
                                height: '35px',
                                color: 'FF0000',
                                padding: '5px'
                            },*/
						    value: 0,
						    hidden : true,
						    listeners: {
							    change: function(){									
							        ApplicationComprarProveedor.currentInstance.pesoEmbarque();
								}//function
						    }//listener
						}//item textfield
					]
				},
				{
				    xtype: 'panel',
				    id:'arpillasDatos',
					html: "<div class='ApplicationComprarProveedor-pesoEmbarque' >" 
								+ "<div class='ApplicationComprarProveedor-numeroArpillas'>Numero Arpillas: </div>" 
								+ "<div id='totalArps' class='ApplicationComprarProveedor-totalArpillas'>0</div>" 
								
								+ "<div class='ApplicationComprarProveedor-PesoArpilla'>Peso por Arpilla: </div>" 
								
								+ "<div id='pesoArpillas' class= 'ApplicationComprarProveedor-pesoArpillas'>0</div>" 
								
						+ "</div>",
					hidden : true						
				},					
				{	
					xtype: 'panel',
					title: 'panelLeft',
					id: 'proveedorProductos_SucursalContainer',
					cls: 'ApplicationComprarProveedor-proveedorProductos_Sucursal',
					items:[
					    { 
					        html: '<div id="proveedorProductos_Sucursal" class="ApplicationComprarProveedor-proveedorProductos_Sucursal2"><div class="ApplicationComprarProveedor-itemsBox" id="productosProvSucursal"></div>' 
					    }
					]
				},
				{
					scroll: 'vertical',
					xtype: 'panel',
					title: 'panelRi',
					cls:'ApplicationComprarProveedor-proveedorProductos_Sucursal',
					items:[
					    {
					        id: 'totales_Compras',  
					        html: "<div id = 'totalesCompra' class='ApplicationComprarProveedor-proveedorProductos_Sucursal2' ></div>"
					    }
					]
				}
			],
			listeners: {
			    afterrender: function(){
	
					if(!ApplicationComprarProveedor.currentInstance._BANDERA)
					{
						console.log("MODO DE COMPRA: 'TOÑO MODE'");
						Ext.getCmp("pesoEmbarque").show();
						Ext.getCmp("arpillasDatos").show();
						
					}
					else
					{
					
						console.log("MODO DE COMPRA: 'GENERAL MODE' (X PIEZAS)");
						
						
						var html = "";
						
						html += "<div class='ApplicationComprarProveedor-item-GeneralMode' >"; 
						html +=     "<div class='cabecera-producto_nombre'>PRODUCTO</div>"; 							
						html +=     "<div class='cabecera-compra'>PRECIO</div>" 							
						html +=     "<div class='cabecera-compra'>CANTIDAD</div>" 							
						html +=     "<div class='cabecera-compra'>SUBTOTAL</div>" 
						html += "</div>"
						html += "<div class='ApplicationComprarProveedor-item-GeneralMode' >" 
						html +=     "<div class='cabecera-subtotales-compra'>SUBTOTAL:</div>"						
						html +=     "<div class='cabecera-subtotales-compra' id ='subtotal_compraProducto'>$ 0</div>"							
						html +=     "<div class='cabecera-subtotales-compra' >IVA:</div>" 
						html +=     "<div class='cabecera-subtotales-compra' id= 'iva_compraProducto'>$ 0</div>" 							
						html +=     "<div class='cabecera-subtotales-compra'>TOTAL:</div>" 
						html +=     "<div class='cabecera-subtotales-compra' id='total_compraProducto'>$ 0</div>" 							
						html += "</div>"
						
						Ext.getCmp("embarqueDatos").update(html);
					}
				}//afterrender
			}//listeners
		});
	}//if !this.panelCompras
	
	/*
		Se llama a funcion q ejecuta ajax para llenar con html las divs del panel compras
	*/
	if(!this._BANDERA)
	{
	    this.llenarPanelComprasXarpillas( idProveedor ); 
	}
	else
	{
		this.llenarPanelComprasXpiezas( idProveedor ); 
	}		
	
	/*
	
	*/
	if( !this.carousel ){

		this.carousel = new Ext.Carousel({
		    id: 'CarruselSurtirProductosSucursal',
			items: [
			    {
				    scroll: 'vertical',
				    xtype: 'panel',
				    title: 'panel_compra_Proveedor',
				    id: 'compraProveedorPanel',
				    items: [this.panelCompras]
			    }, 
			    {
				    xtype: 'panel',
				    scroll: 'vertical',
				    title: 'producto_proveedor',
				    id: 'nuevoProductoProveedor',
				    items: [ {	id:'listaProductosAgregar',	html: '<div id="Productos-Proveedor"></div>' }	]
			    }			
			],
			listeners:{
			    cardswitch : function( a ){						
				    ApplicationComprarProveedor.currentInstance.hideButtons();						
				}//fin cardswitch					
			}//fin listeners
		});		
		
	}//fin if !this.carousel
	
	/*
		Se llama a la funcion que renderea el html del panel con los productos que ofrece el proveedor
	*/
	this.llenarPanelProductosProveedor( idProveedor );
				
		
	/*
	*/
	this.surtir = new Ext.Panel({
		dockedItems: this.dockedItems,
		layout: {
			type: 'vbox',
			align: 'stretch'
		},
		defaults: {
	      flex: 1
		},
		items: [this.carousel]
	});;
	
};//comprarPanel


/*
	se verifica si exite el mensaje	Proveedor no surte a esta sucursal y se 
	bloquea el boton Comprar
*/
ApplicationComprarProveedor.prototype.botonComprar = function(){

		Ext.getCmp("_btnComprarProducto").setDisabled(true);
		
		if ( Ext.fly("productosProvSucursal").first().dom.childElementCount < 2 ) 
		{
			Ext.getCmp("_btnComprarProducto").setDisabled(true);
		}
		else
		{		
			Ext.getCmp("_btnComprarProducto").setDisabled(false);
		}

};


/*
Dependiendo del metodo de compra establecido por ._BANDERA se esconden los botones con respecto
al analisis de divs
*/

ApplicationComprarProveedor.prototype.hideButtons = function (){
	

	//if( ApplicationComprarProveedor.currentInstance._BANDERA == false ){ 

		switch(Ext.getCmp("CarruselSurtirProductosSucursal").getActiveIndex())
		{
					
		    case 0	:
									
			Ext.getCmp("_btnAgregarProducto").setDisabled(true);
			if ( Ext.fly("productosProvSucursal").first().dom.childElementCount < 2 ) {
				Ext.getCmp("_btnComprarProducto").setDisabled(true);
			}else{
				Ext.getCmp("_btnComprarProducto").setDisabled(false);
			}
								
		break;	
							
		case 1:
		
			if ( Ext.fly("Productos-Proveedor").first().dom.childElementCount > 1 ) {
								
				Ext.getCmp("_btnAgregarProducto").setDisabled(false);
			}else{
									
				Ext.getCmp("_btnAgregarProducto").setDisabled(true);	
			}
								
								
			Ext.getCmp("_btnComprarProducto").setDisabled(true);
								
								
		break;	
							
		}//fin switch
	//}else{
		
		
	//}
};

/*
	Se dispara cada vez que un Toggle de un producto es cambiado de valor
	para seleccionar y agregarlo a la compra o eliminarlo de la compra
*/
ApplicationComprarProveedor.prototype.elegirProducto = function ( idToggle ){
	//console.log("******* 4) Esto es el handler del toggle renderado");
	if( !ApplicationComprarProveedor.currentInstance._BANDERATOGGLES ){
		return;
	}

	id = idToggle.substring(6);
	
	if( !ApplicationComprarProveedor.currentInstance._BANDERA ){//toño mode
		
		
		if ( Ext.getCmp("CProd_"+id).getValue() == 1){
			document.getElementById("NoArpProducto_"+id).disabled = false;
			document.getElementById("KgsMenosProducto_"+id).disabled = false;
			document.getElementById("precioKgProducto_"+id).disabled = false;
			
			document.getElementById("NoArpProducto_"+id).value = 0;
			document.getElementById("KgsMenosProducto_"+id).value = 0;
			document.getElementById("precioKgProducto_"+id).value = 0;
			
			//console.log("Apenas voy a agregar 1 producto al arreglo");
			
			ApplicationComprarProveedor.currentInstance.agregarProductoCompra( id );
			
		}else{
			document.getElementById("NoArpProducto_"+id).disabled = true;
			document.getElementById("KgsMenosProducto_"+id).disabled = true;
			document.getElementById("precioKgProducto_"+id).disabled = true;
			
			document.getElementById("NoArpProducto_"+id).value = '';
			document.getElementById("KgsMenosProducto_"+id).value = '';
			document.getElementById("precioKgProducto_"+id).value = '';
			
			if( Ext.get("subtotalesProd_"+id) ){
				Ext.get("subtotalesProd_"+id).remove();
				
				ApplicationComprarProveedor.currentInstance.compras_doDeleteProduct( id );
			}
		}//fin else
		
		
	}else{
		//console.log("................. Seleccione este item: CProd_::"+id);		
		if ( Ext.getCmp("CProd_"+id).getValue() == 1){
			document.getElementById("cantidadProducto_"+id).disabled = false;
			
			document.getElementById("cantidadProducto_"+id).value = 0;
			
			//Se manda llamar directamente al metodo que hace ajax por que en general mode
			//no se agrega div a la derecha
			
			ApplicationComprarProveedor.currentInstance.compras_doAddProduct( id );
						
		}else{
			document.getElementById("cantidadProducto_"+id).disabled = true;
			document.getElementById("cantidadProducto_"+id).value = '';
			Ext.get("subtotProducto_"+id).update("$ 0");
			
			ApplicationComprarProveedor.currentInstance.compras_doDeleteProduct( id );
			
		}//fin else
		
	}//fin else ._BANDERA
};


/*
	Se dispara cada vez que un Toggle de un producto que ofrece el proveedor 
	pero aun no surte es cambiado de valor
	para seleccionar y agregarlo al inventario de la sucursal
*/
ApplicationComprarProveedor.prototype.elegirProductoInventario = function ( idToggle ){
	
	if( !ApplicationComprarProveedor.currentInstance._BANDERATOGGLES2 ){
		return;
	}
	
	id = idToggle.substring(8);
	
	
	if ( Ext.getCmp("agregar_"+id).getValue() == 1){
		document.getElementById("precioVenta_"+id).disabled = false;
		document.getElementById("stockMin_"+id).disabled = false;
		
		document.getElementById("stockMin_"+id).value = 0;
		document.getElementById("precioVenta_"+id).value = 0;
		
		denominacion = Ext.get("nuevoProd_"+id).getHTML();
		
		precioCompra = Ext.get("precioCompraProduc_"+id).getHTML();
		
		ApplicationComprarProveedor.currentInstance.inventario_doAddProduct( id , denominacion , precioCompra );
						
	}else{
		document.getElementById("precioVenta_"+id).disabled = true;
		document.getElementById("stockMin_"+id).disabled = true;
		
		document.getElementById("stockMin_"+id).value = '';
		document.getElementById("precioVenta_"+id).value = '';
		
		ApplicationComprarProveedor.currentInstance.inventario_doDeleteProduct( id );
			
	}//fin else
		
	
};

/*
	Agrega a la div de la derecha el producto seleccinado y lo agrega ala
	lista de items (arreglo), solo se llama en Toño mode 
*/
ApplicationComprarProveedor.prototype.agregarProductoCompra = function( id_producto ){
	
	if(!Ext.get("subtotalesProd_"+id_producto)){
	
		var denominacion = document.getElementById("nombreProdComprar_"+id_producto).innerText;
		
		var producto = document.createElement("div");
			producto.id = "subtotalesProd_"+id_producto;
		var codigo ="";	
		
		
			codigo = "<div class='ApplicationComprarProveedor-item' >" 
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
		
		//console.log("Apenas llamare al metodo q invoca al AJAX ");
		ApplicationComprarProveedor.currentInstance.compras_doAddProduct( id_producto );
	}
};

/*
	Agrega a la div abajo de la lista de productos que surte el proveedor a esta sucursal
*/
ApplicationComprarProveedor.prototype.htmlItemInventario = function(  ){
	
	items = ApplicationComprarProveedor.currentInstance.inventarioItems;
	ApplicationComprarProveedor.currentInstance._BANDERATOGGLES = false;	
	//console.log("-----> 3) htmlItemInventario: se van a renderear: "+items.length+" items");
		
		
	for( a = 0; a < items.length; a++ ){
		
		if(!Ext.get("nombreProdComprar_"+items[a].id)){
			//akkki kambiar render por piezas --------------------------------------------------------
			var producto = document.createElement("div");
			producto.className = 'ApplicationComprarProveedor-item';//
			var codigo = "";		
			
			if( ApplicationComprarProveedor.currentInstance._BANDERA == false  ){
				codigo += ""
					+ "<div class='producto_nombre' id='nombreProdComprar_"+items[a].id+"'>" + items[a].denominacion +"</div>" 
							
					+ "<div class='caja_texto'>" 
					+ "<INPUT TYPE='text' id='NoArpProducto_"+items[a].id+"' SIZE='5'  onchange='ApplicationComprarProveedor.currentInstance.sumarArp("+items[a].id+",this.value, NoArpProducto_"+items[a].id+")' class='description' disabled='true'>" 
					+"</div>"
							
					+ "<div class='caja_texto'>" 
					+ "<INPUT TYPE='text' id='KgsMenosProducto_"+items[a].id+"' SIZE='5'  onchange='ApplicationComprarProveedor.currentInstance.revisarCantidadKg("+items[a].id+",this.value, KgsMenosProducto_"+items[a].id+")' class='description' disabled='true'>" 
					+"</div>"
							
							
					+ "<div class='caja_texto'>" 
					+ "<INPUT TYPE='text' id='precioKgProducto_"+items[a].id+"' SIZE='5'  onchange='ApplicationComprarProveedor.currentInstance.revisarCantidaPrecio("+items[a].id+",this.value, precioKgProducto_"+items[a].id+")' class='description' disabled='true'>" 
					+"</div>"
							
							
					+ "<div class = 'ApplicationComprarProveedor-toggle' id = 'Buy_prod_"+items[a].id+"'></div>";
					
			}else{
				
				codigo += "<div class='producto_nombre' id='nombreProdComprar_"+items[a].id+"'>" + items[a].denominacion +"</div>" 
						
						+ "<div class='cabecera-subtotales-compra'>$ "+ 
							items[a].precioCompra
							
						+"</div>"
						
						+ "<div class='caja_texto'>" 
						+ "<INPUT TYPE='text' id='cantidadProducto_"+items[a].id+"' SIZE='5'  onchange='ApplicationComprarProveedor.currentInstance.sumaCantidad("+items[a].id+",this.value, cantidadProducto_"+items[a].id+")' disabled='true'>" 
						+"</div>"
						
						
						+ "<div class='cabecera-subtotales-compra' id= 'subtotProducto_"+items[a].id+"' >$ 0" 
						
						+"</div>"
						
						
						+ "<div class = 'ApplicationComprarProveedor-toggle' id = 'Buy_prod_"+items[a].id+"'></div>"
						
						+ "</div>";
			}
				

			/*
			Antes de agregar el elemento a la capa del otro carrusel se verifica si exite el mensaje
			Proveedor no surte a esta sucursal y se elimina esa capa y se agrega despues el nuevo elemento
			*/
			if ( Ext.fly("no_ProdComprarProv") ) {
				
				Ext.fly("no_ProdComprarProv").remove();
			}
					
				
			/*
			Se agrega el nuevo elemento a los productos por comprar
			*/
			producto.innerHTML = codigo;		
			document.getElementById("productosProvSucursal").appendChild( producto );
				
			/*
			Se elimina de los productos que se agregan al inventario el elemento del panel q se introdujo
			al inventario de la sucursal
			*/
			Ext.get("nuevoItemInventario_"+items[a].id).remove();
				
				
		}
	}//fin for 
		
		
		//console.log(ApplicationComprarProveedor.currentInstance.toggleBtns);
	for( hh = 0; hh < items.length; hh++ ){
		if(Ext.get("nombreProdComprar_"+items[hh].id)){
			//console.log("ENTRE A RENDEREAR ESTA MIERDA");
			buyProduct = new Ext.form.Toggle({
				id: 'CProd_'+items[hh].id,
				renderTo: 'Buy_prod_'+items[hh].id,
					listeners: {
						change:	function() {
							//console.log("Se ha elegido otro producto");	
							//console.log("--- VOY A ELEGIR A: "+this.getId() );
							ApplicationComprarProveedor.currentInstance.elegirProducto( this.getId() );
						}
					}//listeners
			});

			ApplicationComprarProveedor.currentInstance.toggleBtns.push( buyProduct );
		}//fin if
	}//fin for hh
	//console.log("AHORA:");
	//console.log(ApplicationComprarProveedor.currentInstance.toggleBtns);
	
	ApplicationComprarProveedor.currentInstance._BANDERATOGGLES = true;	
	ApplicationComprarProveedor.currentInstance.inventarioItems.length = 0;
	

	//POS.aviso("Compras Proveedor","SE HAN AGREGADO NUEVOS PRODUCTOS AL INVENTARIO DE ESTA SUCURSAL");
};

/*
	Lanza una llamada al servidor y trae los productos que surte el proveedor
	a la sucursal para generar el html y asi los renderea al panel principal 
	en su capa de la izquierda (Vender en modalidad por arpillas Toño Mode)
*/
ApplicationComprarProveedor.prototype.llenarPanelComprasXarpillas = function( idProveedor ){
	
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
				if(!datos.success)
				{
									
				    var html = "";
				    
				    html += "<div class='ApplicationComprarProveedor-item'>";
					html += "   <div class='cabecera-producto_nombre'>PRODUCTO</div>";							
					html += "   <div class='cabecera-compra-ARP'>No. Arpillas</div>";						
					html += "   <div class='cabecera-compra-ARP'>Kgs Menos</div>";					
					html += "   <div class='cabecera-compra-ARP'>Precio x Kg</div>"; 							
					html += "</div>";	
					html += "<div class='ApplicationClientes-itemsBox' id='no_ProdComprarProv' >";
					html += "   <div class='no-data'>"; 
					html +=         datos.reason;
					html += "   </div>";
					html += "</div>";	
									
					Ext.get("productosProvSucursal").update(html);
					
					Ext.get("totalesCompra").update("<div class='ApplicationComprarProveedor-item' >" 
							+ "<div class='cabecera-producto_nombre'>PRODUCTO</div>" 
							
							+ "<div class='cabecera-subtotales-compra' >Peso Arp</div>"
							
							+ "<div class='cabecera-subtotales-compra'>Kgs a Pagar</div>" 
							
							+ "<div class='cabecera-subtotales-compra'>Precio x Kg</div>" 
							
							+ "<div class='cabecera-subtotales-compra'>Subtotal</div>" 
							
						+ "</div>");
					
					ApplicationComprarProveedor.currentInstance.botonComprar();
					return;
				}
				
				
				productosSucursal.loadData( datos.datos );
				
				
				var html = "";
					html += 
						"<div class='ApplicationComprarProveedor-item' >" 
							+ "<div class='cabecera-producto_nombre'>PRODUCTO</div>" 
							
							+ "<div class='cabecera-compra-ARP'>No. Arpillas</div>" 
							
							+ "<div class='cabecera-compra-ARP'>Kgs Menos</div>" 
							
							+ "<div class='cabecera-compra-ARP'>Precio x Kg</div>" 
							
						+ "</div>";
								
					for( a = 0; a < productosSucursal.getCount(); a++ ){
						//console.log(productosSucursal);	
						html += "<div class='ApplicationComprarProveedor-item' >" 
						+ "<div class='producto_nombre' id='nombreProdComprar_"+productosSucursal.data.items[a].data.id_producto+"'>" + productosSucursal.data.items[a].data.denominacion +"</div>" 
						
						+ "<div class='caja_texto'>" 
						+ "<INPUT TYPE='text' id='NoArpProducto_"+productosSucursal.data.items[a].data.id_producto+"' SIZE='5'  onchange='ApplicationComprarProveedor.currentInstance.sumarArp("+productosSucursal.data.items[a].data.id_producto+",this.value, NoArpProducto_"+productosSucursal.data.items[a].data.id_producto+")' class='description' disabled='true'>" 
						+"</div>"
						
						+ "<div class='caja_texto'>" 
						+ "<INPUT TYPE='text' id='KgsMenosProducto_"+productosSucursal.data.items[a].data.id_producto+"' SIZE='5'  onchange='ApplicationComprarProveedor.currentInstance.revisarCantidadKg("+productosSucursal.data.items[a].data.id_producto+",this.value, KgsMenosProducto_"+productosSucursal.data.items[a].data.id_producto+")' class='description' disabled='true'>" 
						+"</div>"
						
						
						+ "<div class='caja_texto'>" 
						+ "<INPUT TYPE='text' id='precioKgProducto_"+productosSucursal.data.items[a].data.id_producto+"' SIZE='5'  onchange='ApplicationComprarProveedor.currentInstance.revisarCantidaPrecio("+productosSucursal.data.items[a].data.id_producto+",this.value, precioKgProducto_"+productosSucursal.data.items[a].data.id_producto+")' class='description' disabled='true'>" 
						+"</div>"
						
						
						+ "<div class = 'ApplicationComprarProveedor-toggle' id = 'Buy_prod_"+productosSucursal.data.items[a].data.id_producto+"'></div>"
						
						+ "</div>";
						
						
					}//fin for 
				
					//imprimir el html
					
					Ext.get("productosProvSucursal").update("" + html +"</div>");

					Ext.get("totalesCompra").update("<div class='ApplicationComprarProveedor-item' >" 
							+ "<div class='cabecera-producto_nombre'>PRODUCTO</div>" 
							
							+ "<div class='cabecera-subtotales-compra' >Peso Arp</div>"
							
							+ "<div class='cabecera-subtotales-compra'>Kgs a Pagar</div>" 
							
							+ "<div class='cabecera-subtotales-compra'>Precio x Kg</div>" 
							
							+ "<div class='cabecera-subtotales-compra'>Subtotal</div>" 
							
						+ "</div>");
					
					for( hh = 0; hh < productosSucursal.getCount(); hh++ ){
						buyProduct = new Ext.form.Toggle({
							id: 'CProd_'+productosSucursal.data.items[hh].data.id_producto,
							renderTo: 'Buy_prod_'+productosSucursal.data.items[hh].data.id_producto,
							listeners: {
								change:	function() {
									
										ApplicationComprarProveedor.currentInstance.elegirProducto( this.getId() );
								}
							}//listeners
						});
						ApplicationComprarProveedor.currentInstance.toggleBtns.push( buyProduct );
					}
					ApplicationComprarProveedor.currentInstance._BANDERATOGGLES = true;	
					
					ApplicationComprarProveedor.currentInstance.botonComprar();

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
ApplicationComprarProveedor.prototype.llenarPanelComprasXpiezas = function( idProveedor ){
	
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
										
					Ext.get("productosProvSucursal").update("<div class='ApplicationClientes-itemsBox' id= 'no_ProdComprarProv'><div class='no-data'>"+datos.reason+"</div></div>");
					
					
					ApplicationComprarProveedor.currentInstance.botonComprar();
					return;
				}
				
				
				productosSucursal.loadData( datos.datos );
				
				
				var html = "";
					
					
					for( a = 0; a < productosSucursal.getCount(); a++ ){
						
						html += "<div class='ApplicationComprarProveedor-item' >" 
						+ "<div class='producto_nombre' id='nombreProdComprar_"+productosSucursal.data.items[a].data.id_producto+"'>" + productosSucursal.data.items[a].data.denominacion +"</div>" 
						
						+ "<div class='cabecera-subtotales-compra'>$ "+ 
							productosSucursal.data.items[a].data.precio
							
						+"</div>"
						
						+ "<div class='caja_texto'>" 
						+ "<INPUT TYPE='text' id='cantidadProducto_"+productosSucursal.data.items[a].data.id_producto+"' SIZE='5'  onchange='ApplicationComprarProveedor.currentInstance.sumaCantidad("+productosSucursal.data.items[a].data.id_producto+",this.value, cantidadProducto_"+productosSucursal.data.items[a].data.id_producto+")' disabled='true'>" 
						+"</div>"
						
						
						+ "<div class='cabecera-subtotales-compra' id= 'subtotProducto_"+productosSucursal.data.items[a].data.id_producto+"' >$ 0" 
						
						+"</div>"
						
						
						+ "<div class = 'ApplicationComprarProveedor-toggle' id = 'Buy_prod_"+productosSucursal.data.items[a].data.id_producto+"'></div>"
						
						+ "</div>";
						
						
					}//fin for 
				
					//imprimir el html
					Ext.get("productosProvSucursal").update("<div class='ApplicationComprarProveedor-itemsBox'>" + html +"</div>");
					
					
					
					
					for( hh = 0; hh < productosSucursal.getCount(); hh++ ){
						buyProduct = new Ext.form.Toggle({
							id: 'CProd_'+productosSucursal.data.items[hh].data.id_producto,
							renderTo: 'Buy_prod_'+productosSucursal.data.items[hh].data.id_producto,
							listeners: {
								change:	function() {
									
										ApplicationComprarProveedor.currentInstance.elegirProducto( this.getId() );
								}
							}//listeners
						});
						ApplicationComprarProveedor.currentInstance.toggleBtns.push( buyProduct );
					}
					ApplicationComprarProveedor.currentInstance._BANDERATOGGLES = true;	
					
					ApplicationComprarProveedor.currentInstance.botonComprar();
			},
			function (){//no responde
				POS.aviso("ERROR","NO SE PUDO MOSTRAR DATOS DEL CLIENTE,  ERROR EN LA CONEXION :(");	
			}
		);//AJAXandDECODE
};

/*
	Lanza una llamada al servidor y trae los productos que ofrece el proveedor
	y aun no se venden en la sucursal, genera el html y asi los renderea al panel del carrusel
	que esta a la derecha del panel principal
*/
ApplicationComprarProveedor.prototype.llenarPanelProductosProveedor = function( idProveedor ){
	
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
					
					Ext.get("Productos-Proveedor").update("<div class='ApplicationComprarProveedor-itemsBox'><div class=\"no-data\">"+datos.reason+"</div></div>");
					return;
				}
				
				
				nuevosProductos.loadData( datos.datos );
				
				
				var html = "";
					html += 
						"<div class='ApplicationComprarProveedor-item' >" 
							+ "<div class='producto_nombre'>PRODUCTO</div>" 
							
							+ "<div class='cabecera-compra'>PRECIO PROVEEDOR</div>" 
							
							+ "<div class='cabecera-compra'>PRECIO VENTA</div>" 
							
							+ "<div class='cabecera-compra'>STOCK MINIMO</div>" 
							
						+ "</div>";
				
					for( a = 0; a < nuevosProductos.getCount(); a++ ){
					
						html += "<div class='ApplicationComprarProveedor-item' id ='nuevoItemInventario_"+nuevosProductos.data.items[a].data.id_producto+"'>" 
						+ "<div class='producto_nombre' id='nuevoProd_"+nuevosProductos.data.items[a].data.id_producto+"'>" + nuevosProductos.data.items[a].data.denominacion +"</div>" 
						
						+ "<div class='cabecera-compra' id= 'precioCompraProduc_"+nuevosProductos.data.items[a].data.id_producto+"'>" 
						+ nuevosProductos.data.items[a].data.precio
						+"</div>"
						
						+ "<div class='caja_texto'>" 
						+ "<INPUT TYPE='text' id='precioVenta_"+nuevosProductos.data.items[a].data.id_producto+"' SIZE='5'  onchange='ApplicationComprarProveedor.currentInstance.precioVenta("+nuevosProductos.data.items[a].data.id_producto+",this.value, precioVenta_"+nuevosProductos.data.items[a].data.id_producto+")' class='description' disabled='true'>" 
						+"</div>"
						
						
						+ "<div class='caja_texto'>" 
						+ "<INPUT TYPE='text' id='stockMin_"+nuevosProductos.data.items[a].data.id_producto+"' SIZE='5'  onchange='ApplicationComprarProveedor.currentInstance.stockMin("+nuevosProductos.data.items[a].data.id_producto+",this.value, stockMin_"+nuevosProductos.data.items[a].data.id_producto+")' class='description' disabled='true'>" 
						+"</div>"
						
						
						+ "<div class = 'ApplicationComprarProveedor-toggle' id = 'add_prod_"+nuevosProductos.data.items[a].data.id_producto+"'></div>"
						
						+ "</div>";
						
						
					}//fin for 
				
					//imprimir el html
					Ext.get("Productos-Proveedor").update("<div class='ApplicationComprarProveedor-itemsBox'>" + html +"</div>");
					
				
					
					for( hh = 0; hh < nuevosProductos.getCount(); hh++ ){
						addProduct = new Ext.form.Toggle({
							id: 'agregar_'+nuevosProductos.data.items[hh].data.id_producto,
							renderTo: 'add_prod_'+nuevosProductos.data.items[hh].data.id_producto,
							listeners: {
								change:	function() {
									
										  ApplicationComprarProveedor.currentInstance.elegirProductoInventario( this.getId() );
								}
							}//listeners
						});
						ApplicationComprarProveedor.currentInstance.toggleBtns2.push( addProduct );
					}
					ApplicationComprarProveedor.currentInstance._BANDERATOGGLES2 = true;	
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

ApplicationComprarProveedor.prototype.compras_doAddProduct = function ( id_producto )
{
	
	//console.log("Entrando a Metodo AJAX q hace el push");
	

	//buscar si este producto existe (vender un producto que exista en la sucursal)
	POS.AJAXandDECODE({
			action: '1210',
			id_producto : id_producto,
			id_proveedor: ApplicationComprarProveedor.currentInstance.providerId,
		}, 
		function (datos){
			//console.log("-- ANTES A AGREGAR A COMPRA ITEMS SUCCESS: "+datos.success);
			//ya llego el request con los datos si existe o no	
			if(!datos.success){
				POS.aviso("Error", datos.reason);
				return;
			}

			if(!ApplicationComprarProveedor.currentInstance._BANDERA){
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
				//console.log("------ ANTES DE HACER EL PUSH EN COMPRA ITEMS");
			ApplicationComprarProveedor.currentInstance.compraItems.push( item );
			
			

		},
		function (){
			POS.aviso("Error", "Error en la conexion, porfavor intente de nuevo.");
		}
	);
	
};

/*
	agrega item al carrito de productos por agregar al inventario 
*/

ApplicationComprarProveedor.prototype.inventario_doAddProduct = function ( id_producto, denominacion, precioCompra)
{
	var item = {
		id 			: id_producto,
		precioVenta	: 0,
		stockMin : 0,
		denominacion: denominacion,
		precioCompra: precioCompra
	};
		
			//agregarlo al carrito
	ApplicationComprarProveedor.currentInstance.inventarioItems.push( item );
		
	
};

/*
	Elimina un producto de la lista de items por comprar
*/

ApplicationComprarProveedor.prototype.compras_doDeleteProduct = function( id_producto ){
	
	
	for( f = 0; f < ApplicationComprarProveedor.currentInstance.compraItems.length;  f++){
		
		if( ApplicationComprarProveedor.currentInstance.compraItems[ f ].id == id_producto ){
			//item already in cart
			ApplicationComprarProveedor.currentInstance.compraItems.splice( f , 1);
			break;
		}
	}
	
};

/*
	Elimina un producto de la lista de items de prodcutos por agregar al inventario
*/

ApplicationComprarProveedor.prototype.inventario_doDeleteProduct = function( id_producto ){
	
	
	for( f = 0; f < ApplicationComprarProveedor.currentInstance.inventarioItems.length;  f++){
		
		if( ApplicationComprarProveedor.currentInstance.inventarioItems[ f ].id == id_producto ){
			//item already in cart
			ApplicationComprarProveedor.currentInstance.inventarioItems.splice( f , 1);
			break;
		}
	}
	
};


/*---------------------------------------------------
	PROPIEDADES DE LA CLASE QUE SON USADAS UNICAMENTE PARA MODO DE COMPRA
	POR KGS 
-----------------------------------------------------*/
ApplicationComprarProveedor.prototype.pesoArpilla = 0;
ApplicationComprarProveedor.prototype.totalArpillas = 0;

/*---------------------------------------------------------
	METODO QUE SE USA UNICAMENTE PARA MODO DE COMPRA POR KGS (TOÑO MODE)
	SIRVE PARA IR SUMANDO EL NUMERO DE ARPILLAS E IR CALCULANDO LOS TOTALES Y REFRESCANDO
	EL HTML
-----------------------------------------------------------*/

ApplicationComprarProveedor.prototype.sumarArp = function( idProd ,  arpillas , cajaId ){
	
	var indiceItem = 0;
	
	for( f = 0; f < ApplicationComprarProveedor.currentInstance.compraItems.length;  f++){
		
		if( ApplicationComprarProveedor.currentInstance.compraItems[ f ].id == idProd ){
				//item already in cart
				indiceItem = f;
	
				break;
			}
	}//fin for
	
	if( !isNaN(arpillas) && arpillas > 0 )
	{
		ApplicationComprarProveedor.currentInstance.compraItems[ indiceItem ].nA = parseFloat(arpillas);
		
	}
	if ( arpillas === null || arpillas =='' || arpillas < 0 || isNaN(arpillas) ){
		cajaId.value = "0";
		
		ApplicationComprarProveedor.currentInstance.compraItems[ indiceItem ].nA = 0;
		document.getElementById( cajaId.id ).focus();

	}//fin else
	
	var totArp=0;
	
	for(i=0; i < ApplicationComprarProveedor.currentInstance.compraItems.length; i++){
		totArp += parseFloat(ApplicationComprarProveedor.currentInstance.compraItems[i].nA);
	}
	//se saca el peso de las arpillas con el peso del embarque entre el numero total de arpillas de la compra
	ApplicationComprarProveedor.currentInstance.pesoArpilla = Ext.getCmp("pesoEmbarque").getValue(true) / totArp;
	
	Ext.get("totalArps").update("" + totArp);
	Ext.get("pesoArpillas").update(""+ApplicationComprarProveedor.currentInstance.pesoArpilla.toFixed(2));


	//el peso de la arpilla a pagar de cada clasificacion a comprar se saca con el peso de la arpilla menos los Kilogramos que se le restan (Kgr: que vendria siendo la merma de que trae esa clasificacion).
			
	ApplicationComprarProveedor.currentInstance.compraItems[ indiceItem ].pesoArp = ApplicationComprarProveedor.currentInstance.pesoArpilla - ApplicationComprarProveedor.currentInstance.compraItems[ indiceItem ].kgR;
			
	//Los kilogramos de cada clasificacion que se van a pagar (kgTot) se saca restandole al peso de la arpilla los kilogramos restados de la clasificacion y esto se multiplica por el numero de arpillas de esa clasificacion
			
	ApplicationComprarProveedor.currentInstance.compraItems[ indiceItem ].kgTot = (ApplicationComprarProveedor.currentInstance.pesoArpilla - ApplicationComprarProveedor.currentInstance.compraItems[ indiceItem ].kgR) * ApplicationComprarProveedor.currentInstance.compraItems[ indiceItem ].nA;
			
	KgTot = parseFloat(ApplicationComprarProveedor.currentInstance.compraItems[ indiceItem ].kgTot);
	Ext.get("KgsPagarProducto_"+idProd).update(""+KgTot.toFixed(2));
	
	ApplicationComprarProveedor.currentInstance.totalArpillas = totArp;
	
	//cada vez que se cambia el numero de arpillas para un producto cambia el peso de la arpilla por eso se debe de cambiar el peso de arpilla de cada producto de la lista asi como su subtotal y numero de arpillas
	for(i=0; i < ApplicationComprarProveedor.currentInstance.compraItems.length; i++){
		
		ApplicationComprarProveedor.currentInstance.compraItems[i].kgTot = (ApplicationComprarProveedor.currentInstance.pesoArpilla - ApplicationComprarProveedor.currentInstance.compraItems[ i ].kgR) * ApplicationComprarProveedor.currentInstance.compraItems[ i ].nA;
		
		ApplicationComprarProveedor.currentInstance.compraItems[i].subtot = ApplicationComprarProveedor.currentInstance.compraItems[ i ].kgTot * ApplicationComprarProveedor.currentInstance.compraItems[ i ].prKg;
		
		ApplicationComprarProveedor.currentInstance.compraItems[i].pesoArp = ApplicationComprarProveedor.currentInstance.pesoArpilla - ApplicationComprarProveedor.currentInstance.compraItems[ i ].kgR;
		
		
		idProd = ApplicationComprarProveedor.currentInstance.compraItems[i].id;
		precio = parseFloat(ApplicationComprarProveedor.currentInstance.compraItems[i].prKg);
		subtot = parseFloat(ApplicationComprarProveedor.currentInstance.compraItems[i].subtot);
		kgtot = parseFloat(ApplicationComprarProveedor.currentInstance.compraItems[i].kgTot);
		pesoArpPagar = parseFloat(ApplicationComprarProveedor.currentInstance.compraItems[i].pesoArp);
		
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

ApplicationComprarProveedor.prototype.pesoEmbarque = function( ){

	var embarque = parseFloat( Ext.getCmp("pesoEmbarque").getValue() );
	var totArp=0;
	
	for(i=0; i < ApplicationComprarProveedor.currentInstance.compraItems.length; i++){
		totArp += parseFloat(ApplicationComprarProveedor.currentInstance.compraItems[i].nA);
	}
	
	if( !isNaN(embarque) && embarque > 0) 
	{
		if ( ApplicationComprarProveedor.currentInstance.compraItems.length == 0 ){
			return;
		}
		//se saca el peso de las arpillas con el peso del embarque entre el numero total de arpillas de la compra
		ApplicationComprarProveedor.currentInstance.pesoArpilla = Ext.getCmp("pesoEmbarque").getValue(true) / totArp;
		
	}else{
		Ext.getCmp("pesoEmbarque").setValue(0);
		
		ApplicationComprarProveedor.currentInstance.pesoArpilla = 0;
		
	}//fin else
	
	
	
	
	Ext.get("totalArps").update("" + totArp);
	Ext.get("pesoArpillas").update(""+ApplicationComprarProveedor.currentInstance.pesoArpilla.toFixed(2));

	ApplicationComprarProveedor.currentInstance.totalArpillas = totArp;
	
	//cada vez que se cambia el peso del embarque cambia el peso de la arpilla por eso se debe de cambiar el peso de arpilla de cada producto de la lista asi como su subtotal y numero de arpillas
	for(i=0; i < ApplicationComprarProveedor.currentInstance.compraItems.length; i++){
		
		ApplicationComprarProveedor.currentInstance.compraItems[i].kgTot = (ApplicationComprarProveedor.currentInstance.pesoArpilla - ApplicationComprarProveedor.currentInstance.compraItems[ i ].kgR) * ApplicationComprarProveedor.currentInstance.compraItems[ i ].nA;
		
		ApplicationComprarProveedor.currentInstance.compraItems[i].subtot = ApplicationComprarProveedor.currentInstance.compraItems[ i ].kgTot * ApplicationComprarProveedor.currentInstance.compraItems[ i ].prKg;
		
		ApplicationComprarProveedor.currentInstance.compraItems[i].pesoArp = ApplicationComprarProveedor.currentInstance.pesoArpilla - ApplicationComprarProveedor.currentInstance.compraItems[ i ].kgR;
		
		idProd = ApplicationComprarProveedor.currentInstance.compraItems[i].id;
		precio = parseFloat(ApplicationComprarProveedor.currentInstance.compraItems[i].prKg);
		subtot = parseFloat(ApplicationComprarProveedor.currentInstance.compraItems[i].subtot);
		kgtot =  parseFloat(ApplicationComprarProveedor.currentInstance.compraItems[i].kgTot);
		pesoArpPagar = parseFloat(ApplicationComprarProveedor.currentInstance.compraItems[i].pesoArp);
		
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

ApplicationComprarProveedor.prototype.revisarCantidadKg = function( idProd ,  kgsRebajados , cajaId ){
	
	var indiceItem = 0;
	
	for( f = 0; f < ApplicationComprarProveedor.currentInstance.compraItems.length;  f++){
		
		if( ApplicationComprarProveedor.currentInstance.compraItems[ f ].id == idProd ){
				//item already in cart
				indiceItem = f;
	
				break;
			}
	}//fin for

	if(!isNaN(kgsRebajados) && kgsRebajados > 0 )
	{

		ApplicationComprarProveedor.currentInstance.compraItems[ indiceItem ].kgR = kgsRebajados;
	}
	if ( kgsRebajados === null || kgsRebajados =='' || kgsRebajados < 0 || isNaN(kgsRebajados) ){
		cajaId.value = "0";
		ApplicationComprarProveedor.currentInstance.compraItems[ indiceItem ].kgR = 0;
		document.getElementById( cajaId.id ).focus();
	}
	ApplicationComprarProveedor.currentInstance.compraItems[ indiceItem ].kgTot = (ApplicationComprarProveedor.currentInstance.pesoArpilla - ApplicationComprarProveedor.currentInstance.compraItems[ indiceItem ].kgR) * ApplicationComprarProveedor.currentInstance.compraItems[ indiceItem ].nA;
	
	
	ApplicationComprarProveedor.currentInstance.compraItems[indiceItem].pesoArp = ApplicationComprarProveedor.currentInstance.pesoArpilla - ApplicationComprarProveedor.currentInstance.compraItems[ indiceItem ].kgR;
	
	ApplicationComprarProveedor.currentInstance.compraItems[ indiceItem ].subtot = ApplicationComprarProveedor.currentInstance.compraItems[ indiceItem ].prKg * ApplicationComprarProveedor.currentInstance.compraItems[ indiceItem ].kgTot;
	
	kgTot = parseFloat(ApplicationComprarProveedor.currentInstance.compraItems[indiceItem].kgTot);
	Ext.get("KgsPagarProducto_"+idProd).update(""+kgTot.toFixed(2));
	
	subtotal = parseFloat(ApplicationComprarProveedor.currentInstance.compraItems[indiceItem].subtot);
	Ext.get("subtotalProducto_"+idProd).update("$ "+subtotal.toFixed(2));
	
	pesoARP = parseFloat(ApplicationComprarProveedor.currentInstance.compraItems[indiceItem].pesoArp);
	Ext.get("pesoArpPagar_"+idProd).update(""+pesoARP.toFixed(2));

};


ApplicationComprarProveedor.prototype.revisarCantidaPrecio = function( idProd ,  precio , cajaId ){
	
	var indiceItem = 0;
	
	for( f = 0; f < ApplicationComprarProveedor.currentInstance.compraItems.length;  f++){
		
		if( ApplicationComprarProveedor.currentInstance.compraItems[ f ].id == idProd ){
				//item already in cart
				indiceItem = f;
	
				break;
			}
	}//fin for
	
	if( !isNaN(precio) && precio > 0 )
	{
		ApplicationComprarProveedor.currentInstance.compraItems[ indiceItem ].prKg = precio;
		ApplicationComprarProveedor.currentInstance.compraItems[ indiceItem ].subtot = precio * ApplicationComprarProveedor.currentInstance.compraItems[ indiceItem ].kgTot;
		
		price = parseFloat(precio);
		Ext.get("precioPorKgProducto_"+idProd+"").update("$ "+price.toFixed(2));
		
		subtotalP = (precio * (parseFloat(Ext.get("KgsPagarProducto_"+idProd).getHTML())));
		Ext.get("subtotalProducto_"+idProd+"").update("$ "+subtotalP.toFixed(2) );
		
	}
	if ( precio === null || precio =='' || precio < 0 || isNaN(precio) ){
		cajaId.value = "0";
		Ext.get("precioPorKgProducto_"+idProd+"").update("$ 0");
		Ext.get("subtotalProducto_"+idProd+"").update("$ 0");
		ApplicationComprarProveedor.currentInstance.compraItems[ indiceItem ].prKg = 0;
		document.getElementById( cajaId.id ).focus();
	}

};

ApplicationComprarProveedor.prototype.precioVenta = function( idProd ,  precio , cajaId ){
	
	var indiceItem = 0;
	
	for( f = 0; f < ApplicationComprarProveedor.currentInstance.inventarioItems.length;  f++){
		
		if( ApplicationComprarProveedor.currentInstance.inventarioItems[ f ].id == idProd ){
				//item already in cart
				indiceItem = f;
	
				break;
			}
	}//fin for
	
	if( !isNaN(precio) && precio > 0 )
	{
		ApplicationComprarProveedor.currentInstance.inventarioItems[ indiceItem ].precioVenta = precio;
		
				
	}
	if ( precio === null || precio =='' || precio < 0 || isNaN(precio) ){
		cajaId.value = "0";
		
		ApplicationComprarProveedor.currentInstance.inventarioItems[ indiceItem ].precioVenta = 0;
		document.getElementById( cajaId.id ).focus();
	}

};

ApplicationComprarProveedor.prototype.stockMin = function( idProd ,  stockM , cajaId ){
	
	var indiceItem = 0;
	
	for( f = 0; f < ApplicationComprarProveedor.currentInstance.inventarioItems.length;  f++){
		
		if( ApplicationComprarProveedor.currentInstance.inventarioItems[ f ].stockMin == stockM ){
				//item already in cart
				indiceItem = f;
	
				break;
			}
	}//fin for
	
	if( !isNaN(stockM) && stockM > 0 )
	{
		ApplicationComprarProveedor.currentInstance.inventarioItems[ indiceItem ].stockMin = stockM;
		
				
	}
	if ( stockM === null || stockM =='' || stockM < 0 || isNaN(stockM) ){
		cajaId.value = "0";
		
		ApplicationComprarProveedor.currentInstance.inventarioItems[ indiceItem ].stockMin = 0;
		document.getElementById( cajaId.id ).focus();
	}

};


ApplicationComprarProveedor.prototype.do_agregarInventario = function ()
{
	
	if(DEBUG){
		console.log("ApplicationCompras: do_agregarInventario called....");
	}
	
	ban = false;
	ban2= false;
	
	items = ApplicationComprarProveedor.currentInstance.inventarioItems;
	//console.log("-----> 1) do_agregarInventario en inventario items hay: "+items.length+" elementos selecciona2");
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
	
	ApplicationComprarProveedor.currentInstance.do_agregarItemInventario();
};


ApplicationComprarProveedor.prototype.do_agregarItemInventario = function(){
	//console.log("------> 2) AJAX do_agregarItemInventario estos son los items q se mandan: ");
	var jsonItems = Ext.util.JSON.encode(ApplicationComprarProveedor.currentInstance.inventarioItems);
	//console.log(jsonItems);

	POS.AJAXandDECODE({
			action: '1220',
			jsonItems : jsonItems,
		}, 
		function (datos){
			
			//ya llego el request con los datos si existe o no	
			if(!datos.success){
				POS.aviso("Error", ""+datos.reason);
				//console.log("|||||||||||| ME REGRESO FALSO SUCCESS: "+datos.success);
				return;
			}
			//console.log("!!!!!!!!!!!!!!!!!!! SUCCESS: "+datos.success);

			ApplicationComprarProveedor.currentInstance.htmlItemInventario();
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

ApplicationComprarProveedor.prototype.sumaCantidad = function( idProd ,  cantidad , cajaId ){
	
	var indiceItem = 0;
	
	for( f = 0; f < ApplicationComprarProveedor.currentInstance.compraItems.length;  f++){
		
		if( ApplicationComprarProveedor.currentInstance.compraItems[ f ].id == idProd ){
				//item already in cart
				indiceItem = f;
	
				break;
			}
	}//fin for
	
	if(!isNaN(cantidad)  && cantidad > 0 )
	{
		
		ApplicationComprarProveedor.currentInstance.compraItems[ indiceItem ].cantidad = cantidad;
		
		ApplicationComprarProveedor.currentInstance.compraItems[ indiceItem ].subtot = ApplicationComprarProveedor.currentInstance.compraItems[ indiceItem ].cantidad * ApplicationComprarProveedor.currentInstance.compraItems[ indiceItem ].cost;
		
		Ext.get("subtotProducto_"+idProd).update("$ "+ApplicationComprarProveedor.currentInstance.compraItems[ indiceItem ].subtot.toFixed(2) );
		
	}
	if ( cantidad === null || cantidad =='' || cantidad < 0 || isNaN(cantidad) ){
		cajaId.value = "0";
		ApplicationComprarProveedor.currentInstance.compraItems[ indiceItem ].cantidad = 0;
		ApplicationComprarProveedor.currentInstance.compraItems[ indiceItem ].subtot = 0;
		Ext.get("subtotProducto_"+idProd).update("$ 0");
	}
	
	var subtotal_compra=0;
	
	for(i=0; i < ApplicationComprarProveedor.currentInstance.compraItems.length; i++){
		subtotal_compra += parseFloat( ApplicationComprarProveedor.currentInstance.compraItems[i].subtot );
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


ApplicationComprarProveedor.prototype.doComprar = function ()
{
	
	if(DEBUG){
		console.log("ApplicationCompras: doComprar called....");
	}
	
	
	items = ApplicationComprarProveedor.currentInstance.compraItems;

	if(!ApplicationComprarProveedor.currentInstance._BANDERA){//validacion en toño mode

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
			if(ApplicationComprarProveedor.currentInstance.totalArpillas == 0 || ban){
				POS.aviso("Comprar Productos", "3) Llenar las columnas No. Arpillas de todos los productos agregados con un numero mayor a 0");
				return;
			}
			if(ApplicationComprarProveedor.currentInstance.totalArpillas > Ext.getCmp("pesoEmbarque").getValue()){
				POS.aviso("Comprar Productos", "4) El valor de 'Peso en Kg del Embarque' debe ser mayor al de 'Numero de Arpillas'");
				return;
			}
			
			if(total < 1 || ban2){
				POS.aviso("Comprar Productos", "5) Revisar en las columnas 'Kg Menos' de cada producto que tenga un valor y que ese valor no sea mayor o igual al 'Peso por Arpilla' <br>6) Revisar en las columnas 'Precio x Kg' de cada producto que el valor sea mayor '0'");
				return;
			}
			if(ApplicationComprarProveedor.currentInstance.pesoArpilla > Ext.getCmp("pesoEmbarque").getValue()){
				POS.aviso("Comprar Productos", "* El valor de 'Peso en Kg del Embarque' debe ser mayor al de 'Peso por Arpilla'");
				return;
			}
		
		newPanel = ApplicationComprarProveedor.currentInstance.doComprarPanel();
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
		
		newPanel = ApplicationComprarProveedor.currentInstance.doComprarPanel();
		sink.Main.ui.setCard( newPanel, 'slide' );		
	}
	
};


/*------------------------------------------------------
	MUESTRA EL PANEL CON EL SUBTOTAL IVA Y TOTAL DE LA VENTA
	Y SE DEFINE SI ES DE CONTADO O A CREDITO
--------------------------------------------------------*/

ApplicationComprarProveedor.prototype.doComprarPanel = function ()
{
	
	
	var subtotal = 0;
	var total = 0;
	var iva=0;
	for( a = 0; a < ApplicationComprarProveedor.currentInstance.compraItems.length;  a++){
		subtotal += parseFloat( ApplicationComprarProveedor.currentInstance.compraItems[a].subtot );
	}
	
	total = subtotal;
	iva = 0 * total; //0 porque el no incluye iva
	return new Ext.form.FormPanel({
	//tipo de scroll
    scroll: 'none',

	baseCls: "formAgregarProveedor",

	//toolbar
	dockedItems: [new Ext.Toolbar({
        ui: 'dark',
        dock: 'bottom',
        items: [{
				xtype:'button', 
				text:'Regresar',
				ui: 'back',
				handler: function(){
					sink.Main.ui.setCard( ApplicationComprarProveedor.currentInstance.surtir, { type: 'slide', direction: 'right' } );
				}
			},{
				xtype:'button', 
				text:'Cancelar Compra',
				handler: function(){
					//reestablecer todo para una nueva venta
					
					ApplicationComprarProveedor.currentInstance.toggleBtns.length = 0;
					ApplicationComprarProveedor.currentInstance.toggleBtns2.length = 0;
					ApplicationComprarProveedor.currentInstance.compraItems.length = 0;
					ApplicationComprarProveedor.currentInstance.inventarioItems.length = 0;
					ApplicationComprarProveedor.currentInstance._BANDERATOGGLES = false;
					ApplicationComprarProveedor.currentInstance._BANDERATOGGLES2 = false;
					
					//se regresa al panel donde se realiza la compra
					sink.Main.ui.setCard( ApplicationComprarProveedor.currentInstance.surtir, { type: 'slide', direction: 'right' } );
					
					//segun el modo de compra se actualiza todo desde el inicio para una nueva compra
					if(!ApplicationComprarProveedor.currentInstance._BANDERA){
						
						ApplicationComprarProveedor.currentInstance.pesoArpilla = 0;
						ApplicationComprarProveedor.currentInstance.totalArpillas = 0;
						
						ApplicationComprarProveedor.currentInstance.llenarPanelComprasXarpillas( ApplicationComprarProveedor.currentInstance.providerId );
						
						Ext.getCmp("pesoEmbarque").setValue(0);						
						Ext.get("totalArps").update("0");
						Ext.get("pesoArpillas").update("0");
						
					}else{
						
						ApplicationComprarProveedor.currentInstance.llenarPanelComprasXpiezas( ApplicationComprarProveedor.currentInstance.providerId );
						
						Ext.get("subtotal_compraProducto").update("$ 0");
						Ext.get("iva_compraProducto").update("$ 0");
						Ext.get("total_compraProducto").update("$ 0");
							
					}
										
					
				}//handler
				
			},{
				xtype:'spacer'
			},{
				xtype:'button', 
				ui: 'action',
				text:'Registrar Compra',
				
				handler: ApplicationComprarProveedor.currentInstance.doCompraLogic

			}]
    })],
	
	//items del formpanel
    items: [{

        xtype: 'fieldset',				
        title: 'Compra a proveedor: <b>'+ApplicationComprarProveedor.currentInstance.nombreProv+"</b>",
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

ApplicationComprarProveedor.prototype.doCompraLogic = function ()
{
	//insercion en la BD				 
	var jsonItems = Ext.util.JSON.encode(ApplicationComprarProveedor.currentInstance.compraItems);
	//console.log(jsonItems);
	
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
			id_proveedor : ApplicationComprarProveedor.currentInstance.providerId,//aqui el id del proveedor
			tipo_compra : tipoCompra,
			modo_compra	: ApplicationComprarProveedor.currentInstance._BANDERA //false toño mode, true general mode
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
	var thksPanel = ApplicationComprarProveedor.currentInstance.compras_doGraciasPanel();
	sink.Main.ui.setCard( thksPanel, 'fade' );
};


/*-----------------------------------------------------------
	SE REALIZÓ LA COMPRA Y MUESTRA PANEL FINAL
-------------------------------------------------------------*/
ApplicationComprarProveedor.prototype.compras_doGraciasPanel = function ()
{
	//reestablecer todo para una nueva venta
	ApplicationComprarProveedor.currentInstance.toggleBtns.length = 0;
	ApplicationComprarProveedor.currentInstance.toggleBtns2.length = 0;
	ApplicationComprarProveedor.currentInstance.compraItems.length = 0;
	ApplicationComprarProveedor.currentInstance.inventarioItems.length = 0;
	ApplicationComprarProveedor.currentInstance._BANDERATOGGLES = false;
	ApplicationComprarProveedor.currentInstance._BANDERATOGGLES2 = false;
	
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
					sink.Main.ui.setCard( ApplicationComprarProveedor.currentInstance.surtir, { type: 'slide', direction: 'right' } );
					
					//segun el modo de compra se actualiza todo desde el inicio para una nueva compra
					if(!ApplicationComprarProveedor.currentInstance._BANDERA){
						
						Ext.getCmp("pesoEmbarque").setValue(0);						
						Ext.get("totalArps").update("0");
						Ext.get("pesoArpillas").update("0");
						
						ApplicationComprarProveedor.currentInstance.pesoArpilla = 0;
						ApplicationComprarProveedor.currentInstance.totalArpillas = 0;
						
						ApplicationComprarProveedor.currentInstance.llenarPanelComprasXarpillas( ApplicationComprarProveedor.currentInstance.providerId );
												
					}else{
						
											
						ApplicationComprarProveedor.currentInstance.llenarPanelComprasXpiezas( ApplicationComprarProveedor.currentInstance.providerId );
						
						Ext.get("subtotal_compraProducto").update("$ 0");
						Ext.get("iva_compraProducto").update("$ 0");
						Ext.get("total_compraProducto").update("$ 0");
						
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
