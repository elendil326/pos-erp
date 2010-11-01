/** 
 * @fileoverview Este archivo contiene el modulo Clientes 
 * del punto de venta. Es accesible por todos los cajeros,
 * y gerentes del sistema.
 *
 * @author 
 * @version 0.1 
 */




/**
 * Construir un nuevo objeto de tipo ApplicacionClientes.
 * @class Esta clase se encarga de la creacion de interfacez
 * que intervinen en la manipulación de clientes. 
 * @constructor
 * @throws MemoryException Si se agota la memoria
 * @return Un objeto del tipo ApplicacionClientes
 */
ApplicacionClientes= function ()
{


        if(DEBUG){
                console.log("ApplicacionClientes: construyendo");
        }else{

        }

		this._init();
		
	//variable auxiliar para referirse a esta instancia del objeto
    //solo funciona al instanciarse una vez, si el constructor
    //se vuelve a ejecutar esta variable contendra el ultimo 
    //objeto construido
        ApplicacionClientes.currentInstance = this;     

        

        return this;
};


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


/**
 * Contiene el panel principal de la aplicacion clientes
 * @type Ext.Panel
 */
ApplicacionClientes.prototype.mainCard = null;

/**
 * Nombre que se muestra en el menu principal.
 * @type String
 */
ApplicacionClientes.prototype.appName = null;

/**
 * Items que colocaran en el menu principal al cargar este modulo.
 * De no requerirse ninguno, hacer igual a null
 * @type Ext.Panel
 */
ApplicacionClientes.prototype.leftMenuItems = null;

/**
 * Texto de ayuda formateado en HTML para este modulo.
 * @type HTML
 */
ApplicacionClientes.prototype.ayuda = null;
/**
 * Store que contendra la lista de clientes que hay en el sistema, 
 * este Store se sincronizará con un componente Lista para visualizarlos.
 * @type Ext.data.Store
 */
ApplicacionClientes.prototype.ClientesListStore = null;
/**
 * Toolbar que se agrega al mainCard en la parte superior, contiene el TextField 
 * buscar cliente, el SplitButton con los filtrados de busqueda (Nombre, RFC, Direccion), 
 * y el boton Nuevo Cliente
 * @type Ext.Toolbar
 */
ApplicacionClientes.prototype.dockedItems = null;
/**
 * Toolbar que se agrega al panel que visualiza un form con los datos del cliente, el ToolBar
 * se coloca en la parte inferior, contiene los botones Regresar, Cancelar, Modificar
 * @type Ext.Toolbar
 */
ApplicacionClientes.prototype.dockedItemsFormCliente = null;
/**
 * Propiedad de la clase que hace referencia al cliente que se ha elegido de la
 * lista de clientes
 * @type Ext.data.Model
 */
ApplicacionClientes.prototype.clienteSeleccionado = null;



/**
 * Contiene un panel con los detalles del cliente
 */
ApplicacionClientes.prototype.clienteDetallePanel = null;




/**
 * Funcion que se ejecuta para inicializar la Toolbar de la mainCard y
 * y la Toolbar de la card que contiene el formulario de Nuevo Cliente,
 * inicializa la mainCard.
 * @return void
 */											   
ApplicacionClientes.prototype._init = function()
{
        //nombre de la aplicacion
        this.appName = "Clientes";
        
        //ayuda sobre esta applicacion
        this.ayuda = "Ayuda sobre este modulo de prueba <br>, html es valido <br> :D";
        
        //initialize the tootlbar which is a dock
        this._initToolBar();
        //inicializa la mainCard
        this.mainCard = this.ClientesList;
      
};



/**
 * Crea 2 Ext.Toolbar en las cuales se guarda en {@link ApplicacionClientes#dockedItems} y
 * en el dock del form de agregar cliente, para despues agregarselos a {@link ApplicacionClientes#mainCard} y
 * {@link ApplicacionClientes#formAgregarCliente} respectivamente.
 * @return void
 */
ApplicacionClientes.prototype._initToolBar = function (){

    /*
     *   DOCKED ITEMS PARA LA MAINCARD
     */
    var btnagregarCliente = [{
        id: 'btn_agregarCliente',
        text: 'Nuevo Cliente',
        handler: function(){
			sink.Main.ui.setCard( ApplicacionClientes.currentInstance.formAgregarCliente, 'slide' );
		},
        ui: 'action'
    }];


    var detallesDeBusqueda = [{
        xtype: 'segmentedbutton',
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
        inputCls: 'cliente-buscar',
        id: 'btnBuscarCliente'
        
    });

    campoBusqueda.on('keyup', function() {
      ApplicacionClientes.currentInstance.doSearch();
    });


   
   if (!Ext.is.Phone) {
        this.dockedItems = [ new Ext.Toolbar({
            ui: 'light',
            dock: 'bottom',
            //items: [campoBusqueda,{xtype: 'spacer'},detallesDeBusqueda,{xtype: 'spacer'}, btnagregarCliente]
			items: [ {xtype: 'spacer'}, btnagregarCliente]
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
	 *	DOCKED ITEMS PARA EL PANEL QUE CONTIENE AL FORMULARIO DE AGREGAR CLIENTE
	 */
	
	var regresar =[{
        xtype: 'button',
        id: 'cancelarGuardarCliente',
        text: 'Regresar',
        ui: 'back',
        handler: function(event,button) {
			
            sink.Main.ui.setCard( Ext.getCmp('panelClientes'), { type: 'slide', direction: 'right'} );
						
            Ext.getCmp('btn_agregarCliente').setVisible(true);
        }
                
    }];
    
    this.saveClientTool = [ new Ext.Toolbar ({ 
        ui: 'dark',
        dock: 'bottom',
        items: regresar.concat({xtype: 'spacer'})//.concat(guardar)
    })];

	//agregar este Dock al panel con el formulario agregar cliente
	this.formAgregarCliente.addDocked(this.saveClientTool);
};







/*  ------------------------------------------------------------------------------------------
        Detalles de Cliente
------------------------------------------------------------------------------------------*/
/**
 * Funcion que hablilita/deshabilita las cajas de texto del formulario Detalles del Cliente 
 * {@link ApplicacionClientes#addClientDetailsPanel} .
 * Al llamarse, oculta el boton Modificar, e inserta dos nuevos botones al toolbar: Cancelar y Modificar....
 * Cancelar revierte el proceso que hizo esta funcion y Modificar hace una llamda a {@link ApplicacionClientes#handlerModificarCliente}.
 * @param Button El boton que llamo a esta funcion
 * @return void
 */

ApplicacionClientes.prototype.editClient = function ( btn ){
	
	if(DEBUG){
	    console.log("AppClientes: Editar Cliente", btn);
	}
	
	

    switch(Ext.getCmp('btn_EditCliente').getText()){

        case 'Modificar': 
            //deshabilitar las cajas de texto del formulario y cambia el texto a 'Guardar'
	
            Ext.getCmp('btn_EditCliente').setText("Guardar");
            Ext.getCmp('nombreClienteM').setDisabled(false);    
            Ext.getCmp('direccionClienteM').setDisabled(false);
            Ext.getCmp('rfcClienteM').setDisabled(false);   
            Ext.getCmp('emailClienteM').setDisabled(false);
            Ext.getCmp('telefonoClienteM').setDisabled(false);  
            Ext.getCmp('limite_creditoClienteM').setDisabled(false);
            Ext.getCmp('descuentoClienteM').setDisabled(false);
            //Ext.getCmp('btn_CancelEditCliente').setDisabled(false);
            break;

        case 'Guardar': 
            //habilita las cajas de texto del formulario y cambia el texto a 'Modificar'


			if ( !ApplicacionClientes.currentInstance.handlerModificarCliente() ){
				//si regresa falso, es que algo salio mal, entonces no cambiar 
				// el estado de disabled
	            Ext.getCmp('btn_EditCliente').setText("Modificar");
	            Ext.getCmp('nombreClienteM').setDisabled(true); 
	            Ext.getCmp('direccionClienteM').setDisabled(true);
	            Ext.getCmp('rfcClienteM').setDisabled(true);    
	            Ext.getCmp('emailClienteM').setDisabled(true);
	            Ext.getCmp('telefonoClienteM').setDisabled(true);   
	            Ext.getCmp('limite_creditoClienteM').setDisabled(true);
	            Ext.getCmp('descuentoClienteM').setDisabled(true);				
			}   

            //Ext.getCmp('btn_CancelEditCliente').setDisabled(true);
			
			//se ejecuta el metodo que realiza los cambios en la BD (handlerModificarCliente)

			
            break;
    }

};

/**
 * Funcion que deshabilita las cajas de texto del formulario Detalles del Cliente 
 * {@link ApplicacionClientes#addClientDetailsPanel} y cambia el texto del Boton que 
 * inicialemente dice 'Modificar' (y que en ese momento dice 'Guardar') por 'Modificar'
 * @return void
 */
ApplicacionClientes.prototype.cancelEditClient = function(){
	
    Ext.getCmp('btn_EditCliente').setText("Modificar");
    Ext.getCmp('nombreClienteM').setDisabled(true); 
    Ext.getCmp('direccionClienteM').setDisabled(true);
    Ext.getCmp('rfcClienteM').setDisabled(true);    
    Ext.getCmp('emailClienteM').setDisabled(true);
    Ext.getCmp('telefonoClienteM').setDisabled(true);   
    Ext.getCmp('limite_creditoClienteM').setDisabled(true);
    Ext.getCmp('descuentoClienteM').setDisabled(true);
    //Ext.getCmp('btn_CancelEditCliente').setDisabled(true);
	
};


/*----------------------------------------------------
    Carga el panel de 'Detalles del cliente' 
-------------------------------------------------------*/

/**
 * Funcion que regresa un panel que contiene un Carrusel con 3 cards, una card para contener
 * un formulario con los detalles del cliente (datos del cliente editables), otra card 
 * para ventas hechas por un cliente, y la ultima card para las ventas a credito 
 * (aqui en esta ultima card esta contenida la funcionalidad de pagos).
 * @param {Ext.data.Model} Un record con los datos del cliente seleccionado de la lista de clientes.
 * @return Ext.Panel
 */
ApplicacionClientes.prototype.addClientDetailsPanel = function(  ){

	var record = ApplicacionClientes.currentInstance.clienteSeleccionado ;
	
	//si ya existe el panel, solo editar sus contenidos
	if(ApplicacionClientes.currentInstance.clienteDetallePanel){
		
		//editar los campos y regresar el panel ya modificado
		
		//editar compras
		ApplicacionClientes.currentInstance.listarVentas( record );
		ApplicacionClientes.currentInstance.listarVentasCredito( record );		
		
		//editar los campos de la forma
		Ext.getCmp("idClienteM").setValue(record.id_cliente);
		Ext.getCmp("nombreClienteM").setValue(record.nombre);
		Ext.getCmp("rfcClienteM").setValue(record.rfc);
		Ext.getCmp("direccionClienteM").setValue(record.direccion);
		Ext.getCmp("emailClienteM").setValue(record.e_mail);
		Ext.getCmp("telefonoClienteM").setValue(record.telefono);		
		Ext.getCmp("descuentoClienteM").setValue(record.descuento);				
		Ext.getCmp("limite_creditoClienteM").setValue( POS.currencyFormat(record.limite_credito));
		Ext.getCmp("creditoRestanteClienteM").setValue( POS.currencyFormat(record.credito_restante));
		
		//regresar la forma
		return ApplicacionClientes.currentInstance.clienteDetallePanel;
	}
	
	
	//cuando la forma no existe, crearla, y volver a llamar a esta funcion
	
	regresar = [{
		id: 'btn_BackCliente',
		text: 'Regresar',
		ui: 'back',			
		handler: function(){
			sink.Main.ui.setCard( Ext.getCmp('panelClientes'), { type: 'slide', direction: 'right' } );
		}
	}];


	editar = [{
		id: 'btn_EditCliente',
		text: 'Modificar',
		ui: 'action',
		handler: ApplicacionClientes.currentInstance.editClient
	}];



	if (!Ext.is.Phone) {
		this.dockedItemsFormCliente2 = [ 
			new Ext.Toolbar({
				ui: 'dark',
				dock: 'bottom',
				items:  regresar.concat({xtype:'spacer'}).concat(editar)
			})
		];
	} else {
		this.dockedItemsFormCliente2 = [{
			xtype: 'toolbar',
			ui: 'dark',
			items: this.btnBackCliente2.concat(this.btnCancelEditCliente2).concat(this.btnEditCliente2),
			dock: 'bottom'
		}];
	}
	
	



		
	//crear el formulario de los detalles del cliente
	formaDeDetalles = new Ext.form.FormPanel({                                                       
	title: 'Detalles del Cliente',
	id: 'ClienteUpdateForm',
	baseCls: 'formAgregarCliente',			
	items: [{
		xtype: 'fieldset',
	    title: 'Detalles de Cliente',
		defaults: {
			disabledClass : ''
		},
	    //instructions: 'Todos los campos son obligatorios. Para continuar, necesitara la presencia de un gerente.',
		items: [
				new Ext.form.HiddenField({
			    id: 'idClienteM'
			}),
			new Ext.form.TextField({
			    id: 'nombreClienteM',
			    label: 'Nombre',
			    required: true,
			    disabled: true
			}),
			new Ext.form.TextField({
			    id: 'rfcClienteM',
			    label: 'RFC',
			    required: true,
			    disabled: true
			}),
			new Ext.form.TextField({
			    id: 'direccionClienteM',
			    label: 'Direccion',
			    required: true,
			    disabled: true
			}),
			new Ext.form.TextField({
			    id: 'emailClienteM',
			    label: 'E-mail',
			    disabled: true
			}),
			new Ext.form.TextField({
			    id: 'telefonoClienteM',
			    label: 'Telefono',
			    disabled: true
			}),
			new Ext.form.TextField({
			    id: 'descuentoClienteM',
			    label: 'Descuento',
			    required: false,
			    disabled: true
			}),
			new Ext.form.TextField({
			    id: 'limite_creditoClienteM',
			    label: 'Credito Max',
			    required: false,
			    disabled: true
			}),
			new Ext.form.TextField({
			    id: 'creditoRestanteClienteM',
			    label: 'Credito Restante',
			    required: false,
			    disabled: true
			})
		]}
	]});
		
		//se crea el carrusel con el formulario arriba creado, ademas de las 2 cards que contienen 
		//las ventas y ventas a credtio (estas 2 ultimas cards no contienen nada mas que divs a las
		// que posteriormente se les actualizara codigo HTML)
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

						//Ext.getCmp('btn_CancelEditCliente').setDisabled( true );
						Ext.getCmp('btn_EditCliente').setText("Modificar");
					}
				}
	          
	        }, {

	            scroll: 'vertical',
	            xtype: 'panel',
	            title: 'ventas',
	            id: 'customerHistorial',
	            items: [ {html:'<div id="datosCliente"></div>'}, { html: '<div id="customerHistorialSlide"></div>' }],
	          
	        }, { 
	            scroll: 'vertical',
	            xtype: 'panel',
	            title: 'creditos',
	            id: 'customerCreditHistorial',
	            items: [{html:'<div id="datosClienteCredito"></div>'},{html: '<div id="customerCreditHistorialSlide"></div>'}]
	        }],
			listeners: {
				cardswitch : function( a ){
					
					if( Ext.getCmp("carruselDetallesCliente").getActiveIndex() == 0 ){
						
						//Ext.getCmp('btn_CancelEditCliente').setDisabled( true );
	                    Ext.getCmp('btn_EditCliente').setDisabled( false );
						
					}else{
						
						//Ext.getCmp('btn_CancelEditCliente').setDisabled( true );
	                    Ext.getCmp('btn_EditCliente').setDisabled( true );
						
					}
				}
			}
	    });


    

    ApplicacionClientes.currentInstance.clienteDetallePanel = new Ext.Panel({
	
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

	//ya cree el panel, volver a llamar a esta funcion
	return ApplicacionClientes.currentInstance.addClientDetailsPanel();

};







/*
    Application Logic for modifying clients
*/

/**
 * Funcion que hace una peticion al servidor para realizar los cambios efectuados en los
 * datos de un cliente seleccionado. 
 * @param {String,String,String,String,String,String,String} 7 Cadenas que representan los datos del cliente:
 * Id, RFC, nombre, direccion, telefono, email, limite de credito 
 * @return Ext.Panel
 */
ApplicacionClientes.prototype.handlerModificarCliente = function( ){
    
    
	//validar datos
	campos = Ext.getCmp("ClienteUpdateForm").getValues();
	
	if(campos.nombreClienteM.length < 5){
		Ext.Msg.alert( "Agregar Cliente", "Este nombre es muy corto." );
		return false;
	}
	

	if(campos.direccionClienteM.length < 5){
		Ext.Msg.alert( "Agregar Cliente", "La direccion es muy corta." );
		return false;
	}
	


/*	
	if( campos.limite_creditoClienteM < 0 || campos.limite_creditoClienteM > 50000){
		Ext.Msg.alert( "Agregar Cliente" , "El limite de credito debe ser entre $0.00 y $50,000.00" )
		return false;
	}
*/	

	if(campos.rfcClienteM.length < 5){
		Ext.Msg.alert( "Agregar Cliente", "Este RFC es muy corto." );
		return false;
	}
	

	if(campos.telefonoClienteM.length < 5){
		Ext.Msg.alert( "Agregar Cliente", "Este telefono es muy corto." );
		return false;
	}

	if(campos.emailClienteM.length < 5){
		Ext.Msg.alert( "Agregar Cliente", "Este correo electronico es muy corto." );
		return false;
	}
	
	
	
	
	//se pone una capa negra con el texto Guardando Cambios mientras se procesa la peticion, si el servidor responde rapido esta capa no se alcanza a visualizar.
    Ext.getBody().mask(false, '<div class="demos-loading">Guardando cambios</div>');

   	POS.AJAXandDECODE({
            action: '1002',
			load : Ext.encode ( campos )
        },
        function (datos){
			
            if(datos.success === true){
				if(DEBUG){
                	console.log("AppClientes: Datos guardados correctamente");					
				}
				
				//todo salio bien !
				Ext.Msg.alert("Editar Cliente" , "Los datos se han modificado correctamente.");
				
				//recalcular el credito restante si es que se modifico el maximo credito
				
				
				//actualizar la lista de los clientes
                ApplicacionClientes.currentInstance.updateClientsStore();

            }else{
	
				Ext.Msg.alert("Editar Cliente" , datos.reason);

			  	if(DEBUG){
					console.warn("No se pudieron guardar los datos del cliente");
				}
                  
            }


        },
        function (){//no responde  AJAXanDECODE actualizar
            POS.aviso("ERROR","NO SE PUDO MODIFICAR CLIENTE ERROR EN LA CONEXION ");      
        });//AJAXandDECODE actualizar cliente
		//se quita la capa negra con el texto Guardando Cambios
        Ext.getBody().unmask();

		return true;

};






/*  ------------------------------------------------------------------------------------------
        Buscar Clientes 
------------------------------------------------------------------------------------------*/

/* ---- los comentarios solo son p
 * Se crean Modelos para un Store para así poder filtrar los datos que estan contenidos en el.
 * ES IMPORTANTE SABER QUE PARA QUE SE PUEDA SORTEAR EL CONTENIDO DEL STORE TODOS LOS VALORES
 * QUE ESTEN CARGADOS EN ÉL SEAN DIFERENTES DE NULO O NO SEAN CADENAS VACIAS, DE LO CONTRARIO NO SE CARGARAN
 * LOS DATOS, ES DECIR NO DEBE DE HABER CAMPOS VACIOS.
 * @param {String} Nombre del modelo {Array} Arreglo de cadenas que representan los campos por los q se filtrara
 * @return Ext.regModel
 -----  */







/**
 * Variable de tipo String que indica por que campo se sorteara (ordenara) en la lista de clientes
 * dependiendo del valor de la variable es el filtrado
 */
var apClientes_filtro = 'nombre';

/**
 * Funcion que ordena dentro de la lista de clientes por el campo 'nombre'
 * asignando a la variable apClientes_filtro el valor de "nombre" y refresca 
 * la interfaz grafica de la lista para ver el resultado del filtrado
 * @return void
 */
ApplicacionClientes.prototype.filterByName = function(){
    apClientes_filtro = "nombre";
    Ext.getCmp("listaClientes").refresh();
};
/**
 * Funcion que ordena dentro de la lista de clientes por el campo 'rfc'
 * asignando a la variable apClientes_filtro el valor de "rfc" y refresca 
 * la interfaz grafica de la lista para ver el resultado del filtrado
 * @return void
 */
ApplicacionClientes.prototype.filterByRfc = function(){
    apClientes_filtro = "rfc";
    Ext.getCmp("listaClientes").refresh();
};
/**
 * Funcion que ordena dentro de la lista de clientes por el campo 'direccion'
 * asignando a la variable apClientes_filtro el valor de "direccion" y refresca 
 * la interfaz grafica de la lista para ver el resultado del filtrado
 * @return void
 */
ApplicacionClientes.prototype.filterByDireccion = function(){
    apClientes_filtro = "direccion";
    Ext.getCmp("listaClientes").refresh();
};


/**
 * Es el Store que contiene la lista de clientes cargada con una peticion al servidor.
 * Recibe como parametros un modelo y una cadena que indica por que se va a sortear (ordenar) 
 * en este caso ese filtro es dado por apClientes_filtro
 * @return Ext.data.Store
 */
var ClientesListStore = new Ext.data.Store({
    model: 'ApplicacionClientes_'+apClientes_filtro,
    sorters: apClientes_filtro,
            
    getGroupString : function(record) {
        //console.log("voy a filtrar por: "+apClientes_filtro, record.get(apClientes_filtro));
        return record.get(apClientes_filtro)[0];
    }   
});


/**
 * Funcion que filtra dentro de la lista de clientes por el campo 'nombre'
 * y refresca la interfaz grafica de la lista para ver el resultado del filtrado
 * @return void
 */
ApplicacionClientes.prototype.doSearch = function(  ){
 
	if(DEBUG){
		console.log("Doing search....");
	}
	
    if (Ext.getCmp('btnBuscarCliente').getValue().length === 0){
	
        ClientesListStore.clearFilter();

        try{
        	//ClientesListStore.sync(); //marca error pero si lo meto en try catch o no lo llamo la vista no coincide con el store
        }catch(e){
			console.warn("Error:  "+e);
		}

	}else{
		
		if(DEBUG){
			//console.log( "AppCliente Buscando " + Ext.getCmp('btnBuscarCliente').getValue() );
		}
        try{
        	ClientesListStore.filter('nombre', Ext.getCmp('btnBuscarCliente').getValue()  );
			//ClientesListStore.sync();
        }catch(e){
			console.warn("Error "+e);
		}		
		
	}
        
};




/**
 * Regresa la mainCard de la aplicacion Clientes, contiene la lista de clientes dados de
 * alta en el sistema, tiene como elemento principal una lista y una Toolbar 
 * {@link ApplicacionClientes#dockedItems} , manda una peticion al servidor para llenar
 * la lista en el evento beforeshow del panel, y en el evento selectionchange de la lista
 * le asigna valor a {@link ApplicacionClientes#clienteSeleccionado} y ejecuta los metodos
 * {@link ApplicacionClientes#addClientDetailsPanel} , {@link ApplicacionClientes#listarVentas} y 
 * {@link ApplicacionClientes#listarVentasCredito} .
 * @return Ext.Panel
 */
ApplicacionClientes.prototype.ClientesList = new Ext.Panel({
        id: 'panelClientes',
        layout: Ext.is.Phone ? 'fit' : {
            type: 'vbox',
            align: 'left',
            pack: 'center'
        },
        listeners: {
            beforeshow : function(component){

                Ext.getBody().mask(false, '<div class="demos-loading">Loading&hellip;</div>');
                ApplicacionClientes.currentInstance.updateClientsStore();
                Ext.getBody().unmask();
            },
			show : function (){
				//cambiar el tamano de la lista segun el form
				Ext.getCmp('panelClientesList').setHeight( Ext.getCmp('panelClientes').getHeight() );
			}
        },
        items: [{
					id: 'panelClientesList',
					width : '100%',
		            height: 500,
		            xtype: 'list',
		            store: ClientesListStore,
		            tpl: '<tpl for="."><div class="contact"><strong>{nombre}</strong> {rfc} {direccion}</div></tpl>',
	            	itemSelector: 'div.contact',
		            singleSelect: true,
		            grouped: true,
		            indexBar: true,
					listeners: {
						selectionchange: function(){

							if (this.getSelectionCount() == 1) {

								ApplicacionClientes.currentInstance.clienteSeleccionado = this.getSelectedRecords()[0].data;

								if(DEBUG){
									console.log("Seleccionano cliente", recor  );
								}

								//La funcion addClientDetailsPanel regresa un panel con el carrusel de 3 cards y con los datos
								// del cliente que este en ApplicacionClientes.currentInstance.clienteSeleccionado
								var detalles = ApplicacionClientes.currentInstance.addClientDetailsPanel( ); 

								//Se desliza para mostrar el panel que contiene el carrusel con las 3 cards (detalles del cliente, ventas al cliente, ventas a credito al cliente).
								sink.Main.ui.setCard( detalles , 'slide');
							}


						}
					}//fin listener
        }]

});



/*  ------------------------------------------------------------------------------------------
         Nuevo Cliente
------------------------------------------------------------------------------------------*/
/**
 * Regresa un formulario vacio con los campos necesarios para dar de alta 
 * en el sistema a un nuevo cliente.
 * @return Ext.form.FormPanel
 */
ApplicacionClientes.prototype.formAgregarCliente  = new Ext.form.FormPanel({
        scroll: 'vertical',
        id:'formAgregarCliente', 
		scroll: false,
		baseCls: 'formAgregarCliente',
        items: [{
            xtype: 'fieldset',
            title: 'Informacion de nuevo cliente',
            instructions: 'Todos los campos son obligatorios. Para continuar, necesitara la presencia de un gerente.',
            items: [
                    nombreCliente = new Ext.form.TextField({
                        id: 'nombreCliente',
                        label: 'Nombre'
                    }),
                    rfcCliente = new Ext.form.TextField({
                        id: 'rfcCliente',
                        label: 'RFC'
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
                    limite_creditoCliente = new Ext.form.TextField({
                        id: 'limite_creditoCliente',
                        label: 'Max Credito'
                    })
                ]},

                {
                	xtype: 'button',
                    id: 'guardarCliente',
                    text: 'Guardar',
                    ui: 'action',
                    handler: function(event,button) {
	
						//validar campos
						campos = Ext.getCmp("formAgregarCliente").getValues();
						
						if(campos.nombreCliente.length < 5){
							Ext.Msg.alert( "Agregar Cliente", "Este nombre es muy corto." );
							return;
						}
						

						if(campos.direccionCliente.length < 5){
							Ext.Msg.alert( "Agregar Cliente", "La direccion es muy corta." );
							return;
						}
						

						var n = campos.limite_creditoCliente;
						if( !(n.length > 0 && !(/[^0-9]/).test(n) )){
							Ext.Msg.alert( "Agregar Cliente" , "El limite de credito debe ser un numero entero." )
							return;								
						}
						
						if( campos.limite_creditoCliente < 0 || campos.limite_creditoCliente > 50000){
							Ext.Msg.alert( "Agregar Cliente" , "El limite de credito debe ser entre $0.00 y $50,000.00" )
							return;
						}
						

						if(campos.rfcCliente.length < 5){
							Ext.Msg.alert( "Agregar Cliente", "Este RFC es muy corto." );
							return;
						}
						

						if(campos.telefonoCliente.length < 5){
							Ext.Msg.alert( "Agregar Cliente", "Este telefono es muy corto." );
							return;
						}

						if(campos.emailCliente.length < 5){
							Ext.Msg.alert( "Agregar Cliente", "Este correo electronico es muy corto." );
							return;
						}

						//campos validos, revisar si soy gerente, si no lo soy entonces pedir autorizacion
						
						
						
                        Ext.getBody().mask(false, '<div class="demos-loading">Loading&hellip;</div>');


						//guardar los datos
                        POS.AJAXandDECODE({
                                action: '1001',
                                rfc: rfcCliente.getValue(),
                                nombre: nombreCliente.getValue(),
                                direccion: direccionCliente.getValue(),
                                telefono: telefonoCliente.getValue(),
                                e_mail: emailCliente.getValue(),
                                limite_credito: limite_creditoCliente.getValue()
                        },
                        function (datos){
							//responded !
                        	if(datos.success === true){
								Ext.Msg.alert("Agregra Cliente", "El cliente se ha creado exitosamente.");
									
								//limpiar los campos
                                rfcCliente.setValue('');
                                nombreCliente.setValue('');
                                direccionCliente.setValue('');
                                telefonoCliente.setValue('');
                                emailCliente.setValue('');
                                limite_creditoCliente.setValue('');
                                 
								ApplicacionClientes.currentInstance.updateClientsStore();
																		
                             }else{
                                    //algo salio mal
								Ext.Msg.alert("Agregra Cliente", "Porfavor intente de nuevo...");
                             }
                                 
                        },
                        function (){//no responde       
                        	POS.aviso("ERROR","NO SE PUDO INSERTAR CLIENTE, ERROR EN LA CONEXION");      
                        }); //AJAXandDECODE insertar cliente

 						Ext.getBody().unmask();
                         
                        
                                        
                                        
                    }//fin handler
        
                }//fin boton
               
        ]
});




ApplicacionClientes.prototype.updateClientsStore = function (){
	
	if(DEBUG){
		console.log("Actualizando lista de clientes ....");
	}
	
	POS.AJAXandDECODE({
		action: '1005'
	},
	function (datos){
		ClientesListStore.loadData(datos.datos); 
	},
	function (){
		if(DEBUG){
			console.log("Failed to update clients from server ! Reintentando.... ");
		}
		
		//volver a intentar
		ApplicacionClientes.currentInstance.updateClientsStore();
		
	});
};


/*------------------------------------------------------------------
            VENTAS EMITIDAS A UN CLIENTE 
------------------------------------------------------------------------*/
/**
 * Funcion que dado un cliente enlista las ventas hechas a un cliente ya sean de contado o a credito.
 * Con la informacion que proporciona el servidor se actualiza con html generado al vuelo la segunda 
 * card del carrusel contenido en {@link ApplicacionClientes#addClientDetailsPanel} 
 * mostrando asi el estado de cada venta del cliente. Dado el estado de la venta se
 * evalua si se puede facturar o no.
 * @param {Ext.data.Model} El cliente seleccionado en la lista {@link ApplicacionClientes#clienteSeleccionado}
 * @return void
 */
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
		//Se carga el store con los datos enviados del servidor
		ventasCliente.loadData(datos.datos);
        //Cabecera en HTML del panel           
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

		//Generar el html, si hay mas de una venta se genera el html que contendra los datos y el estado
		//de cada venta hecha al cliente.
		for( a = 0; a < ventasCliente.getCount(); a++ ){

			var facturado="";
			//Se evalua si la venta esta facturada de ser asi, se la el estilo y se le da el texto de Facturada
			if ( ventasCliente.data.items[a].data.facturado == 1 ){
				facturado="<div class='pagado'>Facturada</div>";
			}
			//Si no esta facturada se debe evaluar si fue de contado o adeuda a esta venta
			if ( ventasCliente.data.items[a].data.facturado == 0 ){

				vtaClteTotal = parseFloat(ventasCliente.data.items[a].data.total); 
				vtaCltePagado = parseFloat(ventasCliente.data.items[a].data.pagado);
				//Si adeuda se le da el estilo y se le da el texto de Adeuda, de lo contrario se genera un evento
				//al texto Facturar para que pueda Facturar esa venta.
				if(  vtaClteTotal > vtaCltePagado ){
					facturado = "<div class='abonar'>Adeuda</div>";
				}else{
					//Se genera el evento sobre el texto Facturar y se llama ala funcion 
					//{@link ApplicacionClientes#panelFacturas} para que muestre la interfaz para hacerlo.
					facturado ="<div class='abonar' onclick='ApplicacionClientes.currentInstance.panelFacturas(" + ventasCliente.data.items[a].data.id_venta + " , "+ ventasCliente.data.items[a].data.id_cliente +")'>Facturar</div>";
				}
			}
			//Se genera el contenido en html de los datos de cada venta, en cada venta se genera una div con 
			//fondo una imagen para que se pueda visualizar los detalles de esa venta {@link ApplicacionClientes#verVenta} mediante el ID de la venta.
			html += "<div class='ApplicationClientes-Item' >" 
				+ "<div class='trash' onclick='ApplicacionClientes.currentInstance.verVenta(" +ventasCliente.data.items[a].data.id_venta+ ")'><img height=20 width=20 src='sencha/resources/themes/images/default/pictos/search.png'/></div>" 
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


		//Si no hay ventas solo se muestra un mensaje estilizado al centro del panel
		if(!datos.success){
			html="<div class=\"no-data\">Este cliente no ha hecho ninguna compra.</div>";
		}

		//Se actualiza la segunda card del carrusel con el codigo HTML generado
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
/**
 * Funcion que dado el ID de la venta extrae los detalles de la misma y los
 * muestra sombreando la pantalla y colocando un panel en el centro con los items de la venta.
 * @param {Ext.data.Model}
 * @return Ext.Panel
 */
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
                        
                        text: 'Imprimir comprobante',
                        handler: function() {
                            	//imprimir ticket
							   alert("esto falta");
                            }
                        },{
                        
                        text: 'Cerrar',
                        handler: function() {
                            //Elimina el formulario actual para regresar a la lista de ventas
                             Ext.getCmp("detalleVentaPanel").destroy();
                             Ext.getBody().unmask();
                                                   
                            }
                        }]
                    }]
            });
    
       


    if (Ext.is.Phone) {
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
        
        POS.AJAXandDECODE({
            action: '1402',
            id_venta: idVenta
            },
            function (datos){
		
                if(datos.success){
					//generar html
                    var html = "";

                    html += "<table>"
					+ "<tr><td>ID Venta</td><td>"+ datos.id_venta +"</td></tr>"
					+ "<tr><td>ID Cliente</td><td>"+ datos.id_cliente +"</td></tr>"
					+ "<tr><td>Tipo Venta</td><td>"+datos.tipo_venta+"</td></tr>"
					+ "<tr><td>Fecha</td><td>"+datos.fecha+"</td></tr>"
					+ "<tr><td>Vendedor</td><td>"+datos.vendedor+"</td></tr>"
					+ "</table>";


                    html += "<div class='ApplicationClientes-Item' >" 
                    + "<div class='vendedor'>Producto</div>"
                    + "<div class='vendedor'>Denominacion</div>"
                    + "<div class='sucursal'>Cantidad</div>" 
                    + "<div class='subtotal'>Precio</div>" 
                    + "<div class='subtotal'>Subtotal</div>"
                    + "</div>";
                     
  					
                    for( a = 0; a < datos.items.length; a++ ){
                        html += "<div class='ApplicationClientes-Item' >" 
                        + "<div class='vendedor'>" + datos.items[a].id_producto +"</div>" 
                        + "<div class='vendedor'>" + datos.items[a].denominacion +"</div>" 
                        + "<div class='sucursal'>"+ datos.items[a].cantidad +"</div>" 
                        + "<div class='subtotal'> "+ POS.currencyFormat( datos.items[a].precio ) +"</div>"
                        + "<div class='subtotal'> "+ POS.currencyFormat(datos.items[a].precio*datos.items[a].cantidad) +"</div>"
                        + "</div>";
                    }

					
                    html += "<table>"
					+ "<tr><td>Subtotal</td><td>"+ POS.currencyFormat(datos.subtotal) +"</td></tr>"
					+ "<tr><td>Descuento</td><td>"+ POS.currencyFormat(datos.descuento) +"</td></tr>"
					+ "<tr><td>IVA</td><td>"+datos.iva+"</td></tr>"
					+ "<tr><td>Total</td><td>"+POS.currencyFormat(datos.total)+"</td></tr>"
					+ "</table>";

                    //Se actualiza la parte de este formulario con el HTML generado
                    Ext.get("detalleVentaCliente").update("<div class='ApplicationClientes-itemsBox'>" + html +"</div>");
                        
                }//FIN DATOS.SUCCES TRUE MOSTRAR CLIENTE
                if(!datos.success){
					Ext.Msg.alert("Porfavor intente de nuevo.");
                    return;
                }
            },
            function (){//no responde  AJAXDECODE MOSTRAR CLIENTE     
				Ext.Msg.alert("Porfavor intente de nuevo.");
                return;
            }
        );//AJAXandDECODE MOSTRAR CLIENTE                       
    
    formBase.show();
	
    
	//Se le da un fondo al panel contenedor
	
	Ext.get("detalleVentaCliente").parent().parent().setStyle({
					'background-image':'url("media/g3.png")'								   
	});
};


/* -------------------------------------------------------------
        MUESTRA LAS VENTAS A CREDITO DE UN CLIENTE
---------------------------------------------------*/

/**
 * Funcion que dado un cliente enlista las ventas a credito hechas a un cliente.
 * Con la informacion que proporciona el servidor se actualiza con html generado al vuelo la tercera 
 * card del carrusel contenido en {@link addClientDetailsPanel} mostrando asi el estado de cada venta
 * del cliente. Dado el estado de la venta se evalua si se puede abonar o no.
 * @param {Ext.data.Model}
 * @return void
 */
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
					//Carga el store con las ventas a credito
                    ventasClienteCredito.loadData(datos.datos);

					//Cabecera del panel que contendra las ventas a credito
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
                    
                    //Genera el html de cada venta a credito dando la opcion de ver los detalles de cada
					//venta llamando al metodo {@link ApplicacionClientes#verVenta} , asi como Abonar a las
					//ventas que aun se adeuden mediante el metodo {@link ApplicacionClientes#abonarVenta}.
					//Tambien se podran ver los pagos que se hayan hecho a cada venta mediante {@link ApplicacionClientes#verPagosVenta}
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
                        + "<div class='trash' onclick='ApplicacionClientes.currentInstance.verVenta(" + ven.data.id_venta+ ")'><img height=20 width=20 src='sencha/resources/themes/images/default/pictos/search.png'></div>"   
                        + "<div class='id'>" + ven.data.id_venta +"</div>" 
                        + "<div class='fecha'>"+ ven.data.fecha +"</div>" 
                        + "<div class='sucursal'>"+ ven.data.sucursal +"</div>"
                        + "<div class='vendedor'>"+ ven.data.vendedor +"</div>"
                        + "<div class='total'>"+ POS.currencyFormat(ven.data.total) +"</div>"
                        + "<div class='total'>"+ POS.currencyFormat(ven.data.abonado) +"</div>"
                        + "<div class='total'>"+ POS.currencyFormat(ven.data.adeudo) +"</div>"
                        + "<div class='total' style='height:15px;'  onclick='ApplicacionClientes.currentInstance.verPagosVenta(" + ven.data.id_venta+ ")'><img height=20 width=20 src='sencha/resources/themes/images/default/pictos/compose.png'></div>"
                        + status
                        + "</div>";
                                                        
                        
                    }//fin for ventasClienteCredito
        
                    //imprimir el html
                    
                    //console.log(ventasCliente.data.items);
                }
				//Si no hay ventas a credito muestra un mensaje.
                if(!datos.success){
                    
					html="<div class=\"no-data\">Este cliente no ha hecho ninguna compra a credito.</div>";
                }
				//Se actualiza la tercera card del carrusel con el html generado
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
/**
 * Funcion que dado el ID de la venta extrae los pagos hechos a la misma y los
 * muestra sombreando la pantalla y colocando un panel en el centro con los items de la venta.
 * @param {String} Id de la venta
 * @return void
 */
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
                        
                        text: 'Cerrar',
                        handler: function() {
                            //Elimina este panel
                            Ext.getCmp("pagosVentaPanel").destroy();
                             Ext.getBody().unmask();
                                                      
                            }
                        }]
                    }]
            });
    
       


    if (Ext.is.Phone) {
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
                    //Carga el Store con los pagos de la venta
                    ventasDetalle.loadData(datos.datos);
                    
					//Cabecera del panel que contendra los pagos del venta
                    var html = "";
                    html += "<div class='ApplicationClientes-Item' >" 
                    + "<div class='vendedor'># DE VENTA</div>" 
                    + "<div class='sucursal'>FECHA</div>" 
                    + "<div class='subtotal'>MONTO</div>"
                    + "<div class='subtotal'></div>"
                    + "</div>";
                                
                    for( a = 0; a < ventasDetalle.getCount(); a++ ){
                        //Se genera el HML con cada 1 de los pagos hechos a la venta                   
                        html += "<div class='ApplicationClientes-Item' id='pago_Borrar_"+ventasDetalle.data.items[a].data.id_pago+"'>" 
                        + "<div class='vendedor'>" + ventasDetalle.data.items[a].data.id_venta +"</div>" 
                        + "<div class='sucursal'>"+ ventasDetalle.data.items[a].data.fecha +"</div>" 
                        + "<div class='subtotal'>$ "+ ventasDetalle.data.items[a].data.monto+"</div>"
                        + "<div class='abonar' onclick='ApplicacionClientes.currentInstance.EliminarabonoVenta(" +  ventasDetalle.data.items[a].data.id_pago +")'>ELIMINAR</div>"
                        + "</div>";
                    }
                                
                    //Se actualiza con el html generado el panel
                    Ext.get("pagosVentaCliente").update("<div class='ApplicationClientes-itemsBox'>" + html +"</div>");
                        
                }//FIN DATOS.SUCCES TRUE MOSTRAR CLIENTE
				
				//Si no hay pagos muestra un mensaje
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
	
	
	//Se le da un fondo al panel contenedor
	
	Ext.get("pagosVentaCliente").parent().parent().setStyle({
					'background-image':'url("media/g3.png")'								   
	});
    
};




/*------------------------------------------------------------
    ABONAR A UNA VENTA QUE EL CLIENTE ADEUDA
--------------------------------------------------------------*/
/**
 * Funcion que dado el ID de la venta, el total de la venta y lo que se adeda de la misma
 * muestra sombreando la pantalla un panel en el centro con un formulario para poder
 * abonar a esa venta, mostrando asi informacion del abono y los totales de la venta.
 * @param {String} el id de la venta
 * @param {String} el total de la venta
 * @param {String} lo que se deuda a la venta
 * @return void
 */
ApplicacionClientes.prototype.abonarVenta = function( idVenta , total , adeudo ){
    //Cabecera del Panel
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
                                        	//Se manda llamar al metodo que enlista las ventas a credito para que refresque la informacion debido al abono insertado
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
    
       


    if (Ext.is.Phone) {
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
        
           
     //Se muestra el panel con la forma para abonar           
    abonaPanel.show();
    
	
	//Se le da un fono al panel contenedor
	
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

/**
 * Funcion que dado el ID de un pago lo elimina de la BD y actualiza los datos de esa venta
 * para refrescar asi principalemente el campo Adeuda.
 * @param {String} El id del pago
 * @return void
 */
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
            width: Ext.is.Phone ? 260 : 400,
            height: Ext.is.Phone ? 115 : 210,
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
/**
 * Funcion que dado el ID de la venta y del cliente seleccionado crea una 
 * instancia de la clase ApplicationFacturaVentas para poder Deslizar un panel 
 * con los datos del cliente y de la venta a facturar.
 * @param {String} El id de la vente
 * @param {String} El id del clienet
 * @return void
 */
ApplicacionClientes.prototype.panelFacturas = function( id_venta , id_cliente ){
	//Si no hay una instancia de ApplicationFacturaVentas la crea
	if( !ApplicacionClientes.currentInstance.facturaObj ){
		
		ApplicacionClientes.prototype.facturaObj = new ApplicationFacturaVentas();
	}
		
		
		//Con el id de la venta y del cliente se ejecuta el metodo facturaPanel del objeto
		//anteriormente instanciado (facturaObj), el metodo facturarPanel le asigna un panel a la propiedad
		//facturaVenta del objeto anteriormente instanciado (facturaObj) con el html rendereado para poder
		// facturar una venta
		
		ApplicacionClientes.currentInstance.facturaObj.facturarPanel( id_venta, id_cliente );
		
		//Se desliza la propiedad facturarVenta del objeto facturaObj (esa propiedad es un panel)
		sink.Main.ui.setCard(  ApplicacionClientes.currentInstance.facturaObj.facturaVenta, 'slide' );
		
		//Se le da fondo alos datos del cliente
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