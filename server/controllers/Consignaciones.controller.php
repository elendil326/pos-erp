<?php
require_once("interfaces/Consignaciones.interface.php");
/**
  *
  *
  *
  **/
	
  class ConsignacionesController implements IConsignaciones{
  
      
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
         * Valida que un usuario exista, que este activo y que sea cliente
         */
        private static function validarCliente
        (
                $id_cliente
        )
        {
            $usuario = UsuarioDAO::getByPK($id_cliente);
            if(is_null($usuario))
                return "El cliente ".$id_cliente." no existe";
            
            if(!$usuario->getActivo())
                return "EL cliente ".$id_cliente." no esta activo";
            
            if(is_null($usuario->getIdClasificacionCliente()))
                return "El usuario ".$id_cliente." no es un cliente";
            
            return true;
        }
        
        /*
         * Valida que el consignatario sea valido.
         */
        private static function validarConsignatario
        (
                $id_cliente
        )
        {
            $e = self::validarCliente($id_cliente);
            if(is_string($e))
                return $e;
            
            $cliente  = UsuarioDAO::getByPK($id_cliente);
            if(!$cliente->getConsignatario())
                return "El cliente ".$id_cliente." no es un consignatario";
            
            return true;
        }
        
        /*
         * Valida que la consignacion exista y este activa
         */
        private static function validarConsignacion
        (
                $id_consignacion
        )
        {
            $consignacion = ConsignacionDAO::getByPK($id_consignacion);
            if(is_null($consignacion))
            {
                return "La consignacion ".$id_consignacion." no existe";
            }
            
            if(!$consignacion->getActiva())
                return "La consignacion ".$id_consignacion." no existe";
            
            return true;
            
        }
        
        /*
         * Valida los parametros de la tabla consignacion producto
         */
        private static function validarConsignacionProducto
        (
                $id_producto = null,
                $id_unidad = null,
                $cantidad = null,
                $impuesto = null,
                $descuento = null,
                $retencion = null,
                $precio = null
        )
        {
            //Se valida que el producto exista y que este activo
            if(!is_null($id_producto))
            {
                $producto = ProductoDAO::getByPK($id_producto);
                if(is_null($producto))
                    return "El producto ".$id_producto." no existe";
                
                if(!$producto->getActivo())
                    return "El producto ".$id_producto." no esta activo";
                
            }
            
            //Se valida que la unidad exista y que este activa
            if(!is_null($id_unidad))
            {
                $unidad = UnidadDAO::getByPK($id_unidad);
                if(is_null($unidad))
                    return "La unidad ".$id_unidad." no existe";
                
                if(is_null($unidad->getActiva()))
                        return "La unidad ".$id_unidad." no esta activa";
            }
            
            //Se valida que la cantidad este en rango
            if(!is_null($cantidad))
            {
                $e = self::validarNumero($cantidad, 1.8e200, "cantidad");
                if(is_string($e))
                    return $e;
            }
            
            //Se valida que el impuesto este en rango
            if(!is_null($impuesto))
            {
                $e = self::validarNumero($impuesto, 1.8e200, "impuesto");
                if(is_string($e))
                    return $e;
            }
            
            //Se valida que el descuento este en rango
            if(!is_null($descuento))
            {
                $e = self::validarNumero($descuento, 100, "descuento");
                if(is_string($e))
                    return $e;
            }
            
            //Se valida que la retencion este en rango
            if(!is_null($retencion))
            {
                $e = self::validarNumero($retencion, 1.8e200, "retencion");
                if(is_string($e))
                    return $e;
            }
            
            //Se valida que el precio este en rango
            if(!is_null($precio))
            {
                $e = self::validarNumero($precio, 1.8e200, "precio");
                if(is_string($e))
                    return $e;
            }
            
            //No se encontro error
            return true;
            
        }
        
        /*
         * Valida que el almacen exista y este activo
         */
        private static function validarAlmacen
        (
                $id_almacen
        )
        {
            $almacen = AlmacenDAO::getByPK($id_almacen);
            if(is_null($almacen))
                return "El almacen ".$id_almacen." no existe";
            
            if(!$almacen->getActivo())
                return "El almacen ".$id_almacen." no esta activo";
            
            return true;
        }
        
      
      
      
      
      
  
	/**
 	 *
 	 *Desactiva la bandera de consignatario a un cliente y elimina su almacen correspondiente. Para poder hacer esto, el almacen debera estar vacio.
 	 *
 	 * @param id_cliente int Id del cliente a desactivar como consignatario
 	 **/
	public static function DesactivarConsignatario
	(
		$id_cliente
	)
	{  
            Logger::log("Desactivando consignatario ".$id_cliente);
            
            //valida que el cliente exista, que este activo y que sea un cliente
            $e = self::validarConsignatario($id_cliente);
            if(is_string($e))
            {
                Logger::error($e);
                throw new Exception($e);
            }
            
            $cliente = UsuarioDAO::getByPK($id_ciente);
            
            $consignaciones = ConsignacionDAO::search( new Consignacion( array( "id_cliente" => $id_cliente ) ) );
            foreach($consignaciones as $consignacion)
            {
                if($consignacion->getActiva())
                {
                    Logger::error("El consignatario no puede ser desactivado pues aun tiene consignaciones activas: id_consignacion= ".$consignacion->getIdConsignacion());
                    throw new Exception("El consignatario no puede ser desactivado pues aun tiene consignaciones activas");
                }
            }
            
            $cliente->setConsignatario(0);
            $almacenes = AlmacenDAO::search(new Almacen( array( "nombre" => $cliente->getCodigoUsuario() , "activo" => 1 ) ));
            
            DAO::transBegin();
            try
            {
                UsuarioDAO::save($cliente);
                foreach($almacenes as $almacen)
                {
                    $productos_almacen = ProductoAlmacenDAO::search(new ProductoAlmacen( array( "id_almacen" => $almacen->getIdAlmacen() ) ));
                    foreach($productos_almacen as $producto_almacen)
                    {
                        if($producto_almacen->getCantidad()!=0)
                            throw new Exception("El almacen no puede ser borrado pues aun contiene productos");
                    }
                    $almacen->setActivo(0);
                    AlmacenDAO::save($almacen);
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se ha podido desactivar al consignatario: ".$e);
                throw new Exception("No se ha podido desactivar al consignatario");
            }
            DAO::transEnd();
            Logger::log("Consignatario desactivado exitosamente");
	}
  
	/**
 	 *
 	 *Iniciar una orden de consignaci?n. La fecha sera tomada del servidor.
 	 *
 	 * @param productos json Objeto que contendra los ids de los productos que se daran a consignacion a ese cliente con sus cantidades. Se incluira el id del almacen del cual seran tomados para determinar a que empresa pertenece esta consignacion
 	 * @param id_consignatario int Id del cliente al que se le hace la consignacion
 	 * @return id_consignacion int Id de la consignacion autogenerado por la insercion.
 	 **/
	public static function Nueva
	(
		$fecha_termino, 
		$folio, 
		$id_consignatario, 
		$productos, 
		$tipo_consignacion, 
		$fecha_envio_programada = null
	)
	{  
            Logger::log("Creando nueva consignacion");
            //Se valida al consignatario
            $e = self::validarConsignatario($id_consignatario);
            if(is_string($e))
            {
                Logger::error($e);
                throw new Exception($e);
            }
            
            $consignatario = UsuarioDAO::getByPK($id_consignatario);
            
            //Se obtiene al usuario de la sesion actual
            $id_usuario = LoginController::getCurrentUser();
            if(is_null($id_usuario))
            {
                Logger::error("No se pudo obtener al usuario de la sesion, ya inicio sesion?");
                throw new Exception("No se pudo obtener al usuario de la sesion, ya inicio sesion?");
            }
            
            //Valida el parametro tipo de consignacion
            if($tipo_consignacion!="credito"&&$tipo_consignacion!="contado")
            {
                Logger::error("El parametro tipo de consignacion (".$tipo_consignacion.") es invalido");
                throw new Exception("El parametro tipo de consignacion (".$tipo_consignacion.") es invalido");
            }
            
            //Si no se recibio fecha de envio, se toma la fecha actual
            if(is_null($fecha_envio_programada))
            {
                $fecha_envio_programada = date ("Y-m-d H:i:s");
            }
                
            
            $consignacion = new Consignacion( 
                    array(
                        "id_cliente"        => $id_consignatario,
                        "id_usuario"        => $id_usuario,
                        "fecha_creacion"    => date( "Y-m-d H:i:s" ),
                        "activa"            => 1,
                        "cancelada"         => 0,
                        "folio"             => $folio,
                        "fecha_termino"     => $fecha_termino,
                        "saldo"             => 0,
                        "tipo_consignacion" => $tipo_consignacion
                    ) );
            
            //Se agrupan los productos que vienen del mismo almacen en subarreglos para
            //programar un solo traspaso por almacen.
            $productos_por_almacen = array();
            $num_productos = count($productos);
            for($i = 0; $i < $num_productos; $i++)
            {
                if($productos[i]==null)
                    continue;
                $temp = array();
                array_push($temp,$productos[i]);
                for($j = $i+1; $j < $num_productos;$j++)
                {
                    if($productos[$i]["id_almacen"]==$productos[$j]["id_almacen"])
                    {
                        array_push($temp,$productos[$j]);
                        $productos[$j]=null;
                    }
                }
                $productos[$i]=null;
                array_push($productos_por_almacen,$temp);
            }
            
            DAO::transBegin();
            try
            {
                ConsignacionDAO::save($consignacion);
                $consignacion_producto = new ConsignacionProducto( array( "id_consignacion" => $consignacion->getIdConsignacion() ) );
                
                foreach($productos_por_almacen as $producto_por_almacen)
                {
                    //Se validan los parametros obtenidos del arreglo de productos
                    foreach($producto_por_almacen as $producto)
                    {
                        $validar = self::validarConsignacionProducto($producto["id_producto"], $producto["id_unidad"], $producto["cantidad"],
                                $producto["impuesto"], $producto["descuento"], $producto["retencion"], $producto["precio"]);
                        if(is_string($validar))
                            throw new Exception($validar);

                        $validar = self::validarAlmacen($producto["id_almacen"]);
                        if(is_string($validar))
                            throw new Eception($validar);
                    }
                    
                    /*
                     * El consignatario puede contar con algún o ningún almacen de tipo consignacion,
                     * pero solo tendra uno por empresa, esto quiere decir que todos los productos recibidos
                     * seran repartidos en estos almacenes de acuerdo a la empresa a la que pertenece
                     * el almacen de donde son extraidos.
                     * 
                     * Si el consignatario no cuenta con un almacen para la empresa de ese producto, se creara uno
                     * nuevo y se realizara la transferencia.
                     */
                    
                    //Se obtiene el id de la empresa de la cual vienen los productos
                    $id_empresa = AlmacenDAO::getByPK($producto_por_almacen[0]["id_almacen"])->getIdEmpresa();
                    
                    //Se busca el almacen de consignacion de este cliente para esta empresa
                    $almacen = null;
                    $almacenes = AlmacenDAO::search( new Almacen( 
                            array(
                                "id_empresa"    => $id_empresa,
                                "nombre"        => $consignatario->getCodigoUsuario(),
                                "id_sucursal"   => 0 //La sucursal 0 es la sucursal a donde pertenecen todos los almacenes de consignacion
                            ) 
                            ) );
                    
                    //Si no existe, se crea
                    if(empty($almacenes))
                    {
                        $almacen = new Almacen(
                                array(
                                    "id_sucursal"       => 0,
                                    "id_empresa"        => $id_empresa,
                                    "id_tipo_almacen"   => 2,
                                    "nombre"            => $consignatario->getCodigoUsuario(),
                                    "descripcion"       => "Almacen de consignacion del usuario ".$consignatario->getNombre()." con clave ".
                                                            $consignatario->getCodigoUsuario()." para la empresa ".$id_empresa,
                                    "activo"            => 1
                                )
                                );
                         AlmacenDAO::save($almacen);
                    }
                    
                    //Se encontro el almacen
                    else
                        $almacen = $almacenes[0];
                    
                    //Se prepara el arreglo de productos para crear el traspaso de un almacen a otro
                    $productos_para_traspaso = array();
                    foreach($producto_por_almacen as $producto)
                    {
                        $p = array(
                            "id_producto"   => $producto["id_producto"],
                            "id_unidad"     => $producto["id_unidad"],
                            "cantidad"      => $producto["cantidad"]
                        );
                        array_push($productos_para_traspaso, $p);
                    }
                    
                    //Se programa el traspaso del almacen de donde se tomaron estos productos al almacen de consignacion
                    SucursalesController::ProgramarTraspasoAlmacen($almacen->getIdAlmacen(), $producto_por_almacen[0]["id_almacen"], $fecha_envio_programada, $productos_para_traspaso);
                    
                }
                
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo crear la nueva consignacion: ".$e);
                throw new Exception("No se pudo crear la nueva consignacion");
            }
            DAO::transEnd();
            Logger::log("Consignacion creada exitosamente");
            return array( "id_consignacion" => $consignacion->getIdConsignacion() );
            
            
	}
  
	/**
 	 *
 	 *Un consignatario ya es un cliente. Al crear un nuevo consignatario, se le crea un nuevo almacen a la sucursal que hace la consignacion. El nombre de ese almacen sera el rfc o la clave del cliente. Se agregara este nuevo id_almacen al cliente y su bandera de consignatario se pondra activa.
 	 *
 	 * @param id_cliente int Id del cliente que sera el consignatario
 	 * @return id_almacen int Id del almacen que se creo de la operacion
 	 **/
	public static function NuevoConsignatario
	(
		$id_cliente
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Este metodo lista las consignaciones de la instancia. Puede filtrarse por empresa, por sucursal, por cliente, por producto y se puede ordenar por sus atributos.
 	 *
 	 * @param id_empresa int Id de la empresa de la cual se mostraran las consignaciones
 	 * @param id_sucursal int Id de la sucursal de la cual se mostraran las consignaciones
 	 * @param id_cliente int Id del cliente del cual se mostraran las consignaciones
 	 * @param id_producto int Id del producto del cual se mostraran las consignaciones
 	 * @param orden json Valor que determinara el orden de la lista
 	 * @return lista_consignaciones json Objeto que contendra la lista de consignaciones
 	 **/
	public static function Lista
	(
		$id_cliente = null, 
		$id_empresa = null, 
		$id_producto = null, 
		$id_sucursal = null, 
		$orden = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Se llama cuando se realiza una revision a una orden de consigacion. Actualiza el estado del almacen del cliente, se facturan a credito o de contado las ventas realizadas y se registra la entrada de dinero. La fecha sera tomada del servidor.
 	 *
 	 * @param productos_actuales json Ojeto que contendra los ids de los productos con sus cantidades con los que cuenta actualmente el cliente, puede ser un json vacio. Este campo no se ve afectado por los campos producto_solicitado ni producto_devuelto.
 	 * @param id_inspeccion int Id de la inspeccion realizada
 	 * @param id_inspector int Id del usuario que realiza la inspeccion
 	 * @param monto_abonado float Si la consignacion fue de contado, el cobrador debe registrar el monto equivalente a las ventas del cliente
 	 * @param producto_solicitado json Objeto que contendra los ids de los productos y sus cantidades que el cliente solicita, si este campo es obtenido, se editara la consignacion original agregando estos productos
 	 * @param producto_devuelto json Objeto que contendra los ids de los productos y sus cantidades que seran devueltos. Estos productos seran devueltos al almacen  de la sucursal de donde fueron extraidos.
 	 **/
	public static function RegistrarInspeccion
	(
		$id_inspeccion, 
		$productos_actuales, 
		$id_inspector = "", 
		$monto_abonado = null, 
		$producto_devuelto = null, 
		$producto_solicitado = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Dar por terminada una consignacion, bien se termino el inventario en consigacion o se va a regresar al inventario de alguna sucursal.
 	 *
 	 * @param id_consignacion int identifiacdor de consignacion
 	 * @param motivo string Motivo por el cual se termino la consignacion, por que el cliente no vendia, o a peticion del cliente, etc.
 	 **/
	public static function Terminar
	(
		$id_consignacion, 
		$productos_actuales, 
		$motivo = null, 
		$tipo_pago = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Abona un monto de dinero a una inspeccion ya registrada. La fecha sera tomada del sistema. Este metodo sera usado cuando el inspector llegue a la sucursal y deposite el dinero en la caja, de tal forma que se lleve un registro de cuando y cuanto deposito el dinero por el pago de la consignacion, asi como saber si el inspector ya realizo el deposito del dinero que se le consigno.
 	 *
 	 * @param monto float monto que sera abonado a la inspeccion
 	 * @param id_inspeccion int Id de la inspeccion
 	 * @param id_caja int Id de la caja a la que se le abona
 	 **/
	public static function AbonarInspeccion
	(
		$id_caja, 
		$id_inspeccion, 
		$monto
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Registra en que fecha se le hara una inspeccion a un cliente con consignacion 
 	 *
 	 * @param id_consignacion int Id de la consignacion a la que se le hara la revision
 	 * @param fecha_revision string Fecha en que se hara la revision.
 	 * @param id_inspector int Id del usuario al que se le asignara esta inspeccion
 	 * @return id_inspeccion int Id de la inspeccion creada
 	 **/
	public static function NuevaInspeccion
	(
		$fecha_revision, 
		$id_consignacion, 
		$id_inspector = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Usese este metodo cuando se posterga o se adelanta una inspeccion
 	 *
 	 * @param id_inspeccion int Id de la inspeccion a cambiar de fecha
 	 * @param nueva_fecha string Nueva fecha en que se llevara acabo la inspeccion
 	 **/
	public static function Cambiar_fechaInspeccion
	(
		$id_inspeccion, 
		$nueva_fecha
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Este metodo sirve para cancelar una inspeccion que aun no ha sido registrada. Sirve cuando se cancela una orden de consignacion y por consiguiente se tienen que cancelar las inspecciones programadas para esa consignacion.
 	 *
 	 * @param id_inspeccion int Id de la inspeccion a cancelar
 	 **/
	public static function CancelarInspeccion
	(
		$id_inspeccion
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Edita una consignacion, ya sea que agregue o quite productos a la misma. La fecha se toma del sistema.
 	 *
 	 * @param id_consignacion int Id de la consignacion a editar
 	 * @param productos json Objeto que contendra los ids de los productos y sus cantidades que ahora tendra esta consignacion
 	 * @param agregar bool Si estos productos seran agregados a la consignacion o seran quitados de la misma.
 	 **/
	public static function Editar
	(
		$agregar, 
		$id_consignacion, 
		$productos
	)
	{  
  
  
	}
	
	
	/**
 	 *
 	 *Este metodo solo debe ser usado cuando no se ha vendido ningun producto de la consginacion y todos seran devueltos
 	 *
 	 * @param productos_almacen json Arreglo que contendra los ids de producto, ids de unidad, cantidad y el id del almacen al que iran.
 	 **/
  static function Cancelar
	(
		$productos_almacen
	){
		
	}
	
  }
