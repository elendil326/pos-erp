<?php



	define("BYPASS_INSTANCE_CHECK", true);


	require_once("../../server/bootstrap.php");
	
	$request = InstanciasController::BuscarRequests($_GET["rid"]);
	$this_request = $request[0];

	

	/***
	 * 
	 *  Page Rendering
	 * 
	 * 
	 * */
	$p = new JediComponentPage( );

	$m = new MenuComponent();
	$m->addItem("Reenviar correo de confirmacion","");
	$m->addItem("Re-instalar instancia","javascript:forceValidate();");
	$p->addComponent($m);

	$headers = array( 	"id_request" 	=> "request_id",
						"email" 		=> "email",
	 					"ip" 			=> "ip",
	 					"fecha" 		=> "date_requested",
	 					"date_validated" => "date_validated",
	 					"date_installed" => "date_installed");	
	
	
	$t = new TableComponent( $headers , $request);

	function FormatTimeSpecial($ut){
		if(is_null($ut) or (strlen($ut) == 0)){
			return "";
		}
		
		return FormatTime($ut);
	}

	
	$t->addColRender("fecha", "FormatTimeSpecial");
	$t->addColRender("date_validated", "FormatTimeSpecial");
	$t->addColRender("date_installed", "FormatTimeSpecial");

	$p->addComponent( $t );	




	$p->addComponent('
		<script type="text/javascript" charset="utf-8">
			function forceValidate(){
				Ext.Ajax.request({
							method 	: "POST",
							url 	: "../../index.php",
							success : function(a,b,c){ 
								
								try{
									o = Ext.JSON.decode( a.responseText );

									Ext.MessageBox.show({
									           title: "Instancias Controller",
									           msg: o.reason,
									           buttons: Ext.MessageBox.OK,
									           
									       });
									
								}catch(e){
									console.error("JSON NOT DECODABLE:" , a.responseText);
									Ext.MessageBox.show({
									           title: "Error",
									           msg: "Ocurrio un problema con la solicitud, porfavor intente de nuevo en un momento.",
									           buttons: Ext.MessageBox.OK,
									           icon: "error"
									       });
									return;

								}
							},

							failure : function(a,b,c){

							},
							params  :  {
								t : "ajax_validation",
								key : "' . $this_request["token"] .'"
							}
						});

			}


		</script>');
	$p->render( );
