<?php

class SessionManager {
	private static $_sessionManager;
	
	private function __construct(){
		
	}


	public static function getInstance( ){
        if(is_null(self::$_sessionManager))
        {
            self::$_sessionManager = new SessionManager();
        }

        return self::$_sessionManager;
    }

    public function SetCookie($name, $value = null, $expire = null, $path = null, $domain = null, $secure = null, $httponly = null){
        @setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
    }

	public function GetCookie($name){
		if(array_key_exists($name, $_COOKIE)){
			return $_COOKIE[$name];	
		}
		
		return NULL;
	}
}
