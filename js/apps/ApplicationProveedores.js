ApplicationProveedores= function ()
{
	if(DEBUG){
		console.log("ApplicationProveedores: construyendo");
	}
	ApplicationProveedores.currentInstance = this;	
	//ApplicationProveedores.prototype.currentInstance=this;
	this._init();

	return this;
	
};

//variables de esta clase, NO USEN VARIABLES ESTATICAS A MENOS QUE SEA 100% NECESARIO

//aqui va el panel principal 
ApplicationProveedores.prototype.mainCard = null;

ApplicationProveedores.currentInstance=87;

//aqui va el nombre de la applicacion
ApplicationProveedores.prototype.appName = null;

//aqui va el nombre de la applicacion
ApplicationProveedores.prototype.leftMenuItems = null;

//aqui va un texto de ayuda en html
ApplicationProveedores.prototype.ayuda = null;

ApplicationProveedores.prototype.ProveedoresListStore = null;

ApplicationProveedores.prototype.providers = null;

ApplicationProveedores.prototype.actualizaProveedor=null;

ApplicationProveedores.prototype.updateProviderForm= null;

ApplicationProveedores.prototype.record = null;


ApplicationProveedores.prototype._init = function()
{
	//iniciar variables
	//nombre de la aplicacion
	this.appName = "Proveedores";
	//ayuda sobre esta applicacion
	this.ayuda = "Ayuda sobre este modulo de prueba <br>, html es valido <br> :D";
	//submenues en el panel de la izquierda
	this.leftMenuItems = [
	{
        text: 'Ver Proveedores',
        ayuda: 'SE ENLISTAN TODOS LOS PROVEEDORES REGISTRADOS EN PAPAS SUPREMAS',
		card: this.ProveedoresList
    },
    {
        text: 'Agregar Proveedor',
       	card: this.menu2,
        ayuda: 'MODULO QUE AGREGA PROVEEDORES'
    }
	];
	
	//panel principal
	this.mainCard = new Ext.Panel({
		cls: 'card card1',
		html: 'MODULO DE PROVEEDORES'
	});	
};//fin CONSTRUCTOR


Ext.regModel('Proveedor', {
    fields: ['nombre', 'rfc']
});


ProveedoresListStore = new Ext.data.Store({
    model: 'Proveedor',
    sorters: 'nombre',
    getGroupString : function(record) {
        return record.get('nombre')[0];
    }	
});

ApplicationProveedores.prototype.ProveedoresList = new Ext.Panel({
	id: 'panelProveedores',
	layout: Ext.platform.isPhone ? 'fit' : {
        type: 'vbox',
        align: 'left',
        pack: 'center'
    },	
    listeners: {
		beforeshow : function(component){
						Ext.getBody().mask(false, '<div class="demos-loading">Loading&hellip;</div>');
						POS.AJAXandDECODE({
							method: 'listarProveedores'
							},
							function (datos){//mientras responda
								this.providers = datos.datos;
								ProveedoresListStore.loadData(this.providers);							
							},
							function (){//no responde
								POS.aviso("ERROR","NO SE PUDO CARGAR LA LISTA DE PROVEEDORES  ERROR EN LA CONEXION :(");	
							}
						);//AJAXandDECODE listarProveedores
						Ext.getBody().unmask();
		}//fin beforeshow 
	},
    items: [{
        width: '100%',
        height: '100%',
        xtype: 'list',
        store: ProveedoresListStore,
        tpl: '<tpl for="."><div class="proveedor"><strong>{nombre}</strong> {rfc}</div></tpl>',
        itemSelector: 'div.proveedor',
        singleSelect: true,
        grouped: true,
        indexBar: true,
		listeners: {
			selectionchange: function(){
			try{
			if (this.getSelectionCount() > 0) {
				this.record=this.getSelectedRecords();
				
				this.addDocked(updateProviderForm = new Ext.form.FormPanel({
					scroll: 'vertical',
					id: 'updateProviderForm',
					showAnimation: true,
					fullscreen: true,
					items: [{
							xtype: 'fieldset',
							title: 'Proveedor Info',
							instructions: 'Los campos que contienen * son obligatorios',
							items: [
									idProveedorM = new Ext.form.HiddenField({
										id: 'idProveedorM',
										value: this.record[0].id_proveedor
									}),
									nombreProveedorM = new Ext.form.TextField({
										id: 'nombreProveedorM',
										label: '*Nombre',
										value: this.record[0].nombre
									}),
									rfcProveedorM = new Ext.form.TextField({
										id: 'rfcProveedorM',
										label: '*RFC',
										value: this.record[0].rfc
									}),
									direccionProveedorM = new Ext.form.TextField({
										id: 'direccionProveedorM',
										label: '*Direccion',
										value: this.record[0].direccion										
									}),
									emailProveedorM = new Ext.form.TextField({
										id: 'emailProveedorM',
										label: 'E-mail',
										value: this.record[0].e_mail
									}),
									telefonoProveedorM = new Ext.form.TextField({
										id: 'telefonoProveedorM',
										label: 'Telefono',
										value: this.record[0].telefono
									})
							]//fin items formPanel
							
						},
							{
								xtype: 'button',
								id: 'updateProveedor',
								text: 'Modificar',
								maxWidth:150,
								handler: function(event,button) {
										if( nombreProveedorM.getValue() =='' || rfcProveedorM.getValue() =='' || direccionProveedorM.getValue() ==''){
										POS.aviso("ERROR!!","LLENAR ALMENOS LOS CAMPOS CON  *");					
										}else{
											Ext.getBody().mask(false, '<div class="demos-loading">Loading&hellip;</div>');
											POS.AJAXandDECODE({
											method: 'actualizarProveedor',
											idP: idProveedorM.getValue(),
											rfcP: rfcProveedorM.getValue(),
											nombreP: nombreProveedorM.getValue(),
											direccionP: direccionProveedorM.getValue(),
											telefonoP: telefonoProveedorM.getValue(),
											e_mailP: emailProveedorM.getValue()
											},
											function (datos){//mientras responda
												if(datos.success == true){//
													POS.aviso("MODIFICACION","LOS DATOS DEL PROVEEDOR FUERON CAMBIADOS  :)");						

													POS.AJAXandDECODE({
														method: 'listarProveedores'
														},
														function (datos){//mientras responda
															this.providers = datos.datos;
															ProveedoresListStore.loadData(this.providers); 
														},
														function (){//no responde	
															POS.aviso("ERROR!!","NO SE PUDO CARGAR LA LISTA DE PROVEEDORES ERROR EN LA CONEXION :(");	
														}
													);//AJAXandDECODE refrescar lista proveedores
												}else{
													POS.aviso("ERROR!!","LOS DATOS DEL PROVEEDOR NO SE MODIFICARON :(");
												}
												rfcProveedorM.setValue('');
												nombreProveedorM.setValue('');
												direccionProveedorM.setValue('');
												telefonoProveedor.setValue('');
												emailProveedorM.setValue('');
												
												this.updateProviderForm.destroy();
												
											},
											function (){//no responde	
												POS.aviso("ERROR!!","NO SE PUDO MODIFICAR PROVEEDOR ERROR EN LA CONEXION :(");	
											}
										);//AJAXandDECODE actualizar
										Ext.getBody().unmask();
									}//else de validar vacios
								}//fin handler boton modificar
							}//fin boton updateProveedor
							,
							{
								xtype: 'button',
								id: 'cancelUpdateProveedor',
								text: 'Cancelar',
								maxWidth:150,
								handler: function(event,button) {
									Ext.getCmp('updateProviderForm').destroy();
								}
							}//fin boton Cancelar							
						]//,//fin items formpanel
					}),'flip');
				}//if selectedCount
				}catch(e){
					if(DEBUG){
						console.log("BLOQUE TRY CATCH DE LISTENER SELECTIONCHANGE EN PANEL PROVEEDORES LIST ERROR ------------> "+e);
					}
				}
			}//,
			}//fin listener selection change
    }
]//fin items proveedoresList panel
	
});



ApplicationProveedores.prototype.menu2 = new Ext.form.FormPanel({
    scroll: 'vertical',
	id:'menu2',
    items: [{
		
        xtype: 'fieldset',
        title: 'Proveedor Info',
        instructions: 'Los campos que contienen * son obligatorios',
        items: [
				nombreProveedor = new Ext.form.TextField({
					id: 'nombreProveedor',
					label: '*Nombre'
				})
        ,
				rfcProveedor = new Ext.form.TextField({
					id: 'rfcProveedor',
					label: '*RFC'
				})
        ,
				direccionProveedor = new Ext.form.TextField({
					id: 'direccionProveedor',
					label: '*Direccion'
				})
        ,
				emailProveedor = new Ext.form.TextField({
					id: 'emailProveedor',
					label: 'E-mail'
				})
        ,
				telefonoProveedor = new Ext.form.TextField({
					id: 'telefonoProveedor',
					label: 'Telefono'
				})
       
		]//fin items form
		
    },
		{
            xtype: 'button',
            id: 'guardar',
            text: 'Guardar',
			maxWidth:100,
			handler: function(event,button) {
					
				if( nombreProveedor.getValue() =='' || rfcProveedor.getValue() =='' || direccionProveedor.getValue() ==''){
					Ext.getBody().mask(false, '<div class="demos-loading">Loading&hellip;</div>');
					POS.aviso("ERROR!!","LLENAR ALMENOS LOS CAMPOS CON  *");					
					}else{
						POS.AJAXandDECODE({
						method: 'insertarProveedor',
						rfcP: rfcProveedor.getValue(),
						nombreP: nombreProveedor.getValue(),
						direccionP: direccionProveedor.getValue(),
						telefonoP: telefonoProveedor.getValue(),
						e_mailP: emailProveedor.getValue()
						},
						function (datos){//mientras responda
							POS.aviso("NUEVO PROVEEDOR","LOS DATOS DEL PROVEEDOR FUERON GUARDADOS CORRECTAMENTE  :)");
							rfcProveedor.setValue('');
							nombreProveedor.setValue('');
							direccionProveedor.setValue('');
							telefonoProveedor.setValue('');
							emailProveedor.setValue('');
						},
						function (){//no responde	
							POS.aviso("ERROR","NO SE PUDO INSERTAR PROVEEDOR, ERROR EN LA CONEXION :(");	
						}
					);//AJAXandDECODE insertar proveedor
					Ext.getBody().unmask();
					}//else de validar vacios
				}//fin handler	
        }//fin boton
	]//,//fin items formpanel
});//fin menu2

//autoinstalar esta applicacion
AppInstaller( new ApplicationProveedores() );