<?php


class ClientController {
	
	/*
	 * Client wants to know if there is a new client version.
	 * 
	 * */
	public static function chechClientCurrentVersion()
	{
		
		return "OK";

	}
	
	
	
}


if (isset($args['action'])) {

    switch ($args['action']) {

        case 1400:

			echo ClientController::chechClientCurrentVersion();

        break;

	}
}