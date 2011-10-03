<?php
/**
  *
  *
  *
  **/

  interaface IPOS {
  
  
	/**
 	 *
 	 *Si un perdidad de conectividad sucediera, es responsabilidad del cliente registrar las ventas o compras realizadas desde que se perdio conectividad. Cuando se restablezca la conexcion se deberan enviar las ventas o compras. 
 	 *
 	 **/
	protected function EnviarOffline();  
  
  
  
  
	/**
 	 *
 	 *Gerenra y /o valida un hash
 	 *
 	 **/
	protected function Hash();  
  
  
  
  
	/**
 	 *
 	 *Cuando un cliente pierde comunicacion se lanzan peticiones a intervalos pequenos de tiempo para revisar conectividad. Esos requests deberan hacerse a este metodo para que el servidor se de cuenta de que el cliente perdio conectvidad y tome medidas aparte como llenar estadistica de conectividad, ademas esto asegurara que el cliente puede enviar cambios ( compras, ventas, nuevos clientes ) de regreso al servidor. 
 	 *
 	 **/
	protected function Probar_conexion();  
  
  
  
  
	/**
 	 *
 	 *Si el cliente lo desea puede respaldar toda su informacion personal. Esto descargara la base de datos y los documentos que se generan automaticamente como las facturas. Para descargar la base de datos debe tenerse un grupo de 0 o bien el permiso correspondiente.
 	 *
 	 **/
	protected function RespaldarBd();  
  
  
  
  
	/**
 	 *
 	 *Revisar la version que esta actualmente en el servidor. 
 	 *
 	 **/
	protected function Check_current_client_versionClient();  
  
  
  
  
	/**
 	 *
 	 *Descargar un zip con la ultima version del cliente.
 	 *
 	 **/
	protected function DownloadClient();  
  
  
  
  }
