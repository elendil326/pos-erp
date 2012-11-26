<?php


define("BYPASS_INSTANCE_CHECK", true);

	require_once("../../server/bootstrap.php");
	
	function parseRequests(){
		switch($_GET["do"]){
			case "actualizar_instancias":
				Logger::log("---------------------------");
				
				Logger::log("Jedi requested update instances");
				
				
				$result = InstanciasController::Actualizar_Todas_Instancias($_GET['instance_ids']);
				
				if(!is_null($result)){//algo salio mal
					Logger::log("Algo salió mal al actualizar: ".$result);						
					break;
				}
				
				Logger::log("Actualizacion(es) a BD terminada(s) con éxito");
				header("Location: instancias.lista.php");
			break;

			case "respaldar_instancias":
				Logger::log("---------------------------");
				
				Logger::log("Jedi requested respaldar instances");
				
				
				$result = InstanciasController::Respaldar_Instancias($_GET['instance_ids']);
				
				if(!is_null($result)){//algo salio mal
					Logger::log("Algo salió mal al respaldar: ".$result);
					?>
						<script>
						 (function(){alert(<?php echo "'".$result."'" ?>); location.href="instancias.bd.php" })();
						</script>
					<?php							
					break;
				}
				
				Logger::log("Respaldo(s) realizado(s) con éxito");
				header("Location: instancias.lista.php");
			break;

			case "restaurar_instancias":
				Logger::log("---------------------------");
				
				Logger::log("Jedi requested restaurar instances");				
				
				$result = InstanciasController::Restaurar_Instancias($_GET['instance_ids']);
				
				if(!is_null($result)){//algo salio mal
					Logger::log("Algo salió mal al restaurar: ".$result);	
					//header("Location: instancias.bd.php");
					?>
						<script>
						 (function(){alert(<?php echo "'".$result."'" ?>); location.href="instancias.bd.php" })();
						</script>
					<?php					
					break;
				}
				
				Logger::log("Restauracion(es) realizada(s) con éxito");
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
			
			if(ids.length < 1){
				alert('No ha seleccionado ninguna instancia para actualizar');
				return;
			}
			if (!confirm('ACTUALIZAR definición de BD de las instancias seleccionadas? (Se usara el script .sql que esta en desarrollo)')) {
			    return;
			} 
			console.log("Encodeado:",Ext.JSON.encode(ids));

			window.location='instancias.bd.php?do=actualizar_instancias&instance_ids='+Ext.JSON.encode(ids);
		}
		
		function respaldarInstancias(){
			
			if(ids.length < 1){
				alert('No ha seleccionado ninguna instancia para actualizar');
				return;
			}
			if (!confirm('RESPALDAR BD de instancias seleccionadas?')) {
			    return;
			} 

			console.log("Encodeado:",Ext.JSON.encode(ids));
			var tmpMask = new Ext.LoadMask(Ext.getBody(), { msg: "NO HAGA NADA, respaldando BD de instancia(s)" });
			tmpMask.show();
			window.location='instancias.bd.php?do=respaldar_instancias&instance_ids='+Ext.JSON.encode(ids);
		}

		function restaurarInstancias(){
			
			if(ids.length < 1){
				alert('No ha seleccionado ninguna instancia para actualizar');
				return;
			}
			if (!confirm('RESTAURAR BD de instancias seleccionadas? (Se usaran los respaldos mas recientes)')) {
			    return;
			} 
			console.log("Encodeado:",Ext.JSON.encode(ids));
			var tmpMask = new Ext.LoadMask(Ext.getBody(), { msg: "NO HAGA NADA, restaurando BD de instancia(s)" });
			tmpMask.show();
			window.location='instancias.bd.php?do=restaurar_instancias&instance_ids='+Ext.JSON.encode(ids);
		}

		function dlInstancias(){
			if(ids.length < 1){
				alert('No ha seleccionado ninguna instancia para descargar');
				return;
			}
			console.log("Encodeado:",Ext.JSON.encode(ids));

			window.location='instancias.bd.dl.php?&instance_ids='+Ext.JSON.encode(ids);
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
						"instance_id" => "instance_id",
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
    	return "<input type=\"checkbox\" id=\"chk_{$instance_id}\" onclick=\"addId({$instance_id})\">&nbsp;&nbsp;<b>".$instance_id."</b>";
	}


	$p->addComponent( new FreeHtmlComponent( '<div class="POS Boton OK"  onclick="respaldarInstancias()">Respaldar BD</div>') );
	$p->addComponent( new FreeHtmlComponent( '<div class="POS Boton OK"  onclick="restaurarInstancias()">Restaurar BD</div>') );
	$p->addComponent( new FreeHtmlComponent( '<div class="POS Boton OK"  onclick="actualizarInstancias()">Actualizar BD instancias</div>') );	
	$p->addComponent( new FreeHtmlComponent( '<div class="POS Boton OK"  onclick="dlInstancias()">Descargar instancias</div>') );	

	$p->addComponent( new TitleComponent( "Nota:", 3 ) );
	$p->addComponent( new TitleComponent( "'Restaurar BD' restaurara las instancias seleccionadas con los ultimos respaldos que se encuentren en el servidor", 4 ) );

	$p->render( );







