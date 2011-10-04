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
 	 * @param nombre string Nombre del almacen
 	 * @param descripcion string Descripcion del almacen
 	 **/
  function EditarAlmacen
	(
		$id_almacen, 
		$nombre, 
		$descripcion = null
	);  
  
  
	
  
	/**
 	 *
 	 *Descativa un almacen. Para poder desactivar un almacen, este tiene que estar vac?
 	 *
 	 * @param id_almacen int Id del almacen a desactivar
 	 **/
  function EliminarAlmacen
	(
		$id_almacen
	);  
  
  
	
  
	/**
 	 *
 	 *Metodo que surte una sucursal por parte de un proveedor. La sucursal sera tomada de la sesion actual.

<h2>Update</h2>
Creo que este metodo tiene que estar bajo sucursal.
 	 *
 	 * @param productos json Objeto que contendr los ids de los productos y sus cantidades
 	 * @param id_almacen int Id del almacen que se surte
 	 * @param motivo string Motivo del movimiento
 	 * @return id_surtido string Id generado por el registro de surtir
 	 **/
  function EntradaAlmacen
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
  function ListaAlmacen
	(
		$id_empresa = null, 
		$id_sucursal = null, 
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
  function NuevoAlmacen
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
 	 * @param productos json Objeto que contendra los ids de los productos que seran sacados del alamcen con sus cantidades
 	 * @param id_almacen int Id del almacen del cual se hace el movimiento
 	 * @param motivo string Motivo de la salida del producto
 	 * @return id_salida int ID de la salida del producto
 	 **/
  function SalidaAlmacen
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
  function CancelarTraspasoAlmacen
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
  function EditarTraspasoAlmacen
	(
		$id_traspaso, 
		$fecha_envio_programada, 
		$productos
	);  
  
  
	
  
	/**
 	 *
 	 *Cambia el estado del traspaso a enviado y captura la fecha de envio del servidor. El usuario que envia sera tomado del servidor.
 	 *
 	 * @param id_traspaso int Id del traspaso a enviar
 	 **/
  function EnviarTraspasoAlmacen
	(
		$id_traspaso
	);  
  
  
	
  
	/**
 	 *
 	 *Lista los traspasos de almacenes. Puede filtrarse por empresa, por sucursal, por almacen, por producto, cancelados, completos, estado
 	 *
 	 * @param cancelado bool Si este valor no es obtenido, se listaran los traspasos tanto cancelados como no cancelados. Si su valor es verdadero se listaran solo los traspasos cancelados, si su valor es falso, se listaran los traspasos no cancelados
 	 * @param completo bool Si este valor no es obtenido, se listaran los traspasos tanto completos como no completos. Si su valor es verdadero, se listaran los traspasos completos, si es falso, se listaran los traspasos no completos
 	 * @param id_producto int Se listaran los traspasos que incluyan este producto
 	 * @param id_almacen int Se listaran los traspasos enviados y/o recibidos por este almacen
 	 * @param enviados bool Si este valor no es obtenido, se listaran los traspasos tanto enviados como recibidos de este almacen (campo id_almacen). Si su valor es verdader, se listaran los traspasos enviados por este almacen, si su valor es falso, se listaran los traspasos recibidos por este almacen
 	 * @param id_sucursal int Se listaran los traspasos de los almacenes de esta sucursal
 	 * @param id_empresa int Se listaran los traspasos de los almacenes de esta empresa
 	 * @param estado string Se listaran los traspasos cuyo estado sea este, si no es obtenido este valor, se listaran los traspasos de cualqueir estado
 	 * @param ordenar json Determina el orden de la lista
 	 * @return traspasos json Lista de traspasos
 	 **/
  function ListaTraspasoAlmacen
	(
		$cancelado = null, 
		$completo = null, 
		$id_producto = null, 
		$id_almacen = null, 
		$enviados = null, 
		$id_sucursal = null, 
		$id_empresa = null, 
		$estado = null, 
		$ordenar = null
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
  function ProgramarTraspasoAlmacen
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
  function RecibirTraspasoAlmacen
	(
		$productos, 
		$id_traspaso
	);  
  
  
	
  
	/**
 	 *
 	 *Valida si una maquina que realizara peticiones al servidor pertenece a una sucursal.
 	 *
 	 * @param billetes json Ids de billetes y sus cantidades con los que inicia esta caja
 	 * @param saldo float Saldo con el que empieza a funcionar la caja
 	 * @param client_token string El token generado por el POS client
 	 * @param control_billetes bool Si se quiere llevar el control de billetes en la caja
 	 * @param id_cajero int Id del cajero que iniciara en esta caja en caso de que no sea este el que abre la caja
 	 * @return detalles_sucursal json Si esta es una sucursal valida, detalles sucursal contiene un objeto con informacion sobre esta sucursal.
 	 **/
  function AbrirCaja
	(
		$billetes, 
		$saldo, 
		$client_token, 
		$control_billetes, 
		$id_cajero = null
	);  
  
  
	
  
	/**
 	 *
 	 *Calcula el saldo esperado para una caja a partir de los cortes que le han realizado, la fecha de apertura y la fecha en que se realiza el calculo. La caja sera tomada de la sesion, la fecha sera tomada del servidor. Para poder realizar este metodo, la caja tiene que estar abierta
 	 *
 	 * @param id_caja int Id de la caja de la cual se hara el calculo.
 	 * @return saldo_esperado float Saldo esperado de la caja para la fecha actual
 	 **/
  function Calcular_saldo_esperadoCaja
	(
		$id_caja
	);  
  
  
	
  
	/**
 	 *
 	 *Hace un corte en los flujos de dinero de la sucursal. El Id de la sucursal se tomara de la sesion actual. La fehca se tomara del servidor.
 	 *
 	 * @param saldo_real float Saldo que hay actualmente en la caja
 	 * @param billetes json Ids de billetes y sus cantidades encontrados en la caja al hacer el cierre
 	 * @param id_cajero int Id del cajero en caso de que no sea este el que realiza el cierre
 	 * @return id_cierre int Id del cierre autogenerado.
 	 **/
  function CerrarCaja
	(
		$saldo_real, 
		$billetes, 
		$id_cajero = null
	);  
  
  
	
  
	/**
 	 *
 	 *Comprar productos en mostrador. No debe confundirse con comprar productos a un proveedor. Estos productos se agregaran al inventario de esta sucursal de manera automatica e instantanea. La IP ser?omada de la m?ina que realiza la compra. El usuario y la sucursal ser?tomados de la sesion activa. El estado del campo liquidada ser?omado de acuerdo al campo total y pagado.
 	 *
 	 * @param retencion float Cantidad sumada por retenciones
 	 * @param detalle json Objeto que contendr la informacin de los productos comprados, sus cantidades, sus descuentos, y sus precios
 	 * @param id_vendedor int Id del cliente al que se le compra
 	 * @param total float Total de la compra despues de impuestos y descuentos
 	 * @param tipo_compra string Si la compra es a credito o de contado
 	 * @param subtotal float Total de la compra antes de incluirle impuestos.
 	 * @param id_empresa int Empresa a nombre de la cual se realiza la compra
 	 * @param descuento float Cantidad restada por descuento
 	 * @param impuesto float Cantidad sumada por impuestos
 	 * @param billetes_pago json Ids de billetes que se usaron para pagar
 	 * @param billetes_cambio json Ids de billetes que se recibieron como cambio
 	 * @param tipo_pago string Si el pago ser en efectivo, con tarjeta o con cheque
 	 * @param saldo float Saldo de la compra
 	 * @param cheques json Si el tipo de pago es con cheque, se almacena el nombre del banco, el monto y los ultimos 4 numeros del o de los cheques
 	 * @return id_compra_cliente string Id de la nueva compra
 	 **/
  function ComprarCaja
	(
		$retencion, 
		$detalle, 
		$id_vendedor, 
		$total, 
		$tipo_compra, 
		$subtotal, 
		$id_empresa, 
		$descuento, 
		$impuesto, 
		$billetes_pago = null, 
		$billetes_cambio = null, 
		$tipo_pago = null, 
		$saldo = null, 
		$cheques = null
	);  
  
  
	
  
	/**
 	 *
 	 *Realiza un corte de caja. Este metodo reduce el dinero de la caja y va registrando el dinero acumulado de esa caja. Si faltase dinero se carga una deuda al cajero. La fecha sera tomada del servidor. El usuario sera tomado de la sesion.
 	 *
 	 * @param saldo_final float Saldo que se dejara en la caja para que continue realizando sus operaciones.
 	 * @param id_caja int Id de la caja a la que se le hace el corte
 	 * @param saldo_real float Saldo real encontrado en la caja
 	 * @param billetes_encontrados json Ids de billetes encontrados en la caja al hacer el corte
 	 * @param billetes_dejados json Ids de billetes dejados en la caja despues de hacer el corte
 	 * @param id_cajero int Id del cajero en caso de que no sea este el que realiza el corte
 	 * @param id_cajero_nuevo int Id del cajero que entrara despues de realizar el corte
 	 * @return id_corte_caja int Id generado por la insercion del nuevo corte
 	 **/
  function CorteCaja
	(
		$saldo_final, 
		$id_caja, 
		$saldo_real, 
		$billetes_encontrados, 
		$billetes_dejados, 
		$id_cajero = null, 
		$id_cajero_nuevo = null
	);  
  
  
	
  
	/**
 	 *
 	 *Edita la informacion de una caja
 	 *
 	 * @param id_caja int Id de la caja a editar
 	 * @param token string Token generado por el pos client
 	 * @param descripcion string Descripcion de la caja
 	 **/
  function EditarCaja
	(
		$id_caja, 
		$token, 
		$descripcion = null
	);  
  
  
	
  
	/**
 	 *
 	 *Desactiva una caja, para que la caja pueda ser desactivada, tieneq ue estar cerrada
 	 *
 	 * @param id_caja int Id de la caja a eliminar
 	 **/
  function EliminarCaja
	(
		$id_caja
	);  
  
  
	
  
	/**
 	 *
 	 *Este metodo creara una caja asociada a una sucursal. Debe haber una caja por CPU. 
 	 *
 	 * @param token string el token que pos_client otorga por equipo
 	 * @param codigo_caja string El codigo de uso interno de la caja
 	 * @param impresoras json Un objeto con las impresoras asociadas a esta sucursal.
 	 * @param basculas json Un objeto con las basculas conectadas a esta caja.
 	 * @param descripcion string Descripcion de esta caja
 	 * @return id_caja int Id de la caja generada por la isnercion
 	 **/
  function NuevaCaja
	(
		$token, 
		$codigo_caja = null, 
		$impresoras = null, 
		$basculas = null, 
		$descripcion = null
	);  
  
  
	
  
	/**
 	 *
 	 *Vender productos desde el mostrador de una sucursal. Cualquier producto vendido aqui sera descontado del inventario de esta sucursal. La fecha ser?omada del servidor, el usuario y la sucursal ser?tomados del servidor. La ip ser?omada de la m?ina que manda a llamar al m?do. El valor del campo liquidada depender?e los campos total y pagado. La empresa se tomara del alamcen de donde salieron los productos
 	 *
 	 * @param detalle json Objeto que contendr los id de los productos, sus cantidades, su precio y su descuento.
 	 * @param retencion float Cantidad sumada por retenciones
 	 * @param id_comprador int Id del cliente al que se le vende.
 	 * @param subtotal float El total de la venta antes de cargarle impuestos
 	 * @param impuesto float Cantidad sumada por impuestos
 	 * @param total float El total de la venta
 	 * @param descuento float La cantidad que ser descontada a la compra
 	 * @param tipo_venta string Si la venta es a credito o a contado
 	 * @param saldo float La cantidad que ha sido abonada hasta el momento de la venta
 	 * @param cheques json Si el tipo de pago es con cheque, se almacena el nombre del banco, el monto y los ultimos 4 numeros del o de los cheques
 	 * @param tipo_pago string Si el pago ser efectivo, cheque o tarjeta.
 	 * @param billetes_pago json Ids de los billetes que se recibieron 
 	 * @param billetes_cambio json Ids de billetes que se entregaron como cambio
 	 * @return id_venta int Id autogenerado de la inserción de la venta.
 	 **/
  function VenderCaja
	(
		$detalle, 
		$retencion, 
		$id_comprador, 
		$subtotal, 
		$impuesto, 
		$total, 
		$descuento, 
		$tipo_venta, 
		$saldo = null, 
		$cheques = null, 
		$tipo_pago = null, 
		$billetes_pago = null, 
		$billetes_cambio = null
	);  
  
  
	
  
	/**
 	 *
 	 *Edita los datos de una sucursal
 	 *
 	 * @param coidgo_postal string Codigo Postal de la sucursal
 	 * @param colonia string Colonia de la sucursal
 	 * @param calle string Calle de la sucursal
 	 * @param municipio int Municipio de la sucursal
 	 * @param rfc string Rfc de la sucursal
 	 * @param numero_exterior string Numero exterior de la sucursal
 	 * @param razon_social string Razon social de la sucursal
 	 * @param saldo_a_favor float Saldo a favor de la sucursal
 	 * @param id_sucursal int Id de la sucursal a modificar
 	 * @param empresas json Objeto que contendra los ids de las empresas a las que esta sucursal pertenece, por lo menos tiene que haber una empresa. En este JSON, opcionalmente junto con el id de la empresa, aapreceran dos campos que seran margen_utilidad y descuento, que indicaran que todos los productos de esa empresa ofrecidos en esta sucursal tendran un margen de utilidad y/o un descuento con los valores en esos campos
 	 * @param numero_interior string Numero interior de la sucursal
 	 * @param impuestos json Objeto que contendra los ids de los impuestos que afectana esta sucursal
 	 * @param id_gerente int Id del gerente de la sucursal
 	 * @param telefono1 string telefono 1 de la sucursal
 	 * @param telefono2 string telefono 2 de la sucursal
 	 * @param descripcion string Descripcion de la sucursal
 	 * @param margen_utilidad float Porcentaje del margen de utilidad que se obtendra de los productos vendidos en esta sucursal
 	 * @param descuento float Descuento que tendran los productos ofrecidos por esta sucursal
 	 **/
  function Editar
	(
		$coidgo_postal, 
		$colonia, 
		$calle, 
		$municipio, 
		$rfc, 
		$numero_exterior, 
		$razon_social, 
		$saldo_a_favor, 
		$id_sucursal, 
		$empresas, 
		$numero_interior = null, 
		$impuestos = null, 
		$id_gerente = null, 
		$telefono1 = null, 
		$telefono2 = null, 
		$descripcion = null, 
		$margen_utilidad = null, 
		$descuento = null
	);  
  
  
	
  
	/**
 	 *
 	 *Desactiva una sucursal. Para poder desactivar una sucursal su saldo a favor tiene que ser mayor a cero y sus almacenes tienen que estar vacios.
 	 *
 	 * @param id_sucursal int Id de la sucursal a desactivar
 	 **/
  function Eliminar
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
  function EditarGerencia
	(
		$id_sucursal, 
		$id_gerente
	);  
  
  
	
  
	/**
 	 *
 	 *Lista las sucursales relacionadas con esta instancia. Se puede filtrar por empresa,  saldo inferior o superior a, fecha de apertura, ordenar por fecha de apertura u ordenar por saldo. Se agregar?n link en cada una para poder acceder a su detalle.
 	 *
 	 * @param activo bool Si este valor no es pasado, se listaran sucursales tanto activas como inactivas, si su valor es true, solo se mostrarn las sucursales activas, si es false, solo se mostraran las sucursales inactivas.
 	 * @param id_empresa int Id de la empresa de la cual se listaran sus sucursales.
 	 * @param saldo_inferior_que float Si este valor es obtenido, se mostrarán las sucursales que tengan un saldo inferior a este
 	 * @param saldo_igual_que float Si este valor es obtenido, se mostrarán las sucursales que tengan un saldo igual a este
 	 * @param saldo_superior_que float Si este valor es obtenido, se mostrarán las sucursales que tengan un saldo superior a este
 	 * @param fecha_apertura_inferior_que string Si este valor es pasado, se mostraran las sucursales cuya fecha de apertura sea inferior a esta.
 	 * @param fecha_apertura_igual_que string Si este valor es pasado, se mostraran las sucursales cuya fecha de apertura sea igual a esta.
 	 * @param fecha_apertura_superior_que string Si este valor es pasado, se mostraran las sucursales cuya fecha de apertura sea superior a esta.
 	 * @return sucursales json Objeto que contendra la lista de sucursales.
 	 **/
  function Lista
	(
		$activo, 
		$id_empresa = null, 
		$saldo_inferior_que = null, 
		$saldo_igual_que = null, 
		$saldo_superior_que = null, 
		$fecha_apertura_inferior_que = null, 
		$fecha_apertura_igual_que = null, 
		$fecha_apertura_superior_que = null
	);  
  
  
	
  
	/**
 	 *
 	 *Metodo que crea una nueva sucursal
 	 *
 	 * @param codigo_postal string Codigo postal de la empresa
 	 * @param rfc string RFC de la sucursal
 	 * @param activo bool Si esta sucursal estara activa inmediatamente despues de ser creada
 	 * @param colonia string Colonia de la sucursal
 	 * @param razon_social string Razon social de la sucursal
 	 * @param calle string Calle de la sucursal
 	 * @param empresas json Objeto que contendra los ids de las empresas a las que esta sucursal pertenece, por lo menos tiene que haber una empresa. En este JSON, opcionalmente junto con el id de la empresa, aapreceran dos campos que seran margen_utilidad y descuento, que indicaran que todos los productos de esa empresa ofrecidos en esta sucursal tendran un margen de utilidad y/o un descuento con los valores en esos campos
 	 * @param numero_exterior string Numero exterior de la sucursal
 	 * @param id_ciudad int Id de la ciudad donde se encuentra la sucursal
 	 * @param saldo_a_favor float Saldo a favor de la sucursal.
 	 * @param id_gerente int ID del usuario que sera gerente de esta sucursal. Para que sea valido este usuario debe tener el nivel de acceso apropiado.
 	 * @param margen_utilidad float Margen de utilidad que se le ganara a todos los productos ofrecidos por esta sucursal
 	 * @param numero_interior string numero interior
 	 * @param telefono2 string Telefono2 de la sucursal
 	 * @param telefono1 string Telefono1 de la sucursal
 	 * @param descripcion string Descripcion de la sucursal
 	 * @param impuestos json Objeto que contendra el arreglo de impuestos que afectan a esta sucursal
 	 * @param descuento float Descuento que tendran todos los productos ofrecidos por esta sucursal
 	 * @return id_sucursal int Id autogenerado de la sucursal que se creo.
 	 **/
  function Nueva
	(
		$codigo_postal, 
		$rfc, 
		$activo, 
		$colonia, 
		$razon_social, 
		$calle, 
		$empresas, 
		$numero_exterior, 
		$id_ciudad, 
		$saldo_a_favor, 
		$id_gerente = null, 
		$margen_utilidad = null, 
		$numero_interior = null, 
		$telefono2 = null, 
		$telefono1 = null, 
		$descripcion = null, 
		$impuestos = null, 
		$descuento = null
	);  
  
  
	
  }
