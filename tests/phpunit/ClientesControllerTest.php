<?php

	date_default_timezone_set ( "America/Mexico_City" );

	if(!defined("BYPASS_INSTANCE_CHECK"))
		define("BYPASS_INSTANCE_CHECK", false);

	$_GET["_instance_"] = 71;

	require_once("../../server/bootstrap.php");


	class ClientesControllerTests extends PHPUnit_Framework_TestCase {
		
		protected function setUp(){
			Logger::log("-----------------------------");
			$r = SesionController::Iniciar(123, 1, true);
		}
		
		public function testNuevaClasificacionCliente(){
			$c = ClasificacionClienteDAO::getByPK(1);
			
			if(!is_null($c)){
				ClasificacionClienteDAO::delete($c);
				
			}

			$n_c = new ClasificacionCliente();
			$n_c->setIdClasificacionCliente(1);
			$n_c->setClaveInterna('Default');
			$n_c->setNombre('Default');
			$n_c->setIdTarifaVenta(1);
			$n_c->setIdTarifaCompra(1);			
			
			ClasificacionClienteDAO::save( $n_c );
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

		
		public function testNuevoCliente(){
			$c = ClientesController::nuevo("Felix Hdez Reyes",//PERMITE NOMBRES REPETIDOS
			"1",//HARDCODEADO A 1
			"C47465-2012",//->HARDCODEADO A NULL SIEMPRE
			"* Otra cuenta_mensajeria",//INSERTA LONG > A LOS Q INDICA LA DEF DE LA TABLA DE AL BD
			"CURP8",//NO ESTA BIEN VALIDADADO, NO EXP REG
			"denominacion comercial",//INSERTA LONG > A LOS Q INDICA LA DEF DE LA TABLA DE AL BD
			"50", //PUEDE RECIBIR STRINGS E INSERTARLOS COMO #'S
			null,
			"aclvC@hotmail.com",
			null,
			1,
			null, //
			"1", //
			"999999",//ACEPTA CADENAS Y NO TIENEN UN LIM MAX, ACEPTA HASTA 9999999999999999999999999999999999999999999999999999999999999999......
			"1234",//ACEPTA # AUNQUE LOS EVALUA Y NO PERMITE INSERT
			null, 
			"RFC8",//NO EXPR REG PARA RFC 
			12345, //INSERTA NUMEROS, CUANDO SON # ELEVADOS LOS INSERTA 123e+38
			null, 
			null
			);
			$this->assertInternalType("int" , $c["id_cliente"],"---- 'testNuevoCliente' 'id_cliente' NO ES UN ENTERO");
		}

		public function testNuevoClienteConMismoNombre(){
			//se crea un nuevo cliente 
			$nombre = self::RandomString(15,FALSE,FALSE,FALSE)." - ". time();
        	$nuevo_cliente = ClientesController::nuevo($nombre);			
			$this->assertInternalType("int" , $nuevo_cliente["id_cliente"],"---- 'testNuevoClienteConMismoNombre' 'id_cliente' NO ES UN ENTERO");
			//se trata de insertar otro cliente con el mismo nombre
			$nuevo_cliente2 = ClientesController::nuevo($nombre);
			$this->assertLessThanOrEqual(0, $nuevo_cliente2['id_cliente'],"---- 'testNuevoClienteConMismoNombre' NO SE DEBERIA DE PERMITIR DUPLICAR NOMBRES, NOMBRE DUPLICADO: ".$nombre);
		}

		public function testDetalleCliente(){
			//se crea un nuevo cliente 
			$nombre = self::RandomString(15,FALSE,FALSE,FALSE)." - ". time();
        	$nuevo_cliente = ClientesController::nuevo($nombre);			
			$this->assertInternalType("int" , $nuevo_cliente["id_cliente"],"---- 'testDetalleCliente' 'id_cliente' NO ES UN ENTERO");
			
			$array_datos_cliente = ClientesController::Detalle($nuevo_cliente['id_cliente']);
			$this->assertEquals($nombre,$array_datos_cliente[0]->getNombre(),"---- 'testDetalleCliente' LOS DATOS EXTRAÍDOS NO COINCIDEN CON EL DEL ID SOLICITADO");
			//$this->assertInternalType("string",$array_datos_cliente,"---- 'testDetalleCliente' EL VALOR DEVUELTO NO ES UNA CADENA QUE PUEDA SER UN JSON");			
		}

		public function testEditarCliente(){
			$nombre = self::RandomString(15,FALSE,FALSE,FALSE)." - ". time();
			$denominacion = "Denominacion - ".time();
			$descuento = 10;
			$limite_credito = 1000;
			$pass = "hola123";
			$sitio = "myweb.com";
			//se crea el cliente que despues será editado
        	$nuevo_cliente = ClientesController::nuevo($nombre, 
														$clasificacion_cliente = null, 
														$codigo_cliente = null, 
														$cuenta_de_mensajeria = null, 
														$curp = null, 
														$denominacion_comercial = $denominacion, 
														$descuento_general = $descuento, 
														$direcciones = null, 
														$email = null, 
														$id_cliente_padre = null, 
														$id_moneda =  1 , 
														$id_tarifa_compra = null, 
														$id_tarifa_venta = null, 
														$limite_credito = $limite_credito, 
														$password = $pass, 
														$representante_legal = null, 
														$rfc = null, 
														$sitio_web = $sitio, 
														$telefono_personal1 = null, 
														$telefono_personal2 = null);
			//se edita el cliente recien ingresado
			ClientesController::Editar($nuevo_cliente['id_cliente'],
										$clasificacion_cliente = null, 
										$codigo_cliente = null, 
										$cuenta_de_mensajeria = null, 
										$curp = null, 
										$denominacion_comercial = $denominacion."-123", //se modifica este campo
										$descuento_general = 0, //se cambia a 0
										$direcciones = null, 
										$email = null, 
										$id_cliente_padre = null, 
										$id_moneda =  null , 
										$id_tarifa_compra = null, 
										$id_tarifa_venta = null, 
										$limite_credito = 1500, //se cambia
										$password = "hola", //se cambia
										$password_anterior = "hola123", 
										$razon_social = null, 
										$representante_legal = null, 
										$rfc = null, 
										$sitio_web = $sitio.".mx", //se cambia
										$telefono_personal1 = null, 
										$telefono_personal2 = null);

			$array_datos_cliente = ClientesController::Detalle($nuevo_cliente['id_cliente']);
			$this->assertNotEquals($denominacion,$array_datos_cliente[0]->getDenominacionComercial(),"---- 'testEditarCliente' LA DENOMINACION NO SE ACTUALIZÓ");
			$this->assertNotEquals(10,$array_datos_cliente[0]->getDescuento(),"---- 'testEditarCliente' EL DESCUENTO GENERAL NO SE ACTUALIZÓ");
			$this->assertNotEquals(1000,$array_datos_cliente[0]->getLimiteCredito(),"---- 'testEditarCliente' LIMINTE DE CREDITO NO SE ACTUALIZÓ");
			$this->assertNotEquals("hola123",$array_datos_cliente[0]->getPassword(),"---- 'testEditarCliente' PASSWORD NO SE ACTUALIZÓ");
			$this->assertNotEquals($sitio,$array_datos_cliente[0]->getPaginaWeb(),"---- 'testEditarCliente' SITIO WEB NO SE ACTUALIZÓ");
		}

		public function testBuscarClientesPorID_Sucursal(){
			$res = ClientesController::Buscar($id_sucursal = 1 );
			$this->assertInternalType("int" , $res["numero_de_resultados"],"---- 'testBuscarClientesPorID_Sucursal' 'numero_de_resultados' NO ES UN ENTERO");
			foreach($res["resultados"] as $row){
				if($row->getIdSucursal() != '1'|| is_null($row->getIdSucursal()))
					$this->assertEquals($row->getIdSucursal(),1,"---- 'testBuscarClientesPorID_Sucursal' LOS IDS NO COINCIDEN SE ENVIÓ EL id_sucursal =1 Y LA CONSULTA DEVOLVIÓ id_sucursal = ".$row->getIdSucursal()." PARA id_usuario ".$row->getIdUsuario());
			}		
		}

		public function testBuscarClientesPorID_Usuario(){
			$res = ClientesController::Buscar($id_suc= null,$id_usuario=21);//el id_usr = 21 si tiene el rol = 5
			$this->assertInternalType("int" , $res["numero_de_resultados"],"---- 'testBuscarClientesPorID_Usuario' 'numero_de_resultados' NO ES UN ENTERO");			
			
			if($res["numero_de_resultados"]>0 && $res["resultados"][0]->getIdUsuario() != '21' ){
				$this->assertEquals($res["resultados"][0]->getIdUsuario(),21,"---- 'testBuscarClientesPorID_Usuario' LOS IDS NO COINCIDEN SE ENVIÓ EL id_usuario =21 Y LA CONSULTA DEVOLVIÓ id_usuario = ".$res["resultados"][0]->getIdUsuario());
			}
		}
		
		public function testBuscarClientesPorQuery(){
			//se crea un nuevo cliente que es el que debe de ser encontrado en el query
			$nombre = self::RandomString(15,FALSE,FALSE,FALSE)." - ". time();
        	$nuevo_cliente = ClientesController::nuevo($nombre);

			$res = ClientesController::Buscar($id_sucursal = null, $id_usuario=null,$limit = 50,$page = null, $query=$nombre);//se busca el usr recien insertado
			$this->assertInternalType("int" , $res["numero_de_resultados"],"---- 'testBuscarClientesPorQuery' 'numero_de_resultados' NO ES UN ENTERO");	

			$this->assertGreaterThanOrEqual(1, $res['numero_de_resultados'],"---- 'testBuscarClientesPorQuery' SE DEBIÓ DE ENCONTRAR ALMENOS 1 RESULTADO");			
		}

		public function testBuscarClientes(){
        
		    //se crea un nuevo cliente
			$nombre = self::RandomString(12,FALSE,FALSE,FALSE);
		    $nuevo_cliente = ClientesController::nuevo($nombre);
		    
		    $busqueda = ClientesController::Buscar();
			//se realiza una busqueda general y se verifica que contenga los parametros de respuesta
		    $this->assertArrayHasKey('resultados', $busqueda);
		    $this->assertArrayHasKey('numero_de_resultados', $busqueda);
			//que los tipos de datos sean los esperados
		    $this->assertInternalType('int', $busqueda['numero_de_resultados'],"---- 'testBuscarClientes' 'numero_de_resultados' NO ES UN ENTERO");
		    $this->assertInternalType('array', $busqueda['resultados'],"---- 'testBuscarClientes' 'resultados' NO ES UN ARRAY");
		
		    $this->assertGreaterThanOrEqual(0, $busqueda['numero_de_resultados'],"---- 'testBuscarClientes' 'numero_de_resultados' NO ES UN VALOR VALIDO");

		    //probamos busqueda por start
		    $busqueda = ClientesController::Buscar($id_sucursal = null, $id_usuario = null, $limit =  50 , $page = null, $query = null, $start = 1);       
		    $this->assertGreaterThanOrEqual(1, $busqueda["numero_de_resultados"],"---- 'testBuscarClientes' DEBE DE DEVOLVER ALMENOS 1 RESULTADO");

		    //probamos busqueda por limit
		    $busqueda = ClientesController::Buscar($id_sucursal = null, $id_usuario = null, $limit = 1, $page = null, $query = null, $start = null);
		    $this->assertGreaterThanOrEqual(1, $busqueda["numero_de_resultados"],"---- 'testBuscarClientes' DEBE DE DEVOLVER ALMENOS 1 RESULTADO");

		    //probamos busqueda por query
		    $busqueda = ClientesController::Buscar($id_sucursal = null, $id_usuario = null, $limit = 1, $page = null, $query = $nombre, $start = null);
		    $this->assertGreaterThanOrEqual(0, $busqueda["numero_de_resultados"],"---- 'testBuscarClientes' 'numero_de_resultados' NO ES UN VALOR VALIDO");

		    //valores combinados
		    $busqueda = ClientesController::Buscar($id_sucursal = null, $id_usuario = null, $limit =  5 , $page = null, $query = $nombre, $start = 1);

		    $this->assertGreaterThanOrEqual(0, $busqueda["numero_de_resultados"],"---- 'testBuscarClientes' 'numero_de_resultados' NO ES UN VALOR VALIDO");

    	}

		public function testNuevaClasificacion(){
			$nombre_clasificacion = self::RandomString(5,FALSE,FALSE,FALSE)." - ". time();			
			$clave_clasificacion = self::RandomString(2,TRUE,FALSE,FALSE)." - ". time();
			$desc = self::RandomString(25,FALSE,FALSE,FALSE)." - ";

			$nueva = ClientesController::NuevaClasificacion($clave_clasificacion,$nombre_clasificacion,$desc);
			$this->assertInternalType('int',$c['id_categoria_cliente'],"---- 'testNuevaClasificacion' 'id_categoria_cliente' NO ES UN ENTERO");
		}

		public function testNuevaClasificacionConMismoNombre(){
			//se inserta una clasificacion
			$nombre_clasificacion = self::RandomString(5,FALSE,FALSE,FALSE)." - ";			
			$clave_clasificacion = self::RandomString(2,TRUE,FALSE,FALSE)." - ";
			$desc = self::RandomString(25,FALSE,FALSE,FALSE)." - ";

			$nueva = ClientesController::NuevaClasificacion($clave_clasificacion,$nombre_clasificacion,$desc);
			$this->assertInternalType('int',$nueva['id_categoria_cliente'],"---- 'testNuevaClasificacionConMismoNombre' 'id_categoria_cliente' NO ES UN ENTERO");

			//se trata de insertar otra clasificacion con el mismo nombre y los demás datos
			$nueva2 = ClientesController::NuevaClasificacion($clave_clasificacion,$nombre_clasificacion,$desc);
			$this->assertLessThanOrEqual(0, $nueva2['id_categoria_cliente'],"---- 'testNuevaClasificacionConMismoNombre' NO SE DEBERIA DE PERMITIR DUPLICAR NOMBRES O CLAVES, NOMBRE DUPLICADO: ".$nombre_clasificacion," CLAVE DUPLICADA: ".$clave_clasificacion);

		}

		public function testEditarClasificacion(){
			//se inserta una clasificacion
			$nombre_clasificacion = self::RandomString(5,FALSE,FALSE,FALSE)."-";			
			$clave_clasificacion = self::RandomString(2,TRUE,FALSE,FALSE)." - ";
			$desc = self::RandomString(25,FALSE,FALSE,FALSE)." - ";

			$nueva = ClientesController::NuevaClasificacion($clave_clasificacion,$nombre_clasificacion,$desc);
			$this->assertInternalType('int',$nueva['id_categoria_cliente'],"---- 'testEditarClasificacion' 'id_categoria_cliente' NO ES UN ENTERO");

			//se edita la clasificacion recien ingresada
			ClientesController::EditarClasificacion($nueva['id_categoria_cliente'], $clave_clasificacion."1",$desc,$nombre_clasificacion."1");
			//ClientesController::EditarClasificacion(15, "RB - 2",null,"uxcbs-2");
			//no está el metodo detalleClasificacion para verificar q si hayan sido modificado los datos en la BD

		}		
	
		public function testBuscarClasificacionClientesPorQuery(){
			//se crea una nueva clasificacion que es la que debe de ser encontrada en el query
			$nombre_clasificacion = self::RandomString(5,FALSE,FALSE,FALSE)."-";			
			$clave_clasificacion = self::RandomString(2,TRUE,FALSE,FALSE)." - ";
			$desc = self::RandomString(25,FALSE,FALSE,FALSE)." - ";

			$nueva = ClientesController::NuevaClasificacion($clave_clasificacion,$nombre_clasificacion,$desc);
			$this->assertInternalType('int',$nueva['id_categoria_cliente'],"---- 'testBuscarClasificacionClientesPorQuery' 'id_categoria_cliente' NO ES UN ENTERO");

			$res = ClientesController::BuscarClasificacion($limit =  50 , $page = null, $query = $nombre_clasificacion , $start =  0 );//se busca el usr recien insertado
			$this->assertNotNull($res,"---- 'testBuscarClasificacionClientesPorQuery' ::BuscarClasificacion() NO DEVOLVIÓ NINGÚN VALOR");		
			$this->assertInternalType("int" , $res["numero_de_resultados"],"---- 'testBuscarClasificacionClientesPorQuery' 'numero_de_resultados' NO ES UN ENTERO");	
			$this->assertGreaterThanOrEqual(1, $res['numero_de_resultados'],"---- 'testBuscarClasificacionClientesPorQuery' SE DEBIÓ DE ENCONTRAR ALMENOS 1 RESULTADO");			
		}		
		
	}

