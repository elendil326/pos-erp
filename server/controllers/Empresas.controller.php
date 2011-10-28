<?php
require_once("interfaces/Empresas.interface.php");
/**
  *
  *
  *
  **/
	
  class EmpresasController implements IEmpresas{

          private static function validarString($string, $max_length, $nombre_variable,$min_length=0)
	{
		if(strlen($string)<=$min_length||strlen($string)>$max_length)
		{
		    return "La longitud de la variable ".$nombre_variable." proporcionada (".$nombre_variable.") no esta en el rango de ".$min_length." - ".$max_length;
		}
		return true;
    }



	private static function validarNumero($num, $max_length, $nombre_variable, $min_length=0)
	{
	    if($num<$min_length||$num>$max_length)
	    {
	        return "La variable ".$nombre_variable." proporcionada (".$num.") no esta en el rango de ".$min_length." - ".$max_length;
	    }
	    return true;
	}

          private static function validarParametrosEmpresa
          (
                  $id_empresa=null,
                  $id_direccion=null,
                  $curp=null,
                  $rfc=null,
                  $razon_social=null,
                  $representante_legal=null,
                  $activo=null,
                  $direccion_web=null,
                  $margen_utilidad=null,
                  $descuento=null
          )
          {
                if(!is_null($id_empresa))
                {
                    if(is_null(EmpresaDAO::getByPK($id_empresa)))
                    {
                        return "La empresa con id: ".$id_empresa." no existe";
                    }
                }
                if(!is_null($id_direccion))
                {
                    if(is_null(DireccionDAO::getByPK($id_direccion)))
                    {
                         return "La direccion con id: ".$id_direccion." no existe";
                    }
                }
                if(!is_null($curp))
                {
                    $e=self::validarString($curp, 30, "curp");
                    if(is_string($e))
                        return $e;
                    if(preg_match('/[^A-Z0-9]/' ,$curp))
                            return "El curp ".$curp." contiene caracteres fuera del rango A-Z y 0-9";
                }
                if(!is_null($rfc))
                {
                    $e=self::validarString($curp, 30, "rfc");
                    if(is_string($e))
                        return $e;
                }
                if(!is_null($razon_social))
                {
                    $e=self::validarString($razon_social, 100, "razon social");
                    if(is_string($e))
                        return $e;
                }
                if(!is_null($representante_legal))
                {
                    $e=self::validarString($representante_legal, 100, "representante legal");
                    if(is_string($e))
                        return $e;
                }
                if(!is_null($fecha_alta))
                {
                    $e=self::validarString($fecha_alta, strlen("YYYY-mm-dd HH:ii:ss"), "fecha de alta");
                    if(is_string($e))
                        return $e;
                }
                if(!is_null($fecha_baja))
                {
                    $e=self::validarString($fecha_baja, strlen("YYYY-mm-dd HH:ii:ss"), "fecha de baja");
                    if(is_string($e))
                        return $e;
                }
                if(!is_null($activo))
                {
                    $e=self::validarNumero($activo, 1, "activo");
                    if(is_string($e))
                        return $e;
                }
                if(!is_null($direccion_web))
                {
                    $e=self::validarString($direccion_web, 20, "direccion web");
                    if(is_string($e))
                        return $e;
                    if(!preg_match('/^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,6}'.'((:[0-9]{1,5})?\/.*)?$/i' ,$direccion_web)&&
                    !preg_match('/^[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,6}'.'((:[0-9]{1,5})?\/.*)?$/i' ,$direccion_web))
                            return "La direccion web ".$direccion_web." no cumple el formato esperado";
                }
                if(!is_null($margen_utilidad))
                {
                    $e=self::validarNumero($margen_utilidad, 1.8e100, "margen de utilidad");
                    if(is_string($e))
                        return $e;
                }
                if(!is_null($descuento))
                {
                    $e=self::validarNumero($descuento, 100, "descuento");
                    if(is_string($e))
                        return $e;
                }
                return true;
          }

          private static function validarParametrosSucursalEmpresa
          (
                  $id_sucursal=null,
                  $id_empresa=null,
                  $margen_utiliad=null,
                  $descuento=null
          )
          {
              if(!is_null($id_sucursal))
              {
                  if(is_null(SucursalDAO::getByPK($id_sucursal)))
                  {
                      return "La sucursal con id: ".$id_sucursal." no existe";
                  }
              }
              if(!is_null($id_empresa))
              {
                  if(is_null(EmpresaDAO::getByPK($id_empresa)))
                  {
                      return "La empresa con id: ".$id_empresa." no existe";
                  }
              }
              if(!is_null($margen_utilidad))
              {
                  $e=self::validarNumero($margen_utilidad, 1.8e200, "margen de utilidad");
                  if(is_string($e))
                      return $e;
              }
              if(!is_null($descuento))
              {
                  $e=self::validarNumero($descuento, 100, "descuento");
                  if(is_string($e))
                      return $e;
              }
          }

	/**
 	 *
 	 *Mostrar?odas la empresas en el sistema, as?omo sus sucursalse y sus gerentes[a] correspondientes. Por default no se mostraran las empresas ni sucursales inactivas. 
 	 *
 	 * @param activa bool Si no se obtiene este valor, se listaran tanto empresas activas como inactivas, si su valor es true, se mostraran solo las empresas activas, si es false, se mostraran solo las inactivas
 	 * @return empresas json Arreglo de objetos que contendr� las empresas de la instancia
 	 **/
	public static function Lista
	(
		$activa = null
	)
	{  
  		if(is_null($activa))
  			return EmpresaDAO::getAll();
                $validar=self::validarParametrosEmpresa(null, null, null, null, null, null, $activo);
                if(is_string($validar))
                {
                    Logger::error($validar);
                    throw new Exception($validar);
                }
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
	public static function Agregar_sucursales
	(
		$id_empresa, 
		$sucursales
	)
	{
            Logger::log("Agregando sucursales a la empresa");
            $validar=self::validarParametrosEmpresa($id_empresa);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            $sucursal_empresa=new SucursalEmpresa();
            $sucursal_empresa->setIdEmpresa($id_empresa);
            DAO::transBegin();
            try
            {
                foreach($sucursales as $sucursal)
                {
                    $validar=self::validarParametrosSucursalEmpresa($sucursal["id_sucursal"], null,$sucursal["margen_utilidad"], $sucursal["descuento"]);
                    if(is_string($validar))
                    {
                        throw new Exception($validar);
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
	public static function Nuevo
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
            $validar=self::validarParametrosEmpresa(null, null, $curp, $rfc, $razon_social, $representante_legal, null, $direccion_web, $margen_utilidad, $descuento);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
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
            $empresas=EmpresaDAO::search(new Empresa( array( "curp" => $curp ) ));
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
                     if(is_null(ImpuestoDAO::getByPK($id_impuesto)))
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
                     if(is_null(RetencionDAO::getByPK($id_retencion)))
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
	public static function Eliminar
	(
		$id_empresa
	)
	{
            Logger::log("Eliminando empresa");
            $empresa=EmpresaDAO::getByPK($id_empresa);
            if(is_null($empresa))
            {
                Logger::error("La empresa con id: ".$id_empresa." no existe");
                throw new Exception("La empresa con id: ".$id_empresa." no existe");
            }
            if(!$empresa->getActivo())
            {
                Logger::warn("La empresa ya esta cancelada");
                throw new Exception("La empresa ya esta cancelada");
            }
            $empresa->setActivo(0);
            $empresa->setFechaBaja(date("Y-m-d H:i:s"));
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
                        ProductosController::Desactivar($producto->getIdProducto());
                    }
                }

                foreach($paquetes_empresa as $paquete)
                {
                    $paquete_empresa->setIdPaquete($paquete->getIdPaquete());
                    $paquetes=PaqueteEmpresaDAO::search($paquete_empresa);
                    if(count($paquetes)<2)
                    {
                        PaquetesController::Eliminar($paquete->getIdPaquete());
                    }
                }

                foreach($servicios_empresa as $servicio)
                {
                    $servicio_empresa->setIdServicio($servicio->getIdServicio());
                    $servicios=ServicioEmpresaDAO::search($servicio_empresa);
                    if(count($servicios)<2)
                    {
                        ServiciosController::Eliminar($servicio->getIdServicio());
                    }
                }

                foreach($sucursales_empresa as $sucursal)
                {
                    $sucursal_empresa->setIdSucursal($sucursal->getIdSucursal());
                    $sucursales=SucursalEmpresaDAO::search($sucursal_empresa);
                    if(count($sucursales)<2)
                    {
                        SucursalesController::Eliminar($sucursal->getIdSucursal());
                    }
                    else
                    {
                        $almacen->setIdSucursal($sucursal->getIdSucursal());
                        $almacenes=AlmacenDAO::search($almacen);
                        foreach($almacenes as $a)
                        {
                            if($a->getIdTipoAlmacen()!=2&&$a->getActivo())
                                SucursalesController::EliminarAlmacen($a->getIdAlmacen());
                        }
                    }
                    SucursalEmpresaDAO::delete($sucursal);
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
	public static function Editar
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
            if(is_null($empresa))
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
            if(is_null($direccion))
            {
                Logger::error("FATAL!!! La empresa no cuenta con una direccion");
                throw new Exception("FATAL!!! La empresa no cuenta con una direccion");
            }
            if(!is_null($descuento))
            {
                $empresa->setDescuento($descuento);
            }
            if(!is_null($margen_utilidad))
            {
                $empresa->setMargenUtilidad($margen_utilidad);
            }
            if(!is_null($direccion_web))
            {
                $empresa->setDireccionWeb($direccion_web);
            }
            if(!is_null($ciudad))
            {
                $direccion->setIdCiudad($ciudad);
                $modificar_direccion=true;
            }
            if(!is_null($razon_social))
            {
                $empresa->setRazonSocial($razon_social);
            }
            if(!is_null($rfc))
            {
                $empresa->setRfc($rfc);
            }
            if(!is_null($codigo_postal))
            {
                $direccion->setCodigoPostal($codigo_postal);
                $modificar_direccion=true;
            }
            if(!is_null($curp))
            {
                $empresa->setCurp($curp);
            }
            if(!is_null($calle))
            {
                $direccion->setCalle($calle);
                $modificar_direccion=true;
            }
            if(!is_null($numero_interno))
            {
                $direccion->setNumeroInterior($numero_interno);
                $modificar_direccion=true;
            }
            if(!is_null($representante_legal))
            {
                $empresa->setRepresentanteLegal($representante_legal);
            }
            if(!is_null($telefono1))
            {
                $direccion->setTelefono($telefono1);
                $modificar_direccion=true;
            }
            if(!is_null($numero_exterior))
            {
                $direccion->setNumeroExterior($numero_exterior);
                $modificar_direccion=true;
            }
            if(!is_null($colonia))
            {
                $direccion->setColonia($colonia);
                $modificar_direccion=true;
            }
            if(!is_null($telefono2))
            {
                $direccion->setTelefono2($telefono2);
                $modificar_direccion=true;
            }
            if(!is_null($texto_extra))
            {
                $direccion->setReferencia($texto_extra);
                $modificar_direccion=true;
            }
            if($modificar_direccion)
            {
                $direccion->setUltimaModificacion("Y-m-d H:i:s",time());
                $id_usuario=LoginController::getCurrentUser();
                if(is_null($id_usuario))
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
                if(!is_null($impuestos))
                {
                    $i_empresa=new ImpuestoEmpresa(array("id_empresa"=>$id_empresa));
                    foreach($impuestos as $id_impuesto)
                    {
                        if(is_null(ImpuestoDAO::getByPK($id_impuesto)))
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
                if(!is_null($retenciones))
                {
                    $r_empresa=new RetencionEmpresa(array("id_empresa"=>$id_empresa));
                    foreach($retenciones as $id_retencion)
                    {
                        if(is_null(ImpuestoDAO::getByPK($id_retencion)))
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
