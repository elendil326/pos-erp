<?php


	define("BYPASS_INSTANCE_CHECK", true);
	
	require_once("../../server/bootstrap.php");
	
	$page = new PosComponentPage(   );
	
	if(!isset($_GET["step"])){
		$_GET["step"] = 1;
	}

	switch($_GET["step"]){
		case 1:
		case 2:
		case 3:
			$page->addComponent(new FreeHtmlComponent( "<img src='../media/step{$_GET['step']}.png'>" ));
					
		break;
		default:
	}
	
	switch($_GET["step"]){
		case 1:
			$page->addComponent(new FreeHtmlComponent( "<input type='text'>" ));

		break;
		
		case 2:
		
		break;

		case 3:
	
					
		break;
		
	}

	$page->render(   );