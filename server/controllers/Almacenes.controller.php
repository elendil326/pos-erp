<?php

require_once("interfaces/Almacenes.interface.php");

class AlmacenesController extends ValidacionesController implements IAlmacenes{
	

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
	){
		Logger::log("Listando Almacenes");
            $parametros=false;
            if
            (
                    !is_null($id_empresa)  ||
                    !is_null($id_sucursal) ||
                    !is_null($id_tipo_almacen) ||
                    !is_null($activo)
            )
                $parametros=true;
            $almacenes=null;
            
            //Si se recibieron parametros, se buscan los almacenes con dichos parametros, si no
            //se imprimen todos los almacenes
            if($parametros)
            {
                Logger::log("Se recibieron parametros, se listan las almacenes en rango");
                
                //Se valida el parametro activo
                $validar = self::validarParametrosAlmacen(null, null, null, null, null, null, $activo);
                if(is_string($validar))
                {
                    Logger::error($validar);
                    throw new Exception($validar);
                }
                
                $almacen_criterio_1=new Almacen();
                $almacen_criterio_1->setActivo($activo);
                $almacen_criterio_1->setIdEmpresa($id_empresa);
                $almacen_criterio_1->setIdSucursal($id_sucursal);
                $almacen_criterio_1->setIdTipoAlmacen($id_tipo_almacen);
                $almacenes=AlmacenDAO::search($almacen_criterio_1);
            }
            else
            {
                Logger::log("No se recibieron parametros, se listan todos los almacenes");
                $almacenes=AlmacenDAO::getAll();
            }
            Logger::log("Almacenes obtenidos exitosamente");
            return $almacenes;
	}
  
  
	
  
	/**
 	 *
 	 *Descativa un almacen. Para poder desactivar un almacen, este tiene que estar vac?o
 	 *
 	 * @param id_almacen int Id del almacen a desactivar
 	 **/
  static function Desactivar
	(
		$id_almacen
	){
            Logger::log("Eliminando almacen");
            $almacen=AlmacenDAO::getByPK($id_almacen);
            
            //verifica que el almacen exista, que este activo y que su tipo no sea de consignacion
            if(is_null($almacen))
            {
                Logger::error("El almacen con id: ".$id_almacen." no existe");
                throw new Exception("El almacen con id: ".$id_almacen." no existe");
            }
            if(!$almacen->getActivo())
            {
                Logger::warn("El almacen ya esta inactivo");
                throw new Exception("El almacen ya esta inactivo");
            }
            if($almacen->getIdTipoAlmacen()==2)
            {
                Logger::error("No se puede eliminar con este metodo un almacen de tipo consignacion");
                throw new Exception("No se puede eliminar con este metodo un almacen de tipo consignacion");
            }
           
            $almacen->setActivo(0);
            
            //Busca los productos del almacen, si alguno tiene una cantidad distinta a cero,
            //el almacen no puede ser eliminado
            $productos_almacen=ProductoAlmacenDAO::search(new ProductoAlmacen(array( "id_almacen" => $id_almacen )));
            foreach($productos_almacen as $producto_almacen)
            {
                if($producto_almacen->getCantidad()!=0)
                {
                    Logger::error("El almacen no puede ser borrado pues aun contiene productos");
                    throw new Exception("El almacen no puede ser borrado pues aun contiene productos");
                }
            }
            
            //Se cancelaran todos los traspasos que este almacen tenga programados ya sea para enviar o para recibir
            $traspasos_enviados = TraspasoDAO::search( new Traspaso( array( "id_almacen_envia" => $id_almacen ) ) );
            $traspasos_recibidos = TraspasoDAO::search( new Traspaso( array( "id_almacen_recibe" => $id_almacen ) ) );
            
            DAO::transBegin();
            try
            {
                AlmacenDAO::save($almacen);
                foreach($traspasos_enviados as $t_e)
                {
                    if($t_e->getEstado()=="Envio programado")
                        self::CancelarTraspasoAlmacen ($t_e->getIdTraspaso());
                }
                foreach($traspasos_recibidos as $t_r)
                {
                    if(!$t_r->getCancelado()&&!$t_r->getCompleto())
                        self::CancelarTraspasoAlmacen ($t_r->getIdTraspaso());
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo eliminar el almacen: ".$e);
                throw new Exception("No se pudo eliminar el almacen");
            }
            DAO::transEnd();
            Logger::log("Almacen eliminado exitosamente");
	}
  
  
	
  
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
	){
            Logger::log("Editando almacen");
            
            //valida los parametros de editar almacen
            $validar = self::validarParametrosAlmacen($id_almacen,null,null,$id_tipo_almacen,$nombre,$descripcion);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            $almacen=AlmacenDAO::getByPK($id_almacen);
            if(!$almacen->getActivo())
            {
                Logger::error("El almacen no esta activo, no puede ser editado");
                throw new Exception("El almacen no esta activo, no puede ser editado");
            }
            
            //Si un parametro no es nulo, se toma como actualizacion
            if(!is_null($descripcion))
            {
                $almacen->setDescripcion($descripcion);
            }
            if(!is_null($nombre))
            {
                //Se verifica que el nombre del almacen no se repita
                $almacenes = AlmacenDAO::search(new Almacen( array( "id_sucursal" => $almacen->getIdSucursal() ) ) );
                foreach($almacenes as $a)
                {
                    if($a->getNombre()==trim($nombre) && $a->getIdAlmacen()!=$id_almacen)
                    {
                        Logger::log("El nombre (".$nombre.") ya esta siendo usado por el almacen: ".$a->getIdAlmacen());
                        throw new Exception("El nombre ya esta en uso");
                    }
                }
                $almacen->setNombre(trim($nombre));
            }
            
            //El tipo de almacen no puede ser cambiado a un almacen de consignacion, 
            //ni un almacen de consignacion puede ser cambiado a otro tipo de almacen.
            if(!is_null($id_tipo_almacen))
            {
                if($id_tipo_almacen==2)
                {
                    Logger::warn("Se busca cambiar el tipo de almacen para volverse un almacen de consignacion, no se hara nada pues esto no esta permitido");
                }
                else if($almacen->getIdTipoAlmacen()==2)
                {
                    Logger::warn("Se busca editar el tipo de almacen a un almacen de consignacion, no se hara nada pues esto no esta permitido");
                }
                else
                {
                    $almacen->setIdTipoAlmacen($id_tipo_almacen);
                }
            }
            DAO::transBegin();
            try
            {
                AlmacenDAO::save($almacen);
            }
            catch(Exception $e)
            {
                DAO::transRollBack();
                Logger::error("No se pudo editar el almacen: ".$e);
                throw new Exception("No se pudo editar el almacen");
            }
            DAO::transEnd();
            Logger::log("Almacen editado exitosamente");
	}
  
  
	
  
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
	){
            Logger::log("Resgitrando entrada a almacen");
            $entrada_almacen = new EntradaAlmacen();
            
            //Se obtiene el usuario de la sesion
            $id_usuario=SesionController::getCurrentUser();
            if(is_null($id_usuario))
            {
                Logger::error("No se puede obtener al usuario de la sesion, ya inicio sesion?");
                throw new Exception("No se puede obtener al usuario de la sesion, ya inicio sesion?");
            }
            
            //valida que el almacen exista
            $almacen = AlmacenDAO::getByPK($id_almacen);
            if(is_null($almacen))
            {
                Logger::error("El almacen con id: ".$id_almacen." no existe");
                throw new Exception("El almacen con id: ".$id_almacen." no existe");
            }
            
            if(!$almacen->getActivo())
            {
                Logger::error("El almacen no esta activo, no se le pueden ingresar productos");
                throw new Exception("El almacen no esta activo, no se le pueden ingresar productos");
            }
            
            //valida que el motivo sea un string valido
            if(!is_null($motivo))
            {
                $validar = self::validarString($motivo, 255, "motivo");
                if(is_string($validar))
                {
                    Logger::error($validar);
                    throw new Exception($validar);
                }
            }
            //Se inicializa el registro de la tabla entrada_almacen
            $entrada_almacen->setIdAlmacen($id_almacen);
            $entrada_almacen->setMotivo($motivo);
            $entrada_almacen->setIdUsuario($id_usuario);
            $entrada_almacen->setFechaRegistro(date("Y-m-d H:i:s",time()));
            DAO::transBegin();
            try
            {
                //se guarda el registro de entrada_almacen
                EntradaAlmacenDAO::save($entrada_almacen);
                
                //Por cada producto recibido se crea un registro en  la tabla producto_entrada_almacen.
                //Cada producto ingresado incrementa su cantidad en el almacen. Si aun no existe,
                //se crea su registro y se guarda.
                $producto_entrada_almacen=new ProductoEntradaAlmacen(array( "id_entrada_almacen" => $entrada_almacen->getIdEntradaAlmacen() ));
                
                $productos = object_to_array($productos);
                
                if(!is_array($productos))
                {
                    throw new Exception("Los productos fueron recibidos incorrectamente",901);
                }
                
                foreach($productos as $p)
                {
                    
                    if
                    (
                            !array_key_exists("id_producto", $p)    ||
                            !array_key_exists("id_unidad", $p)      ||
                            !array_key_exists("cantidad", $p)
                    )
                    {
                        throw new Exception("Los productos fueron recibidos incorrectamente",901);
                    }
                    
                    //valida que el producto a ingresar pertenezca a la misma empresa que el almacen
                    //pues un almacen solo puede ocntener producto de la empresa a la que pertenece.
                    $productos_empresa = ProductoEmpresaDAO::search( new ProductoEmpresa( array( "id_producto" => $p["id_producto"] ) ) );
                    $encontrado = false;
                    foreach($productos_empresa as $p_e)
                    {
                        if($p_e->getIdEmpresa() == $almacen->getIdEmpresa())
                        {
                            $encontrado = true;
                        }
                    }
                    if(!$encontrado)
                    {
                        throw new Exception("Se quiere agregar un producto que no es de la empresa de este almacen",901);
                    }
                    
                    if(is_null(ProductoDAO::getByPK($p["id_producto"])))
                        throw new Exception("El producto con id: ".$p["id_producto"]." no existe",901);

                    $producto_entrada_almacen->setIdProducto($p["id_producto"]);
                    if(is_null(UnidadDAO::getByPK($p["id_unidad"])))
                        throw new Exception("La unidad con id: ".$p["id_unidad"]." no existe",901);

                    $producto_entrada_almacen->setIdUnidad($p["id_unidad"]);
                    $producto_entrada_almacen->setCantidad($p["cantidad"]);
                    $producto_almacen=ProductoAlmacenDAO::getByPK($p["id_producto"], $id_almacen, $p["id_unidad"]);
                    
                    if(is_null($producto_almacen))
                        $producto_almacen=new ProductoAlmacen(array( "id_producto" => $p["id_producto"] , "id_almacen" => $id_almacen , "id_unidad" => $p["id_unidad"] ));

                    $producto_almacen->setCantidad($producto_almacen->getCantidad()+$p["cantidad"]);
                    ProductoEntradaAlmacenDAO::save($producto_entrada_almacen);
                    ProductoAlmacenDAO::save($producto_almacen);
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo registrar la entrada al almacen: ".$e);
                if($e->getCode()==901)
                    throw new Exception("No se pudo registrar la entrada al almacen: ".$e->getCode(),901);
                throw new Exception("No se pudo registrar la entrada al almacen",901);
            }
            DAO::transEnd();
            Logger::log("Entrada a almacen registrada exitosamente");
            return array("id_surtido" => $entrada_almacen->getIdEntradaAlmacen());
	}
  
  
	
  
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
	){
            Logger::log("Creando nuevo almacen");
            
            //Se validan los parametros obtenidos del almacen
            $validar = self::validarParametrosAlmacen(null, $id_sucursal, $id_empresa, $id_tipo_almacen, $nombre, $descripcion, null);
            if(is_string($validar))
            {
                Logger::log($validar);
                throw new Exception($validar);
            }
            
            //Se valida si hay un almacen con ese mimso nombre en esta sucursal
            $almacenes = AlmacenDAO::search(new Almacen( array( "id_sucursal" => $id_sucursal ) ) );
            foreach($almacenes as $almacen)
            {
                if($almacen->getNombre()==trim($nombre)&&$almacen->getActivo())
                {
                    Logger::log("El nombre (".$nombre.") ya esta siendo usado por el almacen: ".$almacen->getIdAlmacen());
                    throw new Exception("El nombre ya esta en uso");
                }
            }
            
            //Si se recibe un tipo de almacen de consignacion, se manda una excepcion, pues no se puede crear un almacen
            //de consignacion con este metodo.
            if($id_tipo_almacen==2)
            {
                Logger::error("No se puede crear un almacen de consignacion con este metodo");
                throw new Exception("No se puede crear un almacen de consignacion con este metodo");
            }
            
            //Solo puede haber un almacen por tipo por cada empresa en una sucursal.
            $almacenes = AlmacenDAO::search( new Almacen( array( "id_sucursal" => $id_sucursal ,
                "id_empresa" => $id_empresa , "id_tipo_almacen" => $id_tipo_almacen ) ) );
            if(!empty($almacenes))
            {
                Logger::error("Ya existe un almacen (".$almacenes[0]->getIdAlmacen().") de este tipo (".$id_tipo_almacen.") en esta sucursal (".$id_sucursal.") para esta empresa (".$id_empresa.")");
                throw new Exception("Ya existe un almacen de este tipo en esta sucursal para esta empresa");
            }
            
            //Se inicializa el registro a guardar con los datos obtenidos.
            $almacen=new Almacen();
            $almacen->setNombre(trim($nombre));
            $almacen->setDescripcion($descripcion);
            $almacen->setIdSucursal($id_sucursal);
            $almacen->setIdEmpresa($id_empresa);
            $almacen->setIdTipoAlmacen($id_tipo_almacen);
            $almacen->setActivo(1);
            DAO::transBegin();
            try
            {
                //Se guarda el almacen
                AlmacenDAO::save($almacen);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo crear el nuevo almacen");
                throw new Exception("No se pudo crear el nuevo almacen");
            }
            DAO::transEnd();
            Logger::log("Almacen creado exitosamente");
            return array( "id_almacen" => $almacen->getIdAlmacen());
	}
  
  
	
  
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
	){  
            Logger::log("Registrando salida de almacen");
            
            //Se obtiene al usuario de la sesion
            $id_usuario=SesionController::getCurrentUser();
            if(is_null($id_usuario))
            {
                Logger::error("No se pudo obtener al usuario de la sesion, ya inicio sesion?");
                throw new Exception("No se pudo obtener al usuario de la sesion, ya inicio sesion?");
            }
            
            //valida si el almacen existe y si esta activo
            $almacen = AlmacenDAO::getByPK($id_almacen);
            if(is_null($almacen))
            {
                Logger::error("El almacen con id: ".$id_almacen." no existe");
                throw new Exception("El almacen con id: ".$id_almacen." no existe");
            }
            if(!$almacen->getActivo())
            {
                Logger::error("El almacen no esta activo, no puede salir producto de el");
                throw new Exception("El almacen no esta activo, no puede salir producto de el");
            }
            
            //Valida que el motivo sea un string valido
            if(!is_null($motivo))
            {
                $validar = self::validarString($motivo, 255, "motivo");
                if(is_string($validar))
                {
                    Logger::error($validar);
                    throw new Exception($validar);
                }
            }
            //Se inicializa el registro de la tabla salida_almacen
            $salida_almacen = new SalidaAlmacen(array(
                            "id_almacen" => $id_almacen,
                            "id_usuario" => $id_usuario,
                            "fecha_registro" => date("Y-m-d H:i:s",time()),
                            "motivo" => $motivo
                                    )
                                );
            DAO::transBegin();
            try
            {
                //Se guarda el registro de la salida almacen y se sacan los productos solicitados
                //del almacen
                SalidaAlmacenDAO::save($salida_almacen);
                $producto_salida_almacen=new ProductoSalidaAlmacen(array( "id_salida_almacen" => $salida_almacen->getIdSalidaAlmacen() ));
                
                $productos = object_to_array($productos);
                
                if(!is_array($productos))
                {
                    throw new Exception("Los productos son invalidos",901);
                }
                
                foreach($productos as $p)
                {
                    
                    if
                    (
                            !array_key_exists("id_producto", $p)    ||
                            !array_key_exists("id_unidad", $p)      ||
                            !array_key_exists("cantidad", $p)
                    )
                    {
                        throw new Exception("Los productos son invalidos",901);
                    }
                    
                    //Se busca en el almacen el producto solicitado, si no es encontrado, se manda una excepcion
                    $producto_almacen=ProductoAlmacenDAO::getByPK($p["id_producto"], $id_almacen, $p["id_unidad"]);
                    if(is_null($producto_almacen))
                    {
                        throw new Exception("El producto: ".$p["id_producto"]." en la unidad: ".$p["id_unidad"]."
                            no se encuentra en el almacen: ".$id_almacen.". No se puede registrar la salida");
                    }
                    //Se analiza la cantidad actual del producto en el almacen. Si la solicitada
                    //es mayor que la cantidad actual, se arroja una excepcion.
                    $cantidad_actual=$producto_almacen->getCantidad();
                    if($cantidad_actual<$p["cantidad"])
                    {
                        throw new Exception("Se busca sacar mas cantidad de producto de la que hay actualmente. Actual: ".$cantidad_actual." - Salida: ".$p["cantidad"]);
                    }
                    $producto_almacen->setCantidad($cantidad_actual-$p["cantidad"]);
                    $producto_salida_almacen->setIdProducto($p["id_producto"]);
                    $producto_salida_almacen->setIdUnidad($p["id_unidad"]);
                    $producto_salida_almacen->setCantidad($p["cantidad"]);
                    ProductoAlmacenDAO::save($producto_almacen);
                    ProductoSalidaAlmacenDAO::save($producto_salida_almacen);
                }/* Fin de foreach */
            } /* Fin de try */
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo registrar la salida de producto: ".$e);
                throw new Exception("No se pudo registrar la salida de producto");
            }
            DAO::transEnd();
            Logger::log("Salida de almacen registrada correctamente");
            return array("id_salida" => $salida_almacen->getIdSalidaAlmacen());
	}
  
  
	
  
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
	){
		 $r = TipoAlmacenDAO::getAll();

		 return array(
			 "resultados" => $r,
			 "numero_de_resultados" => sizeof($r)
			);
	}
  
  
	
  
	/**
 	 *
 	 *Elimina un tipo de almacen
 	 *
 	 * @param id_tipo_almacen int Id del tipo de almacen a editar
 	 **/
  static function DesactivarTipo
	(
		$id_tipo_almacen
	){
	
        Logger::log("Eliminando tipo de almacen ".$id_tipo_almacen);
        
        //El almacen de consignacion no se puede borrar
        if($id_tipo_almacen==2)
        {
            Logger::error("Se intento eliminar el almacen de consginacion");
            throw new Exception("El almacen de consignacion no puede ser eliminado",901);
        }
        
        //Se valida que el tipo de almacen exista
        $tipo_almacen = TipoAlmacenDAO::getByPK($id_tipo_almacen);
        if(is_null($tipo_almacen))
        {
            Logger::error("El tipo de almacen ".$id_tipo_almacen." no existe");
            throw new Exception("El tipo de almacen ".$id_tipo_almacen." no existe",901);
        }
        
        //Si un almacen activo aun pertenece a este tipo de almacen, no se podra eliminar
        $almacenes = AlmacenDAO::search( new Almacen( array( "id_tipo_almacen" => $id_tipo_almacen ) ) );
        foreach($almacenes as $almacen)
        {
            if($almacen->getActivo())
            {
                Logger::error("No se puede eliminar el tipo de almacen ".$id_tipo_almacen." pues aun es usado por almacenes activos");
                throw new Exception("No se puede eliminar este tipo de almacen pues aun hay almacenes activos con este tipo",901);
            }
        }
        
        
        DAO::transBegin();
        try
        {
            TipoAlmacenDAO::delete($tipo_almacen);
        }
        catch(Exception $e)
        {
            DAO::transRollback();
            Logger::error("No se pudo eliminar el tipo de almacen: ".$e);
            throw new Exception("No se pudo eliminar el tipo de almacen, consulte a su administrador de sistema");
        }
        DAO::transEnd();
        Logger::log("Tipo de almacen eliminado exitosamente");
        
        
	}
  
  
	
  
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
	){
            Logger::log("Editando tipo de almacen ".$id_tipo_almacen);
            
            //Se valida que el tipo de almacen exista
            $tipo_almacen = TipoAlmacenDAO::getByPK($id_tipo_almacen);
            if(is_null($tipo_almacen))
            {
                Logger::error("El tipo de almacen ".$id_tipo_almacen." no existe");
                throw new Exception("El tipo de almacen ".$id_tipo_almacen." no existe");
            }
            
            //Se valida el parametro descripcion
            if(!is_null($descripcion))
            {
                $validar = self::validarString($descripcion, 64, "descripcion");
                if(is_string($validar))
                {
                    Logger::error($validar);
                    throw new Exception($validar,901);
                }
                
                $tipos_almacen = array_diff(TipoAlmacenDAO::search(
                        new TipoAlmacen( array( "descripcion" => trim($descripcion) ) )), array( TipoAlmacenDAO::getByPK($id_tipo_almacen) ) );
                if(!empty($tipos_almacen))
                {
                    Logger::error("La descripcion (".$descripcion.") es repetida");
                    throw new Exception("La descripcion esta repetida");
                }
                
                $tipo_almacen->setDescripcion($descripcion);
            }
            
            DAO::transBegin();
            try
            {
                TipoAlmacenDAO::save($tipo_almacen);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo editar el tipo de almacen ".$e);
                throw new Exception("No se pudo editar el tipo de almacen ",901);
            }
            DAO::transEnd();
            Logger::log("Tipo de almacen editado correctamente");
            
        }
  
  
	
  
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
	){
            Logger::log("Creando nuevo tipo de almacen");
            
            
            if(!self::validarLongitudDeCadena($descripcion, 0, 64))
            {
                Logger::error("descfripcion ");
                throw new Exception($descripcion);
            }
            
            //No se puede repetir la descripcion del tipo de almacen
            $tipos_almacen = TipoAlmacenDAO::search(new TipoAlmacen( array( "descripcion" => trim($descripcion) ) ));

            if(!empty($tipos_almacen)){
                Logger::error("La descripcion (".$descripcion.") es repetida");
                throw new BusinessLogicException("La descripcion esta repetida");
            }
            
            $tipo_almacen = new TipoAlmacen( array( "descripcion" => trim($descripcion) ) );
            
            DAO::transBegin();
            try{
                TipoAlmacenDAO::save($tipo_almacen);

            }catch(Exception $e){
                DAO::transRollback();
                
                Logger::error("No se pudo crear el nuevo tipo de almacen: ".$e);

                throw new InvalidDatabaseOperationException("No se pudo crear el nuevo tipo de almacen, contacte a su administrador de sistema");
            }

            DAO::transEnd();
            
            Logger::log("Tipo de almacen creado exitosamente");
            
            return array( "id_tipo_almacen" =>  (int)$tipo_almacen->getIdTipoAlmacen());
            
        }
  
  
	
  
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
	){
		throw new Exception ("Missing Implementation");
	}
  
  
	
  
	/**
 	 *
 	 *Para poder cancelar un traspaso, este no tuvo que haber sido enviado aun.
 	 *
 	 * @param id_traspaso int Id del traspaso a cancelar
 	 **/
  static function CancelarTraspaso
	(
		$id_traspaso
	){
		throw new Exception ("Missing Implementation");
	} 
  
  
	
  
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
	){
		throw new Exception ("Missing Implementation");
	} 
  
  
	
  
	/**
 	 *
 	 *Cambia el estado del traspaso a enviado y captura la fecha de envio del servidor. El usuario que envia sera tomado del servidor.
 	 *
 	 * @param id_traspaso int Id del traspaso a enviar
 	 **/
  static function EnviarTraspaso
	(
		$id_traspaso
	){
		throw new Exception ("Missing Implementation");
	}  
  
  
	
  
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
	){
		throw new Exception ("Missing Implementation");
	}  
  
  
	
  
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
	){
		throw new Exception ("Missing Implementation");
	}  








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
	){}




	/**
 	 *
 	 *Crear un nuevo lote
 	 *
 	 * @param folio string Una cadena unica para cada lote que identifica a este lote.
 	 * @return id_lote int El identificador del lote recien generado.
 	 **/
  static function NuevoLote
	(
		$folio
	){} 




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
	){}  




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
	){}  




	/**
 	 *
 	 *Para poder cancelar un traspaso, este no tuvo que haber sido enviado aun.
 	 *
 	 * @param id_traspaso int Id del traspaso a cancelar
 	 **/
  static function CancelarTraspasoLote
	(
		$id_traspaso
	){}  




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
	){} 




	/**
 	 *
 	 *Cambia el estado del traspaso a enviado y captura la fecha de envio del servidor. El usuario que envia sera tomado del servidor.
 	 *
 	 * @param id_traspaso int Id del traspaso a enviar
 	 **/
  static function EnviarTraspasoLote
	(
		$id_traspaso
	){}  




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
	){} 




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
	){}  




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
	){} 


}