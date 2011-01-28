

Aplicacion.Inventario = function (	){

	return this._init();
};

Aplicacion.Inventario.prototype._init = function (){
	if(DEBUG){
		console.log("Inventario: construyendo");
	}


	//cargar el inventario existente desde el servidor
	this.cargarInventario();
	
	//crear el panel de que contiene la lista del inventario
	this.listaInventarioPanelCreate();

	//crear el panel de detalle de inventario,
	//que es el detalle de cada producto
	this.detalleInventarioPanelCreator();

	//crar los paneles para sutir del wizard
	this.surtirWizardCreator();



	Aplicacion.Inventario.currentInstance = this;
	
	return this;
};

Aplicacion.Inventario.prototype.getConfig = function (){


    if(POS.U.g === null){
        window.location = "sucursal.html";
    }


	return POS.U.g ? {
		    text: 'Inventario',
		    cls: 'launchscreen',
		    card :	this.listaInventarioPanel,		
		    items: [{
			    text: 'Inventario Actual',
			    card :	this.listaInventarioPanel,
			    leaf: true
		},{
			text: 'Surtir',

				card :	this.surtirWizardPanel,
				leaf: true

		}]
	} : {
		    text: 'Inventario',
		    cls: 'launchscreen',
		    card :	this.listaInventarioPanel,		
		    items: [{
			    text: 'Inventario Actual',
			    card :	this.listaInventarioPanel,
			    leaf: true
		}]
	};
};





/* ***************************************************************************
   * Panel de la lista del inventario
   * 
   *************************************************************************** */
   
/**
 * Registra el model para listaInventario
 */
Ext.regModel('listaInventarioModel', {


	fields: [
		{name: 'descripcion',	  type: 'string'},
		{name: 'productoID',	  type: 'float'} 
	]
});



/**
 * store con la lista del inventario
 */
Aplicacion.Inventario.prototype.inventarioListaStore = new Ext.data.Store({
	model: 'listaInventarioModel',
	sorters: 'descripcion',
		   
	getGroupString : function(record) {
		return record.get('descripcion')[0];
	}
});

/* 
 * escructura que contiene el inventario que se cargo por ultima vez
 * */
Aplicacion.Inventario.prototype.Inventario = {
	lista : null,
	lastUpdate : null
};


Aplicacion.Inventario.prototype.cargarInventario = function ()
{
	
	if(DEBUG){
		console.log("Cargando el inventario online...");
	}

	Ext.Ajax.request({
		url: '../proxy.php',
		scope : this,
		params : {
			action : 400
		},
		success: function(response, opts) {
			try{
				inventario = Ext.util.JSON.decode( response.responseText );				
			}catch(e){
				return POS.error(response, e);
			}

			
			if( !inventario.success ){
				//volver a intentar
				return this.cargarInventario();
			}
			
			if(DEBUG){
				console.log("Inventario retrived !", inventario);
			}
			
			this.Inventario.productos = inventario.datos;
			this.Inventario.lastUpdate = Math.round(new Date().getTime()/1000.0);
            this.Inventario.hash = inventario.hash;

			//agregarlo en el store
			this.inventarioListaStore.loadData( inventario.datos );

		},
		failure: function( response ){
			POS.error( response );
		}
	}); 

};











Aplicacion.Inventario.prototype.listaInventarioPanel = null;

Aplicacion.Inventario.prototype.listaInventarioPanelShow = function ()
{
	if( this.listaInventarioPanel ){
		this.listaInventarioPanelShow();
	}else{
		this.listaInventarioPanelCreate();		
	}
	
	sink.Main.ui.setActiveItem( this.listaInventarioPanel , 'slide');
};

Aplicacion.Inventario.prototype.listaInventarioPanelCreate = function ()
{

	//busqueda en la lista
	var buscar = {
		xtype : 'textfield',
		placeHolder : "Buscar",
		listeners : {
			'focus' : function (){

				kconf = {
					type : 'alfa',
					submitText : 'Buscar',
					callback : null
				};
				POS.Keyboard.Keyboard( this, kconf );
			}
		}
	};



	//toolbar
	var dockedItems = {
		xtype: 'toolbar',
		dock: 'bottom',
		items: [ buscar , { xtype: 'spacer' } ]
	};
	


	this.listaInventarioPanel = new Ext.Panel({
		layout: 'fit',
		items: [{
			xtype: 'list',
			store: this.inventarioListaStore,
			itemTpl: '<div class=""><b>{productoID}</b>&nbsp;{descripcion}</div>',
			grouped: true,
			indexBar: true,
			listeners : {
				"selectionchange"  : function ( view, nodos, c ){
					
					if(nodos.length > 0){
						Aplicacion.Inventario.currentInstance.detalleInventarioPanelShow( nodos[0] );
					}

					//deseleccinar
					view.deselectAll();
				}
			}
			
		}]
	});

	
};





/* ***************************************************************************
   * Detalles de un producto
   * 
   *************************************************************************** */
   
   
//contiene el panel de detalle de inventario
Aplicacion.Inventario.prototype.detalleInventarioPanel = null;

//muestra la forma de detalles de inventario
//recibe un objeto con el producto a mostrar
Aplicacion.Inventario.prototype.detalleInventarioPanelShow = function( producto )
{
	if(this.detalleInventarioPanel){
		this.detalleInventarioPanelCreator();
	}
	
	this.detalleInventarioPanelUpdater( producto );
	sink.Main.ui.setActiveItem( this.detalleInventarioPanel , 'slide'); 
};


//actualiza los datos del panel de detalle inventario ya creado,
//asi solo se modifican los datos y no se crea un nuevo panel
Aplicacion.Inventario.prototype.detalleInventarioPanelUpdater = function( producto )
{
	if(DEBUG){
		console.log("updating detalles del producto", producto );
	}

	detallesPanel = Aplicacion.Inventario.currentInstance.detalleInventarioPanel;

	if(producto.get("tratamiento")) {
		//Ext.get("procesarProductoBoton").show();		
	}else{
		//este producto no se procesa
		//Ext.get("procesarProductoBoton").hide();
	}

	detallesPanel.setValues({

        productoID  :   producto.get('productoID'),
        descripcion :   producto.get('descripcion'),
        precioVenta :   POS.currencyFormat( producto.get('precioVenta') ),
        existenciasOriginales : producto.get('existenciasOriginales') + " " + producto.get('medida')+"s",
        existenciasProcesadas : producto.get('existenciasProcesadas') + " " + producto.get('medida')+"s"


    });

};


//crea el panel de detalle de inventario
Aplicacion.Inventario.prototype.detalleInventarioPanelCreator = function()
{
	
	opciones = [{
		text: 'Agregar a Mostrador',
		ui: 'normal',
		handler : function (){
	        sink.Main.ui.setActiveItem( Aplicacion.Mostrador.currentInstance.mostradorPanel , 'slide');

			var id = Aplicacion.Inventario.currentInstance.detalleInventarioPanel.getValues().productoID;
			Aplicacion.Mostrador.currentInstance.agregarProductoPorID( id );
		}
	}];

	
    if(POS.U.g) {
	  	opciones.push({ 
			text: 'Procesar este producto', 
			id : "procesarProductoBoton" , 
			ui:  "action", 
			handler : this.procesarProductoPanelShow ,
			listeners : {
				"show" : function(){
					console.log("weeee");
					var vals = Aplicacion.Inventario.currentInstance.detalleInventarioPanel.getValues();
					if(vals.tratamiento){
						Ext.get("procesarProductoBoton").hide();
					}else{
						Ext.get("procesarProductoBoton").show();						
					}
				}
			}
		});
   	}



	var dockedItems = [new Ext.Toolbar({
		ui: 'dark',
		dock: 'bottom',
		items: [{xtype:"spacer"}].concat( opciones )
	})];	
	
	
	this.detalleInventarioPanel = new Ext.form.FormPanel({														 
		dockedItems: dockedItems,
		items: [{
			xtype: 'fieldset',
			title: 'Detalles de Producto',
			scroll: false,
			instructions: '',
			defaults : {
				disabled : false
			},
		
			items: [
				new Ext.form.Text({ name: 'productoID', label: 'ID del producto' }),
				new Ext.form.Text({ name: 'descripcion', label: 'Descripcion' }),
				new Ext.form.Text({ name: 'precioVenta', label: 'Precio sugerido' }),
				new Ext.form.Text({ name : 'existenciasOriginales', label: 'Existencias Originales' }),
				new Ext.form.Text({ name : 'existenciasProcesadas', label: 'Existencias' }),
	//			new Ext.form.Text({ name : 'existenciasMinimas', label: 'Existencias Minimas' })
			]}
	]});

};


Aplicacion.Inventario.prototype.detalleInventarioSurtirEsteProd = function()
{
	if(DEBUG){
		console.log("surtiendo producto " + Aplicacion.Inventario.currentInstance.detalleInventarioPanel.getRecord().data.productoID);
	}
	
	sink.Main.ui.setActiveItem( Aplicacion.Inventario.currentInstance.surtirWizardPanel , 'fade');	
	
	Aplicacion.Inventario.currentInstance.surtirAddItem( Aplicacion.Inventario.currentInstance.detalleInventarioPanel.getRecord().data );
};




/* ***************************************************************************
   * Surtir productos 
   * 
   *************************************************************************** */


Aplicacion.Inventario.prototype.carritoSurtir = {
		items : [],
		otherData: null
};


Aplicacion.Inventario.prototype.surtirAddItem = function ( item )
{
	if(DEBUG){
		console.log("Agregnado producto ",	item , " a la peticion");
	}
	
	
	for (var i = this.carritoSurtir.items.length - 1; i >= 0; i--){
		if( this.carritoSurtir.items[i].productoID == item.productoID ){
			if(DEBUG){
				console.log( "este producto ya existe en el carrito para surtir" );
			}
			this.refreshSurtir();
			return ;
		}
	}
	
	this.carritoSurtir.items.push( item );
	
	this.refreshSurtir();
	
};

Aplicacion.Inventario.prototype.refreshSurtir = function ()
{
	
	if(DEBUG){
		console.log("Refrescanco el carrito para surtir...");
	}
	
	carrito = this.carritoSurtir;

    if(carrito.items.length > 0){
    	Ext.getCmp("Inventario-confirmarPedido").show( Ext.anims.slide );
    }else{
    	Ext.getCmp("Inventario-confirmarPedido").hide( Ext.anims.slide );
    }


	var html = "<table border=0>";
	
	html += "<tr class='top'>";
	html += "<td>Descripcion</td>";
    html += "<td></td>";
	html += "<td colspan=2>Cantidad</td>";
//	html += "<td>Precio Intersucursal</td>";
	//html += "<td>Total</td>";

	html += "</tr>";
	
	for (var i=0; i < carrito.items.length; i++){

		if(carrito.items[i].cantidad === null){
			carrito.items[i].cantidad = 1;
		}
		
		if( i == carrito.items.length - 1 )
			html += "<tr class='last'>";
		else
			html += "<tr >";		
		
		html += "<td>" + carrito.items[i].productoID + " " + carrito.items[i].descripcion+ "</td>";
		html += "<td > <span class = 'boton' onClick = 'Aplicacion.Inventario.currentInstance.quitarDelCarrito(" + carrito.items[i].productoID + ")'>Quitar</span> </td>";
		html += "<td colspan=2 > <div id='Inventario-carritoCantidad"+ carrito.items[i].productoID +"'></div></td>";

		//html += "<td > </td>";
//		html += "<td> <div style='color: green'>"+ POS.currencyFormat(carrito.items[i].precioIntersucursal) +"</div></td>";
//		html += "<td>" + POS.currencyFormat( carrito.items[i].cantidad * carrito.items[i].precioIntersucursal )+"</td>";
		
		html += "</tr>";
	}
	
	html += "</table>";
	
	Ext.getCmp("Inventario-surtirTabla").update(html);
	
	
	
	for (i=0; i < carrito.items.length; i++){
	
	
		a = new Ext.form.Text({
			renderTo : "Inventario-carritoCantidad"+ carrito.items[i].productoID ,
			id : "Inventario-carritoCantidad"+ carrito.items[i].productoID + "Text",
			value : carrito.items[i].cantidad,
			prodID : carrito.items[i].productoID,
			width: 150,
			placeHolder : "Cantidad",
			listeners : {
				'focus' : function (){

					kconf = {
						type : 'num',
						submitText : 'Aceptar',
						callback : function ( campo ){
									//console.log(campo, this)
									
									carrito = Aplicacion.Inventario.currentInstance.carritoSurtir;
									
									for (var j=0; j < carrito.items.length; j++) {
										if(campo.prodID == carrito.items[j].productoID){
											carrito.items[j].cantidad = parseFloat( campo.getValue() );
											break;											
										}
									}
									
									Aplicacion.Inventario.currentInstance.refreshSurtir();
						}
					};
					
					POS.Keyboard.Keyboard( this, kconf );
				}
			}
		
		});


	}

};

//quita del carrito el producto indicado
Aplicacion.Inventario.prototype.quitarDelCarrito = function ( id_producto )
{
    if(DEBUG){
        console.log("Removiendo del carrito.");
    }
    
    carrito = Aplicacion.Inventario.currentInstance.carritoSurtir;
    for (var i = carrito.items.length - 1; i >= 0; i--){
        if( carrito.items[i].productoID == id_producto ){
            carrito.items.splice( i ,1 );
            break;
        }
    }
    Aplicacion.Inventario.currentInstance.refreshSurtir();
    
};

Aplicacion.Inventario.prototype.surtirWizardPanel = null;

Aplicacion.Inventario.prototype.surtirWizardFromProveedor = false;

Aplicacion.Inventario.prototype.surtirWizardCreator = function (){
	
	if(DEBUG){
		console.log("Creando wizard par surtir")
	}
	
	bar = [{
		text: 'Agregar producto',
		ui: 'normal',
		handler : function( t ){
			//iniciar wizard
			Aplicacion.Inventario.currentInstance.surtirWizardPopUpPanel.show();
		}
	},{
		xtype : 'spacer'
	},{
		text: 'Cancelar pedido',
		ui: 'normal',
		handler : function( t ){
			
			Aplicacion.Inventario.currentInstance.carritoSurtir.items = [];
			Aplicacion.Inventario.currentInstance.refreshSurtir();
			
		}		
	},{
		text: 'Confirmar pedido',
		ui: 'action',
        hidden: true,
        id : 'Inventario-confirmarPedido',
		handler : function( t ){
            Aplicacion.Inventario.currentInstance.surtirCarritoValidator();
		}		
	}];


	//crear el panel
	this.surtirWizardPanel = new Ext.Panel({
			dockedItems : new Ext.Toolbar({
					ui: 'dark',
					dock: 'bottom',
					items: bar
				}),
			html : "",
			id : "Inventario-surtirTabla",
			cls : "Tabla"
		});
	
	
	
	
	
	
	//crear los paneles de los pop-ups que el wizard utilizara
	this.surtirWizardPopUpPanel = new Ext.Panel({
		floating: true,
		centered: true,
		modal: true,
		width: 475,
		height: 450,
		dockedItems: [{
			dock: 'top',
			xtype: 'toolbar',
			title: 'Seleccione el producto'
		},{
			dock: 'bottom',
			xtype: 'toolbar',
			items: [{
				text: 'Cancelar',
				handler: function() {
					Aplicacion.Inventario.currentInstance.surtirWizardPopUpPanel.hide();
				}
			},{
				xtype: 'spacer'
			},{
				text: 'Seleccionar',
				ui: 'action',
				handler: function () {
					r = Aplicacion.Inventario.currentInstance.surtirWizardPopUpPanel.getComponent(0).getSelectedRecords();
					
					for( a = 0 ; a < r.length ; a ++ ){
						Aplicacion.Inventario.currentInstance.surtirAddItem( r[a].data );
					}

					Aplicacion.Inventario.currentInstance.surtirWizardPopUpPanel.hide();					
				}
			}]
		}],
		items: [{
			multiSelect : true,
			xtype: 'list',
			ui: 'dark',
			store: this.inventarioListaStore,
			itemTpl: '<div class=""><b>{productoID}</b> {descripcion}</div>',
			grouped: true,
			indexBar: true	
		}]
	});
	
	
	

	
};


Aplicacion.Inventario.prototype.surtirWizardPopUpPanel = null;

//valida que esten escritas las cantidades de cada producto qeu se va a surtir
Aplicacion.Inventario.prototype.surtirCarritoValidator = function(){

    for( var i = 0; i < this.carritoSurtir.items.length; i++)
    {
        //valida que al cantidad sea numerica y >= 0 TODO: hay que ver como eliminar el 0
        if( !( this.carritoSurtir.items[i].cantidad && /^\d+(\.\d+)?$/.test(this.carritoSurtir.items[i].cantidad + '') ) ){
            Ext.Msg.alert("Inventario","Error: verifique la cantidad del producto: " + this.carritoSurtir.items[i].descripcion);

            return;

        }
    }

    Aplicacion.Inventario.currentInstance.surtirCarrito();
}

//manda una solicitud al admin para que le surta a la sucursal, lo que haya en el carrito
//data=[{"id_pruducto":"1","cantidad":"55.5"},{"id_pruducto":"1","cantidad":"2"}]
//damos in tratamiento a this.carritoSurtir.items, para agregar la propiedad id_producto, ya que
//es empleada en el servidor
Aplicacion.Inventario.prototype.surtirCarrito = function(){

	if(this.surtirWizardFromProveedor){
		console.log("Surtiendo para proveedor ");
	}else{
		console.log("Surtiendo para centro de distribucion ");		
	}

    for( var i = 0; i < this.carritoSurtir.items.length; i++)
    {
        this.carritoSurtir.items[i].id_producto = this.carritoSurtir.items[i].productoID;
    }

    Ext.Ajax.request({
        url: '../proxy.php',
        scope : this,
        params : {
            action : this.surtirWizardFromProveedor ? 903 : 209,
            data : Ext.util.JSON.encode( this.carritoSurtir.items )
        },
        success: function(response, opts) {
            try{
                r = Ext.util.JSON.decode( response.responseText );
            }catch(e){
                return POS.error(response, e);
            }

            
            if( !r.success ){
                Ext.Msg.alert("Inventario","Intente de nuevo");
                Aplicacion.Inventario.currentInstance.carritoSurtir.items = [];
                Aplicacion.Inventario.currentInstance.refreshSurtir();
                return;
            }

            Ext.Msg.alert("Inventario","Solicitud enviada con exito");

            Aplicacion.Inventario.currentInstance.carritoSurtir.items = [];
            Aplicacion.Inventario.currentInstance.refreshSurtir();



        },
        failure: function( response ){
            POS.error( response );
        }
    }); 


};




POS.Apps.push( new Aplicacion.Inventario() );


