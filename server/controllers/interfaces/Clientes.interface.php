<?php
/**
  *
  *
  *
  **/
	
  interface IClientes {
  
  
	/**
 	 *
 	 *Edita la informacion de la clasificacion de cliente
 	 *
 	 * @param id_clasificacion_cliente int Id de la clasificacion del cliente a modificar
 	 * @param impuestos json Ids de los impuestos que afectan a esta clasificacion
 	 * @param descuento float Descuento que se le aplicara a los productos 
 	 * @param retenciones json Ids de las retenciones que afectan esta clasificacion
 	 * @param clave_interna string Clave interna de la clasificacion
 	 * @param nombre string Nombre de la clasificacion
 	 * @param descripcion string Descripcion larga de la clasificacion
 	 * @param margen_de_utilidad float Margen de utilidad que se le obtendra a todos los productos al venderle a este tipo de cliente
 	 **/
  static function EditarClasificacion
	(
		$id_clasificacion_cliente, 
		$impuestos = null, 
		$descuento = "", 
		$retenciones = null, 
		$clave_interna = "", 
		$nombre = "", 
		$descripcion = "", 
		$margen_de_utilidad = ""
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
 	 * @param impuestos json Impuestos que afectan especificamente a este tipo de clientes
 	 * @param descripcion string Una descripcion para este tipo de cliente
 	 * @param descuento float Porcentaje de descuento que tendra este tipo de cliente sobre todos los productos
 	 * @param retenciones json Retenciones que afectan a este tipo de cliente
 	 * @param utilidad float Utilidad que se ganara a todos los productos que no cuenten con este campo. Se utiliza para calcular el precio al que se le venden los productos a este tipo de cliente.
 	 * @return id_categoria_cliente int El id para esta nueva categoria de cliente.
 	 **/
  static function NuevaClasificacion
	(
		$clave_interna, 
		$nombre, 
		$impuestos = null, 
		$descripcion = "", 
		$descuento = null, 
		$retenciones = null, 
		$utilidad = null
	);  
  
  
	
  
	/**
 	 *
 	 *Obtener los detalles de un cliente.
 	 *
 	 * @param id_cliente int Id del cliente del cual se listarn sus datos.
 	 * @return cliente json Arreglo que contendr� la informaci�n del cliente. 
 	 **/
  static function Detalle
	(
		$id_cliente
	);  
  
  
	
  
	/**
 	 *
 	 *Edita la informaci?n de un cliente. Se diferenc?a del m?todo editar_perfil en qu? est? m?todo modifica informaci?n m?s sensible del cliente. El campo fecha_ultima_modificacion ser? llenado con la fecha actual del servidor. El campo Usuario_ultima_modificacion ser? llenado con la informaci?n de la sesi?n activa.

Si no se envia alguno de los datos opcionales del cliente. Entonces se quedaran los datos que ya tiene.
 	 *
 	 * @param id_cliente int Id del cliente a modificar.
 	 * @param telefono1 string Telefono del cliente
 	 * @param impuestos json Objeto que contendra los ids de los impuestos que afecan a este cliente
 	 * @param codigo_cliente string Codigo interno del cliente
 	 * @param retenciones json Objeto que contendra los ids de las retenciones que afectan a este cliente
 	 * @param direccion_web string Direccin web del cliente.
 	 * @param cuenta_de_mensajeria string Este parmetro se vuelve obligatorio si el parmetro Mensajera es true. Especifica la cuenta de mensajera y paquetera del cliente.
 	 * @param numero_exterior string Nmero exterior del cliente
 	 * @param telefono2 string Segundo telfono del cliente.
 	 * @param saldo_del_ejercicio float Saldo actual del ejercicio del cliente.
 	 * @param municipio int Municipio del cliente
 	 * @param clasificacion_cliente int La clasificacin del cliente.
 	 * @param denominacion_comercial string Nombre comercial del cliente.
 	 * @param moneda_del_cliente string Moneda que maneja el cliente
 	 * @param curp string CURP del cliente.
 	 * @param calle string Calle del cliente
 	 * @param representante_legal string Nombre del representante legal del cliente.
 	 * @param ventas_a_credito int Nmero de ventas a crdito realizadas a este cliente.
 	 * @param password string Password del cliente
 	 * @param facturar_a_terceros bool Si el cliente puede facturar a terceros.
 	 * @param sucursal int Si se desea cambiar al cliente de sucursal, se pasa el id de la nueva sucursal.
 	 * @param colonia string Colonia del cliente
 	 * @param rfc string RFC del cliente.
 	 * @param texto_extra string Comentario sobre la direccin  del cliente.
 	 * @param lim_credito float Valor asignado al lmite del crdito para este cliente.
 	 * @param razon_social string Nombre o razon social del cliente.
 	 * @param estatus string Estatus del cliente.
 	 * @param dias_de_credito int Das de crdito que se le darn al cliente.
 	 * @param mensajeria bool Si el cliente cuenta con un cliente de mensajera y paquetera.
 	 * @param dia_de_pago string Fecha de pago del cliente.
 	 * @param email string E-mail del cliente.
 	 * @param intereses_moratorios float Interes por incumplimiento de pago.
 	 * @param codigo_postal string Codigo postal del cliente
 	 * @param numero_interior string Nmero interior del cliente.
 	 * @param dia_de_revision string Fecha de revisin del cliente.
 	 **/
  static function Editar
	(
		$id_cliente, 
		$telefono1 = null, 
		$impuestos = null, 
		$codigo_cliente = null, 
		$retenciones = null, 
		$direccion_web = null, 
		$cuenta_de_mensajeria = null, 
		$numero_exterior = null, 
		$telefono2 = null, 
		$saldo_del_ejercicio = null, 
		$municipio = null, 
		$clasificacion_cliente = null, 
		$denominacion_comercial = null, 
		$moneda_del_cliente = null, 
		$curp = null, 
		$calle = null, 
		$representante_legal = null, 
		$ventas_a_credito = null, 
		$password = null, 
		$facturar_a_terceros = null, 
		$sucursal = null, 
		$colonia = null, 
		$rfc = null, 
		$texto_extra = null, 
		$lim_credito = null, 
		$razon_social = null, 
		$estatus = null, 
		$dias_de_credito = null, 
		$mensajeria = null, 
		$dia_de_pago = null, 
		$email = null, 
		$intereses_moratorios = null, 
		$codigo_postal = null, 
		$numero_interior = null, 
		$dia_de_revision = null
	);  
  
  
	
  
	/**
 	 *
 	 *Edita la informaci?n de un cliente. El campo fecha_ultima_modificacion ser? llenado con la fecha actual del servidor. El campo Usuario_ultima_modificacion ser? llenado con la informaci?n de la sesi?n activa.
 	 *
 	 * @param password string Password del cliente
 	 * @param razon_social string Nombre o razon social del cliente.
 	 * @param id_cliente int Id del cliente a modificar.
 	 * @param codigo_cliente string Codigo interno del cliente
 	 * @param moneda_del_cliente int Moneda que maneja el cliente
 	 * @param numero_exterior string Nmero exterior del cliente
 	 * @param numero_interior string Nmero interior del cliente.
 	 * @param telefono1 string Telefono del cliente
 	 * @param rfc string RFC del cliente.
 	 * @param representante_legal string Nombre del representante legal del cliente.
 	 * @param curp string CURP del cliente.
 	 * @param cuenta_de_mensajeria string Este parmetro se vuelve obligatorio si el parmetro Mensajera es true. Especifica la cuenta de mensajera y paquetera del cliente.
 	 * @param codigo_postal string Codigo postal del cliente
 	 * @param direccion_web string Direccin web del cliente.
 	 * @param mensajeria bool Si el cliente cuenta con un cliente de mensajera y paquetera.
 	 * @param telefono2 string Segundo telfono del cliente.
 	 * @param denominacion_comercial string Nombre comercial del cliente.
 	 * @param estatus string Estatus del cliente.
 	 * @param calle string Calle del cliente
 	 * @param municipio int Municipio del cliente
 	 * @param clasificacion_cliente int La clasificacin del cliente.
 	 * @param email string E-mail del cliente.
 	 * @param texto_extra string Comentario sobre la direccin del cliente.
 	 * @param colonia string Colonia del cliente
 	 **/
  static function Editar_perfil
	(
		$password, 
		$razon_social, 
		$id_cliente, 
		$codigo_cliente, 
		$moneda_del_cliente =  null, 
		$numero_exterior =  null, 
		$numero_interior =  null, 
		$telefono1 =  null, 
		$rfc =  null, 
		$representante_legal =  null, 
		$curp =  null, 
		$cuenta_de_mensajeria =  null, 
		$codigo_postal =  null, 
		$direccion_web =  null, 
		$mensajeria =  null, 
		$telefono2 =  null, 
		$denominacion_comercial =  null, 
		$estatus =  null, 
		$calle =  null, 
		$municipio =  null, 
		$clasificacion_cliente =  null, 
		$email =  null, 
		$texto_extra =  null, 
		$colonia =  null
	);  
  
  
	
  
	/**
 	 *
 	 *Regresa una lista de clientes. Puede filtrarse por empresa, sucursal, activos, as? como ordenarse seg?n sus atributs con el par?metro orden. Es posible que algunos clientes sean dados de alta por un admnistrador que no les asigne algun id_empresa, o id_sucursal.

Update :  ?Es correcto que contenga el argumento id_sucursal? Ya que as? como esta entiendo que solo te regresara los datos de los clientes de una sola sucursal.
 	 *
 	 * @param orden json Valor que definir la forma de ordenamiento de la lista. 
 	 * @param id_empresa int Filtrara los resultados solo para los clientes que se dieron de alta en la empresa dada.
 	 * @param id_sucursal int Filtrara los resultados solo para los clientes que se dieron de alta en la sucursal dada.
 	 * @param mostrar_inactivos bool Si el valor es obtenido, cuando sea true, mostrar solo los clientes que estn activos, false si solo mostrar clientes inactivos.
 	 * @return clientes json Arreglo de objetos que contendr� la informaci�n de los clientes.
 	 **/
  static function Lista
	(
		$orden = null, 
		$id_sucursal = null, 
		$activo = null,
                $id_clasificacion_cliente = null
	);  
  
  
	
  
	/**
 	 *
 	 *Crear un nuevo cliente. Para los campos de Fecha_alta y Fecha_ultima_modificacion se usar? la fecha actual del servidor. El campo Agente y Usuario_ultima_modificacion ser?n tomados de la sesi?n activa. Para el campo Sucursal se tomar? la sucursal activa donde se est? creando el cliente. 

Al crear un cliente se le creara un usuario para la interfaz de cliente y pueda ver sus facturas y eso, si tiene email. Al crearse se le enviara un correo electronico con el url.
 	 *
 	 * @param codigo_cliente string El codigo para este cliente, con el que se esta usando, puede ser su RFC u otra cosa.
 	 * @param razon_social string Nombre o razon social del cliente.
 	 * @param clasificacion_cliente int Id de la clasificacin del cliente.
 	 * @param rfc string RFC del cliente.
 	 * @param telefono2 string Segundo telfono del cliente.
 	 * @param curp string CURP del cliente.
 	 * @param mensajeria bool Si el cliente cuenta con un cliente de mensajera y paquetera.
 	 * @param password string Password del cliente, si no se envia se le creara uno automaticamente.
 	 * @param numero_exterior string Nmero exterior del cliente
 	 * @param colonia string Colonia del cliente
 	 * @param denominacion_comercial string Nombre comercial del cliente.
 	 * @param cuenta_de_mensajeria string Este parmetro se vuelve obligatorio si el parmetro Mensajera es true. Especifica la cuenta de mensajera instantanea del cliente.
 	 * @param representante_legal string Nombre del representante legal del cliente.
 	 * @param texto_extra string Comentario sobre la direccin del cliente.
 	 * @param telefono1 string Telefono del cliente
 	 * @param referencia string Referencia a la direccion.
 	 * @param codigo_postal string Codigo postal del cliente
 	 * @param id_ciudad int id de la ciudad
 	 * @param retenciones json Objeto que contendra los ids de las retenciones que afectan a este cliente
 	 * @param impuestos json Objeto que contendra los impuestos que afectan a este cliente
 	 * @param moneda_del_cliente int Moneda que maneja el cliente.
 	 * @param numero_interior string Nmero interior del cliente.
 	 * @param calle string Calle del cliente
 	 * @param email string E-mail del cliente
 	 * @param direccion_web string Direccin web del cliente.
 	 * @return id_cliente int Id autogenerado del cliente que se insert�
 	 **/
  static function Nuevo
	(
		$codigo_cliente, 
		$razon_social, 
		$clasificacion_cliente, 
                $password,
		$rfc = null, 
		$telefono2 = null, 
		$curp = null, 
		$mensajeria = null,  
		$numero_exterior = null, 
		$colonia = null, 
		$denominacion_comercial = null, 
		$cuenta_de_mensajeria = null, 
		$representante_legal = null, 
		$texto_extra = null, 
		$telefono1 = null, 
		$referencia = null, 
		$codigo_postal = null, 
		$id_ciudad = null, 
		$retenciones = null, 
		$impuestos = null, 
		$moneda_del_cliente = null, 
		$numero_interior = null, 
		$calle = null, 
		$email = null, 
		$direccion_web = null,
                $telefono_personal1 = null,
                $telefono_personal2 = null
	);  
  
  
	
  }
