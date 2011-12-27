<?php
/**
  *
  *
  *
  **/
	
  interface IClientes {
  
  
	/**
 	 *
 	 *Busca una lista de clientes dada una busqueda
 	 *
 	 * @param query string El texto a buscar
 	 * @return json string Lista de clientes que clientes que satisfacen la busqueda
 	 **/
  static function Buscar
	(
		$query
	);  
  
  
	
  
	/**
 	 *
 	 *Edita la informacion de la clasificacion de cliente
 	 *
 	 * @param id_clasificacion_cliente int Id de la clasificacion del cliente a modificar
 	 * @param clave_interna string Clave interna de la clasificacion
 	 * @param descripcion string Descripcion larga de la clasificacion
 	 * @param descuento float Descuento que se le aplicara a los productos 
 	 * @param impuestos json Ids de los impuestos que afectan a esta clasificacion
 	 * @param margen_de_utilidad float Margen de utilidad que se le obtendra a todos los productos al venderle a este tipo de cliente
 	 * @param nombre string Nombre de la clasificacion
 	 * @param retenciones json Ids de las retenciones que afectan esta clasificacion
 	 **/
  static function EditarClasificacion
	(
		$id_clasificacion_cliente, 
		$clave_interna = null, 
		$descripcion = null, 
		$descuento = null, 
		$impuestos = null, 
		$margen_de_utilidad = null, 
		$nombre = null, 
		$retenciones = null
	);  
  
  
	
  
	/**
 	 *
 	 *Obtener una lista de las categorias de clientes actuales en el sistema. Se puede ordenar por sus atributos
 	 *
 	 * @param orden json Objeto que determinara el orden de la lista
 	 * @return clasifciaciones_cliente json Objeto que contendra la lista de clasificaciones de cliente
 	 **/
  static function ListaClasificacion
	(
		$orden = null
	);  
  
  
	
  
	/**
 	 *
 	 *Los cliente forzosamente pertenecen a una categoria. En base a esta categoria se calcula el precio que se le dara en una venta, o el descuento, o el credito.
 	 *
 	 * @param clave_interna string Una clave interna para darle a este tipo de clientes. Y buscarlos de manera mas rapida.
 	 * @param nombre string Nombre de la clasificacion
 	 * @param descripcion string Una descripcion para este tipo de cliente
 	 * @param descuento float Porcentaje de descuento que tendra este tipo de cliente sobre todos los productos
 	 * @param impuestos json Impuestos que afectan especificamente a este tipo de clientes
 	 * @param retenciones json Retenciones que afectan a este tipo de cliente
 	 * @param utilidad float Utilidad que se ganara a todos los productos que no cuenten con este campo. Se utiliza para calcular el precio al que se le venden los productos a este tipo de cliente.
 	 * @return id_categoria_cliente int El id para esta nueva categoria de cliente.
 	 **/
  static function NuevaClasificacion
	(
		$clave_interna, 
		$nombre, 
		$descripcion = null, 
		$descuento = null, 
		$impuestos = null, 
		$retenciones = null, 
		$utilidad = null
	);  
  
  
	
  
	/**
 	 *
 	 *Obtener los detalles de un cliente.
 	 *
 	 * @param id_cliente int Id del cliente del cual se listarn sus datos.
 	 * @return cliente json Arreglo que contendrá la información del cliente. 
 	 **/
  static function Detalle
	(
		$id_cliente
	);  
  
  
	
  
	/**
 	 *
 	 *Edita la informaci?e un cliente. Se diferenc?del m?do editar_perfil en qu?st??do modifica informaci??sensible del cliente. El campo fecha_ultima_modificacion ser?lenado con la fecha actual del servidor. El campo Usuario_ultima_modificacion ser?lenado con la informaci?e la sesi?ctiva.

Si no se envia alguno de los datos opcionales del cliente. Entonces se quedaran los datos que ya tiene.
 	 *
 	 * @param id_cliente int Id del cliente a modificar.
 	 * @param calle string Calle del cliente
 	 * @param clasificacion_cliente int La clasificacin del cliente.
 	 * @param codigo_cliente string Codigo interno del cliente
 	 * @param codigo_postal string Codigo postal del cliente
 	 * @param colonia string Colonia del cliente
 	 * @param cuenta_de_mensajeria string Este parmetro se vuelve obligatorio si el parmetro Mensajera es true. Especifica la cuenta de mensajera y paquetera del cliente.
 	 * @param curp string CURP del cliente.
 	 * @param denominacion_comercial string Nombre comercial del cliente.
 	 * @param descuento float Descuento que se le dara al usuario
 	 * @param dias_de_credito int Das de crdito que se le darn al cliente.
 	 * @param dia_de_pago string Fecha de pago del cliente.
 	 * @param dia_de_revision string Fecha de revisin del cliente.
 	 * @param direccion_web string Direccin web del cliente.
 	 * @param email string E-mail del cliente.
 	 * @param facturar_a_terceros bool Si el cliente puede facturar a terceros.
 	 * @param impuestos json Objeto que contendra los ids de los impuestos que afecan a este cliente
 	 * @param intereses_moratorios float Interes por incumplimiento de pago.
 	 * @param lim_credito float Valor asignado al lmite del crdito para este cliente.
 	 * @param mensajeria bool Si el cliente cuenta con un cliente de mensajera y paquetera.
 	 * @param moneda_del_cliente string Moneda que maneja el cliente
 	 * @param municipio int Municipio del cliente
 	 * @param numero_exterior string Nmero exterior del cliente
 	 * @param numero_interior string Nmero interior del cliente.
 	 * @param password string Password del cliente
 	 * @param razon_social string Nombre o razon social del cliente.
 	 * @param representante_legal string Nombre del representante legal del cliente.
 	 * @param retenciones json Objeto que contendra los ids de las retenciones que afectan a este cliente
 	 * @param rfc string RFC del cliente.
 	 * @param saldo_del_ejercicio float Saldo actual del ejercicio del cliente.
 	 * @param sucursal int Si se desea cambiar al cliente de sucursal, se pasa el id de la nueva sucursal.
 	 * @param telefono1 string Telefono del cliente
 	 * @param telefono2 string Segundo telfono del cliente.
 	 * @param telefono_personal1 string Telefono personal del cliente
 	 * @param telefono_personal2 string Telefono personal del cliente
 	 * @param texto_extra string Comentario sobre la direccin  del cliente.
 	 * @param ventas_a_credito int Nmero de ventas a crdito realizadas a este cliente.
 	 **/
  static function Editar
	(
		$id_cliente, 
		$calle = null, 
		$clasificacion_cliente = null, 
		$codigo_cliente = null, 
		$codigo_postal = null, 
		$colonia = null, 
		$cuenta_de_mensajeria = null, 
		$curp = null, 
		$denominacion_comercial = null, 
		$descuento = null, 
		$dias_de_credito = null, 
		$dia_de_pago = null, 
		$dia_de_revision = null, 
		$direccion_web = null, 
		$email = null, 
		$facturar_a_terceros = null, 
		$impuestos = null, 
		$intereses_moratorios = null, 
		$lim_credito = null, 
		$mensajeria = null, 
		$moneda_del_cliente = null, 
		$municipio = null, 
		$numero_exterior = null, 
		$numero_interior = null, 
		$password = null, 
		$razon_social = null, 
		$representante_legal = null, 
		$retenciones = null, 
		$rfc = null, 
		$saldo_del_ejercicio = null, 
		$sucursal = null, 
		$telefono1 = null, 
		$telefono2 = null, 
		$telefono_personal1 = null, 
		$telefono_personal2 = null, 
		$texto_extra = null, 
		$ventas_a_credito = null
	);  
  
  
	
  
	/**
 	 *
 	 *Edita la informaci?e un cliente. El campo fecha_ultima_modificacion ser?lenado con la fecha actual del servidor. El campo Usuario_ultima_modificacion ser?lenado con la informaci?e la sesi?ctiva.
 	 *
 	 * @param id_cliente int Id del cliente a modificar.
 	 * @param calle string Calle del cliente
 	 * @param clasificacion_cliente int La clasificacin del cliente.
 	 * @param codigo_cliente string Codigo interno del cliente
 	 * @param codigo_postal string Codigo postal del cliente
 	 * @param colonia string Colonia del cliente
 	 * @param cuenta_de_mensajeria string Este parmetro se vuelve obligatorio si el parmetro Mensajera es true. Especifica la cuenta de mensajera y paquetera del cliente.
 	 * @param curp string CURP del cliente.
 	 * @param denominacion_comercial string Nombre comercial del cliente.
 	 * @param descuento float Descuento que se le dara al usuario
 	 * @param direccion_web string Direccin web del cliente.
 	 * @param email string E-mail del cliente.
 	 * @param mensajeria bool Si el cliente cuenta con un cliente de mensajera y paquetera.
 	 * @param moneda_del_cliente int Moneda que maneja el cliente
 	 * @param municipio int Municipio del cliente
 	 * @param numero_exterior string Nmero exterior del cliente
 	 * @param numero_interior string Nmero interior del cliente.
 	 * @param password string Password del cliente
 	 * @param razon_social string Nombre o razon social del cliente.
 	 * @param representante_legal string Nombre del representante legal del cliente.
 	 * @param rfc string RFC del cliente.
 	 * @param telefono1 string Telefono del cliente
 	 * @param telefono2 string Segundo telfono del cliente.
 	 * @param telefono_personal1 string Telefono personal del cliente
 	 * @param telefono_personal2 string Telefono personal alterno del cliente
 	 * @param texto_extra string Comentario sobre la direccin del cliente.
 	 **/
  static function Editar_perfil
	(
		$id_cliente, 
		$calle =  null, 
		$clasificacion_cliente =  null, 
		$codigo_cliente = null, 
		$codigo_postal =  null, 
		$colonia =  null, 
		$cuenta_de_mensajeria =  null, 
		$curp =  null, 
		$denominacion_comercial =  null, 
		$descuento = null, 
		$direccion_web =  null, 
		$email =  null, 
		$mensajeria =  null, 
		$moneda_del_cliente =  null, 
		$municipio =  null, 
		$numero_exterior =  null, 
		$numero_interior =  null, 
		$password =  null, 
		$razon_social =  null, 
		$representante_legal =  null, 
		$rfc =  null, 
		$telefono1 =  null, 
		$telefono2 =  null, 
		$telefono_personal1 = null, 
		$telefono_personal2 = null, 
		$texto_extra =  null
	);  
  
  
	
  
	/**
 	 *
 	 *Regresa una lista de clientes. Puede filtrarse por empresa, sucursal, activos, as?omo ordenarse seg?us atributs con el par?tro orden. Es posible que algunos clientes sean dados de alta por un admnistrador que no les asigne algun id_empresa, o id_sucursal.

Update :  ¿Es correcto que contenga el argumento id_sucursal? Ya que as?omo esta entiendo que solo te regresara los datos de los clientes de una sola sucursal.
 	 *
 	 * @param activo bool Si el valor es obtenido, cuando sea true, mostrar solo los clientes que estn activos, false si solo mostrar clientes inactivos.
 	 * @param id_clasificacion_cliente int Se listaran los clientes que cumplan con esta clasificacion
 	 * @param id_sucursal int Filtrara los resultados solo para los clientes que se dieron de alta en la sucursal dada.
 	 * @param orden json Valor que definir la forma de ordenamiento de la lista. 
 	 * @return clientes json Arreglo de objetos que contendrá la información de los clientes.
 	 **/
  static function Lista
	(
		$activo = null, 
		$id_clasificacion_cliente = null, 
		$id_sucursal = null, 
		$orden = null
	);  
  
  
	
  
	/**
 	 *
 	 *Crear un nuevo cliente. Para los campos de Fecha_alta y Fecha_ultima_modificacion se usar?a fecha actual del servidor. El campo Agente y Usuario_ultima_modificacion ser?tomados de la sesi?ctiva. Para el campo Sucursal se tomar?a sucursal activa donde se est?reando el cliente. 

Al crear un cliente se le creara un usuario para la interfaz de cliente y pueda ver sus facturas y eso, si tiene email. Al crearse se le enviara un correo electronico con el url.
 	 *
 	 * @param clasificacion_cliente int Id de la clasificacin del cliente.
 	 * @param codigo_cliente string El codigo para este cliente, con el que se esta usando, puede ser su RFC u otra cosa.
 	 * @param password string Password del cliente, si no se envia se le creara uno automaticamente.
 	 * @param razon_social string Nombre o razon social del cliente.
 	 * @param calle string Calle del cliente
 	 * @param codigo_postal string Codigo postal del cliente
 	 * @param colonia string Colonia del cliente
 	 * @param cuenta_de_mensajeria string Este parmetro se vuelve obligatorio si el parmetro Mensajera es true. Especifica la cuenta de mensajera instantanea del cliente.
 	 * @param curp string CURP del cliente.
 	 * @param denominacion_comercial string Nombre comercial del cliente.
 	 * @param descuento float Descuento que se le dara al usuario
 	 * @param direccion_web string Direccin web del cliente.
 	 * @param email string E-mail del cliente
 	 * @param id_ciudad int id de la ciudad
 	 * @param impuestos json Objeto que contendra los impuestos que afectan a este cliente
 	 * @param limite_credito float Limite de credito del usuario
 	 * @param mensajeria bool Si el cliente cuenta con un cliente de mensajera y paquetera.
 	 * @param moneda_del_cliente int Moneda que maneja el cliente.
 	 * @param numero_exterior string Nmero exterior del cliente
 	 * @param numero_interior string Nmero interior del cliente.
 	 * @param representante_legal string Nombre del representante legal del cliente.
 	 * @param retenciones json Objeto que contendra los ids de las retenciones que afectan a este cliente
 	 * @param rfc string RFC del cliente.
 	 * @param telefono1 string Telefono del cliente
 	 * @param telefono2 string Segundo telfono del cliente.
 	 * @param telefono_personal1 string Telefono personal del cliente
 	 * @param telefono_personal2 string Telefono personal alterno del cliente
 	 * @param texto_extra string Comentario sobre la direccin del cliente.
 	 * @return id_cliente int Id autogenerado del cliente que se insertó
 	 **/
  static function Nuevo
	(
		$clasificacion_cliente, 
		$codigo_cliente, 
		$password, 
		$razon_social, 
		$calle = null, 
		$codigo_postal = null, 
		$colonia = null, 
		$cuenta_de_mensajeria = null, 
		$curp = null, 
		$denominacion_comercial = null, 
		$descuento = null, 
		$direccion_web = null, 
		$email = null, 
		$id_ciudad = null, 
		$impuestos = null, 
		$limite_credito = null, 
		$mensajeria = null, 
		$moneda_del_cliente = null, 
		$numero_exterior = null, 
		$numero_interior = null, 
		$representante_legal = null, 
		$retenciones = null, 
		$rfc = null, 
		$telefono1 = null, 
		$telefono2 = null, 
		$telefono_personal1 = null, 
		$telefono_personal2 = null, 
		$texto_extra = null
	);  
  
  
	
  }
