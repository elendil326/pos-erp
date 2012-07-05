<?php

	define("BYPASS_INSTANCE_CHECK", true);


	require_once("../../server/bootstrap.php");
	
	
	
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

			if(($epos = strpos($lines[$a], "ERROR:")) !== false){

				$lines[$a] = substr_replace($lines[$a], "<span style='color:red; background-color:white'>ERROR:", $epos, 6 ) . "</span>";
			}

			echo "<div style='color: rgb(".( 255 -$octetos[1] ) .", 0, ".( 255 -$octetos[3] ) ."); background-color: rgb( " . $octetos[1] . " , " . $octetos[2] . " , " . $octetos[3] . ")'>" . $lines[$a] . "\n</div>" ;

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
