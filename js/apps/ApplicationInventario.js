ApplicationInventario = function ()
{
	if(DEBUG){
		console.log("ApplicationInventario: construyendo");
	}
	
	ApplicationInventario.currentInstance = this;
	
	this._init();
	
	return this;
};


//aqui va el panel principal 
ApplicationInventario.prototype.mainCard = null;

//aqui va el nombre de la applicacion
ApplicationInventario.prototype.appName = null;

//aqui va el nombre de la applicacion
ApplicationInventario.prototype.leftMenuItems = null;

//aqui va un texto de ayuda en html
ApplicationInventario.prototype.ayuda = null;


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

	//nombre de la aplicacion
	this.appName = "Inventario";
	
	//ayuda sobre esta applicacion
	this.ayuda = 'Los botones de la parte inferior muestran <br>los productos que se encuentran actualmente registrados en el<br> inventario de la sucursal especificada <br>en los botones.';
	
	//retrive sucursales
	this.getMosaicItems();
	
	//instancia this.homePanel
	this.loadHomePanel();

	//tarjeta principal
	this.mainCard = this.homePanel;
	
};


//register model
Ext.regModel('inv_Existencias', {
    fields: ['denominacion', 'existencias', 'precio_venta', 'min']
});






/* -------------------------------------------------------------------------------------
			Home 
   ------------------------------------------------------------------------------------- */
ApplicationInventario.prototype.loadHomePanel = function()
{
	
	/*	
		Buscar
	*/
	var buscarMosaico = [{
		xtype: 'textfield',
		emptyText: 'Búsqueda',
		id:'ApplicationInvenario_searchField',
		inputCls: 'caja-buscar',
		showAnimation: true,
		listeners:
				{
					'render': function( ){
						//medio feo, pero bueno
						Ext.get("ApplicationInvenario_searchField").first().dom.setAttribute("onkeyup", "ApplicationInventario.currentInstance.mosaic.doSearch( this.value )");
						//Le damos focus al searchbar
						document.getElementById( Ext.get('ApplicationInvenario_searchField').first().id ).focus();
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
		listeners : {
			'afterrender' : function (){
				ApplicationInventario.currentInstance.mosaic = new Mosaico({
					renderTo : 'invapp_mosaico',
					items: ApplicationInventario.currentInstance.mosaicItems,
					handler: function(item){
						ApplicationInventario.currentInstance.initSucursalPanel(item.id_sucursal, item.title);
					}
				});
			}
		}
	});
};






//Funcion para obtener los items del mosaico dinamicamente
ApplicationInventario.prototype.getMosaicItems = function(){

	POS.AJAXandDECODE({ 
		method: 'listarSucursal'
		
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
ApplicationInventario.prototype.initSucursalPanel = function(sucursal_id, sucursal_name){
	
	if(DEBUG){
		console.log("ApplicationInventario: creando panel para sucursal " + sucursal_id);
	}
	

	//Store para la lista de productos del inventario
	var InvProductsListStore = new Ext.data.Store({
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
			InvProductsListStore.clearFilter();
		}
		
		
		InvProductsListStore.filter([{
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
			text : 'Regresar',
			ui: 'back',
			handler : function(){
				sink.Main.ui.setCard( ApplicationInventario.currentInstance.mainCard, { type: 'slide', direction: 'right' });
			}
		},{
			xtype: 'button',
			text : 'Detalles',
			handler : function(){
				ApplicationInventario.currentInstance.loadDetailPanel(sucursal_id);
			}
		}]
	});
	
	
	/*
		creando el panel
	*/
	var sucursalPanel = new Ext.Panel({

		dockedItems: searchBar,

		draggable: false,
		layout: 'card',			
		listeners: {
			beforeshow : function(component){
						
					POS.AJAXandDECODE({
						method: 'listarProductosInventarioSucursal',
						id_sucursal: sucursal_id
						}, function(datos){
								//responded !
								if (datos.success) { 
									//si el success trae true
									this.products = datos.datos;
									InvProductsListStore.loadData(this.products);
								} else {
									InvProductsListStore.loadData(0);
									return 0;
								}
					
							}, function(){
								//no responde
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
			emptyText: 'No se encontraron datos',
        	store: InvProductsListStore,
        	tpl: String.format('<tpl for="."><div class="products"><strong>{denominacion}</strong> &nbsp;Existencias: {existencias} Precio: {precio_venta}</div></tpl>' ),
        	itemSelector: 'div.products',
        	singleSelect: true
    	}]
	
		});


	sink.Main.ui.setCard(sucursalPanel, 'slide');

};





/* -------------------------------------------------------------------------------------
			Detalles de cada sucursal
   ------------------------------------------------------------------------------------- */

ApplicationInventario.prototype.getDetalles = function( sucursal_id )
{

	//get data via ajax
	POS.AJAXandDECODE(
		{
			method: 'detallesSucursal',
			id_sucursal: sucursal_id
		},function(datos){

			if(datos.success){
				//once data loaded, call panel rendering
				ApplicationInventario.currentInstance.renderDetalles( datos.datos[0] );				
			}else{
				console.error("ApplicationInventario: unsuccessfull response");
			}

		},function(){
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
	
	var backBar = new Ext.Toolbar({
		dock: 'bottom',
		showAnimation: true,
		items: [{
				xtype: 'spacer'
			},{
				xtype: 'button',
				ui	 : 'back',
				text : 'Regresar',
				handler: function(){
					ApplicationInventario.currentInstance.initSucursalPanel( data.id_sucursal );
				}
			},{
				xtype: 'button',
				ui	 : 'action',
				text : 'Editar',
				handler: function(){

				}
			}]
	});
	
	//Un formpanel que llenaremos con los datos que obtengamos
	var detailPanel = new Ext.form.FormPanel({
			scroll: 'none',
			dockedItems : backBar,
			baseCls: "ApplicationInventario-mainPanel",
			items:[{
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
			},{
				xtype: 'fieldset',
				title: 'Mapa',
				width: 400,
				baseCls: "ApplicationInventario-detallesItems",
				items:[new Ext.Panel({
						    layout: 'fit',
							height: 300,
							width: 400,
							style: {
								width: '40%'
							}, items: 
								[ POS.map(data.direccion) ]
						})]
			}]
			
		});
		
		
	sink.Main.ui.setCard( detailPanel );
		
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
