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
		
		/*
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
			"1234",//ACEPTA # AUNQUE LOS EVALUA Y NO PERMITE INSERTAR
			null, 
			"RFC8",//NO EXPR REG PARA RFC 
			12345, //INSERTA NUMEROS, CUANDO SON # ELEVADOS LOS INSERTA 123e+38
			null, 
			null
			);
			$this->assertInternalType("int" , $c["id_cliente"]);
		}
		*/

		//public function testNuevoClienteConMismoNombre(){
		//	ClientesController::nuevo("Alan Gonzalez");
		//}

		//status no devuelve a los clientes dados de alta en la sucursal que se indica, permite ingresar ids de suc que no existen, hasta numeros negativos

		public function testBuscarClientesPorID_Sucursal(){
			$res = ClientesController::Buscar($id_usuario = null,$query=null,$limit=null,$start=null,$id_sucursal = 1);
			$this->assertInternalType("int" , $res["numero_de_resultados"]);
			echo("Numero res por suc: ".$res["numero_de_resultados"]);			
		}

		public function testBuscarClientesPorID_Usuario(){
			$res = ClientesController::Buscar($id_usuario=1);
			$this->assertInternalType("int" , $res["numero_de_resultados"],"No devuelve a los clientes dados de alta en la sucursal que se indica, permite ingresar ids de suc que no existen, hasta numeros negativos");			
			echo("Numero res por usr: ".$res["numero_de_resultados"]);	
		}
		
		public function testBuscarClientesPorQuery(){
			$res = ClientesController::Buscar($id_usuario=null,$query="Social");
			$this->assertInternalType("int" , $res["numero_de_resultados"],"No busca por el criterio que se manda");			
		}

		public function testBuscarClientes(){
        
        //se crea un nuevo cliente
        $nuevo_cliente = ClientesController::nuevo($nombre = "Nombre_". time());
        
        $busqueda = ClientesController::Buscar();
		//se realiza una busqueda general y se verifica que contenga los parametros de respuesta
        $this->assertArrayHasKey('resultados', $busqueda);
        $this->assertArrayHasKey('numero_de_resultados', $busqueda);
		//que los tipos de datos sean los esperados
        $this->assertInternalType('int', $busqueda['numero_de_resultados']);
        $this->assertInternalType('array', $busqueda['resultados']);
		
        $this->assertGreaterThanOrEqual(0, $busqueda['numero_de_resultados']);

        //la busqueda por activo, al menos debe de existir uno por el insertado arriba  
        $busqueda = ClientesController::Buscar($activo = 1);
        $this->assertGreaterThanOrEqual(1, $busqueda["numero_de_resultados"]);

        //probamos busqueda por start
        $busqueda = ClientesController::Buscar($start = 1);       
        $this->assertGreaterThanOrEqual(1, $busqueda["numero_de_resultados"]);

        //probamos busqueda por limit
        $busqueda = ClientesController::Buscar($limit = 1);
        $this->assertGreaterThanOrEqual(1, $busqueda["numero_de_resultados"]);

        //probamos busqueda por query
        $busqueda = ClientesController::Buscar($query = "Nombre_");
        $this->assertGreaterThanOrEqual(0, $busqueda["numero_de_resultados"]);

        //probamos busqueda por id_empresa
        $busqueda = ClientesController::Buscar($id_empresa = 1);
        $this->assertGreaterThanOrEqual(0, $busqueda["numero_de_resultados"]);

        //valores combinados
        $busqueda = ClientesController::Buscar(
            $activo = 1,
            $start = 1,
            $limit = 1,
            $query = "Juan Manuel",
            $id_empresa = 1
        );

        $this->assertGreaterThanOrEqual(0, $busqueda["numero_de_resultados"]);

    }

		public function testNuevaClasificacion(){
			$c = ClientesController::NuevaClasificacion("Clasificacion 5","con el id = 3");
			$this->assertInternalType('int',$c['id_categoria_cliente']);
		}
		public function testNuevaClasificacionConMismoNombre(){
			$c = ClientesController::NuevaClasificacion("Clave2","nombre 2   ","desc");
			$this->assertInternalType('int',$c['id_categoria_cliente']);
		}
	
		
	}

