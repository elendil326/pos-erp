<?php
require_once("interfaces/Empresas.interface.php");
/**
  *
  *
  *
  **/
	
  class EmpresasController implements IEmpresas{
  
        static $formato_fecha="Y-m-d H:i:s";
	/**
 	 *
 	 *Mostrar?odas la empresas en el sistema, as?omo sus sucursalse y sus gerentes[a] correspondientes. Por default no se mostraran las empresas ni sucursales inactivas. 
 	 *
 	 * @param activa bool Si no se obtiene este valor, se listaran tanto empresas activas como inactivas, si su valor es true, se mostraran solo las empresas activas, si es false, se mostraran solo las inactivas
 	 * @return empresas json Arreglo de objetos que contendr� las empresas de la instancia
 	 **/
	public function Lista
	(
		$activa = null
	)
	{  
  		if($activa === null)
  			return EmpresaDAO::getAll();

  		$e = new Empresa();
  		$e->setActivo( $activa );
  		return EmpresaDAO::search( $e );


  	}
  
	/**
 	 *
 	 *Relacionar una sucursal a esta empresa. Cuando se llama a este metodo, se crea un almacen de esta sucursal para esta empresa
 	 *
 	 * @param id_empresa int 
 	 * @param sucursales json Arreglo de objetos que tendran los ids de sucursales, un campo opcional de  margen de utilidad que simboliza el margen de utilidad que esas sucursales ganaran para los productos de esa empresa y un campo de descuento, que indica el descuento que se aplicara a todas los productos de esa empresa en esa sucursal
 	 **/
	public function Agregar_sucursales
	(
		$id_empresa, 
		$sucursales
	)
	{
            Logger::log("Agregando sucursales a la empresa");
            if(EmpresaDAO::getByPK($id_empresa)==null)
            {
                Logger::error("La empresa con id: ".$id_empresa." no existe");
                throw new Exception("La empresa con id: ".$id_empresa." no existe");
            }
            $sucursal_empresa=new SucursalEmpresa();
            $sucursal_empresa->setIdEmpresa($id_empresa);
            DAO::transBegin();
            try
            {
                foreach($sucursales as $sucursal)
                {
                    if(SucursalDAO::getByPK($sucursal["id_sucursal"])==null)
                    {
                        Logger::error("La sucursal con id: ".$sucursal["id_sucursal"]." no existe");
                        throw new Exception("La sucursal con id: ".$sucursal["id_sucursal"]." no existe");
                    }
                    $sucursal_empresa->setIdSucursal($sucursal["id_sucursal"]);
                    $sucursal_empresa->setDescuento($sucursal["descuento"]);
                    $sucursal_empresa->setMargenUtilidad($sucursal["margen_utilidad"]);
                    SucursalEmpresaDAO::save($sucursal_empresa);
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudieron agregar las sucursales a la empresa: ".$e);
                throw $e;
            }
            DAO::transEnd();
            Logger::log("Sucursales agregadas exitosamente");
	}
  
	/**
 	 *
 	 *Crear una nueva empresa. Por default una nueva empresa no tiene sucursales.
 	 *
 	 * @param colonia string Colonia de la empresa
 	 * @param telefono1 string telefono de la empresa
 	 * @param codigo_postal string Codigo postal de la empresa
 	 * @param curp string CURP de la nueva empresa.
 	 * @param razon_social string El nombre de la nueva empresa.
 	 * @param numero_exterior string Numero externo de la emresa
 	 * @param ciudad	 int Identificacor de la ciudad
 	 * @param rfc string RFC de la nueva empresa.
 	 * @param calle string Calle de la empresa
 	 * @param numero_interior string Numero interno de la empresa
 	 * @param telefono2 string Telefono 2 de la empresa
 	 * @param e-mail string Correo electronico de la empresa
 	 * @param texto_extra string Comentarios sobre la ubicacin de la empresa.
 	 * @param direccion_web string Direccin web de la empresa
 	 * @param retenciones json Objeto que contendra los ids de las retenciones que aplican a esta empresa
 	 * @param margen_utilidad float Porcentaje del margen de utilidad que le gana esta empresa a todos los productos que ofrece
 	 * @param descuento float Descuento que se aplciara a todos los productos de esta empresa
 	 * @param representante_legal string El nombre del representante legal de la nueva empresa.
 	 * @param impuestos json Objeto que contendra los ids de los impuestos que aplican a esta empresa 
 	 * @return id_empresa int El ID autogenerado de la nueva empresa.
 	 **/
	public function Nuevo
	(
                $rfc,
                $ciudad,
                $curp,
                $numero_exterior,
                $razon_social,
                $colonia,
                $codigo_postal,
                $calle,
                $representante_legal = "",
                $descuento = null,
                $impuestos = "",
                $numero_interior = "",
                $margen_utilidad = null,
                $texto_extra = "",
                $telefono2 = "",
                $email = "",
                $retenciones = null,
                $telefono1 = "",
                $direccion_web = ""
	)
	{  
            Logger::log("Creando empresa");
//            $addr = new Direccion(array(
//                        "calle"             =>  $calle,
//                        "numero_exterior"   =>  $numero_exterior,
//                        "colonia"           =>  $colonia,
//                        "id_ciudad"         =>  $ciudad,
//                        "codigo_postal"     =>  $codigo_postal,
//                        "numero_interior"   =>  $numero_interior,
//                        "referencia"        =>  $texto_extra,
//                        "telefono"          =>  $telefono1,
//                        "telefono2"         =>  $telefono2
//                    ));
            Logger::log("Colonia:" . $colonia);
            $e = new Empresa(array(
                            "activo"                => true,
                            "curp"                  => $curp,
                            "descuento"             => $descuento,
                            "direccion_web"         => $direccion_web,
                            "fecha_alta"            => date("Y-m-d H:i:s",time()),
                            "fecha_baja"            => null,
                            "margen_utilidad"       => $margen_utilidad,
                            "razon_social"          => $razon_social,
                            "representante_legal"   => $representante_legal,
                            "rfc"                   => $rfc
                    ));
             DAO::transBegin();
             try
             {
                 $id_direccion = DireccionController::NuevaDireccion( 
                    $calle,
                    $numero_exterior,
                    $colonia,
                    $ciudad,
                    $codigo_postal,
                    $numero_interior,
                    $texto_extra,
                    $telefono1,
                    $telefono2
                  );

                 $e->setIdDireccion($id_direccion);
                 EmpresaDAO::save($e);
                 $impuesto_empresa=new ImpuestoEmpresa(array("id_empresa" => $e->getIdEmpresa()));
                 if($impuestos)
                 foreach($impuestos as $id_impuesto)
                 {
                     if(ImpuestoDAO::getByPK($id_impuesto)==null)
                     {
                         Logger::error("El impuesto con id: ".$id_impuesto." no existe");
                         throw new Exception("El impuesto con id: ".$id_impuesto." no existe");
                     }
                     $impuesto_empresa->setIdImpuesto($id_impuesto);
                     ImpuestoEmpresaDAO::save($impuesto_empresa);
                 }
                 $retencion_empresa=new RetencionEmpresa(array("id_empresa" => $e->getIdEmpresa()));
                 if($retenciones)
                 foreach($retenciones as $id_retencion)
                 {
                     if(RetencionDAO::getByPK($id_retencion)==null)
                     {
                         Logger::error("La retencion con id: ".$id_retencion." no existe");
                         throw new Exception("La retencion con id: ".$id_retencion." no existe");
                     }
                     $retencion_empresa->setIdRetencion($id_retencion);
                     RetencionEmpresaDAO::save($retencion_empresa);
                 }
             }
             catch(Exception $e)
             {
                 DAO::transRollback();
                 Logger::error("Error al crear la empresa: ".$e);
                 throw $e;
             }
             DAO::transEnd();
             Logger::log("Empresa creada exitosamente");

            return array ( "id_empresa" => $e->getIdEmpresa() );
	}
  
	/**
 	 *
 	 *Para poder eliminar una empresa es necesario que la empresa no tenga sucursales activas, sus saldos sean 0, que los clientes asociados a dicha empresa no tengan adeudo, ...
 	 *
 	 * @param id_empresa string El id de la empresa a eliminar.
 	 **/
	public function Eliminar
	(
		$id_empresa
	)
	{
            Logger::log("Eliminando empresa");
            $empresa=EmpresaDAO::getByPK($id_empresa);
            if($empresa==null)
            {
                Logger::error("La empresa con id: ".$id_empresa." no existe");
                throw new Exception("La empresa con id: ".$id_empresa." no existe");
            }
            if(!$empresa->getActivo())
            {
                Logger::warn("La empresa ya esta cancelada");
                return;
            }
            $empresa->setActivo(0);

            $pr=new ProductoEmpresa(array("id_empresa"=>$id_empresa));
            $producto_empresa=new ProductoEmpresa();
            $productos_empresa=ProductoEmpresaDAO::search($pr);

            $pa=new PaqueteEmpresa(array("id_empresa"=>$id_empresa));
            $paquete_empresa=new PaqueteEmpresa();
            $paquetes_empresa=PaqueteEmpresaDAO::search($pa);

            $se=new ServicioEmpresa(array("id_empresa"=>$id_empresa));
            $servicio_empresa=new ServicioEmpresa();
            $servicios_empresa=ServicioEmpresaDAO::search($se);

            $su=new SucursalEmpresa(array("id_empresa"=>$id_empresa));
            $sucursal_empresa=new SucursalEmpresa();
            $sucursales_empresa=SucursalEmpresaDAO::search($su);

            $almacen=new Almacen(array("id_empresa"=>$id_empresa));

            $productos_controller=new ProductosController();
            $paquetes_controller=new PaquetesController();
            $servicios_controller=new ServiciosController();
            $sucursales_controller=new SucursalesController();

            DAO::transBegin();
            try
            {
                EmpresaDAO::save($empresa);
                foreach($productos_empresa as $producto)
                {
                    $producto_empresa->setIdProducto($producto->getIdProducto());
                    $productos=ProductoEmpresaDAO::search($producto_empresa);
                    if(count($productos)<2)
                    {
                        $productos_controller->Desactivar($producto->getIdProducto());
                    }
                }

                foreach($paquetes_empresa as $paquete)
                {
                    $paquete_empresa->setIdPaquete($paquete->getIdPaquete());
                    $paquetes=PaqueteEmpresaDAO::search($paquete_empresa);
                    if(count($paquetes)<2)
                    {
                        $paquetes_controller->Eliminar($paquete->getIdPaquete());
                    }
                }

                foreach($servicios_empresa as $servicio)
                {
                    $servicio_empresa->setIdServicio($servicio->getIdServicio());
                    $servicios=ServicioEmpresaDAO::search($servicio_empresa);
                    if(count($servicios)<2)
                    {
                        $servicios_controller->Eliminar($servicio->getIdServicio());
                    }
                }

                foreach($sucursales_empresa as $sucursal)
                {
                    $sucursal_empresa->setIdSucursal($sucursal->getIdSucursal());
                    $sucursales=SucursalEmpresaDAO::search($sucursal_empresa);
                    if(count($sucursales)<2)
                    {
                        $sucursales_controller->Eliminar($sucursal->getIdSucursal());
                    }
                    else
                    {
                        $almacen->setIdSucursal($sucursal->getIdSucursal());
                        $almacenes=AlmacenDAO::search($almacen);
                        foreach($almacenes as $a)
                        {
                            if($a->getIdTipoAlmacen()!=2&&$a->getActivo())
                                $sucursales_controller->EliminarAlmacen($a->getIdAlmacen());
                        }
                    }
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("Error al eliminar la empresa: ".$e);
                throw $e;
            }
            DAO::transEnd();
            Logger::log("Empresa eliminada exitosamente");
	}

  
	/**
 	 *
 	 *Un administrador puede editar una sucursal, incuso si hay puntos de venta con sesiones activas que pertenecen a esa empresa. 
 	 *
 	 * @param telefono1 string telefono de la empresa
 	 * @param numero_exterior	 string Numero externo de la emresa
 	 * @param colonia	 string Colonia de la empresa
 	 * @param codigo_postal string Codigo postal de la empresa
 	 * @param curp string CURP de la nueva empresa.
 	 * @param calle	 string Calle de la empresa
 	 * @param id_empresa int Id de la empresa a modificar
 	 * @param rfc string RFC de la nueva empresa.
 	 * @param ciudad int Ciudad donde se encuentra la empresa
 	 * @param razon_social string El nombre de la nueva empresa.
 	 * @param e-mail string Correo electronico de la empresa
 	 * @param representante_legal string El nombre del representante legal de la nueva empresa.
 	 * @param numero_interno string Numero interno de la empresa
 	 * @param direccion_web string Direccin web de la empresa
 	 * @param retenciones json Objeto que contendra los ids de las retenciones que aplican a esta empresa
 	 * @param descuento float Descuento que se aplicara a todos los productos de esta empresa
 	 * @param margen_utilidad float Porcentaje del margen de utilidad que esta empresa le gana a todos sus productos
 	 * @param impuestos json Objeto que contendra los ids de los impuestos que afectan a esta empresa
 	 * @param texto_extra string Comentarios sobre la ubicacin de la empresa.
 	 * @param telefono2 string Telefono 2 de la empresa
 	 **/
	public function Editar
	(
		$id_empresa,
		$descuento = null,
		$margen_utilidad = null,
		$impuestos = null,
		$retenciones = null,
		$direccion_web = null,
		$ciudad = null,
		$razon_social = null,
		$rfc = null,
		$codigo_postal = null,
		$curp = null,
		$calle	 = null,
		$numero_interno = null,
		$representante_legal = null,
		$telefono1 = null,
		$numero_exterior	 = null,
		$colonia	 = null,
		$email = null,
		$telefono2 = null,
		$texto_extra = null
	)
	{
            Logger::log("Editando la empresa");
            $empresa=EmpresaDAO::getByPK($id_empresa);
            $modificar_direccion=false;
            if($empresa==null)
            {
                Logger::error("La empresa con id: ".$id_empresa." no existe");
                throw new Exception("La empresa con id: ".$id_empresa." no existe");
            }
            if(!$empresa->getActivo())
            {
                Logger::error("La empresa no esta activa, no se puede editar una empresa desactivada");
                throw new Exception("La empresa no esta activa, no se puede editar una empresa desactivada");
            }
            $direccion=DireccionDAO::getByPK($empresa->getIdDireccion());
            if($direccion==null)
            {
                Logger::error("FATAL!!! La empresa no cuenta con una direccion");
                throw new Exception("FATAL!!! La empresa no cuenta con una direccion");
            }
            if($descuento!==null)
            {
                $empresa->setDescuento($descuento);
            }
            if($margen_utilidad!==null)
            {
                $empresa->setMargenUtilidad($margen_utilidad);
            }
            if($direccion_web!==null)
            {
                $empresa->setDireccionWeb($direccion_web);
            }
            if($ciudad!==null)
            {
                $direccion->setIdCiudad($ciudad);
                $modificar_direccion=true;
            }
            if($razon_social!==null)
            {
                $empresa->setRazonSocial($razon_social);
            }
            if($rfc!==null)
            {
                $empresa->setRfc($rfc);
            }
            if($codigo_postal!==null)
            {
                $direccion->setCodigoPostal($codigo_postal);
                $modificar_direccion=true;
            }
            if($curp!==null)
            {
                $empresa->setCurp($curp);
            }
            if($calle!==null)
            {
                $direccion->setCalle($calle);
                $modificar_direccion=true;
            }
            if($numero_interno!==null)
            {
                $direccion->setNumeroInterior($numero_interno);
                $modificar_direccion=true;
            }
            if($representante_legal!==null)
            {
                $empresa->setRepresentanteLegal($representante_legal);
            }
            if($telefono1!==null)
            {
                $direccion->setTelefono($telefono1);
                $modificar_direccion=true;
            }
            if($numero_exterior!==null)
            {
                $direccion->setNumeroExterior($numero_exterior);
                $modificar_direccion=true;
            }
            if($colonia!==null)
            {
                $direccion->setColonia($colonia);
                $modificar_direccion=true;
            }
            if($telefono2!==null)
            {
                $direccion->setTelefono2($telefono2);
                $modificar_direccion=true;
            }
            if($texto_extra!==null)
            {
                $direccion->setReferencia($texto_extra);
                $modificar_direccion=true;
            }
            if($modificar_direccion)
            {
                $direccion->setUltimaModificacion("Y-m-d H:i:s",time());
                $id_usuario=LoginController::getCurrentUser();
                if($id_usuario==null)
                {
                    Logger::error("No se pudo obtener el usuario de la sesion, ya inicio sesion?");
                    throw new Exception("No se pudo obtener el usuario de la sesion, ya inicio sesion?");
                }
            }
            $impuesto_empresa=new ImpuestoEmpresa(array("id_empresa"=>$id_empresa));
            $impuestos_empresa=ImpuestoEmpresaDAO::search($impuesto_empresa);
            $retencion_empresa=new RetencionEmpresa(array("id_empresa"=>$id_empresa));
            $retenciones_empresa=RetencionEmpresaDAO::search($retencion_empresa);
            DAO::transBegin();
            try
            {
                EmpresaDAO::save($empresa);
                DireccionDAO::save($direccion);
                if($impuestos!==null)
                {
                    $i_empresa=new ImpuestoEmpresa(array("id_empresa"=>$id_empresa));
                    foreach($impuestos as $id_impuesto)
                    {
                        if(ImpuestoDAO::getByPK($id_impuesto)==null)
                        {
                            throw new Exception("El impuesto con id: ".$id_impuesto." no existe");
                        }
                        $i_empresa->setIdImpuesto($id_impuesto);
                        ImpuestoEmpresaDAO::save($i_empresa);
                    }
                    foreach($impuestos_empresa as $impuesto_empresa)
                    {
                        $encontrado=false;
                        foreach($impuestos as $id_impuesto)
                        {
                            if($id_impuesto==$impuesto_empresa->getIdImpuesto())
                            {
                                    $encontrado=true;
                                    break;
                            }
                        }
                        if(!$encontrado)
                        {
                            ImpuestoEmpresaDAO::delete($impuesto_empresa);
                        }
                    }
                }
                if($retenciones!==null)
                {
                    $r_empresa=new RetencionEmpresa(array("id_empresa"=>$id_empresa));
                    foreach($retenciones as $id_retencion)
                    {
                        if(ImpuestoDAO::getByPK($id_retencion)==null)
                        {
                            throw new Exception("La retencion con id: ".$id_retencion." no existe");
                        }
                        $r_empresa->setIdRetencion($id_retencion);
                        RetencionEmpresaDAO::save($r_empresa);
                    }
                    foreach($retenciones_empresa as $retencion_empresa)
                    {
                        $encontrado=false;
                        foreach($retenciones as $id_retencion)
                        {
                            if($id_retencion==$retencion_empresa->getIdRetencion())
                            {
                                $encontrado=true;
                                break;
                            }
                        }
                        if(!$encontrado)
                        {
                            RetencionEmpresaDAO::delete($retencion_empresa);
                        }
                    }
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo modificar la empresa");
                throw $e;
            }
            DAO::transEnd();
            Logger::log("Empresa editada con exito");
	}
  }
