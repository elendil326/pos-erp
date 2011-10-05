<?php
require_once("CargosYAbonos.interface.php");
/**
  *
  *
  *
  **/
	
  class CargosYAbonosController implements ICargosYAbonos{

        //valida que una empresa exista y tenga su estado en activo
        private function validarEmpresa
        (
                $id_empresa
        )
        {
            Logger::log("Validando empresa");
            $empresa=EmpresaDAO::getByPK($id_empresa);
            if($empresa==null)
            {
                Logger::log("La empresa con id:".$id_empresa." no existe");
                return false;
            }
            $activo=$empresa->getActivo();
            Logger::log("La empresa con id:".$id_empresa." tiene un activo:".$activo);
            return $activo;
        }

        private function cancelarAbonoCompra
        (
                AbonoCompra $abono
        )
        {

        }

        //valida que un usuario tenga permisos
        private function validarPermisos
        (
                $id_usuario
        )
        {
            return true;
        }

        //regresa el usuario de la sesion
        private function getUsuario()
        {
            return 1;
        }

        //regresa la sucursal de la sesion
        private function getSucursal()
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
            $id_usuario=$this->getUsuario();
            if($id_usuario==-1)
            {
                Logger::error("No se pudo obtener el usuario de la sesion, ya inicio sesion?");
                die('{ "success" : false , "reason" : "No se pudo obtener el usuario de la sesion, ya inicio sesion?" }');
            }
            Logger::log("Se obtuvo el usuario ".$id_usuario." de la sesion");
            if(!$this->validarPermisos($id_usuario))
            {
                Logger::error("El usuario ".$id_usuario." no tiene el permiso");
                die('{ "success" : false , "reason" : "El usuario '.$id_usuario.' no tiene el permiso" }');
            }
            if(!$this->validarEmpresa($id_empresa))
            {
                Logger::error("Se recibio una empresa no valida");
                die('{ "success" : false , "reason" : "Se recibio una empresa no valida" }');
            }
            if($id_concepto_ingreso!=null)
            {
                Logger::log("Validando concepto de ingreso");
                $concepto_ingreso=ConceptoIngresoDAO::getByPK($id_concepto_ingreso);
                if($concepto_ingreso==null)
                {
                    Logger::log("El concepto de ingreso con id:".$id_concepto_ingreso." no existe");
                    die('{ "success" : false , "reason" : "El concepto de ingreso con id:'.$id_concepto_ingreso.' no existe" }');
                }
            }
            if($monto==null)
            {
                Logger::log("No se recibio monto, se procede a buscar en el concepto de ingreso");
                if($id_concepto_ingreso==null)
                {
                    Logger::error("No se recibio un concepto de ingreso");
                    die('{ "success" : false , "reason" : "No se recibio un concepto de ingreso ni un monto" }');
                }
                $monto=$concepto_ingreso->getMonto();
                if($monto==null)
                {
                    Logger::error("El concepto de ingreso recibido no cuenta con un monto");
                    die('{ "success" : false , "reason" : "El concepto de ingreso recibido no cuenta con un monto" }');
                }
                Logger::log("El monto obtenido para este ingreso es:".$monto);
            }
            $id_sucursal=$this->getSucursal();
            if($id_sucursal==-1)
            {
                Logger::error("No se pudo obtener la sucursal en la que se realiza el ingreso");
                die('{ "success" : false , "reason" : "No se pudo obtener la sucursal en la que se realiza el ingreso" }');
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
            $ingreso->setFechaDeRegistro(date("Y-m-d H:i:s", time()));
            IngresoDAO::transBegin();
            try
            {
                IngresoDAO::save($ingreso);
            }
            catch(Exception $e)
            {
                Logger::error("Error al guardar el nuevo ingreso:".$e);
                IngresoDAO::transRollback();
                die('{ "success" : false , "reason" : "Error al guardar el nuevo ingreso:'.$e.'" }');
            }
            IngresoDAO::transEnd();
            Logger::log("Ingreso creado exitosamente!");
            printf('{ "success" : true , "id_ingreso" : %d }',$ingreso->getIdIngreso());
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
                $prestamo = null
	)
	{
            Logger::log("Cancelando abono");
            if($compra!=null&&$compra)
            {
                Logger::log("El abono es un abono a una compra");
                $abono=AbonoCompraDAO::getByPK($id_abono);
                Logger::log("Validando abono");
                if($abono==null)
                {
                    Logger::error("El abono con id:".$id_abono." no existe");
                    die('{ "success" : false , "reason" : "El abono con id:'.$id_abono.' no existe" }');
                }
                $abono->setCancelado(1);
                AbonoCompraDAO::transBegin();
                try {
                    AbonoCompraDAO::save($abono);
                    $this->cancelarAbonoCompra($abono);
                }
                catch(Exception $e)
                {
                    AbonoCompraDAO::transRollback();
                    Logger::error("Error al cancelar el abono:".$e);
                    die('{ "success" : false , "reason" : "Error al cancelar el abono:'.$e.'" }');
                }
                AbonoCompraDAO::transEnd();

            }
            else if($venta!=null&&$venta)
            {

            }
            else if($prestamo!=null&&$prestamo)
            {

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
		$id_caja = null, 
		$id_usuario = null, 
		$orden = null, 
		$id_sucursal = null, 
		$id_empresa = null
	)
	{  
  
  
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
		$monto_maximo = null
	)
	{  
  
  
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
		$nombre, 
		$id_concepto_gasto, 
		$monto = null, 
		$descripcion = null
	)
	{  
  
  
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
		$nombre, 
		$id_concepto_ingreso, 
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
