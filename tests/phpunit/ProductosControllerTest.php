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
											$id_unidad_compra = 1, 
											$metodo_costeo="costo", 
											$nombre_producto = $nombre_p, 
											$codigo_de_barras = null, 
											$control_de_existencia = null, 
											$descripcion_producto = null, 
											$foto_del_producto = null, 
											$garantia = null, 
											$id_categoria = null, 
											$id_empresas = null, 
											$id_unidad = null, 
											$impuestos = null, 
											$precio_de_venta = 12
											);
			$this->assertInternalType("int" , $p["id_producto"],"---- 'testNuevoProducto' 'id_producto' NO ES UN ENTERO");
	}
	
	 /**
     * @expectedException BusinessLogicException
     */
	public function testNuevoProductoConMismoNombre(){
			//se crea un nuevo producto
			$codigo_p = self::RandomString(5,FALSE,FALSE,FALSE);
			$nombre_p = self::RandomString(15,FALSE,FALSE,FALSE); 

        	$p = ProductosController::Nuevo($activo= true, 
											$codigo_producto = $codigo_p, 
											$compra_en_mostrador = true, 
											$costo_estandar=10, 
											$id_unidad_compra = 1, 
											$metodo_costeo="costo", 
											$nombre_producto = $nombre_p, 
											$codigo_de_barras = null, 
											$control_de_existencia = null, 
											$descripcion_producto = null, 
											$foto_del_producto = null, 
											$garantia = null, 
											$id_categoria = null, 
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
											$id_unidad_compra = 1,
											$metodo_costeo="costo",
											$nombre_producto = $nombre_p, 
											$codigo_de_barras = null, 
											$control_de_existencia = null, 
											$descripcion_producto = null, 
											$foto_del_producto = null, 
											$garantia = null, 
											$id_categoria = null, 
											$id_empresas = null, 
											$id_unidad = null, 
											$impuestos = null, 
											$precio_de_venta = 12);			
	}

	public function testEditarProducto(){
			//se crea un nuevo producto
			$codigo_p = self::RandomString(5,FALSE,FALSE,FALSE);
			$nombre_p = self::RandomString(15,FALSE,FALSE,FALSE); 
			
        	$p = ProductosController::Nuevo($activo= true, 
											$codigo_producto = $codigo_p, 
											$compra_en_mostrador = true, 
											$costo_estandar=10, 
											$id_unidad_compra = 1,
											$metodo_costeo="costo", 
											$nombre_producto = $nombre_p, 
											$codigo_de_barras = null, 
											$control_de_existencia = null, 
											$descripcion_producto = null, 
											$foto_del_producto = null, 
											$garantia = null, 
											$id_categoria = null, 
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
										$codigo_producto = null, 
										$compra_en_mostrador = null, 
										$control_de_existencia = null, 
										$costo_estandar = 12, //se cambia
										$costo_extra_almacen = null, 
										$descripcion_producto = null, 
										$empresas = null, 
										$foto_del_producto = null, 
										$garantia = null, 
										$id_unidad = null, 
										$impuestos = null, 
										$metodo_costeo = null, 
										$nombre_producto = $nombre_p."-E", //se cambia
										$peso_producto = null, 
										$precio = null
										);			
		}
		
		
		public function testBuscarProductosPorID_Sucursal(){
			$dir_suc = self::RandomString(25,FALSE,FALSE,FALSE);
			$suc_razon = self::RandomString(10,FALSE,FALSE,FALSE); 
			
			$sucursal = SucursalesController::Nueva($direccion = $dir_suc, $razon_social = $suc_razon);
			$this->assertInternalType("int" , $sucursal['id_sucursal'],"---- 'testBuscarProductosPorID_Sucursal' 'id_sucursal' NO ES UN ENTERO");

			$empresa_rfc = self::RandomString(13,FALSE,FALSE,FALSE);
			$empresa_razon = self::RandomString(10,FALSE,FALSE,FALSE); 
			
			$empresa = EmpresasController::Nuevo($direccion = array(array(
														"calle"                 => "Monte Balcanes",
														"numero_exterior"       => "107",
														"colonia"               => "Arboledas",
														"id_ciudad"             => 334,
												   	    "codigo_postal"         => "38060",
														"numero_interior"       => null,
														"referencia"            => "Calle cerrada",
												   	    "telefono1"             => "4616149974",
														"telefono2"             => "45*451*454"
														)),
													$id_moneda = 1,
													$razon_social = $empresa_razon,
													$rfc = $empresa_rfc,
													$cedula = null, 
													$email = null, 
													$impuestos_compra = "", 
													$impuestos_venta = null, 
													$logo = null, 
													$representante_legal = null, 
													$sucursales = array($sucursal['id_sucursal']), 
													$texto_extra = null
												);

			$this->assertInternalType("int" , $empresa['id_empresa'],"---- 'testBuscarProductosPorID_Sucursal' 'id_empresa' NO ES UN ENTERO");
			
			//se crea un nuevo producto
			$codigo_p = self::RandomString(5,FALSE,FALSE,FALSE);
			$nombre_p = self::RandomString(15,FALSE,FALSE,FALSE); 

        	$p = ProductosController::Nuevo($activo= true, 
											$codigo_producto = $codigo_p, 
											$compra_en_mostrador = true, 
											$costo_estandar=10, 
											$id_unidad_compra = 1, 
											$metodo_costeo="costo", 
											$nombre_producto = $nombre_p, 
											$codigo_de_barras = null, 
											$control_de_existencia = null, 
											$descripcion_producto = null, 
											$foto_del_producto = null, 
											$garantia = null, 
											$id_categoria = null, 
											$id_empresas = array($empresa['id_empresa']), 
											$id_unidad = null, 
											$impuestos = null, 
											$precio_de_venta = 12
											);						

			$res = ProductosController::Buscar($query =null, $id_producto = null, $id_sucursal = $sucursal['id_sucursal'] );
			$this->assertInternalType("int" , $res["numero_de_resultados"],"---- 'testBuscarProductosPorID_Sucursal' 'numero_de_resultados' NO ES UN ENTERO");
			$this->assertGreaterThan(0, $res['numero_de_resultados'] ,"---- 'testBuscarProductosPorID_Sucursal' SE DEBIÓ DE ENCONTRAR ALMENOS 1 RESULTADO CON NOMBRE PRODUCTO: ".$nombre_p);				
		}

		public function testBuscarProductosPorID_Producto(){
			//se crea un nuevo producto para buscarlo por su id
			$codigo_p = self::RandomString(5,FALSE,FALSE,FALSE);
			$nombre_p = self::RandomString(15,FALSE,FALSE,FALSE); 

        	$p = ProductosController::Nuevo($activo= true, 
											$codigo_producto = $codigo_p, 
											$compra_en_mostrador = true, 
											$costo_estandar=10, 
											$id_unidad_compra = 1, 
											$metodo_costeo="costo", 
											$nombre_producto = $nombre_p, 
											$codigo_de_barras = null, 
											$control_de_existencia = null, 
											$descripcion_producto = null, 
											$foto_del_producto = null, 
											$garantia = null, 
											$id_categoria = null, 
											$id_empresas = null, 
											$id_unidad = null, 
											$impuestos = null, 
											$precio_de_venta = 12
											);
			$this->assertInternalType("int" , $p["id_producto"],"---- 'testNuevoProducto' 'id_producto' NO ES UN ENTERO");
			
			$res = ProductosController::Buscar($query =null, $id_producto = $p['id_producto'], $id_sucursal = null );
			
			$this->assertInternalType("int" , $res["numero_de_resultados"],"---- 'testBuscarProductosPorID_Producto' 'numero_de_resultados' NO ES UN ENTERO");
			$this->assertEquals($res['numero_de_resultados'],1,"---- 'testBuscarProductosPorID_Producto' SE DEBIÓ DE ENCONTRAR SÓLO 1 RESULTADO CON id_producto = ".$p['id_producto']);
			foreach($res["resultados"] as $row){
				if($row['id_producto'] != $p['id_producto'])
					$this->assertEquals($row['id_producto'],$p['id_producto'],"---- 'testBuscarProductosPorID_Producto' LOS IDS NO COINCIDEN SE ENVIÓ EL id_producto =".$p['id_producto']." Y LA CONSULTA DEVOLVIÓ id_producto = ".$row['id_producto']);
			}		
		}

		public function testBuscarProductosPorQuery(){
			//se crea un nuevo cliente que es el que debe de ser encontrado en el query
			$nombre = self::RandomString(15,FALSE,FALSE,FALSE)." - ". time();
			$codigo = self::RandomString(5,FALSE,FALSE,FALSE);
        	$nuevo_prod = ProductosController::Nuevo($activo = true,$codigo_producto = $codigo,$compra_mostrador = true,$costo_estandar = "costo",$id_unidad_compra=1,$metodo_costeo ="costo",$nombre_producto = $nombre);

			$res = ProductosController::Buscar($query = $nombre,$id_producto = null,$id_sucursal = null);//se busca el prod recien insertado
			$this->assertInternalType("int" , $res["numero_de_resultados"],"---- 'testBuscarProductosPorQuery' 'numero_de_resultados' NO ES UN ENTERO");	

			$this->assertGreaterThan(0, $res['numero_de_resultados'] ,"---- 'testBuscarProductosPorQuery' SE DEBIÓ DE ENCONTRAR ALMENOS 1 RESULTADO CON NOMBRE: ".$nombre);		
		}

		public function testDesactivarProducto(){
			//se crea un nuevo cliente que es el que debe de ser encontrado en el query
			$nombre = self::RandomString(15,FALSE,FALSE,FALSE)." - ". time();
			$codigo = self::RandomString(5,FALSE,FALSE,FALSE);
        	$nuevo_prod = ProductosController::Nuevo($activo = true,$codigo_producto = $codigo,$compra_mostrador = true,$costo_estandar = "costo",$id_unidad_compra=1,$metodo_costeo ="costo",$nombre_producto = $nombre);
			$this->assertInternalType("int" , $nuevo_prod["id_producto"],"---- 'testDesactivarProducto' 'id_producto' NO ES UN ENTERO");
			
			ProductosController::Desactivar($nuevo_prod["id_producto"]);
			//se busca el prod recien insertado para ver si esta activo = 0
			$res = ProductoDAO::getByPK($nuevo_prod["id_producto"]);
			$this->assertEquals(0,$res->getActivo(),"---- 'testDesactivarProducto' EL PRODUCTO NO SE DESACTIVÓ  id_producto= ". $nuevo_prod["id_producto"]);		
		}

		public function testNuevaCategoria(){
			$nombre_cat = self::RandomString(15,true,FALSE,FALSE); 
			
			$c = ProductosController::NuevaCategoria($nombre = $nombre_cat, 
											$descripcion = null, 
											$id_categoria_padre = null
											);
			$this->assertInternalType("int" , $c["id_categoria"],"---- 'testNuevaCategoria' 'id_categoria' NO ES UN ENTERO");
		}

		 /**
     	* @expectedException BusinessLogicException
     	*/
		public function testNuevaCategoriaMismoNombre(){
			$nombre_cat = self::RandomString(15,FALSE,FALSE,FALSE); 			
			$c = ProductosController::NuevaCategoria($nombre = $nombre_cat, 
											$descripcion = null, 
											$id_categoria_padre = null
											);
			$this->assertInternalType("int" , $c["id_categoria"],"---- 'testNuevaCategoriaMismoNombre' 'id_categoria' NO ES UN ENTERO");
			//se intenta insertar otra cat con mismo nombre
			$cat2 = ProductosController::NuevaCategoria($nombre = $nombre_cat);				
		}	
		
		public function testEditarCategoria(){
			//se crea un nueva categoria
			$nombre_cat = self::RandomString(15,true,FALSE,FALSE); 
			$desc = self::RandomString(25,FALSE,FALSE,FALSE); 
			$c = ProductosController::NuevaCategoria($nombre = $nombre_cat, 
											$descripcion = $desc, 
											$id_categoria_padre = null
											);
			$this->assertInternalType("int" , $c["id_categoria"],"---- 'testEditarCategoria' 'id_categoria' NO ES UN ENTERO");

			//se edita la categoria recien ingresada
			ProductosController::EditarCategoria($id_categoria = $c["id_categoria"],
										$descripcion = "", //cambia a cadena vacia
										$id_categoria_padre = null, 
										$nombre = $nombre_cat."-edit"
										);			
		}
		
		public function testBuscarCategoriaPorID_Categoria(){
			//se genera una categoria para despues buscarla por su id
			$nombre_cat = self::RandomString(15,FALSE,FALSE,FALSE); 
			$desc = self::RandomString(25,FALSE,FALSE,FALSE); 
			$c = ProductosController::NuevaCategoria($nombre = $nombre_cat, 
											$descripcion = $desc, 
											$id_categoria_padre = null
											);
			$this->assertInternalType("int" , $c["id_categoria"],"---- 'testNuevaCategoria' 'id_categoria' NO ES UN ENTERO");

			$res = ProductosController::BuscarCategoria($id_categoria = $c['id_categoria'], $id_categoria_padre =null, $query = null );
			$this->assertInternalType("int" , $res["numero_de_resultados"],"---- 'testBuscarProductosPorID_Categoria' 'numero_de_resultados' NO ES UN ENTERO");
			$this->assertEquals(1, $res['numero_de_resultados'],"---- 'testBuscarProductosPorID_Categoria' SE DEBIÓ DE ENCONTRAR UNICAMENTE 1 RESULTADO CON id_categoria = ".$c['id_categoria']);
			foreach($res["resultados"] as $row){
				if($row['id_categoria'] != $c['id_categoria']|| is_null($row['id_categoria']))
					$this->assertEquals($row['id_categoria'],$c['id_categoria'],"---- 'testBuscarProductosPorID_Categoria' LOS IDS NO COINCIDEN SE ENVIÓ EL id_categoria = ".$c['id_categoria']." Y LA CONSULTA DEVOLVIÓ id_categoria = ".$row['id_categoria']);
			}		
		}		

		public function testBuscarCategoriaPorQuery(){
			//se genera una categoria para despues buscarla por su nombre
			$nombre_cat = self::RandomString(15,FALSE,FALSE,FALSE); 
			$desc = self::RandomString(25,FALSE,FALSE,FALSE); 
			$c = ProductosController::NuevaCategoria($nombre = $nombre_cat, 
											$descripcion = $desc, 
											$id_categoria_padre = null
											);
			$this->assertInternalType("int" , $c["id_categoria"],"---- 'testNuevaCategoria' 'id_categoria' NO ES UN ENTERO");

			$res = ProductosController::BuscarCategoria($id_categoria = null,$id_categoria_padre = null, $query = $nombre_cat );
			$this->assertInternalType("int" , $res["numero_de_resultados"],"---- 'testBuscarProductosPorID_Categoria' 'numero_de_resultados' NO ES UN ENTERO");
			$this->assertGreaterThanOrEqual(1, $res['numero_de_resultados'],"---- 'testBuscarProductosPorID_Categoria' SE DEBIÓ DE ENCONTRAR ALMENOS 1 RESULTADO CON nombre = ".$nombre_cat);			
				
		}

		public function testBuscarCategoriaPorID_CategoriaPadre(){
			//se genera una categoria para despues asignarla como id_padre a la otra
			$nombre_cat_padre = self::RandomString(15,FALSE,FALSE,FALSE); 
			$desc_padre = self::RandomString(25,FALSE,FALSE,FALSE); 
			$cp = ProductosController::NuevaCategoria($nombre = $nombre_cat_padre, 
											$descripcion = $desc_padre, 
											$id_categoria_padre = null
											);
			$this->assertInternalType("int" , $cp["id_categoria"],"---- 'testNuevaCategoria' 'id_categoria' NO ES UN ENTERO");
			
			$nombre_cat = self::RandomString(15,FALSE,FALSE,FALSE); 
			$desc = self::RandomString(25,FALSE,FALSE,FALSE); 
			$c = ProductosController::NuevaCategoria($nombre = $nombre_cat, 
											$descripcion = $desc, 
											$id_categoria_padre = $cp['id_categoria']
											);
			$this->assertInternalType("int" , $c["id_categoria"],"---- 'testNuevaCategoria' 'id_categoria' NO ES UN ENTERO");
			
			$res = ProductosController::BuscarCategoria($id_categoria = null, $id_categoria_padre = $cp['id_categoria'], $query = null );
			$this->assertInternalType("int" , $res["numero_de_resultados"],"---- 'testBuscarProductosPorID_CategoriaPadre' 'numero_de_resultados' NO ES UN ENTERO");
			$this->assertGreaterThanOrEqual(1, $res['numero_de_resultados'],"---- 'testBuscarProductosPorID_CategoriaPadre' SE DEBIÓ DE ENCONTRAR ALMENOS 1 RESULTADO CON id_categoria_padre = ".$cp['id_categoria']);
			
			foreach($res["resultados"] as $row){				
				if($row['id_categoria'] != $cp['id_categoria'] || is_null($row->getIdProducto()))
					$this->assertEquals($row['id_categoria'],$cp['id_categoria'],"---- 'testBuscarProductosPorID_CategoriaPadre' LOS IDS NO COINCIDEN SE ENVIÓ EL id_categoria_padre = ".$cp['id_categoria']." Y LA CONSULTA DEVOLVIÓ id_categoria_padre = ".$row['id_categoria']);
			}		
		}

		public function testDesactivarCategoria(){
			//se genera una categoria para despues darla de baja
			$nombre_cat = self::RandomString(15,FALSE,FALSE,FALSE); 
			$desc = self::RandomString(25,FALSE,FALSE,FALSE); 
			$c = ProductosController::NuevaCategoria($nombre = $nombre_cat, 
											$descripcion = $desc, 
											$id_categoria_padre = null
											);
			$this->assertInternalType("int" , $c["id_categoria"],"---- 'testNuevaCategoria' 'id_categoria' NO ES UN ENTERO");
			
			ProductosController::DesactivarCategoria($c["id_categoria"]);
			//se busca el prod recien insertado para ver si esta activo = 0
			$res = ClasificacionProductoDAO::getByPK($c['id_categoria']);
			$this->assertEquals(0,$res->getActiva(),"---- 'testDesactivarCategoria' LA CATEGORIA NO SE DESACTIVÓ  id_categoria= ". $c["id_categoria"]);		
		}

		public function testVolumenEnNuevo(){
			$codigo_p = self::RandomString(5,FALSE,FALSE,FALSE);
			$nombre_p = self::RandomString(15,FALSE,FALSE,FALSE); 
			$codigo_p2 = self::RandomString(5,FALSE,FALSE,FALSE);
			$nombre_p2 = self::RandomString(15,FALSE,FALSE,FALSE); 
			$codigo_p3 = self::RandomString(5,FALSE,FALSE,FALSE);
			$nombre_p3 = self::RandomString(15,FALSE,FALSE,FALSE); 

			$productos = array(
							array(
								"activo"				=> true,
								"codigo_producto"		=> $codigo_p,
								"compra_en_mostrador" 	=> true,
								"costo_estandar"   		=> 10,
								"id_unidad_compra"   	=> 1,
								"metodo_costeo"			=> "costo",
								"nombre_producto"		=> $nombre_p,
							),
							array(
								"activo"				=> true,
								"codigo_producto"		=> $codigo_p2,
								"compra_en_mostrador" 	=> true,
								"costo_estandar"   		=> 20,
								"id_unidad_compra"   	=> 1,
								"metodo_costeo"			=> "costo",
								"nombre_producto"		=> $nombre_p2,
							),
							array(
								"activo"				=> true,
								"codigo_producto"		=> $codigo_p3,
								"compra_en_mostrador" 	=> true,
								"costo_estandar"   		=> 30,
								"id_unidad_compra"   	=> 1,
								"metodo_costeo"			=> "costo",
								"nombre_producto"		=> $nombre_p3,
							)							
						);
			$prod_insertados = ProductosController::VolumenEnNuevo($productos);			
			$this->assertEquals(count($productos),count($prod_insertados),"---- 'testVolumenEnNuevo' SE DEBIERON DE INSERTAR ".count($productos)." PRODUCTOS, INSERTADOS: ".count($prod_insertados));		
		}			

		public function testNuevaCategoriaUdm(){
			$descripcion_catUdm = self::RandomString(15,true,FALSE,FALSE); 
			
			$c = ProductosController::NuevaCategoriaUdm($descripcion = $descripcion_catUdm, $activo = null);
			$this->assertInternalType("int" , $c["id_categoria"],"---- 'testNuevaCategoriaUdm' 'id_categoria' NO ES UN ENTERO");
		}
		
		/**
     	* @expectedException BusinessLogicException
     	*/
		public function testNuevaCategoriaUdmMismoNombre(){
			$descripcion_catUdm = self::RandomString(15,true,FALSE,FALSE); 
			
			$c = ProductosController::NuevaCategoriaUdm($descripcion = $descripcion_catUdm, $activo = null);
			$this->assertInternalType("int" , $c["id_categoria"],"---- 'testNuevaCategoriaUdm' 'id_categoria' NO ES UN ENTERO");
			//se intenta insertar otra cat con mismo nombre
			$cat2 = ProductosController::NuevaCategoriaUdm($descripcion = $descripcion_catUdm, $activo = null);				
		}

		public function testEditarCategoriaUdm(){
			//se crea un nueva categoria
			$descripcion_catUdm = self::RandomString(15,true,FALSE,FALSE); 
			$descripcion_editada = self::RandomString(15,true,FALSE,FALSE);

			$c = ProductosController::NuevaCategoriaUdm($descripcion = $descripcion_catUdm, $activo = null);
			$this->assertInternalType("int" , $c["id_categoria"],"---- 'testEditarCategoriaUdm' 'id_categoria' NO ES UN ENTERO");

			$catUdm = CategoriaUnidadMedidaDAO::getByPK($c['id_categoria']);
			//se edita la categoriaUdm recien ingresada
			ProductosController::EditarCategoria($activa = $catUdm->getActiva(), $descripcion = $descripcion_editada, $c['id_categoria']);			
			//se redefine el obj para comparar valores
			$catUdm = CategoriaUnidadMedidaDAO::getByPK($c['id_categoria']);
			
			if($catUdm->getDescripcion() != $descripcion_editada)//el valor es diferente, no se actualizó entonces es error
				$this->assertFalse( True ,"---- 'testEditarCategoriaUdm' NO SE EDITÓ LA CategoriaUdm SE DEBIÓ CAMBIAR: ".$descripcion_catUdm." POR: ".$descripcion_editada);//forzar assertFalse para imprimir error
		}

		public function testBuscarCategoriaUdmPorQuery(){
			//se genera una categoria para despues buscarla por su nombre
			$descripcion_catUdm = self::RandomString(15,true,FALSE,FALSE); 
			
			$c = ProductosController::NuevaCategoriaUdm($descripcion = $descripcion_catUdm, $activo = null);
			$this->assertInternalType("int" , $c["id_categoria"],"---- 'testBuscarCategoriaUdmPorQuery' 'id_categoria' NO ES UN ENTERO");

			$res = ProductosController::BuscarCategoria($limit =  50 , $page = null, $query = $descripcion_catUdm, $start =  0  );
			$this->assertInternalType("int" , $res["numero_de_resultados"],"---- 'testBuscarCategoriaUdmPorQuery' 'numero_de_resultados' NO ES UN ENTERO");
			$this->assertGreaterThanOrEqual(1, $res['numero_de_resultados'],"---- 'testBuscarCategoriaUdmPorQuery' SE DEBIÓ DE ENCONTRAR ALMENOS 1 RESULTADO CON nombre = ".$descripcion_catUdm);				
		}

		public function testNuevaUnidadUdm(){
			$abreviatura_Udm = self::RandomString(5,true,FALSE,FALSE); 
			$descripcion_Udm = self::RandomString(15,true,FALSE,FALSE); 
			$descripcion_catUdm = self::RandomString(15,true,FALSE,FALSE); 
			
			$cat = ProductosController::NuevaCategoriaUdm($descripcion = $descripcion_catUdm, $activo = null);

			$udm = ProductosController::NuevaUnidadUdm(
														$abreviatura = $abreviatura_Udm, 
														$descripcion = $descripcion_Udm, 
														$factor_conversion = 1, 
														$id_categoria_unidad_medida = $cat['id_categoria'], 
														$tipo_unidad_medida = "Referencia UdM para esta categoria", 
														$activa = null
													);
			$this->assertInternalType("int" , $udm["id_unidad_medida"],"---- 'testNuevaUnidadUdm' 'id_unidad_medida' NO ES UN ENTERO");
		}

}
