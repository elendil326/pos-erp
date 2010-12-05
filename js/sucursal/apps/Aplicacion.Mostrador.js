

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
	
};




//estructura donde se guardan los datos de la venta actual
Aplicacion.Mostrador.prototype.carrito = {
	tipoDeVenta : null,
	items : [  ]
};



Aplicacion.Mostrador.prototype.carritoCambiarCantidadPanel = null;

Aplicacion.Mostrador.prototype.carritoCambiarCantidad = function ( id )
{

	if(DEBUG){
		console.log("cambiar la cantidad del producto "+id+", mostrando panel");
	}
	
	if(!this.carritoCambiarCantidadPanel){
		this.carritoCambiarCantidadPanel = new Ext.Panel({
			autoRender: true,
			floating: true,
			modal: true,
			centered: true,
			hideOnMaskTap: true,
			height: 100,
			width: 200,
			items: [
				
			]
		});

	}
	
	this.carritoCambiarCantidadPanel.show();
};



Aplicacion.Mostrador.prototype.refrescarMostrador = function (  )
{	
	carrito = Aplicacion.Mostrador.currentInstance.carrito;
	
	var html = "<table border=0>";
	
	html += "<tr class='top'>";
	html += "<td>Descripcion</td>";
	html += "<td>Cantidad</td>";	
	html += "<td>Precio</td>";
	html += "<td>Total</td>";

	html += "</tr>";
	
	for (var i=0; i < carrito.items.length; i++){
		
		if( i == carrito.items.length - 1 )
			html += "<tr class='last'>";
		else
			html += "<tr >";		
		
		html += "<td>" + carrito.items[i].productoID + " " + carrito.items[i].descripcion+ "</td>";

		//html += "<td onclick='Aplicacion.Mostrador.currentInstance.carritoCambiarCantidad("+ carrito.items[i].productoID +")'>" + carrito.items[i].cantidad + "</td>";
		html += "<td > <span class='boton'>&nbsp;-&nbsp;</span> <span style='width:100px'> " + carrito.items[i].cantidad + " </span> <span class='boton'>&nbsp;+&nbsp;</span> </td>";

		html += "<td>" + POS.currencyFormat ( carrito.items[i].precioVenta ) + "</td>";
		html += "<td>" + POS.currencyFormat( carrito.items[i].cantidad * carrito.items[i].precioVenta )+"</td>";
		html += "</tr>";
	};
	
	html += "</table>";
	
	Ext.getCmp("MostradorHtmlPanel").update(html);
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
        allowDepress: false,
        items: [{
            text: 'Caja Comun',
            pressed: true
        }, {
            text: 'Cliente',
			handler : this.buscarClienteFormShow
        }]    
    },{
        text: 'Vender',
        ui: 'forward'
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

Aplicacion.Mostrador.prototype.cliente = null;

Aplicacion.Mostrador.prototype.clienteSeleccionado = function ( cliente )
{
	
	if(DEBUG){
		console.log("cliente seleccionado", cliente);
		this.buscarClienteFormShow();
	}
	
	
};







Aplicacion.Mostrador.prototype.buscarClienteFormCreator = function ()
{
	

	//cancelar busqueda
	var dockedCancelar = {
		xtype : 'button',		
        text: 'Cancelar',
		handler : this.buscarClienteFormShow
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


















POS.Apps.push( new Aplicacion.Mostrador() );






