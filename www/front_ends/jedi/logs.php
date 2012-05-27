<?php

	define("BYPASS_INSTANCE_CHECK", true);


	require_once("../../../server/bootstrap.php");
	
	
	
	/***
	 * 
	 *  Page Rendering
	 * 
	 * 
	 * */
	$page = new JediComponentPage( );


	$page->partialRender();
	
	
	$lines =  Logger::read("access",1000);

	$this_ip = "127.0.0.1";

	echo "<pre style='overflow: scroll; padding: 5px; margin-left:-10px; width: 103%; background: whiteSmoke; margin-bottom:5px; font-size:8.5px;'>";

	for($a = sizeof($lines) - 1; $a >= 0 ; $a-- ){
		//echo "<div style='color: black;  '>" . $lines[$a] . "\n</div>" ;
				
		
	    $linea = explode(  "|", $lines[$a] );

		if( (sizeof($linea) > 1)  && filter_var( trim($linea[1]) , FILTER_VALIDATE_IP)){

			$ip = $linea[1];

			$octetos = explode(".", $ip);

			if(trim($this_ip) == trim($ip)){
				echo "<div style='color: white; background-color: rgb( " . $octetos[1] . " , " . $octetos[2] . " , " . $octetos[3] . ")'><strike>" . $lines[$a] . "</strike>\n</div>" ;					
			}else{
				echo "<div style='color: white; background-color: rgb( " . $octetos[1] . " , " . $octetos[2] . " , " . $octetos[3] . ")'>" . $lines[$a] . "\n</div>" ;					
			}

		}else{

			echo "<div>" . $lines[$a] . "\n</div>" ;		
		}
		
	}
	echo "</pre>";
	
	
	
	/*
	?><textarea rows="8" cols="40"><?php
	
		echo Logger::read();
	
	?></textarea><?php
	*/

	$page->render( );
