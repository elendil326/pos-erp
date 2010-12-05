

Aplicacion.Mostrador = function (  ){

	return this._init();
}




Aplicacion.Mostrador.prototype._init = function (){
    if(DEBUG){
		console.log("Mostrador: construyendo");
    }

	
	//crear el panel del mostrador
	this.mostradorPanelCreator();
	
	//crear la forma de la busqueda de clientes
	this.buscarClienteFormCreator();
	
	//crear la forma de ventas
	this.doVentaPanelCreator();
	
	Aplicacion.Mostrador.currentInstance = this;
	
	return this;
};




Aplicacion.Mostrador.prototype.getConfig = function (){
	return {
	    text: 'Mostrador',
	    cls: 'launchscreen',
	    card: this.mostradorPanel,
        leaf: true
		};
};






/* ********************************************************
	Funciones de carrito
******************************************************** */




Aplicacion.Mostrador.prototype.cancelarVenta = function ()
{
	
	Aplicacion.Mostrador.currentInstance.carrito.items = [];
	
	Aplicacion.Mostrador.currentInstance.refrescarMostrador();
	
	Aplicacion.Mostrador.currentInstance.setCajaComun();
	
};




/*
 *	Estructura donde se guardaran los detalles de la venta actual.
 * */
Aplicacion.Mostrador.prototype.carrito = {
	tipoDeVenta : null,
	items : [  ],
	cliente : null
};








Aplicacion.Mostrador.prototype.carritoCambiarCantidad = function ( id, qty, forceNewValue )
{
	
	carrito = Aplicacion.Mostrador.currentInstance.carrito.items;
	
	for (var i = carrito.length - 1; i >= 0; i--){
		if( carrito[i].productoID == id ){
			
			if(forceNewValue){
				carrito[i].cantidad = qty;
			}else{
				carrito[i].cantidad += qty;
			}
			
			if(carrito[i].cantidad <= 0){
				carrito[i].cantidad = 1;
			}
			
			this.refrescarMostrador();
			break;
		}
	};
	
};



Aplicacion.Mostrador.prototype.refrescarMostrador = function (  )
{	
	carrito = Aplicacion.Mostrador.currentInstance.carrito;
	
	var html = "<table border=0>";
	
	html += "<tr class='top'>";
	html += "<td>Descripcion</td>";
	html += "<td colspan=3>Cantidad</td>";	
	html += "<td>Precio</td>";
	html += "<td>Total</td>";

	html += "</tr>";
	
	for (var i=0; i < carrito.items.length; i++){
		
		if( i == carrito.items.length - 1 )
			html += "<tr class='last'>";
		else
			html += "<tr >";		
		
		html += "<td>" + carrito.items[i].productoID + " " + carrito.items[i].descripcion+ "</td>";

		html += "<td > <span class='boton' onClick='Aplicacion.Mostrador.currentInstance.carritoCambiarCantidad("+ carrito.items[i].productoID +", -1, false)'>&nbsp;-&nbsp;</span></td>";

		html += "<td> <div id='Mostrador-carritoCantidad"+ carrito.items[i].productoID +"'></div></td>"

		html += "<td > <span class='boton' onClick='Aplicacion.Mostrador.currentInstance.carritoCambiarCantidad("+ carrito.items[i].productoID +", 1, false)'>&nbsp;+&nbsp;</span></td>";

		html += "<td> <div id='Mostrador-carritoPrecio"+ carrito.items[i].productoID +"'></div></td>"
		
		html += "<td>" + POS.currencyFormat( carrito.items[i].cantidad * carrito.items[i].precioVenta )+"</td>";
		
		html += "</tr>";
	};
	
	html += "</table>";
	
	Ext.getCmp("MostradorHtmlPanel").update(html);
	
	
	
	for (var i=0; i < carrito.items.length; i++){
		
	    if(Ext.get("Mostrador-carritoCantidad"+ carrito.items[i].productoID + "Text")){
			continue;
		}
	
		a = new Ext.form.Text({
			renderTo : "Mostrador-carritoCantidad"+ carrito.items[i].productoID ,
			id : "Mostrador-carritoCantidad"+ carrito.items[i].productoID + "Text",
			value : carrito.items[i].cantidad,
			placeHolder : "Agregar Producto",
			listeners : {
				'focus' : function (){

					kconf = {
						type : 'num',
						submitText : 'Aceptar',
						callback : Aplicacion.Mostrador.currentInstance.refrescarMostrador
					};
					
					POS.Keyboard.Keyboard( this, kconf );
				}
			}
		
		});

		b = new Ext.form.Text({
			renderTo : "Mostrador-carritoPrecio"+ carrito.items[i].productoID ,
			id : "Mostrador-carritoPrecio"+ carrito.items[i].productoID + "Text",
			value : carrito.items[i].precioVenta,
			placeHolder : "Precio de Venta",
			listeners : {
				'focus' : function (){

					kconf = {
						type : 'num',
						submitText : 'Cambiar precio',
						callback : function () {
							console.log(this)
							Aplicacion.Mostrador.currentInstance.refrescarMostrador();
						}
					};
					
					POS.Keyboard.Keyboard( this, kconf );
				}
			}
		
		});
	};	
};

Aplicacion.Mostrador.prototype.agregarProducto = function (  )
{	
	val = Aplicacion.Mostrador.currentInstance.mostradorPanel.getDockedComponent(0).getComponent(0).getValue();
	Aplicacion.Mostrador.currentInstance.mostradorPanel.getDockedComponent(0).getComponent(0).setValue("");
	Aplicacion.Mostrador.currentInstance.agregarProductoPorID(val);
};

Aplicacion.Mostrador.prototype.agregarProductoPorID = function ( id )
{

	//buscar el la estructura que esta en el Inventario
	inventario = Aplicacion.Inventario.currentInstance.inventarioListaStore;
	
	
	res = inventario.findRecord("productoID", id);
	
	if(res==null){
		Ext.Msg.alert("Mostrador", "Este producto no existe");		
		return;
	}

	
	if(DEBUG){
		console.log("Agregando el producto " + id + " al carrito.");
	}

	//buscar este producto en el carrito
	for(var a = 0, found = false ; a < this.carrito.items.length; a++)
	{
		if(this.carrito.items[a].productoID == id)
		{
			//ya esta en el carrito, aumentar su cuenta
			found = true;
			this.carrito.items[a].cantidad++;
			break;
		}
	}
	
	//si no, agregarlo al carrito
	if(!found)
	{
		this.carrito.items.push({
			descripcion: res.data.descripcion,
			existencias: res.data.existencias,
			existenciasMinimas: res.data.existenciasMinimas,
			precioVenta: res.data.precioVenta,
			productoID: res.data.productoID,
			medida: res.data.medida,
			precioIntersucursal : res.data.precioIntersucursal,
			cantidad : 1
		});
	}
	

	//refrescar el html
	this.refrescarMostrador();
};



/* ********************************************************
	Panel principal del mostrador
******************************************************** */


/**
 * Contiene el panel con la forma del mostrador
 */
Aplicacion.Mostrador.prototype.mostradorPanel = null;


/**
 * Pone un panel en mostradorPanel
 */
Aplicacion.Mostrador.prototype.mostradorPanelCreator = function (){
	
	
	var productos = [{
		xtype: 'textfield',
		placeHolder : "Agregar Producto",
		listeners : {
			'focus' : function (){

				kconf = {
					type : 'num',
					submitText : 'Agregar',
					callback : Aplicacion.Mostrador.currentInstance.agregarProducto
				};
				POS.Keyboard.Keyboard( this, kconf );
			}
		}
    }];


    var venta = [{
        text: 'Cancelar Venta',
        ui: 'action',
		handler : this.cancelarVenta
    },{
        xtype: 'segmentedbutton',
		id : 'Mostrador-tipoCliente',
        allowDepress: false,
        items: [{
            text: 'Caja Comun',
            pressed: true,
			handler : this.setCajaComun
        }, {
            text: 'Cliente',
			handler : this.buscarClienteFormShow
        }]    
    },{
        text: 'Vender',
        ui: 'forward',
		handler : this.doVentaPanelShow
    }];


   productos.push({xtype: 'spacer'});

	var dockedItems = [new Ext.Toolbar({
		ui: 'light',
		dock: 'bottom',
		items: productos.concat(venta)
	})];
   
	this.mostradorPanel = new Ext.Panel({
		listeners : {
			"show" : this.refrescarMostrador
		},
		floating: false,
		ui : "dark",
	    modal: false,
		cls : "Tabla",
		items : [{
			id: 'MostradorHtmlPanel',				
			html : null
		}],
		
	    scroll: 'none',
		dockedItems: dockedItems
	});

};











/* ********************************************************
	Buscar y seleccionar cliente para la venta
******************************************************** */


Aplicacion.Mostrador.prototype.buscarClienteForm = null;



Aplicacion.Mostrador.prototype.clienteSeleccionado = function ( cliente )
{
	
	if(DEBUG){
		console.log("cliente seleccionado", cliente);
	}
	
	this.buscarClienteFormShow();
	
	Aplicacion.Mostrador.currentInstance.carrito.cliente = cliente;
		
};




Aplicacion.Mostrador.prototype.setCajaComun = function ()
{


	
	if(Ext.getCmp('Mostrador-tipoCliente').getPressed()){
		Ext.getCmp('Mostrador-tipoCliente').setPressed( Ext.getCmp('Mostrador-tipoCliente').getPressed() );
	}
		
	Ext.getCmp('Mostrador-tipoCliente').setPressed(0);

	Aplicacion.Mostrador.currentInstance.carrito.cliente = null;
	
};


Aplicacion.Mostrador.prototype.buscarClienteFormCreator = function ()
{
	

	//cancelar busqueda
	var dockedCancelar = {
		xtype : 'button',		
        text: 'Cancelar',
		handler : function(){
			Aplicacion.Mostrador.currentInstance.setCajaComun();
			Aplicacion.Mostrador.currentInstance.buscarClienteFormShow();
		}
    };



	//toolbar
	var dockedItems = {
		xtype: 'toolbar',
        dock: 'bottom',
        items: [ dockedCancelar , { xtype: 'spacer' } ]
	};


	var formBase = {
		autoRender: true,
		floating: true,
		modal: true,
		centered: true,
		hideOnMaskTap: false,
		height: 585,
		width: 680,
		items: [{
			
			width : '100%',
			height: '100%',
			xtype: 'list',
			store: Aplicacion.Clientes ? Aplicacion.Clientes.currentInstance.listaDeClientesStore : null ,
			itemTpl: '<div class="listaDeClientesCliente"><strong>{nombre}</strong> {rfc}</div>',
			grouped: true,
			indexBar: true,
			listeners : {
				"selectionchange"  : function ( view, nodos, c ){
					
					if(nodos.length > 0){
						Aplicacion.Mostrador.currentInstance.clienteSeleccionado( nodos[0].data );
					}

					//deseleccinar el cliente
					view.deselectAll();
				}
			}
			
        }],
		dockedItems: dockedItems
	};



	if(DEBUG){
		console.log( "creando la forma de buscar clientes" );
	}
	
	
	
 	this.buscarClienteForm = new Ext.Panel(formBase);

	//por alguna razon al crearlo dice que es visible, 
	//habra que hacerlo invisible
	this.buscarClienteForm.setVisible(false);


};







Aplicacion.Mostrador.prototype.buscarClienteFormShow = function (  )
{


	if(Aplicacion.Mostrador.currentInstance.buscarClienteForm){

		//invertir la visibilidad de la forma
		Aplicacion.Mostrador.currentInstance.buscarClienteForm.setVisible( !Aplicacion.Mostrador.currentInstance.buscarClienteForm.isVisible() );
		
	}else{
		Aplicacion.Mostrador.currentInstance.buscarClienteFormCreator();
		Aplicacion.Mostrador.currentInstance.buscarClienteFormShow();		
	}

    
};






/* ********************************************************
	Formas y funciones de venta (paso2)
******************************************************** */

/*
 * Guarda el panel donde estan la forma de venta
 **/
Aplicacion.Mostrador.prototype.doVentaPanel = null;





/*
 * Es la funcion de entrada para mostrar el panel de venta
 **/
Aplicacion.Mostrador.prototype.doVentaPanelShow = function ( ){

	//hacer un update de la nueva informacion en el panel
	Aplicacion.Mostrador.currentInstance.doVentaPanelUpdater();
	
	//hacer un setcard manual
	sink.Main.ui.setActiveItem( Aplicacion.Mostrador.currentInstance.doVentaPanel , 'slide');
};




Aplicacion.Mostrador.prototype.doVentaPanelUpdater = function ()
{
	
	//obtener los datos del objeto en this.carrito
	console.log(this.carrito)
	
};


/*
 * Se llama para crear por primera vez el panel de venta
 **/
Aplicacion.Mostrador.prototype.doVentaPanelCreator = function (  ){
	
	this.doVentaPanel = new Ext.form.FormPanel({                                                       

		items: [{
			xtype: 'fieldset',
		    title: 'Datos de la venta',
			items: [
				new Ext.form.Text({ label : 'Cliente', 			id: 'Mostrador-doVentaCliente' }),
				new Ext.form.Text({ label : 'Tipo de Venta', 	id: 'Mostrador-doVentaTipo' }),
				new Ext.form.Text({ label : 'Subtotal', 		id: 'Mostrador-doVentaSub' }),
				new Ext.form.Text({ label : 'IVA', 				id: 'Mostrador-doVentaIva' }),
				new Ext.form.Text({ label : 'Descuento',		id: 'Mostrador-doVentaDesc' }),
				new Ext.form.Text({ label : 'Total', 			id: 'Mostrador-doVentaTotal' })
			]},
			
			new Ext.Button({ ui  : 'action', text: 'Vender', margin : 5,  handler : null }),
	]});


	
};













POS.Apps.push( new Aplicacion.Mostrador() );
