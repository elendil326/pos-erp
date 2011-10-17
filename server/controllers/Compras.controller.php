<?php
require_once("interfaces/Compras.interface.php");
/**
  *
  *
  *
  **/
	
  class ComprasController implements ICompras{
  
        private $formato_fecha="Y-m-d H:i:s";
	/**
 	 *
 	 *Lista las compras. Se puede filtrar por empresa, sucursal, caja, usuario que registra la compra, usuario al que se le compra, tipo de compra, si estan pagadas o no, por tipo de pago, canceladas o no, por el total, por fecha, por el tipo de pago y se puede ordenar por sus atributos.
 	 *
 	 * @param fecha_inicial string Se listaran las compras cuya fecha sea mayor a la indicada aqui
 	 * @param tipo_compra string Si es a credito, se listaran las compras que sean a credito, si es de contado, se listaran las compras de contado
 	 * @param id_usuario_compra int Se listaran las compras realizadas por este usuario, si es sucursal su id sera negativo
 	 * @param id_caja int Se listaran las compras registradas en esta caja
 	 * @param id_usuario int Se listaran las compras que ha registrado este usuario
 	 * @param id_empresa int Id de la empresa de la cual se listaran sus compras
 	 * @param id_sucursal int Id de la sucursal de la cual se listaran sus compras
 	 * @param fecha_final string Se listaran las compras cuya fecha sea menor a la indicada aqui
 	 * @param total_minimo float Se listaran las compras cuyo total sea mayor a este
 	 * @param total_maximo float Se listaran las compras cuyo total sea menor a este
 	 * @param saldada bool Si este valor no es obtenido, se listaran las compras tanto saldadas como las que no lo estan. Si es verdadero se listaran solo las compras saldadas, si es falso, se listaran solo las compras que no lo estan
 	 * @param cancelada bool Si este valor no es obtenido, se listaran tanto compras canceladas como no canceladas. Si es verdadero, se listaran solo las compras canceladas, si su valor es falso, se listaran solo las compras que no lo esten
 	 * @param tipo_pago string Se listaran las compras pagadas con cheque, tarjeta o efectivo de acuerdo a este valor
 	 * @return compras json Objeto que contendra la lista de las compras
 	 **/
	public function Lista
	(
		$fecha_inicial = null, 
		$tipo_compra = null, 
		$id_vendedor_compra = null,
		$id_caja = null, 
		$id_usuario = null, 
		$id_empresa = null, 
		$id_sucursal = null, 
		$fecha_final = null, 
		$total_minimo = null, 
		$total_maximo = null, 
		$saldada = null, 
		$cancelada = null, 
		$tipo_pago = null,
                $orden = null
	)
	{  
            Logger::log("Listando compras");
            $parametros=false;
            if
            (
                $fecha_inicial != null ||
		$tipo_compra != null ||
		$id_vendedor_compra != null ||
		$id_caja != null ||
		$id_usuario != null ||
		$id_empresa != null ||
		$id_sucursal != null ||
		$fecha_final != null ||
		$total_minimo != null ||
		$total_maximo != null ||
		$cancelada != null ||
		$tipo_pago != null
            )
                $parametros = true;
            $compras=null;
            if($parametros)
            {
                Logger::log("Se recibieron parametros, se listan las compras en rango");
                $compra_criterio_1 = new Compra();
                $compra_criterio_2 = new Compra();
                $compra_criterio_1->setTipoDeCompra($tipo_compra);
                $compra_criterio_1->setIdVendedorCompra($id_vendedor_compra);
                $compra_criterio_1->setIdCaja($id_caja);
                $compra_criterio_1->setIdUsuario($id_usuario);
                $compra_criterio_1->setIdEmpresa($id_empresa);
                $compra_criterio_1->setIdSucursal($id_sucursal);
                $compra_criterio_1->setCancelada($cancelada);
                $compra_criterio_1->setTipoDePago($tipo_pago);
                if($fecha_inicial!=null)
                {
                    $compra_criterio_1->setFecha($fecha_inicial);
                    if($fecha_final!=null)
                    {
                        $compra_criterio_2->setFecha($fecha_final);
                    }
                    else
                    {
                        $compra_criterio_2->setFecha(date($this->formato_fecha,time()));
                    }
                }
                else if($fecha_final!=null)
                {
                    $compra_criterio_1->setFecha($fecha_final);
                    $compra_criterio_2->setFecha("1001-01-01 00:00:00");
                }
                if($total_minimo!==null)
                {
                    $compra_criterio_1->setTotal($total_minimo);
                    if($total_maximo!==null)
                        $compra_criterio_2->setTotal($total_maximo);
                    else
                        $compra_criterio_2->setTotal(1.8e100);
                }
                else if($total_maximo!==null)
                {
                    $compra_criterio_1->setTotal($total_maximo);
                    $compra_criterio_2->setTotal(0);
                }
                $compras=CompraDAO::byRange($compra_criterio_1, $compra_criterio_2,$orden);
            }
            else
            {
                Logger::log("No se recibieron parametros, se listan todas las compras");
                $compras=CompraDAO::getAll(null,null,$orden);
            }
            if($saldada!==null)
            {
                $temporal=array();
                if($saldada)
                    foreach($compras as $compra)
                    {
                        if($compra->getTotal()<=$compra->getSaldo())
                            array_push($temporal, $compra);
                    }
                else
                    foreach($compras as $compra)
                    {
                        if($compra->getTotal()>$compra->getSaldo())
                            array_push($temporal, $compra);
                    }
                $compras=$temporal;
            }
            Logger::log("Se obtuvo la lista con éxito");
            return $compras;
	}
  
	/**
 	 *
 	 *Registra una nueva compra por arpillas. Este metodo tiene que usarse en conjunto con el metodo api/compras/nueva
Update : Todo este metodo esta mal, habria que definir nuevamente como se van a manejar las compras a los proveedores ya que como esta definido aqui solo funcionaria para el POS de las papas.
 	 *
 	 * @param peso_por_arpilla float peso por arpilla
 	 * @param arpillas float numero de arpillas recibidas
 	 * @param peso_recibido float peso que se recibi en kg
 	 * @param id_compra int Id de la compra a la que pertenece esta compra por arpilla
 	 * @param total_origen float Lo que vale el embarque segun el proveedor
 	 * @param merma_por_arpilla float Merma por arpilla
 	 * @param numero_de_viaje string numero de viaje
 	 * @param folio string folio de la remision
 	 * @param peso_origen float Peso del embarque en el origen.
 	 * @param fecha_origen string Fecha de cuando salio el embarque del proveedor
 	 * @param productor string Nombre del productor
 	 * @return id_compra_arpilla int ID autogenerado por la insercion
 	 **/
	public function Nueva_compra_arpilla
	(
		$peso_por_arpilla, 
		$arpillas, 
		$peso_recibido, 
		$id_compra, 
		$total_origen, 
		$merma_por_arpilla, 
		$numero_de_viaje = null, 
		$folio = null, 
		$peso_origen = null, 
		$fecha_origen = null, 
		$productor = null
	)
	{
            Logger::log("Registrando compras de arpillas");
            $compra_arpilla=new CompraArpilla();
            $compra_arpilla->setPesoPorArpilla($peso_por_arpilla);
            $compra_arpilla->setArpillas($arpillas);
            $compra_arpilla->setPesoRecibido($peso_recibido);
            if(CompraDAO::getByPK($id_compra)==null)
            {
                Logger::error("La compra con id: ".$id_compra." no existe");
                throw new Exception("La compra con id: ".$id_compra." no existe");
            }
            $compra_arpilla->setIdCompra($id_compra);
            $compra_arpilla->setTotalOrigen($total_origen);
            $compra_arpilla->setMermaPorArpilla($merma_por_arpilla);
            $compra_arpilla->setNumeroDeViaje($numero_de_viaje);
            $compra_arpilla->setFolio($folio);
            $compra_arpilla->setPesoOrigen($peso_origen);
            $compra_arpilla->setFechaOrigen($fecha_origen);
            $compra_arpilla->setProductor($productor);
            DAO::transBegin();
            try
            {
                CompraArpillaDAO::save($compra_arpilla);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo guardar la compra de arpillas");
                throw $e;
            }
            DAO::transEnd();
            Logger::log("Se registro la compra de arpillas con exito ");
            return $compra_arpilla->getIdCompraArpilla();
	}
  
	/**
 	 *
 	 *Cancela una compra
 	 *
 	 * @param id_compra int Id de la compra a cancelar
 	 **/
	public function Cancelar
	(
		$id_compra
	)
	{  
            $compra=CompraDAO::getByPK($id_compra);
            if($compra==null)
            {
                Logger::error("La compra con id: ".$id_compra." no existe");
                throw new Exception("La compra con id: ".$id_compra." no existe");
            }
            if($compra->getCancelada())
            {
                Logger::warn("La compra ya ha sido cancelada");
                return;
            }
            $usuario=UsuarioDAO::getByPK($compra->getIdVendedorCompra());
            if($usuario==null)
            {
                Logger::error("FATAL!!! Esta compra apunta a un usuario que no existe");
                throw new Exception("FATAL!!! Esta compra apunta a un usuario que no existe");
            }
            $compra->setCancelada(1);
            DAO::transBegin();
            try
            {
                CompraDAO::save($compra);
                if($compra->getTipoDeCompra()==="credito")
                {
                    $abono_compra=new AbonoCompra();
                    $abono_compra->setIdCompra($id_compra);
                    $abonos=AbonoCompraDAO::search($abono_compra);
                    foreach($abonos as $abono)
                    {
                        $cargosyabonos = new CargosYAbonosController();
                        $cargosyabonos->EliminarAbono($abono->getIdAbonoCompra(),"Compra cancelada",1,0,0,null,null);
                    }
                    $usuario->setSaldoDelEjercicio($usuario->getSaldoDelEjercicio()-$compra->getTotal());
                    UsuarioDAO::save($usuario);
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("Error al cancelar la compra: ".$e);
                throw $e;
            }
            DAO::transEnd();
            Logger::log("Compra cancelada exitosamente");
	}
  
	/**
 	 *
 	 *Muestra el detalle de una compra
 	 *
 	 * @param id_compra int Id de la compra a detallar
 	 * @return detalle_compra json Objeto que contendra los productos con sus cantidades de esa compra
 	 **/
	public function Detalle
	(
		$id_compra
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Muestra el detalle de una compra por arpillas. Este detalle no es el detalle por producto, este muestra los detalles por embarque de la compra. Para el detalle por producto refierase a api/compras/detalle

Update : Todo este metodo esta mal, habria que definir nuevamente como se van a manejar las compras a los proveedores ya que como esta definido aqui solo funcionaria para el POS de las papas.
 	 *
 	 * @param id_compra int Id de la compra de la que se detallaran las compras por arpilla
 	 * @return detalle_compra_arpilla json Objeto que contendr� la informaci�n del detalle de la compra
 	 **/
	public function Detalle_compra_arpilla
	(
		$id_compra
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Registra una nueva compra fuera de caja, puede usarse para que el administrador haga directamente una compra. El usuario y al sucursal seran tomados de la sesion. La fecha sera tomada del servidor. La empresa sera tomada del almacen del cual fueron tomados los productos.
 	 *
 	 * @param descuento float Monto descontado por descuentos
 	 * @param subtotal float Total de la compra antes de impuestos y descuentos.
 	 * @param detalle json Objeto que contendr el arreglo de id productos, cantidad,  precio, descuento, id de unidad y procesado que involucran esta compra.
 	 * @param impuesto float Monto agregado por impuestos
 	 * @param tipo_compra string Si la compra es a credito o de contado
 	 * @param retencion float Monto agregado por retenciones
 	 * @param id_usuario_compra int Id usuario al que se le compra, si es a una sucursal, se pone el id en negativo
 	 * @param id_empresa int Id de la empresa a nombre de la cual se hace la compra
 	 * @param total float Total de la compra
 	 * @param cheques json Si el tipo de pago es con cheque, se almacena el nombre del banco, el monto y los ultimos 4 numeros del o de los cheques
 	 * @param saldo float Cantidad pagada de la 
 	 * @param tipo_de_pago string Si el pago sera en efectivo, con cheque o tarjeta
 	 * @return id_compra int Id autogenerado por la inserci�n de la compra
 	 **/
	public function Nueva
	(
		$descuento, 
		$subtotal, 
		$detalle, 
		$impuesto, 
		$tipo_compra, 
		$retencion, 
		$id_usuario_compra, 
		$id_empresa, 
		$total, 
		$cheques = null, 
		$saldo = null, 
		$tipo_de_pago = null
	)
	{  
  
  
	}
  }
