ApplicationInventario = function ()
{
	if(DEBUG){
		console.log("ApplicationInventario: construyendo");
	}
	
	ApplicationInventario.currentInstance = this;
	
	this._init();
	
	return this;
};


//variable estatica, false si solo se muestra mi sucursal, true si se quieren mostrar todas
ApplicationInventario.mostrarTodasSucursales = false;

//variable que guarda la sucursal en la que se esta
ApplicationInventario.prototype.sucursal_id = null;

//aqui va el panel principal 
ApplicationInventario.prototype.mainCard = null;

//aqui va el nombre de la applicacion
ApplicationInventario.prototype.appName = null;

//aqui va el nombre de la applicacion
ApplicationInventario.prototype.leftMenuItems = null;

//aqui va un texto de ayuda en html
ApplicationInventario.prototype.ayuda = null;

//El store de la lista del inventario
ApplicationInventario.prototype.InvProductsListStore = null;

	//Productos
	ApplicationInventario.prototype.products = null;

	//Lista que guardara los productos que pueden ser insertados en el inventario
	ApplicationInventario.prototype.productList = null;


// --- paneles ----

	//Panel que traera los detalles de la sucursal
	ApplicationInventario.prototype.detailPanel = null;

	//Panel que trae la lista del inventario
	ApplicationInventario.prototype.sucursalPanel = null;

	//panel de inicio que contiene el mosaico
	ApplicationInventario.prototype.homePanel = null;




//Mosaico
ApplicationInventario.prototype.mosaic = null;

ApplicationInventario.prototype.mosaicItems = null;



/* -------------------------------------------------------------------------------------
			init
   ------------------------------------------------------------------------------------- */
ApplicationInventario.prototype._init = function()
{

	//obtenemos la id de la sucursal del usuario
	this.loadSucursalID();

	//nombre de la aplicacion
	this.appName = "Inventario";
	
	//ayuda sobre esta applicacion
	this.ayuda = 'Los botones de la parte inferior muestran <br>los productos que se encuentran actualmente registrados en el<br> inventario de la sucursal especificada <br>en los botones.';
	
	//retrive sucursales
	this.getMosaicItems();
	
	//instancia this.homePanel
	this.loadHomePanel();

	//tarjeta principal
	if (this.mostrarTodasSucursales === true) {
		this.mainCard = this.homePanel;
	}
};


//register model
Ext.regModel('inv_Existencias', {
    fields: ['id_producto','denominacion', 'existencias', 'precio_venta', 'min']
});


ApplicationInventario.prototype.loadSucursalID = function(){
	
	
	POS.AJAXandDECODE({
		action: 1607//'obtenerSucursalUsuario'
	}, function(result){
		if (result.success){
			ApplicationInventario.currentInstance.sucursal_id = result.id_sucursal;
			//ApplicationInventario.currentInstance.initSucursalPanel(result.datos[0].sucursal_id);
		}
		//ApplicationInventario.currentInstance.sucursal_id 
	}, function(){
		if(DEBUG){
				console.error("ApplicationInventario.loadSucursalID: Failed Ajax");
			}
	});
};



/* -------------------------------------------------------------------------------------
			Home 
   ------------------------------------------------------------------------------------- */
ApplicationInventario.prototype.loadHomePanel = function()
{
	
	if (ApplicationInventario.mostrarTodasSucursales === true) {
	
	
		/*	
	 Buscar
	 */
		var buscarMosaico = [{
			xtype: 'textfield',
			emptyText: 'Búsqueda',
			id: 'ApplicationInvenario_searchField',
			inputCls: 'caja-buscar',
			showAnimation: true,
			listeners: {
				'render': function(){
					//medio feo, pero bueno
					Ext.get("ApplicationInvenario_searchField").first().dom.setAttribute("onkeyup", "ApplicationInventario.currentInstance.mosaic.doSearch( this.value )");
					//Le damos focus al searchbar
					document.getElementById(Ext.get('ApplicationInvenario_searchField').first().id).focus();
				}
			}
		}];
		
		
		var buMoToolbar = new Ext.Toolbar({
			ui: 'dark',
			dock: 'bottom',
			items: buscarMosaico
		});
		
		
		this.homePanel = new Ext.Panel({
			id: 'inventarioHomePanel',
			layout: 'card',
			dockedItems: buMoToolbar,
			html: '<div style="width:100%; height:100%" id="invapp_mosaico"></div>',
			listeners: {
				'afterrender': function(){
					ApplicationInventario.currentInstance.mosaic = new Mosaico({
						renderTo: 'invapp_mosaico',
						items: ApplicationInventario.currentInstance.mosaicItems,
						handler: function(item){
							ApplicationInventario.currentInstance.initSucursalPanel(item.id_sucursal, item.title);
						}
					});
				}
			}
		});
		
		this.mainCard = this.homePanel;
	}
	else{
		//this.loadSucursalID();
		this.initSucursalPanel(this.sucursal_id);
		this.mainCard = this.sucursalPanel;
	}
};






//Funcion para obtener los items del mosaico dinamicamente
ApplicationInventario.prototype.getMosaicItems = function(){

	POS.AJAXandDECODE({ 
		action: 1708//'listarSucursal'
		
		}, function( result ){
			
			var arrayItems = [];

			if (result.success)
			{
				
				for(var i=0; i<result.datos.length; i++)
				{
					arrayItems.push({
						title: result.datos[i].descripcion,
						image: 'media/truck.png',
						id_sucursal: result.datos[i].id_sucursal
					});
					

				}
				
				ApplicationInventario.currentInstance.mosaicItems = arrayItems;
			}
		}, function(){
			if(DEBUG){
				console.error("ApplicationInventario: Failed Ajax");
			}
		});

};



/* -------------------------------------------------------------------------------------
			Inventario de cada sucursal
   ------------------------------------------------------------------------------------- */
//funcion para crear un panel con el listado de una sucursal especifica
ApplicationInventario.prototype.initSucursalPanel = function(sucursal_id, sucursal_name, back){
	
	if(DEBUG){
		console.log("ApplicationInventario: creando panel para sucursal " + sucursal_id);
	}
	

	//Store para la lista de productos del inventario
	this.InvProductsListStore = new Ext.data.Store({
    	model: 'inv_Existencias',
    	sorters: 'denominacion',
    	getGroupString : function(record) {
        	return record.get('denominacion')[0];
    	}
	});


	/*
		buscar dentro de la lista
	*/
	buscar = function( textvalue ) {
      
		if ( textvalue == ""){
			ApplicationInventario.currentInstance.InvProductsListStore.clearFilter();
		}
		
		
		ApplicationInventario.currentInstance.InvProductsListStore.filter([{
			property     : 'denominacion',
			value        : textvalue,
			anyMatch     : true, 
			caseSensitive: false 
		}]);
    };
	
	
	
	/*
		Creando toolbar de panel de inventario
	*/
	var searchBar = new Ext.Toolbar({
		dock: 'bottom',
		animation: 'slide',
		showAnimation: true,
		items: [{
			xtype: 'textfield',
			emptyText: 'Búsqueda',
			id:'ApplicationInventario_searchInventario',
			inputCls: 'caja-buscar',
			showAnimation: true,
			listeners:
				{
					'render': function( ){
						//medio feo, pero bueno
						Ext.get("ApplicationInventario_searchInventario").first().dom.setAttribute("onkeyup", "buscar( this.value )");
						
						//Le damos focus al searchbar
						document.getElementById( Ext.get('ApplicationInventario_searchInventario').first().id ).focus();
					}
				}
		},{
			xtype: 'spacer'
		},{
			xtype: 'button',
			text : 'Sucursales',
			ui: 'back',
			disabled: !ApplicationInventario.mostrarTodasSucursales,
			handler : function(){
				if(ApplicationInventario.mostrarTodasSucursales == false){
					ApplicationInventario.mostrarTodasSucursales = true;
					ApplicationInventario.currentInstance.loadHomePanel();
				}
				sink.Main.ui.setCard( ApplicationInventario.currentInstance.mainCard, { type: 'slide', direction: 'right' });
			}
		},{
			xtype: 'button',
			text: 'Agregar nuevo producto',
			ui: 'action',
			handler: function(){
				ApplicationInventario.currentInstance.addNewProduct();
			}
		},{
			xtype: 'button',
			text : 'Detalles',
			ui : 'forward',
			handler : function(){
				if (ApplicationInventario.mostrarTodasSucursales === true) {
					ApplicationInventario.currentInstance.loadDetailPanel(sucursal_id, {
						type: 'slide',
						direction: 'left'
					});
				}
				else
				{
					ApplicationInventario.currentInstance.loadDetailPanel(ApplicationInventario.currentInstance.sucursal_id, {
						type: 'slide',
						direction: 'left'
					});
				}
			}
		}]
	});
	
	//Parametros para el AJAX
	var AJAXparams;
	
	//Mostramos mi sucursal nada mas?
	if ( ApplicationInventario.mostrarTodasSucursales == true ){
		
		AJAXparams = {
						action: 1705,//'listarProductosInventarioSucursal',
						id_sucursal: sucursal_id
					};
	}
	else{
		AJAXparams = {
					action: 1704//'listarProductosInventario'
		};
	}
	
	/*
		creando el panel
	*/
	this.sucursalPanel = new Ext.Panel({

		dockedItems: searchBar,

		draggable: false,
		layout: 'card',			
		listeners: {
			beforeshow : function(component){
		
					POS.AJAXandDECODE(//Parametros
									AJAXparams, 
							function(datos){
								//responded 
								
								if (datos.success) {
									 
									//si el success trae true
									this.products = datos.datos;
									ApplicationInventario.currentInstance.InvProductsListStore.loadData(this.products);
									if(ApplicationInventario.currentInstance.mostrarTodasSucursales == false){
										//ApplicationInventario.currentInstance.mainCard = ApplicationInventario.currentInstance.sucursalPanel;
									}
									if (ApplicationInventario.mostrarTodasSucursales == false) {
										if (back === true) {
											sink.Main.ui.setCard(ApplicationInventario.currentInstance.sucursalPanel, {
												type: 'slide',
												direction: 'right'
											});
										}
										else {
											sink.Main.ui.setCard(ApplicationInventario.currentInstance.sucursalPanel, {
												type: 'slide',
												direction: 'left'
											});
										}
									}
								} else {
									ApplicationInventario.currentInstance.InvProductsListStore.loadData(0);
									return 0;
								}
					
							}, function(){
								//no responde
								ApplicationInventario.currentInstance.InvProductsListStore.loadData(0);
								
								if(DEBUG){
									console.error("ApplicationInventario: no server response");
								}
							}
					);

			}//fin before
			
		},
		items: [{
        	width: '100%',
        	height: '100%',
        	xtype: 'list',
			baseCls : 'ApplicationInventario-mainPanel',
			loadingText: 'Cargando datos...',
			emptyText: '<div class="no-data">No se encontraron productos para esta sucursal.</div>',
        	store: ApplicationInventario.currentInstance.InvProductsListStore,
        	tpl: String.format('<tpl for="."><div class="products">ID: {id_producto} <strong>{denominacion}</strong> &nbsp;Existencias: {existencias} Precio: {precio_venta}</div></tpl>' ),
        	itemSelector: 'div.products',
        	singleSelect: true
    	}]
	
		});
		
	
	if (ApplicationInventario.mostrarTodasSucursales === true) {
		
		if (back === true) {
			sink.Main.ui.setCard(this.sucursalPanel, {
				type: 'slide',
				direction: 'right'
			});
		}
		else {
			
			sink.Main.ui.setCard(this.sucursalPanel, {
				type: 'slide',
				direction: 'left'
			});
		}
	}


};


/* -------------------------------------------------------------------------------------
			Agregar nuevo producto
   ------------------------------------------------------------------------------------- */

ApplicationInventario.prototype.addNewProduct = function(){
	
	if ( Ext.getCmp('ApplicationInventario-addNewProduct-panel') != null)
	{
		Ext.getCmp('ApplicationInventario-addNewProduct-panel').show();
		return;
	}
	
	var addProductTB = new Ext.Toolbar({
		dock: 'top',
		title: 'Agregar',
		items:[{
			xtype: 'spacer'
		},{
			xtype: 'button',
			text: 'Aceptar',
			ui: 'action',
			handler: function(){
				ApplicationInventario.currentInstance.addNewProductLogic();
			}
		}]
	})
	
	
	var addProductPanel = new Ext.Panel({
		
		id: 'ApplicationInventario-addNewProduct-panel',
		floating: true,
		centered: true,
		modal: true,
		height: 400,
		width: 450,
		layout: 'fit',
		dockedItems: addProductTB,
		items: [new Ext.form.FormPanel({
			id: 'ApplicationInventario-addNewProduct-form',
			scroll: 'vertical',
			
			items: [{
				xtype: 'fieldset',
				title: 'Nuevo producto',
				instructions: ' *Cantidad en Kg mínima que se recomienda debe existir en almacen ',
				items: [{
					xtype: 'textfield',
					label: 'Nombre',
					name: 'nombre'
				}, {
					xtype: 'textfield',
					label: 'Denominación',
					name: 'denominacion'
				}, {
					xtype: 'textfield',
					label: 'Precio/Kg',
					name: 'precio_venta'
				}, {
					xtype: 'textfield',
					label: 'Mínimo',
					name: 'min',
					required: true
				}]
			}]
		})
		
		]
		
		
	});
	
	addProductPanel.show();
	
};


ApplicationInventario.prototype.addNewProductLogic = function(){
	
	var formData = Ext.getCmp('ApplicationInventario-addNewProduct-form').getValues();
	
	POS.AJAXandDECODE(
			//Parametros
			{
				action: 1707,//'agregarNuevoProducto',
				denominacion: formData['denominacion'],
				nombre: formData['nombre'],
				precio: formData['precio_venta'],
				min: formData['min']
			},
			//Responded
			function(result)
			{
				
				if ( result.success )
				{
					Ext.getCmp('ApplicationInventario-addNewProduct-form').reset();
					Ext.getCmp('ApplicationInventario-addNewProduct-panel').hide();
					
					POS.aviso('Éxito', 'Se agrego el nuevo producto existosamente');
					//['denominacion', 'existencias', 'precio_venta', 'min']
					ApplicationInventario.currentInstance.InvProductsListStore.add(
						{
							denominacion: formData['denominacion'],
							existencias: 0,
							precio_venta: formData['precio_venta'],
							min: formData['min']
						}
					
					);
					
				}
				else{
					POS.aviso('Error', 'Error al intentar insertar el nuevo producto');
				}
				
			},
			//Not responded
			function()
			{
				POS.aviso('Error', 'Error en la conexión. Intente nuevamente')
			}
	);
	
	
	
};


/* -------------------------------------------------------------------------------------
			Detalles de cada sucursal
   ------------------------------------------------------------------------------------- */

ApplicationInventario.prototype.getDetalles = function( sucursal_id )
{

	//get data via ajax
	POS.AJAXandDECODE(
		{
			action: 1709,//'detallesSucursal',
			id_sucursal: sucursal_id
		},function(datos){

			if(datos.success){
				//once data loaded, call panel rendering
				ApplicationInventario.currentInstance.renderDetalles( datos.datos[0] );				
			}else{
				console.error("ApplicationInventario: unsuccessfull response");
			}

		},function(){
			
			POS.aviso('Error', 'Error de conexión: No se pudieron obtener los detalles de la sucursal, intente de nuevo');
			
			if(DEBUG){
				console.error("ApplicationInventario: could not retrive data");
			}

		});

	
};



ApplicationInventario.prototype.renderDetalles = function( data )
{
	if(DEBUG){
		console.log("ApplicationInventario: mostrando detalles" , data);
	}
	
	var html = "";
	
	html += "<div class='nombre'>" 		+data.descripcion		+ "</div>";
	html += "<div class='direccion'>" 	+data.direccion 	+ "</div>";
	html += "<div class='mail'>" 	+	 "</div>";
	html += "<div class='id_provedor'>" + "</div>";
	html += "<div class='rfc'>"  		+"</div>";
	html += "<div class='telefono'>"  	+ "</div>";


	var divDetalles = "<div class='ApplicationProveedores-Detalles'>"+html+"</div>";
	

	
	
	//Creamos un overlay para mostrar el mapa
	var mapOverlayTb = new Ext.Toolbar({
			title: 'Mapa',
			dock: 'top',
			items: [{
				xtype: 'button',
				ui: 'action',
				text: 'Imprimir',
				handler: function(){
					//alert("PRINT!");
					POS.print(mapOverlay.el.id);
				}
			}]
	});
        
	/*var mapOverlay = new Ext.Panel({
		floating: true,
		modal: true,
		centered: true,
		width: Ext.platform.isPhone ? 260 : 400,
		height: Ext.platform.isPhone ? 220 : 400,
		//styleHtmlContent: true,
		dockedItems: mapOverlayTb,
		//scroll: 'vertical',
		//items: POS.map(data.direccion)
		items: //POS.map(data.direccion)
		{xtype: 'map'}
		//contentEl: 'lipsum',
		//cls: 'htmlcontent'
	});*/
	
	
	var mapOverlay = POS.map(data.direccion);
	mapOverlay.modal = true;
	mapOverlay.setCentered(true);
	mapOverlay.setFloating(true, true);
	mapOverlay.setHeight(400);
	mapOverlay.setWidth(400);
	//mapOverlay.addDocked(mapOverlayTb);
	mapOverlay.doLayout();

	
	var backBar = new Ext.Toolbar({
		dock: 'bottom',
		showAnimation: true,
		items: [{
				xtype: 'spacer'
			},{
				xtype: 'button',
				ui	 : 'back',
				text : 'Inventario',
				handler: function(){
					//ApplicationInventario.currentInstance.initSucursalPanel( data.id_sucursal, null, true );
					sink.Main.ui.setCard( ApplicationInventario.currentInstance.sucursalPanel, {type: 'slide', direction: 'right'})
				}
			},{
				xtype: 'button',
				ui	 : 'action',
				text : 'Mapa',
				handler: function(){
						mapOverlay.show();
						mapOverlay.addDocked(mapOverlayTb);
				}
			}]
	});
	
	//Un formpanel que llenaremos con los datos que obtengamos
	var detailPanel = new Ext.form.FormPanel({
			scroll: 'vertical',
			dockedItems : backBar,
			baseCls: "ApplicationInventario-mainPanel",
			html: divDetalles
			/*items:[{
				xtype: 'fieldset',
				title: 'Detalles de la sucursal',
				baseCls: "ApplicationInventario-detallesItems",
				items:[{
						xtype: 'textfield',
						disabled: true,
						name: 'descripcion',
						label: 'Nombre',
						value: data.descripcion
					},{
						xtype: 'textfield',
						disabled: true,
						name: 'direccion',
						label: 'Dirección',
						value: data.direccion	
					},{
						xtype: 'textfield',
						disabled: true,
						name: 'telefono',
						label: 'Teléfono'
					},{
						xtype: 'textfield',
						disabled: true,
						name: 'encargado',
						label: 'Encargado'
					}]
			}]*/
			
		});
		
		
	sink.Main.ui.setCard( detailPanel , 'slide');
		
};


ApplicationInventario.prototype.loadDetailPanel = function(sucursal_id)
{
	
	if(DEBUG){
		console.log("ApplicationInventario: cargando detalles de sucursal " + sucursal_id);
	} 

	//first off, retrive office info
	ApplicationInventario.currentInstance.getDetalles( sucursal_id );

};





//autoinstalar esta applicacion
AppInstaller( new ApplicationInventario() );
