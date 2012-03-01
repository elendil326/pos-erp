<?php
/**
  *
  *
  *
  **/
	
  interface IEmpresas {
  
  
	/**
 	 *
 	 *Mostrar? todas la empresas en el sistema, as? como sus sucursalse y sus gerentes[a] correspondientes. Por default no se mostraran las empresas ni sucursales inactivas. 
 	 *
 	 * @param activa bool Verdadero para mostrar solo las empresas activas. En caso de false, se mostraran ambas.
 	 * @param limit string Indica hasta que registro se desea obtener a partir del conjunto de resultados productos de la bsqueda.
 	 * @param query string Valor que se buscara en la consulta
 	 * @param start string Indica desde que registro se desea obtener a partir del conjunto de resultados productos de la bsqueda.
 	 * @return resultados json Arreglo de objetos que contendr las empresas de la instancia
 	 * @return numero_de_resultados int 
 	 **/
  static function Buscar
	(
		$activa =  false , 
		$limit = null, 
		$query = null, 
		$start = null
	);  
  
  
	
  
	/**
 	 *
 	 *Un administrador puede editar una sucursal, incuso si hay puntos de venta con sesiones activas que pertenecen a esa empresa. 
 	 *
 	 * @param id_empresa int Id de la empresa a modificar
 	 * @param cedula string url de la cedula de la empresa
 	 * @param direccion json [{    "tipo": "fiscal",    "calle": "Francisco I Madero",    "numero_exterior": "1009A",    "numero_interior": 12,    "colonia": "centro",    "codigo_postal": "38000",    "telefono1": "4611223312",    "telefono2": "",       "id_ciudad": 3,    "referencia": "El local naranja"}]
 	 * @param email string Correo electronico de la empresa
 	 * @param id_moneda string Id de la moneda base que manejaran las sucursales
 	 * @param impuestos_venta json Impuestos de venta por default que se heredan a las sucursales y estas a su vez a los productos
 	 * @param impuesto_compra json Impuestos de compra por default que se heredan a las sucursales y estas a su vez a los productos
 	 * @param logo string url del logo de la empresa
 	 * @param razon_social string El nombre de la nueva empresa.
 	 * @param representante_legal string El nombre del representante legal de la nueva empresa.
 	 * @param texto_extra string Comentarios sobre la ubicacin de la empresa.
 	 **/
  static function Editar
	(
		$id_empresa, 
		$cedula = null, 
		$direccion = null, 
		$email = null, 
		$id_moneda = "0", 
		$impuestos_venta = null, 
		$impuesto_compra = null, 
		$logo = null, 
		$razon_social = null, 
		$representante_legal = null, 
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
 	 *Crear una nueva empresa. Por default una nueva empresa no tiene sucursales.

Varios RFC`s pueden repetirse siempre y cuando solo exista una empresa activa.
 	 *
 	 * @param direccion string [{    "tipo": "fiscal",    "calle": "Francisco I Madero",    "numero_exterior": "1009A",    "numero_interior": 12,    "colonia": "centro",    "codigo_postal": "38000",    "telefono1": "4611223312",    "telefono2": "",       "id_ciudad": 3,    "referencia": "El local naranja"}]
 	 * @param id_moneda int Id de la moneda base que manejaran las sucursales
 	 * @param razon_social string El nombre de la nueva empresa.
 	 * @param rfc string RFC de la nueva empresa.
 	 * @param cedula string url de la imagen de la cedula
 	 * @param email string Correo electronico de la empresa
 	 * @param impuestos_compra json Impuestos de compra por default que se heredan  a los productos
 	 * @param impuestos_venta json Impuestos de venta por default que se heredan  a los productos
 	 * @param logo string url del logo de la empresa
 	 * @param representante_legal string El nombre del representante legal de la nueva empresa.
 	 * @param sucursales json Arreglo de objetos con un `id_sucursal` cada uno que indicara que sucursales pertenecen a esta empresa.
 	 * @param texto_extra string Comentarios sobre la ubicacin de la empresa.
 	 * @return id_empresa int El ID autogenerado de la nueva empresa.
 	 **/
  static function Nuevo
	(
		$direccion, 
		$id_moneda, 
		$razon_social, 
		$rfc, 
		$cedula = null, 
		$email = null, 
		$impuestos_compra = "", 
		$impuestos_venta = null, 
		$logo = null, 
		$representante_legal = null, 
		$sucursales = null, 
		$texto_extra = null
	);  
  
  
	
  }
