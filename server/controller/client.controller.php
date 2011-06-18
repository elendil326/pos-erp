<?php


class ClientController {
	
	public static function clientStarted()
	{
		
		Logger::log("Client has started !");
		echo "Hola cliente ! Como estas ! Yo bien !";

	}
	
	
}


if (isset($args['action'])) {

    switch ($args['action']) {

        case 1400:

			ClientController::clientStarted();

        break;
	}
}