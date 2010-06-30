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

ApplicationPagos.prototype.storeVentas = null;
ApplicationPagos.prototype.storePagos = null;

ApplicationPagos.prototype.datosVentas = null;
ApplicationPagos.prototype.datosPagos = null;

ApplicationPagos.prototype._init = function()
{
	
	//iniciar variables
	
	//nombre de la aplicacion
	this.appName = "Pagos";
	
	//ayuda sobre esta applicacion
	this.ayuda = "Ayuda sobre este modulo de prueba <br>, html es valido <br> :D";
	
	//submenues en el panel de la izquierda
	this.leftMenuItems = [
	{
        text: 'Listar',
        ayuda: 'SE ENLISTAN TODOS LOS CLIENTES REGISTRADOS EN PAPAS SUPREMAS',
		card: this.mainCard
    },
    {
        text: 'Abonar',
       	card: this.abonar,
        ayuda: 'ayuda en SECOND'
    }
	];
	
	
	
	this._initToolBar();
	
};//fin CONSTRUCTOR

btnClientes=new Ext.Button({
			hidden:'true',		
			handler:function (){
				POS.aviso("ok","ok");
			},
            name: 'id_cliente',
			width: 300,
            text : 'Seleccionar Cliente',
            options: [{
                text: 'nombre',
                value: 'id'
            },{
                text: 'name',
                value: 'id_c'
            }]
        });
de =new Ext.form.SearchField({
			fieldLabel: 'Del:',
			name: 'fecha_inicio:',
			allowBlank:false
		});
al =new Ext.form.TextField({
			fieldLabel: 'al:',
			name: 'fecha_fin:',
			allowBlank:false
		});
fechas = new Ext.form.FormPanel({
	hidden:'true',
    items: [de,al]
});

formulario=new Ext.form.FormPanel({
		minHeight:80,
    items: [{
        xtype: 'fieldset',
        title: 'Buscar por:',
        instructions: 'Insertelos datos y de click en el boton de buscar.(formato de fecha DD/MM/AA)',
        items: [btnClientes,fechas]
    }]
});

//Iniciar el toolbar
ApplicationPagos.prototype._initToolBar = function (){

	if(DEBUG)console.log("iniciando el tool bar");


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
            handler: clickDeudores
        }, {
            text: 'Pagados',
            handler: clickPagados
        }, {
            text: 'Todos',
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
            items: buttonsGroup1.concat(buttonsGroup2).concat(buttonsGroup3)
			
        })];
    }else {
        this.dockedItems = [{
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

Ext.regModel('modeloVentas', {
    fields: ['id_venta','total', 'pagado', 'debe', 'nombre', 'fecha']
});



storeVentas = new Ext.data.Store({
    model: 'modeloVentas',
    sorters: 'nombre',
    getGroupString : function(record) {
        return record.get('nombre')[0];
    }
});

Ext.regModel('modeloPagos', {
    fields: ['id_pago','id_venta','fecha','monto']
});



storePagos = new Ext.data.Store({
    model: 'modeloPagos',
    sorters: 'fecha',
    getGroupString : function(record) {
        return record.get('fecha')[0];
    }
});


ApplicationPagos.prototype.mainCard = new Ext.Panel({
   
    scroll: 'vertical',
	cls: 'cards',
	layout: {
		type: 'vbox',
		align: 'strech'
	},
	
    listeners: {
		beforeshow : function(component){
						
						POS.AJAXandDECODE({
							method: 'reporteClientesComprasCreditoDeben'
							},
							function (datos){
								this.datosVentas = datos.datos;
								storeVentas.loadData(this.datosVentas); 
							},
							function (){//no responde
								POS.aviso("ERROR",":(");
							}
						);	//ajaxAndDecode
		}//fin before
	},
    items: [formulario,
			{
						xtype: 'toolbar',
						dock: 'bottom',
						title: "<pre>VENTA	     TOTAL	PAGADO	      ADEUDA		    CLIENTE			FECHA</pre>"
				},
			{
				width: '100%',
				height: '100%',
				xtype: 'list',
				store: storeVentas,
				tpl: 	'<tpl for="."><div class="modeloVentas"><pre>{id_venta}		{total}		{pagado} 		{debe} 		{nombre} 		   {fecha}</pre></div></tpl>',
				itemSelector: 'div.modeloVentas',
				singleSelect: true,
				indexBar: true,
				listeners: {
					selectionchange: function(){
						try{
							if (this.getSelectionCount() == 1) {
								var id=this.getSelectedRecords()[0].id_venta;
								
								POS.AJAXandDECODE({
									method: 'listarPagosVentaDeVenta',
									id_venta:id
									},
									function (datos){
										if(datos.success){
											this.datosPagos = datos.datos;
											storePagos.loadData(this.datosPagos); 
										}else{
											POS.aviso("error",datos.reason);
										}
									},
									function (){//no responde
										POS.aviso("ERROR",":(");
									}
								);	//ajaxAndDecode
												
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
		        store: storePagos,
		        tpl: '<tpl for="."><div class="pagos"><pre>	   {id_pago}		  {fecha}		   {monto}		</pre></div></tpl>',
		        itemSelector: 'div.pagos',
		        singleSelect: true,
		        indexBar: true
		    }],
			dockedItems: [{
                    xtype: 'toolbar',
                    dock: 'bottom',
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
					}]
                }
            ]
		};				
								
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
		
							}//if seleccionado 1
						}catch(e){ 
							//EN CASO DE ERROR AGREGAR CODIGO
						}//try-catch
					}//selectionChange
				}//listeners
			}
			]//items principal	
});

ApplicationPagos.prototype.abonar = new Ext.form.FormPanel({

    });
	
this.clickBuscar = function ()
{
	if(!btnClientes.isVisible()){
			if(!fechas.isVisible()){
				POS.aviso("Buscar", "Buscar al cliente: "+btnClientes.getValue()+" del:"+de.getValue()+" al "+al.getValue()); 				
			}else{
				POS.aviso("Buscar", "Buscar al cliente: "+btnClientes.getValue()); 			
			}	
	}else{
			if(!fechas.isVisible()){
				POS.aviso("Buscar", "Buscar ventas a credito del: "+de.getValue()+" al: "+al.getValue()); 
			}else{	
				POS.aviso("Imposible", "No se puede buscar ya que no hay ninguna opcion seleccionada"); 
			}
	}
};
this.clickDeudores = function ()
{
	POS.AJAXandDECODE({
						method: 'reporteClientesComprasCreditoDeben'
						},
						function (datos){
							this.datosVentas = datos.datos;
							storeVentas.loadData(this.datosVentas); 
						},
						function (){//no responde
							POS.aviso("ERROR",":(");
						}
					);
};

this.clickPagados = function ()
{
	POS.AJAXandDECODE({
						method: 'reporteClientesComprasCreditoPagado'
						},
						function (datos){
							this.datosVentas = datos.datos;
							storeVentas.loadData(this.datosVentas); 
						},
						function (){//no responde
							POS.aviso("ERROR",":(");
						}
					);
};

this.clickTodos = function ()
{
	POS.AJAXandDECODE({
						method: 'reporteClientesComprasCredito'
						},
						function (datos){
							this.datosVentas = datos.datos;
							storeVentas.loadData(this.datosVentas); 
						},
						function (){//no responde
							POS.aviso("ERROR",":(");
						}
					);
};

this.clickCliente = function ()
{
	if(btnClientes.isVisible()){
			btnClientes.setVisible(true);
			formulario.setHeight(formulario.getHeight()+30)
	}else{
			btnClientes.setVisible(false);
			formulario.setHeight(formulario.getHeight()-30)
	}
};

this.clickPeriodo = function ()
{
	if(fechas.isVisible()){
			fechas.setVisible(true);
			formulario.setHeight(formulario.getHeight()+70)
	}else{
			fechas.setVisible(false);
			formulario.setHeight(formulario.getHeight()-70)
	}
};

//autoinstalar esta applicacion
AppInstaller( new ApplicationPagos() );
