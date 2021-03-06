POS.currencyFormat = function (num){

    num = num.toString().replace(/\$|\,/g,'');
    if(isNaN(num)){
        num = "0";
    }

    sign = (num == (num = Math.abs(num)));
    num = Math.floor(num*100+0.50000000001);
    cents = num%100;
    num = Math.floor(num/100).toString();
    if(cents<10){
        cents = "0" + cents;
    }

    for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
    {
        num = num.substring(0,num.length-(4*i+3))+','+num.substring(num.length-(4*i+3));
    }

    return (((sign)?'':'-') + '$' + num + '.' + cents);

};

//almacena informacion hacerca de la sucursal
POS.infoSucursal = null;

//contiene informacion documentos que se imprimen en esa sucursal y con que impresoras se deben de imprimir
POS.documentos = null;

POS.fecha = function( f ){

	var fecha = new Date(f.replace(/-/g, "/"));
	
	var mes ;
	switch(fecha.getMonth()){
		case 0 : mes = "Enero"; 	break;
		case 1 : mes = "Febrero";	break;
		case 2 : mes = "Marzo";		break;
		case 3 : mes = "Abril";		break;
		case 4 : mes = "Mayo";		break;
		case 5 : mes = "Junio";		break;
		case 6 : mes = "Julio";		break;
		case 7 : mes = "Agosto";	break;
		case 8 : mes = "Septiembre";break;
		case 9 : mes = "Octubre";	break;
		case 10 : mes = "Noviembre";break;
		case 11 : mes = "Diciembre";break;
	}
	
	var min = fecha.getMinutes() < 10 ? "0" + fecha.getMinutes() : fecha.getMinutes(); 
	var hours = Math.abs(fecha.getHours() - 12);
	var meridiano = fecha.getHours() > 12 ? "pm" : "am";
	return fecha.getDate() +" de "+ mes + " a las " + hours + ":" + min + " " + meridiano;
}

//contiene informacion acerca de las leyendas de los tickets
POS.leyendasTicket = null;


//poner el boton de yes, con si 
Ext.MessageBox.YESNO[1].text = "Si";


Ext.Ajax.timeout 		= 10000;
POS.CHECK_DB_TIMEOUT 	= 15000;

POS.A = {
    failure : false,
    sendHeart : true
};

POS.U = {
    g : null
};

var POS_COMPRA_A_CLIENTES;

Ext.Ajax.on("requestexception", function(a,b,c){
    if(!POS.A.failure){
        POS.A.failure = true;
        //Ext.getBody().mask("Problemas de conexion, porfavor espere...");

        setTimeout("dummyRequest()", 500);
		
        if(DEBUG){
            console.warn("SERVER UNREACHABLE");
        }
    }
});

Ext.Ajax.on("beforerequest", function( conn, options ){
	
    if(POS.A.failure && options.params.action != "dummy"){
        if(DEBUG){

			console.warn("--- ---- conection is lost !!!! --------- ---");
            console.warn("server request on unreachable server" );
            console.log( "parametros:", options.params)
        }
    }
	
});

Ext.Ajax.on("requestcomplete", function(a,b,c){
    if(POS.A.failure){
        POS.A.failure = false;
    	//Ext.getBody().unmask();

		if(DEBUG){
			console.warn("--- ---- conection is back !!!! --------- ---");
		}
		
		Aplicacion.Mostrador.currentInstance.checkForOfflineSales();
    }
});


POS.fillWithSpaces = function( cad, spaces, multiline )
{
    //string is smaller than spaces needed
    //fill with spaces
    cad = cad.toString();
    
    if( cad.length < spaces ){

        for (var s = cad.length; s < spaces; s++){
            cad += " ";
        };

        return cad;
    }

    //string is larger
    if(multiline){
        
    }else{
        return cad.substring(0, spaces);
    }

}

function dummyRequest(){
    //enviar hash de inventario
	
    Ext.Ajax.request({
        url: 'proxy.php',
        params : {
            action : 1105
        },
		success : function( r ){
			if(DEBUG){console.log("dummy request completed !!!");}
			
            try{
                r = Ext.util.JSON.decode( response.responseText );
	            if((r.reboot !== undefined)){
					if(DEBUG){
						console.error("reboot !");
					}
	                //window.location = ".";
					console.warn("i was going to reboot");
	            }
            }catch(e){
                return;
            }




		},
        failure: function() {
            setTimeout("dummyRequest()", 500);
        }
    });
	
}

function task(){
	
    //enviar hash de inventario
    Ext.Ajax.request({
        url: 'proxy.php',
        scope : this,
        params : {
            action : 1101,
            hash : heartHash
        },
        success: function(response, opts) {
			
           if(DEBUG)console.log("heartbeat returned")

            setTimeout("task()", POS.CHECK_DB_TIMEOUT);
			
            if(response.responseText.length == 0){
                return;
            }
			
            try{
                r = Ext.util.JSON.decode( response.responseText );
            }catch(e){
                return;
            }



            if((r.reboot !== undefined)){
				if(DEBUG){
					console.error("reboot !");
				}

                //window.location = ".";
				console.warn("i was going to reboot");
            }

			
            if( r.success && r.hash != heartHash){

                if(DEBUG){
                    console.log( "Main hash has changed, reloading !" );
                }
								
                heartHash = r.hash;
                reload();

            }
			

        }
    });
	
}

function reload(){


    //enviar hash de inventario
    Ext.Ajax.request({
        url: 'proxy.php',
        scope : this,
        params : {
            action : 400,
            hashCheck : Aplicacion.Inventario.currentInstance.Inventario.hash
        },
        success: function(response, opts) {
            try{
                inventario = Ext.util.JSON.decode( response.responseText );
            }catch(e){
                return;
            }
            if( !inventario.success ){
                return;
            }
            Aplicacion.Inventario.currentInstance.Inventario.productos = inventario.datos;
            Aplicacion.Inventario.currentInstance.Inventario.lastUpdate = Math.round(new Date().getTime()/1000.0);
            Aplicacion.Inventario.currentInstance.Inventario.hash = inventario.hash;
            Aplicacion.Inventario.currentInstance.inventarioListaStore.loadData( inventario.datos );
        }
    });
	
	
	
	
    //enviar hash de clientes
    Ext.Ajax.request({
        url: 'proxy.php',
        scope : this,
        params : {
            action : 300,
            hashCheck : Aplicacion.Clientes.currentInstance.listaDeClientes.hash
        },
        success: function(response, opts) {
            try{
                clientes = Ext.util.JSON.decode( response.responseText );
            }catch(e){
                return;
            }
            if( !clientes.success ){
                return ;
            }
            Aplicacion.Clientes.currentInstance.listaDeClientes.lista = clientes.datos;
            Aplicacion.Clientes.currentInstance.listaDeClientes.lastUpdate = Math.round(new Date().getTime()/1000.0);
            Aplicacion.Clientes.currentInstance.listaDeClientes.hash = clientes.hash;
            Aplicacion.Clientes.currentInstance.listaDeClientesStore.loadData( clientes.datos );
        }
    });
	
	
    if(POS.U.g){
        //enviar hash de autorizaciones
        Ext.Ajax.request({
            url: 'proxy.php',
            scope : this,
            params : {
                action : 207,
                hashCheck : Aplicacion.Autorizaciones.currentInstance.listaDeAutorizaciones.hash
            },
            success: function(response, opts) {
                try{
                    autorizaciones = Ext.util.JSON.decode( response.responseText );
                }catch(e){
                    return;
                }
                if( !autorizaciones.success ){
                    return ;
                }
              
                Aplicacion.Autorizaciones.currentInstance.listaDeAutorizaciones.lista = autorizaciones.payload;
                Aplicacion.Autorizaciones.currentInstance.listaDeAutorizaciones.lastUpdate = Math.round(new Date().getTime()/1000.0);
                Aplicacion.Autorizaciones.currentInstance.listaDeAutorizaciones.hash = autorizaciones.hash;
                Aplicacion.Autorizaciones.currentInstance.listaDeAutorizacionesStore.loadData( autorizaciones.payload );
				
                //actualizamos cada row de la lista de autorizaciones mostrando su estado actual
                Aplicacion.Autorizaciones.currentInstance.updateListaAutorizaciones();
				
                Ext.Msg.confirm("Autorizaciones", "Tiene autorizaciones por atender. &iquest; Desea verlas ahora ?<br><br>",
                    function(a){
                        if(a == "yes"){
                            sink.Main.ui.setActiveItem( Aplicacion.Autorizaciones.currentInstance.listaDeAutorizacionesPanel , 'fade');
                        }
                    }
                    );
				
            }
        });
    }
}


var heartHash = null;

if(POS.A.sendHeart){
    setTimeout("task( )", POS.CHECK_DB_TIMEOUT);
}


POS.error = function (ajaxResponse, catchedError)
{
	//enviar error al cliente local !
	console.error("POS.ERROR() : ", ajaxResponse, catchedError);
	if(ajaxResponse !== undefined
		&& ajaxResponse.reboot !== undefined
		&& ajaxResponse.reboot === true
	){
		/*window.location = ".";*/
		console.warn("i was going to reboot");
	}
};



/* --------------------------------------------------------------------------------  -------------------------------------------------------------------------------- */
/* --------------------------------------------------------------------------------  -------------------------------------------------------------------------------- */
/* --------------------------------------------------------------------------------  -------------------------------------------------------------------------------- */
/* --------------------------------------------------------------------------------  -------------------------------------------------------------------------------- */
/* --------------------------------------------------------------------------------  -------------------------------------------------------------------------------- */
/* --------------------------------------------------------------------------------  -------------------------------------------------------------------------------- */
/* --------------------------------------------------------------------------------  -------------------------------------------------------------------------------- */
//extrae informacion acerca de las leyendas de los tickets
POS.RETRIVE_CONFIG = function(){

    if(DEBUG)
	{
        console.log("Loading sucursal config ....");
    }

    Ext.Ajax.request({
        url: 'proxy.php',
        scope : this,
        params : {
            action : 1106
        },
        success: function(response, opts) {
            try{
                data = Ext.util.JSON.decode( response.responseText );
            }catch(e){
                return POS.error(response, e);
            }

			/* ---------------- OK TENGO LA INFO --------------------- */
			/* ----------------                  --------------------- */	
			console.group("CONFIG");		

			console.log( "RAW_CONFIG", data );			

			POS.leyendasTicket = data.POS_LEYENDAS_TICKET;
			if(DEBUG) console.log("POS.leyendasTicket = ", POS.leyendasTicket);
			
			POS.documentos = data.POS_DOCUMENTOS;
			if(DEBUG) console.log("POS.documentos = ", POS.documentos);
			
			POS.infoSucursal = data.POS_INFO_SUCURSAL;
			if(DEBUG) console.log("POS.infoSucursal = ", POS.infoSucursal);
			
			Ext.Ajax.timeout = data.EXT_AJAX_TIMEOUT;
			if(DEBUG) console.log("Ext.Ajax.timeout = ", Ext.Ajax.timeout);


			POS.CHECK_DB_TIMEOUT = data.CHECK_DB_TIMEOUT;
			if(DEBUG) console.log("POS.CHECK_DB_TIMEOUT = ", POS.CHECK_DB_TIMEOUT);

			POS_COMPRA_A_CLIENTES = data.POS_COMPRA_A_CLIENTES;
			if(DEBUG) console.log("POS_COMPRA_A_CLIENTES = ", POS_COMPRA_A_CLIENTES);
			
			console.groupEnd();					

			imReadyToStart();
						
			/* ----------------                  --------------------- */
			/* ----------------                  --------------------- */			
        },
        failure: function( response ){
            POS.error( response );
        }
    });
};

POS.RETRIVE_CONFIG();

/* --------------------------------------------------------------------------------  -------------------------------------------------------------------------------- */
/* --------------------------------------------------------------------------------  -------------------------------------------------------------------------------- */
/* --------------------------------------------------------------------------------  -------------------------------------------------------------------------------- */
/* --------------------------------------------------------------------------------  -------------------------------------------------------------------------------- */
/* --------------------------------------------------------------------------------  -------------------------------------------------------------------------------- */
/* --------------------------------------------------------------------------------  -------------------------------------------------------------------------------- */
/* --------------------------------------------------------------------------------  -------------------------------------------------------------------------------- */