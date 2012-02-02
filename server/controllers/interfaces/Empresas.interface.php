<?php
/**
  *
  *
  *
  **/
	
  interface IEmpresas {
  
  
	/**
 	 *
 	 *Relacionar una sucursal a esta empresa. Cuando se llama a este metodo, se crea un almacen de esta sucursal para esta empresa
 	 *
 	 * @param id_empresa int Empresa a la que se le agregaran sucursales
 	 * @param sucursales json Arreglo que contendra los ids de las sucursales a relacionar con esta empresa
 	 **/
  static function SucursalesAgregar
	(
		$id_empresa, 
		$sucursales
	);  
  
  
	
  
	/**
 	 *
 	 *Un administrador puede editar una sucursal, incuso si hay puntos de venta con sesiones activas que pertenecen a esa empresa. 
 	 *
 	 * @param id_empresa int Id de la empresa a modificar
 	 * @param calle	 string Calle de la empresa
 	 * @param ciudad int Ciudad donde se encuentra la empresa
 	 * @param codigo_postal string Codigo postal de la empresa
 	 * @param colonia	 string Colonia de la empresa
 	 * @param curp string CURP de la nueva empresa.
 	 * @param direccion_web string Direccin web de la empresa
 	 * @param email string Correo electronico de la empresa
 	 * @param impuestos json Objeto que contendra los ids de los impuestos que afectan a esta empresa
 	 * @param numero_exterior	 string Numero externo de la emresa
 	 * @param numero_interno string Numero interno de la empresa
 	 * @param razon_social string El nombre de la nueva empresa.
 	 * @param representante_legal string El nombre del representante legal de la nueva empresa.
 	 * @param retenciones json Objeto que contendra los ids de las retenciones que aplican a esta empresa
 	 * @param rfc string RFC de la nueva empresa.
 	 * @param telefono1 string telefono de la empresa
 	 * @param telefono2 string Telefono 2 de la empresa
 	 * @param texto_extra string Comentarios sobre la ubicacin de la empresa.
 	 **/
  static function Editar
	(
		$id_empresa, 
		$calle	 = null, 
		$ciudad = null, 
		$codigo_postal = null, 
		$colonia	 = null, 
		$curp = null, 
		$direccion_web = null, 
		$email = null, 
		$impuestos = null, 
		$numero_exterior	 = null, 
		$numero_interno = null, 
		$razon_social = null, 
		$representante_legal = null, 
		$retenciones = null, 
		$rfc = null, 
		$telefono1 = null, 
		$telefono2 = null, 
		$texto_extra = null
	);  
  
  
	
  
	/**
 	 *
 	 *Para poder eliminar una empresa es necesario que la empresa no tenga sucursales activas, sus saldos sean 0, que los clientes asociados a dicha empresa no tengan adeudo, ...
 	 *
 	 * @param id_empresa string El id de la empresa a eliminar.
 	 **/
  static function Eliminar
	(
		$id_empresa
	);  
  
  
	
  
	/**
 	 *
 	 *Mostrar? todas la empresas en el sistema, as? como sus sucursalse y sus gerentes[a] correspondientes. Por default no se mostraran las empresas ni sucursales inactivas. 
 	 *
 	 * @param activa bool Si no se obtiene este valor, se listaran tanto empresas activas como inactivas, si su valor es true, se mostraran solo las empresas activas, si es false, se mostraran solo las inactivas
 	 * @return empresas json Arreglo de objetos que contendrá las empresas de la instancia
 	 **/
  static function Lista
	(
		$activa = "false"
	);  
  
  
	
  
	/**
 	 *
 	 *Crear una nueva empresa. Por default una nueva empresa no tiene sucursales.
 	 *
 	 * @param calle string Calle de la empresa
 	 * @param ciudad int El id de la ciudad a la que pertenece esta empresa
 	 * @param codigo_postal string Codigo postal de la empresa
 	 * @param colonia string Colonia de la empresa
 	 * @param curp string CURP de la nueva empresa.
 	 * @param numero_exterior string Numero externo de la emresa
 	 * @param razon_social string El nombre de la nueva empresa.
 	 * @param rfc string RFC de la nueva empresa.
 	 * @param direccion_web string Direccin web de la empresa
 	 * @param email string Correo electronico de la empresa
 	 * @param impuestos json Objeto que contendra los ids de los impuestos que aplican a esta empresa 
 	 * @param numero_interior string Numero interno de la empresa
 	 * @param representante_legal string El nombre del representante legal de la nueva empresa.
 	 * @param retenciones json Objeto que contendra los ids de las retenciones que aplican a esta empresa
 	 * @param telefono1 string telefono de la empresa
 	 * @param telefono2 string Telefono 2 de la empresa
 	 * @param texto_extra string Comentarios sobre la ubicacin de la empresa.
 	 * @return id_empresa int El ID autogenerado de la nueva empresa.
 	 **/
  static function Nuevo
	(
		$calle, 
		$ciudad, 
		$codigo_postal, 
		$colonia, 
		$curp, 
		$numero_exterior, 
		$razon_social, 
		$rfc, 
		$direccion_web = null, 
		$email = null, 
		$impuestos = null, 
		$numero_interior = null, 
		$representante_legal = null, 
		$retenciones = null, 
		$telefono1 = null, 
		$telefono2 = null, 
		$texto_extra = null
	);  
  
  
	
  }
