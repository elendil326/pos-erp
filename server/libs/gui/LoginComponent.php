<?php 

class LoginComponent implements GuiComponent
{




	private $next_hop;




	function __construct()
	{
		$this->next_hop = ".";
	}


	private function printAPICall( ){
		?>
		<script type="text/javascript" charset="utf-8">

			var snd_to_api = function (   ){
				Ext.Ajax.request({
					method 	: "POST",
					url 	: "<?php echo $this->api_login_method; ?>",
					success : function(a,b,c){ 
						/* POS.API.ajaxCallBack( callback, a, b, c );  */
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
						
						Ext.util.Cookies.set( "a_t", o.auth_token );
						
						if(o.status == "ok") window.location = "gerencia/";
					},
					failure : function(a,b,c){ alert("ERROR"); },
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

	public function submitTo( $submit_to )
	{
		$this->next_hop = $submit_to;
	}

	private $api_login_method;
	public function setLoginApiCall($addr){
		$this->api_login_method = $addr;
	}

	function renderCmp()
	{
	
		$this->printAPICall();
		
		?>
		<div align="center" >
		<form action="<?php echo $this->next_hop; ?>" method="POST">
			<table border="0" cellspacing="5" cellpadding="5">
				<tr>
					<td rowspan="5"><img width="256" height="256" src="../../media/safe.png"></td>
				</tr>
				<tr>
					<td>
					</td>
					<td colspan=2><h2>Inicio de sesion</h2>
					</td>
				</tr>
				<tr>
					<td></td>
					<td align="right">Usuario</td>
					<td><input type="text" name="user" id="user" size="40"/></td>
				</tr><tr>
					<td></td>
					<td align="right">Contrase&ntilde;a</td><td>
						<input type="password" id="password" name="password" size="40" onkeypress=""/>
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