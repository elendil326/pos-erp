<?php 

class LoginComponent implements GuiComponent
{




	private $next_hop;
	private $api_login_method;



	function __construct(){
		$this->next_hop = ".";
	}


	private function printAPICall( ){
		?>
		<script type="text/javascript" charset="utf-8">
		
			function getParameterByName(name)
			{
			  name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
			  var regexS = "[\\?&]" + name + "=([^&#]*)";
			  var regex = new RegExp(regexS);
			  var results = regex.exec(window.location.href);
			  if(results == null)
			    return "";
			  else
			    return decodeURIComponent(results[1].replace(/\+/g, " "));
			}
			
			
			var snd_to_api = function (   ){
				Ext.Ajax.request({
					method 	: "POST",
					url 	: "<?php echo $this->api_login_method; ?>",
					success : function(a,b,c){ 
						/* POS.API.ajaxCallBack( callback, a, b, c );  */
						console.log("back")
						try{
							o = Ext.JSON.decode( a.responseText );

						}catch(e){
							console.error("JSON NOT DECODABLE:" , a.responseText);
							
							Ext.MessageBox.show({
							           title: 'Error',
							           msg: "Ocurrio un problema con la peticion porfavor intente de nuevo.",
							           buttons: Ext.MessageBox.OK,
							           icon: "error"
							       });
							return;

						}
						
						console.log(o)
						
						if(o.login_succesful === false){
							//credenciales invalidas
							Ext.get("LoginComponentResponse").show();
							return;
						}
						
						if(o.login_succesful === true){
							Ext.util.Cookies.set( "a_t", o.auth_token );
							
							if(o.status == "ok") window.location = "gerencia/" + getParameterByName("next_url");
						}

					},
					failure : function(a,b,c){ 

						Ext.get("LoginComponentResponse").html("Error").show();
					},
					params  : {
						usuario : Ext.get("user").getValue(),
						password : Ext.get("password").getValue(),
						request_token : true
					}
				});
			}

		</script>
		
		<?php
	}


	public function submitTo( $submit_to ){
		$this->next_hop = $submit_to;
	}


	public function setLoginApiCall($addr){
		$this->api_login_method = $addr;
	}


	function renderCmp(){
	
		$this->printAPICall();
		
		?>
		<style type="text/css" media="screen">
			.LoginComponent{

				width: 500px;
				margin : 150px auto;
				background-color: white;
				color: rgb( 53, 98, 162 );
				-moz-border-radius: 15px;
				border-radius: 15px;
				padding: 5px;

				font-family: "lucida grande",tahoma,verdana,arial,sans-serif;
				
				color: #0E385F;
				
				line-height: 29px;
				

				word-spacing: -1px;
			}
			
			.LoginComponent .Error{
				background: #FFEBE8;
				border: 1px solid #DD3C10;
				line-height: 15px;
				margin: 10px 0 0 0;
				text-align: center;

				overflow: hidden;
			}
		</style>
		<div align="center" class="LoginComponent">
			
			<table cellspacing="0">
				<tr>
					

		<form action="<?php echo $this->next_hop; ?>" method="POST">
			<table border="0" cellspacing="5" cellpadding="5">
				<tr>
					<td rowspan="6"><img width="256" height="256" src="../../media/safe.png">&nbsp;</td>
				</tr>
				<tr>
					<td>
					</td>
					<td colspan=2>
						<div style="padding: 5px">
						<h2>Iniciar sesion</h2>							
						</div>
					</td>
				</tr>
				<tr>
					<td>
					</td>
					<td colspan=2 >
						<div style="margin-top: 30px; display: none;" id="LoginComponentResponse" class="Error" >
							Credenciales invalidas
						</div>
					</td>
				</tr>
				<tr>
					<td></td>
					<td align="right">Usuario</td>
					<td><input type="text" name="user" id="user" size="10"/></td>
				</tr><tr>
					<td></td>
					<td align="right">Contrase&ntilde;a</td><td>
						<input type="password" id="password" name="password" size="10" onkeypress=""/>
					</td>
				</tr><tr valign="top">
					<td></td>
					<td></td>
					<td align="right">
						<?php
						if(!is_null($this->api_login_method)){
							?><input type="button" value="Ingresar" onClick="snd_to_api()"/><?php
						}else{
							?><input type="submit" value="Ingresar" onkeypress=""/><?php
						}
						?>
					</td>
				</tr>
			</table>
			<input type="hidden" name="do_login" value=1>
		</form>
		</div>
		<?php

	}

}