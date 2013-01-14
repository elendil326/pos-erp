<?php

require_once("interfaces/Documentos.interface.php");

/**
  *
  *
  *
  **/
	
  class DocumentosController implements IDocumentos{

  	private static function GetUsuarioArray($id_usuario){

		$result = UsuarioDAO::getByPK( $id_usuario )->asArray();

		if(!is_null($result["id_direccion"])){
			$result["direccion"] = DireccionDAO::getByPK($result["id_direccion"])->asArray();
			unset($result["direccion"]["id_direccion"]);

			if(!is_null($result["direccion"]["id_ciudad"])){
				$result["direccion"]["ciudad"] = CiudadDAO::getByPK($result["direccion"]["id_ciudad"])->asArray();
				unset($result["direccion"]["ciudad"]["id_ciudad"]);
			}

			unset($result["direccion"]["id_ciudad"]);
		}

		if(!is_null($result["id_direccion_alterna"])){
			$result["direccion_alterna"] = DireccionDAO::getByPK($result["id_direccion_alterna"])->asArray();
		}


		if(!is_null($result["id_rol"])){
			if(!is_null($r = RolDAO::getByPK($result["id_rol"]))){
				$result["rol"] = $r->asArray();	
			}
			unset($result["id_rol"]);
		}

		

		unset($result["password"]);
		unset($result["id_direccion_alterna"]);
		unset($result["id_direccion"]);
		unset($result["id_usuario"]);
		unset($result["fecha_asignacion_rol"]);
		unset($result["token_recuperacion_pass"]);

		unset($result["id_clasificacion_proveedor"]);
		unset($result["id_clasificacion_cliente"]);
		unset($result["comision_ventas"]);
		unset($result["last_login"]);
		unset($result["consignatario"]);
		unset($result["salario"]);
		unset($result["saldo_del_ejercicio"]);
		unset($result["ventas_a_credito"]);
		unset($result["dia_de_pago"]);
		unset($result["mensajeria"]);
		unset($result["dias_de_embarque"]);
		unset($result["id_tarifa_compra"]);
		unset($result["tarifa_compra_obtenida"]);
		unset($result["id_tarifa_venta"]);
		unset($result["tarifa_venta_obtenida"]);
		unset($result["facturar_a_terceros"]);

		return $result;

  	}
















  	private static function GetCerrarVentaParams($id_venta){

  		$ventaVo = VentaDAO::getByPK( $id_venta );
  		$resultArray = $ventaVo->asArray();

		//buscar su cliente
		$result["cliente"] = self::GetUsuarioArray( $ventaVo->getIdCompradorVenta() );

		//agente de venta
		$result["agente"] = self::GetUsuarioArray( $ventaVo->getIdUsuario() );


  		//buscar sus productos y ordenes
		$result["contenido"] = array_merge( 
					VentaProductoDAO::search(new VentaProducto(array("id_venta" =>  $id_venta))),
					VentaOrdenDAO::search(new VentaOrden(array("id_venta" => $id_venta )))
				 );
  		

  		//direccion

  		//buscar su agente

  		//buscar sucursal

  		//buscar empresa


		return $result;
  	}



  	private static function flattenArray($multiLevelArray, $parentName = NULL){

  		foreach ($multiLevelArray as $key => $value) {

  			if(is_array( $value ) ){

  				$flatted = self::flattenArray( $value, $key );

  				unset( $multiLevelArray[$key] );

  				$multiLevelArray = array_merge ( $multiLevelArray, $flatted );

  			}else if(!is_null($parentName)){

				$multiLevelArray[ $parentName . "->" . $key ] = $value;

				unset( $multiLevelArray[$key] );

  			}
  		}
	
  		return $multiLevelArray;
  	}




  	public static function Cerrar($id_documento, $params){


//		$params["hola1"] = array("hola2" => array( "hola3" => 333));
  		
  		var_dump($params);


  		$params = self::flattenArray($params);

		var_dump($params);

  		exit;
  		$params = self::GetCerrarVentaParams($params["id_venta"]);

  		ImpresionesController::Documento($id_documento, FALSE , $params );
  	}

	/**
 	 *
 	 *Lista los documentos en el sistema. Se puede filtrar por activos y por la empresa. Se puede ordenar por sus atributos
 	 *
 	 * @param activos bool Si no se obtiene este valor, se listaran los documentos activos e inactivos. Si su valor es true, mostrara solo los documentos activos, si es false, mostrara solo los documentos inactivos.
 	 * @param id_empresa int Id de la empresa de la cual se tomaran sus documentos.
 	 * @param nombre string Buscar por nombre
 	 * @return resultados json Objeto que contendr la informacin de los documentos.
 	 * @return numero_de_resultados int 
 	 **/
  public static function Buscar
	(
		$activos = "", 
		$id_empresa = null, 
		$nombre = null
	){

		$q = DocumentoBaseDAO::search( new DocumentoBase( array( "nombre" => $nombre ) ) );

		return array(
			"resultados" => $q,
			"numero_de_resultados" => sizeof($q));

	}

  	
  
  
	
  
	/**
 	 *
 	 *Update : Falta indicar en los argumentos el si el documeto esta activo y a que sucursal pertenece.
 	 *
 	 * @param id_documento int Id del documento a editar.
 	 * @param activo bool 
 	 * @param foliado json 
 	 * @param id_empresa int 
 	 * @param id_sucursal int 
 	 * @param json_impresion string 
 	 * @param nombre string 
 	 **/
  public static function Editar
	(
		$id_documento, 
		$activo = null, 
		$foliado = null, 
		$id_empresa = null, 
		$id_sucursal = null, 
		$json_impresion = null, 
		$nombre = null
	){


		$nDoc = DocumentoBaseDAO::getByPK($id_documento);

		if( is_null($nDoc) ) throw new InvalidDataException("El documento a editar no existe.");

		if(!is_null($json_impresion)){
			$nDoc->setJsonImpresion( json_encode($json_impresion));	
		}

		if(!is_null($nombre)){
			$nDoc->setNombre($nombre);
		}


		if(!is_null($activo)){
			$nDoc->setActivo($activo);
		}
		
		
		if(!is_null($id_empresa)){
			$nDoc->setIdEmpresa($id_empresa);
		}

		if(!is_null($id_sucursal)){
			$nDoc->setIdSucursal($id_sucursal);
		}


		$nDoc->setUltimaModificacion(time());


		

		try{

			DocumentoBaseDAO::save( $nDoc );

		}catch(Exception $e){
			throw new InvalidDatabaseOperationException ($e);
		}


		
	}

  
  
  
	
  
	/**
 	 *
 	 *Cancela una factura.
 	 *
 	 * @param id_folio int Id de la factura a eliminar
 	 **/
  public static function CancelarFactura
	(
		$id_folio
	){


	}

  
  
  
	
  
	/**
 	 *
 	 *Genera una factura seg?n la informaci?n de un cliente y la venta realizada.

Update : Falta especificar si seria una factura detallada (cuando en los conceptos de la factura describe a cada articulo) o generica (un solo concepto que engloba a todos los productos).
 	 *
 	 * @param id_cliente int Id del cliente al cual se le va a facturar
 	 * @param id_venta int Id de la venta sobre la cual se facturara
 	 * @return id_folio int Id de la factura generada
 	 **/
  public static function GenerarFactura
	(
		$id_cliente, 
		$id_venta
	){

	}



	/**
	 *
	 *Imprime una factura
Update : La respuesta solo deber?a de contener success :true | false, y en caso de error, su descripcion, no se necesita apra anda en el JSON de respuesta una propiedad factura.
 	 *
 	 * @param id_folio int Id de la factura que se desea imprimir.
 	 * @return factura json Objeto con la informacion de la factura
 	 **/
  public static function ImprimirFactura
	(
		$id_folio
	){


	}



	/**
	  * crear una nuva instancia de un documento base
	  */
	public static function NuevaInstancia(){



	}



	/**
 	 *
 	 *Crea un nuevo documento.

 	 *
 	 * @param json_impresion json El json que se utilizara para imprimir este documento.
 	 * @param nombre string Nombre del documento
 	 * @param activo bool Si esta activo o si no se puede realizar documentos de este tipo.
 	 * @param foliado json El json que describe como sera el foliado de este documento. Incluye en que folio va.
 	 * @param id_empresa int Si pertence a una empresa en especifico, o puede realizarse en cualquier empresa.
 	 * @param id_sucursal int Si pertenece a una sucursal en especifico o puede realizarse en cualquier sucursal.
 	 * @return id_documento int Id del nuevo documento
 	 **/
  public static function Nuevo
	(
		$json_impresion, 
		$nombre, 
		$activo =  1 ,
		$extra_params = null, 
		$foliado = null, 
		$id_empresa = null, 
		$id_sucursal = null
	){

		if(is_null($json_impresion)){
			throw new InvalidDataException("El json de impresion no es valido.");
		}

		$q = DocumentoBaseDAO::search( new DocumentoBase( array( "nombre" => $nombre ) ) );

		if(sizeof($q) > 0 ) throw new InvalidDataException("Ya existe un documento con este nombre.");

		$nDoc = new DocumentoBase();
		$nDoc->setJsonImpresion( json_encode($json_impresion));
		$nDoc->setNombre($nombre);
		$nDoc->setActivo($activo);
		$nDoc->setIdEmpresa($id_empresa);
		$nDoc->setIdSucursal($id_sucursal);
		$nDoc->setUltimaModificacion(time());

		try{
			DocumentoBaseDAO::save( $nDoc );

		}catch(Exception $e){
			throw new InvalidDatabaseOperationException ($e);

		}

		if ( !is_null($extra_params) ) {
			for ( $i = 0; $i < sizeof($extra_params); $i++ ){

				//test
				if( !isset( $extra_params[$i]->obligatory ) ){
					 $extra_params[$i]->obligatory = FALSE;
				}

				if( is_null( $extra_params[$i]->obligatory ) ){
					 $extra_params[$i]->obligatory = FALSE;
				}

				$paramStruct = new ExtraParamsEstructura();
				$paramStruct->setTabla("documento_base");
				$paramStruct->setCampo( $nDoc->getIdDocumentoBase( ) );
				$paramStruct->setTipo( $extra_params[$i]->type );
				$paramStruct->setLongitud( 256 );
				$paramStruct->setObligatorio($extra_params[$i]->obligatory );
				$paramStruct->setCaption( $extra_params[$i]->desc );
				$paramStruct->setDescripcion( $extra_params[$i]->desc  );

				try{
					ExtraParamsEstructuraDAO::save( $paramStruct  );

				}catch(Exception $e){
					Logger::error($e);
					//DAO::transEnd();
					throw new Exception( $e );

				}
			}
		}



		return array("id_documento_base" => $nDoc->getIdDocumentoBase() );
	}

}
