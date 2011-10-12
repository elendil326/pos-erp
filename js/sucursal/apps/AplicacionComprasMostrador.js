

Aplicacion.ComprasMostrador = function ()
{
		

    	return this._init();
};




/* ************************************************************************************************************************
        BASCULA
 * ************************************************************************************************************************ */

Aplicacion.ComprasMostrador.bascula = 
{
    basculas : [
        {
            puerto          : "COM1",
            desc            : "CHICA",
            read_next       : 25,
            discard_first   : 0

         },
        {
            puerto          : "COM5",
            desc            : "GRANDE",
            read_next       : 14,
            discard_first   : 0          
        }
    ],
    bascula_actual : 1,
	is_ok          : false,
	time_out       : 550,
	running        : false,
    cambiar_bascula : function (){

        if((Aplicacion.ComprasMostrador.bascula.bascula_actual + 1) >= Aplicacion.ComprasMostrador.bascula.basculas.length )  
            Aplicacion.ComprasMostrador.bascula.bascula_actual = 0;
        else
            Aplicacion.ComprasMostrador.bascula.bascula_actual ++;

        if(DEBUG){
            console.log("Cambiand de bascula a :" + Aplicacion.ComprasMostrador.bascula.bascula_actual);
        }

        Ext.get("display-bascula").update("BASCULA: " +  Aplicacion.ComprasMostrador.bascula.basculas[
                                    Aplicacion.ComprasMostrador.bascula.bascula_actual
                                ].desc    );
        
    },
    enviar_comando :  function(c){

        if(Aplicacion.ComprasMostrador.bascula.running === false ) return;

        POS.ajaxToClient({
                module : "bascula",
                raw_args : {
                    send_command : c,

                    port : Aplicacion.ComprasMostrador.bascula.basculas[
                                    Aplicacion.ComprasMostrador.bascula.bascula_actual
                                ].puerto                    
                },
                success : function ( r ){

                },
                failure: function (){
                    //client not found !
                    if(DEBUG){
                        console.warn("client not found !!!", r);                        
                    }
                }
            });
    },
	check_now : function(){
		
		if(Aplicacion.ComprasMostrador.bascula.running === false ) return;
		
		POS.ajaxToClient({
                module : "bascula",
                raw_args : {

                    send_command : 'P',

                    discar_first : Aplicacion.ComprasMostrador.bascula.basculas[
                                    Aplicacion.ComprasMostrador.bascula.bascula_actual
                                ].discard_first,

		    		read_next : Aplicacion.ComprasMostrador.bascula.basculas[
                                    Aplicacion.ComprasMostrador.bascula.bascula_actual
                                ].read_next,

                    port : Aplicacion.ComprasMostrador.bascula.basculas[
                                    Aplicacion.ComprasMostrador.bascula.bascula_actual
                                ].puerto
                },
                success : function ( r ){
			
						if(DEBUG)
                        	console.log("la erre",r);

                        trimmed = r.reading.replace( /^\s+|\s+$/g, "" );

                        Ext.get('led_display').update(trimmed);
						
						setTimeout( "Aplicacion.ComprasMostrador.bascula.check_now(  )", Aplicacion.ComprasMostrador.bascula.time_out );

                },
                failure: function (){
                    //client not found !
                    if(DEBUG){
                        console.warn("client not found !!!", r);						
                    }
                }
            });
	}
	
	
}

Aplicacion.ComprasMostrador.prototype._init = function () 
{
    if(DEBUG){
        console.log("Mostrador: construyendo");
    }




    //this.checkForOfflineSales();
	
    //crear el panel del mostrador
    this.mostradorPanelCreator();
	
    //crear la forma de la busqueda de clientes
    this.buscarClienteFormCreator();
	
    //crear la forma de ventas
    //this.doCompraPanelCreator();
    this.doNuevaCompraPanelCreator();
    Ext.getCmp('ComprasMostrador-doNuevaVenta-Menu-Efectivo').hide();
    Ext.getCmp('ComprasMostrador-doNuevaVenta-Menu-Cheque').hide();
    //crear la forma de que todo salio bien en la venta
    this.finishedPanelCreator();
	
    Aplicacion.ComprasMostrador.currentInstance = this;
	



    return this;
};

Aplicacion.ComprasMostrador.prototype.getConfig = function (){
    return {
        text: 'Compras Mostrador',
        cls: 'launchscreen',
        card: this.mostradorPanel,
        leaf: true
    };
};




/*  ****************************************************************************************************************
    ****************************************************************************************************************
	*	carritoCompras de compra
	****************************************************************************************************************
    **************************************************************************************************************** 	*/

/*
 *	Estructura donde se guardaran los detalles de la venta actual.
 * */
Aplicacion.ComprasMostrador.prototype.carritoCompras = 
{
    tipo_venta : null,
    items : [],
    cliente : null,
    venta_preferencial : {
        cliente : null,
        id_autorizacion : null
    },
    factura : false,
    tipo_pago: null
};



Aplicacion.ComprasMostrador.prototype.cancelarCompra = function ()
{
    if(DEBUG){
        console.log("------cancelando compra------");
    }
	
    Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items = [];

    Aplicacion.ComprasMostrador.currentInstance.refrescarMostrador();
	
    Aplicacion.ComprasMostrador.currentInstance.setCajaComun();

};

Aplicacion.ComprasMostrador.prototype.carritoComprasCambiarCantidad = function ( id, qty, forceNewValue )
{
    var carritoCompras = Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items;
	
    for (var i = carritoCompras.length - 1; i >= 0; i--){
	
	
        if( carritoCompras[i].idUnique == id ){
			
            if(forceNewValue){
                carritoCompras[i].cantidad = qty;
            }else{
                carritoCompras[i].cantidad += qty;
            }
			
            if(carritoCompras[i].cantidad <= 0){
                carritoCompras[i].cantidad = 1;
            }
			
            this.refrescarMostrador();
            break;
        }
    }	
};



/**
  * refrescarMostrador. 
  *
  * Refrescar el panel que muestra los productos y demas listos para vender.
  *
  **/

Aplicacion.ComprasMostrador.prototype.refrescarMostrador = function (	)
{	

    
	/*
	 * Hay que revisar si la bascula responde bien
	 *
	 **/

	if(DEBUG)
		console.log("Revisando el estado de la bascula !!!!");
	

    if(Aplicacion.ComprasMostrador.bascula.running == false){
            POS.ajaxToClient({
             module : "bascula",
             raw_args : {
                 send_command : 'P',
                 read_next : 14
             },
             success : function ( r ){
            
                
                Aplicacion.ComprasMostrador.bascula.is_ok = r.success;
                
                console.log("Revision de la bascula regreso : " + r.success);
                
                if(!r.success){
                     Ext.Msg.alert("Basculas", r.reason );
                }else{
                    Aplicacion.ComprasMostrador.bascula.running = true;
                   Aplicacion.ComprasMostrador.bascula.check_now()              ;
                }
             },
             failure: function (){
                Aplicacion.ComprasMostrador.bascula.running = false;    
                Aplicacion.ComprasMostrador.bascula.is_ok = false;
             }
         });
    }

    

    //obtener el carritoCompras
    carritoCompras = Aplicacion.ComprasMostrador.currentInstance.carritoCompras;
	
    //comenzar el html del carritoCompras
    var html = "<table border=0  style='font-size: 14px;border: 1px solid #DDD;' >";
    
    html += "<tr class='top' style = 'height:200px;'>";
    html +=     "<td align='center' colspan = '12'>";
    html += "       <div align='center' width:100%; height:200px;'>";
    html += "           <div id = 'led_display' style = 'position:relative; float:left; width:80%; left:10%; height:140px; line-height:140px; background-color:black; color:#00FF00; font-size:50px;'>88888.88</div>";
    html += "           <div align='center'             style = 'position:relative; float:left; width:90%; left:5%; height:60px; font-size:15px;'>";
    html += "               <div  style = 'position:relative; float:left; width:20%;'><div style = 'position:relative; width:80%; top:5px; height:50px; line-height:50px; border:solid 1px #8c898c; -moz-border-radius: 5px; -webkit-border-radius: 5px; border-radius: 5px;' onClick=\"Aplicacion.ComprasMostrador.currentInstance.setDisplay('CB')\" id = 'display-bascula'>BASCULA</div></div>";
    html += "               <div id = 'display-unit'    style = 'position:relative; float:left; width:20%;'><div style = 'position:relative; width:80%; top:5px; height:50px; line-height:50px; border:solid 1px #8c898c; -moz-border-radius: 5px; -webkit-border-radius: 5px; border-radius: 5px;' onClick=\"Aplicacion.ComprasMostrador.currentInstance.setDisplay('C')\">LB/KG</div></div>";
    html += "               <div id = 'display-zero'    style = 'position:relative; float:left; width:20%;'><div style = 'position:relative; width:80%; top:5px; height:50px; line-height:50px; border:solid 1px #8c898c; -moz-border-radius: 5px; -webkit-border-radius: 5px; border-radius: 5px;' onClick=\"Aplicacion.ComprasMostrador.currentInstance.setDisplay('Z')\">ZERO</div></div>";
    html += "               <div id = 'display-net-gross' style = 'position:relative; float:left; width:20%;'><div style = 'position:relative; width:80%; top:5px; height:50px; line-height:50px; border:solid 1px #8c898c; -moz-border-radius: 5px; -webkit-border-radius: 5px; border-radius: 5px;' onClick=\"Aplicacion.ComprasMostrador.currentInstance.setDisplay('GN')\">NET/GROSS</div></div>";
    html += "               <div id = 'display-tare'    style = 'position:relative; float:left; width:20%;'><div style = 'position:relative; width:80%; top:5px; height:50px; line-height:50px; border:solid 1px #8c898c; -moz-border-radius: 5px; -webkit-border-radius: 5px; border-radius: 5px;' onClick=\"Aplicacion.ComprasMostrador.currentInstance.setDisplay('T')\">TARA</div></div>";    
    html += "           </div>";
    html += "       </div>";
    html +=     "</td>";
    html += "</tr>";
	
    html += "<tr class='top'>";
    html +=     "<td align='left'>Descripcion</td>";
    html +=     "<td>&nbsp</td>";

    html +=     "<td align='center'>&nbsp</td>";
    html +=     "<td align='center'>&nbsp</td>";
    html +=     "<td align='center'>&nbsp</td>";
    html +=     "<td align='center'>Cantidad</td>";
    html +=     "<td align='center'>&nbsp</td>";
    html +=     "<td align='center'>&nbsp</td>";
    
    //html +=     "<td align='center' colspan=5>Cantidad</td>";

    
    html +=     "<td>Descuento</td>";
    html +=     "<td align='left' >Total</td>";
    html +=     "<td align='left' >Precio</td>";
    html +=     "<td align='left' >Sub Total</td>";
    html += "</tr>";
	
    //verificamos si se trata de una venta intersucursal
    var venta_intersucursal = false;
    if( carritoCompras.cliente != null && carritoCompras.cliente.id_cliente < 0 ){
        venta_intersucursal = true;
        if(DEBUG){
            console.log("venta_ intersucursal activada");
        }
    }
	
	
    var stotal = 0;

	
    //iteramos los productos que hay en el carritoCompras para crear la tabla dond se muestran los productos
    for (var i=0; i < carritoCompras.items.length; i++){

		
        var productoI = inventario.findRecord("productoID", carritoCompras.items[i].id_producto, 0, false, true, true);

        //console.log("Producto en carritoCompras("+ i +"): " , productoI);

        //revisar si las cantidades son por pieza o cajas o asi.. 
        //si son por pieza, entonces no me deja vender fracciones y asi
        switch(productoI.get("medida")){
            case "pieza" :
                carritoCompras.items[i].cantidad  = Math.round(carritoCompras.items[i].cantidad );
                if(DEBUG){
                    console.log( "ROUNDING:", carritoCompras.items[i].cantidad, Math.round(carritoCompras.items[i].cantidad ) );
                }
                break;
            default:
        }
		
		
        //revisar existencias
        if( parseFloat(productoI.get("existencias")) == 0){
            if(DEBUG){
                console.log("No hay originales !!");
            }
            carritoCompras.items[i].procesado = "true";
        }
	
	
        if( parseFloat(productoI.get("existenciasProcesadas")) == 0){
            if(DEBUG){
                console.log("No hay procesadas !!");
            }
            carritoCompras.items[i].procesado = "false";
        }

			
        if(carritoCompras.items[i].tratamiento != null){
            //si se pueden procesar
            if(carritoCompras.items[i].procesado == "true"){
		
                if(DEBUG){
                    console.log( "quiero "+carritoCompras.items[i].cantidad + " procesadas y hay "+ productoI.get("existenciasProcesadas") );
                }

		
                /*if( parseFloat(productoI.get("existenciasProcesadas") ) < parseFloat(carritoCompras.items[i].cantidad)){
                    carritoCompras.items[i].cantidad = parseFloat(productoI.get("existenciasProcesadas") );
                    Ext.Msg.alert("Mostrador", "No hay suficientes existencias de " + productoI.get("descripcion") );
                }*/
            }else{
                if(DEBUG){
                    console.log("quiero "+carritoCompras.items[i].cantidad + " originales y hay "+ productoI.get("existencias") );
                }

		
                /*if( parseFloat(productoI.get("existencias") ) < parseFloat(carritoCompras.items[i].cantidad)){
                    carritoCompras.items[i].cantidad = parseFloat(productoI.get("existencias") );
                    Ext.Msg.alert("Mostrador", "No hay suficientes existencias de " + productoI.get("descripcion") );
                }*/
            }
		
		
		
        }else{
            // no se pueden procesar
			
            /*if( parseFloat(productoI.get("existencias") ) < parseFloat(carritoCompras.items[i].cantidad)){
                carritoCompras.items[i].cantidad = parseFloat(productoI.get("existencias") );
                Ext.Msg.alert("Mostrador", "No hay suficientes existencias de " + productoI.get("descripcion") );
            }*/
        }

		
        var color = i % 2 == 0 ? "" : "style='background-color:#f7f7f7;'";
        color = "";
        /*
		if( i == carritoCompras.items.length - 1 ){
			html += "<tr " + color + " class='last'>";
		}else{
			
		}*/
        html += "<tr " + color + ">";

        //descripcion del producto
        html += "<td style='width: 18.7%;' ><b>" + carritoCompras.items[i].id_producto + "</b> &nbsp;" + carritoCompras.items[i].descripcion+ "</td>";

        //selector de tratamiento
        html += "<td style='width: 12%;' ><div id='ComprasMostrador-carritoTratamiento"+ carritoCompras.items[i].idUnique +"'></div></td>";
        		
        //quitar del carritoCompras
        html += "<td  style = 'background :none' align='right' style='width:4%;'> <span class='boton'  onClick=\"Aplicacion.ComprasMostrador.currentInstance.quitarDelcarritoCompras('"+ carritoCompras.items[i].idUnique +"')\"><img src='../media/icons/close_16.png'></span></td>";

        //pesar el producto
        html += "<td style = 'background :none'  align='center' style='width:4%;'> <span class='boton'  onClick=\"Aplicacion.ComprasMostrador.currentInstance.pesarProducto('"+ carritoCompras.items[i].idUnique +"')\"><img width = 16px; height:16px;  valign = 'center' src='../media/icons/basket_search_32.png'></span></td>";

        //sumar una unidad
        html += "<td  style = 'background :none' align='center'  style='width: 8.1%;'> <span class='boton' onClick=\"Aplicacion.ComprasMostrador.currentInstance.carritoComprasCambiarCantidad('"+ carritoCompras.items[i].idUnique + "', -1, false)\"><img src='../media/icons/arrow_down_16.png'></span></td>";
		
        var escala_de_compra = null ;
		
        if(productoI.get("precioPorAgrupacion")){
            // el precio es por agrupacion !
            escala_de_compra = productoI.get("agrupacion");
        }else{
            //el precio es por escala
            switch(productoI.get("medida")){
                case "kilogramo":
                    escala_de_compra = "Kgs";
                    break;
                case "pieza":
                    escala_de_compra = "Pzas";
                    break;
                case "litro":
                    escala_de_compra = "Lts.";
                    break;
            }
        }



        //cantidad ! y escala !
        html += "<td  align='center'  style='width: 7.2%;' ><div id='ComprasMostrador-carritoCantidad"+ carritoCompras.items[i].idUnique +"'></div></td><td>"+ escala_de_compra +"</td>";

        //quitar una unidad
        html += "<td  align='center'  style='width: 8.1%;'> <span class='boton' onClick=\"Aplicacion.ComprasMostrador.currentInstance.carritoComprasCambiarCantidad('"+ carritoCompras.items[i].idUnique +"', 1, false)\"><img src='../media/icons/arrow_up_16.png'></span></td>";

        //selector de descuento
        html += "<td style='width: 8%;' ><div id='ComprasMostrador-carritoDescuento"+ carritoCompras.items[i].idUnique +"'></div></td>";

        //total ya con descuento
        html += "<td  align='center'  style='width: 6.3%;' ><div >" + (carritoCompras.items[i].cantidad - carritoCompras.items[i].descuento) + " " + escala_de_compra + "</div></td>";
		
		
        html += "<td style='width: 10.4%;'> <div  id='ComprasMostrador-carritoPrecio"+ carritoCompras.items[i].idUnique +"'></div></td>";
		
        //importe
        html += "<td  style='width: 11.3%;'>" + POS.currencyFormat( ( carritoCompras.items[i].cantidad - carritoCompras.items[i].descuento ) * carritoCompras.items[i].precio )+"</td>";
		
        html += "</tr>";
				
        stotal += ( ( carritoCompras.items[i].cantidad - carritoCompras.items[i].descuento ) * carritoCompras.items[i].precio );
    }//for
	
	 
    var style = "";
    //style += "font-size: 35px;";
    style += "font-weight: bold;";
    //style += "margin: 32px 0 0 -4px;";
    //style += "text-shadow: 1px 1px 4px black;";
    style += "color: black;";
    //style += "font-family: 'ff-din-web-1', 'ff-din-web-2', 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Arial, Helvetica, sans-serif;";
    //style += "letter-spacing: -2px;";
    //style += "background-color: gray;";
	
    if(carritoCompras.items.length > 0){
        //html += "<div style='"+style+"' align=right>Total "+POS.currencyFormat( stotal )+"&nbsp;</div>";
        html += "<tr class='last' style='"+style+"' align=right>";
        html += "	<td colspan=9></td>";
        html += "	<td style='text-align:right'>Total</td>";
        html += "	<td style='text-align:left'>" +POS.currencyFormat( stotal )+ "</td>";
        html += "</tr>" ;
    }
	
    html += "</table>";
	
	
	
    //mostramos al tabla
    //Ext.getCmp("MostradorHtmlPanel").hide();
    Ext.getCmp("ComprasMostradorHtmlPanel").update(html);
    //Ext.getCmp("MostradorHtmlPanel").show(Ext.anims.fade);
	
    //si hay mas de un producto, mostrar el boton de vender
    if(carritoCompras.items.length > 0){
        Ext.getCmp("ComprasMostrador-mostradorVender").show( Ext.anims.slide );
    }else{
        Ext.getCmp("ComprasMostrador-mostradorVender").hide( Ext.anims.slide );
    }
	
    //creamos los controles de la tabla
    for (i=0; i < carritoCompras.items.length; i++){
		
        if(Ext.get("ComprasMostrador-carritoCantidad"+ carritoCompras.items[i].productoID + "Text")){
            continue;
        }

        //----------------------------------------------------------------
        //----------------------------------------------------------------
        // Control donde se muestra la cantidad de producto
        //----------------------------------------------------------------
        a = new Ext.form.Text({
            renderTo : "ComprasMostrador-carritoCantidad"+ carritoCompras.items[i].idUnique ,
            id : "ComprasMostrador-carritoCantidad"+ carritoCompras.items[i].idUnique + "Text",
            value : (carritoCompras.items[i].cantidad == null || carritoCompras.items[i].cantidad == "null")?"":carritoCompras.items[i].cantidad,
            prodID : carritoCompras.items[i].id_producto,
            idUnique : carritoCompras.items[i].idUnique,
            fieldCls:'ComprasMostrador-input',
            style:{
                textAlign: 'center',
                width: '100%'
            },
            placeHolder : "",
            listeners : {
                'focus' : function (){

                    this.setValue( "");

                    kconf = {
                        type : 'num',
                        submitText : 'Aceptar',
                        callback : function ( campo ){
                            //buscar el producto en la estructura y ponerle esa nueva cantidad
                            for (var i=0; i < Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items.length; i++) {
							
                                if(Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].idUnique  == campo.idUnique ){
                                    Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].cantidad = parseFloat( campo.getValue() );
                                    break;
                                }
                            }
							
                            Aplicacion.ComprasMostrador.currentInstance.refrescarMostrador();
                        }
                    };
					
                    POS.Keyboard.Keyboard( this, kconf );
                }
            }
		
        });



        //----------------------------------------------------------------
        //----------------------------------------------------------------
        // control donde se muestra el precio del producto
        //----------------------------------------------------------------
        b = new Ext.form.Text({
            renderTo : "ComprasMostrador-carritoPrecio"+ carritoCompras.items[i].idUnique ,
            id : "ComprasMostrador-carritoPrecio"+ carritoCompras.items[i].idUnique + "Text",
            value : POS.currencyFormat( carritoCompras.items[i].precio ),
            prodID : carritoCompras.items[i].id_producto,
            idUnique : carritoCompras.items[i].idUnique,
            placeHolder : "Precio de Venta",
            style:{
                width: '100%'
            },
            listeners : {
                'focus' : function (a){
					
                    //this.setValue( this.getValue().replace("$", '').replace(",", "") );
                    this.setValue( "");
					
                    kconf = {
                        type : 'num',
                        submitText : 'Cambiar',
                        callback : function ( campo ){
						
                            //buscar el producto en la estructura y ponerle esa nueva cantidad
                            for (var i=0; i < Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items.length; i++) {

                                if(Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].idUnique == campo.idUnique){

                                    //
                                    // 
                                    //
                                    if( Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].procesado == "true" ){
                                        //esta procesado
                                        precioVenta = Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].precioVentaProcesado ;				

                                    }else{
                                        //no esta procesado
                                        precioVenta = Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].precioVenta ;

                                    }
									
                                    // 
                                    // 
                                    // 
                                    if( Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].procesado == "true" ){
                                        //esta procesado
                                        precioVentaIntersucursal = Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].precioIntersucursalProcesado ;				

                                    }else{
                                        //no esta procesado
                                        precioVentaIntersucursal = Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].precioIntersucursal ;

                                    }
																		

                                    //verificamos que sea una venta preferencial
                                    //haya un cliente_preferencial y un cliente y el id
											
                                    if(
                                        !(
                                            Aplicacion.ComprasMostrador.currentInstance.carritoCompras.cliente != null &&
                                            Aplicacion.ComprasMostrador.currentInstance.carritoCompras.venta_preferencial.cliente != null &&
                                            Aplicacion.ComprasMostrador.currentInstance.carritoCompras.cliente.id_cliente == Aplicacion.ComprasMostrador.currentInstance.carritoCompras.venta_preferencial.cliente.id_cliente
                                            )
                                        )
                                        {
                                        //aqui no entra a la venta preferencial
                                        if( parseFloat(campo.getValue()) < parseFloat( precioVenta) ){
                                            Ext.Msg.alert("Mostrador", "No puede bajar un precio por debajo del preestablecido.");
                                            Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].precio = precioVenta;
                                            break;
                                        }
                                    }
											
                                    //si es una venta intersucursal, validamos que no le cambie el precio
                                    if( venta_intersucursal )
                                    {
                                        if( parseFloat(campo.getValue()) != parseFloat( precioVentaIntersucursal ) )
                                        {
                                            Ext.Msg.alert("Mostrador", "No puede modificar el precio de un producto en una venta intersucursal.");
                                            Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].precio = precioVentaIntersucursal;
                                            break;
                                        }
                                    }
									
                                    var error = false;
									
                                    //verificamos que no exista 2 productos con las mismas caracteristicas pero con precio diferente
                                    for(var j=0; j < Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items.length; j++){
							                
                                        if( Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].idUnique == Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[j].idUnique ){
                                            continue;
                                        }
							                
                                        if(
                                            Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].id_producto == Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[j].id_producto &&
                                            Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].procesado == Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[j].procesado &&
                                            parseFloat( campo.getValue() ) != Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[j].precio
                                            ){
                                            descripcion = ( Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].procesado == "true" )?"Limpia":"Original";
                                            Ext.Msg.alert( "Alerta","Existen 2 procuctos " + Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].descripcion + " " + descripcion + " con diferente precio.");
                                            Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].precio= parseFloat(Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[j].precio)
                                            error = true;
                                            break;
                                        }
							                
                                    }
							            
                                    if(error){
                                        break;
                                    }
									
									
									
                                    Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].precio= parseFloat( campo.getValue() );
                                    break;
                                }
                            }
							
                            Aplicacion.ComprasMostrador.currentInstance.refrescarMostrador();
                        }
                    };
					
                    POS.Keyboard.Keyboard( this, kconf );
                }
            }
		
        });

        if(carritoCompras.items[i].tratamiento != null  ){
		 
            var habilitar_boton_tratamiento = true;
            var productoI = inventario.findRecord("productoID", carritoCompras.items[i].id_producto, 0, false, true, true);
			
            if( parseFloat(productoI.get("existencias")) == 0){
                if(DEBUG){
                    console.log("no hay originales !!");
                }
                carritoCompras.items[i].procesado = "true";
                habilitar_boton_tratamiento  = false;
            }
		
		
            if( parseFloat(productoI.get("existenciasProcesadas")) == 0){
                if(DEBUG){
                    console.log("no hay procesadas !!");
                }
                carritoCompras.items[i].procesado = "false";
                habilitar_boton_tratamiento  = false;
            }
		
		
		
		
		
            //----------------------------------------------------------------
            //----------------------------------------------------------------
            // cada de tratamiento
            //----------------------------------------------------------------
            c = new Ext.form.Select({
                renderTo : "ComprasMostrador-carritoTratamiento"+ carritoCompras.items[i].idUnique ,
                id : "ComprasMostrador-carritoTratamiento"+ carritoCompras.items[i].idUnique + "Select",
                idUnique : carritoCompras.items[i].idUnique,
                disabled : !habilitar_boton_tratamiento,
                value : carritoCompras.items[i].procesado,
                style:{
                    width: '100%'
                },
                options : [
                {
                    text : "Limpia",
                    value : "true"
                },{
                    text : "Original",
                    value : "false"
                }
                ],
                listeners : {
                    "change" : function (){
                     
                        if(DEBUG){
                            console.log("OK, cambie de tipo de proceso a ...", this.value);
                        }
						
                        //iteramos el arreglo de productos
                        for (var i=0; i < Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items.length; i++) {
                        
                            //obtemeteomos el producto
                            if(Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].idUnique == this.idUnique){
					
                                //guardamos la seleccion
                                Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].procesado = this.value;
					         
                                var found = null;
                                                 
                                //reconocemos si es un producto procesado o no
                                if( Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].procesado == "true" ){
                                    
                                    Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].descuento = "0";
                                    
                                    //verificamos que no existan 2 productos con las mismas caracteristicas pero con precio diferente
                                    found = false;
									    
                                    for(var j = 0; j < Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items.length; j++){
							                
                                        if( Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].idUnique == Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[j].idUnique ){
                                            continue;
                                        }
							                
                                        if(
                                            Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].id_producto == Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[j].id_producto &&
                                            Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].procesado == Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[j].procesado
                                            ){
                                            //si encuentra un producto con las mismas caracteristicas, entonces a este producto le asignamos el mismo precio, para que no haya 2 productos iguales pero con diferente precio
                                            Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].precio= parseFloat(Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[j].precio);
                                            found = true;
                                            break;
                                        }
							                
                                    }
							            
                                    if(found ){
                                        break;
                                    }else{
                                        //si no se encontro un producto con las mismas propiedades entonces le asignamos el valos por default
                                        if(venta_intersucursal){
                                            Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].precio = Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].precioIntersucursalProcesado;

                                        }else{
                                            Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].precio = Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].precioVentaProcesado;
                                        }

                                    }
					                    
                                }else{
					                
					                
                                    //verificamos que no existan 2 productos con las mismas caracteristicas pero con precio diferente
                                    found = false;

                                    for(var j=0; j < Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items.length; j++){
							                
                                        if( Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].idUnique == Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[j].idUnique ){
                                            continue;
                                        }
							                
                                        if(
                                            Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].id_producto == Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[j].id_producto &&
                                            Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].procesado == Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[j].procesado
                                            ){
                                            //si encuentra un producto con las mismas caracteristicas, entonces a este producto le asignamos el mismo precio, para que no haya 2 productos iguales pero con diferente precio
                                            Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].precio= parseFloat(Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[j].precio);
                                            Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].descuento = parseFloat(Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[j].descuento);
                                            found = true;
                                            break;
                                        }
							                
                                    }
					
                                    
                                    if(found ){
                                        break;
                                    }else{
                                        //si no se encontro un producto con las mismas propiedades entonces le asignamos el valos por default
                                        if(venta_intersucursal){
                                            Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].precio = Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].precioIntersucursal;

                                        }else{
                                            Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].precio = Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].precioVenta;
                                        }

                                    }
                                    
                                    
                                }
					                
					                
                                if(DEBUG){
                                    console.log("El producto " + this.idUnique + " esta  procesado ?"  + Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].procesado );	
                                }	
                            }
                        }
                        
                        //refrescar el html
                        Aplicacion.ComprasMostrador.currentInstance.refrescarMostrador();
				        
                    }//change
                }//listeners
	
            });//c
        //control donde se muestra el descuento
		
        }//if
        
        d = new Ext.form.Text({
            renderTo : "ComprasMostrador-carritoDescuento"+ carritoCompras.items[i].idUnique ,
            id : "ComprasMostrador-carritoDescuento"+ carritoCompras.items[i].idUnique + "Text",
            value : carritoCompras.items[i].descuento,
            prodID : carritoCompras.items[i].id_producto,
            idUnique : carritoCompras.items[i].idUnique,
            style:{
                width: '100%'
            },
            //hidden : true,
            listeners : {
                'focus' : function (a){

                    this.setValue( "");
										    
                    kconf = {
                        type : 'num',
                        submitText : 'Cambiar',
                        callback : function ( campo ){
						
                            //buscar el producto en la estructura y ponerle esa nueva cantidad
                            for (var i=0; i < Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items.length; i++) {

                                if(Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].idUnique == campo.idUnique){
								
                                    var error = false;
									
                                    //verificamos que no exista 2 productos con las mismas caracteristicas pero con descuento diferente
                                    for(var j=0; j < Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items.length; j++){
							                
                                        if( Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].idUnique == Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[j].idUnique ){
                                            continue;
                                        }
							                
                                        if( 
                                            Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].id_producto == Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[j].id_producto &&
                                            Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].procesado == Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[j].procesado &&
                                            parseFloat( campo.getValue() ) != Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[j].descuento
                                            ){
                                            descripcion = ( Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].procesado == "true" )?"Limpia":"Original";
                                            Ext.Msg.alert( "Alerta","Existen 2 procuctos " + Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].descripcion + " " + descripcion + " con diferente descuento.");
                                            Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].descuento= parseFloat( Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[j].descuento )
                                            error = true;
                                            break;
                                        }
							                
                                    }
							            
                                    if(error){
                                        break;
                                    }
									
									
                                    var value = campo.getValue();
									    
                                    if( !isNaN( value ) && value >= 0 && value < carritoCompras.items[i].cantidad ){
                                        carritoCompras.items[i].descuento= value;
                                    }else{
                                        campo.setValue( "0" );
                                        carritoCompras.items[i].descuento = "0";
                                    }			
									   
                                    break;
                                }
                            }
							
                            Aplicacion.ComprasMostrador.currentInstance.refrescarMostrador();
						
                        }
                    };
					
                    POS.Keyboard.Keyboard( this, kconf );
                }
            }
		
        });
		
    }//for
	
};


Aplicacion.ComprasMostrador.prototype.agregarProducto = function (	 )
{	
    val = Aplicacion.ComprasMostrador.currentInstance.mostradorPanel.getDockedComponent(0).getComponent(0).getValue();

    Aplicacion.ComprasMostrador.currentInstance.mostradorPanel.getDockedComponent(0).getComponent(0).setValue("");
    Aplicacion.ComprasMostrador.currentInstance.agregarProductoPorID(val);
};


Aplicacion.ComprasMostrador.prototype.agregarProductoPorID = function ( id )
{

    //buscar el la estructura que esta en el Inventario
    inventario = Aplicacion.Inventario.currentInstance.inventarioListaStore;
	
    if(DEBUG){
        console.log("buscando el producto" + id);
        console.log("Aplicacion.Inventario.currentInstance.inventarioListaStore es : "  + Aplicacion.Inventario.currentInstance.inventarioListaStore );
    }
	
    res = inventario.findRecord("productoID", id, 0, false, true, true);


	
    if(res === null){
        Ext.Msg.alert("Mostrador", "Este producto no existe");
        return;
    }

	
    if(DEBUG){
        console.log("Agregando el producto " + id + " al carritoCompras.", res);
    }

    //buscar este producto en el carritoCompras (solo se permiten 2 articulos iguales)
    for(var a = 0, found = false, incidencias = 0 ; a < this.carritoCompras.items.length; a++)
    {
	
        if(this.carritoCompras.items[a].id_producto == id)
        {
            //ya esta en el carritoCompras, aumentar su cuenta
            incidencias ++;

			
            if( this.carritoCompras.items[a].tratamiento == "" ){
                found = true;
                this.carritoCompras.items[a].cantidad++;
                break;
            }
			
            if( incidencias > 1 ){
                Ext.Msg.alert("Mostrador","El producto " + res.data.descripcion + " ya existe en el carritoCompras.");
                found = true;
                break;
            }

        }
    }
	

    //verificamos si se trata de una venta intersucursal
    var venta_intersucursal = false;
	
    if( carritoCompras.cliente != null && carritoCompras.cliente.id_cliente < 0 ){
        venta_intersucursal = true;
        if( DEBUG ){
            console.log("venta intesucursal activadan al insertar el producto");
        }
    }
		 
    //si no, agregarlo al carritoCompras
    if(!found)
    {
        this.carritoCompras.items.push({
            descripcion 			: res.data.descripcion,
            existencias 			: res.data.existencias,
            existencias_procesadas 	: res.data.existenciasProcesadas,
            tratamiento 			: res.data.tratamiento,   //si es !null  entonces el producto puede ser original o procesado

            precioVenta 			: res.data.precioVenta,
            precioVentaProcesado 	: res.data.precioVentaProcesado,

            precioIntersucursal 	: res.data.precioIntersucursal,
            precioIntersucursalProcesado : res.data.precioIntersucursalProcesado,

            //esto que es ?
            precio 					: venta_intersucursal ? res.data.tratamiento != null ? res.data.precioIntersucursalProcesado : res.data.precioIntersucursalProcesado : res.data.tratamiento != null ? res.data.precioVentaProcesado : res.data.precioVenta,

            id_producto 			: res.data.productoID,
            escala 					: res.data.medida,

            procesado 				: res.data.tratamiento != null ? "true":"false",
            cantidad 				: 1,
            idUnique 				: res.data.productoID + "_" +  Aplicacion.ComprasMostrador.currentInstance.uniqueIndex,
            descuento 				: "0",
            input_box_rendered 		: false
        });
		
        //identificador unico e irepetible
        Aplicacion.ComprasMostrador.currentInstance.uniqueIndex++; 
		
    }
	

    //refrescar el html
    this.refrescarMostrador();
};


Aplicacion.ComprasMostrador.prototype.uniqueIndex = 0;

/**
 * Quita un articulo del carritoCompras dado su id
 */
Aplicacion.ComprasMostrador.prototype.quitarDelcarritoCompras = function ( id )
{
    if(DEBUG){
        console.log("Removiendo del carritoCompras.");
    }
	
    carritoCompras = Aplicacion.ComprasMostrador.currentInstance.carritoCompras;
    for (var i = carritoCompras.items.length - 1; i >= 0; i--){
        if( carritoCompras.items[i].idUnique == id ){
            carritoCompras.items.splice( i ,1 );

            break;
        }
    }
    Aplicacion.ComprasMostrador.currentInstance.refrescarMostrador();
	
};






/*  ****************************************************************************************************************
    ****************************************************************************************************************
	*	Panel principal del mostrador
	****************************************************************************************************************
    **************************************************************************************************************** 	*/

/**
 * Contiene el panel con la forma del mostrador
 */
Aplicacion.ComprasMostrador.prototype.mostradorPanel = null;


/**
 * Crea el panel del mostrador
 */
Aplicacion.ComprasMostrador.prototype.mostradorPanelCreator = function (){
	
	
    var productos = [{
        xtype: 'textfield',
        placeHolder : "Agregar Producto",
        listeners : {
            'focus' : function (){

                kconf = {
                    type : 'num',
                    submitText : 'Agregar',
                    callback : Aplicacion.ComprasMostrador.currentInstance.agregarProducto
                };
                POS.Keyboard.Keyboard( this, kconf );
            }
        }
    },{
        xtype : "button",
        text : "Buscar",
        handler : function(){
            sink.Main.ui.setActiveItem( Aplicacion.Inventario.currentInstance.listaInventarioPanel , 'fade');
        }
    }];


    var venta = [{
        text: 'Cancelar Compra',
        ui: 'action',
        handler : this.cancelarCompra
    },{
        xtype: 'segmentedbutton',
        id : 'ComprasMostrador-tipoCliente',
        allowDepress: false,
        items: [{
            text: 'Caja Comun',
            pressed: true,
            handler : this.setCajaComun
        }, {
            text: 'Cliente',
            handler : this.buscarClienteFormShow,
            badgeText : ""
        }]
    },{
        id: 'ComprasMostrador-mostradorVender',
        hidden: true,
        text: 'Comprar',
        ui: 'forward',
        handler : this.doCompraPanelShow
    }];


    productos.push({
        xtype: 'spacer'
    });

    var dockedItems = [new Ext.Toolbar({
        ui: 'light',
        dock: 'bottom',
        items: productos.concat(venta)
    })];
   
    this.mostradorPanel = new Ext.Panel({

        listeners : {
            "show" : this.refrescarMostrador,
			"hide" : function(){
				if(DEBUG)
				console.log("Estoy ocultando el mostrador de compras ! Deteniendo la bascula");
				Aplicacion.ComprasMostrador.bascula.running = false;
			}
        },
        floating: false,
        ui : "dark",
        modal: false,
        cls : "Tabla",
        items : [{
            id: 'ComprasMostradorHtmlPanel',
            html : null
        }],
		
        scroll: 'none',
        dockedItems: dockedItems
    });

};









/*  ****************************************************************************************************************
    ****************************************************************************************************************
	*	Buscar y seleccionar cliente para la venta
	****************************************************************************************************************
    **************************************************************************************************************** 	*/

Aplicacion.ComprasMostrador.prototype.buscarClienteForm = null;

Aplicacion.ComprasMostrador.prototype.clienteSeleccionado = function ( cliente )
{
	
    if(DEBUG){
        console.log("cliente seleccionado", cliente);
    }
	
    this.buscarClienteFormShow();
	
    Ext.getCmp("ComprasMostrador-tipoCliente").getComponent(1).setBadge(cliente.razon_social);
	
    Aplicacion.ComprasMostrador.currentInstance.carritoCompras.cliente = cliente;
	
    //verificamos si se trata de una venta intersucursal
    if( cliente.id_cliente < 0 )
    {
        if(DEBUG){
            console.log("Seleccionamos una sucursal como cliente : restaurando precios intersucursal");
        }
        Aplicacion.ComprasMostrador.currentInstance.restaurarPreciosIntersucursal();
    }
    else
    {
        if(DEBUG){
            console.log("Seleccionamos un cliente normal : restaurando precios originales");
        }
        Aplicacion.ComprasMostrador.currentInstance.restaurarPreciosOriginales();
    }	
		
};

Aplicacion.ComprasMostrador.prototype.clientePreferencialSeleccionado = function ( cliente )
{
	
    if(DEBUG){
        console.log("cliente preferencial seleccionado", cliente);
    }
	
	
    Ext.getCmp("ComprasMostrador-tipoCliente").getComponent(1).setBadge(cliente.razon_social);
	
    Aplicacion.ComprasMostrador.currentInstance.carritoCompras.cliente = cliente;
	
    Aplicacion.ComprasMostrador.currentInstance.refrescarMostrador()
		
};

Aplicacion.ComprasMostrador.prototype.setCajaComun = function ()
{


	
    if(Ext.getCmp('ComprasMostrador-tipoCliente').getPressed()){
        Ext.getCmp('ComprasMostrador-tipoCliente').setPressed( Ext.getCmp('ComprasMostrador-tipoCliente').getPressed() );
    }

    Ext.getCmp("ComprasMostrador-tipoCliente").getComponent(1).setBadge( );
    Ext.getCmp('ComprasMostrador-tipoCliente').setPressed(0);

    Aplicacion.ComprasMostrador.currentInstance.carritoCompras.tipo_venta = "contado";
    Aplicacion.ComprasMostrador.currentInstance.carritoCompras.cliente = null;
	
    Aplicacion.ComprasMostrador.currentInstance.restaurarPreciosOriginales ();
	
};

Aplicacion.ComprasMostrador.prototype.buscarClienteFormCreator = function ()
{
	

    //cancelar busqueda
    var dockedCancelar = {
        xtype : 'button',
        text: 'Cancelar',
        handler : function(){


            Aplicacion.ComprasMostrador.currentInstance.setCajaComun();
            Aplicacion.ComprasMostrador.currentInstance.buscarClienteFormShow();
        }
		
    };



    //toolbar
    var dockedItems = {
        xtype: 'toolbar',
        dock: 'bottom',
        items: [ dockedCancelar , {
            xtype: 'spacer'
        } ]
    };


    var formBase = {
        autoRender: true,
        style : {
            zIndex : '10000 !important'
        },
        listeners:{
            'show':function(){
                //TODO: verificar que esto solo se haga una sola vez
                if( !Aplicacion.ComprasMostrador.currentInstance.buscarClienteForm.getComponent(0).getStore() )
                {
                    Aplicacion.ComprasMostrador.currentInstance.buscarClienteForm.getComponent(0).bindStore(Aplicacion.Clientes.currentInstance.listaDeClientesStore);
                }
            }
        },
        floating: true,
        modal: true,
        centered: true,
        hideOnMaskTap: false,
        height: 585,
        width: 680,
        items: [{
			
            width : '100%',
            height: '100%',
            xtype: 'list',
            store: Aplicacion.Clientes ? Aplicacion.Clientes.currentInstance.listaDeClientesStore : null ,
            itemTpl: '<div class="listaDeClientesCliente"><strong>{razon_social}</strong> {rfc}</div>',
            grouped: true,
            indexBar: true,
            listeners : {
                "selectionchange"  : function ( view, nodos, c ){
					
                    if(nodos.length > 0){
                        Aplicacion.ComprasMostrador.currentInstance.clienteSeleccionado( nodos[0].data );
                    }

                    //deseleccinar el cliente
                    view.deselectAll();
                }
            }
			
        }],
        dockedItems: dockedItems
    };



    if(DEBUG){
        console.log( "creando la forma de buscar clientes" );
    }
	
	
	
    this.buscarClienteForm = new Ext.Panel(formBase);

    //por alguna razon al crearlo dice que es visible,
    //habra que hacerlo invisible
    this.buscarClienteForm.setVisible(false);


};

Aplicacion.ComprasMostrador.prototype.buscarClienteFormShow = function (  )
{


    if(Aplicacion.ComprasMostrador.currentInstance.buscarClienteForm){

        //invertir la visibilidad de la forma
        Aplicacion.ComprasMostrador.currentInstance.buscarClienteForm.setVisible( !Aplicacion.ComprasMostrador.currentInstance.buscarClienteForm.isVisible() );

    }else{
        Aplicacion.ComprasMostrador.currentInstance.buscarClienteFormCreator();
        Aplicacion.ComprasMostrador.currentInstance.buscarClienteFormShow();
    }

	
};





/*  ****************************************************************************************************************
    ****************************************************************************************************************
	*	Venta preferencial
	****************************************************************************************************************
    **************************************************************************************************************** 	*/


/**
  * se llama cuando se han modificado los valores de los 
  * precios de los prcutos y afinal de cuenta no se realiza 
  * la venta preferencial
  **/
Aplicacion.ComprasMostrador.prototype.restaurarPreciosOriginales = function()
{

    if(DEBUG){
        console.log("restaurando precios originales");
    }

    for( var i = 0; i < Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items.length; i++ )
    {
        //el producto esta procesado
        if( Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].procesado == "true" ){
            precioVenta = Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].precioVentaProcesado;
			
        }else{
            precioVenta = Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].precioVenta;
			
        }

        Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].precio = precioVenta;
    }

    Aplicacion.ComprasMostrador.currentInstance.refrescarMostrador();

}

Aplicacion.ComprasMostrador.prototype.restaurarPreciosIntersucursal = function()
{

    if(DEBUG){
        console.log("restaurando precios intersucursales");
    }

    for( var i = 0; i < Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items.length; i++ )
    {

        if( Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].procesado == "true" ){
            //esta procesado
            precioVenta = Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].precioIntersucursalProcesado ;				

        }else{
            //no esta procesado
            precioVenta = Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].precioIntersucursal ;

        }
			
        Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].precio = precioVenta;
    }

    Aplicacion.ComprasMostrador.currentInstance.refrescarMostrador();

}






/*  ****************************************************************************************************************
    ****************************************************************************************************************
	*	Venta terminada !
	****************************************************************************************************************
    **************************************************************************************************************** 	*/
Aplicacion.ComprasMostrador.prototype.finishedPanel = null;

Aplicacion.ComprasMostrador.prototype.finishedPanelShow = function()
{
    //update panel
    this.finishedPanelUpdater();
	
    //resetear los formularios
    //this.cancelarCompra();
	
    sink.Main.ui.setActiveItem( Aplicacion.ComprasMostrador.currentInstance.finishedPanel , 'fade');
	
};

/**
  *
  *
  **/
Aplicacion.ComprasMostrador.prototype.finishedPanelUpdater = function()
{
	
    //datos del carritoCompras
    carritoCompras = Aplicacion.ComprasMostrador.currentInstance.carritoCompras;
    
    //incluye los datos de la sucursal
    carritoCompras.sucursal = POS.infoSucursal;
	                                
    //parseamos el descuento
    carritoCompras.descuento = parseFloat(carritoCompras.descuento);
    
    //tipo de ticket a imprimir
    carritoCompras.ticket = "venta_cliente";

    //buscamos la impresora para este tipo de documento
    for( i = 0; i < POS.documentos.length; i++){
        if( POS.documentos[i].documento == carritoCompras.ticket ){
            carritoCompras.impresora = POS.documentos[i].impresora;
            break;
        }
    }

    carritoCompras.leyendasTicket = POS.leyendasTicket;

    if(DEBUG){
        console.log("carritoCompras : ", carritoCompras);
        console.log("carritoCompras.items : ", carritoCompras.items);
    }

    html = "";
	
    html += "<table class='Mostrador-ThankYou'>";
    html += "	<tr>";
    html += "		<td ><img width = 200px height = 200px src='../media/Receipt128.png'></td>";
    html += "		<td></td>";
    html += "	</tr>";
	
    if(carritoCompras.tipo_venta != "credito"){
        //mostrar el cambio
        html += "	<tr>";
        html += "		<td>Su cambio: <b>"+POS.currencyFormat( parseFloat( Ext.getCmp("ComprasMostrador-doNuevaVentaImporte").getValue() ) - parseFloat( carritoCompras.total ) )+"</b></td>";
        html += "		<td></td>";
        html += "	</tr>";
    }

    html += "</table>";
	
	
	
	
    if( carritoCompras.factura )
    {
        //html += "<iframe id = 'frame' src ='../impresora/pdf.php?json=" + Ext.util.JSON.encode(carrito) + "' width='0px' height='0px'></iframe> ";
        window.open("../impresora/pdf.php?json=" + Ext.util.JSON.encode(carritoCompras));

    } else {

        hora = new Date()
        var dia = hora.getDate();
        var mes = hora.getMonth();
        var anio = hora.getFullYear();
        horas = hora.getHours()
        minutos = hora.getMinutes()
        segundos = hora.getSeconds()
        if (mes <= 9) mes = "0" + mes
        if (horas >= 12) tiempo = " p.m."
        else tiempo = " a.m."
        if (horas > 12) horas -= 12
        if (horas == 0) horas = 12
        if (minutos <= 9) minutos = "0" + minutos
        if (segundos <= 9) segundos = "0" + segundos


        var PRINT_VIA_JAVA_CLIENT = true;
		
        if(PRINT_VIA_JAVA_CLIENT)
        {
            /**
			  *	Impresion via cliente
			  *
			  **/           
            POS.ajaxToClient({
                module : "Printer",
                args : carritoCompras,
                success : function ( r ){
					
                    //ok client is there...
                    if(DEBUG){
                        console.log("ticket printing responded", r);						
                    }

                },
                failure: function (){
                    //client not found !
                    if(DEBUG){
                        console.warn("client not found !!!", r);						
                    }
                }
            });
					
        }else{
    /**
			  *	Impresion via applet
			  *
			  **/
    /*html += ''
	        +'<applet code="printer.Main" archive="PRINTER/dist/PRINTER.jar" WIDTH=0 HEIGHT=0>'
	        +'     <param name="json" value="'+ json +'">'
	        +'     <param name="hora" value="' + horas + ":" + minutos + ":" + segundos + tiempo + '">'
	        +'     <param name="fecha" value="' + dia +"/"+ (hora.getMonth() + 1) +"/"+ anio + '">'
	        +' </applet>'; */
	
    }
    }
	
	
    this.finishedPanel.update(html);

    Ext.getCmp("ComprasMostrador-mostradorVender").hide( Ext.anims.slide );

    action = "sink.Main.ui.setActiveItem( Aplicacion.ComprasMostrador.currentInstance.mostradorPanel , 'fade');";

    setTimeout(action, 4000);

};

Aplicacion.ComprasMostrador.prototype.finishedPanelCreator = function()
{

    this.finishedPanel = new Ext.Panel({
        html : ""
    });
	
};







/*  ****************************************************************************************************************
    ****************************************************************************************************************
	*	Realizar la Venta  !
	****************************************************************************************************************
    **************************************************************************************************************** 	*/

Aplicacion.ComprasMostrador.prototype.doCompra = function ()
{
	
    carritoCompras = Aplicacion.ComprasMostrador.currentInstance.carritoCompras;

    if(carritoCompras.tipo_venta == 'contado'){
	
        if(DEBUG){
            console.log("revisando compra a contado...");
        }
		
        //ver si pago lo suficiente
        pagado = Ext.getCmp("ComprasMostrador-doNuevaVentaImporte").getValue();
		
        if( (pagado.length === 0) || (parseFloat(pagado) < parseFloat(carritoCompras.total)) ){
			
            //no pago lo suficiente
            Ext.Msg.alert("Mostrador", "Verifique al cantidad del importe.");
			
            return;
        }
		
        this.carritoCompras.pagado = parseFloat(pagado);
        this.comprar();

		
    }else{
		
        if(DEBUG){
            console.log("revisando compra a credito...");
        }
        //ver si si puede comprar a credito
        //aunque se supone que si debe poder

        this.comprar();
		
    }

};

Aplicacion.ComprasMostrador.thisPosSaleId = 0;


Aplicacion.ComprasMostrador.prototype.offlineVender = function( )
{
    if(DEBUG){
        console.warn("-------- Venta offline !!! ---------");
        console.log("Aplicacion.ComprasMostrador.thisPosSaleId="+Aplicacion.ComprasMostrador.thisPosSaleId);
    }

    var carritoCompras = Aplicacion.ComprasMostrador.currentInstance.carritoCompras;

    var this_sale = new Ventas({
        id_venta		: Aplicacion.ComprasMostrador.thisPosSaleId,
        id_venta_equipo	: Aplicacion.ComprasMostrador.thisPosSaleId,
        id_equipo		: 1,
        id_cliente		: carritoCompras.cliente,
        tipo_venta		: carritoCompras.tipo_venta,
        tipo_pago		: carritoCompras.tipo_pago,
        fecha			: null,
        subtotal		: carritoCompras.subtotal,
        iva				: carritoCompras.iva,
        descuento		: 0,
        total			: carritoCompras.total,
        id_sucursal		: null,
        id_usuario		: null,
        pagado			: carritoCompras.pagado,
        cancelada		: null,
        ip				: null,
        liquidada		: null
    });
	
    this_sale.save(function(saved_sale){
		
        if(DEBUG) console.log("Back from saving offline sale sale !", saved_sale);

        //ahora a guardar los detalles de la venta
        var detalles 	= Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items,
        l 			= Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items.length;
		
        if(DEBUG) console.log("Ahora intentare guardar los " + l + " detalles.");		
		
        for (var d=0, td = null; d < detalles.length; d++) {
            if(DEBUG)console.log("guardando detalle...");
			
            td = new DetalleVenta({
                id_venta: 			saved_sale.getIdVenta(),
                id_producto: 		detalles[d].id_producto,
                cantidad: 			detalles[d].cantidad,
                cantidad_procesada: detalles[d].cantidad,
                precio: 			detalles[d].precioVenta,
                precio_procesada: 	detalles[d].precioVentaProcesado,
                descuento: 			detalles[d].descuento
            });
			
            td.save(
                function(){
                    if(DEBUG){
                        console.log("termine de guardar el detalle de venta");
                    }
                }
                );

            //reseteamos el carritoCompras
            Aplicacion.ComprasMostrador.currentInstance.cancelarCompra();
        }
		
		
		
    });

    //mostrar el panel final
    Aplicacion.ComprasMostrador.currentInstance.finishedPanelShow();
	

}

Aplicacion.ComprasMostrador.prototype.comprar = function (){

    if(DEBUG){
        console.log(" ----- Enviando compra :", Aplicacion.ComprasMostrador.currentInstance.carritoCompras , " ----------");
    }


    Aplicacion.ComprasMostrador.thisPosSaleId++;
	
    //Hay un error en la red justo ahora !
    if(POS.A.failure){
        return Aplicacion.ComprasMostrador.currentInstance.offlineVender();
    }
	
         
    Ext.Ajax.request({
        url: '../proxy.php',
        scope : this,
        params : {
            action 	: 1006,
            data : Ext.util.JSON.encode( 
            {
                id_cliente : Aplicacion.ComprasMostrador.currentInstance.carritoCompras.cliente,
                tipo_compra : Aplicacion.ComprasMostrador.currentInstance.carritoCompras.tipo_venta,
                tipo_pago : Aplicacion.ComprasMostrador.currentInstance.carritoCompras.tipo_pago,
                productos : Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items
            }
            )
        },
        success: function(response, opts) {
            
            var compra = null;
            
            try{
                compra = Ext.util.JSON.decode( response.responseText );

            }catch(e){                
                //whoops algo paso en el servidor
                POS.error( response, e );
                Aplicacion.ComprasMostrador.currentInstance.offlineVender();
                return;
				
            }
			
            if( !compra.success ){
                //volver a intentar
                if(DEBUG){
                    console.log("resultado de la compra sin exito ",compra );
                }

                POS.error( compra );
                Aplicacion.ComprasMostrador.currentInstance.offlineVender();
                return;

            }
			
            if(DEBUG){
                console.log("resultado de la compra exitosa ", compra );
            }
			
            carritoCompras = Aplicacion.ComprasMostrador.currentInstance.carritoCompras;
			
            //verificamos si se hiso una venta preferencial
            if(  carritoCompras.cliente != null &&  carritoCompras.venta_preferencial.cliente != null &&
                carritoCompras.cliente.id_cliente == carritoCompras.venta_preferencial.cliente.id_cliente )
                {
                
                //hacemos una jaxaso para modificar la autorizacion de venta preferencial e indicarle que ya se hiso la venta preferencial                
                Aplicacion.Autorizaciones.currentInstance.finalizarAutorizacionVentaPreferencial();
                                
            }

            ///console.log("la compra : ", venta);

            //almacenamos en el carritoCompras el id de la venta
            carritoCompras.id_venta = compra.id_compra;
			
            //almacenamos en el carritoCompras el nombre del empleado
            Aplicacion.ComprasMostrador.currentInstance.carritoCompras.empleado = compra.empleado;			
			
            //recargar el inventario
            Aplicacion.Inventario.currentInstance.cargarInventario();
			
			
            //recargar la lista de clientes y de compras
            if( Aplicacion.ComprasMostrador.currentInstance.carritoCompras.cliente !== null)
            {
                Aplicacion.Clientes.currentInstance.listaDeClientesLoad();
            }

            //mostrar el panel final
            Aplicacion.ComprasMostrador.currentInstance.finishedPanelShow();
			
            //reseteamos el carritoCompras
            Aplicacion.ComprasMostrador.currentInstance.cancelarCompra();
			
			

        },
        failure: function( response ){
            Ext.Msg.alert("Error", "Error en la compra");
            POS.error( response );
            return offlineVender();
        }
    });
};



/* ********************************************************
	Seleccion de Pago y Vender
******************************************************** */

/*
 * Guarda el panel donde estan la forma de venta
 **/
Aplicacion.ComprasMostrador.prototype.doNuevaCompraPanel = null;


/*
 * Es la funcion de entrada para mostrar el panel de venta
 **/
Aplicacion.ComprasMostrador.prototype.doCompraPanelShow = function ( )
{	
	//
	if(DEBUG)
		console.log("Voy a hacer el calculo, detener la bascula");

	Aplicacion.ComprasMostrador.bascula.running = false;
	
    //hacer un setcard manual
    sink.Main.ui.setActiveItem( Aplicacion.ComprasMostrador.currentInstance.doNuevaCompraPanel , 'slide');
	
};




/**
    Esta funcion se llama cuando el usuario presioan el boton de tipo de venta Efectivo o Credito
    dependiendo de la seleccion se ocultan los botones de Efectivo o Cheque, si es que selecciono Credito
    ademas se construye la estructura del formulario de la venta(subtotal, total, cambio etc)
*/
Aplicacion.ComprasMostrador.prototype.setTipoVenta = function ( tipo_venta ){


    if( tipo_venta == "contado" ){
        //inicializamos valores
        Aplicacion.ComprasMostrador.currentInstance.carritoCompras.tipo_venta = "contado";
        Aplicacion.ComprasMostrador.currentInstance.doNuevaCompraPanel.getComponent(1).setActiveItem(1, Ext.anims.slide);
  		
        Ext.getCmp('ComprasMostrador-doNuevaVentaClienteCredito' ).setVisible(false);
        Ext.getCmp('ComprasMostrador-doNuevaVentaClienteCredito').setValue( "" ); 
		
        Ext.getCmp('ComprasMostrador-doNuevaVentaClienteCreditoRestante').setVisible(false); 
        Ext.getCmp('ComprasMostrador-doNuevaVentaClienteCreditoRestante').setValue( "" );
        
        //mostramos el campo de importe
        Ext.getCmp('ComprasMostrador-doNuevaVentaImporte').show();
  		  
    }

    if( tipo_venta == "credito" ){
        //inicializamos valores
        Aplicacion.ComprasMostrador.currentInstance.carritoCompras.tipo_venta = "credito";
        
        //ocultamos el boton de factura
        Ext.getCmp('ComprasMostrador-doNuevaVentaFacturar').setVisible(false);
        
       
        //establecemos nulo el tipo de pago
        Aplicacion.ComprasMostrador.currentInstance.carritoCompras.tipo_pago = null;                  
        
        //ocultamos el campo de importe
        Ext.getCmp('ComprasMostrador-doNuevaVentaImporte').hide();
  		
        //verificamos si se trata de una venta entre sucursales
        if(Aplicacion.ComprasMostrador.currentInstance.carritoCompras.cliente != null && Aplicacion.ComprasMostrador.currentInstance.carritoCompras.cliente.id_cliente < 0){
  		    
            //ocultamos la informacion de los creditos
            Ext.getCmp('ComprasMostrador-doNuevaVentaClienteCredito').hide( Ext.anims.slide );
            Ext.getCmp('ComprasMostrador-doNuevaVentaClienteCreditoRestante').hide( Ext.anims.slide );
  		
            //ocultamos los botones de tipo de pago
            Ext.getCmp('ComprasMostrador-doNuevaVenta-Menu-Efectivo').hide();
            Ext.getCmp('ComprasMostrador-doNuevaVenta-Menu-Cheque').hide();
  		    
        }else{

        //COMO YA SE QUITO EL "BOTONSOTE DE VENDER YA NO ES NECESARIO MOSTRAR EL LIMITE DE CREDITO
        //Y EL CREDITO RESTANTE YA QUE CUANDO SE PRESIONE EL BOTON DE CREDITO EL CAJERO NO TENDRA
        //TIEMPO DE MIRAR ESOS CAMPOS"
        //Ext.getCmp('Mostrador-doNuevaVentaClienteCredito').show( Ext.anims.slide );
        //Ext.getCmp('Mostrador-doNuevaVentaClienteCreditoRestante').show( Ext.anims.slide );
            
        }
  		
        //mostramos la card donde muestra el boton de vender
        //Aplicacion.ComprasMostrador.currentInstance.doNuevaCompraPanel.getComponent(1).setActiveItem(2, Ext.anims.slide);
        Aplicacion.ComprasMostrador.currentInstance.doCompra();

    }
    


};



Aplicacion.ComprasMostrador.prototype.setTipoPago = function( tipoPago ){

    switch( tipoPago ){
	
        case 'efectivo':
            Aplicacion.ComprasMostrador.currentInstance.carritoCompras.tipo_pago = "efectivo";

            //verificamos si es un cliente para mostrar el boton de factura
            if( Aplicacion.ComprasMostrador.currentInstance.carritoCompras.cliente != null ){
                Ext.getCmp('ComprasMostrador-doNuevaVentaFacturar').setVisible(true);
            }
            
            //fijamo el importe en ceros
            Ext.getCmp('ComprasMostrador-doNuevaVentaImporte').setValue( "" );
			
            break;
        
        case 'cheque':
            Aplicacion.ComprasMostrador.currentInstance.carritoCompras.tipo_pago = "cheque";

            //verificamos si es un cliente para mostrar el boton de factura
            if( Aplicacion.ComprasMostrador.currentInstance.carritoCompras.cliente != null ){
                Ext.getCmp('ComprasMostrador-doNuevaVentaFacturar').setVisible(true);
            }
            
            //fijamos el importe igual al total
            Ext.getCmp('ComprasMostrador-doNuevaVentaImporte').setValue( POS.currencyFormat( this.carritoCompras.total ) );

            Aplicacion.ComprasMostrador.currentInstance.doCompra();
                                    
            break;
        
        default:
            Aplicacion.ComprasMostrador.currentInstance.carritoCompras.tipo_pago = null;
            Ext.Msg.alert("Mostraror","Error, porfavor intente de nuevo.");
    }
    
    //mostramos la card del boton de pagar 
    Aplicacion.ComprasMostrador.currentInstance.doNuevaCompraPanel.getComponent(1).setActiveItem(2, Ext.anims.slide);
    
};



/**
* Actualiza el panel de la venta, mostrando u ocultando los campos que se requieran
*/
Aplicacion.ComprasMostrador.prototype.doNuevaCompraPanelUpdater = function ()
{

    if(DEBUG){
        console.log("Haciendo update en el formulario de la venta", this.carritoCompras);
    }

    //ocultar el formulario de Importe
    Ext.getCmp('ComprasMostrador-doNuevaVentaImporte').setValue("");

    //ocultamos los datos de  cliente
    Ext.getCmp('ComprasMostrador-doNuevaVentaCliente').setValue( "Caja comun" );
		
    Ext.getCmp('ComprasMostrador-doNuevaVentaClienteCredito' ).setVisible(false);
    Ext.getCmp('ComprasMostrador-doNuevaVentaClienteCredito').setValue( "" ); 
		
    Ext.getCmp('ComprasMostrador-doNuevaVentaClienteCreditoRestante').setVisible(false); 
    Ext.getCmp('ComprasMostrador-doNuevaVentaClienteCreditoRestante').setValue( "" );
	    
    Ext.getCmp('ComprasMostrador-doNuevaVentaDescuento' ).setVisible(false);
    Ext.getCmp('ComprasMostrador-doNuevaVentaDescuento').setValue( "" );			
		
    //ocultamos el boton de factura
    if( Ext.getCmp('ComprasMostrador-doNuevaVentaFacturar').rendered ){
        Ext.getCmp('ComprasMostrador-doNuevaVentaFacturar').reset();
    }
    
    Ext.getCmp('ComprasMostrador-doNuevaVentaFacturar').setVisible(false);

    //mostramos el campo de importe
    Ext.getCmp('ComprasMostrador-doNuevaVentaImporte').show();
    
    //mostramos los botones de tipo de pago
    Ext.getCmp('ComprasMostrador-doNuevaVenta-Menu-Efectivo').show();
    Ext.getCmp('ComprasMostrador-doNuevaVenta-Menu-Cheque').show();
    //mostramos los botones de tipod e venta
    Ext.getCmp('ComprasMostrador-doNuevaVenta-Menu-Contado').show();
    Ext.getCmp('ComprasMostrador-doNuevaVenta-Menu-Credito').show();

    //mostrar los totales
    subtotal = 0;
    total = 0;
    for (var i=0; i < this.carritoCompras.items.length; i++) {
        subtotal += (this.carritoCompras.items[i].precio * (this.carritoCompras.items[i].cantidad - this.carritoCompras.items[i].descuento));
    }
	
    if( this.carritoCompras.cliente === null ){

        total = subtotal;
		
        Aplicacion.ComprasMostrador.currentInstance.carritoCompras.factura = false;
		
        //establecemos en elcarritoCompras el tipod e venta contado
        Aplicacion.ComprasMostrador.currentInstance.carritoCompras.tipo_venta = "contado";

        //ocultamos el boton tipo de venta a credito
        Ext.getCmp('ComprasMostrador-doNuevaVenta-Menu-Credito').hide( );
         
        //mostramos el menu de tipo de pago
        Aplicacion.ComprasMostrador.currentInstance.doNuevaCompraPanel.getComponent(1).setActiveItem(1, Ext.anims.slide);
                  

    }else{
				
        //es un cliente
        Ext.getCmp('ComprasMostrador-doNuevaVentaCliente').setValue( this.carritoCompras.cliente.razon_social + "  " + this.carritoCompras.cliente.rfc );
		
        //verificamos si el cliente tiene asignado un limite de credito
        if(this.carritoCompras.cliente.limite_credito > 0){
		
            //mostramos el credito restante
            Ext.getCmp('ComprasMostrador-doNuevaVentaClienteCredito').setValue( POS.currencyFormat(this.carritoCompras.cliente.limite_credito) );
            Ext.getCmp('ComprasMostrador-doNuevaVentaClienteCreditoRestante').setValue( POS.currencyFormat( this.carritoCompras.cliente.credito_restante ));
			
        }else{
            //ocultamos el limite de credito
            Ext.getCmp('ComprasMostrador-doNuevaVentaClienteCreditoRestante').setVisible(false);
            Ext.getCmp('ComprasMostrador-doNuevaVentaClienteCredito').setVisible(false);
			
            //establecemos el tipo de venta manualmente a contado ya que no tiene la posibilidad de pagar a credito
            Aplicacion.ComprasMostrador.currentInstance.carritoCompras.tipo_venta = "contado";
        }		 
	
        //verificamos si este cliente tiene asignado un descuento
        if( this.carritoCompras.cliente.descuento > 0 ){
            Ext.getCmp('ComprasMostrador-doNuevaVentaDescuento' ).setVisible(true);
            Ext.getCmp('ComprasMostrador-doNuevaVentaDescuento').setValue( POS.currencyFormat( subtotal * (this.carritoCompras.cliente.descuento / 100)) + " ( " + this.carritoCompras.cliente.descuento+"% )" );
        }else{
            Ext.getCmp('ComprasMostrador-doNuevaVentaDescuento' ).setVisible(false);
        }

        total = subtotal - ( subtotal * (this.carritoCompras.cliente.descuento / 100));

        //verificamos que tipos de venta se le aplican
        if( total <= this.carritoCompras.cliente.credito_restante){
            //si puede comprar a credito

            //hacemos visible la tarjeta 0 que es el tipo de compra
            Aplicacion.ComprasMostrador.currentInstance.doNuevaCompraPanel.getComponent(1).setActiveItem(0);        
		        
            //restauramos el boton de pago credito
            Ext.getCmp('ComprasMostrador-doNuevaVenta-Menu-Credito').show();

        }else{
		
            //no puede comprar a credito

            //establecemos el tipo de venta manualmente a contado ya que no tiene la posibilidad de pagar a credito
            Aplicacion.ComprasMostrador.currentInstance.carritoCompras.tipo_venta = "contado";

            //ocultamos el boton tipo de venta a credito
            Ext.getCmp('ComprasMostrador-doNuevaVenta-Menu-Credito').hide( );
                 
            //mostramos el menu de tipo de pago
            Aplicacion.ComprasMostrador.currentInstance.doNuevaCompraPanel.getComponent(1).setActiveItem(1, Ext.anims.slide);  
             
        }
		
        if( this.carritoCompras.cliente != null && this.carritoCompras.cliente.id_cliente < 0 ){
            //vemos que se trata de una caja comun entonces solos e le debe permitir las ventas a credito              

            //ocultamos el boton tipo de venta a contado
            Ext.getCmp('ComprasMostrador-doNuevaVenta-Menu-Contado').hide( );
                
            //mostramos el boton tipo de venta a credito
            Ext.getCmp('ComprasMostrador-doNuevaVenta-Menu-Credito').show( );
                 
            Aplicacion.ComprasMostrador.currentInstance.setTipoVenta ("credito");             
            
            //quitamos todas las cards del carousel
            Ext.getCmp('ComprasMostrador-doNuevaVentaForm-Carousel').removeAll(false);
            
            //solo agregamos al qeu contiene el boton de vender
            Ext.getCmp('ComprasMostrador-doNuevaVentaForm-Carousel').insert( 0, Ext.getCmp('ComprasMostrador-doNuevaVentaCobrar') );                                
                             
        }
		
    }//if cliente

    Ext.getCmp('ComprasMostrador-doNuevaVentaSubTotal' ).setValue( POS.currencyFormat( subtotal ) );
    Ext.getCmp('ComprasMostrador-doNuevaVentaTotal' ).setValue( POS.currencyFormat( total ) );

    this.carritoCompras.subtotal = subtotal;
    this.carritoCompras.total = total;
	
};






/**
  * Se llama para crear por primera vez el nuevo panel de venta
  **/
Aplicacion.ComprasMostrador.prototype.doNuevaCompraPanelCreator = function (	 ){
	
    //cancelar busqueda
    dockedCancelar = {
        xtype : 'button',
        text: 'Regresar',
        ui :'back',
        handler : function(){
            sink.Main.ui.setActiveItem( Aplicacion.ComprasMostrador.currentInstance.mostradorPanel , 'slide');
        }
    };




    //hacer la venta
    dockedVender = {
        xtype : 'button',
        text: 'Vender',
        ui :'confirm',
        handler : function (){
            Aplicacion.ComprasMostrador.currentInstance.doCompra();
        }
    };


    //toolbar
    dockedItems = {
        xtype: 'toolbar',
        dock: 'bottom',
        items: [ dockedCancelar , {
            xtype: 'spacer'
        } ]
    };
	
	
    this.doNuevaCompraPanel = new Ext.Panel({
        listeners : {
            "show" : function(){
			            
                Aplicacion.ComprasMostrador.currentInstance.doNuevaCompraPanelUpdater();
			        	        			        
			   
            }
        },
        cls: 'cards',
        dockedItems : dockedItems,
        layout: {
            type: 'vbox',
            align: 'stretch'
        },
        defaults: {
            flex: 1
        },
        items: [
        /** ************************ **
		 **		Carrusel de arriba
		 ** ************************ **/
        {
            xtype: 'fieldset',
            bodyPadding: 10,
            id:'ComprasMostrador-doNuevaVentaForm',
            items: [
            new Ext.form.Text({
                label : 'Cliente',
                id: 'ComprasMostrador-doNuevaVentaCliente'
            }),
            new Ext.form.Text({
                label : 'Limite de Credito',
                id: 'ComprasMostrador-doNuevaVentaClienteCredito',
                hidden : true
            }),
            new Ext.form.Text({
                label : 'Credito restante',
                id: 'ComprasMostrador-doNuevaVentaClienteCreditoRestante',
                hidden : true
            }),
            new Ext.form.Toggle({
                listeners : {
                    "change" : function ( a, b, newVal, oldVal ){
                        Aplicacion.ComprasMostrador.currentInstance.carritoCompras.factura = newVal == 1;
                    }
                },
                id : 'ComprasMostrador-doNuevaVentaFacturar',
                hidden : true,
                label : 'Facturar'
            }),
            new Ext.form.Text({
                label : 'Subtotal',
                id: 'ComprasMostrador-doNuevaVentaSubTotal'
            }),
            new Ext.form.Text({
                label : 'Descuento',
                id: 'ComprasMostrador-doNuevaVentaDescuento'
            }),
            new Ext.form.Text({
                label : 'Total',
                id: 'ComprasMostrador-doNuevaVentaTotal'
            })
            ]
			
        }, {
            /** ************************ **
			 **		Carrusel de abajo
			 ** ************************ **/
            id : 'ComprasMostrador-doNuevaVentaForm-Carousel',
            xtype: 'carousel',
            direction: 'vertical',
            listeners:{
                "cardswitch":function(){
	            
                /*if( this.getActiveIndex() == 2 && Ext.getCmp('Mostrador-doNuevaVentaImporte').getValue( ) == "" ){
	                    
	                    //aqui entra si el pago es en efectivo
	                    
	                    Ext.getCmp('Mostrador-doNuevaVentaCobrarButton').hide();
	                    Ext.getCmp('Mostrador-doNuevaVentaImporte').show();
	                    
                        kconf = {
                            type : 'num',
                            ubmitText : 'Cobrar',
                            callback : function ( campo ){
                                Aplicacion.ComprasMostrador.currentInstance.doCompra();
                            }
                        };

                        POS.Keyboard.Keyboard( Ext.getCmp('Mostrador-doNuevaVentaImporte'), kconf );	
                        		
	                }else{
	                    
	                    //aqui entra si el pago es con cheque
	                   Ext.getCmp('Mostrador-doNuevaVentaCobrarButton').show();
	                    Ext.getCmp('Mostrador-doNuevaVentaImporte').hide();
	                    
	                }//if*/
                }
            },
            activeItem : 1,
            indicator:true,
            //draggable : false,
            //ui: 'light',
            items: [
            /****************************
				 **		Primera tarjeta, CONTADO/CREDITO
				 ** ************************ **/
            {
				    		    
                layout:'hbox',
                style:{
                    width:'100%',
                    marginLeft: '25%'
                },
                items:[{
                    id:'ComprasMostrador-doNuevaVenta-Menu-Contado',
                    style:{
                        width:'190px !important',
                        height:'100px !important'
                    },
                    html:'<img onClick="Aplicacion.ComprasMostrador.currentInstance.setTipoVenta(\'contado\')" src="../media/venta_contado.png"  />'
                + '<br>Venta a contado'
                },
                {
                    id:'ComprasMostrador-doNuevaVenta-Menu-Credito',
                    style:{
                        width:'190px !important',
                        height:'100px !important',
                        marginLeft: '100px'
                    },
                    html:'<img onClick="Aplicacion.ComprasMostrador.currentInstance.setTipoVenta(\'credito\')" src="../media/venta_credito.png"  />'
                + '<br>Venta a credito'
                }]
            },
            /** ************************ **
				 **		Segunda tarjeta EFECTIVO/CHEQUE
				 ** ************************ **/
            {
                id:'ComprasMostrador-doNuevaVentaForm-TipoPago',
                layout:'hbox',
                style:{
                    width:'100%',
                    marginLeft: '25%'
                },
                items:[{
                    id:'ComprasMostrador-doNuevaVenta-Menu-Efectivo',
                    style:{
                        width:'190px !important',
                        height:'100px !important'
                    },
                    html:'<img  onClick="Aplicacion.ComprasMostrador.currentInstance.setTipoPago(\'efectivo\')" src="../media/pago_efectivo.png"  />'
                + '<br>Efectivo'
                },
                {
                    id:'ComprasMostrador-doNuevaVenta-Menu-Cheque',
                    style:{
                        width:'190px !important',
                        height:'100px !important',
                        marginLeft: '100px'
                    },
                    html:'<img  onClick="Aplicacion.ComprasMostrador.currentInstance.setTipoPago(\'cheque\')" src="../media/pago_cheque.png"  />'
                + '<br>Cheque'
                }]
            },

            /** ************************ **
				 **		Tercera tarjeta, ASKS FOR DA MONEY !
				 ** ************************ **/		
            new Ext.form.FormPanel({
 
                style:{
                    width:'100% !important'
                },
                id : "ComprasMostrador-doNuevaVentaCobrar",
                items: [
                new Ext.form.Text({
                    label : 'Importe',
                    id: 'ComprasMostrador-doNuevaVentaImporte',
                    listeners:{
                        "focus":function(){
                            kconf = {
                                type : 'num',
                                submitText : 'Comprar',
                                callback : function ( campo ){
                                    Aplicacion.ComprasMostrador.currentInstance.doCompra();
                                }
                            };

                            POS.Keyboard.Keyboard( Ext.getCmp('ComprasMostrador-doNuevaVentaImporte'), kconf );
                        }
                    }
                })/*,
							new Ext.Button({
                                ui  : 'action', 
                                text: 'Vender', 
                                id : "Mostrador-doNuevaVentaCobrarButton",
                                //hidden : true,
                                handler: function(){
                                    Aplicacion.ComprasMostrador.currentInstance.doCompra();
                                },
                                style:{
                                    marginTop:'30px'
                                }
                            })*/
                ]
					 
            })
				
            ]
        }]
    });
};


//Pesar el producto
Aplicacion.ComprasMostrador.prototype.pesarProducto = function (id_unique){

    	POS.ajaxToClient({
                module : "bascula",
                raw_args : {
                    send_command : 'P',
                    read_next : 8
                },
                success : function ( r ){
			
			//valor = r.reading
            if(r.success == true){


                var trimmed = r.reading;



                trimmed = trimmed.replace( /^\s+|\s+$/g, "" );

                trimmed = trimmed.replace( "K", ""  );



                //Ext.getCmp("ComprasMostrador-carritoCantidad"+ id_unique + "Text").setValue(trimmed);





                for (var i=0; i < Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items.length; i++) {



                    if(Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].idUnique  == id_unique ){

                        Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].cantidad = parseFloat( (trimmed == null || trimmed == "null")? 0 :trimmed );

                        break;

                    }

                }



                Aplicacion.ComprasMostrador.currentInstance.refrescarMostrador();



            }else{

                 Ext.Msg.alert("Alerta", "Error al acceder a la bascula" );

            }



                },
                failure: function (){
                    //client not found !
                    if(DEBUG){
                        console.warn("client not found !!!", r);						
                    }
                }
            });
/*
    POS.ajaxToClient({
        module : "bascula",
        args : null,
        success : function ( r ){
					
            //ok client is there...
            if(DEBUG){
                console.log("bascula responded", r);						
            }            

            if(r.success == true){

                var trimmed = r.reading;

                trimmed = trimmed.replace( /^\s+|\s+$/g, "" );
                trimmed = trimmed.replace( "K", ""  );

                //Ext.getCmp("ComprasMostrador-carritoCantidad"+ id_unique + "Text").setValue(trimmed);


                for (var i=0; i < Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items.length; i++) {

                    if(Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].idUnique  == id_unique ){
                        Aplicacion.ComprasMostrador.currentInstance.carritoCompras.items[i].cantidad = parseFloat( (trimmed == null || trimmed == "null")? 0 :trimmed );
                        break;
                    }
                }

                Aplicacion.ComprasMostrador.currentInstance.refrescarMostrador();

            }else{
                 Ext.Msg.alert("Alerta", "Error al acceder a la bascula" );
            }


        },
        failure: function (){
            //client not found !
            if(DEBUG){
                console.warn("client not found !!!", r);						
            }
        }
    }); 
*/

};




Aplicacion.ComprasMostrador.NET_GROSS = true;
Aplicacion.ComprasMostrador.BASCULA_ACTIVA = 0;

Aplicacion.ComprasMostrador.prototype.setDisplay = function(command_to_send){

    if(command_to_send == "CB")
    {
        
        return Aplicacion.ComprasMostrador.bascula.cambiar_bascula();

    }

	if(command_to_send == "NG"){

		if(Aplicacion.ComprasMostrador.NET_GROSS){
			command_to_send = "G";
		}else{
			command_to_send = "N";
		}

		Aplicacion.ComprasMostrador.NET_GROSS = !Aplicacion.ComprasMostrador.NET_GROSS;
	}
				

    Aplicacion.ComprasMostrador.bascula.enviar_comando( command_to_send );
    

};

//solo cargar esta aplicacion si POS_COMPRA_A_CLIENTES es veraadero
if(DEBUG)
    console.warn("POS_COMPRA_A_CLIENTES esta activado !!!")

//if(POS_COMPRA_A_CLIENTES)
POS.Apps.push( new Aplicacion.ComprasMostrador() );
