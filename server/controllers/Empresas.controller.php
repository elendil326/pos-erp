<?php
/**
  *
  *
  *
  **/
	
  class EmpresasController implements IEmpresas{
  
  
	/**
 	 *
 	 *Mostrar?odas la empresas en el sistema, as?omo sus sucursalse y sus gerentes[a] correspondientes. Por default no se mostraran las empresas ni sucursales inactivas. 
 	 *
 	 * @param activa bool Si no se obtiene este valor, se listaran tanto empresas activas como inactivas, si su valor es true, se mostraran solo las empresas activas, si es false, se mostraran solo las inactivas
 	 * @return empresas json Arreglo de objetos que contendrá las empresas de la instancia
 	 **/
	public function Lista
	(
		$activa = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Relacionar una sucursal a esta empresa. Cuando se llama a este metodo, se crea un almacen de esta sucursal para esta empresa
 	 *
 	 * @param id_empresa int 
 	 * @param sucursales json Arreglo de objetos que tendran los ids de sucursales, un campo opcional de  margen de utilidad que simboliza el margen de utilidad que esas sucursales ganaran para los productos de esa empresa y un campo de descuento, que indica el descuento que se aplicara a todas los productos de esa empresa en esa sucursal
 	 **/
	public function Agregar_sucursales
	(
		$id_empresa, 
		$sucursales
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Crear una nueva empresa. Por default una nueva empresa no tiene sucursales.
 	 *
 	 * @param colonia string Colonia de la empresa
 	 * @param telefono1 string telefono de la empresa
 	 * @param codigo_postal string Codigo postal de la empresa
 	 * @param curp string CURP de la nueva empresa.
 	 * @param razon_social string El nombre de la nueva empresa.
 	 * @param numero_exterior string Numero externo de la emresa
 	 * @param ciudad	 int Identificacor de la ciudad
 	 * @param rfc string RFC de la nueva empresa.
 	 * @param calle string Calle de la empresa
 	 * @param numero_interior string Numero interno de la empresa
 	 * @param telefono2 string Telefono 2 de la empresa
 	 * @param e-mail string Correo electronico de la empresa
 	 * @param texto_extra string Comentarios sobre la ubicacin de la empresa.
 	 * @param direccion_web string Direccin web de la empresa
 	 * @param retenciones json Objeto que contendra los ids de las retenciones que aplican a esta empresa
 	 * @param margen_utilidad float Porcentaje del margen de utilidad que le gana esta empresa a todos los productos que ofrece
 	 * @param descuento float Descuento que se aplciara a todos los productos de esta empresa
 	 * @param representante_legal string El nombre del representante legal de la nueva empresa.
 	 * @param impuestos json Objeto que contendra los ids de los impuestos que aplican a esta empresa 
 	 * @return id_empresa int El ID autogenerado de la nueva empresa.
 	 **/
	public function Nuevo
	(
		$colonia, 
		$telefono1, 
		$codigo_postal, 
		$curp, 
		$razon_social, 
		$numero_exterior, 
		$ciudad	, 
		$rfc, 
		$calle, 
		$numero_interior = null, 
		$telefono2 = null, 
		$e-mail = null, 
		$texto_extra = null, 
		$direccion_web = null, 
		$retenciones = null, 
		$margen_utilidad = null, 
		$descuento = null, 
		$representante_legal = null, 
		$impuestos = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Para poder eliminar una empresa es necesario que la empresa no tenga sucursales activas, sus saldos sean 0, que los clientes asociados a dicha empresa no tengan adeudo, ...
 	 *
 	 * @param id_empresa string El id de la empresa a eliminar.
 	 **/
	public function Eliminar
	(
		$id_empresa
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Un administrador puede editar una sucursal, incuso si hay puntos de venta con sesiones activas que pertenecen a esa empresa. 
 	 *
 	 * @param telefono1 string telefono de la empresa
 	 * @param numero_exterior	 string Numero externo de la emresa
 	 * @param colonia	 string Colonia de la empresa
 	 * @param codigo_postal string Codigo postal de la empresa
 	 * @param curp string CURP de la nueva empresa.
 	 * @param calle	 string Calle de la empresa
 	 * @param id_empresa int Id de la empresa a modificar
 	 * @param rfc string RFC de la nueva empresa.
 	 * @param ciudad int Ciudad donde se encuentra la empresa
 	 * @param razon_social string El nombre de la nueva empresa.
 	 * @param e-mail string Correo electronico de la empresa
 	 * @param representante_legal string El nombre del representante legal de la nueva empresa.
 	 * @param numero_interno string Numero interno de la empresa
 	 * @param direccion_web string Direccin web de la empresa
 	 * @param retenciones json Objeto que contendra los ids de las retenciones que aplican a esta empresa
 	 * @param descuento float Descuento que se aplicara a todos los productos de esta empresa
 	 * @param margen_utilidad float Porcentaje del margen de utilidad que esta empresa le gana a todos sus productos
 	 * @param impuestos json Objeto que contendra los ids de los impuestos que afectan a esta empresa
 	 * @param texto_extra string Comentarios sobre la ubicacin de la empresa.
 	 * @param telefono2 string Telefono 2 de la empresa
 	 **/
	public function Editar
	(
		$telefono1, 
		$numero_exterior	, 
		$colonia	, 
		$codigo_postal, 
		$curp, 
		$calle	, 
		$id_empresa, 
		$rfc, 
		$ciudad, 
		$razon_social, 
		$e-mail = null, 
		$representante_legal = null, 
		$numero_interno = null, 
		$direccion_web = null, 
		$retenciones = null, 
		$descuento = null, 
		$margen_utilidad = null, 
		$impuestos = null, 
		$texto_extra = null, 
		$telefono2 = null
	)
	{  
  
  
	}
  }
