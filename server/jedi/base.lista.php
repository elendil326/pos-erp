<?php

	$sql = "SELECT * FROM instances;";
	$rs = $core_conn->Execute($sql);
	$instancias = $rs->GetArray();


	function backup_tables($host, $user, $pass, $name, $tables = '*', $backup_values = true, $return_as_string = false){

		$link = mysql_connect($host,$user,$pass);

		mysql_select_db($name,$link);

		//get all of the tables
	  	if($tables == '*'){
	    	$tables = array();
	    	$result = mysql_query('SHOW TABLES');

	    	while($row = mysql_fetch_row($result))
	      		$tables[] = $row[0];
	  	}else{
	    	$tables = is_array($tables) ? $tables : explode(',',$tables);
		}

		$return = "";

	  //cycle through
	  foreach($tables as $table)
	  {
	    $result = mysql_query('SELECT * FROM '.$table);
	    $num_fields = mysql_num_fields($result);

	    //$return.= 'DROP TABLE '.$table.';';
	    $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
	    $return.= "\n\n".$row2[1].";\n\n";

		if($backup_values)
		{
		    for ($i = 0; $i < $num_fields; $i++) 
		    {
		      while($row = mysql_fetch_row($result))
		      {
		        $return.= 'INSERT INTO '.$table.' VALUES(';
		        for($j=0; $j<$num_fields; $j++) 
		        {
		          $row[$j] = addslashes($row[$j]);
		          $row[$j] = @ereg_replace("\n","\\n",$row[$j]);
		          if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
		          if ($j<($num_fields-1)) { $return.= ','; }
		        }
		        $return.= ");\n";
		      }
		    }			
		}

	    $return.="\n\n\n";
	  }
	
		if($return_as_string)
			return $return;

		$fname = '../../static_content/db_backups/db-backup-'.time().'-'.(md5(implode(',',$tables))).'.sql';
	  	$handle = fopen($fname,'w+');
	  	fwrite($handle, $return);
	  	fclose($handle);
		return $fname;
	}
	
	if(isset($_GET["BACKUP"])){
		require_once("librerias/zipfile.php");
		
		$dbs = array();
		
		if($_GET["db"] != "all_of_them"){
			$found = false;
			for ($i=0; $i < sizeof($instancias); $i++) { 
				if($instancias[$i]["DB_NAME"] == $_GET["db"]){
					array_push( $dbs, $instancias[$i] );
					break;
				}
			}
		}else{
			$dbs = $instancias;
		}
		
		$zipfile = new zipfile();
		
		foreach ($dbs as $db) {
			$fname = backup_tables($db["DB_HOST"],$db["DB_USER"],$db["DB_PASSWORD"],$db["DB_NAME"]);
			$zipfile->add_file(implode("",file($fname)), $db["DB_NAME"]. ".sql");			
		}

		$handle = fopen('../../static_content/db_backups/'. time() .'.dbs.zip','w+');
	  	fwrite($handle, $zipfile->file());
	  	fclose($handle);
		
		header("Content-type: application/octet-stream");
		header("Content-disposition: attachment; filename=db_backup.zip");
		echo $zipfile->file();
		die();
		
	}
	
	function formatfilesize( $data ) {
	        // bytes
	        if( $data < 1024 ) {
	            return $data . " bytes";
	        }

	        // kilobytes
	        else if( $data < 1024000 ) {
	            return round( ( $data / 1024 ), 1 ) . "k";
	        }

	        // megabytes
	        else {
	            return round( ( $data / 1024000 ), 1 ) . " MB";
	        }
	}

	
	
	
	

$db_info = array();
$err_log = "";

foreach ($instancias as $db) {

	try{
		$link = @mysql_connect($db["DB_HOST"],$db["DB_USER"],$db["DB_PASSWORD"], $db["DB_NAME"]);	

	}catch(Exception $e){
		Logger::log($e);
		continue;
	}
	

	if(!$link) {
		Logger::log("Imposible conectarme a bd");
		$err_log .= "<div>Imposible conectarse al servidor de base de datos.</div>";
		continue;	
	}
	
	
	$success = mysql_select_db( $db["DB_NAME"] );
	
	if(!$success){
		$err_log .= "<div>Imposible conectarse a la BD {$db["DB_NAME"]}</div>";
		continue;
	}

	
	$result = mysql_query( "SHOW TABLE STATUS" ) ;

    $dbsize = 0;

    while( $row = mysql_fetch_array( $result ) ) 
        $dbsize += $row[ "Data_length" ] + $row[ "Index_length" ];

	mysql_close($link);
	
	$db["total_size"] = formatfilesize($dbsize);
	
	//ahora vamos a intentar abrirla con las credeciales de POS_CORE
	//para saber si puedo hacer cosas
	$link = @mysql_connect( $db["DB_HOST"], POS_CORE_DB_USER, POS_CORE_DB_PASSWORD, $db["DB_NAME"]);
	
	if($link){
		$db["core_user_has_access"] = true;	
	}else{
		$db["core_user_has_access"] = false;
	}
	
	mysql_close($link);
	
	array_push( $db_info, $db );
}

	$header = array(
		"instance_id" 	=> "Instancia",
		"desc" 			=> "Descripcion",
		"core_user_has_access" => "Core user access?",
		"DB_NAME" 		=> "Base de datos",
		"total_size" 	=> "Tama&ntilde;o" );



	echo "<div>{$err_log}</div>";

	$t = new Tabla($header, $db_info);
	$t->render();

?>





<h2>Descargar bases de datos</h2>

<form >
	<select name='db'>
		<?php
		echo "<option value='all_of_them' >Todas (". sizeof($instancias) .")</option>";
		foreach($instancias as $i){
			echo "<option value='". $i["DB_NAME"] ."'>". $i["desc"] ." / ".  $i["DB_NAME"]  ."</option>";
		}
		?>
	</select>
	<input type="hidden" name="action" value="lista">
	<input type="hidden" name="BACKUP" value="1">
	<input type="submit" value="Descargar" method="GET">
</form>








<h2>Estructura incongruente</h2>
<br>
<?php

	function diff( $a , $b )
	{
		$global_diff = false;
		
		$diff_html = "<pre style='font-size:10px; overflow: scroll; height: 500px'><table border=1 width=600>";
		
		$a_lines = explode("\n", $a);
		$b_lines = explode("\n", $b);

		
		$max_lines = sizeof($a_lines) > sizeof($b_lines) ? sizeof($a_lines) : sizeof($b_lines);
		
		
		for($i = 0; $i < $max_lines; $i++)
		{
			
			
			$is_diff = $a_lines[ $i ] != $b_lines[ $i ];
			$is_really_diff = false;
			$is_diff_style = "style='background-color: #bb0000; color: white;'";
			
			if($is_diff)
			{
				//es diferente... en que caracteres?
				$is_really_diff = true;

				//si la diferencia es en el numero de auto_increment
				if((strpos($a_lines[ $i ], ") ENGINE=") !== false) && (strpos($b_lines[ $i ], ") ENGINE=") !== false))
				{
					$is_diff_style = "style='background-color: orange; color: white;'";		
					$is_really_diff = false;
				}
				
				//si la diferencia es en un comentario
				if(($a_comment_at = strpos($a_lines[ $i ], "COMMENT")) !== false)
				{
					if(($b_comment_at = strpos($a_lines[ $i ], "COMMENT")) !== false)
					
					if(substr( $a_lines[ $i ], 0, $a_comment_at ) == substr( $b_lines[ $i ], 0, $b_comment_at ))
					{
						$is_diff_style = "style='background-color: orange; color: black;'";								
					}
					
					$is_really_diff = false;
				}
				
			}

			if($is_really_diff)
				$global_diff = true;


			$diff_html .= "<tr ". ($is_diff ? $is_diff_style : '') .">";
			$diff_html .= "<td width=300 style='width:300px; overflow:hidden;'>" . $a_lines[ $i ] . "</td>";	
			$diff_html .= "<td width=300 style='width:300px; overflow:hidden;'>" . $b_lines[ $i ] . "</td>";
			$diff_html .= "</tr>";

			
		
		}
	
		$diff_html .=		"</table></pre>";
		
		return array( $global_diff, $diff_html);
	}
	
	
	
	$instancias_schema = array();
	
	$schema_index = -1;
	?><p>Las comparaciones se haran asumiendo que la base de datos de la instancia 1 esta correcta. Las diferencias que radican en comentarios y en auto_increment se colorearan en naranaja. Todas las demas diferencias se colorearan en rojo.</p><?php
	foreach($instancias as $i){

		$schema_index ++;
		$instancias_schema[$schema_index] = backup_tables($i["DB_HOST"], $i["DB_USER"], $i["DB_PASSWORD"], $i["DB_NAME"], '*', false, true);

		if($schema_index == 0) continue;
		
		$diff = diff($instancias_schema[0], $instancias_schema[$schema_index], true);
		
		if($diff[0] === true )
			echo "<input type='button' value='{$i["DB_NAME"]} Mostrar diferencia' onClick='showdiff(". $schema_index .")'>";
		
		echo "<div  id='html_diff_". $schema_index ."' style='display:none'>". $diff[1] ."</div>";
	}
	
	
	

?>

<script type="text/javascript" charset="utf-8">

	function showdiff( schema_index )
	{
		if(jQuery("#html_diff_"+ schema_index +":visible").length == 1)
			jQuery("#html_diff_"+ schema_index ).hide();
		else
			jQuery("#html_diff_"+ schema_index ).show();
	}
</script>

