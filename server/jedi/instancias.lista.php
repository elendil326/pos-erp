<?php

/*  ****************************************************
	*	Opciones de instancias
	**************************************************** */
?>


	<script type="text/javascript" charset="utf-8">
		function ver_detalles( iid )
		{
			window.location = 'instancias.php?action=detalles&iid=' + iid;
		}
	</script>
	<h2>Opciones</h2>
	<input type="button" name="" value="Nueva instancia" id="">

	
<?php

/*  ****************************************************
	*	Lista de instancias 
	**************************************************** */
	
	echo  "<h2>Lista de instancias</h2>";
	
	$sql = "SELECT * FROM instances;";
	
	$rs = $core_conn->Execute($sql);
	
	if(count($rs)==0){

		echo "no hay instancias !";
		
	}else{
		$results = $rs->GetArray();


		//render the table
		$header = array(  
			"instance_id" 	=> "INSTANCE_ID",
			"desc" 			=> "DESC",
			"DB_NAME" 		=> "DB_NAME",
			"DB_DEBUG"		 => "DB_DEBUG",
			"HEARTBEAT_INTERVAL"	 => "HEARTBEAT_INTERVAL",
			"DEMO" 			=> "DEMO" );

		$tabla = new Tabla( $header, $results );
		$tabla->addNoData("No hay instancias registradas.");
		$tabla->addOnClick("instance_id", "ver_detalles");		
		$tabla->render();
		
	}
	


