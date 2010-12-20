

Aplicacion.Efectivo = function (  ){

	return this._init();
}




Aplicacion.Efectivo.prototype._init = function (){
    if(DEBUG){
		console.log("Efectivo: construyendo");
    }

    //cargar las sucursales
    this.cargarSucursales();

	//crear el panel de nuevo gasto
	this.nuevoGastoPanelCreator();
	
	this.nuevoAbonoPanelCreator();
	
	//crear el panel de nuevo ingreso
	this.nuevoIngresoPanelCreator();
	
	//crear el panel de operacion intersucursal efectivo
	this.operacionInterSucursalEfectivoPanelCreator();

    //crear el panel de operacion intersucursal producto
    this.operacionIntersucursalProductoPanelCreator();
	
	
	Aplicacion.Efectivo.currentInstance = this;
	
	return this;
};




Aplicacion.Efectivo.prototype.getConfig = function (){
    return {
        text: 'Efectivo',
        cls: 'launchscreen',
        items: [{
            text: 'Egresos',
            items: [{
                text: 'Gasto',
                card: this.nuevoGastoPanel,
                leaf: true
            },
            {
                text: 'Abono',
                card: this.nuevoAbonoPanel,
                leaf: true
            }]
            
            
            
        },
        {
            text: 'Ingresos',
            card: this.nuevoIngresoPanel,
            leaf: true
        },
        {
            text: 'Operaciones entre Sucursales',
            //card: this.operacionesInterSucursalesPanel,
            items: [{
                text: 'Efectivo',
                card: this.operacionInterSucursalEfectivoPanel,
                leaf: true
            },
            {
                text: 'Producto',
                card: this.operacionInterSucursalProductoPanel,
                leaf: true
            }]
        }]
    };
};


Aplicacion.Efectivo.prototype.sucursalesLista = null;

/*
 * Cargar la lista de las sucursales
 *  
 **/
Aplicacion.Efectivo.prototype.cargarSucursales = function ( )
{
    Ext.Ajax.request({
        url: 'proxy.php',
        scope : this,
        params : {
            action : 700
        },
        success: function(response, opts) {
            try{
                r = Ext.util.JSON.decode( response.responseText );
            }catch(e){
                return POS.error(response, e);
            }

            if( !r.success ){
                return;
            }
            Aplicacion.Efectivo.currentInstance.sucursalesLista = r.datos;

        },
        failure: function( response ){
            POS.error( response );
        }
    });
};

/*
 * Cargar la lista de los empleados en cierta sucursal
 *  
 **/
Aplicacion.Efectivo.prototype.cargarEmpleados = function ( a, b )
{

    Ext.Ajax.request({
        url: 'proxy.php',
        scope : this,
        params : {
            action : 505,
            id_sucursal : b
        },
        success: function(response, opts) {
            try{
                r = Ext.util.JSON.decode( response.responseText );
            }catch(e){
                return POS.error(response, e);
            }

            if( !r.success ){
                return;
            }

            Ext.getCmp("Efectivo-operacionInterSucursalEfectivo-responsable").setOptions( r.datos );

        },
        failure: function( response ){
            POS.error( response );
        }
    });
};


/* ********************************************************
	Operacion Inter Sucursales
******************************************************** */

/**
 * Inserta un nuevo prestamo de efectivo a la BD
 */
Aplicacion.Efectivo.prototype.nuevaOperacionInterSucursalEfectivo = function( data ){

    Ext.getBody().mask('Guardando nuevo prestamo de efectivo ...', 'x-mask-loading', true);

    Ext.Ajax.request({
        url: 'proxy.php',
        scope : this,
        params : {
            action : 600,
            data : Ext.util.JSON.encode( data )
        },
        success: function(response, opts) {
            try{
                r = Ext.util.JSON.decode( response.responseText );
            }catch(e){
                return POS.error(response, e);
            }

            Ext.getBody().unmask(); 

            //limpiar la forma      
            Aplicacion.Efectivo.currentInstance.operacionInterSucursalEfectivoPanel.reset();


            //informamos lo que sucedio
            if( r.success == "true" )
            {
                Ext.Msg.alert("Efectivo","Se ha registrado el nuevo prestamo de efectivo"); 
            }
            else
            {
                Ext.Msg.alert("Efectivo","Error: " + r.success); 
            }
        },
        failure: function( response ){
            POS.error( response );
        }
    }); 

};

/**
 * Validar los datos de la forma de nuevo prestamps de efectivo
 */
Aplicacion.Efectivo.prototype.nuevoPrestamoEfectivoValidator = function ()
{
    //obtener los valores de la forma
    var values = Aplicacion.Efectivo.currentInstance.operacionInterSucursalEfectivoPanel.getValues();

    if( !( Ext.getCmp( 'Efectivo-operacionInterSucursalEfectivo-sucursal' ).getValue() ) )
    {
        Ext.Anim.run(Ext.getCmp( 'Efectivo-operacionInterSucursalEfectivo-sucursal' ),
            'fade', {duration: 250,
            out: true,
            autoClear: true
        });

        return;
    }


    if( !( Ext.getCmp( 'Efectivo-operacionInterSucursalEfectivo-responsable' ).getValue() ) )
    {
        Ext.Anim.run(Ext.getCmp( 'Efectivo-operacionInterSucursalEfectivo-responsable' ),
            'fade', {duration: 250,
            out: true,
            autoClear: true
        });

        return;
    }

    if( values.concepto == "" )
    {
        Ext.Anim.run(Ext.getCmp( 'Efectivo-operacionInterSucursalEfectivo-concepto' ),
            'fade', {duration: 250,
            out: true,
            autoClear: true
        });

        return;
    }

    if( !( values.monto && /^-?\d+(\.\d+)?$/.test(values.monto + '') ) ){

        Ext.Anim.run(Ext.getCmp( 'Efectivo-operacionInterSucursalEfectivo-monto' ), 
            'fade', {duration: 250,
            out: true,
            autoClear: true
        });

        return;
    }

    Aplicacion.Efectivo.currentInstance.nuevaOperacionInterSucursalEfectivo( values );

};


/**
 * Contiene un panel con la forma de una operacion intersucursal de efectivo
 */
Aplicacion.Efectivo.prototype.operacionInterSucursalEfectivoPanel = null;



/**
 * Pone un panel en operacionInterSucursalEfectivoPanel
 */
Aplicacion.Efectivo.prototype.operacionInterSucursalEfectivoPanelCreator = function (){


    this.operacionInterSucursalEfectivoPanel = new Ext.form.FormPanel({
            listeners : {
                    "show" : function (){
                        Ext.getCmp("Efectivo-operacionInterSucursalEfectivo-sucursal").setOptions( Aplicacion.Efectivo.currentInstance.sucursalesLista );
                    }
                },
            items: [{

                xtype: 'fieldset',
                title: 'Nuevo prestamo de efectivo',
                instructions: 'Todos los campos son necesarios para una nueva autorizacion.',
                items: [
                    new Ext.form.Select({ 
                        id:'Efectivo-operacionInterSucursalEfectivo-sucursal',
                        name : 'sucursal',
                        label: 'Sucursal',
                        required:true,
                        options: [ {text : "Seleccione una sucursal", value : null } ],
                        listeners : {
                            "change" : function(a,b) {Aplicacion.Efectivo.currentInstance.cargarEmpleados(a,b);}
                        }
                    }),
                    new Ext.form.Select({ 
                        id:'Efectivo-operacionInterSucursalEfectivo-responsable',
                        name : 'responsable',
                        label: 'Responsable',
                        required:true,
                        options: [ {text : "Seleccione un responsable", value : null } ]
                    }),
                    new Ext.form.Text({
                        id: 'Efectivo-operacionInterSucursalEfectivo-concepto',
                        label: 'Concepto',
                        name: 'concepto',
                        required:true,
                        listeners : {
                            'focus' : function (){

                                kconf = {
                                    type : 'alfa',
                                    submitText : 'Aceptar'
                                };
                                POS.Keyboard.Keyboard( this, kconf );
                            }
                        }
                    }),
                    new Ext.form.Text({
                        id: 'Efectivo-operacionInterSucursalEfectivo-monto',
                        label: 'Monto',
                        name: 'monto',
                        required:true,
                        listeners : {
                            'focus' : function (){
                                kconf = {
                                    type : 'num',
                                    submitText : 'Aceptar'
                                };
                                POS.Keyboard.Keyboard( this, kconf );
                            }
                        }
                    }),
                    new Ext.form.Text({
                        id: 'Efectivo-operacionInterSucursalEfectivo-nota',
                        label: 'Nota',
                        name: 'nota',
                        required:true,
                        listeners : {
                            'focus' : function (){

                                kconf = {
                                    type : 'alfa',
                                    submitText : 'Aceptar'
                                };
                                POS.Keyboard.Keyboard( this, kconf );
                            }
                        }
                    }),
                    new Ext.form.Hidden({
                        name: 'folio',
                        value:'-1'
                    })
                ]},
                new Ext.Button({ ui  : 'action', text: 'Registrar Prestamo de Efectivo', margin : 5,  handler : this.nuevoPrestamoEfectivoValidator, disabled : false })
        ]});

}



/**
 * Contiene un panel con la forma de una operacion intersucursal de producto
 */
Aplicacion.Efectivo.prototype.operacionInterSucursalProductoPanel = null;



/**
 * Pone un panel en operacionInterSucursalProductoPanel
 */
Aplicacion.Efectivo.prototype.operacionIntersucursalProductoPanelCreator = function (){

    
    this.operacionInterSucursalProductoPanel = new Ext.form.FormPanel({
            items: [{

                xtype: 'fieldset',
                title: 'Nueva venta de producto',
                instructions: 'Todos los campos son necesarios para una nueva autorizacion.',
                defaults : {
                    listeners : {
                        "change" : function (){
                            Aplicacion.Efectivo.currentInstance.nuevoGastoValidator();
                        }
                    }
                },
                items: [
                    {
                        xtype : "textfield",
                        label : "Monto",
                        name : "monto",
                        listeners : {
                            'focus' : function (){

                                kconf = {
                                    type : 'num',
                                    submitText : 'Aceptar',
                                    callback : Aplicacion.Efectivo.currentInstance.nuevoGastoValidator
                                };
                                POS.Keyboard.Keyboard( this, kconf );
                            }
                        }
                        
                    },{
                        xtype : "textfield",
                        label : "Folio",
                        name : "folio",
                        listeners : {
                            'focus' : function (){

                                kconf = {
                                    type : 'alfa',
                                    submitText : 'Aceptar',
                                    callback : Aplicacion.Efectivo.currentInstance.nuevoGastoValidator
                                };
                                POS.Keyboard.Keyboard( this, kconf );
                            }
                        }

                    },{
                        xtype : "datepickerfield",
                        label : "Fecha",
                        picker : { yearFrom : 2010 },
                        name : "fecha"
                    },{
                        xtype : "selectfield",
                        label : "Concepto",
                        name : "concepto",
                        options : [
                            {
                                text : "Luz",
                                value : "luz"
                            },{
                                text : "Predial",
                                value : "predial"
                            },{
                                text : "Sueldo",
                                value : "sueldo"
                            },{
                                text : "Otro",
                                value : "otro"
                            }
                        ]
                    },{
                        xtype : "textfield",
                        label : "Nota",
                        name : "nota",
                        listeners : {
                            'focus' : function (){

                                kconf = {
                                    type : 'alfa',
                                    submitText : 'Aceptar',
                                    callback : Aplicacion.Efectivo.currentInstance.nuevoGastoValidator
                                };
                                POS.Keyboard.Keyboard( this, kconf );
                            }
                        }
                    }
                ]}
        ]});

}



/* ********************************************************
	Nuevo Gasto
******************************************************** */


/**
 * Validar los datos de la forma de nuevo gasto
 */
Aplicacion.Efectivo.prototype.nuevoGastoValidator = function ()
{
	//obtener los valores de la forma
	var values = Aplicacion.Efectivo.currentInstance.nuevoGastoPanel.getValues();
	
	if( !( values.monto && /^-?\d+(\.\d+)?$/.test(values.monto + '') ) ){

        Ext.Anim.run(Ext.getCmp( 'Efectivo-nuevoGastoPanel-monto' ), 
            'fade', {duration: 250,
            out: true,
            autoClear: true
        });

		return;
	}
	
    if( Ext.getCmp( 'Efectivo-nuevoGastoPanel-folio' ).isVisible() )
    {
        if(  values.folio.length == 0 )
        {
            Ext.Anim.run(Ext.getCmp( 'Efectivo-nuevoGastoPanel-folio' ), 
                'fade', {duration: 250,
                out: true,
                autoClear: true
            });

            return;
        }
    }

    if( !values.fecha )
    {
        Ext.Anim.run(Ext.getCmp( 'Efectivo-nuevoGastoPanel-fecha' ), 
            'fade', {duration: 250,
            out: true,
            autoClear: true
        });

        return;
    }

    Aplicacion.Efectivo.currentInstance.nuevoGasto( values );

};


/**
 * Inserta el nuevo gasto en la BD
 */
Aplicacion.Efectivo.prototype.nuevoGasto = function( data ){

    Ext.getBody().mask('Guardando nuevo gasto ...', 'x-mask-loading', true);

    Ext.Ajax.request({
        url: 'proxy.php',
        scope : this,
        params : {
            action : 600,
            data : Ext.util.JSON.encode( data )
        },
        success: function(response, opts) {
            try{
                r = Ext.util.JSON.decode( response.responseText );
            }catch(e){
                return POS.error(response, e);
            }

            Ext.getBody().unmask(); 

            //limpiar la forma      
            Aplicacion.Efectivo.currentInstance.nuevoGastoPanel.reset();

            //si no esta visible el campo de folio lo regresamos
            if( !Ext.getCmp( 'Efectivo-nuevoGastoPanel-folio' ).isVisible() )
            {
                Ext.getCmp( 'Efectivo-nuevoGastoPanel-folio' ).show();
                Ext.Anim.run(Ext.getCmp( 'Efectivo-nuevoGastoPanel-folio' ), 
                'slide', 
                {
                    duration: 500,
                    out: false,
                    autoClear: false,
                });
            }

            //informamos lo que sucedio
            if( r.success == "true" )
            {
                Ext.Msg.alert("Efectivo","Se ha registrado el nuevo gasto"); 
            }
            else
            {
                Ext.Msg.alert("Efectivo","Error: " + r.success); 
            }

        },
        failure: function( response ){
            POS.error( response );
        }
    }); 

};


/**
 * Contiene el panel con la forma de nuevo gasto
 */
Aplicacion.Efectivo.prototype.nuevoGastoPanel = null;


/**
 * Pone un panel en nuevoGastoPanel
 */
Aplicacion.Efectivo.prototype.nuevoGastoPanelCreator = function (){

	this.nuevoGastoPanel = new Ext.form.FormPanel({
        items: [{
                xtype: 'fieldset',
                title: 'Ingrese los detalles del nuevo gasto',
                instructions: 'Si desea agregar un gasto que no se encuentre en la lista debera pedir una autorizacion.',
                items: [
                    new Ext.form.Text({ id:'Efectivo-nuevoGastoPanel-monto', name: 'monto', label: 'Monto', required:true,
                    listeners : {
                        'focus' : function (){
                                kconf = {
                                type : 'num',
                                submitText : 'Aceptar',
                                callback : Aplicacion.Efectivo.currentInstance.nuevoGastoValidator
                            };
                        POS.Keyboard.Keyboard( this, kconf );
                        }
                    }}),
                    new Ext.form.Text({ id:'Efectivo-nuevoGastoPanel-folio', name: 'folio', label: 'Folio', required:true,
                    listeners : {
                        'focus' : function (){
                                kconf = {
                                type : 'alfa',
                                submitText : 'Aceptar',
                                callback : Aplicacion.Efectivo.currentInstance.nuevoGastoValidator
                            };
                        POS.Keyboard.Keyboard( this, kconf );
                        }
                    }}),
                    new Ext.form.DatePicker({ id:'Efectivo-nuevoGastoPanel-fecha', name : 'fecha', label: 'Fecha', picker : { yearFrom : 2010, yearTo : 2011 }, required:true }),
                    new Ext.form.Select({ 
                        id:'Efectivo-nuevoGastoPanel-concepto',
                        name : 'concepto',
                        label: 'Concepto',
                        required:true,
                        options : [
                            {
                                text : "Luz",
                                value : "luz"
                            },{
                                text : "Agua",
                                value : "agua"
                            },{
                                text : "Telefono",
                                value : "telefono"
                            },{
                                text : "Nextel",
                                value : "nextel"
                            },{
                                text : "Comida",
                                value : "comida"
                            }
                        ],
                        listeners : {
                            "change" : function (){

                                if(this.value == "comida")
                                {
                                    Ext.Anim.run(Ext.getCmp( 'Efectivo-nuevoGastoPanel-folio' ), 
                                    'slide', 
                                    {
                                        duration: 500,
                                        out: true,
                                        autoClear: false,
                                        after : function (){ 
                                            Ext.getCmp( 'Efectivo-nuevoGastoPanel-folio' ).setValue("");
                                            Ext.getCmp( 'Efectivo-nuevoGastoPanel-folio' ).hide()
                                        }
                                    });
                                }
                                else if( !Ext.getCmp( 'Efectivo-nuevoGastoPanel-folio' ).isVisible() )
                                {
                                    Ext.getCmp( 'Efectivo-nuevoGastoPanel-folio' ).show();
                                    Ext.Anim.run(Ext.getCmp( 'Efectivo-nuevoGastoPanel-folio' ), 
                                    'slide', 
                                    {
                                        duration: 500,
                                        out: false,
                                        autoClear: false,
                                    });
                                }

                            }
                        }
                    }),
                    new Ext.form.Text({ 
                        id:'Efectivo-nuevoGastoPanel-nota', 
                        name : 'nota', 
                        label: 'Nota',
                        listeners : {
                            'focus' : function (){

                                kconf = {
                                    type : 'alfa',
                                    submitText : 'Aceptar'
                                };
                                POS.Keyboard.Keyboard( this, kconf );
                            }
                        }
                     }),
                ]},
                new Ext.Button({ id : 'Efectivo-NuevoGasto', ui  : 'action', text: 'Registrar Gasto', margin : 5,  handler : this.nuevoGastoValidator, disabled : false })
        ]
    });
};




/**
 * Validar los datos de la forma de nuevo gasto
 */
Aplicacion.Efectivo.prototype.nuevoAbonoValidator = function ()
{
	//obtener los valores de la forma
	var values = Aplicacion.Efectivo.currentInstance.nuevoAbonoPanel.getValues();
	
	if( !( values.monto && /^-?\d+(\.\d+)?$/.test(values.monto + '') ) ){

        Ext.Anim.run(Ext.getCmp( 'Efectivo-nuevoAbonoPanel-monto' ), 
            'fade', {duration: 250,
            out: true,
            autoClear: true
        });

		return;
	}

    Aplicacion.Efectivo.currentInstance.nuevoAbono( values );

};


/**
 * Inserta el nuevo abono en la BD
 */
Aplicacion.Efectivo.prototype.nuevoAbono = function( data ){

     Ext.Msg.alert("Efectivo","Guardando el nuevo abono");

    Ext.getBody().mask('Guardando nuevo abono ...', 'x-mask-loading', true);

    Ext.Ajax.request({
        url: 'proxy.php',
        scope : this,
        params : {
            action : 606,
            data : Ext.util.JSON.encode( data )
        },
        success: function(response, opts) {
            try{
                r = Ext.util.JSON.decode( response.responseText );
            }catch(e){
                return POS.error(response, e);
            }

            Ext.getBody().unmask(); 

            //limpiar la forma      
            Aplicacion.Efectivo.currentInstance.nuevoAbonoPanel.reset();
            
            //informamos lo que sucedio
            if( r.success == "true" )
            {
                Ext.Msg.alert("Efectivo","Se ha registrado el nuevo abono"); 
            }
            else
            {
                Ext.Msg.alert("Efectivo","Error: " + r.success); 
            }

        },
        failure: function( response ){
            POS.error( response );
        }
    }); 

};


/**
 * Contiene el panel con la forma de nuevo abono
 */
Aplicacion.Efectivo.prototype.nuevoAbonoPanel = null;


/**
 * Pone un panel en nuevoAbonoPanel
 */
Aplicacion.Efectivo.prototype.nuevoAbonoPanelCreator = function (){

	this.nuevoAbonoPanel = new Ext.form.FormPanel({
        items: [{
                xtype: 'fieldset',
                title: 'Ingrese los detalles del nuevo abono',
                instructions: 'Ingrese la cantidad a abonar a sus compras.',
                items: [
                    new Ext.form.Text({ id:'Efectivo-nuevoAbonoPanel-monto', name: 'monto', label: 'Monto', required:true,
                    listeners : {
                        'focus' : function (){
                                kconf = {
                                type : 'num',
                                submitText : 'Aceptar',
                                callback : Aplicacion.Efectivo.currentInstance.nuevoAbonoValidator
                            };
                        POS.Keyboard.Keyboard( this, kconf );
                        }
                    }})
                    
                ]},
                new Ext.Button({ id : 'Efectivo-NuevoAbono', ui  : 'action', text: 'Registrar Abono', margin : 5,  handler : this.nuevoAbonoValidator, disabled : false })
        ]
    });
};














/* ********************************************************
	Nuevo Ingreso
******************************************************** */


/**
 * Validar los datos de la forma de nuevo gasto
 */
Aplicacion.Efectivo.prototype.nuevoIngresoValidator = function ()
{
    //obtener los valores de la forma
    var values = Aplicacion.Efectivo.currentInstance.nuevoIngresoPanel.getValues();
    
    if( !( values.monto && /^-?\d+(\.\d+)?$/.test(values.monto + '') ) ){

        Ext.Anim.run(Ext.getCmp( 'Efectivo-nuevoIngresoPanel-monto' ), 
            'fade', {duration: 250,
            out: true,
            autoClear: true
        });

        return;
    }
    

    if( !values.fecha )
    {
        Ext.Anim.run(Ext.getCmp( 'Efectivo-nuevoIngresoPanel-fecha' ), 
            'fade', {duration: 250,
            out: true,
            autoClear: true
        });

        return;
    }

    if( values.concepto.length < 3 )
    {
        Ext.Anim.run(Ext.getCmp( 'Efectivo-nuevoIngresoPanel-concepto' ), 
            'fade', {duration: 250,
            out: true,
            autoClear: true
        });

        return;
    }

    Aplicacion.Efectivo.currentInstance.nuevoIngreso( values );

};


/**
 * Inserta el nuevo gasto en la BD
 */
Aplicacion.Efectivo.prototype.nuevoIngreso = function( data ){

    Ext.getBody().mask('Guardando nuevo ingreso ...', 'x-mask-loading', true);

    Ext.Ajax.request({
        url: 'proxy.php',
        scope : this,
        params : {
            action : 603,
            data : Ext.util.JSON.encode( data )
        },
        success: function(response, opts) {
            try{
                r = Ext.util.JSON.decode( response.responseText );
            }catch(e){
                return POS.error(response, e);
            }

            Ext.getBody().unmask(); 

            //limpiar la forma      
            Aplicacion.Efectivo.currentInstance.nuevoIngresoPanel.reset();

            //informamos que todo salio bien
            Ext.Msg.alert("Efectivo","Se ha registrado el nuevo ingreso"); 

        },
        failure: function( response ){
            POS.error( response );
        }
    }); 

};



/*
 * Guarda el panel donde estan la forma de nuevo cliente
 **/
Aplicacion.Efectivo.prototype.nuevoIngresoPanel = null;


/*
 * Se llama para crear por primera vez el panel de nuevo cliente
 **/
Aplicacion.Efectivo.prototype.nuevoIngresoPanelCreator = function (  ){

    this.nuevoIngresoPanel = new Ext.form.FormPanel({
        items: [{
                xtype: 'fieldset',
                title: 'Ingrese los detalles del nuevo ingreso',
                instructions: 'Si desea agregar un gasto que no se encuentre en la lista debera pedir una autorizacion.',
                items: [
                    new Ext.form.Text({ 
                        id:'Efectivo-nuevoIngresoPanel-monto', 
                        name: 'monto', 
                        label: 'Monto', 
                        required:true,
                        listeners : {
                            'focus' : function (){

                                kconf = {
                                    type : 'num',
                                    submitText : 'Aceptar'
                                };
                                POS.Keyboard.Keyboard( this, kconf );
                            }
                        } 
                    }),
                    new Ext.form.DatePicker({ 
                        id:'Efectivo-nuevoIngresoPanel-fecha', 
                        name : 'fecha', 
                        label: 'Fecha', 
                        picker : { yearFrom : 2010, yearTo : 2011 }, 
                        required:true 
                    }),
                    new Ext.form.Text({ 
                        id:'Efectivo-nuevoIngresoPanel-concepto', 
                        name: 'concepto', 
                        label: 'Concepto', 
                        required:true,
                        listeners : {
                            'focus' : function (){

                                kconf = {
                                    type : 'alfa',
                                    submitText : 'Aceptar'
                                };
                                POS.Keyboard.Keyboard( this, kconf );
                            }
                        }
                    }),
                    new Ext.form.Text({ 
                        id:'Efectivo-nuevoIngresoPanel-nota', 
                        name : 'nota', 
                        label: 'Nota',
                        listeners : {
                            'focus' : function (){

                                kconf = {
                                    type : 'alfa',
                                    submitText : 'Aceptar'
                                };
                                POS.Keyboard.Keyboard( this, kconf );
                            }
                        }
                    }),
                ]},
                new Ext.Button({ 
                    id : 'Efectivo-NuevoIngreso', 
                    ui  : 'action', text: 'Registrar Ingreso', 
                    margin : 5,  
                    handler : this.nuevoIngresoValidator, 
                    disabled : false 
                })
        ]
    });

};



POS.Apps.push( new Aplicacion.Efectivo() );
