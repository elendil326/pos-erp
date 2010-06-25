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






ApplicationVender.prototype.swapClienteComun = function (val)
{	
	if(val==0){
		ApplicationVender.currentInstance.mainCard.items.items[0].add(  {xtype: 'slider', id: 'asdfclienteComun', value: 1, label: 'Agregar Cliente'}   );
		//ApplicationVender.currentInstance.mainCard.items.items[0].add(  {xtype: 'button', id: 'asdfclienteComun', value: 1, label: 'Agregar Cliente'}   );
		ApplicationVender.currentInstance.mainCard.doLayout();		
	}else{
		ApplicationVender.currentInstance.mainCard.items.items[0].remove(   'asdfclienteComun'  );
		ApplicationVender.currentInstance.mainCard.doLayout();
	}

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
	        name: 'clienteComun',
			value: 1,
	        label: 'Cliente Comun',
			listeners:
					{
						change: function( slider, thumb, oldValue, newValue){
							if(oldValue == newValue) { return; }
							ApplicationVender.currentInstance.swapClienteComun(newValue);
						}
					}
        },
        {
            xtype: 'hidden',
            name: 'secret',
            value: false
        }]
    },{
        xtype: 'fieldset',
        title: 'Detalles de la Venta',
        defaults: {
            xtype: 'radio',
        },
        items: [{
	        xtype: 'toggle',
	        name: 'enable',
	        label: 'Factura'
        },
        {
	        xtype: 'toggle',
	        name: 'enable',
	        label: 'Nota'
        }]
    },{
		html: '',
		id : 'carritoDeCompras'
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
	
	var found = false;
	
	//revisar que no este ya en el carrito
	for( a = 0; a < this.htmlCart_items.length;  a++){
		if( this.htmlCart_items[ a ].id == id ){
			found = true;
			break;
		}
	}
	
	
	if(found){
		POS.aviso("Mostrador", "Ya ha agregado este producto");
		return;
	}
	
	//empujar al carrito
	this.htmlCart_items.push(item);
	
	var html = "";
	
	//renderear el html
	for( a = 0; a < this.htmlCart_items.length; a++ ){
		html += "<li class='ApplicationVender'><span class='id'>" + this.htmlCart_items[a].id +"</span><span class='name'>" + this.htmlCart_items[a].name +"</span><span class='description'>" + this.htmlCart_items[a].description +"</span></span><span class='cost'>" + this.htmlCart_items[a].cost +"</span></li>";
	}

	
	Ext.get("carritoDeCompras").update("<ul class='ApplicationVender'>" + html +"</ul>");
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
			
			console.log(datos);
			
			var existe = true;
			
			if(existe===true){
				//si existe, agregarlo a detalles de venta
				
				var item = {
					id : Ext.get("APaddProductByID").first().getValue(),
					name : "cosa rara",
					description : 'esta es una cosa rara',
					cost : "123.88"
				};
				
				ApplicationVender.currentInstance.htmlCart_addItem( item );
				
				Ext.get("APaddProductByID").first().dom.value = "";
				
			}else{
				//no existe en el inventario
				POS.aviso("Inventario", "Este articulo no existe en el inventario.");
			}
			
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
	
	
	console.log("prueba de comunicacion con server");
	

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

	

 
};


ApplicationVender.prototype.doCotizar = function ()
{
	
	if(DEBUG){
		console.log("ApplicationVender: doCotizar called....");
	}
	
};


//autoinstalar esta applicacion
AppInstaller( new ApplicationVender() );



