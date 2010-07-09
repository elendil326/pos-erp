ApplicationGastos = function ()
{
	if(DEBUG){
		console.log("ApplicationGastos: construyendo");
	}
	
	ApplicationGastos.currentInstance = this;
	
	this._init();
	
	return this;
};


//variable que guarda la sucursal en la que se esta
ApplicationGastos.prototype.sucursal_id = null;

//aqui va el panel principal 
ApplicationGastos.prototype.mainCard = null;

//aqui va el nombre de la applicacion
ApplicationGastos.prototype.appName = null;

//aqui va el nombre de la applicacion
ApplicationGastos.prototype.leftMenuItems = null;

//aqui va un texto de ayuda en html
ApplicationGastos.prototype.ayuda = null;

//Toolbar persistente en toda la aplicacion
ApplicationGastos.prototype.gastosToolbar = null;



// --- paneles ----

	//panel de inicio que contiene el mosaico
	ApplicationGastos.prototype.homePanel = null;

	//panel donde se agregará un nuevo gasto
	ApplicationGastos.prototype.gastosPanel = null;
	
	//panel principal que sera al que le pondremos las tarjetas
	ApplicationGastos.prototype.appMainPanel = null;

/* -------------------------------------------------------------------------------------
			init
   ------------------------------------------------------------------------------------- */
ApplicationGastos.prototype._init = function(){
	
	//obtenemos la id de la sucursal del usuario
	this.loadSucursalID();

	//nombre de la aplicacion
	this.appName = "Gastos";
	
	//ayuda sobre esta applicacion
	this.ayuda = 'Aquí se registran los gastos hechos por la sucursal';
	
	//instancia this.homePanel
	this.loadHomePanel();

	
};

ApplicationGastos.prototype.loadSucursalID = function(){
	
	
	POS.AJAXandDECODE({
		method: 'obtenerSucursalUsuario'
	}, function(result){
		if (result.success){
			
			ApplicationGastos.currentInstance.sucursal_id = result.datos[0].sucursal_id;
			
		}
		
	}, function(){
		if(DEBUG){
				console.error("ApplicationGastos.loadSucursalID: Failed Ajax");
			}
	});
};


/* -------------------------------------------------------------------------------------
			Home 
   ------------------------------------------------------------------------------------- */
ApplicationGastos.prototype.loadHomePanel = function()
{
	this.gastosToolbar = new Ext.Toolbar({
			dock: 'bottom',
			items: 
				[{
					xtype: 'spacer'
				},{
					xtype: 'button',
					text: 'Agregar gasto',
					ui: 'action',
					handler: function(){
						ApplicationGastos.currentInstance.loadGastosPanel();
						ApplicationGastos.currentInstance.loadGastosButtons();
					}
				}]
	});
				
	this.homePanel = new Ext.Panel({
			id: 'gastosHomePanel',
			layout: 'card',
			baseCls: 'ApplicationGastos-mainPanel',
			html: '<div class="no-data">Presione el botón en la parte inferior para empezar</div>'
	});
	
	this.appMainPanel = new Ext.Panel({
		layout: 'card',
		dockedItems: this.gastosToolbar,
		items: this.homePanel
	});
		
	this.mainCard = this.appMainPanel;
	
	
};

/* -------------------------------------------------------------------------------------
			Panel de gastos
   ------------------------------------------------------------------------------------- */

ApplicationGastos.prototype.loadGastosPanel = function(){

	this.gastosPanel = new Ext.form.FormPanel({
		id: 'ApplicationGastos-gastosFormPanel',
		scroll: 'none',
		items:
			[{
				xtype: 'fieldset',
				title: 'Gasto nuevo',
				instructions: '* Estos campos son requeridos',
				items:
					[{
						xtype: 'textfield',
						label: 'Concepto',
						name: 'concepto',
						required: true
					},
					{
						id: 'ApplicationGastos-gastosForm-monto',
						xtype: 'textfield',
						label: 	'Monto',
						name: 'monto',
						required: true
					},
					{
						id: 'ApplicationGastos-gastosForm-fecha',
						xtype: 'textfield',
						label: 'Fecha',
						name: 'fecha',
						required: true,
						listeners: {
							focus: function( field ){
								ApplicationGastos.currentInstance.getDate( field );
							}
						}
					}] 	
			}]
	});
	
	
	this.appMainPanel.setCard( this.gastosPanel, 'slide' );
	
};

ApplicationGastos.prototype.getDate = function( textfield ){
	
	var currentDate = new Date();
	
	if ( Ext.get( 'ApplicationGastos-getDate-panel' ) != null){
		Ext.getCmp( 'ApplicationGastos-getDate-panel' ).show();
		
		 var picker = Ext.getCmp('ApplicationGastos-getDate-picker')
		 picker.value.day = currentDate.getDate();
		 picker.value.month = currentDate.getMonth();
		 picker.value.year = currentDate.getFullYear();
		
		return;
	}
	
	
	
	
	//alert(currentDate.getDay());
	
	var datePicker = new Ext.Panel({
		id: 'ApplicationGastos-getDate-panel',
		height: 320,
		width: 356,
		floating: true,
		centered: true,
		modal: true,
	    items: [{
			id: 'ApplicationGastos-getDate-picker',
	        xtype: 'datepicker',
	        width: (!Ext.platform.isPhone ? 400 : 320),
	        height: Ext.platform.isAndroidOS ? 320 : (!Ext.platform.isPhone ? 356 : 300),
	        useTitles: false,
			floating: true,
			centered: true,
	        value: {
	            day: currentDate.getDate(),
	            month: currentDate.getMonth(),
	            year: currentDate.getFullYear()
	        },
	        dockedItems: [{
	            xtype: 'toolbar',
	            dock: 'top',
				title: 'Fecha',
	
	            // alignment of the button to the right via
	            // flexed components
	            items: [{xtype: 'spacer'}, {
	                xtype: 'button',
	                ui: 'action',
	                text: 'Aceptar',
	                handler: function() {
	                    var fecha = Ext.encode(Ext.getCmp('ApplicationGastos-getDate-picker').getValue());
						var formatFecha = fecha.slice(1, fecha.indexOf('T'));
						Ext.getCmp('ApplicationGastos-gastosForm-fecha').setValue(formatFecha);
						datePicker.hide();
	                }
	            }]
	        }]
	    }]
	});
	
	
	datePicker.show();
	
};


/* -------------------------------------------------------------------------------------
			Agregar gasto logica
   ------------------------------------------------------------------------------------- */

ApplicationGastos.prototype.logicAddGasto = function(){
	
	//Se obtienen los valores del form en un arreglo
	var datos = Ext.getCmp('ApplicationGastos-gastosFormPanel').getValues();
	
	POS.AJAXandDECODE(
			//Parametros
			{
				method	: 'insertarGasto',
				concepto: datos['concepto'],
				monto	: datos['monto'],
				fecha	: datos['fecha']
			},
			//Responded
			function(result)
			{
				if ( result.success)
				{
					POS.aviso('Éxito', 'Se agregó el nuevo gasto correctamente');
					ApplicationGastos.currentInstance.appMainPanel.setCard( ApplicationGastos.currentInstance.homePanel, { type:'slide', direction:'right'});
					ApplicationGastos.currentInstance.loadHomeButtons();
				}
				else{
					POS.aviso('Error', 'No se pudo agregar el nuevo gasto, intente nuevamente');
				}
				
			},
			//No response
			function(){
				POS.aviso('Error', 'Hubo un error en la conexión, intente nuevamente');
				if (DEBUG){
					console.warn('ApplicationGastos.logicAddGasto: AJAX failed');
				}
			}	
	);
	
};



/* -------------------------------------------------------------------------------------
			Funciones Toolbar
   ------------------------------------------------------------------------------------- */
//Funcion que quita los botones que estan en la toolbar y agrega los correspondientes al gastosPanel  
ApplicationGastos.prototype.loadGastosButtons = function(){
	
	this.gastosToolbar.removeAll();
	
	this.gastosToolbar.add(
		[{
			xtype: 'button',
			text: 'Regresar',
			ui: 'back',
			handler: function(){
				ApplicationGastos.currentInstance.appMainPanel.setCard( ApplicationGastos.currentInstance.homePanel, { type: 'slide', direction: 'right'});
				ApplicationGastos.currentInstance.loadHomeButtons();
			}
		},
		{
			xtype: 'spacer'
		},
		{
			xtype: 'button',
			text: 'Aceptar',
			ui: 'action',
			handler: function(){
				//alert("Agregando gasto...");
				
				var datos = Ext.getCmp('ApplicationGastos-gastosFormPanel').getValues();
				console.log(datos);
				
				if( datos['concepto'] == "" || datos['monto'] == "" || datos['fecha'] == "" )
				{
					POS.aviso('Error', 'Debes llenar todos los campos requeridos');
					return;
				}
				
				var monto = datos['monto'];
				
				if ( isNaN(monto) || monto < 1 )
				{
					POS.aviso('Error', 'El monto debe ser un número válido');
					return;
				}
				
				ApplicationGastos.currentInstance.logicAddGasto();
			}
		}]
	);
	
	this.gastosToolbar.doLayout();
};

ApplicationGastos.prototype.loadHomeButtons = function(){
	
	this.gastosToolbar.removeAll();
	
	this.gastosToolbar.add(
	[
		{
			xtype: 'spacer'
		},
		{
			xtype: 'button',
			text: 'Agregar gasto',
			ui: 'action',
			handler: function(){
				ApplicationGastos.currentInstance.loadGastosPanel();
				ApplicationGastos.currentInstance.loadGastosButtons();
			}
		}
	]
	);
	
	this.gastosToolbar.doLayout();
	
};

//autoinstalar esta applicacion
AppInstaller( new ApplicationGastos() );