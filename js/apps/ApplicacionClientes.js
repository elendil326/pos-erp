ApplicacionClientes= function ()
{
	if(DEBUG){
		console.log("ApplicacionClientes: construyendo");
	}
	ApplicacionClientes.currentInstance = this;	
	//ApplicacionClientes.prototype.currentInstance=this;
	this._init();

	return this;
};
//variables de esta clase, NO USEN VARIABLES ESTATICAS A MENOS QUE SEA 100% NECESARIO

//aqui va el panel principal 
ApplicacionClientes.prototype.mainCard = null;

ApplicacionClientes.currentInstance=87;

//aqui va el nombre de la applicacion
ApplicacionClientes.prototype.appName = null;

//aqui va el nombre de la applicacion
ApplicacionClientes.prototype.leftMenuItems = null;

//aqui va un texto de ayuda en html
ApplicacionClientes.prototype.ayuda = null;

ApplicacionClientes.prototype.clientesContainer= null;

ApplicacionClientes.prototype.ClientesListStore = null;

ApplicacionClientes.prototype.ListaClientes= null;

ApplicacionClientes.prototype.customers = null;

ApplicacionClientes.prototype.actualizaCliente=null;

ApplicacionClientes.prototype.updateForm= null;

ApplicacionClientes.prototype._init = function()
{
	//iniciar variables
	//nombre de la aplicacion
	this.appName = "Clientes";
	
	//ayuda sobre esta applicacion
	this.ayuda = "Ayuda sobre este modulo de prueba <br>, html es valido <br> :D";
	
	//submenues en el panel de la izquierda
	this.leftMenuItems = [
	{
        text: 'Ver Clientes',
        ayuda: 'SE ENLISTAN TODOS LOS CLIENTES REGISTRADOS EN PAPAS SUPREMAS',
		card: this.ClientesList
    },
    {
        text: 'Agregar Cliente',
       	card: this.menu2,
        ayuda: 'ayuda en SECOND'
    }
	];
	//panel principal
	this.mainCard = new Ext.Panel({
		cls: 'card card1',
		html: 'MODULO DE CLIENTES'
	});
};//fin CONSTRUCTOR

Ext.regModel('Contact', {
    fields: ['nombre', 'rfc']
});



ClientesListStore = new Ext.data.Store({
    model: 'Contact',
    sorters: 'nombre',
    getGroupString : function(record) {
        return record.get('nombre')[0];
    }	
});

ApplicacionClientes.prototype.ClientesList = new Ext.Panel({
	id: 'panelClientes',
    layout: Ext.platform.isPhone ? 'fit' : {
        type: 'vbox',
        align: 'left',
        pack: 'center'
    },
    listeners: {
		beforeshow : function(component){
						Ext.getBody().mask(false, '<div class="demos-loading">Loading&hellip;</div>');
						POS.AJAXandDECODE({
							method: 'listarClientes'
							},
							function (datos){//mientras responda
								this.customers = datos.datos;
								ClientesListStore.loadData(this.customers); 							
							},
							function (){//no responde	
								POS.aviso("ERROR","NO SE PUDO CARGAR LA LISTA DE CLIENTES 	ERROR EN LA CONEXION :(");	
							}
						);//AJAXandDECODE
						Ext.getBody().unmask();
		}//fin before
	},
    items: [{
        width: '100%',
        height: '100%',
        xtype: 'list',
        store: ClientesListStore,
        tpl: '<tpl for="."><div class="contact"><strong>{nombre}</strong> {rfc}</div></tpl>',
        itemSelector: 'div.contact',
        singleSelect: true,
        grouped: true,
        indexBar: true,
		listeners: {
			selectionchange: function(){
				try{
			if (this.getSelectionCount() > 0) {
				var recor=this.getSelectedRecords();

				this.addDocked(updateForm = new Ext.form.FormPanel({
					scroll: 'vertical',
					id: 'updateForm',
					showAnimation: true,
					fullscreen: true,
					items: [{							
							xtype: 'fieldset',
							title: 'Cliente Info',
							instructions: 'Los campos que contienen * son obligatorios',
							items: [idClienteM = new Ext.form.HiddenField({
										id: 'idClienteM',
										value: recor[0].id_cliente
									}),
									nombreClienteM = new Ext.form.TextField({
										id: 'nombreClienteM',
										label: '*Nombre',
										value: recor[0].nombre
									}),
									rfcClienteM = new Ext.form.TextField({
										id: 'rfcClienteM',
										label: '*RFC',
										value: recor[0].rfc
									}),
									direccionClienteM = new Ext.form.TextField({
										id: 'direccionClienteM',
										label: 'Direccion',
										value: recor[0].direccion
										
									}),
									emailClienteM = new Ext.form.TextField({
										id: 'emailClienteM',
										label: 'E-mail',
										value: recor[0].e_mail
									}),
									telefonoClienteM = new Ext.form.TextField({
										id: 'telefonoClienteM',
										label: 'Telefono',
										value: recor[0].telefono
									}),
									limite_creditoClienteM = new Ext.form.NumberField({
										id: 'limite_creditoClienteM',
										label: '*Max Credito',
										value: recor[0].limite_credito
									})
							]//fin items form
							
						},
							{
								xtype: 'button',
								id: 'updateCliente',
								text: 'Modificar',
								maxWidth:150,
								handler: function(event,button) {
										//console.log("valor de nombre: "+nombreClienteM.getValue());			
									if( nombreClienteM.getValue() =='' || rfcClienteM.getValue() =='' || limite_creditoClienteM.getValue() ==''){
										POS.aviso("ERROR!!","LLENAR ALMENOS LOS CAMPOS CON  *");					
										}else{
											Ext.getBody().mask(false, '<div class="demos-loading">Loading&hellip;</div>');
											POS.AJAXandDECODE({
											method: 'actualizarCliente',
											id: idClienteM.getValue(),
											rfc: rfcClienteM.getValue(),
											nombre: nombreClienteM.getValue(),
											direccion: direccionClienteM.getValue(),
											telefono: telefonoClienteM.getValue(),
											e_mail: emailClienteM.getValue(),
											limite_credito: limite_creditoClienteM.getValue()
											},
											function (datos){//mientras responda
												if(datos.success == true){//
													POS.aviso("MODIFICACION","LOS DATOS DEL CLIENTE FUERON CAMBIADOS  :)");						
													POS.AJAXandDECODE({
														method: 'listarClientes'
														},
														function (datos){//mientras responda
															this.customers = datos.datos;
															ClientesListStore.loadData(this.customers); 
														},
														function (){//no responde	
															POS.aviso("ERROR!!","NO SE PUDO CARGAR LA LISTA DE CLIENTES ERROR EN LA CONEXION :(");	
														}
													);//AJAXandDECODE refrescar lista clientes
												}else{
														POS.aviso("ERROR!!","LOS DATOS DEL 	CLIENTE NO SE MODIFICARON :(");
												}
												rfcClienteM.setValue('');
												nombreClienteM.setValue('');
												direccionClienteM.setValue('');
												telefonoCliente.setValue('');
												emailClienteM.setValue('');
												limite_creditoClienteM.setValue('');
												
												this.updateForm.destroy();
												
											},
											function (){//no responde	
												POS.aviso("ERROR","NO SE PUDO MODIFICAR CLIENTE ERROR EN LA CONEXION :(");	
											}
										);//AJAXandDECODE actualizar cliente
										Ext.getBody().unmask();
									}//else de validar vacios
								}//fin handler
							}//fin boton updateCliente
							,
							{
								xtype: 'button',
								id: 'cancelUpdateCliente',
								text: 'Cancelar',
								maxWidth:150,
								handler: function(event,button) {
									Ext.getCmp('updateForm').destroy();
								}
							}//fin boton cancelar
						]//,//fin items formpanel
					}),'slide');
				}//if selectedCount
				}catch(e){ 
					if(DEBUG){
						console.log("BLOQUE TRY CATCH DE LISTENER SELECTIONCHANGE EN PANEL CLIENTES LIST ERROR ------------> "+e);
					}
				}
				//console.log(this.getSelectedRecords());
			}//,
			}//fin listener
    }
]
	
});

ApplicacionClientes.prototype.menu2 = new Ext.form.FormPanel({
    scroll: 'vertical',
	id:'menu2',
    items: [{
		
        xtype: 'fieldset',
        title: 'Cliente Info',
        instructions: 'Los campos que contienen * son obligatorios',
        items: [
				nombreCliente = new Ext.form.TextField({
					id: 'nombreCliente',
					label: '*Nombre'
				})
        ,
				rfcCliente = new Ext.form.TextField({
					id: 'rfcCliente',
					label: '*RFC'
				})
        ,
				direccionCliente = new Ext.form.TextField({
					id: 'direccionCliente',
					label: 'Direccion'
				})
        ,
				emailCliente = new Ext.form.TextField({
					id: 'emailCliente',
					label: 'E-mail'
				})
        ,
				telefonoCliente = new Ext.form.TextField({
					id: 'telefonoCliente',
					label: 'Telefono'
				})
        ,
        		limite_creditoCliente = new Ext.form.NumberField({
					id: 'limite_creditoCliente',
					label: '*Max Credito'
				})
		]//fin items form
		
    },
		{
            xtype: 'button',
            id: 'guardar',
            text: 'Guardar',
			maxWidth:100,
			handler: function(event,button) {
				if( nombreCliente.getValue() =='' || rfcCliente.getValue() =='' || limite_creditoCliente.getValue() ==''){
					POS.aviso("ERROR!!","LLENAR ALMENOS LOS CAMPOS CON  *");					
					}else{
						Ext.getBody().mask(false, '<div class="demos-loading">Loading&hellip;</div>');
						POS.AJAXandDECODE({
						method: 'insertarCliente',
						rfc: rfcCliente.getValue(),
						nombre: nombreCliente.getValue(),
						direccion: direccionCliente.getValue(),
						telefono: telefonoCliente.getValue(),
						e_mail: emailCliente.getValue(),
						limite_credito: limite_creditoCliente.getValue()
						},
						function (datos){//mientras responda
							POS.aviso("NUEVO CLIENTE","LOS DATOS DEL CLIENTE FUERON GUARDADOS CORRECTAMENTE  :)");
							rfcCliente.setValue('');
							nombreCliente.setValue('');
							direccionCliente.setValue('');
							telefonoCliente.setValue('');
							emailCliente.setValue('');
							limite_creditoCliente.setValue('');
						},
						function (){//no responde	
							POS.aviso("ERROR","NO SE PUDO INSERTAR CLIENTE, ERROR EN LA CONEXION :(");	
						}
					);//AJAXandDECODE insertar cliente
					Ext.getBody().unmask();
					}//else de validar vacios
				}//fin handler
	
        }//fin boton
	]//,//fin items formpanel
});

//autoinstalar esta applicacion
AppInstaller( new ApplicacionClientes() );
