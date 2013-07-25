<?php
	require_once("../../server/bootstrap.php");
	
class EmpresasControllerTest extends PHPUnit_Framework_TestCase {

	private $_empresa;

	protected function setUp(){
		Logger::log("-----------------------------");

		$r = SesionController::Iniciar(123, 1, true);

		if($r["login_succesful"] == false){
			global $POS_CONFIG;
			$POS_CONFIG["INSTANCE_CONN"]->Execute("INSERT INTO `usuario` (`id_usuario`, `id_direccion`, `id_direccion_alterna`, `id_sucursal`, `id_rol`, `id_clasificacion_cliente`, `id_clasificacion_proveedor`, `id_moneda`, `fecha_asignacion_rol`, `nombre`, `rfc`, `curp`, `comision_ventas`, `telefono_personal1`, `telefono_personal2`, `fecha_alta`, `fecha_baja`, `activo`, `limite_credito`, `descuento`, `password`, `last_login`, `consignatario`, `salario`, `correo_electronico`, `pagina_web`, `saldo_del_ejercicio`, `ventas_a_credito`, `representante_legal`, `facturar_a_terceros`, `dia_de_pago`, `mensajeria`, `intereses_moratorios`, `denominacion_comercial`, `dias_de_credito`, `cuenta_de_mensajeria`, `dia_de_revision`, `codigo_usuario`, `dias_de_embarque`, `tiempo_entrega`, `cuenta_bancaria`, `id_tarifa_compra`, `tarifa_compra_obtenida`, `id_tarifa_venta`, `tarifa_venta_obtenida`) VALUES
				(1, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2011-10-24 18:28:24', 'Administrador', NULL, NULL, NULL, NULL, NULL, '2011-10-24 18:28:34', NULL, 1, 0, NULL, '202cb962ac59075b964b07152d234b70', NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, 0, 'rol', 0, 'rol');");

			$r = SesionController::Iniciar(123, 1, true);
		}
	}

	public static function setUpBeforeClass() {
		POSController::DropBd();
	}

	public function testBootstrap() {

		$r = SesionController::Iniciar(123, 1, true);

		$this->assertEquals($r["login_succesful"], true);

		$r = SesionController::getCurrentUser(  );

		$this->assertEquals($r->getIdUsuario(), 1);
	}

	public function testNuevo(){

		$direccion = Array(
			"calle"  			=> "Monte Balcanes",
			"numero_exterior"   => "107",
			"colonia"  			=> "Arboledas",
			"id_ciudad"  		=> 334,
			"codigo_postal"  	=> "38060",
			"numero_interior"  	=> null,
			"texto_extra"  		=> "Calle cerrada",
			"telefono1"  		=> "4616149974",
			"telefono2"			=> "45*451*454"
		);

		$id_moneda = 1;
		$razon_social = "Caffeina Software".time();
		$rfc  = "GOHA".time();

		$empresas_con_rfc = EmpresaDAO::getByRFC($rfc);

		foreach ($empresas_con_rfc as $empObj) {
			EmpresasController::Eliminar( $empObj->getIdEmpresa() );
		}

		$this->_empresa = EmpresasController::Nuevo(
				$direccion 			= array( $direccion ), 
				$id_moneda			= 1, 
				$razon_social		= time()	."a",
				$rfc				= time()	."b",
				$cedula 			= time()	."c", 
				$email				= time()	."d", 
				$impuestos_compra 	= null, 
				$impuestos_venta 	= null, 
				$logo 				= time()	."e",
				$representante_legal = time()	."f", 
				$sucursales 		= null, 
				$texto_extra 		= time()	."g"
			);

		$this->assertInternalType('int', $this->_empresa["id_empresa"] );

		$dao = EmpresaDAO::getByPK($this->_empresa["id_empresa"]);

		$this->assertEquals($razon_social, 			$dao->getRazonSocial());
		$this->assertEquals($rfc, 					$dao->getRfc());
		$this->assertEquals($representante_legal,	$dao->getRepresentanteLegal());
	}

	/**
    * @expectedException InvalidDataException
    */
	public function testNuevoEmpresaMismoNombre(){
		$direccion = Array(
			"calle"  			=> "Calle ".time(),
	        "numero_exterior"   => "107",
	        "colonia"  			=> "Colonia ".time(),
	        "id_ciudad"  		=> 334,
	        "codigo_postal"  	=> "38060",
	        "numero_interior"  	=> null,
	        "texto_extra"  		=> null,
	        "telefono1"  		=> "4616149974",
	        "telefono2"			=> "45*451*454"
		);
		
		$id_moneda = 1;
		$razon_social = "Caffeina Software".time();
		$rfc  = "GOHA".time();
		
		$empresas_con_rfc = EmpresaDAO::getByRFC($rfc);
		
		foreach ($empresas_con_rfc as $empObj) {
			EmpresasController::Eliminar( $empObj->getIdEmpresa() );
		}

		$this->_empresa = EmpresasController::Nuevo(
				array($direccion), 
				$id_moneda, 
				$razon_social, 
				$rfc
			);

		$this->assertInternalType('int', $this->_empresa["id_empresa"] );
		//se trata de ingresar una empresa con los mismos datos
		$this->_empresa = EmpresasController::Nuevo(
				array($direccion), 
				$id_moneda, 
				$razon_social, 
				$rfc
			);
	}

	public function testEditarEmpresa(){
		$direccion = Array(
			"calle"  			=> "Calle ".time(),
	        "numero_exterior"   => "107",
	        "colonia"  			=> "Colonia ".time(),
	        "id_ciudad"  		=> 334,
	        "codigo_postal"  	=> "38060",
	        "numero_interior"  	=> null,
	        "texto_extra"  		=> null,
	        "telefono1"  		=> "4616149974",
	        "telefono2"			=> "45*451*454"
		);
		
		$id_moneda = 1;
		$razon_social = "Caffeina Software-".time();
		$rfc  = "GOHA-".time();

		$nueva_empresa = EmpresasController::Nuevo(
				array($direccion), 
				$id_moneda, 
				$razon_social, 
				$rfc
			);

		$this->assertInternalType('int', $nueva_empresa["id_empresa"] );

		$original = EmpresaDAO::getByPK( $nueva_empresa['id_empresa'] );
		//se edita la empresa con los mismos datos
		EmpresasController::Editar($id_empresa = $nueva_empresa['id_empresa'], 
									$cedula = "cedula_".time(), //se cambia
									$direccion = null, 
									$email = null, 
									$id_moneda = null, 
									$impuestos_venta = null, 
									$impuesto_compra = null, 
									$logo = null, 
									$razon_social = null, 
									$representante_legal = null, 
									$texto_extra = "Texto_".time());//se cambia

		$editada = EmpresaDAO::getByPK( $nueva_empresa['id_empresa'] );
		
		//$this->assertNotEquals($editada->getCedula() , $original->getCedula(),"---- 'testEditarEmpresa' LA CEDULA NO SE ACTUALIZÓ");
		//$this->assertNotEquals($editada->getTextoExtra() , $original->getTextExtra(),"---- 'testEditarEmpresa' LA TEXTO EXTRA NO SE ACTUALIZÓ");
	}

	
	public function testBuscar(){
		try{
			$busqueda = EmpresasController::Buscar();			
			
		}catch(Exception $e){
			Logger::error($e);
			
		}


		
		//debe haber por lo menos un resultado
		if( $busqueda["numero_de_resultados"] == 0 ){
			echo "REVISAR BUG EN DAOS. serch() debe regresar getAll() cuando no se envian parametros";
		}
		$this->assertGreaterThan( 0, $busqueda["numero_de_resultados"]);
		$this->assertInternalType('int', $busqueda["numero_de_resultados"]);
		$this->assertEquals( $busqueda["numero_de_resultados"], sizeof( $busqueda["resultados"]));
	}
	

}
