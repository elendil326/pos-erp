<?php
require_once("interfaces/Autorizaciones.interface.php");
/**
  *
  *
  *
  **/
	
  class AutorizacionesController implements IAutorizaciones{
  
  
	/**
 	 *
 	 *La fecha de peticion se tomar? del servidor. El usuario y la sucursal que emiten la autorizaci?n ser?n tomadas de la sesi?n.
 	 *
 	 * @param monto float Monto a gastar
 	 * @param descripcion string Justificaci�n por la cual se pide el gasto.
 	 **/
	public static function Gasto
	(
		$descripcion, 
		$monto
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Solicitud para cambiar alg?n dato de un cliente. La fecha de petici?n se tomar? del servidor. El usuario y la sucursal que emiten la autorizaci?n ser?n tomadas de la sesi?n.

La autorizacion se guardara con los datos del usuario que la pidio. Si es aceptada, entonces el usuario podra editar al cliente una vez.
 	 *
 	 * @param id_cliente int Id del cliente al que se le pide editar el lmite de crdito.
 	 * @return id_autorizacion int El id de la autorizacion creada
 	 **/
	public static function EditarCliente
	(
		$id_cliente
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Solicitud para devolver una compra. La fecha de petici?n se tomar? del servidor. El usuario y la sucursal que emiten la autorizaci?n ser?n tomadas de la sesi?n.
 	 *
 	 * @param id_compra int Id de la compra a devolver
 	 * @param descripcion int Una descripcion para justificar esto.
 	 * @return id_autorizacion int El id de la autorizacion recien creada
 	 **/
	public static function DevolucionCompra
	(
		$id_compra, 
		$descripcion = ""
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Solicitud para devolver una venta. La fecha de petici?n se tomar? del servidor. El usuario y la sucursal que emiten la autorizaci?n ser?n tomadas de la sesi?n.
 	 *
 	 * @param id_venta int Id de la venta a devolver
 	 * @param descripcion string Justificacin de la devolucin de la compra
 	 * @return id_autorizacion int El id de la nueva autorizacion 
 	 **/
	public static function DevolucionVenta
	(
		$descripcion, 
		$id_venta
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Solicitud para cambiar la relaci?n entre cliente y el precio ofrecido para cierto producto ya sea en compra o en venta. La fecha de peticion se tomar? del servidor. El usuario y la sucursal que emiten la autorizaci?n ser?n tomadas de la sesi?n.

UPDATE : Actualmente como se maneja esto es por medio de las ventas preferenciales, es decir, se manda una autorizaci?n para que el cajero pueda editar todos los precios que desee, de todos los productos "solo para esa venta y solo para ese cliente especificamente", ya que si el cliente quisiera que le vendieran mas de un solo producto a diferente precio tendr?as que generar mas de una autorizaci?n, esto implica un incremento considerable en el tiempo de respuesta y aplicaci?n de los cambios.

UPDATE 2: Creo que los metodos : 
api/autorizaciones/editar_precio_cliente y api/autorizaciones/editar_siguiente_compra_venta_precio_cliente
Se podr?an combinar y as? tener un solo m?todo para una compra venta preferencial.
 	 *
 	 * @param siguiente_compra bool Si es true, el cambio solo se acplicara a la siguiente compra/venta, pero si es false, el cambio se hara sobre la relacion del cliente con el tipo de precio
 	 * @param id_cliente int Id del cliente al que se le har el cambio.
 	 * @param descripcion string Justificacin del cambio de precio del cliente.
 	 * @param id_productos json Arreglo de Ids de los productos en los que se hara el cambio 
 	 * @param compra bool Si es true, el nuevo precio ser requerido para compras en el producto especificado, si es false, el nuevo precio ser requerido para ventas en el producto especificado.
 	 * @param precio float Si el precio deseado no se encuentra en los campos del precio de acuerdo al tipo del cliente, se pued especificar el precio que se desea dar.
 	 * @param id_precio int Id del nuevo precio requerido.
 	 **/
	public static function Editar_precio_cliente
	(
		$compra, 
		$descripcion, 
		$id_cliente, 
		$id_productos, 
		$siguiente_compra, 
		$id_precio = null, 
		$precio = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Muestra la lista de autorizaciones, con la opci?n de filtrar por pendientes, aceptadas, rechazadas, en tr?nsito, embarques recibidos y de ordenar seg?n los atributos de autorizaciones. 
Update :  falta definir el ejemplo de envio.
 	 *
 	 * @param filtro json Valor numrico que definir que filtro se pondr a la lista.
 	 * @param ordenar json Valor numrico que definir el orden de la lista.
 	 * @return autorizaciones json Arreglo de objetos que contendr� las autorizaciones
 	 **/
	public static function Lista
	(
		$filtro = null, 
		$ordenar = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Responde a una autorizaci?n en estado pendiente. Este m?todo no se puede aplicar a una autorizaci?n ya resuelta.
 	 *
 	 * @param aceptar bool Valor booleano que indicara si se debe aceptar o no esta autorizacion.
 	 * @param id_autorizacion int Id de la autorizacin a responder
 	 **/
	public static function Responder
	(
		$aceptar, 
		$id_autorizacion
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Solicitud de un producto, la fecha de peticion se tomar? del servidor. El usuario y la sucursal que emiten la autorizaci?n ser?n tomadas de la sesi?n.
Update :  Me parece que no es buena idea manejar en los argumentos solo un id_producto y cantidad, creo que seria mejor manejar un array de objetos producto, que tuvieran como propiedades el id del producto y la cantidad solicitada, ya que si por ejemplo llega un cliente grande y necesita mas de un producto, y no pudiera cubrir la cantidad solicitada, por cada producto tendr?as que solicitar una autorizaci?n.
 
 	 *
 	 * @param descripcion string Justificacin del porqu la solicitud del producto.
 	 * @param productos json Json que contendra los ids de los productos con sus respectivas cantidades.
 	 **/
	public static function ProductoSolicitar
	(
		$descripcion, 
		$productos
	)
	{  
  
  
	}
  
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
	public static function Detalle
	(
		$id_autorizacion	
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Editar una autorizacion en caso de tener permiso.

Update :  Creo que seriabuena idea que se definiera de una vez la estructura de las autorizaciones, ya que como se maneja actualemnte es de la siguiente manera : 

Digo que seria buena idea definir el formato de las autorizaciones para ir pensando en como en un futuro se van a mostrar en las interfaces, apartir de que se se crearan los formularios, actualmente se toma el campo tipo para de ahi saber que tipo de autorizacion es y crear un formulario de este tipo para desplegar los datos, y dependiendo del tipo se identifica que formato de JSON se espera que contenga el campo parametros .



Al momento de editar la autorizacion veo que aparentemente se podria editar el id_autorizacion, id_usuario, id_sucursal, peticion y estado, creo yo que no es prudente editar ninguno de estos campos ya que el mal uso de esta informacion puede da?ar gravemente la integridad del sistema.
 	 *
 	 * @param descripcion string Justificacin de la solicitud.
 	 * @param id_autorizacion	 int Id de la autorizacin a modificar
 	 * @param estado int Id del estado de la autorizacin
 	 **/
	public static function Editar
	(
		$descripcion, 
		$estado, 
		$id_autorizacion
	)
	{  
  
  
	}
        
        /**
 	 *
 	 *Solicitud para cambiar la relaci?n entre cliente y el precio ofrecido para cierto producto ya sea en compra o en venta. La fecha de peticion se tomar? del servidor. El usuario y la sucursal que emiten la autorizaci?n ser?n tomadas de la sesi?n.

UPDATE : Actualmente como se maneja esto es por medio de las ventas preferenciales, es decir, se manda una autorizaci?n para que el cajero pueda editar todos los precios que desee, de todos los productos "solo para esa venta y solo para ese cliente especificamente", ya que si el cliente quisiera que le vendieran mas de un solo producto a diferente precio tendr?as que generar mas de una autorizaci?n, esto implica un incremento considerable en el tiempo de respuesta y aplicaci?n de los cambios.

UPDATE 2: Creo que los metodos : 
api/autorizaciones/editar_precio_cliente y api/autorizaciones/editar_siguiente_compra_venta_precio_cliente
Se podr?an combinar y as? tener un solo m?todo para una compra venta preferencial.
 	 *
 	 * @param compra bool Si es true, el nuevo precio ser requerido para compras en el producto especificado, si es false, el nuevo precio ser requerido para ventas en el producto especificado.
 	 * @param descripcion string Justificacin del cambio de precio del cliente.
 	 * @param id_cliente int Id del cliente al que se le har el cambio.
 	 * @param id_productos json Arreglo de Ids de los productos en los que se hara el cambio 
 	 * @param siguiente_compra bool Si es true, el cambio solo se acplicara a la siguiente compra/venta, pero si es false, el cambio se hara sobre la relacion del cliente con el tipo de precio
 	 * @param id_precio int Id del nuevo precio requerido.
 	 * @param precio float Si el precio deseado no se encuentra en los campos del precio de acuerdo al tipo del cliente, se pued especificar el precio que se desea dar.
 	 **/
        public static function PrecioCliente
	(
		$compra, 
		$descripcion, 
		$id_cliente, 
		$id_productos, 
		$siguiente_compra, 
		$id_precio = null, 
		$precio = null
	)
        {
            
        }
  }
