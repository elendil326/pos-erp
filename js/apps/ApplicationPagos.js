ApplicationPagos= function ()
{
	if(DEBUG){
		console.log("ApplicationPagos: construyendo");
	}
	ApplicationPagos.currentInstance = this;	
	//ApplicationPagos.prototype.currentInstance=this;
	this._init();

	return this;
	
	
};


//variables de esta clase, NO USEN VARIABLES ESTATICAS A MENOS QUE SEA 100% NECESARIO

//aqui va el panel principal 
ApplicationPagos.prototype.mainCard = null;


//aqui va el nombre de la applicacion
ApplicationPagos.prototype.appName = null;

//aqui va el nombre de la applicacion
ApplicationPagos.prototype.leftMenuItems = null;

//aqui va un texto de ayuda en html
ApplicationPagos.prototype.ayuda = null;

//store en el que tendra los datos de todas las compras a credito
ApplicationPagos.storeVentasCredito = null;

//datos de las ventas a credito del AJAXANDDECODE
ApplicationPagos.prototype.datosVentas = null;
//datos de los pagos de las ventas a credito del AJAXANDDECODE
ApplicationPagos.prototype.datosPagos = null;
//formulario de las fechas a seleccionar
ApplicationPagos.fechas = null;
//boton para seleccionar un cliente
ApplicationPagos.btnClientes = null;
//formualario en donde se colocaran los botones de cliente y fechas
ApplicationPagos.formulario = null;
//funcion que llamarala ventana parapagar las ventas
ApplicationPagos.prototype.PagarVenta=null;

//variables que se ocupan para la busqueda de ventas a credito
//id de cliente a buscar
ApplicationPagos.idCliente = null;
//fecha de inicio a buscar
ApplicationPagos.fechaInicio = new Date();
//fecha hasta la que se buscara
ApplicationPagos.fechaFin = new Date();
//tipo de busqueda, 1=adeudan, 2=pagados, 3=todos
ApplicationPagos.tipo = 1;

ApplicationPagos.prototype.id = null;
ApplicationPagos.prototype.debe = null;

//constructor.
ApplicationPagos.prototype._init = function()
{
	
	//iniciar variables

	
	//nombre de la aplicacion
	this.appName = "Pagos";
	
	//ayuda sobre esta applicacion
	this.ayuda = "Ayuda sobre este modulo de prueba <br>, html es valido <br> :D";
	
	//submenues en el panel de la izquierda
	//no agrega ninguno
	
	//agregamos el toolbar
	this._initToolBar();
	
};//fin CONSTRUCTOR



//Iniciar el toolbar
ApplicationPagos.prototype._initToolBar = function (){
	
	//primer grupo de botones, solo el boton de buscar
	var buttonsGroup1 = [{
        text: 'Buscar',
        ui: 'round',
        handler: clickBuscar
    }];
	//grupo 2 la busqueda por estado de credito
	var buttonsGroup2 = [{
        xtype: 'splitbutton',
        activeButton: 0,
        items: [{
            text: 'Deudores',
			id:'btnDeudores',
            handler: clickDeudores
        }, {
            text: 'Pagados',
			id:'btnPagados',
            handler: clickPagados
        }, {
            text: 'Todos',
			id:'btnTodos',
            handler: clickTodos
        }]    
    }];
	//grupo 3 activar busqueda por cliente y/o periodo
    var buttonsGroup3 = [{
        text: 'Cliente',
        handler: clickCliente
    },{
        text: 'Periodo',
        handler: clickPeriodo
    }];
	//agregamos los grupos de botones a la tool bar
	if (!Ext.platform.isPhone) {
        buttonsGroup1.push({xtype: 'spacer'});
        buttonsGroup2.push({xtype: 'spacer'});
        
        this.dockedItems = [new Ext.Toolbar({
            // dock this toolbar at the bottom
            ui: 'light',
            dock: 'top',
			scroll: 'horizontal',
            items: buttonsGroup1.concat(buttonsGroup2).concat(buttonsGroup3)
        })];
    }else {
        this.dockedItems = [{
			
			scroll: 'horizontal',
            xtype: 'toolbar',
            ui: 'light',
            items: buttonsGroup1,
            dock: 'bottom'
        }, {
            xtype: 'toolbar',
            ui: 'dark',
            items: buttonsGroup2,
            dock: 'bottom'
        }, {
            xtype: 'toolbar',
            ui: 'metal',
            items: buttonsGroup3,
            dock: 'bottom'
        }];
    }
	//agregar este dock a el panel principal
	this.mainCard.addDocked( this.dockedItems );
};
//fin del toolbar
		
//-------------------------------------------------------------------------------------
//								Funciones para los pickers.
//-------------------------------------------------------------------------------------
//picker para la fecha de inicio
this.clickFechaInicio=function(){

    var fechaActual = new Date();
 
    var dia = ApplicationPagos.fechaInicio.getDate();
    var mes = ApplicationPagos.fechaInicio.getMonth();
    var anio = ApplicationPagos.fechaInicio.getYear()+1900;
	 var picker = new Ext.DatePicker({
            floating: true,
            width: (!Ext.platform.isPhone ? 400 : 320),
            height: Ext.platform.isAndroidOS ? 320 : (!Ext.platform.isPhone ? 356 : 300),
            useTitles: false,
            value: {
                day: dia,
                month: mes,
                year: anio	
            },
            dockedItems: [{
                xtype: 'toolbar',
                dock: 'bottom',
                title: 'Inicio de periodo',
                items: [{xtype: 'component', flex: 1},{
                    xtype: 'button',
                    ui: 'action',
                    text: 'OK',
                    handler: function() {
                        ApplicationPagos.fechaInicio = (picker.getValue()<=fechaActual)?picker.getValue():fechaActual;
						if(ApplicationPagos.fechaFin !== null ){
							ApplicationPagos.fechaInicio = (ApplicationPagos.fechaFin<ApplicationPagos.fechaInicio)?ApplicationPagos.fechaFin:ApplicationPagos.fechaInicio;
						}
						if(ApplicationPagos.fechaInicio !== null ){
							Ext.get("finicio").update((ApplicationPagos.fechaInicio.getYear()+1900)+"-"+(ApplicationPagos.fechaInicio.getMonth()+1)+"-"+(ApplicationPagos.fechaInicio.getDate()));
						}
						picker.hide();
                    }
                }]
            }]
        });
        picker.show();
};
//picker fecha inicio

//picker fin de periodo
this.clickfechaFin=function(){
    var fechaActual = new Date();
    var dia = ApplicationPagos.fechaFin.getDate();
    var mes = ApplicationPagos.fechaFin.getMonth();
    var anio = ApplicationPagos.fechaFin.getYear()+1900;
	 var picker = new Ext.DatePicker({
            floating: true,
            width: (!Ext.platform.isPhone ? 400 : 320),
            height: Ext.platform.isAndroidOS ? 320 : (!Ext.platform.isPhone ? 356 : 300),
            useTitles: false,
            value: {
                day: dia,
                month: mes,
                year: anio	
            },
            dockedItems: [{
                xtype: 'toolbar',
                dock: 'bottom',
                title: 'Fin de periodo',
                items: [{xtype: 'component', flex: 1},{
                    xtype: 'button',
                    ui: 'action',
                    text: 'OK',
                    handler: function() {
                        ApplicationPagos.fechaFin = (picker.getValue()<=fechaActual)?picker.getValue():fechaActual;
                        if(ApplicationPagos.fechaInicio !== undefined ){
							ApplicationPagos.fechaFin = (ApplicationPagos.fechaFin<ApplicationPagos.fechaInicio)?ApplicationPagos.fechaInicio:ApplicationPagos.fechaFin;
						}
						Ext.get("ffin").update((ApplicationPagos.fechaFin.getYear()+1900)+"-"+(ApplicationPagos.fechaFin.getMonth()+1)+"-"+(ApplicationPagos.fechaFin.getDate()));
						picker.hide();
                    }
                }]
            }]
        });
        picker.show();

};
//picker fecha fin

//--------------------------------------------------------------------------------
//					Inicializamos los controles del formulario
//--------------------------------------------------------------------------------

//Boton de clientes
ApplicationPagos.btnClientes=new Ext.Button({
			hidden:'true',		
			handler:function (){
				POS.aviso("ok","ok");
			},
            name: 'id_cliente',
			width: 300,
            text : 'Seleccionar Cliente'
        });	
//btnClientes		

//formulario de las fechas
ApplicationPagos.fechas = new Ext.form.FormPanel({
	hidden:'true',
    items: [
	{
		html: '',
		id : 'bCliente'
	},
	{
		xtype:'button',
		id:'btnFechaIni',
		width: 300,
		text : 'Fecha de inicio de periodo',
		handler:clickFechaInicio
	},{
		html:(ApplicationPagos.fechaInicio.getYear()+1900)+"-"+(ApplicationPagos.fechaInicio.getMonth()+1)+"-"+(ApplicationPagos.fechaInicio.getDate()),
		id : 'finicio'
	},
	{
		xtype:'button',
		id:'btnApplicationPagos.fechaFin',
		width: 300,
		text : 'Fecha de fin de periodo',
		handler:clickfechaFin
	},{
		html: (ApplicationPagos.fechaFin.getYear()+1900)+"-"+(ApplicationPagos.fechaFin.getMonth()+1)+"-"+(ApplicationPagos.fechaFin.getDate()),
		id : 'ffin'
	}]
});
//fechas

//agregamos los controles al formulario
ApplicationPagos.formulario=new Ext.form.FormPanel({
	minHeight:80,
    items: [{
        xtype: 'fieldset',
        title: 'Buscar por:',
        instructions: 'Insertelos datos y de click en el boton de buscar.',
        items: [	ApplicationPagos.btnClientes,ApplicationPagos.fechas	]
    }]
});
//formulario

//------------------------------------------------------------------------------------------
//							Inicializamos el store de ventas a credito
//------------------------------------------------------------------------------------------

//agregamos el modelo para ver las ventas a credito
Ext.regModel('modeloVentas', {
    fields: ['id_venta','total', 'pagado', 'debe', 'nombre', 'fecha']
});

//inicializamos el store de ventas a credito
ApplicationPagos.storeVentasCredito = new Ext.data.Store({
    model: 'modeloVentas',
    sorters: 'fecha',
    getGroupString : function(record) {
        return record.get('nombre')[0];
    }
});
//store ventas credito


//------------------------------------------------------------------------------------------
//					Funciones AjaxAndDecode para los datos de los stores
//------------------------------------------------------------------------------------------

//ajax and decode para la busqueda de ventas a credito, funciona en todos los casos de busqueda
ApplicationPagos.funcion_ajax_ventas_credito = function(cliente,deFecha,aFecha){
//	-cliente el el id de cliente a buscar
//	-defecha es el inicio de la fecha busqueda
//	-afecha el el fin de la fecha de busqueda

//El tipo nos da si van ser adeudados, pagados o todos
tipo=ApplicationPagos.tipo;

//segun el tipo elejimos el metodo que enviaremos al ajaxAndDecode
var metodo=(tipo==1)?'reporteClientesComprasCreditoDeben':((tipo==2)?'reporteClientesComprasCreditoPagado':'reporteClientesComprasCredito');
						
						if(DEBUG){console.log("llamando funcion_ajax_ventas_credito");}
						POS.AJAXandDECODE({
							//Parametros a enviar
							method: metodo,
							//id del cliente a buscar, si es null nos devuelve todos
							id_cliente: cliente,
							//fechas del periodo, si son null muestra todos
							de: deFecha,
							al: aFecha
							},
							function (datos){
								if(datos.success){
									//el resultado es exitoso
									this.datosVentas = datos.datos;
									//cargamos los datos al store
									ApplicationPagos.storeVentasCredito.loadData(this.datosVentas); 
								}else{
									//limpia el store si no encuentra datos
									ApplicationPagos.storeVentasCredito.loadData(new Array()); 
								}
							},
							function (){//no responde
								POS.aviso("ERROR",":(");
							}
						);	//ajaxAndDecode
};
//funcion_ajax_ventas_credito

//ajaxAndDecode para los pagos de la venta
ApplicationPagos.prototype.funcion_ajax_pagos = function(){

	//registramos el modelo de los pagos de la venta
	Ext.regModel('modeloPagos', {
		fields: ['id_pago','id_venta','fecha','monto']
	});

	//definimos el store
	var storePagos = new Ext.data.Store({
		model: 'modeloPagos',
		sorters: 'fecha',
		getGroupString : function(record) {
			return record.get('fecha')[0];
		}
	});
	
	//llamamos ajaxAndDecode para asignar los datos al store
	POS.AJAXandDECODE({
		method: 'listarPagosVentaDeVenta',
		//mandamos el id de la venta para la que buscamos los datos
		id_venta:ApplicationPagos.currentInstance.id
		},
		//peticion correcta
		function (datos){
			if(datos.success){
					//asignamos datos al store
					this.datosPagos = datos.datos;
					storePagos.loadData(this.datosPagos); 
			}else{
				//si el success es false el store queda vacio
				//si nos regresa alguna razon de por que falla nos manda la razon
				if(datos.reason!==undefined){		POS.aviso("error",datos.reason);	}
			}
			//llamamos a la ventana que nos muestra los pagos, le enviamos el store para que aparezca hasta que este tenga datos
			ApplicationPagos.currentInstance.muestraPagos(storePagos);
		},
		function (){//no responde
			POS.aviso("ERROR",":(");
		}
	);	//ajaxAndDecode
};
//funcion_ajax_pagos

//----------------------------------------------------------------------------------
//						Formularios dinamicos eventos
//----------------------------------------------------------------------------------

//formulario para mostrar los pagos de ventas a credito
ApplicationPagos.prototype.muestraPagos=function(store){	
		ApplicationPagos.currentInstance.formBase = {
			//	items
			items: [
				{
					scroll: 'vertical',
					id:'formPago',
					items: [
						{
							xtype: 'fieldset',
							title: 'Pagar venta '+ApplicationPagos.currentInstance.id+" adeuda: "+ApplicationPagos.currentInstance.debe,
							//agregamos el boton al formulario
							items: [
								{
									xtype:'numberfield',
									name: 'monto',
									label: 'Cantidad',
									id: 'montoPago'
								}							
							]
						}
					]
				}
				,
				{
                    xtype: 'toolbar',
                    dock: 'bottom',
					title: "<pre>No. PAGO		FECHA		MONTO</pre>"
				},
				{
		        width: "100%",
				height:"70%",
				id: 'ListaPagos',
		        xtype: 'list',
		        store: store,
		        tpl: '<tpl for="."><div class="pagos"><pre>	   {id_pago}		  {fecha}		   ${monto}		</pre></div></tpl>',
		        itemSelector: 'div.pagos',
		        singleSelect: true,
		        indexBar: true
		    }
			]//items
			,
			dockedItems: [{
                    xtype: 'toolbar',
                    dock: 'bottom',
					scroll:'horizontal',
                    items: [{
						text: 'Cerrar',
						handler: function() {
							//ocultar esta tabla
							ApplicationPagos.currentInstance.form.hide();	
							ApplicationPagos.currentInstance.form.destroy();
							ApplicationPagos.currentInstance.formBase.destroy();		
							//destruir la lista
							if( Ext.getCmp('ListaPagos') ){
									Ext.getCmp('ListaPagos').store = null;
									Ext.getCmp('ListaPagos').destroy();
								}
                            }
					},{
						xtype:'button',
						text: 'Pagar',
						id:'btnPagarVenta',
						listeners: {
							added : function(){
								if(ApplicationPagos.currentInstance.debe<=0)
								{
									Ext.getCmp('formPago').hide();
									this.hide();
								}
							}//fin before
						},
						handler: function() 
						{	
						
							var patron=/^\d*.\d{0,2}$/;
							var cantidad=Ext.getCmp('montoPago').getValue();
							//verificamos que la cantidad sea valida
							if (patron.test(cantidad)){
							//cantidad valida
								//ajax and decode que guarda el pago
								POS.AJAXandDECODE({
									//mandamos el id de la venta y la cantidad
									method: 'insertarPagoVenta',
									id_venta: ApplicationPagos.currentInstance.id,
									monto: cantidad
									},
									function (datos){
									//peticion exitosa
										if(datos.success){
											//nos envia un success true
											if(datos.cambio===undefined)
											{
												//si no nos regresa cambio
												POS.aviso("Guardado","Pago guardado correctamente");
											}
											else
											{
												//si tenemos que devolver cambio
												POS.aviso("Guardado","Pago guardado correctamente. Cambio: $"+datos.cambio);
											}
											//actualizamos la lista de ventas a credito
											ApplicationPagos.funcion_ajax_ventas_credito(null,null,null);
										}
										else
										{
											//nos devuelve un succes false
											POS.aviso("Vacio.",datos.reason);
										}
									},
									function (){
										//no responde la peticion
										POS.aviso("ERROR","Error al guardar los datos");
									}
								);	//ajaxAndDecode
								ApplicationPagos.currentInstance.form.hide();	
								ApplicationPagos.currentInstance.form.destroy();	
								ApplicationPagos.currentInstance.formBase.destroy();	
								//destruir la lista
								if( Ext.getCmp('ListaPagos') ){
										Ext.getCmp('ListaPagos').store = null;
										Ext.getCmp('ListaPagos').destroy();
									}
							}
							else 
							{
								//cantidad invalida
								POS.aviso("ERROR","No inserto una cantidad valida");
								
							}
														
						}//handler boton pagar
					}//boton pagar
					]//items toolbar
                }]//docked
		};//fromulario
        if (Ext.platform.isPhone) {
            ApplicationPagos.currentInstance.formBase.fullscreen = true;
        } else {
            Ext.apply(ApplicationPagos.currentInstance.formBase, {
                autoRender: true,
                floating: true,
                modal: true,
                centered: true,
                hideOnMaskTap: false,
                height: 500,
                width: 500
            });
        }
        ApplicationPagos.currentInstance.form = new Ext.Panel(ApplicationPagos.currentInstance.formBase);
        ApplicationPagos.currentInstance.form.show();
};//muestraPagos




//--------------------------------------------------------------------------------------------------
//				PANEL PRINCIPAL (MAINCARD)
//--------------------------------------------------------------------------------------------------


//Panel Principal
ApplicationPagos.prototype.mainCard = new Ext.Panel({
   
    scroll: 'vertical',

	cls: 'cards',
	layout: {
		type: 'vbox',
		align: 'strech'
	},
	
    listeners: {
		beforeshow : function(component){
						ApplicationPagos.funcion_ajax_ventas_credito(null,null,null);
		}//fin before
	},
    items: [ApplicationPagos.formulario,
			{
						xtype: 'toolbar',
						dock: 'bottom',
						title: "<pre>VENTA	     TOTAL	PAGADO	      ADEUDA		    CLIENTE			FECHA</pre>"
				},
			{
				width: '100%',
				height: '100%',
				xtype: 'list',
				id:'listaVentas',
				store: ApplicationPagos.storeVentasCredito,
				tpl: 	'<tpl for="."><div class="modeloVentas"><pre>{id_venta}		{total}		{pagado} 		{debe} 		{nombre} 		   {fecha}</pre></div></tpl>',
				itemSelector: 'div.modeloVentas',
				singleSelect: true,
				indexBar: true,
				listeners: {
					selectionchange:function(){
						try{
							ApplicationPagos.currentInstance.id=this.getSelectedRecords()[0].id_venta;
							ApplicationPagos.currentInstance.debe=this.getSelectedRecords()[0].debe;
							if (this.getSelectionCount() == 1){
								ApplicationPagos.currentInstance.funcion_ajax_pagos();
							}
						}catch(e){ 
							//EN CASO DE ERROR AGREGAR CODIGO
						}//try-catch
					}					
				}//listeners
			}
			]//items principal	
});
//fin de panel principal

//--------------------------------------------------------------------------
//									Eventos botones
//--------------------------------------------------------------------------

//clickBuscar
this.clickBuscar = function ()
{
	//obtiene las fechas del periodo
	var de=Ext.get("finicio").dom.textContent;
	var al=Ext.get("ffin").dom.textContent;
	//obtiene el id del cliente
	var cliente=ApplicationPagos.idCliente;
	
	//verifica el caso, es decir si se busca por periodo, fecha, ambos o ninguno y manda los parametros a la carga de la lista
	if(!ApplicationPagos.btnClientes.isVisible()){
			if(!(ApplicationPagos.fechas.isVisible())){
				ApplicationPagos.funcion_ajax_ventas_credito(cliente,de,al);				
			}else{
				ApplicationPagos.funcion_ajax_ventas_credito(cliente,null,null);		
			}	
	}else{
			if(! (ApplicationPagos.fechas.isVisible())){
				ApplicationPagos.funcion_ajax_ventas_credito(null,de,al);
			}else{
				ApplicationPagos.funcion_ajax_ventas_credito(null,null,null);
			}
	}
};
//clickBuscar

//click Deudores
this.clickDeudores = function ()
{
	//ponemos el tipo a 1 (deudores) y llamamos la funcion funcion_ajax_ventas_credito para llenar la lista
	ApplicationPagos.tipo=1;
	clickBuscar();
};
//clickDeudores

//clickPagados
this.clickPagados = function ()
{
	//ponemos el tipo a 2 (pagados) y llamamos la funcion funcion_ajax_ventas_credito para llenar la lista
	ApplicationPagos.tipo=2;
	clickBuscar();
};
//clickPagados

//clickTodos
this.clickTodos = function ()
{
	//ponemos el tipo a 3 (todos) y llamamos la funcion funcion_ajax_ventas_credito para llenar la lista
	ApplicationPagos.tipo=3;
	clickBuscar();
};
//clickTodos

//clickCliente
this.clickCliente = function ()
{
	//muestra el boton de seleccionar cliente o lo esconde si esta visible
	if(ApplicationPagos.btnClientes.isVisible()){
			ApplicationPagos.btnClientes.setVisible(true);
			ApplicationPagos.formulario.setHeight(ApplicationPagos.formulario.getHeight()+30);
	}else{
			ApplicationPagos.btnClientes.setVisible(false);
			ApplicationPagos.formulario.setHeight(ApplicationPagos.formulario.getHeight()-30);
	}
};
//clickCliente

//clickPeriodo
this.clickPeriodo = function ()
{
	//muestra el botones de seleccionar periodo o lo esconde si esta visible
	if(ApplicationPagos.fechas.isVisible()){
			ApplicationPagos.fechas.setVisible(true);
			ApplicationPagos.formulario.setHeight(ApplicationPagos.formulario.getHeight()+80);
	}else{
			ApplicationPagos.fechas.setVisible(false);
			ApplicationPagos.formulario.setHeight(ApplicationPagos.formulario.getHeight()-80);
	}
};
//clickPeriodo

//autoinstalar esta applicacion
AppInstaller( new ApplicationPagos() );