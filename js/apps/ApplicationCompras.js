ApplicationCompras = function ()
{
	if(DEBUG){
		console.log("ApplicationCompras: construyendo");
	}
	
	this._init();
	
	ApplicationCompras.currentInstance = this;
	
	return this;
};








//aqui va el panel principal 
ApplicationCompras.prototype.mainCard = null;

//aqui va el nombre de la applicacion
ApplicationCompras.prototype.appName = null;

//aqui van los items del menu de la izquierda
ApplicationCompras.prototype.leftMenuItems = null;

//aqui va un texto de ayuda en html
ApplicationCompras.prototype.ayuda = null;

//dockedItems
ApplicationCompras.prototype.dockedItems = null;


ApplicationCompras.prototype._BANDERA = false; //en false habilita el modo surtir por kgs y embarques (TOÑO MODE)

ApplicationCompras.prototype.idProveedor = 0;

ApplicationCompras.prototype.nombreProv = "";

ApplicationCompras.prototype._init = function()
{
	
	//nombre de la aplicacion
	this.appName = "Compras Proveedor";
	
	//ayuda sobre esta applicacion
	this.ayuda = "Pulsar el boton 'Comprar' y seguir pasos";
	
	//submenues en el panel de la izquierda
	this.leftMenuItems = null;


	//panel principal	
	this.mainCard = this.comprarMainPanel;

	//initialize the tootlbar which is a dock
	this._initToolBar();

	
};










/* ------------------------------------------------------------------------------------
					tool bar
   ------------------------------------------------------------------------------------ */

ApplicationCompras.prototype._initToolBar = function (){


	//grupo 1, agregar producto
	var buttonsGroup1 = [
		{
			xtype: 'textfield',
			id: 'compras_APaddProductByID',
			startValue: 'ID del producto',
			listeners:
					{
						'afterrender': function( ){
							//focus
							document.getElementById( Ext.get("compras_APaddProductByID").first().id ).focus();
							
							//medio feo, pero bueno
							Ext.get("compras_APaddProductByID").first().dom.setAttribute("onkeyup","ApplicationCompras.currentInstance.compras_addProductByIDKeyUp( this, this.value )");
							
						}
					}
		},{
        	text: 'Agregar producto',
        	ui: 'round',
        	handler: this.compras_doAddProduct
    	}];


	//grupo 2, cualquier otra cosa
	var buttonsGroup2 = [
		{
            text: 'Regresar',
			ui: 'back',
			handler: function(){
				sink.Main.ui.setCard( ApplicationProveedores.currentInstance.mainCard, { type: 'slide', direction: 'right' } );
			}
        }
		
		];


	//grupo 3, listo para vender
    var buttonsGroup3 = [{
        text: 'Limpiar',
        handler: this.compras_LimpiarCarrito
    },{
        text: 'Comprar',
        ui: 'action',
		id: 'compras_doVenderButton',
        handler: this.compras_doVender
    }];


	if (!Ext.platform.isPhone) {
		
        buttonsGroup1.push({xtype: 'spacer'});
        //buttonsGroup2.push({xtype: 'spacer'});
        
        this.dockedItems = [new Ext.Toolbar({
            ui: 'dark',
            dock: 'bottom',
            items: buttonsGroup1.concat(buttonsGroup2).concat(buttonsGroup3)
        })];

    }else {
        this.dockedItems = [{
            xtype: 'toolbar',
            ui: 'light',
            items: buttonsGroup1,
            dock: 'bottom'
        }, {
            xtype: 'toolbar',
            ui: 'dark',
            items: buttonsGroup2,
            dock: 'bottom'
        }, {
            xtype: 'toolbar',
            ui: 'metal',
            items: buttonsGroup3,
            dock: 'bottom'
        }];
    }

	
	//agregar este dock a el panel principal
	this.mainCard.addDocked( this.dockedItems );
	
	

};



						   
ApplicationCompras.prototype.compras_addProductByIDKeyUp = function (a, b)
{
	if(event.keyCode == 13){
		ApplicationCompras.currentInstance.compras_doAddProduct();
	}
};


/*------------------------------------------------------------------
	PROPIEDAD DE LA CLAS QUE ALMACENA EL ID DEL PROVEEDOR SELECCIONADO
--------------------------------------------------------------------*/

ApplicationCompras.prototype.idProviderSelected = function( id ){
	//console.log("ENTRE AL METODO DE IDPROVIDERSELECTED Y ME ESTA LLEGANDO UN :"+id);
	//console.log(id);
	//console.log(id.id_proveedor);
	ApplicationCompras.currentInstance.idProveedor = id.id_proveedor;
	ApplicationCompras.currentInstance.nombreProv = id.nombre;
};
/* ------------------------------------------------------------------------------------
					main card
   ------------------------------------------------------------------------------------ */

ApplicationCompras.prototype.comprarMainPanel = new Ext.Panel({
    scroll: 'vertical',

	dockedItems: null,
	
	cls: "ApplicationCompras-mainPanel",
	
	//items del formpanel !ApplicationCompras.currentInstance.COMPRAR_PIEZAS
    items: [{
			xtype: 'textfield',
			label: 'Peso en Kgs del Embarque:',
			id: 'pesoEmbarque',
			value: 0,
			hidden : true,
			listeners: {
				change: function(){
					if(isNaN(Ext.getCmp("pesoEmbarque").getValue()) || Ext.getCmp("pesoEmbarque").getValue()=="")
					{	
						Ext.getCmp("pesoEmbarque").setValue(0);
						document.getElementById( "pesoEmbarque" ).focus();
					}else{
						try{
						ApplicationCompras.currentInstance.sumarArp(0,Ext.get("arpillasItem_0").getValue(), Ext.get("arpillasItem_0").id);
						}catch(e){
							
						}
					}
				}
			}
			},
			{
			html: '',
			id : 'detallesCliente'
		},{
			html: '',
			id : 'carritoDeCompras'
		},{
			html: '',
			id: 'totalArpillas'
			},
			{
			html: '',
			id: 'totalCompra'
			}
		],
		listeners: {
			beforeshow: function(){
				//console.log("EL ID DEL PROVEEDOR SELEC ES: "+ApplicationCompras.currentInstance.idProveedor);
				if(!ApplicationCompras.currentInstance._BANDERA){
					console.log("SI ES FALSO DEBO DE ENTRAR A SURTIR POR KGS (TOÑO MODE)");
					Ext.getCmp("pesoEmbarque").show();
					
				}
			},
			show: function(){
				/*
					PRODUCTOS QUE VENDE ESTE PROVEEDOR A ESTA SUCURSAL
				*/
				POS.AJAXandDECODE({
						action: '1211',
						id_proveedor: ApplicationCompras.currentInstance.idProveedor 
						},
						function (datos){//mientras responda AJAXDECODE LISTAR VENTAS CLIENTE
							if(!datos.success){
								POS.aviso("Error",""+datos.reason);
								return;
							}
							
						},
						function (){//no responde AJAXDECODE DE VENTAS CLIENTE
							POS.aviso("ERROR","NO SE PUDO CARGAR LA LISTA DE VENTAS   ERROR EN LA CONEXION :(");      
						}
					);//AJAXandDECODE LISTAR VENTAS CLIENTE
				/*-------------
				*/
			}
		}
});




/* ------------------------------------------------------------------------------------
					carrito de compras
   ------------------------------------------------------------------------------------ */
ApplicationCompras.prototype.htmlCart_items = [];


ApplicationCompras.prototype.compras_LimpiarCarrito = function ( )
{
	
	var items = ApplicationCompras.currentInstance.htmlCart_items;
	
	if( items.length != 0){
		while(items.length != 0){
			items.pop();
		}
		if(!ApplicationCompras.currentInstance._BANDERA){
			
			ApplicationCompras.currentInstance.pesoArpilla = 0;
			ApplicationCompras.currentInstance.totalArpillas = 0;
			ApplicationCompras.currentInstance.doRefreshItemList();
		}else{
			ApplicationCompras.currentInstance.doRefreshItemList2();
		}
	}

	
	
};



/*-------------------------------------------------
	DELETE ITEM: BORRA UN PRODUCTO DEL CARRITO DE COMPRAS Y REFRESCA EL HTML DELA COMPRA,
	EL REFRESCADO DEPENDE DEL MODO DE COMPRA, POR KGS (TOÑO MODE) O POR CANTIDAD DE 
	PRODUCTO (GENERAL MODE)
---------------------------------------------------*/


ApplicationCompras.prototype.doDeleteItem = function ( item , indiceItem)
{
	
	this.htmlCart_items.splice(item, 1);
	
	if(!ApplicationCompras.currentInstance._BANDERA){
		var totArp=0;
		for(i=0; i < this.htmlCart_items.length; i++){
			totArp += parseFloat(this.htmlCart_items[i].nA);
		}
		ApplicationCompras.currentInstance.pesoArpilla = parseFloat(Ext.getCmp("pesoEmbarque").getValue()) / totArp;
		
		Ext.get("totalArpillas").update("<div class='ApplicationCompras-itemsBox'><div class='ApplicationCompras-totalesBox'  id= 'totalArps'>Numero de Arpillas: " + totArp +"</div></div>   <div class='ApplicationCompras-itemsBox'><div class='ApplicationCompras-totalesBox'  id= 'pesoArpillas'>Peso por Arpilla: "+ApplicationCompras.currentInstance.pesoArpilla+" </div></div>");
		
		ApplicationCompras.currentInstance.totalArpillas = totArp;
		
		
		for(i=0; i < this.htmlCart_items.length; i++){
			this.htmlCart_items[i].kgTot = (ApplicationCompras.currentInstance.pesoArpilla - this.htmlCart_items[ i ].kgR) * this.htmlCart_items[ i ].nA;
			this.htmlCart_items[i].subtot = this.htmlCart_items[ i ].kgTot * this.htmlCart_items[ i ].prKg;
			this.htmlCart_items[i].pesoArp = ApplicationCompras.currentInstance.pesoArpilla - this.htmlCart_items[ i ].kgR;
		}
	
	this.doRefreshItemList();
	}else{
		this.doRefreshItemList2();
	}
	
	
};//fin doDeleteItem


/*------------------------------------------------------------------
	REFRESCADO DE HTML EN EL CASO DE QUE EL MODO DE COMPRA SEA POR CANTIDAD (GENERAL MODE)
--------------------------------------------------------------------*/

ApplicationCompras.prototype.doRefreshItemList2 = function (  )
{
	
	if( this.htmlCart_items.length == 0){
		Ext.get("carritoDeCompras").update("");
		Ext.get("totalArpillas").update("");
		Ext.get("totalCompra").update("");
		Ext.getCmp("pesoEmbarque").setValue(0);
		return;
	}
	
	var html = "";
	var totalCompra ="";
	
	
	// cabezera
	html += "<div class='ApplicationCompras-item' >" 
	+ "<div class='trash' ></div>"
	+ "<div class='id'>ID</div>" 
	+ "<div class='name'>Producto</div>" 
	+ "<div class='description'>Cantidad</div>" 
	+ "<div class='cost'>Precio</div>"
	+ "<div class='cost'>Subtotal</div>"
	+ "</div>";
	
	// items
	for( a = 0; a < this.htmlCart_items.length; a++ ){

		html += "<div class='ApplicationCompras-item' >" 
		+ "<div class='trash' onclick='ApplicationCompras.currentInstance.doDeleteItem(" +a+ ")'><img height=20 width=20 src='sencha/resources/img/toolbaricons/trash.png'></div>"	
		+ "<div class='id'>" + this.htmlCart_items[a].id +"</div>" 
		+ "<div class='name'>" + this.htmlCart_items[a].name +"</div>" 
		+ "<div class='description'>" //this.htmlCart_items[a].description 
		+ "<INPUT TYPE='text' id='cantidadProducto_"+a+"' SIZE='5' VALUE='"+this.htmlCart_items[a].cantidad+"' onchange='ApplicationCompras.currentInstance.sumaCantidad("+a+",this.value, cantidadProducto_"+a+")' class='description'>" 
		+"</div>"
		+ "<div class='cost'>"+ this.htmlCart_items[a].cost	
		+ "</div>"
		+ "<div class='cost'>"+ (this.htmlCart_items[a].cost * this.htmlCart_items[a].cantidad )
		+ "</div>"
		+ "</div>";
		
	}
	//preparar un html para los totales
	
	var subtotal = 0;
		
	//calcular totales
	for( a = 0; a < this.htmlCart_items.length;  a++){
		var subtotalItem = this.htmlCart_items[a].cost * this.htmlCart_items[a].cantidad;
		subtotal += subtotalItem;
		this.htmlCart_items[a].subtot = subtotalItem;
	}

	var totals_html = "<span id='subtotal_compra'>Subtotal " +  subtotal + "</span> "
				+ "<span id='iva_compra'>IVA $" +  (subtotal* 0) + "</span> "
				+ "<span id='total_compra'>Total " +  ((subtotal* 0) + subtotal) + "</span>";
					

	// wrap divs
	var compraHtml = "<div class='ApplicationCompras-itemsBox'>" + html +"</div>" ;
	totalCompra = "<div class='ApplicationCompras-totalesBox' >" + totals_html +"</div>" ;
	

	Ext.get("carritoDeCompras").update("<div >" + compraHtml + "</div>");
	Ext.get("totalCompra").update("<div >" + totalCompra + "</div>");


};//fin doRefresItemsList2 para general mode


/*----------------------------------------------------------
	REFRESCADO DE HTML EN CASO DE QUE EL MODO DE COMPRA SEA POR KGS (TOÑO MODE)
------------------------------------------------------------*/

ApplicationCompras.prototype.doRefreshItemList = function (  )
{
	
	if( this.htmlCart_items.length == 0){
		Ext.get("carritoDeCompras").update("");
		Ext.get("totalArpillas").update("");
		Ext.get("totalCompra").update("");
		Ext.getCmp("pesoEmbarque").setValue(0);
		return;
	}
	
	var html = "";
	var totalCompra ="";
	
	
	// cabezera
	html += "<div class='ApplicationCompras-item' >" 
	+ "<div class='trash' ></div>"
	+ "<div class='id'>ID</div>" 
	+ "<div class='name'>Nombre</div>" 
	+ "<div class='description'>No. Arpillas</div>" 
	+ "<div class='cost'>Kgs Rebajados</div>"
	+ "<div class='cost'>Precio x Kg</div>"
	+ "</div>";
	
	totalCompra += "<div class='ApplicationCompras-item' >" 
	+ "<div class='cost' >Nombre</div>"
	+ "<div class='cost'>Kgs a Pagar</div>" 
	+ "<div class='cost'>Precio por Kg</div>" 
	+ "<div class='cost'>Subtotal</div>" 
	+ "</div>";
	
	// items
	for( a = 0; a < this.htmlCart_items.length; a++ ){

		html += "<div class='ApplicationCompras-item' >" 
		+ "<div class='trash' onclick='ApplicationCompras.currentInstance.doDeleteItem(" +a+ ")'><img height=20 width=20 src='sencha/resources/img/toolbaricons/trash.png'></div>"	
		+ "<div class='id'>" + this.htmlCart_items[a].id +"</div>" 
		+ "<div class='name'>" + this.htmlCart_items[a].name +"</div>" 
		+ "<div class='description'>" //this.htmlCart_items[a].description 
		+ "<INPUT TYPE='text' id='arpillasItem_"+a+"' SIZE='5' VALUE='"+this.htmlCart_items[a].nA+"' onchange='ApplicationCompras.currentInstance.sumarArp("+a+",this.value, arpillasItem_"+a+")' class='description'>" 
		+"</div>"
		+ "<div class='cost'>"
		+ "<INPUT TYPE='text' id='kgsMenosItem_"+a+"' SIZE='5' VALUE='"+this.htmlCart_items[a].kgR+"' onchange='ApplicationCompras.currentInstance.revisarCantidadKg("+a+",this.value, kgsMenosItem_"+a+")' class='cost'>"
		+"</div>"
		+ "<div class='cost'>"
		+ "<INPUT TYPE='text' id='precioKgItem_"+a+"' SIZE='5' VALUE='"+this.htmlCart_items[a].prKg+"' onchange='ApplicationCompras.currentInstance.revisarCantidaPrecio("+a+",this.value, precioKgItem_"+a+")' class='cost'>"
		+ "</div>"
		+ "</div>";
		this.htmlCart_items[a].htmlCompra = "<div class='ApplicationCompras-item' >" 
									+ "<div class='cost'>"+ this.htmlCart_items[a].name +"</div>"
									+ "<div class='cost' id='kgPagarCompra_"+a+"'> "+this.htmlCart_items[a].kgTot+" </div>" 
									+ "<div class='cost' id='precioKgCompra_"+a+"'>$ "+this.htmlCart_items[a].prKg+" </div>" 
									+ "<div class='cost' id='subtotalCompra_"+a+"'>$ "+this.htmlCart_items[a].subtot+" </div>" 
									+ "</div>";
		totalCompra += this.htmlCart_items[a].htmlCompra;
		
	}
	


	//preparar un html para los totales
	var totals_html = "";


	var subtotal = 0;
		
	//calcular totales
	for( a = 0; a < this.htmlCart_items.length;  a++){
		subtotal += parseInt( this.htmlCart_items[a].cost );
	}


	totals_html = "<span>Subtotal " +  subtotal + "</span> "
				+ "<span>IVA $" +  (subtotal * 0) + "</span> "
				+ "<span>Total " +  ((subtotal * 0)+subtotal) + "</span>";
					

	// wrap divs
	html = "<div class='ApplicationCompras-itemsBox'>" + html +"</div>" ;
	totals_html = "<div class='ApplicationCompras-totalesBox' >" + totals_html +"</div>" ;
	
	var endhtml = html;

	Ext.get("carritoDeCompras").update("<div >" + endhtml + "</div>");
	Ext.get("totalCompra").update("<div >" + totalCompra + "</div>");


};
//fin doRefreshItemsList toño mode

/*---------------------------------------------------
	PROPIEDADES DE LA CLASE QUE SON USADAS UNICAMENTE PARA MODO DE COMPRA
	POR KGS
-----------------------------------------------------*/
ApplicationCompras.prototype.pesoArpilla = 0;
ApplicationCompras.prototype.totalArpillas = 0;

/*---------------------------------------------------------
	METODO QUE SE USA UNICAMENTE PARA MODO DE COMPRA POR KGS (TOÑO MODE)
	SIRVE PARA IR SUMANDO EL NUMERO DE ARPILLAS E IR CALCULANDO LOS TOTALES Y REFRESCANDO
	EL HTML
-----------------------------------------------------------*/

ApplicationCompras.prototype.sumarArp = function( indiceItem ,  arpillas , cajaId ){
	
	if(!isNaN(arpillas))
	{
		this.htmlCart_items[ indiceItem ].nA = arpillas;
		
	}else{
		cajaId.value = "0";
		this.htmlCart_items[ indiceItem ].nA = 0;
		
		//document.getElementById( cajaId.id ).focus();
	}
	var totArp=0;
	for(i=0; i < this.htmlCart_items.length; i++){
		totArp += parseFloat(this.htmlCart_items[i].nA);
	}
	ApplicationCompras.currentInstance.pesoArpilla = parseFloat(Ext.getCmp("pesoEmbarque").getValue()) / totArp;
	
	Ext.get("totalArpillas").update("<div class='ApplicationCompras-itemsBox'><div class='ApplicationCompras-totalesBox'  id= 'totalArps'>Numero de Arpillas: " + totArp +"</div></div>   <div class='ApplicationCompras-itemsBox'><div class='ApplicationCompras-totalesBox'  id= 'pesoArpillas'>Peso por Arpilla: "+ApplicationCompras.currentInstance.pesoArpilla+" </div></div>");
	
	this.htmlCart_items[ indiceItem ].pesoArp = ApplicationCompras.currentInstance.pesoArpilla - this.htmlCart_items[ indiceItem ].kgR;
	
	this.htmlCart_items[ indiceItem ].kgTot = (ApplicationCompras.currentInstance.pesoArpilla - this.htmlCart_items[ indiceItem ].kgR) * this.htmlCart_items[ indiceItem ].nA;
	Ext.get("kgPagarCompra_"+indiceItem).update(this.htmlCart_items[indiceItem].kgTot);
	
	ApplicationCompras.currentInstance.totalArpillas = totArp;
	
	
	for(i=0; i < this.htmlCart_items.length; i++){
		this.htmlCart_items[i].kgTot = (ApplicationCompras.currentInstance.pesoArpilla - this.htmlCart_items[ i ].kgR) * this.htmlCart_items[ i ].nA;
		this.htmlCart_items[i].subtot = this.htmlCart_items[ i ].kgTot * this.htmlCart_items[ i ].prKg;
		this.htmlCart_items[i].pesoArp = ApplicationCompras.currentInstance.pesoArpilla - this.htmlCart_items[ i ].kgR;
	}
	this.doRefreshItemList();
}

/*-------------------------------------------------------------------
	METODO QUE SE USA UNICAMENTE PARA MODO DE COMPRA POR KGS (TOÑO MODE)
	SIRVE PARA IR VALIDANDO LA CANTIDAD DE KGS DEL PRODUCTO E IR CALCULANDO LOS TOTALES Y REFRESCANDO
	EL HTML
---------------------------------------------------------------------*/

ApplicationCompras.prototype.revisarCantidadKg = function( indiceItem ,  kgsRebajados , cajaId ){

	if(!isNaN(kgsRebajados))
	{

		this.htmlCart_items[ indiceItem ].kgR = kgsRebajados;
	}else{
		cajaId.value = "0";
		this.htmlCart_items[ indiceItem ].kgR = 0;
		//document.getElementById( cajaId.id ).focus();
	}
	this.htmlCart_items[ indiceItem ].kgTot = (ApplicationCompras.currentInstance.pesoArpilla - this.htmlCart_items[ indiceItem ].kgR) * this.htmlCart_items[ indiceItem ].nA;
	Ext.get("kgPagarCompra_"+indiceItem).update(this.htmlCart_items[indiceItem].kgTot);
	
	this.htmlCart_items[indiceItem].pesoArp = ApplicationCompras.currentInstance.pesoArpilla - this.htmlCart_items[ indiceItem ].kgR;
	this.htmlCart_items[ indiceItem ].subtot = this.htmlCart_items[ indiceItem ].prKg * this.htmlCart_items[ indiceItem ].kgTot;
	Ext.get("subtotalCompra_"+indiceItem).update("$ "+this.htmlCart_items[indiceItem].subtot);
	this.doRefreshItemList;
}

ApplicationCompras.prototype.revisarCantidaPrecio = function( indiceItem ,  precio , cajaId ){

	if(!isNaN(precio))
	{
		this.htmlCart_items[ indiceItem ].prKg = precio;
		this.htmlCart_items[ indiceItem ].subtot = precio * this.htmlCart_items[ indiceItem ].kgTot;
		Ext.get("precioKgCompra_"+indiceItem+"").update("$ "+precio);
		Ext.get("subtotalCompra_"+indiceItem+"").update("$ "+ (precio * (parseFloat(Ext.get("kgPagarCompra_"+indiceItem).getHTML()))));
	}else{
		cajaId.value = "0";
		Ext.get("precioKgCompra_"+indiceItem+"").update("$ 0");
		Ext.get("subtotalCompra_"+indiceItem+"").update("$ 0");
		this.htmlCart_items[ indiceItem ].prKg = 0;
		//document.getElementById( cajaId.id ).focus();
	}
	this.doRefreshItemList;
}

/*--------------------------------------------------------------
	METODO QUE SE USA UNICAMENTE PARA MODO DE COMPRA POR CANIIDAD(GENERAL MODE)
	SIRVE PARA IR VALIDANDO LAS CANTIDADES E IR CALCULANDO LOS TOTALES Y REFRESCANDO
	EL HTML
----------------------------------------------------------------*/

ApplicationCompras.prototype.sumaCantidad = function( indiceItem ,  cantidad , cajaId ){
	
	if(!isNaN(cantidad))
	{
		this.htmlCart_items[ indiceItem ].cantidad = cantidad;
		
	}else{
		cajaId.value = "0";
		this.htmlCart_items[ indiceItem ].cantidad = 0;
		
		//document.getElementById( cajaId.id ).focus();
	}
	var subtotal_compra=0;
	for(i=0; i < this.htmlCart_items.length; i++){
		subtotal_compra += parseFloat(this.htmlCart_items[i].subtot);
	}
	var iva_compra = subtotal_compra * 0;
	
	Ext.get("subtotal_compra").update("$ "+subtotal_compra);
	Ext.get("iva_compra").update("$ "+ iva_compra);
	Ext.get("total_compra").update("$ "+ (subtotal_compra + iva_compra));
	
	this.doRefreshItemList2();
}

/*-----------------------------------------------------------------------
	AGREGA UN PRODUCTO AL CARRO DE COMPRA SEGUN SEA EL MODO DE COMPRA
-------------------------------------------------------------------------*/

ApplicationCompras.prototype.htmlCart_addItem = function( item )
{

	//overrride multiple items
	var MULTIPLE_SAME_ITEMS = false;

	var id = item.id;
	var name = item.name;
	var description = item.description;
	var existencias = item.existencias;
	var costo = item.cost;
	var html = item.html;
	var nA  = item.nA;
	//revisar que no este ya en el carrito
	var found = false;
	for( a = 0; a < this.htmlCart_items.length;  a++){
		if( this.htmlCart_items[ a ].id == id ){
			//item already in cart
			found = true;
			break;
		}
	}
	
	
	if(found && !MULTIPLE_SAME_ITEMS){
		POS.aviso("Comprar Productos", "Este producto ya está en la lista de esta compra.");
		return;
	}
	
	this.htmlCart_items.push(item);
	
	if(!ApplicationCompras.currentInstance._BANDERA){//false = toño mode
		this.doRefreshItemList();
	}else{
		this.doRefreshItemList2();
	}
};

/*---------------------------------------------------------
	HACE LA PETICION AL SERVIDOR, REVISA QUE EXISTA EL PRODUCTO
	Y DEVUELVE EL PRODUCTO PARA AGREGARLO A LA COMPRA SEGUN SEA EL MODO DE COMPRA
------------------------------------------------------------*/


ApplicationCompras.prototype.compras_doAddProduct = function (button, event)
{
	
	if(DEBUG){
		console.log("ApplicationCompras: compras_doAddProduct called....");
	}


	//obtener el id del producto
	var prodID = Ext.get("compras_APaddProductByID").first().getValue();
	
	if(prodID.length == 0){
		var opt = {
		    duration: 2, 
		    easing: 'elasticOut',
		    callback: null,
		    scope: this
		};

		return;
	}
	
	
	//buscar si este producto existe (vender un producto que exista en la sucursal)
	POS.AJAXandDECODE({
			action: '1210',
			id_producto : prodID,
			id_proveedor: ApplicationCompras.currentInstance.idProveedor,
			sucursal_id : 2 //de todos modos del lado del server con SESSION se pesca este dato
		}, 
		function (datos){
			
			//ya llego el request con los datos si existe o no	
			if(!datos.success){
				POS.aviso("Mostrador", datos.reason);
				return;
			}
			
			if(!ApplicationCompras.currentInstance._BANDERA){
			//crear el item
			var item = {//si es false entra a toño mode
				id 			: datos.datos[0].id_producto,
				name 		: datos.datos[0].nombre,
				description : datos.datos[0].denominacion,
				cost 		: datos.datos[0].precio_venta,
				existencias : datos.datos[0].existencias,
				kgR			: 0,
				nA			: 0,
				prKg		: 0,
				htmlCompra	: "",
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
			ApplicationCompras.currentInstance.htmlCart_addItem( item );
			
			//clear the textbox
			Ext.get("compras_APaddProductByID").first().dom.value = "";
			
			//give focus again
			document.getElementById( Ext.get("compras_APaddProductByID").first().id ).focus();

		},
		function (){
			POS.aviso("Error", "Error en la conexion, porfavor intente de nuevo.");
		}
	);
	
};



/* ------------------------------------------------------------------------------------
		vender, VALIDA LA COMPRA (SEGUN SEA EL METODO DE COMPRA), Y SI TODO ESTA BIEN, 
		HACE EL LLAMADO	AL METODO QUE SE COMUNICA CON EL SERVER
   ------------------------------------------------------------------------------------ */


ApplicationCompras.prototype.compras_doVender = function ()
{
	
	if(DEBUG){
		console.log("ApplicationCompras: compras_doVender called....");
	}
	
	
	items = ApplicationCompras.currentInstance.htmlCart_items;

	if(!ApplicationCompras.currentInstance._BANDERA){//validacion en toño mode

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
				return
		}
			if(Ext.getCmp("pesoEmbarque").getValue() == "0"){
				POS.aviso("Comprar Productos", "2) Colocar un numero mayor a 0 en el campo 'Peso en Kg del Embarque'");
				return;
			}
			if(ApplicationCompras.currentInstance.totalArpillas == 0 || ban){
				POS.aviso("Comprar Productos", "3) Llenar las columnas No. Arpillas de todos los productos agregados con un numero mayor a 0");
				return;
			}
			if(ApplicationCompras.currentInstance.totalArpillas > Ext.getCmp("pesoEmbarque").getValue()){
				POS.aviso("Comprar Productos", "4) El valor de 'Peso en Kg del Embarque' debe ser mayor al de 'Numero de Arpillas'");
				return;
			}
			
			if(total < 1 || ban2){
				POS.aviso("Comprar Productos", "5) Revisar en las columnas 'Kg Rebajados' de cada producto que el valor no sea mayor o igual al 'Peso por Arpilla' <br>6) Revisar en las columnas 'Precio x Kg' de cada producto que el valor sea mayor '0'");
				return;
			}
			if(ApplicationCompras.currentInstance.pesoArpilla > Ext.getCmp("pesoEmbarque").getValue()){
				POS.aviso("Comprar Productos", "* El valor de 'Peso en Kg del Embarque' debe ser mayor al de 'Peso por Arpilla'");
				return;
			}
		
		newPanel = ApplicationCompras.currentInstance.doComprarPanel();
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
				return
		}
		if(total < 1 || ban){
			POS.aviso("Comprar Productos", "5) Revisar en las columnas 'Cantidad' de cada producto que el valor sea mayor '0'");
			return;
		}
		
		newPanel = ApplicationCompras.currentInstance.doComprarPanel();
		sink.Main.ui.setCard( newPanel, 'slide' );		
	}
};

/*------------------------------------------------------
	MUESTRA EL PANEL CON EL SUBTOTAL IVA Y TOTAL DE LA VENTA
	Y SE DEFINE SI ES DE CONTADO O A CREDITO
--------------------------------------------------------*/

ApplicationCompras.prototype.doComprarPanel = function ()
{
	
	
	var subtotal = 0;
	var total = 0;
	var iva=0;
	for( a = 0; a < this.htmlCart_items.length;  a++){
		subtotal += parseFloat( this.htmlCart_items[a].subtot );
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
					sink.Main.ui.setCard( ApplicationCompras.currentInstance.comprarMainPanel, 'slide' );
				}
			},{
				xtype:'button', 
				text:'Cancelar Compra',
				handler: function(){
					Ext.get('carritoDeCompras').update("");
					Ext.get('totalArpillas').update("");
					Ext.get('totalCompra').update("");
					if(!ApplicationCompras.currentInstance._BANDERA)
						Ext.getCmp("pesoEmbarque").setValue(0);
					ApplicationCompras.currentInstance.compras_LimpiarCarrito();
					
					sink.Main.ui.setCard( this.comprarMainPanel, 'slide' );
				}
			},{
				xtype:'spacer'
			},{
				xtype:'button', 
				ui: 'action',
				text:'Registrar Compra',
				handler: ApplicationCompras.currentInstance.doCompraLogic

			}]
    })],
	
	//items del formpanel
    items: [{

        xtype: 'fieldset',
        title: 'Compra a proveedor: <b>'+ApplicationCompras.currentInstance.nombreProv+"</b>",
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
				value: 1,
				listeners: 
				{
					change: function(  ){
						var tipo = Ext.getCmp("tipoCompra").getValue(true);
						if(tipo == 0){
							//POS.aviso("message","SOY UNA VENTA DE CONTADO COMPA");
							try{
								Ext.get("abonoCompra").hide();
							}catch(e){}
						}else{
							//POS.aviso("message","SOY UNA VENTA A CREDITO");
							try{
							Ext.get("abonoCompra").show();
							}catch(e){}
						}
					}
				}
            },{
			    xtype: 'textfield',
			    label: 'Abono',
				value: 0,
				id: 'abonoCompra',
				listeners:{
					change: function(){
						valor= Ext.getCmp("abonoCompra").getValue(true);
						if(isNaN(valor)){
							Ext.getCmp("abonoCompra").setValue(0);
							document.getElementById( "abonoCompra" ).focus();
						}
					}
				}
			}
			]
		}]
	});
};

/*-----------------------------------------------------------------
	SE ENVIA PETICION AL SERVER PARA REGISTRAR LA COMPRA EN LA BD
-------------------------------------------------------------------*/

ApplicationCompras.prototype.doCompraLogic = function ()
{
	//insercion en la BD
	var jsonItems = Ext.util.JSON.encode(ApplicationCompras.currentInstance.htmlCart_items);

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
			id_proveedor : ApplicationCompras.currentInstance.idProveedor,//aqui el id del proveedor
			tipo_compra : tipoCompra,
			modo_compra	: ApplicationCompras.currentInstance._BANDERA //false toño mode, true general mode
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
	var thksPanel = ApplicationCompras.currentInstance.compras_doGraciasPanel();
	sink.Main.ui.setCard( thksPanel, 'fade' );
};

/*-----------------------------------------------------------
	SE REALIZÓ LA COMPRA Y MUESTRA PANEL FINAL
-------------------------------------------------------------*/
ApplicationCompras.prototype.compras_doGraciasPanel = function ()
{
	
	
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
					Ext.get('carritoDeCompras').update("");
					Ext.get('totalArpillas').update("");
					Ext.get('totalCompra').update("");
					Ext.getCmp("pesoEmbarque").setValue(0);
					ApplicationCompras.currentInstance.compras_LimpiarCarrito();
					
					sink.Main.ui.setCard( ApplicationProveedores.currentInstance.mainCard, 'slide' );
				}
			},{
				xtype:'spacer'
			},{
				xtype:'button', 
				ui: 'action',
				text:'Comprar a mismo Proveedor',
				handler: function(){
					Ext.get('carritoDeCompras').update("");
					Ext.get('totalArpillas').update("");
					Ext.get('totalCompra').update("");
					Ext.getCmp("pesoEmbarque").setValue(0);
					ApplicationCompras.currentInstance.compras_LimpiarCarrito();
					
					sink.Main.ui.setCard( ApplicationCompras.currentInstance.comprarMainPanel, 'slide' );
				}
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








//autoinstalar esta applicacion
//AppInstaller( new ApplicationCompras() );



