<?php


	define("BYPASS_INSTANCE_CHECK", true);


	require_once("../../server/bootstrap.php");

require_once("../../server/bootstrap.php");
	/*
	function parseRequests(){
		switch($_GET["do"]){
			case "tc":
				Logger::log("---------------------------");
				
				Logger::log("Jedi requested update instances");
				
				
				$result = EfectivoController::ObtenerTiposCambioDesdeServicio();
				
				echo "<b>$result.</b><br>"; var_dump($result);
				
				Logger::log("Actualizacion(es) a BD terminada(s) con éxito");
				//header("Location: instancias.lista.php");
			break;

			default:
			
		}
	}
	
	
	
	
	if(isset($_GET["do"])){
		parseRequests();
	}

<script>

		function respaldarInstancias(){
        	window.location='index.php?do=tc';
		}

		
	</script>
	<?php
	$p->addComponent( $t );	$p->addComponent( new FreeHtmlComponent( '<div class="POS Boton OK"  onclick="respaldarInstancias()">Probar tipos cambio</div>') );
*/
	$p = new JediComponentPage( );

	$p->addComponent( new TitleComponent( "POS ERP JEDI INTERFACE" ) );

	$p->render( );
