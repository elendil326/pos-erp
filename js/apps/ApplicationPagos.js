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

ApplicationPagos.prototype.clientesContainer= null;

ApplicationPagos.storeVentas = null;

ApplicationPagos.prototype.datosVentas = null;
ApplicationPagos.prototype.datosPagos = null;
ApplicationPagos.fechas = null;
ApplicationPagos.btnClientes = null;
ApplicationPagos.formulario = null;
ApplicationPagos.prototype.PagarVenta=null;

ApplicationPagos.idCliente = null;
ApplicationPagos.fechaInicio = new Date();
ApplicationPagos.fechaFin = new Date();
ApplicationPagos.tipo = 1;

ApplicationPagos.prototype._init = function()
{
	
	//iniciar variables

	
	//nombre de la aplicacion
	this.appName = "Pagos";
	
	//ayuda sobre esta applicacion
	this.ayuda = "Ayuda sobre este modulo de prueba <br>, html es valido <br> :D";
	
	//submenues en el panel de la izquierda
	
	this._initToolBar();
	
};//fin CONSTRUCTOR



//Iniciar el toolbar
ApplicationPagos.prototype._initToolBar = function (){
	
	//Dejo los demas grupos por si quieres agregar mas botones
	//grupo 1, funciones basicas
	var buttonsGroup1 = [{
        text: 'Buscar',
        ui: 'round',
        handler: clickBuscar
    }];
	//grupo 2, cualquier otra mamada
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
	//grupo 3, listo para vender
    var buttonsGroup3 = [{
        text: 'Cliente',
        handler: clickCliente
    },{
        text: 'Periodo',
        handler: clickPeriodo
    }];

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
	//agregar este dock a el panel que quieras
	this.mainCard.addDocked( this.dockedItems );
};

		
		
//Funciones para los pickers.		
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

ApplicationPagos.btnClientes=new Ext.Button({
			hidden:'true',		
			handler:function (){
				POS.aviso("ok","ok");
			},
            name: 'id_cliente',
			width: 300,
            text : 'Seleccionar Cliente'
        });	
		
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




ApplicationPagos.formulario=new Ext.form.FormPanel({
	minHeight:80,
    items: [{
        xtype: 'fieldset',
        title: 'Buscar por:',
        instructions: 'Insertelos datos y de click en el boton de buscar.',
        items: [	ApplicationPagos.btnClientes,ApplicationPagos.fechas	]
    }]
});


Ext.regModel('modeloVentas', {
    fields: ['id_venta','total', 'pagado', 'debe', 'nombre', 'fecha']
});



ApplicationPagos.storeVentas = new Ext.data.Store({
    model: 'modeloVentas',
    sorters: 'fecha',
    getGroupString : function(record) {
        return record.get('nombre')[0];
    }
});



ApplicationPagos.funcion_ajax_ventas = function(cliente,deFecha,aFecha){
tipo=ApplicationPagos.tipo;
var metodo=(tipo==1)?'reporteClientesComprasCreditoDeben':((tipo==2)?'reporteClientesComprasCreditoPagado':'reporteClientesComprasCredito');
						
						if(DEBUG){console.log("llamando funcion_ajax_ventas");}
//							POS.aviso("datos",cliente+"--"+deFecha+"--"+aFecha);
						POS.AJAXandDECODE({
							method: metodo,
							id_cliente: cliente,
							de: deFecha,
							al: aFecha
							},
							function (datos){
								if(datos.success){
									this.datosVentas = datos.datos;
									ApplicationPagos.storeVentas.loadData(this.datosVentas); 
								}else{
									ApplicationPagos.storeVentas.loadData(new Array()); 
								}
							},
							function (){//no responde
								POS.aviso("ERROR",":(");
							}
						);	//ajaxAndDecode
};

ApplicationPagos.funcion_ajax_pagos = function(id,debe){

Ext.regModel('modeloPagos', {
    fields: ['id_pago','id_venta','fecha','monto']
});


var storePagos = new Ext.data.Store({
    model: 'modeloPagos',
    sorters: 'fecha',
    getGroupString : function(record) {
        return record.get('fecha')[0];
    }
});
	POS.AJAXandDECODE({
		method: 'listarPagosVentaDeVenta',
		id_venta:id
		},
		function (datos){
			if(datos.success){
					this.datosPagos = datos.datos;
					storePagos.loadData(this.datosPagos); 
			}else{
				if(datos.reason!==undefined){		POS.aviso("error",datos.reason);	}
			}
			ApplicationPagos.muestraPagos(id,debe,storePagos);
		},
		function (){//no responde
			POS.aviso("ERROR",":(");
		}
	);	//ajaxAndDecode
};




ApplicationPagos.prototype.PagarVenta=function(id,debe){	
		var montoPago=new  Ext.form.NumberField({
                        name: 'monto',
                        label: 'Cantidad',
						id: 'montoPago'
                    });
		var formPagaBase =  {
            scroll: 'vertical',
            items: [
                {
                    xtype: 'fieldset',
                    title: 'Pagar venta '+id+" adeuda: "+debe,
                    items: [montoPago]
                }
            ],
			dockedItems: [{
                    xtype: 'toolbar',
                    dock: 'bottom',
					scroll:'horizontal',
                    items: [{
						text: 'Cerrar',
						handler: function() {
							//ocultar esta tabla
							form.hide();	
							form.destroy();	
                            }
					},{
						xtype:'button',
						text: 'Guardar',
						handler: function() {	
							var patron=/^\d*.\d{0,2}$/;
							if (patron.test(montoPago.getValue())){
								POS.AJAXandDECODE({
									method: 'insertarPagoVenta',
									id_venta: id,
									monto: montoPago.getValue()
									},
									function (datos){
										if(datos.success){
											if(datos.cambio===undefined){POS.aviso("Guardado","Pago guardado correctamente");}
											else{POS.aviso("Guardado","Pago guardado correctamente. Cambio: $"+datos.cambio);}
											ApplicationPagos.funcion_ajax_ventas(null,null,null);
										}else{
											POS.aviso("Vacio.",datos.reason);
										}
									},
									function (){//no responde
										POS.aviso("ERROR","Error al guardar los datos");
									}
								);	//ajaxAndDecode
								form.hide();	
								form.destroy();
							}else {POS.aviso("ERROR","No inserto una cantidad valida");}
						}
					}]
                }
            ]
		};//fromulario
        if (Ext.platform.isPhone) {
            formPagaBase.fullscreen = true;
        } else {
            Ext.apply(formPagaBase, {
                autoRender: true,
                floating: true,
                modal: true,
                centered: true,
                hideOnMaskTap: false,
                height: 150,
                width: 400
            });
        }
        var form = new Ext.form.FormPanel(formPagaBase);
        form.show();
};//PagarVenta




ApplicationPagos.muestraPagos=function(id,debe,store){		
							
		var formBase = {
			//	items
            items: [
				{
                    xtype: 'toolbar',
                    dock: 'bottom',
					title: "<pre>No. PAGO		FECHA		MONTO</pre>"
				},
				{
		        width: "90%",
		        height: "90%",
				id: 'ListaPagos',
		        xtype: 'list',
		        store: store,
		        tpl: '<tpl for="."><div class="pagos"><pre>	   {id_pago}		  {fecha}		   ${monto}		</pre></div></tpl>',
		        itemSelector: 'div.pagos',
		        singleSelect: true,
		        indexBar: true
		    }],
			dockedItems: [{
                    xtype: 'toolbar',
                    dock: 'bottom',
					scroll:'horizontal',
                    items: [{
						text: 'Cerrar',
						handler: function() {
							//ocultar esta tabla
							form.hide();	
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
								if(debe<=0){this.hide();}
							}//fin before
						},
						handler: function() {	
							//ocultar esta tabla
							form.hide();	
							//destruir la lista
							if( Ext.getCmp('ListaPagos') ){
									Ext.getCmp('ListaPagos').store = null;
									Ext.getCmp('ListaPagos').destroy();
								}
								ApplicationPagos.currentInstance.PagarVenta(id,debe);
                            }
					}]
                }
            ]
		};//fromulario
        if (Ext.platform.isPhone) {
            formBase.fullscreen = true;
        } else {
            Ext.apply(formBase, {
                autoRender: true,
                floating: true,
                modal: true,
                centered: true,
                hideOnMaskTap: false,
                height: 500,
                width: 500
            });
        }
        var form = new Ext.Panel(formBase);
        form.show();
};//muestraPagos


ApplicationPagos.prototype.mainCard = new Ext.Panel({
   
    scroll: 'vertical',

	cls: 'cards',
	layout: {
		type: 'vbox',
		align: 'strech'
	},
	
    listeners: {
		beforeshow : function(component){
						ApplicationPagos.funcion_ajax_ventas(null,null,null);
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
				store: ApplicationPagos.storeVentas,
				tpl: 	'<tpl for="."><div class="modeloVentas"><pre>{id_venta}		{total}		{pagado} 		{debe} 		{nombre} 		   {fecha}</pre></div></tpl>',
				itemSelector: 'div.modeloVentas',
				singleSelect: true,
				indexBar: true,
				listeners: {
					selectionchange:function(){
						try{
							var id=this.getSelectedRecords()[0].id_venta;
							var debe=this.getSelectedRecords()[0].debe;
							if (this.getSelectionCount() == 1){
								ApplicationPagos.funcion_ajax_pagos(id,debe);
							}
						}catch(e){ 
							//EN CASO DE ERROR AGREGAR CODIGO
						}//try-catch
					}					
				}//listeners
			}
			]//items principal	
});



this.clickBuscar = function ()
{
	var de=Ext.get("finicio").dom.textContent;
	var al=Ext.get("ffin").dom.textContent;
	var cliente=ApplicationPagos.idCliente;
	if(!ApplicationPagos.btnClientes.isVisible()){
			if(!(ApplicationPagos.fechas.isVisible())){
				POS.aviso("Buscar","Buscar al cliente: "+cliente+" del:"+de+" al "+al); 				
			}else{
				POS.aviso("Buscar","Buscar al cliente: "+cliente); 			
			}	
	}else{
			if(! (ApplicationPagos.fechas.isVisible())){
				ApplicationPagos.funcion_ajax_ventas(null,de,al);
			}else{	
				POS.aviso("Imposible", "No se puede buscar ya que no hay ninguna opcion seleccionada"); 
			}
	}
};
this.clickDeudores = function ()
{
	ApplicationPagos.tipo=1;
	ApplicationPagos.funcion_ajax_ventas(null,null,null);
};

this.clickPagados = function ()
{
	ApplicationPagos.tipo=2;
	ApplicationPagos.funcion_ajax_ventas(null,null,null);
};

this.clickTodos = function ()
{
	ApplicationPagos.tipo=3;
	ApplicationPagos.funcion_ajax_ventas(null,null,null);
};

this.clickCliente = function ()
{
	if(ApplicationPagos.btnClientes.isVisible()){
			ApplicationPagos.btnClientes.setVisible(true);
			ApplicationPagos.formulario.setHeight(ApplicationPagos.formulario.getHeight()+30);
	}else{
			ApplicationPagos.btnClientes.setVisible(false);
			ApplicationPagos.formulario.setHeight(ApplicationPagos.formulario.getHeight()-30);
	}
};

this.clickPeriodo = function ()
{
	if(ApplicationPagos.fechas.isVisible()){
			ApplicationPagos.fechas.setVisible(true);
			ApplicationPagos.formulario.setHeight(ApplicationPagos.formulario.getHeight()+80);
	}else{
			ApplicationPagos.fechas.setVisible(false);
			ApplicationPagos.formulario.setHeight(ApplicationPagos.formulario.getHeight()-80);
	}
};

//autoinstalar esta applicacion
AppInstaller( new ApplicationPagos() );