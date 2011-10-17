<?php
require_once("CargosYAbonos.interface.php");
/**
  *
  *
  *
  **/

  class CargosYAbonosController implements ICargosYAbonos{

        //valida que una empresa exista y tenga su estado en activo
        private $formato_fecha="Y-m-d H:i:s";
        private function validarEmpresa
        (
                $id_empresa
        )
        {
            $empresa=EmpresaDAO::getByPK($id_empresa);
            if($empresa==null)
            {
                Logger::error("La empresa con id:".$id_empresa." no existe");
                return false;
            }
            $activo=$empresa->getActivo();
            if(!$activo)
            Logger::error("La empresa con id:".$id_empresa." no esta activa");
            return $activo;
        }
        private function getSucursal()
        {
            return 1;
        }
        private function getCaja()
        {
            return 1;
        }
	/**
 	 *
 	 *Registra un nuevo ingreso
 	 *
 	 * @param fecha_ingreso string Fecha del ingreso
 	 * @param id_empresa int Id de la empresa a la que pertenece este ingreso
 	 * @param monto float Monto del ingreso en caso de que no este contemplado por el concepto de ingreso o que sea diferente
 	 * @param id_sucursal int Id de la caja a la que pertenece este ingreso
 	 * @param id_concepto_ingreso int Id del concepto al que hace referencia el ingreso
 	 * @param id_caja int Id de la caja en la que se registra el ingreso
 	 * @param folio string Folio de la factura del ingreso
 	 * @param nota string Nota del ingreso
 	 * @param descripcion string Descripcion del ingreso en caso de no este contemplado en la lista de conceptos de ingreso
 	 * @return id_ingreso int Id autogenerado por la insercion del ingreso
 	 **/
	public function NuevoIngreso
	(
		$id_empresa,
		$fecha_ingreso,
		$descripcion = null,
		$nota = null,
		$folio = null,
		$id_sucursal = null,
		$id_concepto_ingreso = null,
		$id_caja = null,
		$monto = null, 
		$billetes = null
	)
	{
            Logger::log("Creando nuevo ingreso");
            $id_usuario=LoginController::getCurrentUser();
            if($id_usuario==null)
            {
                Logger::error("No se pudo obtener el usuario de la sesion, ya inicio sesion?");
                throw new Exception("No se pudo obtener el usuario de la sesion, ya inicio sesion?");
            }
            if(!$this->validarEmpresa($id_empresa))
            {
                throw new Exception("Se recibio una empresa no valida");
            }
            if($id_concepto_ingreso!=null)
            {
                $concepto_ingreso=ConceptoIngresoDAO::getByPK($id_concepto_ingreso);
                if($concepto_ingreso==null)
                {
                    Logger::error("El concepto de ingreso con id:".$id_concepto_ingreso." no existe");
                    throw new Exception("El concepto de ingreso con id:".$id_concepto_ingreso." no existe");
                }
            }
            if($monto===null)
            {
                Logger::log("No se recibio monto, se procede a buscar en el concepto de ingreso");
                if($id_concepto_ingreso==null)
                {
                    Logger::error("No se recibio un concepto de ingreso");
                    throw new Exception("No se recibio un concepto de ingreso ni un monto");
                }
                $monto=$concepto_ingreso->getMonto();
                if($monto===null)
                {
                    Logger::error("El concepto de ingreso recibido no cuenta con un monto");
                    throw new Exception("El concepto de ingreso recibido no cuenta con un monto ni se recibio un monto");
                }
            }
            if(!$id_sucursal)
                $id_sucursal=$this->getSucursal();
            if(!$id_caja)
                $id_caja=$this->getCaja();
            if($id_caja!=null)
            {
                try
                {
                    $this->modificarCaja($id_caja, 1,$billetes,$monto);
                }
                catch(Exception $e)
                {
                    throw $e;
                }
            }
            $ingreso=new Ingreso();
            $ingreso->setCancelado(0);
            $ingreso->setDescripcion($descripcion);
            $ingreso->setFechaDelIngreso($fecha_ingreso);
            $ingreso->setFolio($folio);
            $ingreso->setIdCaja($id_caja);
            $ingreso->setIdConceptoIngreso($id_concepto_ingreso);
            $ingreso->setIdEmpresa($id_empresa);
            $ingreso->setIdSucursal($id_sucursal);
            $ingreso->setIdUsuario($id_usuario);
            $ingreso->setMonto($monto);
            $ingreso->setNota($nota);
            $ingreso->setFechaDeRegistro(date($this->formato_fecha, time()));
            DAO::transBegin();
            try
            {
                IngresoDAO::save($ingreso);
            }
            catch(Exception $e)
            {
                Logger::error("Error al guardar el nuevo ingreso:".$e);
                DAO::transRollback();
                throw new Exception("Error al guardar el nuevo ingreso:".$e);
            }
            DAO::transEnd();
            Logger::log("Ingreso creado exitosamente!");
            //PARA PROBAR
            //printf('{ "success" : true , "id_ingreso" : %d }',$ingreso->getIdIngreso());
            //
            return $ingreso->getIdIngreso();
	}

	/**
 	 *
 	 *Cancela un abono
 	 *
 	 * @param id_abono int Id del abono a cancelar
 	 * @param motivo_cancelacion string Motivo por el cual se realiza la cancelacion
 	 **/
	public function EliminarAbono
	(
		$id_abono,
		$motivo_cancelacion = null,
                $compra = null,
                $venta = null,
                $prestamo = null,
                $id_caja = null,
                $billetes = null
	)
	{
            Logger::log("Cancelando abono");
            if($compra)
            {
                $abono=AbonoCompraDAO::getByPK($id_abono);
                if($abono==null)
                {
                    Logger::error("El abono para compra con id:".$id_abono." no existe");
                    throw new Exception("El abono para compra con id:".$id_abono." no existe");
                }
                if($abono->getCancelado())
                {
                    Logger::log("El abono ya ha sido cancelado antes");
                    return;
                }
                $abono->setCancelado(1);
                $abono->setMotivoCancelacion($motivo_cancelacion);
                if(!$id_caja)
                    $id_caja=$this->getCaja();
                DAO::transBegin();
                try {
                    AbonoCompraDAO::save($abono);
                    $this->cancelarAbonoCompra($abono,$id_caja,$billetes);
                }
                catch(Exception $e)
                {
                    DAO::transRollback();
                    throw new Exception("Error al cancelar el abono:".$e);
                }
                DAO::transEnd();
                Logger::log("Abono cancelado exitosamente");
            }
            else if($venta)
            {
                $abono=AbonoVentaDAO::getByPK($id_abono);
                if($abono==null)
                {
                    Logger::error("El abono para venta con id:".$id_abono." no existe");
                    throw new Exception("El abono para venta con id:".$id_abono." no existe");
                }
                if($abono->getCancelado())
                {
                    Logger::log("El abono ya ha sido cancelado antes");
                    return;
                }
                $abono->setCancelado(1);
                $abono->setMotivoCancelacion($motivo_cancelacion);
                DAO::transBegin();
                try {
                    AbonoVentaDAO::save($abono);
                    $this->cancelarAbonoVenta($abono,$id_caja,$billetes);
                }
                catch(Exception $e)
                {
                    DAO::transRollback();
                    throw new Exception("Error al cancelar el abono:".$e);
                }
                DAO::transEnd();
                Logger::log("Abono cancelado exitosamente");
            }
            else if($prestamo)
            {
                $abono=AbonoPrestamoDAO::getByPK($id_abono);
                if($abono==null)
                {
                    Logger::error("El abono para prestamo con id:".$id_abono." no existe");
                    throw new Exception("El abono para prestamo con id:".$id_abono." no existe");
                }
                if($abono->getCancelado())
                {
                    Logger::log("El abono ya ha sido cancelado antes");
                    return;
                }
                $abono->setCancelado(1);
                $abono->setMotivoCancelacion($motivo_cancelacion);
                DAO::transBegin();
                try {
                    AbonoPrestamoDAO::save($abono);
                    $this->cancelarAbonoPrestamo($abono,$id_caja,$billetes);
                }
                catch(Exception $e)
                {
                    DAO::transRollback();
                    throw new Exception("Error al cancelar el abono:".$e);
                }
                DAO::transEnd();
                Logger::log("Abono cancelado exitosamente");
            }
            else
            {
                Logger::error("No se recibio el parametro compra, venta ni prestamo, no se sabe que abono cancelar");
                throw new Exception("No se recibio el parametro compra, venta ni prestamo, no se sabe que abono cancelar");
            }
	}

        private function cancelarAbonoCompra
        (
                AbonoCompra $abono,
                $id_caja,
                $billetes
        )
        {
            $compra=CompraDAO::getByPK($abono->getIdCompra());
            if($compra==null)
            {
                Logger::error("FATAL!!!! Este abono apunta a una compra que no existe!!");
                throw new Exception("FATAL!!!! Este abono apunta a una compra que no existe!!");
            }
            $usuario=UsuarioDAO::getByPK($compra->getIdVendedorCompra());
            if($usuario==null)
            {
                Logger::error("FATAL!!!! La compra de este abono no tiene un vendedor");
                throw new Exception("FATAL!!!! La compra de este abono no tiene un vendedor");
            }
            $monto=$abono->getMonto();
            $usuario->setSaldoDelEjercicio($usuario->getSaldoDelEjercicio()+$monto);
            $compra->setSaldo($compra->getSaldo()-$monto);

            //
            //Si la compra ha sido cancelada, quiere decir que se cancelaran todos los abonos y
            //ese dinero solo se quedara a cuenta del usuario.
            //
            //Si no ha sido cancelada, quiere decir que solo se cancela este abono y por ende el
            //dinero regresa a la caja que indica el usuario.
            //
            //Si no hay una caja, se tomara el dinero como perdido.
            if(!$compra->getCancelada()&&$id_caja!=null)
            {
                try
                {
                    $this->modificarCaja($id_caja, 1,$billetes,$monto);
                }
                catch(Exception $e)
                {
                    throw $e;
                }
            }
            if(!$compra->getCancelada())
            {
                try
                {
                    $this->eliminarCheques($abono->getIdAbonoCompra(),1,null,null);
                }
                catch(Exception $e)
                {
                    throw $e;
                }
            }
            //
            //Si no ha ocurrido ningun error con los billetes o con la caja, actualizas
            //al usuario y a la compra
            //
            DAO::transBegin();
            try
            {
                UsuarioDAO::save($usuario);
                CompraDAO::save($compra);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se ha podido actualizar al usuario ni la compra");
                throw $e;
            }
            DAO::transEnd();
        }

        private function cancelarAbonoVenta
        (
                AbonoVenta $abono,
                $id_caja,
                $billetes
        )
        {
            $venta=VentaDAO::getByPK($abono->getIdVenta());
            if($venta==null)
            {
                Logger::error("FATAL!!!! Este abono apunta a una venta que no existe!!");
                throw new Exception("FATAL!!!! Este abono apunta a una venta que no existe!!");
            }
            $usuario=UsuarioDAO::getByPK($venta->getIdCompradorVenta());
            if($usuario==null)
            {
                Logger::error("FATAL!!!! La venta de este abono no tiene un comprador");
                throw new Exception("FATAL!!!! La venta de este abono no tiene un comprador");
            }
            $monto=$abono->getMonto();
            $usuario->setSaldoDelEjercicio($usuario->getSaldoDelEjercicio()-$monto);
            $venta->setSaldo($venta->getSaldo()-$monto);
            //
            //Si la venta ha sido cancelada, quiere decir que se cancelaran todos los abonos y
            //ese dinero solo se quedara a cuenta del usuario.
            //
            //Si no ha sido cancelada, quiere decir que solo se cancela este abono y por ende el
            //dinero sale de la caja que indica el usuario.
            //
            //Si no hay una caja, se tomara el dinero como ganado.
            if(!$venta->getCancelada()&&$id_caja!=null)
            {
                try
                {
                    $this->modificarCaja($id_caja, 0,$billetes,$monto);
                }
                catch(Exception $e)
                {
                    throw $e;
                }
            }
            if(!$venta->getCancelada())
            {
                try
                {
                    $this->eliminarCheques($abono->getIdAbonoVenta(),null,1,null);
                }
                catch(Exception $e)
                {
                    throw $e;
                }
            }
            //
            //Si no ha ocurrido ningun error con los billetes o con la caja, actualizas
            //al usuario y a la compra
            //
            DAO::transBegin();
            try
            {
                UsuarioDAO::save($usuario);
                VentaDAO::save($venta);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se ha podido actualizar al usuario ni la venta");
                throw $e;
            }
            DAO::transEnd();
        }

        private function cancelarAbonoPrestamo
        (
                AbonoPrestamo $abono,
                $id_caja,
                $billetes
        )
        {
            $prestamo=PrestamoDAO::getByPK($abono->getIdPrestamo());
            if($prestamo==null)
            {
                Logger::error("FATAL!!!! Este abono apunta a un prestamo que no existe!!");
                throw new Exception("FATAL!!!! Este abono apunta a un prestamo que no existe!!");
            }
            //
            //Los solicitantes pueden ser positivos o negativos.
            //Si son positivos, son usuarios, si son negativos, son sucursales.
            //
            //Cuando un usuario cancela su abono a un prestamo, se incrementa su deuda.
            //
            //Cuando una sucursal cancela su abono, solo se afecta el prestamo, pues
            //la cuenta de la sucursal se calcula a partir de todos sus movimientos
            //y es en el prestamo donde se vera reflejado el cambio.
            //
            $id_solicitante=$prestamo->getIdSolicitante();
            if($id_solicitante>0)
                $solicitante=UsuarioDAO::getByPK($id_solicitante);
            else
                $solicitante=SucursalDAO::getByPK($id_solicitante*-1);
            if($solicitante==null)
            {
                Logger::error("FATAL!!!! El prestamo de este abono no tiene un solicitante");
                throw new Exception("FATAL!!!! El prestamo de este abono no tiene un solicitante");
            }
            $monto=$abono->getMonto();
            if($id_solicitante>0)
                $solicitante->setSaldoDelEjercicio($solicitante->getSaldoDelEjercicio()-$monto);
            $prestamo->setSaldo($prestamo->getSaldo()-$monto);
            //
            //Un Prestamo no puede ser cancelado, solo liquidado.
            //
            //Si no hay una caja, se tomara el dinero como ganado.
            //
            if($id_caja!=null)
            {
                try
                {
                    $this->modificarCaja($id_caja, 0,$billetes,$monto);
                }
                catch(Exception $e)
                {
                    throw $e;
                }
            }
            try
            {
                $this->eliminarCheques($abono->getIdAbonoPrestamo(),null,null,1);
            }
            catch(Exception $e)
            {
                throw $e;
            }
            //
            //Si no ha ocurrido ningun error con los billetes o con la caja, actualizas
            //al usuario y a la compra
            //
            DAO::transBegin();
            try
            {
                if($id_solicitante>0)
                    UsuarioDAO::save($solicitante);
                PrestamoDAO::save($prestamo);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se ha podido actualizar al usuario ni al prestamo");
                throw $e;
            }
            DAO::transEnd();
        }

        private function modificarCaja
        (
                $id_caja,
                $suma,
                $billetes,
                $monto
        )
        {
            $caja=CajaDAO::getByPK($id_caja);
            if($caja==null)
            {
                Logger::error("La caja especificada no existe");
                throw new Exception("La caja especificada no existe");
            }
            if(!$caja->getAbierta())
            {
                Logger::error("La caja especificada esta cerrada, tiene que abrirla para realizar movimientos");
                throw new Exception("La caja especificada esta cerrada, tiene que abrirla para realizar movimientos");
            }
            //
            //Si se esta llevando control de lo billetes en la caja
            //tienen que haber pasado en un arreglo bidimensional los ids
            //de los billetes con sus cantidades.
            //
            if($caja->getControlBilletes())
            {
                if($billetes==null)
                {
                    Logger::error("No se recibieron los billetes para esta caja");
                    throw new Exception("No se recibieron los billetes para esta caja");
                }
                $numero_billetes=count($billetes);
                //
                //Inicializas el arreglo de billetes_caja con los billetes que recibes
                //para despues insertarlo o actualizarlo.
                //
                for($i=0;$i<$numero_billetes; $i++)
                {
                    $billete_caja[$i]=new BilleteCaja();
                    $billete_caja[$i]->setIdBillete($billetes[$i]["id_billete"]);
                    $billete_caja[$i]->setIdCaja($id_caja);
                    $billete_caja[$i]->setCantidad($billetes[$i]["cantidad"]);
                }
                //
                //Intentas insertar cada billete con su cantidad, si ese billete ya existe
                //en esa caja, actulizas su cantidad.
                //
                DAO::transBegin();
                try
                {
                    for($i=0;$i<$numero_billetes;$i++)
                    {
                        $billete_caja_original=BilleteCajaDAO::getByPK($billete_caja[$i]->getIdBillete(), $billete_caja[$i]->getIdCaja());
                        if($billete_caja_original==null)
                        {
                            if(!$suma)
                                $billete_caja[$i]->setCantidad($billete_caja[$i]->getCantidad()*-1);
                            BilleteCajaDAO::save($billete_caja[$i]);
                        }
                        else
                        {
                            if($suma)
                                $billete_caja_original->setCantidad($billete_caja_original->getCantidad()+$billete_caja[$i]->getCantidad());
                            else
                                $billete_caja_original->setCantidad($billete_caja_original->getCantidad()-$billete_caja[$i]->getCantidad());
                            BilleteCajaDAO::save($billete_caja_original);
                        }
                    }
                }
                catch(Exception $e)
                {
                    DAO::transRollback();
                    Logger::error("No se pudieron actualizar los billetes de la caja");
                    throw $e;
                }
                DAO::transEnd();
            }
            //
            //Actualizas el saldo de la caja
            //
            if($suma)
                $caja->setSaldo($caja->getSaldo()+$monto);
            else
                $caja->setSaldo($caja->getSaldo()-$monto);
            DAO::transBegin();
            try
            {
                CajaDAO::save($caja);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo actualizar el saldo de la caja");
                throw $e;
            }
            DAO::transEnd();
        }

        private function eliminarCheques
        (
                $id_abono,
                $compra=null,
                $venta=null,
                $prestamo=null
        )
        {
            $resultados=null;
            $from=0;
            if($compra)
            {
                $cheque_abono_compra=new ChequeAbonoCompra();
                $cheque_abono_compra->setIdAbonoCompra($id_abono);
                $resultados=ChequeAbonoCompraDAO::search($cheque_abono_compra);
                $from=1;
            }
            else if($venta)
            {
                $cheque_abono_venta=new ChequeAbonoVenta();
                $cheque_abono_venta->setIdAbonoVenta($id_abono);
                $resultados=ChequeAbonoVentaDAO::search($cheque_abono_venta);
                $from=2;
            }
            else if($prestamo)
            {
                $cheque_abono_prestamo=new ChequeAbonoPrestamo();
                $cheque_abono_prestamo->setIdAbonoPrestamo($id_abono);
                $resultados=ChequeAbonoPrestamoDAO::search($cheque_abono_prestamo);
                $from=3;
            }
            else
            {
                Logger::error("No se recibio si se eliminaran de una compra, una venta o un prestamo los cheques");
                throw new Exception("No se recibio si los cheques se eliminaran de una compra, una venta o un prestamo");
            }
            $cheque=new Cheque();
            if($resultados==null)
                return;
            DAO::transBegin();
            try
            {
                foreach($resultados as $resultado)
                {
                    $cheque->setIdCheque($resultado->getIdCheque());
                    ChequeDAO::delete($cheque);
                    switch($from)
                    {
                        case 1:
                            ChequeAbonoCompraDAO::delete($resultado);
                            break;
                        case 2:
                            ChequeAbonoVentaDAO::delete($resultado);
                            break;
                        case 3:
                            ChequeAbonoPrestamoDAO::delete($resultado);
                    }
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudieron borrar los cheques: ".$e);
                throw $e;
            }
            DAO::transEnd();
        }

	/**
 	 *
 	 *Lista los abonos, puede filtrarse por empresa, por sucursal, por caja, por usuario que abona y puede ordenarse segun sus atributos
 	 *
 	 * @param id_caja int Id de la caja de la cual se mostraran los abonos
 	 * @param id_usuario int Id del usuario del cual se mostraran los abonos que ha realizado
 	 * @param orden json Objeto que indicara el orden en que se mostrara la lista
 	 * @param id_sucursal int Id de la sucursal de la cual se mostraran los abonos
 	 * @param id_empresa int Id de la empresa de la cual se mostraran los abonos
 	 * @return abonos json Objeto que contendra la lista de abonos
 	 **/
	public function ListaAbono
	(
                $compra,
                $venta,
                $prestamo,
		$id_caja = null,
		$id_usuario = null,
		$orden = null,
		$id_sucursal = null,
		$id_empresa = null,
                $id_compra = null,
                $id_venta = null,
                $id_prestamo = null,
                $cancelado = null,
                $fecha_minima = null,
                $fecha_maxima = null,
                $fecha_actual = null,
                $monto_menor_a = null,
                $monto_mayor_a = null,
                $monto_igual_a = null
	)
	{
            Logger::log("Inicia lista de abonos");
            if(!$compra&&!$venta&&!$prestamo)
            {
                Logger::warn("No se recibio si se listaran compras, ventas o prestamos, no se lista nada");
                return null;
            }
            $abonos_compra=null;
            $abonos_venta=null;
            $abonos_prestamo=null;
            $parametros=false;
            //
            //Verificar si se recibieron parametros para filtrar
            //de no ser así, usar el metodo getAll en lugar del
            //metodo getByRange
            //
            if
            (
                $id_caja != null||
		$id_usuario != null||
		$id_sucursal != null||
		$id_empresa != null||
                $id_compra != null||
                $id_venta != null||
                $id_prestamo != null||
                $cancelado !== null||
                $fecha_minima != null||
                $fecha_maxima != null||
                $fecha_actual != null||
                $monto_menor_a != null||
                $monto_mayor_a != null||
                $monto_igual_a != null
            )
            {
                $parametros=true;
            }
            //
            //Verficiar si se listaran abonos de compra
            //
            if($compra)
            {
                if($parametros)
                {
                    Logger::log("Se encontraron parametros para compra");
                    $abono_criterio_compra=new AbonoCompra();
                    $abono_criterio_compra2=new AbonoCompra();
                    //
                    //El objeto 1 (abono_criterio_compra) obtiene todos los valores recibidos
                    //para que a la hora de comparar, getByRange traiga
                    //los campos que cumplan exactamente con esa informacion
                    //
                    $abono_criterio_compra->setCancelado($cancelado);
                    $abono_criterio_compra->setIdCaja($id_caja);
                    if($fecha_minima!=null)
                    {
                        //
                        //Si pasaron una fecha minima y existe una fecha maxima, entonces
                        //el objeto 1 almacenara la minima y el objeto 2 la maxima para
                        //que se impriman los abonos entre esas dos fechas.
                        //
                        //Si no hay fecha maxima, el objeto 2 almacenara la fecha de hoy
                        //para que se impriman los abonos desde la fecha minima hasta hoy.
                        //
                        //
                        $abono_criterio_compra->setFecha($fecha_minima);
                        if($fecha_maxima!=null)
                            $abono_criterio_compra2->setFecha($fecha_maxima);
                        else
                            $abono_criterio_compra2->setFecha(date($this->formato_fecha, time()));
                    }
                    else if($fecha_maxima!=null)
                    {
                        //
                        //Si no se recibio fecha minima pero si fecha maxima
                        //El objeto 1 guarda la fecha maxima y el objeto 2 guarda
                        //la fecha minima posible de MySQL, para asi poder listar
                        //los abonos anteriores a la fecha maxima.
                        //
                        $abono_criterio_compra->setFecha($fecha_maxima);
                        $abono_criterio_compra2->setFecha("1000-01-01 00:00:00");
                    }
                    else if($fecha_actual)
                    {
                        //
                        //Si se recibio el booleano fecha_actual, se listaran los abonos
                        //solo de hoy, se crea un timestamp con el año, el mes y el dia de hoy
                        //pero se inicia con la hora 00:00:00 y se almacena como fecha en el objeto 1.
                        //
                        //Se crea un segundo timestamp con el año, el mes y el dia de hoy pero
                        //con la hora 23:59:59 y se almacena como fecha en el objeto 2.
                        //
                        $hoy=mktime(0,0,0,date("m"),date("d"),date("Y"));
                        $abono_criterio_compra->setFecha(date($this->formato_fecha, $hoy));
                        $manana=mktime(23,59,59,date("m"),date("d"),date("Y"));
                        $abono_criterio_compra2->setFecha(date($this->formato_fecha,$manana));
                    }

                    $abono_criterio_compra->setIdCompra($id_compra);
                    $abono_criterio_compra->setIdReceptor($id_usuario);
                    $abono_criterio_compra->setIdSucursal($id_sucursal);
                    if($monto_mayor_a!==null)
                    {
                        //
                        //Si se recibio el monto_mayor_a y se recibio el monto_menor_a
                        //el objeto 1 guarda el primero y el objeto2 guarda el segundo
                        //para asi listar los abonos cuyo monto sea mayor a mayor_a y
                        //menor a menor_a
                        //
                        //Si no, el objeto 2 almacena el valor mas grande posible
                        //para que se listen los objeto cuyo monto sea mayor a mayor_a
                        //
                        $abono_criterio_compra->setMonto($monto_mayor_a);
                        if($monto_menor_a!==null)
                            $abono_criterio_compra2->setMonto($monto_menor_a);
                        else
                            $abono_criterio_compra2->setMonto(1.8e100);
                    }
                    else if($monto_menor_a!==null)
                    {
                        //
                        //Si solo se obtuvo monto_menor_a, el objeto 1 lo almacena y el
                        //objeto 2 almacena el monto mas bajo posible para que  se listen
                        //los abonos cuyo monto sea menor a menor_a
                        //
                        $abono_criterio_compra->setMonto($monto_menor_a);
                        $abono_criterio_compra2->setMonto(0);
                    }
                    else if($monto_igual_a!==null)
                    {
                        //
                        //Si se recibe monto_igual_a se asignara este monto al
                        //objeto 1 para que se listen solo los abonos con dicho monto
                        //
                        $abono_criterio_compra->setMonto($monto_igual_a);

                    }
                    //
                    //Almacena la consulta en un arreglo de objetos
                    //
                    $abonos_compra=AbonoCompraDAO::byRange($abono_criterio_compra, $abono_criterio_compra2, $orden);
                }
                else
                {
                    Logger::log("No se encontraron parametros para compra, se listan todos los abonos a compra");
                    $abonos_compra=AbonoCompraDAO::getAll(null, null, $orden);
                }
            }
            //
            //Verficiar si se listaran abonos de venta
            //
            if($venta)
            {
                if($parametros)
                {
                    $abono_criterio_venta=new AbonoVenta();
                    $abono_criterio_venta2=new AbonoVenta();
                    //
                    //El objeto 1 (abono_criterio_venta) obtiene todos los valores recibidos
                    //para que a la hora de comparar, getByRange traiga
                    //los campos que cumplan exactamente con esa informacion
                    //
                    $abono_criterio_venta->setCancelado($cancelado);
                    $abono_criterio_venta->setIdCaja($id_caja);
                    if($fecha_minima!=null)
                    {
                        //
                        //Si pasaron una fecha minima y existe una fecha maxima, entonces
                        //el objeto 1 almacenara la minima y el objeto 2 la maxima para
                        //que se impriman los abonos entre esas dos fechas.
                        //
                        //Si no hay fecha maxima, el objeto 2 almacenara la fecha de hoy
                        //para que se impriman los abonos desde la fecha minima hasta hoy.
                        //
                        //
                        $abono_criterio_venta->setFecha($fecha_minima);
                        if($fecha_maxima!=null)
                            $abono_criterio_venta2->setFecha($fecha_maxima);
                        else
                            $abono_criterio_venta2->setFecha(date($this->formato_fecha, time()));
                    }
                    else if($fecha_maxima!=null)
                    {
                        //
                        //Si no se recibio fecha minima pero si fecha maxima
                        //El objeto 1 guarda la fecha maxima y el objeto 2 guarda
                        //la fecha minima posible de MySQL, para asi poder listar
                        //los abonos anteriores a la fecha maxima.
                        //
                        $abono_criterio_venta->setFecha($fecha_maxima);
                        $abono_criterio_venta2->setFecha("1000-01-01 00:00:00");
                    }
                    else if($fecha_actual)
                    {
                        //
                        //Si se recibio el booleano fecha_actual, se listaran los abonos
                        //solo de hoy, se crea un timestamp con el año, el mes y el dia de hoy
                        //pero se inicia con la hora 00:00:00 y se almacena como fecha en el objeto 1.
                        //
                        //Se crea un segundo timestamp con el año, el mes y el dia de hoy pero
                        //con la hora 23:59:59 y se almacena como fecha en el objeto 2.
                        //
                        $hoy=mktime(0,0,0,date("m"),date("d"),date("Y"));
                        $abono_criterio_venta->setFecha(date($this->formato_fecha, $hoy));
                        $manana=mktime(23,59,59,date("m"),date("d"),date("Y"));
                        $abono_criterio_venta2->setFecha(date($this->formato_fecha,$manana));
                    }
                    $abono_criterio_venta->setIdVenta($id_venta);
                    $abono_criterio_venta->setIdDeudor($id_usuario);
                    $abono_criterio_venta->setIdSucursal($id_sucursal);
                    if($monto_mayor_a!==null)
                    {
                        //
                        //Si se recibio el monto_mayor_a y se recibio el monto_menor_a
                        //el objeto 1 guarda el primero y el objeto2 guarda el segundo
                        //para asi listar los abonos cuyo monto sea mayor a mayor_a y
                        //menor a menor_a
                        //
                        //Si no, el objeto 2 almacena el valor mas grande posible
                        //para que se listen los objeto cuyo monto sea mayor a mayor_a
                        //
                        $abono_criterio_venta->setMonto($monto_mayor_a);
                        if($monto_menor_a!==null)
                            $abono_criterio_venta2->setMonto($monto_menor_a);
                        else
                            $abono_criterio_venta2->setMonto(1.8e100);
                    }
                    else if($monto_menor_a!==null)
                    {
                        //
                        //Si solo se obtuvo monto_menor_a, el objeto 1 lo almacena y el
                        //objeto 2 almacena el monto mas bajo posible para que  se listen
                        //los abonos cuyo monto sea menor a menor_a
                        //
                        $abono_criterio_venta->setMonto($monto_menor_a);
                        $abono_criterio_venta2->setMonto(0);
                    }
                    else if($monto_igual_a!==null)
                    {
                        //
                        //Si se recibe monto_igual_a se asignara este monto al
                        //objeto 1 para que se listen solo los abonos con dicho monto
                        //
                        $abono_criterio_venta->setMonto($monto_igual_a);
                    }
                    //
                    //Almacena la consulta en un arreglo de objetos
                    //
                    $abonos_venta=AbonoVentaDAO::byRange($abono_criterio_venta, $abono_criterio_venta2, $orden);
                }
                else
                    $abonos_venta=AbonoVentaDAO::getAll(null, null, $orden);

            }
            //
            //Verficiar si se listaran abonos de prestamo
            //
            if($prestamo)
            {
                if($parametros)
                {
                    $abono_criterio_prestamo=new AbonoPrestamo();
                    $abono_criterio_prestamo2=new AbonoPrestamo();
                    //
                    //El objeto 1 (abono_criterio_prestamo) obtiene todos los valores recibidos
                    //para que a la hora de comparar, getByRange traiga
                    //los campos que cumplan exactamente con esa informacion
                    //
                    $abono_criterio_prestamo->setCancelado($cancelado);
                    $abono_criterio_prestamo->setIdCaja($id_caja);
                    if($fecha_minima!=null)
                    {
                        //
                        //Si pasaron una fecha minima y existe una fecha maxima, entonces
                        //el objeto 1 almacenara la minima y el objeto 2 la maxima para
                        //que se impriman los abonos entre esas dos fechas.
                        //
                        //Si no hay fecha maxima, el objeto 2 almacenara la fecha de hoy
                        //para que se impriman los abonos desde la fecha minima hasta hoy.
                        //
                        //
                        $abono_criterio_prestamo->setFecha($fecha_minima);
                        if($fecha_maxima!=null)
                            $abono_criterio_prestamo2->setFecha($fecha_maxima);
                        else
                            $abono_criterio_prestamo2->setFecha(date($this->formato_fecha, time()));
                    }
                    else if($fecha_maxima!=null)
                    {
                        //
                        //Si no se recibio fecha minima pero si fecha maxima
                        //El objeto 1 guarda la fecha maxima y el objeto 2 guarda
                        //la fecha minima posible de MySQL, para asi poder listar
                        //los abonos anteriores a la fecha maxima.
                        //
                        $abono_criterio_prestamo->setFecha($fecha_maxima);
                        $abono_criterio_prestamo2->setFecha("1000-01-01 00:00:00");
                    }
                    else if($fecha_actual)
                    {
                        //
                        //Si se recibio el booleano fecha_actual, se listaran los abonos
                        //solo de hoy, se crea un timestamp con el año, el mes y el dia de hoy
                        //pero se inicia con la hora 00:00:00 y se almacena como fecha en el objeto 1.
                        //
                        //Se crea un segundo timestamp con el año, el mes y el dia de hoy pero
                        //con la hora 23:59:59 y se almacena como fecha en el objeto 2.
                        //
                        $hoy=mktime(0,0,0,date("m"),date("d"),date("Y"));
                        $abono_criterio_prestamo->setFecha(date($this->formato_fecha, $hoy));
                        $manana=mktime(23,59,59,date("m"),date("d"),date("Y"));
                        $abono_criterio_prestamo2->setFecha(date($this->formato_fecha,$manana));
                    }
                    $abono_criterio_prestamo->setIdPrestamo($id_prestamo);
                    $abono_criterio_prestamo->setIdDeudor($id_usuario);
                    $abono_criterio_prestamo->setIdSucursal($id_sucursal);
                    if($monto_mayor_a!==null)
                    {
                        //
                        //Si se recibio el monto_mayor_a y se recibio el monto_menor_a
                        //el objeto 1 guarda el primero y el objeto2 guarda el segundo
                        //para asi listar los abonos cuyo monto sea mayor a mayor_a y
                        //menor a menor_a
                        //
                        //Si no, el objeto 2 almacena el valor mas grande posible
                        //para que se listen los objeto cuyo monto sea mayor a mayor_a
                        //
                        $abono_criterio_prestamo->setMonto($monto_mayor_a);
                        if($monto_menor_a!==null)
                            $abono_criterio_prestamo2->setMonto($monto_menor_a);
                        else
                            $abono_criterio_prestamo2->setMonto(1.8e100);
                    }
                    else if($monto_menor_a!==null)
                    {
                        //
                        //Si solo se obtuvo monto_menor_a, el objeto 1 lo almacena y el
                        //objeto 2 almacena el monto mas bajo posible para que  se listen
                        //los abonos cuyo monto sea menor a menor_a
                        //
                        $abono_criterio_prestamo->setMonto($monto_menor_a);
                        $abono_criterio_prestamo2->setMonto(0);
                    }
                    else if($monto_igual_a!==null)
                    {
                        //
                        //Si se recibe monto_igual_a se asignara este monto al
                        //objeto 1 para que se listen solo los abonos con dicho monto
                        //
                        $abono_criterio_prestamo->setMonto($monto_igual_a);
                    }
                    //
                    //Almacena la consulta en un arreglo de objetos
                    //
                    $abonos_prestamo=AbonoPrestamoDAO::byRange($abono_criterio_prestamo, $abono_criterio_prestamo2, $orden);
            }
             else
                $abonos_prestamo=AbonoPrestamoDAO::getAll(null, null, $orden);
            }
            $cont=0;
            $abonos=null;
            //
            //Si la consulta de abonos en compras trae un resultado, agregala al arreglo de abonos
            //y asi con las ventas y los prestamos.
            //
            if($abonos_compra!=null)
            {
                $abonos[$cont]=$abonos_compra;
                $cont++;
            }
            if($abonos_venta!=null)
            {
                $abonos[$cont]=$abonos_venta;
                $cont++;
            }
            if($abonos_prestamo!=null)
            {
                $abonos[$cont]=$abonos_prestamo;
                $cont++;
            }
            Logger::log("Recuperado lista de abonos exitosamente");
            return $abonos;
	}

	/**
 	 *
 	 *Cancela un gasto
 	 *
 	 * @param id_gasto int Id del gasto a eliminar
 	 * @param motivo_cancelacion string Motivo por el cual se realiza la cancelacion
 	 **/
	public function EliminarGasto
	(
		$id_gasto,
		$motivo_cancelacion = null,
                $id_caja=null,
                $billetes=null
	)
	{
            Logger::log("Eliminando gasto");
            $gasto=GastoDAO::getByPK($id_gasto);
            if(!$gasto)
            {
                Logger::error("El gasto con id:".$id_gasto." no existe");
                throw new Exception("El gasto con id:".$id_gasto." no existe");
            }
            if($gasto->getCancelado())
            {
                Logger::log("El gasto ya ha sido cancelado");
                return;
            }
            $gasto->setCancelado(1);
            $gasto->setMotivoCancelacion($motivo_cancelacion);
            if(!$id_caja)
                $id_caja=$this->getCaja();
            if($id_caja!=null)
            {
                try
                {
                    $this->modificarCaja($id_caja, 1, $billetes, $gasto->getMonto());
                }
                catch(Exception $e)
                {
                    throw $e;
                }
            }
            DAO::transBegin();
            try
            {
                GastoDAO::save($gasto);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se ha podido cancelar el gasto: ".$e);
                throw new Exception("No se ha podido cancelar el gasto: ".$e);
            }
            DAO::transEnd();
            Logger::log("Gasto cancelado exitosamente");
	}

	/**
 	 *
 	 *Lista los gastos, se puede filtrar de acuerdo a la empresa, la sucursal, el usuario que registra el gasto, el concepto de gasto, la orden de servicio, la caja de la cual se sustrajo el dinero para pagar el gasto, de una fecha inicial a una final, por monto, por cancelacion, y se puede ordenar de acuerdo a ss atributos.
 	 *
 	 * @param id_empresa int Id de la empresa de la cual se listaran sus gastos
 	 * @param id_usuario int Id del usuario del cual se listaran los gastos que ha registrado
 	 * @param id_concepto_gasto int Se listaran solo los gastos que tengan como concepto este id
 	 * @param id_orden_servicio int Se listaran los gastos que pertenezcan solamente a esta orden de servicio
 	 * @param id_caja int Id de caja de la cual se listaran los gastos que ha financiado
 	 * @param fecha_inicial string Se listaran los gastos cuya fecha de gasto sea mayor a esta fecha
 	 * @param fecha_final string Se listaran los gastos cuya fecha de gasto sea menor a esta fecha
 	 * @param id_sucursal int Id de la sucursal de la cual se listaran sus gastos
 	 * @param cancelado bool Si este valor no es obtenido, se listaran los gastos tanto cancelados como no cancelados. Si es true, se listaran solo los gastos cancelados, si es false, se listaran solo los gastos que no han sido cancelados
 	 * @param monto_minimo float Se listaran los gastos cuyo monto sea mayor a este valor
 	 * @param monto_maximo float Se listaran los gastos cuyo monto sea menor a este valor
 	 **/
	public function ListaGasto
	(
		$id_empresa = null,
		$id_usuario = null,
		$id_concepto_gasto = null,
		$id_orden_servicio = null,
		$id_caja = null,
		$fecha_inicial = null,
		$fecha_final = null,
		$id_sucursal = null,
		$cancelado = null,
		$monto_minimo = null,
		$monto_maximo = null,
                $orden = null,
                $fecha_actual = null
	)
	{
            Logger::log("Listando Gastos");
            $parametros=false;
            if
            (
                    $id_empresa!=null ||
                    $id_usuario!=null ||
                    $id_concepto_gasto!=null ||
                    $id_orden_servicio!=null ||
                    $id_caja!=null ||
                    $fecha_inicial!=null ||
                    $fecha_final!=null ||
                    $id_sucursal!=null ||
                    $cancelado!==null ||
                    $monto_minimo!=null ||
                    $monto_maximo!=null ||
                    $fecha_actual!=null
            )
                $parametros=true;
                $gastos=null;
            if($parametros)
            {
                Logger::log("Se recibieron parametros, se listan los Gastos dentro del rango");
                //
                //Se almacenan los parametros recibidos en el objeto criterio 1
                //para luego ser comparados.
                //
                $gasto_criterio_1 = new Gasto();
                $gasto_criterio_2 = new Gasto();
                $gasto_criterio_1->setIdEmpresa($id_empresa);
                $gasto_criterio_1->setIdUsuario($id_usuario);
                $gasto_criterio_1->setIdConceptoGasto($id_concepto_gasto);
                $gasto_criterio_1->setIdOrdenDeServicio($id_orden_servicio);
                $gasto_criterio_1->setIdCaja($id_caja);
                $gasto_criterio_1->setIdSucursal($id_sucursal);
                $gasto_criterio_1->setCancelado($cancelado);
                if($fecha_inicial!=null)
                {
                    //
                    //Si pasaron una fecha minima y existe una fecha maxima, entonces
                    //el objeto 1 almacenara la minima y el objeto 2 la maxima para
                    //que se impriman los gastos entre esas dos fechas.
                    //
                    //Si no hay fecha maxima, el objeto 2 almacenara la fecha de hoy
                    //para que se impriman los gastos desde la fecha minima hasta hoy.
                    //
                    //
                    $gasto_criterio_1->setFechaDelGasto($fecha_inicial);
                    if($fecha_final!=null)
                        $gasto_criterio_2->setFechaDelGasto($fecha_final);
                    else
                        $gasto_criterio_2->setFechaDelGasto(date($this->formato_fecha, time()));
                }
                else if($fecha_final!=null)
                {
                    //
                    //Si no se recibio fecha minima pero si fecha maxima
                    //El objeto 1 guarda la fecha maxima y el objeto 2 guarda
                    //la fecha minima posible de MySQL, para asi poder listar
                    //los gastos anteriores a la fecha maxima.
                    //
                    $gasto_criterio_1->setFechaDelGasto($fecha_final);
                    $gasto_criterio_2->setFechaDelGasto("1000-01-01 00:00:00");
                }
                else if($fecha_actual)
                {
                    //
                    //Si se recibio el booleano fecha_actual, se listaran los abonos
                    //solo de hoy, se crea un timestamp con el año, el mes y el dia de hoy
                    //pero se inicia con la hora 00:00:00 y se almacena como fecha en el objeto 1.
                    //
                    //Se crea un segundo timestamp con el año, el mes y el dia de hoy pero
                    //con la hora 23:59:59 y se almacena como fecha en el objeto 2.
                    //
                    $hoy=mktime(0,0,0,date("m"),date("d"),date("Y"));
                    $gasto_criterio_1->setFechaDelGasto(date($this->formato_fecha, $hoy));
                    $manana=mktime(23,59,59,date("m"),date("d"),date("Y"));
                    $gasto_criterio_2->setFechaDelGasto(date($this->formato_fecha,$manana));
                }
                if($monto_minimo!==null)
                {
                    //
                    //Si se recibio el monto_minimo y se recibio el monto_maximo
                    //el objeto 1 guarda el primero y el objeto2 guarda el segundo
                    //para asi listar los gastos cuyo monto sea mayor al minimo y
                    //menor que el maximo
                    //
                    //Si no, el objeto 2 almacena el valor mas grande posible
                    //para que se listen los gastos cuyo monto sea mayor al minimo
                    //
                    $gasto_criterio_1->setMonto($monto_minimo);
                    if($monto_maximo!==null)
                        $gasto_criterio_2->setMonto($monto_maximo);
                    else
                        $gasto_criterio_2->setMonto(1.8e100);
                }
                else if($monto_maximo!==null)
                {
                    //
                    //Si solo se obtuvo monto_maximo, el objeto 1 lo almacena y el
                    //objeto 2 almacena el monto mas bajo posible para que  se listen
                    //los gastos cuyo monto sea menor al maximo
                    //
                    $gasto_criterio_1->setMonto($monto_maximo);
                    $gasto_criterio_2->setMonto(0);
                }
                $gastos=GastoDAO::byRange($gasto_criterio_1, $gasto_criterio_2, $orden);
            }
            else
            {
                Logger::log("No se recibieron parametros, se listan todos los Gastos");
                $gastos=GastoDAO::getAll(null, null,$orden);
            }
            Logger::log("Se obtuvo la lista de gastos exitosamente");
            return $gastos;
	}

	/**
 	 *
 	 *Cancela un ingreso
 	 *
 	 * @param id_ingreso int Id del ingreso a cancelar
 	 * @param motivo_cancelacion string Motivo por el cual se realiza la cancelacion
 	 **/
	public function EliminarIngreso
	(
		$id_ingreso,
		$motivo_cancelacion = null,
                $id_caja=null,
                $billetes=null
	)
	{
            Logger::log("Cancelando Ingreso");
            $ingreso=IngresoDAO::getByPK($id_ingreso);
            if(!$ingreso)
            {
                Logger::error("El ingreso con id: ".$id_ingreso." no existe");
                throw new Exception("El ingreso con id: ".$id_ingreso." no existe");
            }
            $ingreso->setCancelado(1);
            $ingreso->setMotivoCancelacion($motivo_cancelacion);
            if(!$id_caja)
                $id_caja=$this->getCaja();
            if($id_caja!=null)
            {
                try
                {
                    $this->modificarCaja($id_caja, 0, $billetes, $ingreso->getMonto());
                }
                catch(Exception $e)
                {
                    throw $e;
                }
            }
            DAO::transBegin();
            try
            {
                IngresoDAO::save($ingreso);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo Eliminar el ingreso: ".$e);
                throw $e;
            }
            DAO::transEnd();
            Logger::log("Ingreso cancelado exitosamente");
	}

	/**
 	 *
 	 *Registra un nuevo concepto de gasto

<br/><br/><b>Update :</b> En la respuesta basta con solo indicar success : true | false, y en caso de fallo indicar el por que.
 	 *
 	 * @param nombre string la justificacion que aparecera despues de la leyenda "gasto por concepto de"
 	 * @param descripcion string Descripcion larga del concepto de gasto
 	 * @param monto float Monto fijo del concepto de gasto
 	 * @return id_concepto_gasto int Id autogenerado por la inserci�n del nuevo gasto
 	 **/
	public function NuevoConceptoGasto
	(
		$nombre,
		$descripcion = null,
		$monto = null
	)
	{
            Logger::log("Creando concepto de gasto");
            $concepto_gasto = new ConceptoGasto();
            $concepto_gasto->setNombre($nombre);
            $concepto_gasto->setDescripcion($descripcion);
            $concepto_gasto->setMonto($monto);
            $concepto_gasto->setActivo(1);
            DAO::transBegin();
            try
            {
                ConceptoGastoDAO::save($concepto_gasto);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo crear el concepto de gasto: ".$e);
                throw $e;
            }
            DAO::transEnd();
            Logger::log("Gasto creado exitosamente");
            return $concepto_gasto->getIdConceptoGasto();
	}

	/**
 	 *
 	 *Edita la informaci?e un concepto de gasto

<br/><br/><b>Update : </b>Se deber?de tomar de la sesi?l id del usuario que hiso la ultima modificaci? la fecha.
 	 *
 	 * @param nombre string Justificacion del concepto de gasto que aparecera despues de la leyenda "gasto por concepto de"
 	 * @param id_concepto_gasto int Id del concepto de gasto a modificar
 	 * @param monto float monto fijo del concepto de gasto
 	 * @param descripcion string Descripcion larga del concepto de gasto
 	 **/
	public function EditarConceptoGasto
	(
		$id_concepto_gasto,
                $nombre = null,
		$monto = null,
		$descripcion = null
	)
	{
            Logger::log("Editando concepto de gasto");
            if(!$nombre && !$monto && !$descripcion)
            {
                Logger::warn("No se recibieron parametros para editar, no se edita nada");
                return;
            }
            $concepto_gasto=ConceptoGastoDAO::getByPK($id_concepto_gasto);
            if(!$concepto_gasto)
            {
                Logger::error("El concepto de gasto con id: ".$id_concepto_gasto." no existe");
                throw new Exception("El concepto de gasto con id: ".$id_concepto_gasto." no existe");
            }
            if($nombre!==null)
                $concepto_gasto->setNombre($nombre);
            if($monto!==null)
                $concepto_gasto->setMonto($monto);
            if($descripcion!==null)
                $concepto_gasto->setDescripcion($descripcion);
            DAO::transBegin();
            try
            {
                ConceptoGastoDAO::save($concepto_gasto);
            }
            catch(Exception $e)
            {
                Logger::error("No se pudo editar el concepto de gasto: ".$e);
                DAO::transRollback();
                throw $e;
            }
            DAO::transEnd();
            Logger::log("Se edito el concepto de gasto con exito");

	}

	/**
 	 *
 	 *Deshabilita un concepto de gasto
<br/><br/><b>Update :</b>Se deber?de tomar tambi?de la sesi?l id del usuario y fecha de la ultima modificaci?
 	 *
 	 * @param id_concepto_gasto int Id del concepto que ser eliminado
 	 **/
	public function EliminarConceptoGasto
	(
		$id_concepto_gasto
	)
	{
            Logger::log("Eliminando concepto de gasto");
            $concepto_gasto=ConceptoGastoDAO::getByPK($id_concepto_gasto);
            if(!$concepto_gasto)
            {
                Logger::error("El concepto gasto con id: ".$id_concepto_gasto." no existe");
                throw new Exception("El concepto gasto con id: ".$id_concepto_gasto." no existe");
            }
            if(!$concepto_gasto->getActivo())
            {
                Logger::warn("El concepto de gasto ya ha sido desactivado");
                return;
            }
            $concepto_gasto->setActivo(0);
            DAO::transBegin();
            try
            {
                ConceptoGastoDAO::save($concepto_gasto);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo eliminar el concepto de gasto: ".$e);
                throw $e;
            }
            DAO::transEnd();
            Logger::log("Concepto de gasto eliminado con exito");
	}

	/**
 	 *
 	 *Crea un nuevo concepto de ingreso

<br/><br/><b>Update :</b> En la respuesta basta con solo indicar success : true | false, y en caso de fallo indicar el por que.
 	 *
 	 * @param nombre string Justificacion que aparecer despus de la leyenda "ingreso por concepto de"
 	 * @param monto float Monto fijo del concepto de ingreso
 	 * @param descripcion string Descripcion larga de este concepto de ingreso
 	 * @return id_concepto_ingreso int Id autogenerado por la creacion del nuevo concepto de ingreso
 	 **/
	public function NuevoConceptoIngreso
	(
		$nombre,
		$monto = null,
		$descripcion = null
	)
	{
            Logger::log("Creando nuevo concepto de ingreso");
            $concepto_ingreso = new ConceptoIngreso();
            $concepto_ingreso->setNombre($nombre);
            $concepto_ingreso->setMonto($monto);
            $concepto_ingreso->setDescripcion($descripcion);
            $concepto_ingreso->setActivo(1);
            DAO::transBegin();
            try
            {
                ConceptoIngresoDAO::save($concepto_ingreso);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo crea el nuevo concepto de ingreso: ".$e);
                throw $e;
            }
            DAO::transEnd();
            Logger::log("Concepto de ingreso creado exitosamente");
            return $concepto_ingreso->getIdConceptoIngreso();
	}

	/**
 	 *
 	 *Edita un concepto de ingreso
 	 *
 	 * @param nombre string Justificacion que aparecera despues de la leyenda "ingreso por concepto de"
 	 * @param id_concepto_ingreso int Id del concepto de ingreso a modificar
 	 * @param descripcion string Descripcion larga del concepto de ingreso
 	 * @param monto float Si este concepto tiene un monto fijo, se debe mostrar aqui. Si no hay un monto fijo, dejar esto como null.
 	 **/
	public function EditarConceptoIngreso
	(
                $id_concepto_ingreso,
		$nombre = null,
		$descripcion = null,
		$monto = null
	)
	{
            Logger::log("Editando concepto de ingreso");
            if(!$nombre && !$descripcion && !$monto)
            {
                Logger::warn("No se ha recibido un parametro a editar, no hay nada que editar");
                return;
            }
            $concepto_ingreso = ConceptoIngresoDAO::getByPK($id_concepto_ingreso);
            if(!$concepto_ingreso)
            {
                Logger::error("El concepto de ingreso con id:".$id_concepto_ingreso." no existe");
                throw new Exception("El concepto de ingreso con id:".$id_concepto_ingreso." no existe");
            }
            if($nombre !== null)
                $concepto_ingreso->setNombre($nombre);
            if($descripcion !== null)
                $concepto_ingreso->setDescripcion($descripcion);
            if($monto !== null)
                $concepto_ingreso->setMonto($monto);
            DAO::transBegin();
            try
            {
                ConceptoIngresoDAO::save($concepto_ingreso);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo editar el concepto de ingreso: ".$e);
                throw $e;
            }
            DAO::transEnd();
            Logger::log("Concepto de Ingreso editado exitosamente");
	}

	/**
 	 *
 	 *Deshabilita un concepto de ingreso

<br/><br/><b>Update :</b>Se deber?tambi?obtener de la sesi?l id del usuario y fecha de la ultima modificaci?
 	 *
 	 * @param id_concepto_ingreso int Id del ingreso a eliminar
 	 **/
	public function EliminarConceptoIngreso
	(
		$id_concepto_ingreso
	)
	{
            Logger::log("Eliminando concepto de ingreso");
            $concepto_ingreso = ConceptoIngresoDAO::getByPK($id_concepto_ingreso);
            if(!$concepto_ingreso)
            {
                Logger::error("El concepto de ingreso con id:".$id_concepto_ingreso." no existe");
                throw new Exception("El concepto de ingreso con id:".$id_concepto_ingreso." no existe");
            }
            if(!$concepto_ingreso->getActivo())
            {
                Logger::warn("El concepto de ingreso ya ha sido desactivado");
                return;
            }
            $concepto_ingreso->setActivo(0);
            DAO::transBegin();
            try
            {
                ConceptoIngresoDAO::save($concepto_ingreso);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo desactivar el concepto de ingreso: ".$e);
                throw $e;
            }
            DAO::transEnd();
            Logger::log("Concepto de ingreso desactivado exitosamente");
	}

	/**
 	 *
 	 *Lista los conceptos de gasto. Se puede ordenar por los atributos de concepto de gasto
<br/><br/><b>Update : </b>Falta especificar los parametros y el ejemplo de envio.
 	 *
 	 * @param ordenar json Valor que contendr la manera en que se ordenar la lista.
 	 * @return conceptos_gasto json Arreglo que contendr� la informaci�n de conceptos de gasto.
 	 **/
	public function ListaConceptoGasto
	(
		$orden = null,
                $activo = null
	)
	{
            Logger::log("Listando conceptos de gasto");
            $conceptos_gasto=null;
            $concepto_gasto_criterio = new ConceptoGasto();
            if($activo!==null)
            {
                $concepto_gasto_criterio->setActivo($activo);
                $conceptos_gasto=ConceptoGastoDAO::byRange($concepto_gasto_criterio, new ConceptoGasto(),$orden);
            }
            else
            {
                $conceptos_gasto=ConceptoGastoDAO::getAll(null, null, $orden);
            }
            Logger::log("Lista exitosa");
            return $conceptos_gasto;
	}

	/**
 	 *
 	 *Lista los conceptos de ingreso, se puede ordenar por los atributos del concepto de ingreso.

<br/><br/><b>Update :</b>Falta especificar la estructura del JSON que se env?como parametro
 	 *
 	 * @param ordenar json Valor que indicar la forma en que se ordenar la lista
 	 * @return conceptos_ingreso json Arreglo que contendr� la informaci�n de los conceptos de ingreso
 	 **/
	public function ListaConceptoIngreso
	(
		$orden = null,
                $activo = null
	)
	{
            Logger::log("Listando conceptos de ingreso");
            $conceptos_ingreso=null;
            $concepto_ingreso_criterio = new ConceptoIngreso();
            if($activo!==null)
            {
                $concepto_ingreso_criterio->setActivo($activo);
                $conceptos_ingreso=ConceptoIngresoDAO::byRange($concepto_ingreso_criterio, new ConceptoIngreso(),$orden);
            }
            else
            {
                $conceptos_ingreso=ConceptoIngresoDAO::getAll(null, null, $orden);
            }
            Logger::log("Lista exitosa");
            return $conceptos_ingreso;
	}

	/**
 	 *
 	 *Registrar un gasto. El usuario y la sucursal que lo registran ser?tomados de la sesi?ctual.

<br/><br/><b>Update :</b>Ademas deber?tambi?de tomar la fecha de ingreso del gasto del servidor y agregar tambi?como par?tro una fecha a la cual se deber?de aplicar el gasto. Por ejemplo si el d?09/09/11 (viernes) se tomo dinero para pagar la luz, pero resulta que ese d?se olvidaron de registrar el gasto y lo registran el 12/09/11 (lunes). Entonces tambien se deberia de tomar como parametro una <b>fecha</b> a la cual aplicar el gasto, tambien se deberia de enviar como parametro una <b>nota</b>
 	 *
 	 * @param fecha_gasto string Fecha del gasto
 	 * @param id_empresa int Id de la empresa a la que pertenece este gasto
 	 * @param monto float Monto del gasto en caso de que no este contemplado por el concepto de gasto o sea diferente a este
 	 * @param id_sucursal int Id de la sucursal a la que pertenece este gasto
 	 * @param id_caja int Id de la caja de la que se sustrae el dinero para pagar el gasto
 	 * @param id_orden_de_servicio int Id de la orden del servicio que genero este gasto
 	 * @param id_concepto_gasto int Id del concepto al que  hace referencia el gasto
 	 * @param descripcion string Descripcion del gasto en caso de que no este contemplado en la lista de concpetos de gasto
 	 * @param folio string Folio de la factura del gasto
 	 * @param nota string Nota del gasto
 	 * @return id_gasto int Id generado por la inserci�n del nuevo gasto
 	 **/
	public function NuevoGasto
	(
		$fecha_gasto,
		$id_empresa,
		$monto = null,
		$id_sucursal = null,
		$id_caja = null,
		$id_orden_de_servicio = null,
		$id_concepto_gasto = null,
		$descripcion = null,
		$folio = null,
		$nota = null,
                $billetes = null
	)
	{
            Logger::log("Creando nuevo gasto");
            $id_usuario=LoginController::getCurrentUser();
            if($id_usuario==null)
            {
                Logger::error("No se pudo obtener el usuario de la sesion, ya inicio sesion?");
                throw new Exception("No se pudo obtener el usuario de la sesion, ya inicio sesion?");
            }
            if(!$this->validarEmpresa($id_empresa))
            {
                throw new Exception("Se recibio una empresa no valida");
            }
            if($id_concepto_gasto!=null)
            {
                $concepto_gasto=ConceptoGastoDAO::getByPK($id_concepto_gasto);
                if($concepto_gasto==null)
                {
                    Logger::error("El concepto de gasto con id:".$id_concepto_gasto." no existe");
                    throw new Exception("El concepto de gasto con id:".$id_concepto_gasto." no existe");
                }
            }
            if($monto===null)
            {
                Logger::log("No se recibio monto, se procede a buscar en el concepto de ingreso");
                if($id_concepto_gasto==null)
                {
                    Logger::error("No se recibio un concepto de gasto");
                    throw new Exception("No se recibio un concepto de gasto ni un monto");
                }
                $monto=$concepto_gasto->getMonto();
                if($monto===null)
                {
                    Logger::error("El concepto de gasto recibido no cuenta con un monto");
                    throw new Exception("El concepto de gasto recibido no cuenta con un monto ni se recibio un monto");
                }
            }
            if(!$id_sucursal)
                $id_sucursal=$this->getSucursal();
            if(!$id_caja)
                $id_caja=$this->getCaja();
            if($id_caja!=null)
            {
                try
                {
                    $this->modificarCaja($id_caja, 0,$billetes,$monto);
                }
                catch(Exception $e)
                {
                    throw $e;
                }
            }
            $gasto = new Gasto();
            $gasto->setFechaDelGasto($fecha_gasto);
            $gasto->setIdEmpresa($id_empresa);
            $gasto->setMonto($monto);
            $gasto->setIdSucursal($id_sucursal);
            $gasto->setIdCaja($id_caja);
            $gasto->setIdOrdenDeServicio($id_orden_de_servicio);
            $gasto->setIdConceptoGasto($id_concepto_gasto);
            $gasto->setDescripcion($descripcion);
            $gasto->setFolio($folio);
            $gasto->setNota($nota);
            $gasto->setFechaDeRegistro(date($this->formato_fecha, time()));
            $gasto->setIdUsuario($id_usuario);
            $gasto->setCancelado(0);
            DAO::transBegin();
            try
            {
                GastoDAO::save($gasto);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo crear el gasto: ".$e);
                throw $e;
            }
            DAO::transEnd();
            Logger::log("Gasto creado exitosamente");
            return $gasto->getIdGasto();
	}

	/**
 	 *
 	 *Editar los detalles de un gasto.
<br/><br/><b>Update : </b> Tambien se deberia de tomar  de la sesion el id del usuario qeu hiso al ultima modificacion y una fecha de ultima modificacion.
 	 *
 	 * @param id_gasto int Id que hace referencia a este gasto
 	 * @param fecha_gasto string Fecha que el usuario selecciona en el sistema, a la cual le quiere asignar el gasto.
 	 * @param monto float Monto a gastar
 	 * @param id_concepto_gasto int Id del concepto del gasto
 	 * @param descripcion string Descripcion del gasto en caso de que no este en la lista de conceptos.
 	 * @param nota string Informacion adicinal sobre el gasto
 	 * @param folio string Folio de la factura de ese gasto
 	 **/
	public function EditarGasto
	(
		$id_gasto,
		$folio = null,
		$fecha_gasto = null,
		$descripcion = null,
		$nota = null,
		$id_concepto_gasto = null
	)
	{
            Logger::log("Editando gasto");
            if(!$fecha_gasto && !$id_concepto_gasto && !$descripcion && !$nota && !$folio)
            {
                Logger::warn("No se recibieron parametros para editar, no hay nada que editar");
                return;
            }
            $gasto=GastoDAO::getByPK($id_gasto);
            if(!$gasto)
            {
                Logger::error("El gasto con id: ".$id_gasto." no existe");
                throw new Exception("El gasto con id: ".$id_gasto." no existe");
            }
            if($gasto->getCancelado())
            {
                Logger::error("El gasto ya ha sido cancelado y no puede ser editado");
                throw new Exception("El gasto ya ha sido cancelado y no puede ser editado");
            }
            if($id_concepto_gasto!==null)
            {
                $concepto_gasto=ConceptoGastoDAO::getByPK($id_concepto_gasto);
                if($concepto_gasto==null)
                {
                    Logger::error("El concepto de gasto con id:".$id_concepto_gasto." no existe");
                    throw new Exception("El concepto de gasto con id:".$id_concepto_gasto." no existe");
                }
            }
            if($fecha_gasto!==null)
                $gasto->setFechaDelGasto($fecha_gasto);
            if($id_concepto_gasto!==null)
                $gasto->setIdConceptoGasto($id_concepto_gasto);
            if($descripcion!==null)
                $gasto->setDescripcion($descripcion);
            if($nota!==null)
                $gasto->setNota($nota);
            if($folio!==null)
                $gasto->setFolio($folio);
            DAO::transBegin();
            try
            {
                GastoDAO::save($gasto);
            }
            catch(Exception $e)
            {
                DAO::transRollBack();
                Logger::error("No se pudo editar el gasto: ".$e);
                throw $e;
            }
            DAO::transEnd();
            Logger::log("Gasto editado exitosamente");
	}

	/**
 	 *
 	 *Edita un ingreso

<br/><br/><b>Update :</b>El usuario y la fecha de la ultima modificaci?e deber? de obtener de la sesi?
 	 *
 	 * @param fecha_ingreso string Fecha que el usuario selecciona en el sistema, a la cual le quiere asignar el ingreso.
 	 * @param id_ingreso int Id del ingreso que se editar
 	 * @param descrpicion string Descripciond el ingreso en caso de que no se encentre en la lista de conceptos.
 	 * @param folio string Folio de la factura generada por el ingreso
 	 * @param nota string Informacion adicional del ingreso
 	 * @param id_concepto_ingreso int Id del concepto del ingreso
 	 * @param monto float Monto a registrar como ingreso
 	 **/
	public function EditarIngreso
	(
		$id_ingreso,
		$id_concepto_ingreso = null,
		$descripcion = null,
		$folio = null,
		$fecha_ingreso = null,
		$nota = null
	)
	{
            Logger::log("Editando ingreso");
            if(!$fecha_ingreso && !$id_concepto_ingreso && !$descripcion && !$nota && !$folio)
            {
                Logger::warn("No se recibieron parametros para editar, no hay nada que editar");
                return;
            }
            $ingreso=IngresoDAO::getByPK($id_ingreso);
            if(!$ingreso)
            {
                Logger::error("El ingreso con id: ".$id_ingreso." no existe");
                throw new Exception("El ingreso con id: ".$id_ingreso." no existe");
            }
            if($ingreso->getCancelado())
            {
                Logger::error("El ingreso ya ha sido cancelado y no puede ser editado");
                throw new Exception("El ingreso ya ha sido cancelado y no puede ser editado");
            }
            if($id_concepto_ingreso!==null)
            {
                $concepto_ingreso=ConceptoIngresoDAO::getByPK($id_concepto_ingreso);
                if($concepto_ingreso==null)
                {
                    Logger::error("El concepto de ingreso con id:".$id_concepto_ingreso." no existe");
                    throw new Exception("El concepto de ingreso con id:".$id_concepto_ingreso." no existe");
                }
            }
            if($fecha_ingreso!==null)
                $ingreso->setFechaDelIngreso($fecha_ingreso);
            if($id_concepto_ingreso!==null)
                $ingreso->setIdConceptoIngreso($id_concepto_ingreso);
            if($descripcion!==null)
                $ingreso->setDescripcion($descripcion);
            if($nota!==null)
                $ingreso->setNota($nota);
            if($folio!==null)
                $ingreso->setFolio($folio);
            DAO::transBegin();
            try
            {
                IngresoDAO::save($ingreso);
            }
            catch(Exception $e)
            {
                DAO::transRollBack();
                Logger::error("No se pudo editar el ingreso: ".$e);
                throw $e;
            }
            DAO::transEnd();
            Logger::log("Ingreso editado exitosamente");

	}

	/**
 	 *
 	 *Se crea un  nuevo abono, la caja o sucursal y el usuario que reciben el abono se tomaran de la sesion. La fecha se tomara del servidor
 	 *
 	 * @param id_deudor int Id del usuario o la sucursal que realiza el abono, las sucursales seran negativas
 	 * @param tipo_pago json JSON con la informacion que describe el tipo de pago, si es con cheque, en efectivo o con tarjeta
 	 * @param monto float monto abonado de la sucursal
 	 * @param nota string Nota del abono
 	 * @param id_venta int Id de la venta a la que se le abona
 	 * @param varios bool True si el monto sera repartido en los prestamos,ventas o compras mas antiguas. Esto se define si se pasa el valor id_venta,id_prestamo o id_compra
 	 * @param cheques json Se toma el nombre del banco, el monto y los ultimos cuatro numeros del o los cheques usados para este abono
 	 * @param id_prestamo int Id del prestamo al que se le esta abonando
 	 * @param id_compra int Id de la compra a la que se abona
 	 * @return id_abono int El id autogenerado del abono de la sucursal
 	 **/
	public function NuevoAbono
	(
		$monto,
		$tipo_pago,
		$id_deudor,
		$id_compra = null,
		$cheques = null,
		$id_prestamo = null,
		$id_venta = null,
		$nota = null,
                $billetes = null
	)
	{
            $id_usuario=LoginController::getCurrentUser();
            if($id_usuario==null)
            {
                Logger::error("No se pudo obtener el usuario de la sesion, ya inicio sesion?");
                throw new Exception("No se pudo obtener el usuario de la sesion, ya inicio sesion?");
            }
            if($tipo_pago==="cheque"&&$cheques==null)
            {
                    Logger::error("No se recibio informacion del cheque");
                    throw new Exception("No se recibio informacion del cheque");   
            }
            $usuario=UsuarioDAO::getByPK($id_deudor);
            if($usuario==null)
            {
                Logger::error("El deudor obtenido no existe, verifique que este correcto");
                throw new Exception("El deudor obtenido no existe, verifique que este correcto");
            }
            $id_sucursal=$this->getSucursal();
            $id_caja=$this->getCaja();
            $fecha=date($this->formato_fecha,time());
            $cancelado=0;
            $abono=null;
            $from=0;
            $operacion=null;
            if($id_compra!=null)
            {
                $operacion=CompraDAO::getByPK($id_compra);
                if($operacion==null)
                {
                    Logger::error("La compra con id: ".$id_compra." no existe");
                    throw new Exception("La compra con id: ".$id_compra." no existe");
                }
                if($operacion->getCancelada())
                {
                    Logger::error("La compra ya ha sido cancelada, no se puede abonar a esta compra");
                    throw new Exception("La compra ya ha sido cancelada, no se puede abonar a esta compra");
                }
                if($operacion->getTotal()<=$operacion->getSaldo())
                {
                    Logger::error("La compra ya ha sido saldada, no se puede abonar a esta compra");
                    throw new Exception("La compra ya ha sido saldada, no se puede abonar a esta compra");
                }
                if($operacion->getTotal()<($operacion->getSaldo()+$monto))
                {
                    Logger::error("No se puede abonar esta cantidad a esta compra, pues sobrepasa el total de la misma");
                    throw new Exception("No se puede abonar esta cantidad a esta compra, pues sobrepasa el total de la misma");
                }
                $abono=new AbonoCompra();
                $abono->setIdCompra($id_compra);
                $abono->setIdReceptor($id_deudor);
                $abono->setIdDeudor($id_usuario);
                $usuario->setSaldoDelEjercicio($usuario->getSaldoDelEjercicio()-$monto);
                $from=1;
            }
            else if($id_prestamo!=null)
            {
                $operacion=PrestamoDAO::getByPK($id_prestamo);
                if($operacion==null)
                {
                    Logger::error("El prestamo con id: ".$id_prestamo." no existe");
                    throw new Exception("El prestamo con id: ".$id_prestamo." no existe");
                }
                if($operacion->getMonto()<=$operacion->getSaldo())
                {
                    Logger::error("El prestamo ya ha sido saldado, no se puede abonar a este prestamo");
                    throw new Exception("El prestamo ya ha sido saldad0, no se puede abonar a este prestamo");
                }
                if($operacion->getMonto()<($operacion->getSaldo()+$monto))
                {
                    Logger::error("No se puede abonar esta cantidad a este prestamo, pues sobrepasa el total del mismo");
                    throw new Exception("No se puede abonar esta cantidad a este prestamo, pues sobrepasa el total del mismo");
                }
                $abono=new AbonoPrestamo();
                $abono->setIdPrestamo($id_prestamo);
                $abono->setIdReceptor($id_usuario);
                $abono->setIdDeudor($id_deudor);
                $usuario->setSaldoDelEjercicio($usuario->getSaldoDelEjercicio()+$monto);
                $from=2;
            }
            else if($id_venta!=null)
            {
                $operacion=VentaDAO::getByPK($id_venta);
                if($operacion==null)
                {
                    Logger::error("La venta con id: ".$id_venta." no existe");
                    throw new Exception("La venta con id: ".$id_venta." no existe");
                }
                if($operacion->getCancelada())
                {
                    Logger::error("La venta ya ha sido cancelada, no se puede abonar a esta venta");
                    throw new Exception("La venta ya ha sido cancelada, no se puede abonar a esta venta");
                }
                if($operacion->getTotal()<=$operacion->getSaldo())
                {
                    Logger::error("La venta ya ha sido saldada, no se puede abonar a esta venta");
                    throw new Exception("La venta ya ha sido saldada, no se puede abonar a esta venta");
                }
                if($operacion->getTotal()<($operacion->getSaldo()+$monto))
                {
                    Logger::error("No se puede abonar esta cantidad a esta venta, pues sobrepasa el total de la misma");
                    throw new Exception("No se puede abonar esta cantidad a esta venta, pues sobrepasa el total de la misma");
                }
                $abono=new AbonoVenta();
                $abono->setIdVenta($id_venta);
                $abono->setIdReceptor($id_usuario);
                $abono->setIdDeudor($id_deudor);
                $usuario->setSaldoDelEjercicio($usuario->getSaldoDelEjercicio()+$monto);
                $from=3;
            }
            else
            {
                Logger::error("No se recibio si el abono sera para una venta, una compra o un prestamo, no se hace nada");
                throw new Exception("No se recibio si el abono sera para una venta, una compra o un prestamo, no se hace nada");
            }
            $operacion->setSaldo($operacion->getSaldo()+$monto);
            $abono->setCancelado($cancelado);
            $abono->setIdCaja($id_caja);
            $abono->setFecha($fecha);
            $abono->setIdSucursal($id_sucursal);
            $abono->setMonto($monto);
            $abono->setNota($nota);
            $abono->setTipoDePago($tipo_pago);
            $id_cheques=array();
            $id_abono=null;
            DAO::transBegin();
            try
            {
                if($tipo_pago==="cheque"&&$cheques!=null)
                {
                    foreach($cheques as $cheque)
                    {
                        array_push($id_cheques,Cheques::NuevoCheque($cheque["nombre_banco"],$cheque["monto"],$cheque["numero"],$cheque["expedido"]));
                    }
                }
                switch($from)
                {
                    case 1:
                        AbonoCompraDAO::save($abono);
                        CompraDAO::save($operacion);
                        $id_abono=$abono->getIdAbonoCompra();
                        $cheque_abono_compra=new ChequeAbonoCompra();
                        $cheque_abono_compra->setIdAbonoCompra($id_abono);
                        foreach($id_cheques as $id_cheque)
                        {
                            $cheque_abono_compra->setIdCheque($id_cheque);
                            ChequeAbonoCompraDAO::save($cheque_abono_compra);
                        }
                        if($id_caja!=null)
                        {
                            $this->modificarCaja($id_caja, 0, $billetes, $monto);
                        }
                        break;
                    case 2:
                        AbonoPrestamoDAO::save($abono);
                        PrestamoDAO::save($operacion);
                        $id_abono=$abono->getIdAbonoPrestamo();
                        $cheque_abono_prestamo=new ChequeAbonoPrestamo();
                        $cheque_abono_prestamo->setIdAbonoPrestamo($id_abono);
                        foreach($id_cheques as $id_cheque)
                        {
                            $cheque_abono_prestamo->setIdCheque($id_cheque);
                            ChequeAbonoPrestamoDAO::save($cheque_abono_prestamo);
                        }
                        if($id_caja!=null)
                        {
                            $this->modificarCaja($id_caja, 1, $billetes, $monto);
                        }
                        break;
                    case 3:
                        AbonoVentaDAO::save($abono);
                        VentaDAO::save($operacion);
                        $id_abono=$abono->getIdAbonoVenta();
                        $cheque_abono_venta=new ChequeAbonoVenta();
                        $cheque_abono_venta->setIdAbonoVenta($id_abono);
                        foreach($id_cheques as $id_cheque)
                        {
                            $cheque_abono_venta->setIdCheque($id_cheque);
                            ChequeAbonoVentaDAO::save($cheque_abono_venta);
                        }
                        if($id_caja!=null)
                        {
                            $this->modificarCaja($id_caja, 1, $billetes, $monto);
                        }
                }
                UsuarioDAO::save($usuario);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("Error al crear el abono");
                throw $e;
            }
            DAO::transEnd();
            Logger::log("Abono creado exitosamente");
            return $id_abono;
	}

        

	/**
 	 *
 	 *Lista los ingresos, se puede filtrar de acuerdo a la empresa, la sucursal, el usuario que registra el ingreso, el concepto de ingreso, la caja que recibi? ingreso, de una fecha inicial a una final, por monto, por cancelacion, y se puede ordenar de acuerdo a sus atributos.
 	 *
 	 * @param id_caja int Id de la caja de la cual se listaran los ingresos que ha recibido
 	 * @param fecha_inicial string Se listaran los ingresos cuya fecha de ingreso sea mayor a este valor
 	 * @param fecha_final string Se listaran los ingresos cuya fecha de ingreso sea menor a este valor
 	 * @param id_sucursal int Id de la sucursal de la cual se listaran sus ingresos
 	 * @param id_concepto_ingreso int Se listaran los ingresos que tengan este concepto de ingreso
 	 * @param id_empresa int Id de la empresa de la cual se listaran sus ingresos
 	 * @param id_usuario int Id del usuario del cual se listaran los ingresos que ha registrado
 	 * @param cancelado bool Si este valor no es obtenido, se listaran tanto ingresos cancelados como no cancelados, si es true, solo se listaran los ingresos cancelados, si es false, se listaran solo los ingresos no cancelados
 	 * @param monto_minimo float Se listaran los ingresos cuyo monto sea mayor a este valor
 	 * @param monto_maximo float Se listaran los ingresos cuyo monto sea menor a este valor
 	 **/
	public function ListaIngreso
	(
		$id_caja = null,
		$fecha_inicial = null,
		$fecha_final = null,
		$id_sucursal = null,
		$id_concepto_ingreso = null,
		$id_empresa = null,
		$id_usuario = null,
		$cancelado = null,
		$monto_minimo = null,
		$monto_maximo = null
	)
	{


	}

	/**
 	 *
 	 *Edita la informaci?e un abono
 	 *
 	 * @param id_abono int Id del abono a editar
 	 * @param nota string Nota del abono
 	 * @param motivo_cancelacion string Motivo por el cual se cancelo el abono
 	 **/
	public function EditarAbono
	(
		$id_abono,
		$motivo_cancelacion = null,
		$nota = null,
		$compra = null,
		$venta = null,
		$prestamo = null
	)
	{


	}
  }
