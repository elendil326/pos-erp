
/**
 * @constructor
 * @throws MemoryException Si se agota la memoria
 * @return Un objeto del tipo Autorizaciones
 */
Aplicacion.Autorizaciones = function (  ){

	return this._init();
}




Aplicacion.Autorizaciones.prototype._init = function (){

    if(DEBUG){
		console.log("Autorizaciones: construyendo");
    }
    
    Aplicacion.Autorizaciones.currentInstance = this;
	
	//funcion que crea los paneles para las nuevas autorizaciones
	this.nueva.createPanels();
	
	//crear la el panel con la lista de autorizaciones
	this.listaDeAutorizacionesPanelCreator();

	//obtener autorizaciones actuales
	this.listaDeAutorizacionesLoad();
	
	
	
	
	return this;
};




Aplicacion.Autorizaciones.prototype.getConfig = function (){
	return {
	    text: 'Autorizaciones',
	    cls: 'launchscreen',
        card: Aplicacion.Autorizaciones.currentInstance.listaDeAutorizacionesPanel,	
	    items: [{
		    text: 'Nueva Autorizacion',
			items: [{
		        text: 'Limite de Credito',
	        	card: Aplicacion.Autorizaciones.currentInstance.nueva.creditoPanel,
	        	leaf: true
		    },{
		        text: 'Venta Preferencial',
	        	card: Aplicacion.Autorizaciones.currentInstance.nueva.ventaPreferencialPanel,
	        	leaf: true
		    }, /*{
	        	text: 'Merma de producto',
	        	card: Aplicacion.Autorizaciones.currentInstance.nueva.mermaPanel,
		        leaf: true
		    }, */ {
	        	text: 'Devoluciones',
	        	card: Aplicacion.Autorizaciones.currentInstance.nueva.devolucionesPanel,
		        leaf: true
		    }]
	    },
	    {
	        text: 'Autorizaciones de hoy',
	        card: Aplicacion.Autorizaciones.currentInstance.listaDeAutorizacionesPanel,
	        leaf: true
	    }]
	};
};










/* ***************************************************************************
   * Nueva autorizacion
   * 
   *************************************************************************** */

Aplicacion.Autorizaciones.prototype.nueva = {};

//funcion que manda llamar a las funciones que crean los paneles
Aplicacion.Autorizaciones.prototype.nueva.createPanels = function ()
{
	//cargar todos los paneles de nuevos
	
	this.devolucionesPanelCreator();
	
	this.creditoPanelCreator();
	
	this.mermaPanelCreator();
	
	this.ventaPreferencialPanelCreator();
	
};



/* ********************************************************
*    Venta preferencial
******************************************************** */

//panel de la venta preferencial
Aplicacion.Autorizaciones.prototype.nueva.ventaPreferencialPanel = null;


/**
    *
    *
    */
Aplicacion.Autorizaciones.prototype.nueva.ventaPreferencialPanelCreator = function()
{






    clientesPanel = {
        xtype: 'fieldset',
        title: 'Autorización de Venta Preferencial',
        //autoRender: true,
        instructions:'Seleccione un cliente de la lista.',
        listeners:{
            'show':function(){
                //TODO: verificar que esto solo se haga una sola vez
                if( !Aplicacion.Mostrador.currentInstance.buscarClienteForm.getComponent(0).getStore() )
                {
                    Aplicacion.Mostrador.currentInstance.buscarClienteForm.getComponent(0).bindStore(Aplicacion.Clientes.currentInstance.listaDeClientesStore);
                }
            }
        },
		centered: true,
		items: [{
			
			width : '100%',
			height: 345,
			xtype: 'list',
			store: Aplicacion.Clientes ? Aplicacion.Clientes.currentInstance.listaDeClientesStore : null ,
			itemTpl: '<div class="listaDeClientesCliente"><strong>{nombre}</strong> {rfc}</div>',
			grouped: true,
			indexBar: true,
			listeners : {
				"selectionchange"  : function ( view, nodos, c ){				
					
					if(nodos.length > 0){
						//Aplicacion.Mostrador.currentInstance.clienteSeleccionado( nodos[0].data );
						//console.log(nodos[0].data)
						Ext.getCmp('Autorizaciones-ventaPreferencial-cliente').setValue( nodos[0].data.nombre );
						Ext.getCmp('Autorizaciones-ventaPreferencial-clienteID').setValue( nodos[0].data.id_cliente );
						
					}

					//deseleccinar el cliente
					view.deselectAll();
				}
			}
			
		}]
        
    
    };
    
    this.ventaPreferencialPanel = new Ext.Panel({
        title:'Autorización de Venta Preferencial',        
        items:[
            clientesPanel,
            {
                xtype: 'fieldset',
                title: 'Nombre del Cliente',        
                name:'nombre',
                items:[ 
                    new Ext.form.Text({ id: 'Autorizaciones-ventaPreferencial-cliente', label: 'Cliente' }),     
                    new Ext.form.Hidden({ id: 'Autorizaciones-ventaPreferencial-clienteID', value : "" }) 
                ]
            },
            {xtype: 'spacer'},
            new Ext.Button({ id : 'Autorizaciones-ventaPreferencial-action', ui  : 'action', text: 'Solicitar Autorización',  handler : this.ventaPreferencialPanelValidator })
        ]
    });
    
};



/**
    *
    *
    */
Aplicacion.Autorizaciones.prototype.nueva.ventaPreferencialPanelShow = function( cliente )
{

    //crear este panel on-demand ya que no sera muy utilizado
    if( !this.ventaPreferencialPanel ){
        this.ventaPreferencialPanelCreator();
    }
    
    sink.Main.ui.setActiveItem( this.ventaPreferencialPanel, 'slide');

};


/**
    *
    *
    */
Aplicacion.Autorizaciones.prototype.nueva.ventaPreferencialPanelValidator = function(){

    var cliente = { id_cliente : null, nombre : null };

    cliente.id_cliente = Ext.getCmp('Autorizaciones-ventaPreferencial-clienteID').getValue();
    
    cliente.nombre = Ext.getCmp('Autorizaciones-ventaPreferencial-cliente').getValue();

    if( cliente.id_cliente== "" )
    {
        Ext.Anim.run(Ext.getCmp( 'Autorizaciones-ventaPreferencial-cliente' ), 
            'fade', {duration: 250,
            out: true,
            autoClear: true
        });        
        
        return;
    }


    Aplicacion.Autorizaciones.currentInstance.nueva.autorizacionVentaPreferencial( cliente );

};



/**
    *
    *
    */
Aplicacion.Autorizaciones.prototype.nueva.autorizacionVentaPreferencial = function ( cliente ){

    Ext.getBody().mask('Enviando autorizacion de venta preferencial', 'x-mask-loading', true);
    
	if(DEBUG){
		console.log("Enviando autorizacion de venta preferencial");
	}
	
    Ext.Ajax.request({
        url: '../proxy.php',
        scope : this,
        params : {
            action : 204,
            id_cliente : cliente.id_cliente,
            nombre : cliente.nombre
        },
        success: function(response, opts) {
            try{
                r = Ext.util.JSON.decode( response.responseText );
            }catch(e){
                return POS.error(response, e);
            }

            Ext.getBody().unmask(); 

            
            //informamos lo que sucedio
            if( r.success == true )
            {
                Ext.Msg.alert("Autorizacion","Se ha enviado su autorización"); 
                sink.Main.ui.setActiveItem( Aplicacion.Autorizaciones.currentInstance.listaDeAutorizacionesPanel , 'fade');
            
            }
            else
            {
                Ext.Msg.alert("Autorizacion","Error: " + r.success); 
            }

        },
        failure: function( response ){
            POS.error( response );
        }
    }); 

};


/* ********************************************************
*    Extender credito
******************************************************** */

Aplicacion.Autorizaciones.prototype.nueva.creditoModificarPanel = null;

Aplicacion.Autorizaciones.prototype.nueva.creditoModificarPanelShow = function( cliente )
{

    //crear este panel on-demand ya que no sera muy utilizado
    if(!this.creditoModificarPanel){
        this.creditoModificarPanelCreator();
    }

    //poner los valores del cliente en la forma
    Ext.getCmp("Autorizaciones-CreditoIdCliente").setValue(cliente.data.id_cliente);
    Ext.getCmp("Autorizaciones-CreditoCliente").setValue(cliente.data.nombre);
    Ext.getCmp("Autorizaciones-CreditoActual").setValue( POS.currencyFormat( cliente.data.limite_credito) );
    Ext.getCmp("Autorizaciones-CreditoUsado").setValue( POS.currencyFormat( cliente.data.limite_credito - cliente.credito_restante ) );
    
    sink.Main.ui.setActiveItem( this.creditoModificarPanel , 'slide');
};


Aplicacion.Autorizaciones.prototype.nueva.nuevoCreditoModificar = function( data ){

    Ext.getBody().mask('Enviando autorizacion de credito', 'x-mask-loading', true);
    
	if(DEBUG){
		console.log("Enviando autorizacion de credito extendido", data);
	}
	
    Ext.Ajax.request({
        url: '../proxy.php',
        scope : this,
        params : {
            action : 202,
            id_cliente : data.id_cliente,
            cantidad : data.limite
        },
        success: function(response, opts) {
            try{
                r = Ext.util.JSON.decode( response.responseText );
            }catch(e){
                return POS.error(response, e);
            }

            Ext.getBody().unmask(); 

            //limpiar la forma      
            Aplicacion.Autorizaciones.currentInstance.nueva.creditoModificarPanel.reset();

            //informamos lo que sucedio
            if( r.success == true )
            {
                Ext.Msg.alert("Autorizacion","Se ha enviado su autorización"); 
            }
            else
            {
                Ext.Msg.alert("Autorizacion","Error: " + r.success); 
            }

        },
        failure: function( response ){
            POS.error( response );
        }
    }); 

};


Aplicacion.Autorizaciones.prototype.nueva.nuevoCreditoModificarValidator = function(){

    var values = Aplicacion.Autorizaciones.currentInstance.nueva.creditoModificarPanel.getValues();

    if( !( values.limite && /^-?\d+(\.\d+)?$/.test(values.limite + '') ) ){

        Ext.Anim.run(Ext.getCmp( 'Autorizaciones-CreditoNuevo' ), 
            'fade', {duration: 250,
            out: true,
            autoClear: true
        });

        return;
    }

    Aplicacion.Autorizaciones.currentInstance.nueva.nuevoCreditoModificar( values );

};


Aplicacion.Autorizaciones.prototype.nueva.creditoModificarPanelCreator = function()
{
    this.creditoModificarPanel = new Ext.form.FormPanel({                                                       
            items: [{
                xtype: 'fieldset',
                title: 'Solicitud de limite de credito extendido',
                instructions: 'Ingrese el nuevo limite de credito para este cliente.',
                items: [
                    new Ext.form.Hidden({ id: 'Autorizaciones-CreditoIdCliente', name: 'id_cliente'}),
                    new Ext.form.Text({ id: 'Autorizaciones-CreditoCliente', label: 'Cliente', disabled : true }),
                    new Ext.form.Text({ id: 'Autorizaciones-CreditoActual', label: 'Actual', disabled : true }),
                    new Ext.form.Text({ id: 'Autorizaciones-CreditoUsado', label: 'Usado', disabled : true }),  
                    new Ext.form.Text({ id: 'Autorizaciones-CreditoNuevo', label: 'Nuevo limite', name:'limite',  required:true,
                    listeners : {
                        'focus' : function (){
                                kconf = {
                                type : 'num',
                                submitText : 'Aceptar',
                                callback : Aplicacion.Efectivo.currentInstance.nuevoGastoValidator
                            };
                        POS.Keyboard.Keyboard( this, kconf );
                        }
                    } })
            ]},
            new Ext.Button({ ui  : 'action', text: 'Pedir autorizacion', margin : 5,handler : this.nuevoCreditoModificarValidator  })
        ]});
};




Aplicacion.Autorizaciones.prototype.nueva.creditoPanel = null;

Aplicacion.Autorizaciones.prototype.nueva.creditoPanelCreator = function()
{
    this.creditoPanel = new Ext.form.FormPanel({                                                       
            items: [{
                xtype: 'fieldset',
                title: 'Limite de credito extendido',
                instructions: 'Seleccione al cliente al que desea extenerle el credito',
                items: [{
                    width : '100%',
                    height : 350, 
                    padding : 2,
                    xtype: 'list',
                    store: Aplicacion.Clientes.currentInstance.listaDeClientesStore,
                    itemTpl: '<div class="listaDeClientesCliente"><strong>{nombre}</strong> {rfc}</div>',
                    grouped: true,
                    indexBar: true,
                    listeners : {
                        "selectionchange"  : function ( view, nodos, c ){
                    
                                if(nodos.length > 0){
                                    //poner los valores del cliente en la forma
                                    Aplicacion.Autorizaciones.currentInstance.nueva.creditoModificarPanelShow( nodos[0] );
                                }

                                //deseleccinar el cliente
                                view.deselectAll();
                        }
                    }
                }
            ]}      
        ]});
};




/* ********************************************************
    Reportar merma
******************************************************** */


//ingresa la solicitud de la autorizaciond e la merma a la BD
Aplicacion.Autorizaciones.prototype.solicitudAutorizacionMerma = function( values ){

    Ext.Ajax.request({
        url: '../proxy.php',
        scope : this,
        params : {
            action : 205,
            data: Ext.util.JSON.encode( values )
        },
        success: function(response, opts) {
            try{
                r = Ext.util.JSON.decode( response.responseText );
            }catch(e){
                return POS.error(response, e);
            }

            Aplicacion.Autorizaciones.currentInstance.panelSolicitudMerma.hide('pop');

            if(!r.success)
            {
                Ext.Msg.alert("Autorizaciones","Error: " + r.reason);
            }

            Ext.Msg.alert("Autorizaciones","Solicitud enviada con exito.");

            //Ext.getBody().unmask(); 

        },
        failure: function( response ){
            POS.error( response );
        }
    }); 
}

//valida los datos del formulario de solicitarMermaCompraPanel
Aplicacion.Autorizaciones.prototype.solicitarMermaCompraPanelValidator = function()
{
    values = Aplicacion.Autorizaciones.currentInstance.solicitarMermaCompraPanel.getValues();

    //verificamos que el id_compra sea un numero entero
    if( !( values.cantidad && /^\d+$/.test(values.cantidad + '') ) ){

        Ext.Anim.run(Ext.getCmp( 'Autorizacion-MermaCompraPanel-cantidad' ), 
            'fade', {duration: 250,
            out: true,
            autoClear: true
        });

        return;
    }

     if( values.cantidadOriginal < values.cantidad ){

        Ext.Anim.run(Ext.getCmp( 'Autorizacion-MermaCompraPanel-cantidad' ), 
            'fade', {duration: 250,
            out: true,
            autoClear: true
        });

        return;
    }

    //ya que se esta seguro de que la informacion es correcta se envia la informacion
    Aplicacion.Autorizaciones.currentInstance.solicitudAutorizacionMerma( values );
};

//construye un panel emergente donde el gerente indicaria la cantidad de merma de cierto producto
Aplicacion.Autorizaciones.prototype.solicitarMermaCompra = function( id_compra, id_producto,cantidadOriginal )
{
   
    this.solicitarMermaCompraPanel = new Ext.form.FormPanel({
        scroll: 'none',
        items: [{
            xtype: 'fieldset',
            title: 'Reportar merma en compra',
            instructions: 'Ingrese la cantidad de Merma.',
            items: [
                new Ext.form.Text({name:'id_compra', label: 'ID Compra', disabled: true, value:id_compra }),
                new Ext.form.Text({name:'id_producto', label: 'ID Producto', disabled: true, value:id_producto }),
                new Ext.form.Text({name:'cantidadOriginal', disabled: true, label: 'Cantidad comprada', value:cantidadOriginal }),
                new Ext.form.Text({id:'Autorizacion-MermaCompraPanel-cantidad',
                    name:'cantidad', 
                    label: 'Cantidad reportada',
                    listeners : {
                        'focus' : function (){
                                kconf = {
                                type : 'num',
                                submitText : 'Aceptar',
                                callback : Aplicacion.Efectivo.currentInstance.nuevoGastoValidator
                            };
                        POS.Keyboard.Keyboard( this, kconf );
                        }
                    } })
            ]}, 
            new Ext.Button({ ui  : 'action', text: 'Enviar Solicitud', margin : 15,  handler : this.solicitarMermaCompraPanelValidator })
        ]
    });

    this.panelSolicitudMerma = new Ext.Panel({
        floating:true,
        modal:true,
        centered:true,
        height: 390,
        width: 680,
        scroll:'none',
        //styleHtmlContent:true,
        //html:'ok'
        items:[
            this.solicitarMermaCompraPanel
        ]
    });

    this.panelSolicitudMerma.show('pop');

};

//construye una con el detalle de compra indicado
Aplicacion.Autorizaciones.prototype.listaDetalleCompra = function( id_compra )
{
    Ext.getBody().mask('Obteniendo lista de compras ...', 'x-mask-loading', true);

    Ext.Ajax.request({
        url: '../proxy.php',
        scope : this,
        params : {
            action : 403,
            id_compra: id_compra
        },
        success: function(response, opts) {
            try{
                r = Ext.util.JSON.decode( response.responseText );
            }catch(e){
                return POS.error(response, e);
            }

            if(!r.success)
            {
                Ext.getCmp('MermaHtmlPanel').update("No se tiene registro de esa compra");
                Ext.getBody().unmask(); 
            }

            var html = "";

            if( r.datos.num_compras == 0 )
            {
                Ext.getCmp('MermaHtmlPanel').update("No se tiene registro del detalle de esa compra");
                Ext.getBody().unmask();
                return;
            }

            //verificamos si se ha seleccionado una compra diferente
            if( Aplicacion.Autorizaciones.currentInstance.nueva.numeroCompra != r.datos.id_compra )
            {
                Aplicacion.Autorizaciones.currentInstance.nueva.numeroCompra= r.datos.id_compra;

                html += "<table border = 0>";
                html += "   <tr class = 'top'>";
                html += "       <td>id_compra</td>";
                html += "       <td>id_producto</td>";
                html += "       <td>descripcion</td>";  
                html += "       <td>cantidad</td>";
                html += "       <td>precio</td>";
                html += "   </tr>";
                
                //class es una palabra registrada ! Tronaba en safari
                //class = null;

                for ( var i = 0; i < r.datos.num_compras; i++ )
                {

                    //class = ( i == r.datos.num_compras - 1 )? " 'last Autorizaciones-row' " : " 'Autorizaciones-row' ";

                    html += "<tr class = 'Autorizaciones-row' onClick = ' Aplicacion.Autorizaciones.currentInstance.solicitarMermaCompra(" + r.datos.id_compra + "," + r.datos.compras[ i ].id_producto + ", " + r.datos.compras[ i ].cantidad + ") ' >";

                    html += "   <td>" + r.datos.id_compra + "</td>";
                    html += "   <td>" + r.datos.compras[ i ].id_producto + "</td>";
                    html += "   <td>" + r.datos.compras[ i ].descripcion + "</td>";
                    html += "   <td>" + r.datos.compras[ i ].cantidad + "</td>";
                    html += "   <td>" + POS.currencyFormat( r.datos.compras[ i ].precio ) + "</td>";
                    html += "</tr>";

                }

                html += "   <tr class = ' last Autorizaciones-row ' >";
                html += "       <td colspan = '5'> Total: " + POS.currencyFormat( r.datos.total ) + "</td>";
                html += "   </tr>";
                html += "</table>";

                Ext.getCmp('MermaHtmlPanel').update(html);

            }

            Ext.getBody().unmask(); 

        },
        failure: function( response ){
            POS.error( response );
        }
    }); 
    
};

//almacena el numero de compra actual
Aplicacion.Autorizaciones.prototype.nueva.numeroCompra = null;

//valida el formulario de mermapanel
Aplicacion.Autorizaciones.prototype.nueva.mermaBuscarCompraBoton = function()
{

    var values = Aplicacion.Autorizaciones.currentInstance.nueva.mermaPanel.getValues();

    //verificamos que el id_compra sea un numero entero
    if( !( values.compra && /^\d+$/.test(values.compra + '') ) ){

        Ext.Anim.run(Ext.getCmp( 'Autorizacion-MermaCompraID' ), 
            'fade', {duration: 250,
            out: true,
            autoClear: true
        });

        return;
    }

    Aplicacion.Autorizaciones.currentInstance.listaDetalleCompra( values.compra )

};

//contendra el formulario donde el gerente ingresa el ID de la compra a la cual le va a hacer una devolicion
Aplicacion.Autorizaciones.prototype.nueva.mermaPanel = null;

Aplicacion.Autorizaciones.prototype.nueva.mermaPanelCreator = function ()
{
    this.mermaPanel = new Ext.form.FormPanel({
            scroll: 'none',
            cls : "Tabla",
            listeners : {
                "show"  : function ( ){
                    //construye panel MermaHtmlPanel donde mostrara las compras para reportar merma
                    //Aplicacion.Autorizaciones.currentInstance.listaCompras();
                }
            },
            items: [{
                xtype: 'fieldset',
                title: 'Reportar merma en compra',
                instructions: 'Ingrese el ID de compra.',
                items: [
                    new Ext.form.Text({ 
                        id: 'Autorizacion-MermaCompraID', 
                        name:'compra', 
                        label: 'ID Compra',
                        listeners : {
                        'focus' : function (){
                                kconf = {
                                type : 'num',
                                submitText : 'Aceptar',
                                callback : Aplicacion.Efectivo.currentInstance.nuevoGastoValidator
                            };
                        POS.Keyboard.Keyboard( this, kconf );
                        }
                    } })
                ]}, 
                new Ext.Button({ ui  : 'action', text: 'Buscar Compra', margin : 15,  handler : this.mermaBuscarCompraBoton }),
                { id: 'MermaHtmlPanel', html : null }
        ]});
};



/* ********************************************************
    Devoluciones
******************************************************** */


//ingresa la solicitud de la autorizaciond e la merma a la BD
Aplicacion.Autorizaciones.prototype.solicitudAutorizacionDevolucion = function( values ){

    Ext.Ajax.request({
        url: '../proxy.php',
        scope : this,
        params : {
            action : 203,
            data: Ext.util.JSON.encode( values )
        },
        success: function(response, opts) {
            try{
                r = Ext.util.JSON.decode( response.responseText );
            }catch(e){
                return POS.error(response, e);
            }
            
            //ocultamos el panelSolicitudDevolucion
            Aplicacion.Autorizaciones.currentInstance.panelSolicitudDevolucion.hide('pop');

            if(!r.success)
            {
                Ext.Msg.alert("Autorizaciones","Error: " + r.reason);
            }

            Ext.Msg.alert("Autorizaciones","Solicitud enviada con exito.");

            //Ext.getBody().unmask(); 

        },
        failure: function( response ){
            POS.error( response );
        }
    }); 
}



//construye un panel emergente donde el gerente indicaria la cantidad de merma de cierto producto
Aplicacion.Autorizaciones.prototype.solicitarDevolucionVenta = function( id_venta, id_producto, cantidad, cantidad_procesada )
{
   
    this.solicitarDevolucionVentaPanel = new Ext.form.FormPanel({
        scroll: 'none',
        items: [{
            xtype: 'fieldset',
            title: 'Reportar devolucion en venta',
            instructions: 'Ingrese la cantidad de Devolución.',
            id : 'Autorizacion-DevolucionVentaPanel-Form',
            items: [
                new Ext.form.Text({name:'id_venta', label: 'ID Venta', disabled: true, value : id_venta }),
                new Ext.form.Text({name:'id_producto', label: 'ID Producto', disabled: true, value : id_producto }),
                new Ext.form.Text({name:'cantidadOriginal', value : cantidad, disabled: true, label:'Cantidad  origen'}),
                new Ext.form.Text({name:'cantidadProcesada', value : cantidad_procesada, disabled: true, label:'Cantidad procesada'}),
                new Ext.form.Text({
                    id:'Autorizacion-DevolucionVentaPanel-cantidad',
                    name:'cantidadDevuelta', 
                    label: 'De origen a devolver',
                    disabled : ( cantidad )? false : true ,
                    listeners : {
                        'focus' : function (){
                                kconf = {
                                type : 'num',
                                submitText : 'Aceptar',
                                callback : null
                            };
                        POS.Keyboard.Keyboard( this, kconf );
                        }
                    } }),
                new Ext.form.Text({
                    id:'Autorizacion-DevolucionVentaPanel-cantidadProcesada',
                    name:'cantidadProcesadaDevuelta', 
                    label: 'Procesada a devolver',
                    disabled : ( cantidad_procesada )? false : true ,
                    listeners : {
                        'focus' : function (){
                                kconf = {
                                type : 'num',
                                submitText : 'Aceptar',
                                callback : null
                            };
                        POS.Keyboard.Keyboard( this, kconf );
                        }
                    } })     
            ]}, 
            new Ext.Button({ ui  : 'action', text: 'Enviar Solicitud', margin : 15,  handler : this.solicitarDevolucionVentaPanelValidator })
        ]
    });

    this.panelSolicitudDevolucion = new Ext.Panel({
        floating:true,
        modal: false,
        centered:true,
        height: 465,
        width: 680,
        scroll:'none',
        //styleHtmlContent:true,
        //html:'ok'
        items:[
            this.solicitarDevolucionVentaPanel
        ]
    });

    this.panelSolicitudDevolucion.show('pop');

};




//valida los datos del formulario de solicitarDevolucionVentaPanel
Aplicacion.Autorizaciones.prototype.solicitarDevolucionVentaPanelValidator = function()
{

    
    var values = Aplicacion.Autorizaciones.currentInstance.solicitarDevolucionVentaPanel.getValues();

    // isNaN si es un numero devuelve falso

    if( !Ext.getCmp('Autorizacion-DevolucionVentaPanel-cantidad').isDisabled() ){
        if( isNaN( parseFloat( values.cantidadDevuelta) ) || parseFloat( values.cantidadDevuelta) < 0 ) {

                Ext.Anim.run( Ext.getCmp('Autorizacion-DevolucionVentaPanel-cantidad'), 
                    'fade', {duration: 250,
                    out: true,
                    autoClear: true
                });
                return;
        }
    }

    if( !Ext.getCmp('Autorizacion-DevolucionVentaPanel-cantidadProcesada').isDisabled() ){
        if( isNaN( parseFloat( values.cantidadProcesadaDevuelta ) ) || parseFloat( values.cantidadProcesadaDevuelta) < 0  ) {

                Ext.Anim.run( Ext.getCmp('Autorizacion-DevolucionVentaPanel-cantidadProcesada'), 
                    'fade', {duration: 250,
                    out: true,
                    autoClear: true
                });
                return;
                
        }
    }

    if(DEBUG){console.log("enviando solicitud de devolucion",values);}

    //ya que se esta seguro de que la informacion es correcta se envia la informacion
    Aplicacion.Autorizaciones.currentInstance.solicitudAutorizacionDevolucion( values );

};



//construye una lista con el detalle de una venta
Aplicacion.Autorizaciones.prototype.listaDetalleVenta = function( id_venta )
{
    Ext.getBody().mask('Obteniendo lista de ventas ...', 'x-mask-loading', true);

    Ext.Ajax.request({
        url: '../proxy.php',
        scope : this,
        params : {
            action : 404,
            id_venta: id_venta
        },
        success: function(response, opts) {
            try{
                r = Ext.util.JSON.decode( response.responseText );
            }catch(e){
                return POS.error(response, e);
            }

            if(!r.success)
            {
                Ext.getCmp('DevolucionHtmlPanel').update("No se tiene registro de esa venta");
                Ext.getBody().unmask(); 
            }

            var html = "";

            if( r.datos.num_ventas == 0 )
            {
                Ext.getCmp('DevolucionHtmlPanel').update("No se tiene registro del detalle de esa venta");
                Ext.getBody().unmask();
                return;
            }

            //verificamos si se pide la lista de otra venta difirente
            if( Aplicacion.Autorizaciones.currentInstance.nueva.numeroVenta != r.datos.id_venta )
            {
                Aplicacion.Autorizaciones.currentInstance.nueva.numeroVenta= r.datos.id_venta;

                html += "<table border = 0>";
                html += "   <tr class = 'top'>";
                html += "       <td>id_venta</td>";
                html += "       <td>id_producto</td>";
                html += "       <td>descripcion</td>";  
                html += "       <td>cantidad</td>";
                html += "       <td>precio</td>";
                html += "       <td>cantidad procesada</td>";
                html += "       <td>precio procesada</td>";
                html += "       <td>subtotal</td>";
                html += "   </tr>";
                
                //Class es una palabra registrada !
                //class = null;

                for ( var i = 0; i < r.datos.num_ventas; i++ )
                {

                    //class = ( i == r.datos.num_compras - 1 )? " 'last Autorizaciones-row' " : " 'Autorizaciones-row' ";
                   
                    html += "<tr class = 'Autorizaciones-row' onClick = ' Aplicacion.Autorizaciones.currentInstance.solicitarDevolucionVenta(" + r.datos.id_venta + "," + r.datos.ventas[ i ].id_producto + "," + r.datos.ventas[ i ].cantidad + "," + r.datos.ventas[ i ].cantidad_procesada + ") ' >";

                    html += "   <td>" + r.datos.id_venta + "</td>";
                    html += "   <td>" + r.datos.ventas[ i ].id_producto + "</td>";
                    html += "   <td>" + r.datos.ventas[ i ].descripcion + "</td>";
                    html += "   <td>" + r.datos.ventas[ i ].cantidad + "</td>";
                    html += "   <td>" + POS.currencyFormat( r.datos.ventas[ i ].precio ) + "</td>";
                    html += "   <td>" + r.datos.ventas[ i ].cantidad_procesada + "</td>";
                    html += "   <td>" + POS.currencyFormat( r.datos.ventas[ i ].precio_procesada ) + "</td>";
                    html += "   <td>" + POS.currencyFormat( (  r.datos.ventas[ i ].cantidad * r.datos.ventas[ i ].precio  ) + (  r.datos.ventas[ i ].cantidad_procesada * r.datos.ventas[ i ].precio_procesada  ) ) + "</td>";
                    html += "</tr>";

                }

                html += "   <tr class = ' last Autorizaciones-row ' >";
                html += "       <td colspan = '8'> Total: " + POS.currencyFormat( r.datos.total ) + "</td>";
                html += "   </tr>";
                html += "</table>";

                Ext.getCmp('DevolucionHtmlPanel').update(html);

            }

            Ext.getBody().unmask(); 

        },
        failure: function( response ){
            POS.error( response );
        }
    }); 
    
};

//almacena el numero de venta actual
Aplicacion.Autorizaciones.prototype.nueva.numeroVenta = null;

//valida el formulario de devolucionesPanel
Aplicacion.Autorizaciones.prototype.nueva.devolucionBuscarVentaBoton = function()
{

    var values = Aplicacion.Autorizaciones.currentInstance.nueva.devolucionesPanel.getValues();

    //verificamos que el id_compra sea un numero entero
    if( !( values.venta && /^\d+$/.test(values.venta + '') ) ){

        Ext.Anim.run(Ext.getCmp( 'Aurotizacion-devolucionesIdVenta' ), 
            'fade', {duration: 250,
            out: true,
            autoClear: true
        });

        return;
    }

    Aplicacion.Autorizaciones.currentInstance.listaDetalleVenta( values.venta )

};


//contendra el formulario donde el gerente ingresa el ID de la compra a la cual le va a hacer una devolicion
Aplicacion.Autorizaciones.prototype.nueva.devolucionesPanel = null;

Aplicacion.Autorizaciones.prototype.nueva.devolucionesPanelCreator = function()
{


    this.devolucionesPanel = new Ext.form.FormPanel({
            scroll: 'none',
            cls : "Tabla",
            items: [{
                xtype: 'fieldset',
                title: 'Autorizar devolucion',
                instructions: 'Seleccione el ID de la venta',
                items: [
                    new Ext.form.Text({ 
                        id: 'Aurotizacion-devolucionesIdVenta', 
                        name: 'venta', 
                        label: 'ID Venta',
                        listeners : {
                        'focus' : function (){
                                kconf = {
                                type : 'num',
                                submitText : 'Aceptar',
                                callback : Aplicacion.Efectivo.currentInstance.nuevoGastoValidator
                            };
                        POS.Keyboard.Keyboard( this, kconf );
                        }
                    } })
                ]}, 
                new Ext.Button({ ui  : 'action', text: 'Buscar Venta', margin : 15,handler: this.devolucionBuscarVentaBoton }),
                { id: 'DevolucionHtmlPanel', html : null }
        ]});
};






/* ***************************************************************************
   * Historial de autorizacones
   * Es una lista con las distintas autorizaciones, que pueden ordenarse por 
   * sus distintas caracteristicas
   *************************************************************************** */

/**
 * Registra el model para listaDeAutorizaciones
 */
Ext.regModel('listaDeAutorizacionesModel', {
    fields: [
        { name: 'id_autorizacion',     type: 'int'},
        { name: 'estado',              type: 'int'},
        { name: 'fecha_peticion',      type: 'date'}
    ]
});



/**
 * Contiene un objeto con la lista de autorizaciones actual, para no estar
 * haciendo peticiones a cada rato
 */
Aplicacion.Autorizaciones.prototype.listaDeAutorizaciones = {
    lista : null,
    lastUpdate : null,
    hash: null
};


/**
 * Es el Store que contiene la lista de autorizaciones cargada con una peticion al servidor.
 * Recibe como parametros un modelo y una cadena que indica por que se va a sortear (ordenar) 
 * en este caso ese filtro es dado por 
 * @return Ext.data.Store
 */
Aplicacion.Autorizaciones.prototype.listaDeAutorizacionesStore = new Ext.data.Store({
    model: 'listaDeAutorizacionesModel' ,
    sorters: 'fecha_peticion',
    

    getGroupString : function(record) {

        json = Ext.util.JSON.decode( record.get('parametros') );
        return json.descripcion;
    }
});



/**
 * Leer la lista de autorizaciones del servidor mediante AJAX
 */

Aplicacion.Autorizaciones.prototype.listaDeAutorizacionesLoad = function (){
	
	if(DEBUG){
		console.log("Actualizando lista de autorizaciones ....");
	}
	
	Ext.Ajax.request({
		url: '../proxy.php',
		scope : this,
		params : {
			action : 207
		},
		success: function(response, opts) {
			try{
				autorizaciones = Ext.util.JSON.decode( response.responseText );				
			}catch(e){
				return POS.error(e);
			}
			
			
			//volver a intentar			
			if( !autorizaciones.success ){
				return POS.error(autorizaciones);
			}
			
			
			this.listaDeAutorizaciones.lista = autorizaciones.payload;
			this.listaDeAutorizaciones.lastUpdate = Math.round(new Date().getTime()/1000.0);
			this.listaDeAutorizaciones.hash = autorizaciones.hash;

			//agregarlo en el store
			this.listaDeAutorizacionesStore.loadData( autorizaciones.payload );


			if(DEBUG){
                console.log("Lista de autorizaciones retrived !", autorizaciones, this.listaDeAutorizacionesStore );
            }
		},
		failure: function( response ){
			POS.error( response );
		}
	});

};



Aplicacion.Autorizaciones.prototype.checkForNewAuts = function(  ){
	
}


/*
 * Contiene el panel con la lista de autorizaciones
 */
Aplicacion.Autorizaciones.prototype.listaDeAutorizacionesPanel = null;

/**
 * Pone un panel en listaDeAutorizacionesPanel
 */
Aplicacion.Autorizaciones.prototype.listaDeAutorizacionesPanelCreator = function (){


    this.listaDeAutorizacionesPanel = new Ext.Panel({
        layout: 'fit' ,
        items: [{
			xtype: 'list',
			emptyText: "vacio",
            store: this.listaDeAutorizacionesStore,
            itemTpl: '<div class="listaDeAutorizacionesAutorizacion">ID de autorizacion : {id_autorizacion}&nbsp; Enviada el {fecha_peticion}</div>',
            grouped: true,
            indexBar: false,
            listeners : {
                "selectionchange"  : function ( view, nodos, c ){
                    if(nodos.length > 0){
                        //if(DEBUG){console.log(nodos, c, view);}
                        Aplicacion.Autorizaciones.currentInstance.detalleAutorizacionPanelShow( nodos[0] );
                        console.error("bug ! cuando haces un tap el orden de nodos[0] no es el correcto");
                        //console.log(view.getSelectedRecords());
                    }


                    view.deselectAll();
                    },
                "itemtap" : function(a,b,c,d){
                        //console.log(a,b,c,d)
                        //console.log(a.getSelectedRecords())    
                    }
                }
        }]

    });

    Aplicacion.Autorizaciones.currentInstance.listaDeAutorizacionesPanel = this.listaDeAutorizacionesPanel;
    
};



//surte al inventario los productos mandados por le admin
Aplicacion.Autorizaciones.prototype.surtirAutorizacion = function( aid ){


    //obtenemos al autorizacion actual
    autorizacion = Aplicacion.Autorizaciones.currentInstance.detalleAutorizacion;

    if(DEBUG){console.log("Surtir inventario dada una autorizacion ", aid );}


    Ext.Ajax.request({
        url: '../proxy.php',
        scope : this,
        params : {
            action : 211,
            id_autorizacion : aid
        },
        success: function(response, opts) {
            try{
                autorizaciones = Ext.util.JSON.decode( response.responseText );             
            }catch(e){
                return POS.error(e);
            }
            
            if( !autorizaciones.success ){
                //volver a intentar
                //return POS.error(autorizaciones);
                Ext.Msg.alert("Autorizaciones","Error: " + autorizaciones.reason);
                return;
            }



			//recargar todo
			task();

            //cambiamos la card
            sink.Main.ui.setActiveItem( Aplicacion.Inventario.currentInstance.listaInventarioPanel , 'fade');
            

            Ext.Msg.alert("Autorizaciones","Se modifico correctamente el inventario");
        },
        failure: function( response ){
            POS.error( response );
        }
    });

}

//guardara los detalles de la autorizacion actual
Aplicacion.Autorizaciones.prototype.detalleAutorizacion = null;

Aplicacion.Autorizaciones.prototype.detalleAutorizacionFormPanel = null;




//se muestran los detalles de la autorizacion
Aplicacion.Autorizaciones.prototype.detalleAutorizacionPanelShow = function( autorizacion ){

    if(DEBUG){
        console.log("Mostrando autorizacion : " , autorizacion);
    }

    //decodificar el json de parametros
    var parametros = Ext.util.JSON.decode( autorizacion.get('parametros') );
    
    console.log("------------------------------------");
    console.log(parametros);
    console.log("------------------------------------");
    
    //estado de la autorizacion
    var estado = autorizacion.data.estado;
    
    //establecemos una descripcion del estado legible para el cliente
    switch( estado ){
    
        case 0 :
            estado = 'No ha sido revisado por el administrador';
        break;
        
        case 1 :
            estado = "Autorización aprovada";
        break;
        
        case 2 :
            estado = "Autorizacón denegada";
        break;
        
        case 3 :
            estado = "Producto en transito";
        break;
        
        case 4 :
            estado = "El embarque ha sido surtido";
        break;
        
        default:
            estado = "Indefinido.";        
    
    }
    
    instrucciones = null;   
    
    //almacenara los items del formulario
    var itemsForm = [
    ];


    //creamos los items para el detalleAutorizacionFormPanel
    switch( parametros.clave )
    {
        case '201'://solicitud de autorizacion de gasto (gerente)
            itemsForm.push(
                new Ext.form.Text({
                    label:'ID Autorización',
                    name:'id_autorizacion',
                    value:autorizacion.data.id_autorizacion
                }),new Ext.form.Text({label: 'Concepto', value:parametros.concepto }),
                new Ext.form.Text({label: 'Monto', value:parametros.monto })
            );
            height = 375;
        break;

        case '202'://solicitud de autorizacion de cambio de limite de credito (gerente)
            itemsForm.push(
                new Ext.form.Text({
                    label:'ID Autorización',
                    name:'id_autorizacion',
                    value:autorizacion.data.id_autorizacion
                }),new Ext.form.Text({label: 'ID Cliente', value : parametros.id_cliente }),
                new Ext.form.Text({label: 'Cantidad', value : parametros.cantidad })
            );
            height = 375;
        break;

        case '203'://solicitud de autorizacion de devolucion (gerente)
            itemsForm.push(
                new Ext.form.Text({
                    label:'ID Autorización',
                    name:'id_autorizacion',
                    value:autorizacion.data.id_autorizacion
                }),new Ext.form.Text({label: 'ID Venta', value:parametros.id_venta }),
                new Ext.form.Text({label: 'ID Producto', value:parametros.id_producto }),
                new Ext.form.Text({label: 'Cantidad de origen', value:parametros.cantidad }),
                new Ext.form.Text({label: 'Cantidad procesada', value:parametros.cantidad })
            );
            height = 425;
        break;

        case '204'://solicitud de venta preferencial (gerente)
            itemsForm.push(
                new Ext.form.Text({
                    label:'ID Autorización',
                    name:'id_autorizacion',
                    value:autorizacion.data.id_autorizacion
                }),
                new Ext.form.Text({label: 'ID Cliente', value : parametros.id_cliente }),
                new Ext.form.Text({label: 'Nombre', value : parametros.nombre })
            );
            height = 360;
        break;

        case '205':////solicitud de autorizacion de merma (gerente)
            itemsForm.push(
                new Ext.form.Text({
                    label:'ID Autorización',
                    name:'id_autorizacion',
                    value:autorizacion.data.id_autorizacion
                }),new Ext.form.Text({label: 'ID Compra', value : parametros.id_compra }),
                new Ext.form.Text({label: 'ID Producto', value : parametros.id_producto }),
                new Ext.form.Text({label: 'Cantidad', value : parametros.cantidad })
            );
            height = 425;
        break;


        /*
         *
         * El gerente ha enviado productos desde el centro de administracion
         */
        case '209':

            //creamos la tabla
            html = "";
            html += "<table border = 0>";
            html += "   <tr class = 'top'>";
            html += "       <td>Producto</td>";
            html += "       <td>Cantidad</td>";
            html += "       <td>Costo</td>";
            html += "   </tr>";

            for ( var i = 0; i < parametros.productos.length; i++ ){
                html += "<tr  >";
                html += "   <td>" + parametros.productos[i].id_producto + " " + parametros.productos[i].descripcion +  "</td>";
                html += "   <td>" + parametros.productos[i].cantidad + " " + parametros.productos[i].escala + "s</td>";
                html += "   <td>" + POS.currencyFormat( parametros.productos[i].precio ) + "</td>";
                html += "</tr>";
            }

            html += "</table>";

            itemsForm.push({
                id:     'detalleAutorizacionFormPanel-Tabla',
                html:   html,
                cls :   'Tabla',
            });


            if(autorizacion.get('estado') == 3){
                instrucciones = "Hay un embarque en transito con estos productos.";
            }else{
                instrucciones = "Usted ya ha recibido este embarque.";
            }

        break;


        /*
         *
         * El gerente de esta sucursal a solicitado producto.
         */
        case '210':

            //creamos la tabla
            html = "";
            html += "<table border = 0>";
            html += "   <tr class = 'top'>";
            html += "       <td>Producto</td>";
            html += "       <td>Cantidad Original</td>";
            html += "       <td>Cantidad Procesada</td>";
            html += "   </tr>";

            for ( var i = 0; i < parametros.productos.length; i++ ){
                html += "<tr  >";
                html += "   <td>" + parametros.productos[i].id_producto + " " + parametros.productos[i].descripcion +  "</td>";
                html += "   <td>" + parametros.productos[i].cantidad + " " + parametros.productos[i].escala + "s</td>";
                html += "   <td>" + parametros.productos[i].cantidad + " " + parametros.productos[i].escala + "s</td>";
                html += "</tr>";
            }

            html += "</table>";

            itemsForm.push({
                id:     'detalleAutorizacionFormPanel-Tabla',
                html:   html,
                cls :   'Tabla',
            });

            instrucciones = "Esta es una solicitud de producto al centro de distribucion.";

        break;
    }//switch


    Aplicacion.Autorizaciones.currentInstance.detalleAutorizacionFormPanel = new Ext.form.FormPanel({
        dockedItems: [{
            dock: 'bottom',
            xtype: 'toolbar',
            items: [{
                text: 'Regresar a lista de autorizaciones',
                ui: 'back',
                handler: function() {                   
                   sink.Main.ui.setActiveItem( Aplicacion.Autorizaciones.currentInstance.listaDeAutorizacionesPanel , 'slide');
                }
            },{
                xtype: 'spacer'
            },
            //cuando esta autorizacion es estado 3, mostrar un boton para aceptar el embarque
            autorizacion.get('estado') == 3 ? 
                new Ext.Button({ 
                    ui  : 'forward', 
                    text: 'He recibido el embarque', 
                    handler: function(){
                        Aplicacion.Autorizaciones.currentInstance.surtirAutorizacion(autorizacion.get('id_autorizacion'))
                    }
                }) : {  xtype: 'spacer' } 
            ]
        }],
        items: [{
                xtype: 'fieldset',
                title: (!parametros.descripcion)?"(" + estado +")" : parametros.descripcion + ".  (" + estado + ")",
                instructions: (instrucciones != null)?instrucciones:"",
                items:  itemsForm 
            }
        ]
    });

    sink.Main.ui.setActiveItem( Aplicacion.Autorizaciones.currentInstance.detalleAutorizacionFormPanel , 'slide');
};
























POS.Apps.push( new Aplicacion.Autorizaciones() );



/**
*    PENDIENTES
*
*   --Cuando se poide al autorizacion de devolucion de un producto, al seleccionar el producto a devolver, se debe
*      de tomar en cuenta si fue un producto procesado o sin procesar
*   
*   -- que pasa cuandos e hace una solicitud de cambio de limite de credito
*
*   -- el gerente ya no puede hacer unas olicitud de producto?
*
*   -- el action 210 la respuesta de la autorizacion de surtir producto, pero cuando se geenra por vez primera essta autorizacion 
*       no se ha modificado para que especifiqeu si es producto procesado o sin procesar
*
*   -- que podemos hacer con respecto a eliminar las soliocitud de autorizaciones
*
*   --. se pueden cancelar las autorizaciones?
*/



