<?php

	define("BYPASS_INSTANCE_CHECK", true);

	require_once("../../../server/bootstrap.php");
	
	function parseRequests(){
		switch($_GET["do"]){
			case "actualizar_instancias":
				Logger::log("---------------------------");
				
				Logger::log("Jedi requested update instances");
				
				$result = InstanciasController::Actualizar_Todas_Instancias();

				if(!is_null($result)){//algo salio mal						
					break;
				}
				
				//todo salio bien...
				header("Location: instancias.lista.php");
			break;
			default:
			
		}
	}
	
	
	
	
	if(isset($_GET["do"])){
		parseRequests();
	}

	$p = new JediComponentPage( );
	$p->addComponent( new TitleComponent( "Instancias" ) );


	/**
	  *
	  * Lista de instancias
	  *
	  **/
	$p->addComponent( new TitleComponent( "Instancias instaladas", 2 ) );

	$p->addComponent( new FreeHtmlComponent( '<div class="POS Boton OK"  onclick="window.location=\'instancias.lista.php?do=actualizar_instancias\'">Actualizar Instancias</div>') );	

	$headers = array( 	"instance_id" => "Instance ID",
						"fecha_creacion" => "Creada",
	 					"descripcion" => "Descripcion",
						"instance_token" => "Token");
	
	
	
	$t = new TableComponent( $headers , InstanciasController::Buscar());
	function todate($unixtime){
		if($unixtime == 0){
			return "";
		}
		return date("r", $unixtime);
	}
	$t->addColRender( "fecha_creacion", "todate" );
	
	$t->addOnClick( "instance_id" , "(function(i){window.location='instancias.ver.php?id='+i;})"  );
	$p->addComponent( $t );	


	$p->render( );






