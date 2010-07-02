ApplicacionClientes= function ()
{
        if(DEBUG){
                console.log("ApplicacionClientes: construyendo");
        }
        ApplicacionClientes.currentInstance = this;     

        this._init();

        return this;
};




/*
	Class fields
*/
//aqui va el panel principal 
ApplicacionClientes.prototype.mainCard = null;

//aqui va el nombre de la applicacion
ApplicacionClientes.prototype.appName = null;

//aqui va el nombre de la applicacion
ApplicacionClientes.prototype.leftMenuItems = null;

//aqui va un texto de ayuda en html
ApplicacionClientes.prototype.ayuda = null;

ApplicacionClientes.prototype.ClientesListStore = null;

ApplicacionClientes.prototype.customers = null;

ApplicacionClientes.prototype.updateForm= null;

ApplicacionClientes.prototype.dockedItems = null;

ApplicacionClientes.prototype.dockedItemsFormCliente = null;



ApplicacionClientes.prototype._init = function()
{
        //nombre de la aplicacion
        this.appName = "Clientes";
        
        //ayuda sobre esta applicacion
        this.ayuda = "Ayuda sobre este modulo de prueba <br>, html es valido <br> :D";
        
        //initialize the tootlbar which is a dock
        this._initToolBar();
        
        this.mainCard = this.ClientesList
      
};




ApplicacionClientes.prototype._initToolBar = function (){

	/*
		Buscar cliente
	*/
    var btnagregarCliente = [{
        id: 'btn_agregarCliente',
        text: 'Nuevo Cliente',
        handler: this.addnewClientPanel,
        ui: 'action'
    }];

	var detallesDeBusqueda = [{
        xtype: 'splitbutton',
        items: [{
            text: 'Nombre'
        }, {
            text: 'RFC'
        },{
			text: 'Direccion'
		}]    
    }];

    var campoBusqueda = [{
		xtype: 'textfield',
		inputCls : 'caja-buscar'
    }];




	/*
		Detalles cliente
	*/
	var btnBackCliente = [{
        id: 'btn_BackCliente',
        text: 'Regresar',
        handler: function(){
			sink.Main.ui.setCard( Ext.getCmp('panelClientes'), 'slide' );
		},
        ui: 'back'
    }];


	var detallesDeCliente = [{
        xtype: 'splitbutton',
        items: [{
            text: 'Detalles'
        }, {
            text: 'Compras'
        },{
			text: 'Creditos'
		}]    
    }];

	var btnEditCliente = [{
		id: 'btn_EditCliente',
        text: 'Modificar',
        handler: this.editClient,
        ui: 'action'
    }];

	
   if (!Ext.platform.isPhone) {
        /*
			Buscar cliente
		*/
        this.dockedItems = [ new Ext.Toolbar({
            ui: 'light',
            dock: 'top',
            items: [campoBusqueda,{xtype: 'spacer'},detallesDeBusqueda,{xtype: 'spacer'}, btnagregarCliente]
        })];
		
		
		/*
			Detalles cliente
		*/
		btnBackCliente.push({xtype: 'spacer'});
		detallesDeCliente.push({xtype: 'spacer'});
		
		this.dockedItemsFormCliente =[ new Ext.Toolbar({
            ui: 'light',
            dock: 'top',
            items:  btnBackCliente.concat(detallesDeCliente).concat(btnEditCliente)
                 
        })];
		
    }else {
        this.dockedItems = [{
            xtype: 'toolbar',
            ui: 'metal',
            items: btnagregarCliente,
            dock: 'bottom'
        }];
		
		this.dockedItemsFormCliente = [{
            xtype: 'toolbar',
            ui: 'metal',
            items: btnBackCliente.concat(detallesDeCliente).concat(btnEditCliente),
            dock: 'top'
        }];
    }
    
    
	//agregar este dock a el panel principal
	this.ClientesList.addDocked( this.dockedItems );
		
		
};














/*	------------------------------------------------------------------------------------------
		Detalles de Cliente
------------------------------------------------------------------------------------------*/
ApplicacionClientes.prototype.editClient = function (){
	
	switch(Ext.getCmp('btn_EditCliente').getText()){
		case 'Modificar': 
			//disable form items
			Ext.getCmp('btn_EditCliente').setText("Guardar");
			Ext.getCmp('nombreClienteM').setDisabled(false);	
			Ext.getCmp('direccionClienteM').setDisabled(false);
			Ext.getCmp('rfcClienteM').setDisabled(false);	
			Ext.getCmp('emailClienteM').setDisabled(false);
			Ext.getCmp('telefonoClienteM').setDisabled(false);	
			Ext.getCmp('limite_creditoClienteM').setDisabled(false);
			
			//add cancel button
			//Ext.getCmp("updateForm").dockedItems
			
			break;
		case 'Guardar': 
			//enable form items
			Ext.getCmp('btn_EditCliente').setText("Modificar");
			Ext.getCmp('nombreClienteM').setDisabled(true);	
			Ext.getCmp('direccionClienteM').setDisabled(true);
			Ext.getCmp('rfcClienteM').setDisabled(true);	
			Ext.getCmp('emailClienteM').setDisabled(true);
			Ext.getCmp('telefonoClienteM').setDisabled(true);	
			Ext.getCmp('limite_creditoClienteM').setDisabled(true);
			//call handlerModificarCliente
			break;
	}

}

/*
	Carga el panel de 'Detalles del cliente'
*/
ApplicacionClientes.prototype.addClientDetailsPanel= function( recor ){
	
	var foo = ApplicacionClientes.currentInstance.updateForm( recor );
	
	Ext.getCmp('btn_EditCliente').setText('Modificar');
	
	sink.Main.ui.setCard( foo, 'slide' );
}



/*
	Application Logic for modifying clients
*/
ApplicacionClientes.prototype.handlerModificarCliente= function(id,rfc,nombre,direccion,telefono,email,limite_credito){
	//Ext.getCmp('nombreClienteM').setDisabled(false);
	
	if( nombre =='' || rfc =='' || limite_credito ==''){
    	POS.aviso("ERROR!!","LLENAR ALMENOS LOS CAMPOS CON  *");                                        
	}else{
		Ext.getBody().mask(false, '<div class="demos-loading">Loading&hellip;</div>');
        POS.AJAXandDECODE({
			method: 'actualizarCliente',
			id: id,
			rfc: rfc,
			nombre: nombre,
			direccion: direccion,
			telefono: telefono,
			e_mail: email,
			limite_credito: limite_credito
        },
        function (datos){//mientras responda
        	if(datos.success == true){//
            	POS.aviso("MODIFICACION","LOS DATOS DEL CLIENTE FUERON CAMBIADOS  :)");                                         
            	POS.AJAXandDECODE({
            		method: 'listarClientes'
            	},
            	function (datos){//mientras responda
            		ClientesListStore.loadData(datos.datos); 
            	},
				function (){//no responde       
                	POS.aviso("ERROR!!","NO SE PUDO CARGAR LA LISTA DE CLIENTES ERROR EN LA CONEXION :(");  
                }
                );//AJAXandDECODE refrescar lista clientes
            }else{
                  POS.aviso("ERROR!!","LOS DATOS DEL      CLIENTE NO SE MODIFICARON :(");
                  }
            //Ext.getCmp('updateForm').destroy();
            //sink.Main.ui.setCard( Ext.getCmp('panelClientes'), 'slide' );
            },
        function (){//no responde  AJAXanDECODE actualizar
        	POS.aviso("ERROR","NO SE PUDO MODIFICAR CLIENTE ERROR EN LA CONEXION :(");      
        }
        );//AJAXandDECODE actualizar cliente
            Ext.getBody().unmask();
	}//else de validar vacios
};




ApplicacionClientes.prototype.updateForm = function( recor ){

	 var forma =  new Ext.form.FormPanel({
						scroll: 'vertical',
                        id: 'updateForm',
						dockedItems: this.dockedItemsFormCliente,
                        items: [{                                                       
                        		xtype: 'fieldset',
                                title: 'Detalles del Cliente',
								
                                instructions: 'Los campos que contienen * son obligatorios',
                                items: [idClienteM = new Ext.form.HiddenField({
                                			id: 'idClienteM',
                                        	value: recor[0].id_cliente
                                        }),
                                        nombreClienteM = new Ext.form.TextField({
                                        	id: 'nombreClienteM',
                                        	label: 'Nombre',
											required: true,
                                        	value: recor[0].nombre,
											disabled: true
										}),
                                        rfcClienteM = new Ext.form.TextField({
                                        	id: 'rfcClienteM',
                                        	label: 'RFC',
											required: true,
                                        	value: recor[0].rfc,
											disabled: true
                                        }),
                                        direccionClienteM = new Ext.form.TextField({
                                        	id: 'direccionClienteM',
                                        	label: 'Direccion',
                                        	value: recor[0].direccion,
											disabled: true
                                        }),
                                        emailClienteM = new Ext.form.TextField({
                                        	id: 'emailClienteM',
                                        	label: 'E-mail',
                                        	value: recor[0].e_mail,
											disabled: true
                                        }),
                                        telefonoClienteM = new Ext.form.TextField({
                                        	id: 'telefonoClienteM',
                                        	label: 'Telefono',
                                        	value: recor[0].telefono,
											disabled: true
                                        }),
                                        limite_creditoClienteM = new Ext.form.NumberField({
                                        	id: 'limite_creditoClienteM',
                                        	label: 'Max Credito',
											required: true,
                                        	value: recor[0].limite_credito,
											disabled: true
                                        })
                                ]//fin items form
                                }/*,{
                                		xtype: 'button',
                                    	id: 'updateCliente',
                                    	text: 'Modificar',
                                    	maxWidth:150,
                                    	handler: function(event,button) {
			                              		ApplicacionClientes.currentInstance.handlerModificarCliente(idClienteM.getValue(),rfcClienteM.getValue(),nombreClienteM.getValue(),direccionClienteM.getValue(),telefonoClienteM.getValue(),emailClienteM.getValue(),limite_creditoClienteM.getValue());        
                                    	}//fin handler
								}//fin boton updateCliente
								,
                                {
                                	xtype: 'button',
                                    id: 'cancelUpdateCliente',
                                    text: 'Cancelar',
                                    maxWidth:150,
                                    handler: function(event,button) {
                                    	sink.Main.ui.setCard( Ext.getCmp('panelClientes'), 'slide' );
                                    }
                               	}//fin boton cancelar*/
                        ]//,//fin items formpanel
                     });
	return forma;

};

/*	------------------------------------------------------------------------------------------
		Credito de Cliente
------------------------------------------------------------------------------------------*/







/*	------------------------------------------------------------------------------------------
		Buscar Clientes
------------------------------------------------------------------------------------------*/

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
				                ClientesListStore.loadData(datos.datos);                                                     
				        },
				        function (){//no responde       
				                POS.aviso("ERROR","NO SE PUDO CARGAR LA LISTA DE CLIENTES       ERROR EN LA CONEXION :(");      
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
							//DESLIZAR EL NUEVO PANEL (FORMULARIO DE ACTUALIZACION)
                        	ApplicacionClientes.currentInstance.addClientDetailsPanel( recor ); 
						}
					}catch(e){
						if(DEBUG){
							console.error("ApplicationClientes:" +e);
						}
					}
				}
			}//fin listener
    	}]

});










/*	------------------------------------------------------------------------------------------
		Nuevo Cliente
------------------------------------------------------------------------------------------*/

ApplicacionClientes.prototype.addnewClientPanel= function(){
	
	var tonto = ApplicacionClientes.currentInstance.formAgregarCliente;
	
	sink.Main.ui.setCard( tonto, 'slide' );
}

ApplicacionClientes.prototype.formAgregarCliente = new Ext.form.FormPanel({
    	scroll: 'vertical',
        id:'formAgregarCliente',
    	items: [{
        	xtype: 'fieldset',
        	title: 'Cliente Info',
        	instructions: 'Los campos que contienen * son obligatorios',
        	items: [
            		nombreCliente = new Ext.form.TextField({
                    	id: 'nombreCliente',
                        label: '*Nombre'
                    }),
					rfcCliente = new Ext.form.TextField({
                    	id: 'rfcCliente',
                        label: '*RFC'
                   	}),
                    direccionCliente = new Ext.form.TextField({
                    	id: 'direccionCliente',
                        label: 'Direccion'
                    }),
                    emailCliente = new Ext.form.TextField({
                    	id: 'emailCliente',
                        label: 'E-mail'
                    }),
                    telefonoCliente = new Ext.form.TextField({
                    	id: 'telefonoCliente',
                        label: 'Telefono'
                    }),
                    limite_creditoCliente = new Ext.form.NumberField({
                    	id: 'limite_creditoCliente',
                        label: '*Max Credito'
                    })
                ]//fin items form
                },
                {
            	xtype: 'button',
            		id: 'guardarCliente',
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
                        		if(datos.success == true){//
                            		POS.aviso("NUEVO CLIENTE","LOS DATOS DEL CLIENTE FUERON GUARDADOS CORRECTAMENTE :)");
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
                                	POS.aviso("ERROR!!","LOS DATOS DEL      CLIENTE NO SE MODIFICARON :(");
                                }
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
                                sink.Main.ui.setCard( Ext.getCmp('panelClientes'), 'slide' );
                                //Ext.getCmp('btn_agregarCliente').setVisible(true);
                        }//else de validar vacios
                                        
                                        
                    }//fin handler
        
        		}//fin boton
                ,
                {
                xtype: 'button',
                	id: 'cancelarGuardarCliente',
                    text: 'Regresar',
                    maxWidth:100,
                    handler: function(event,button) {
                    	sink.Main.ui.setCard( Ext.getCmp('panelClientes'), 'slide' );
                        Ext.getCmp('btn_agregarCliente').setVisible(true);
                                
                   	}//fin handler cancelar cliente
                        
                }//fin boton cancelar
        ]//,//fin items formpanel
});//fin formPanel



//autoinstalar esta applicacion
AppInstaller( new ApplicacionClientes() );