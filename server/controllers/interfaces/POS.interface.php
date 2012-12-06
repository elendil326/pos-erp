<?php
/**
  *
  *
  *
  **/
	
  interface IPOS {
  
  
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
	);  
  
  
	
  
	/**
 	 *
 	 *Eliminar una columna extra
 	 *
 	 * @param campo string 
 	 * @param tabla string 
 	 **/
  static function EliminarColumnaBd
	(
		$campo, 
		$tabla
	);  
  
  
	
  
	/**
 	 *
 	 *No la agrega fisicamente a la BD pero fuera del API se puede asumir que si.
 	 *
 	 * @param campo string un id que identifique este campo. No puede haber 2 campos iguales para una misma tabla.
 	 * @param caption string El texto que se mostrara al pedir este campo.
 	 * @param obligatorio bool Si el campo es obligatorio para esta tabla.
 	 * @param tabla string La tabla padre. La tabla debe existir fisicamente salvo unas tablas que hemos creado para usted.
 	 * @param tipo enum "string","int","float","date","bool","contacto","enum","direccion"
 	 * @param descripcion string La descrpcion de ayuda para este campo.
 	 * @param longitud int 
 	 * @return id_extra_params_estructura int El id de esta estructura
 	 **/
  static function NuevaColumnaBd
	(
		$campo, 
		$caption, 
		$obligatorio, 
		$tabla, 
		$tipo, 
		$descripcion = "", 
		$longitud = ""
	);  
  
  
	
  
	/**
 	 *
 	 *Metodo que elimina todos los registros en la base de datos, especialmente util para hacer pruebas unitarias. Este metodo NO estara disponible al publico.
 	 *
 	 **/
  static function DropBd
	(
	);  
  
  
	
  
	/**
 	 *
 	 *Si el cliente lo desea puede respaldar toda su informacion personal. Esto descargara la base de datos y los documentos que se generan automaticamente como las facturas. Para descargar la base de datos debe tenerse un grupo de 0 o bien el permiso correspondiente.
 	 *
 	 **/
  static function RespaldarBd
	(
	);  
  
  
	
  
	/**
 	 *
 	 *Genera un scrip .sql en el servirdor de los id de instancia que reciba este metodo
 	 *
 	 * @param instance_ids json Lista de los id de las instancias a respaldar
 	 * @return status string Respuesta enviada del servidor
 	 * @return mensaje string Mensaje de respuesta del servidor
 	 **/
  static function BdInstanciasRespaldarBd
	(
		$instance_ids
	);  
  
  
	
  
	/**
 	 *
 	 *Restaurar una BD especifica, a partir de un listado de archivos.
 	 *
 	 * @param id_instancia int Id de la instancia que se requiere restaurar
 	 * @param time int Fecha de creacin del archivo
 	 * @return status string Estado de la respuesta
 	 **/
  static function EspecificaBdRestaurarBd
	(
		$id_instancia, 
		$time
	);  
  
  
	
  
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
	);  
  
  
	
  
	/**
 	 *
 	 *Revisar la version que esta actualmente en el servidor. 
 	 *
 	 **/
  static function VersionClientCurrentCheckClient
	(
	);  
  
  
	
  
	/**
 	 *
 	 *Descargar un zip con la ultima version del cliente.
 	 *
 	 **/
  static function DownloadClient
	(
	);  
  
  
	
  
	/**
 	 *
 	 *Gerenra y /o valida un hash
 	 *
 	 **/
  static function Hash
	(
	);  
  
  
	
  
	/**
 	 *
 	 *Este metodo se utiliza para poder enviar un correo electronico a un tercero. 
 	 *
 	 * @param cuerpo string El cuerpo del correo electronico.
 	 * @param destinatario string El correo electronico a enviar.
 	 * @param titulo string El titulo del correo electronico. 
 	 * @param emisor string El correo electronico de donde se enviara este correo si es que esta configurado para esta instancia. De no enviarse se enviara un correo de la cuenta default de POS ERP.
 	 **/
  static function EnviarMail
	(
		$cuerpo, 
		$destinatario, 
		$titulo, 
		$emisor = "no-reply@caffeina.mx"
	);  
  
  
	
  
	/**
 	 *
 	 *Si un perdidad de conectividad sucediera, es responsabilidad del cliente registrar las ventas o compras realizadas desde que se perdio conectividad. Cuando se restablezca la conexcion se deberan enviar las ventas o compras. 
 	 *
 	 * @param compras json Objeto que contendr la informacin de las compras as como su detalle.
 	 * @param ventas json Objeto que contendr la informacin de las ventas as como su detalle.
 	 * @return id_ventas json Arreglo de ids generados por las inserciones de ventas si las hay
 	 * @return id_compras json Arreglo de ids generados por las inserciones de compras si las hay
 	 **/
  static function EnviarOffline
	(
		$compras = null, 
		$ventas = null
	);  
  
  
	
  
	/**
 	 *
 	 *Cuando un cliente pierde comunicacion se lanzan peticiones a intervalos pequenos de tiempo para revisar conectividad. Esos requests deberan hacerse a este metodo para que el servidor se de cuenta de que el cliente perdio conectvidad y tome medidas aparte como llenar estadistica de conectividad, ademas esto asegurara que el cliente puede enviar cambios ( compras, ventas, nuevos clientes ) de regreso al servidor. 
 	 *
 	 **/
  static function ConexionProbar
	(
	);  
  
  
	
  }
