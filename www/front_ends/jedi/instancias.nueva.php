<?php




	define("BYPASS_INSTANCE_CHECK", true);


	require_once("../../../server/bootstrap.php");
	
	/***
	 * 
	 *  Business Logic
	 * 
	 * 
	 * */
	
	function parseRequests(){
		switch($_GET["do"]){
			case "nueva":
				Logger::log("---------------------------");
				
				Logger::log("Jedi requested new instance");

				
				$N_I_ID = InstanciasController::Nueva(null, $_GET["d"]);

				if(is_null($N_I_ID)){
					//algo salio mal
					Logger::log("xxxxx algo salio mal con la instancia xxxxxxx");
					break;
				}
				
				//todo salio bien...
				header("Location: instancias.ver.php?id=" . $N_I_ID);
			break;
			default:
			
		}
	}
	
	
	
	
	if(isset($_GET["do"])){
		parseRequests();
	}
	
	
	
	
	/***
	 * 
	 *  Page Rendering
	 * 
	 * 
	 * */
	$p = new JediComponentPage( );


	/**
	  *
	  *	Nueva instancia
	  *
	  **/
	$p->addComponent( new TitleComponent( "Nueva instancia" ) );

	
	$p->addComponent( new FreeHtmlComponent( '<input type="text" style="font-size: 17px;" placeholder="Descripcion" id="_new_instance_desc">&nbsp;'));
	$p->addComponent( new FreeHtmlComponent( '<div class="POS Boton OK"  onclick="window.location=\'instancias.nueva.php?do=nueva&d=\'+HtmlEncode(Ext.get(\'_new_instance_desc\').getValue());">Nueva Instancia</div>') );	

	$p->addComponent( new FreeHtmlComponent( "<hr>") );



	$p->addComponent( new TitleComponent( "Utilizando una base de datos externa", 2 ) );
	$form = new FormComponent(  );
	$form->addField( "descripcion", "Descripcion", "text" );
	$form->addField( "instance_token", "instance_token", "text" );
	$form->addField( "db_user", "db_user", "text" );
	$form->addField( "db_password", "db_password", "text" );
	$form->addField( "db_name", "db_name", "text" );
	$form->addField( "db_driver", "db_driver", "text" );
	$form->addField( "db_host", "db_host", "text" );
	$form->addField( "db_debug", "db_debug", "text" );
	$p->addComponent( $form );



	$p->render( );
