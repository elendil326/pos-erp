<?php 

class LoginComponent implements GuiComponent
{

	private $next_hop;
	private $api_login_method;
	private $extern_login;

	function __construct(){
		$this->next_hop = ".";
	}


	public function setExternLoginUrl( $url ){
		$this->extern_login = $url;
	}

	private function printAPICall( ){
		?>
		<script type="text/javascript" charset="utf-8">
		
				function enviarCorreo(){
					POS.API.POST("api/pos/mail/enviar", 
						{
							cuerpo : "Bienvendio",
							destinatario : "", 
							titulo : "Bienvenido a POS ERP"
						}, 
						{
							callback : function( a ){ 
								if(!Ext.isIE)console.log(a);
							}
						});
					Ext.MessageBox.alert('Enviando correo', 'Se ha enviado un correo a <?php // echO $este_usuario->getCorreoElectronico(); ?>.');
				}
				
				
				var win;
				
				var required = '';

				html = "<table ><tr ><td>"
							+"<img src='../../../media/1335388431_Forward.png'>"
							+"</td><td style='vertical-align:top'><br>"
							+"<h1>Dele la bienvenida a </h1>"
							+"<p>&iquest; Desea enviar un correo a  <span style='color:gray'>( )</span> para darle una"
							+ " breve introduccion a POS ERP ?</p>"
							+"</td></tr></table>";

			    function lostpass() {
			        if (!win) {
			            var form = Ext.widget('form', {
			                layout: {
			                    type: 'vbox',
			                    align: 'stretch'
			                },
			                border: false,
			                bodyPadding: 5,
							html : html,
			                buttons: [{
			                    text: 'No enviar',
			                    handler: function() {
			                        this.up('form').getForm().reset();
			                        this.up('window').hide();
			                    }
			                }, {
			                    text: 'Enviar',
			                    handler: function() {
			                        if (this.up('form').getForm().isValid()) {
			                            // In a real application, this would submit the form to the configured url
			                            // this.up('form').getForm().submit();
			                            this.up('form').getForm().reset();
			                            this.up('window').hide();
										enviarCorreo();
			                        }
			                    }
			                }]
			            });

			            win = Ext.widget('window', {
			                title: 'Nuevo personal creado correctamente',
			                closeAction: 'hide',
			                width: 450,
			                height: 190,
			                layout: 'fit',
			                resizable: false,
			                modal: true,
			                items: form
			            });
			        }
			        win.show();
			    }




			var getParameterByName = function (name){
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
						
						if(!Ext.isIE)console.log(o)
						
						if(o.login_succesful === false){
							//credenciales invalidas
							Ext.get("LoginComponentResponse").show();
							return;
						}
						
						if(o.login_succesful === true){
							//the cookie should be arleady set
							//Ext.util.Cookies.set( "a_t", o.auth_token );
							
							<?php
								if(is_null($this->extern_login)){
									?>if(o.status == "ok") window.location = o.siguiente_url  + getParameterByName("next_url"); <?php
								}else{
									?>if(o.status == "ok") window.location = getParameterByName("extern_login") + "?au=" +  o.auth_token; <?php
								}
							?>	
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
				margin : 35px auto;
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
			
			<table cellspacing="0" >
				<tr>
					

		<form action="<?php echo $this->next_hop; ?>" method="POST">
			<table border="0" cellspacing="5" cellpadding="5" class='overrideTHTD'>
				<tr>
					<td rowspan="6">
						<?php
							if(is_file("static/" . IID . ".jpg")){
								$file = "static/" . IID . ".jpg";
							}else{
								$file = "../media/safe.png";
							}
						?>
						<div style="width: 256px; height: 265px; background-size:256px; background-repeat: no-repeat; background-image: url(<?php echo $file; ?>)">
							&nbsp;
						</div>
						<!--<img width="256" height="256" src="<">
						-->
					&nbsp;</td>
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
					<td style="text-align:right" align="right">Usuario</td>
					<td>
						<input 
							type="text" 
							name="user" 
							id="user" 
							size="10" 
							onkeypress="(function(e){var k=e.keyCode || e.which;if (k==13){snd_to_api();}})(event)"/></td>
				</tr><tr>
					<td></td>
					<td align="right" style="text-align:right">Contrase&ntilde;a&nbsp;</td><td>
						<input 
							type="password" 
							id="password" 
							name="password" 
							size="10" 
							onkeypress="(function(e){var k=e.keyCode || e.which;if (k==13){snd_to_api();}})(event)"/></td>

				</tr><tr valign="top">
					<td colspan=3 style="text-align:center">
						<?php
						if( !is_null($this->api_login_method) ){
		
							if(is_null($this->extern_login)){
								 ?><div class='POS Boton OK' onClick="snd_to_api()" style='width:150px' >Iniciar sesion</div><?php
							}else{
								 ?><div class='POS Boton OK' 
									onClick="snd_to_api()" 
									style='width:150px; height: 55px;' >Iniciar sesion y otorgar permiso</div><?php
							}
							?>
							</div>
							<div class='POS Boton' onClick="lostpass()"  style='width:150px' >Olvide mi constrase&ntilde;a</div>
							<?php
						}else{
							?><input type="submit" value="Ingresar" onkeypress=""/><?php
						}
						?>
					</td>
				</tr>
				<tr >
					<td colspan=4 style="text-align:center">
						<hr>
					</td>
				</tr>
				<tr>

					<td align=center style="text-align:center">
						<!--
						<a href="s/v1/"><img src="../media/1334968530_iPad.png"></a><br>
						iPad
					-->
					</td>
				</tr>				
			</table>
			<input type="hidden" name="do_login" value=1>
		</form>
		</div>
		<?php

	}

}
