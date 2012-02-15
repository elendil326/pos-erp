<?php


class PosException extends Exception{
	
	protected   $text_for_frontend;
	protected 	$text_for_backend;
}



class ApiException extends PosException{
	private $http_response;
}

class InvalidDatabaseException extends PosException{
	private $sql;
}

class BusinessLogicException extends PosException{
	
}

class AccessDeniedException extends PosException{
	private $level_needed;
}

/*
class InvalidArgumentException extends PosException{
	private $field;
}*/