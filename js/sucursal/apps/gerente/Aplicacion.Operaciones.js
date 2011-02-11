
Aplicacion.Operaciones = function (  ){





	return this._init();
}




Aplicacion.Operaciones.prototype._init = function (){

    if(DEBUG){
		console.log("Operaciones: construyendo");
    }
    
    Aplicacion.Operaciones.currentInstance = this;       
    
    Aplicacion.Operaciones.currentInstance.cancelarVentaPanelCreator();
    
    Aplicacion.Operaciones.currentInstance.prestamoEfectivoPanelCreator(); 
    
    Aplicacion.Operaciones.currentInstance.abonarPrestamoEfectivoSucursalPanelCreator();
    
    Aplicacion.Operaciones.currentInstance.listaDePrestamosSucursalLoad();
    
    Aplicacion.Operaciones.currentInstance.abonarVentaSucursalPanelCreator();
    
	
	return this;
};




Aplicacion.Operaciones.prototype.getConfig = function (){
	return {
	    text: 'Operaciones',
	    cls: 'launchscreen',
	    items: [
	    {
	        text: 'Cobrar a sucursal',
	        items: [{
		        text: 'Abono a Prestamo',
	        	card: Aplicacion.Operaciones.currentInstance.abonarPrestamoEfectivoSucursalPanel,
	        	leaf: true
		    },{
		        text: 'Abono a Venta',
	        	card: Aplicacion.Operaciones.currentInstance.abonarVentaSucursalPanel,
	        	leaf: true
		    }]   
	        
	    },
		{
	        text: 'Prestar efectivo',
	        card: Aplicacion.Operaciones.currentInstance.prestamoEfectivoPanel, 
	        leaf: true
	    },
	    {
	        text: 'Cancelar venta',
	        card: Aplicacion.Operaciones.currentInstance.cancelarVentaPanel,
	        leaf: true
	    }]
	};
};





/* ***************************************************************************
   * Cancelar Venta
   * 
   *************************************************************************** */

    //almacena informacion acerca de la venta a ser eliminada
    Aplicacion.Operaciones.prototype.venta = {
        id_venta : null,
        cliente : null
    };
    
    /**
      * obtiene informacion acerca de la ultima venta y actualiza los paneles donde se despliega la informacion
      * de la venta
      */    
   Aplicacion.Operaciones.prototype.cancelarVentaPanelUpdater = function(){
    
         Ext.Ajax.request({
            url: '../proxy.php',
            scope : this,
            params : {
                action : 803
            },
            success: function(response, opts) {
                try{
                    r = Ext.util.JSON.decode( response.responseText );
                }catch(e){
                    return POS.error(response, e);
                }

                if( r.success ==  false ){
                
                    //TODO: Verificar por que aqui no entra nunca
                    
                    Aplicacion.Operaciones.currentInstance.venta.id_venta = null;      
                    Aplicacion.Operaciones.currentInstance.venta.cliente = null;    
                
                    var html = "<div style='margin-top:20px; margin-bottom:10px;position:relative; width:100%; color: #333; font-weight: bold;text-shadow: white 0px 1px 1px;left:5px; position:relative;float:left;' >No se tiene registro de ninguna venta. </div>";                   
				    
				    Ext.getCmp('Operaciones-cancelarVentaPanel-Tabla').update(html);     

                   Ext.getCmp('Operaciones-cancelarVentaPanel-Form-Cliente').setValue(  "" );
                   
                   Ext.getCmp('Operaciones-cancelarVentaPanel-Form-Subtotal').setValue( "" );
                   
                   Ext.getCmp('Operaciones-cancelarVentaPanel-Form-Descuento').setValue( POS.currencyFormat( "" ) );
                   
                   Ext.getCmp('Operaciones-cancelarVentaPanel-Form-Total').setValue( POS.currencyFormat( "" ) );
                }
                
                    Aplicacion.Operaciones.currentInstance.venta.id_venta = r.id_venta;      
                    Aplicacion.Operaciones.currentInstance.venta.cliente = r.cliente;      
                    
                    var html = "<div style='margin-top:20px; margin-bottom:10px;position:relative; width:100%; color: #333; font-weight: bold;text-shadow: white 0px 1px 1px;left:5px; position:relative;float:left;' > Detalle de la venta  " + r.id_venta  + " </div>";                   
                    
                    html += "<table border=0 style = 'margin-top:10px;' >";
	
	                html += "<tr class='top'>";
	                html +=     "<td align='center'>Descripcion</td>";

	                html +=     "<td align='center'>Cantidad Original</td>";	
	                html +=     "<td align='center' >Precio Original</td>";
	                html +=     "<td align='center'>Cantidad  Procesado</td>";	

	                html +=     "<td align='center' >Precio Procesado</td>";
	                html +=     "<td align='center' >Sub Total</td>";
	                html += "</tr>";
	                
	               detalle_venta = r.detalle_venta;
	               
	               subtotal = 0;
	                
	                for (var i=0; i < detalle_venta.length  ; i++){
		
		                if( i == detalle_venta.length - 1 ){
			                html += "<tr class='last'>";
		                }else{
			                html += "<tr >";		
		                }
		                
		                html += "<td style='width: 30%;' ><b>" + detalle_venta[i].id_producto + "</b> " + detalle_venta[i].descripcion+ "</td>";

		                html += "<td  align='center' style='width: 14%;'> " + detalle_venta[i].cantidad + "  </td>";

		                html += "<td  align='center'  style='width: 14%;'> " + detalle_venta[i].precio + "  </td>";

		                html += "<td  align='center'  style='width: 14%;' > " + detalle_venta[i].cantidad_procesada + "  </td>";

		                html += "<td  align='center'  style='width: 14%;'> " + detalle_venta[i].precio_procesada + " </td>";	 
		                
		                _subtotal = ( detalle_venta[i].cantidad * detalle_venta[i].precio ) + ( detalle_venta[i].cantidad_procesada * detalle_venta[i].precio_procesada ) ;
		                
		                subtotal += _subtotal;
		                
		                html += "<td  align='center'  style='width: 14%;'> " + POS.currencyFormat( _subtotal )+ " </td>";	 
		
		                html += "</tr>";
	                }
	
	
	                var descuento = ( subtotal * r.cliente.descuento / 100 );
	
	                html += "</table>";
	                
                    Ext.getCmp('Operaciones-cancelarVentaPanel-Tabla').update(html);     
                    
                   Ext.getCmp('Operaciones-cancelarVentaPanel-Form-Cliente').setValue(  r.cliente.nombre );
                   
                   Ext.getCmp('Operaciones-cancelarVentaPanel-Form-Subtotal').setValue( POS.currencyFormat( subtotal ) );
                   
                   Ext.getCmp('Operaciones-cancelarVentaPanel-Form-Descuento').setValue( POS.currencyFormat( descuento ) );
                   
                   Ext.getCmp('Operaciones-cancelarVentaPanel-Form-Total').setValue( POS.currencyFormat( subtotal - descuento ) );
                    


            },
            failure: function( response ){
                POS.error( response );                                
                
            }
        }); 
        
        
       
        
    };

    Aplicacion.Operaciones.prototype.cancelarVentaPanelCreator = function(){
    
        this.cancelarVentaPanel = new Ext.Panel({         
            ui : "dark",
		    modal: false,
            floating: false,
            scroll:true,    
            id : "Operaciones-cancelarVentaPanel",
            style:{
                padding:'10px'
            },
            listeners : {
			    "show" : function(){
			        Aplicacion.Operaciones.currentInstance.cancelarVentaPanelUpdater();
			    }
		    },      
            items: [
                {
                title :"",
                cls : "Tabla",
                items:[{
                    id: 'Operaciones-cancelarVentaPanel-Tabla',				
			        html : "<div style='margin-top:20px; margin-bottom:10px;position:relative; width:100%; color: #333; font-weight: bold;text-shadow: white 0px 1px 1px;left:5px; position:relative;float:left;' >No se tiene registro de ninguna venta. </div>"
                }]
                
                },
                {
                    xtype: 'fieldset',
                    title: 'Detalles del Cliente',
                    items:[
                        new Ext.form.Text({name: 'nombre', label: 'Cliente', id:'Operaciones-cancelarVentaPanel-Form-Cliente'}),
                        new Ext.form.Text({name: 'subtotal', label: 'Subtotal', id:'Operaciones-cancelarVentaPanel-Form-Subtotal'}),
                        new Ext.form.Text({name: 'descuento', label: 'Descuento', id:'Operaciones-cancelarVentaPanel-Form-Descuento'}),
                        new Ext.form.Text({name: 'total', label: 'Total', id:'Operaciones-cancelarVentaPanel-Form-Total'})
                    ]
                },
            new Ext.Button({ ui  : 'action', text: 'Cancelar venta', margin : 5,handler : Aplicacion.Operaciones.currentInstance.doCancelarVenta  })
        ]}); 
        
       // Ext.getCmp('Operaciones-cancelarVentaPanel').update(html);
    
    };
    


    Aplicacion.Operaciones.prototype.doCancelarVenta = function(){
    
        Ext.Msg.confirm("Eliminar Venta", "¿Esta usted seguro de que desea eliminar la venta?", function( action ){
            
            if( action == "yes" )
            {
                Aplicacion.Operaciones.currentInstance.eliminarVenta();
            }           
            
        });
    
    };
    
    
    /**
    * hace una ajax para eliminar la venta
    */
    Aplicacion.Operaciones.prototype.eliminarVenta = function(){
                
         Ext.Ajax.request({
		    url: '../proxy.php',
		    scope : this,
		    params : {
			    action : 804,
			    id_venta : Aplicacion.Operaciones.currentInstance.venta.id_venta
		    },
		    success: function(response, opts) {
			    try{
				    informacion = Ext.util.JSON.decode( response.responseText );				
			    }catch(e){
				    return POS.error(response, e);
			    }
			
			    if( !informacion.success ){
				    //volver a intentar
				    if(DEBUG){
					    console.log("obtenicion de la informacion sin exito.");
				    }				    
				    
				    var html = "<div style='margin-top:20px; margin-bottom:10px;position:relative; width:100%; color: #333; font-weight: bold;text-shadow: white 0px 1px 1px;left:5px; position:relative;float:left;' >No se tiene registro de ninguna venta. </div>";                   
				    
				    Ext.getCmp('Operaciones-cancelarVentaPanel-Tabla').update(html);     

                   Ext.getCmp('Operaciones-cancelarVentaPanel-Form-Cliente').setValue(  "" );
                   
                   Ext.getCmp('Operaciones-cancelarVentaPanel-Form-Subtotal').setValue( "" );
                   
                   Ext.getCmp('Operaciones-cancelarVentaPanel-Form-Descuento').setValue( POS.currencyFormat( "" ) );
                   
                   Ext.getCmp('Operaciones-cancelarVentaPanel-Form-Total').setValue( POS.currencyFormat( "" ) );
                   
				    return;

			    }
			
			    if(DEBUG){
				    console.log("obtenicion de la informacion exitosa " );
			    }
						
			    //actualizamos el panel del detalle de la venta
			    Aplicacion.Operaciones.currentInstance.cancelarVentaPanelUpdater();
			   

		    },
		    failure: function( response ){
			    POS.error( response );
		    }
	    });
	    
    };






/* ***************************************************************************
   * Prestar Efectivo
   * 
   *************************************************************************** */

    //panel de 
    Aplicacion.Operaciones.prototype.prestamoEfectivoPanel = null;

    /**
        *
        *
        */
    Aplicacion.Operaciones.prototype.prestamoEfectivoPanelCreator = function (){


        this.prestamoEfectivoPanel = new Ext.form.FormPanel({
                listeners : {
                        "show" : function (){
                            Ext.getCmp("Operaciones-prestamoEfectivo-sucursal").setOptions( Aplicacion.Efectivo.currentInstance.sucursalesLista );
                        }
                    },
                items: [{

                    xtype: 'fieldset',
                    title: 'Nuevo prestamo de efectivo',
                    instructions: 'Todos los campos son necesarios para una nueva autorizacion.',
                    items: [
                        new Ext.form.Select({ 
                            id:'Operaciones-prestamoEfectivo-sucursal',
                            name : 'sucursal',
                            label: 'Sucursal',
                            required:true,
                            options: [ {text : "Seleccione una sucursal", value : "" } ]
                        }),
                        new Ext.form.Text({
                            id: 'Operaciones-prestamoEfectivo-concepto',
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
                            id: 'Operaciones-prestamoEfectivo-monto',
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
                        })          
                    ]},
                    new Ext.Button({ ui  : 'action', text: 'Registrar Prestamo de Efectivo', margin : 5,  handler : this.nuevoPrestamoEfectivoValidator, disabled : false })
            ]});

    };



/**
        *
        *
        */
    Aplicacion.Operaciones.prototype.nuevoPrestamoEfectivoValidator = function ()
    {
        //obtener los valores de la forma
        var values = Aplicacion.Operaciones.currentInstance.prestamoEfectivoPanel.getValues();

        //validamos si selecciono una sucursal
        if( !( values.sucursal != "") )
        {
            Ext.Anim.run(Ext.getCmp( 'Operaciones-prestamoEfectivo-sucursal' ),
                'fade', {duration: 250,
                out: true,
                autoClear: true
            });

            return;
        }

        //valdamos si selecciono un concepto
        if( values.concepto == "" )
        {
            Ext.Anim.run(Ext.getCmp( 'Operaciones-prestamoEfectivo-concepto' ),
                'fade', {duration: 250,
                out: true,
                autoClear: true
            });

            return;
        }

        if( isNaN( parseFloat( values.monto) ) || parseFloat( values.monto) <= 0){

            Ext.Anim.run(Ext.getCmp( 'Operaciones-prestamoEfectivo-monto' ), 
                'fade', {duration: 250,
                out: true,
                autoClear: true
            });

            return;
        }        

        Aplicacion.Operaciones.currentInstance.nuevaOperacionInterSucursalEfectivo( values );

    };



    /**
        *
        *
        */
    Aplicacion.Operaciones.prototype.nuevaOperacionInterSucursalEfectivo = function( data ){

        Ext.getBody().mask('Guardando nuevo prestamo de efectivo ...', 'x-mask-loading', true);

        Ext.Ajax.request({
            url: '../proxy.php',
            scope : this,
            params : {
                action : 607,
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
                Aplicacion.Operaciones.currentInstance.prestamoEfectivoPanel.reset();


                //informamos lo que sucedio
                if( r.success)
                {
                    Ext.Msg.alert("Operaciones","Se ha registrado el nuevo prestamo de efectivo"); 
                }
                else
                {
                    Ext.Msg.alert("Operaciones","Error: " + r.success); 
                }
            },
            failure: function( response ){
                POS.error( response );
            }
        }); 

    };
    
    
    
    
    
   /* ***************************************************************************
   * Abonar a Prestamo Sucursal
   * 
   *************************************************************************** */

    /**
     * Registra el model para listaDePrestamosSucursal
     */
    Ext.regModel('listaDePrestamosSucursalModel', {
        fields: [
            { name: 'id_prestamo',     type: 'int'},
            { name: 'concepto',      type: 'string'}
        ]
    });



    /**
     * Contiene un objeto con la lista de autorizaciones actual, para no estar
     * haciendo peticiones a cada rato
     */
    Aplicacion.Operaciones.prototype.listaDePrestamosSucursal = {
        lista : null,
        lastUpdate : null,
        hash: null
    };

    /**
     * Es el Store que contiene la lista de prestamos a sucursales cargada con una peticion al servidor.
     * Recibe como parametros un modelo y una cadena que indica por que se va a sortear (ordenar) 
     * en este caso ese filtro es dado por 
     * @return Ext.data.Store
     */
    Aplicacion.Operaciones.prototype.listaDePrestamosSucursalStore = new Ext.data.Store({
        model: 'listaDePrestamosSucursalModel' ,
        sorters: 'sucursal',
        

        getGroupString : function(record) {
            return record.get("sucursal");
        }
    });



    /**
     * Leer la lista de autorizaciones del servidor mediante AJAX
     */

    Aplicacion.Operaciones.prototype.listaDePrestamosSucursalLoad = function (){
	
	    if(DEBUG){
		    console.log("Actualizando lista de autorizaciones ....");
	    }
	
	    Ext.Ajax.request({
		    url: '../proxy.php',
		    scope : this,
		    params : {
			    action : 609
		    },
		    success: function(response, opts) {
			    try{
				    prestamos = Ext.util.JSON.decode( response.responseText );				
			    }catch(e){
				    return POS.error(e);
			    }
			
			
			    //volver a intentar			
			    if( !prestamos.success ){
				    return POS.error(prestamos);
			    }
			
			
			    this.listaDePrestamosSucursal.lista = prestamos.data;
			    this.listaDePrestamosSucursal.lastUpdate = Math.round(new Date().getTime()/1000.0);
			    this.listaDePrestamosSucursal.hash = prestamos.hash;

			    //agregarlo en el store
			    //console.log( response.responseText )
			    //console.log( "ya", prestamos.data , this.listaDePrestamosSucursalStore    )
			    
			    this.listaDePrestamosSucursalStore.loadData( prestamos.data );

			    if(DEBUG){
                    console.log("Lista de prestamos retrived !", prestamos, this.listaDePrestamosSucursalStore );
                }
		    },
		    failure: function( response ){
			    POS.error( response );
		    }
	    });

    };

    //panel de 
    Aplicacion.Operaciones.prototype.abonarPrestamoEfectivoSucursalPanel = null;

    /**
        *
        *
        */
    Aplicacion.Operaciones.prototype.abonarPrestamoEfectivoSucursalPanelCreator = function (){


        this.abonarPrestamoEfectivoSucursalPanel = new Ext.Panel({
            items: [{
			    xtype: 'list',
			    width : '100%',
    			height: 220,
			    emptyText: "vacio",
                store: this.listaDePrestamosSucursalStore,
                itemTpl: '<div class="listaDeAutorizacionesAutorizacion">ID del prestamo : {id_prestamo}&nbsp; Concepto :  {concepto}</div>',
                grouped: true,
                indexBar: false,
                listeners : {
                    "selectionchange"  : function ( view, nodos, c ){
                        if(nodos.length > 0){
                            Aplicacion.Operaciones.currentInstance.abonarPrestamosEfectivoSucursalUpdateDetallesPrestamo( nodos[0].data );                            
                        }
                        view.deselectAll();
                    }
                }
            },{
                    xtype: 'fieldset',
                    title: 'Detalles del Prestamo a Sucursal',
                    //instructions: 'Todos los campos son necesarios para una nueva autorizacion.',
                    items: [                       
                        new Ext.form.Text({
                            id: 'Operaciones-abonarPrestamoEfectivoSucursal-prestamo',
                            label: 'ID Prestamo',
                            name: 'prestamo'
                        }),
                        new Ext.form.Text({
                            id: 'Operaciones-abonarPrestamoEfectivoSucursal-sucursal',
                            label: 'Sucursal',
                            name: 'sucursal'
                        }),
                        new Ext.form.Text({
                            id: 'Operaciones-abonarPrestamoEfectivoSucursal-saldo',
                            label: 'Saldo',
                            name: 'saldo'
                        }),
                        new Ext.form.Text({
                            id: 'Operaciones-abonarPrestamoEfectivoSucursal-concepto',
                            label: 'Concepto',
                            name: 'concepto'
                        }),
                        new Ext.form.Text({
                            id: 'Operaciones-abonarPrestamoEfectivoSucursal-monto',
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
                        })          
                    ]},
                new Ext.Button({ ui  : 'action', text: 'Abonar', margin : 5,  handler : this.abonarPrestamosEfectivoSucursalValidator, disabled : false })
            ]            
        });
    };




    /**
        *
        *
        */
    Aplicacion.Operaciones.prototype.abonarPrestamosEfectivoSucursalUpdateDetallesPrestamo = function( prestamo ){
        
        Ext.getCmp('Operaciones-abonarPrestamoEfectivoSucursal-prestamo').setValue( prestamo.id_prestamo );
        Ext.getCmp('Operaciones-abonarPrestamoEfectivoSucursal-sucursal').setValue( prestamo.sucursal );
        Ext.getCmp('Operaciones-abonarPrestamoEfectivoSucursal-concepto').setValue( prestamo.concepto );
        Ext.getCmp('Operaciones-abonarPrestamoEfectivoSucursal-saldo').setValue( prestamo.saldo ); 
        Ext.getCmp('Operaciones-abonarPrestamoEfectivoSucursal-monto').setValue("");
        
    }; 


    
    /**
        *
        *
        */        
    Aplicacion.Operaciones.prototype.abonarPrestamosEfectivoSucursalValidator = function(){                
               
        var transaccion = {
            monto: null,
            id_prestamo : null,
            saldo : null,
            cambio : null
        };
        
        transaccion.monto = parseFloat( Ext.getCmp('Operaciones-abonarPrestamoEfectivoSucursal-monto').getValue() );
        transaccion.id_prestamo = Ext.getCmp('Operaciones-abonarPrestamoEfectivoSucursal-prestamo').getValue();
        transaccion.saldo = parseFloat( Ext.getCmp('Operaciones-abonarPrestamoEfectivoSucursal-saldo').getValue() );      
        
        if( transaccion.monto >= transaccion.saldo )
        {
            transaccion.cambio = transaccion.monto - transaccion.saldo;
            transaccion.monto = transaccion.saldo;            
            transaccion.saldo =  parseFloat( 0 );
        }
        else
        {
             transaccion.cambio =  parseFloat( 0 );        
            transaccion.saldo = transaccion.saldo - transaccion.monto;
        }
          
       
        
        //validamos el monto
        if( isNaN( parseFloat( transaccion.monto) ) || parseFloat( transaccion.monto) <= 0 ){

            Ext.Anim.run(Ext.getCmp('Operaciones-abonarPrestamoEfectivoSucursal-monto'), 
                'fade', {duration: 250,
                out: true,
                autoClear: true
            });

            return;
        }
        
        Aplicacion.Operaciones.currentInstance.abonarPrestamosEfectivoSucursalNuevoAbono( transaccion );
                
    };


    /**
        *
        *
        */
    Aplicacion.Operaciones.prototype.abonarPrestamosEfectivoSucursalNuevoAbono = function( transaccion ){
    
        if(DEBUG){
		    console.log("Ingresando nuevo Abono a prestamo sucursal..");
	    }
	
	    Ext.Ajax.request({
		    url: '../proxy.php',
		    scope : this,
		    params : {
			    action : 608,
			    monto : transaccion.monto,
			    id_prestamo : transaccion.id_prestamo
		    },
		    success: function(response, opts) {
		    
			    try{
				    abono = Ext.util.JSON.decode( response.responseText );				
			    }catch(e){
				    return POS.error(e);
			    }
			
			
			    //volver a intentar			
			    if( !abono.success ){
				    //return POS.error(abono);
				    Ext.Msg.alert("Operaciones",abono.reason);
				    
			    }
			
			        if(DEBUG){
                        console.warn("IMPRMIR TICKET");
			        }
			
			    Ext.Msg.alert( "Operaciones","Abonado: " + POS.currencyFormat(transaccion.monto) + "<br>Su cambio: " + POS.currencyFormat(transaccion.cambio) + "<br>Saldo Pendiente: " + POS.currencyFormat(transaccion.saldo) );
                
                //actualizamos la lista
                Aplicacion.Operaciones.currentInstance.listaDePrestamosSucursalLoad();
                
                //limpiamos el formulario
                Ext.getCmp('Operaciones-abonarPrestamoEfectivoSucursal-prestamo').setValue( "" );
                Ext.getCmp('Operaciones-abonarPrestamoEfectivoSucursal-sucursal').setValue( "" );
                Ext.getCmp('Operaciones-abonarPrestamoEfectivoSucursal-concepto').setValue( "" );
                Ext.getCmp('Operaciones-abonarPrestamoEfectivoSucursal-saldo').setValue( "" ); 
                Ext.getCmp('Operaciones-abonarPrestamoEfectivoSucursal-monto').setValue("");
			   
		    },
		    failure: function( response ){
			    POS.error( response );
		    }
	    });
    
    };




/* ***************************************************************************
   * Abonar a Venta Sucursal
   * 
   *************************************************************************** */
   
    Aplicacion.Operaciones.prototype.abonarVentaSucursalPanel = null;


    /**
        *
        *
        */
    Aplicacion.Operaciones.prototype.abonarVentaSucursalPanelCreator = function (){


        this.abonarVentaSucursalPanel = new Ext.form.FormPanel({
            listeners : {
			    "show" : function(){
			        Ext.getCmp("Operaciones-abonarVentaSucursalPanel-abonar-selectSucursal").setOptions( Aplicacion.Efectivo.currentInstance.sucursalesLista );
			    }
		    },   
		    items: [{
			    xtype: 'fieldset',
			    title: 'Abonar a venta',
			    id : 'Operaciones-abonarVentaSucursalPanel-abonar',
			    instructions: 'Seleccione una venta para ver sus detalles.',
			    items: [
			        {
				        xtype: 'selectfield',
				        id : "Operaciones-abonarVentaSucursalPanel-abonar-selectSucursal",
				        name: 'optionsSucursal',
				        label : 'Sucursal', 
				        options: [{text : "Seleccione una sucursal", value : "" }],
				        listeners : {
						        "change" : function(a,b) {
						            Aplicacion.Operaciones.currentInstance.abonarVentaSucursalPanelLoadVentas( b );
						        } 
					    }
				    },{
				        xtype: 'selectfield',
				        id : "Operaciones-abonarVentaSucursalPanel-abonar-selectVenta",
				        name: 'optionsVenta',
				        label : "Venta",
				        options: [ {text : "Seleccione una venta a credito de la lista", value : ""} ],
				        listeners : {
						        "change" : function(a,b) {
						            Aplicacion.Operaciones.currentInstance.abonarVentaSucursalPanelDetalleVenta( b );
						        } 
					    }
				    }]
			    },{
				    xtype: 'fieldset',				    
				    hidden : true,
				    title: 'Detalle de la Venta',
				    id : 'Operaciones-abonarVentaSucursalPanel-detalleVenta',
				    items: [
					    new Ext.form.Text({ name: 'fecha', label: 'Fecha',  id : 'Operaciones-abonarVentaSucursalPanel-detalleVenta-fecha' }),
					    new Ext.form.Text({ name: 'sucursal', label: 'Sucursal', id : 'Operaciones-abonarVentaSucursalPanel-detalleVenta-sucursal' }),
					    new Ext.form.Text({ name: 'cajero', label: 'Vendedor', id : 'Operaciones-abonarVentaSucursalPanel-detalleVenta-cajero' }),
					    new Ext.form.Text({ name: 'total', label: 'Total', id: 'Operaciones-abonarVentaSucursalPanel-detalleVenta-total' }),
					    new Ext.form.Text({ name: 'abonado', label: 'Abonado', id:'Operaciones-DetallesVentaCredito-detalleVenta-abonado' }),
					    new Ext.form.Text({ name: 'detalleVentaSaldo', label: 'Saldo', id:'Operaciones-DetallesVentaCredito-detalleVenta-saldo' }) 
			        ]
			    },{
				    xtype: 'fieldset',
				    title: 'Abonar a la venta',
				    id : 'Operaciones-abonarVentaSucursalPanel-abono',
				    hidden : true,
				    items: [
					    new Ext.form.Text({ name: 'abonoSaldo', label: 'Saldo', id: 'Operaciones-abonarVentaSucursalPanel-abono-saldo' }),
					    {
				            xtype: 'selectfield',
				            id : 'Operaciones-abonarVentaSucursalPanel-abono-tipoPago',
				            name: 'abonoTipoPago',
				            label : 'Tipo de pago',
				            options: [ 
				                {
				                    text : "Seleccione un tipo de pago", value : ""
				                },{
				                    text : "Efectivo", value : "efectivo"
				                },{
				                    text : "Cheque", value : "cheque"
				                } 
				            ]
				        },
					    new Ext.form.Text({ id: 'Operaciones-abonarVentaSucursalPanel-abono-monto', name: 'monto', label: 'Monto',
					        listeners : {
                                'focus' : function (){
                                    kconf = {
                                        type : 'num',
                                        submitText : 'Aceptar'
                                    };
                                    POS.Keyboard.Keyboard( this, kconf );
                                }
                            }
                        })
				    ]
			    },

			    new Ext.Button({ id : 'Operaciones-abonarVentaSucursalPanelBoton', ui  : 'action', text: 'Abonar', margin : 15, handler : this.abonarVentaSucursalShowPanelAbonar, hidden : true }),
			    new Ext.Button({ id : 'Operaciones-abonarVentaSucursalPanelBotonAceptar', ui  : 'action', text: 'Abonar', margin : 15, handler : this.abonarVentaSucursalValidator, hidden : true }),
			    new Ext.Button({ id : 'Operaciones-abonarVentaSucursalPanelBotonCancelar', ui  : 'drastic', text: 'Cancelar', margin : 15, handler : this.abonarVentaSucursalCancelar, hidden : true })


		    ]
		});

    };

    //almacena los detalels de la venta a credito de la sucursal
    Aplicacion.Operaciones.prototype.detalleVentaCreditoSucursal = null;

    /**
        *
        *
        */
    Aplicacion.Operaciones.prototype.abonarVentaSucursalPanelLoadVentas = function( id_cliente ){
    
    
        cid = id_cliente * -1;

	    lista = Aplicacion.Clientes.currentInstance.listaDeCompras.lista;

	    ventasCredito  = [{
		    text : "Seleccione una venta a credito de la lista",
		    value : ""
	    }];

        //buscar en todas la ventas
	    for (var i = lista.length - 1; i >= 0; i--){
            //si la venta es de este cliente, y es a credito y tiene algun saldo, mostrarla en la lista
		    if ( lista[i].id_cliente == cid && lista[i].tipo_venta  == "credito" ) {
                
                if(parseFloat(lista[i].total) != parseFloat(lista[i].pagado)){
			        ventasCredito.push( {
				        text : "Venta " + lista[i].id_venta + " ( "+lista[i].fecha+" ) ",
				        value : lista[i].id_venta
			        });    
                }

		    }
	    }
    
        //actualizamos el combo de las ventas
        Ext.getCmp('Operaciones-abonarVentaSucursalPanel-abonar-selectVenta').setOptions( ventasCredito );
    
    };        



    /**
        *
        *
        */
    Aplicacion.Operaciones.prototype.abonarVentaSucursalPanelDetalleVenta = function( id_venta ){    
    
        if( id_venta == "" ){
            //ocultamos el panel del detalle de la venta
            Ext.getCmp('Operaciones-abonarVentaSucursalPanel-detalleVenta').hide();
            Ext.getCmp('Operaciones-abonarVentaSucursalPanelBoton').hide();
            return;
        }
    
        //obtenemos el formualrio del detalle de la venta a sucursal
        var values = Aplicacion.Operaciones.currentInstance.abonarVentaSucursalPanel.getFields();
        
        //obtenemos todas las ventas
        //TODO: hay qeu optimizar esta lista, ya qeu se jala todas las ventas co un getAll
        var ventas = Aplicacion.Clientes.currentInstance.listaDeCompras.lista;
                
        
        //iteramos todas las ventas en busca de la venta que selecciono
        for( var i = 0; i < ventas.length; i++ )
        {
            if( ventas[i].id_venta == id_venta )
            {
                break;
            }    
        }
        
        //guardamos el detalle de la venta
        Aplicacion.Operaciones.currentInstance.detalleVentaCreditoSucursal = ventas[i];
                
        //llenamos el formulario del detalle de la venta
        values.fecha.setValue( ventas[i].fecha );
        values.sucursal.setValue( ventas[i].sucursal );
        values.cajero.setValue( ventas[i].cajero );
        values.total.setValue( POS.currencyFormat( ventas[i].total ) );
        values.abonado.setValue( POS.currencyFormat( ventas[i].pagado ) );
        values.abonoSaldo.setValue( POS.currencyFormat( ventas[i].total - ventas[i].pagado ) );
        values.detalleVentaSaldo.setValue( POS.currencyFormat( ventas[i].total - ventas[i].pagado ) );
    
        //mostramos el panel del detalle de la venta
        Ext.getCmp('Operaciones-abonarVentaSucursalPanel-detalleVenta').show()
        Ext.getCmp('Operaciones-abonarVentaSucursalPanelBoton').show();
        
        
    };


    /**
        *
        *
        */
    Aplicacion.Operaciones.prototype.abonarVentaSucursalShowPanelAbonar = function(){
    
        //ocultamos el formulario de abonar a la venta
        Ext.getCmp('Operaciones-abonarVentaSucursalPanel-abonar').hide();
    
         //ocultamos el panel del detalle de la venta
        Ext.getCmp('Operaciones-abonarVentaSucursalPanel-detalleVenta').hide();
        Ext.getCmp('Operaciones-abonarVentaSucursalPanelBoton').hide();
    
        //mostramos el panel del abono a la venta  acredito a sucursal
        Ext.getCmp('Operaciones-abonarVentaSucursalPanel-abono').show();
        Ext.getCmp('Operaciones-abonarVentaSucursalPanelBotonAceptar').show();
		Ext.getCmp('Operaciones-abonarVentaSucursalPanelBotonCancelar').show();
        
    };        


    /**
        *
        *
        */
    Aplicacion.Operaciones.prototype.abonarVentaSucursalValidator = function(){
    
    
        if( Ext.getCmp('Operaciones-abonarVentaSucursalPanel-abono-tipoPago').getValue() == ""  )
        {
            Ext.Anim.run(Ext.getCmp('Operaciones-abonarVentaSucursalPanel-abono-tipoPago'),
                'fade', {duration: 250,
                out: true,
                autoClear: true
            });

            return;
        }
    
       var monto = parseFloat( Ext.getCmp('Operaciones-abonarVentaSucursalPanel-abono-monto').getValue() );
       
       if( isNaN( parseFloat( monto) ) || parseFloat( monto) <= 0){

            Ext.Anim.run(Ext.getCmp('Operaciones-abonarVentaSucursalPanel-abono-monto'),
                'fade', {duration: 250,
                out: true,
                autoClear: true
            });

            return;
        }        
        
        
        //obtenemos el formualrio del detalle de la venta a sucursal
        var values = Aplicacion.Operaciones.currentInstance.abonarVentaSucursalPanel.getFields();
        
        //validamos que si el abono excede el saldo, solos e abone a la cuenta lo qeu resta del saldo

        var transaccion = {
            abono : null,
            cambio : null,
            abonado: parseFloat( Aplicacion.Operaciones.currentInstance.detalleVentaCreditoSucursal.pagado ),
            saldo : parseFloat( Aplicacion.Operaciones.currentInstance.detalleVentaCreditoSucursal.total ) - parseFloat( Aplicacion.Operaciones.currentInstance.detalleVentaCreditoSucursal.pagado )
        }

        if( monto > transaccion.saldo )
        {
            transaccion.abono = transaccion.saldo;
            transaccion.cambio = monto -transaccion.saldo;
            transaccion.abonado = parseFloat(transaccion.abonado + transaccion.abono );
            transaccion.saldo = parseFloat(0);
        }
        else
        {
            transaccion.abono = monto;
            transaccion.cambio = 0;
            transaccion.saldo = transaccion.saldo - monto;
            transaccion.abonado =  parseFloat(transaccion.abonado +  monto );
        }
        
        
        Aplicacion.Operaciones.currentInstance.doAbonarVentaSucursal ( transaccion );
       
    };        
    
    
    /**
        *
        *
        */
    Aplicacion.Operaciones.prototype.doAbonarVentaSucursal = function( transaccion ){        
    
        Ext.Ajax.request({
		    url: '../proxy.php',
		    scope : this,
		    params : {
			    action : 305,
			    data : Ext.util.JSON.encode({
						    id_venta : Aplicacion.Operaciones.currentInstance.detalleVentaCreditoSucursal.id_venta,
			        		monto : parseFloat( transaccion.abono ),
						    tipo_pago: Ext.getCmp("Operaciones-abonarVentaSucursalPanel-abono-tipoPago").getValue()
					    })
		    },
		    success: function(response, opts) {

                Ext.Msg.alert( "Abono a venta","Abona: " + POS.currencyFormat(transaccion.abono) + "<br>Su cambio: " + POS.currencyFormat(transaccion.cambio) + "<br>Saldo Pendiente: " + POS.currencyFormat(transaccion.saldo) );

			    if(DEBUG){
                	console.warn("IMPRMIR TICKET");				
			    }


			    //cargar la lista de compras de los clientes con los nuevos detalles de las ventas
			    Aplicacion.Clientes.currentInstance.listaDeComprasLoad();


                //Actualizamos los valores de lso campos Abonado y saldo amnualmente ya que se ejecuta primero
                //creditoDeClientesOptionChange y no alcanza a cargarse la lista de compras conn los nuevo valores
                
                //Aplicacion.Operaciones.currentInstance.abonarVentaSucursalPanelLoadVentas( Ext.getCmp('Operaciones-abonarVentaSucursalPanel-abonar-selectVenta').getValue() );
                //Aplicacion.Operaciones.currentInstance.abonarVentaSucursalPanelDetalleVenta ( Ext.getCmp('Operaciones-abonarVentaSucursalPanel-abonar-selectSucursal').getValue() );
                Aplicacion.Operaciones.currentInstance.abonarVentaSucursalPanelDetalleVenta( Aplicacion.Operaciones.currentInstance.detalleVentaCreditoSucursal.id_venta );
                
                //Ext.getCmp( "Operaciones-abonarVentaSucursalPanel-detalleVenta-total" ).setValue( POS.currencyFormat( Aplicacion.Operaciones.currentInstance.detalleVentaCreditoSucursal.total ) );
                Ext.getCmp( "Operaciones-DetallesVentaCredito-detalleVenta-abonado" ).setValue( POS.currencyFormat( transaccion.abonado ) );
                Ext.getCmp( "Operaciones-DetallesVentaCredito-detalleVenta-saldo" ).setValue( POS.currencyFormat(transaccion.saldo) );
                Ext.getCmp( "Operaciones-abonarVentaSucursalPanel-abono-saldo" ).setValue( POS.currencyFormat(transaccion.saldo) );
                Ext.getCmp('Operaciones-abonarVentaSucursalPanel-abono-monto').setValue("");

                Ext.getCmp('Operaciones-abonarVentaSucursalPanel-abonar-selectVenta').setValue("");
                Ext.getCmp('Operaciones-abonarVentaSucursalPanel-abonar-selectSucursal').setValue("");

                //mostramos el panel de detalle de venta
			    Aplicacion.Operaciones.currentInstance.abonarVentaSucursalCancelar();

		    },
		    failure: function( response ){
			    return POS.error( response );
		    }
	    });
    
    };
    
    
    /**
        *
        *
        */
    Aplicacion.Operaciones.prototype.abonarVentaSucursalCancelar= function(){
        
        //
        Ext.getCmp('Operaciones-abonarVentaSucursalPanel-abonar').show();
        
        //mostramos el panel del detalle de la venta
        Ext.getCmp('Operaciones-abonarVentaSucursalPanel-detalleVenta').show();
        Ext.getCmp('Operaciones-abonarVentaSucursalPanelBoton').hide();

         //ocultamos el panel del abono a la venta  acredito a sucursal
        Ext.getCmp('Operaciones-abonarVentaSucursalPanel-abono').hide();
        Ext.getCmp('Operaciones-abonarVentaSucursalPanelBotonAceptar').hide();
		Ext.getCmp('Operaciones-abonarVentaSucursalPanelBotonCancelar').hide();				 
        
        
    };      
        
        
        

/* ***************************************************************************
   * Venta a Credito a Sucursal
   * 
   *************************************************************************** */

    
    
    
    
    
    
        

POS.Apps.push( new Aplicacion.Operaciones() );






