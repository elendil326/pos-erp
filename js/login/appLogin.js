

/* --------------------------------------------
	appLogin contiene todas las funciones para 
	el manejo de validacion de usuarios
-------------------------------------------- */


AppLogin = function ()
{
	
	
	this._init();

}



AppLogin.prototype._init = function ()
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
}





AppLogin.prototype.checkCurrentLoginInfo = function ()
{
	
	//Sacamos datos de los inputs
	var user = $('#login0').val();
	var pwd = $('#login1').val();

	//Comprobacion que no haya datos invalidos
	if(user.length > 5 && pwd.length > 5 )
	{
		this.submitData( u, p );
	}
	else
	{
      	$("#login_content").effect("shake", { times:2 }, 100);
	}
	
}



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
				data: {'action': '1201', 'u' : u, 'p': p },
				context: document.body, 
				complete: login.ajaxReturned,
			});
	  	});
  	});
}



AppLogin.prototype.ajaxReturned = function (data)
{
	//fade slider
	$('#message').fadeOut('slow', function() {
		//show loader animation
    	$('#login_content').fadeIn('slow', function() {
			
			if(data.status != 200){
				//algo anda mal
				return;
			}
			
			var x;
			try{
				x = jQuery.parseJSON( data.responseText );
			}catch(e){
				//invalid json
				if(DEBUG)console.log("invalid json");
				return;
			}
			
			if(!x.success){
				//algo anda mal, success igual a falso
				return;
			}
			
			//todo anda bien...
			
			
			
			
	  	});
  	});
}
