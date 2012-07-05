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
	
	
	$lines =  Logger::read("error", 1000);

	$this_ip = "127.0.0.1";

	echo "<pre style='overflow: scroll; padding: 5px; margin-left:-10px; width: 103%; background: whiteSmoke; margin-bottom:5px; font-size:8.5px;'>";

	for($a = sizeof($lines) - 1; $a >= 0 ; $a-- ){
		echo "<div style='color: black;  '>" . $lines[$a] . "\n</div>" ;
	}
	echo "</pre>";
	
	
	$page->render( );
