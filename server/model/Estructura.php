<?php
		/** Table Data Access Object.
                  * 
		  * Esta clase abstracta comprende metodos comunes para todas las clases DAO que mapean una tabla
		  * @author someone@caffeina.mx
		  * @access private
		  * @abstract
		  * @package docs
		  */
		abstract class DAO
		{

		protected static $isTrans = false;
		protected static $transCount = 0;
		
                protected static $redisConection = null;
                public static function predis($dbname, $host){
                    if(!is_null(self::$redisConection)){
                        return;
                    }
                    Predis\Autoloader::register();
                    self::$redisConection = new Predis\Client(array(
                        'host'     => $host, 
                        'database' => $dbname
                    ));
                }
		public static function transBegin (){
			
			self::$transCount ++;
			Logger::log("Iniciando transaccion (".self::$transCount.")");
			
			if(self::$isTrans){
				//Logger::log("Transaccion ya ha sido iniciada antes.");
				return;
			}
			
			global $conn;
			$conn->StartTrans();
			self::$isTrans = true;
			
		}

		public static function transEnd (  ){
			
			if(!self::$isTrans){
				Logger::log("Transaccion commit pero no hay transaccion activa !!.");
				return;
			}
			
			self::$transCount --;
			Logger::log("Terminando transaccion (".self::$transCount.")");
			
			if(self::$transCount > 0){
				return;
			}
			global $conn;
			$conn->CompleteTrans();
			Logger::log("Transaccion commit !!");
			self::$isTrans = false;
		}
		public static function transRollback (  ){
			if(!self::$isTrans){
				Logger::log("Transaccion rollback pero no hay transaccion activa !!.");
				return;
			}
			
			self::$transCount = 0;
			global $conn;
			$conn->FailTrans();
			Logger::log("Transaccion rollback !");
			self::$isTrans = false;
		}
		}
		/** Value Object.
		  * 
		  * Esta clase abstracta comprende metodos comunes para todas los objetos VO
		  * @author someone@caffeina.mx
		  * @access private
		  * @package docs
		  * 
		  */
		abstract class VO
		{

	        /**
	          *	Obtener una representacion en forma de arreglo.
	          *	
	          * Este metodo transforma todas las propiedades este objeto en un arreglo asociativo.
	          *	
	          * @returns Array Un arreglo asociativo que describe a este objeto.
	          **/
			function asArray(){
				return get_object_vars($this);
			}

            protected static function object_to_array($mixed) {
		    if(is_object($mixed)) $mixed = (array) $mixed;
		    if(is_array($mixed)) {
		        $new = array();
		        foreach($mixed as $key => $val) {
		            $key = preg_replace("/^\\0(.*)\\0/","",$key);
		            $new[$key] = object_to_array($val);
		        }
		    } 
		    else $new = $mixed;
		    return $new; 
		}
		}
