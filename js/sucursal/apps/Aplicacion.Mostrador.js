

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
	
	//crear la forma de que todo salio bien en la venta
	this.finishedPanelCreator();
	
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
	tipoDeVenta : "contado",
	items : [  ],
	cliente : null,
	factura : false
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
			prodID : carrito.items[i].productoID,
			width: 50,
			placeHolder : "Agregar Producto",
			listeners : {
				'focus' : function (){

					kconf = {
						type : 'num',
						submitText : 'Aceptar',
						callback : function ( campo ){
							//buscar el producto en la estructura y ponerle esa nueva cantidad
							for (var i=0; i < Aplicacion.Mostrador.currentInstance.carrito.items.length; i++) {
								if(Aplicacion.Mostrador.currentInstance.carrito.items[i].productoID == campo.prodID){
									Aplicacion.Mostrador.currentInstance.carrito.items[i].cantidad = parseFloat( campo.getValue() );
									break;
								}
							};
							
							Aplicacion.Mostrador.currentInstance.refrescarMostrador();
						}
					};
					
					POS.Keyboard.Keyboard( this, kconf );
				}
			}
		
		});

		b = new Ext.form.Text({
			renderTo : "Mostrador-carritoPrecio"+ carrito.items[i].productoID ,
			id : "Mostrador-carritoPrecio"+ carrito.items[i].productoID + "Text",
			value : carrito.items[i].precioVenta,
			prodID : carrito.items[i].productoID,			
			placeHolder : "Precio de Venta",
			listeners : {
				'focus' : function (){

					kconf = {
						type : 'num',
						submitText : 'Cambiar precio',
						callback : function ( campo ){
							//buscar el producto en la estructura y ponerle esa nueva cantidad
							for (var i=0; i < Aplicacion.Mostrador.currentInstance.carrito.items.length; i++) {
								if(Aplicacion.Mostrador.currentInstance.carrito.items[i].productoID == campo.prodID){
									Aplicacion.Mostrador.currentInstance.carrito.items[i].precioVenta = parseFloat( campo.getValue() );
									break;
								}
							};
							
							Aplicacion.Mostrador.currentInstance.refrescarMostrador();
						}
					};
					
					POS.Keyboard.Keyboard( this, kconf );
				}
			}
		
		});
	};
	
	
	//si hay mas de un producto, mostrar el boton de vender
	if(carrito.items.length > 0){
		Ext.getCmp("Mostrador-mostradorVender").show( Ext.anims.slide );
	}else{
		Ext.getCmp("Mostrador-mostradorVender").hide( Ext.anims.slide );
	}
	
	
	
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
            text: 'Venta a Caja Comun',
            pressed: true,
			handler : this.setCajaComun
        }, {
            text: 'Venta a Cliente',
			handler : this.buscarClienteFormShow,
			badgeText : ""
        }]    
    },{
		id: 'Mostrador-mostradorVender',
		hidden: true,
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
	
	Ext.getCmp("Mostrador-tipoCliente").getComponent(1).setBadge(cliente.nombre);
	
	Aplicacion.Mostrador.currentInstance.carrito.cliente = cliente;
		
};




Aplicacion.Mostrador.prototype.setCajaComun = function ()
{


	
	if(Ext.getCmp('Mostrador-tipoCliente').getPressed()){
		Ext.getCmp('Mostrador-tipoCliente').setPressed( Ext.getCmp('Mostrador-tipoCliente').getPressed() );
	}

	Ext.getCmp("Mostrador-tipoCliente").getComponent(1).setBadge( );		
	Ext.getCmp('Mostrador-tipoCliente').setPressed(0);

	Aplicacion.Mostrador.currentInstance.carrito.tipoDeVenta = "contado";
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
	ThankYou for your bussiness
******************************************************** */
Aplicacion.Mostrador.prototype.finishedPanel = null;


Aplicacion.Mostrador.prototype.finishedPanelShow = function()
{
	//update panel
	this.finishedPanelUpdater();
	
	//resetear los formularios
	this.cancelarVenta();
	
	sink.Main.ui.setActiveItem( Aplicacion.Mostrador.currentInstance.finishedPanel , 'fade');
	
};



Aplicacion.Mostrador.prototype.finishedPanelUpdater = function()
{
	carrito = Aplicacion.Mostrador.currentInstance.carrito;
	
	
	html = "";
	
	html += "<table class='Mostrador-ThankYou'>";
	html += "	<tr>";	
	html += "		<td>Venta existosa</td>";
	html += "		<td></td>";
	html += "	</tr>";	
	
	if(carrito.tipoDeVenta == "credito"){
		//mostrar un boton para abonar
		html += "	<tr>";	
		html += "		<td>Abonar</td>";
		html += "		<td></td>";
		html += "	</tr>";
	}else{
		//mostrar el cambio
		html += "	<tr>";	
		html += "		<td>cambio=</td>";
		html += "		<td></td>";
		html += "	</tr>";
	}	
	
	html += "	<tr>";	
	html += "		<td>Nueva Venta</td>";
	html += "		<td></td>";
	html += "	</tr>";
	
	html += "</table>";
	
	this.finishedPanel.update(html);

};



Aplicacion.Mostrador.prototype.finishedPanelCreator = function()
{

	this.finishedPanel = new Ext.Panel({
		html : ""
	});
	
};


/* ********************************************************
	Hacer la venta
******************************************************** */

Aplicacion.Mostrador.prototype.doVenta = function ()
{
	
	
	carrito = Aplicacion.Mostrador.currentInstance.carrito;

	
	if(carrito.tipoDeVenta == 'contado'){
		if(DEBUG){
			console.log("revisando venta a contado...");
		}
		
		//ver si pago lo suficiente
		pagado = Ext.getCmp("Mostrador-doVentaEfectivo").getValue(); 
		
		if( (pagado.length == 0) || (parseFloat(pagado) < parseFloat(carrito.total)) ){
			
			//no pago lo suficiente
			Ext.Msg.alert("Mostrador", "Cantidad insuficiente de efectivo.");
			
			return;
		}
		
		this.carrito.pagado = pagado;
		this.vender();

		
	}else{
		
		if(DEBUG){
			console.log("revisando venta a credito...");
		}
		//ver si si puede comprar a credito
		//aunque se supone que si debe poder

		this.vender();
		
	}

};


Aplicacion.Mostrador.prototype.vender = function ()
{
	carrito = Aplicacion.Mostrador.currentInstance.carrito;
	json = Ext.util.JSON.encode( carrito );
	
	if(DEBUG){
		console.log("Enviando venta ....");
	}
	
	Ext.Ajax.request({
		url: 'proxy.php',
		scope : this,
		params : {
			action : 100,
			payload : json
		},
		success: function(response, opts) {
			try{
				venta = Ext.util.JSON.decode( response.responseText );				
			}catch(e){
				return POS.error(e);
			}
			
			if( !venta.success ){
				//volver a intentar
				if(DEBUG){
					console.log("resultado de la venta sin exito ",venta )
				}
				Ext.Msg.alert("Mostrador", venta.reason);
				return;

			}
			
			if(DEBUG){
				console.log("resultado de la venta exitosa ",venta )
			}
			
			//recargar el inventario
			Aplicacion.Inventario.currentInstance.cargarInventario();
			
			
			//recargar la lista de clientes y de compras
			if( Aplicacion.Mostrador.currentInstance.carrito.cliente != null){
				Aplicacion.Clientes.currentInstance.listaDeClientesLoad();
				Aplicacion.Clientes.currentInstance.listaDeComprasLoad();
			}

			//mostrar el panel final
			Aplicacion.Mostrador.currentInstance.finishedPanelShow();

		},
		failure: function( response ){
			POS.error( response );
		}
	});
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
	

	if(DEBUG){
		console.log("Haciendo update en el formulario de la venta", this.carrito);
	}
	
	
	//mostrar los totales
	subtotal = 0;
	total = 0;
	for (var i=0; i < this.carrito.items.length; i++) {
		subtotal += (this.carrito.items[i].precioVenta * this.carrito.items[i].cantidad);
	};
	
	
	
	if( this.carrito.cliente == null ){
		
		//si es caja comun
		Ext.getCmp('Mostrador-doVentaCliente').setValue("Caja Comun");
		Ext.getCmp('Mostrador-doVentaClienteCredito' ).setVisible(false);
		Ext.getCmp('Mostrador-doVentaClienteCreditoRestante').setVisible(false);
		Ext.getCmp('Mostrador-doVentaTipoContado').setVisible(true);
		Ext.getCmp('Mostrador-doVentaTipoCredito').setVisible(false);		
		Ext.getCmp('Mostrador-doVentaFacturar').setVisible(false);
		Ext.getCmp('Mostrador-doVentaDesc' ).setVisible(false);
		total = subtotal;
		Ext.getCmp('Mostrador-doVentaNuevoCliente' ).setVisible(true);
		Aplicacion.Mostrador.currentInstance.carrito.factura = false;
		Ext.getCmp('Mostrador-doVentaCobrar').setVisible(true);
		
	}else{
		
		//es un cliente
		Ext.getCmp('Mostrador-doVentaNuevoCliente' ).setVisible(false);		
		Ext.getCmp('Mostrador-doVentaCliente').setValue( this.carrito.cliente.nombre + "  " + this.carrito.cliente.rfc );
		
		Ext.getCmp('Mostrador-doVentaClienteCredito' ).setVisible(true);
		Ext.getCmp('Mostrador-doVentaClienteCredito').setValue( POS.currencyFormat(this.carrito.cliente.limite_credito) );

		if(this.carrito.cliente.limite_credito > 0){
			Ext.getCmp('Mostrador-doVentaClienteCreditoRestante').setVisible(true);
			Ext.getCmp('Mostrador-doVentaClienteCreditoRestante').setValue( POS.currencyFormat( this.carrito.cliente.credito_restante ));
		}else{
			Ext.getCmp('Mostrador-doVentaClienteCreditoRestante').setVisible(false);			
			Aplicacion.Mostrador.currentInstance.carrito.tipoDeVenta = "contado";
			Ext.getCmp('Mostrador-doVentaCobrar').setVisible(true);
		}


		Ext.getCmp('Mostrador-doVentaFacturar').setVisible(true);
		
		if( this.carrito.cliente.descuento > 0 ){
			Ext.getCmp('Mostrador-doVentaDesc' ).setVisible(true);
			Ext.getCmp('Mostrador-doVentaDesc').setValue( POS.currencyFormat ( subtotal * (this.carrito.cliente.descuento / 100)) + " ( " + this.carrito.cliente.descuento+"% )" );			
		}else{
			Ext.getCmp('Mostrador-doVentaDesc' ).setVisible(false);			
		}


		total = subtotal - ( subtotal * (this.carrito.cliente.descuento / 100));

		if( total <= this.carrito.cliente.credito_restante){
			Ext.getCmp('Mostrador-doVentaTipoContado').setVisible(false);
			Ext.getCmp('Mostrador-doVentaTipoCredito').setVisible(true);
			Aplicacion.Mostrador.currentInstance.carrito.tipoDeVenta = Ext.getCmp('Mostrador-doVentaTipoCredito').getValue();
			
			if( Aplicacion.Mostrador.currentInstance.carrito.tipoDeVenta == "contado" ){
				Ext.getCmp('Mostrador-doVentaCobrar').setVisible(true);
			}else{
				Ext.getCmp('Mostrador-doVentaCobrar').setVisible(false);
			}

		}else{
			Ext.getCmp('Mostrador-doVentaTipoContado').setVisible(true);
			Ext.getCmp('Mostrador-doVentaTipoCredito').setVisible(false);
			Aplicacion.Mostrador.currentInstance.carrito.tipoDeVenta = "contado";
			
			Ext.getCmp('Mostrador-doVentaCobrar').setVisible(true);
			
		}
		
	}
	
	
	Ext.getCmp('Mostrador-doVentaSub' ).setValue( POS.currencyFormat( subtotal ) );
	Ext.getCmp('Mostrador-doVentaTotal' ).setValue( POS.currencyFormat( total ) );

	this.carrito.subtotal = subtotal;
	this.carrito.total = total;
	
};


/*
 * Se llama para crear por primera vez el panel de venta
 **/
Aplicacion.Mostrador.prototype.doVentaPanelCreator = function (  ){
	
	
	//cancelar busqueda
	dockedCancelar = {
		xtype : 'button',		
        text: 'Regresar',
		ui :'back',
		handler : function(){
			sink.Main.ui.setActiveItem( Aplicacion.Mostrador.currentInstance.mostradorPanel , 'slide');
		}
    };


	//cancelar busqueda
	dockedNuevo = {
		xtype : 'button',
		id : 'Mostrador-doVentaNuevoCliente',
        text: 'Nuevo Cliente',
		ui :'normal',
		handler : function(){
			sink.Main.ui.setActiveItem( Aplicacion.Clientes.currentInstance.nuevoClientePanel , 'fade');
		}
    };

	//hacer la venta
	dockedVender = {
		xtype : 'button',		
        text: 'Vender',
		ui :'confirm',
		handler : function (){
			Aplicacion.Mostrador.currentInstance.doVenta();
		} 

    };

	//toolbar
	dockedItems = {
		xtype: 'toolbar',
        dock: 'bottom',
        items: [ dockedCancelar , { xtype: 'spacer' }, dockedNuevo, dockedVender ]
	};
	
	this.doVentaPanel = new Ext.form.FormPanel({                                                       
		dockedItems : dockedItems,
		items: [{
			xtype: 'fieldset',
		    title: 'Datos de la venta',
			items: [

				
				new Ext.form.Text({ label : 'Cliente', 			 id: 'Mostrador-doVentaCliente' }),
				new Ext.form.Text({ label : 'Limite de Credito', id: 'Mostrador-doVentaClienteCredito' }),
				new Ext.form.Text({ label : 'Credito restante',  id: 'Mostrador-doVentaClienteCreditoRestante' }),
				
				new Ext.form.Text({ label : 'Tipo de Venta',  id: 'Mostrador-doVentaTipoContado', value : "Contado" }),
				new Ext.form.Select({
					listeners : {
						"change" : function ( a, val ){
							Aplicacion.Mostrador.currentInstance.carrito.tipoDeVenta = val;
							if(val == "credito"){
								Ext.getCmp("Mostrador-doVentaFacturar").hide( Ext.anims.fade );
								Aplicacion.Mostrador.currentInstance.carrito.factura = false;
								Ext.getCmp('Mostrador-doVentaCobrar').hide( Ext.anims.fade );
							}else{
								Ext.getCmp("Mostrador-doVentaFacturar").show( Ext.anims.fade );
								Aplicacion.Mostrador.currentInstance.carrito.factura = Ext.getCmp("Mostrador-doVentaFacturar").getValue() == 1;
								Ext.getCmp('Mostrador-doVentaCobrar').show( Ext.anims.fade );								
							}
						},
						"show" : function (){
							Aplicacion.Mostrador.currentInstance.carrito.tipoDeVenta = Ext.getCmp('Mostrador-doVentaTipoCredito').getValue();
						}
					},
					label : 'Tipo de Venta', 
					id: 'Mostrador-doVentaTipoCredito', 
					options: [{text: 'Contado',  value: 'contado'},{text: 'Credito', value: 'credito'}] 
				}),

				new Ext.form.Toggle({ 
					listeners : {
						"change" : function ( a, b, newVal, oldVal ){
							Aplicacion.Mostrador.currentInstance.carrito.factura = newVal == 1;
						}
					},
					id : 'Mostrador-doVentaFacturar', 
					label : 'Facturar' 
				}),
				
				
				new Ext.form.Text({ label : 'Subtotal', 		id: 'Mostrador-doVentaSub' }),

				new Ext.form.Text({ label : 'Descuento',		id: 'Mostrador-doVentaDesc' }),
				new Ext.form.Text({ label : 'Total', 			id: 'Mostrador-doVentaTotal' })
			]},
			{
				id : "Mostrador-doVentaCobrar",
				xtype: 'fieldset',
				hidden : true,
				items: [
					new Ext.form.Text({ 
						label : 'Efectivo', 
						id: 'Mostrador-doVentaEfectivo',
						listeners : {
							"focus" : function (){
								kconf = {
									type : 'num',
									submitText : 'Realizar venta',
									callback : function ( campo ){
										Aplicacion.Mostrador.currentInstance.doVenta();
									}
								};

								POS.Keyboard.Keyboard( this, kconf );								
							}
						}
						})
				]
			}

	]});


	
};













POS.Apps.push( new Aplicacion.Mostrador() );
