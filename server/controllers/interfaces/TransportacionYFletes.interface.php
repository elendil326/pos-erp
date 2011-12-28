<?php
/**
  *
  *
  *
  **/
	
  interface ITransportacionYFletes {
  
  
	/**
 	 *
 	 *Realizar un cargamento a un carro. El id de la sucursal sera tomada de la sesion actual. La fecha sera tomada del servidor. El inventario de la sucursal que carga el camion se vera afectado por esta operacion.
 	 *
 	 * @param id_carro int Id del carro que sera cargado
 	 * @param productos json Objeto que contendra los id de productos como sus cantidades en las que son cargados al vehiculo
 	 **/
  static function Cargar
	(
		$id_carro, 
		$productos
	);  
  
  
	
  
	/**
 	 *
 	 *Descargar producto de un carro. El id de la sucursal se tomara de la sesion actual. La fecha se tomara del servidor. El almacen de la sucursal que realiza la operacion se vera afectada.
 	 *
 	 * @param id_carro int Id del carro que se descarga
 	 * @param productos json Objeto que contendra los productos con sus cantidades
 	 **/
  static function Descargar
	(
		$id_carro, 
		$productos
	);  
  
  
	
  
	/**
 	 *
 	 *Ver los detalles e historial de un carro especifico
 	 *
 	 * @param id_carro int Id del carro a detallar
 	 * @return carro json Objeto que contendra los detalles y el historial del carro
 	 **/
  static function Detalle
	(
		$id_carro
	);  
  
  
	
  
	/**
 	 *
 	 *Edita la informacion de un carro
 	 *
 	 * @param id_carro int Id del carro a editar
 	 * @param codigo string Codigo interno del vehiculo
 	 * @param combustible float Cantidad de combustible que tiene el vehiculo
 	 * @param ids_empresas json Los ids de las empresas a las que este vehiculo pertenece. Si este valor no se pasa, se tomara que el vehiculo pertenece a todas las empresas.
 	 * @param id_estado int Id del estado del vehiculo, debera contener [en servicio], [accidentado], [averiado], [baja definitiva], [taller]
 	 * @param id_localizacion int Id de la sucursal en la que se encuentra, se tomara un entero especial para determinar que se encuentra en una ruta.
 	 * @param id_marca_carro int Id de la marca del vehiculo (Nissan, Mazda)
 	 * @param id_modelo_vehiculo int Id del modelo del vehiculo
 	 * @param id_tipo_carro int Id del tipo de carro (camioneta, coche, camion)
 	 * @param imagen string url del a imagen del vehiculo
 	 * @param kilometros float Kilometros recorridos por el vehiculo
 	 * @param km_por_litro float numero de kilometros que puede trnasitar el vehiculo por un litro de combustible
 	 * @param matricula string Matricula del vehiculo
 	 * @param num_neumaticos int Numero de neumaticos del vehiculo
 	 **/
  static function Editar
	(
		$id_carro, 
		$codigo = null, 
		$combustible = null, 
		$ids_empresas = null, 
		$id_estado = null, 
		$id_localizacion = null, 
		$id_marca_carro = null, 
		$id_modelo_vehiculo = null, 
		$id_tipo_carro = null, 
		$imagen = null, 
		$kilometros = null, 
		$km_por_litro = null, 
		$matricula = null, 
		$num_neumaticos = null
	);  
  
  
	
  
	/**
 	 *
 	 *Enviar un cargamento. No necesariamente debe tener cargamento. Seria excelente calcular el kilometraje. La sucursal origen sera tomada de la sesion actual.
 	 *
 	 * @param fecha_llegada_tentativa string Fecha tentativa en la que se espera que el carro llegue a su destino.
 	 * @param fecha_salida string Fecha en la que se planea que salga carro
 	 * @param id_carro int Carro que sera enrutado.
 	 * @param id_sucursal_destino int Id de la sucursal destino
 	 **/
  static function Enrutar
	(
		$fecha_llegada_tentativa, 
		$fecha_salida, 
		$id_carro, 
		$id_sucursal_destino
	);  
  
  
	
  
	/**
 	 *
 	 *Lista todos los carros de la instancia. Puede filtrarse por empresa, por su estado y ordenarse por sus atributos
 	 *
 	 * @param id_empresa int Id de la empresa de la cual se listaran sus vehiculos
 	 * @param id_estado int Valor que determina si se mostraran los coches de un cierto estado.
 	 * @param orden string Nombre de la columan por el cual se ordenara la lista
 	 * @return carros json Lista de carros con datos generales.
 	 **/
  static function Lista
	(
		$id_empresa = null, 
		$id_estado = null, 
		$orden = null
	);  
  
  
	
  
	/**
 	 *
 	 *Edita una marca de un carro
 	 *
 	 * @param id_marca_carro int Id de la marca que sera editada
 	 * @param activo bool Si la marca estara habilitada para su seleccion
 	 * @param nombre_marca string Nombre de la marca del  carro
 	 **/
  static function EditarMarca
	(
		$id_marca_carro, 
		$activo = true, 
		$nombre_marca = null
	);  
  
  
	
  
	/**
 	 *
 	 *Agrega una nueva marca de carro
 	 *
 	 * @param nombre_marca string Nombre de la marca del carro ( Mazda, Nissan, etc)
 	 * @param activo bool Si la marca estara disponible para su seleccion
 	 * @return id_marca_carro int Id autogenerado por la insercion de la nueva marca
 	 **/
  static function NuevoMarca
	(
		$nombre_marca, 
		$activo = null
	);  
  
  
	
  
	/**
 	 *
 	 *Editar el modelo del carro
 	 *
 	 * @param id_modelo_carro int Id del modelo de carro
 	 * @param nombre_modelo_carro string Nombre del modelo del carro ( 99,2010, etc)
 	 * @param activo bool Si el modelo sera activo para seleccionarse
 	 **/
  static function EditarModelo
	(
		$id_modelo_carro, 
		$nombre_modelo_carro, 
		$activo = true
	);  
  
  
	
  
	/**
 	 *
 	 *Crea un nuevo modelo de carro
 	 *
 	 * @param nombre_modelo string Modelo del carro (98,99,2010,etc)
 	 * @param activo bool Si el modelo estara activo para su seleccion
 	 * @return id_modelo int Id del modelo autogenerado por la insercion
 	 **/
  static function NuevoModelo
	(
		$nombre_modelo, 
		$activo = true
	);  
  
  
	
  
	/**
 	 *
 	 *Crea un nuevo carro. La fecha de creacion sera tomada del servidor.
 	 *
 	 * @param id_estado int Id del estado del vehiculo, debera contener [en servicio], [accidentado], [averiado], [baja definitiva], [taller]
 	 * @param id_localizacion int Id de la sucursal en la que se encuentra, se tomara un entero especial para determinar que se encuentra en una ruta.
 	 * @param id_marca_carro int Id de la marca del vehiculo (Nissan, Mazda)
 	 * @param id_modelo_vehiculo int Id del modelo del vehiculo
 	 * @param id_tipo_carro int Id del tipo de carro (camioneta, coche, camion)
 	 * @param imagen string url del a imagen del vehiculo
 	 * @param codigo string Codigo interno del vehiculo
 	 * @param combustible float Cantidad de combustible que tiene el vehiculo
 	 * @param ids_empresas json Los ids de las empresas a las que este vehiculo pertenece. Si este valor no se pasa, se tomara que el vehiculo pertenece a todas las empresas.
 	 * @param kilometros float Kilometros recorridos por el vehiculo
 	 * @param km_por_litro float numero de kilometros que puede trnasitar el vehiculo por un litro de combustible
 	 * @param matricula string Matricula del vehiculo
 	 * @param num_neumaticos int Numero de neumaticos del vehiculo
 	 **/
  static function Nuevo
	(
		$id_estado, 
		$id_localizacion, 
		$id_marca_carro, 
		$id_modelo_vehiculo, 
		$id_tipo_carro, 
		$imagen, 
		$codigo = null, 
		$combustible = null, 
		$ids_empresas = null, 
		$kilometros = null, 
		$km_por_litro = null, 
		$matricula = null, 
		$num_neumaticos = null
	);  
  
  
	
  
	/**
 	 *
 	 *Registra la llegada de un carro a una sucursal. La fecha sera tomada del servidor
 	 *
 	 * @param id_carro int Id del carro del cual se registra su llegada
 	 * @param fecha_llegada string Registra la fecha de llegada en caso de que haya pasado un retraso y no se haya  registrado a tiempo la llegada
 	 **/
  static function Registrar_llegada
	(
		$id_carro, 
		$fecha_llegada = null
	);  
  
  
	
  
	/**
 	 *
 	 *Edita un registro de tipo de carro (camion, camioneta, etc)
 	 *
 	 * @param id_tipo_carro int Id del tipo de carro a editar
 	 * @param activo bool Si este carro va a estar activo para su seleccion
 	 * @param nombre_tipo_carro string Nombre del tipo de carro
 	 **/
  static function EditarTipo
	(
		$id_tipo_carro, 
		$activo = true, 
		$nombre_tipo_carro = null
	);  
  
  
	
  
	/**
 	 *
 	 *Agrega un nuevo tipo de carro ( camion, camioneta, etc)
 	 *
 	 * @param nombre_tipo string Nombre del tipo de carro 
 	 * @param activo bool SI este tipo de carro sera valido para seleccionarlo o no
 	 * @return id_tipo_carro int Id autogenerado por la insercion del tipo de carro
 	 **/
  static function NuevoTipo
	(
		$nombre_tipo, 
		$activo = true
	);  
  
  
	
  
	/**
 	 *
 	 *Mover mercancia de un carro a otro. 
UPDATE
Se movera parcial o totalmente la carga?
 	 *
 	 * @param id_carro_destino int Id del carro al que se mueve la carga
 	 * @param id_carro_origen int Id del carro del cual se mueve la carga
 	 * @param productos json Productos que se mueve de un carro a otros.
 	 **/
  static function Transbordo
	(
		$id_carro_destino, 
		$id_carro_origen, 
		$productos = null
	);  
  
  
	
  }
