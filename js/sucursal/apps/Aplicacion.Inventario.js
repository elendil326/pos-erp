

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

	this.procesarProducto = new Aplicacion.Inventario.ProcesarProducto();

	Aplicacion.Inventario.currentInstance = this;
	
	return this;
};

Aplicacion.Inventario.prototype.getConfig = function (){


    if(POS.U.g === null){
        window.location = "sucursal.html";
    }


	return {
		    text: 'Inventario',
		    cls: 'launchscreen',
		    card :	this.listaInventarioPanel,	
			leaf : true
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
		var s = record.get('descripcion');
		return s.split(" ")[0];
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
			//itemTpl: '<div class=""><b>{productoID}</b>&nbsp;{descripcion} </div>',
			itemTpl: '<div class="">'
				+'<table width = 100% >'
					+'<tr width = 100%>'
						+'<td width = 20%>'
							+'<b>{productoID}</b> &nbsp; {descripcion}'
						+'</td>'
						+'<td width = 30%>'
							+'<b>Original</b> &nbsp; {existenciasOriginales} {medida}s'
						+'</td>'
						+'<td width = 10% >${precioVentaSinProcesar}'
						+'</td>'
						+'<td width = 30%>'
							+'<b>Procesado</b> &nbsp; {existenciasProcesadas} {medida}s'
						+'</td>'
						+'<td width = 10% > ${precioVenta} '
						+'</td>'
					+'</tr>'
				+'</table>'
				+'</div>',
			grouped: true,
			indexBar: true,
			listeners : {
				"activate" : function(){
					if(DEBUG){
						console.log("Mostrando lista de inventario*************");
					}
					Aplicacion.Inventario.currentInstance.inventarioListaStore.clearFilter();
				},
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

	Aplicacion.Inventario.currentInstance.procesarProducto.setProducto( producto );

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
			handler : function(){
					var thiz = Aplicacion.Inventario.currentInstance;

					sink.Main.ui.setActiveItem( thiz.procesarProducto.getPanel() , 'slide');				
			} ,
			listeners : {
				"show" : function(){

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
				new Ext.form.Text({ name : 'existenciasOriginales', label: 'Existencias originales' }),
				new Ext.form.Text({ name : 'existenciasProcesadas', label: 'Existencias procesadas' }),
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


Aplicacion.Inventario.prototype.carritoCambiarCantidad = function ( id, qty, forceNewValue )
{

	carrito = this.carritoSurtir;
		
	
	for (var i = carrito.items.length - 1; i >= 0; i--){
	
	
		if( carrito.items[i].idUnique == id ){
			
			if(forceNewValue){
				carrito.items[i].cantidad = qty;
			}else{
				carrito.items[i].cantidad += qty;
			}
			
			if(carrito.items[i].cantidad <= 0){
				carrito.items[i].cantidad = 1;
			}
			
			this.refreshSurtir();
			break;
		}
	}
	
};

Aplicacion.Inventario.prototype.carritoSurtir = {
		items : [],
		otherData: null
};

Aplicacion.Inventario.prototype.idUnique = 0;


Aplicacion.Inventario.prototype.copy = function( o ) {
	if (typeof o != "object" || o === null) return o;
	var r = o.constructor == Array ? [] : {};
	for (var i in o) {
		r[i] = this.copy(o[i]);
	}
	return r;
};

Aplicacion.Inventario.prototype.surtirAddItem = function ( item )
{
	if(DEBUG){
		console.log("Agregnado producto ",	item , " a la peticion");
	}
	
	var cont_prod = 0;
	
	for (var i = this.carritoSurtir.items.length - 1; i >= 0; i--){
	
		if( this.carritoSurtir.items[i].productoID == item.productoID ){
			cont_prod++;
		}
		
		if( cont_prod >= 2 ){
		
		    if( DEBUG ){
				console.log( "este producto ya existe en el carrito para surtir" );
			}						
			
			Ext.Msg.alert("Proveedores", "El producto " + item.descripcion + " ya se encuentra en el carrito");
			
			return ;
		
		}
		
		this.refreshSurtir();
		
	}
	
	item.cantidad = 1;
	item.procesado = "true";
	
	this.idUnique++;
	
	if( DEBUG ){
	    console.log("Aplicacion.Inventario.currentInstance.idUnique : ", this.idUnique );
	}
	
	var _item = this.copy( item );
	
	_item.idUnique = _item.productoID + "_" + this.idUnique;
	
	this.carritoSurtir.items.push( _item );
	
	if( DEBUG ){
	    console.log("Agregado al carrito : ", _item );
	}
	
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
	html += "<td>&nbsp;</td>";
    html += "<td>&nbsp;</td>";
	html += "<td align='center'  colspan=4>Cantidad</td>";
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
		
		
		var m ;
		switch(carrito.items[i].medida){
			case "kilogramo": m = "kgs"; break;
			case "pieza": m = "pzas"; break;
			case "litro": m = "lts"; break;						
		}
		
		html += "<td style='width: 25%;' ><b>" + carrito.items[i].productoID + "</b> &nbsp; " + carrito.items[i].descripcion+ "</td>";
		html += "<td style='width: 15%;' > <div id='Inventario-carritoTipo"+ carrito.items[i].idUnique +"'></div></td>";
		html += "<td style='width: 8%;' > <span class = 'boton' onClick = 'Aplicacion.Inventario.currentInstance.quitarDelCarrito(" + carrito.items[i].idUnique+ ")'><img src='../media/icons/close_16.png'></span> </td>";		
		html += "<td  align='center'  style='width: 6.1%;'> <span class='boton' onClick=\"Aplicacion.Inventario.currentInstance.carritoCambiarCantidad('"+ carrito.items[i].idUnique + "', -1, false)\">&nbsp;-&nbsp;<img src='../media/icons/arrow_down_16.png'></span></td>";
		html += "<td style='width: 6.8%;' > <div id='Inventario-carritoCantidad"+ carrito.items[i].idUnique +"'></div></td><td>"+m+"</td>";
		html += "<td  align='center'  style='width: 6.1%;'> <span class='boton' onClick=\"Aplicacion.Inventario.currentInstance.carritoCambiarCantidad('"+ carrito.items[i].idUnique +"', 1, false)\"><img src='../media/icons/arrow_up_16.png'>&nbsp;+&nbsp;</span></td>";
		//html += "<td > </td>";
		//html += "<td> <div style='color: green'>"+ POS.currencyFormat(carrito.items[i].precioIntersucursal) +"</div></td>";
		//html += "<td>" + POS.currencyFormat( carrito.items[i].cantidad * carrito.items[i].precioIntersucursal )+"</td>";
		
		html += "</tr>";
	}
	
	html += "</table>";
	
	Ext.getCmp("Inventario-surtirTabla").update(html);
	
	
	
	for (i=0; i < carrito.items.length; i++){
	
	
		a = new Ext.form.Text({
			renderTo : "Inventario-carritoCantidad"+ carrito.items[i].idUnique ,
			id : "Inventario-carritoCantidad"+ carrito.items[i].idUnique + "Text",
			value : carrito.items[i].cantidad,
			idUnique : carrito.items[i].idUnique,
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
										if(campo.idUnique== carrito.items[j].idUnique){
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



		b = new Ext.form.Select({
			renderTo : "Inventario-carritoTipo"+ carrito.items[i].idUnique ,
			id : "Inventario-carritoTipo"+ carrito.items[i].idUnique + "Value",
			idUnique : carrito.items[i].idUnique ,
			value : carrito.items[i].procesado,
			options: [
                {text: 'Procesada',  value: 'true'},
                {text: 'Original', value: 'false'}
            ],
              listeners : {
                    "change" : function (){
                         
                        carrito = Aplicacion.Inventario.currentInstance.carritoSurtir

	                    for (var i = carrito.items.length - 1; i >= 0; i--){
                        
		                    if( carrito.items[i].idUnique == this.idUnique ){                 
                                carrito.items[i].procesado = this.getValue();
                            }
                                        
                        }
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
        if( carrito.items[i].idUnique == iidUnique ){
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
			Aplicacion.Inventario.currentInstance.surtirWizardPopUpPanel.showBy(this);
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
			layout: 'fit',
			store: this.inventarioListaStore,
			itemTpl: '<div class=""><b>{productoID}</b> {descripcion}</div>',
			//grouped: true,
			indexBar: false	
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
	    if(DEBUG){
		    console.log("Surtiendo para proveedor ");
		}
	}else{
	    if(DEBUG){
		    console.log("Surtiendo para centro de distribucion ");		
		}
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




Aplicacion.Inventario.ProcesarProducto = function(){
	
	/**
	  *
	  * panel general que contiene dos paneles
	  * y el docked items para esta sub-aplicacion
	  * esos dos paneles son los detalles del proceso
	  * y un panel con html vacio para poder crear una
	  * tabla donde se iran insertando los productos
	  * que tambien resultaron de este proceso
	  **/
	var panel,
	
	/**
	  * El store del producto a procesar
	  **/
		producto,
		
	/**
	  * Panel con la lista de productos
	  **/		
		listaDeProductos,
		
	/**
	  * crear el panel de productos y el 
	  * panel principal que tiene el ingreso
	  * de detalles
	  **/		
		crearPanel,
		
	/**
	  * actualizar los detalles de panel,
	  * con el producto que se encuentra en 
	  * <i>producto</i>
	  **/		
		updatePanel,
		
	/**
	  * validar la informacion para revisar 
	  * existencias
	  **/		
		validar,
		
	/**
	  * panel que va dentro de <i>panel</i>
	  * con los detalles, es un field 
	  * para accesso rapido a sus detalles
	  **/		
		panelDetalles,
		
	/**
	  *
	  *
	  **/
		otrosProductos,
		
	/**
	  *
	  **/
		foo,
		
	/**
	  * arreglo con los productos derivados
	  **/
		carritoDeDerivados = [],
		
	/**
	  * 
	  *
	  **/	
		renderCarrito;
	
	
	
	/**
	  * Crear el panel.
	  * Crea el panel y lo guarda internamente asi como crea la el
	  * panel de la lista de productos
	  *	
	  * @return void
	  **/
	crearPanel = function (){

		
		panelDetalles = new Ext.form.FormPanel({														 
			items: [{
				xtype: 'fieldset',
				title: 'Detalles de proceso',
				instructions: '',
				defaults : {
					disabled : false
				},
				items: [
					new Ext.form.Text({ 
						name: 'tomado', 
						label: 'Tomado para procesar'
					}),
					new Ext.form.Text({ 
						name: 'resultante', 
						label: 'Resultante procesado',
						listeners: {
							"focus" : function (){
								kconf = {
									type : 'num',
									submitText : 'Aceptar',
									callback : null
								};
								if(DEBUG){
								    console.log(this)
								}    
								POS.Keyboard.Keyboard( this, kconf );								
							}
						}
					}),
					new Ext.form.Text({ 
						name: 'merma', 
						label: 'Merma resultante',
						listeners: {
							"focus" : function (){
								kconf = {
									type : 'num',
									submitText : 'Aceptar',
									callback : null
								};
								if(DEBUG){
								    console.log(this)
								}
								POS.Keyboard.Keyboard( this, kconf );								
							}
						}
					})
				]}
		]});
		
		
		//panel de botones
		otrosProductos = new Ext.Panel({
			padding : "20%",
			cls: "Tabla",
			html : ""
		});
		
	
		panel = new Ext.Panel({
			layout: {
			    type: 'vbox',
			    padding: '5',
			    align: 'left'
			},
			dockedItems: [{
	            dock: 'bottom',
	            xtype: 'toolbar',
	            items: [{
	                text: 'Regresar a detalles de producto',
	                ui: 'back',
	                handler: function() {                   
	                   sink.Main.ui.setActiveItem( Aplicacion.Inventario.currentInstance.detalleInventarioPanel , 'slide');
	                }
	            },{
	                xtype: 'spacer'
	            },{
	                text: 'Agregar producto resultante',
	                ui: 'nomral',
	                handler: function() { 
						Aplicacion.Inventario.currentInstance.procesarProducto.mostrarLista( this );
	                }
		
				},{
	                text: 'Registar proceso',
	                ui: 'action',
	                handler: function() {                   
						Aplicacion.Inventario.currentInstance.procesarProducto.send();
	                }

				}]
	        }],
			items  : [ panelDetalles, otrosProductos ]
		});
		
		
		
		
		/**
		  * panel de seleccion de producto derivado
		  *
		  **/
		var productoDerivadoDetallesPanel = new Ext.form.FormPanel({
			dockedItems: [{
	            dock: 'bottom',
	            xtype: 'toolbar',
	            items: [{
	                text: 'Cancelar',
	                handler: function() {                   
						Aplicacion.Inventario.currentInstance.procesarProducto.getListaDeProductosPanel().hide();
	                }
	            },{
	                xtype: 'spacer'
	            },{
	                text: 'Aceptar',
	                ui: 'action',
	                handler: function() { 
						var pp = Aplicacion.Inventario.currentInstance.procesarProducto;
				
						pp.agregarProductoDerivado( Ext.getCmp("inventario_producto_derivado_qty").getValue() );
						pp.getListaDeProductosPanel( ).hide( );
	                }
				}]
	        }],														 
			items: [{
				xtype: 'fieldset',
				title: 'Detalles de producto derivado',
				instructions: '',
				defaults : {
					disabled : false,
					labelAlign : "top",
					labelWidth: "100%"
				},
				items: [
					new Ext.form.Text({ 
						name: 'derivado_resultante', 
						id : 'inventario_producto_derivado_qty',
						label: 'Resultante',
						labelAlign : "top",
						listeners: {
							"focus" : function (){
								kconf = {
									type : 'num',
									submitText : 'Aceptar',
									callback : null
								};

								POS.Keyboard.Keyboard( this, kconf );								
							}
						}
					})
				]}
		]});
		
		
		/**
		  * Lista de productos
		  **/
		listaDeProductos = new Ext.Panel({
			floating: true,
			ui : "dark",
			layout : "card",
	        modal: false,
	        scroll: false,
	        width: 300,
	        height: 500,
			showAnimation : Ext.anims.fade ,
			hideOnMaskTap : false,
			bodyPadding : 0,
			bodyMargin : 0,
	        styleHtmlContent: false,
			listeners : {
				"show" : function (){
					updatePanel();
					this.setActiveItem(0);
				},
				"hide" : function (){
					var store;

					store = Aplicacion.Inventario.currentInstance.inventarioListaStore;

					store.clearFilter();
				}
			},
			items : [{
				multiSelect : true,
				xtype: 'list',
				ui: 'dark',
				store: null,
				itemTpl: '<div class=""><b>{productoID}</b> {descripcion}</div>',
				grouped: true,
				indexBar: false,
				listeners : {
					"selectionchange"  : function ( view, nodos, c ){
						var panel;
						
						if(nodos.length > 0){
							
							panel = Aplicacion.Inventario.currentInstance.procesarProducto.getListaDeProductosPanel( );
							
							panel.setActiveItem( productoDerivadoDetallesPanel,	Ext.anims.slide ); 
							
							foo = nodos[0];
						}

						
					}
				}
			}]
		});
		
		if(DEBUG){
			console.log("Creando panel de procesado con ", panelDetalles, otrosProductos, panel);
		}
		
	};
	

	
		
	/**
	  *
	  *
	  **/
	updatePanel = function(  ){

		var store;
		
		store = Aplicacion.Inventario.currentInstance.inventarioListaStore;
		
		store.clearFilter();
		
		store.filterBy( function( record ){
			
			if(record.get("productoID") == producto.get("productoID")){
				return false;
			}
			
			for (var i = carritoDeDerivados.length - 1; i >= 0; i--){
				
				if( record.get("productoID") == carritoDeDerivados[i].get("productoID") ){
					return false;
				}
			};

			return true;
		});
		
		listaDeProductos.getComponent(0).bindStore(store);

		return true;
	};
	
	
	
	/** Validar los campos
	  *	 
	  *
	  *
	  **/			
	validar = function (){

		var detalles = panelDetalles.getValues();
		if(DEBUG){
			console.log("Validando detalles ", detalles , producto);
		}
		
		//revisar que el total no sobrepase las existencias de ese producto
		var totalTomado = parseFloat(detalles.merma) + parseFloat( detalles.resultante);
		
		//TODO: ir por cada subproducto que salio y sumarlo a totalTomado
		//totalTomado += 
		
		if( parseFloat( producto.get("existenciasOriginales") ) < totalTomado){
			
			//no hay suficiente producto
			return false;
		}
		
		return true;
		
	};
	
	
	
	renderCarrito = function(){
		var html;
		
		//re-rendereando el carrito
		if(DEBUG){
			console.log("Re-rendereando el carrito", otrosProductos);
		}
		
		html = '<table style="width: 100%" >';
		html += '<tr> <th> Producto </th><th> Cantidad resultante del proceso </th> </tr>';
		for (var i = carritoDeDerivados.length - 1; i >= 0; i--){

			html += '<tr>';
			html += '<td><b>' + carritoDeDerivados[i].get("productoID") +  '</b> ' + carritoDeDerivados[i].get("descripcion") + '</td>';
			html += '<td>' + carritoDeDerivados[i].get("cantidad") + carritoDeDerivados[i].get("medida") + 's</td>';			
			html += '</tr>';	
		};

		html += '</table>';		
		otrosProductos.update(html);
	};
	
	
	
	/** 
	  * Usa FOO !!
	  **/
	this.agregarProductoDerivado = function( qty ){
		if(DEBUG){
		    console.log("Agregando producto derivado" , foo);
		}
		
		for (var i = carritoDeDerivados.length - 1; i >= 0; i--){
			if ( carritoDeDerivados[i].get("productoID") == foo.get("productoID")){
				//ya tiene agregado este all carrito
				return;
			}
		};
		
		foo.set("cantidad", qty )
		
		carritoDeDerivados.push(foo);
		
		renderCarrito();
		
	}
	
	
	
	
	/**
	  * regresar el panel con lista de productos para 
	  * manipularlo desde fuera
	  **/
	this.getListaDeProductosPanel = function(){
		return listaDeProductos;
	};
	
	/**
	  * Mostrar la lista de productos restantes del proceso
	  *
	  **/
	this.mostrarLista = function( where ){
		listaDeProductos.showBy(where);
	}
	
	

	
	/**
	  * Obtener el panel de procesar producto
	  *
	  **/
	this.getPanel = function(){
		updatePanel();
		return panel;
	};
	
	
	
	
	/** Establecer de que producto estamos hablando
	  * 
	  * @access Public
	  *
	  **/
	this.setProducto = function ( productoStore ){
		producto = productoStore;
	}
	
	
	/** Enviar los resultados al servidor
	  *
	  *
	  *
	  **/
	this.send = function(){
		
		var detalles,
			data;
		
		if( !validar() ){
			Ext.Msg.alert("Procesar producto", "No hay suficiente producto orginal para procesar esta cantidad");
			return false;
		}
		
		Ext.getBody().mask('Enviando proceso', 'x-mask-loading', true);
		
		detalles = panelDetalles.getValues();
		
		data = {
			id_producto : producto.get("productoID"),
			procesado : detalles.resultante,
			desecho : detalles.merma,
			subproducto : [] // { id_producto : , procesado : }
		};
		
	    Ext.Ajax.request({
	        url: '../proxy.php',
	        scope : this,
	        params : {
	            action : 408,
	            data : Ext.util.JSON.encode( data )
	        },
	        success: function(response, opts) {
		
				Ext.getBody().unmask();
				
				try{
		        	respuesta = Ext.util.JSON.decode( response.responseText );             
		        }catch(e){
		            return POS.error(e);
		        }
				
				if(respuesta.success){
					Ext.Msg.alert( "Procesar producto", "Producto procesado correctamente" );
				}else{
					Ext.Msg.alert("Procesar producto", respuesta.reason);
					return;
				}
		
				if(DEBUG){
					console.log("Regrese de procesar producto en suscursal", respuesta);
				}
			
				//mostrar los detalles del producto
				Aplicacion.Inventario.currentInstance.detalleInventarioPanelShow(producto);
	        }
	    });
	}
	
	
	
	
	/**
	  * Init
	  * 
	  **/
	crearPanel();
	
};




POS.Apps.push( new Aplicacion.Inventario() );


