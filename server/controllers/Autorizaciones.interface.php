<?php
/**
  *
  *
  *
  **/
	
  interface IAutorizaciones {
  
  
	/**
 	 *
 	 *Solicitud para cambiar alg?ato de un cliente. La fecha de petici?e tomar?el servidor. El usuario y la sucursal que emiten la autorizaci?er?tomadas de la sesi?
<br><br>
La autorizacion se guardara con los datos del usuario que la pidio. Si es aceptada, entonces el usuario podra editar al cliente una vez.
 	 *
 	 * @param id_cliente int Id del cliente al que se le pide editar el lmite de crdito.
 	 * @return id_autorizacion int El id de la autorizacion creada
 	 **/
  function EditarCliente
	(
		$id_cliente
	);  
  
  
	
  
	/**
 	 *
 	 *Solicitud para devolver una compra. La fecha de petici?e tomar?el servidor. El usuario y la sucursal que emiten la autorizaci?er?tomadas de la sesi?
 	 *
 	 * @param id_compra int Id de la compra a devolver
 	 * @param descripcion int Una descripcion para justificar esto.
 	 * @return id_autorizacion int El id de la autorizacion recien creada
 	 **/
  function DevolucionCompra
	(
		$id_compra, 
		$descripcion = null
	);  
  
  
	
  
	/**
 	 *
 	 *Muestra la informacion detallada de una autorizacion.
 	 *
 	 * @param id_autorizacion	 int Id de la autorizacion a inspeccionar.
 	 * @return id_autorizacion int El id de esta autorizacion
 	 * @return id_sucursal int El id de la sucursal donde se inicio la peticion
 	 * @return id_solicitante int El id del usuario que pidio la autorizacion
 	 * @return fecha_peticion string La fecha cuando la peticion se hizo.
 	 * @return fecha_respuesta string La fecha cuando la peticion se soluciono, o respondio por un administrador.
 	 **/
  function Detalle
	(
		$id_autorizacion	
	);  
  
  
	
  
	/**
 	 *
 	 *Editar una autorizacion en caso de tener permiso.

<br/><br/><b>Update : </b> Creo que seriabuena idea que se definiera de una vez la estructura de las autorizaciones, ya que como se maneja actualemnte es de la siguiente manera : 

Digo que seria buena idea definir el formato de las autorizaciones para ir pensando en como en un futuro se van a mostrar en las interfaces, apartir de que se se crearan los formularios, actualmente se toma el campo <b>tipo</b> para de ahi saber que tipo de autorizacion es y crear un formulario de este tipo para desplegar los datos, y dependiendo del <b>tipo</b> se identifica que formato de JSON se espera que contenga el campo <b>parametros</b> .

<br/><br/>

Al momento de editar la autorizacion veo que aparentemente se podria editar el id_autorizacion, id_usuario, id_sucursal, peticion y estado, creo yo que no es prudente editar ninguno de estos campos ya que el mal uso de esta informacion puede da?gravemente la integridad del sistema.
 	 *
 	 * @param descripcion string Justificacin de la solicitud.
 	 * @param id_autorizacion	 int Id de la autorizacin a modificar
 	 * @param estado int Id del estado de la autorizacin
 	 **/
  function Editar
	(
		$descripcion, 
		$id_autorizacion	, 
		$estado
	);  
  
  
	
  
	/**
 	 *
 	 *Solicitud para cambiar la relaci?ntre cliente y el precio ofrecido para cierto producto ya sea en compra o en venta. La fecha de peticion se tomar?el servidor. El usuario y la sucursal que emiten la autorizaci?er?tomadas de la sesi?

<br/><br/><b>UPDATE :</b> Actualmente como se maneja esto es por medio de las ventas preferenciales, es decir, se manda una autorizaci?ara que el cajero pueda editar todos los precios que desee, de todos los productos "solo para esa venta y solo para ese cliente especificamente", ya que si el cliente quisiera que le vendieran mas de un solo producto a diferente precio tendr? que generar mas de una autorizaci?esto implica un incremento considerable en el tiempo de respuesta y aplicaci?e los cambios.

<br/><br/><b>UPDATE 2:</b> Creo que los metodos : 
<br/><i><b>api/autorizaciones/editar_precio_cliente</b></i> y <i><b>api/autorizaciones/editar_siguiente_compra_venta_precio_cliente</b></i>
<br/>Se podr? combinar y as?ener un solo m?do para una compra venta preferencial.
 	 *
 	 * @param siguiente_compra bool Si es true, el cambio solo se acplicara a la siguiente compra/venta, pero si es false, el cambio se hara sobre la relacion del cliente con el tipo de precio
 	 * @param id_cliente int Id del cliente al que se le har el cambio.
 	 * @param descripcion string Justificacin del cambio de precio del cliente.
 	 * @param id_productos json Arreglo de Ids de los productos en los que se hara el cambio 
 	 * @param compra bool Si es true, el nuevo precio ser requerido para compras en el producto especificado, si es false, el nuevo precio ser requerido para ventas en el producto especificado.
 	 * @param precio float Si el precio deseado no se encuentra en los campos del precio de acuerdo al tipo del cliente, se pued especificar el precio que se desea dar.
 	 * @param id_precio int Id del nuevo precio requerido.
 	 **/
  function Editar_precio_cliente
	(
		$siguiente_compra, 
		$id_cliente, 
		$descripcion, 
		$id_productos, 
		$compra, 
		$precio = null, 
		$id_precio = null
	);  
  
  
	
  
	/**
 	 *
 	 *La fecha de peticion se tomar?el servidor. El usuario y la sucursal que emiten la autorizaci?er?tomadas de la sesi?
 	 *
 	 * @param monto float Monto a gastar
 	 * @param descripcion string Justificación por la cual se pide el gasto.
 	 **/
  function Gasto
	(
		$monto, 
		$descripcion
	);  
  
  
	
  
	/**
 	 *
 	 *Muestra la lista de autorizaciones, con la opci?e filtrar por pendientes, aceptadas, rechazadas, en tr?ito, embarques recibidos y de ordenar seg?os atributos de autorizaciones. 
<br/><br/><b>Update : </b> falta definir el ejemplo de envio.
 	 *
 	 * @param filtro json Valor numrico que definir que filtro se pondr a la lista.
 	 * @param ordenar json Valor numrico que definir el orden de la lista.
 	 * @return autorizaciones json Arreglo de objetos que contendrá las autorizaciones
 	 **/
  function Lista
	(
		$filtro = null, 
		$ordenar = null
	);  
  
  
	
  
	/**
 	 *
 	 *Responde a una autorizaci?n estado pendiente. Este m?do no se puede aplicar a una autorizaci?a resuelta.
 	 *
 	 * @param aceptar bool Valor booleano que indicara si se debe aceptar o no esta autorizacion.
 	 * @param id_autorizacion int Id de la autorizacin a responder
 	 **/
  function Responder
	(
		$aceptar, 
		$id_autorizacion
	);  
  
  
	
  
	/**
 	 *
 	 *Solicitud de un producto, la fecha de peticion se tomar?el servidor. El usuario y la sucursal que emiten la autorizaci?er?tomadas de la sesi?
<br/><br/><b>Update : </b> Me parece que no es buena idea manejar en los argumentos solo un id_producto y cantidad, creo que seria mejor manejar un array de objetos producto, que tuvieran como propiedades el id del producto y la cantidad solicitada, ya que si por ejemplo llega un cliente grande y necesita mas de un producto, y no pudiera cubrir la cantidad solicitada, por cada producto tendr? que solicitar una autorizaci?
 
 	 *
 	 * @param descripcion string Justificacin del porqu la solicitud del producto.
 	 * @param productos json Json que contendra los ids de los productos con sus respectivas cantidades.
 	 **/
  function Solicitar_producto
	(
		$descripcion, 
		$productos
	);  
  
  
	
  
	/**
 	 *
 	 *Solicitud para devolver una venta. La fecha de petici?e tomar?el servidor. El usuario y la sucursal que emiten la autorizaci?er?tomadas de la sesi?
 	 *
 	 * @param id_venta int Id de la venta a devolver
 	 * @param descripcion string Justificacin de la devolucin de la compra
 	 * @return id_autorizacion int El id de la nueva autorizacion 
 	 **/
  function DevolucionVenta
	(
		$id_venta, 
		$descripcion
	);  
  
  
	
  }
