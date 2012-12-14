<?php
//PRUEBAS UNITARIAS CONFIGURACION

date_default_timezone_set ( "America/Mexico_City" );

require_once("../../../server/bootstrap.php");
if(!defined("BYPASS_INSTANCE_CHECK")){
	define("BYPASS_INSTANCE_CHECK", true);}
          class ConfiguracionTest extends PHPUnit_Framework_TestCase {
                //Pendiente: Comrpobar que no se puedan reducir la cantidad de decimales despues de una operacion
                
                public function testLecturaDecimalesAPI()
                {//Comprueba que se pueden leer los decimales del API
                      echo " >> Comprobando lectura de decimales desde el API\n";
                }
                
                public function testCadenaDecimales()
                {//Comprueba que no se admitan caracteres de texto en la configuracion de decimales
                      echo " >> Comprobando caracteres admitidos por la configuración\n";
                }
                
                public function testNegativoDecimales()
                {//Comprueba que no se acepten decimales negativos
                      echo " >> Comprobando aceptacion de decimales negativos\n";
                }
                
                public function testMaxDecimales()
                {//Comprueba que se admita la cantidad correcta de decimales
                      echo " >> Comprobando numero maximo de decimales admitidos\n";
                }
                
                public function testAPIDecimales()
                {//Prueba el funcionamiento correcto del API de las configuraciones decimales
                      echo " >> Realizando pruebas desde el API\n";
                }
                
                public function testRestaurarConfiguracionl() 
                {//Reestablece la configuracion de decimales y lo comprueba
                      echo " >> Comprobando reestablecimiento de la configuracion\n";
                }
          }
?>
