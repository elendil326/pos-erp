<?php


define("BYPASS_INSTANCE_CHECK", true);

	require_once("../../../server/bootstrap.php");
	
	function parseRequests(){
		switch($_GET["do"]){
			case "actualizar_instancias":
				Logger::log("---------------------------");
				
				Logger::log("Jedi requested update instances");
				
				
				$result = InstanciasController::Actualizar_Todas_Instancias($_GET['instance_ids']);

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

	$p->partialRender();
	?>
	
	<script>
	var ids= new Array();
		
		function addId(instance_id){
			var esta = false;
			for(i = 0; i < ids.length; i++)
			{
				if(ids[i] == instance_id)
				{
					esta = true;
					if(!Ext.get('chk_'+instance_id).dom.checked)		
						ids.splice(i,1);
				}
			}
			if(!esta)
				ids.push(instance_id);
			
		}
	
		function actualizarInstancias(){
			console.log(ids);
			if(ids.length < 1){
				alert('No ha seleccionado ninguna instancia para actualizar');
				return;
			}
			console.log("Encodeado:",Ext.JSON.encode(ids));

			window.location='instancias.bd.php?do=actualizar_instancias&instance_ids='+Ext.JSON.encode(ids);
		}
		
	</script>
	
	<?php
	$p->addComponent( new TitleComponent( "Instancias" ) );
	

	/**
	  *
	  * Lista de instancias
	  *
	  **/
	$p->addComponent( new TitleComponent( "Instancias instaladas", 3 ) );

	

	$headers = array( 							
						"instance_id" => "instnace_id",
						"instance_token" => "instance_token",
						"fecha_creacion" => "Creada",
	 					"db_name" => "db_name",
	 					"db_driver" => "db_driver",
	 					"db_host" => "db_host"
					);
	
	$t = new TableComponent( $headers , InstanciasController::Buscar());
	$t->addColRender( "fecha_creacion", "FormatTime" );	
	$t->addColRender("instance_id", "getActiva");

	$p->addComponent( $t );	

	function getActiva($instance_id) {
    	return "<input type=\"checkbox\" id=\"chk_{$instance_id}\" onclick=\"addId({$instance_id})\">";
	}


	$p->addComponent( new FreeHtmlComponent( '<div class="POS Boton OK"  onclick="actualizarInstancias()">Actualizar instancias seleccionadas</div>') );	

	$p->render( );







