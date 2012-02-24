<?php

date_default_timezone_set ( "America/Mexico_City" );

if(!defined("BYPASS_INSTANCE_CHECK"))
	define("BYPASS_INSTANCE_CHECK", false);

$_GET["_instance_"] = 71;

require_once("../../server/bootstrap.php");





class ProductosControllerTest extends PHPUnit_Framework_TestCase {



	protected function setUp(){
		Logger::log("-----------------------------");
		$r = SesionController::Iniciar(123, 1, true);
	}

	public function RandomString($length=10,$uc=FALSE,$n=FALSE,$sc=FALSE)
	{
	    $source = 'abcdefghijklmnopqrstuvwxyz';
		if($uc==1) $source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    if($n==1) $source .= '1234567890';
	    if($sc==1) $source .= '|@#~$%()=^*+[]{}-_';

	    if($length>0){
	        $rstr = "";
	        $source = str_split($source,1);

	        for($i=1; $i<=$length; $i++){
	            mt_srand((double)microtime() * 1000000);
	            $num = mt_rand(1,count($source));
	            $rstr .= $source[$num-1];
	        }
	    }
	    return $rstr;
	}
/*	EN EL PROCESAMIENTO DEL Productos.controller.php SE REQUIRE QUE SE MANDE UN JSON DE CLASIFICACIONES, MISMO QUE NO ESTÁ EN LOS PARAMS DE LA INTERFAZ NI EN EL API*/

	public function testNuevoProducto(){
			$codigo_p = self::RandomString(5,FALSE,FALSE,FALSE);
			$nombre_p = self::RandomString(15,FALSE,FALSE,FALSE); 
		
			$p = ProductosController::Nuevo($activo= true, 
											$codigo_producto = $codigo_p, 
											$compra_en_mostrador = true, 
											$costo_estandar=10, 
											$id_unidad_compra = 1, //----------------------------NO esta en la BD
											$metodo_costeo="costo", //---------------------------el api dice 'costo estandar' y en el codigo es costo al igual q en la BD
											$nombre_producto = $nombre_p, 
											$codigo_de_barras = null, 
											$control_de_existencia = null, 
											$descripcion_producto = null, 
											$foto_del_producto = null, 
											$garantia = "AAA-", 
											$id_categoria = 100, 
											$id_empresas = null, 
											$id_unidad = null, 
											$impuestos = null, 
											$precio_de_venta = 12
											);
			$this->assertInternalType("int" , $p["id_producto"],"---- 'testNuevoProducto' 'id_producto' NO ES UN ENTERO");
	}

	public function testNuevoProductoConMismoNombre(){
			//se crea un nuevo producto
			$codigo_p = self::RandomString(5,FALSE,FALSE,FALSE);
			$nombre_p = self::RandomString(15,FALSE,FALSE,FALSE); 

        	$p = ProductosController::Nuevo($activo= true, 
											$codigo_producto = $codigo_p, 
											$compra_en_mostrador = true, 
											$costo_estandar=10, 
											$id_unidad_compra = 1, //----------------------------NO esta en la BD esta en la BD
											$metodo_costeo="costo", //---------------------------el api dice 'costo estandar' y en el codigo es costo al igual q en la BD
											$nombre_producto = $nombre_p, 
											$codigo_de_barras = null, 
											$control_de_existencia = null, 
											$descripcion_producto = null, 
											$foto_del_producto = null, 
											$garantia = "AAA-", 
											$id_categoria = 100, 
											$id_empresas = null, 
											$id_unidad = null, 
											$impuestos = null, 
											$precio_de_venta = 12
											);
			$this->assertInternalType("int" , $p["id_producto"],"---- 'testNuevoProducto' 'id_producto' NO ES UN ENTERO");
			//se trata de insertar otro producto con el mismo nombre y codigo
			$p2 = ProductosController::Nuevo($activo= true, 
											$codigo_producto = $codigo_p, 
											$compra_en_mostrador = true, 
											$costo_estandar=10, 
											$id_unidad_compra = 1, //----------------------------NO esta en la BD esta en la BD
											$metodo_costeo="costo", //---------------------------el api dice 'costo estandar' y en el codigo es costo al igual q en la BD
											$nombre_producto = $nombre_p, 
											$codigo_de_barras = null, 
											$control_de_existencia = null, 
											$descripcion_producto = null, 
											$foto_del_producto = null, 
											$garantia = "AAA-", 
											$id_categoria = 100, 
											$id_empresas = null, 
											$id_unidad = null, 
											$impuestos = null, 
											$precio_de_venta = 12);
			$this->assertLessThanOrEqual(0, $p2['id_producto'],"---- 'testNuevoProductoConMismoNombre' NO SE DEBERIA DE PERMITIR DUPLICAR NOMBRES, NOMBRE DUPLICADO: ".$nombre);
	}

	public function testEditarProducto(){
			//se crea un nuevo producto
			$codigo_p = self::RandomString(5,FALSE,FALSE,FALSE);
			$nombre_p = self::RandomString(15,FALSE,FALSE,FALSE); 

        	$p = ProductosController::Nuevo($activo= true, 
											$codigo_producto = $codigo_p, 
											$compra_en_mostrador = true, 
											$costo_estandar=10, 
											$id_unidad_compra = 1, //----------------------------NO esta en la BD esta en la BD
											$metodo_costeo="costo", //---------------------------el api dice 'costo estandar' y en el codigo es costo al igual q en la BD
											$nombre_producto = $nombre_p, 
											$codigo_de_barras = null, 
											$control_de_existencia = null, 
											$descripcion_producto = null, 
											$foto_del_producto = null, 
											$garantia = "AAA-", 
											$id_categoria = 100, 
											$id_empresas = null, 
											$id_unidad = null, 
											$impuestos = null, 
											$precio_de_venta = 12
											);
			$this->assertInternalType("int" , $p["id_producto"],"---- 'testEditarProducto' 'id_producto' NO ES UN ENTERO");

			//se edita el cliente recien ingresado
			ProductosController::Editar($p['id_producto'],
										$clasificaciones = null, 
										$codigo_de_barras = null, 
										$codigo_producto = "nuevo-c".time(),//se cambia
										$compra_en_mostrador = null, 
										$contro_de_existencia= null,
										$costo_estandar = 12, //se cambia
										$costo_extra_almacen = null, 
										$descripcion_producto = null, 
										$empresas = null, 
										$foto_del_producto =  null , 
										$garantia = null, 
										$id_unidad = null, 
										$impuestos = null, 
										$metodo_costeo = null, 
										$nombre_producto = $nombre_p."-2", //cambia
										$peso_producto = null, 
										$precio = null
										);			
		}
		*/
		/*EN EL DAO EL METODO DE BUSQUEDA NO ACEPTA CRITERIOS COMO ID_SUC O ID_PROD*/
	
		public function testBuscarProductosPorID_Sucursal(){
			$res = ProductosController::Buscar($query =null, $id_producto = null, $id_sucursal = 1 );
			$this->assertInternalType("int" , $res["numero_de_resultados"],"---- 'testBuscarProductosPorID_Sucursal' 'numero_de_resultados' NO ES UN ENTERO");
			foreach($res["resultados"] as $row){
				var_dump($row);
				//if($row->getIdSucursal() != '1'|| is_null($row->getIdSucursal()))
					//$this->assertEquals($row->getIdSucursal(),1,"---- 'testBuscarClientesPorID_Sucursal' LOS IDS NO COINCIDEN SE ENVIÓ EL id_sucursal =1 Y LA CONSULTA DEVOLVIÓ id_sucursal = ".$row->getIdSucursal()." PARA id_usuario ".$row->getIdUsuario());
			}		
		}

		public function testBuscarProductosPorID_Producto(){
			$res = ProductosController::Buscar($query =null, $id_producto = 1, $id_sucursal = null );
			$this->assertInternalType("int" , $res["numero_de_resultados"],"---- 'testBuscarProductosPorID_Producto' 'numero_de_resultados' NO ES UN ENTERO");
			foreach($res["resultados"] as $row){
				var_dump($row);
				//if($row->getIdSucursal() != '1'|| is_null($row->getIdSucursal()))
					//$this->assertEquals($row->getIdSucursal(),1,"---- 'testBuscarClientesPorID_Sucursal' LOS IDS NO COINCIDEN SE ENVIÓ EL id_sucursal =1 Y LA CONSULTA DEVOLVIÓ id_sucursal = ".$row->getIdSucursal()." PARA id_usuario ".$row->getIdUsuario());
			}		
		}

		public function testBuscarProductosPorQuery(){
			//se crea un nuevo cliente que es el que debe de ser encontrado en el query
			$nombre = self::RandomString(15,FALSE,FALSE,FALSE)." - ". time();
			$codigo = self::RandomString(5,FALSE,FALSE,FALSE);
        	$nuevo_prod = ProductosController::Nuevo($activo = true,$codigo_producto = $codigo,$compra_mostrador = true,$costo_estandar = "costo",$id_unidad_compra=1,$metodo_costeo ="costo",$nombre_producto = $nombre);

			$res = ProductosController::Buscar($query = $nombre,$id_producto = null,$id_sucursal = null);//se busca el prod recien insertado
			$this->assertInternalType("int" , $res["numero_de_resultados"],"---- 'testBuscarProductosPorQuery' 'numero_de_resultados' NO ES UN ENTERO");	

			$this->assertGreaterThanOrEqual(1, $res['numero_de_resultados'],"---- 'testBuscarProductosPorQuery' SE DEBIÓ DE ENCONTRAR ALMENOS 1 RESULTADO");		
		}

		public function testDesactivarProducto(){
			//se crea un nuevo cliente que es el que debe de ser encontrado en el query
			$nombre = self::RandomString(15,FALSE,FALSE,FALSE)." - ". time();
			$codigo = self::RandomString(5,FALSE,FALSE,FALSE);
        	$nuevo_prod = ProductosController::Nuevo($activo = true,$codigo_producto = $codigo,$compra_mostrador = true,$costo_estandar = "costo",$id_unidad_compra=1,$metodo_costeo ="costo",$nombre_producto = $nombre);
			$this->assertInternalType("int" , $nuevo_prod["id_producto"],"---- 'testDesactivarProducto' 'id_producto' NO ES UN ENTERO");
			
			ProductosController::Desactivar($nuevo_prod["id_producto"]);
			//se busca el prod recien insertado para ver si esta activo = 0
			$res = ProductosController::Buscar($query = null,$id_producto = $nuevo_prod["id_producto"],$id_sucursal = null);//-------- BUSCAR SIEMPRE MANEJA EN EL WHERE ACTIVO = 1 ENTONCES numero_resultados siempre será = 0 por lo tanto aqui va a tronar
			$this->assertInternalType("int" , $res["numero_de_resultados"],"---- 'testBuscarProductosPorQuery' 'numero_de_resultados' NO ES UN ENTERO");
			$this->assertGreaterThanOrEqual(1, $res['numero_de_resultados'],"---- 'testBuscarProductosPorQuery' SE DEBIÓ DE ENCONTRAR ALMENOS 1 RESULTADO");
			if($res['numero_de_resultados'] > 0)
				$this->assertEquals(0,$res["resultados"][0]->getActivo(),"---- 'testDesactivarProducto' EL PRODUCTO NO SE DESACTIVÓ  id_producto= ". $nuevo_prod["id_producto"]);		
		}

		//NUEVA CATEGORIA -> clasificacion_producto
				
}
