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
		$fecha_ingreso,
		$id_empresa,
		$monto = null,
		$id_sucursal = null,
		$id_concepto_ingreso = null,
		$id_caja = null,
		$folio = null,
		$nota = null,
		$descripcion = null
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
            if($monto==null)
            {
                Logger::log("No se recibio monto, se procede a buscar en el concepto de ingreso");
                if($id_concepto_ingreso==null)
                {
                    Logger::error("No se recibio un concepto de ingreso");
                    throw new Exception("No se recibio un concepto de ingreso ni un monto");
                }
                $monto=$concepto_ingreso->getMonto();
                if($monto==null)
                {
                    Logger::error("El concepto de ingreso recibido no cuenta con un monto");
                    throw new Exception("El concepto de ingreso recibido no cuenta con un monto ni se recibio un monto");
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
            if($compra!=null&&$compra)
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
                DAO::transBegin();
                try {
                    AbonoCompraDAO::save($abono);
                    $this->cancelarAbonoCompra($abono,$id_caja,$billetes);
                }
                catch(Exception $e)
                {
                    DAO::transRollback();
                    Logger::error("Error al cancelar el abono:".$e);
                    throw new Exception("Error al cancelar el abono:".$e);
                }
                DAO::transEnd();
                Logger::log("Abono cancelado exitosamente");
            }
            else if($venta!=null&&$venta)
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
                    Logger::error("Error al cancelar el abono:".$e);
                    throw new Exception("Error al cancelar el abono:".$e);
                }
                DAO::transEnd();
                Logger::log("Abono cancelado exitosamente");
            }
            else if($prestamo!=null&&$prestamo)
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
                    Logger::error("Error al cancelar el abono:".$e);
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
                throw new Exception("FATAL!!!! Este abono apunta a una compra que no existe!!");
            }
            $usuario=UsuarioDAO::getByPK($compra->getIdVendedorCompra());
            if($usuario==null)
            {
                throw new Exception("FATAL!!!! La compra de este abono no tiene un vendedor");
            }
            $monto=$abono->getMonto();
            $usuario->setSaldoDelEjercicio($usuario->getSaldoDelEjercicio()-$monto);
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
                $caja=CajaDAO::getByPK($id_caja);
                if($caja==null)
                {
                    throw new Exception("La caja especificada no existe");
                }
                if(!$caja->getAbierta())
                {
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
                        throw new Exception("No se recibieron los billetes para esta caja");
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
                    //en esa caja, actulizas sus cantidad.
                    //
                    try
                    {
                        for($i=0;$i<$numero_billetes;$i++)
                        {
                            $billete_caja_original=BilleteCajaDAO::getByPK($billete_caja[$i]->getIdBillete(), $billete_caja[$i]->getIdCaja());
                            if($billete_caja_original==null)
                                BilleteCajaDAO::save($billete_caja[$i]);
                            else
                            {
                                $billete_caja_original->setCantidad($billete_caja_original->getCantidad()+$billete_caja[$i]->getCantidad());
                                BilleteCajaDAO::save($billete_caja_original);
                            }
                        }
                    }
                    catch(Exception $e)
                    {
                        throw $e;
                    }
                }
                //
                //Actualizas el saldo de la caja a la que ira el dinero
                //
                $caja->setSaldo($caja->getSaldo()+$monto);
                try
                {
                    CajaDAO::save($caja);
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
            try
            {
                UsuarioDAO::save($usuario);
                CompraDAO::save($compra);
            }
            catch(Exception $e)
            {
                throw $e;
            }
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
                throw new Exception("FATAL!!!! Este abono apunta a una venta que no existe!!");
            }
            $usuario=UsuarioDAO::getByPK($venta->getIdCompradorVenta());
            if($usuario==null)
            {
                throw new Exception("FATAL!!!! La venta de este abono no tiene un comprador");
            }
            $monto=$abono->getMonto();
            $usuario->setSaldoDelEjercicio($usuario->getSaldoDelEjercicio()+$monto);
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
                $caja=CajaDAO::getByPK($id_caja);
                if($caja==null)
                {
                    throw new Exception("La caja especificada no existe");
                }
                if(!$caja->getAbierta())
                {
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
                        throw new Exception("No se recibieron los billetes para esta caja");
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
                    //en esa caja, actulizas sus cantidad.
                    //
                    try
                    {
                        for($i=0;$i<$numero_billetes;$i++)
                        {
                            $billete_caja_original=BilleteCajaDAO::getByPK($billete_caja[$i]->getIdBillete(), $billete_caja[$i]->getIdCaja());
                            if($billete_caja_original==null)
                                BilleteCajaDAO::save($billete_caja[$i]);
                            else
                            {
                                $billete_caja_original->setCantidad($billete_caja_original->getCantidad()-$billete_caja[$i]->getCantidad());
                                BilleteCajaDAO::save($billete_caja_original);
                            }
                        }
                    }
                    catch(Exception $e)
                    {
                        throw $e;
                    }
                }
                //
                //Actualizas el saldo de la caja a la que ira el dinero
                //
                $caja->setSaldo($caja->getSaldo()-$monto);
                try
                {
                    CajaDAO::save($caja);
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
            try
            {
                UsuarioDAO::save($usuario);
                VentaDAO::save($venta);
            }
            catch(Exception $e)
            {
                throw $e;
            }
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
                throw new Exception("FATAL!!!! El prestamo de este abono no tiene un solicitante");
            }
            $monto=$abono->getMonto();
            if($id_solicitante>0)
                $solicitante->setSaldoDelEjercicio($solicitante->getSaldoDelEjercicio()+$monto);
            $prestamo->setSaldo($prestamo->getSaldo()-$monto);
            //
            //Un Prestamo no puede ser cancelado, solo liquidado.
            //
            //Si no hay una caja, se tomara el dinero como ganado.
            //
            if($id_caja!=null)
            {
                $caja=CajaDAO::getByPK($id_caja);
                if($caja==null)
                {
                    throw new Exception("La caja especificada no existe");
                }
                if(!$caja->getAbierta())
                {
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
                        throw new Exception("No se recibieron los billetes para esta caja");
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

                    try
                    {
                        for($i=0;$i<$numero_billetes;$i++)
                        {

                            $billete_caja_original=BilleteCajaDAO::getByPK($billete_caja[$i]->getIdBillete(), $billete_caja[$i]->getIdCaja());
                            if($billete_caja_original==null)
                                BilleteCajaDAO::save($billete_caja[$i]);
                            else
                            {
                                $billete_caja_original->setCantidad($billete_caja_original->getCantidad()-$billete_caja[$i]->getCantidad());
                                BilleteCajaDAO::save($billete_caja_original);
                            }
                        }
                    }
                    catch(Exception $e)
                    {
                        throw $e;
                    }
                }
                //
                //Actualizas el saldo de la caja a la que ira el dinero
                //
                $caja->setSaldo($caja->getSaldo()-$monto);
                try
                {
                    CajaDAO::save($caja);
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
            try
            {
                if($id_solicitante>0)
                    UsuarioDAO::save($solicitante);
                PrestamoDAO::save($prestamo);
            }
            catch(Exception $e)
            {
                throw $e;
            }
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
                            $abono_criterio_compra2->setMonto(1.8e308);
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
                            $abono_criterio_venta2->setMonto(1.8e308);
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
                            $abono_criterio_prestamo2->setMonto(1.8e308);
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
		$motivo_cancelacion = null
	)
	{
            Logger::log("Eliminando gasto");
            $gasto=GastoDAO::getByPK($id_gasto);
            if($gasto)
            {
                if($gasto->getCancelado())
                {
                    Logger::log("El gasto ya ha sido cancelado");
                    return;
                }
                $gasto->setCancelado(1);
                $gasto->setMotivoCancelacion($motivo_cancelacion);
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
            else
            {
                Logger::error("El gasto con id:".$id_gasto." no existe");
                throw new Exception("El gasto con id:".$id_gasto." no existe");
            }
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
		$motivo_cancelacion = null
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
		$ordenar = null
	)
	{


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
		$ordenar = null
	)
	{


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
		$nota = null
	)
	{


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
		$fecha_gasto,
		$monto = null,
		$id_concepto_gasto = null,
		$descripcion = null,
		$nota = null,
		$folio = null
	)
	{


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
		$fecha_ingreso,
		$id_ingreso,
		$descrpicion = null,
		$folio = null,
		$nota = null,
		$id_concepto_ingreso = null,
		$monto = null
	)
	{


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
		$id_deudor,
		$tipo_pago,
		$monto,
		$nota = null,
		$id_venta = null,
		$varios = null,
		$cheques = null,
		$id_prestamo = null,
		$id_compra = null
	)
	{


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
		$nota = null,
		$motivo_cancelacion = null
	)
	{


	}
  }
