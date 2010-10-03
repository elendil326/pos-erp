ApplicacionClientes= function ()
{


        if(DEBUG){
                console.log("ApplicacionClientes: construyendo");
        }else{

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
        
        this.mainCard = this.ClientesList;
      
};




ApplicacionClientes.prototype._initToolBar = function (){

    /*
        Buscar cliente
    */
    var btnagregarCliente = [{
        id: 'btn_agregarCliente',
        text: 'Nuevo Cliente',
        handler: function(){
			sink.Main.ui.setCard( ApplicacionClientes.currentInstance.formAgregarCliente, 'slide' );
		},//this.addnewClientPanel
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
   if (!Ext.platform.isPhone) {
        /*
            Buscar cliente
        */
        this.dockedItems = [ new Ext.Toolbar({
            ui: 'light',
            dock: 'top',
            items: [campoBusqueda,{xtype: 'spacer'},detallesDeBusqueda,{xtype: 'spacer'}, btnagregarCliente]
        })];
    }else {
        this.dockedItems = [{
            xtype: 'toolbar',
            ui: 'metal',
            items: btnagregarCliente,
            dock: 'bottom'
        }];
        
        
    }
    
    
    //agregar este dock a el panel principal
    this.ClientesList.addDocked( this.dockedItems );
	
	
	
	/*
	Tool bar para formulario agregar cliente
	*/
	
	var regresar =[{
        xtype: 'button',
        id: 'cancelarGuardarCliente',
        text: 'Regresar',
        ui: 'back',
        handler: function(event,button) {
			
            sink.Main.ui.setCard( Ext.getCmp('panelClientes'), { type: 'slide', direction: 'right'} );
						
            Ext.getCmp('btn_agregarCliente').setVisible(true);
        }//fin handler cancelar cliente
                
    }];//fin boton regresar
    
    this.saveClientTool = [ new Ext.Toolbar ({ 
        ui: 'dark',
        dock: 'bottom',
        items: regresar.concat({xtype: 'spacer'})//.concat(guardar)
    })];

	this.formAgregarCliente.addDocked(this.saveClientTool);
};







/*  ------------------------------------------------------------------------------------------
        Detalles de Cliente
------------------------------------------------------------------------------------------*/
ApplicacionClientes.prototype.editClient = function (){
	
	if(DEBUG){
	    console.log("AppClientes: Editar Cliente");
		
	}
		
	
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
            Ext.getCmp('descuentoClienteM').setDisabled(false);
            Ext.getCmp('btn_CancelEditCliente').setDisabled(false);
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
            Ext.getCmp('descuentoClienteM').setDisabled(true);
            Ext.getCmp('btn_CancelEditCliente').setDisabled(true);

            ApplicacionClientes.currentInstance.handlerModificarCliente(idClienteM.getValue(),rfcClienteM.getValue(),nombreClienteM.getValue(),direccionClienteM.getValue(),telefonoClienteM.getValue(),emailClienteM.getValue(),limite_creditoClienteM.getValue());    
            break;
    }

};

ApplicacionClientes.prototype.cancelEditClient = function(){
	
    Ext.getCmp('btn_EditCliente').setText("Modificar");
    Ext.getCmp('nombreClienteM').setDisabled(true); 
    Ext.getCmp('direccionClienteM').setDisabled(true);
    Ext.getCmp('rfcClienteM').setDisabled(true);    
    Ext.getCmp('emailClienteM').setDisabled(true);
    Ext.getCmp('telefonoClienteM').setDisabled(true);   
    Ext.getCmp('limite_creditoClienteM').setDisabled(true);
    Ext.getCmp('descuentoClienteM').setDisabled(true);
    Ext.getCmp('btn_CancelEditCliente').setDisabled(true);
	
};


/*----------------------------------------------------
    Carga el panel de 'Detalles del cliente' 
-------------------------------------------------------*/


ApplicacionClientes.prototype.addClientDetailsPanel= function( recor ){


	if( !this.btnBackCliente2 ){
		
		this.btnBackCliente2 = [{
			id: 'btn_BackCliente',
			text: 'Regresar',
			handler: function(){
				Ext.getCmp('btn_EditCliente').setText("Modificar");
				sink.Main.ui.setCard( Ext.getCmp('panelClientes'), { type: 'slide', direction: 'right' } );
			},
			ui: 'back'
		}];
		
	}

    
	if( !this.btnEditCliente2 ){

		this.btnEditCliente2 = [{
			id: 'btn_EditCliente',
			text: 'Modificar',
			handler: ApplicacionClientes.currentInstance.editClient,
			ui: 'action'
		}];
	}
	
	if( !this.btnCancelEditCliente2 ){
		
		this.btnCancelEditCliente2 = [{
			id: 'btn_CancelEditCliente',
			text: 'Cancelar',
			handler: ApplicacionClientes.currentInstance.cancelEditClient,
			//hidden: true,
			ui: 'action'
		}];
	}
    
	if( !this.dockedItemsFormCliente2 ){
		
		this.dockedItemsFormCliente2;
		
		if (!Ext.platform.isPhone) {
		   
			this.dockedItemsFormCliente2 =[ new Ext.Toolbar({
				ui: 'dark',
				dock: 'bottom',
				items:  this.btnBackCliente2.concat({xtype:'spacer'}).concat(this.btnCancelEditCliente2).concat(this.btnEditCliente2)
					 
			})];
			
		}else {
			
			this.dockedItemsFormCliente2 = [{
				xtype: 'toolbar',
				ui: 'dark',
				items: this.btnBackCliente2.concat(this.btnCancelEditCliente2).concat(this.btnEditCliente2),
				dock: 'bottom'
			}];
		}
	
	}


	//crear el carrusel que contiene esa forma si es que no existe
	if(!this.carousel){
		
		//crear la forma de detalles
		formaDeDetalles = {                                                       
		        xtype: 'fieldset',
		        title: 'Detalles del Cliente',
		        id: 'updateForm',
				defaults: {
					disabledClass: '',
				},
				//utliizare un css que ya tenia por ahi
				baseCls: "ApplicationVender-ventaListaPanel",
		        items: [idClienteM = new Ext.form.HiddenField({
		                    id: 'idClienteM',
		                    value: recor.id_cliente
		                }),
		                nombreClienteM = new Ext.form.TextField({
		                    id: 'nombreClienteM',
		                    label: 'Nombre',
		                    required: true,
		                    value: recor.nombre,
		                    disabled: true
		                }),
		                rfcClienteM = new Ext.form.TextField({
		                    id: 'rfcClienteM',
		                    label: 'RFC',
		                    required: true,
		                    value: recor.rfc,
		                    disabled: true
		                }),
		                direccionClienteM = new Ext.form.TextField({
		                    id: 'direccionClienteM',
		                    label: 'Direccion',
		                    required: true,
		                    value: recor.direccion,
		                    disabled: true
		                }),
		                emailClienteM = new Ext.form.TextField({
		                    id: 'emailClienteM',
		                    label: 'E-mail',
		                    value: recor.e_mail,
		                    disabled: true
		                }),
		                telefonoClienteM = new Ext.form.TextField({
		                    id: 'telefonoClienteM',
		                    label: 'Telefono',
		                    value: recor.telefono,
		                    disabled: true
		                }),
						limite_creditoClienteM = new Ext.form.TextField({
		                    id: 'descuentoClienteM',
		                    label: 'Descuento',
		                    required: false,
		                    value: recor.descuento,
		                    disabled: true
		                }),
		                    new Ext.form.TextField({
		                    id: 'limite_creditoClienteM',
		                    label: 'Credito Max',
		                    required: false,
		                    value: POS.currencyFormat(recor.limite_credito),
		                    disabled: true
		                })	,
			            	new Ext.form.TextField({
			                    id: 'creditoRestanteClienteM',
			                    label: 'Credito Res',
			                    required: false,
			                    value: POS.currencyFormat(recor.credito_restante),
			                    disabled: true
			                })
		        ]};
		
		this.carousel = new Ext.Carousel({
			id: 'carruselDetallesCliente',
	        items: [{
	            scroll: 'vertical',
	            xtype: 'panel',
	            title: 'customerDetails',
	            id: 'customerDetailsForm',
	            items: [ formaDeDetalles ],
				listeners:{
					show: function(){

						Ext.getCmp('btn_CancelEditCliente').setDisabled( true );
						Ext.getCmp('btn_EditCliente').setText("Modificar");
					}
				}
	          
	        }, {

	            scroll: 'vertical',
	            xtype: 'panel',
	            title: 'ventas',
	            id: 'customerHistorial',
	            items: [ {id:'datosCliente'}, {id: 'customerHistorialSlide' }],
	          
	        }, { 
	            scroll: 'vertical',
	            xtype: 'panel',
	            title: 'creditos',
	            id: 'customerCreditHistorial',
	            items: [{id:'datosClienteCredito'},{id: 'customerCreditHistorialSlide'}]
	        }],
			listeners: {
				cardswitch : function( a ){
					
					if( Ext.getCmp("carruselDetallesCliente").getActiveIndex() == 0 ){
						
						Ext.getCmp('btn_CancelEditCliente').setDisabled( true );
	                    Ext.getCmp('btn_EditCliente').setDisabled( false );
						
					}else{
						
						Ext.getCmp('btn_CancelEditCliente').setDisabled( true );
	                    Ext.getCmp('btn_EditCliente').setDisabled( true );
						
					}
				}
			}
	    });
		
	}else{
		
		//Si si existe, solo actualizar los datos de los campos de texto
		Ext.getCmp("idClienteM").setValue(recor.id_cliente);
		Ext.getCmp("nombreClienteM").setValue(recor.nombre);
		Ext.getCmp("rfcClienteM").setValue(recor.rfc);
		Ext.getCmp("direccionClienteM").setValue(recor.direccion);
		Ext.getCmp("emailClienteM").setValue(recor.e_mail);
		Ext.getCmp("telefonoClienteM").setValue(recor.telefono);		
		Ext.getCmp("descuentoClienteM").setValue(recor.descuento);				
		Ext.getCmp("limite_creditoClienteM").setValue( POS.currencyFormat(recor.limite_credito));
		Ext.getCmp("creditoRestanteClienteM").setValue( POS.currencyFormat(recor.credito_restante));		
		
	
	}

    



    return new Ext.Panel({
	
		cls: "ApplicationClientes-addClientDetailsPanel",
		
        dockedItems : this.dockedItemsFormCliente2,
	
        layout: {
            type: 'vbox',
            align: 'stretch'
        },

        defaults: {
		  disabledClass: '',
          flex: 1
        },
        items: [ this.carousel ]
	
    });
};







/*
    Application Logic for modifying clients
*/
ApplicacionClientes.prototype.handlerModificarCliente = function(id,rfc,nombre,direccion,telefono,email,limite_credito){
    
    
	//revisar los datos de la forma e intentar guardarlos

    if( nombre === '' || rfc === '' || limite_credito === ''){
		return;
    }

    Ext.getBody().mask(false, '<div class="demos-loading">Guardando cambios</div>');

   	POS.AJAXandDECODE({
            action: '1002',//102
            id: id,
            rfc: rfc,
            nombre: nombre,
            direccion: direccion,
            telefono: telefono,
            e_mail: email,
            limite_credito: limite_credito,
			descuento : 0
        },
        function (datos){//mientras responda
            if(datos.success === true){
				if(DEBUG){
                	console.log("AppClientes: Datos guardados correctamente");					
				}
				
				//actualizar la lista de los clientes
                POS.AJAXandDECODE({
                    action: '1005'
                },
                function (datos){//mientras responda
                    ClientesListStore.loadData(datos.datos); 
                },
                function (){//no responde       
                    POS.aviso("ERROR!!","NO SE PUDO CARGAR LA LISTA DE CLIENTES ERROR EN LA CONEXION :(");  

                });

            }else{
                  	POS.aviso("No se guardaron los datos",""+datos.reason);

				  	if(DEBUG){
						console.warn("No se pudieron guardar los datos del cliente");
					}
                  
            }


        },
        function (){//no responde  AJAXanDECODE actualizar
            POS.aviso("ERROR","NO SE PUDO MODIFICAR CLIENTE ERROR EN LA CONEXION ");      
        });//AJAXandDECODE actualizar cliente

        Ext.getBody().unmask();


};






/*  ------------------------------------------------------------------------------------------
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
};

ApplicacionClientes.prototype.filterByRfc = function(){
    apClientes_filtro = "rfc";
    Ext.getCmp("listaClientes").refresh();
};

ApplicacionClientes.prototype.filterByDireccion = function(){
    apClientes_filtro = "direccion";
    Ext.getCmp("listaClientes").refresh();
};



var ClientesListStore = new Ext.data.Store({
    model: 'ApplicacionClientes_'+apClientes_filtro,
    sorters: apClientes_filtro,
            
    getGroupString : function(record) {
        //console.log("voy a filtrar por: "+apClientes_filtro, record.get(apClientes_filtro));
        return record.get(apClientes_filtro)[0];
    }   
});

/*  ------------------------------------------------------------------------------------
        Filtrado de busqueda en el store HAY BUG AQUI, DESPUES DE 1 FILTRO CUANDO SE REGRESA A CLIENTES NO LOS MUESTRA TODOS A MENOS QUE SE TECLE EN EL TEXT DE BUSQUEDA ALGO O SE DEJE EN ''
---------------------------------------------------------------------------------------*/
ApplicacionClientes.prototype.doSearch = function(  ){
 
    if (Ext.getCmp('btnBuscarCliente').getValue().length === 0){
	
        ClientesListStore.clearFilter();
        try{
        	ClientesListStore.sync(); //marca erro pero si lo meto en try catch o no lo llamo la vista no coincide con el store
        }catch(e){
			console.warn("Error sync -> "+e);
		}

	}else{
		
		if(DEBUG){
			//console.log( "AppCliente Buscando " + Ext.getCmp('btnBuscarCliente').getValue() );
		}
        try{
        	ClientesListStore.filter('nombre', Ext.getCmp('btnBuscarCliente').getValue()  );
			ClientesListStore.sync();
        }catch(e){
			console.warn("Error -> "+e);
		}		
		
	}
        


        
};



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
                        action: '1005'
                        },
                        function (datos){//mientras responda
                            if(!datos.success){
                                POS.aviso("ERROR", ""+datos.reason);    
                                return;
                            }
                            ClientesListStore.loadData(datos.datos);          
                        },
                        function (){//no responde       
                                POS.aviso("ERROR","NO SE PUDO CARGAR LA LISTA DE CLIENTES       ERROR EN LA CONEXION :(");      
                        }
                );//AJAXandDECODE
                Ext.getBody().unmask();
            }//fin beforef
        },
        items: [{
            width: '100%',
            height: '100%',
            xtype: 'list',
            store: ClientesListStore,
            id: 'listaClientes',
            tpl: '<tpl for="."><div class="contact"><strong>{nombre}</strong> {rfc} {direccion}</div></tpl>',
            itemSelector: 'div.contact',
            singleSelect: true,
            grouped: true,
            indexBar: true,
            listeners: {
                selectionchange: function(){
	
                        if (this.getSelectionCount() == 1) {

                            var recor = this.getSelectedRecords();
							
							recor = recor[0].data;
							
							if(DEBUG){
								console.log("Seleccionano cliente", recor  );
							}

                            ApplicacionClientes.currentInstance.clienteSeleccionado = recor;


							
							//crear el nuevo panel
                            var detalles = ApplicacionClientes.currentInstance.addClientDetailsPanel( recor ); 


						   	ApplicacionClientes.currentInstance.listarVentas( recor );

						    ApplicacionClientes.currentInstance.listarVentasCredito( recor );

							//deslizarlo
                            sink.Main.ui.setCard( detalles , 'fade');
                        }


                }
            }//fin listener
        }]

});


/*  ------------------------------------------------------------------------------------------
        AGREGAR Nuevo Cliente
------------------------------------------------------------------------------------------*/

ApplicacionClientes.prototype.formAgregarCliente  = new Ext.form.FormPanel({
        scroll: 'vertical',
        id:'formAgregarCliente', 
		baseCls: 'formAgregarCliente',
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
                        label: '*Direccion'
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
                    ui: 'action',
                    maxWidth:150,
                    handler: function(event,button) {
                        if( nombreCliente.getValue() ==='' || rfcCliente.getValue() ==='' || limite_creditoCliente.getValue() ===''){
                            POS.aviso("ERROR!!","LLENAR ALMENOS LOS CAMPOS CON  *");                                        
                        }else{
                            Ext.getBody().mask(false, '<div class="demos-loading">Loading&hellip;</div>');
                            POS.AJAXandDECODE({
                                action: '1001',
                                rfc: rfcCliente.getValue(),
                                nombre: nombreCliente.getValue(),
                                direccion: direccionCliente.getValue(),
                                telefono: telefonoCliente.getValue(),
                                e_mail: emailCliente.getValue(),
                                limite_credito: limite_creditoCliente.getValue()
                            },
                            function (datos){//mientras responda
                                if(datos.success === true){//
                                    POS.aviso("NUEVO CLIENTE","LOS DATOS DEL CLIENTE FUERON GUARDADOS CORRECTAMENTE :)");
                                    rfcCliente.setValue('');
                                    nombreCliente.setValue('');
                                    direccionCliente.setValue('');
                                    telefonoCliente.setValue('');
                                    emailCliente.setValue('');
                                    limite_creditoCliente.setValue('');
                                 
                                    POS.AJAXandDECODE({
                                            action: '1005'
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
                                    POS.aviso("ERROR!!","NO SE INSERTO EL CLIENTE <br>Detalles:"+datos.reason);
                                }
                                 
                                },
                            function (){//no responde       
                                POS.aviso("ERROR","NO SE PUDO INSERTAR CLIENTE, ERROR EN LA CONEXION");      
                            }
                            );//AJAXandDECODE insertar cliente
                                Ext.getBody().unmask();
                               // sink.Main.ui.setCard( Ext.getCmp('panelClientes'), { type: 'slide', direction: 'right'} );
                                //Ext.getCmp('btn_agregarCliente').setVisible(true);
                        }//else de validar vacios
                                        
                                        
                    }//fin handler
        
                }//fin boton
               
        ]
});//fin formPanel


/*------------------------------------------------------------------
            VENTAS EMITIDAS A UN CLIENTE 
------------------------------------------------------------------------*/

ApplicacionClientes.prototype.listarVentas = function ( record_cliente ){
    
    
    Ext.regModel('ventasStore', {
        fields: ['nombre', 'rfc']
    });

    var ventasCliente = new Ext.data.Store({
        model: 'ventasStore'
    }); 
    
    
	//cabecera de datos del cliente seleccionado
    POS.AJAXandDECODE({
       	action: '1401',
       	id_cliente: record_cliente.id_cliente 
    },
    function (datos){
		if(datos.success === true){
	
		ventasCliente.loadData(datos.datos);
                    
		var html = "<div class='AC-Title'>Todas la ventas a este cliente</div>";

		html += "<div class='ApplicationClientes-Item' style='border:0px; font-size: 14px;' >"
                            + "<div class='trash' ></div>"
                            + "<div class='id' style='width:6%; '>Venta</div>" 
                            + "<div class='tipo'>Tipo Venta</div>" 
                            + "<div class='fecha'>Fecha</div>" 
                            + "<div class='sucursal'>Sucursal</div>"
                            + "<div class='vendedor'>Vendedor</div>"
                            + "<div class='subtotal'>Subtotal</div>"
                            + "<div class='descuento' >Desc.</div>"
                            + "<div class='iva'>IVA</div>"
                            + "<div class='total'>Total</div>"
			+ "</div>";

		//renderear el html
		for( a = 0; a < ventasCliente.getCount(); a++ ){

			var facturado="";
			
			if ( ventasCliente.data.items[a].data.facturado == 1 ){
				facturado="<div class='pagado'>Facturada</div>";
			}

			if ( ventasCliente.data.items[a].data.facturado == 0 ){

				vtaClteTotal = parseFloat(ventasCliente.data.items[a].data.total); 
				vtaCltePagado = parseFloat(ventasCliente.data.items[a].data.pagado);

				if(  vtaClteTotal > vtaCltePagado ){
					facturado = "<div class='abonar'>Adeuda</div>";
				}else{
					facturado ="<div class='abonar' onclick='ApplicacionClientes.currentInstance.panelFacturas(" + ventasCliente.data.items[a].data.id_venta + " , "+ ventasCliente.data.items[a].data.id_cliente +")'>Facturar</div>";
				}
			}

			html += "<div class='ApplicationClientes-Item' >" 
				+ "<div class='trash' onclick='ApplicacionClientes.currentInstance.verVenta(" +ventasCliente.data.items[a].data.id_venta+ ")'><img height=20 width=20 src='sencha/resources/img/toolbaricons/search.png'></div>" 
				+ "<div class='id'>" + ventasCliente.data.items[a].data.id_venta +"</div>" 
				+ "<div class='tipo'>" + ventasCliente.data.items[a].data.tipo_venta +"</div>" 
				+ "<div class='fecha'>"+ ventasCliente.data.items[a].data.fecha +"</div>" 
				+ "<div class='sucursal'>"+ ventasCliente.data.items[a].data.descripcion +"</div>"
				+ "<div class='vendedor'>"+ ventasCliente.data.items[a].data.nombre +"</div>"
				+ "<div class='subtotal'>"+ POS.currencyFormat(ventasCliente.data.items[a].data.subtotal) +"</div>"
				+ "<div class='descuento'>"+ POS.currencyFormat(ventasCliente.data.items[a].data.descuento) +"</div>"
				+ "<div class='iva'>"+ POS.currencyFormat(ventasCliente.data.items[a].data.iva) +"</div>"
				+ "<div class='total'>"+ POS.currencyFormat(ventasCliente.data.items[a].data.total) +"</div>"
				+ facturado
				+ "</div>";
		}

		}



		if(!datos.success){
			
			html="<div class=\"no-data\">Este cliente no ha hecho ninguna compra.</div>";
		}


		Ext.get("customerHistorialSlide").update("<div class='ApplicationClientes-itemsBox'>" + html +"</div>");

		},
		function (){//no responde AJAXDECODE DE VENTAS CLIENTE
			POS.aviso("ERROR","NO SE PUDO CARGAR LA LISTA DE VENTAS   ERROR EN LA CONEXION :(");      
		}
		);//AJAXandDECODE LISTAR VENTAS CLIENTE
                
        
    //return listaVentas;
};



/*--------------------------------------------------------------------
        VER DETALLES DE LA VENTA DEL CLIENTE
----------------------------------------------------------------------*/
ApplicacionClientes.prototype.verVenta = function( idVenta ){

     var formBase = new Ext.Panel({
        id: 'detalleVentaPanel',
         scroll: 'vertical',
            //  items
            items: [{
                id: 'detalleVentaCliente',
                html: ''
            }], 
            //  dock        
            dockedItems: [{
                    xtype: 'toolbar',
                    dock: 'bottom',
                    items: [{
                        xtype: 'spacer'
                        },{
                        
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
            action: '1402',
            id_venta: idVenta
            },
            function (datos){//mientras responda AJAXDECODE MOSTRAR CLIENTE
                if(datos.success){
                    
                    ventasDetalle.loadData(datos.datos);
                    
                    var html = "";
                    html += "<div class='ApplicationClientes-Item' >" 
                    + "<div class='vendedor'>PRODUCTO</div>" 
                    + "<div class='sucursal'>CANTIDAD</div>" 
                    + "<div class='subtotal'>PRECIO</div>" 
                    + "<div class='subtotal'>SUBTOTAL</div>"
                    + "</div>";
                                
                    for( a = 0; a < ventasDetalle.getCount(); a++ ){
                                            
                        html += "<div class='ApplicationClientes-Item' >" 
                        + "<div class='vendedor'>" + ventasDetalle.data.items[a].data.denominacion +"</div>" 
                        + "<div class='sucursal'>"+ ventasDetalle.data.items[a].data.cantidad +"</div>" 
                        + "<div class='subtotal'>$ "+ ventasDetalle.data.items[a].data.precio+"</div>"
                        + "<div class='subtotal'>$ "+ ventasDetalle.data.items[a].data.subtotal +"</div>"
                        + "</div>";
                    }
                                
                                //imprimir el html
                    Ext.get("detalleVentaCliente").update("<div class='ApplicationClientes-itemsBox'>" + html +"</div>");
                        
                }//FIN DATOS.SUCCES TRUE MOSTRAR CLIENTE
                if(!datos.success){
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
	
    /*
		Se le da un fono al panel contenedor
	*/
	Ext.get("detalleVentaCliente").parent().parent().setStyle({
					'background-image':'url("media/g3.png")'								   
	});
};


/* -------------------------------------------------------------
        MUESTRA LAS VENTAS A CREDITO DE UN CLIENTE
---------------------------------------------------*/


ApplicacionClientes.prototype.listarVentasCredito = function ( record_cliente ){

    Ext.regModel('ventasCreditoStore', {
        fields: ['nombre', 'rfc']
    });

    var ventasClienteCredito = new Ext.data.Store({
        model: 'ventasCreditoStore'  
    }); 
    
    
    //cabecera de datos del cliente seleccionado
    
        POS.AJAXandDECODE({
            action: '1403',
            id_cliente: record_cliente.id_cliente 
            },
            function (datos){//mientras responda AJAXDECODE LISTAR VENTAS CLIENTE
                if(datos.success === true){
                    ventasClienteCredito.loadData(datos.datos);

					var html = "<div class='AC-Title'>Ventas a Credito para este cliente</div>";
                    
					html += "<div class='ApplicationClientes-Item' style='border:0px; font-size: 14px;' >"
                            + "<div class='trash' ></div>"
                            + "<div class='id'>Venta</div>" 
                            + "<div class='fecha'>Fecha</div>" 
                            + "<div class='sucursal'>Sucursal</div>"
                            + "<div class='vendedor'>Vendedor</div>"
                            + "<div class='total'>Total</div>"
                            + "<div class='total'>Abonado</div>"
                            + "<div class='total'>Adeudo</div>"
                            + "<div class='subtotal'>Abonos</div>"
                            + "<div class='total'>Estado</div>"
                            + "</div>";
                    
                    //renderear el html
                    for( a = 0; a < ventasClienteCredito.getCount(); a++ ){
                        var ven = ventasClienteCredito.data.items[a];
                        var adeudo = ven.data.adeudo;
                        

                        var status="";

                        if (adeudo <= 0){
                            status="<div class='pagado'>PAGADO</div>";
                        }else{
                            status ="<div onclick='ApplicacionClientes.currentInstance.abonarVenta(" + ven.data.id_venta + " , "+ ven.data.total +" , "+ ven.data.adeudo +")'>ABONAR</div>";
                        }
                        html+= "<div class='ApplicationClientes-Item' >" 
                        + "<div class='trash' onclick='ApplicacionClientes.currentInstance.verVenta(" + ven.data.id_venta+ ")'><img height=20 width=20 src='sencha/resources/img/toolbaricons/search.png'></div>"   
                        + "<div class='id'>" + ven.data.id_venta +"</div>" 
                        + "<div class='fecha'>"+ ven.data.fecha +"</div>" 
                        + "<div class='sucursal'>"+ ven.data.sucursal +"</div>"
                        + "<div class='vendedor'>"+ ven.data.vendedor +"</div>"
                        + "<div class='total'>"+ POS.currencyFormat(ven.data.total) +"</div>"
                        + "<div class='total'>"+ POS.currencyFormat(ven.data.abonado) +"</div>"
                        + "<div class='total'>"+ POS.currencyFormat(ven.data.adeudo) +"</div>"
                        + "<div class='total' style='height:15px;'  onclick='ApplicacionClientes.currentInstance.verPagosVenta(" + ven.data.id_venta+ ")'><img height=20 width=20 src='sencha/resources/img/toolbaricons/compose.png'></div>"
                        + status
                        + "</div>";
                                                        
                        
                    }//fin for ventasClienteCredito
        
                    //imprimir el html
                    
                    //console.log(ventasCliente.data.items);
                }
                if(!datos.success){
                    
					html="<div class=\"no-data\">Este cliente no ha hecho ninguna compra a credito.</div>";
                }
                
                Ext.get("customerCreditHistorialSlide").update("<div class='ApplicationClientes-itemsBox'>" + html +"</div>");
            },
            function (){//no responde AJAXDECODE DE VENTAS CLIENTE
                POS.aviso("ERROR","NO SE PUDO CARGAR LA LISTA DE VENTAS   ERROR EN LA CONEXION :(");      
            }
        );//AJAXandDECODE LISTAR VENTAS CLIENTE
                
        
    //return listaVentasCredito;
};

/*-------------------------------------------------------
    PAGOS HECHOS SOBRE 1 VENTA EN ESPECIFICO
---------------------------------------------------------*/
ApplicacionClientes.prototype.verPagosVenta = function( idVenta ){

     var formBase = new Ext.Panel({
        id: 'pagosVentaPanel',
         scroll: 'vertical',
            //  items
            items: [{
                id: 'pagosVentaCliente',
                html: ''
            }], 
            //  dock        
            dockedItems: [{
                    xtype: 'toolbar',
                    dock: 'bottom',
                    items: [{
                        xtype: 'spacer'
                        },{
                        
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
            action: '1404',
            id_venta: idVenta
            },
            function (datos){//mientras responda AJAXDECODE MOSTRAR CLIENTE
                if(datos.success){
                    
                    ventasDetalle.loadData(datos.datos);
                    
                    var html = "";
                    html += "<div class='ApplicationClientes-Item' >" 
                    + "<div class='vendedor'># DE VENTA</div>" 
                    + "<div class='sucursal'>FECHA</div>" 
                    + "<div class='subtotal'>MONTO</div>"
                    + "<div class='subtotal'></div>"
                    + "</div>";
                                
                    for( a = 0; a < ventasDetalle.getCount(); a++ ){
                                            
                        html += "<div class='ApplicationClientes-Item' id='pago_Borrar_"+ventasDetalle.data.items[a].data.id_pago+"'>" 
                        + "<div class='vendedor'>" + ventasDetalle.data.items[a].data.id_venta +"</div>" 
                        + "<div class='sucursal'>"+ ventasDetalle.data.items[a].data.fecha +"</div>" 
                        + "<div class='subtotal'>$ "+ ventasDetalle.data.items[a].data.monto+"</div>"
                        + "<div class='abonar' onclick='ApplicacionClientes.currentInstance.EliminarabonoVenta(" +  ventasDetalle.data.items[a].data.id_pago +")'>ELIMINAR</div>"
                        + "</div>";
                    }
                                
                                //imprimir el html
                    Ext.get("pagosVentaCliente").update("<div class='ApplicationClientes-itemsBox'>" + html +"</div>");
                        
                }//FIN DATOS.SUCCES TRUE MOSTRAR CLIENTE
                if(!datos.success){

					html = "<div class='ApplicationClientes-itemsBox' id='no_pagosVentaClien' ><div class='no-data'>"+datos.reason+"</div></div>";
					
					Ext.get("pagosVentaCliente").update(html);
					
                    return;
                }
                },
            function (){//no responde  AJAXDECODE MOSTRAR CLIENTE     
                POS.aviso("ERROR","NO SE PUDO CARGAR LA LISTA DE PAGOS ERROR EN LA CONEXION :("); 
                return;
            }
        );//AJAXandDECODE MOSTRAR CLIENTE                       
    
    formBase.show();
	
	/*
		Se le da un fono al panel contenedor
	*/
	Ext.get("pagosVentaCliente").parent().parent().setStyle({
					'background-image':'url("media/g3.png")'								   
	});
    
};




/*------------------------------------------------------------
    ABONAR A UNA VENTA QUE EL CLIENTE ADEUDA
--------------------------------------------------------------*/

ApplicacionClientes.prototype.abonarVenta = function( idVenta , total , adeudo ){
    
    var clienteHtml = "<div class='ApplicationProveedores-itemsBox'>";
        clienteHtml += " <div class='no-data' id='abonar-Venta' > ";
        clienteHtml += " <div class='nombre'>Abono de " + ApplicacionClientes.currentInstance.clienteSeleccionado.nombre + " para la venta '"+idVenta+"'</div>";
        clienteHtml += " <div class='nombre'> Total de Venta: " + total + "</div>";
        clienteHtml += " <div class='nombre'> Adeuda: " + adeudo + "</div>";
        clienteHtml += " </div> </div><br>";
        
        
     var abonaPanel = new Ext.form.FormPanel({
         id: 'abonarVentaPanel',
         scroll: 'vertical',
         //html: clienteHtml,
            //  items
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
            //  dock        
            dockedItems: [{
                    xtype: 'toolbar',
                    dock: 'bottom',
                    items: [{text: 'Abonar',
                            ui: 'action',
                            handler: function(){
                                
                                POS.AJAXandDECODE({
                                    action: '1405',
                                    id_venta: idVenta,
                                    monto: Ext.getCmp("monto").getValue()
                                    },
                                    function (datos){
                                        if(datos.success){
                                        
                                            ApplicacionClientes.currentInstance.listarVentasCredito( ApplicacionClientes.currentInstance.clienteSeleccionado );
                                            
                                            Ext.getCmp("abonarVentaPanel").destroy();
                                            Ext.getBody().unmask();
                                            
                                        }else{
                                            POS.aviso("Abono Venta",""+datos.reason);       
                                        }
                                    },
                                    function (){
                                        POS.aviso("Provedores","Algo anda mal con tu conexion.");   
                                    }
                                );
                                
                            }//fin handler
                            },
                            {
                            xtype: 'spacer'
                            },{
                        //-------------------------------------------------------------------------------
                        //          cancelar
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
    
	/*
		Se le da un fono al panel contenedor
	*/
	
	Ext.get("abonar-Venta").setStyle({
					'background-image':'url("media/g3.png")'								   
	});
	
	Ext.get("abonar-Venta").parent().parent().setStyle({
					'background-image':'url("media/g2.png")'								   
	});
	
	Ext.get("abonarVentaCliente").parent().parent().setStyle({
					'background-image':'url("media/g2.png")'								   
	});
	
};

/*---------------------------------------------------
    ELIMINAR UN PAGO DE UNA VENTA
-----------------------------------------------------*/
ApplicacionClientes.prototype.EliminarabonoVenta = function ( id_Pago ){
    var overlayTb = new Ext.Toolbar({
            dock: 'top'
        });
    var btns = new Ext.Toolbar({
            items :[{
                    text: 'Eliminar',
                    ui: 'action',
                    handler: function(){
                        POS.AJAXandDECODE({
                            action: '1406',
                            id_pago: id_Pago
                            },
                            function (datos){//mientras responda
                                if(!datos.success){
                                    POS.aviso("ERROR",""+datos.reason);
									return;
                                }
                                ApplicacionClientes.currentInstance.listarVentasCredito( ApplicacionClientes.currentInstance.clienteSeleccionado );
                                Ext.get("pago_Borrar_"+id_Pago).remove();
                                Ext.getCmp("confirmaBorrarPago").destroy();
                            },
                            function (){//no responde
                                POS.aviso("ERROR","NO SE PUDO ELIMINAR PAGO  ERROR EN LA CONEXION :("); 
                            }
                        );//AJAXandDECODE 1105
                        
                    }
                    },
                    {
                    xtype: 'spacer'
                    },
                    {
                    text: 'Cancelar',
                    ui: 'action',
                    handler: function(){
                        Ext.getCmp("confirmaBorrarPago").destroy();
                    }
                    }],
            dock: 'bottom'
        });
    
    var overlay = new Ext.Panel({
            id: 'confirmaBorrarPago',
            floating: true,
            modal: true,
            centered: true,
            width: Ext.platform.isPhone ? 260 : 400,
            height: Ext.platform.isPhone ? 115 : 210,
            styleHtmlContent: true,
            dockedItems: [overlayTb, btns],
            scroll: 'vertical',
            html: '- Si elimina este pago es porque nunca reicibio ese dinero <br>- Por que usted esta devolviendo ese dinero<br>- Eliminar este pago incrementara la deuda del cliente con esta venta.'
        });

    overlayTb.setTitle('CONFIRMAR ELIMINACION DE PAGO');
    overlay.show();
};

/*--------------------------------------------------------------------
    VER PANEL DE FACTURAS
----------------------------------------------------------------------*/

ApplicacionClientes.prototype.panelFacturas = function( id_venta , id_cliente ){
	
	if( !ApplicacionClientes.currentInstance.facturaObj ){
		
		ApplicacionClientes.prototype.facturaObj = new ApplicationFacturaVentas();
	}
		
		
		
		ApplicacionClientes.currentInstance.facturaObj.facturarPanel( id_venta, id_cliente );
		sink.Main.ui.setCard(  ApplicacionClientes.currentInstance.facturaObj.facturaVenta, 'slide' );
		
		Ext.get("datosClienteFactura").parent().parent().setStyle({
					'background-image':'url("media/g3.png")'								   
		});
		
		Ext.get("datosClienteFactura").setStyle({   
			'overflow': 'hidden',
			'margin': '5px',
			'-moz-border-radius': '15px',
		    '-webkit-border-radius': '15px',
    		'-khtml-border-radius': '15px',
			'background-image':'url(media/dark2.png)',
			'padding-left' : '5px',
			'border-style':'solid',
			'border-width':'1px',
			'border-right-color':'#60656b',
			'border-left-color':'#61666c',
			'border-top-color':'#474a4f',
			'border-bottom-color':'#959a9f'
		});
	
};



//autoinstalar esta applicacion
AppInstaller( new ApplicacionClientes() );