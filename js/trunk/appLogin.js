

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
	
	html_content += '<div id="message"></div>';
	html_content += '<div id="login" class="login">';
	html_content += '</div><form action="serverProxy.php" id="login_form" method="POST" >';
	html_content += '<div id="login_content" class="login_content" >';
	html_content += '	<div>Nombre  <input id="login0" type="text" name="user"></div>';
	html_content += '	<div>Clave <input id="login1" type="password" name="pwd"></div>';
	html_content += '	<div><input type="button" id="login2" style="width: 70px" value="aceptar" onclick="login.checkCurrentLoginInfo()"></div>';
	html_content += '<input type="hidden" name="method" value="login">';
	html_content += "</div></form>";
	

	$('#work_zone').html(html_content);
	
	var url = document.URL;
	
	 if (url.search('contrasena') != -1) {
	 
	 	$('#message').addClass('failure');
		$('#message').html('Sus credenciales no coinciden');
		
		$('#message').slideDown();
	 
	 	return;
	 }

	if (url.search('nousuario') != -1) {
	 
	 	$('#message').addClass('failure');
		$('#message').html('Sus credenciales no coinciden');
		
		$('#message').slideDown();
	 
	 	return;
	 }
	 
	if (url.search('error') != -1) {
	 
	 	$('#message').addClass('failure');
		$('#message').html('Error grave al iniciar sesi&oacute;n');
		
		$('#message').slideDown();
	 
	 }
}




AppLogin.prototype.fadeForm = function ()
{

	

}



AppLogin.prototype.checkCurrentStatus = function ()
{
	
	
}



AppLogin.prototype.checkCurrentLoginInfo = function ()
{
	
	//Sacamos datos de los inputs
	var user = $('#login0').val();
	var pwd = $('#login1').val();
	
	//Comprobacion que no haya datos invalidos
	if(user.length != 0 && pwd.length !=0)
	{
		$('#login_form').submit();
	}
	else
	{
		//Se encontro algun input vacio
		if($('#message').css('display') == "block")
		{
			//Si ya existe un div message primero se desaparece y despues aparece el nuevo
			$('#message').fadeOut(null, function(){
			
				$('#message').addClass('warning');
				$('#message').html('Se encontr&oacute; un dato vac&iacute;o');
				$('#message').fadeIn();
			
			});
			
			
		}
		else
		{
			$('#message').addClass('warning');
			$('#message').html('Se encontr&oacute; un dato vac&iacute;o');
			$('#message').slideDown();
		}
		//alert('Se encontro algun input vacio');
	}
	
}



AppLogin.prototype.ajaxSuccess = function ( data )
{
	//Ext.get("login").hasActiveFx
	console.log(data);
	

	
}



AppLogin.prototype.ajaxFailure = function ()
{
	
}
