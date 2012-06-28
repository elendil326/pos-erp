<?php

class Logger
{

    private static $db_querys = 0;

    public static final function read($which = "access", $lines = 100){

    	switch($which){
    		case "access":
    		    $file = POS_CONFIG_LOG_ACCESS_FILE;
    		break;
    		
    		case "error":
    		    $file = POS_CONFIG_LOG_ERROR_FILE;
    		break;
    		
    		default:
    		return;
    		
    		
    	}
        if(!file_exists($file)){
            die("POS: Unable to open logfile:" .$file );
        }


    $header = null;
    global $error_string;
    
    // Number of lines read per time
    $bufferlength = 1024;
    $aliq = "";
    $line_arr = array();
    $tmp = array();
    $tmp2 = array();
    
    if (!($handle = fopen($file , "r"))) {
        echo("Could not fopen $file");
    }

    if (!$handle) {
        echo("Bad file handle");
        return 0;
    }

    // Get size of file
    fseek($handle, 0, SEEK_END);
    $filesize = ftell($handle);

    $position= - min($bufferlength,$filesize);

    while ($lines > 0) {
        if (fseek($handle, $position, SEEK_END)) {
            echo("Could not fseek");
            return 0;
        }
        
        unset($buffer);
        $buffer = "";
        // Read some data starting fromt he end of the file
        if (!($buffer = fread($handle, $bufferlength))) {
            echo("Could not fread");
            return 0;
        }
        
        // Split by line
        $cnt = (count($tmp) - 1);
        for ($i = 0; $i < count($tmp); $i++ ) {
            unset($tmp[0]);
        }
        unset($tmp);
        $tmp = explode("\n", $buffer);
        
        // Handle case of partial previous line read
        if ($aliq != "") {
            $tmp[count($tmp) - 1] .= $aliq;
        }

        unset($aliq);
        // Take out the first line which may be partial
        $aliq = array_shift($tmp);
        $read = count($tmp);
        
        // Read too much (exceeded indicated lines to read)
        if ($read >= $lines) {
            // Slice off the lines we need and merge with current results
            unset($tmp2);
            $tmp2 = array_slice($tmp, $read - $lines);
            $line_arr = array_merge($tmp2, $line_arr);
            
            // Discard the header line if it is there
            if ($header &&
                (count($line_arr) <= $lines)) {
                array_shift($line_arr);
            }

            // Break the loop
            $lines = 0;
        }
        // Reached start of file
        elseif (-$position >= $filesize) {
            // Get back $aliq which contains the very first line of the file
            unset($tmp2);
            $tmp2[0] = $aliq;
            
            $line_arr = array_merge($tmp2, $tmp, $line_arr);
            
            // Discard the header line if it is there
            if ($header &&
                (count($line_arr) <= $lines)) {
                array_shift($line_arr);
            }

            // Break the loop
            $lines = 0;
        }
        // Continue reading
        else {
            // Add the freshly grabbed lines on top of the others
            $line_arr = array_merge($tmp, $line_arr);
            $lines -= $read;

            // No longer a full buffer's worth of data to read
            if ($position - $bufferlength < -$filesize) {
                $bufferlength = $filesize + $position;
                $position = -$filesize;                    
            }
            // Still >= $bufferlength worth of data to read
            else {
                $position -= $bufferlength;
            }
        }
    }
    
    fclose($handle);

    return $line_arr;
    
  }
  
  
  
  public static final function logSQL( $sql ){

    if(POS_CONFIG_LOG_DB_QUERYS){

        

		self::$db_querys ++;
		

        //$arr = explode("\n", $sql); foreach ($arr as $l) { self::log( "SQL(" . self::$db_querys . "): " . $l ); }

        $sql = str_replace("\n", " ", $sql); self::log( "SQL(" . self::$db_querys . "): " . $sql  );
    }
  }






  public static final function error ( $msg ){
    
    $arr = explode("\n", $msg);
    foreach ($arr as $l) {
        self::log(  "ERROR: " . $l, true );    
    }
    
  }





  public static final function warn ($msg ){
    self::log(  "WARN: " . $msg );
  }


  public static final function debug ($msg ){
    self::log(  "------->DEBUG: " . $msg );
  }

  public static final function testerLog ($msg ){
    self::log(  "phpunit | " . $msg );
  }

  public static final function log( $msg, $toError = false ){
	

	
        if(!POS_CONFIG_LOG_TO_FILE)
            return;
        
        $msg = str_replace("\n", " ", $msg);
		if($msg instanceof Exception ){
			return self::error($msg);
		}

		if($toError && file_exists(POS_CONFIG_LOG_ERROR_FILE) && is_writable(POS_CONFIG_LOG_ERROR_FILE)){
        	$err_log = fopen( POS_CONFIG_LOG_ERROR_FILE, "a" );
        }else{
			$err_log = null;
		}


        if(!file_exists(POS_CONFIG_LOG_ACCESS_FILE)){
			return;
        }

        if(!is_writable(POS_CONFIG_LOG_ACCESS_FILE)){
			return;
        }


        $log = fopen( POS_CONFIG_LOG_ACCESS_FILE, "a" );

        $out = date("g:i:sa j M");

		if(isset($_SERVER["REMOTE_ADDR"])){
        	$out .= " | " . $_SERVER["REMOTE_ADDR"];

		}else{
	        $out .= " | CLI"  ;

		}

		if(defined("IID"))
		{
			$out .= " | IID = " . IID;
		}

		/*
		if(class_exists("SesionController") ){
			$a = SesionController::Actual();
			if(!is_null( $a["id_usuario"] )){
				$out .= " | UID = " . $a["id_usuario"];				
			}

		}
        */
		


    $track = "";
    if(POS_CONFIG_LOG_TRACKBACK){
		$d = debug_backtrace();
		
		$track = " | TRACK : ";
		
		for ($i= 1; $i < sizeof($d) -1 ; $i++) { 
			//        $track .= isset($d[$i]["function"]) ? "->" . $d[$i]["function"] : "*" ;
			$track .= isset($d[$i]["file"]) ? substr( strrchr( $d[$i]["file"], "\\" ), 1 )  : "*"; 
            $track .= isset($d[$i]["file"]) ? substr( strrchr( $d[$i]["file"], "/" ), 1 )  : "*"; 
			$track .= isset($d[$i]["line"]) ? ":" .  $d[$i]["line"] ." <-"  : "* " ;
		}
		
		
    }


	fwrite($log, $out. " | " . $msg . "". $track ."\n");

	fclose($log);

	if($err_log){
		fwrite($err_log, $out. " | " . $msg . "\n");
		fclose($err_log);
	}
	

}
  
  
  
  
  
}





define("LFP",'LOG_ACCESS_FILE');

function LogTrace($Argument, $lfn = LFP, $itw = '  ')
{
    Logger::log("=====\r", 3, $lfn); 
    Logger::log("[BEGIN BACKTRACE]\r", 3, $lfn); 
    $it = '';
    $Ts = array_reverse(debug_backtrace());
    foreach($Ts as $T)
       {  
        if($T['function'] != 'include' && $T['function'] != 'require' && $T['function'] != 'include_once' && $T['function'] != 'require_once')
        {
            $ft = $it . '<'. basename($T['file']) . '> on line ' . $T['line'];  
            if($T['function'] != 'LogTrace')
            {
                if(isset($T['class']))
                    $ft .= ' in method ' . $T['class'] . $T['type'];
                else 
                    $ft .= ' in function ';
                $ft .= $Trace['function'] . '(';
            }
            else
                $ft .= '(';
            if(isset($T['args'][0]))
            {
                if($T['function'] != 'LogTrace')
                {
                    $ct = '';
                    foreach($T['args'] as $A)
                    {
                        $ft .= $ct . LogVar($A, '', $it, $itw, 0);
                        $ct = $it . $itw . ',';
                    }
                }
                else
                    $ft .= LogVar($T['args'][0], '', $it, $itw, 0);
            }
            $ft .= $it . ")\r";
            Logger::log($ft, 3, $lfn); 
            $it .= $itw;
        }            
    }
    Logger::log("[END BACKTRACE]\r", 3, $lfn);
}

function LogVar(&$Var, $vn, $pit, $itw, $nlvl, $m = '')
{
    if($nlvl>=16) return;
    if($nlvl==0){$tv=serialize($Var);$tv=unserialize($tv);}
    else $tv=&$Var; 
    $it=$pit.$itw;
    for($i=0; $i<$nlvl;$i++) $it.='.'.$itw;
    $o='';$nl="\n";
    if(is_array($tv))
    {
        if(strlen($vn)>0) $o.=$it.$m.'<array> $'.$vn.' = (';
        else $o.="\r".$it.$m.'<array> = (';
        $o.= $nl;$AK=array_keys($tv);
        foreach($AK as $AN) {$AV=&$tv[$AN];$o.=LogVar($AV,$AN,$pit,$itw,$nlvl+1);}
        $o.=$it.')'.$nl;
    }
    else if(is_string($tv))
    {
        if(strlen($vn)>0)$o.=$it.$m.'<string> $'.$vn.' = ';
        else $o.=' '.$m.'<string> = ';
        if($tv===null) $o.='NULL';
        else $o.='"'.$tv.'"';
        $o.=$nl;
    }
    else if(is_bool($tv))
    {
        if(strlen($vn) > 0) $o.=$it.$m.'<boolean> $'.$vn.' = ';
        else $o.=' '.$m.'<boolean> = ';
        if($tv===true) $o.='TRUE';
        else $o.='FALSE';
        $o.=$nl;
    }
    else if(is_object($tv))
    {
        if(strlen($vn)>0)
        {
            $o.=$pit.$itw;
            for($i=0;$i<$nlvl;$i++) $o.='.'.$itw;
            $o.=$m.'<'.get_class($tv).'::$'.$vn.'> = {'.$nl;
        }
        else $o.=' '.$m.'<'.get_class($tv).'::> = {'.$nl;
        $R=new ReflectionClass($tv);
        $o.=$it.'.'.$itw.'Class methods {'.$nl;
        $CM=$R->getMethods();
        foreach($CM as $MN => $MV)
        {
            $o.=$it.'.'.$itw.'.'.$itw.implode(' ',Reflection::getModifierNames($MV->getModifiers())).' '.$MV->getName().'(';
            $MP=$MV->getParameters(); $ct='';
            foreach($MP as $MPN => $MPV)
            {
                $o.=$ct; $o.=$MPV->isOptional()?'[':'';
                if($MPV->isArray()) $o.='<array> ';
                else if($MPV->getClass()!==null) $o.='<'.$MPV->getClass()->getName().'::> ';
                $o.=$MPV->isPassedByReference()?'&':''; $o.='$'.$MPV->getName();
                if($MPV->isDefaultValueAvailable())
                 {
                    if($MPV->getDefaultValue()===null) $o.=' = NULL';
                    else if($MPV->getDefaultValue()===true) $o.=' = TRUE';
                    else if($MPV->getDefaultValue()===false) $o.=' = FALSE';    
                    else $o.=' = '.$MPV->getDefaultValue();    
                }
                $o.=$MPV->isOptional()?']':''; $ct=', ';
            }
            $o.=')'.$nl;
        }
        $o.=$it.'.'.$itw.'}'.$nl; $o.=$it.'.'.$itw.'Class properties {'.$nl;
        $CV=$R->getProperties();
        foreach($CV as $CN => $CV)
        {
            $M=implode(' ',Reflection::getModifierNames($CV->getModifiers())).' ';
            $CV->setAccessible(true); 
            $o.=LogVar($CV->getValue($tv),$CV->getName(),$pit,$itw,$nlvl+2,$M);
        }
        $o.=$it.'.'.$itw.'}'.$nl; $o.=$it.'.'.$itw.'Object variables {'.$nl;
         $OVs=get_object_vars($tv);    
        foreach($OVs as $ON => $OV) $o.=LogVar($OV,$ON,$pit,$itw,$nlvl+2);
        $o.=$it.'.'.$itw.'}'.$nl; $o.=$pit.$itw;
        for($i=0;$i<$nlvl;$i++)    $o.='.'.$itw;
        $o.='}'.$nl;
    }
    else
    {
        if(strlen($vn)>0) $o.=$it.$m.'<'.gettype($tv).'> $'.$vn.' = '.$tv;
        else $o.=' '.$m.'<'.gettype($tv).'> = '.$tv;
        $o.=$nl;
    }          
    return $o;    
}
