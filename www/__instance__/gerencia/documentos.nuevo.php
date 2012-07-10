<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();


		$json = '{
			"margin-top" : 1,
			"margin-bottom" : 1,
			"margin-left" : 1,
			"margin-right" : 1,
			"body" : [
				{
					"type" 		: "text",
					"fontSize" 	: 17,
					"x" 		: 0,
					"y" 		: 15,
					"value" 	: "hola {nombre}"
				},
				{
					"type" 		: "text",
					"fontSize" 	: 18,
					"x" 		: 50,
					"y" 		: 15,
					"value" 	: "hola"
				},				
				{
					"type" 		: "round-box",
					"fontSize" 	: 18,
					"x" 		: 150,
					"y" 		: 650,
					"w"			: 100,
					"h"			: 100
				},
				{
					"type" 		: "text",
					"fontSize" 	: 18,
					"x" 		: 50,
					"y" 		: 150,
					"value" 	: "hola"
				}				
			]
		}';
		$json = '{
			"margin-top" : 1,
			"margin-bottom" : 1,
			"margin-left" : 1,
			"margin-right" : 1,
			"body" : [
				{
					"type" 		: "text",
					"fontSize" 	: 17,
					"x" 		: 0,
					"y" 		: 15,
					"value" 	: "hola {nombre}, como estas? seguro {nombre} !?!?!?"
				}				
			]
		}';
		ImpresionesController::Documento($json, 
			array(
				"nombre" => "Alan gonzalez"
			));

		$page->render();