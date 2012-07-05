<?php




	define("BYPASS_INSTANCE_CHECK", true);


	require_once("../../server/bootstrap.php");
	
	
	
	/***
	 * 
	 *  Page Rendering
	 * 
	 * 
	 * */
	$p = new JediComponentPage( );


	$headers = array( 	"id_request" 	=> "request_id",
						"email" 		=> "email",
	 					"ip" 			=> "ip",
	 					"fecha" 		=> "date_requested",
	 					"date_validated" => "date_validated",
	 					"date_installed" => "date_installed");	
	
	
	$t = new TableComponent( $headers , InstanciasController::BuscarRequests());

	function FormatTimeSpecial($ut){
		if(is_null($ut) or (strlen($ut) == 0)){
			return "";
		}
		
		return FormatTime($ut);
	}

	$t->addOnClick("id_request", "(function(a){window.location = 'requests.ver.php?rid='+a;})");

	$t->addColRender("fecha", "FormatTimeSpecial");
	$t->addColRender("date_validated", "FormatTimeSpecial");
	$t->addColRender("date_installed", "FormatTimeSpecial");

	$p->addComponent( $t );	


	$p->render( );
