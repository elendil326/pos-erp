
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
    
    //Aplicacion.Operaciones.currentInstance.abonarVentaSucursalPanelCreator();
    
	
	return this;
};




Aplicacion.Operaciones.prototype.getConfig = function (){
	return {
	    text: 'Operaciones',
	    cls: 'launchscreen',
	    items: [
	    {
            text: 'Abono a Prestamo',
            card: Aplicacion.Operaciones.currentInstance.abonarPrestamoEfectivoSucursalPanel,
            leaf: true
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
				    
				    if( informacion.info && informacion.info == "elimination_time" ){
				    
				        Ext.Msg.alert("Eliminar Venta", informacion.reason);
				    
				        return;
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
                    xtype:'fieldset',
                    title:'Seleccione un prestamo para realizar un abono',
                    items:[{
			            xtype: 'list',
			            width : '100%',
            			height: 235,
			            emptyText: "vacio",
                        store: this.listaDePrestamosSucursalStore,
                        itemTpl: '<div class="listaDeAutorizacionesAutorizacion">ID del prestamo : {id_prestamo}&nbsp; Concepto :  {concepto}</div>',
                        grouped: true,
                        indexBar: false,
                        listeners : {
                            "selectionchange"  : function ( view, nodos, c ){
                                if(nodos.length > 0){
                                    if(DEBUG){
                                        console.log("selecciono realizar abono a : ", nodos[0].data);
                                    }
                                    Aplicacion.Operaciones.currentInstance.abonarPrestamosEfectivoSucursalUpdateDetallesPrestamo( nodos[0].data );                            
                                }
                                view.deselectAll();
                            }
                        }
                    }
                    ]
                },{
                    xtype: 'fieldset',
                    title: 'Detalles del prestamo a sucursal',
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


        

POS.Apps.push( new Aplicacion.Operaciones() );






