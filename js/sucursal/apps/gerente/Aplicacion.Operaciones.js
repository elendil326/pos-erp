
Aplicacion.Operaciones = function (  ){





	return this._init();
}




Aplicacion.Operaciones.prototype._init = function (){

    if(DEBUG){
		console.log("Operaciones: construyendo");
    }
    
    Aplicacion.Operaciones.currentInstance = this;
    
   
    
    Aplicacion.Operaciones.currentInstance.cancelarVentaPanelCreator();
     
	
	return this;
};




Aplicacion.Operaciones.prototype.getConfig = function (){
	return {
	    text: 'Operaciones',
	    cls: 'launchscreen',
	    items: [{
	        text: 'Vender a sucursal',
	        card: null,
	        leaf: true
	    },
	    {
	        text: 'Cobrar a sucursal',
	        card: null,
	        leaf: true
	    },
		{
	        text: 'Prestar efectivo',
	        card: null,
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

    Aplicacion.Operaciones.prototype.detalle_venta =null;
    
    
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

                if( r.success ){
                    Aplicacion.Operaciones.currentInstance.detalle_venta  = r.detalle_venta;      
                    
                    var html = "<div style='margin-top:20px; margin-bottom:10px;position:relative; width:100%; color: #333; font-weight: bold;text-shadow: white 0px 1px 1px;left:15px; position:relative;float:left;' > Venta  " + r.id_venta  + " - Cliente " + r.cliente.nombre  + " </div>";                   
                    
                    html += "<table border=0 style = 'margin-top:10px;' >";
	
	                html += "<tr class='top'>";
	                html +=     "<td align='center'>Descripcion</td>";

	                html +=     "<td align='center'>Cantidad Original</td>";	
	                html +=     "<td align='center' >Precio Original</td>";
	                html +=     "<td align='center'>Cantidad  Procesado</td>";	

	                html +=     "<td align='center' >Precio Procesado</td>";
	                html +=     "<td align='center' >Sub Total</td>";
	                html += "</tr>";
	                
	               detalle_venta = Aplicacion.Operaciones.currentInstance.detalle_venta;
	               
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
	                
	                html += "<div style='margin-top:20px; margin-bottom:20px;position:relative; width:100%; color: #333; font-weight: bold;text-shadow: white 0px 1px 1px;left:15px; position:relative;float:left;' > SubTotal " + POS.currencyFormat( subtotal )  + " - Descuento " + POS.currencyFormat( descuento ) + " - Total " + POS.currencyFormat( subtotal - descuento )  + "</div>";
	                
	                //html += "<div style='margin-top:10px; margin-bottom:0px;position:relative; width:100%; color: #333; font-weight: bold;text-shadow: white 0px 1px 1px;left:15px; position:relative;float:left;' > Descuento  " + POS.currencyFormat( descuento ) + "</div>";
	                
	                //html += "<div style='margin-top:10px; margin-bottom:10px;position:relative; width:100%; color: #333; font-weight: bold;text-shadow: white 0px 1px 1px;left:15px; position:relative;float:left;' > Total  " + POS.currencyFormat( subtotal - descuento )  + "</div>";
                    
                    Ext.getCmp('Operaciones-cancelarVentaPanel-Tabla').update(html);     
                    
                }

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
            listeners : {
			    "show" : function(){
			        Aplicacion.Operaciones.currentInstance.cancelarVentaPanelUpdater();
			    }
		    },      
            items: [{
                title :"",
                cls : "Tabla",
                items:[{
                    id: 'Operaciones-cancelarVentaPanel-Tabla',				
			        html : null
            }]
                //instructions: 'Ingrese el nuevo limite de credito para este cliente.',
            },
            new Ext.Button({ ui  : 'action', text: 'Cancelar venta', margin : 5,handler : Aplicacion.Operaciones.currentInstance.doCancelarVenta  })
        ]}); 
        
       // Ext.getCmp('Operaciones-cancelarVentaPanel').update(html);
    
    };
    


    Aplicacion.Operaciones.prototype.doCancelarVenta = function(){
    
        alert("under construction");
    
    };


/*


Aplicacion.Efectivo.prototype.nuevaOperacionInterSucursalEfectivo = function( data ){

    Ext.getBody().mask('Guardando nuevo prestamo de efectivo ...', 'x-mask-loading', true);

    Ext.Ajax.request({
        url: '../proxy.php',
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
    
    if( values.nota == "" )
    {
        Ext.Anim.run(Ext.getCmp( 'Efectivo-operacionInterSucursalEfectivo-nota' ),
            'fade', {duration: 250,
            out: true,
            autoClear: true
        });

        return;
    }

    Aplicacion.Efectivo.currentInstance.nuevaOperacionInterSucursalEfectivo( values );

};



Aplicacion.Efectivo.prototype.operacionInterSucursalEfectivoPanel = null;


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




Aplicacion.Efectivo.prototype.operacionInterSucursalProductoPanel = null;



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

}*/

















POS.Apps.push( new Aplicacion.Operaciones() );






