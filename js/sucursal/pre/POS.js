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





Ext.Ajax.timeout = 5000;
POS.CHECK_DB_TIMEOUT = 15000;
POS.A = { failure : false,  sendHeart : true };
POS.U = { g : null };


Ext.Ajax.on("requestexception", function(){
    if(!POS.A.failure){
        POS.A.failure = true;
        Ext.getBody().mask("Problemas de conexion, porfavor espere...");
    }
});




Ext.Ajax.on("requestcomplete", function(){
    if(POS.A.failure){
        POS.A.failure = false;
        Ext.getBody().unmask();
    }
});


function task(){


    //enviar hash de inventario
    Ext.Ajax.request({
		url: '../proxy.php',
		scope : this,
		params : { action : 400, hashCheck : Aplicacion.Inventario.currentInstance.Inventario.hash },
		success: function(response, opts) {
			try{ inventario = Ext.util.JSON.decode( response.responseText ); }catch(e){ return; }
			if( !inventario.success ){ return; }
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
		params : { action : 300, hashCheck : Aplicacion.Clientes.currentInstance.listaDeClientes.hash },
		success: function(response, opts) {
			try{ clientes = Ext.util.JSON.decode( response.responseText ); }catch(e){ return; }
			if( !clientes.success ){ return ; }
			Aplicacion.Clientes.currentInstance.listaDeClientes.lista = clientes.datos;
			Aplicacion.Clientes.currentInstance.listaDeClientes.lastUpdate = Math.round(new Date().getTime()/1000.0);
			Aplicacion.Clientes.currentInstance.listaDeClientes.hash = clientes.hash;
			Aplicacion.Clientes.currentInstance.listaDeClientesStore.loadData( clientes.datos );
		}
	});
	
	
	
    //enviar hash de autorizaciones
	Ext.Ajax.request({
		url: '../proxy.php',
		scope : this,
		params : { action : 207, hashCheck : Aplicacion.Autorizaciones.currentInstance.listaDeAutorizaciones.hash },
		success: function(response, opts) {
			try{ autorizaciones = Ext.util.JSON.decode( response.responseText ); }catch(e){ return; }
			if( !autorizaciones.success ){ return ; }
			Aplicacion.Autorizaciones.currentInstance.listaDeAutorizaciones.lista = autorizaciones.payload;
			Aplicacion.Autorizaciones.currentInstance.listaDeAutorizaciones.lastUpdate = Math.round(new Date().getTime()/1000.0);
			Aplicacion.Autorizaciones.currentInstance.listaDeAutorizaciones.hash = autorizaciones.hash;
			Aplicacion.Autorizaciones.currentInstance.listaDeAutorizacionesStore.loadData( autorizaciones.payload );
		}
	});
	
	
	if(POS.A.sendHeart)setTimeout("task()", POS.CHECK_DB_TIMEOUT);
    
    //inventario = Aplicacion.Inventario.currentInstance.Inventario.hash;
    //clientes = Aplicacion.Clientes.currentInstance.listaDeClientes.hash;
    //autorizaciones = Aplicacion.Autorizaciones.currentInstance.listaDeAutorizaciones.hash;
}

//incir el heartbeat
if(POS.A.sendHeart)setTimeout("task()", POS.CHECK_DB_TIMEOUT);


POS.error = function (ajaxResponse, catchedError)
{

};



