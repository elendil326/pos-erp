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



ApplicationInventario.prototype._init = function()
{

	
	
	//iniciar variables
	
	//nombre de la aplicacion
	this.appName = "Inventario";
	
	//ayuda sobre esta applicacion
	this.ayuda = "esto es una ayuda sobre este modulo de compras, html es valido <br> :D";
	
	//panel principal
	this.inventarioMainPanel = new Ext.Panel({
		id: 'inventarioMainPanel',
		layout: 'card'
	});
	//alert();
	console.log(this.inventarioMainPanel);
	
	//initialize the tootlbar which is a dock
	this._initToolbar();
	//this.initSucursalPanel(1);
		
	this.mainCard = this.inventarioMainPanel;
	 
	//this.inventarioMainPanel.add( firstPanel );
	//this.inventarioMainPanel.setCard( firstPanel , 'slide' );
	//submenues en el panel de la izquierda
	this.leftMenuItems = [
	{
        text: 'Ingresar producto',
       	card: new Ext.Panel({
    cls: 'cards',
    layout: {
        type: 'vbox',
        align: 'stretch'
    },
    defaults: {
        flex: 1
    },
    items: [{
        xtype: 'carousel',
        cls: 'card',
        items: [{
            html: '<p>Navigate the carousel on this page by swiping left/right or clicking on one side of the circle indicators below.</p>',
            cls: 'card1',
        },
        {
            html: 'Card #2',
            cls: 'card2'
        },
        {
            html: 'Card #3',
            cls: 'card3'
        }]
    }, {
        xtype: 'carousel',
        cls: 'card',
        ui: 'light',
        items: [{
            html: '<p>Carousels can be given a <code>ui</code> of &#8216;light&#8217; or &#8216;dark.&#8217;</p>',
            cls: 'card1',
        },
        {
            html: 'Card #2',
            cls: 'card2'
        },
        {
            html: 'Card #3',
            cls: 'card3'
        }]
    }]
}),
        ayuda: 'ayuda en menu 1'
    },
    {
        text: 'Existencias',
       	card: this.inventarioMainPanel,
        ayuda: 'ayuda en menu2'
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
	
	var buttonsArray = [];
	var panel;
	POS.AJAXandDECODE({
		 method: 'listarSucursal',
	}, function(result){  //Success AJAX
		console.log(result);
		for(var i=0; i < result.datos.length ; i++){
			buttonsArray.push(new Ext.Button({
				text: result.datos[i].descripcion,
				index: i,
				handler: function(btn){
					//alert(i);
					console.log(btn);
					//alert(btn.index);
					
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
						
						POS.AJAXandDECODE({
						method: 'listarProductosInventarioSucursal',
						id_sucursal: sucursal_id
						},
						function (datos){//mientras responda
							
							console.log(datos.datos);
							//console.log("datos.datos[0].nombre = "+datos.datos[0].nombre);
							if (datos.success) { //Si el success trae true
								this.products = datos.datos;
								InvProductsListStore.loadData(this.products);
							} 
							else{
								//alert("No trae nada el json");
								InvProductsListStore.loadData(0);
								return 0;
							}
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
		loadingText: 'Cargando datos...',
		emptyText: 'No se encontraron datos',
        store: InvProductsListStore,
        tpl: String.format('<tpl for="."><div class="products"><strong>{denominacion}</strong><div width= "30%"></div>Existencias: {existencias} Precio: {precio_venta}</div></tpl>' /*ApplicationInventario.backgroundPicker('{existencias}','{min}')*/ ),
        itemSelector: 'div.products',
        singleSelect: true
    }]
	
	});

	console.log(ApplicationInventario.currentInstance.inventarioMainPanel); 
	this.inventarioMainPanel.setCard( sucursalPanel , 'slide' );
	//this.inventarioMainPanel.addDocked(sucursalPanel);
	//this.mainCard = sucursalPanel;
	return sucursalPanel;
};

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
