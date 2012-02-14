<?php
/**
  *
  *
  *
  **/
	
  interface IAlmacenes {
  
  
	/**
 	 *
 	 *listar almacenes de la isntancia. Se pueden filtrar por empresa, por sucursal, por tipo de almacen, por activos e inactivos y ordenar por sus atributos.
 	 *
 	 * @param activo bool Si este valor no es obtenido, se mostraran almacenes tanto activos como inactivos. Si es verdadero, solo se lsitaran los activos, si es falso solo se lsitaran los inactivos.
 	 * @param id_empresa int Id de la empresa de la cual se listaran sus almacenes
 	 * @param id_sucursal int el id de la sucursal de la cual se listaran sus almacenes
 	 * @param id_tipo_almacen int Se listaran los almacenes de este tipo
 	 * @return numero_de_resultados int 
 	 * @return resultados json Almacenes encontrados
 	 **/
  static function Buscar
	(
		$activo = null, 
		$id_empresa = null, 
		$id_sucursal = null, 
		$id_tipo_almacen = null
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

Update
Creo que este metodo tiene que estar bajo sucursal.
 	 *
 	 * @param id_almacen int Id del almacen que se surte
 	 * @param productos json Objeto que contendr los ids de los productos, sus unidades y sus cantidades
 	 * @param motivo string Motivo del movimiento
 	 * @return id_surtido string Id generado por el registro de surtir
 	 **/
  static function Entrada
	(
		$id_almacen, 
		$productos, 
		$motivo = null
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
 	 *Envia productos fuera del almacen. Ya sea que sea un traspaso de un alamcen a otro o por motivos de inventarios fisicos.
 	 *
 	 * @param id_almacen int Id del almacen del cual se hace el movimiento
 	 * @param productos json Objeto que contendra los ids de los productos que seran sacados del alamcen con sus cantidades y sus unidades
 	 * @param motivo string Motivo de la salida del producto
 	 * @return id_salida int ID de la salida del producto
 	 **/
  static function Salida
	(
		$id_almacen, 
		$productos, 
		$motivo = null
	);  
  
  
	
  
	/**
 	 *
 	 *Imprime la lista de tipos de almacen
 	 *
 	 * @param query string Buscar por descripcion
 	 * @return lista_tipos_almacen json Arreglo con la lista de almacenes
 	 **/
  static function BuscarTipo
	(
		$query = null
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
 	 * @param descripcion string Descripcion del tipo de almacen
 	 **/
  static function EditarTipo
	(
		$id_tipo_almacen, 
		$descripcion = null
	);  
  
  
	
  
	/**
 	 *
 	 *Crea un nuevo tipo de almacen
 	 *
 	 * @param descripcion string Descripcion de este tipo de almacen
 	 * @return id_tipo_almacen int Id del tipo de almacen
 	 **/
  static function NuevoTipo
	(
		$descripcion
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
  static function BuscarTraspaso
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
 	 *Para poder cancelar un traspaso, este no tuvo que haber sido enviado aun.
 	 *
 	 * @param id_traspaso int Id del traspaso a cancelar
 	 **/
  static function CancelarTraspaso
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
  static function EditarTraspaso
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
  static function EnviarTraspaso
	(
		$id_traspaso
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
  static function ProgramarTraspaso
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
  static function RecibirTraspaso
	(
		$id_traspaso, 
		$productos
	);  
  
  
	
  }
