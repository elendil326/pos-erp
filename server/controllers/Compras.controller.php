<?php
require_once("interfaces/Compras.interface.php");
/**
  *
  *
  *
  **/
	
  class ComprasController extends ValidacionesController implements ICompras{
	
      
        
        
        /*
         * Valida los parametros de la tabla compra_arpilla. Regresa un string con el error en caso de 
         * encontrarse alguno, de lo contrario regresa verdadero
         */
        private static function validarParametrosCompraArpilla
        (
                $id_compra = null,
                $peso_origen = null,
                $folio = null,
                $numero_de_viaje = null,
                $peso_recibido = null,
                $arpillas = null,
                $peso_por_arpilla = null,
                $productor = null,
                $merma_por_arpilla = null,
                $total_origen = null
        )
        {
            //valida que la compra exista y que este activa
            if(!is_null($id_compra))
            {
                $compra = CompraDAO::getByPK($id_compra);
                if(is_null($compra))
                    return "La compra ".$id_compra." no existe";
                
                if($compra->getCancelada())
                    return "La compra ".$id_compra." ya esta cancelada";
            }
            
            //valida el peso de origen
            if(!is_null($peso_origen))
            {
                $e = self::validarNumero($peso_origen, 1.8e200, "peso de origen");
                if(is_string($e))
                    return $e;
            }
            
            //valida el folio
            if(!is_null($folio))
            {
                $e = self ::validarString($folio, 11, "folio");
                if(is_string($e))
                    return $e;
            }
            
            //valida el numero de viaje
            if(!is_null($numero_de_viaje))
            {
                $e = self::validarString($numero_de_viaje, 11, "numero de viaje");
                if(is_string($e))
                    return $e;
            }
            
            //valida el peso recibido
            if(!is_null($peso_recibido))
            {
                $e = self::validarNumero($peso_recibido, 1.8e200, "peso recibido");
                if(is_string($e))
                    return $e;
            }
            
            //valida el numero de arpillas
            if(!is_null($arpillas))
            {
                $e = self::validarNumero($arpillas, 1.8e200, "arpillas");
                if(is_string($e))
                    return $e;
            }
            
            //valida el peso por arpilla
            if(!is_null($peso_por_arpilla))
            {
                $e = self::validarNumero($peso_por_arpilla, 1.8e200, "peso por arpilla");
                if(is_string($e))
                    return $e;
            }
            
            //valida el productor
            if(!is_null($productor))
            {
                $e = self::validarString($productor, 64, "productor");
                if(is_string($e))
                    return $e;
            }
            
            //valida la merma por arpilla
            if(!is_null($merma_por_arpilla))
            {
                $e = self::validarNumero($merma_por_arpilla, 1.8e200, "merma por arpilla");
                if(is_string($e))
                    return $e;
            }
            
            //valida el total de origen
            if(!is_null($total_origen))
            {
                $e = self::validarNumero($total_origen, 1.8e200, "Total de origen");
                if(is_string($e))
                    return $e;
            }
            
            //No se encontro error
            return true;
        }
        
        
        
        
        
        
        
        
        
        
      
      
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
	public static function Lista
	(
		$cancelada = null, 
		$fecha_final = null, 
		$fecha_inicial = null, 
		$id_caja = null, 
		$id_empresa = null, 
		$id_sucursal = null, 
		$id_usuario = null, 
		$id_vendedor_compra = null, 
		$orden = null, 
		$saldada = null, 
		$tipo_compra = null, 
		$tipo_pago = null, 
		$total_maximo = null, 
		$total_minimo = null
	)
	{
            Logger::log("Listando compras");
            
            //Se valida que el parametro orden sea correcto
            if
            (
                    !is_null($orden)                &&
                    $orden != "id_compra"           &&
                    $orden != "id_caja"             &&
                    $orden != "id_compra_caja"      &&
                    $orden != "id_vendedor_compra"  &&
                    $orden != "tipo_compra"         &&
                    $orden != "fecha"               &&
                    $orden != "subtotal"            &&
                    $orden != "impuesto"            &&
                    $orden != "descuento"           &&
                    $orden != "total"               &&
                    $orden != "id_sucursal"         &&
                    $orden != "id_usuario"          &&
                    $orden != "id_empresa"          &&
                    $orden != "saldo"               &&
                    $orden != "cancelada"           &&
                    $orden != "tipo_de_pago"        &&
                    $orden != "retencion"
            )
            {
                Logger::error("EL parametro orden (".$orden.") no es valido");
                throw new Exception("EL parametro orden (".$orden.") no es valido",901);
            }
            
            //Revisa si se recibieron parametros o no para saber cual metodo usar
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
                
                //objetos que serviran para obtener un rango de valores
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
                
                //revisa los rangos recibidos y se asignan en conjunto con limites para obtener el rango deseado
                if($fecha_inicial!=null)
                {
                    $compra_criterio_1->setFecha($fecha_inicial);
                    if($fecha_final!=null)
                    {
                        $compra_criterio_2->setFecha($fecha_final);
                    }
                    else
                    {
                        $compra_criterio_2->setFecha( time());
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
            
            //Si se recibe el parametro saldada, se tiene que hace un filtro extra donde solo se incluyan las compras 
            //con ese valor de saldadada
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
	public static function ArpillaCompraNueva
	(
		$arpillas, 
		$id_compra, 
		$merma_por_arpilla, 
		$peso_por_arpilla, 
		$peso_recibido, 
		$total_origen, 
		$fecha_origen = null, 
		$folio = null, 
		$numero_de_viaje = null, 
		$peso_origen = null, 
		$productor = null
	)
	{
            Logger::log("Registrando compras de arpillas");
            
            //Se validan los parametros recibidos
            $validar = self::validarParametrosCompraArpilla($id_compra,$peso_origen,$folio,
                    $numero_de_viaje,$peso_recibido,$arpillas,$peso_por_arpilla,$productor,$merma_por_arpilla,$total_origen);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar,901);
            }
            
            //Se asignan los valores y se guarda el nuevo registro
            $compra_arpilla=new CompraArpilla();
            $compra_arpilla->setPesoPorArpilla($peso_por_arpilla);
            $compra_arpilla->setArpillas($arpillas);
            $compra_arpilla->setPesoRecibido($peso_recibido);
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
                Logger::error("No se pudo guardar la compra de arpillas: ".$e);
                throw new Exception("No se pudo guardar la compra de arpillas, consulte a su administrador de sistema",901);
            }
            DAO::transEnd();
            Logger::log("Se registro la compra de arpillas con exito ");
            return array( "id_compra_arpilla" => $compra_arpilla->getIdCompraArpilla());
	}

	/**
 	 *
 	 *Cancela una compra
 	 *
 	 * @param id_compra int Id de la compra a cancelar
 	 **/
	public static function Cancelar
	(
		$id_compra, 
		$billetes = null, 
		$id_caja = null
	)
	{
            Logger::log("Cancenlando compra ".$id_compra);
            
            //valida que la compra exista y que este activa
            $compra=CompraDAO::getByPK($id_compra);
            if($compra==null)
            {
                Logger::error("La compra con id: ".$id_compra." no existe");
                throw new Exception("La compra con id: ".$id_compra." no existe",901);
            }
            if($compra->getCancelada())
            {
                Logger::warn("La compra ya ha sido cancelada");
                return;
            }
            
            //Obtiene al usuario al que se le compro
            $usuario=UsuarioDAO::getByPK($compra->getIdVendedorCompra());
            if($usuario==null)
            {
                Logger::error("FATAL!!! Esta compra apunta a un usuario que no existe");
                throw new Exception("FATAL!!! Esta compra apunta a un usuario que no existe",901);
            }
            
            //Deja la compra como cancelada y la guarda. 
            $compra->setCancelada(1);
            DAO::transBegin();
            try
            {
                CompraDAO::save($compra);
                
                //Si la compra fue a credito, se cancelan todos los abonos hechos al mismo y el dinero se queda a cuenta del usuario.
                if($compra->getTipoDeCompra()=="credito")
                {
                    $abono_compra=new AbonoCompra();
                    $abono_compra->setIdCompra($id_compra);
                    $abonos=AbonoCompraDAO::search($abono_compra);
                    foreach($abonos as $abono)
                    {
                        if(!$abono->getCancelado())
                            CargosYAbonosController::EliminarAbono($abono->getIdAbonoCompra(),"Compra cancelada",1,0,0,null,null);
                    }
                    $usuario->setSaldoDelEjercicio($usuario->getSaldoDelEjercicio()-$compra->getTotal());
                    UsuarioDAO::save($usuario);
                }
                
                //Si la compra fue de contado y se tiene la caja a la que regresera el dinero, se modifica dicha caja.
                else if($compra->getTipoDeCompra()=="contado" && !is_null($id_caja))
                {
                    CajasController::modificarCaja($id_caja, 1, $billetes, $compra->getTotal());
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo cancelar la compra: ".$e);
                if($e->getCode()==901)
                    throw new Exception("No se pudo cancelar la compra: ".$e->getMessage(),901);
                throw new Exception("No se pudo cancelar la compra, consulte a su administrador de sistema",901);
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
	public static function Detalle
	(
		$id_compra
	)
	{  
            Logger::log("Listando el detalle de la compra ".$id_compra);
            
            //Se regresara un arreglo que contendra en la primera posicion la compra en sí, en la segunda
            //contendra otro arreglo de arreglos, estos arreglos en la primera posicion tendran un arreglo con la informacion del producto,
            //en la segunda un arreglo con la informacion de la unidad, y en las siguientes posiciones la demas informacion
            //del detalle (cantidad, precio, descuento, etc.)
            //
            //En la tercera posicion contendra todos los abonos realizados para esta compra
            
            $detalle = array();
            
            array_push($detalle, CompraDAO::getByPK($id_compra));
            
            $compras_productos = array();
            
            $compra_productos = CompraProductoDAO::search( new CompraProducto( array("id_compra" => $id_compra) ) );
            foreach($compra_productos as $c_p)
            {
                $compra_producto = array();
                array_push($compra_producto,ProductoDAO::getByPK($c_p->getIdProducto()));
                array_push($compra_producto,UnidadDAO::getByPK($c_p->getIdUnidad()));
                array_push($compra_producto,$c_p->getCantidad());
                array_push($compra_producto,$c_p->getPrecio());
                array_push($compra_producto,$c_p->getDescuento());
                array_push($compra_producto,$c_p->getImpuesto());
                array_push($compra_producto,$c_p->getRetencion());
                array_push($compras_productos,$compra_producto);
            }
            
            array_push($detalle, $compras_productos);
            
            array_push($detalle, AbonoCompraDAO::search(new AbonoCompra( array( "id_compra" => $id_compra ) )));
            
            
            Logger::log("Detalle obtenido exitosamente");
            return $detalle;
	}
  
	/**
 	 *
 	 *Muestra el detalle de una compra por arpillas. Este detalle no es el detalle por producto, este muestra los detalles por embarque de la compra. Para el detalle por producto refierase a api/compras/detalle

Update : Todo este metodo esta mal, habria que definir nuevamente como se van a manejar las compras a los proveedores ya que como esta definido aqui solo funcionaria para el POS de las papas.
 	 *
 	 * @param id_compra int Id de la compra de la que se detallaran las compras por arpilla
 	 * @return detalle_compra_arpilla json Objeto que contendr� la informaci�n del detalle de la compra
 	 **/
	public static function ArpillaCompraDetalle
	(
		$id_compra
	)
	{  
            Logger::log("Mostrando detalle de la compra por arpillas");
            $detalle_compra_arpilla = CompraArpillaDAO::search( new CompraArpilla( array( "id_compra" => $id_compra ) ) );
            return $detalle_compra_arpilla;
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
	public static function Nueva
	(
		$descuento, 
		$detalle, 
		$id_empresa, 
		$id_usuario_compra, 
		$impuesto, 
		$retencion, 
		$subtotal, 
		$tipo_compra, 
		$total, 
		$cheques = null, 
		$id_sucursal = null, 
		$saldo = 0, 
		$tipo_de_pago = null
	)
	{  
            Logger::log(" ===== Creando nueva compra... ===== ");
			
			
			//validemos al comprador
			$proveedor = UsuarioDAO::getByPK($id_usuario_compra);
			
			if(is_null($proveedor)){
				Logger::error("el provedor $id_usuario_compra no exite");
				throw new InvalidDataException("El proveedor no existe");
			}
			
			
			if($proveedor->getActivo() == false){
				throw new BusinessLogicException("No se puede comprar de un proveedor no activo.");
			}
			
			//validemos la empresa
			$empresa = EmpresaDAO::getByPK( $id_empresa );
			
			if(is_null($empresa)){
				Logger::error("La empresa $id_empresa no existe");
				throw new InvalidDataException("La empresa que compra no existe.");
			}
			
			if($empresa->getActivo() == false){
				throw new BusinessLogicException("Una empresa inactiva no puede hacer compras.");
			}
			
			
			
			
			//validemos los valores conocidos
			//( 0 >= descuento > 100, subtotal > 0, total >= subtotal, etc etc)
			
			
			//validemos sucursal
			$sucursal = null;
			
			if(!is_null($id_sucursal)){
				$sucursal = SucursalDAO::getByPK($id_sucursal);
				
				if(is_null($sucursal)){
					Logger::error("La sucursal $id_sucursal no existe");
					//throw new InvalidDataException("La sucural que se envio no existe.");
				}
			}
			
			
			
			
			//validemos detalles de compra
			//debe traer 
			// 	-id_producto
			//	-cantidad
			//	-precio
			//	-lote
           	if(!is_array($detalle)){
           		throw InvalidDataException("El detalle no es un arreglo");
           	}
			
			
			for ($detalleIterator=0; $detalleIterator < sizeof($detalle); $detalleIterator++) { 
				
				//por cada producto
		   		//	-debe existir
		   		//	-si se lo compro a un proveedor no hay pedo
		   		// 	 si se lo compro a un cliente, debe de tener comprar_caja = 1
		   		//	-debe tener cantidad mayor a 0
		   		//	-que exista el lote a donde va a ir
				$p = $detalle[$detalleIterator];
				
				if(!isset($p->precio)){
					throw new InvalidArgumentException("No se envio el precio");
				}
				
				if(!isset($p->id_producto)){
					throw new InvalidArgumentException("No se envio el id_producto");
				}
				
				if(!isset($p->cantidad)){
					throw new InvalidArgumentException("No se envio la cantidad");
				}
				
				if(!isset($p->lote)){
					throw new InvalidArgumentException("No se envio el lote");
				}
				
				
				$producto = ProductoDAO::getByPK($p->id_producto);
				
				if(is_null($producto)){
					throw new InvalidArgumentException("El producto a comprar no existe");
				}
				
				if($p->cantidad <= 0){
					throw new InvalidArgumentException("No puedes comprar 0 unidades");
				}
				
				
				
			}
			
			$s = SesionController::getCurrentUser();

			//terminaron las validaciones
			$compra = new Compra();
			$compra->setIdVendedorCompra	( $id_usuario_compra	);
			$compra->setTipoDeCompra		( $tipo_compra			);
			$compra->setFecha				( time()				);
			$compra->setSubtotal			( $subtotal				);
			$compra->setImpuesto			( $impuesto				);
			$compra->setDescuento			( $descuento			);
			$compra->setTotal				( $subtotal + $impuesto	);
			$compra->setIdUsuario			( $s->getIdUsuario()	);
			$compra->setIdEmpresa			( $id_empresa			);
			$compra->setSaldo				( 0						);
			$compra->setCancelada			( false					);
			$compra->setTipoDePago			( $tipo_de_pago			);
			$compra->setRetencion			( 0						);
		   	
			try{
				DAO::transBegin();
				
				CompraDAO::save($compra);
				
			}catch(Exception $e){
				DAO::transRollback();
				throw InvalidDatabaseOperationException($e);
			}
			
			
			
			
			for ($detalleIterator=0; $detalleIterator < sizeof($detalle); $detalleIterator++) { 
				
				//por cada producto
		   		//	--- procesos ---
	   			//	-insertar en productoempresa
	   			//	-insertar en loteproducto
	   			//	-insertar en entradalote
	   			//	-si el tipo de precio de venta es costo, actualizar
	   			//	-insertar compra producto
				$p = $detalle[$detalleIterator];
				
				try{
					
					ProductoEmpresaDAO::save( new ProductoEmpresa( array(
							"id_empresa" => $id_empresa,
							"id_producto" => $p->id_producto
						) ) );
						
					
					//busquemos el id del lote
					$l = LoteDAO::search(new Lote(array(
							"folio" => $p->lote
						)));
						
					$l = $l[0];
					
					
					//busequemos si este producto ya existe en este lote
					$lp = LoteProductoDAO::getByPK($l->getIdLote(), $p->id_producto);
					
					if(is_null($lp)){
						//no existe, insertar
						$loteProducto = new LoteProducto(array(
								"id_lote" 		=> $l->getIdLote(),
								"id_producto" 	=> $p->id_producto,
								"cantidad" 		=> $p->cantidad, 
								"id_unidad"		=> 1
							) );
							
						LoteProductoDAO::save ( $loteProducto);
											
					}else{
						//ya existe, sumar
						
						//Aqui falta revisar que las unidades sean las mismas
						Logger::warn("Aqui falta revisar que las unidades sean las mismas...");
						$lp->setCantidad( $lp->getCantidad() + $p->cantidad );
						LoteProductoDAO::save( $lp );
						
					}
					

					
					$loteEntrada = new LoteEntrada(array(
							"id_lote" 		=>$l->getIdLote(), 
							"id_usuario"	=>$s->getIdUsuario(),
							"fecha_registro"=>time(),
							"motivo"		=>"Compra a Proveedor"
						) );
						
						
					LoteEntradaDAO::save ( $loteEntrada );						

					LoteEntradaProductoDAO::save (new LoteEntradaProducto(array(
							"id_lote_entrada" 	=> $loteEntrada->getIdLoteEntrada(),
							"id_producto"		=> $p->id_producto,
							"id_unidad"			=> 1,
							"cantidad"			=> $p->cantidad
						) ) );
						
					$compraProducto = new CompraProducto(array(
							"id_compra"			=> $compra->getIdCompra(),
							"id_producto"		=> $p->id_producto,
							"cantidad"			=> $p->cantidad,
							"precio"			=> $p->precio,
							"descuento"			=> 0,
							"impuesto"			=> 0,
							"id_unidad"			=> 1,
							"retencion"			=> 0
						) );
						
					CompraProductoDAO::save ( $compraProducto);
					
				}catch(exception $e){
					DAO::transRollback();
					throw InvalidDatabaseOperationException($e);
					
				}
				
				
				
			}			
			

		   		
		   try{
				DAO::transEnd();

			}catch(Exception $e){
				throw InvalidDatabaseOperationException($e);
			}
		   
		   
		   
		   
		   
			Logger::log("===== COMPRA ". $compra->getIdCompra() ." EXITOSA ===== ");
			
			return array(
					"id_compra" => $compra->getIdCompra()
				);
	}
  }
