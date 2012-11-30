<?php

date_default_timezone_set ( "America/Mexico_City" );

require_once("../../../server/bootstrap.php");
if(!defined("BYPASS_INSTANCE_CHECK")){
	define("BYPASS_INSTANCE_CHECK", true);}

	//ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . "C:\wamp\www\pos-local\branches\dev-main\server");
//require_once("bootstrap.php");

 class InstanciasControllerTest extends PHPUnit_Framework_TestCase {
	//50b3e297
	private static $instance_id=84;
                   private static $TokenInstancia;
	public function tNuevaInstancia( ){
                                      self::$instance_id=InstanciasController::Nueva(null, "Instacia para unit testing" );
                                      echo " >> Creando nueva instancia para pruebas\n";
                                      if (is_null(self::$instance_id))//Si no se creo una nueva instancia
                                      {
                                          echo "¡¡Error al crear la nueva instancia!!\n";
                                          $this->assertTrue(false);
                                      }
                                      else
                                      {
                                          echo "\tNueva instancia creada con exito, ID: " . self::$instance_id . "\n";
                                      }
	}
        
                public function testBuscarInstanciaID( )
                {
                    echo " >> Buscando instancia por su ID: ". self::$instance_id  ."\n";
		//Debe existir la instancia cuyo id es $instance_id
                    if(is_null($i=InstanciasController::BuscarPorId(self::$instance_id)))
                    {
                        echo "\tError, resultado de la búsqueda nulo\n";
                        $this->assertTrue(false);
                    }
                    else
                    {
                        self::$TokenInstancia=$i["instance_token"];
                        echo "\tNuevo token obtenido: " . self::$TokenInstancia . "\n";
                    }
                }
                
                public function testBuscarInstanciaToken()
                {
                    echo " >> Buscando instancia por su Token: " . self::$TokenInstancia . "\n";
                    if (is_null(InstanciasController::BuscarPorToken(self::$TokenInstancia)))
                    {
                        echo "\tError, no se pudo localizar la instancia buscada, o la instancia no se creó\n";
                        $this->assertTrue(false);
                    }
                    else
                    {
                        echo "\tI!nstancia encontrada!!";
                    }
                }	
                
                public function testEliminarInstancia()
                {
                    echo " >> (En construcción...) Eliminando instancia creada, ID: ". self::$instance_id . " Token: ". self::$TokenInstancia . "\n";
                    if (!is_null(InstanciasController::Eliminar(self::$TokenInstancia)))//Si no regresa null, se borró
                    {
                        echo "\tInstancia eliminada con exito\n";
                    }
                    else
                    {
                        echo "\tError al eliminar la instancia\n";
                       $this->assertTrue(false);
                    }
                }
                
                public function testRespaldarBD()
                {
                    echo " >> Intentando respaldar la base de datos\n";
                    if ((InstanciasController::Respaldar_Instancias(json_encode(array(self::$instance_id))))!=null)
                    {
                        echo "\tRespaldo exitoso\n";
                    }
                    else
                    {
                        echo "\tSe ha producido un error al realizar el respaldo\n";
                        $this->assertTrue(false);
                    }
                }
                
                public function testRestarurarBD()
                {
                    echo " >> Intentando restaurar la base de datos\n";
                    if ((!is_null(InstanciasController::Restaurar_Instancias(json_encode(array(self::$instance_id)))))!=null)
                    {
                        echo "\tRestauración exitosa\n";
                    }
                    else
                    {
                        echo "\tSe ha producido un error al realizar la restauración\n";
                        $this->assertTrue(false);
                    }
                }
                
                public function testComprobarPermisos()
                {
                    $CarpetaRespaldos=(POS_PATH_TO_SERVER_ROOT . "/../static_content/db_backups/");
                    echo " >> Comprobando permisos totales de: $CarpetaRespaldos\n";
                    if(strcmp(0777,(substr(decoct(fileperms($CarpetaRespaldos)),2))))//Comprueba los permisos de la carpeta
                    {
                        echo "\tOk: Permisos correctos: 0777\n";
                    }
                    else
                    {
                        echo " \tError, los permisos no son los correctos: " . (substr(decoct(fileperms($CarpetaRespaldos)),2)) . "\n";
                        $this->assertTrue(false);
                    }
                }
                
                public function testComprobarArchivos()
                {
                        $CarpetaRespaldos=(POS_PATH_TO_SERVER_ROOT . "/../static_content/db_backups/");
                        $nArchivos=0;
                        $directorio=dir($CarpetaRespaldos);
                        $d1=date("d",time());$d2;
                        $m1=date("m",time());$m2;
                        $a1=date("y",time());$a2;
                        echo " >> Comprobando archivos:\n";
                        while ($archivo = $directorio->read())
                        {
                            if(strlen($archivo)>2)//Cada nombre de archivo debe medir 30 caracteres
                            {
                                $d2=date("d",fileatime($CarpetaRespaldos.$archivo));
                                $m2=date("m",fileatime($CarpetaRespaldos.$archivo));
                                $a2=date("y",fileatime($CarpetaRespaldos.$archivo));
                                if((substr($archivo, 10,14)=="_pos_instance_")&&(substr($archivo, strlen($archivo)-3,3)=="sql")){echo "\tOk >> Archivo encontrado: ".$archivo." (" . date("d/m/y",fileatime($CarpetaRespaldos.$archivo)) . ")\n"; $nArchivos++;;};
                                if ($d1>($d2+7)||$m1>$m2||$a1>$a2&&((substr($archivo, 10,14)=="_pos_instance_")&&(substr($archivo, strlen($archivo)-3,3)=="sql")))
                                {
                                    echo "\tBorrando respaldo archivo Antiguo: ' " . $archivo . " ' \n";
                                    if (unlink($CarpetaRespaldos.$archivo))//Comprueba que se borró correctamente el archivo viejo
                                    {
                                        echo "\t!!Archivo borrado correctamente¡¡\n";
                                        $this->assertTrue(true);
                                    }
                                    else
                                    {
                                        echo "\t¡¡Error al borrar el archivo\n";
                                        $this->assertTrue(false);
                                    }
                                }
                            }
                        }
                        $directorio->close();
                        echo "\tComprobando si existen al menos 7 respaldos\n";
                        if ($nArchivos==7)
                        {
                            echo "\tOk: Hay al menos 7 respaldos válidos\n";
                        }
                        else
                         {
                            echo "\tError, no hay por lo menos 7 respaldos válidos\n";
                            $this->assertEquals(7,$nArchivos);//Comprueba que existen al menos 7 respaldos
                         }
                }
}
