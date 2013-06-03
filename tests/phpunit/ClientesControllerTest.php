<?php



require_once("../../server/bootstrap.php");


class ClientesControllerTests extends PHPUnit_Framework_TestCase {
/*
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
		$nombre = self::RandomString(15,FALSE,FALSE,FALSE)." - ". time();		
		
		$c = ClientesController::nuevo($nombre, 
													$clasificacion_cliente = null, 
													$codigo_cliente = time(), 
													$cuenta_de_mensajeria = null, 
													$curp = null, 
													$denominacion_comercial = null, 
													$descuento_general = null, 
													$direcciones = Array(Array(
														"calle"  			=> "Monte Balcanes",
														"numero_exterior"   => "107",
														"colonia"  			=> "Arboledas",
														"id_ciudad"  		=> 334,
														"codigo_postal"  	=> "38060",
														"numero_interior"  	=> null,
														"texto_extra"  		=> "Calle cerrada",
														"telefono1"  		=> "4616149974",
														"telefono2"			=> "45*451*454"
													)), 
													$email = null, 
													$id_cliente_padre = null, 
													$id_moneda =  1 , 
													$id_tarifa_compra = null, 
													$id_tarifa_venta = null, 
													$limite_credito = null, 
													$password = null, 
													$representante_legal = null, 
													$rfc = null, 
													$sitio_web = null, 
													$telefono_personal1 = null, 
													$telefono_personal2 = null);
		
		$this->assertInternalType("int" , $c["id_cliente"],"---- 'testNuevoCliente' 'id_cliente' NO ES UN ENTERO");
		$array_datos_cliente = ClientesController::Detalle($c['id_cliente']);
		$this->assertNotEquals(null , $array_datos_cliente[0]->getIdDireccion(),"---- 'testNuevoCliente' 'id_direccion' NO TIENE VALOR, NO SE INSERTÓ LA DIRECCION");			
		
	}


	public function testDetalleCliente(){
		//se crea un nuevo cliente 
		$nombre = self::RandomString(15,FALSE,FALSE,FALSE)." - ". time();
		$nuevo_cliente = ClientesController::nuevo($nombre);			
		$this->assertInternalType("int" , $nuevo_cliente["id_cliente"],"---- 'testDetalleCliente' 'id_cliente' NO ES UN ENTERO");
		
		$array_datos_cliente = ClientesController::Detalle($nuevo_cliente['id_cliente']);
		$this->assertEquals($nombre,$array_datos_cliente[0]->getNombre(),"---- 'testDetalleCliente' LOS DATOS EXTRAÍDOS NO COINCIDEN CON EL DEL ID SOLICITADO");
					
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



	public function testBuscarClientesPorID_Usuario(){
		//se crea un nuevo cliente que es el que debe de ser encontrado en el query
		$nombre = self::RandomString(15,FALSE,FALSE,FALSE)." - ". time();
		$nuevo_cliente = ClientesController::nuevo($nombre);

		$res = ClientesController::Buscar($id_suc= null,$id_usuario=$nuevo_cliente['id_cliente']);
		
		$this->assertInternalType("int" , $res["numero_de_resultados"],"---- 'testBuscarClientesPorID_Usuario' 'numero_de_resultados' NO ES UN ENTERO");
		
		$this->assertEquals(1, $res['numero_de_resultados'],"---- 'testBuscarClientesPorID_Usuario' SE DEBIÓ DE ENCONTRAR SÓLO 1 RESULTADO");
		
		if($res["numero_de_resultados"]>0 && $res["resultados"][0]["id_usuario"] != $nuevo_cliente['id_cliente'] ){
			$this->assertEquals($res["resultados"][0]->getIdUsuario(),$nuevo_cliente['id_cliente'],"---- 'testBuscarClientesPorID_Usuario' LOS IDS NO COINCIDEN SE ENVIÓ EL id_usuario =".$nuevo_cliente['id_cliente']."Y LA CONSULTA DEVOLVIÓ id_usuario = ".$res["resultados"][0]->getIdUsuario());
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
		$nombre_clasificacion = time();
		$clave_clasificacion = "c". time();
		$desc = self::RandomString(25,FALSE,FALSE,FALSE)." - ";

		$nueva = ClientesController::NuevaClasificacion($clave_clasificacion,$nombre_clasificacion,$desc);

		$this->assertInternalType('int',$nueva['id_categoria_cliente'],"---- 'testNuevaClasificacion' 'id_categoria_cliente' NO ES UN ENTERO");
	}


	// @expectedException BusinessLogicException
	public function testNuevaClasificacionConMismoNombre(){
		//se inserta una clasificacion
		$nombre_clasificacion = self::RandomString(5,FALSE,FALSE,FALSE)." - ";
		$clave_clasificacion = self::RandomString(2,TRUE,FALSE,FALSE)." - ";
		$desc = self::RandomString(25,FALSE,FALSE,FALSE)." - ";

		$nueva = ClientesController::NuevaClasificacion($clave_clasificacion,$nombre_clasificacion,$desc);
		$this->assertInternalType('int',$nueva['id_categoria_cliente'],"---- 'testNuevaClasificacionConMismoNombre' 'id_categoria_cliente' NO ES UN ENTERO");

		//se trata de insertar otra clasificacion con el mismo nombre y los demás datos
		$nueva2 = ClientesController::NuevaClasificacion($clave_clasificacion,$nombre_clasificacion,$desc);
	}

	public function testEditarClasificacion(){
		//se inserta una clasificacion
		$nombre_clasificacion = self::RandomString(5,FALSE,FALSE,FALSE)."-";
		$clave_clasificacion = self::RandomString(8,TRUE,FALSE,FALSE)." - ";
		$desc = self::RandomString(25,FALSE,FALSE,FALSE)." - ";

		$nueva = ClientesController::NuevaClasificacion($clave_clasificacion,$nombre_clasificacion,$desc);
		$this->assertInternalType('int',$nueva['id_categoria_cliente'],"---- 'testEditarClasificacion' 'id_categoria_cliente' NO ES UN ENTERO");

		//se edita la clasificacion recien ingresada
		ClientesController::EditarClasificacion($nueva['id_categoria_cliente'], $clave_clasificacion."1",$desc,$nombre_clasificacion."1");

	}		

	public function testBuscarClasificacionClientesPorQuery(){
		//se crea una nueva clasificacion que es la que debe de ser encontrada en el query
		$nombre_clasificacion = self::RandomString(5,FALSE,FALSE,FALSE)."-";
		$clave_clasificacion = self::RandomString(8,TRUE,FALSE,FALSE)." - ";
		$desc = self::RandomString(25,FALSE,FALSE,FALSE)." - ";

		$nueva = ClientesController::NuevaClasificacion($clave_clasificacion,$nombre_clasificacion,$desc);
		$this->assertInternalType('int',$nueva['id_categoria_cliente'],"---- 'testBuscarClasificacionClientesPorQuery' 'id_categoria_cliente' NO ES UN ENTERO");

		$res = ClientesController::BuscarClasificacion($limit =  50 , $page = null, $query = $nombre_clasificacion , $start =  0 );//se busca el usr recien insertado
		$this->assertNotNull($res,"---- 'testBuscarClasificacionClientesPorQuery' ::BuscarClasificacion() NO DEVOLVIÓ NINGÚN VALOR");
		$this->assertInternalType("int" , $res["numero_de_resultados"],"---- 'testBuscarClasificacionClientesPorQuery' 'numero_de_resultados' NO ES UN ENTERO");
		$this->assertGreaterThanOrEqual(1, $res['numero_de_resultados'],"---- 'testBuscarClasificacionClientesPorQuery' SE DEBIÓ DE ENCONTRAR ALMENOS 1 RESULTADO");
	}

	public function testNuevoAval(){
		//Crear un cliente
		$a = ClientesController::nuevo(time()."cliente");
		
		//crear su aval
		$b = ClientesController::nuevo(time()."aval");
		
		//asignar el aval al cliente
		ClientesController::NuevoAval(  array( array( "id_aval" => $b["id_cliente"] , "tipo_aval" => "prendario") ), 
				$a["id_cliente"] );

		$r = ClienteAvalDAO::getByPK($a["id_cliente"], $b["id_cliente"]);

		$this->assertNotNull($r);
	}

	public function testNuevoClienteDesdeAdminPAQ(){

		POSController::DropBd();
		
		$raw_exportation = file_get_contents( "adminpaq.catalogo.clientes.csv" );

		ClientesController::Importar( $raw_exportation );

	}

	public function testNuevoClienteDesdeCSV(){
		
		POSController::DropBd();
		
		$raw_exportation = file_get_contents( "test.csv" );

		ClientesController::ImportarCSV( $raw_exportation );
		
	}
*/
	public function testInsertarUsuariosSapuraiya() {

		$NombreArchivo = "registro.xls";
		$this->assertTrue(file_exists ($NombreArchivo));
		if(! file_exists ($NombreArchivo))
			return;

		$FilaInicio=2;
		$ColInicio=-1;
		$FilaFin=-1;
		$ColFin=-1;
		$Cabeceras=array();//Crea el arreglo de cabeceras
		$Contenido=array();//Crea el arreglo de contenido
		$ItCols=0;$ItFils=0;
		$Lector = new PHPExcel_Reader_Excel5;
		$ObjExcel = $Lector->load($NombreArchivo);
		$Hoja=$ObjExcel->getActiveSheet();//Obtiene la hoja activa

		foreach($Hoja->getRowIterator() as $IteradorFilas){
			foreach($IteradorFilas->getCellIterator() as $IteradorColumnas){
				if($ItFils==$FilaInicio){//Carga las cabeceras
					if($ColFin>-1){
						if(($ItCols>$ColInicio)&&($ItCols<$ColFin)){//Comprueba que esté dentro de los límites de columnas
							array_push($Cabeceras,$IteradorColumnas->getValue());
						}
					}else{
							if($ItCols>$ColInicio){//Comprueba que esté dentro de los límites de columnas
								array_push($Cabeceras,$IteradorColumnas->getValue());
							}
					}
				}
				$ItCols++;
			}
			$ItCols=0;
			$ItFils++;
		}
		if(($ColFin<=(sizeof($Cabeceras)-1))&&($ColFin>-1)){
			$X=$ColFin;
		}else{
			$X=sizeof($Cabeceras)-1;
		}
		if(($FilaFin>-1)&&($FilaFin<=$ItFils)){
			$Y=$ItFils;
		}else{
			$Y=$ItFils;
		}

		for($m=($FilaInicio+2);$m<=$Y;$m++){
			$ArrFila=array();//Reinicia la variable
			for($n=$ColInicio;$n<=$X;$n++){
				if($n < 0){
					continue;
				}

					$encabezado = $Cabeceras[$n];
					$celda = $Hoja->getCellByColumnAndRow($n, $m)->getValue();
					$ArrFila[$encabezado] = $celda;
			}
			array_push($Contenido, $ArrFila);
		}

		$params = array();
		$i=1;

		foreach ($Cabeceras as $c) {

			if($c !='R.F.C' && $c!="r.f.c" && $c!="NOMBRE O RAZÓN SOCIAL" && $c!="nombre o razón social"
				&& $c!="TELÉFONO" && $c!="teléfono" && $c!="CORREO ELECTRÓNICO" && $c!="correo electrónico"
				&& $c!="PÁGINA WEB" && $c!="página web")
			{

				$extra = new ExtraParamsEstructura();
				$extra->setTabla("clientes");
				$extra->setTipo("string");
				$extra->setObligatorio(0);
				if(strlen($c)>32){
					$extra->setCampo(str_replace(" ","_",substr($c,0,31)));
					$extra->setCaption(substr($c,0,28)."...");
				}else{
					$extra->setCaption($c);
					$extra->setCampo(str_replace(" ","_",$c));
				}
				$extra->setDescripcion($c);
				$extra->setLongitud(999999999);
				try{
					Logger::log("---($i) AGREGANDO NUEVO PARAM EXTRA: ".$extra->campo);
					ExtraParamsEstructuraDAO::save( $extra );
					$i++;
					$extra->setCampo($c);
					$extra->setCaption($c);
					array_push($params,$extra);
				}catch(Exception $e){
					Logger::log("--------> Error al insertar Parametro extra desde ClientesControllerTest, Error:".$e);
				}

			}
		}

		$extra = new ExtraParamsEstructura();
		$extra->setTabla("clientes");
		$extra->setTipo("string");
		$extra->setObligatorio(0);
		$extra->setCaption("Agenda");
		$extra->setCampo("Agenda");
		$extra->setDescripcion("La agenda de Sapuraiya");
		$extra->setLongitud(999999999);
		try{
			Logger::log("---($i) AGREGANDO NUEVO PARAM EXTRA: ".$extra->campo);
			ExtraParamsEstructuraDAO::save( $extra );
			array_push($params,$extra);
		}catch(Exception $e){
			Logger::log("--------> Error al insertar Parametro extra desde ClientesControllerTest, Error:".$e);
		}

		$i=1;
		$nuevos_user = array();
		foreach ($Contenido as $c) {
			$nuevo = new Usuario();
			$nuevo->setFechaAsignacionRol(time());
			$nuevo->setFechaAlta(time());
			$nuevo->setPassword(hash("md5","123"));
			$nuevo->setTarifaCompraObtenida('cliente');
			$nuevo->setTarifaVentaObtenida('cliente');
			$nuevo->setIdRol(5);
			$nuevo->setActivo(1);
			$nuevo->setIdPerfil(1);
			$nuevo->setConsignatario(0);
			$nuevo->setIdTarifaCompra(2);
			$nuevo->setIdTarifaVenta(1);
			$nuevo->setLimiteCredito(0);
			$nuevo->setSaldoDelEjercicio(0);

			if(array_key_exists('R.F.C', $c) || array_key_exists("r.f.c", $c)){
				if(array_key_exists('R.F.C', $c)){
					$nuevo->setRfc($c["R.F.C"]);
				}
				if (array_key_exists("r.f.c", $c)) {
					$nuevo->setRfc($c["r.f.c"]);
				}
			}

			if(array_key_exists("NOMBRE O RAZÓN SOCIAL", $c) || array_key_exists("nombre o razón social", $c)){
				if(array_key_exists('NOMBRE O RAZÓN SOCIAL', $c)){
					$nuevo->setNombre($c["NOMBRE O RAZÓN SOCIAL"]);
				}
				if (array_key_exists("nombre o razón social", $c)) {
					$nuevo->setNombre($c["nombre o razón social"]);
				}
			}

			if(array_key_exists("TELÉFONO", $c) || array_key_exists("teléfono", $c)){
				if(array_key_exists('TELÉFONO', $c)){
					$nuevo->setTelefonoPersonal1($c["TELÉFONO"]);
				}
				if (array_key_exists("teléfono", $c)) {
					$nuevo->setTelefonoPersonal1($c["teléfono"]);
				}
			}

			if(array_key_exists("CORREO ELECTRÓNICO", $c) || array_key_exists("correo electrónico", $c)){
				if(array_key_exists('CORREO ELECTRÓNICO', $c)){
					$nuevo->setCorreoElectronico($c["CORREO ELECTRÓNICO"]);
				}
				if (array_key_exists("correo electrónico", $c)) {
					$nuevo->setCorreoElectronico($c["correo electrónico"]);
				}
			}

			if(array_key_exists("PÁGINA WEB", $c) || array_key_exists("página web", $c)){
				if(array_key_exists('PÁGINA WEB', $c)){
					$nuevo->setPaginaWeb($c["PÁGINA WEB"]);
				}
				if (array_key_exists("página web", $c)) {
					$nuevo->setPaginaWeb($c["página web"]);
				}
			}

			try{
				UsuarioDAO::save($nuevo);
				$i++;
				array_push($nuevos_user,$nuevo);
			}catch(Exception $e){
				Logger::log("--------> Error al insertar Usuario nuevo desde ClientesControllerTest, Error:".$e);
			}
		}

		foreach ($nuevos_user as $u) {
			$para = array();
			foreach ($Contenido as $c) {
				if ($c["R.F.C"]==$u->rfc) {
					$para["Agenda"] ="";
					foreach ($params as $p) {
						$index = $p->campo;
						$prop ="";
						if(strlen($index)>32){
							$prop = (str_replace(" ","_",substr($index,0,31)));
						}else{
							$prop = (str_replace(" ","_",$index));
						}

						if($index!="Agenda"){
							if(strlen($c[$index])<1 || $c[$index]==NULL)
								$para[$prop] = "";
							else
								$para[$prop] = $c[$index];
						}
					}
				}
			}
			ClientesController::Editar(
										$id_cliente= $u->id_usuario, 
										$clasificacion_cliente = null, 
										$codigo_cliente = null, 
										$cuenta_de_mensajeria = null, 
										$curp = null, 
										$denominacion_comercial = null, 
										$descuento_general = null, 
										$direcciones = null, 
										$email = null, 
										$extra_params = $para, 
										$id_cliente_padre = null, 
										$id_moneda = null, 
										$id_tarifa_compra = null, 
										$id_tarifa_venta = null, 
										$limite_credito = null, 
										$password = null, 
										$password_anterior = null, 
										$razon_social = null, 
										$representante_legal = null, 
										$rfc = null, 
										$sitio_web = null, 
										$telefono_personal1 = null, 
										$telefono_personal2 = null
									);
		}
	}

}

