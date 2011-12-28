<?php
/**
  *
  *
  *
  **/
	
  interface ISucursales {
  
  
	/**
 	 *
 	 *Edita la informacion de un almacen
 	 *
 	 * @param id_almacen int Id del almacen a editar
 	 * @param descripcion string Descripcion del almacen
 	 * @param id_tipo_almacen int Id del tipo de almacen al que sera cambiado. No se puede cambiar este parametro si se trata de un almacen de consignacion ni se puede editar para que sea un almacen de consignacion
 	 * @param nombre string Nombre del almacen
 	 **/
  static function EditarAlmacen
	(
		$id_almacen, 
		$descripcion = null, 
		$id_tipo_almacen = null, 
		$nombre = null
	);  
  
  
	
  
	/**
 	 *
 	 *Descativa un almacen. Para poder desactivar un almacen, este tiene que estar vac?o
 	 *
 	 * @param id_almacen int Id del almacen a desactivar
 	 **/
  static function EliminarAlmacen
	(
		$id_almacen
	);  
  
  
	
  
	/**
 	 *
 	 *Metodo que surte una sucursal por parte de un proveedor. La sucursal sera tomada de la sesion actual.

Update
Creo que este metodo tiene que estar bajo sucursal.
 	 *
 	 * @param id_almacen int Id del almacen que se surte
 	 * @param productos json Objeto que contendr los ids de los productos, sus unidades y sus cantidades
 	 * @param motivo string Motivo del movimiento
 	 * @return id_surtido string Id generado por el registro de surtir
 	 **/
  static function EntradaAlmacen
	(
		$id_almacen, 
		$productos, 
		$motivo = null
	);  
  
  
	
  
	/**
 	 *
 	 *listar almacenes de la isntancia. Se pueden filtrar por empresa, por sucursal, por tipo de almacen, por activos e inactivos y ordenar por sus atributos.
 	 *
 	 * @param activo bool Si este valor no es obtenido, se mostraran almacenes tanto activos como inactivos. Si es verdadero, solo se lsitaran los activos, si es falso solo se lsitaran los inactivos.
 	 * @param id_empresa int Id de la empresa de la cual se listaran sus almacenes
 	 * @param id_sucursal int el id de la sucursal de la cual se listaran sus almacenes
 	 * @param id_tipo_almacen int Se listaran los almacenes de este tipo
 	 * @return almacenes json Almacenes de esta sucursal
 	 **/
  static function ListaAlmacen
	(
		$activo = null, 
		$id_empresa = null, 
		$id_sucursal = null, 
		$id_tipo_almacen = null
	);  
  
  
	
  
	/**
 	 *
 	 *Creara un nuevo almacen en una sucursal, este almacen contendra lotes.
 	 *
 	 * @param id_empresa int Id de la empresa a la que pertenecen los productos de este almacen
 	 * @param id_sucursal int El id de la sucursal a la que pertenecera este almacen.
 	 * @param id_tipo_almacen int Id del tipo de almacen 
 	 * @param nombre string nombre del almacen
 	 * @param descripcion string Descripcion extesa del almacen
 	 * @return id_almacen int el id recien generado
 	 **/
  static function NuevoAlmacen
	(
		$id_empresa, 
		$id_sucursal, 
		$id_tipo_almacen, 
		$nombre, 
		$descripcion = null
	);  
  
  
	
  
	/**
 	 *
 	 *Envia productos fuera del almacen. Ya sea que sea un traspaso de un alamcen a otro o por motivos de inventarios fisicos.
 	 *
 	 * @param id_almacen int Id del almacen del cual se hace el movimiento
 	 * @param productos json Objeto que contendra los ids de los productos que seran sacados del alamcen con sus cantidades y sus unidades
 	 * @param motivo string Motivo de la salida del producto
 	 * @return id_salida int ID de la salida del producto
 	 **/
  static function SalidaAlmacen
	(
		$id_almacen, 
		$productos, 
		$motivo = null
	);  
  
  
	
  
	/**
 	 *
 	 *Para poder cancelar un traspaso, este no tuvo que haber sido enviado aun.
 	 *
 	 * @param id_traspaso int Id del traspaso a cancelar
 	 **/
  static function CancelarTraspasoAlmacen
	(
		$id_traspaso
	);  
  
  
	
  
	/**
 	 *
 	 *Para poder editar un traspaso,este no tuvo que haber sido enviado aun
 	 *
 	 * @param id_traspaso int Id del traspaso a editar
 	 * @param fecha_envio_programada string Fecha de envio programada
 	 * @param productos json Productos a enviar con sus cantidades
 	 **/
  static function EditarTraspasoAlmacen
	(
		$id_traspaso, 
		$fecha_envio_programada = null, 
		$productos = null
	);  
  
  
	
  
	/**
 	 *
 	 *Cambia el estado del traspaso a enviado y captura la fecha de envio del servidor. El usuario que envia sera tomado del servidor.
 	 *
 	 * @param id_traspaso int Id del traspaso a enviar
 	 **/
  static function EnviarTraspasoAlmacen
	(
		$id_traspaso
	);  
  
  
	
  
	/**
 	 *
 	 *Lista los traspasos de almacenes. Puede filtrarse por empresa, por sucursal, por almacen, por producto, cancelados, completos, estado
 	 *
 	 * @param cancelado bool Si este valor no es obtenido, se listaran los traspasos tanto cancelados como no cancelados. Si su valor es verdadero se listaran solo los traspasos cancelados, si su valor es falso, se listaran los traspasos no cancelados
 	 * @param completo bool Si este valor no es obtenido, se listaran los traspasos tanto completos como no completos. Si su valor es verdadero, se listaran los traspasos completos, si es falso, se listaran los traspasos no completos
 	 * @param estado string Se listaran los traspasos cuyo estado sea este, si no es obtenido este valor, se listaran los traspasos de cualqueir estado
 	 * @param id_almacen_envia int Se listaran los traspasos enviados por este almacen
 	 * @param id_almacen_recibe int Se listaran los traspasos recibidos por este almacen
 	 * @param ordenar string Nombre de la columna por la cual se ordenara
 	 * @return traspasos json Lista de traspasos
 	 **/
  static function ListaTraspasoAlmacen
	(
		$cancelado = null, 
		$completo = null, 
		$estado = null, 
		$id_almacen_envia = null, 
		$id_almacen_recibe = null, 
		$ordenar = null
	);  
  
  
	
  
	/**
 	 *
 	 *Crea un registro de traspaso de producto de un almacen a otro. El usuario que envia sera tomada de la sesion.
 	 *
 	 * @param fecha_envio_programada string Fecha de envio programada para este traspaso
 	 * @param id_almacen_envia int Id del almacen que envia el producto
 	 * @param id_almacen_recibe int Id del almacen al que se envia el producto
 	 * @param productos json Productos a ser enviados con sus cantidades
 	 * @return id_traspaso int Id del traspaso autogenerado
 	 **/
  static function ProgramarTraspasoAlmacen
	(
		$fecha_envio_programada, 
		$id_almacen_envia, 
		$id_almacen_recibe, 
		$productos
	);  
  
  
	
  
	/**
 	 *
 	 *Cambia el estado de un traspaso a recibido. La  bandera de completo se prende si los productos enviados son los mismos que los recibidos. La fecha de recibo es tomada del servidor. El usuario que recibe sera tomada de la sesion actual.
 	 *
 	 * @param id_traspaso int Id del traspaso que se recibe
 	 * @param productos json Productos que se reciben con sus cantidades
 	 **/
  static function RecibirTraspasoAlmacen
	(
		$id_traspaso, 
		$productos
	);  
  
  
	
  
	/**
 	 *
 	 *Valida si una maquina que realizara peticiones al servidor pertenece a una sucursal.
 	 *
 	 * @param client_token string El token generado por el POS client
 	 * @param control_billetes bool Si se quiere llevar el control de billetes en la caja
 	 * @param id_caja int Id de la caja a abrir
 	 * @param saldo float Saldo con el que empieza a funcionar la caja
 	 * @param billetes json Ids de billetes y sus cantidades con los que inicia esta caja
 	 * @param id_cajero int Id del cajero que iniciara en esta caja en caso de que no sea este el que abre la caja
 	 * @return detalles_sucursal json Si esta es una sucursal valida, detalles sucursal contiene un objeto con informacion sobre esta sucursal.
 	 **/
  static function AbrirCaja
	(
		$client_token, 
		$control_billetes, 
		$id_caja, 
		$saldo, 
		$billetes = null, 
		$id_cajero = null
	);  
  
  
	
  
	/**
 	 *
 	 *Hace un corte en los flujos de dinero de la sucursal. El Id de la sucursal se tomara de la sesion actual. La fehca se tomara del servidor.
 	 *
 	 * @param id_caja int Id de la caja a cerrar
 	 * @param saldo_real float Saldo que hay actualmente en la caja
 	 * @param billetes json Ids de billetes y sus cantidades encontrados en la caja al hacer el cierre
 	 * @param id_cajero int Id del cajero en caso de que no sea este el que realiza el cierre
 	 * @return id_cierre int Id del cierre autogenerado.
 	 **/
  static function CerrarCaja
	(
		$id_caja, 
		$saldo_real, 
		$billetes = null, 
		$id_cajero = null
	);  
  
  
	
  
	/**
 	 *
 	 *Comprar productos en mostrador. No debe confundirse con comprar productos a un proveedor. Estos productos se agregaran al inventario de esta sucursal de manera automatica e instantanea. La IP ser? tomada de la m?quina que realiza la compra. El usuario y la sucursal ser?n tomados de la sesion activa. El estado del campo liquidada ser? tomado de acuerdo al campo total y pagado.
 	 *
 	 * @param descuento float Cantidad restada por descuento
 	 * @param detalle json Objeto que contendr la informacin de los productos comprados, sus cantidades, sus descuentos, y sus precios
 	 * @param id_empresa int Empresa a nombre de la cual se realiza la compra
 	 * @param id_vendedor int Id del cliente al que se le compra
 	 * @param impuesto float Cantidad sumada por impuestos
 	 * @param retencion float Cantidad sumada por retenciones
 	 * @param subtotal float Total de la compra antes de incluirle impuestos.
 	 * @param tipo_compra string Si la compra es a credito o de contado
 	 * @param total float Total de la compra despues de impuestos y descuentos
 	 * @param billetes_cambio json Ids de billetes que se recibieron como cambio
 	 * @param billetes_pago json Ids de billetes que se usaron para pagar
 	 * @param cheques json Si el tipo de pago es con cheque, se almacena el nombre del banco, el monto y los ultimos 4 numeros del o de los cheques
 	 * @param id_caja int Id de la caja de la cual se realizara la compra. Si no se recibe se tomara de la sesion
 	 * @param id_compra_caja int Id de la compra de esta caja, sirve cuando se va el internet
 	 * @param id_sucursal int Id de la sucursal de la cual se hara la compra. Si no se recibe se tomara de la sesion
 	 * @param saldo float Saldo de la compra
 	 * @param tipo_pago string Si el pago ser en efectivo, con tarjeta o con cheque
 	 * @return id_compra_cliente string Id de la nueva compra
 	 **/
  static function ComprarCaja
	(
		$descuento, 
		$detalle, 
		$id_empresa, 
		$id_vendedor, 
		$impuesto, 
		$retencion, 
		$subtotal, 
		$tipo_compra, 
		$total, 
		$billetes_cambio = null, 
		$billetes_pago = null, 
		$cheques = null, 
		$id_caja = null, 
		$id_compra_caja = null, 
		$id_sucursal = null, 
		$saldo = 0, 
		$tipo_pago = null
	);  
  
  
	
  
	/**
 	 *
 	 *Realiza un corte de caja. Este metodo reduce el dinero de la caja y va registrando el dinero acumulado de esa caja. Si faltase dinero se carga una deuda al cajero. La fecha sera tomada del servidor. El usuario sera tomado de la sesion.
 	 *
 	 * @param id_caja int Id de la caja a la que se le hace el corte
 	 * @param saldo_final float Saldo que se dejara en la caja para que continue realizando sus operaciones.
 	 * @param saldo_real float Saldo real encontrado en la caja
 	 * @param billetes_dejados json Ids de billetes dejados en la caja despues de hacer el corte
 	 * @param billetes_encontrados json Ids de billetes encontrados en la caja al hacer el corte
 	 * @param id_cajero int Id del cajero en caso de que no sea este el que realiza el corte
 	 * @param id_cajero_nuevo int Id del cajero que entrara despues de realizar el corte
 	 * @return id_corte_caja int Id generado por la insercion del nuevo corte
 	 **/
  static function CorteCaja
	(
		$id_caja, 
		$saldo_final, 
		$saldo_real, 
		$billetes_dejados = null, 
		$billetes_encontrados = null, 
		$id_cajero = null, 
		$id_cajero_nuevo = null
	);  
  
  
	
  
	/**
 	 *
 	 *Edita la informacion de una caja
 	 *
 	 * @param id_caja int Id de la caja a editar
 	 * @param control_billetes bool Si la caja llevara control de los billetes que tiene o no
 	 * @param descripcion string Descripcion de la caja
 	 * @param token string Token generado por el pos client
 	 **/
  static function EditarCaja
	(
		$id_caja, 
		$control_billetes = null, 
		$descripcion = null, 
		$token = null
	);  
  
  
	
  
	/**
 	 *
 	 *Desactiva una caja, para que la caja pueda ser desactivada, tieneq ue estar cerrada
 	 *
 	 * @param id_caja int Id de la caja a eliminar
 	 **/
  static function EliminarCaja
	(
		$id_caja
	);  
  
  
	
  
	/**
 	 *
 	 *Lista las cajas. Se puede filtrar por la sucursal a la que pertenecen.
 	 *
 	 * @param activa bool Valor de activa de las cajas que se listaran
 	 * @param id_sucursal int Sucursal de la cual se listaran sus cajas
 	 * @return cajas json Objeto que contendra la lista de cajas
 	 **/
  static function ListaCaja
	(
		$activa = null, 
		$id_sucursal = null
	);  
  
  
	
  
	/**
 	 *
 	 *Este metodo creara una caja asociada a una sucursal. Debe haber una caja por CPU. 
 	 *
 	 * @param token string el token que pos_client otorga por equipo
 	 * @param basculas json Un objeto con las basculas conectadas a esta caja.
 	 * @param control_billetes bool Si la caja llevara el control de los billetes que hay en ella
 	 * @param descripcion string Descripcion de esta caja
 	 * @param id_sucursal int Id de la sucursal a la que pertenecera esta caja. Si no es obtenido se tomara la de la sesion
 	 * @param impresoras json Un objeto con las impresoras asociadas a esta sucursal.
 	 * @return id_caja int Id de la caja generada por la insercion
 	 **/
  static function NuevaCaja
	(
		$token, 
		$basculas = null, 
		$control_billetes = 0, 
		$descripcion = null, 
		$id_sucursal = null, 
		$impresoras = null
	);  
  
  
	
  
	/**
 	 *
 	 *Vender productos desde el mostrador de una sucursal. Cualquier producto vendido aqui sera descontado del inventario de esta sucursal. La fecha ser? tomada del servidor, el usuario y la sucursal ser?n tomados del servidor. La ip ser? tomada de la m?quina que manda a llamar al m?todo. El valor del campo liquidada depender? de los campos total y pagado. La empresa se tomara del alamcen de donde salieron los productos
 	 *
 	 * @param descuento float La cantidad que ser descontada a la compra
 	 * @param id_comprador int Id del cliente al que se le vende.
 	 * @param impuesto float Cantidad sumada por impuestos
 	 * @param retencion float Cantidad sumada por retenciones
 	 * @param subtotal float El total de la venta antes de cargarle impuestos
 	 * @param tipo_venta string Si la venta es a credito o a contado
 	 * @param total float El total de la venta
 	 * @param billetes_cambio json Ids de billetes que se entregaron como cambio
 	 * @param billetes_pago json Ids de los billetes que se recibieron 
 	 * @param cheques json Si el tipo de pago es con cheque, se almacena el nombre del banco, el monto y los ultimos 4 numeros del o de los cheques
 	 * @param detalle_orden json Objeto que contendr los id de los servicios, sus cantidades, su precio y su descuento.
 	 * @param detalle_paquete json Objeto que contendr los id de los paquetes, sus cantidades, su precio y su descuento.
 	 * @param detalle_producto json Objeto que contendr los id de los productos, sus cantidades, su precio y su descuento.
 	 * @param id_caja int Id de la caja desde la que se vende, en caso de que se venda desde otra caja
 	 * @param id_sucursal int Id de la sucursal de donde saldran los productos en caso de que se venda desde otra sucursal
 	 * @param id_venta_caja int Id de la venta de esta caja, utilizado cuando se va el internet
 	 * @param saldo float La cantidad que ha sido abonada hasta el momento de la venta
 	 * @param tipo_pago string Si el pago ser efectivo, cheque o tarjeta.
 	 * @return id_venta int Id autogenerado de la inserción de la venta.
 	 **/
  static function VenderCaja
	(
		$descuento, 
		$id_comprador, 
		$impuesto, 
		$retencion, 
		$subtotal, 
		$tipo_venta, 
		$total, 
		$billetes_cambio = null, 
		$billetes_pago = null, 
		$cheques = null, 
		$detalle_orden = null, 
		$detalle_paquete = null, 
		$detalle_producto = null, 
		$id_caja = null, 
		$id_sucursal = null, 
		$id_venta_caja = null, 
		$saldo = 0, 
		$tipo_pago = null
	);  
  
  
	
  
	/**
 	 *
 	 *Edita los datos de una sucursal
 	 *
 	 * @param id_sucursal int Id de la sucursal a modificar
 	 * @param calle string Calle de la sucursal
 	 * @param coidgo_postal string Codigo Postal de la sucursal
 	 * @param colonia string Colonia de la sucursal
 	 * @param descripcion string Descripcion de la sucursal
 	 * @param descuento float Descuento que tendran los productos ofrecidos por esta sucursal
 	 * @param empresas json Objeto que contendra los ids de las empresas a las que esta sucursal pertenece, por lo menos tiene que haber una empresa. En este JSON, opcionalmente junto con el id de la empresa, aapreceran dos campos que seran margen_utilidad y descuento, que indicaran que todos los productos de esa empresa ofrecidos en esta sucursal tendran un margen de utilidad y/o un descuento con los valores en esos campos
 	 * @param id_gerente int Id del gerente de la sucursal
 	 * @param impuestos json Objeto que contendra los ids de los impuestos que afectana esta sucursal
 	 * @param margen_utilidad float Porcentaje del margen de utilidad que se obtendra de los productos vendidos en esta sucursal
 	 * @param municipio int Municipio de la sucursal
 	 * @param numero_exterior string Numero exterior de la sucursal
 	 * @param numero_interior string Numero interior de la sucursal
 	 * @param razon_social string Razon social de la sucursal
 	 * @param retenciones json Objeto que contendra los ids de las retenciones que afectan a esta sucursal
 	 * @param rfc string Rfc de la sucursal
 	 * @param saldo_a_favor float Saldo a favor de la sucursal
 	 * @param telefono1 string telefono 1 de la sucursal
 	 * @param telefono2 string telefono 2 de la sucursal
 	 **/
  static function Editar
	(
		$id_sucursal, 
		$calle = null, 
		$coidgo_postal = null, 
		$colonia = null, 
		$descripcion = null, 
		$descuento = null, 
		$empresas = null, 
		$id_gerente = null, 
		$impuestos = null, 
		$margen_utilidad = null, 
		$municipio = null, 
		$numero_exterior = null, 
		$numero_interior = null, 
		$razon_social = null, 
		$retenciones = null, 
		$rfc = null, 
		$saldo_a_favor = null, 
		$telefono1 = null, 
		$telefono2 = null
	);  
  
  
	
  
	/**
 	 *
 	 *Desactiva una sucursal. Para poder desactivar una sucursal su saldo a favor tiene que ser mayor a cero y sus almacenes tienen que estar vacios.
 	 *
 	 * @param id_sucursal int Id de la sucursal a desactivar
 	 **/
  static function Eliminar
	(
		$id_sucursal
	);  
  
  
	
  
	/**
 	 *
 	 *Edita la gerencia de una sucursal
 	 *
 	 * @param id_gerente string Id del nuevo gerente
 	 * @param id_sucursal int Id de la sucursal de la cual su gerencia sera cambiada
 	 **/
  static function EditarGerencia
	(
		$id_gerente, 
		$id_sucursal
	);  
  
  
	
  
	/**
 	 *
 	 *Lista las sucursales relacionadas con esta instancia. Se puede filtrar por empresa,  saldo inferior o superior a, fecha de apertura, ordenar por fecha de apertura u ordenar por saldo. Se agregar? un link en cada una para poder acceder a su detalle.
 	 *
 	 * @param activo bool Si este valor no es pasado, se listaran sucursales tanto activas como inactivas, si su valor es true, solo se mostrarn las sucursales activas, si es false, solo se mostraran las sucursales inactivas.
 	 * @param fecha_apertura_inferior_que string Si este valor es pasado, se mostraran las sucursales cuya fecha de apertura sea inferior a esta.
 	 * @param fecha_apertura_superior_que string Si este valor es pasado, se mostraran las sucursales cuya fecha de apertura sea superior a esta.
 	 * @param id_empresa int Id de la empresa de la cual se listaran sus sucursales.
 	 * @param saldo_inferior_que float Si este valor es obtenido, se mostrarn las sucursales que tengan un saldo inferior a este
 	 * @param saldo_superior_que float Si este valor es obtenido, se mostrarn las sucursales que tengan un saldo superior a este
 	 * @return sucursales json Objeto que contendra la lista de sucursales.
 	 **/
  static function Lista
	(
		$activo = null, 
		$fecha_apertura_inferior_que = null, 
		$fecha_apertura_superior_que = null, 
		$id_empresa = null, 
		$saldo_inferior_que = null, 
		$saldo_superior_que = null
	);  
  
  
	
  
	/**
 	 *
 	 *Metodo que crea una nueva sucursal
 	 *
 	 * @param activo bool Si esta sucursal estara activa inmediatamente despues de ser creada
 	 * @param calle string Calle de la sucursal
 	 * @param codigo_postal string Codigo postal de la empresa
 	 * @param colonia string Colonia de la sucursal
 	 * @param id_ciudad int Id de la ciudad donde se encuentra la sucursal
 	 * @param numero_exterior string Numero exterior de la sucursal
 	 * @param razon_social string Razon social de la sucursal
 	 * @param rfc string RFC de la sucursal
 	 * @param saldo_a_favor float Saldo a favor de la sucursal.
 	 * @param descripcion string Descripcion de la sucursal
 	 * @param descuento float Descuento que tendran todos los productos ofrecidos por esta sucursal
 	 * @param id_gerente int ID del usuario que sera gerente de esta sucursal. Para que sea valido este usuario debe tener el nivel de acceso apropiado.
 	 * @param impuestos json Objeto que contendra el arreglo de impuestos que afectan a esta sucursal
 	 * @param margen_utilidad float Margen de utilidad que se le ganara a todos los productos ofrecidos por esta sucursal
 	 * @param numero_interior string numero interior
 	 * @param referencia string Referencia para localizar la direccion de la sucursal
 	 * @param retenciones json Objeto que contendra el arreglo de retenciones que afectan a esta sucursal
 	 * @param telefono1 string Telefono1 de la sucursal
 	 * @param telefono2 string Telefono2 de la sucursal
 	 * @return id_sucursal int Id autogenerado de la sucursal que se creo.
 	 **/
  static function Nueva
	(
		$activo, 
		$calle, 
		$codigo_postal, 
		$colonia, 
		$id_ciudad, 
		$numero_exterior, 
		$razon_social, 
		$rfc, 
		$saldo_a_favor, 
		$descripcion = null, 
		$descuento = null, 
		$id_gerente = null, 
		$impuestos = null, 
		$margen_utilidad = null, 
		$numero_interior = null, 
		$referencia = null, 
		$retenciones = null, 
		$telefono1 = null, 
		$telefono2 = null
	);  
  
  
	
  
	/**
 	 *
 	 *Edita un tipo de almacen
 	 *
 	 * @param id_tipo_almacen int Id del tipo de almacen a editar
 	 * @param descripcion string Descripcion del tipo de almacen
 	 **/
  static function EditarTipo_almacen
	(
		$id_tipo_almacen, 
		$descripcion = null
	);  
  
  
	
  
	/**
 	 *
 	 *Elimina un tipo de almacen
 	 *
 	 * @param id_tipo_almacen int Id del tipo de almacen a editar
 	 **/
  static function EliminarTipo_almacen
	(
		$id_tipo_almacen
	);  
  
  
	
  
	/**
 	 *
 	 *Imprime la lista de tipos de almacen
 	 *
 	 * @param orden string Nombre de la columan por el cual se ordenara la lista
 	 * @return lista_tipos_almacen json Arreglo con la lista de almacenes
 	 **/
  static function ListaTipo_almacen
	(
		$orden = null
	);  
  
  
	
  
	/**
 	 *
 	 *Crea un nuevo tipo de almacen
 	 *
 	 * @param descripcion string Descripcion de este tipo de almacen
 	 * @return id_tipo_almacen int Id del tipo de almacen
 	 **/
  static function NuevoTipo_almacen
	(
		$descripcion
	);  
  
  
	
  }
