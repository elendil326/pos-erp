ApplicationVender = function ()
{
	if(DEBUG){
		console.log("ApplicationVender: construyendo");
	}
	
	this._init();
	
	ApplicationVender.currentInstance = this;
	
	return this;
};


/* 
	CONSTANTES DE LA APLICACIN
*/
var MOSTRADOR_IVA = .15;

var MULTIPLE_SAME_ITEMS = true;






/*
 	CLASS MEMBERS
*/
//aqui va el panel principal 
ApplicationVender.prototype.mainCard = null;

//aqui va el nombre de la applicacion
ApplicationVender.prototype.appName = null;

//aqui van los items del menu de la izquierda
ApplicationVender.prototype.leftMenuItems = null;

//aqui va un texto de ayuda en html
ApplicationVender.prototype.ayuda = null;

//dockedItems
ApplicationVender.prototype.dockedItems = null;

//Informacion del cliente
ApplicationVender.prototype.cliente = null;




ApplicationVender.prototype.panelContado = null;

ApplicationVender.prototype.panelCredito = null;

ApplicationVender.prototype._init = function()
{
	
	//nombre de la aplicacion
	this.appName = "Mostrador";
	
	//ayuda sobre esta applicacion
	this.ayuda = "esto es una ayuda sobre este modulo de compras, html es valido <br> :D";
	
	//submenues en el panel de la izquierda
	this.leftMenuItems = null;


	//panel principal	
	this.mainCard = this.venderMainPanel;

	//initialize the tootlbar which is a dock
	this._initToolBar();

	
};










/* ------------------------------------------------------------------------------------
					tool bar
   ------------------------------------------------------------------------------------ */

ApplicationVender.prototype._initToolBar = function (){


	//grupo 1, agregar producto
	var buttonsGroup1 = [
		{
			xtype: 'textfield',
			id: 'APaddProductByID',
			startValue: 'ID del producto',
			listeners:
					{
						'afterrender': function( ){
							//focus
							document.getElementById( Ext.get("APaddProductByID").first().id ).focus();
							
							//medio feo, pero bueno
							Ext.get("APaddProductByID").first().dom.setAttribute("onkeyup","ApplicationVender.currentInstance.addProductByIDKeyUp( this, this.value )");
							
						}
					}
		},{
        	text: 'Agregar producto',
        	ui: 'round',
        	handler: this.doAddProduct
    	}];


	//grupo 2, caja comun o cliente
	var buttonsGroup2 = [{
        xtype: 'splitbutton',
		id:'_cliente_cajacomun_btn',
		activeItem: '0',
        items: [{
            text: 'Caja Comun',
			listeners:{
				render: function (a){
					Ext.getCmp("_cliente_cajacomun_btn").setActive(0);
				}
			},
			handler : function (){
					ApplicationVender.currentInstance.swapClienteComun(1);
			}
        }, {
            text: 'Cliente',
			handler : function (){
					ApplicationVender.currentInstance.swapClienteComun(0);
			}
        }]    
    }];


	//grupo 3, listo para vender
    var buttonsGroup3 = [{
        text: 'Limpiar',
        handler: this.doLimpiarCarrito
    },{
        text: 'Cotizar',
        handler: this.doCotizar
    },{
        text: 'Vender',
        ui: 'action',
		id: 'doVenderButton',
        handler: this.doVender
    }];


	if (!Ext.platform.isPhone) {
		
        buttonsGroup1.push({xtype: 'spacer'});
        buttonsGroup2.push({xtype: 'spacer'});
        
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




ApplicationVender.prototype.addProductByIDKeyUp = function (a, b)
{

	if(event.keyCode == 13)
	{
		//si teclea enter, pero hay un pop up visible, ocultarlo con este enter
		if(POS.aviso.visible){
			//close current pop up
			POS.aviso.hide();
		}else{
			
			//add product
			ApplicationVender.currentInstance.doAddProduct();			
		}

	}
};





/* ------------------------------------------------------------------------------------
					main card
   ------------------------------------------------------------------------------------ */

ApplicationVender.prototype.venderMainPanel = new Ext.Panel({
    scroll: 'none',

	dockedItems: null,
	
	cls: "ApplicationVender-mainPanel",
	
	//items del formpanel
    items: [{
			html: '',
			id : 'detallesCliente'
		},{
			html: '',
			id : 'carritoDeCompras'
		}]
});



















/* ------------------------------------------------------------------------------------
					carrito de compras
   ------------------------------------------------------------------------------------ */
ApplicationVender.prototype.htmlCart_items = [];


ApplicationVender.prototype.doLimpiarCarrito = function ( )
{
	
	var items = ApplicationVender.currentInstance.htmlCart_items;
	
	if( items.length != 0){
		while(items.length != 0){
			items.pop();
		}
		
		ApplicationVender.currentInstance.doRefreshItemList();
	}

	ApplicationVender.currentInstance.swapClienteComun(1);
	
};






ApplicationVender.prototype.doDeleteItem = function ( item )
{
	
	this.htmlCart_items.splice(item, 1);
	
	this.doRefreshItemList();
	
};





ApplicationVender.prototype.doRefreshItemList = function (  )
{
	
	if( this.htmlCart_items.length == 0){
		Ext.get("carritoDeCompras").update("");
		return;
	}
	
	
	var html = "";
	
	
	
	// cabezera
	html += "<div class='ApplicationVender-item' >" 
	+ "<div class='trash' ><img height=20 width=20 src='sencha/resources/img/toolbaricons/trash.png'></div>"
	+ "<div class='id'>ID</div>" 
	+ "<div class='name'>Nombre</div>" 
	+ "<div class='description'>Descripcion</div>" 
	+ "<div class='cost'>Precio</div>"
	+ "<div class='qty_dummy'>&nbsp;</div>"
	+ "<div class='cantidad'>Cantidad</div>"
	+ "<div class='qty_dummy'>&nbsp;</div>"
	+ "<div class='importe'>Importe</div>"
	+ "</div>";
	
	// items
	for( a = 0; a < this.htmlCart_items.length; a++ ){


		html += "<div class='ApplicationVender-item' >" 
		+ "<div class='trash' onclick='ApplicationVender.currentInstance.doDeleteItem(" +a+ ")'><img height=20 width=20 src='sencha/resources/img/toolbaricons/trash.png'></div>"	
		+ "<div class='id'>" + this.htmlCart_items[a].id +"</div>" 
		+ "<div class='name'>" + this.htmlCart_items[a].name +"</div>" 
		+ "<div class='description'>"+ this.htmlCart_items[a].description +"</div>" 
		+ "<div class='cost'>"+ this.htmlCart_items[a].cost +"</div>"
		+ "<div class='qty_change'>-</div>"		
		+ "<div class='cantidad' onclick='ApplicationVender.currentInstance.doCambiarCantidad("+a+")'>"+ this.htmlCart_items[a].cantidad +"</div>"
		+ "<div class='qty_change'>+</div>"		
		+ "<div class='importe'>"+ this.htmlCart_items[a].cost +"</div>"
		+ "</div>";
	}





	//preparar un html para los totales
	var totals_html = "";


	var subtotal = 0;
		
	//calcular totales
	for( a = 0; a < this.htmlCart_items.length;  a++){
		subtotal += parseInt( this.htmlCart_items[a].cost );
	}


	totals_html = "<span>Subtotal " +  POS.currencyFormat(subtotal) + "</span> "
				+ "<span>IVA " +  POS.currencyFormat(subtotal*.15) + "</span> "
				+ "<span>Total " +  POS.currencyFormat((subtotal*.15)+subtotal) + "</span>";
					

	// wrap divs
	html = "<div class='ApplicationVender-itemsBox'>" + html +"</div>" ;
	totals_html = "<div class='ApplicationVender-totalesBox' >" + totals_html +"</div>" ;
	
	var endhtml = html + totals_html;

	Ext.get("carritoDeCompras").update("<div >" + endhtml + "</div>");
	
	

};


ApplicationVender.prototype.doCambiarCantidad = function(item)
{
	
	if (Ext.getCmp('ApplicationVender-doCambiarCantidad-panel') == null ) {
		var cantidadToolbar = new Ext.Toolbar({
			title: 'Cantidad',
			dock: 'top',
			items: [{
				xtype: 'spacer'
			}, {
				xtype: 'button',
				text: 'Aceptar',
				ui: 'action',
				handler: function(){
					
					var spinValue = Ext.getCmp('ApplicationVender-doCambiarCantidad-cantidad').getValue();

					if(spinValue.length > 0){
					
						spinValue = parseFloat(spinValue);

						if(isNaN(spinValue)){
							cantidadPanel.hide();
							cantidadPanel.destroy();
							return;
						}

						if(spinValue <= 0){
							
							cantidadPanel.hide();
							cantidadPanel.destroy();
							return;
						}
						

						ApplicationVender.currentInstance.htmlCart_items[item].cantidad = spinValue;						
					}

					cantidadPanel.hide();
					cantidadPanel.destroy();
					
					ApplicationVender.currentInstance.doRefreshItemList();
				}
			}]
		});
	
		
		var cantidadPanel = new Ext.Panel({
			id: 'ApplicationVender-doCambiarCantidad-panel',
			floating: true,
			modal: true,
			centered: true,
			height: 150,
			width: 400,
			dockedItems: cantidadToolbar,
			items: [new Ext.form.FormPanel({
				items: [{
					activeItem: 0,
					xtype: 'fieldset',
					label: 'Cantidad',
					items: [{
						id: 'ApplicationVender-doCambiarCantidad-cantidad',
						xtype: 'spinnerfield',
						label: 'Cantidad',
						name: 'cantidad',
						defaultValue: 1,
						minValue: 1,
					}]
				}]
			})]
		});
	}
	
	Ext.getCmp('ApplicationVender-doCambiarCantidad-panel').show();
	
	
	
};


ApplicationVender.prototype.htmlCart_addItem = function( item )
{



	var id = item.id;
	var name = item.name;
	var description = item.description;
	var existencias = item.existencias;
	var costo = item.cost;
	var cantidad = item.cantidad;

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
		POS.aviso("Mostrador", "Ya ha agregado este producto.");
		return;
	}
	
	this.htmlCart_items.push(item);
	
	this.doRefreshItemList();
};




ApplicationVender.prototype.doAddProduct = function (button, event)
{
	
	if(DEBUG){
		console.log("ApplicationVender: doAddProduct called....");
	}


	//obtener el id del producto
	var prodID = Ext.get("APaddProductByID").first().getValue();
	
	if(prodID.length == 0){
		var opt = {
		    duration: 2,
		    easing: 'elasticOut',
		    callback: null,
		    scope: this
		};

		return;
	}
	
	//buscar si este producto existe
	POS.AJAXandDECODE({
			method: 'existenciaProductoSucursal',
			id_producto : prodID
		}, 
		function (datos){
			
			//ya llego el request con los datos si existe o no	
			if(!datos.success){
				POS.aviso("Mostrador", datos.reason);
				
				//clear the textbox
				Ext.get("APaddProductByID").first().dom.value = "";
				
				return;
			}
			
			//crear el item
			var item = {
				id 			: datos.datos[0].id_producto,
				name 		: datos.datos[0].nombre,
				description : datos.datos[0].denominacion,
				cost 		: datos.datos[0].precio_venta,
				existencias : datos.datos[0].existencias,
				cantidad	: 1
			};
				
			//agregarlo al carrito
			ApplicationVender.currentInstance.htmlCart_addItem( item );
			
			//clear the textbox
			Ext.get("APaddProductByID").first().dom.value = "";
			
			//give focus again
			document.getElementById( Ext.get("APaddProductByID").first().id ).focus();

		},
		function (){
			POS.aviso("Error", "Algo anda mal, porfavor intente de nuevo.");
		}
	);
	
	
};












/* ------------------------------------------------------------------------------------
					vender
   ------------------------------------------------------------------------------------ */


ApplicationVender.prototype.doVender = function ()
{
	
	if(DEBUG){
		console.log("ApplicationVender: doVender called....");
	}
	
	
	items = ApplicationVender.currentInstance.htmlCart_items;
	
	//revisar que exista por lo menos un item
	if(items.length == 0){
		POS.aviso("Mostrador", "Agregue al menos un artículo para poder vender.");
		return;
	}
	
	var subtotal = 0;
	var total = 0;
	
	for( a = 0; a < items.length;  a++){
		subtotal += parseFloat( items[a].cost );
	}
	
	total = (subtotal* MOSTRADOR_IVA) + subtotal;
	
	
	
	
	/*
		listo para hacer la venta
	*/
	ApplicationVender.currentInstance.doVentaForms();


};


ApplicationVender.prototype.payingMethod = null;

ApplicationVender.prototype.doVentaForms = function()
{
	if(this.panelContado === null){
		this.panelContado = this.doVentaContadoPanel();
	}else{
		this.panelContado.destroy();
		this.panelContado = this.doVentaContadoPanel();
	}
	
	if(!this.CLIENTE_COMUN && this.panelCredito === null){
		this.panelCredito = this.doVentaCreditoPanel();			
	}else{
		
	}
	
	this.payingMethod = 'contado';
	sink.Main.ui.setCard( this.panelContado, 'slide' );
};


ApplicationVender.prototype.swapPayingMethod = function ( tipo )
{
	if(DEBUG){
		console.log("Mostrador: cambiando modo de pago a ", tipo.tipo);
	}
	
	var app = ApplicationVender.currentInstance;
	
	switch (tipo.tipo){
		case 'credito' : 
			sink.Main.ui.setCard( app.panelCredito, {type:'fade', duration: 600} );                                  
			break;
			
		case 'contado' : 
			sink.Main.ui.setCard( app.panelContado, {type:'fade', duration: 600} );
			break;
	}
};


ApplicationVender.prototype.doVentaContadoPanel = function (  )
{

	var subtotal = 0;
	var total = 0;
	
	for( a = 0; a < this.htmlCart_items.length;  a++){
		subtotal += parseInt( this.htmlCart_items[a].cost );
	}
	
	total = (subtotal*.15) + subtotal;
	
	
	
	//docked items
	var dockedItems = [{
			xtype:'button', 
			text:'Regresar',
			ui: 'back',
			handler : function () {
				sink.Main.ui.setCard( ApplicationVender.currentInstance.venderMainPanel, 'slide' );
			}
		},{
			xtype:'button', 
			text:'Cancelar Venta',
			handler : function (){
				ApplicationVender.currentInstance.doLimpiarCarrito();
				sink.Main.ui.setCard( ApplicationVender.currentInstance.venderMainPanel, 'fade' );
			}
		},{
			xtype:'spacer'
		}];
	
	//si no es cliente comun, agregar la opcion para pagar con credito
	if(!this.CLIENTE_COMUN){
		dockedItems.push({
				xtype:'button',
				tipo: 'credito',
				ui: 'action',
				text:'Comprar a Credito',
				handler : ApplicationVender.currentInstance.swapPayingMethod
			})
	}
	
	//cobrar
	dockedItems.push({
			xtype:'button', 
			ui: 'action',
			text:'Cobrar',
			handler: ApplicationVender.currentInstance.doVentaLogic

		});
	
	return new Ext.Panel({

    scroll: 'none',

	cls: "ApplicationVender-mainPanel",
	
	html : '<div class="helperMostrador"></div>',

	//toolbar
	dockedItems: [new Ext.Toolbar({
        ui: 'dark',
        dock: 'bottom',
        items: dockedItems
    	})],
	
	//items del formpanel
    items: [{

        xtype: 'fieldset',
        title: 'Venta a contado',
		baseCls: "ApplicationVender-ventaListaPanel",
        items: [{
	        	xtype: 'textfield',
	        	label: 'Subtotal',
				value : 0,//POS.currencyFormat(subtotal),
				disabled: true
       		},{
		        xtype: 'textfield',
		        label: 'IVA',
				value : '15%',
				disabled: true
	      	},{
			    xtype: 'textfield',
			    label: 'Total',
				value : 0,//POS.currencyFormat(total),
				disabled: true
		    },{
			    xtype: 'textfield',
			    label: 'Pago',
				value: 0,//POS.currencyFormat(cantidadPago),
				disabled: false
			},{
				xtype: 'textfield',
				label: 'Cambio',
				value: 0,//POS.currencyFormat(cantidadPago-total),
				disabled: true
			}]
		}]
	});
};


ApplicationVender.prototype.doVentaCreditoPanel = function ( cantidadPago )
{


	
	return new Ext.Panel({

    scroll: 'none',

	cls: "ApplicationVender-mainPanel",

	//toolbar
	dockedItems: [new Ext.Toolbar({
        ui: 'dark',
        dock: 'bottom',
        items: [{
				xtype:'button', 
				text:'Regresar',
				ui: 'back'
			},{
				xtype:'button', 
				text:'Cancelar Venta'
			},{
				xtype:'spacer'
			},{
				xtype:'button', 
				ui: 'action',
				text:'Comprar a Contado',
				tipo : 'contado',
				handler : ApplicationVender.currentInstance.swapPayingMethod
				
			},{
				xtype:'button', 
				ui: 'action',
				text:'Vender',
				handler: ApplicationVender.currentInstance.doVentaLogic

			}]
    	})],
	
	//items del formpanel
    items: [{

        xtype: 'fieldset',
        title: 'Venta a credito',
		baseCls: "ApplicationVender-ventaListaPanel",
        items: [{
	        	xtype: 'textfield',
	        	label: 'Subtotal',
				value : 0,//POS.currencyFormat(subtotal),
				disabled: true
       		},{
		        xtype: 'textfield',
		        label: 'IVA',
				value : MOSTRADOR_IVA,
				disabled: true
	      	},{
			    xtype: 'textfield',
			    label: 'Total',
				value : 0,//POS.currencyFormat(total),
				disabled: true
		    },{
			    xtype: 'textfield',
			    label: 'Limite de credito',
				value: 0,//POS.currencyFormat(cantidadPago),
				disabled: true
			},{
				xtype: 'textfield',
				label: 'Cambio',
				value: 0,//POS.currencyFormat(cantidadPago-total),
				disabled: true
			}]
		}]
	});
};


ApplicationVender.prototype.doVentaLogic = function ()
{
	//Ajax para guardar la venta en la BD
	
	var jsonItems = Ext.util.JSON.encode(ApplicationVender.currentInstance.htmlCart_items);
	console.log(jsonItems);
	
	POS.AJAXandDECODE(
					//Parametros
					{
						method: 'insertarVenta',
						id_cliente: ApplicationVender.currentInstance.cliente.iden,
						tipo_venta: 1,
						jsonItems: jsonItems
					},
					//Funcion success
					function(result){
						console.log(result);
						
						if (result.success)
						{
							//Termina la venta y se agredece :)
							var thksPanel = ApplicationVender.currentInstance.doGraciasPanel();
							sink.Main.ui.setCard( thksPanel, 'fade' );							
						}
					},
					//Funcion failure
					function(){
						console.warn("ApplicationVender: Error al realizar la venta");
					}
	);
	
	
	
};


ApplicationVender.prototype.doGraciasPanel = function ()
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
				xtype:'spacer'
			},{
				xtype:'button', 
				ui: 'action',
				text:'Nueva venta'
			}]
    })],
	
	//items del formpanel
    items: [{
			html : 'Gracias !',
			cls  : 'gracias',
			baseCls: "ApplicationVender-ventaListaPanel",
		}]
	});
};


//Funcion para mostrarle un overlay para recibir la cantidad con la que se pagará
ApplicationVender.prototype.askForMoney = function(totalPagar){
	
	var pagoToolbar = new Ext.Toolbar({
		title: 'Pago',
		dock: 'top',
		items: [{
				xtype: 'button',
				text: 'Crédito',
				handler: function(){
					//Comprobar aca si no ha rebasado su limite de credito
				}
			}, { xtype: 'spacer' } ,
			{
				xtype: 'button',
				ui: 'action',
				text: 'Aceptar',
				handler: function(){
				
					//Comprobamos que se ingrese una cantidad suficiente para pagar	
					var cantidadPago = Ext.getCmp('ApplicationVender-askForMoney-cantidad').getValue();
					if( totalPagar > cantidadPago){
						alert("Falta dinero");
						return false;
					}
				
					pagoOverlay.hide();
					
					Ext.getCmp('ApplicationVender-askForMoney-cantidad').reset();
					var cantidadPago = Ext.getCmp('ApplicationVender-askForMoney-cantidad').getValue();
					newPanel = ApplicationVender.currentInstance.doVenderPanel(cantidadPago);
					sink.Main.ui.setCard(newPanel, 'slide');
				}
			}]
	});
	
	var pagoOverlay = new Ext.Panel({
		id: 'ApplicationVender-askForMoney-pagoOverlay',
		floating: true,
		modal: true,
		centered: true,
		width: Ext.platform.isPhone ? 260 : 400,
		height: Ext.platform.isPhone ? 220 : 200,
		dockedItems: pagoToolbar,
		items: [ new Ext.form.FormPanel({
			items:[{
				activeItem: 0,
				xtype: 'fieldset',
				label: 'Pago',
				items:[{
					id: 'ApplicationVender-askForMoney-cantidad',
					xtype: 'textfield',
					label: 'Cantidad',
					name: 'cantidad'
				}]
			}]
		})]
		
	});
	
	pagoOverlay.show();
	
	var funcion = String.format("ApplicationVender.currentInstance.enterOnKeyUp( this, this.value, {0})", totalPagar);
	
	//medio feo, pero bueno
	Ext.get("ApplicationVender-askForMoney-cantidad").dom.childNodes[1].setAttribute("onkeyup",funcion);
	
	//Focus a cantidad
	document.getElementById(Ext.get('ApplicationVender-askForMoney-cantidad').dom.childNodes[1].id).focus();
	
	
};

ApplicationVender.prototype.enterOnKeyUp = function (a, b, total)
{
	if(event.keyCode == 13){
		
		//Comprobamos que se ingrese una cantidad suficiente para pagar	
		var cantidadPago = Ext.getCmp('ApplicationVender-askForMoney-cantidad').getValue();
		if( total > cantidadPago){
			alert("Falta dinero");
			return false;
		}
		
		
		Ext.getCmp('ApplicationVender-askForMoney-pagoOverlay').hide();
		
		Ext.getCmp('ApplicationVender-askForMoney-cantidad').reset();
		newPanel = ApplicationVender.currentInstance.doVenderPanel(b);
		sink.Main.ui.setCard(newPanel, 'slide');
		
	}
};




/* ------------------------------------------------------------------------------------
					cotizar
   ------------------------------------------------------------------------------------ */
ApplicationVender.prototype.doCotizar = function ()
{
	
	if(DEBUG){
		console.log("ApplicationVender: doCotizar called....");
	}
	
	items = ApplicationVender.currentInstance.htmlCart_items;
	
	//revisar que exista por lo menos un item
	if(items.length == 0){
		POS.aviso("Mostrador", "Agregue primero al menos un arituclo para poder cotizar.");
		return;
	}
};







ApplicationVender.prototype.CLIENTE_COMUN = true;

/* ------------------------------------------------------------------------------------
					buscar cliente
   ------------------------------------------------------------------------------------ */
ApplicationVender.prototype.swapClienteComun = function (val)
{		
	if(val==0){
		//buscar cliente
		ApplicationVender.currentInstance.buscarCliente();

		ApplicationVender.currentInstance.CLIENTE_COMUN = false;

	}else{
		Ext.getCmp("_cliente_cajacomun_btn").setActive(0);
		Ext.get("detallesCliente").update("");
		this.cliente = null;
		ApplicationVender.currentInstance.CLIENTE_COMUN = true; 	
	}
};


ApplicationVender.prototype.actualizarDetallesCliente = function ( cliente )
{
	//si ya vamos a actualizar cliente entonces es porque todo salio bien, entonces.... agregar la opcion de facturar
	//ApplicationVender.currentInstance.mainCard.items.items[2].add(  {xtype: 'toggle', id: 'ventaFacturar', value: 0, label: 'Facturar'}   );
	//ApplicationVender.currentInstance.mainCard.doLayout();
	
	//mostrar los detalles del cliente
	
	this.cliente = cliente;
	
	var html = "";
	html += " <div class='ApplicationVender-clienteBox'> ";
		html += " <div class='nombre'>" + cliente.nombre + "</div>";
		html += " <div class='ApplicationVender-clienteDetalle'> RFC " + cliente.rfc + "</div>";
		html += " <div class='ApplicationVender-clienteDetalle'> Direccion " + cliente.direccion + "</div>";
		html += " <div class='ApplicationVender-clienteDetalle'> Telefono " + cliente.telefono + "</div>";
		html += " <div class='ApplicationVender-clienteDetalle'> Correo Electronico " + cliente.e_mail + "</div>";
		html += " <div class='ApplicationVender-clienteDetalle'> Limite de Credito " + cliente.limite_credito + "</div>";
	html += " </div> ";
	
	
	Ext.get("detallesCliente").update( html );
};





ApplicationVender.prototype.buscarCliente = function ()
{
	//retrive client list from server
	POS.AJAXandDECODE({
			method : "listarClientes"
		},
		function(response){
			
			//success
			if(!response.success){
				POS.aviso("Mostrador", "Error al traer la lista de clintes.");
				return;
			}


			//createArray for client data
			var clientesData = [];
			
			
			//fill array
			for(a = 0; a < response.datos.length ; a++){
				clientesData.push( {
					iden: 		response.datos[a].id_cliente, 
					nombre: 	response.datos[a].nombre, 
					rfc: 		response.datos[a].rfc,
					direccion: 	response.datos[a].direccion,
					telefono: 	response.datos[a].telefono,
					e_mail: 	response.datos[a].e_mail,
					limite_credito:response.datos[a].limite_credito
					
					});
			}




			//create regmodel para busqueda por nombre
			Ext.regModel('ApplicationVender_nombre', {
		                    fields: [ 'nombre']
		    });
		
			//create regmodel para busqueda por rfc
			Ext.regModel('ApplicationVender_rfc', {
		                    fields: [ 'rfc', 'nombre']
		    });
		
			//create regmodel para busqueda por direccion
			Ext.regModel('ApplicationVender_direccion', {
		                    fields: [ 'direccion' ]
		    });

			//create the actual store
			var clientesStore = new Ext.data.Store({
                model: 'ApplicationVender_' + ApplicationVender.currentInstance.buscarClienteFormSearchtype,
                sorters: ApplicationVender.currentInstance.buscarClienteFormSearchtype,
                getGroupString : function(record) {
                    return record.get( ApplicationVender.currentInstance.buscarClienteFormSearchtype )[0];
                },
                data: clientesData
            });

		 	//send the store to the client searching form
			ApplicationVender.currentInstance.buscarClienteShowForm( clientesStore );
			
		},
		function(){
			//failure
		});

};



ApplicationVender.prototype.buscarClienteFormSearchtype = 'nombre';



//thi shit wont work

ApplicationVender.prototype.buscarClienteFormSearchTemplate = function ()
{
	
	switch(ApplicationVender.currentInstance.buscarClienteFormSearchtype){
		case 'nombre': 		return '<tpl for="."><div class="contact"><strong>{nombre}</strong>{rfc},{direccion}</div></tpl>';
		case 'rfc': 		return '<tpl for="."><div class="contact"><strong>{rfc}</strong> {nombre}</div></tpl>';		
		case 'direccion': 	return '<tpl for="."><div class="contact">{direccion}<strong> {nombre}</strong></div></tpl>';
	}
};



var alanboy = '<tpl for="."><div class="contact"><strong>{nombre}</strong> {rfc} {direccion}</div></tpl>';


ApplicationVender.prototype.buscarClienteShowForm = function ( clientesStore )
{
	
        var formBase = {
			//	items
            items: [{
				loadingText: 'Cargando datos...',
		        width: "100%",
		        height: "100%",
				id: 'buscarClientesLista',
		        xtype: 'list',
		        store: clientesStore,
		        tpl: ApplicationVender.currentInstance.buscarClienteFormSearchTemplate() , //al hacer refresh() la lista no actualiza el tpl, creo que lo tendre que hacer con CSS
				itemSelector: 'div.contact',
		        singleSelect: true,
		        grouped: true,
		        indexBar: true
		    }],
		
		
			//	dock		
            dockedItems: [{
                    xtype: 'toolbar',
                    dock: 'bottom',
                    items: [{
				        xtype: 'splitbutton',
				        activeButton: "1",
				        items: [{
				            text: 'Nombre',
				            handler: function (){
								//Ext.getCmp("buscarClientesLista")
								ApplicationVender.currentInstance.buscarClienteFormSearchtype = "nombre";
								Ext.getCmp("buscarClientesLista").refresh();
							}
				        }, {
				            text: 'RFC',
				            handler: function (){

								ApplicationVender.currentInstance.buscarClienteFormSearchtype = "rfc";
								Ext.getCmp("buscarClientesLista").refresh();
							}
				        }, {
				            text: 'Direccion',
				            handler: function (){

								ApplicationVender.currentInstance.buscarClienteFormSearchtype = "direccion";
								Ext.getCmp("buscarClientesLista").refresh();
							}
				        }]    
				    },{
						xtype: 'spacer'
					},{
						//-------------------------------------------------------------------------------
						//			cancelar
						//-------------------------------------------------------------------------------
						text: 'Cancelar',
						handler: function() {
							alanboy
							//ocultar esta tabla
							form.hide();							
							//cambiar el boton
							ApplicationVender.currentInstance.swapClienteComun(1);
							
							//destruir la lista
							if( Ext.getCmp('buscarClientesLista') ){
									Ext.getCmp('buscarClientesLista').store = null;
									Ext.getCmp('buscarClientesLista').destroy();
								}
								
                            }
					},{
						//-------------------------------------------------------------------------------
						//			seleccionar	
						//-------------------------------------------------------------------------------
	                    text: 'Seleccionar',
	                    ui: 'action',
	                    handler: function() {

						
							if(Ext.getCmp("buscarClientesLista").selected.elements.length == 0){
								//no haseleccionado a nadie
								return;
							
							}
						
							//imprimir los detalles del cliente en la forma principal
							ApplicationVender.currentInstance.actualizarDetallesCliente( Ext.getCmp("buscarClientesLista").getSelectedRecords()[0].data )
						
							//hide the form
							form.hide();
						
						
							//destruir la lista
							if( Ext.getCmp('buscarClientesLista') ){
									Ext.getCmp('buscarClientesLista').store = null;
									Ext.getCmp('buscarClientesLista').destroy();
								}
	                   }
					
                    }]
                }
            ]
        };





        
        if (Ext.platform.isAndroidOS) {
            formBase.items.unshift({
                xtype: 'component',
                styleHtmlContent: true,
                html: '<span style="color: red">Forms on Android are currently under development. We are working hard to improve this in upcoming releases.</span>'
            });
        }
        



        if (Ext.platform.isPhone) {
            formBase.fullscreen = true;
        } else {
            Ext.apply(formBase, {
                autoRender: true,
                floating: true,
                modal: true,
                centered: true,
                hideOnMaskTap: false,
                height: 585,
                width: 680
            });
        }
        


        var form = new Ext.Panel(formBase);

        form.show();
	

};




ApplicationVender.prototype.ventaLista = new Ext.Panel({
	
});



//autoinstalar esta applicacion
AppInstaller( new ApplicationVender() );



