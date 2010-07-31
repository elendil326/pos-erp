<?


function sendLogin( $u, $p )
{
	echo $u . " " . $p;
}


switch($args['action'])
{
	 
	case '1201': 	
		sendLogin($args['u'], $args['p']);
	break;

	default:

}




