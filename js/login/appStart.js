

/* --------------------------------------------
	Application Main Entry Point
-------------------------------------------- */

var login;
var DEBUG = true;

function dostart(){
	
	//iniciar con el appLogin
	login = new AppLogin ();
}


$(document).ready(function() {

	if(DEBUG){
		console.log("Dom loaded. Iniciando Applicacion...");
	}
	
	
	if(DEBUG){
		console.log("Probando conexion con servidor...");
		//actual ajax call
			$.ajax({ 
				url: "proxy.php", 
				dataType: 'json',
				data: {'action': 'test' },
				context: document.body, 
				complete: function (data){

					try{
						data = jQuery.parseJSON( data.responseText );
						if(data.success === true)
							dostart();
						else
							if(DEBUG) console.warn("Error", data);
					}catch(e){
						//invalid json
						alert("Algo anda mal con la conexion al servidor. Probablemnte el la base de datos no existe o el usuario y contrasena estan mal en el archivo de configuracion.");
						
						if(DEBUG){console.error("Invalid json : ", data);}
						return;
					}
				}
			});

	}
	


});
	




