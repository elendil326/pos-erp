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

//dockedItems
ApplicationInventario.prototype.dockedItems = null;

//Productos
ApplicationInventario.prototype.products = null;

//Color de fondo de los elementos de la lista
ApplicationInventario.backgroundColor = null;

//Lista que guardara los productos que pueden ser insertados en el inventario
ApplicationInventario.prototype.productList = null;


/********Paneles para navegar hacia atras y adelante***********/
//Panel que traera los detalles de la sucursal
ApplicationInventario.prototype.detailPanel = null;

//Panel que trae la lista del inventario
ApplicationInventario.prototype.sucursalPanel = null;

//Panel de inicio
ApplicationInventario.prototype.homePanel = null;

//Panel de inicio2
ApplicationInventario.prototype.homePanel2 = null;
/*******Paneles***********************************************/


//Toolbar de navegacion, se mantendra constante en todas las cards
ApplicationInventario.prototype.navToolbar = null;

ApplicationInventario.popup = null;

//Mosaico
ApplicationInventario.prototype.mosaic = null;

ApplicationInventario.prototype.mosaicItems = null;
ApplicationInventario.prototype.mosaicItems2 = null;



ApplicationInventario.prototype._init = function()
{

	//nombre de la aplicacion
	this.appName = "Inventario";
	
	//ayuda sobre esta applicacion
	this.ayuda = 'Los botones de la parte inferior muestran <br>los productos que se encuentran actualmente registrados en el<br> inventario de la sucursal especificada <br>en los botones.';
	
	//cargamos los items del mosaico
	this.getMosaicItems();
	
	//panel principal
	this.inventarioMainPanel =  new Ext.Panel({
		id: 'inventarioMainPanel',
		layout: 'card',
		html: '<div></div>'
	});

	this.loadHomePanel();
	//initialize the tootlbar which is a dock
	//this._initToolbar();
	//this.initSucursalPanel(1);
	
	this.inventarioMainPanel = this.homePanel;
	this.mainCard = this.inventarioMainPanel;
	this.loadNavigationBar();
	
		
};
 
ApplicationInventario.prototype.loadHomePanel = function(){
	
	this.homePanel2 = new Ext.Panel({
		id: 'inventarioHomePanel2',
		layout: 'card',
		html: '<div style="width:100%; height:100%" id="invapp_mosaico2"></div>',
		listeners : {
			'afterrender' : function (){
				ApplicationInventario.currentInstance.mosaic = new Mosaico({
					renderTo : 'invapp_mosaico2',
					items: ApplicationInventario.currentInstance.mosaicItems2,
					handler: function(item){
						ApplicationInventario.currentInstance.initSucursalPanel(item.id_sucursal, item.title);
					}
				});
			}
		}
	});
	
	this.homePanel = new Ext.Panel({
		id: 'inventarioHomePanel',
		layout: 'card',
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
	
	//console.log(this.inventarioMainPanel);
	//this.inventarioMainPanel.setCard( this.homePanel , 'slide');
	
};

Ext.regModel('inv_Existencias', {
    fields: ['denominacion', 'existencias', 'precio_venta', 'min']
});

//Funcion para obtener los items del mosaico dinamicamente
ApplicationInventario.prototype.getMosaicItems = function(){
	
	
	
	POS.AJAXandDECODE(
		//Parametros
		{
			method: 'listarSucursal'
		},
		//Funcion success
		function(result){
			
			//Arreglo con los items que tendra el mosaico
			var arrayItems = [];
			var arrayItems2 = [];
			// Un item del mosaico
			var mosaicItem = null;
			
			if (result.success)
			{
				
				for(var i=0; i<result.datos.length; i++)
				{
					mosaicItem = {
						title: result.datos[i].descripcion,
						image: 'media/truck.png',
						id_sucursal: result.datos[i].id_sucursal
						
					};
					
					arrayItems.push(mosaicItem);
				}
				
				ApplicationInventario.currentInstance.mosaicItems = arrayItems;
				ApplicationInventario.currentInstance.mosaicItems2 = arrayItems;
			}
		},
		//Funcion failure
		function(){
			
		});
		
		
	
};


//Funcion sencilla para escoger el color de fondo del elemento de la lista
ApplicationInventario.backgroundPicker = function (existencias, min){
	
	if ( min < existencias ) {
								return '#04B404';//verde
							}
							else 
								if ( min > existencias ) {
									return'#DF0101'; //rojo
								}
								else 
									if ( min == existencias ) {
										return '#D7DF01'; //amarillo
									}
};


ApplicationInventario.prototype.loadNavigationBar = function(){
	
	/*	
		Buscar
	*/
	var buscar = [{
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

	//Si existe la toolbar de navegacion, quita los componentes y agrega buscar, si no existe, la crea
	if (!this.navToolbar) {
		this.navToolbar = new Ext.Toolbar({
			ui: 'dark',
			dock: 'top',
			//animation: 'slide',
			items: buscar
		});
		
		this.dockedItems = [this.navToolbar];
		
		
		//agregar este dock a el panel principal
		this.inventarioMainPanel.addDocked(this.dockedItems);
	}else{
		this.navToolbar.removeAll();
		this.navToolbar.add(buscar);
		this.navToolbar.doLayout();
	}
	
	
}

ApplicationInventario.prototype._initToolbar = function(sucursal_name){
	
	
	if (!this.toolBar) {
		
		var buttonsArray = [];
		var panel;
		POS.AJAXandDECODE({
			method: 'listarSucursal'
		}, function(result){ //Success AJAX		
			var disableFlag = false;
			for (var i = 0; i < result.datos.length; i++) {
				
				//Condicion para cambiar la bandera para que salga deshabilitado el boton de la sucursal
				if ( result.datos[i].descripcion == sucursal_name)
				{
					disableFlag = true;
				}
				else{
					disableFlag = false;
				}
				
				buttonsArray.push(new Ext.Button({
					text: result.datos[i].descripcion,
					index: i,
					disabled: disableFlag,
					handler: function(btn){
						
						//Si se da click en un boton del toolbar se deshabilita y los demas se habilitan
						for (var j = 0; j < buttonsArray.length; j++) {
							if (btn.index == j) {
								btn.disable();
							}
							else {
								buttonsArray[j].enable();
							}
						}
						
						//ApplicationInventario.currentInstance.initSucursalPanel(result.datos[btn.index].id_sucursal);
						ApplicationInventario.currentInstance.initSucursalPanel(result.datos[btn.index].id_sucursal, btn.text);
						
						
					}
				}));
			}
			
			
			//Toolbar inferior	
			if (!ApplicationInventario.currentInstance.toolBar) {
				ApplicationInventario.currentInstance.toolBar = new Ext.Toolbar({
					dock: 'bottom',
					scroll: 'horizontal',
					animation: 'slide',
					showAnimation: true,
					items: [buttonsArray]
				});
			}
			
			
			ApplicationInventario.currentInstance.inventarioMainPanel.addDocked(ApplicationInventario.currentInstance.toolBar);
		
		
		}, function(error){ //Failure Ajax
		});
	}
	
	
};

//Funcion para crear un panel con el listado de una sucursal especifica
ApplicationInventario.prototype.initSucursalPanel = function(sucursal_id, sucursal_name){
	
	//Cargamos la toolbar inferior con las sucursales
	this._initToolbar(sucursal_name);
	
	//Store para la lista de productos del inventario
	var InvProductsListStore = new Ext.data.Store({
    model: 'inv_Existencias',
    sorters: 'denominacion',
    getGroupString : function(record) {
        return record.get('denominacion')[0];
    }
});


	
	var sucursalPanel = new Ext.Panel({
	id: 'sucursalPanel'+sucursal_id,			    
	draggable: false,
	layout: 'card',			
	listeners: {
		beforeshow : function(component){
						
				funcAjax = function(){
					POS.AJAXandDECODE({
						method: 'listarProductosInventarioSucursal',
						id_sucursal: sucursal_id
					}, function(datos){//mientras responda
					
						if (datos.success) { //Si el success trae true
							this.products = datos.datos;
							InvProductsListStore.loadData(this.products);
						}
						else {
							InvProductsListStore.loadData(0);
							return 0;
						}
						if (ApplicationInventario.popup){
							ApplicationInventario.popup.hide();
						}
					
					}, function(){//no responde
						if (!ApplicationInventario.popup) {
							//Creamos un aviso de error para reintentar conexion
							ApplicationInventario.popup = new Ext.Panel({
								id: 'errorPopUpInv',
								floating: true,
								modal: true,
								centered: true,
								width: 500,
								height: 150,
								styleHtmlContent: true,
								html: '<div align="center" style="font-size: 18px">Hubo un error en la conexión.<br> ¿ Desea reintentar la conexión ?</div>',
								dockedItems: [{
									dock: 'top',
									xtype: 'toolbar',
									title: 'Error',
									items: [new Ext.Button({
										ui: 'dark',
										text: 'Cancelar',
										handler: function(){
											Ext.getCmp('errorPopUpInv').hide();
										}
									}), {
										xtype: 'spacer'
									}, new Ext.Button({
										ui: 'action',
										text: 'Reintentar',
										handler: function(){
											funcAjax();
										}
									})]
								}]
							});
						}
						
						ApplicationInventario.popup.show('pop');
					});
				};
				funcAjax();
		}//fin before
	},
	items: [{
        width: '100%',
        height: '100%',
        xtype: 'list',
		loadingText: 'Cargando datos...',
		emptyText: 'No se encontraron datos',
        store: InvProductsListStore,
        tpl: String.format('<tpl for="."><div class="products"><strong>{denominacion}</strong><div width= "30%"></div>Existencias: {existencias} Precio: {precio_venta}</div></tpl>' /*ApplicationInventario.backgroundPicker('{existencias}','{min}')*/ ),
        itemSelector: 'div.products',
        singleSelect: true
    }]
	
	});
 
 	/*
	//this._initToolbar();
	//Quitamos los botones de la toolbar de navegacion
	this.navToolbar.removeAll();
	
	//Y solo le agregamos un boton de atras
	this.navToolbar.add({
		text: 'Inventario',
		ui: 'back',
		handler: function(){
			ApplicationInventario.currentInstance.inventarioMainPanel.setCard( ApplicationInventario.currentInstance.detailPanel, { type: 'slide', direction: 'right'});
			
			//Creamos los botones de atras e inventario
			ApplicationInventario.currentInstance.createBackInvButtons(sucursal_id);
		}
	});
	this.navToolbar.doLayout();*/
	
	this.createBackInvButtons(sucursal_id, sucursal_name);
	
	this.inventarioMainPanel.setCard( sucursalPanel , 'slide' );
	//this.inventarioMainPanel.addDocked(sucursalPanel);
	this.sucursalPanel = sucursalPanel;
	
	return sucursalPanel;
};

//Funcion que carga un panel con los detalles de la sucursal especificada
ApplicationInventario.prototype.loadDetailPanel = function(sucursal_id){
	
	//Variable que tendra los datos de la sucursal
	var data = [];
	//Panel que trae la form
	var detailPanel;
	
	//Ajax para obtener los datos de la sucursal
	POS.AJAXandDECODE(
		//Parametros
		{
			method: 'detallesSucursal',
			id_sucursal: sucursal_id
		},
		//Funcion success
		function(datos){
			console.log(datos);
			data = datos;
			/*
			 * PANEL
			 */
			//Un formpanel que llenaremos con los datos que obtengamos
			detailPanel = new Ext.form.FormPanel({
					scroll: 'vertical',
					items:[//--- item 0
					{
						xtype: 'fieldset',
						title: 'Detalles de la sucursal',
						items:[
							//---- item fieldset 0
						{
							xtype: 'textfield',
							disabled: true,
							name: 'descripcion',
							label: 'Nombre',
							value: data.datos[0].descripcion
						},
							//---- item fieldset 1
						{
							xtype: 'textfield',
							disabled: true,
							name: 'direccion',
							label: 'Dirección',
							value: data.datos[0].direccion	
						},
							//-----item fieldset 2
						{
							xtype: 'textfield',
							disabled: true,
							name: 'telefono',
							label: 'Teléfono'
						},
							//-----item fieldset 3
						{
							xtype: 'textfield',
							disabled: true,
							name: 'encargado',
							label: 'Encargado'
						}]
					},
					{//----item 2
						xtype: 'fieldset',
						title: 'Mapa',
						items:[new Ext.Panel({
								    layout: 'fit',
									height: 300,
								    items: [ POS.map(data.datos[0].direccion) ]
								})]
					}]
					
				});
				
				/*
				 * TOOLBARS
				 */
				
				//Creamos los botones de atras e inventario
				//ApplicationInventario.currentInstance.createBackInvButtons(sucursal_id);
				
				//Quitamos los botones de la toolbar de navegacion
				ApplicationInventario.currentInstance.navToolbar.removeAll();
				
				//Y solo le agregamos un boton de atras
				ApplicationInventario.currentInstance.navToolbar.add({
					text: 'Inventario',
					ui: 'back',
					handler: function(){
						ApplicationInventario.currentInstance.inventarioMainPanel.setCard( ApplicationInventario.currentInstance.sucursalPanel, { type: 'slide', direction: 'right'});
						
						//Creamos los botones de atras e inventario
						ApplicationInventario.currentInstance.createBackInvButtons(sucursal_id, data.datos[0].descripcion);
					}
				});
				ApplicationInventario.currentInstance.navToolbar.doLayout();
				
				//Agregamos la toolbar de abajo para cambiar rapido de sucursal
				ApplicationInventario.currentInstance._initToolbar(data.datos[0].descripcion);
				/*if (!ApplicationInventario.currentInstance.toolBar){
					alert("no existe");
					ApplicationInventario.currentInstance._initToolbar();
					
				}else{
					alert("si existe");
					ApplicationInventario.currentInstance.inventarioMainPanel.addDocked(ApplicationInventario.currentInstance.toolBar);
					
				}*/
				
				/*
				 * SETCARD
				 */
						
				//ApplicationInventario.currentInstance.inventarioMainPanel.setCard( detailPanel , 'slide' );
				ApplicationInventario.currentInstance.detailPanel = detailPanel;
				ApplicationInventario.currentInstance.inventarioMainPanel.setCard( detailPanel , 'slide' );
		},
		//Funcion failure
		function(){
			alert("Error");
		}
	);
	
	
	
	
	return detailPanel;
	
};

//Funcion para agregar los botones de back e inventario en la toolbar de navegacion
ApplicationInventario.prototype.createBackInvButtons = function(sucursal_id, sucursal_name){
	
				/*
				 * Navigation Toolbar
				 */
				
				//Quitamos los botones del navigation bar
				ApplicationInventario.currentInstance.navToolbar.removeAll();
				
				//Creamos los botones de atras e inventario
				var backinvButtons = [ new Ext.Button({
						text: 'Inicio',
						ui: 'back',
						showAnimation: true,
						handler: function(){
							ApplicationInventario.currentInstance.inventarioMainPanel.setCard(ApplicationInventario.currentInstance.homePanel2 , {type: 'slide', direction: 'right'});
							ApplicationInventario.currentInstance.loadNavigationBar();
							ApplicationInventario.currentInstance.inventarioMainPanel.removeDocked(ApplicationInventario.currentInstance.toolBar);
							ApplicationInventario.currentInstance.toolBar.destroy();
							ApplicationInventario.currentInstance.toolBar = null;
							ApplicationInventario.currentInstance.navToolbar.setTitle("");
						}
				}),{ xtype: 'spacer' },
					new Ext.Button({
						text: 'Detalles',
						ui: 'forward',
						showAnimation: true,
						handler: function(){
							ApplicationInventario.currentInstance.loadDetailPanel(sucursal_id);	
						}
					})];
				
				//Añadimos los botones de atras e inventario a la toolbar de navegacion	
				ApplicationInventario.currentInstance.navToolbar.add( backinvButtons );
				ApplicationInventario.currentInstance.navToolbar.setTitle(sucursal_name);
				ApplicationInventario.currentInstance.navToolbar.doLayout();
	
	
};

//autoinstalar esta applicacion
AppInstaller( new ApplicationInventario() );
