<?php
require_once("interfaces/Sucursales.interface.php");
/**
  *
  *
  *
  **/


	
  class SucursalesController implements ISucursales{

      //funcion para pruebas que obtiene el id de la caja de la sesion.
      private static function getCaja()
      {
          return 1;
      }

      //funcion para pruebas que obtiene el id de la sucursal de la sesion.
      private static function getSucursal()
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
        
        private static function validarParametrosSucursal
        (
        )
        {
            
        }
      
	/**
 	 *
 	 *Creara un nuevo almacen en una sucursal, este almacen contendra lotes.
 	 *
 	 * @param nombre string nombre del almacen
 	 * @param id_sucursal int El id de la sucursal a la que pertenecera este almacen.
 	 * @param id_empresa int Id de la empresa a la que pertenecen los productos de este almacen
 	 * @param id_tipo_almacen int Id del tipo de almacen 
 	 * @param descripcion string Descripcion extesa del almacen
 	 * @return id_almacen int el id recien generado
 	 **/
	public static function NuevoAlmacen
	(
		$nombre, 
		$id_sucursal, 
		$id_empresa, 
		$id_tipo_almacen, 
		$descripcion = null
	)
	{
            Logger::log("Creando nuevo almacen");
            if(is_null(SucursalDAO::getByPK($id_sucursal)))
            {
                Logger::error("La sucursal con id: ".$id_sucursal." no existe");
                throw new Exception("La sucursal con id: ".$id_sucursal." no existe");
            }
            if(is_null(EmpresaDAO::getByPK($id_empresa)))
            {
                Logger::error("La empresa con id: ".$id_empresa." no existe");
                throw new Exception("La empresa con id: ".$id_empresa." no existe");
            }
            if(is_null(TipoAlmacenDAO::getByPK($id_tipo_almacen)))
            {
                Logger::error("El tipo de almacen con id: ".$id_tipo_almacen." no existe");
                throw new Exception("El tipo de almacen con id: ".$id_tipo_almacen." no existe");
            }
            $almacen=new Almacen();
            $almacen->setNombre($nombre);
            $almacen->setDescripcion($descripcion);
            $almacen->setIdSucursal($id_sucursal);
            $almacen->setIdEmpresa($id_empresa);
            $almacen->setIdTipoAlmacen($id_tipo_almacen);
            $almacen->setActivo(1);
            DAO::transBegin();
            try
            {
                AlmacenDAO::save($almacen);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo crear el nuevo almacen");
                throw $e;
            }
            DAO::transEnd();
            Logger::log("Almacen creado exitosamente");
            return $almacen->getIdAlmacen();
	}
  
	/**
 	 *
 	 *listar almacenes de la isntancia. Se pueden filtrar por empresa, por sucursal, por tipo de almacen, por activos e inactivos y ordenar por sus atributos.
 	 *
 	 * @param id_empresa int Id de la empresa de la cual se listaran sus almacenes
 	 * @param id_sucursal int el id de la sucursal de la cual se listaran sus almacenes
 	 * @param id_tipo_almacen int Se listaran los almacenes de este tipo
 	 * @param activo bool Si este valor no es obtenido, se mostraran almacenes tanto activos como inactivos. Si es verdadero, solo se lsitaran los activos, si es falso solo se lsitaran los inactivos.
 	 * @return almacenes json Almacenes de esta sucursal
 	 **/
	public static function ListaAlmacen
	(
		$id_empresa = null, 
		$id_sucursal = null, 
		$id_tipo_almacen = null, 
		$activo = null
	)
	{
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
            if($parametros)
            {
                Logger::log("Se recibieron parametros, se listan las almacenes en rango");
                $almacen_criterio_1=new Almacen();
                $almacen_criterio_2=new Almacen();
                $almacen_criterio_1->setActivo($activo);
                $almacen_criterio_1->setIdEmpresa($id_empresa);
                $almacen_criterio_1->setIdSucursal($id_sucursal);
                $almacen_criterio_1->setIdTipoAlmacen($id_tipo_almacen);
                $almacenes=AlmacenDAO::byRange($almacen_criterio_1, $almacen_criterio_2);
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
 	 *Vender productos desde el mostrador de una sucursal. Cualquier producto vendido aqui sera descontado del inventario de esta sucursal. La fecha ser?omada del servidor, el usuario y la sucursal ser?tomados del servidor. La ip ser?omada de la m?ina que manda a llamar al m?do. El valor del campo liquidada depender?e los campos total y pagado. La empresa se tomara del alamcen de donde salieron los productos
 	 *
 	 * @param detalle json Objeto que contendr los id de los productos, sus cantidades, su precio y su descuento.
 	 * @param retencion float Cantidad sumada por retenciones
 	 * @param id_comprador int Id del cliente al que se le vende.
 	 * @param subtotal float El total de la venta antes de cargarle impuestos
 	 * @param impuesto float Cantidad sumada por impuestos
 	 * @param total float El total de la venta
 	 * @param descuento float La cantidad que ser descontada a la compra
 	 * @param tipo_venta string Si la venta es a credito o a contado
 	 * @param saldo float La cantidad que ha sido abonada hasta el momento de la venta
 	 * @param cheques json Si el tipo de pago es con cheque, se almacena el nombre del banco, el monto y los ultimos 4 numeros del o de los cheques
 	 * @param tipo_pago string Si el pago ser efectivo, cheque o tarjeta.
 	 * @param billetes_pago json Ids de los billetes que se recibieron 
 	 * @param billetes_cambio json Ids de billetes que se entregaron como cambio
 	 * @return id_venta int Id autogenerado de la inserci�n de la venta.
 	 **/
	public static function VenderCaja
	( 
		$retencion, 
		$id_comprador, 
		$subtotal, 
		$impuesto, 
		$total, 
		$descuento, 
		$tipo_venta, 
		$saldo = null, 
		$cheques = null, 
		$tipo_pago = null, 
		$billetes_pago = null, 
		$billetes_cambio = null,
                $id_venta_caja = null,
                $detalle_producto = null,
                $detalle_orden = null,
                $detalle_paquete = null
	)
	{
            Logger::log("Realizando la venta");
            $id_usuario=LoginController::getCurrentUser();
            if(is_null($id_usuario))
            {
                Logger::error("No se pudo obtener al usuario de la sesion actual, ya inicio sesion?");
                throw new Exception("No se pudo obtener al usuario de la sesion actual, ya inicio sesion?");
            }
            $usuario=UsuarioDAO::getByPK($id_comprador);
            if(is_null($usuario))
            {
                Logger::error("El usuario recibido como comprador no existe");
                throw new Exception("El usuario recibido como comprador no existe");
            }
            $venta=new Venta();
            $venta->setRetencion($retencion);
            $venta->setIdCompradorVenta($id_comprador);
            $venta->setSubtotal($subtotal);
            $venta->setImpuesto($impuesto);
            $venta->setTotal($total);
            $venta->setDescuento($descuento);
            $venta->setTipoDeVenta($tipo_venta);
            $venta->setIdCaja(self::getCaja());
            $venta->setIdSucursal(self::getSucursal());
            $venta->setIdUsuario($id_usuario);
            $venta->setIdVentaCaja($id_venta_caja);
            $venta->setCancelada(0);
            $venta->setTipoDePago($tipo_pago);
            $venta->setFecha(date("Y-m-d H:i:s",time()));
            DAO::transBegin();
            try
            {
                if($tipo_venta==="contado")
                {
                    if($tipo_pago==="cheque"&&is_null($cheques))
                    {
                        throw new Exception("El tipo de pago es con cheque pero no se recibio informacion del mismo");
                    }
                    if(!is_null($saldo))
                    {
                        Logger::warn("Se recibio un saldo cuando la venta es de contado, el saldo se tomara del total");
                    }
                    $venta->setSaldo($total);
                    VentaDAO::save($venta);
                    if($tipo_pago==="cheque")
                    {
                        $cheque_venta = new ChequeVenta();
                        $cheque_venta->setIdVenta($venta->getIdVenta());
                        foreach($cheques as $cheque)
                        {
                            $id_cheque=ChequesController::NuevoCheque($cheque["nombre_banco"], $cheque["monto"], $cheque["numero"], 0);
                            $cheque_venta->setIdCheque($id_cheque);
                            ChequeVentaDAO::save($cheque_venta);
                        }
                    }
                    else if($tipo_pago==="efectivo")
                    {
                        CajasController::modificarCaja($venta->getIdCaja(), 1, $billetes_pago, $total);
                        if(!is_null($billetes_cambio))
                        {
                            CajasController::modificarCaja($venta->getIdCaja(), 0, $billetes_cambio, 0);
                        }
                    }
                    else 
                    {
                        throw new Exception("Se recibio un tipo de pago de: ".$tipo_pago." para esta venta de contado");
                    }
                }
                else if($tipo_venta=="credito")
                {
                    if(!is_null($saldo))
                    {
                        Logger::warn("No se recibio un saldo, se tomara 0 como saldo");
                        $saldo=0;
                    }
                    $venta->setSaldo($saldo);
                    VentaDAO::save($venta);
                    $usuario->setSaldoDelEjercicio($usuario->getSaldoDelEjercicio()-$total+$saldo);
                    $usuario->setVentasACredito($usuario->getVentasACredito()+1);
                    UsuarioDAO::save($usuario);
                }
                else
                {
                    throw new Exception("El tipo de venta recibido no es valido");
                }
                if(is_null($detalle_orden)&&is_null($detalle_paquete)&&is_null($detalle_producto))
                {
                    throw new Exception ("No se recibieron ni paquetes ni productos ni servicios para esta venta");
                }
                if(!is_null($detalle_paquete))
                {
                    $d_paquete=new VentaPaquete();
                    $d_paquete->setIdVenta($venta->getIdVenta());
                    foreach($detalle_paquete as $d_p)
                    {
                        if(is_null(PaqueteDAO::getByPK($d_p["id_paquete"])))
                        {
                            throw new Exception("El paquete con id: ".$d_p["id_paquete"]." no existe");
                        }
                        $d_paquete->setCantidad($d_p["cantidad"]);
                        $d_paquete->setDescuento($d_p["descuento"]);
                        $d_paquete->setIdPaquete($d_p["id_paquete"]);
                        $d_paquete->setPrecio($d_p["precio"]);
                        VentaPaqueteDAO::save($d_paquete);
                    }
                }
                if(!is_null($detalle_producto))
                {
                    $d_producto=new VentaProducto();
                    $d_producto->setIdVenta($venta->getIdVenta());
                    foreach($detalle_producto as $d_p)
                    {
                        $producto=ProductoDAO::getByPK($d_p["id_producto"]);
                        if(is_null($producto))
                        {
                            throw new Exception("El producto con id: ".$d_p["id_producto"]." no existe");
                        }
                        if(is_null(UnidadDAO::getByPk($d_p["id_unidad"])))
                        {
                            throw new Exception("La unidad con id: ".$d_p["id_unidad"]." no existe");
                        }
                        
                        if(!$producto->getCompraEnMostrador())
                        {
                            throw new Exception("No se puede vender el producto con id ".$d_p["id_producto"]." en mostrador");
                        }
                        $d_producto->setCantidad($d_p["cantidad"]);
                        $d_producto->setDescuento($d_p["descuento"]);
                        $d_producto->setIdProducto($d_p["id_producto"]);
                        $d_producto->setIdUnidad($d_p["id_unidad"]);
                        $d_producto->setImpuesto($d_p["impuesto"]);
                        $d_producto->setPrecio($d_p["precio"]);
                        $d_producto->setRetencion($d_p["retencion"]);
                        VentaProductoDAO::save($d_producto);
                        self::DescontarDeAlmacenes($d_producto, self::getSucursal());
                    }
                }
                if(!is_null($detalle_orden))
                {
                    $d_orden = new VentaOrden();
                    $d_orden->setIdVenta($venta->getIdVenta());
                    foreach($detalle_orden as $d_p)
                    {
                        if(is_null(OrdenDeServicioDAO::getByPK($d_p["id_orden_de_servicio"])))
                        {
                            throw new Exception("La orden de servicio con id: ".$d_p["id_orden_de_servicio"]." no existe");
                        }
                        $d_orden->setDescuento($d_p["descuento"]);
                        $d_orden->setIdOrdenDeServicio($d_p["id_orden_de_servicio"]);
                        $d_orden->setImpuesto($d_p["impuesto"]);
                        $d_orden->setPrecio($d_p["precio"]);
                        $d_orden->setRetencion($d_p["retencion"]);
                        VentaOrdenDAO::save($d_orden);
                    }
                }
                $id_empresas=self::ObtenerEmpresasParaAsignacionVenta($detalle_producto, $detalle_paquete, $detalle_orden);
                $venta_empresa=new VentaEmpresa(array("id_venta" => $venta->getIdVenta()));
                $n=count($id_empresas["id"]);
                for($i = 0 ; $i < $n ; $i++)
                {
                    $venta_empresa->setIdEmpresa($id_empresas["id"][$i]);
                    $venta_empresa->setTotal($id_empresas["total"][$i]);
                    if($tipo_venta==="contado")
                        $venta_empresa->setSaldada(1);
                    else
                        $venta_empresa->setSaldada(0);
                    VentaEmpresaDAO::save($venta_empresa);
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo realizar la venta: ".$e);
                throw $e;
            }
            DAO::transEnd();
            Logger::log("venta realizada exitosamente");
            return $venta->getIdVenta();
	}

        private static function ObtenerEmpresasParaAsignacionVenta
        (
                $detalle_producto=null,
                $detalle_paquete=null,
                $detalle_orden=null
        )
        {
            $empresas=array();
            $id_empresas=array( "id" => array(), "total" => array());
            $parametro=false;
            if(!is_null($detalle_producto))
            {
                $parametro=true;
                $producto_empresa=new ProductoEmpresa();
                foreach($detalle_producto as $d_p)
                {
                    $producto_empresa->setIdProducto($d_p["id_producto"]);
                    $productos_empresa=ProductoEmpresaDAO::search($producto_empresa);
                    foreach($productos_empresa as $p_e)
                    {
                        self::InsertarIdEmpresa($p_e, $id_empresas, $d_p["precio"]*$d_p["cantidad"]);
                    }
                }
            }
            if(!is_null($detalle_paquete))
            {
                $parametro=true;
                $paquete_empresa=new PaqueteEmpresa();
                foreach($detalle_paquete as $d_p)
                {
                    $paquete_empresa->setIdPaquete($d_p["id_paquete"]);
                    $paquetes_empresa=PaqueteEmpresaDAO::search($paquete_empresa);
                    foreach($paquetes_empresa as $p_e)
                    {
                        self::InsertarIdEmpresa($p_e, $id_empresas, $d_p["precio"]*$d_p["cantidad"]);
                    }
                }
            }
            if(!is_null($detalle_orden))
            {
                $parametro=true;
                $servicio_empresa=new ServicioEmpresa();
                foreach($detalle_orden as $orden)
                {
                    $orden_de_servicio=OrdenDeServicioDAO::getByPK($orden["id_orden_de_servicio"]);
                    $servicio_empresa->setIdServicio($orden_de_servicio->getIdServicio());
                    $servicios_empresa=ServicioEmpresaDAO::search($servicio_empresa);
                    foreach($servicios_empresa as $s_e)
                    {
                        self::InsertarIdEmpresa($s_e, $id_empresas, $orden["precio"]);
                    }
                }
            }
            if(!$parametro)
            {
                throw new Exception("No se recibio un id_producto ni un id_paquete ni un id_orden");
            }
            
            foreach($empresas as $empresa)
            {
                foreach($empresa as $objeto)
                {
                    self::InsertarIdEmpresa($objeto, $id_empresas);
                }
            }
            return $id_empresas;
        }

        private static function InsertarIdEmpresa
        (
                $objeto,
                &$id_empresas,
                $precio
        )
        {
            $repetido=false;
            $n=count($id_empresas["id"]);
            for($i = 0; $i < $n; $i++)
            {
                if($id_empresas["id"][$i] == $objeto->getIdEmpresa())
                {
                    $repetido=true;
                    break;
                }
            }
            if(!$repetido)
            {
                array_push($id_empresas["id"],$objeto->getIdEmpresa());
                array_push($id_empresas["total"],$precio);
            }
            else if($i!=$n)
            {
                $id_empresas["total"][$i]+=$precio;
            }
        }

        private static function DescontarDeAlmacenes
        (
                VentaProducto $detalle_producto,
                $id_sucursal
        )
        {
            $almacenes=AlmacenDAO::search(new Almacen(array("id_sucursal" => $id_sucursal)));
            $productos_almacen=array();
            $total=0;
            foreach($almacenes as $almacen)
            {
                if($almacen->getIdTipoAlmacen()==2)
                    continue;
                $producto_almacen=ProductoAlmacenDAO::getByPK($detalle_producto->getIdProducto(), $almacen->getIdAlmacen(), $detalle_producto->getIdUnidad());
                if(!is_null($producto_almacen))
                {
                    if($producto_almacen->getCantidad()>0)
                        $total+=$producto_almacen->getCantidad();
                    array_push($productos_almacen,$producto_almacen);
                }
            }
            if(empty ($productos_almacen))
            {
                throw new Exception("No se encontro el producto en los almacenes de esta sucursal");
            }
            $n=$detalle_producto->getCantidad();
            $n_almacenes=count($productos_almacen);
            $unidad=UnidadDAO::getByPK($detalle_producto->getIdUnidad());
            if(is_null($unidad))
            {
                throw new Exception("FATAL!!! este producto no tiene unidad");
            }
            if($n>=$total)
            {
                $n-=$total;
                if($unidad->getEsEntero())
                {
                    $mod=$n%$n_almacenes;
                    $cantidad=floor($n/$n_almacenes);
                }
                else
                {
                    $mod=0;
                    $cantidad=$n/$n_almacenes;
                }
                DAO::transBegin();
                try
                {
                    foreach($productos_almacen as $p)
                    {
                        $temp=$cantidad;
                        if($mod>0)
                        {
                            $temp++;
                            $mod--;
                        }
                        $p->setCantidad(0-$temp);
                        ProductoAlmacenDAO::save($p);
                    }
                }
                catch(Exception $e)
                {
                    DAO::transRollback();
                    Logger::error("No se pudo actualizar la cantidad de producto en almacen");
                    throw $e;
                }
                DAO::transEnd();
            }
            else
            {
                $productos_almacen=self::OrdenarProductosAlmacen($productos_almacen);
                $diferencia=array();
                for($i=0;$i<$n_almacenes-1;$i++)
                {
                    $diferencia[$i]=$productos_almacen[$i]->getCantidad()-$productos_almacen[$i+1]->getCantidad();
                }
                $diferencia[$i]=$productos_almacen[$i]->getCantidad();
                for($i=0;$n>0&&$i<$n_almacenes;$i++)
                {
                    if($n/($i+1)<=$diferencia[$i]*($i+1))
                    {
                        if($unidad->getEsEntero())
                        {
                            $mod=$n%($i+1);
                            $cantidad=floor($n/($i+1));
                        }
                        else
                        {
                            $mod=0;
                            $cantidad=$n/($i+1);
                        }
                        DAO::transBegin();
                        try
                        {
                            for($j=$i;$j>=0;$j--)
                            {
                                $temp=$cantidad;
                                if($mod>0)
                                {
                                    $temp++;
                                    $mod--;
                                }
                                $productos_almacen[$j]->setCantidad($productos_almacen[$j]->getCantidad()-$temp);
                                ProductoAlmacenDAO::save($productos_almacen[$j]);
                                
                            }
                        }
                        catch(Exception $e)
                        {
                            DAO::transRollback();
                            Logger::error("No se pudo actualizar la cantidad de producto en almacen");
                            throw $e;
                        }
                        DAO::transEnd();
                    }
                    else
                    {
                        for($j=$i;$j>=0;$j--)
                        {
                            $productos_almacen[$j]->setCantidad($productos_almacen[$j]->getCantidad()-$diferencia[$i]);
                        }
                    }
                    $n-=$diferencia[$i]*($i+1);
                }
            }

        }

        public static function OrdenarProductosAlmacen
        (
                $productos_almacen
        )
        {
            $cantidades=array();
            $productos_almacen_ordenado=array();
            foreach($productos_almacen as $p)
            {
                $productos_almacen_ordenado[]=$p;
                $cantidades[]=$p->getCantidad();
            }
            array_multisort($cantidades,SORT_DESC,$productos_almacen_ordenado);
            return $productos_almacen_ordenado;
        }


	/**
 	 *
 	 *Comprar productos en mostrador. No debe confundirse con comprar productos a un proveedor. Estos productos se agregaran al inventario de esta sucursal de manera automatica e instantanea. La IP ser?omada de la m?ina que realiza la compra. El usuario y la sucursal ser?tomados de la sesion activa. El estado del campo liquidada ser?omado de acuerdo al campo total y pagado.
 	 *
 	 * @param retencion float Cantidad sumada por retenciones
 	 * @param detalle json Objeto que contendr la informacin de los productos comprados, sus cantidades, sus descuentos, y sus precios
 	 * @param id_vendedor int Id del cliente al que se le compra
 	 * @param total float Total de la compra despues de impuestos y descuentos
 	 * @param tipo_compra string Si la compra es a credito o de contado
 	 * @param subtotal float Total de la compra antes de incluirle impuestos.
 	 * @param id_empresa int Empresa a nombre de la cual se realiza la compra
 	 * @param descuento float Cantidad restada por descuento
 	 * @param impuesto float Cantidad sumada por impuestos
 	 * @param billetes_pago json Ids de billetes que se usaron para pagar
 	 * @param billetes_cambio json Ids de billetes que se recibieron como cambio
 	 * @param tipo_pago string Si el pago ser en efectivo, con tarjeta o con cheque
 	 * @param saldo float Saldo de la compra
 	 * @param cheques json Si el tipo de pago es con cheque, se almacena el nombre del banco, el monto y los ultimos 4 numeros del o de los cheques
 	 * @return id_compra_cliente string Id de la nueva compra
 	 **/
	public static function ComprarCaja
	(
		$retencion, 
		$detalle, 
		$id_vendedor, 
		$total, 
		$tipo_compra, 
		$subtotal, 
		$id_empresa, 
		$descuento, 
		$impuesto, 
		$billetes_pago = null, 
		$billetes_cambio = null, 
		$tipo_pago = null, 
		$saldo = null, 
		$cheques = null,
                $id_compra_caja = null
	)
	{  
            Logger::log("Realizando la compra");
            $id_usuario=LoginController::getCurrentUser();
            if(is_null($id_usuario))
            {
                Logger::error("No se pudo obtener al usuario de la sesion actual, ya inicio sesion?");
                throw new Exception("No se pudo obtener al usuario de la sesion actual, ya inicio sesion?");
            }
            $usuario=UsuarioDAO::getByPK($id_vendedor);
            if(is_null($usuario))
            {
                Logger::error("El usuario recibido como vendedor no existe");
                throw new Exception("El usuario recibido como vendedor no existe");
            }
            $compra=new Compra();
            $compra->setRetencion($retencion);
            $compra->setIdVendedorCompra($id_vendedor);
            $compra->setSubtotal($subtotal);
            $compra->setImpuesto($impuesto);
            $compra->setTotal($total);
            $compra->setDescuento($descuento);
            $compra->setTipoDeCompra($tipo_compra);
            $compra->setIdCaja(self::getCaja());
            $compra->setIdSucursal(self::getSucursal());
            $compra->setIdUsuario($id_usuario);
            $compra->setIdCompraCaja($id_compra_caja);
            $compra->setCancelada(0);
            $compra->setTipoDePago($tipo_pago);
            $compra->setFecha(date("Y-m-d H:i:s",time()));
            $compra->setIdEmpresa($id_empresa);
            DAO::transBegin();
            try
            {
                if($tipo_compra==="contado")
                {
                    if($tipo_pago==="cheque"&&is_null($cheques))
                    {
                        throw new Exception("El tipo de pago es con cheque pero no se recibio informacion del mismo");
                    }
                    if(!is_null($saldo))
                    {
                        Logger::warn("Se recibio un saldo cuando la venta es de contado, el saldo se tomara del total");
                    }
                    $compra->setSaldo($total);
                    CompraDAO::save($compra);
                    if($tipo_pago==="cheque")
                    {
                        $cheque_compra = new ChequeCompra();
                        $cheque_compra->setIdCompra($compra->getIdCompra());
                        foreach($cheques as $cheque)
                        {
                            $id_cheque=ChequesController::NuevoCheque($cheque["nombre_banco"], $cheque["monto"], $cheque["numero"], 1);
                            $cheque_compra->setIdCheque($id_cheque);
                            ChequeCompraDAO::save($cheque_compra);
                        }
                    }
                    else if($tipo_pago==="efectivo")
                    {
                        CajasController::modificarCaja($compra->getIdCaja(), 0, $billetes_pago, $total);
                        if(!is_null($billetes_cambio))
                        {
                            CajasController::modificarCaja($compra->getIdCaja(), 1, $billetes_cambio, 0);
                        }
                    }
                    else
                    {
                        throw new Exception("Se recibio un tipo de pago de: ".$tipo_pago." para esta compra de contado");
                    }
                }
                else if($tipo_compra=="credito")
                {
                    if(is_null($saldo))
                    {
                        Logger::warn("No se recibio un saldo, se tomara 0 como saldo");
                        $saldo=0;
                    }
                    $compra->setSaldo($saldo);
                    CompraDAO::save($compra);
                    $usuario->setSaldoDelEjercicio($usuario->getSaldoDelEjercicio()+$total-$saldo);
                    UsuarioDAO::save($usuario);
                }
                else
                {
                    throw new Exception("El tipo de compra recibido no es valido");
                }
                if(!is_null($detalle))
                {
                    $d_producto=new CompraProducto();
                    $d_producto->setIdCompra($compra->getIdCompra());
                    $almacenes=AlmacenDAO::search(new Almacen(array("id_sucursal" => self::getSucursal(), "id_empresa" => $id_empresa)));
                    $id_almacen=null;
                    foreach($almacenes as $a)
                    {
                        if($a->getIdTipoAlmacen()==2)
                                continue;
                        $id_almacen=$a->getIdAlmacen();
                    }
                    if(is_null($id_almacen))
                    {
                        throw new Exception("No existe un almacen para esta empresa en esta sucursal");
                    }
                    foreach($detalle as $d_p)
                    {
                        $producto=ProductoDAO::getByPK($d_p["id_producto"]);
                        if(is_null($producto))
                        {
                            throw new Exception("El producto con id: ".$d_p["id_producto"]." no existe");
                        }
                        if(is_null(UnidadDAO::getByPk($d_p["id_unidad"])))
                        {
                            throw new Exception("La unidad con id: ".$d_p["id_unidad"]." no existe");
                        }
                        if(!$producto->getCompraEnMostrador())
                        {
                            throw new Exception("No se puede comprar el producto con id ".$d_p["id_producto"]." en mostrador");
                        }
                        if(is_null(ProductoEmpresaDAO::getByPK($d_p["id_producto"], $id_empresa)))
                        {
                            throw new Exception("El producto no pertenece a la empresa seleccionada");
                        }
                        $d_producto->setCantidad($d_p["cantidad"]);
                        $d_producto->setDescuento($d_p["descuento"]);
                        $d_producto->setIdProducto($d_p["id_producto"]);
                        $d_producto->setIdUnidad($d_p["id_unidad"]);
                        $d_producto->setImpuesto($d_p["impuesto"]);
                        $d_producto->setPrecio($d_p["precio"]);
                        $d_producto->setRetencion($d_p["retencion"]);
                        CompraProductoDAO::save($d_producto);
                        $producto_almacen=ProductoAlmacenDAO::getByPK($d_p["id_producto"], $id_almacen, $d_p["id_unidad"]);
                        if(is_null($producto_almacen))
                        {
                            $producto_almacen=new ProductoAlmacen(array("id_producto" => $d_p["id_producto"], "id_almacen" => $id_almacen, "id_unidad" => $d_p["id_unidad"]));
                        }
                        $producto_almacen->setCantidad($producto_almacen->getCantidad() + $d_p["cantidad"]);
                        ProductoAlmacenDAO::save($producto_almacen);
                    }
                }
                else
                {
                    Logger::error("No se recibieron productos para esta compra");
                    throw new Exception ("No se recibieron productos para esta compra");
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo realizar la compra: ".$e);
                throw $e;
            }
            DAO::transEnd();
            Logger::log("compra realizada exitosamente");
            return $compra->getIdCompra();
	}
  
	/**
 	 *
 	 *Lista las sucursales relacionadas con esta instancia. Se puede filtrar por empresa,  saldo inferior o superior a, fecha de apertura, ordenar por fecha de apertura u ordenar por saldo. Se agregar?n link en cada una para poder acceder a su detalle.
 	 *
 	 * @param activo bool Si este valor no es pasado, se listaran sucursales tanto activas como inactivas, si su valor es true, solo se mostrarn las sucursales activas, si es false, solo se mostraran las sucursales inactivas.
 	 * @param id_empresa int Id de la empresa de la cual se listaran sus sucursales.
 	 * @param saldo_inferior_que float Si este valor es obtenido, se mostrar�n las sucursales que tengan un saldo inferior a este
 	 * @param saldo_igual_que float Si este valor es obtenido, se mostrar�n las sucursales que tengan un saldo igual a este
 	 * @param saldo_superior_que float Si este valor es obtenido, se mostrar�n las sucursales que tengan un saldo superior a este
 	 * @param fecha_apertura_inferior_que string Si este valor es pasado, se mostraran las sucursales cuya fecha de apertura sea inferior a esta.
 	 * @param fecha_apertura_igual_que string Si este valor es pasado, se mostraran las sucursales cuya fecha de apertura sea igual a esta.
 	 * @param fecha_apertura_superior_que string Si este valor es pasado, se mostraran las sucursales cuya fecha de apertura sea superior a esta.
 	 * @return sucursales json Objeto que contendra la lista de sucursales.
 	 **/
	public static function Lista
	(
		$activo = null,
		$id_empresa = null, 
		$saldo_inferior_que = null, 
		$saldo_superior_que = null, 
		$fecha_apertura_inferior_que = null, 
		$fecha_apertura_superior_que = null
	)
	{
            Logger::log("Listando sucursales");
            $parametros=false;
            if
            (
                    !is_null($activo) ||
                    !is_null($id_empresa) ||
                    !is_null($saldo_inferior_que) ||
                    !is_null($saldo_superior_que) ||
                    !is_null($fecha_apertura_inferior_que) ||
                    !is_null($fecha_apertura_superior_que)
            )
                $parametros=true;
            $sucursales=array();
            $sucursales1=array();
            if($parametros)
            {
                Logger::log("se recibieron parametros, se listan las sucursales en rango");
                if(!is_null($id_empresa))
                {
                    $sucursales_empresa=SucursalEmpresaDAO::search(new SucursalEmpresa(array( "id_empresa" => $id_empresa )));
                    foreach($sucursales_empresa as $sucursal_empresa)
                    {
                        array_push($sucursales1,SucursalDAO::getByPK($sucursal_empresa->getIdSucursal()));
                    }
                }
                else
                {
                    $sucursales1=SucursalDAO::getAll();
                }
                $sucursal_criterio1=new Sucursal();
                $sucursal_criterio2=new Sucursal();

                $sucursal_criterio1->setActiva($activo);
                if(!is_null($saldo_superior_que))
                {
                    $sucursal_criterio1->setSaldoAFavor($saldo_superior_que);
                    if(!is_null($saldo_inferior_que))
                        $sucursal_criterio2->setSaldoAFavor($saldo_inferior_que);
                    else
                        $sucursal_criterio2->setSaldoAFavor(1.8e100);
                }
                else if(!is_null($saldo_inferior_que))
                {
                    $sucursal_criterio1->setSaldoAFavor($saldo_inferior_que);
                    $sucursal_criterio2->setSaldoAFavor(0);
                }
                if(!is_null($fecha_apertura_superior_que))
                {
                    $sucursal_criterio1->setFechaApertura($fecha_apertura_superior_que);
                    if(!is_null($fecha_apertura_inferior_que))
                        $sucursal_criterio2->setFechaApertura($fecha_apertura_inferior_que);
                    else
                        $sucursal_criterio2->setFechaApertura(date("Y-m-d H:i:s",time()));
                }
                else if(!is_null($fecha_apertura_inferior_que))
                {
                    $sucursal_criterio1->setFechaApertura($fecha_apertura_inferior_que);
                    $sucursal_criterio2->setFechaApertura("1001-01-01 00:00:00");
                }
                $sucursales2=SucursalDAO::byRange($sucursal_criterio1, $sucursal_criterio2);
                $sucursales=array_intersect($sucursales1, $sucursales2);
            }
            else
            {
                Logger::log("No se recibieron parametros, se listan todas las sucursales");
                $sucursales=SucursalDAO::getAll();
            }
            Logger::log("Sucursales obtenidos con exitos");
            return $sucursales;
	}
  
	/**
 	 *
 	 *Valida si una maquina que realizara peticiones al servidor pertenece a una sucursal.
 	 *
 	 * @param billetes json Ids de billetes y sus cantidades con los que inicia esta caja
 	 * @param saldo float Saldo con el que empieza a funcionar la caja
 	 * @param client_token string El token generado por el POS client
 	 * @param control_billetes bool Si se quiere llevar el control de billetes en la caja
 	 * @param id_cajero int Id del cajero que iniciara en esta caja en caso de que no sea este el que abre la caja
 	 * @return detalles_sucursal json Si esta es una sucursal valida, detalles sucursal contiene un objeto con informacion sobre esta sucursal.
 	 **/
	public static function AbrirCaja
	(
                $id_caja,
		$billetes, 
		$saldo, 
		$client_token, 
		$control_billetes, 
		$id_cajero = null
	)
	{
            Logger::log("Abriendo caja");
            $caja=CajaDAO::getByPK($id_caja);
            if(is_null($caja))
            {
                Logger::error("La caja con id: ".$id_caja." no existe");
                throw new Exception("La caja con id: ".$id_caja." no existe");
            }
            if(!$caja->getActiva())
            {
                Logger::error("La caja no esta activa y no puede ser abierta");
                throw new Exception("La caja no esta activa y no puede ser abierta");
            }
            if($caja->getAbierta())
            {
                Logger::warn("La caja ya ha sido abierta");
                throw new Exception("La caja ya ha sido abierta");
            }
            $apertura_caja=new AperturaCaja();
            $apertura_caja->setIdCaja($id_caja);
            $apertura_caja->setFecha(date("Y-m-d H:i:s",time()));
            $apertura_caja->setIdCajero($id_cajero);
            $apertura_caja->setSaldo($saldo);
            $caja->setAbierta(1);
            $caja->setSaldo($saldo);
            $caja->setControlBilletes($control_billetes);
            DAO::transBegin();
            try
            {
                CajasController::modificarCaja($id_caja, 1, $billetes, 0);
                AperturaCajaDAO::save($apertura_caja);
                CajaDAO::save($caja);
                if($control_billetes)
                {
                    $billetes_caja=BilleteCajaDAO::search(new BilleteCaja(array( "id_caja" => $id_caja)));
                    foreach($billetes_caja as $b_c)
                    {
                        $b_c->setCantidad(0);
                        BilleteCajaDAO::save($b_c);
                    }
                    $billete_apertura_caja=new BilleteAperturaCaja(array( "id_apertura_caja" => $apertura_caja->getIdAperturaCaja()));
                    $billete_caja=new BilleteCaja(array( "id_caja" => $id_caja ));
                    foreach($billetes as $billete)
                    {
                        $billete_apertura_caja->setIdBillete($billete["id_billete"]);
                        $billete_caja->setIdBillete($billete["id_billete"]);
                        $billete_apertura_caja->setCantidad($billete["cantidad"]);
                        $billete_caja->setCantidad($billete["cantidad"]);
                        BilleteAperturaCajaDAO::save($billete_apertura_caja);
                        BilleteCajaDAO::save($billete_caja);
                    }
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo abrir la caja: ".$e);
                throw $e;
            }
            DAO::transEnd();
            Logger::log("Caja abierta exitosamente");
            return $apertura_caja->getIdAperturaCaja();
	}
  
	/**
 	 *
 	 *Metodo que crea una nueva sucursal
 	 *
 	 * @param codigo_postal string Codigo postal de la empresa
 	 * @param rfc string RFC de la sucursal
 	 * @param activo bool Si esta sucursal estara activa inmediatamente despues de ser creada
 	 * @param colonia string Colonia de la sucursal
 	 * @param razon_social string Razon social de la sucursal
 	 * @param calle string Calle de la sucursal
 	 * @param empresas json Objeto que contendra los ids de las empresas a las que esta sucursal pertenece, por lo menos tiene que haber una empresa. En este JSON, opcionalmente junto con el id de la empresa, aapreceran dos campos que seran margen_utilidad y descuento, que indicaran que todos los productos de esa empresa ofrecidos en esta sucursal tendran un margen de utilidad y/o un descuento con los valores en esos campos
 	 * @param numero_exterior string Numero exterior de la sucursal
 	 * @param id_ciudad int Id de la ciudad donde se encuentra la sucursal
 	 * @param saldo_a_favor float Saldo a favor de la sucursal.
 	 * @param id_gerente int ID del usuario que sera gerente de esta sucursal. Para que sea valido este usuario debe tener el nivel de acceso apropiado.
 	 * @param margen_utilidad float Margen de utilidad que se le ganara a todos los productos ofrecidos por esta sucursal
 	 * @param numero_interior string numero interior
 	 * @param telefono2 string Telefono2 de la sucursal
 	 * @param telefono1 string Telefono1 de la sucursal
 	 * @param descripcion string Descripcion de la sucursal
 	 * @param impuestos json Objeto que contendra el arreglo de impuestos que afectan a esta sucursal
 	 * @param descuento float Descuento que tendran todos los productos ofrecidos por esta sucursal
 	 * @return id_sucursal int Id autogenerado de la sucursal que se creo.
 	 **/
	public static function Nueva
	(
		$codigo_postal, 
		$rfc, 
		$activo, 
		$colonia, 
		$razon_social, 
		$calle, 
		$empresas, 
		$numero_exterior, 
		$id_ciudad, 
		$saldo_a_favor, 
		$id_gerente = null, 
		$margen_utilidad = null, 
		$numero_interior = null, 
		$telefono2 = null, 
		$telefono1 = null, 
		$descripcion = null, 
		$impuestos = null, 
		$descuento = null,
                $retenciones = null,
                $referencia = null
	)
	{
            Logger::log("Creando nueva sucursal");
            $sucursal=new Sucursal();
            $sucursal->setRfc($rfc);
            $sucursal->setActiva($activo);
            $sucursal->setRazonSocial($razon_social);
            if(is_null(CiudadDAO::getByPK($id_ciudad)))
            {
                Logger::error("La ciudad con id: ".$id_ciudad." no existe");
                throw new Exception("La ciudad con id: ".$id_ciudad." no existe");
            }
            $sucursal->setSaldoAFavor($saldo_a_favor);
            $sucursal->setIdGerente($id_gerente);
            $sucursal->setMargenUtilidad($margen_utilidad);
            $sucursal->setDescripcion($descripcion);
            $sucursal->setDescuento($descuento);
            $sucursal->setFechaApertura("Y-m-d H:i:s",time());
            DAO::transBegin();
            try
            {
                $id_direccion=DireccionController::NuevaDireccion($calle,$numero_exterior,$colonia,$id_ciudad,$codigo_postal,$numero_interior,$referencia,$telefono1,$telefono2);
                $sucursal->setIdDireccion($id_direccion);
                SucursalDAO::save($sucursal);
                $sucursal_empresa = new SucursalEmpresa();
                $sucursal_empresa->setIdSucursal($sucursal->getIdSucursal());
                foreach($empresas as $empresa)
                {
                    $sucursal_empresa->setIdEmpresa($empresa["id_empresa"]);
                    $sucursal_empresa->setDescuento($empresa["descuento"]);
                    $sucursal_empresa->setMargenUtilidad($empresa["margen_utilidad"]);
                    SucursalEmpresaDAO::save($sucursal_empresa);
                }
                if(!is_null($impuestos))
                {
                    $impuesto=new ImpuestoSucursal(array( "id_sucursal" => $sucursal->getIdSucursal()));
                    foreach($impuestos as $i)
                    {
                        if(is_null(ImpuestoDAO::getByPK($i)))
                        {
                            throw new Exception("El impuesto con id: ".$i." no existe");
                        }
                        $impuesto->setIdImpuesto($i);
                        ImpuestoSucursalDAO::save($impuesto);
                    }
                }
                if(!is_null($retenciones))
                {
                    $retencion= new RetencionSucursal(array( "id_sucursal" => $sucursal->getIdSucursal()));
                    foreach($retenciones as $r)
                    {
                        if(!is_null(RetencionDAO::getByPK($r)))
                        {
                            throw new Exception("La retencion con id: ".$r." no existe");
                        }
                        $retencion->setIdRetencion($r);
                        RetencionSucursalDAO::save($retencion);
                    }
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo crear la nueva sucursal ".$e);
                throw $e;
            }
            DAO::transEnd();
            Logger::log("Sucursal creada con exito");
            return $sucursal->getIdSucursal();
	}
  
	/**
 	 *
 	 *Edita los datos de una sucursal
 	 *
 	 * @param id_sucursal int Id de la sucursal a modificar
 	 * @param descuento float Descuento que tendran los productos ofrecidos por esta sucursal
 	 * @param margen_utilidad float Porcentaje del margen de utilidad que se obtendra de los productos vendidos en esta sucursal
 	 * @param descripcion string Descripcion de la sucursal
 	 * @param empresas json Objeto que contendra los ids de las empresas a las que esta sucursal pertenece, por lo menos tiene que haber una empresa. En este JSON, opcionalmente junto con el id de la empresa, aapreceran dos campos que seran margen_utilidad y descuento, que indicaran que todos los productos de esa empresa ofrecidos en esta sucursal tendran un margen de utilidad y/o un descuento con los valores en esos campos
 	 * @param telefono1 string telefono 1 de la sucursal
 	 * @param telefono2 string telefono 2 de la sucursal
 	 * @param numero_exterior string Numero exterior de la sucursal
 	 * @param razon_social string Razon social de la sucursal
 	 * @param id_gerente int Id del gerente de la sucursal
 	 * @param municipio int Municipio de la sucursal
 	 * @param impuestos json Objeto que contendra los ids de los impuestos que afectana esta sucursal
 	 * @param rfc string Rfc de la sucursal
 	 * @param saldo_a_favor float Saldo a favor de la sucursal
 	 * @param numero_interior string Numero interior de la sucursal
 	 * @param colonia string Colonia de la sucursal
 	 * @param calle string Calle de la sucursal
 	 * @param coidgo_postal string Codigo Postal de la sucursal
 	 **/
	public static function Editar
	(
		$id_sucursal, 
		$descuento = null, 
		$margen_utilidad = null, 
		$descripcion = null, 
		$empresas = null, 
		$telefono1 = null, 
		$telefono2 = null, 
		$numero_exterior = null, 
		$razon_social = null, 
		$id_gerente = null, 
		$municipio = null, 
		$impuestos = null, 
		$rfc = null, 
		$saldo_a_favor = null, 
		$numero_interior = null, 
		$colonia = null, 
		$calle = null, 
		$coidgo_postal = null,
                $retenciones = null
	)
	{
            Logger::log("Editando sucursal");
            $sucursal=SucursalDAO::getByPK($id_sucursal);
            $cambio_direccion=false;
            if(is_null($sucursal))
            {
                Logger::error("La sucursal con id: ".$id_sucursal." no existe");
                throw new Exception("La sucursal con id: ".$id_sucursal." no existe");
            }
            $direccion=DireccionDAO::getByPK($sucursal->getIdDireccion());
            if(is_null($direccion))
            {
                Logger::error("FATAL!!! La sucursal no cuenta con una direccion");
                throw new Exception("FATAL!!! La sucursal no cuenta con una direccion");
            }
            if(!is_null($descuento))
            {
                $sucursal->setDescuento($descuento);
            }
            if(!is_null($margen_utilidad))
            {
                $sucursal->setMargenUtilidad($margen_utilidad);
            }
            if(!is_null($descripcion))
            {
                $sucursal->setDescripcion($descripcion);
            }
            if(!is_null($telefono1))
            {
                $cambio_direccion=true;
                $direccion->setTelefono($telefono1);
            }
            if(!is_null($telefono2))
            {
                $cambio_direccion=true;
                $direccion->setTelefono2($telefono2);
            }
            if(!is_null($numero_exterior))
            {
                $cambio_direccion=true;
                $direccion->setNumeroExterior($numero_exterior);
            }
            if(!is_null($razon_social))
            {
                $sucursal->setRazonSocial($razon_social);
            }
            if(!is_null($id_gerente))
            {
                $sucursal->setIdGerente($id_gerente);
            }
            if(!is_null($municipio))
            {
                $cambio_direccion=true;
                $direccion->setIdCiudad($municipio);
            }
            if(!is_null($rfc))
            {
                $sucursal->setRfc($rfc);
            }
            if(!is_null($saldo_a_favor))
            {
                $sucursal->setSaldoAFavor($saldo_a_favor);
            }
            if(!is_null($numero_interior))
            {
                $cambio_direccion=true;
                $direccion->setNumeroInterior($numero_interior);
            }
            if(!is_null($colonia))
            {
                $cambio_direccion=true;
                $direccion->setColonia($colonia);
            }
            if(!is_null($calle))
            {
                $cambio_direccion=true;
                $direccion->setCalle($calle);
            }
            if(!is_null($coidgo_postal))
            {
                $cambio_direccion=true;
                $direccion->setCodigoPostal($coidgo_postal);
            }
            if($cambio_direccion)
            {
                $direccion->setUltimaModificacion(date("Y-m-d H:i:s",time()));
                $id_usuario=LoginController::getCurrentUser();
                if(is_null($id_usuario))
                {
                    Logger::error("No se pudo obtener al usuario de la sesion, ya inicio sesion?");
                    throw new Exception("No se pudo obtener al usuario de la sesion, ya inicio sesion?");
                }
                $direccion->setIdUsuarioUltimaModificacion($id_usuario);
            }
            DAO::transBegin();
            try
            {
                DireccionDAO::save($direccion);
                if(!is_null($empresas))
                {
                    foreach($empresas as $empresa)
                    {
                        if(is_null(EmpresaDAO::getByPK($empresa["id_empresa"])))
                        {
                            throw new Exception("La empresa con id: ".$empresa["id_empresa"]." no existe");
                        }
                        SucursalEmpresaDAO::save(new SucursalEmpresa(array( "id_sucursal" => $id_sucursal,
                            "id_empresa" => $empresa["id_empresa"], "margen_utilidad" => $empresa["margen_utilidad"], "descuento" => $empresa["descuento"] )));
                    }
                    $sucursales_empresa_actual=SucursalEmpresaDAO::search(new SucursalEmpresa(array( "id_sucursal" => $id_sucursal)));
                    foreach($sucursales_empresa_actual as $sucursal_empresa)
                    {
                        $encontrado=false;
                        foreach($empresas as $empresa)
                        {
                            if($empresa["id_empresa"]==$sucursal_empresa->getIdEmpresa())
                            {
                                $encontrado=true;
                                break;
                            }
                        }
                        if(!$encontrado)
                            SucursalEmpresaDAO::delete($sucursal_empresa);
                    }
                }
                if(!is_null($impuestos))
                {
                    foreach($impuestos as $impuesto)
                    {
                        if(is_null(ImpuestoDAO::getByPK($impuesto)))
                        {
                            throw new Exception("El impuesto con id: ".$impuesto." no existe");
                        }
                        ImpuestoSucursalDAO::save(new ImpuestoSucursal(array( "id_sucursal" => $id_sucursal, "id_impuesto" => $impuesto)));
                    }
                    $impuestos_sucursal_actual = ImpuestoSucursalDAO::search(new ImpuestoSucursal(array( "id_sucursal" => $id_sucursal)));
                    foreach($impuestos_sucursal_actual as $i)
                    {
                        $encontrado=false;
                        foreach($impuestos as $impuesto)
                        {
                            if($impuesto==$i->getIdImpuesto())
                            {
                                $encontrado=true;
                                break;
                            }
                        }
                        if(!$encontrado)
                        {
                            ImpuestoSucursalDAO::delete($i);
                        }
                    }
                }
                if(!is_null($retenciones))
                {
                    foreach($retenciones as $retencion)
                    {
                        if(is_null(RetencionDAO::getByPK($retencion)))
                        {
                            throw new Exception("La retencion con id: ".$retencion." no existe");
                        }
                        RetencionSucursalDAO::save(new RetencionSucursal(array( "id_sucursal" => $id_sucursal, "id_retencion" => $retencion)));
                    }
                    $retenciones_sucursal_actual = RetencionSucursalDAO::search(new RetencionSucursal(array( "id_sucursal" => $id_sucursal)));
                    foreach($retenciones_sucursal_actual as $r)
                    {
                        $encontrado=false;
                        foreach($retenciones as $retencion)
                        {
                            if($retencion==$r->getIdRetencion())
                            {
                                $encontrado=true;
                                break;
                            }
                        }
                        if(!$encontrado)
                            RetencionSucursalDAO::delete($r);
                    }
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo actualizar la sucursal: ".$e);
                throw new Exception("No se pudo actualizar la sucursal: ".$e);
            }
            DAO::transEnd();
            Logger::log("Sucursal actualizada exitosamente");
	}
  
	/**
 	 *
 	 *Edita la gerencia de una sucursal
 	 *
 	 * @param id_sucursal int Id de la sucursal de la cual su gerencia sera cambiada
 	 * @param id_gerente string Id del nuevo gerente
 	 **/
	public static function EditarGerencia
	(
		$id_sucursal, 
		$id_gerente
	)
	{
            Logger::log("Editando gerencia de sucursal");
            $sucursal=SucursalDAO::getByPK($id_sucursal);
            if(is_null($sucursal))
            {
                Logger::error("La sucursal con id: ".$id_sucursal." no existe");
                throw new Exception("La sucursal con id: ".$id_sucursal." no existe");
            }
            $gerente=UsuarioDAO::getByPK($id_gerente);
            if(is_null($gerente))
            {
                Logger::error("El usuario con id: ".$gerente." no existe");
                throw new Exception("El usuario con id: ".$gerente." no existe");
            }
            if($gerente->getIdRol()!=3)
            {
                Logger::error("El usuario no tiene rol de gerente");
                throw new Exception("El usuario no tiene rol de gerente");
            }
            $sucursal->setIdGerente($id_gerente);
            DAO::transBegin();
            try
            {
                SucursalDAO::save($sucursal);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("Error al editar la gerencia de la sucursal: ".$e);
                throw $e;
            }
            DAO::transEnd();
            Logger::log("Gerencia editada correctamente");
	}
  
	/**
 	 *
 	 *Hace un corte en los flujos de dinero de la sucursal. El Id de la sucursal se tomara de la sesion actual. La fehca se tomara del servidor.
 	 *
 	 * @param saldo_real float Saldo que hay actualmente en la caja
 	 * @param billetes json Ids de billetes y sus cantidades encontrados en la caja al hacer el cierre
 	 * @param id_cajero int Id del cajero en caso de que no sea este el que realiza el cierre
 	 * @return id_cierre int Id del cierre autogenerado.
 	 **/
	public static function CerrarCaja
	(
                $id_caja,
		$saldo_real, 
		$billetes = null,
		$id_cajero = null
	)
	{
            Logger::log("Cerrando caja");
            $caja=CajaDAO::getByPK($id_caja);
            if(is_null($caja))
            {
                Logger::error("No existe la caja con id:".$id_caja." no existe");
                throw new Exception("No existe la caja con id:".$id_caja." no existe");
            }
            if(!$caja->getActiva())
            {
                Logger::error("La caja proporcionada no esta activa, no se puede cerrar");
                throw new Exception("La caja proporcionada no esta activa, no se puede cerrar");
            }
            if(!$caja->getAbierta())
            {
                Logger::warn("La caja proporcionada ya esta cerrada");
                throw new Exception("La caja proporcionada ya esta cerrada");
            }
            $cierre_caja=new CierreCaja(
                    array(
                        "id_caja" => $id_caja,
                        "id_cajero" => $id_cajero,
                        "fecha" => date("Y-m-d H:i:s",time()),
                        "saldo_real" => $saldo_real,
                        "saldo_esperado" => $caja->getSaldo()
                    )
                    );
            try
            {
                CajasController::modificarCaja($id_caja, 0, $billetes, $caja->getSaldo());
            }
            catch(Exception $e)
            {
                throw $e;
            }
            DAO::transBegin();
            try
            {
                $caja->setAbierta(0);
                CierreCajaDAO::save($cierre_caja);
                CajaDAO::save($caja);
                if($caja->getControlBilletes())
                {
                    $billete_cierre_caja=new BilleteCierreCaja(array( "id_cierre_caja" => $cierre_caja->getIdCierreCaja() ));
                    $billete_cierre_caja->setCantidadFaltante(0);
                    $billete_cierre_caja->setCantidadSobrante(0);
                    foreach($billetes as $b)
                    {
                        $billete_cierre_caja->setIdBillete($b["id_billete"]);
                        $billete_cierre_caja->setCantidadEncontrada($b["cantidad"]);
                        BilleteCierreCajaDAO::save($billete_cierre_caja);
                    }
                    $billetes_caja=BilleteCajaDAO::search(new BilleteCaja(array( "id_caja" => $id_caja )));
                    foreach($billetes_caja as $b_c)
                    {
                        $billete_cierre_caja=BilleteCierreCajaDAO::getByPK($b_c->getIdBillete(), $cierre_caja->getIdCierreCaja());
                        if(is_null($billete_cierre_caja))
                            $billete_cierre_caja=new BilleteCierreCaja(array(
                                                    "id_billete" => $b_c->getIdBillete(),
                                                    "id_cierre_caja" => $cierre_caja->getIdCierreCaja(),
                                                    "cantidad_encontrada" => 0,
                                                    "cantidad_sobrante" => 0,
                                                    "cantidad_faltante" => 0
                                                    )
                                                        );
                        if($b_c->getCantidad()>0)
                        {
                            $billete_cierre_caja->setCantidadSobrante($b_c->getCantidad());
                        }
                        else if($b_c->getCantidad()<0)
                        {
                            $billete_cierre_caja->setCantidadFaltante($b_c->getCantidad()*-1);
                        }
                        else
                            continue;
                        $b_c->setCantidad(0);
                        BilleteCierreCajaDAO::save($billete_cierre_caja);
                        BilleteCajaDAO::save($b_c);
                    }
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("Error al cerrar la caja: ".$e);
                throw $e;
            }
            DAO::transEnd();
            Logger::log("Caja cerrada exitosamente");
            return $cierre_caja->getIdCierreCaja();
	}


	/**
 	 *
 	 *Metodo que surte una sucursal por parte de un proveedor. La sucursal sera tomada de la sesion actual.

Update
Creo que este metodo tiene que estar bajo sucursal.
 	 *
 	 * @param productos json Objeto que contendr los ids de los productos, sus unidades y sus cantidades
 	 * @param id_almacen int Id del almacen que se surte
 	 * @param motivo string Motivo del movimiento
 	 * @return id_surtido string Id generado por el registro de surtir
 	 **/
	public static function EntradaAlmacen
	(
		$productos, 
		$id_almacen, 
		$motivo = null
	)
	{
            Logger::log("Resgitrando entrada a almacen");
            $entrada_almacen = new EntradaAlmacen();
            $id_usuario=LoginController::getCurrentUser();
            if(is_null($id_usuario))
            {
                Logger::error("No se puede obtener al usuario de la sesion, ya inicio sesion?");
                throw new Exception("No se puede obtener al usuario de la sesion, ya inicio sesion?");
            }
            if(is_null(AlmacenDAO::getByPK($id_almacen)))
            {
                Logger::error("El almacen con id: ".$id_almacen." no existe");
                throw new Exception("El almacen con id: ".$id_almacen." no existe");
            }
            $entrada_almacen->setIdAlmacen($id_almacen);
            $entrada_almacen->setMotivo($motivo);
            $entrada_almacen->setIdUsuario($id_usuario);
            $entrada_almacen->setFechaRegistro(date("Y-m-d H:i:s",time()));
            DAO::transBegin();
            try
            {
                EntradaAlmacenDAO::save($entrada_almacen);
                $producto_entrada_almacen=new ProductoEntradaAlmacen(array( "id_entrada_almacen" => $entrada_almacen->getIdEntradaAlmacen() ));
                foreach($productos as $p)
                {
                    if(is_null(ProductoDAO::getByPK($p["id_producto"])))
                        throw new Exception("El producto con id: ".$p["id_producto"]." no existe");

                    $producto_entrada_almacen->setIdProducto($p["id_producto"]);
                    if(is_null(UnidadDAO::getByPK($p["id_unidad"])))
                        throw new Exception("La unidad con id: ".$p["id_unidad"]." no existe");

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
                throw $e;
            }
            DAO::transEnd();
            Logger::log("Entrada a almacen registrada exitosamente");
            return $entrada_almacen->getIdEntradaAlmacen();
	}
  
	/**
 	 *
 	 *Este metodo creara una caja asociada a una sucursal. Debe haber una caja por CPU. 
 	 *
 	 * @param token string el token que pos_client otorga por equipo
 	 * @param codigo_caja string El codigo de uso interno de la caja
 	 * @param impresoras json Un objeto con las impresoras asociadas a esta sucursal.
 	 * @param basculas json Un objeto con las basculas conectadas a esta caja.
 	 * @param descripcion string Descripcion de esta caja
 	 * @return id_caja int Id de la caja generada por la isnercion
 	 **/
	public static function NuevaCaja
	(
		$token, 
		$impresoras = null, 
		$basculas = null, 
		$descripcion = null,
                $id_sucursal = null
	)
	{
            Logger::log("Creando nueva caja");
            if(!is_null($id_sucursal))
            {
                if(is_null(SucursalDAO::getByPK($id_sucursal)))
                {
                    Logger::error("La sucursal con id: ".$id_sucursal." no existe");
                    throw new Exception("La sucursal con id: ".$id_sucursal." no existe");
                }
            }
            else
                $id_sucursal=self::getSucursal();
            $caja = new Caja();
            $caja->setIdSucursal($id_sucursal);
            $caja->setAbierta(0);
            $caja->setActiva(1);
            $caja->setControlBilletes(0);
            $caja->setDescripcion($descripcion);
            $caja->setIdSucursal($id_sucursal);
            $caja->setSaldo(0);
            $caja->setToken($token);
            DAO::transBegin();
            try
            {
                CajaDAO::save($caja);
                $impresora_caja = new ImpresoraCaja(array( "id_caja" => $caja->getIdCaja() ));
                foreach($impresoras as $id_impresora)
                {
                    if(is_null(ImpresoraDAO::getByPK($id_impresora)))
                    {
                        throw new Exception("La impresora con id: ".$id_impresora." no existe");
                    }
                    $impresora_caja->setIdImpresora($id_impresora);
                    ImpresoraCajaDAO::save($impresora_caja);
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo crear la caja: ".$e);
                throw $e;
            }
            DAO::transEnd();
            Logger::log("caja creada exitosamente");
            return $caja->getIdCaja();
	}
  
	/**
 	 *
 	 *Envia productos fuera del almacen. Ya sea que sea un traspaso de un alamcen a otro o por motivos de inventarios fisicos.
 	 *
 	 * @param productos json Objeto que contendra los ids de los productos que seran sacados del alamcen con sus cantidades y sus unidades
 	 * @param id_almacen int Id del almacen del cual se hace el movimiento
 	 * @param motivo string Motivo de la salida del producto
 	 * @return id_salida int ID de la salida del producto
 	 **/
	public static function SalidaAlmacen
	(
		$productos, 
		$id_almacen, 
		$motivo = null
	)
	{  
            Logger::log("Registrando salida de almacen");
            $id_usuario=LoginController::getCurrentUser();
            if(is_null($id_usuario))
            {
                Logger::error("No se pudo obtener al usuario de la sesion, ya inicio sesion?");
                throw new Exception("No se pudo obtener al usuario de la sesion, ya inicio sesion?");
            }
            if(is_null(AlmacenDAO::getByPK($id_almacen)))
            {
                Logger::error("El almacen con id: ".$id_almacen." no existe");
                throw new Exception("El almacen con id: ".$id_almacen." no existe");
            }
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
                SalidaAlmacenDAO::save($salida_almacen);
                $producto_salida_almacen=new ProductoSalidaAlmacen(array( "id_salida_almacen" => $salida_almacen->getIdSalidaAlmacen() ));
                foreach($productos as $p)
                {
                    $producto_almacen=ProductoAlmacenDAO::getByPK($p["id_producto"], $id_almacen, $p["id_unidad"]);
                    if(is_null($producto_almacen))
                    {
                        throw new Exception("El producto: ".$p["id_producto"]." en la unidad: ".$p["id_unidad"]."
                            no se encuentra en el almacen: ".$id_almacen.". No se puede registrar la salida");
                    }
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
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo registrar la salida de producto: ".$e);
                throw $e;
            }
            DAO::transEnd();
            Logger::log("Salida de almacen registrada correctamente");
            return $salida_almacen->getIdSalidaAlmacen();
	}
  
	/**
 	 *
 	 *Realiza un corte de caja. Este metodo reduce el dinero de la caja y va registrando el dinero acumulado de esa caja. Si faltase dinero se carga una deuda al cajero. La fecha sera tomada del servidor. El usuario sera tomado de la sesion.
 	 *
 	 * @param saldo_final float Saldo que se dejara en la caja para que continue realizando sus operaciones.
 	 * @param id_caja int Id de la caja a la que se le hace el corte
 	 * @param saldo_real float Saldo real encontrado en la caja
 	 * @param billetes_encontrados json Ids de billetes encontrados en la caja al hacer el corte
 	 * @param billetes_dejados json Ids de billetes dejados en la caja despues de hacer el corte
 	 * @param id_cajero int Id del cajero en caso de que no sea este el que realiza el corte
 	 * @param id_cajero_nuevo int Id del cajero que entrara despues de realizar el corte
 	 * @return id_corte_caja int Id generado por la insercion del nuevo corte
 	 **/
	public static function CorteCaja
	(
		$saldo_final, 
		$id_caja, 
		$saldo_real, 
		$billetes_encontrados = null,
		$billetes_dejados = null,
		$id_cajero = null, 
		$id_cajero_nuevo = null
	)
	{
            Logger::log("Realizando corte de caja");
            $caja=CajaDAO::getByPK($id_caja);
            if(is_null($caja))
            {
                Logger::error("La caja con id: ".$id_caja." no existe");
                throw new Exception("La caja con id: ".$id_caja." no existe");
            }
            if(!$caja->getActiva())
            {
                Logger::error("La caja proporcionada no esta activa, no se le puede hacer un corte");
                throw new Exception("La caja proporcionada no esta activa, no se le puede hacer un corte");
            }
            if(!$caja->getAbierta())
            {
                Logger::error("La caja proporcionada esta cerrada, no se pueden realizar movimientos a una caja cerrada");
                throw new Exception("La caja proporcionada esta cerrada, no se pueden realizar movimientos a una caja cerrada");
            }
            $corte_de_caja= new CorteDeCaja(array(
                                "id_caja" => $id_caja,
                                "id_cajero" => $id_cajero,
                                "id_cajero_nuevo" => $id_cajero_nuevo,
                                "fecha" => date("Y-m-d H:i:s",time()),
                                "saldo_real" => $saldo_real,
                                "saldo_esperado" => $caja->getSaldo(),
                                "saldo_final" => $saldo_final
                                )
                            );
            DAO::transBegin();
            try
            {
                CorteDeCajaDAO::save($corte_de_caja);
                CajasController::modificarCaja($id_caja, 0, $billetes_encontrados, $caja->getSaldo());
                if($caja->getControlBilletes())
                {
                    $billete_corte_caja = new BilleteCorteCaja(array( 
                                        "id_corte_caja" => $corte_de_caja->getIdCorteDeCaja(), 
                                        "cantidad_dejada" => 0,
                                        "cantidad_sobrante" => 0,
                                        "cantidad_faltante" => 0
                                                )
                                            );
                    foreach($billetes_encontrados as $billete)
                    {
                        $billete_corte_caja->setIdBillete($billete["id_billete"]);
                        $billete_corte_caja->setCantidadEncontrada($billete["cantidad"]);
                        BilleteCorteCajaDAO::save($billete_corte_caja);
                    }
                    $billetes_caja=BilleteCajaDAO::search(new BilleteCaja(array( "id_caja" => $id_caja )));
                    foreach($billetes_caja as $b_c)
                    {
                        $billete_corte_caja=BilleteCorteCajaDAO::getByPK($b_c->getIdBillete(), $corte_de_caja->getIdCorteDeCaja());
                        if(is_null($billete_corte_caja ))
                            $billete_corte_caja = new BilleteCorteCaja(array(
                                                    "id_billete" => $b_c->getIdBillete(),
                                                    "id_corte_caja" => $corte_de_caja->getIdCorteDeCaja(),
                                                    "cantidad_encontrada" => 0,
                                                    "cantidad_dejada" => 0,
                                                    "cantidad_sobrante" => 0,
                                                    "cantidad_faltante" => 0
                                                    )
                                                            );
                        if($b_c->getCantidad()>0)
                        {
                            $billete_corte_caja->setCantidadSobrante($b_c->getCantidad());
                        }
                        else if($b_c->getCantidad()<0)
                        {
                            $billete_corte_caja->setCantidadFaltante($b_c->getCantidad()*-1);
                        }
                        else
                            continue;
                        $b_c->setCantidad(0);
                        BilleteCajaDAO::save($b_c);
                        BilleteCorteCajaDAO::save($billete_corte_caja);
                    }
                    if(is_null($billetes_dejados)&&$saldo_final!=0)
                    {
                        throw new Exception("No se encontro el parametro billetes_dejados cuando se esta llevando control de los billetes en esta caja");
                    }
                    foreach($billetes_dejados as $b_d)
                    {
                         $billete_corte_caja=BilleteCorteCajaDAO::getByPK($b_d["id_billete"], $corte_de_caja->getIdCorteDeCaja());
                         if(is_null($billete_corte_caja ))
                            $billete_corte_caja = new BilleteCorteCaja(array(
                                                    "id_billete" => $b_d["id_billete"],
                                                    "id_corte_caja" => $corte_de_caja->getIdCorteDeCaja(),
                                                    "cantidad_encontrada" => 0,
                                                    "cantidad_dejada" => 0,
                                                    "cantidad_sobrante" => 0,
                                                    "cantidad_faltante" => 0
                                                    )
                                                            );
                         $billete_corte_caja->setCantidadDejada($b_d["cantidad"]);
                         BilleteCorteCajaDAO::save($billete_corte_caja);
                    }
                }
                CajasController::modificarCaja($id_caja, 1, $billetes_dejados, $saldo_final);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo realizar el corte de caja: ".$e);
                throw $e;
            }
            DAO::transEnd();
            Logger::log("Corte de caja realizado correctamente");
            return $corte_de_caja->getIdCorteDeCaja();
	}
  
	/**
 	 *
 	 *Descativa un almacen. Para poder desactivar un almacen, este tiene que estar vac?
 	 *
 	 * @param id_almacen int Id del almacen a desactivar
 	 **/
	public static function EliminarAlmacen
	(
		$id_almacen
	)
	{
            Logger::log("Eliminando almacen");
            $almacen=AlmacenDAO::getByPK($id_almacen);
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
            $productos_almacen=ProductoAlmacenDAO::search(new ProductoAlmacen(array( "id_almacen" => $id_almacen )));
            try
            {
                AlmacenDAO::save($almacen);
                foreach($productos_almacen as $producto_almacen)
                {
                    if($producto_almacen->getCantidad()!=0)
                    {
                        throw new Exception("El almacen no puede ser borrado pues aun contiene productos");
                    }
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo eliminar el almacen: ".$e);
                throw $e;
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
 	 * @param nombre string Nombre del almacen
 	 **/
	public static function EditarAlmacen
	(
		$id_almacen, 
		$descripcion = null, 
		$nombre = null,
                $id_tipo_almacen = null
	)
	{
            Logger::log("Editando almacen");
            $almacen=AlmacenDAO::getByPK($id_almacen);
            if(is_null($almacen))
            {
                Logger::error("El almacen con id: ".$id_almacen." no existe");
                throw new Exception("El almacen con id: ".$id_almacen." no existe");
            }
            if(!$almacen->getActivo())
            {
                Logger::error("El almacen no esta activo, no puede ser editado");
                throw new Exception("El almacen no esta activo, no puede ser editado");
            }
            if(!is_null($descripcion))
            {
                $almacen->setDescripcion($descripcion);
            }
            if(!is_null($nombre))
            {
                $almacen->setNombre($nombre);
            }
            if(!is_null($id_tipo_almacen))
            {
                if(is_null(TipoAlmacenDAO::getByPK($id_tipo_almacen)))
                {
                    Logger::error("El tipo de almacen con id: ".$id_tipo_almacen." no existe");
                    throw new Exception("El tipo de almacen con id: ".$id_tipo_almacen." no existe");
                }
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
                throw $e;
            }
            DAO::transEnd();
            Logger::log("Almacen editado exitosamente");
	}
  
	/**
 	 *
 	 *Edita la informacion de una caja
 	 *
 	 * @param id_caja int Id de la caja a editar
 	 * @param descripcion string Descripcion de la caja
 	 * @param token string Token generado por el pos client
 	 **/
	public static function EditarCaja
	(
		$id_caja, 
		$descripcion = null, 
		$token = null
	)
	{
            Logger::log("Editando caja");
            $caja=CajaDAO::getByPK($id_caja);
            if(is_null($caja))
            {
                Logger::error("La caja con id: ".$id_caja." no existe");
                throw new Exception("La caja con id: ".$id_caja." no existe");
            }
            if(!$caja->getActiva())
            {
                Logger::error("La caja no esta activa, no se puede editar");
                throw new Exception("La caja no esta activa, no se puede editar");
            }
            if(!is_null($descripcion))
            {
                $caja->setDescripcion($descripcion);
            }
            if(!is_null($token))
            {
                $caja->setToken($token);
            }
            DAO::transBegin();
            try
            {
                CajaDAO::save($caja);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo editar la caja: ".$e);
                throw $e;
            }
            DAO::transEnd();
            Logger::log("Caja editada exitosamente");
	}
  
	/**
 	 *
 	 *Desactiva una caja, para que la caja pueda ser desactivada, tieneq ue estar cerrada
 	 *
 	 * @param id_caja int Id de la caja a eliminar
 	 **/
	public static function EliminarCaja
	(
		$id_caja
	)
	{  
            Logger::log("Eliminando caja ".$id_caja);
            $caja=CajaDAO::getByPK($id_caja);
            if(is_null($caja))
            {
                Logger::error("La caja con id: ".$id_caja." no existe");
                throw new Exception("La caja con id: ".$id_caja." no existe");
            }
            if(!$caja->getActiva())
            {
                Logger::warn("La caja ya ha sido eliminada");
                throw new Exception("La caja ya ha sido eliminada");
            }
            if($caja->getAbierta())
            {
                Logger::error("La caja esta abierta y no puede ser eliminada");
                throw new Exception("La caja esta abierta y no puede ser eliminada");
            }
            if($caja->getSaldo()!=0)
            {
                Logger::error("El saldo de la caja no esta en 0, no se puede eliminar");
                throw new Exception("El saldo de la caja no esta en 0, no se puede eliminar");
            }
            $caja->setActiva(0);
            DAO::transBegin();
            try
            {
                CajaDAO::save($caja);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("Error al eliminar la caja ".$e);
                throw $e;
            }
            DAO::transEnd();
            Logger::log("Caja eliminada exitosamente");
	}
  
	/**
 	 *
 	 *Desactiva una sucursal. Para poder desactivar una sucursal su saldo a favor tiene que ser mayor a cero y sus almacenes tienen que estar vacios.
 	 *
 	 * @param id_sucursal int Id de la sucursal a desactivar
 	 **/
	public static function Eliminar
	(
		$id_sucursal
	)
	{
            Logger::log("Eliminando sucursal ".$id_sucursal);
            $sucursal=SucursalDAO::getByPK($id_sucursal);
            if(is_null($sucursal))
            {
                Logger::error("La sucursal con id :".$id_sucursal." no existe");
                throw new Exception("La sucursal con id :".$id_sucursal." no existe");
            }
            if(!$sucursal->getActiva())
            {
                Logger::warn("La sucursal ya ha sido eliminada");
                throw new Exception("La sucursal ya ha sido eliminada");
            }
            if($sucursal->getSaldoAFavor()!=0)
            {
                Logger::error("La sucursal no tiene un saldo de 0 y no puede ser eliminada");
                throw new Exception("La sucursal no tiene un saldo de 0 y no puede ser eliminada");
            }
            $sucursal->setFechaBaja(date("Y-m-d H:i:s"));
            $sucursal->setActiva(0);
            DAO::transBegin();
            try
            {
                SucursalDAO::save($sucursal);
                $cajas=CajaDAO::search(new Caja(array( "id_sucursal" => $id_sucursal )));
                foreach($cajas as $c)
                {
                    self::EliminarCaja($c->getIdCaja());
                }
                $impuestos_sucursal=ImpuestoSucursalDAO::search(new ImpuestoSucursal(array( "id_sucursal" => $id_sucursal )));
                foreach($impuestos_sucursal as $i_e)
                {
                    ImpuestoSucursalDAO::delete($i_e);
                }
                $paquetes_sucursal=PaqueteSucursalDAO::search(new PaqueteSucursal(array( "id_sucursal" => $id_sucursal )));
                foreach($paquetes_sucursal as $p_s)
                {
                    PaqueteSucursalDAO::delete($p_s);
                }
                $retencion_sucursal=RetencionSucursalDAO::search(new RetencionSucursal(array( "id_sucursal" => $id_sucursal )));
                foreach($retencion_sucursal as $r_s)
                {
                    RetencionSucursalDAO::delete($r_s);
                }
                $servicio_sucursal=ServicioSucursalDAO::search(new ServicioSucursal(array( "id_sucursal" => $id_sucursal )));
                foreach($servicio_sucursal as $s_s)
                {
                    ServicioSucursalDAO::delete($s_s);
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("La sucursal no pudo ser eliminada ".$e);
                throw $e;
            }
            DAO::transEnd();
            Logger::log("Sucursal eliminada exitosamente");
	}
  
	/**
 	 *
 	 *Crea un registro de traspaso de producto de un almacen a otro. El usuario que envia sera tomada de la sesion.
 	 *
 	 * @param id_almacen_recibe int Id del almacen al que se envia el producto
 	 * @param id_almacen_envia int Id del almacen que envia el producto
 	 * @param fecha_envio_programada string Fecha de envio programada para este traspaso
 	 * @param productos json Productos a ser enviados con sus cantidades
 	 * @return id_traspaso int Id del traspaso autogenerado
 	 **/
	public static function ProgramarTraspasoAlmacen
	(
		$id_almacen_recibe, 
		$id_almacen_envia, 
		$fecha_envio_programada, 
		$productos
	)
	{  
            Logger::log("Creando traspaso");
            $id_usuario=LoginController::getCurrentUser();
            if(is_null($id_usuario))
            {
                Logger::error("No se pudo obtener al usuario de la sesion, ya inicio sesion?");
                throw new Exception("No se pudo obtener al usuario de la sesion, ya inicio sesion?");
            }
            if(is_null(AlmacenDAO::getByPK($id_almacen_recibe)))
            {
                Logger::error("El almacen con id: ".$id_almacen_recibe." no existe");
                throw new Exception("El almacen con id: ".$id_almacen_recibe." no existe");
            }
            if(is_null(AlmacenDAO::getByPK($id_almacen_envia)))
            {
                Logger::error("El almacen con id: ".$id_almacen_envia." no existe");
                throw new Exception("El almacen con id: ".$id_almacen_envia." no existe");
            }
            $traspaso=new Traspaso(array(
                            "id_usuario_programa"   => $id_usuario,
                            "id_usuario_envia"      => 0,
                            "id_almacen_envia"      => $id_almacen_envia,
                            "fecha_envio_programada"=> $fecha_envio_programada,
                            "fecha_envio"           => "0000-00-00 00:00:00",
                            "id_usuario_recibe"     => 0,
                            "id_almacen_recibe"     => $id_almacen_recibe,
                            "fecha_recibo"          => "0000-00-00 00:00:00",
                            "estado"                => "Envio programado",
                            "cancelado"             => 0,
                            "completo"              => 0
                            )
                        );
            DAO::transBegin();
            try
            {
                TraspasoDAO::save($traspaso);
                $traspaso_producto=new TraspasoProducto(array(
                                    "id_traspaso"       => $traspaso->getIdTraspaso(),
                                    "cantidad_recibida" => 0
                                        )
                                    );
                foreach($productos as $p)
                {
                    if(is_null(ProductoDAO::getByPK($p["id_producto"])))
                    {
                        throw new Exception("El producto con id: ".$p["id_producto"]." no existe");
                    }
                    if(is_null(UnidadDAO::getByPK($p["id_unidad"])))
                    {
                        throw new Exception("La unidad con id: ".$p["id_unidad"]." no existe");
                    }
                    $traspaso_producto->setIdProducto($p["id_producto"]);
                    $traspaso_producto->setIdUnidad($p["id_unidad"]);
                    $traspaso_producto->setCantidadEnviada($p["cantidad"]);
                    TraspasoProductoDAO::save($traspaso_producto);
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("Error al crear el traspaso de producto: ".$e);
                throw $e;
            }
            DAO::transEnd();
            Logger::log("traspaso creado exitosamente");
            return $traspaso->getIdTraspaso();
	}
  
	/**
 	 *
 	 *Cambia el estado del traspaso a enviado y captura la fecha de envio del servidor. El usuario que envia sera tomado del servidor.
 	 *
 	 * @param id_traspaso int Id del traspaso a enviar
 	 **/
	public static function EnviarTraspasoAlmacen
	(
		$id_traspaso
	)
	{  
            Logger::log("Enviando traspaso: ".$id_traspaso);
            $id_usuario=LoginController::getCurrentUser();
            if(is_null($id_usuario))
            {
                Logger::error("No se puede obtener al usuario de la sesion, ya inicio sesion?");
                throw new Exception("No se puede obtener al usuario de la sesion, ya inicio sesion?");
            }
            $traspaso=TraspasoDAO::getByPK($id_traspaso);
            if(is_null($traspaso))
            {
                Logger::error("El traspaso con id: ".$id_traspaso." no existe");
                throw new Exception("El traspaso con id: ".$id_traspaso." no existe");
            }
            if($traspaso->getCancelado())
            {
                Logger::error("El traspaso ya ha sido cancelado, no se puede enviar");
                throw new Exception("El traspaso ya ha sido cancelado, no se puede enviar");
            }
            if($traspaso->getCompleto())
            {
                Logger::error("El traspaso ya ha sido completado, no se puede enviar");
                throw new Exception("El traspaso ya ha sido completado, no se puede enviar");
            }
            if($traspaso->getEstado()==="Enviado")
            {
                Logger::warn("El traspaso ya ha sido enviado");
                throw new Exception("El traspaso ya ha sido enviado");
            }
            $traspaso->setFechaEnvio(date("Y-m-d H:i:s"));
            $traspaso->setIdUsuarioEnvia($id_usuario);
            $traspaso->setEstado("Enviado");
            if(is_null(AlmacenDAO::getByPK($traspaso->getIdAlmacenEnvia())))
            {
                Logger::error("FATAL!!! el traspaso no cuenta con un almacen q envia");
                throw new Exception("FATAL!!! el traspaso no cuenta con un almacen q envia");
            }
            $productos_traspaso=TraspasoProductoDAO::search(new TraspasoProducto(array( "id_traspaso" => $id_traspaso )));
            DAO::transBegin();
            try
            {
                TraspasoDAO::save($traspaso);
                foreach($productos_traspaso as $p_t)
                {
                    $producto_almacen=ProductoAlmacenDAO::getByPK($p_t->getIdProducto(), $traspaso->getIdAlmacenEnvia(), $p_t->getIdUnidad());
                    if(is_null($producto_almacen))
                    {
                        throw new Exception("No se puede enviar el producto de este almacen ya que no hay existencias");
                    }
                    if($producto_almacen->getCantidad()<$p_t->getCantidadEnviada())
                    {
                        throw new Exception("No se puede enviar esta cantidad de producto de este almacen ya que no hay existencias");
                    }
                    $producto_almacen->setCantidad($producto_almacen->getCantidad()-$p_t->getCantidadEnviada());
                    ProductoAlmacenDAO::save($producto_almacen);
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo enviar el traspaso ".$e);
                throw $e;
            }
            DAO::transEnd();
            Logger::log("Traspaso enviado exitosamente");
	}
  
	/**
 	 *
 	 *Cambia el estado de un traspaso a recibido. La  bandera de completo se prende si los productos enviados son los mismos que los recibidos. La fecha de recibo es tomada del servidor. El usuario que recibe sera tomada de la sesion actual.
 	 *
 	 * @param productos json Productos que se reciben con sus cantidades
 	 * @param id_traspaso int Id del traspaso que se recibe
 	 **/
	public static function RecibirTraspasoAlmacen
	(
		$productos, 
		$id_traspaso
	)
	{  
            Logger::log("Recibiendo traspaso ".$id_traspaso);
            $id_usuario=LoginController::getCurrentUser();
            if(is_null($id_usuario))
            {
                Logger::error("El usuario no puede ser obtenido de la sesion, ya inicio sesion?");
                throw new Exception("El usuario no puede ser obtenido de la sesion, ya inicio sesion?");
            }
            $traspaso=TraspasoDAO::getByPK($id_traspaso);
            if(is_null($traspaso))
            {
                Logger::error("El traspaso con id: ".$id_traspaso." no existe");
                throw new Exception("El traspaso con id: ".$id_traspaso." no existe");
            }
            if($traspaso->getCancelado())
            {
                Logger::error("El traspaso ya ha sido cancelado, no puede ser recibido");
                throw new Exception("El traspaso ya ha sido cancelado, no puede ser recibido");
            }
            if($traspaso->getCompleto())
            {
                Logger::error("El traspaso ya ha sido completado, no puede volver a ser recibido");
                throw new Exception("El traspaso ya ha sido completado, no puede volver a ser recibido");
            }
            if($traspaso->getEstado()!=="Enviado")
            {
                Logger::error("El traspaso no ha sido enviado");
                throw new Exception("El traspaso no ha sido enviado");
            }
            $traspaso->setIdUsuarioRecibe($id_usuario);
            $traspaso->setFechaRecibo(date("Y-m-d H:i:s"));
            $traspaso->setEstado("Recibido");
            $traspaso->setCompleto(1);
            DAO::transBegin();
            try
            {
                TraspasoDAO::save($traspaso);
                foreach($productos as $p)
                {
                    $producto_traspaso=TraspasoProductoDAO::getByPK($id_traspaso, $p["id_producto"],$p["id_unidad"]);
                    if(is_null($producto_traspaso))
                    {
                        $producto_traspaso= new TraspasoProducto(array(
                                                        "id_traspaso"       => $id_traspaso,
                                                        "id_producto"       => $p["id_producto"],
                                                        "id_unidad"         => $p["id_unidad"],
                                                        "cantidad_enviada"  => 0
                                                        )
                                                    );
                    }
                    $producto_almacen=ProductoAlmacenDAO::getByPK($p["id_producto"], $traspaso->getIdAlmacenRecibe(), $p["id_unidad"]);
                    if(is_null($producto_almacen))
                    {
                        $producto_almacen= new ProductoAlmacen(array(
                                                    "id_producto"   => $p_t->getIdProducto(),
                                                    "id_almacen"    => $traspaso->getIdAlmacenEnvia(),
                                                    "id_unidad"     => $p_t->getIdUnidad(),
                                                    "cantidad"      => 0
                                                    )
                                                );
                    }
                    $producto_almacen->setCantidad($producto_almacen->getCantidad()+$p["cantidad"]);
                    $producto_traspaso->setCantidadRecibida($p["cantidad"]);
                    ProductoAlmacenDAO::save($producto_almacen);
                    TraspasoProductoDAO::save($producto_traspaso);
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo recibir el traspaso: ".$e);
                throw $e;
            }
            DAO::transEnd();
            Logger::log("Eltraspaso ha sido recibido exitosamente");
	}
  
	/**
 	 *
 	 *Para poder cancelar un traspaso, este no tuvo que haber sido enviado aun.
 	 *
 	 * @param id_traspaso int Id del traspaso a cancelar
 	 **/
	public static function CancelarTraspasoAlmacen
	(
		$id_traspaso
	)
	{  
           Logger::log("Cancelando traspaso: ".$id_traspaso);
           $traspaso=TraspasoDAO::getByPK($id_traspaso);
           if(is_null($traspaso))
           {
               Logger::error("El traspaso con id: ".$id_traspaso." no existe");
               throw new Exception("El traspaso con id: ".$id_traspaso." no existe");
           }
           if($traspaso->getCancelado())
           {
               Logger::warn("El traspaso ya ha sido cancelado");
               throw new Exception("El traspaso ya ha sido cancelado");
           }
           if($traspaso->getCompleto()||$traspaso->getEstado()!=="Envio programado")
           {
               Logger::error("El traspaso no puede ser cancelado pues ya se han realizado acciones sobre el");
               throw new Exception("El traspaso no puede ser cancelado pues ya se han realizado acciones sobre el");
           }
           $traspaso->setCancelado(1);
           $traspaso->setEstado("Cancelado");
           DAO::transBegin();
           try
           {
               TraspasoDAO::save($traspaso);
           }
           catch(Exception $e)
           {
               DAO::transRollback();
               Logger::error("No se pudo cancelar el traslado ".$e);
               throw $e;
           }
           DAO::transEnd();
           Logger::log("Traslado cancelado exitosamente");
	}
  
	/**
 	 *
 	 *Lista los traspasos de almacenes. Puede filtrarse por empresa, por sucursal, por almacen, por producto, cancelados, completos, estado
 	 *
 	 * @param cancelado bool Si este valor no es obtenido, se listaran los traspasos tanto cancelados como no cancelados. Si su valor es verdadero se listaran solo los traspasos cancelados, si su valor es falso, se listaran los traspasos no cancelados
 	 * @param completo bool Si este valor no es obtenido, se listaran los traspasos tanto completos como no completos. Si su valor es verdadero, se listaran los traspasos completos, si es falso, se listaran los traspasos no completos
 	 * @param id_producto int Se listaran los traspasos que incluyan este producto
 	 * @param id_almacen int Se listaran los traspasos enviados y/o recibidos por este almacen
 	 * @param enviados bool Si este valor no es obtenido, se listaran los traspasos tanto enviados como recibidos de este almacen (campo id_almacen). Si su valor es verdader, se listaran los traspasos enviados por este almacen, si su valor es falso, se listaran los traspasos recibidos por este almacen
 	 * @param id_sucursal int Se listaran los traspasos de los almacenes de esta sucursal
 	 * @param id_empresa int Se listaran los traspasos de los almacenes de esta empresa
 	 * @param estado string Se listaran los traspasos cuyo estado sea este, si no es obtenido este valor, se listaran los traspasos de cualqueir estado
 	 * @param ordenar json Determina el orden de la lista
 	 * @return traspasos json Lista de traspasos
 	 **/
	public static function ListaTraspasoAlmacen
	(
		$cancelado = null, 
		$completo = null, 
		$id_almacen_envia = null,
                $id_almacen_recibe = null,
		$estado = null, 
		$ordenar = null
	)
	{  
            Logger::log("Listando traspaso de almacenes");
            $parametros=false;
            if(
                    !is_null($cancelado)        ||
                    !is_null($completo)         ||
                    !is_null($id_almacen_recibe)||
                    !is_null($id_almacen_envia) ||
                    !is_null($estado)
            )
                $parametros=true;
            $traspasos_almacen=null;
            if($parametros)
            {
                Logger::log("Se recibieron parametros, se listan los traspasos en rango");
                $traspaso_criterio=new Traspaso(array(
                                            "cancelado"         => $cancelado,
                                            "completo"          => $completo,
                                            "id_almacen_envia"  => $id_almacen_envia,
                                            "id_almacen_recibe" => $id_almacen_recibe,
                                            "estado"            => $estado
                                            )
                                        );
                $traspasos_almacen=TraspasoDAO::search($traspaso_criterio);
            }
            else
            {
                Logger::log("No se recibieron parametros, se listan todos los traspasos");
                $traspasos_almacen=TraspasoDAO::getAll(null,null,$ordenar);
            }
            Logger::log("lista de traspasos recibida con ".count($traspasos_almacen)." elementos");
            return $traspasos_almacen;
	}
  
	/**
 	 *
 	 *Para poder editar un traspaso,este no tuvo que haber sido enviado aun
 	 *
 	 * @param id_traspaso int Id del traspaso a editar
 	 * @param productos json Productos a enviar con sus cantidades
 	 * @param fecha_envio_programada string Fecha de envio programada
 	 **/
	public static function EditarTraspasoAlmacen
	(
		$id_traspaso, 
		$productos = null, 
		$fecha_envio_programada = null
	)
	{  
            Logger::log("Editando traspaso ".$id_traspaso);
            $traspaso=TraspasoDAO::getByPK($id_traspaso);
            if(is_null($traspaso))
            {
                Logger::error("El traspaso con id: ".$id_traspaso." no existe");
                throw new Exception("El traspaso con id: ".$id_traspaso." no existe");
            }
            if($traspaso->getCancelado())
            {
                Logger::error("El traspaso ya ha sido cancelado, no puede ser editado");
                throw new Exception("El traspaso ya ha sido cancelado, no puede ser editado");
            }
            if($traspaso->getCompleto()||$traspaso->getEstado()!=="Envio programado")
            {
                Logger::error("No se puede editar el traspaso de almacen pues ya se han realizado acciones sobre el");
                throw new Exception("No se puede editar el traspaso de almacen pues ya se han realizado acciones sobre el");
            }
            if(!is_null($fecha_envio_programada))
            {
                $traspaso->setFechaEnvioProgramada($fecha_envio_programada);
            }
            DAO::transBegin();
            try
            {
                TraspasoDAO::save($traspaso);
                if(!is_null($productos))
                {
                    foreach($productos as $p)
                    {
                        $traspaso_producto=TraspasoProductoDAO::getByPK($id_traspaso, $p["id_producto"],$p["id_unidad"]);
                        if(is_null($traspaso_producto))
                        {
                            $traspaso_producto=new TraspasoProducto(array(
                                                            "id_traspaso"       => $id_traspaso,
                                                            "id_producto"       => $p["id_producto"],
                                                            "id_unidad"         => $p["id_unidad"],
                                                            "cantidad_recibida" => 0
                                                            )
                                                        );
                        }
                        $traspaso_producto->setCantidadEnviada($p["id_unidad"]);
                        TraspasoProductoDAO::save($traspaso_producto);
                    }
                    $traspasos_producto=TraspasoProductoDAO::search(new TraspasoProducto(array( "id_traspaso" => $id_traspaso )));

                    foreach($traspasos_producto as $t_p)
                    {
                        $encontrado=false;
                        foreach($productos as $p)
                        {
                            if($t_p->getIdProducto()==$p["id_producto"]&&$t_p->getIdUnidad()==$p["id_unidad"])
                            {
                                $encontrado=true;
                                break;
                            }
                        }
                        if(!$encontrado)
                        {
                            TraspasoProductoDAO::delete($t_p);
                        }
                    }
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo editar el traspaso: ".$e);
                throw $e;
            }
            DAO::transEnd();
            Logger::log("Traspaso editado correctamente");
	}
  }
