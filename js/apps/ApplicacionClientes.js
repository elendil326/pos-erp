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

ApplicacionClientes.prototype.clienteSeleccionado = null;


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
            text: 'Nombre',
			handler: this.filterByName
        }, {
            text: 'RFC',
			handler: this.filterByRfc
        },{
			text: 'Direccion',
			handler: this.filterByDireccion
		}]    
    }];

    var campoBusqueda = new Ext.form.TextField({
		//xtype: 'textfield',
		inputCls: 'cliente-buscar',
		id: 'btnBuscarCliente'
		
    });

	campoBusqueda.on('keyup', function() {
      ApplicacionClientes.currentInstance.doSearch();
		
    });


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
		id: 'btn_detallesCliente',
        items: [{
            text: 'Detalles',
			handler: this.mostrarDetallesCliente
        }, {
            text: 'Compras',
			id: 'btnComprasCliente',
			handler: this.mostrarVentasCliente
        },{
			text: 'Creditos',
			handler: this.mostrarVentasCreditoCliente
		}]    
    }];

	var btnEditCliente = [{
		id: 'btn_EditCliente',
        text: 'Modificar',
        handler: this.editClient,
        ui: 'action'
    }];
	
	var btnCancelEditCliente = [{
		id: 'btn_CancelEditCliente',
		text: 'Cancelar',
		//handler: 
		//hidden: true,
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
            items:  btnBackCliente.concat(detallesDeCliente).concat(btnCancelEditCliente).concat(btnEditCliente)
                 
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
            items: btnBackCliente.concat(detallesDeCliente).concat(btnCancelEditCliente).concat(btnEditCliente),
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
			
			Ext.getCmp('btn_CancelEditCliente').setVisible(true);
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
			
			Ext.getCmp('btn_CancelEditCliente').setVisible(false);
			//call handlerModificarCliente
			ApplicacionClientes.currentInstance.handlerModificarCliente(idClienteM.getValue(),rfcClienteM.getValue(),nombreClienteM.getValue(),direccionClienteM.getValue(),telefonoClienteM.getValue(),emailClienteM.getValue(),limite_creditoClienteM.getValue());    
			break;
	}

}

/*
	Carga el panel de 'Detalles del cliente'
*/
ApplicacionClientes.prototype.mostrarDetallesCliente = function(){
	ApplicacionClientes.prototype.addClientDetailsPanel(ApplicacionClientes.currentInstance.clienteSeleccionado);
}

ApplicacionClientes.prototype.addClientDetailsPanel= function( recor ){
	
	var foo = ApplicacionClientes.currentInstance.updateForm( recor );
	
	Ext.getCmp('btn_CancelEditCliente').setVisible(false);
	Ext.getCmp('btn_EditCliente').setText('Modificar');
	//Ext.getCmp('btn_detallesCliente').setActive();
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
		Buscar Clientes
------------------------------------------------------------------------------------------*/

Ext.regModel('Contact', {
    fields: ['nombre', 'rfc']
});
//create regmodel para busqueda por nombre
			Ext.regModel('ApplicacionClientes_nombre', {
		                    fields: [ 'nombre']
		    });
		
			//create regmodel para busqueda por rfc
			Ext.regModel('ApplicacionClientes_rfc', {
		                    fields: [ 'rfc', 'nombre','direccion']
		    });
		
			//create regmodel para busqueda por direccion
			Ext.regModel('ApplicacionClientes_direccion', {
		                    fields: [ 'direccion' ]
		    });

var apClientes_filtro = 'nombre';

ApplicacionClientes.prototype.filterByName = function(){
	apClientes_filtro = "nombre";
	Ext.getCmp("listaClientes").refresh();
}
ApplicacionClientes.prototype.filterByRfc = function(){
	apClientes_filtro = "rfc";
	Ext.getCmp("listaClientes").refresh();
}
ApplicacionClientes.prototype.filterByDireccion = function(){
	apClientes_filtro = "direccion";
	Ext.getCmp("listaClientes").refresh();
}

ClientesListStore = new Ext.data.Store({
    model: 'ApplicacionClientes_'+apClientes_filtro,
    sorters: apClientes_filtro,
			
    getGroupString : function(record) {
        return record.get(apClientes_filtro)[0];
    }   
});


/*ApplicacionClientes.prototype.filter = function ()
{
	console.log("requesting template-...");
	switch(ApplicationVender.currentInstance.filterTemplate){
		case 'nombre': 		return '<tpl for="."><div class="contact"><strong>{nombre}</strong>{rfc},{direccion}</div></tpl>';
		case 'rfc': 		return '<tpl for="."><div class="contact"><strong>{rfc}</strong> {nombre}</div></tpl>';		
		case 'direccion': 	return '<tpl for="."><div class="contact">{direccion}<strong> {nombre}</strong></div></tpl>';
	}
};*/
/*	------------------------------------------------------------------------------------
		Filtrado de busqueda en el store HAY BUG AQUI, DESPUES DE 1 FILTRO CUANDO SE REGRESA A CLIENTES NO LOS MUESTRA TODOS A MENOS QUE SE TECLE EN EL TEXT DE BUSQUEDA ALGO O SE DEJE EN ''
---------------------------------------------------------------------------------------*/
ApplicacionClientes.prototype.doSearch = function(  ){
	
	if (Ext.getCmp('btnBuscarCliente').getValue() == "")
		{			
			ClientesListStore.clearFilter();
			//Ext.getCmp("listaClientes").refresh();
			ClientesListStore.sync();
			
		}
		
		ClientesListStore.filter([
		{
			property: 'nombre',
			value: Ext.getCmp('btnBuscarCliente').getValue()
		}
		]);
		
}



ApplicacionClientes.prototype.ClientesList = new Ext.Panel({
        id: 'panelClientes',
    	layout: Ext.platform.isPhone ? 'fit' : {
        	type: 'vbox',
        	align: 'left',
        	pack: 'center'
    	},
    	listeners: {
			beforeshow : function(component){
				Ext.getCmp('btnBuscarCliente').setValue() == "";
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
			id: 'listaClientes',
        	tpl: '<tpl for="."><div class="contact"><strong>{nombre}</strong>--<i>{rfc}</i>--{direccion}</div></tpl>',
        	itemSelector: 'div.contact',
        	singleSelect: true,
        	grouped: true,
        	indexBar: true,
            listeners: {
				selectionchange: function(){

					try{
						if (this.getSelectionCount() > 0) {
							var recor=this.getSelectedRecords();
							
							//console.log("ApplicacionClientes.currentInstance.clienteSeleccionado = "+ recor.id_cliente);
							//console.log(recor[0]);
							ApplicacionClientes.currentInstance.clienteSeleccionado = recor;
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
		AGREGAR Nuevo Cliente
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
                                        		POS.aviso("ERROR!","NO SE PUDO CARGAR LA LISTA DE CLIENTES ERROR EN LA CONEXION :(");  
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



/*------------------------------------------------------------------
			VENTAS EMITIDAS A UN CLIENTE
------------------------------------------------------------------------*/

ApplicacionClientes.prototype.mostrarVentasCliente = function(){ //mostrarVentasCreditoCliente
	console.log("a entrar a listar ventas");
	console.log("ID cliente: "+ApplicacionClientes.currentInstance.clienteSeleccionado);
	var dump = ApplicacionClientes.currentInstance.listarVentas( ApplicacionClientes.currentInstance.clienteSeleccionado );
	
	sink.Main.ui.setCard( dump , 'slide' );
}


ApplicacionClientes.prototype.listarVentas = function ( record_cliente ){
	
	var listaVentas = new Ext.form.FormPanel({
			scroll: 'vertical',
			id: 'listaVentasCliente',
			dockedItems: this.dockedItemsFormCliente,
			items: [{
				  		id: 'datosCliente',
						html: ''
					},
					{
						id: 'ventasCliente',
						html:''
					}
					]
		});
	
	
	Ext.regModel('ventasStore', {
    	fields: ['nombre', 'rfc']
	});

	var ventasCliente = new Ext.data.Store({
    	model: 'ventasStore'/*,
    	sorters: 'nombre',
    	getGroupString : function(record) {
        	return record.get('nombre')[0];
    	} */  
	});	
	
	
	//cabecera de datos del cliente seleccionado
	
	var clienteHtml = "";
	clienteHtml += " <div class='ApplicationClientes-clienteBox'> ";
	clienteHtml += " <div class='nombre'>" + record_cliente[0].nombre + "</div>";
	clienteHtml += " <div class='ApplicationClientes-clienteDetalle'> RFC: " + record_cliente[0].rfc + "</div>";
	clienteHtml += " <div class='ApplicationClientes-clienteDetalle'> Direccion: " + record_cliente[0].direccion + "</div>";
	clienteHtml += " <div class='ApplicationClientes-clienteDetalle'> Telefono: " + record_cliente[0].telefono + "</div>";
	clienteHtml += " <div class='ApplicationClientes-clienteDetalle'> Correo Electronico: " + record_cliente[0].e_mail + "</div>";
	clienteHtml += " <div class='ApplicationClientes-clienteDetalle'> Limite de Credito: $ " + record_cliente[0].limite_credito + "</div>";
	clienteHtml += " </div> ";
	
		POS.AJAXandDECODE({
			method: 'listarVentasCliente',
			id_cliente: record_cliente[0].id_cliente //recor[0].id_cliente
			},
			function (datos){//mientras responda AJAXDECODE LISTAR VENTAS CLIENTE
				if(datos.success === true){
					ventasCliente.loadData(datos.datos);
					
					Ext.get("datosCliente").update( clienteHtml );
					var html = "";
					html += "<div class='ApplicationClientes-item' >"
							+ "<div class='trash' ></div>"
							+ "<div class='id'>No. Venta</div>" 
							+ "<div class='tipo'>Tipo Venta</div>" 
							+ "<div class='fecha'>Fecha</div>" 
							+ "<div class='sucursal'>Sucursal</div>"
							+ "<div class='vendedor'>Vendedor</div>"
							+ "<div class='subtotal'>Subtotal</div>"
							+ "<div class='iva'>IVA</div>"
							+ "<div class='total'>TOTAL</div>"
							+ "</div>";
					
					//renderear el html
					for( a = 0; a < ventasCliente.getCount(); a++ ){
						var tipo="";
						if (ventasCliente.data.items[a].tipo_venta == "2"){
							tipo="Credito";
						}else{
							tipo ="Contado";
						}
						html += "<div class='ApplicationClientes-item' >" 
						+ "<div class='trash' onclick='ApplicacionClientes.currentInstance.verVenta(" +ventasCliente.data.items[a].id_venta+ ")'><img height=20 width=20 src='sencha/resources/img/toolbaricons/search.png'></div>"	
							+ "<div class='id'>" + ventasCliente.data.items[a].id_venta +"</div>" 
							+ "<div class='tipo'>" + tipo +"</div>" 
							+ "<div class='fecha'>"+ ventasCliente.data.items[a].fecha +"</div>" 
							+ "<div class='sucursal'>"+ ventasCliente.data.items[a].descripcion +"</div>"
							+ "<div class='vendedor'>"+ ventasCliente.data.items[a].nombre +"</div>"
							+ "<div class='subtotal'>$"+ ventasCliente.data.items[a].subtotal +"</div>"
							+ "<div class='iva'>$"+ ventasCliente.data.items[a].iva +"</div>"
							+ "<div class='total'>$"+ ventasCliente.data.items[a].total +"</div>"
							+ "</div>";
					}
								
					//imprimir el html
					Ext.get("ventasCliente").update("<div class='ApplicationClientes-itemsBox'>" + html +"</div>");
					//console.log(ventasCliente.data.items);
				}
				if(datos.success == false){
					//POS.aviso("ERROR","NO SE PUDO CARGAR LA LISTA DE VENTAS PROBABLEMENTE ESTE CLIENTE 'NO' HA COMPRADO");
					Ext.get("datosCliente").update( clienteHtml );
					Ext.get("ventasCliente").update("<div class='ApplicationClientes-itemsBox'><div class='noVentas' align='center'>ESTE CLIENTE NO TIENE LISTA DE VENTAS</div> </div>");
				}
			},
			function (){//no responde AJAXDECODE DE VENTAS CLIENTE
				POS.aviso("ERROR","NO SE PUDO CARGAR LA LISTA DE VENTAS   ERROR EN LA CONEXION :(");      
			}
		);//AJAXandDECODE LISTAR VENTAS CLIENTE
				
		
	return listaVentas;
}

/*--------------------------------------------------------------------
		VER DETALLES DE LA VENTA DEL CLIENTE
----------------------------------------------------------------------*/
ApplicacionClientes.prototype.verVenta = function( idVenta ){

	 var formBase = new Ext.Panel({
		id: 'detalleVentaPanel',
		 scroll: 'vertical',
			//	items
            items: [{
				id: 'detalleVentaCliente',
		        html: ''
		    }], 
			//	dock		
            dockedItems: [{
                    xtype: 'toolbar',
                    dock: 'bottom',
                    items: [{
						xtype: 'spacer'
						},{
						//-------------------------------------------------------------------------------
						//			cancelar
						//-------------------------------------------------------------------------------
						text: 'X Cerrar',
						handler: function() {
							//regresar el boton de cliente comun a 1
							Ext.getCmp("detalleVentaPanel").destroy();
							 Ext.getBody().unmask();
							//ocultar este form
							//form.hide();							
                            }
						}]
					}]
			});
	
       
	   if (Ext.platform.isAndroidOS) {
            formBase.items.unshift({
                xtype: 'component',
                styleHtmlContent: true,
                html: '<span style="color: red">Forms on Android are currently under development. We are working hard to improve this in upcoming releases.</span>'
            });
        }

	if (Ext.platform.isPhone) {
            formBase.fullscreen = true;
        } else {
            Ext.apply(formBase, {
                autoRender: true,
                floating: true,
                modal: true,
                centered: true,
                hideOnMaskTap: false,
                height: 585,
                width: 680
            });
        }
        
		Ext.regModel('ventasDetalleStore', {
    	fields: ['nombre', 'rfc']
		});

		var ventasDetalle= new Ext.data.Store({
    	model: 'ventasDetalleStore'
    	
		});	
		
		POS.AJAXandDECODE({
			method: 'mostrarDetalleVenta',
			id: 0,
			id_venta: idVenta
			},
			function (datos){//mientras responda AJAXDECODE MOSTRAR CLIENTE
				if(datos.success == true){
					
					ventasDetalle.loadData(datos.datos);
					
					var html = "";
					html += "<div class='ApplicationClientes-item' >" 
					+ "<div class='vendedor'>PRODUCTO</div>" 
					+ "<div class='sucursal'>CANTIDAD</div>" 
					+ "<div class='subtotal'>PRECIO</div>" 
					+ "<div class='subtotal'>SUBTOTAL</div>"
					+ "</div>";
								
					for( a = 0; a < ventasDetalle.getCount(); a++ ){
											
						html += "<div class='ApplicationClientes-item' >" 
						+ "<div class='vendedor'>" + ventasDetalle.data.items[a].denominacion +"</div>" 
						+ "<div class='sucursal'>"+ ventasDetalle.data.items[a].cantidad +"</div>" 
						+ "<div class='subtotal'>$ "+ ventasDetalle.data.items[a].precio+"</div>"
						+ "<div class='subtotal'>$ "+ ventasDetalle.data.items[a].subtotal +"</div>"
						+ "</div>";
					}
								
								//imprimir el html
					Ext.get("detalleVentaCliente").update("<div class='ApplicationClientes-itemsBox'>" + html +"</div>");
						
				}//FIN DATOS.SUCCES TRUE MOSTRAR CLIENTE
				if(datos.success == false){
					POS.aviso("ERROR","NO SE PUDO CARGAR LA LISTA DE VENTAS PROBABLEMENTE ESTE CLIENTE NO HA COMPRADO");
					return;
				}
				},
			function (){//no responde  AJAXDECODE MOSTRAR CLIENTE     
				POS.aviso("ERROR","NO SE PUDO CARGAR LA LISTA DE VENTAS   ERROR EN LA CONEXION :("); 
				return;
			}
		);//AJAXandDECODE MOSTRAR CLIENTE						
	
	formBase.show();
	
}
/*------------------------------------------------------------
	CREDITOS CLIENTE
--------------------------------------------------------------*/

ApplicacionClientes.prototype.mostrarVentasCreditoCliente = function(){ 
	//console.log("a entrar a listar ventas");
	//console.log("ID cliente: "+ApplicacionClientes.currentInstance.clienteSeleccionado);
	var creditCard = ApplicacionClientes.currentInstance.listarVentasCredito( ApplicacionClientes.currentInstance.clienteSeleccionado );
	
	sink.Main.ui.setCard( creditCard , 'slide' );
}

ApplicacionClientes.prototype.listarVentasCredito = function ( record_cliente ){
	
	var listaVentasCredito = new Ext.form.FormPanel({
			scroll: 'vertical',
			id: 'listaVentasCreditoCliente',
			dockedItems: this.dockedItemsFormCliente,
			items: [{
				  		id: 'datosClienteCredito',
						html: ''
					},
					{
						id: 'ventasCreditoCliente',
						html:''
					}
					]
		});
	
	
	Ext.regModel('ventasCreditoStore', {
    	fields: ['nombre', 'rfc']
	});

	var ventasClienteCredito = new Ext.data.Store({
    	model: 'ventasCreditoStore'  
	});	
	
	
	//cabecera de datos del cliente seleccionado
	
	var clienteHtml = "";
	clienteHtml += " <div class='ApplicationClientes-clienteBox'> ";
	clienteHtml += " <div class='nombre'>" + record_cliente[0].nombre + "</div>";
	clienteHtml += " <div class='ApplicationClientes-clienteDetalle'> RFC: " + record_cliente[0].rfc + "</div>";
	clienteHtml += " <div class='ApplicationClientes-clienteDetalle'> Direccion: " + record_cliente[0].direccion + "</div>";
	clienteHtml += " <div class='ApplicationClientes-clienteDetalle'> Telefono: " + record_cliente[0].telefono + "</div>";
	clienteHtml += " <div class='ApplicationClientes-clienteDetalle'> Correo Electronico: " + record_cliente[0].e_mail + "</div>";
	clienteHtml += " <div class='ApplicationClientes-clienteDetalle'> Limite de Credito: $ " + record_cliente[0].limite_credito + "</div>";
	clienteHtml += " <div class='nombre'> SALDO: $ " + record_cliente[0].saldo + "</div>";
	clienteHtml += " </div> ";
	var html = "";
	
		POS.AJAXandDECODE({
			method: 'listarVentasCreditoCliente',
			id_cliente: record_cliente[0].id_cliente //recor[0].id_cliente
			},
			function (datos){//mientras responda AJAXDECODE LISTAR VENTAS CLIENTE
				if(datos.success === true){
					ventasClienteCredito.loadData(datos.datos);
					
					Ext.get("datosClienteCredito").update( clienteHtml );
					
					html += "<div class='ApplicationClientes-item' >"
							+ "<div class='trash' ></div>"
							+ "<div class='id'>No. Venta</div>" 
							+ "<div class='fecha'>Fecha</div>" 
							+ "<div class='sucursal'>Sucursal</div>"
							+ "<div class='vendedor'>Vendedor</div>"
							+ "<div class='total'>TOTAL</div>"
							+ "<div class='total'>ABONADO</div>"
							+ "<div class='total'>ADEUDO</div>"
							+ "<div class='subtotal'>VER ABONOS</div>"
							+ "<div class='total'>STATUS</div>"
							+ "</div>";
					
					//renderear el html
					for( a = 0; a < ventasClienteCredito.getCount(); a++ ){
						var ven = ventasClienteCredito.data.items[a];
						var tot = parseFloat(ven.data.subtotal) + parseFloat(ven.data.iva);
						var adeudo = tot - ven.data.abonado;
						//console.log("-------------------- en la venta: "+ven.data.id_venta+" abonado: "+ven.data.abonado);
						var status="";
						if (adeudo <= 0){
							status="<div class='pagado'>PAGADO</div>";
						}else{
							//console.log(record_cliente[0].data.nombre);
							//var x = record_cliente[0].data.nombre;
							//console.log(ven.data.id_venta +" "+ x +"	"+tot+"	"+adeudo);
							status ="<div class='abonar' onclick='ApplicacionClientes.currentInstance.abonarVenta(" + ven.data.id_venta + " , "+ tot +" , "+ adeudo +")'>ABONAR</div>";
						}
						html+= "<div class='ApplicationClientes-item' >" 
						+ "<div class='trash' onclick='ApplicacionClientes.currentInstance.verVenta(" + ven.data.id_venta+ ")'><img height=20 width=20 src='sencha/resources/img/toolbaricons/search.png'></div>"	
						+ "<div class='id'>" + ven.data.id_venta +"</div>" 
						+ "<div class='fecha'>"+ ven.data.fecha +"</div>" 
						+ "<div class='sucursal'>"+ ven.data.descripcion +"</div>"
						+ "<div class='vendedor'>"+ ven.data.nombre +"</div>"
						+ "<div class='total'>$"+ tot +"</div>"
						+ "<div class='total'>$"+ ven.data.abonado +"</div>"
						+ "<div class='total'>$"+ adeudo +"</div>"
						+ "<div class='subtotal' onclick='ApplicacionClientes.currentInstance.verPagosVenta(" + ven.data.id_venta+ ")'><img height=20 width=20 src='sencha/resources/img/toolbaricons/compose.png'></div>"
						+ status
						+ "</div>";
														
						
					}//fin for ventasClienteCredito
		
					//imprimir el html
					Ext.get("ventasCreditoCliente").update("<div class='ApplicationClientes-itemsBox'>" + html +"</div>");
					//console.log(ventasCliente.data.items);
				}
				if(datos.success == false){
					//POS.aviso("ERROR","NO SE PUDO CARGAR LA LISTA DE VENTAS PROBABLEMENTE ESTE CLIENTE 'NO' HA COMPRADO");
					Ext.get("datosClienteCredito").update( clienteHtml );
					Ext.get("ventasCreditoCliente").update("<div class='ApplicationClientes-itemsBox'><div class='noVentas' align='center'>ESTE CLIENTE NO TIENE LISTA DE VENTAS A CREDITO</div> </div>");
				}
			},
			function (){//no responde AJAXDECODE DE VENTAS CLIENTE
				POS.aviso("ERROR","NO SE PUDO CARGAR LA LISTA DE VENTAS   ERROR EN LA CONEXION :(");      
			}
		);//AJAXandDECODE LISTAR VENTAS CLIENTE
				
		
	return listaVentasCredito;
}


ApplicacionClientes.prototype.verPagosVenta = function( idVenta ){

	 var formBase = new Ext.Panel({
		id: 'pagosVentaPanel',
		 scroll: 'vertical',
			//	items
            items: [{
				id: 'pagosVentaCliente',
		        html: ''
		    }], 
			//	dock		
            dockedItems: [{
                    xtype: 'toolbar',
                    dock: 'bottom',
                    items: [{
						xtype: 'spacer'
						},{
						//-------------------------------------------------------------------------------
						//			cancelar
						//-------------------------------------------------------------------------------
						text: 'X Cerrar',
						handler: function() {
							//regresar el boton de cliente comun a 1
							Ext.getCmp("pagosVentaPanel").destroy();
							 Ext.getBody().unmask();
							//ocultar este form
							//form.hide();							
                            }
						}]
					}]
			});
	
       
	   if (Ext.platform.isAndroidOS) {
            formBase.items.unshift({
                xtype: 'component',
                styleHtmlContent: true,
                html: '<span style="color: red">Forms on Android are currently under development. We are working hard to improve this in upcoming releases.</span>'
            });
        }

	if (Ext.platform.isPhone) {
            formBase.fullscreen = true;
        } else {
            Ext.apply(formBase, {
                autoRender: true,
                floating: true,
                modal: true,
                centered: true,
                hideOnMaskTap: false,
                height: 585,
                width: 680
            });
        }
        
		Ext.regModel('ventasDetalleStore', {
    	fields: ['nombre', 'rfc']
		});

		var ventasDetalle= new Ext.data.Store({
    	model: 'ventasDetalleStore'
    	
		});	
		
		POS.AJAXandDECODE({
			method: 'abonosVentaCredito',
			id_venta: idVenta
			},
			function (datos){//mientras responda AJAXDECODE MOSTRAR CLIENTE
				if(datos.success == true){
					
					ventasDetalle.loadData(datos.datos);
					
					var html = "";
					html += "<div class='ApplicationClientes-item' >" 
					+ "<div class='vendedor'>NUMERO DE VENTA</div>" 
					+ "<div class='sucursal'>FECHA</div>" 
					+ "<div class='subtotal'>MONTO</div>" 
					+ "</div>";
								
					for( a = 0; a < ventasDetalle.getCount(); a++ ){
											
						html += "<div class='ApplicationClientes-item' >" 
						+ "<div class='vendedor'>" + ventasDetalle.data.items[a].id_venta +"</div>" 
						+ "<div class='sucursal'>"+ ventasDetalle.data.items[a].fecha +"</div>" 
						+ "<div class='subtotal'>$ "+ ventasDetalle.data.items[a].monto+"</div>"
						+ "</div>";
					}
								
								//imprimir el html
					Ext.get("pagosVentaCliente").update("<div class='ApplicationClientes-itemsBox'>" + html +"</div>");
						
				}//FIN DATOS.SUCCES TRUE MOSTRAR CLIENTE
				if(datos.success == false){
					POS.aviso("ERROR","NO SE PUDO CARGAR LA LISTA DE PAGOS ESTE CLIENTE NO HA ABONADO A ESTA VENTA (No. VENTA: "+idVenta+")");
					return;
				}
				},
			function (){//no responde  AJAXDECODE MOSTRAR CLIENTE     
				POS.aviso("ERROR","NO SE PUDO CARGAR LA LISTA DE PAGOS ERROR EN LA CONEXION :("); 
				return;
			}
		);//AJAXandDECODE MOSTRAR CLIENTE						
	
	formBase.show();
	
}
/*------------------------------------------------------------
	ABONAR A UNA VENTA QUE EL CLIENTE ADEUDA
--------------------------------------------------------------*/

ApplicacionClientes.prototype.abonarVenta = function( idVenta , total , adeudo ){
	
	var clienteHtml = "<div class='ApplicationClientes-itemsBox'>";
		clienteHtml += " <div class='ApplicationClientes-clienteBox'> ";
		clienteHtml += " <div class='nombre'>Abono de " + ApplicacionClientes.currentInstance.clienteSeleccionado[0].nombre + " para la venta '"+idVenta+"'</div>";
		clienteHtml += " <div class='nombre'> Total de Venta: " + total + "</div>";
		clienteHtml += " <div class='nombre'> Adeuda: " + adeudo + "</div>";
		clienteHtml += " </div> </div><br>";
		
		
	 var abonaPanel = new Ext.form.FormPanel({
		 id: 'abonarVentaPanel',
		 scroll: 'vertical',
		 //html: clienteHtml,
			//	items
            items: [{
					id: 'abonarVentaCliente',
					html: clienteHtml
		 			},{                                                       
                        		xtype: 'fieldset',
                                title: 'Detalles del Pago',
								instructions: 'Inserte unicamente numeros no letras',
                                items: [
										monto = new Ext.form.TextField({
                                			id: 'monto',
											label: 'Abona $',
											listeners:{
												change: function(){
													if(this.getValue() > adeudo){
														this.setValue(adeudo);	
														Ext.getCmp("restaria").setValue(adeudo - this.getValue());
													}else{
														Ext.getCmp("restaria").setValue(adeudo - this.getValue());
													}
												},
												blur: function(){
													if(this.getValue() > adeudo){
														this.setValue(adeudo);	
														Ext.getCmp("restaria").setValue(adeudo - this.getValue());
													}else{
														Ext.getCmp("restaria").setValue(adeudo - this.getValue());
													}
												}
											}
                                        }),
										paga = new Ext.form.TextField({
											id: 'paga',
											label: 'Efectivo $',
											listeners:{
												change: function(){
													if(Ext.getCmp("paga").getValue() < Ext.getCmp("monto").getValue()){
														this.setValue(Ext.getCmp("monto").getValue());
														Ext.getCmp("cambio").setValue(this.getValue() - monto.getValue());
														Ext.getCmp("restaria").setValue(adeudo - monto.getValue());
													}
													if(Ext.getCmp("paga").getValue >= Ext.getCmp("monto").getValue()){
														Ext.getCmp("cambio").setValue(Ext.getCmp("paga").getValue() - monto.getValue());	
														Ext.getCmp("restaria").setValue(adeudo - monto.getValue());
													}
												},
												blur: function(){
													if(Ext.getCmp("paga").getValue() < Ext.getCmp("monto").getValue()){
														Ext.getCmp("paga").setValue(Ext.getCmp("monto").getValue());
														Ext.getCmp("cambio").setValue(this.getValue() - monto.getValue());
														Ext.getCmp("restaria").setValue(adeudo - monto.getValue());
													}
													if(Ext.getCmp("paga").getValue >= Ext.getCmp("monto").getValue()){
														Ext.getCmp("cambio").setValue(Ext.getCmp("paga").getValue() - monto.getValue());	
														Ext.getCmp("restaria").setValue(adeudo - monto.getValue());
													}
												}
											}
										}),
										cambio = new Ext.form.TextField({
											id: 'cambio',
											label: 'Cambio $',
											disabled: true
										}),
										restaria = new Ext.form.TextField({
											id: 'restaria',
											label: 'Restaria $',
											disabled: true
										})
										]
						}
				], 
			//	dock		
            dockedItems: [{
                    xtype: 'toolbar',
                    dock: 'bottom',
                    items: [{text: 'Abonar',
							ui: 'action'
							},
							{
							xtype: 'spacer'
							},{
						//-------------------------------------------------------------------------------
						//			cancelar
						//-------------------------------------------------------------------------------
						text: 'X Cancelar',
						handler: function() {
							//regresar el boton de cliente comun a 1
							Ext.getCmp("abonarVentaPanel").destroy();
							 Ext.getBody().unmask();
							//ocultar este form
							//form.hide();							
                            }
						}]
					}]
			});
	
       
	   if (Ext.platform.isAndroidOS) {
            abonaPanel.items.unshift({
                xtype: 'component',
                styleHtmlContent: true,
                html: '<span style="color: red">Forms on Android are currently under development. We are working hard to improve this in upcoming releases.</span>'
            });
        }

	if (Ext.platform.isPhone) {
            abonaPanel.fullscreen = true;
        } else {
            Ext.apply(abonaPanel, {
                autoRender: true,
                floating: true,
                modal: true,
                centered: true,
                hideOnMaskTap: false,
                height: 585,
                width: 680
            });
        }
        
		
		

		
								
					/*for( a = 0; a < ventasDetalle.getCount(); a++ ){
											
						html += "<div class='ApplicationClientes-item' >" 
						+ "<div class='vendedor'>" + ventasDetalle.data.items[a].id_venta +"</div>" 
						+ "<div class='sucursal'>"+ ventasDetalle.data.items[a].fecha +"</div>" 
						+ "<div class='subtotal'>$ "+ ventasDetalle.data.items[a].monto+"</div>"
						+ "</div>";
					}*/
								
								//imprimir el html
		//Ext.get("abonarVentaCliente").update("<div class='ApplicationClientes-itemsBox'>" + clienteHtml +"</div>");
						
				
	abonaPanel.show();
	
}


//autoinstalar esta applicacion
AppInstaller( new ApplicacionClientes() );