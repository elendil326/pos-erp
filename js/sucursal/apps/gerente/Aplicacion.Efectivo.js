

Aplicacion.Efectivo = function (  ){

	return this._init();
}




Aplicacion.Efectivo.prototype._init = function (){
    if(DEBUG){
		console.log("Efectivo: construyendo");
    }

	
	//crear el panel de nuevo gasto
	this.nuevoGastoPanelCreator();
	
	//crear el panel de nuevo ingreso
	this.nuevoIngresoPanelCreator();
	
	
	//crear el panel de operacion intersucursales
	this.operacionesInterSucursalesPanelCreator();
	
	
	Aplicacion.Efectivo.currentInstance = this;
	
	return this;
};




Aplicacion.Efectivo.prototype.getConfig = function (){
	return {
	    text: 'Efectivo',
	    cls: 'launchscreen',
	    items: [{
	        text: 'Gastos',
	        card: this.nuevoGastoPanel,
	        leaf: true
	    },
	    {
	        text: 'Ingresos',
	        card: this.nuevoIngresoPanel,
	        leaf: true
	    },
	    {
	        text: 'Operaciones entre Sucursales',
	        //card: this.operacionesInterSucursalesPanel,
	        items: [{
		        text: 'Efectivo',
		        card: this.nuevoGastoPanel,
		        leaf: true
		    },
		    {
		        text: 'Producto',
		        card: this.nuevoIngresoPanel,
		        leaf: true
		    }]
	    }]
	};
};











/* ********************************************************
	Operacion Inter Sucursales
******************************************************** */

/**
 * Contiene el panel con la forma de nuevo gasto
 */
Aplicacion.Efectivo.prototype.operacionesInterSucursalesPanel = null;


/**
 * Pone un panel en nuevoGastoPanel
 */
Aplicacion.Efectivo.prototype.operacionesInterSucursalesPanelCreator = function (){
	this.operacionesInterSucursalesPanel = new Ext.form.FormPanel({                                                       

			items: [{
				xtype: 'fieldset',
			    title: 'Nueva autorizacion',
			    instructions: 'Todos los campos son necesarios para una nueva autorizacion.',
				items: [
					new Ext.form.Text({
					    id: 'nombreClienteM',
					    label: 'Nombre'
					}),
					new Ext.form.Text({
					    id: 'rfcClienteM',
					    label: 'RFC'
					}),
					new Ext.form.Text({
					    id: 'direccionClienteM',
					    label: 'Direccion'
					}),
					new Ext.form.Text({
					    label: 'Ciudad'
					}),
					new Ext.form.Text({
					    id: 'emailClienteM',
					    label: 'E-mail'
					}),
					new Ext.form.Text({
					    id: 'telefonoClienteM',
					    label: 'Telefono'
					}),
					new Ext.form.Text({
					    id: 'descuentoClienteM',
					    label: 'Descuento'
					}),
					new Ext.form.Text({
					    id: 'limite_creditoClienteM',
					    label: 'Lim. Credito'
					})
				]}
		]});
};








/* ********************************************************
	Nuevo Gasto
******************************************************** */


/**
 * Validar los datos de la forma de nuevo gasto
 */
Aplicacion.Efectivo.prototype.nuevoGastoValidator = function ()
{
	//obtener los valores de la forma
	var values = Aplicacion.Efectivo.currentInstance.nuevoGastoPanel.getValues();
	
	if( isNaN(values.monto) || values.monto.length == 0 ){
		console.log("no ok");		
		return;
	}
	
	console.log("ok");
	
	/*
	
	Ext.Anim.run( Ext.getCmp("Efectivo-CrearGasto"), "fade", { duration: 250, out: false, autoClear : false , after : function (){ }});
	
	
	Ext.Anim.run( Ext.getCmp("Efectivo-CrearGasto"), "fade", { duration: 250, out: false, autoClear : false }); //in
	Ext.Anim.run( Ext.getCmp("Efectivo-CrearGasto"), "fade", { duration: 250, out: true, autoClear : false }); //out
	*/
};






/**
 * Contiene el panel con la forma de nuevo gasto
 */
Aplicacion.Efectivo.prototype.nuevoGastoPanel = null;


/**
 * Pone un panel en nuevoGastoPanel
 */
Aplicacion.Efectivo.prototype.nuevoGastoPanelCreator = function (){
	
    var menu = [{
        xtype : "spacer"
    },{
		id : "Efectivo-CrearGasto",
        text: 'Crear Gasto',
        ui: 'forward',
		hidden : false

    }];




	var dockedItems = [new Ext.Toolbar({
		ui: 'light',
		dock: 'bottom',
		items: menu
	})];
	
	this.nuevoGastoPanel = new Ext.form.FormPanel({                                                       
		dockedItems: dockedItems,
			items: [{

				xtype: 'fieldset',
			    title: 'Nuevo gasto',
			    instructions: 'Todos los campos son necesarios para una nueva autorizacion.',
				defaults : {
					listeners : {
						"change" : function (){
							Aplicacion.Efectivo.currentInstance.nuevoGastoValidator();
						}
					}
				},
				items: [
					{
						xtype : "textfield",
						label : "Monto",
						name : "monto",
						listeners : {
							'focus' : function (){

								kconf = {
									type : 'num',
									submitText : 'Aceptar',
									callback : Aplicacion.Efectivo.currentInstance.nuevoGastoValidator
								};
								POS.Keyboard.Keyboard( this, kconf );
							}
						}
						
					},{
						xtype : "textfield",
						label : "Folio",
						name : "folio",
						listeners : {
							'focus' : function (){

								kconf = {
									type : 'alfa',
									submitText : 'Aceptar',
									callback : Aplicacion.Efectivo.currentInstance.nuevoGastoValidator
								};
								POS.Keyboard.Keyboard( this, kconf );
							}
						}

					},{
						xtype : "datepickerfield",
						label : "Fecha",
						picker : { yearFrom : 2010 },
						name : "fecha"
					},{
						xtype : "selectfield",
						label : "Concepto",
						name : "concepto",
						options : [
							{
								text : "Luz",
								value : "luz"
							},{
								text : "Predial",
								value : "predial"
							},{
								text : "Sueldo",
								value : "sueldo"
							},{
								text : "Otro",
								value : "otro"
							}
						]
					},{
						xtype : "textfield",
						label : "Nota",
						name : "nota",
						listeners : {
							'focus' : function (){

								kconf = {
									type : 'alfa',
									submitText : 'Aceptar',
									callback : Aplicacion.Efectivo.currentInstance.nuevoGastoValidator
								};
								POS.Keyboard.Keyboard( this, kconf );
							}
						}
					}
				]}
		]});
};
























/* ********************************************************
	Nuevo Ingreso
******************************************************** */


/*
 * Guarda el panel donde estan la forma de nuevo cliente
 **/
Aplicacion.Efectivo.prototype.nuevoIngresoPanel = null;






/*
 * Se llama para crear por primera vez el panel de nuevo cliente
 **/
Aplicacion.Efectivo.prototype.nuevoIngresoPanelCreator = function (  ){
	if(DEBUG){ console.log ("creando panel de historial de autorizaciones"); }
	
	
	this.nuevoIngresoPanel = new Ext.form.FormPanel({                                                       
		items: [{
			xtype: 'fieldset',
		    title: 'Nueva autorizacion',
		    instructions: 'Todos los campos son necesarios para una nueva autorizacion.',
			items: [
				new Ext.form.Text({
				    id: 'nombreClienteM',
				    label: 'Nombre'
				}),
				new Ext.form.Text({
				    id: 'rfcClienteM',
				    label: 'RFC'
				}),
				new Ext.form.Text({
				    id: 'direccionClienteM',
				    label: 'Direccion'
				}),
				new Ext.form.Text({
				    label: 'Ciudad'
				}),
				new Ext.form.Text({
				    id: 'emailClienteM',
				    label: 'E-mail'
				}),
				new Ext.form.Text({
				    id: 'telefonoClienteM',
				    label: 'Telefono'
				}),
				new Ext.form.Text({
				    id: 'descuentoClienteM',
				    label: 'Descuento'
				}),
				new Ext.form.Text({
				    id: 'limite_creditoClienteM',
				    label: 'Lim. Credito'
				})
			]}
	]});


	
};

















POS.Apps.push( new Aplicacion.Efectivo() );






