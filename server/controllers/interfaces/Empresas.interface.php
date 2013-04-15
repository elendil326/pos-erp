<?php
/**
  *
  *
  *
  **/
	
  interface IEmpresas {
  
  
	/**
 	 *
 	 *Mostrar? todas la empresas en el sistema. Por default no se mostraran las empresas ni sucursales inactivas. 
 	 *
 	 * @param activa bool Verdadero para mostrar solo las empresas activas. En caso de false, se mostraran ambas.
 	 * @param limit string Indica hasta que registro se desea obtener a partir del conjunto de resultados productos de la bsqueda.
 	 * @param order string Indica si se ordenan los registros de manera Ascendente ASC, o descendente DESC.
 	 * @param order_by string Indica por que campo se ordenan los resultados.
 	 * @param query string Valor que se buscara en la consulta
 	 * @param start string Indica desde que registro se desea obtener a partir del conjunto de resultados productos de la bsqueda.
 	 * @return resultados json Arreglo de objetos que contendr las empresas de la instancia
 	 * @return numero_de_resultados int 
 	 **/
  static function Buscar
	(
		$activa =  false , 
		$limit = null, 
		$order = null, 
		$order_by = null, 
		$query = null, 
		$start = null
	);  
  
  
	
  
	/**
 	 *
 	 *Muestra los detalles de una empresa en espec?fico. 
 	 *
 	 * @param id_empresa int Id de la empresa
 	 **/
  static function Detalles
	(
		$id_empresa
	);  
  
  
	
  
	/**
 	 *
 	 *Un administrador puede editar una sucursal, incuso si hay puntos de venta con sesiones activas que pertenecen a esa empresa. 
 	 *
 	 * @param id_empresa int Id de la empresa a modificar
 	 * @param cuentas_bancarias json Arreglo que contiene los id de las cuentas bancarias
 	 * @param direccion json {    "tipo": "fiscal",    "calle": "Francisco I Madero",    "numero_exterior": "1009A",    "numero_interior": 12,    "colonia": "centro",    "codigo_postal": "38000",    "telefono1": "4611223312",    "telefono2": "",       "id_ciudad": 3,    "referencia": "El local naranja"}
 	 * @param direccion_web string Direccion del sitio de la empresa
 	 * @param email string Correo electronico de la empresa
 	 * @param id_moneda string Id de la moneda base que manejaran las sucursales
 	 * @param impuestos_compra json Impuestos de compra por default que se heredan a las sucursales y estas a su vez a los productos
 	 * @param impuestos_venta json Impuestos de venta por default que se heredan a las sucursales y estas a su vez a los productos
 	 * @param mensaje_morosos string mensaje enviado a los clientes (email) cuando un pago es demorado
 	 * @param razon_social string El nombre de la nueva empresa.
 	 * @param representante_legal string El nombre del representante legal de la nueva empresa.
 	 * @param rfc string RFC de la empresa
 	 * @param uri_logo string url del logo de la empresa
 	 **/
  static function Editar
	(
		$id_empresa, 
		$cuentas_bancarias = null, 
		$direccion = null, 
		$direccion_web = null, 
		$email = null, 
		$id_moneda = "0", 
		$impuestos_compra = null, 
		$impuestos_venta = null, 
		$mensaje_morosos = null, 
		$razon_social = null, 
		$representante_legal = null, 
		$rfc = null, 
		$uri_logo = null
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
 	 *Crear una nueva empresa. Por default una nueva empresa no tiene sucursales.

Varios RFC`s pueden repetirse siempre y cuando solo exista una empresa activa.
 	 *
 	 * @param contabilidad json JSON donde se describe la moneda que usara como base la empresa, indica la descripcin del ejercicio, el periodo inicial y la duracin de cada periodo
 	 * @param direccion json {    "calle": "Francisco I Madero",    "numero_exterior": "1009A",    "numero_interior": 12,    "colonia": "centro",    "codigo_postal": "38000",    "telefono1": "4611223312",    "telefono2": "",       "id_ciudad": 3,    "referencia": "El local naranja"}
 	 * @param razon_social string El nombre de la nueva empresa.
 	 * @param rfc string RFC de la nueva empresa.
 	 * @param cuentas_bancarias json arreglo que contiene los id de las cuentas bancarias
 	 * @param direccion_web string Direccion del sitio de la empresa
 	 * @param duplicar bool Significa que se duplicara una empresa, solo es una bandera, en caso de que exista y sea = true , significa que duplicara todo lo referente a la empresa (direccion, impuestos asociados, cuentas bancarias, etc..)
 	 * @param email string Correo electronico de la empresa
 	 * @param impuestos_compra json Impuestos de compra por default que se heredan  a los productos
 	 * @param impuestos_venta json Impuestos de venta por default que se heredan  a los productos
 	 * @param mensaje_morosos string mensaje enviado a los clientes cuando un pago es demorado
 	 * @param representante_legal string El nombre del representante legal de la nueva empresa.
 	 * @param uri_logo string url del logo de la empresa
 	 * @return id_empresa int El ID autogenerado de la nueva empresa.
 	 **/
  static function Nuevo
	(
		$contabilidad, 
		$direccion, 
		$razon_social, 
		$rfc, 
		$cuentas_bancarias = null, 
		$direccion_web = null, 
		$duplicar =  false , 
		$email = null, 
		$impuestos_compra = null, 
		$impuestos_venta = null, 
		$mensaje_morosos = null, 
		$representante_legal = null, 
		$uri_logo = null
	);  
  
  
	
  }
