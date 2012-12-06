<?php
require_once("interfaces/POS.interface.php");
/**
  *
  *
  * @author Alan Gonzalez <alan@caffeina.mx>
  **/
	
  class POSController implements IPOS{



	/**
 	 *
 	 *Si un perdidad de conectividad sucediera, es responsabilidad del cliente registrar las ventas o compras realizadas desde que se perdio conectividad. Cuando se restablezca la conexcion se deberan enviar las ventas o compras. 
 	 *
 	 * @param compras json Objeto que contendr la informacin de las compras as como su detalle.
 	 * @param ventas json Objeto que contendr la informacin de las ventas as como su detalle.
 	 * @return id_compras json Arreglo de ids generados por las inserciones de compras si las hay
 	 * @return id_ventas json Arreglo de ids generados por las inserciones de ventas si las hay
 	 **/
	public static function EnviarOffline
	(
		$compras = null, 
		$ventas = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Gerenra y /o valida un hash
 	 *
 	 **/
	public static function Hash
	(
	)
	{  
  
  
	}
  



	/**
 	 *
 	 *Busca en el erp
 	 *
 	 * @param query string 
 	 * @return numero_de_resultados int 
 	 * @return resultados json 
 	 **/
  static function Buscar
	(
		$query
	){
		
		$out = array();
		

		//buscar clientes
		$c = ClientesController::Buscar(
			null, 
			null, 
			5000, 
			null, 
			$query);
		
		
		foreach($c["resultados"] as $cliente){
			array_push( $out, array(
				"texto" => $cliente["nombre"],
				"rfc" => $cliente["rfc"],				
				"id" => $cliente["id_usuario"],
				"tipo"	=> "cliente"
			));
		}
		

		

		//buscar productos
		$p = ProductosController::Buscar($query);
		
		foreach($p["resultados"] as $cliente){
			array_push( $out, array(
				"texto" => $cliente["nombre_producto"],
				"id" => $cliente["id_producto"],
				"tipo"	=> "producto"
			));
		}
		
		
		//buscar servicios
		$s = ServiciosController::Buscar($query);
		
		foreach($s["resultados"] as $cliente){
			array_push( $out, array(
				"texto" => $cliente["nombre_servicio"],
				"id" => $cliente["id_servicio"],
				"tipo"	=> "servicio"
			));
		}

		
		/*
		array_push($out, array(
			"texto" => "&iquest; Como crear un cliente ?",
			"id" => 0,
			"tipo"	=> "Ayuda"
		));
		*/

		
		return array("numero_de_resultados" => (sizeof($c) + sizeof($p) + sizeof($s)),
						"resultados" => $out);
		
	}


	/**
 	 *
 	 *Cuando un cliente pierde comunicacion se lanzan peticiones a intervalos pequenos de tiempo para revisar conectividad. Esos requests deberan hacerse a este metodo para que el servidor se de cuenta de que el cliente perdio conectvidad y tome medidas aparte como llenar estadistica de conectividad, ademas esto asegurara que el cliente puede enviar cambios ( compras, ventas, nuevos clientes ) de regreso al servidor. 
 	 *
 	 **/
	public static function ConexionProbar
	(
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Si el cliente lo desea puede respaldar toda su informacion personal. Esto descargara la base de datos y los documentos que se generan automaticamente como las facturas. Para descargar la base de datos debe tenerse un grupo de 0 o bien el permiso correspondiente.
 	 *
 	 **/
	public static function RespaldarBd
	(
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Revisar la version que esta actualmente en el servidor. 
 	 *
 	 **/
	public static function VersionClientCurrentCheckClient
	(
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Descargar un zip con la ultima version del cliente.
 	 *
 	 **/
	public static function DownloadClient
	(
	)
	{  
  		
  
	}
  
	/**
 	 *
 	 *Metodo que elimina todos los registros en la base de datos, especialmente util para hacer pruebas unitarias. Este metodo NO estara disponible al publico.
 	 *
 	 **/
	public static function DropBd
	(
	)
	{  



		Logger::warn("TRUNCANDO LA BASE DE DATOS !");

		global $conn;


  		$conn->Execute( "TRUNCATE TABLE `abono_venta`;"  );

  		$conn->Execute( "TRUNCATE TABLE `rol`;"  );
		
		$conn->Execute( "INSERT INTO `rol` (`id_rol`, `nombre`, `descripcion`, `salario`, `id_tarifa_compra`, `id_tarifa_venta`) VALUES ('5', 'Clientes', 'Clientes', NULL, 0, 0);");
		
  		$conn->Execute( "TRUNCATE TABLE `usuario`; "  );

  		$conn->Execute( "TRUNCATE TABLE `almacen`; "  );

  		$conn->Execute( "TRUNCATE TABLE `empresa`; "  );

  		$conn->Execute( "TRUNCATE TABLE `clasificacion_cliente`; "  );

  		$conn->Execute( "TRUNCATE TABLE `sucursal`; "  );

  		$conn->Execute( "TRUNCATE TABLE `tipo_almacen`; "  );

  		$conn->Execute( "TRUNCATE TABLE `producto`; "  );
                
		$conn->Execute( "TRUNCATE TABLE `servicio`; "  );

  		$conn->Execute( "TRUNCATE TABLE `sesion`; "  );
                
		$conn->Execute( "TRUNCATE TABLE `clasificacion_producto`; " );
                
		$conn->Execute( "TRUNCATE TABLE `clasificacion_servicio`; " );

  		$conn->Execute( "TRUNCATE TABLE `ciudad`; "  );

  		$conn->Execute( "TRUNCATE TABLE `direccion`; "  );

  		$conn->Execute( "INSERT INTO `usuario` (`id_usuario`, `id_direccion`, `id_direccion_alterna`, `id_sucursal`, `id_rol`, `id_clasificacion_cliente`, `id_clasificacion_proveedor`, `id_moneda`, `fecha_asignacion_rol`, `nombre`, `rfc`, `curp`, `comision_ventas`, `telefono_personal1`, `telefono_personal2`, `fecha_alta`, `fecha_baja`, `activo`, `limite_credito`, `descuento`, `password`, `last_login`, `consignatario`, `salario`, `correo_electronico`, `pagina_web`, `saldo_del_ejercicio`, `ventas_a_credito`, `representante_legal`, `facturar_a_terceros`, `dia_de_pago`, `mensajeria`, `intereses_moratorios`, `denominacion_comercial`, `dias_de_credito`, `cuenta_de_mensajeria`, `dia_de_revision`, `codigo_usuario`, `dias_de_embarque`, `tiempo_entrega`, `cuenta_bancaria`) VALUES (1, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2011-10-24 18:28:24', 'Alan Gonzalez', NULL, NULL, NULL, NULL, NULL, '2011-10-24 18:28:34', NULL, 1, 0, NULL, '202cb962ac59075b964b07152d234b70', NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL);") ;
		
		$conn->Execute( "INSERT INTO `ciudad` (`id_ciudad`, `id_estado`, `nombre`) VALUES
							(1, 11, 'Abasolo'),
							(2, 11, 'Acambaro'),
							(3, 11, 'Apaseo el Alto'),
							(4, 11, 'Apaseo el Grande'),
							(5, 11, 'Atarjea'),
							(6, 11, 'Celaya'),
							(7, 11, 'Comonfort'),
							(8, 11, 'Coroneo'),
							(9, 11, 'Cortazar'),
							(10, 11, 'Cueramaro'),
							(11, 11, 'Doctor Mora'),
							(12, 11, 'Dolores Hidalgo'),
							(13, 11, 'Guanajuato'),
							(14, 11, 'Huanimaro'),
							(15, 11, 'Irapuato'),
							(16, 11, 'Jaral del Progreso'),
							(17, 11, 'Jerecuaro'),
							(18, 11, 'Leon'),
							(19, 11, 'Manuel Doblado'),
							(20, 11, 'Moroleon'),
							(21, 11, 'Ocampo'),
							(22, 11, 'Penjamo'),
							(23, 11, 'Pueblo Nuevo'),
							(24, 11, 'Purisima del Rincon'),
							(25, 11, 'Romita'),
							(26, 11, 'Salamanca'),
							(27, 11, 'Salvatierra'),
							(28, 11, 'San Diego de la Union'),
							(29, 11, 'San Felipe'),
							(30, 11, 'San Francisco del Rincon');");
	}
        
        
        /**
 	 *
 	 *Este metodo se utiliza para poder enviar un correo electronico a un tercero. 
 	 *
 	 * @param cuerpo string El cuerpo del correo electronico.
 	 * @param destinatario string El correo electronico a enviar.
 	 * @param titulo string El titulo del correo electronico. 
 	 * @param emisor string El correo electronico de donde se enviara este correo si es que esta configurado para esta instancia. De no enviarse se enviara un correo de la cuenta default de POS ERP.
 	 **/
        public static function EnviarMail
	(
		$cuerpo, 
		$destinatario, 
		$titulo, 
		$emisor = "no-reply@caffeina.mx"
	)
    {
			/*if(!is_file("Mail.php"))	{
				Logger::error("no esta isntalado mail.php ");
				return;
			}*/

			@require_once "Mail.php";
			
			
	
			if(!defined("MAIL")){
				Logger::error("Se intento enviar un correo a ". $destinatario ." pero la configuracion de MAIL no esta definida.");
				return;				
			}
			
			
			if(!MAIL) {
				Logger::error("Se intento enviar un correo a ". $destinatario ." pero la configuracion de MAIL esta apagada.");
				return;	
			}
		
			Logger::log("Enviando correo electronico...");

			Logger::log("	FROM:" . MAIL_FROM);
			Logger::log("	TO:" . $destinatario);
			Logger::log("	SUBJECT:" . $titulo);

									
			$headers = array (
					'From' 	=> MAIL_FROM,
					'To' 	=> $destinatario,
					'Subject' => $titulo
				);

			$smtp = @Mail::factory('smtp',
				array ('host' 	=> MAIL_HOST,
					'port' 		=> MAIL_PORT,
					'auth' 		=> true,
					'username' 	=> MAIL_USERNAME,
					'password' 	=> MAIL_PASSWORD));


			$mail = @$smtp->send($destinatario, $headers, $cuerpo);
		
			
			if(@PEAR::isError($mail)){
				Logger::error(" ***** Error al enviar el correo... ***** " );
				Logger::error($mail->getMessage());
				throw new Exception($mail->getMessage());
			}
			

			Logger::log("Correo enviado correctamente a " . $destinatario);
    }



    
    public static function EliminarColumnaBd
    (
    	$campo, 
		$tabla
	){
		Logger::log("Eliminando campo $campo, de tabla $tabla");

    	$r = ExtraParamsEstructuraDAO::getByTablaCampo($tabla, $campo);

    	if(is_null($r)) throw new InvalidDataException("No existe tal campo en tal tabla");


    	ExtraParamsEstructuraDAO::delete($r);

    }

  
    
	/**
 	 *
 	 *editar una columna dado su campo y tabla
 	 *
 	 * @param campo string 
 	 * @param tabla string 
 	 * @param caption string 
 	 * @param descripcion string 
 	 * @param longitud int 
 	 * @param obligatorio bool 
 	 * @param tipo enum "string","int","float","date","bool","contacto","enum","direccion"
 	 **/
  	static function EditarColumnaBd
	(
		$campo, 
		$tabla, 
		$caption = "", 
		$descripcion = "", 
		$longitud = "", 
		$obligatorio = "", 
		$tipo = ""
	){

		//busquemos que exista la tabla
    	switch($tabla){
    		case "clientes":

    		break;

    		default:
    			throw new InvalidDataException("No puede crear una nueva columna para la tabla $tabla"); 
    	}



    	$ncbd = ExtraParamsEstructuraDAO::getByTablaCampo($tabla, $campo);

    	if(is_null($ncbd)){
    		throw new InvalidDataException("La combinacion $tabla, $campo no existe.");
	   	}




    	//$ncbd->setTabla($tabla);
		$ncbd->setCampo($campo);
		$ncbd->setTipo($tipo);
		$ncbd->setLongitud($longitud);
		$ncbd->setObligatorio($obligatorio);
		$ncbd->setCaption($caption);
		$ncbd->setDescripcion($descripcion);

  		
    	//insertar
		ExtraParamsEstructuraDAO::save( $ncbd );
		
	}
  


    public static function NuevaColumnaBd
    (
		$campo, 
		$caption, 
		$obligatorio, 
		$tabla, 
		$tipo, 
		$descripcion = "", 
		$longitud = ""
    )
    {

    	//busquemos que exista la tabla
    	switch($tabla){
    		case "clientes":

    		break;

    		default:
    			throw new InvalidDataException("No puede crear una nueva columna para la tabla $tabla"); 
    	}

    	//veamos que no exista la combinacion de tabla-campo


    	//validar tipo y longitud


    	//crear objeto
    	$ncbd = new ExtraParamsEstructura();
    	
    	  
		$ncbd->setTabla($tabla);
		$ncbd->setCampo($campo);
		$ncbd->setTipo($tipo);
		$ncbd->setLongitud($longitud);
		$ncbd->setObligatorio($obligatorio);
		$ncbd->setCaption($caption);
		$ncbd->setDescripcion($descripcion);

  		

    	//insertar
    	try{
			ExtraParamsEstructuraDAO::save( $ncbd );
		}catch(Exception $e){
			throw new InvalidDataException("El campo `". $campo ."` ya existe en esta tabla.");
		}
		
		return array("id_columna" =>  $ncbd->getIdExtraParamsEstructura() );

    }

	/**
 	 *
 	 *Restaurar una BD especifica, a partir de un listado de archivos.
 	 *
 	 * @param id_instancia int Id de la instancia que se requiere restaurar
 	 * @param time int Fecha de creacin del archivo
 	 * @return status string Estado de la respuesta
 	 **/
  public static function EspecificaBdRestaurarBd
	(
		$id_instancia, 
		$time
	){                
                       return array("status" => "ok");
          }


	/**
 	 *
 	 *Genera un scrip .sql en el servirdor de los id de instancia que reciba este metodo
 	 *
 	 * @param instance_ids json Lista de los id de las instancias a respaldar
 	 * @return status string Respuesta enviada del servidor
 	 * @return mensaje string Mensaje de respuesta del servidor
 	 **/
  public static function BdInstanciasRespaldarBd($instance_ids){
  		$c = prin_r($instance_ids,true);
  		//$res = InstanciasController::Respaldar_Instancias($instance_ids);
  		return array("status" => "ok", "mensaje"=>"entrada en server: {$c}");
  }

	
  }
