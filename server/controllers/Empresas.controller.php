<?php
require_once("interfaces/Empresas.interface.php");
/**
  *
  *
  *
  **/
	
  class EmpresasController implements IEmpresas{

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
         * Valida los parametros de la tabla empresa haciendo uso de las validaciones basicas
         * de string y num. El minimo y el maximo se toman de la tabla y de su uso en particular.
         */
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
              //Se valida que la empresa exista en la base de datos
                if(!is_null($id_empresa))
                {
                    if(is_null(EmpresaDAO::getByPK($id_empresa)))
                    {
                        return "La empresa con id: ".$id_empresa." no existe";
                    }
                }
                //se valida que la direccion exista en la base de datos
                if(!is_null($id_direccion))
                {
                    if(is_null(DireccionDAO::getByPK($id_direccion)))
                    {
                         return "La direccion con id: ".$id_direccion." no existe";
                    }
                }

                //se valida que la curp tenga solo letras mayusculas y numeros
                if(!is_null($curp))
                {
                    $e=self::validarString($curp, 30, "curp");
                    if(is_string($e))
                        return $e;
                    if(preg_match('/[^A-Z0-9]/' ,$curp))
                            return "El curp ".$curp." contiene caracteres fuera del rango A-Z y 0-9";
                }

                //se valida que el rfc tenga solo letras mayusculas y numeros.
                if(!is_null($rfc))
                {
                    $e=self::validarString($curp, 30, "rfc");
                    if(is_string($e))
                        return $e;
                    if(preg_match('/[^A-Z0-9]/' ,$rfc))
                            return "El rfc ".$rfc." contiene caracteres fuera del rango A-Z y 0-9";
                }

                //se valida que la razon social este en le rango
                if(!is_null($razon_social))
                {
                    $e=self::validarString($razon_social, 100, "razon social");
                    if(is_string($e))
                        return $e;
                }

                //se valida que el representante legal este en el rango
                if(!is_null($representante_legal))
                {
                    $e=self::validarString($representante_legal, 100, "representante legal");
                    if(is_string($e))
                        return $e;
                }

                //se valida que el boleano activo este en el rango
                if(!is_null($activo))
                {
                    $e=self::validarNumero($activo, 1, "activo");
                    if(is_string($e))
                        return $e;
                }

                //se valida que la direccion web tenga un formato adecuado
                if(!is_null($direccion_web))
                {
                    $e=self::validarString($direccion_web, 20, "direccion web");
                    if(is_string($e))
                        return $e;
                    if(!preg_match('/^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,6}'.'((:[0-9]{1,5})?\/.*)?$/i' ,$direccion_web)&&
                    !preg_match('/^[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,6}'.'((:[0-9]{1,5})?\/.*)?$/i' ,$direccion_web))
                            return "La direccion web ".$direccion_web." no cumple el formato esperado";
                }

                //Se valida que el margen de utilidad este en rango
                if(!is_null($margen_utilidad))
                {
                    $e=self::validarNumero($margen_utilidad, 1.8e200, "margen de utilidad");
                    if(is_string($e))
                        return $e;
                }

                //se valida que el descuento este en rango. Descuento es un porcentaje y no puede pasar de 100
                if(!is_null($descuento))
                {
                    $e=self::validarNumero($descuento, 100, "descuento");
                    if(is_string($e))
                        return $e;
                }
                return true;
          }

          /*
           * Valida los parametros de la tabla sucursal_empresa
           */
          private static function validarParametrosSucursalEmpresa
          (
                  $id_sucursal=null,
                  $id_empresa=null,
                  $margen_utilidad=null,
                  $descuento=null
          )
          {
              //verifica que la sucursal exista en la base de datos
              if(!is_null($id_sucursal))
              {
                  if(is_null(SucursalDAO::getByPK($id_sucursal)))
                  {
                      return "La sucursal con id: ".$id_sucursal." no existe";
                  }
              }

              //verifica que la empresa exista en la base de datos
              if(!is_null($id_empresa))
              {
                  if(is_null(EmpresaDAO::getByPK($id_empresa)))
                  {
                      return "La empresa con id: ".$id_empresa." no existe";
                  }
              }

              //valida que el margen de utilidad este en el rango
              if(!is_null($margen_utilidad))
              {
                  $e=self::validarNumero($margen_utilidad, 1.8e200, "margen de utilidad");
                  if(is_string($e))
                      return $e;
              }

              //valida que el descuento no pase de 100 pues e sun porcentaje
              if(!is_null($descuento))
              {
                  $e=self::validarNumero($descuento, 100, "descuento");
                  if(is_string($e))
                      return $e;
              }
          }
          
          /*
           * Valida los parametros de la tabla impuesto_empresa
           */
          private static function validarParametrosImpuestoEmpresa
          (
                  $id_impuesto = null,
                  $id_empresa = null
          )
          {
              //valida que el impuesto exista en la base de datos
              if(!is_null($id_impuesto))
              {
                  if(is_null(ImpuestoDAO::getByPK($id_impuesto)))
                          return "El impuesto con id: ".$id_impuesto." no existe";
              }
              
              //valida que la empresa exista en la base de datos
              if(!is_null($id_empresa))
              {
                  if(is_null(EmpresaDAO::getByPK($id_empresa)))
                          return "La empresa con id: ".$id_empresa." no existe";
              }
              return true;
          }
          
           /*
           * Valida los parametros de la tabla retencion_empresa
           */
          private static function validarParametrosRetencionEmpresa
          (
                  $id_retencion = null,
                  $id_empresa = null
          )
          {
              //valida que la retencion exista en la base de datos
              if(!is_null($id_retencion))
              {
                  if(is_null(ImpuestoDAO::getByPK($id_retencion)))
                          return "La retencion con id: ".$id_retencion." no existe";
              }
              
              //valida que la empresa exista en la base de datos
              if(!is_null($id_empresa))
              {
                  if(is_null(EmpresaDAO::getByPK($id_empresa)))
                          return "La empresa con id: ".$id_empresa." no existe";
              }
              return true;
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
            Logger::log("Listando empresas");
            //Si activa no es obtenida, se regresan todas las empresas.
  		if(is_null($activa))
                {
                    Logger::log("Se listan todas las empresas");
  			return EmpresaDAO::getAll();
                }

                //Se valida el boleano activa
                $validar=self::validarParametrosEmpresa(null, null, null, null, null, null, $activa);
                if(is_string($validar))
                {
                    Logger::error($validar);
                    throw new Exception($validar);
                }

                //Se listan las empresas con el valor de activa obtenido
  		$e = new Empresa();
  		$e->setActivo( $activa );
                Logger::log("Listando empresas con activo = ".$activa);
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

            //Se valida que la empresa exista en la base de datos
            $validar=self::validarParametrosEmpresa($id_empresa);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }

            //Se crea un registro de sucursal-empresa y se le asigna como empresa la obtenida.
            $sucursal_empresa=new SucursalEmpresa();
            $sucursal_empresa->setIdEmpresa($id_empresa);
            DAO::transBegin();
            try
            {
                //Por cada una de las sucursales como sucursal, se validan los parametros que contiene
                //y si son v'alidos, se almacenan en el objeto sucursal-empresa para luego guardarlo.
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
                throw new Exception("NO se pudieron agregar las sucursales a la empresa");
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
                $representante_legal = null,
                $descuento = null,
                $impuestos = null,
                $numero_interior = null,
                $margen_utilidad = null,
                $texto_extra = null,
                $telefono2 = null,
                $email = null,
                $retenciones = null,
                $telefono1 = null,
                $direccion_web = null
	)
	{  
            Logger::log("Creando empresa");

            //Se validan los parametros
            $validar=self::validarParametrosEmpresa(null, null, $curp, $rfc, $razon_social, $representante_legal, null, $direccion_web, $margen_utilidad, $descuento);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }

            //Se crea la empresa con los parametros obtenidos.
            $e = new Empresa(array(
                            "activo"                => true,
                            "curp"                  => $curp,
                            "descuento"             => $descuento,
                            "direccion_web"         => $direccion_web,
                            "fecha_alta"            => date("Y-m-d H:i:s",time()),
                            "fecha_baja"            => null,
                            "margen_utilidad"       => $margen_utilidad,
                            "razon_social"          => trim($razon_social),
                            "representante_legal"   => $representante_legal,
                            "rfc"                   => $rfc
                    ));

            //Se busca la curp en la base de datos. Si hay una empresa activa
            //con esa misma curp se lanza un error
            $empresas=EmpresaDAO::search(new Empresa( array( "curp" => $curp ) ));
            foreach($empresas as $empresa)
            {
                if($empresa->getActivo())
                {
                    Logger::error("El curp: ".$curp." ya esta en uso por la empresa: ".$empresa->getIdEmpresa());
                    throw new Exception("El curp: ".$curp." ya esta en uso");
                }
            }

            //Se busca el rfc en la base de datos. Si hay una empresa activa
            //con el mismo rfc se lanza un error
            $empresas=EmpresaDAO::search( new Empresa( array( "rfc" => $rfc ) ) );
            foreach($empresas as $empresa)
            {
                if($empresa->getActivo())
                {
                    Logger::error("El rfc: ".$rfc." ya esta en uso por la empresa: ".$empresa->getIdEmpresa());
                    throw new Exception("El rfc: ".$rfc." ya esta en uso");
                }
            }
            
            //Se busca la razon social en la base de datos. Si hay una empresa activa
            //con la misma razon zocial se lanza un error. Se usa trim para cubrir 
            //los casos "caffeina" y "    caffeina    ".
            $empresas=EmpresaDAO::search( new Empresa( array( "razon_social" => trim($razon_social) ) ) );
            foreach($empresas as $empresa)
            {
                if($empresa->getActivo())
                {
                    Logger::error("La razon social: ".$razon_social." ya esta en uso por la empresa: ".$empresa->getIdEmpresa());
                    throw new Exception("La razon social: ".$razon_social." ya esta en uso");
                }
            }
             DAO::transBegin();
             try
             {
                 //Se crea la nueva direccion con los parametros obtenidos.
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
                 
                 //Se agrega la direccion a la empresa y se guarda
                 $e->setIdDireccion($id_direccion);
                 EmpresaDAO::save($e);
                 
                 //Si se recibieron impuestos se genera un registro impuesto-empresa y 
                 //se inicializa con el id de esta empresa.
                 //Por cada uno de los impuestos como id impuesto, se verifica que el 
                 //impuesto exista, se asigna al registro y se guarda.
                 if($impuestos)
                 {
                     $impuesto_empresa=new ImpuestoEmpresa(array("id_empresa" => $e->getIdEmpresa()));
                     foreach($impuestos as $id_impuesto)
                     {
                         $validar=self::validarParametrosImpuestoEmpresa($id_impuesto);
                         if(is_sring($validar))
                         {
                             Logger::error($validar);
                             throw new Exception($validar);
                         }
                         $impuesto_empresa->setIdImpuesto($id_impuesto);
                         ImpuestoEmpresaDAO::save($impuesto_empresa);
                     }
                 }
                 
                 //Si se recibieron retenciones se genera un registro retencion-empresa y 
                 //se inicializa con el id de esta empresa.
                 //Por cada uno de las retenciones como id retencion, se verifica que la 
                 //retencion exista, se asigna al registro y se guarda.
                 if($retenciones)
                 {
                     $retencion_empresa=new RetencionEmpresa(array("id_empresa" => $e->getIdEmpresa()));
                     foreach($retenciones as $id_retencion)
                     {
                         $validar = self::validarParametrosRetencionEmpresa($id_retencion);
                         if(is_string($validar))
                         {
                             Logger::error($validar);
                             throw new Exception($validar);
                         }
                         $retencion_empresa->setIdRetencion($id_retencion);
                         RetencionEmpresaDAO::save($retencion_empresa);
                     }
                 }
             }
             catch(Exception $e)
             {
                 DAO::transRollback();
                 Logger::error("No se pudo crear la empresa: ".$e);
                 throw new Exception("No se pudo crear la empresa");
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
            
            //se valida que la empresa exista en la base de datos
            $validar = self::validarParametrosEmpresa($id_empresa);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($valdiar);
            }
            
            //Se guarda el registro de la empresa y se verifica si esat activa
            $empresa=EmpresaDAO::getByPK($id_empresa);
            if(!$empresa->getActivo())
            {
                Logger::warn("La empresa ya esta cancelada");
                throw new Exception("La empresa ya esta cancelada");
            }
            
            //Se cambia el campo activo a falso y se registra la fecha de baja como hoy
            $empresa->setActivo(0);
            $empresa->setFechaBaja(date("Y-m-d H:i:s"));
            
            //Se buscan los productos pertenecientes a esta empresa y 
            //se inicializa una variable temporal producto_empresa
            $pr=new ProductoEmpresa(array("id_empresa"=>$id_empresa));
            $productos_empresa=ProductoEmpresaDAO::search($pr);
            $producto_empresa=new ProductoEmpresa();
            
            //Se buscan los paquetes pertenecientes a esta empresa y 
            //se inicializa una variable temporal paquete_empresa
            $pa=new PaqueteEmpresa(array("id_empresa"=>$id_empresa));
            $paquetes_empresa=PaqueteEmpresaDAO::search($pa);
            $paquete_empresa=new PaqueteEmpresa();
            
            //Se buscan los servicios pertenecientes a esta empresa y 
            //se inicializa una variable temporal servicio_empresa
            $se=new ServicioEmpresa(array("id_empresa"=>$id_empresa));
            $servicios_empresa=ServicioEmpresaDAO::search($se);
            $servicio_empresa=new ServicioEmpresa();

            //Se buscan las sucursales pertenecientes a esta empresa y 
            //se inicializa una variable temporal sucursal_empresa
            $su=new SucursalEmpresa(array("id_empresa"=>$id_empresa));
            $sucursales_empresa=SucursalEmpresaDAO::search($su);
            $sucursal_empresa=new SucursalEmpresa();
            
            //Se buscan los almacenes de esta empresa distribuidos en las distintas sucursales
            $almacen=new Almacen(array("id_empresa"=>$id_empresa));

            DAO::transBegin();
            try
            {
                //Se actualiza la empresa
                EmpresaDAO::save($empresa);
                
                //Por cada uno de los productos en la empresa como producto,
                //se le asigna el id del producto a la variable temporal producto_empresa para
                //poder buscar las empresas en la que es ofrecido ese producto.
                //Si la busqueda regresa menos de dos campos, significa que solo esta empresa ofrece este producto,
                //por tanto, se tiene que desactivar el producto tambien.
                //
                //En cualquier caso, se eliminan tambien los registros de la tabla producto_empresa correspondiente
                //a todos los productos de la empresa.
                foreach($productos_empresa as $producto)
                {
                    $producto_empresa->setIdProducto($producto->getIdProducto());
                    $productos=ProductoEmpresaDAO::search($producto_empresa);
                    if(count($productos)<2)
                    {
                        ProductosController::Desactivar($producto->getIdProducto());
                    }
                    $pr->setIdProducto($producto->getIdProducto());
                    ProductoEmpresaDAO::delete($pr);
                }
                
                //Por cada uno de los paquetes en la empresa como paquete,
                //se le asigna el id del paquete a la variabe temporal paquete_empresa para
                //poder buscar las empresas en la que es ofrecido ese paquete.
                //Si la busqueda regresa menos de dos campos, significa que solo esta empresa ofrece este paquete,
                //por lo tanto, se tiene que desactivar el paquete tambien.
                //
                //En cualquier caso, se eliminan tambien los registros de la tabla paquete_empresa correspondiente
                //a todos los paquetes de la empresa
                foreach($paquetes_empresa as $paquete)
                {
                    $paquete_empresa->setIdPaquete($paquete->getIdPaquete());
                    $paquetes=PaqueteEmpresaDAO::search($paquete_empresa);
                    if(count($paquetes)<2)
                    {
                        PaquetesController::Eliminar($paquete->getIdPaquete());
                    }
                    $pa->setIdPaquete($paquete->getIdPaquete());
                    PaqueteEmpresaDAO::delete($pa);
                }
                
                //Por cada uno de los servicios en la empresa como paquete,
                //se le asigna el id del servicio a la variable temporal servicio_empresa para
                //poder buscar las empresas en la que es ofrecido ese servicio.
                //Si la busqueda regresa menos de dos campos, significa que solo esta empresa ofrece este servicio,
                //por lo tanto, se tiene que desactivar el servicio tambie.
                //
                //En cualquier caso, se eliminan tambien los registros de la tabla servicio_empresa correspondiente
                //a todos los servicios de la empresa.
                foreach($servicios_empresa as $servicio)
                {
                    $servicio_empresa->setIdServicio($servicio->getIdServicio());
                    $servicios=ServicioEmpresaDAO::search($servicio_empresa);
                    if(count($servicios)<2)
                    {
                        ServiciosController::Eliminar($servicio->getIdServicio());
                    }
                    $se->setIdServicio($servicio->getIdServicio());
                    ServicioEmpresaDAO::delete($se);
                }
                
                //Por cada una de las sucursales en la empresa como sucursal,
                //se le asigna el id de la sucursal a la variable temporal sucursal_empresa para
                //poder buscar las empresas que existen en la sucursal.
                //Si la busqueda regresa menos de dos campos, significa que solo existe esta empresa en esta sucursal,
                //por lo tanto, se tiene que desactivar la sucursal tambien.
                //
                //Si no, se buscan los almacenes de esta empresa y se eliminan aquellos que esten activos. 
                //Por ende, si un almacen de consignacion de esta empresa sigue activo significa
                //que aun no se termina la consignacion y por tal motivo no se puede eliminar la empresa
                //
                //En cualquier caso, se eliminan tambien los registros de la tabla sucursal_empresa correspondiente
                //a todas las sucursales de la empresa.
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
                            if($a->getActivo())
                            {
                                SucursalesController::EliminarAlmacen($a->getIdAlmacen());
                            }
                        }
                    }
                    SucursalEmpresaDAO::delete($sucursal);
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo eliminar la empresa: ".$e);
                throw "No se pudo eliminar la empresa";
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
            
            //Se validan los parametros de empresa recibidos
            $validar = self::validarParametrosEmpresa($id_empresa,null,$curp,$rfc,$razon_social,$representante_legal,null,$direccion_web,$margen_utilidad,$descuento);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            //Se validan los parametros de direccion recibidos
            $validar = DireccionController::validarParametrosDireccion(null,$calle,$numero_exterior,$numero_interno,$texto_extra,$colonia,$ciudad,$codigo_postal,$telefono1,$telefono2);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            //se guarda el registro de la empresa y se verifica que este activa
            $empresa=EmpresaDAO::getByPK($id_empresa);
            if(!$empresa->getActivo())
            {
                Logger::error("La empresa no esta activa, no se puede editar una empresa desactivada");
                throw new Exception("La empresa no esta activa, no se puede editar una empresa desactivada");
            }
            
            //se guarda el registro de la direccion perteneciente a esta empresa
            $direccion=DireccionDAO::getByPK($empresa->getIdDireccion());
            if(is_null($direccion))
            {
                Logger::error("FATAL!!! La empresa no cuenta con una direccion");
                throw new Exception("FATAL!!! La empresa no cuenta con una direccion");
            }
            
            //bandera para saber si se modifico algun campo de la direccion
            $modificar_direccion=false;
            
            //se evaluan los parametros. Los que no sean nulos seran tomados com oactualizacion
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
            
            //Si se cambio algun campo de la direccion se actualiza el campo ultima modificacion
            //y se toma al usuario de la sesion.
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
            
            DAO::transBegin();
            try
            {
                //Se guardan los cambios hechos en la empresa y en su direccion
                EmpresaDAO::save($empresa);
                DireccionDAO::save($direccion);
                
                //Si se obtiene el parametro impuestos se buscan los impuestos actuales de la empresa.
                //Por cada impuesto recibido, se verifica que el impuesto exista y se almacena en la tabla
                //impuesto_empresa. Si esta relacion ya existe solo se actualizara.
                //
                //Despues, se recorren los impuestos actuales y se buscan en la lista de impuestos recibidos.
                //Se eliminaran aquellos impuestos qe no esten en la lista recibida.
                if(!is_null($impuestos))
                {
                    $impuesto_empresa=new ImpuestoEmpresa(array("id_empresa"=>$id_empresa));
                    $impuestos_empresa=ImpuestoEmpresaDAO::search($impuesto_empresa);
                    
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
                            }
                        }
                        if(!$encontrado)
                        {
                            ImpuestoEmpresaDAO::delete($impuesto_empresa);
                        }
                    }
                }
                
                //Si se obtiene el parametro retneciones se buscan lretenciones actuales de la empresa.
                //Por cada retencion recibida, se verifica que la retencion exista y se almacena en la tabla
                //retencion_empresa. Si esta relacion ya existe solo se actualizara.
                //
                //Despues, se recorren las retenciones actuales y se buscan en la lista de retenciones recibidas.
                //Se eliminaran aquellas retenciones que no esten en la lista recibida.
                if(!is_null($retenciones))
                {
                    $retencion_empresa=new RetencionEmpresa(array("id_empresa"=>$id_empresa));
                    $retenciones_empresa=RetencionEmpresaDAO::search($retencion_empresa);
                    
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
                Logger::error("No se pudo modificar la empresa: ".$e);
                throw "No se pudo modificar la empresa";
            }
            DAO::transEnd();
            Logger::log("Empresa editada con exito");
	}
  }
