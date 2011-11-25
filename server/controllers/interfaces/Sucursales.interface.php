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
 	 * @param id_tipo_almacen int Id del tipo de almacen al que sera cambiado. No se puede cambiar este parametro si se trata de un almacen de consignacion ni se puede editar para que sea un almacen de consignacion
 	 * @param nombre string Nombre del almacen
 	 * @param descripcion string Descripcion del almacen
 	 **/
  static function EditarAlmacen
	(
		$id_almacen, 
		$id_tipo_almacen = null, 
		$nombre = "", 
		$descripcion = ""
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
 	 * @param productos json Objeto que contendr los ids de los productos, sus unidades y sus cantidades
 	 * @param id_almacen int Id del almacen que se surte
 	 * @param motivo string Motivo del movimiento
 	 * @return id_surtido string Id generado por el registro de surtir
 	 **/
  static function EntradaAlmacen
	(
		$productos, 
		$id_almacen, 
		$motivo = null
	);  
  
  
	
  
	/**
 	 *
 	 *listar almacenes de la isntancia. Se pueden filtrar por empresa, por sucursal, por tipo de almacen, por activos e inactivos y ordenar por sus atributos.
 	 *
 	 * @param id_empresa int Id de la empresa de la cual se listaran sus almacenes
 	 * @param id_sucursal int el id de la sucursal de la cual se listaran sus almacenes
 	 * @param id_tipo_almacen int Se listaran los almacenes de este tipo
 	 * @param activo bool Si este valor no es obtenido, se mostraran almacenes tanto activos como inactivos. Si es verdadero, solo se lsitaran los activos, si es falso solo se lsitaran los inactivos.
 	 * @return almacenes json Almacenes de esta sucursal
 	 **/
  static function ListaAlmacen
	(
		$id_empresa = null, 
		$id_sucursal = "", 
		$id_tipo_almacen = null, 
		$activo = null
	);  
  
  
	
  
	/**
 	 *
 	 *Creara un nuevo almacen en una sucursal, este almacen contendra lotes.
 	 *
 	 * @param nombre string nombre del almacen
 	 * @param id_sucursal int El id de la sucursal a la que pertenecera este almacen.
 	 * @param id_empresa int Id de la empresa a la que pertenecen los productos de este almacen
 	 * @param id_tipo_almacen int Id del tipo de almacen 
 	 * @param descripcion string Descripcion extesa del almacen
 	 * @return id_almacen int el id recien generado
 	 **/
  static function NuevoAlmacen
	(
		$nombre, 
		$id_sucursal, 
		$id_empresa, 
		$id_tipo_almacen, 
		$descripcion = null
	);  
  
  
	
  
	/**
 	 *
 	 *Envia productos fuera del almacen. Ya sea que sea un traspaso de un alamcen a otro o por motivos de inventarios fisicos.
 	 *
 	 * @param productos json Objeto que contendra los ids de los productos que seran sacados del alamcen con sus cantidades y sus unidades
 	 * @param id_almacen int Id del almacen del cual se hace el movimiento
 	 * @param motivo string Motivo de la salida del producto
 	 * @return id_salida int ID de la salida del producto
 	 **/
  static function SalidaAlmacen
	(
		$productos, 
		$id_almacen, 
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
 	 * @param productos json Productos a enviar con sus cantidades
 	 * @param fecha_envio_programada string Fecha de envio programada
 	 **/
  static function EditarTraspasoAlmacen
	(
		$id_traspaso, 
		$productos = "", 
		$fecha_envio_programada = ""
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
 	 * @param ordenar json Determina el orden de la lista
 	 * @param estado string Se listaran los traspasos cuyo estado sea este, si no es obtenido este valor, se listaran los traspasos de cualqueir estado
 	 * @param id_almacen_recibe int Se listaran los traspasos recibidos por este almacen
 	 * @param id_almacen_envia int Se listaran los traspasos enviados por este almacen
 	 * @param completo bool Si este valor no es obtenido, se listaran los traspasos tanto completos como no completos. Si su valor es verdadero, se listaran los traspasos completos, si es falso, se listaran los traspasos no completos
 	 * @param cancelado bool Si este valor no es obtenido, se listaran los traspasos tanto cancelados como no cancelados. Si su valor es verdadero se listaran solo los traspasos cancelados, si su valor es falso, se listaran los traspasos no cancelados
 	 * @return traspasos json Lista de traspasos
 	 **/
  static function ListaTraspasoAlmacen
	(
		$ordenar = "", 
		$estado = "", 
		$id_almacen_recibe = "", 
		$id_almacen_envia = "", 
		$completo = "", 
		$cancelado = ""
	);  
  
  
	
  
	/**
 	 *
 	 *Crea un registro de traspaso de producto de un almacen a otro. El usuario que envia sera tomada de la sesion.
 	 *
 	 * @param id_almacen_recibe int Id del almacen al que se envia el producto
 	 * @param id_almacen_envia int Id del almacen que envia el producto
 	 * @param fecha_envio_programada string Fecha de envio programada para este traspaso
 	 * @param productos json Productos a ser enviados con sus cantidades
 	 * @return id_traspaso int Id del traspaso autogenerado
 	 **/
  static function ProgramarTraspasoAlmacen
	(
		$id_almacen_recibe, 
		$id_almacen_envia, 
		$fecha_envio_programada, 
		$productos
	);  
  
  
	
  
	/**
 	 *
 	 *Cambia el estado de un traspaso a recibido. La  bandera de completo se prende si los productos enviados son los mismos que los recibidos. La fecha de recibo es tomada del servidor. El usuario que recibe sera tomada de la sesion actual.
 	 *
 	 * @param productos json Productos que se reciben con sus cantidades
 	 * @param id_traspaso int Id del traspaso que se recibe
 	 **/
  static function RecibirTraspasoAlmacen
	(
		$productos, 
		$id_traspaso
	);  
  
  
	
  
	/**
 	 *
 	 *Valida si una maquina que realizara peticiones al servidor pertenece a una sucursal.
 	 *
 	 * @param id_caja int Id de la caja a abrir
 	 * @param saldo float Saldo con el que empieza a funcionar la caja
 	 * @param client_token string El token generado por el POS client
 	 * @param control_billetes bool Si se quiere llevar el control de billetes en la caja
 	 * @param billetes json Ids de billetes y sus cantidades con los que inicia esta caja
 	 * @param id_cajero int Id del cajero que iniciara en esta caja en caso de que no sea este el que abre la caja
 	 * @return detalles_sucursal json Si esta es una sucursal valida, detalles sucursal contiene un objeto con informacion sobre esta sucursal.
 	 **/
  static function AbrirCaja
	(
		$id_caja, 
		$saldo, 
		$client_token, 
		$control_billetes, 
		$billetes, 
		$id_cajero = null
	);  
  
  
	
  
	/**
 	 *
 	 *Hace un corte en los flujos de dinero de la sucursal. El Id de la sucursal se tomara de la sesion actual. La fehca se tomara del servidor.
 	 *
 	 * @param id_caja int Id de la caja a cerrar
 	 * @param saldo_real float Saldo que hay actualmente en la caja
 	 * @param id_cajero int Id del cajero en caso de que no sea este el que realiza el cierre
 	 * @param billetes json Ids de billetes y sus cantidades encontrados en la caja al hacer el cierre
 	 * @return id_cierre int Id del cierre autogenerado.
 	 **/
  static function CerrarCaja
	(
		$id_caja, 
		$saldo_real, 
		$id_cajero = null, 
		$billetes = null
	);  
  
  
	
  
	/**
 	 *
 	 *Comprar productos en mostrador. No debe confundirse con comprar productos a un proveedor. Estos productos se agregaran al inventario de esta sucursal de manera automatica e instantanea. La IP ser? tomada de la m?quina que realiza la compra. El usuario y la sucursal ser?n tomados de la sesion activa. El estado del campo liquidada ser? tomado de acuerdo al campo total y pagado.
 	 *
 	 * @param retencion float Cantidad sumada por retenciones
 	 * @param detalle json Objeto que contendr la informacin de los productos comprados, sus cantidades, sus descuentos, y sus precios
 	 * @param descuento float Cantidad restada por descuento
 	 * @param impuesto float Cantidad sumada por impuestos
 	 * @param id_empresa int Empresa a nombre de la cual se realiza la compra
 	 * @param subtotal float Total de la compra antes de incluirle impuestos.
 	 * @param tipo_compra string Si la compra es a credito o de contado
 	 * @param total float Total de la compra despues de impuestos y descuentos
 	 * @param id_vendedor int Id del cliente al que se le compra
 	 * @param saldo float Saldo de la compra
 	 * @param tipo_pago string Si el pago ser en efectivo, con tarjeta o con cheque
 	 * @param billetes_pago json Ids de billetes que se usaron para pagar
 	 * @param billetes_cambio json Ids de billetes que se recibieron como cambio
 	 * @param cheques json Si el tipo de pago es con cheque, se almacena el nombre del banco, el monto y los ultimos 4 numeros del o de los cheques
 	 * @param id_compra_caja int Id de la compra de esta caja, sirve cuando se va el internet
 	 * @return id_compra_cliente string Id de la nueva compra
 	 **/
  static function ComprarCaja
	(
		$retencion, 
		$detalle, 
		$descuento, 
		$impuesto, 
		$id_empresa, 
		$subtotal, 
		$tipo_compra, 
		$total, 
		$id_vendedor, 
		$saldo = 0, 
		$tipo_pago = null, 
		$billetes_pago = null, 
		$billetes_cambio = null, 
		$cheques = null, 
		$id_compra_caja = null
	);  
  
  
	
  
	/**
 	 *
 	 *Realiza un corte de caja. Este metodo reduce el dinero de la caja y va registrando el dinero acumulado de esa caja. Si faltase dinero se carga una deuda al cajero. La fecha sera tomada del servidor. El usuario sera tomado de la sesion.
 	 *
 	 * @param saldo_final float Saldo que se dejara en la caja para que continue realizando sus operaciones.
 	 * @param saldo_real float Saldo real encontrado en la caja
 	 * @param id_caja int Id de la caja a la que se le hace el corte
 	 * @param id_cajero int Id del cajero en caso de que no sea este el que realiza el corte
 	 * @param id_cajero_nuevo int Id del cajero que entrara despues de realizar el corte
 	 * @param billetes_dejados json Ids de billetes dejados en la caja despues de hacer el corte
 	 * @param billetes_encontrados json Ids de billetes encontrados en la caja al hacer el corte
 	 * @return id_corte_caja int Id generado por la insercion del nuevo corte
 	 **/
  static function CorteCaja
	(
		$saldo_final, 
		$saldo_real, 
		$id_caja, 
		$id_cajero = null, 
		$id_cajero_nuevo = "", 
		$billetes_dejados = null, 
		$billetes_encontrados = null
	);  
  
  
	
  
	/**
 	 *
 	 *Edita la informacion de una caja
 	 *
 	 * @param id_caja int Id de la caja a editar
 	 * @param descripcion string Descripcion de la caja
 	 * @param token string Token generado por el pos client
 	 **/
  static function EditarCaja
	(
		$id_caja, 
		$descripcion = "", 
		$token = ""
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
 	 * @param id_sucursal int Sucursal de la cual se listaran sus cajas
 	 * @param activa bool Valor de activa de las cajas que se listaran
 	 * @return cajas json Objeto que contendra la lista de cajas
 	 **/
  static function ListaCaja
	(
		$id_sucursal = null, 
		$activa = null
	); 
  
  
	
  
	/**
 	 *
 	 *Este metodo creara una caja asociada a una sucursal. Debe haber una caja por CPU. 
 	 *
 	 * @param token string el token que pos_client otorga por equipo
 	 * @param descripcion string Descripcion de esta caja
 	 * @param id_sucursal int Id de la sucursal a la que pertenecera esta caja. Si no es obtenido se tomara la de la sesion
 	 * @param basculas json Un objeto con las basculas conectadas a esta caja.
 	 * @param impresoras json Un objeto con las impresoras asociadas a esta sucursal.
 	 * @return id_caja int Id de la caja generada por la isnercion
 	 **/
  static function NuevaCaja
	(
		$token, 
		$descripcion = null, 
		$id_sucursal = null, 
		$basculas = null, 
		$impresoras = null
	);  
  
  
	
  
	/**
 	 *
 	 *Vender productos desde el mostrador de una sucursal. Cualquier producto vendido aqui sera descontado del inventario de esta sucursal. La fecha ser? tomada del servidor, el usuario y la sucursal ser?n tomados del servidor. La ip ser? tomada de la m?quina que manda a llamar al m?todo. El valor del campo liquidada depender? de los campos total y pagado. La empresa se tomara del alamcen de donde salieron los productos
 	 *
 	 * @param impuesto float Cantidad sumada por impuestos
 	 * @param descuento float La cantidad que ser descontada a la compra
 	 * @param total float El total de la venta
 	 * @param tipo_venta string Si la venta es a credito o a contado
 	 * @param subtotal float El total de la venta antes de cargarle impuestos
 	 * @param retencion float Cantidad sumada por retenciones
 	 * @param id_comprador int Id del cliente al que se le vende.
 	 * @param cheques json Si el tipo de pago es con cheque, se almacena el nombre del banco, el monto y los ultimos 4 numeros del o de los cheques
 	 * @param detalle_orden json Objeto que contendr los id de los servicios, sus cantidades, su precio y su descuento.
 	 * @param detalle_paquete json Objeto que contendr los id de los paquetes, sus cantidades, su precio y su descuento.
 	 * @param billetes_pago json Ids de los billetes que se recibieron 
 	 * @param tipo_pago string Si el pago ser efectivo, cheque o tarjeta.
 	 * @param saldo float La cantidad que ha sido abonada hasta el momento de la venta
 	 * @param detalle_producto json Objeto que contendr los id de los productos, sus cantidades, su precio y su descuento.
 	 * @param billetes_cambio json Ids de billetes que se entregaron como cambio
 	 * @param id_venta_caja int Id de la venta de esta caja, utilizado cuando se va el internet
 	 * @return id_venta int Id autogenerado de la inserci�n de la venta.
 	 **/
  static function VenderCaja
	(
		$impuesto, 
		$descuento, 
		$total, 
		$tipo_venta, 
		$subtotal, 
		$retencion, 
		$id_comprador, 
		$cheques = null, 
		$detalle_orden = null, 
		$detalle_paquete = null, 
		$billetes_pago = null, 
		$tipo_pago = null, 
		$saldo = 0, 
		$detalle_producto = null, 
		$billetes_cambio = null, 
		$id_venta_caja = null
	);  
  
  
	
  
	/**
 	 *
 	 *Edita los datos de una sucursal
 	 *
 	 * @param id_sucursal int Id de la sucursal a modificar
 	 * @param calle string Calle de la sucursal
 	 * @param id_gerente int Id del gerente de la sucursal
 	 * @param municipio int Municipio de la sucursal
 	 * @param impuestos json Objeto que contendra los ids de los impuestos que afectana esta sucursal
 	 * @param rfc string Rfc de la sucursal
 	 * @param saldo_a_favor float Saldo a favor de la sucursal
 	 * @param numero_interior string Numero interior de la sucursal
 	 * @param colonia string Colonia de la sucursal
 	 * @param numero_exterior string Numero exterior de la sucursal
 	 * @param razon_social string Razon social de la sucursal
 	 * @param telefono2 string telefono 2 de la sucursal
 	 * @param telefono1 string telefono 1 de la sucursal
 	 * @param empresas json Objeto que contendra los ids de las empresas a las que esta sucursal pertenece, por lo menos tiene que haber una empresa. En este JSON, opcionalmente junto con el id de la empresa, aapreceran dos campos que seran margen_utilidad y descuento, que indicaran que todos los productos de esa empresa ofrecidos en esta sucursal tendran un margen de utilidad y/o un descuento con los valores en esos campos
 	 * @param descripcion string Descripcion de la sucursal
 	 * @param margen_utilidad float Porcentaje del margen de utilidad que se obtendra de los productos vendidos en esta sucursal
 	 * @param descuento float Descuento que tendran los productos ofrecidos por esta sucursal
 	 * @param coidgo_postal string Codigo Postal de la sucursal
 	 * @param retenciones json Objeto que contendra los ids de las retenciones que afectan a esta sucursal
 	 **/
  static function Editar
	(
		$id_sucursal, 
		$calle = null, 
		$id_gerente = null, 
		$municipio = null, 
		$impuestos = null, 
		$rfc = null, 
		$saldo_a_favor = null, 
		$numero_interior = null, 
		$colonia = null, 
		$numero_exterior = null, 
		$razon_social = null, 
		$telefono2 = null, 
		$telefono1 = null,
		$descripcion = null, 
		$margen_utilidad = null, 
		$descuento = null, 
		$coidgo_postal = null, 
		$retenciones = null
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
 	 * @param id_sucursal int Id de la sucursal de la cual su gerencia sera cambiada
 	 * @param id_gerente string Id del nuevo gerente
 	 **/
  static function EditarGerencia
	(
		$id_sucursal, 
		$id_gerente
	);  
  
  
	
  
	/**
 	 *
 	 *Lista las sucursales relacionadas con esta instancia. Se puede filtrar por empresa,  saldo inferior o superior a, fecha de apertura, ordenar por fecha de apertura u ordenar por saldo. Se agregar? un link en cada una para poder acceder a su detalle.
 	 *
 	 * @param saldo_inferior_que float Si este valor es obtenido, se mostrarn las sucursales que tengan un saldo inferior a este
 	 * @param id_empresa int Id de la empresa de la cual se listaran sus sucursales.
 	 * @param saldo_superior_que float Si este valor es obtenido, se mostrarn las sucursales que tengan un saldo superior a este
 	 * @param fecha_apertura_inferior_que string Si este valor es pasado, se mostraran las sucursales cuya fecha de apertura sea inferior a esta.
 	 * @param activo bool Si este valor no es pasado, se listaran sucursales tanto activas como inactivas, si su valor es true, solo se mostrarn las sucursales activas, si es false, solo se mostraran las sucursales inactivas.
 	 * @param fecha_apertura_superior_que string Si este valor es pasado, se mostraran las sucursales cuya fecha de apertura sea superior a esta.
 	 * @return sucursales json Objeto que contendra la lista de sucursales.
 	 **/
  static function Lista
	(
		$saldo_inferior_que = null, 
		$id_empresa = null, 
		$saldo_superior_que = null, 
		$fecha_apertura_inferior_que = null, 
		$activo = null, 
		$fecha_apertura_superior_que = null
	);  
  
  
	
  
	/**
 	 *
 	 *Metodo que crea una nueva sucursal
 	 *
 	 * @param codigo_postal string Codigo postal de la empresa
 	 * @param calle string Calle de la sucursal
 	 * @param empresas json Objeto que contendra los ids de las empresas a las que esta sucursal pertenece, por lo menos tiene que haber una empresa. En este JSON, opcionalmente junto con el id de la empresa, aapreceran dos campos que seran margen_utilidad y descuento, que indicaran que todos los productos de esa empresa ofrecidos en esta sucursal tendran un margen de utilidad y/o un descuento con los valores en esos campos
 	 * @param activo bool Si esta sucursal estara activa inmediatamente despues de ser creada
 	 * @param colonia string Colonia de la sucursal
 	 * @param razon_social string Razon social de la sucursal
 	 * @param numero_exterior string Numero exterior de la sucursal
 	 * @param rfc string RFC de la sucursal
 	 * @param id_ciudad int Id de la ciudad donde se encuentra la sucursal
 	 * @param saldo_a_favor float Saldo a favor de la sucursal.
 	 * @param id_gerente int ID del usuario que sera gerente de esta sucursal. Para que sea valido este usuario debe tener el nivel de acceso apropiado.
 	 * @param referencia string Referencia para localizar la direccion de la sucursal
 	 * @param retenciones json Objeto que contendra el arreglo de retenciones que afectan a esta sucursal
 	 * @param numero_interior string numero interior
 	 * @param telefono2 string Telefono2 de la sucursal
 	 * @param telefono1 string Telefono1 de la sucursal
 	 * @param margen_utilidad float Margen de utilidad que se le ganara a todos los productos ofrecidos por esta sucursal
 	 * @param descripcion string Descripcion de la sucursal
 	 * @param impuestos json Objeto que contendra el arreglo de impuestos que afectan a esta sucursal
 	 * @param descuento float Descuento que tendran todos los productos ofrecidos por esta sucursal
 	 * @return id_sucursal int Id autogenerado de la sucursal que se creo.
 	 **/
  static function Nueva
	(
		$codigo_postal, 
		$calle, 
		$activo, 
		$colonia, 
		$razon_social, 
		$numero_exterior, 
		$rfc, 
		$id_ciudad, 
		$saldo_a_favor, 
		$id_gerente = null, 
		$referencia = null, 
		$retenciones = null, 
		$numero_interior = null, 
		$telefono2 = null, 
		$telefono1 = null, 
		$margen_utilidad = null, 
		$descripcion = null, 
		$impuestos = null, 
		$descuento = null
	);  
  
  
	
  }
