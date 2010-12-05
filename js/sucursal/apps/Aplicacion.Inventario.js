

Aplicacion.Inventario = function (  ){

	return this._init();
}




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
	return {
	    text: 'Inventario',
	    cls: 'launchscreen',
	    items: [{
	        text: 'Inventario Actual',
			card : 	this.listaInventarioPanel,
	        leaf: true
	    },
	    {
	        text: 'Surtir',
	        items: [{
		        text: 'Solicitar Producto',
      			card : 	this.surtirWizardPanel,
		        leaf: true
		    },
		    {
		        text: 'Reportar Merma',
		        leaf: true
		    }]
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
		{name: 'descripcion',     type: 'string'},
		{name: 'productoID',     type: 'float'}	
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

//escructura que contiene el inventario que se cargo por ultima vez
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
		url: 'proxy.php',
		scope : this,
		params : {
			action : 400
		},
		success: function(response, opts) {
			try{
				inventario = Ext.util.JSON.decode( response.responseText );				
			}catch(e){
				POS.error(e);
			}

			
			if( !inventario.success ){
				//volver a intentar
				return this.cargarInventario();
			}
			
			if(DEBUG){
				console.log("Inventario retrived !", inventario);
			}
			
			this.Inventario.productos = inventario.datos;
			this.Inventario.productos2 = inventario.datos;
			this.Inventario.lastUpdate = Math.round(new Date().getTime()/1000.0);

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
		dockedItems : dockedItems,
        layout: Ext.is.Phone ? 'fit' : {
            type: 'vbox',
            align: 'center',
            pack: 'center'
        },
        
        items: [{
			
			width : '100%',
			height: '100%',
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
	detallesPanel.loadRecord( producto );
	
};


//crea el panel de detalle de inventario
Aplicacion.Inventario.prototype.detalleInventarioPanelCreator = function()
{
	
	this.detalleInventarioPanel = new Ext.form.FormPanel({                                                       
	
	items: [{
		xtype: 'fieldset',
	    title: 'Detalles de Producto',
		scroll: 'vertical',
	    instructions: '',
		defaults : {
			disabled : false
		},
		
		items: [
			new Ext.form.Text({ name: 'productoID', label: 'ID'	}),
			new Ext.form.Text({ name: 'descripcion', label: 'Descripcion' }),
			new Ext.form.Text({ name: 'precioVenta', label: 'Venta' }),
			new Ext.form.Text({ name : 'existencias', label: 'Existencias' }),
			new Ext.form.Text({ name : 'existenciasMinimas', label: 'Minimas' })
		]},
		
		new Ext.Button({ ui  : 'action', text: 'Agregar a venta', margin : 5, handler : this.detalleInventarioAgregarACarro }),
		new Ext.Button({ ui  : 'confirm', text: 'Surtir', margin : 5, handler : this.detalleInventarioSurtirEsteProd  })
	]});

};



Aplicacion.Inventario.prototype.detalleInventarioAgregarACarro = function()
{
	console.warn("TODO : Agregar este producto a carrito ");
};



Aplicacion.Inventario.prototype.detalleInventarioSurtirEsteProd = function()
{

};


/* ***************************************************************************
   * Surtir productos 
   * 
   *************************************************************************** */



Aplicacion.Inventario.prototype.surtirWizardPanel = null;

Aplicacion.Inventario.prototype.surtirWizardPopUpPanel = null;


Aplicacion.Inventario.prototype.surtirWizardCreator = function ()
{
	bar = [{
        text: 'Agregar producto',
        ui: 'normal',
		handler : function( t ){
			//iniciar wizard
			Aplicacion.Inventario.currentInstance.surtirWizardPopUpPanel.show();
		}
    }];


	//crear el panel
	this.surtirWizardPanel = new Ext.Panel({
			dockedItems : new Ext.Toolbar({
					ui: 'dark',
					dock: 'bottom',
					items: bar
				}),
			html : "asdf"
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
		        handler: function() {

		        }
		    }]
		}],
		items: [{
			width : 450,
			multiSelect : false,
			height: 350,
			xtype: 'list',
			ui: 'dark',
			store: this.inventarioListaStore,
			itemTpl: '<div class=""><b>{productoID}</b> {descripcion}</div>',
			grouped: true,
			indexBar: true	
        }]
	});
	
	
	

	
};






POS.Apps.push( new Aplicacion.Inventario() );


