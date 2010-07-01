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

ApplicationInventario.popup = null;

//Mosaico
ApplicationInventario.prototype.mosaic = null;

ApplicationInventario.prototype._init = function()
{

	//nombre de la aplicacion
	this.appName = "Inventario";
	
	//ayuda sobre esta applicacion
	this.ayuda = 'Los botones de la parte inferior muestran <br>los productos que se encuentran actualmente registrados en el<br> inventario de la sucursal especificada <br>en los botones.';
	
	//panel principal
	this.inventarioMainPanel = new Ext.Panel({
		id: 'inventarioMainPanel',
		layout: 'card',
		html: '<div style="width:100%; height:100%" id="inventario_mosaico"></div>',
		listeners : {
			'afterrender' : function (){
				ApplicationInventario.currentInstance.mosaic = new Mosaico({
					renderTo : 'inventario_mosaico',
					items: [{ 
							title: 'norte',
							image: 'media/truck.png',
							keywords: [ 'f', 'g']
						},{
							title: 'pino suarez',
							image: 'media/truck.png',
							keywords: [ 'h','i']
						},{ 
							title: 'pinos',
							image: 'media/truck.png',
							keywords: []
						},{
							title: 'leon',
							image: 'media/truck.png'
						}]
				});
			}
		}
	});


	//initialize the tootlbar which is a dock
	this._initToolbar();
	//this.initSucursalPanel(1);
		
	this.mainCard = this.inventarioMainPanel;
	 
	//this.inventarioMainPanel.add( firstPanel );
	//this.inventarioMainPanel.setCard( firstPanel , 'slide' );
	//submenues en el panel de la izquierda
	this.leftMenuItems = [
    {
        text: 'Existencias',
       	card: this.inventarioMainPanel,
        ayuda: this.ayuda
    }];
		
};
 
 

Ext.regModel('InvExistencias', {
    fields: ['denominacion', 'existencias', 'precio_venta', 'min']
});

InvProductsListStore = new Ext.data.Store({
    model: 'InvExistencias',
    sorters: 'denominacion',
    getGroupString : function(record) {
        return record.get('denominacion')[0];
    }
});


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
}




ApplicationInventario.prototype._initToolbar = function(){
	
	
	/*	
		Buscar
	*/
	var buscar = [{
		xtype: 'textfield',
		id:'ApplicationInvenario_searchField',
		listeners:
				{
					'render': function( ){
						//medio feo, pero bueno
						Ext.get("ApplicationInvenario_searchField").first().dom.setAttribute("onkeyup", "ApplicationInventario.currentInstance.mosaic.doSearch( this.value )");

					}
				}
		}];



        this.dockedItems = [ new Ext.Toolbar({
            ui: 'dark',
            dock: 'bottom',
            items: buscar
        })];
    
	
	//agregar este dock a el panel principal
	this.inventarioMainPanel.addDocked( this.dockedItems );
	
	

	
	
	
	
	
	
	var buttonsArray = [];
	var panel;
	POS.AJAXandDECODE({
		 method: 'listarSucursal',
	}, function(result){  //Success AJAX
		
		for(var i=0; i < result.datos.length ; i++){
			buttonsArray.push(new Ext.Button({
				text: result.datos[i].descripcion,
				index: i,
				handler: function(btn){
					
					//Si se da click en un boton del toolbar se deshabilita y los demas se habilitan
					for (var j=0; j<buttonsArray.length ; j++){
						if( btn.index == j){
							btn.disable();
						}else{
							buttonsArray[j].enable();
						}
					}
					
					ApplicationInventario.currentInstance.initSucursalPanel(result.datos[btn.index].id_sucursal);
					
					
					
				}
			}));
		}
		
		//Hacemos una lista para poner los productos que pueden ingresarse al inventario
		//this.productList = new Ext.list
		 
		//Checamos si es telefono, si no para agregar espacio entre botones
		/*if(!Ext.platform.isPhone){
			buttonsArray.push({ xtype: 'spacer'} , {
				text: '+',
				ui: 'action',
				handler: function(){
					if (!this.popup) {
			                this.popup = new Ext.Panel({
			                    floating: true,
			                    modal: true,
			                    centered: true,
			                    width: 320,
			                    height: 300,
			                    styleHtmlContent: true,
			                    html: '<p>Tap anywhere outside the overlay to close it.</p>',
			                    dockedItems: [{
			                        dock: 'top',
			                        xtype: 'toolbar',
			                        title: 'Agregar Producto'
			                    }],
			                    scroll: 'vertical'
			                });
			            }
			            this.popup.show('pop');
						}
			});
		}else{
			buttonsArray.push({
				text: '+',
				ui: 'action',
				handler: function(){
					POS.aviso('Agregar producto', 'Aqui ira el contenido');
				}
			});
		}*/
		
		ApplicationInventario.currentInstance.toolBar = new Ext.Toolbar({
		dock: 'bottom',
	    items: [buttonsArray]
	});
	
	ApplicationInventario.currentInstance.inventarioMainPanel.addDocked(ApplicationInventario.currentInstance.toolBar);
	
	},function(error){ //Failure Ajax
		
	});

	
};

//Funcion para crear un panel con el listado de una sucursal especifica
ApplicationInventario.prototype.initSucursalPanel = function(sucursal_id){
	
	/*if (Ext.get('sucursalPanel'+sucursal_id)){
		ApplicationInventario.currentInstance.inventarioMainPanel.setCard( Ext.getCmp('sucursalPanel'+sucursal_id) , 'slide');
		return 0;
	}*/
	
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
				}
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
 
	this.inventarioMainPanel.setCard( sucursalPanel , 'slide' );
	//this.inventarioMainPanel.addDocked(sucursalPanel);
	//this.mainCard = sucursalPanel;
	return sucursalPanel;
};

//ApplicationInventario.prototype.getInvList = function(){
	
	/*var listInvStore = new Ext.store({
		fields:[ '']
	});*/
	
//};


/*
//Panel principal de Inventario
ApplicationInventario.prototype.inventarioMainPanel = new Ext.Panel({
			    
	draggable: false,
	layout: 'card',			
	listeners: {
		beforeshow : function(component){
						
						POS.AJAXandDECODE({
						method: 'listarProductosInventario'
						},
						function (datos){//mientras responda
							
							console.log(datos.datos);
							//console.log("datos.datos[0].nombre = "+datos.datos[0].nombre);
							this.products = datos.datos;
							InvProductsListStore.loadData(this.products); 
							//console.log(InvProductsListStore);
							
						},
						function (){//no responde
							POS.aviso("ERROR",":(");
						}
					);	
		}//fin before
	},
	items: [{
        width: '100%',
        height: '100%',
        xtype: 'list',
        store: InvProductsListStore,
        tpl: '<tpl for="."><div style="background-color: '+ApplicationInventario.backgroundPicker('{existencias}', '{min}')+' " class="products"><strong>{denominacion}</strong><div width= "30%"></div>Existencias: {existencias} Precio: {precio_venta}</div></tpl>',
        itemSelector: 'div.products',
        singleSelect: true
    }]

});
*/





//autoinstalar esta applicacion
AppInstaller( new ApplicationInventario() );
