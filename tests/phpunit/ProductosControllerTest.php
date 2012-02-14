<?php

date_default_timezone_set ( "America/Mexico_City" );

if(!defined("BYPASS_INSTANCE_CHECK"))
	define("BYPASS_INSTANCE_CHECK", false);

$_GET["_instance_"] = 123;

require_once("../../server/bootstrap.php");





class ProductosControllerTest extends PHPUnit_Framework_TestCase {

	protected function setUp(){
		Logger::log("-----------------------------");
		$r = SesionController::Iniciar(123, 1, true);
	}



	public function testBuscar(){
		//insertar un nuevo producto
		
		$p = ProductosController::Buscar( "b3af409bb8423187c75e6c7f5b683908" );

		$this->assertInternalType( "int", $p["numero_de_resultados"] );
		
		if ( $p["numero_de_resultados"] == 1 ) {
			//ya existe el producto hay que eliminarlo
			//var_dump($p["resultados"][0]);

			ProductosController::Desactivar($p["resultados"][0]["id_producto"]);
		}

	}



	public function testNuevo(){
		//insertar un nuevo producto
		/*$activo, 
		$codigo_producto, 
		$compra_en_mostrador, 
		$costo_estandar, 
		$metodo_costeo, 
		$nombre_producto, 
		$clasificaciones = null, 
		$codigo_de_barras = null, 
		$control_de_existencia = null, 
		$costo_extra_almacen = null, 
		$descripcion_producto = null, 
		$foto_del_producto = null, 
		$garantia = null, 
		$id_empresas = null, 
		$id_unidad = null, 
		$impuestos = null, 
		$peso_producto = null, 
		$precio = null*/
		$p = ProductosController::Nuevo( 
			true, 
			"b3af409bb8423187c75e6c7f5b683908", 
			false, 
			"b3af409bb8423187c75e6c7f5b683908", 
			"precio",
			"b3af409bb8423187c75e6c7f5b683908",
			null, 
			null, 
			null, 
			null, 
			null, 
			null, 
			null, 
			null, 
			null, 
			null, 
			null, 
			10.10 );
	}
	
}