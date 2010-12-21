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



POS.U = {
	g : null
};

Ext.Ajax.timeout = 5000;

POS.A = {
    failure : false,
    sendHeart : true
};



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


function enviarHeartTask(){
    if(POS.A.sendHeart && !POS.A.failure){
        Aplicacion.Clientes.currentInstance.checkVentasDbDiff();
        Aplicacion.Clientes.currentInstance.checkClientesDbDiff();
    }

    setTimeout("enviarHeartTask()",POS.CHECK_DB_TIMEOUT );
}


setTimeout("enviarHeartTask()",POS.CHECK_DB_TIMEOUT * 2);


POS.error = function (ajaxResponse, catchedError)
{

/*
			setTimeout( "Aplicacion.Clientes.currentInstance.checkVentasDbDiff()", POS.CHECK_DB_TIMEOUT );
            setTimeout( "Aplicacion.Clientes.currentInstance.checkClientesDbDiff()", POS.CHECK_DB_TIMEOUT );

*/
//    if(ajaxResponse.request.headers && ajaxResponse.status == 0 ){   }
/*
	Ext.Msg.alert("Error ", catchedError);
	console.warn( "POS ERROR ! ");
	console.warn( "ajaxResponse", ajaxResponse );
	console.warn( "catchedError", catchedError);	
*/
};


POS.CHECK_DB_TIMEOUT = 5000;
