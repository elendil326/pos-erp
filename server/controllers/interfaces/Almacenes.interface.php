<?php
/**
  *
  *
  *
  **/
	
  interface IAlmacenes {
  
  
	/**
 	 *
 	 *listar almacenes de la isntancia. Se pueden filtrar por empresa, por sucursal, por tipo de almacen, por activos e inactivos y ordenar por sus atributos...........s
 	 *
 	 * @param id_empresa int Id de la empresa de la cual se listaran sus almacenes
 	 * @return resultados json Almacenes encontrados.
 	 * @return numero_de_resultados int 
 	 **/
  static function Buscar
	(
		$id_empresa = null
	);  
  
  
	
  
	/**
 	 *
 	 *Descativa un almacen. Para poder desactivar un almacen, este tiene que estar vac?o
 	 *
 	 * @param id_almacen int Id del almacen a desactivar
 	 **/
  static function Desactivar
	(
		$id_almacen
	);  
  
  
	
  
	/**
 	 *
 	 *Edita la informacion de un almacen
 	 *
 	 * @param id_almacen int Id del almacen a editar
 	 * @param descripcion string Descripcion del almacen
 	 * @param id_tipo_almacen int Id del tipo de almacen al que sera cambiado. No se puede cambiar este parametro si se trata de un almacen de consignacion ni se puede editar para que sea un almacen de consignacion
 	 * @param nombre string Nombre del almacen
 	 **/
  static function Editar
	(
		$id_almacen, 
		$descripcion = null, 
		$id_tipo_almacen = null, 
		$nombre = null
	);  
  
  
	
  
	/**
 	 *
 	 *Metodo que surte una sucursal por parte de un proveedor. La sucursal sera tomada de la sesion actual.

 	 *
 	 * @param id_lote int Id del lote que se generó previamente y es el que recibe los productos
 	 * @param productos json Objeto que contendra los ids de los productos, sus unidades y sus cantidades
 	 * @param motivo string Motivo del movimiento
 	 * @return id_entrada_lote string Id generado por el registro de surtir
 	 **/
  static function EntradaLote
	(
		$id_lote, 
		$productos, 
		$motivo = null
	);  
  
  
	
  
	/**
 	 *
 	 *Crear un nuevo lote
 	 *
 	 * @param id_almacen int A que almacen pertenecera este lote.
 	 * @param observaciones string Alguna observacin o detalle relevante que se deba documentar
 	 * @return id_lote int El identificador del lote recien generado.
 	 **/
  static function NuevoLote
	(
		$id_almacen, 
		$observaciones = null
	);  
  
  
	
  
	/**
 	 *
 	 *Envia productos fuera del almacen. Ya sea que sea un traspaso de un alamcen a otro o por motivos de inventarios fisicos.
 	 *
 	 * @param id_lote int Id del lote de donde se descontaran los productos.
 	 * @param productos json Objeto que contendra los ids de los productos que seran sacados del alamcen con sus cantidades y sus unidades
 	 * @param motivo string Motivo de la salida del producto
 	 * @return id_salida_lote int ID de la salida del producto
 	 **/
  static function SalidaLote
	(
		$id_lote, 
		$productos, 
		$motivo = null
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
 	 * @return resultados json Lista de traspasos
 	 * @return numero_de_resultados int 
 	 **/
  static function BuscarTraspasoLote
	(
		$cancelado = null, 
		$completo = null, 
		$estado = null, 
		$id_almacen_envia = null, 
		$id_almacen_recibe = null
	);  
  
  
	
  
	/**
 	 *
 	 *Para poder cancelar un traspaso, este no tuvo que haber sido enviado aun.
 	 *
 	 * @param id_traspaso int Id del traspaso a cancelar
 	 **/
  static function CancelarTraspasoLote
	(
		$id_traspaso
	);  
  
  
	
  
	/**
 	 *
 	 *Para poder editar un traspaso,este no tuvo que haber sido enviado aun
 	 *
 	 * @param id_sucursal string Id de la sucursal que recibir el traspaso
 	 * @param id_traspaso int Id del traspaso a editar
 	 * @param productos json Productos a enviar con sus cantidades y respectivos lotes del cual saldran
 	 * @param fecha_envio_programada string Fecha de envio programada
 	 **/
  static function EditarTraspasoLote
	(
		$id_sucursal, 
		$id_traspaso, 
		$productos, 
		$fecha_envio_programada = null
	);  
  
  
	
  
	/**
 	 *
 	 *Cambia el estado del traspaso a enviado y captura la fecha de envio del servidor. El usuario que envia sera tomado del servidor.
 	 *
 	 * @param id_traspaso int Id del traspaso a enviar
 	 **/
  static function EnviarTraspasoLote
	(
		$id_traspaso
	);  
  
  
	
  
	/**
 	 *
 	 *Crea un registro de traspaso de productos de un almacen a otro. El usuario que envia sera tomada de la sesion.
 	 *
 	 * @param fecha_envio_programada string Fecha de envi programada
 	 * @param id_sucursal string Id de la sucursal que va a recibir el producto
 	 * @param productos json Conjunto de productos que se van a traspasar.
 	 * @return id_traspaso int Id del traspaso que se genero
 	 **/
  static function NuevoTraspasoLote
	(
		$fecha_envio_programada, 
		$id_sucursal, 
		$productos
	);  
  
  
	
  
	/**
 	 *
 	 *ESTO NO SE DEBE DE TOMAR EN CUENTA, PARA ESO ESTA NUEVO Crea un registro de traspaso de producto de un almacen a otro. El usuario que envia sera tomada de la sesion.
 	 *
 	 * @param fecha_envio_programada string Fecha de envio programada para este traspaso
 	 * @param id_almacen_envia int Id del almacen que envia el producto
 	 * @param id_almacen_recibe int Id del almacen al que se envia el producto
 	 * @param productos json Productos a ser enviados con sus cantidades
 	 * @return id_traspaso int Id del traspaso autogenerado
 	 **/
  static function ProgramarTraspasoLote
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
 	 * @param productos json Productos que se reciben con sus cantidades y a su respectivo lote al cual se iran
 	 **/
  static function RecibirTraspasoLote
	(
		$id_traspaso, 
		$productos
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
  static function Nuevo
	(
		$id_empresa, 
		$id_sucursal, 
		$id_tipo_almacen, 
		$nombre, 
		$descripcion = null
	);  
  
  
	
  
	/**
 	 *
 	 *Imprime la lista de tipos de almacen
 	 *
 	 * @param activo bool Si este valor no es pasado, se listaran los tipos de almacen tanto activos como inactivos, si su valor es true, solo se mostraran los tipos de amacen activos, si es false, solo se mostraran los tipos de almacén inactivos.
 	 * @param limit string Indica hasta que registro se desea obtener a partir del conjunto de resultados productos de la busqueda.
 	 * @param query string Valor que se buscara en la consulta
 	 * @param start string Indica desde que registro se desea obtener a partir del conjunto de resultados productos de la busqueda.
 	 * @return numero_de_resultados int Arreglo con la lista de almacenes
 	 * @return resultados json 
 	 **/
  static function BuscarTipo
	(
		$activo = null, 
		$limit = null, 
		$query = null, 
		$start = null
	);  
  
  
	
  
	/**
 	 *
 	 *Elimina un tipo de almacen
 	 *
 	 * @param id_tipo_almacen int Id del tipo de almacen a editar
 	 **/
  static function DesactivarTipo
	(
		$id_tipo_almacen
	);  
  
  
	
  
	/**
 	 *
 	 *Edita un tipo de almacen
 	 *
 	 * @param id_tipo_almacen int Id del tipo de almacen a editar
 	 * @param activo bool Indica si el tipo almacén se activa o desactiva
 	 * @param descripcion string Descripcion del tipo de almacen
 	 **/
  static function EditarTipo
	(
		$id_tipo_almacen, 
		$activo = null, 
		$descripcion = null
	);  
  
  
	
  
	/**
 	 *
 	 *Crea un nuevo tipo de almacen
 	 *
 	 * @param descripcion string Descripcion de este tipo de almacen
 	 * @param activo bool Indica si el tipo de almacen esta activo, en caso de no especificarlo el valor por default sera true
 	 * @return id_tipo_almacen int Id del tipo de almacen
 	 **/
  static function NuevoTipo
	(
		$descripcion, 
		$activo = null
	);  
  
  
	
  }
