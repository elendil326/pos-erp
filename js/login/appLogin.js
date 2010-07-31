

/* --------------------------------------------
	appLogin contiene todas las funciones para 
	el manejo de validacion de usuarios
-------------------------------------------- */


AppLogin = function ()
{
	
	
	this._init();

};



AppLogin.prototype._init = function ()
{
	this.createBasicHTML();

};


AppLogin.prototype.createBasicHTML = function ()
{
	var html_content = '';
	html_content += '<div id="message"><img src="media/loader.gif"></div>';
	html_content += '<div id="login" class="login">';
	html_content += '</div>';
	html_content += '<div id="login_content" class="login_content" >';
	html_content += '	<div>Nombre  <input id="login0" type="text" name="user"></div>';
	html_content += '	<div>Clave <input id="login1" type="password" name="pwd"></div>';
	html_content += '	<div><input type="button" id="login2" style="width: 70px" value="aceptar" onclick="login.checkCurrentLoginInfo()"></div>';
	html_content += "</div>";
	document.getElementById("work_zone").innerHTML = html_content;	
};


AppLogin.prototype.checkCurrentLoginInfo = function ()
{
	
	//Sacamos datos de los inputs
	var user = $('#login0').val();
	var pwd = $('#login1').val();

	//Comprobacion que no haya datos invalidos
	if(user.length > 2 && pwd.length > 2 )
	{
		this.submitData( user, pwd );
	}
	else
	{
      	$("#login_content").effect("shake", { times:2 }, 100);
	}
	
};



AppLogin.prototype.submitData = function ( u, p )
{
	//fade content
	$('#login_content').fadeOut('slow', function() {
		//show loader animation
    	$('#message').fadeIn('slow', function() {
			//actual ajax call
			$.ajax({ 
				url: "proxy.php", 
				dataType: 'json',
				data: {'action': '2001', 'u' : u, 'p': p },
				context: document.body, 
				complete: login.ajaxReturned,
			});
	  	});
  	});
};


AppLogin.prototype.wrong = function ( t )
{
	if(DEBUG){
		console.log("Credenciales invalidas o algo anda mal.", "intentos:" + t );
	}
	
	//limpiar los campos
	var user = $('#login0').val("");
	var pwd = $('#login1').val("");
	
   	$('#login_content').fadeIn('slow', function() {
   		$("#login_content").effect("shake", { times:2 }, 100);
	});
};


AppLogin.prototype.ajaxReturned = function (data)
{
	
	//fade slider
	$('#message').fadeOut('slow', function() {
		//show loader animation

			
			if(data.status != 200){
				//algo anda mal
				return;
			}
			
			var x;
			
			try{
				x = jQuery.parseJSON( data.responseText );
			}catch(e){
				//invalid json
				if(DEBUG){console.error("Invalid json", data.responseText);}
				return;
			}
			
			if(!x){
				if(DEBUG){console.error("Json is null" , data.responseText);}
				return;
			}
			
			if(!x.succes){
				//algo anda mal, success igual a falso
				switch(x.reason){
					case 100 : 
						//credenciales invalidas
						login.wrong( x.intentos );
					break;
					case 101 :
					
						if(DEBUG){
							console.warn( x.text );
						}
						alert(x.text);
						login.wrong(  );
					break;
				}
				return;
			}
			
			//verificar sucursal
			login.verificarSucursal();
			
			if(DEBUG){
				console.log("Sucursal" , x.payload.sucursal);
			}
			
			window.location = x.payload.redir;

  	});
};




AppLogin.prototype.verificarSucursal = function ()
{
			

};
