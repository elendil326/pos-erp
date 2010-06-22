<?php

/*

	This is the proxy betwen the application and the server side, heavy securtity should 
	be applied here, to leave the businness logic on the server folder.

*/


include_once("../server/AddAllClass.php");



if(!isset($_REQUEST['m'])){
	//failed to recieve request
	
}


//main switch
switch( $_REQUEST['m'] ){
	
	//login functions
	case 'echo' : echo "TODO BIEN !"; break;
	
	case 199 : echo "hola"; break;
	
	//pos functions
	case 200 : break;
	
	
	//admin functions
	case 300: break;
	
	default : 

}


?>