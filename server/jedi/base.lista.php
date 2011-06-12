<?php

	$sql = "SELECT * FROM instances;";
	$rs = $core_conn->Execute($sql);
	$instancias = $rs->GetArray();


	function backup_tables($host, $user, $pass, $name, $tables = '*')
	{

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
	    $return.="\n\n\n";
	  }
	
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

		
	}
	
	
	
	
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

