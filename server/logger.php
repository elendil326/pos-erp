<?php


class Logger
{

	public static final function log( $msg, $level = 0 )
	{
        if(!_POS_LOG_TO_FILE)
            return;
        
        if(!file_exists(_POS_LOG_TO_FILE_FILENAME)){
            die("POS: Unable to open logfile:" ._POS_LOG_TO_FILE_FILENAME );
        }

        if(!is_writable(_POS_LOG_TO_FILE_FILENAME)){
            die("POS: Unable to write to logfile:" ._POS_LOG_TO_FILE_FILENAME );
        }


        $log = fopen( _POS_LOG_TO_FILE_FILENAME, "a" );

        

        $out = date(DATE_RFC822);

        $out .= " | " . $_SERVER["REMOTE_ADDR"];

        $out .= " | LEVEL:" . $level;


        if(isset($_SESSION['userid'])){
            $out .= " | USERID:" . $_SESSION['userid'];
        }


        if(isset($_SESSION['sucursal'])){
            $out .= " | SUC:" . $_SESSION['sucursal'];
        }

        $d = debug_backtrace();

        $out .= " | TRACE:" . $d[0]["file"].":" .$d[1]["function"] . "()";

        $out .= " | MSG:" . $msg;



        fwrite($log, $out . "\n");

        fclose($log);

	}
}
