ApplicationVender = function ()
{
	if(DEBUG){
		console.log("ApplicationVender: construyendo");
	}
	
	this._init();
	
	ApplicationVender.currentInstance = this;
	
	return this;
};








//aqui va el panel principal 
ApplicationVender.prototype.mainCard = null;

//aqui va el nombre de la applicacion
ApplicationVender.prototype.appName = null;

//aqui va el nombre de la applicacion
ApplicationVender.prototype.leftMenuItems = null;

//aqui va un texto de ayuda en html
ApplicationVender.prototype.ayuda = null;

//dockedItems
ApplicationVender.prototype.dockedItems = null;





ApplicationVender.prototype._init = function()
{

	
	
	//iniciar variables
	
	//nombre de la aplicacion
	this.appName = "Mostrador";
	
	//ayuda sobre esta applicacion
	this.ayuda = "esto es una ayuda sobre este modulo de compras, html es valido <br> :D";
	
	//submenues en el panel de la izquierda

	//this.leftMenuItems = 


	//panel principal	
	this.mainCard = this.venderMainPanel;

	//initialize the tootlbar which is a dock
	this._initToolBar();
	
	

	
	
	
};












ApplicationVender.prototype._initToolBar = function (){


	//grupo 1, agregar producto
	var buttonsGroup1 = [{
		  xtype: 'textfield',
		  id: 'APaddProductByID',
		  startValue: 'ID del producto',
			listeners:
					{
						keydown: function( ){
							console.log("hola");

						}
					}
		},{
        text: 'Agregar producto',
        ui: 'round',
        handler: this.doAddProduct
    }];


	//grupo 2, cualquier otra cosa
	var buttonsGroup2 = [];
    



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
            // dock this toolbar at the bottom
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
	
	//----------------------------------------------------------------------
	//grupo 1
	buttonsGroup1 = [{
        xtype: 'splitbutton',
		activeItem: 0,
		id: 'av_request_notes',
		allowMultiple: true,
		listeners:
				{
					render: function( a ){

					}
				},
        items: [{
            text: 'Nota'
        }, {
            text: 'Factura',
			id :'av_request_factura',
			handler: function (){
				
			}

        }]    
    }];


	//grupo 2
	buttonsGroup2 = [{
        xtype: 'splitbutton',
		id:'av_top_nota_factura',
		activeItem: 0,
		listeners:
				{
					render: function( a ){

					}
				},
        items: [{
            text: 'Caja Comun',
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
    

	if (!Ext.platform.isPhone) {
        buttonsGroup1.push({xtype: 'spacer'});
        
        this.dockedItemsTop = [ new Ext.Toolbar({
			scroll: 'horizontal',
            // dock this toolbar at the bottom
            ui: 'light',
            dock: 'top',
            items: buttonsGroup1.concat(buttonsGroup2)
        })];

    }else {
        this.dockedItemsTop = [{
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
	this.mainCard.addDocked( this.dockedItemsTop );

};










//----------------------------------------------------------------------------------------------------------------------------------------------
//					main card
//----------------------------------------------------------------------------------------------------------------------------------------------
ApplicationVender.prototype.venderMainPanel = new Ext.form.FormPanel({
	//tipo de scroll
    scroll: 'vertical',

	//toolbar
	dockedItems: null,
	
	//items del formpanel
    items: [{
		// ---- item-0 ---- 
        	xtype: 'fieldset',
        	title: 'Detalles del cliente',
        	items: []
    	},{
		// ---- item-1 ---- 
			html: '<div class="helper1"></div>',
			id : 'detallesCliente'
		},{
		// ---- item-2 ---- 
			xtype: 'fieldset',
        	title: 'Detalles de la Venta',
        	defaults: {
            	xtype: 'radio',
        	},
        	items: []
    	},{
		// ---- item-3 ---- 
			html: '',
			id : 'carritoDeCompras'
		},{
		// ---- item-4 ---- 
			html: '',
			id : 'carritoDeComprasTotales'
		}]
});





//----------------------------------------------------------------------------------------------------------------------------------------------
//					lista de carrito de compras
//----------------------------------------------------------------------------------------------------------------------------------------------
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
		Ext.get("carritoDeComprasTotales").update("");
		return;
	}
	
	var html = "";
	
	//renderear el html
	for( a = 0; a < this.htmlCart_items.length; a++ ){
		
		//si es el ultimo, quitar el border de abajo
		var starter = (a == (this.htmlCart_items.length-1)) ? "<li class='ApplicationVender' style='border-bottom-width: 0px;'>" : "<li class='ApplicationVender'>";
		
		html += starter 
		+ "<span class='id'>" + this.htmlCart_items[a].id +"</span>" 
		+ "<span class='name'>" + this.htmlCart_items[a].name +"</span>" 
		+ "<span class='description'>"+ this.htmlCart_items[a].description +"</span>" 
		+ "<span class='cost'>"+ this.htmlCart_items[a].cost +"</span>"
		+ "<span class='trash' onclick='ApplicationVender.currentInstance.doDeleteItem(" +a+ ")'>&nbsp;<img height=20 width=20 src='sencha/resources/img/toolbaricons/trash.png'></span>"		
		

		+ "</li>";
	}
	


	//imprimir el html
	Ext.get("carritoDeCompras").update("<ul class='ApplicationVender'>" + html +"</ul>");
	
	
	
	//preparar un html para los totales
	var totals_html = "";

	//si hay mas de un producto, mostrar los totales
	if(this.htmlCart_items.length > 0){
		
		var subtotal = 0;
		
		//revisar que no este ya en el carrito
		for( a = 0; a < this.htmlCart_items.length;  a++){
			subtotal += parseInt( this.htmlCart_items[a].cost );
		}

		
		totals_html = "<li class='ApplicationVender-Totales'>Subtotal <b>" +  subtotal + "</b></li>"
		+ "<li class='ApplicationVender-Totales'>IVA <b>" +  (subtotal*.15) + "</b></li>"
		+ "<li class='ApplicationVender-Totales' style='border-bottom-width: 0px;'>Total <b>" +  ((subtotal*.15)+subtotal) + "</b></li>";
	}else{
		totals_html = "";
	}
	
	Ext.get("carritoDeComprasTotales").update("<ul class='ApplicationVender-Totales'>" + totals_html +"</ul>");
};










//funciones de parse del contenido del carrito
ApplicationVender.prototype.htmlCart_addItem = function( item )
{

	var id = item.id;
	var name = item.name;
	var description = item.description;
	var existencias = item.existencias;
	var costo = item.cost;
	
	var found = false;
	
	//overrride multiple items
	var MULTIPLE_SAME_ITEMS = true;
	
	
	
	//revisar que no este ya en el carrito
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
	
	//empujar al carrito
	this.htmlCart_items.push(item);
	
	
	//dibujar el carrito
	this.doRefreshItemList();



};






//----------------------------------------------------------------------------------------------------------------------------------------------
//					Agregar al carrito 
//----------------------------------------------------------------------------------------------------------------------------------------------
//evento de click en agregar producto
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

		//Ext.get("APaddProductByID").setSize(100, 100, opt);
		return;
	}
	
	//buscar si este producto existe
	POS.AJAXandDECODE({
			method: 'existenciaProductoSucursal',
			id_producto : prodID,
			id_sucursal : 2
		}, 
		function (datos){
			
			//ya llego el request con los datos si existe o no	
			if(!datos.success){
				POS.aviso("Mostrador", datos.reason);
				return;
			}
			
			//crear el item
			var item = {
				id 			: datos.datos[0].id_producto,
				name 		: datos.datos[0].nombre,
				description : datos.datos[0].denominacion,
				cost 		: datos.datos[0].precio_venta,
				existencias : datos.datos[0].existencias
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












//----------------------------------------------------------------------------------------------------------------------------------------------
//					doVender & doCotizar
//----------------------------------------------------------------------------------------------------------------------------------------------


ApplicationVender.prototype.doVender = function ()
{
	
	if(DEBUG){
		console.log("ApplicationVender: doVender called....");
	}
	
	
	items = ApplicationVender.currentInstance.htmlCart_items;
	
	//revisar que exista por lo menos un item
	if(items.length == 0){
		POS.aviso("Mostrador", "Agregue primero al menos un arituclo para poder vender.");
		return;
	}
	
	newPanel = ApplicationVender.currentInstance.doVenderPanel;
	
	sink.Main.ui.setCard( newPanel, 'slide' );

	/*
	POS.AJAXandDECODE({
		method: 'listarClientes',
		byName : 'Monica'
		}, 
		function (datos){
		 	POS.aviso("OK", "Todo salio bien");
			console.log(datos);

		},
		function (){
			POS.aviso("DE LA VERGA", ":(");
			
		}
	);
	*/

	

 
};


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





//----------------------------------------------------------------------------------------------------------------------------------------------
//				Panel de Vender  
//----------------------------------------------------------------------------------------------------------------------------------------------


ApplicationVender.prototype.doVenderPanel = new Ext.form.FormPanel({
	//tipo de scroll
    scroll: 'vertical',

	//toolbar
	dockedItems: null,
	
	//items del formpanel
    items: [{

        xtype: 'fieldset',
        title: 'Venta',
        items: [{
	        	xtype: 'textfield',
	        	label: 'SubTotal'
       		},{
		        xtype: 'textfield',
		        label: 'IVA'
	      	},{
			    xtype: 'textfield',
			    label: 'Total'
		    }]
		},{
        	xtype: 'fieldset',
        	title: 'Credito',
        	defaults: {
            	xtype: 'radio',
        	},
        	items: [{
	        	xtype: 'toggle',
	        	name: 'enable',
	        	label: 'Compra a credito'
        		}]
    	}]
});



ApplicationVender.prototype.CLIENTE_COMUN = true;

//----------------------------------------------------------------------------------------------------------------------------------------------
//				Buscar cliente  
//----------------------------------------------------------------------------------------------------------------------------------------------
ApplicationVender.prototype.swapClienteComun = function (val)
{	
	
	
	if(val==0){
		//buscar cliente
		ApplicationVender.currentInstance.buscarCliente();
		ApplicationVender.currentInstance.CLIENTE_COMUN = false;

	}else{
		
		//remove client detail
		Ext.get("detallesCliente").update("");
		
		//remove "facturar" option
		//ApplicationVender.currentInstance.mainCard.items.items[2].remove(   'ventaFacturar'  );
		//ApplicationVender.currentInstance.mainCard.doLayout();
		
		ApplicationVender.currentInstance.CLIENTE_COMUN = true; 	
	}

};


ApplicationVender.prototype.actualizarDetallesCliente = function ( cliente )
{
	//si ya vamos a actualizar cliente entonces es porque todo salio bien, entonces.... agregar la opcion de facturar
	//ApplicationVender.currentInstance.mainCard.items.items[2].add(  {xtype: 'toggle', id: 'ventaFacturar', value: 0, label: 'Facturar'}   );
	//ApplicationVender.currentInstance.mainCard.doLayout();
	
	//mostrar los detalles del cliente
	
	var html = "";
	html += " <div class='ApplicationVender-clienteBox'> ";
		html += " <div><h2>" + cliente.nombre + "</h2></div>";
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
	console.log("requesting template-...");
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
							//regresar el boton de cliente comun a 1

							Ext.getCmp("av_top_nota_factura").setActive(0);


							//ocultar esta tabla
							form.hide();							
							
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



