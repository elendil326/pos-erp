<?php


class ClientController {
	
	/*
	 * Client wants to know if there is a new client version.
	 * 
	 * */
	public static function checkClientCurrentVersion( $version_to_compare = null )
	{

		if(is_file( "../pos_client/VERSION" )){
			
			$contents = file_get_contents("../pos_client/VERSION");
			$ver_num = explode("\n", $contents);
			
			//la version esta en la primer linea
			$ver_num = $ver_num[0];
			
			if( $ver_num != $version_to_compare )
			{
				return "NO_NEW_VERSION";
			}
			
			//si hay una version nueva !
			return "PLEASE_UPGRADE_YOURSELF";
			
		}else{
			Logger::log("NO ENCONTRE EL ARCHIVO DE VERSION DEL CLIENTE !");
			return "NOT_FOUND";
		}

	}
	
	
	
}


if (isset($args['action'])) {

    switch ($args['action']) {

        case 1400:

			echo ClientController::checkClientCurrentVersion(  $args['my_version']  );

        break;

	}
}