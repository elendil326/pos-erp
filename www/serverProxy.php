<?php

/*

	This is the proxy betwen the application and the server side, heavy securtity should 
	be applied here, to leave the businness logic on the server folder.

*/








function failure () 
{
	
	die("{success: false }");
}







if(!isset($_REQUEST['m'])){
	
}

switch( $_REQUEST['m'] ){
	
	//login functions
	case 100 : echo "100";
	
	case 199 : echo "hola"; break;
	
	//pos functions
	case 200 : break;
	
	
	//admin functions
	case 300: break;
	default : failure();

}


?>