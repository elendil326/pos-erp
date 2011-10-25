<?php
require_once("interfaces/Sucursales.interface.php");
/**
  *
  *
  *
  **/


	
  class SucursalesController implements ISucursales{
  
      var $formato_fecha="Y-m-d H:i:s";

      private static $almacen_consignacion=2;

      private function getCaja()
      {
          return 1;
      }

      private function getSucursal()
      {
          return 1;
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
	public function NuevoAlmacen
	(
		$nombre, 
		$id_sucursal, 
		$id_empresa, 
		$id_tipo_almacen, 
		$descripcion = null
	)
	{
            Logger::log("Creando nuevo almacen");
            if(SucursalDAO::getByPK($id_sucursal)==null)
            {
                Logger::error("La sucursal con id: ".$id_sucursal." no existe");
                throw new Exception("La sucursal con id: ".$id_sucursal." no existe");
            }
            if(EmpresaDAO::getByPK($id_empresa)==null)
            {
                Logger::error("La empresa con id: ".$id_empresa." no existe");
                throw new Exception("La empresa con id: ".$id_empresa." no existe");
            }
            if(TipoAlmacenDAO::getByPK($id_tipo_almacen)==null)
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
	public function ListaAlmacen
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
                    $id_empresa != null ||
                    $id_sucursal != null ||
                    $id_tipo_almacen != null ||
                    $activo !== null
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
	public function VenderCaja
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
            if($id_usuario==null)
            {
                Logger::error("No se pudo obtener al usuario de la sesion actual, ya inicio sesion?");
                throw new Exception("No se pudo obtener al usuario de la sesion actual, ya inicio sesion?");
            }
            $usuario=UsuarioDAO::getByPK($id_comprador);
            if($usuario==null)
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
            $venta->setIdCaja($this->getCaja());
            $venta->setIdSucursal($this->getSucursal());
            $venta->setIdUsuario($id_usuario);
            $venta->setIdVentaCaja($id_venta_caja);
            $venta->setCancelada(0);
            $venta->setTipoDePago($tipo_pago);
            $venta->setFecha(date($this->formato_fecha,time()));
            DAO::transBegin();
            try
            {
                if($tipo_venta==="contado")
                {
                    if($tipo_pago==="cheque"&&$cheques==null)
                    {
                        throw new Exception("El tipo de pago es con cheque pero no se recibio informacion del mismo");
                    }
                    if($saldo!==null)
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
                        if($billetes_cambio!=null)
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
                    if($saldo==null)
                    {
                        Logger::warn("No se recibio un saldo, se tomara 0 como saldo");
                        $saldo=0;
                    }
                    $venta->setSaldo($saldo);
                    VentaDAO::save($venta);
                    $usuario->setSaldoDelEjercicio($usuario->getSaldoDelEjercicio()-$total+$saldo);
                    UsuarioDAO::save($usuario);
                }
                else
                {
                    throw new Exception("El tipo de venta recibido no es valido");
                }
                if($detalle_orden==null&&$detalle_paquete==null&&$detalle_producto==null)
                {
                    throw new Exception ("No se recibieron ni paquetes ni productos ni servicios para esta venta");
                }
                if($detalle_paquete!=null)
                {
                    $d_paquete=new VentaPaquete();
                    $d_paquete->setIdVenta($venta->getIdVenta());
                    foreach($detalle_paquete as $d_p)
                    {
                        if(PaqueteDAO::getByPK($d_p["id_paquete"])==null)
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
                if($detalle_producto!=null)
                {
                    $d_producto=new VentaProducto();
                    $d_producto->setIdVenta($venta->getIdVenta());
                    foreach($detalle_producto as $d_p)
                    {
                        $producto=ProductoDAO::getByPK($d_p["id_producto"]);
                        if($producto==null)
                        {
                            throw new Exception("El producto con id: ".$d_p["id_producto"]." no existe");
                        }
                        if(UnidadDAO::getByPk($d_p["id_unidad"])==null)
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
                        $this->DescontarDeAlmacenes($d_producto, $this->getSucursal());
                    }
                }
                if($detalle_orden!=null)
                {
                    $d_orden = new VentaOrden();
                    $d_orden->setIdVenta($venta->getIdVenta());
                    foreach($detalle_orden as $d_p)
                    {
                        if(OrdenDeServicioDAO::getByPK($d_p["id_orden_de_servicio"])==null)
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
                $id_empresas=$this->ObtenerEmpresas($detalle_producto, $detalle_paquete, $detalle_orden);
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

        private function ObtenerEmpresas
        (
                $detalle_producto=null,
                $detalle_paquete=null,
                $detalle_orden=null
        )
        {
            $empresas=array();
            $id_empresas=array( "id" => array(), "total" => array());
            $parametro=false;
            if($detalle_producto!=null)
            {
                $parametro=true;
                $producto_empresa=new ProductoEmpresa();
                foreach($detalle_producto as $d_p)
                {
                    $producto_empresa->setIdProducto($d_p["id_producto"]);
                    $productos_empresa=ProductoEmpresaDAO::search($producto_empresa);
                    foreach($productos_empresa as $p_e)
                    {
                        $this->InsertarIdEmpresa($p_e, $id_empresas, $d_p["precio"]*$d_p["cantidad"]);
                    }
                }
            }
            if($detalle_paquete!=null)
            {
                $parametro=true;
                $paquete_empresa=new PaqueteEmpresa();
                foreach($detalle_paquete as $d_p)
                {
                    $paquete_empresa->setIdPaquete($d_p["id_paquete"]);
                    $paquetes_empresa=PaqueteEmpresaDAO::search($paquete_empresa);
                    foreach($paquetes_empresa as $p_e)
                    {
                        $this->InsertarIdEmpresa($p_e, $id_empresas, $d_p["precio"]*$d_p["cantidad"]);
                    }
                }
            }
            if($detalle_orden!=null)
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
                        $this->InsertarIdEmpresa($s_e, $id_empresas, $orden["precio"]);
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
                    $this->InsertarIdEmpresa($objeto, $id_empresas);
                }
            }
            return $id_empresas;
        }

        private function InsertarIdEmpresa
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

        private function DescontarDeAlmacenes
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
                if($almacen->getIdTipoAlmacen()==self::$almacen_consignacion)
                    continue;
                $producto_almacen=ProductoAlmacenDAO::getByPK($detalle_producto->getIdProducto(), $almacen->getIdAlmacen(), $detalle_producto->getIdUnidad());
                if($producto_almacen!=null)
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
            if($unidad==null)
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
                $productos_almacen=$this->OrdenarProductosAlmacen($productos_almacen);
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

        public function OrdenarProductosAlmacen
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
	public function ComprarCaja
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
            if($id_usuario==null)
            {
                Logger::error("No se pudo obtener al usuario de la sesion actual, ya inicio sesion?");
                throw new Exception("No se pudo obtener al usuario de la sesion actual, ya inicio sesion?");
            }
            $usuario=UsuarioDAO::getByPK($id_vendedor);
            if($usuario==null)
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
            $compra->setIdCaja($this->getCaja());
            $compra->setIdSucursal($this->getSucursal());
            $compra->setIdUsuario($id_usuario);
            $compra->setIdCompraCaja($id_compra_caja);
            $compra->setCancelada(0);
            $compra->setTipoDePago($tipo_pago);
            $compra->setFecha(date($this->formato_fecha,time()));
            $compra->setIdEmpresa($id_empresa);
            DAO::transBegin();
            try
            {
                if($tipo_compra==="contado")
                {
                    if($tipo_pago==="cheque"&&$cheques==null)
                    {
                        throw new Exception("El tipo de pago es con cheque pero no se recibio informacion del mismo");
                    }
                    if($saldo!==null)
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
                        if($billetes_cambio!=null)
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
                    if($saldo==null)
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
                if($detalle!=null)
                {
                    $d_producto=new CompraProducto();
                    $d_producto->setIdCompra($compra->getIdCompra());
                    $almacenes=AlmacenDAO::search(new Almacen(array("id_sucursal" => $this->getSucursal(), "id_empresa" => $id_empresa)));
                    $id_almacen=null;
                    foreach($almacenes as $a)
                    {
                        if($a->getIdTipoAlmacen()==self::$almacen_consignacion)
                                continue;
                        $id_almacen=$a->getIdAlmacen();
                    }
                    if($id_almacen==null)
                    {
                        throw new Exception("No existe un almacen para esta empresa en esta sucursal");
                    }
                    foreach($detalle as $d_p)
                    {
                        $producto=ProductoDAO::getByPK($d_p["id_producto"]);
                        if($producto==null)
                        {
                            throw new Exception("El producto con id: ".$d_p["id_producto"]." no existe");
                        }
                        if(UnidadDAO::getByPk($d_p["id_unidad"])==null)
                        {
                            throw new Exception("La unidad con id: ".$d_p["id_unidad"]." no existe");
                        }
                        if(!$producto->getCompraEnMostrador())
                        {
                            throw new Exception("No se puede comprar el producto con id ".$d_p["id_producto"]." en mostrador");
                        }
                        if(ProductoEmpresaDAO::getByPK($d_p["id_producto"], $id_empresa)==null)
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
                        if($producto_almacen==null)
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
	public function Lista
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
                    $activo !== null ||
                    $id_empresa != null ||
                    $saldo_inferior_que !== null ||
                    $saldo_superior_que !== null ||
                    $fecha_apertura_inferior_que != null || 
                    $fecha_apertura_superior_que != null
            )
                $parametros=true;
            $sucursales=array();
            $sucursales1=array();
            if($parametros)
            {
                Logger::log("se recibieron parametros, se listan las sucursales en rango");
                if($id_empresa!=null)
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
                if($saldo_superior_que!==null)
                {
                    $sucursal_criterio1->setSaldoAFavor($saldo_superior_que);
                    if($saldo_inferior_que!==null)
                        $sucursal_criterio2->setSaldoAFavor($saldo_inferior_que);
                    else
                        $sucursal_criterio2->setSaldoAFavor(1.8e100);
                }
                else if($saldo_inferior_que!==null)
                {
                    $sucursal_criterio1->setSaldoAFavor($saldo_inferior_que);
                    $sucursal_criterio2->setSaldoAFavor(0);
                }
                if($fecha_apertura_superior_que!=null)
                {
                    $sucursal_criterio1->setFechaApertura($fecha_apertura_superior_que);
                    if($fecha_apertura_inferior_que!=null)
                        $sucursal_criterio2->setFechaApertura($fecha_apertura_inferior_que);
                    else
                        $sucursal_criterio2->setFechaApertura(date($this->formato_fecha,time()));
                }
                else if($fecha_apertura_inferior_que!=null)
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
	public function AbrirCaja
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
            if($caja==null)
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
                return;
            }
            $apertura_caja=new AperturaCaja();
            $apertura_caja->setIdCaja($id_caja);
            $apertura_caja->setFecha(date($this->formato_fecha,time()));
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
                    $billete_apertura_caja=new BilleteAperturaCaja(array( "id_apertura_caja" => $apertura_caja->getIdAperturaCaja()));
                    foreach($billetes as $billete)
                    {
                        $billete_apertura_caja->setIdBillete($billete["id_billete"]);
                        $billete_apertura_caja->setCantidad($billete["cantidad"]);
                        BilleteAperturaCajaDAO::save($billete_apertura_caja);
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
	public function Nueva
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
            if(CiudadDAO::getByPK($id_ciudad)==null)
            {
                Logger::error("La ciudad con id: ".$id_ciudad." no existe");
            }
            $sucursal->setSaldoAFavor($saldo_a_favor);
            $sucursal->setIdGerente($id_gerente);
            $sucursal->setMargenUtilidad($margen_utilidad);
            $sucursal->setDescripcion($descripcion);
            $sucursal->setDescuento($descuento);
            $sucursal->setFechaApertura($this->formato_fecha,time());
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
                if($impuestos!=null)
                {
                    $impuesto=new ImpuestoSucursal(array( "id_sucursal" => $sucursal->getIdSucursal()));
                    foreach($impuestos as $i)
                    {
                        if(ImpuestoDAO::getByPK($i)==null)
                        {
                            throw new Exception("El impuesto con id: ".$i." no existe");
                        }
                        $impuesto->setIdImpuesto($i);
                        ImpuestoSucursalDAO::save($impuesto);
                    }
                }
                if($retenciones!=null)
                {
                    $retencion= new RetencionSucursal(array( "id_sucursal" => $sucursal->getIdSucursal()));
                    foreach($retenciones as $r)
                    {
                        if(RetencionDAO::getByPK($r)==null)
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
	public function Editar
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
            $sucursal=SucursalDAO::getByPK($id_sucursal);
            $cambio_direccion=false;
            if($sucursal==null)
            {
                Logger::error("La sucursal con id: ".$id_sucursal." no existe");
                throw new Exception("La sucursal con id: ".$id_sucursal." no existe");
            }
            $direccion=DireccionDAO::getByPK($sucursal->getIdDireccion());
            if($direccion==null)
            {
                Logger::error("FATAL!!! La sucursal no cuenta con una direccion");
                throw new Exception("FATAL!!! La sucursal no cuenta con una direccion");
            }
            if($descuento!==null)
            {
                $sucursal->setDescuento($descuento);
            }
            if($margen_utilidad!==null)
            {
                $sucursal->setMargenUtilidad($margen_utilidad);
            }
            if($descripcion!=null)
            {
                $sucursal->setDescripcion($descripcion);
            }
            if($telefono1!=null)
            {
                $cambio_direccion=true;
                $direccion->setTelefono($telefono1);
            }
            if($telefono2!=null)
            {
                $cambio_direccion=true;
                $direccion->setTelefono2($telefono2);
            }
            if($numero_exterior!=null)
            {
                $cambio_direccion=true;
                $direccion->setNumeroExterior($numero_exterior);
            }
            if($razon_social!=null)
            {
                $sucursal->setRazonSocial($razon_social);
            }
            if($id_gerente!=null)
            {
                $sucursal->setIdGerente($id_gerente);
            }
            if($municipio!=null)
            {
                $cambio_direccion=true;
                $direccion->setIdCiudad($municipio);
            }
            if($rfc!=null)
            {
                $sucursal->setRfc($rfc);
            }
            if($saldo_a_favor!=null)
            {
                $sucursal->setSaldoAFavor($saldo_a_favor);
            }
            if($numero_interior!=null)
            {
                $cambio_direccion=true;
                $direccion->setNumeroInterior($numero_interior);
            }
            if($colonia!=null)
            {
                $cambio_direccion=true;
                $direccion->setColonia($colonia);
            }
            if($calle!=null)
            {
                $cambio_direccion=true;
                $direccion->setCalle($calle);
            }
            if($coidgo_postal!=null)
            {
                $cambio_direccion=true;
                $direccion->setCodigoPostal($coidgo_postal);
            }
            if($cambio_direccion)
            {
                $direccion->setUltimaModificacion(date($this->formato_fecha,time()));
                $id_usuario=LoginController::getCurrentUser();
                if($id_usuario==null)
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
                if($empresas!=null)
                {
                    foreach($empresas as $empresa)
                    {
                        if(EmpresaDAO::getByPK($empresa["id_empresa"])==null)
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
                if($impuestos!=null)
                {
                    foreach($impuestos as $impuesto)
                    {
                        if(ImpuestoDAO::getByPK($impuesto)==null)
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
                if($retenciones!=null)
                {
                    foreach($retenciones as $retencion)
                    {
                        if(RetencionDAO::getByPK($retencion)==null)
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
	public function EditarGerencia
	(
		$id_sucursal, 
		$id_gerente
	)
	{  
  
  
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
	public function CerrarCaja
	(
		$saldo_real, 
		$billetes, 
		$id_cajero = null
	)
	{  
  
  
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
	public function EntradaAlmacen
	(
		$productos, 
		$id_almacen, 
		$motivo = null
	)
	{  
  
  
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
	public function NuevaCaja
	(
		$token, 
		$codigo_caja = null, 
		$impresoras = null, 
		$basculas = null, 
		$descripcion = null
	)
	{  
  
  
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
	public function SalidaAlmacen
	(
		$productos, 
		$id_almacen, 
		$motivo = null
	)
	{  
  
  
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
	public function CorteCaja
	(
		$saldo_final, 
		$id_caja, 
		$saldo_real, 
		$billetes_encontrados, 
		$billetes_dejados, 
		$id_cajero = null, 
		$id_cajero_nuevo = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Descativa un almacen. Para poder desactivar un almacen, este tiene que estar vac?
 	 *
 	 * @param id_almacen int Id del almacen a desactivar
 	 **/
	public function EliminarAlmacen
	(
		$id_almacen
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Edita la informacion de un almacen
 	 *
 	 * @param id_almacen int Id del almacen a editar
 	 * @param descripcion string Descripcion del almacen
 	 * @param nombre string Nombre del almacen
 	 **/
	public function EditarAlmacen
	(
		$id_almacen, 
		$descripcion = null, 
		$nombre = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Calcula el saldo esperado para una caja a partir de los cortes que le han realizado, la fecha de apertura y la fecha en que se realiza el calculo. La caja sera tomada de la sesion, la fecha sera tomada del servidor. Para poder realizar este metodo, la caja tiene que estar abierta
 	 *
 	 * @param id_caja int Id de la caja de la cual se hara el calculo.
 	 * @return saldo_esperado float Saldo esperado de la caja para la fecha actual
 	 **/
	public function Calcular_saldo_esperadoCaja
	(
		$id_caja
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Edita la informacion de una caja
 	 *
 	 * @param id_caja int Id de la caja a editar
 	 * @param descripcion string Descripcion de la caja
 	 * @param token string Token generado por el pos client
 	 **/
	public function EditarCaja
	(
		$id_caja, 
		$descripcion = null, 
		$token = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Desactiva una caja, para que la caja pueda ser desactivada, tieneq ue estar cerrada
 	 *
 	 * @param id_caja int Id de la caja a eliminar
 	 **/
	public function EliminarCaja
	(
		$id_caja
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Desactiva una sucursal. Para poder desactivar una sucursal su saldo a favor tiene que ser mayor a cero y sus almacenes tienen que estar vacios.
 	 *
 	 * @param id_sucursal int Id de la sucursal a desactivar
 	 **/
	public function Eliminar
	(
		$id_sucursal
	)
	{  
  
  
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
	public function ProgramarTraspasoAlmacen
	(
		$id_almacen_recibe, 
		$id_almacen_envia, 
		$fecha_envio_programada, 
		$productos
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Cambia el estado del traspaso a enviado y captura la fecha de envio del servidor. El usuario que envia sera tomado del servidor.
 	 *
 	 * @param id_traspaso int Id del traspaso a enviar
 	 **/
	public function EnviarTraspasoAlmacen
	(
		$id_traspaso
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Cambia el estado de un traspaso a recibido. La  bandera de completo se prende si los productos enviados son los mismos que los recibidos. La fecha de recibo es tomada del servidor. El usuario que recibe sera tomada de la sesion actual.
 	 *
 	 * @param productos json Productos que se reciben con sus cantidades
 	 * @param id_traspaso int Id del traspaso que se recibe
 	 **/
	public function RecibirTraspasoAlmacen
	(
		$productos, 
		$id_traspaso
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Para poder cancelar un traspaso, este no tuvo que haber sido enviado aun.
 	 *
 	 * @param id_traspaso int Id del traspaso a cancelar
 	 **/
	public function CancelarTraspasoAlmacen
	(
		$id_traspaso
	)
	{  
  
  
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
	public function ListaTraspasoAlmacen
	(
		$cancelado = null, 
		$completo = null, 
		$id_producto = null, 
		$id_almacen = null, 
		$enviados = null, 
		$id_sucursal = null, 
		$id_empresa = null, 
		$estado = null, 
		$ordenar = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Para poder editar un traspaso,este no tuvo que haber sido enviado aun
 	 *
 	 * @param id_traspaso int Id del traspaso a editar
 	 * @param productos json Productos a enviar con sus cantidades
 	 * @param fecha_envio_programada string Fecha de envio programada
 	 **/
	public function EditarTraspasoAlmacen
	(
		$id_traspaso, 
		$productos = null, 
		$fecha_envio_programada = null
	)
	{  
  
  
	}
  }
