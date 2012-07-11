<?php
require_once("interfaces/Documentos.interface.php");





/**
  *
  *
  *
  **/
	
  class DocumentosController implements IDocumentos{
  
  
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
		/* @TODO $nDoc->setFoliado($foliado); */
		$nDoc->setIdEmpresa($id_empresa);
		$nDoc->setIdSucursal($id_sucursal);
		$nDoc->setUltimaModificacion(time());


		

		try{

			DocumentoBaseDAO::save( $nDoc );

		}catch(Exception $e){
			throw new InvalidDatabaseOperationException ($e);
		}


		return array("id_documento_base" => $nDoc->getIdDocumentoBase() );

	}

 
  }
