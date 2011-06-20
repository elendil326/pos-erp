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

//extrae informacion acerca de la sucursal actual
POS.loadInfoSucursal = function(){

    if(DEBUG){
        console.log("Obteniendo informaci\ufffdn de la sucursal ....");
    }

    Ext.Ajax.request({
        url: '../proxy.php',
        scope : this,
        params : {
            action : 712
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
                    console.log("obtenicion de la informacion sin exito ");
                }
                Ext.Msg.alert("Error", informacion.reason);
                return;

            }

            if(DEBUG){
                console.log("obtenicion de la informacion exitosa ");
            }

            POS.infoSucursal = informacion.datos;

			imReadyToStart();

            if(DEBUG){
                console.log("POS.infoSucursal contiene : ", POS.infoSucursal);
            }

        },
        failure: function( response ){
            POS.error( response );
        }
    });
   

};

//contiene informacion documentos que se imprimen en esa sucursal y con que impresoras se deben de imprimir
POS.documentos = null;

//extrae informacion acerca de los docuemntos y con que impresoras se imprimen
POS.loadDocumentos = function(){

    if(DEBUG){
        console.log("Obteniendo informaci\ufffdn de los docuemtnos e impresoras ....");
    }

    Ext.Ajax.request({
        url: '../proxy.php',
        scope : this,
        params : {
            action : 1300
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
                    console.log("obtenicion de documentos sin exito ");
                }
                Ext.Msg.alert("Error", informacion.reason);
                return;

            }

            if(DEBUG){
                console.log("obtenicion de documentos exitosa");
            }

            POS.documentos = informacion.datos;

            if(DEBUG){
                console.log("POS.documentos contiene : ", POS.documentos);
            }

        },
        failure: function( response ){
            POS.error( response );
        }
    });


};

POS.fecha = function( f ){

	var fecha = new Date(f.replace(/-/g, "/"));
	
	var mes ;
	switch(fecha.getMonth()){
		case 0 : mes = "Enero"; break;
		case 1 : mes = "Febrero";break;
		case 2 : mes = "Marzo";break;
		case 3 : mes = "Abril";break;
		case 4 : mes = "Mayo";break;
		case 5 : mes = "Junio";break;
		case 6 : mes = "Julio";break;
		case 7 : mes = "Agosto";break;
		case 8 : mes = "Septiembre";break;
		case 9 : mes = "Octubre";break;
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

POS.ajaxToClient = function( module, args, callback )
{
	console.log("AJAX TO CLIENT !!");
	/** **** Cross-broswer call **** **/
    Ext.util.JSONP.request({
        url: 'http://127.0.0.1:8080/',
        callbackKey: "callback",
        params: {
			action : module,
			data : args,
			unique : Math.random()
			
		},
        callback: function(data)
        {
			console.log("AJAX TO CLIENT RETURNED !!", data);

        }
    });
	/** **** Cross-broswer call **** **/
	
}


function test_p(){
	POS.ajaxToClient(
		"Printer",
		Ext.util.JSON.encode({
		    tipo_venta: "credito",
		    impresora: "EPSON TM-U220 Receipt",
		    leyendasTicket: {
		        cabeceraTicket: "JUAN ANTONIO GARCIA TAPIA",
		        rfc: "GATJ680704DF2",
		        nombreEmpresa: "PAPAS SUPREMAS 1",
		        direccion: "JUAN MANUEL GARCIA CARMONA ING",
		        telefono: "(461) 61 28194",
		        notaFiscal: "Este comprobante no es valido para fines fiscales.",
		        cabeceraPagare: "PAGARE",
		        pagare: "DEBE(MOS) Y PAGARE(MOS) INCONDICIONALMENTE A LA ORDEN DE JUAN ANTONIO GARCIA TAPIA EN LA CIUDAD DE ____________________ EL ____________________ LA CANTIDAD DE ____________________ VALOR RECEBIDO A NUESTRA ENTERA SATISFACCION.",
		        contacto: "QUEJAS Y SUGERENCIAS (461) 61 72030",
		        gracias: "GRACIAS POR SU COMPRA"
		    },
		    items: [
		        {
		            descripcion: "papas primeras",
		            existencias: "2197",
		            existencias_procesadas: "271",
		            tratamiento: "limpia",
		            precioVenta: "11",
		            precioVentaSinProcesar: "10",
		            precio: "11",
		            id_producto: 1,
		            escala: "kilogramo",
		            precioIntersucursal: "10.5",
		            precioIntersucursalSinProcesar: "9.5",
		            procesado: "true",
		            cantidad: 2,
		            idUnique: "1_7",
		            descuento: "0"
		        }
		    ],
		    cliente: {
		        id_cliente: "2",
		        rfc: "ALCB770612",
		        nombre: "BRENDA ALFARO CARMONA",
		        direccion: "MUTUALISMO #345, COL. CENTRO",
		        ciudad: "CELAYA",
		        telefono: "a",
		        e_mail: " ",
		        limite_credito: "20",
		        descuento: "2",
		        activo: "1",
		        id_usuario: "101",
		        id_sucursal: "1",
		        fecha_ingreso: "2011-01-12 18:05:59",
		        credito_restante: 19.5
		    },
		    venta_preferencial: {
		        cliente: null,
		        id_autorizacion: null
		    },
		    factura: false,
		    tipo_pago: "cheque",
		    subtotal: 22,
		    total: 21.56,
		    pagado: "21.56",
		    id_venta: 230,
		    empleado: "Alan gonzalez hernandez",
		    sucursal: {
		        id_sucursal: "1",
		        gerente: "102",
		        descripcion: "papas supremas 1",
		        direccion: "monte radiante #123 col centro celaya",
		        rfc: "alskdfjlasdj8787",
		        telefono: "1726376672",
		        token: null,
		        letras_factura: "c",
		        activo: "1",
		        fecha_apertura: "2011-01-09 01:38:26",
		        saldo_a_favor: "0"
		    },
		    ticket: "venta_cliente"
		})
    );
}

//extrae informacion acerca de las leyendas de los tickets
POS.loadLeyendasTicket = function(){
	




    if(DEBUG){
        console.log("Obteniendo informaci\ufffdn de la sucursal ....");
    }

    Ext.Ajax.request({
        url: '../proxy.php',
        scope : this,
        params : {
            action : 1301
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
                    console.log("Error : POS.leyendasTicket ");
                }
                Ext.Msg.alert("Error", informacion.reason);
                return;

            }

            if(DEBUG){
                console.log("obtenicion de leyendasTicket exitosa.");
            }

            POS.leyendasTicket = informacion.datos;

            if(DEBUG){
                console.log("POS.leyendasTicket contiene : ", POS.leyendasTicket);
            }

        },
        failure: function( response ){
            POS.error( response );
        }
    });


};

//poner el boton de yes, con si 
Ext.MessageBox.YESNO[1].text = "Si";


Ext.Ajax.timeout = 25000;
POS.CHECK_DB_TIMEOUT = 15000;

POS.A = {
    failure : false,
    sendHeart : true
};

POS.U = {
    g : null
};


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
            console.warn("server request on unreachable server" );
            console.log( "parametros:", options.params)
        }
    }
	
});

Ext.Ajax.on("requestcomplete", function(a,b,c){
    if(POS.A.failure){
        POS.A.failure = false;
    //Ext.getBody().unmask();
    }
});


function dummyRequest(){
    //enviar hash de inventario
	
    Ext.Ajax.request({
        url: '../proxy.php',
        params : {
            action : 1105
        },
        failure: function() {
            setTimeout("dummyRequest()", 500);
        }
    });
	
}

function task(){
	
    //enviar hash de inventario
    Ext.Ajax.request({
        url: '../proxy.php',
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

                window.location = ".";
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
        url: '../proxy.php',
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
        url: '../proxy.php',
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
            url: '../proxy.php',
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
    setTimeout("task()", POS.CHECK_DB_TIMEOUT);
}


POS.error = function (ajaxResponse, catchedError){

    };

//lee la informacion de la sucursal
POS.loadInfoSucursal();

//lee la informacion de todos los documentos y las impresoras con las que se imprimen
POS.loadDocumentos();

//lee la informacion de la sucursal
POS.loadLeyendasTicket();

