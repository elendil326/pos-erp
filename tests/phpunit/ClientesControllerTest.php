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
			"CURP8".time(),//NO ESTA BIEN VALIDADADO, NO EXP REG
			"denominacion comercial",//INSERTA LONG > A LOS Q INDICA LA DEF DE LA TABLA DE AL BD
			"50", //PUEDE RECIBIR STRINGS E INSERTARLOS COMO #'S
			null,
			time() . "@hotmail.com",
			null,
			1,
			null, //
			"1", //
			"999999",//ACEPTA CADENAS Y NO TIENEN UN LIM MAX, ACEPTA HASTA 9999999999999999999999999999999999999999999999999999999999999999......
			"1234",//ACEPTA # AUNQUE LOS EVALUA Y NO PERMITE INSERT
			null, 
			"RFC8".time(),//NO EXPR REG PARA RFC 
			12345, //INSERTA NUMEROS, CUANDO SON # ELEVADOS LOS INSERTA 123e+38
			null, 
			null
			);
			$this->assertInternalType("int" , $c["id_cliente"],"---- 'testNuevoCliente' 'id_cliente' NO ES UN ENTERO");
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

		/**
     	* @expectedException BusinessLogicException
     	*/
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
		



		/*
		public function testBuscarClientesPorID_Sucursal(){
			$res = ClientesController::Buscar($id_sucursal = 1 );
			$this->assertInternalType("int" , $res["numero_de_resultados"],"---- 'testBuscarClientesPorID_Sucursal' 'numero_de_resultados' NO ES UN ENTERO");
			
			foreach($res["resultados"] as $row){
				if($row->getIdSucursal() != '1'|| is_null($row->getIdSucursal()))
					$this->assertEquals($row->getIdSucursal(),1,"---- 'testBuscarClientesPorID_Sucursal' LOS IDS NO COINCIDEN SE ENVIÓ EL id_sucursal =1 Y LA CONSULTA DEVOLVIÓ id_sucursal = ".$row->getIdSucursal()." PARA id_usuario ".$row->getIdUsuario());
			}		
		}*/
		
		

		public function testNuevoClienteDesdeAdminPAQ(){
			
			POSController::DropBd();
			
			$raw_exportation = 
"CATALOGOS
MGW10034
cIdMoneda
cNombreMoneda
cSimboloMoneda
cPosicionSimbolo
cPlural
cSingular
cDescripcionProtegida
cIdBandera
cDecimalesMoneda
/MGW10034
MGW10019
cIdClasificacion
cNombreClasificacion
/MGW10019
MGW10020
cValorClasificacion
cCodigoValorClasificacion
/MGW10020
MGW10021
cNombreCaracteristica
/MGW10021
MGW10022
cValorCaracteristica
cNemoCaracteristica
/MGW10022
MGW10026
cNombreUnidad
cAbreviatura
cDespliegue
/MGW10026
MGW10027
cNombreUnidad1
cNombreUnidad2
cFactorConversion
/MGW10027
MGW10003
cCodigoAlmacen
cNombreAlmacen
cFechaAltaAlmacen
cCodigoValorClasif1
cCodigoValorClasif2
cCodigoValorClasif3
cCodigoValorClasif4
cCodigoValorClasif5
cCodigoValorClasif6
cSegContAlmacen
cTextoExtra1
cTextoExtra2
cTextoExtra3
cFechaExtra
cImporteExtra1
cImporteExtra2
cImporteExtra3
cImporteExtra4
cBanDomicilio
cSistOrig
/MGW10003
MGW10029
CCODIGOPROMOCION
CNOMBREPROMOCION
CFECHAINICIO
CFECHAFIN
CVOLUMENMINIMO
CVOLUMENMAXIMO
CPORCENTAJEDESCUENTO
cCodigoValorClasifCliente1
cCodigoValorClasifCliente2
cCodigoValorClasifCliente3
cCodigoValorClasifCliente4
cCodigoValorClasifCliente5
cCodigoValorClasifCliente6
cCodigoValorClasifProducto1
cCodigoValorClasifProducto2
cCodigoValorClasifProducto3
cCodigoValorClasifProducto4
cCodigoValorClasifProducto5
cCodigoValorClasifProducto6
/MGW10029
MGW10001
CCODIGOAGENTE
CNOMBREAGENTE
CFECHAALTAAGENTE
CTIPOAGENTE
CCOMISIONVENTAAGENTE
CCOMISIONCOBROAGENTE
cCodigoCliente
cCodigoProveedor
cCodigoValorClasif1
cCodigoValorClasif2
cCodigoValorClasif3
cCodigoValorClasif4
cCodigoValorClasif5
cCodigoValorClasif6
CTEXTOEXTRA1
CTEXTOEXTRA2
CTEXTOEXTRA3
CFECHAEXTRA
CIMPORTEEXTRA1
CIMPORTEEXTRA2
CIMPORTEEXTRA3
CIMPORTEEXTRA4
/MGW10001
MGW10002
CCODIGOCLIENTE
CRAZONSOCIAL
CFECHAALTA
CRFC
CCURP
CDENCOMERCIAL
CREPLEGAL
CIDMONEDA
CIDMONEDA2
CLISTAPRECIOCLIENTE
CDESCUENTODOCTO
CDESCUENTOMOVTO
CBANVENTACREDITO
cCodigoValorClasifCliente1
cCodigoValorClasifCliente2
cCodigoValorClasifCliente3
cCodigoValorClasifCliente4
cCodigoValorClasifCliente5
cCodigoValorClasifCliente6
CTIPOCLIENTE
CESTATUS
CFECHABAJA
CFECHAULTIMAREVISION
CLIMITECREDITOCLIENTE
CDIASCREDITOCLIENTE
CBANEXCEDERCREDITO
CDESCUENTOPRONTOPAGO
CDIASPRONTOPAGO
CINTERESMORATORIO
CDIAPAGO
CDIASREVISION
CMENSAJERIA
CCUENTAMENSAJERIA
CDIASEMBARQUECLIENTE
cCodigoAlmacen
cCodigoAgenteVenta
cCodigoAgenteCobro
CRESTRICCIONAGENTE
CIMPUESTO1
CIMPUESTO2
CIMPUESTO3
CRETENCIONCLIENTE1
CRETENCIONCLIENTE2
cCodigoValorClasifProveedor1
cCodigoValorClasifProveedor2
cCodigoValorClasifProveedor3
cCodigoValorClasifProveedor4
cCodigoValorClasifProveedor5
cCodigoValorClasifProveedor6
CLIMITECREDITOPROVEEDOR
CDIASCREDITOPROVEEDOR
CTIEMPOENTREGA
CDIASEMBARQUEPROVEEDOR
CIMPUESTOPROVEEDOR1
CIMPUESTOPROVEEDOR2
CIMPUESTOPROVEEDOR3
CRETENCIONPROVEEDOR1
CRETENCIONPROVEEDOR2
CBANINTERESMORATORIO
CCOMVENTAEXCEPCLIENTE
CCOMCOBROEXCEPCLIENTE
CBANPRODUCTOCONSIGNACION
CSEGCONTCLIENTE1
CSEGCONTCLIENTE2
CSEGCONTCLIENTE3
CSEGCONTCLIENTE4
CSEGCONTCLIENTE5
CSEGCONTCLIENTE6
CSEGCONTCLIENTE7
CSEGCONTPROVEEDOR1
CSEGCONTPROVEEDOR2
CSEGCONTPROVEEDOR3
CSEGCONTPROVEEDOR4
CSEGCONTPROVEEDOR5
CSEGCONTPROVEEDOR6
CSEGCONTPROVEEDOR7
CTEXTOEXTRA1
CTEXTOEXTRA2
CTEXTOEXTRA3
CFECHAEXTRA
CIMPORTEEXTRA1
CIMPORTEEXTRA2
CIMPORTEEXTRA3
CIMPORTEEXTRA4
CBANDOMICILIO
CBANCREDITOYCOBRANZA
CBANENVIO
CBANAGENTE
CBANIMPUESTO
CBANPRECIO
CCOMVENTA
CCOMCOBRO
CFACTERC01
/MGW10002
MGW10011
CTIPOCATALOGO
CTIPODIRECCION
CNOMBRECALLE
CNUMEROEXTERIOR
CNUMEROINTERIOR
CCOLONIA
CCODIGOPOSTAL
CTELEFONO1
CTELEFONO2
CTELEFONO3
CTELEFONO4
CEMAIL
CDIRECCIONWEB
CPAIS
CESTADO
CCIUDAD
CTEXTOEXTRA
CMUNICIPIO
/MGW10011
MGW10005
cCodigoProducto
cNombreProducto
cTipoProducto
cFechaAltaProducto
cControlExistencia
cDescripcionProducto
cMetodoCosteo
cPesoProducto
cComVentaExcepProducto
cComCobroExcepProducto
cCostoEstandar
cMargenUtilidad
cStatusProducto
cNombreUnidadBase
cNombreUnidadCompra
cNombreUnidadVenta
cNombreUnidadNoConvertible
cFechaBaja
cImpuesto1
cImpuesto2
cImpuesto3
cRetencion1
cRetencion2
cNombrePadreCarac1
cNombrePadreCarac2
cNombrePadreCarac3
cCodigoValorClasif1
cCodigoValorClasif2
cCodigoValorClasif3
cCodigoValorClasif4
cCodigoValorClasif5
cCodigoValorClasif6
cSegContProducto1
cSegContProducto2
cSegContProducto3
cTextoExtra1
cTextoExtra2
cTextoExtra3
cFechaExtra
cImporteExtra1
cImporteExtra2
cImporteExtra3
cImporteExtra4
cPrecio1
cPrecio2
cPrecio3
cPrecio4
cPrecio5
cPrecio6
cPrecio7
cPrecio8
cPrecio9
cPrecio10
cBanUnidades
cBanCaracteristicas
cBanMetodoCosteo
cBanMaxMin
cBanPrecio
cBanImpuesto
cBanCodigoBarra
cBanComponente
cEsCuotaI2
cEsCuotaI3
cTipoPaque
/MGW10005
MGW10015
cCodigoProducto
cCantidadProducto
cNombreUnidadVenta
cCodigoValorCarac1
cCodigoValorCarac2
cCodigoValorCarac3
cTipoProducto
/MGW10015
MGW10004
cTipoProducto
cCodigoProductoPadre
cCodigoValorCarac1
cCodigoValorCarac2
cCodigoValorCarac3
/MGW10004
MGW10016
CCODIGOALMACEN
CCODIGOPRODUCTO
CCODIGOPRODUCTOPADRE
CEXISTENCIAMINBASE
CEXISTENCIAMAXBASE
CEXISTMINNOCONVERTIBLE
CEXISTMAXNOCONVERTIBLE
CZONA
CPASILLO
CANAQUEL
CREPISA
/MGW10016
MGW10014
CCODIGOPRODUCTO
CCODIGOCLIENTE
CPRECIOCOMPRA
CIDMONEDA
CCODIGOPRODUCTOPROVEEDOR
CNOMBREUNIDAD
/MGW10014

MGW10002
00000000
GARCIA GARCIA JOEL
09/14/2011




1
1
1
0.00000
0.00000
0






1
1
09/14/2011
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
REVOLUCION
324

EL VERGEL
38060






MEXICO
GUANAJUATO
CELAYA


MGW10002
00000000000010
AMEZOLA HERNANDEZ ALEJANDRO
09/05/2011




1
1
3
0.00000
0.00000
0






1
1
09/05/2011
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
AVENIDA TORRES LANDA
725

SAN JUANICO

4616152122

4614218551



MEXICO
GUANAJUATO
CELAYA


MGW10002
0000000000052
GOMEZ MONTA�O OSVALDO
10/26/2011




1
1
1
0.00000
0.00000
0






1
1
10/26/2011
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
MARIANA SUARTO
112

LAS INSURGENTES
38080
6172449





MEXICO
GUANAJUATO
CELAYA


MGW10002
000000000015501
TORRES TORRES JUAN CLEMENTE
09/15/2011




1
1
3
0.00000
0.00000
0






1
1
09/15/2011
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
FRAY SERVANDO TERESA DE MIER
323A

PADRE NIEVES
38357
4111568837

5541111438



MEXICO
GUANAJUATO
CORTAZAR


MGW10002
000000001521215
GARCIA TIRADO GERARDO
09/08/2011




1
1
1
0.00000
0.00000
0






1
1
09/08/2011
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
MADERO
52

LA LOMA
38700
4666640203

4661077158



MEXICO
GUANAJUATO
TARIMORO


MGW10002
000001152005
MALDONADO DIAZ MARIA GUADALUPE
09/09/2011




1
1
3
0.00000
0.00000
0






1
1
09/09/2011
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
AV. ARAUCARIAS
553

LOS PINOS
38020
6151829
4611435031




MEXICO
GUANAJUATO
CELAYA


MGW10002
0000156
HERNANDEZ CA�ADA JOSE JAVIER
02/13/2010




1
1
3
0.00000
0.00000
0






1
1
02/13/2010
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
A.L.M
42

SAN CAYETANO












MGW10002
0000K
GUERRERO RAYAS KARLA GABRIELA
09/11/2010




1
1
3
0.00000
0.00000
0






1
1
09/11/2010
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
PASEO DEL CAMPESTRE
203
A
CAMPESTRE
38000






MEXICO
GUANAJUATO
CELAYA


MGW10002
0000N
NAVA AVILA JAZDY LINEY
01/28/2010




1
1
3
0.00000
0.00000
0






1
1
01/28/2010
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
GUSTAVO CABRERA
103

FRACCIONAMIENTO VALLE DORADO
38240






MEXICO
GUANAJUATO
JUVENTINO R


MGW10002
0000NR
NAVA REYES ABRAHAM
08/15/2011




1
1
3
0.00000
0.00000
0






1
1
08/15/2011
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
18 DE MARZO
118

MARAVATIO DEL ENCINAL








GUANAJUATO
ENCINA

MARAVATIO DEL
MGW10002
0000P
SERRANO MARROQUI  PAOLA  KARINA
01/30/2010




1
1
1
0.00000
0.00000
0






1
1
01/30/2010
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
LUCIA  GARCIA
239
B
LAS INSURGENTES
38080






MEXICO
GUANAJUATO
CELAYA


MGW10002
0000W
RUIZ CORNEJO WALDEMAR
03/17/2011




1
1
1
0.00000
0.00000
1






1
1
03/17/2011
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
0
0
0
0
0
0
0.00000
0.00000
0
MGW10002
00016
VENTA MOSTRADOR
01/02/2010




1
1
3
0.00000
0.00000
1
UF





1
1
01/02/2010
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
0
0
0
1
0
0
0.00000
0.00000
0
MGW10002
00058412540
MENDEZ MORALES RAUL
10/06/2011




1
1
3
0.00000
0.00000
0






1
1
10/06/2011
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
ANDRES QUINTANA ROO
1134

GIRASOLES

4611514921





MEXICO
GUANAJUATO
CELAYA


MGW10002
000CO
INTERNET COMPUSTORE
01/06/2009




1
1
2
0.00000
0.00000
1






1
1
01/06/2009
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
047
















12/30/1899
0.00000
0.00000
0.00000
0.00000
0
0
0
1
0
0
0.00000
0.00000
0
MGW10002
000ICO
IVONNE (CORREGIDORA)
04/14/2009




1
1
2
0.00000
0.00000
1






1
1
04/14/2009
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
054
















12/30/1899
0.00000
0.00000
0.00000
0.00000
0
0
0
1
0
0
0.00000
0.00000
0
MGW10002
000L
RANGEL MORA MARIA DE LOURDES
05/31/2010
RAML851229MGT



1
1
3
0.00000
0.00000
1






1
1
05/31/2010
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
0
0
0
1
0
0
0.00000
0.00000
0
MGW10002
000S
INTERNET SAUZ
01/07/2010




1
1
2
0.00000
0.00000
1






1
1
01/07/2010
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
043
















12/30/1899
0.00000
0.00000
0.00000
0.00000
0
0
0
1
0
0
0.00000
0.00000
0
MGW10002
000SER
SERVICIO TECNICO
12/15/2010




1
1
2
0.00000
0.00000
1






1
1
12/15/2010
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
064
















12/30/1899
0.00000
0.00000
0.00000
0.00000
0
0
0
0
0
0
0.00000
0.00000
0
MGW10002
000Z
INTERNET CORREGIDORA
08/11/2009




1
1
2
0.00000
0.00000
1






1
1
08/11/2009
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
057
















12/30/1899
0.00000
0.00000
0.00000
0.00000
0
0
0
1
0
0
0.00000
0.00000
0
MGW10002
0092636249427
EQAICC  MEXICO S.A DE C.V
05/03/2008
EME020308LH5



1
1
3
0.00000
0.00000
0
UF





1
1
05/03/2008
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
AV FCO JUAREZ
815
101
PISO 1 COL LOS ANGELOS
38040






MEXICO
GUANAJUATO
CELAYA


MGW10002
01010
AGUILLEN PABLO
04/28/2011




1
1
3
0.00000
0.00000
0






1
1
04/28/2011
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
CALLE DOLORES TIBET Y CALZ DE GUADALUPE
8

LA VILLITA
38160
4131584296





MEXICO
GUANAJUATO
APASEO EL GDE


MGW10002
010203
HERNANDEZ RENTERIA RODOLFO
05/25/2011




1
1
1
0.00000
0.00000
0






1
1
05/25/2011
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
PICACHO
64

PRADERAS DE LA VENTA
38260
4612121545
4111084780
92*825493*1



MEXICO
GUANAJUATO
VILLAGRAN


MGW10002
012345656789
CARLOS MA. ELENA
05/12/2011




1
1
3
0.00000
0.00000
0






1
1
05/12/2011
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
0
0
0
0
0
0
0.00000
0.00000
0
MGW10002
090909
GARCIA GONZALEZ JOSE JAIME
06/02/2011




1
1
1
0.00000
0.00000
0






1
1
06/02/2011
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
TRES GUERRAS
303

CENTRO
38240
4121573525





MEXICO
GUANAJUATO
JUVENTINO ROSAS


MGW10002
100611
ENSAMBLES
06/10/2011




1
1
1
0.00000
0.00000
1






1
1
06/10/2011
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
0
0
0
0
0
0
0.00000
0.00000
0
MGW10002
100612
RODRIGUEZ CENTENO MERCEDES
08/29/2011




1
1
1
0.00000
0.00000
0






1
1
08/29/2011
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
MIGUEL HIDALGO
2

SAN ELIAS
38102
4611854834





MEXICO
GUANAJUATO
CELAYA


MGW10002
AAAG551210H88
ALBA ARREGUIN GERARDO
12/30/2011
AAAG551210H88



1
1
3
0.00000
0.00000
0






1
1
12/30/2011
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0

















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
OYAMEL
220

LOS LAURELES
38020






MEXICO
GUANAJUATO
CELAYA


MGW10002
AABK820425G333
ALMARAZ BARRERA KARINA
05/23/2009
AABK820425G33



1
1
1
0.00000
0.00000
0
UF





1
1
05/23/2009
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
JOSE MARIA ALFARO SUR
222

GOBERNADORES
38030






MEXICO
GUANAJUATO
CELAYA


MGW10002
AAC0406175U3
AGRO Y ACOLCHADOS SA DE CV
12/30/2011
AAC0406175U3



1
1
3
0.00000
0.00000
0






1
1
12/30/2011
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0

















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
ORQUIDEA
131

ROSALINDA
38060
4616140641





MEXICO
GUANANJUATO
CELAYA


MGW10002
AACG4701089N3
ALFARO CABRERA MARIA GUADALUPE ISABEL
03/07/2009
AACG4701089N3



1
1
3
0.00000
0.00000
0
UF





1
1
03/07/2009
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
AV. MADERO
414

CENTRO
38600






MEXICO
GUANAJUATO
ACAMBARO


MGW10002
AACJ47092442A
ABARCA CANCINO JAVIER ARNULFO
12/30/2011
AACJ47092442A



1
1
3
0.00000
0.00000
0






1
1
12/30/2011
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0

















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
HACIENDA DEL SOL
104

FRACC.  HACIENDA DEL SOL
38020
6175226





MEXICO
GUANAJUATO
CELAYA


MGW10002
AACJ571123643
DE ANDA CABRERA JAIME
02/05/2008
AACJ571123643



1
1
3
0.00000
0.00000
0
UF





1
1
02/05/2008
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
PASEO DEL BAJIO
113

JARDINES DE CELAYA
38080






MEXICO
GUANAJUATO
CELAYA


MGW10002
AACL680127AZ2
ANDRADE CUELLA JOSE LUIS
12/02/2010
AACL680127AZ2



1
1
3
0.00000
0.00000
0






1
1
12/02/2010
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
ALLENDE
11


38930







GUANAJUATO
SALVATIERRA


MGW10002
AADA621008RM7
ARANDA DELGADILLO ALEJANDRO JACOB
04/01/2008
AADA621008RM7



1
1
3
0.00000
0.00000
0
UF





1
1
04/01/2008
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
TEOTIHUACAN MZ.
512
LT10
CD.AZTECA 1SECC
55120







EDO.MEXICO
ECATEPEC DE MORELOS


MGW10002
AADH640831ID7
ARANDA DELGADILLO HECTOR SALVADOR
02/03/2010
AADH640831ID7



1
1
1
0.00000
0.00000
0






1
1
02/03/2010
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
NICOLAS BRAVO
835

CENTRO
38000
6137321





MEXICO
GUANAJUATO
CELAYA

CELAYA
MGW10002
AAGA870331PRO
ANDRADE GONZALEZ AURORA GERALDINE
03/25/2011
AAGA870331PRO



1
1
1
0.00000
0.00000
0
DI





1
1
03/25/2011
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
FRANCISCO I MADERO
0307A
A
ZONA CENTRO
38000







GUANAJUATO


CELAYA
MGW10002
AAGE7007044F8
AMADOR GASCA ERIC
12/30/2011
AAGE7007044F8



1
1
3
0.00000
0.00000
0






1
1
12/30/2011
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0

















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
AZTECAS
453

CENTRO
38000






MEXICO
GUANAJUATO
CELAYA


MGW10002
AAGL820121F97
ARANDA GONZALEZ LUZ ADRIANA
12/30/2011
AAGL820121F97



1
1
3
0.00000
0.00000
0






1
1
12/30/2011
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0

















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
ALVARO OBREGON
207
2DOPIS
ZONA CENTRO


38000




MEXICO
GUANAJUATO
CELAYA


MGW10002
AAHC640603H81
AMADOR HERMANDEZ  CARMEN SOFIA
12/29/2008
AAHC640603H81



1
1
3
0.00000
0.00000
0
UF





1
1
12/29/2008
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
GUILLERMO PRIETO
302

COL. ALAMEDA







MEXICO
GUANAJUATO
CELAYA


MGW10002
AAI070817SJ2
ARQUITECTURA AVALUOS INGENIERIA Y CONSTRUCCION SA DE CV
11/07/2011
AAI070817SJ2



1
1
3
0.00000
0.00000
0






1
1
11/07/2011
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
ALBINO GARCIA
802

ZONA DE ORO 1
38020
4611740930
92*884644*1




MEXICO
GUANAJUATO
CELAYA


MGW10002
AAIS771217BG5
ALMANZA JARALE�O SAUL
03/03/2010
AAIS771217BG5



1
1
3
0.00000
0.00000
0






1
1
03/03/2010
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
JUAN BAUTISTA MORALES
371

ZONA DE ORO l
38022
6147541





MEXICO
GUANAJUATO
CELAYA


MGW10002
AALI770225G69
ALVAREZ LOPEZ ISABEL
12/30/2011
AALI770225G69



1
1
3
0.00000
0.00000
0






1
1
12/30/2011
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0

















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
CERRADA MAR NEGRO
104

LOS SAUCES
38070
6143796





MEXICO
GUANAJUATO
CELAYA


MGW10002
AALJ541021338
ALVAREZ LEDESMA JAIME
11/04/2009
AALJ541021338



1
1
3
0.00000
0.00000
0
UF





1
1
11/04/2009
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
BLVD ADOLFO LOPEZ MATEOS
1007
OTE
DESP 311
38070






MEXICO
GUANAJUATO
CELAYA


MGW10002
AALR521022K30
ARANA LARA ROSA ELENA
12/30/2011
AALR521022K30



1
1
3
0.00000
0.00000
0






1
1
12/30/2011
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0

















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
AV LOS LAURELES
108

LOS LAURELES
38020
6140366





MEXICO
GUANAJUATO
CELAYA


MGW10002
AAMA951124H51
ACAL MEDINA ANA IRIS
03/02/2011
AAMA951124H51



1
1
3
0.00000
0.00000
0






1
1
03/02/2011
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
JUAREZ
100B

ZONA CENTRO
38300







GUANAJUATO


CORTAZAR
MGW10002
AAMJ690918G69
ARAMBURO MALDONADO JUAN ANTONIO
01/14/2010
AAMJ690918G69



1
1
1
0.00000
0.00000
0






1
1
01/14/2010
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
PIPILA
119

CENTRO
38070






MEXICO
GUANAJUATO
CELAYA


MGW10002
AAML760105F82
ANAYA MARTINEZ LUIS
06/17/2008
AAML760105F82



1
1
3
0.00000
0.00000
0
UF





1
1
06/17/2008
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
VALLE DEL CARMEN
113

VALLE RESIDENCIAL
38020







GTO
CELAYA


MGW10002
AAMM6003016M8
ALBARRAN MARTINEZ MAURICIO ANTONIO
03/10/2011
AAMM6003016M8



1
1
3
0.00000
0.00000
0






1
1
03/10/2011
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
SEIZO FURUYA
1005

GUANAJUATO
38010
6129124






GUANAJUATO


CELAYA
MGW10002
AAPF480529KY9
ALVAREZ PLATA  ING FRANCISCO
03/01/2011
AAPF480529KY9



1
1
3
0.00000
0.00000
0






1
1
03/01/2011
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
ALFONSO TRUEBA OLIVARES
204

VILLAS DEL PARAISO
38020






MEXICO
GUANJUATO
CELAYA


MGW10002
AAPY740925533
AYALA PEREZ YOLANDA
02/27/2010
AAPY740925533



1
1
3
0.00000
0.00000
0






1
1
02/27/2010
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
MONTES ESCANDINAVOS
104
PTE
LOMA BONITA
76118






MEXICO
QRO
QUERETARO


MGW10002
AAQJ771118V34
AYALA QUEZADA JORGE
04/14/2010
AAQJ771118V34



1
1
3
0.00000
0.00000
0






1
1
04/14/2010
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
VICENTE SUAREZ
6

NI�OS HEROES
38260
014111652504





MEXICO
GUANAJUATO
VILLAGRAN


MGW10002
AAR9501304U3
ABARROTERA ARREGUIN RIVERA S.A. DE C.V.
03/16/2010
AAR9501304U3



1
1
3
0.00000
0.00000
0






1
1
03/16/2010
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
AV. COSTITUYENTES OTE.
318

ZONA CENTRO

6139097





MEXICO
GUANAJUATO
CELAYA

CELAYA
MGW10002
AARC5912088I7
ARRASTIO ROLDAN MARIA CRISTINA
10/23/2009
AARC5912088I7



1
1
3
0.00000
0.00000
0
UF





1
1
10/23/2009
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
JUAN JOSE TORRES LANDA  105 LOCAL
2
3
FRACC DEL PARQUE
38010
6152002





MEXICO
GUANAJUATO
CELAYA


MGW10002
AARL871229AZ4
ALVAREZ RODRIGUEZ JOSE LUIS
02/02/2011
AARL871229AZ4



1
1
3
0.00000
0.00000
0






1
1
02/02/2011
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
CARR. LIBRE QUERETARO-CELAYA
KM
36
RANCHO LA PURISIMA
38170






GUANAJUATO
EL GRANDE
APASEO


MGW10002
AARR8111308H8
ALFARO RODRIGUEZ RAFAEL
11/03/2009
AARR8111308H8



1
1
3
0.00000
0.00000
0
UF





1
1
11/03/2009
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
PRIVADA BILBAO
111

CAMINO REAL
38024






MEXICO
GUANAJUATO
CELAYA


MGW10002
AASE8510207BA
AYALA SARMIENTO EVELYN ARISBETH
08/12/2010
AASE8510207BA



1
1
3
0.00000
0.00000
0






1
1
08/12/2010
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
FRANCISCO VILLA
502

COL. RESURECCION
38070
4616136300





MEXICO
GUANAJUATO
CELAYA

CELAYA
MGW10002
AAVL490316V55
ANAYA VILLAVICENCIO LUIS HERIBERTO
11/06/2008
AAVL490316V55



1
1
3
0.00000
0.00000
0
UF





1
1
11/06/2008
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
IGNACIO CENTENO
107

ZONA DE ORO I
38020






MEXICO
GUANAJUATO
CELAYA


MGW10002
AAVL510923Q60
ALCANTARA DEL VALLE LINO
06/10/2009
AAVL510923Q60



1
1
1
0.00000
0.00000
0
DIS





1
1
06/10/2009
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
PONCIANO ARRIAGA  ESQ  LOPEZ MATEOS
.

COL  EL ESFUERZO
92810






MEXICO
VERACRUZ
TUXPAN


MGW10002
ABR070809CP2
AFEL BIENES RAICES SA DE CV
06/30/2009
ABR070809CP2



1
1
3
0.00000
0.00000
0
UF





1
1
06/30/2009
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
HACIENDA SANTA MONICA
202

PRADERAS DE LA HACIENDA
38010






MEXICO
GUANAJUATO
CELAYA


MGW10002
ACO040902UR6
AK CORPORACION S. DE R.L DE C.V.
05/18/2010
ACO040902UR6



1
1
3
0.00000
0.00000
0






1
1
05/18/2010
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
URUGUAY
36

COL. LOMAS DE QUERETARO
76190
(442)1839426





MEXICO
QUERETARO
QUERETARO


MGW10002
ACO081209AG2
AVE CONSULTING  S.C
02/24/2009
ACO081209AG2



1
1
3
0.00000
0.00000
0
UF





1
1
02/24/2009
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
ALVARO OBREGON
505
A
CENTRO
38000






MEXICO
GUANAJUATO
CELAYA


MGW10002
ACT6808066SA
AUTOTRANSPORTES DE CARGA TRESGUERRAS S.A DE C.V
09/25/2009
ACT6808066SA



1
1
3
0.00000
0.00000
0
DIS





1
1
09/25/2009
10/07/2009
0.00000
15
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
060
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
1
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
CARR.INDUSTRIAL CELAYA-VILLAGRAN KM
3.570

LOC.ESTRADA S/N







MEXICO
GUANAJUATO
CELAYA


MGW10002
ADA961209RU3
AVISA DIVISION AGRICOLA, S.A. DE C.V.
12/06/2011
ADA961209RU3



1
1
3
0.00000
0.00000
0






1
1
12/06/2011
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0

















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
ISLA DE LA CONCEPCION
25 B

FRACC. PRADO VALLEJO
54170
4611200937





MEXICO
MEXICO
TLANEPANTLA


MGW10002
ADEO020422Q19
ASOCIACION PARA EL DESARROLLO EDUCATIVO INTEGRAL A.C.
04/15/2011
ADEO020422Q19



1
1
3
0.00000
0.00000
0






1
1
04/15/2011
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
BENITO JUAREZ
404

CENTRO
37000






MEXICO
GUANAJUATO
LEON

LEON
MGW10002
ADI830701389
ANUNCIOS EN DIRECTORIOS  S.A DE C.V
07/31/2008
ADI830701389



1
1
3
0.00000
0.00000
0
UF





1
1
07/31/2008
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
AV, LAS  FUENTES
706

LAS FUENTES
38040






MEXICO
GUANAJUATO
CELAYA


MGW10002
AEBE460615256
APPENDINI BARROSO ENRIQUE HUMBERTO
12/04/2008
AEBE460615256



1
1
1
0.00000
0.00000
0
DIS





1
1
12/04/2008
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
CARR. CELAYA-SALVATIERRA K.M
8.5









MEXICO
GUANAJUATO
CELAYA


MGW10002
AECA7308194I5
ARREGUIN CENTENO ADRIAN
09/19/2008
AECA7308194I5



1
1
1
0.00000
0.00000
0
DIS





1
1
09/19/2008
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
INSURGENTES
14

SAN JOSE AGUA AZUL
38191






MEXICO
GDE
APASEO  EL


MGW10002
AECA750710NWA
ARTEAGA CERVANTES ANA LAURA
06/09/2009
AECA750710NWA



1
1
1
0.00000
0.00000
0
DIS





1
1
06/09/2009
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
C
5

ENRIQUE COLUNGA
38067






MEXICO
GUANAJUATO
CELAYA


MGW10002
AECC280517HT8
ARREGUIN CENTENO CELESTINA
02/18/2010
AECC280517HT8



1
1
3
0.00000
0.00000
0






1
1
02/18/2010
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
CARR.CELAYA-VILLAGRAN K.M
3.57

LOC.ESTRADA
38110






MEXICO
GUANAJUATO
CELAYA


MGW10002
AECD901108UF5
ARREDONDO CORONA DANIEL
01/13/2011
AECD901108UF5



1
1
3
0.00000
0.00000
0






1
1
01/13/2011
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
MIGUEL ALEMAN
101

TRES GUERRAS
38080
6132837





MEXICO
GUANAJUATO
CELAYA


MGW10002
AEDC54020515A
ARCE DUPOND CARLOS FELIPE
02/26/2009
AEDC54020515A



1
1
3
0.00000
0.00000
0
UF





1
1
02/26/2009
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
TINTORETO
207

4 SECC.ARBOLEDAS
38060






MEXICO
GUANAJUATO
CELAYA


MGW10002
AEFF460712DZ6
ARREGUIN FIGUEROA JOSE FELIX
08/27/2011
AEFF460712DZ6



1
1
3
0.00000
0.00000
0






1
1
08/27/2011
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
BOULEVARD ADOLFO LOPEZ MATEOS
1000

COL. RESIDENCIAL
38060
6155262





MEXICO
GUANAJUATO
CELAYA

CELAYA
MGW10002
AEGM730308SW6
ARMENTA GAMBOA MIRNA DE JESUS
08/08/2008
AEGM730308SW6



1
1
1
0.00000
0.00000
0
DIS





1
1
08/08/2008
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
CAMINO DE LAS TORRES
209

EL CAMPANARIO
38010
61 53962





MEXICO
GUANAJUATO
CELAYA


MGW10002
AELC751202P1A
ALEJO LUGO CARLOS GERARDO
08/24/2009
AELC751202P1A



1
1
1
0.00000
0.00000
0
DIS





1
1
08/24/2009
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
JOJUTLA
115

EMILIANO ZAPATA







MEXICO
GUANAJUATO
CELAYA


MGW10002
AEMA6902276D9
ARENAS MU�OS ALBERTO
01/18/2011
AEMA6902276D9



1
1
3
0.00000
0.00000
0






1
1
01/18/2011
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
RIO MAGDALENA MANZANA
19

LOTE 21 COL SAN BLAS 1
54870







EDO  MEXICO
COAHUTITLAN


MGW10002
AEMA731121AY4
ARREGUIN MORENO JOSE ANTONIO
06/23/2010
AEMA731121AY4



1
1
3
0.00000
0.00000
0






1
1
06/23/2010
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
AZTECAS
624
8
LOS  ANGELES
38040
6093374





MEXICO
GUANAJUATO
CELAYA


MGW10002
AEMJ630208JM2
ARREGUIN MORENO J. GUADALUPE SAMUEL
08/06/2010
AEMJ630208JM2



1
1
3
0.00000
0.00000
0






1
1
08/06/2010
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
PLATA
105

ZONA DE ORO ll
38020






MEXICO
GUANAJUATO
CELAYA


MGW10002
AEMJ640407J34
ARREGUIN MOLINA JOSE
06/25/2010
AEMJ640407J34



1
1
1
0.00000
0.00000
0






1
1
06/25/2010
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
FLORENCIO ANTILLON
108

CENTRO
38000






MEXICO
GUANAJUATO
CELAYA


MGW10002
AEMJ831222RF2
ACEVEDO MAGA�A MARIA JOSE LISETH
05/17/2010
AEMJ831222RF2



1
1
1
0.00000
0.00000
0






1
1
05/17/2010
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
AV. IRRIGACION
101

BENITO JUAREZ
38030






MEXICO
GUANAJUATO
CELAYA


MGW10002
AEPG280420MS6
ALMEIDA PONCE GUSTAVO
03/19/2009
AEPG280420MS6



1
1
3
0.00000
0.00000
0
UF





1
1
03/19/2009
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
MARSELLA
14

JUAREZ
06600






D.F
MEXICO
DEL.CUAUHTEMOC


MGW10002
AEPK881121D39
ARREDONDO PRECIADO KARLA PATRICIA
05/02/2011
AEPK881121D39



1
1
1
0.00000
0.00000
0






1
1
05/02/2011
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
0
0
0
0.00000
0.00000
0
MGW10011
1
0
GUADALUPE VICTORIA
202
B
CENTRO
38000
6120168



PALMARNACIONAL@GMAIL.COM

MEXICO
GUANAJUATO
CELAYA

CELAYA
MGW10002
AERJ7004276PA
ARREGUIN RUIZ DAVID
02/10/2010
AERJ7004276PA



1
1
3
0.00000
0.00000
0






1
1
02/10/2010
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
CIRCUITO DEL TRUENO
162
A
PASEO DEL CAMPESTRE
38088






MEXICO
GUANAJUATO
CELAYA


MGW10002
AERM640827FE7
ARREDONDO ROSALES MARTIN
01/25/2010
AERM640827FE7



1
1
1
0.00000
0.00000
0






1
1
01/25/2010
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
10 D EMAYO
406

BARRIO DE SAN MIGUEL
38060
61 329 55





MEXICO
GUANAJUATO
CELAYA


MGW10002
AERR670128BD8
ARENAS  ROSILLO RODRIGO
01/19/2008
AERR670128BD8



1
1
3
0.00000
0.00000
0
UF





1
1
01/19/2008
12/30/1899
0.00000
0
0
0.00000
0
0.00000
31
31


31



0
0.00000
0.00000
0.00000
0.00000
0.00000






0.00000
0
0
31
0.00000
0.00000
0.00000
0.00000
0.00000
0
0.00000
0.00000
0
024
















12/30/1899
0.00000
0.00000
0.00000
0.00000
1
0
0
1
0
0
0.00000
0.00000
0
MGW10011
1
0
MORELOS
106

URIREO
38901






MEXICO
GUANAJUATO
SALVATIERRA


";

				ClientesController::ImportarClientes( $raw_exportation );

			
		}
	}

