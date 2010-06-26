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


	//initialize the tootlbar which is a dock
	this._initToolBar();
	
	
	//panel principal	
	this.mainCard = this.venderMainPanel;
	
	
	
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


	//grupo 2, cualquier otra mamada
	var buttonsGroup2 = [/*{
        xtype: 'splitbutton',
        activeButton: 0,
        items: [{
            text: 'Option 1',
            handler: tapHandler
        }, {
            text: 'Option 2',
            handler: tapHandler
        }, {
            text: 'Option 3',
            handler: tapHandler
        }]    
    }*/];
    



	//grupo 3, listo para vender
    var buttonsGroup3 = [{
        text: 'Limpiar',
        handler: null
    },{
        text: 'Cotizar',
        handler: this.doCotizar
    },{
        text: 'Vender',
        ui: 'action',
        handler: this.doVender
    }];


	if (!Ext.platform.isPhone) {
        buttonsGroup1.push({xtype: 'spacer'});
        buttonsGroup2.push({xtype: 'spacer'});
        
        this.dockedItems = [new Ext.Toolbar({
            // dock this toolbar at the bottom
            ui: 'light',
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
	this.venderMainPanel.addDocked( this.dockedItems );

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

        xtype: 'fieldset',
        title: 'Detalles del cliente',
        //instructions: 'Please enter the information above.',
        items: [{
	        xtype: 'toggle',
	        id: 'clienteComunToggle',
			value: 1,
	        label: 'Caja comun',
			listeners:
					{
						change: function( slider, thumb, oldValue, newValue){
							if(oldValue == newValue) { return; }
							ApplicationVender.currentInstance.swapClienteComun(newValue);
						}
					}
        }]
    },{
			html: '',
			id : 'detallesCliente'
		},{
        xtype: 'fieldset',
        title: 'Detalles de la Venta',
        defaults: {
            xtype: 'radio',
        },
        items: [{
	        xtype: 'toggle',
	        name: 'enable',
	        label: 'Nota'
        }]
    },{
		html: '',
		id : 'carritoDeCompras'
	},{
		html: '',
		id : 'carritoDeComprasTotales'
	}]
});





//----------------------------------------------------------------------------------------------------------------------------------------------
//					lista de carrito de compras
//----------------------------------------------------------------------------------------------------------------------------------------------
ApplicationVender.prototype.htmlCart_items = [];




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
		+ "<span class='trash'>&nbsp;<img height=20 width=20 src='sencha/resources/img/toolbaricons/trash.png'></span>"		
		

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
	
};




//----------------------------------------------------------------------------------------------------------------------------------------------
//				Buscar cliente  
//----------------------------------------------------------------------------------------------------------------------------------------------
ApplicationVender.prototype.swapClienteComun = function (val)
{	
	if(val==0){
		//buscar cliente
		ApplicationVender.currentInstance.buscarCliente();

		//agregar la opcion de facturar
		ApplicationVender.currentInstance.mainCard.items.items[2].add(  {xtype: 'toggle', id: 'ventaFacturar', value: 1, label: 'Facturar'}   );
		ApplicationVender.currentInstance.mainCard.doLayout();		
	}else{
		
		//remove client detail
		Ext.get("detallesCliente").update("");
		
		//remove "facturar" option
		ApplicationVender.currentInstance.mainCard.items.items[2].remove(   'ventaFacturar'  );
		ApplicationVender.currentInstance.mainCard.doLayout();		
	}

};


ApplicationVender.prototype.actualizarDetallesCliente = function ( clienteID )
{
	//mostrar los detalles del cliente
	Ext.get("detallesCliente").update("Detallles del cliente.... " + clienteID);
	
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
				clientesData.push( {firstName: response.datos[a].id_cliente, lastName: response.datos[a].nombre} );
			}

			//create regmodel
			Ext.regModel('Contact', {
		                    fields: ['firstName', 'lastName']
		    });

			//create the actual store
			var clientesStore = new Ext.data.Store({
                model: 'Contact',
                sorters: 'lastName',
                getGroupString : function(record) {
                    return record.get('lastName')[0];
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








ApplicationVender.prototype.buscarClienteShowForm = function ( clientesStore )
{
	
        var formBase = {
			//	items
            items: [{
		        width: "100%",
		        height: "100%",
				id: 'buscarClientesLista',
		        xtype: 'list',
		        store: clientesStore,
		        tpl: '<tpl for="."><div class="contact"><strong>{firstName}</strong> {lastName}</div></tpl>',
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
				            handler: null
				        }, {
				            text: 'RFC',
				            handler: null
				        }, {
				            text: 'Direccion',
				            handler: null
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
							Ext.getCmp("clienteComunToggle").setValue(1);


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
							ApplicationVender.currentInstance.actualizarDetallesCliente( Ext.getCmp("buscarClientesLista").selected.elements[0].innerText )
						
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








//autoinstalar esta applicacion
AppInstaller( new ApplicationVender() );



