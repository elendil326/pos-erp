<?php
/**
  *
  *
  *
  **/
	
  interface IPOS {
  
  
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
