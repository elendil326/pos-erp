<?php


class PosException extends Exception{
	
	protected   $text_for_frontend;
	protected 	$text_for_backend;
}



class ApiException extends PosException{
    protected $arrayMsg;
    protected $wrappedException;
    
    function __construct(array $arrayMsg, Exception $e = NULL) 
    {
        $this->wrappedException = $e;
        $this->arrayMsg = $arrayMsg;
    }
    
    public function getArrayMessage()
    {
        return $this->arrayMsg;
    }
    
    public function getWrappedException()
    {
        return $this->wrappedException;
    }
    
}

class InvalidDatabaseOperationException extends PosException{
	private $sql;
}

class BusinessLogicException extends PosException{
	
}

class AccessDeniedException extends PosException{
	private $level_needed;
}


class InvalidDataException extends PosException{
	private $field;
}