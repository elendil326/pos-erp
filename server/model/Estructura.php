<?php
		/** Table Data Access Object.
       *	 
		  * Esta clase abstracta comprende metodos comunes para todas las clases DAO que mapean una tabla
		  * @author Alan Gonzalez <alan@caffeina.mx> 
		  * @access private
		  * 
		  */
		abstract class DAO
		{

            protected static $isTrans = false;

            public static function transBegin (){
        		global $conn;
                $conn->StartTrans();
                self::$isTrans = true;

            }

            public static function transEnd (  ){
        		global $conn;
                $conn->CompleteTrans();
                self::$isTrans = false;
            }


            public static function transRollback (  ){
        		global $conn;
                $conn->FailTrans();
                self::$isTrans = false;
            }
		}

		/** Table Data Access Object.
		  * 
		  * Esta clase abstracta comprende metodos comunes para todas las clases DAO que mapean una vista
		  * @author Alan Gonzalez <alan@caffeina.mx> 
		  * @access private
		  * 
		  */
		abstract class VistaDAO
		{

		}
		/** Value Object.
		  * 
		  * Esta clase abstracta comprende metodos comunes para todas los objetos VO
		  * @author Alan Gonzalez <alan@caffeina.mx> 
		  * @access private
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

		}
