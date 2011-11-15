<?php
require_once("interfaces/Proveedores.interface.php");
/**
  *
  *
  *
  **/
	
  class ProveedoresController implements IProveedores{
  
      
        //Metodo para pruebas que simula la obtencion del id de la sucursal actual
        private static function getSucursal()
        {
            return 1;
        }
        
        //metodo para pruebas que simula la obtencion del id de la caja actual
        private static function getCaja()
        {
            return 1;
        }
      
        
        /*
         *Se valida que un string tenga longitud en un rango de un maximo inclusivo y un minimo exclusvio.
         *Regresa true cuando es valido, y un string cuando no lo es.
         */
          private static function validarString($string, $max_length, $nombre_variable,$min_length=0)
	{
		if(strlen($string)<=$min_length||strlen($string)>$max_length)
		{
		    return "La longitud de la variable ".$nombre_variable." proporcionada (".$string.") no esta en el rango de ".$min_length." - ".$max_length;
		}
		return true;
        }


        /*
         * Se valida que un numero este en un rango de un maximo y un minimo inclusivos
         * Regresa true cuando es valido, y un string cuando no lo es
         */
	private static function validarNumero($num, $max_length, $nombre_variable, $min_length=0)
	{
	    if($num<$min_length||$num>$max_length)
	    {
	        return "La variable ".$nombre_variable." proporcionada (".$num.") no esta en el rango de ".$min_length." - ".$max_length;
	    }
	    return true;
	}
      
        /*
         * Valida los parametros de la tabla clasificacion proveedor. Regresa un string con el error en caso de haber uno,
         * de lo contrario regresa verdadero.
         */
        private static function validarParametrosClasificacionProveedor
        (
                $id_clasificacion_proveedor = null,
                $nombre = null,
                $descripcion = null,
                $activa = null
        )
        {
            //valida que la clasificacion exista y este activa
            if(!is_null($id_clasificacion_proveedor))
            {
                $clasificacion_proveedor = ClasificacionProveedorDAO::getByPK($id_clasificacion_proveedor);
                if(is_null($clasificacion_proveedor))
                    return "La clasificacion proveedor ".$id_clasificacion_proveedor." no existe";
                
                if(!$clasificacion_proveedor->getActiva())
                    return "La clasificacion proveedor ".$id_clasificacion_proveedor." no esta activa";
            }
            
            //valida que el nombre este en rango y que no se repita
            if(!is_null($nombre))
            {
                $e = self::validarString($nombre, 100, "nombre");
                if(is_string($e))
                    return $e;
                
                $clasificaciones_proveedor = ClasificacionProveedorDAO::search( new ClasificacionProveedor( array( "nombre" => trim($nombre) ) ) );
                foreach($clasificaciones_proveedor as $clasificacion_proveedor)
                {
                    if($clasificacion_proveedor->getActiva())
                        return "El nombre (".$nombre.") esta siendo usado por la clasificacion de proveedor ".$clasificacion_proveedor->getIdClasificacionProveedor();
                }
            }
            
            //valida que la descripcion este en rango
            if(!is_null($descripcion))
            {
                $e = self::validarString($descripcion, 255, "descripcion");
                if(is_string($e))
                    return $e;
            }
            
            //valida el boleano activa
            if(!is_null($activa))
            {
                $e = self::validarNumero($activa, 1, "activa");
                if(is_string($e))
                    return $e;
            }
            
            //no se encontro error, regresa verdadero
            return true;
        }
        
      
      
      
        
  
	/**
 	 *
 	 *Desactiva una clasificacion de proveedor
 	 *
 	 * @param id_clasificacion_proveedor int Id de la clasificacion de proveedor a desactivar
 	 **/
	public static function EliminarClasificacion
	(
		$id_clasificacion_proveedor
	)
	{  
            Logger::log("Eliminando la clasificacion de proveedor ".$id_clasificacion_proveedor);
            
            //valida que la clasificacion exista y este activa
            $validar = self::validarParametrosClasificacionProveedor($id_clasificacion_proveedor);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            //Desactiva la clasificacion proveedor y elimina los registros de las tablas impuesto_clasificacion_proveedor
            //y retencion_clasificacion_proveedor que lo contengan
            $clasificacion_proveedor = ClasificacionProveedorDAO::getByPK($id_clasificacion_proveedor);
            $clasificacion_proveedor->setActiva(0);
            
            $impuestos_clasificacion_proveedor = ImpuestoClasificacionProveedorDAO::search( 
                    new ImpuestoClasificacionProveedor( array( "id_clasificacion_proveedor" => $id_clasificacion_proveedor ) ) );
            
            $retenciones_clasificacion_proveedor = RetencionClasificacionProveedorDAO::search( 
                    new RetencionClasificacionProveedor( array( "id_clasificacion_proveedor" => $id_clasificacion_proveedor ) ) );
            
            DAO::transBegin();
            try
            {
                ClasificacionProveedorDAO::save($clasificacion_proveedor);
                
                foreach($impuestos_clasificacion_proveedor as $impuesto_clasificacion_proveedor)
                    ImpuestoClasificacionProveedorDAO::delete ($impuesto_clasificacion_proveedor);
                
                foreach($retenciones_clasificacion_proveedor as $retencion_clasificacion_proveedor)
                    RetencionClasificacionProveedorDAO::delete ($retencion_clasificacion_proveedor);
                
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("Error al desactivar la clasificacion proveedor ".$e);
                throw new Exception("Error al desactivar la clasificacion proveedor");
            }
            DAO::transEnd();
            Logger::log("La clasificacion de proveedor ha sido eliminada exitosamente");
	}
  
	/**
 	 *
 	 *Crea una nueva clasificacion de proveedor
 	 *
 	 * @param nombre string Nombre de la clasificacion de proveedor
 	 * @param descripcion string Descripcion de la clasificacion del proveedor
 	 * @param impuestos json Ids de impuestos que afectan esta clasificacion de proveedor
 	 * @param retenciones json Ids de retenciones que afecta esta clasificacion de proveedor
 	 * @return id_clasificacion_proveedor int Id de la clasificacion del proveedor
 	 **/
	public static function NuevaClasificacion
	(
		$nombre, 
		$descripcion = null, 
		$impuestos = null, 
		$retenciones = null
	)
	{  
            Logger::log("Creando nueva clasificacion de proveedor");
            
            //Se validan los parametros recibidos
            $validar = self::validarParametrosClasificacionProveedor(null, $nombre, $descripcion);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            $clasificacion_proveedor = new ClasificacionProveedor( array( 
                                                
                                                                        "nombre"        => $nombre,
                                                                        "descripcion"   => $descripcion,
                                                                        "activa"        => 1
                                                                        
                                                                        )
                                                                    );
            
            //Se almacena la nueva clasificacion proveedor y si se recibieron impuestos o retenciones,
            //se guardan en sus respectivas tablas
            DAO::transBegin();
            try
            {
                ClasificacionProveedorDAO::save($clasificacion_proveedor);
                if(!is_null($impuestos))
                {
                    $impuesto_clasificacion_proveedor = new ImpuestoClasificacionProveedor(
                            array( "id_clasificacion_proveedor" => $clasificacion_proveedor->getIdClasificacionProveedor() ));
                    foreach ($impuestos as $impuesto)
                    {
                        if(is_null(ImpuestoDAO::getByPK($impuesto)))
                                throw new Exception ("El impuesto ".$impuesto." no existe");
                        $impuesto_clasificacion_proveedor->setIdImpuesto($impuesto);
                        ImpuestoClasificacionProveedorDAO::save($impuesto_clasificacion_proveedor);
                    }
                }/* Fin if de impuestos */
                if(!is_null($retenciones))
                {
                    $retencion_clasificacion_proveedor = new RetencionClasificacionProveedor(
                            array ( "id_clasificacion_proveedor" => $clasificacion_proveedor->getIdClasificacionProveedor() ) );
                    foreach( $retenciones as $retencion )
                    {
                        if(is_null(RetencionDAO::getByPK($retencion)))
                                throw new Exception("La retencion ".$retencion." no existe");
                        $retencion_clasificacion_proveedor->setIdRetencion($retencion);
                        RetencionClasificacionProveedorDAO::save($retencion_clasificacion_proveedor);
                    }
                }/* Fin if de retenciones */
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se ha podido crear la nueva clasificacion de proveedor ".$e);
                throw new Exception("No se ha podido crear la nueva clasificacion de proveedor");
            }
            DAO::transEnd();
            Logger::log("Clasificacion de proveedor creada exitosamente");
            return array( "id_clasificacion_proveedor" => $clasificacion_proveedor->getIdClasificacionProveedor() );
	}
  
	/**
 	 *
 	 *Edita la informacion de una clasificacion de proveedor
 	 *
 	 * @param id_clasificacion_proveedor int Id de la clasificacion del proveedor a editar
 	 * @param retenciones json Ids de las retenciones de la clasificacion de  proveedor
 	 * @param impuestos json Ids de los impuestos de la clasificacion del proveedor
 	 * @param descripcion string Descripcion de la clasificacion del proveedor
 	 * @param nombre string Nombre de la clasificacion del proveedor
 	 **/
	public static function EditarClasificacion
	(
		$id_clasificacion_proveedor, 
		$retenciones = null, 
		$impuestos = null, 
		$descripcion = null, 
		$nombre = null
	)
	{  
            Logger::log("Editando la clasificacion de proveedor ".$id_clasificacion_proveedor);
            
            //valida los parametros recibidos
            $validar = self::validarParametrosClasificacionProveedor($id_clasificacion_proveedor, $nombre, $descripcion);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            //Los parametros que no sean nulos seran tomados como actualizacion
            $clasificacion_proveedor = ClasificacionProveedorDAO::getByPK($id_clasificacion_proveedor);
            if(!is_null($descripcion))
            {
                $clasificacion_proveedor->setDescripcion($descripcion);
            }
            if(!is_null($nombre))
            {
                $clasificacion_proveedor->setNombre($nombre);
            }
            
            //Se actualiza el registro. Si se reciben listas de impuestos y/o registros se guardan los
            //que estan en la lista, despues se recorren los registros de la base de datos y aquellos que no
            //se encuentren en la lista nueva seran eliminados.
            DAO::transBegin();
            try
            {
                ClasificacionProveedorDAO::save($clasificacion_proveedor);
                if(!is_null($impuestos))
                {
                    $impuesto_clasificacion_proveedor = new ImpuestoClasificacionProveedor(
                            array( "id_clasificacion_proveedor" => $clasificacion_proveedor->getIdClasificacionProveedor() ));
                    foreach ($impuestos as $impuesto)
                    {
                        if(is_null(ImpuestoDAO::getByPK($impuesto)))
                                throw new Exception ("El impuesto ".$impuesto." no existe");
                        $impuesto_clasificacion_proveedor->setIdImpuesto($impuesto);
                        ImpuestoClasificacionProveedorDAO::save($impuesto_clasificacion_proveedor);
                    }
                    
                    $impuestos_clasificacion_proveedor = ImpuestoClasificacionProveedorDAO::search( 
                            new ImpuestoClasificacionProveedor( array( "id_clasificacion_proveedor" => $id_clasificacion_proveedor ) ) );
                    foreach($impuestos_clasificacion_proveedor as $impuesto_clasificacion_proveedor)
                    {
                        $encontrado = false;
                        foreach($impuestos as $impuesto)
                        {
                            if($impuesto == $impuesto_clasificacion_proveedor->getIdImpuesto())
                                $encontrado = true;
                        }
                        if(!$encontrado)
                            ImpuestoClasificacionProveedorDAO::delete ($impuesto_clasificacion_proveedor);
                    }
                }/* Fin if de impuestos */
                if(!is_null($retenciones))
                {
                    $retencion_clasificacion_proveedor = new RetencionClasificacionProveedor(
                            array ( "id_clasificacion_proveedor" => $clasificacion_proveedor->getIdClasificacionProveedor() ) );
                    foreach( $retenciones as $retencion )
                    {
                        if(is_null(RetencionDAO::getByPK($retencion)))
                                throw new Exception("La retencion ".$retencion." no existe");
                        $retencion_clasificacion_proveedor->setIdRetencion($retencion);
                        RetencionClasificacionProveedorDAO::save($retencion_clasificacion_proveedor);
                    }
                    
                    $retenciones_clasificacion_proveedor = RetencionClasificacionProveedorDAO::search( 
                            new RetencionClasificacionProveedor( array( "id_clasificacion_proveedor" => $id_clasificacion_proveedor ) ) );
                    foreach($retenciones_clasificacion_proveedor as $retencion_clasificacion_proveedor)
                    {
                        $encontrado = false;
                        foreach($retenciones as $retencion)
                        {
                            if($retencion == $retencion_clasificacion_proveedor->getIdRetencion())
                                $encontrado = true;
                        }
                        if(!$encontrado)
                            RetencionClasificacionProveedorDAO::delete ($retencion_clasificacion_proveedor);
                    }
                }/* Fin if de retenciones */
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("La clasificacion de proveedor no ha podido ser editada: ".$e);
                throw new Exception("La clasificacion de proveedor no ha podido ser editada");
            }
            DAO::transEnd();
            Logger::log("La clasificacion de proveedor ha sido eeditada exitosamente ");
            
	}
        
        public static function ListaClasificacion
	(
                $activo = null,
		$orden = null
	)
	{  
            Logger::log("Listando las clasificaciones de proveedor");
            
            //Se valida el parametro orden
            if
            (
                    !is_null($orden)                        &&
                    $orden != "id_clasificacion_proveedor"  &&
                    $orden != "nombre"                      &&
                    $orden != "descripcion"                 &&
                    $orden != "activa"
            )
            {
                Logger::error("La variable orden (".$orden.") es invalida");
                throw new Exception("La variable orden (".$orden.") es invalida");
            }
            if(is_null($activo))
                $clasificaciones_proveedor = ClasificacionProveedorDAO::getAll(null,null,$orden);
            else
                $clasificaciones_proveedor = ClasificacionProveedorDAO::search( new ClasificacionProveedor( array( "activa" => $activo ) ) );
            Logger::log("Se obtuvieron ".count($clasificaciones_proveedor)." clasificaciones de proveedor");
            return $clasificaciones_proveedor;
            
	}
  
	/**
 	 *
 	 *Obtener una lista de proveedores. Puede filtrarse por activo o inactivos, y puede ordenarse por sus atributos.
 	 *
 	 * @param activo bool Si el valor no es obtenido, se listaran los proveedores tanto activos como inactivos. Si su valor es true, se mostraran solo los proveedores activos, si es false, se mostraran solo los proveedores inactivos.
 	 * @param ordenar json Valor que determinara el orden de la lista.
 	 * @return proveedores json Objeto que contendra la lista de proveedores.
 	 **/
	public static function Lista
	(
		$activo = null, 
		$ordenar = null
	)
	{  
            Logger::log("Listando los proveedores");
	}
  
	/**
 	 *
 	 *Crea un nuevo proveedor
 	 *
 	 * @param id_tipo_proveedor int Id del tipo proveedor al que pertenece este usuario
 	 * @param password string Password del proveedor para entrar al sistema
 	 * @param nombre string Nombre del proveedor
 	 * @param codigo_proveedor string Codigo interno para identificar al proveedor
 	 * @param codigo_postal string Codigo postal de la direccion del proveedor
 	 * @param id_ciudad int Id de la ciudad de la direccion del proveedor
 	 * @param texto_extra string Referencia de la direcciond el proveedor
 	 * @param numero_interior string Numero interior de la direccion del proveedor
 	 * @param numero_exterior string Numero exterior de la direccion del proveedor
 	 * @param direccion_web string Direccion web del proveedor
 	 * @param retenciones json Retenciones que afectan a este proveedor
 	 * @param impuestos json Ids de los impuestos que afectan a este proveedor
 	 * @param dias_embarque int Dias en que el proveedor embarca ( Lunes, Martes, Miercoles, Jueves..)
 	 * @param telefono_personal string Telefono personal del proveedor
 	 * @param rfc string RFC del proveedor
 	 * @param calle string Calle de la direccion del proveedor
 	 * @param email string Correo electronico del proveedor
 	 * @param id_moneda int Id de la moneda preferente del proveedor
 	 * @param cuenta_bancaria string Cuenta bancaria del proveedor
 	 * @param activo bool Si este proveedor esta activo o no
 	 * @param representante_legal string Representante legal del proveedor
 	 * @param tiempo_entrega string Tiempo de entrega del proveedor en dias
 	 * @param limite_credito float Limite de credito que otorga el proveedor
 	 * @param dias_de_credito int Dias de credito que otorga el proveedor
 	 * @param telefono1 string Telefono 1 de la direccion del proveedor
 	 * @param telefono2 string Telefono 2 de la direccion del proveedor
 	 * @return id_proveedor int Id autogenerado por la inserci�n del nuevo proveedor.
 	 **/
	public static function Nuevo
	(
		$id_tipo_proveedor, 
		$password, 
		$nombre, 
		$codigo_proveedor, 
		$codigo_postal = null, 
		$id_ciudad = null, 
		$texto_extra = null, 
		$numero_interior = null, 
		$numero_exterior = null, 
		$direccion_web = null, 
		$retenciones = null, 
		$impuestos = null, 
		$dias_embarque = true, 
		$telefono_personal = null, 
		$rfc = null, 
		$calle = 1, 
		$email = null, 
		$id_moneda = null, 
		$cuenta_bancaria = null, 
		$activo = null, 
		$representante_legal = null, 
		$tiempo_entrega = null, 
		$limite_credito = null, 
		$dias_de_credito = null, 
		$telefono1 = null, 
		$telefono2 = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Edita la informacion de un proveedor. 
 	 *
 	 * @param id_proveedor int Id del proveedor a editar
 	 * @param limite_credito float Limite de credito que otorga el proveedor
 	 * @param password string Password del proveedor para entrar al sistema
 	 * @param tiempo_entrega int Tiempo de entrega del proveedor en dias
 	 * @param codigo_postal string Codigo postal de la direccion del proveedor
 	 * @param id_ciudad int Id de la ciudad de la direccion del proveedor
 	 * @param texto_extra string Referencia para el domicilio del proveedor
 	 * @param direccion_web string Pagina web del proveedor
 	 * @param numero_interior string Numero interior de la direccion del proveedor
 	 * @param numero_exterior string Numero exterior de la direccion del proveedor
 	 * @param representante_legal string Representante legal del proveedor
 	 * @param activo bool Si el proveedor sera tomado como activo despues de la insercion o no.
 	 * @param rfc string RFC del proveedor
 	 * @param id_tipo_proveedor int El id del tipo de proveedor
 	 * @param dias_de_credito int Dias de credito que otorga el proveedor
 	 * @param calle string Calle de la direccion del proveedor
 	 * @param telefono_personal string Telefono del proveedor
 	 * @param nombre string Nombre del proveedor
 	 * @param email string E-mail del proveedor
 	 * @param dias_embarque int Dias en que el proveedor embarca ( Lunes, Martes, Miercoles, Jueves..)
 	 * @param impuestos json Arreglo de enteros que contendr&#65533;n los ids de impuestos por comprar a este proveedor
 	 * @param telefono2 string Telefono 2 de la direccion del proveedor
 	 * @param telefono1 string Telefono 1 de la direccion del proveeor
 	 * @param cuenta_bancaria string Cuenta bancaria del proveedor a la cual se le deposita
 	 * @param id_moneda int Id de la moneda que maneja el proveedor
 	 * @param retenciones json Retenciones que afectan a este proveedor
 	 * @param codigo_proveedor string Codigo con el que se peude identificar al proveedor
 	 **/
	public static function Editar
	(
		$id_proveedor, 
		$limite_credito = null, 
		$password = null, 
		$tiempo_entrega = null, 
		$codigo_postal = null, 
		$id_ciudad = null, 
		$texto_extra = null, 
		$direccion_web = null, 
		$numero_interior = null, 
		$numero_exterior = null, 
		$representante_legal = null, 
		$activo = 1, 
		$rfc = null, 
		$id_tipo_proveedor = null, 
		$dias_de_credito = null, 
		$calle = null, 
		$telefono_personal = null, 
		$nombre = null, 
		$email = null, 
		$dias_embarque = null, 
		$impuestos = null, 
		$telefono2 = null, 
		$telefono1 = null, 
		$cuenta_bancaria = null, 
		$id_moneda = null, 
		$retenciones = null, 
		$codigo_proveedor = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Este metodo desactiva un proveedor. Para que este metodo funcione, no debe de haber ordenes de compra hacia ese proveedor ??
 	 *
 	 * @param id_proveedor int Id del proveedor que sera eliminado
 	 **/
	public static function Eliminar
	(
		$id_proveedor
	)
	{  
  
  
	}
  }
