<?php

date_default_timezone_set ( "America/Mexico_City" );

require_once("../../../server/bootstrap.php");
if(!defined("BYPASS_INSTANCE_CHECK")){
	define("BYPASS_INSTANCE_CHECK", true);}

//echo "Cadena de prueba";
	//ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . "C:\wamp\www\pos-local\branches\dev-main\server");
//require_once("bootstrap.php");



class InstanciasControllerTest extends PHPUnit_Framework_TestCase {
	//50b3e297
	private static $instance_id = 80;
                    
	public function testNuevaInstancia( ){
		
		if(!is_null(self::$instance_id)) return;
		$id = InstanciasController::Nueva(null, "instacia para unit testing" );
		
		$this->assertInternalType('int', $id);
		
		self::$instance_id = $id;	
	}

	public function testBuscarInstancia( )
                {
		//debe existir la instancia cuyo id es $instance_id
		$this->assertFalse( is_null($i= InstanciasController::BuscarPorId( self::$instance_id ) ) );
                
		$this->assertFalse( is_null( InstanciasController::BuscarPorToken( $i["instance_token"] ) ) );		
		
	}	
                public function testRespaldarBD()
                {
                                     
                    $this->assertFalse(!is_null(InstanciasController::Respaldar_Instancias('['.self::$instance_id.']')));//self::$instance_id)));
                }
                public function testRestarurarBD()
                {
                    $this->assertFalse(!is_null(InstanciasController::Restaurar_Instancias('['.self::$instance_id.']')));//self::$instance_id)));
                }
                public function testComprobarPermisos()
                {
                    $CarpetaRespaldos="../../../static_content/db_backups/";
                    $this->assertEquals(0777,octdec(substr(decoct(fileperms($CarpetaRespaldos)),2)));//Comprueba los permisos de la carpeta
                }
                
                public function ComprobarArchivos()
                {
                        $nArchivos=0;$fecha=getdate();
                        $CarpetaRespaldos="../../../static_content/db_backups/";
                        $directorio=dir($CarpetaRespaldos);
                        while ($archivo = $directorio->read())
                        {
                                if(strlen($archivo)==30)//Cada nombre de archivo debe medir 30 caracteres
                                {
                                        $nArchivos++;
                                        if((($fecha['mday']-7)>substr($archivo, 16, 2))||(substr($archivo, 19, 2)<($fecha['mon'])))//Comprueba que la fecha sea mayor que la fecha actual menos 7
                                        {
                                            //Si el archivo es más viejo de lo esperado, lo borra
                                            echo "Borrando archivo: ".$archivo;
                                            $this->assertTrue(unlink($CarpetaRespaldos.$archivo));//Comprueba que se borró correctamente el archivo viejo
                                        }
                                }
                        }
                        $directorio->close();
                        $this->assertEquals(7,$nArchivos);//Comprueba que existen al menos 7 respaldos
                }
}
