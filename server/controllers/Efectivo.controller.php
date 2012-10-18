<?php

    /**
      * Description:
      *
      *
      * Author:
      *     Manuel Garcia (manuel)
      *     Alan Gonzalez (alan)
      *     Andres Zendejas
      *
      ***/

require_once("interfaces/Efectivo.interface.php");




class EfectivoController implements IEfectivo{

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
         * Valida los parametros de la tabla billete. Regresa un string con el error en caso de encontrarse alguno.
         * De lo contrario, regresa verdadero
         */
        private static function validarParametrosBillete
        (
                $id_billete = null,
                $id_moneda = null,
                $nombre = null,
                $valor = null,
                $foto_billete = null,
                $activo = null
        )
        {
            //valida que el billete exista y que este activo
            if(!is_null($id_billete))
            {
                $billete = BilleteDAO::getByPK($id_billete);
                if(is_null($billete))
                    return "El billete ".$id_billete." no existe";
                
                if(!$billete->getActivo())
                    return "El billete ".$id_billete." no esta activo";
            }
            
            //valida que la moneda exista y que sea activa
            if(!is_null($id_moneda))
            {
                $e = self::validarParametrosMoneda($id_moneda);
                if(is_string($e))
                    return $e;
            }
            
            //valida que el nombre este en rango y que no se repita
            if(!is_null($nombre))
            {
                $e = self::validarString($nombre, 50, "nombre");
                if(is_string($e))
                    return $e;
                if(!is_null($id_billete))
                {
                    $billetes = array_diff(BilleteDAO::search( new Billete( array( "nombre" => trim($nombre) ) ) ), array(BilleteDAO::getByPK($id_billete)) );
                }
                else
                {
                    $billetes = BilleteDAO::search( new Billete( array( "nombre" => trim($nombre) ) ) );
                }
                foreach($billetes as $billete)
                {
                    if($billete->getActivo())
                        return "El nombre (".$nombre.") ya esta siendo usado por el billete ".$billete->getIdBillete();
                }
            }
            
            //valida que el valor este en rango
            if(!is_null($valor))
            {
                $e = self::validarNumero($valor, 1.8e200, "valor");
                if(is_string($e))
                    return $e;
            }
            
            //valida que la foto del billete este en rango
            if(!is_null($foto_billete))
            {
                $e = self::validarString($foto_billete, 100, "foto de billete");
                if(is_string($e))
                    return $e;
            }
            
            //valida el boleano activo
            if(!is_null($activo))
            {
                $e = self::validarNumero($activo, 1, "activo");
                if(is_string($e))
                    return $e;
            }
            
            //no se encontro error
            return true;
        }
        
        /*
         * Valida los parametros de la tabla moneda. Regresa un string con el error en caso de encontrarse alguno.
         * De lo contrario, regresa verdadero
         */
        private static function validarParametrosMoneda
        (
                $id_moneda = null,
                $nombre = null,
                $simbolo = null,
                $activa = null
        )
        {
            //valida que la moneda exista y que este activa
            if(!is_null($id_moneda))
            {
                $moneda = MonedaDAO::getByPK($id_moneda);
                if(is_null($moneda))
                    return "La moneda ".$id_moneda." no existe";
                
                if(!$moneda->getActiva())
                    return "La moneda ".$id_moneda." no esta activa";
            }
            
            //valida que el nombre sea valido y que no se repita
            if(!is_null($nombre))
            {
                $e = self::validarString($nombre, 100, "nombre");
                if(is_string($e))
                    return $e;
                
                if(!is_null($id_moneda))
                {
                    $monedas = array_diff(MOnedaDAO::search( new Moneda( array( "nombre" => trim($nombre) ) ) ), array(MonedaDAO::getByPK($id_moneda)) );
                }
                else
                {
                    $monedas = MOnedaDAO::search( new Moneda( array( "nombre" => trim($nombre) ) ) );
                }
                foreach($monedas as $moneda)
                {
                    if($moneda->getActiva())
                        return "El nombre (".$nombre.") ya esta en uso por la moneda ".$moneda->getIdMoneda();
                }
            }
            
            //valida que el simbolo este en rango
            if(!is_null($simbolo))
            {
                $e = self::validarString($simbolo, 10, "simbolo");
                if(is_string($e))
                    return $e;
            }
            
            //valida el boleano activa
            if(!is_null($activa))
            {
                $e = self::validarNumero($activa, 1, "activa");
                if(is_string($e))
                    return $e;
            }
            
            //no se encontro error
            return true;
        }
      
      
      
      
      
      
      
  
	/**
 	 *
 	 *Crea un nuevo billete, se puede utilizar para monedas tambien.
 	 *
 	 * @param nombre string Nombre del billete, puede ser el valor en texto, "cincuenta", "cien", etc.
 	 * @param valor int Valor del billete
 	 * @param id_moneda int Id de la moneda a la que pertence el billete
 	 * @param foto_billete string Url de la foto del billete
 	 * @return id_billete int Id del billete autogenerado
 	 **/
	public static function NuevoBillete
	(
		$id_moneda, 
		$nombre, 
		$valor, 
		$foto_billete = null
	)
	{  
            Logger::log("Creando nuevo billete");
            
            //se validan los parametros recibidos
            $validar = self::validarParametrosBillete(null, $id_moneda, $nombre, $valor, $foto_billete);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            //Se crea el nuevo billete y se guarda
            $billete = new Billete(
                    array(
                        "nombre"        => trim($nombre),
                        "valor"         => $valor,
                        "id_moneda"     => $id_moneda,
                        "foto_billete"  => $foto_billete,
                        "activo"        => 1
                    )
                    );
            DAO::transBegin();
            try
            {
                BilleteDAO::save($billete);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se ha podido crear el nuevo billete: ".$e);
                throw new Exception("No se ha podido crear el nuevo billete");
            }
            DAO::transEnd();
            Logger::log("Billete creado exitosamente");
            return array( "id_billete" => $billete->getIdBillete() );
	}
  
	/**
 	 *
 	 *Edita la informacion de un billete
 	 *
 	 * @param id_billete int Id del billete a editar
 	 * @param valor int Valor del billete
 	 * @param foto_billete string Url de la foto del billete
 	 * @param nombre string Nombre del billete, valor en texto, "cincuenta", "cien", etc
 	 * @param id_moneda int Id de la moneda a la que pertenece el billete
 	 **/
	public static function EditarBillete
	(
		$id_billete, 
		$foto_billete = null, 
		$id_moneda = null, 
		$nombre = null, 
		$valor = null
	)
	{  
            Logger::log("Editando billete ".$id_billete);
            
            //se validan los parametros recibidos
            $validar = self::validarParametrosBillete($id_billete,$id_moneda,$nombre,$valor,$foto_billete);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            //Los parametros que no sean nulos seran tomados como actualizacion
            $billete = BilleteDAO::getByPK($id_billete);
            if(!is_null($valor))
            {
                $billete->setValor($valor);
            }
            if(!is_null($foto_billete))
            {
                $billete->setFotoBillete($foto_billete);
            }
            if(!is_null($nombre))
            {
                $billete->setNombre(trim($nombre));
            }
            if(!is_null($id_moneda))
            {
                $billete->setIdMoneda($id_moneda);
            }
            DAO::transBegin();
            try
            {
                BilleteDAO::save($billete);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo editar el billete: ".$e);
                throw new Exception("No se pudo editar el billete");
            }
            DAO::transEnd();
            Logger::log("Billete editado exitosamente");
	}
  
	/**
 	 *
 	 *Desactiva un billete
 	 *
 	 * @param id_billete int Id del billete a desactivar
 	 **/
	public static function EliminarBillete
	(
		$id_billete
	)
	{  
            Logger::log("Eliminando el billete ".$id_billete);
            
            //Se valida que el billete exista y este activo
            $validar = self::validarParametrosBillete($id_billete);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            //Si alguna caja aun contiene uno de estos billetes, no se pueden eliminar
            $billetes_caja = BilleteCajaDAO::search( new BilleteCaja( array("id_billete" => $id_billete) ) );
            foreach($billetes_caja as $billete_caja)
            {
                if($billete_caja->getCantidad!=0)
                {
                    Logger::error("El billete no puede ser eliminado pues la caja ".$billete_caja->getIdCaja()." aun lo contiene");
                    throw new Exception("El billete no puede ser eliminado pues la caja ".$billete_caja->getIdCaja()." aun lo contiene");
                }
            }
            
            //Se desactiva el billete y se guarda
            $billete = BilleteDAO::getByPK($id_billete);
            $billete->setActivo(0);
            DAO::transBegin();
            try
            {
                BilleteDAO::save($billete);
            }
            catch(Exception $e)
            {
                DAO::transEnd();
                Logger::error("No se pudo eliminar el billete: ".$e);
                throw new Exception("No se pudo eliminar el billete");
            }
            DAO::transEnd();
            Logger::log("El billete ha sido eliminado exitosamente");
            
	}
  
	/**
 	 *
 	 *Lista los billetes de una instancia
 	 *
 	 * @param ordenar json Valor que determina el orden de la lista
 	 * @param activo bool Si este valor no es obtenido, se listaran tanto activos como inactivos, si es verdadero, se listaran solo los activos, si es falso, se listaran solo los inactivos
 	 * @return billetes json Lista de billetes
 	 **/
	public static function ListaBillete
	(
		$activo = null, 
		$ordenar = null
	)
	{  
            Logger::log("Listando billetes");
            
            //valida los parametros
            $validar = self::validarParametrosBillete(null, null, null, null, null, $activo);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
             if
             (
                     !is_null($ordenar)         &&
                     $ordenar != "id_billete"   &&
                     $ordenar != "id_moneda"    &&
                     $ordenar != "nombre"       &&
                     $ordenar != "valor"        &&
                     $ordenar != "foto_billete" &&
                     $ordenar != "activo"
             )
             {
                 Logger::error("El parametro ordenar (".$ordenar.") es invalido");
                 throw new Exception("El parametro ordenar (".$ordenar.") es invalido");
             }
             
             $billetes = array();
             if(!is_null($activo))
                 $billetes = BilleteDAO::search ( new Billete( array("activo" => $activo) ), $ordenar );
             else
                 $billetes = BilleteDAO::getAll (null,null,$ordenar);
             
             Logger::log("Se obtuvieron ".count($billetes)." billetes");
             return $billetes;
	}
  
	/**
 	 *
 	 *Crea una moneda, "pesos", "dolares", etc.
 	 *
 	 * @param nombre string Nombre de la moneda
 	 * @param simbolo string Simbolo de la moneda
 	 * @return id_moneda int Id de la moneda recien creada
 	 **/
	public static function NuevaMoneda
	(
		$nombre, 
		$simbolo
	)
	{  
            Logger::log("Creando nueva moneda");
            
            //Se validan los parametros recibidos
            $validar = self::validarParametrosMoneda(null, $nombre, $simbolo);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            //Se crea la moneda y se guarda
            $moneda = new Moneda( array( "nombre" => trim($nombre), "simbolo" => $simbolo, "activa" => 1 ) );
            DAO::transBegin();
            try
            {
                MonedaDAO::save($moneda);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se ha podido crear la moneda: ".$e);
                throw new Exception("No se ha podido crear la moneda");
            }
            DAO::transEnd();
            Logger::log("Moneda creada exitosamente");
            return array( "id_moneda" => $moneda->getIdMoneda() );
	}
  
	/**
 	 *
 	 *Edita la informacion de una moneda
 	 *
 	 * @param id_moneda int Id de la moneda a editar
 	 * @param nombre string Nombre de la moneda
 	 * @param simbolo string Simbolo de la moneda
 	 **/
	public static function EditarMoneda
	(
		$id_moneda, 
		$nombre = null, 
		$simbolo = null
	)
	{  
            Logger::log("Editando la moneda ".$id_moneda);
            
            //Se validan los parametros
            $validar = self::validarParametrosMoneda($id_moneda, $nombre, $simbolo);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            //Los parametros que no sea nulos seran tomados como actualizacion
            $moneda = MonedaDAO::getByPK($id_moneda);
            if(!is_null($nombre))
            {
                $moneda->setNombre(trim($nombre));
            }
            if(!is_null($simbolo))
            {
                $moneda->setSimbolo($simbolo);
            }
            
            DAO::transBegin();
            try
            {
                MonedaDAO::save($moneda);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo editar la moneda: ".$e);
                throw new Exception("No se pudo editar la moneda");
            }
            DAO::transEnd();
            Logger::log("Moneda editada exitosamente");
            
  
	}
  
	/**
 	 *
 	 *Desactiva una moneda
 	 *
 	 * @param id_moneda int Id de la moneda a desactivar
 	 **/
	public static function EliminarMoneda
	(
		$id_moneda
	)
	{  
            Logger::log("Eliminando la moneda ".$id_moneda);
            
            //Se valida que la moneda exista y que este activa
            $validar = self::validarParametrosMoneda($id_moneda);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            //Si algun billete o usuario tiene asignada esta moneda, no se podra eliminar
            $billetes = BilleteDAO::search( new Billete( array( "id_moneda" => $id_moneda ) ) );
            foreach($billetes as $billete)
            {
                if($billete->getActivo())
                {
                    Logger::error("La moneda no puede ser eliminada pues esta asignada al billete ".$billete->getIdBillete());
                    throw new Exception("La moneda no puede ser eliminada pues esta asignada al billete ".$billete->getIdBillete());
                }
            }
            $usuarios = UsuarioDAO::search( new Usuario( array( "id_moneda" => $id_moneda ) ) );
            foreach($usuarios as $usuario)
            {
                if($usuario->getActivo())
                {
                    Logger::error("La moneda no puede ser eliminada pues esta asignada al usuario ".$usuario->getIdUsuario());
                    throw new Exception("La moneda no puede ser eliminada pues esta asignada al usuario ".$usuario->getIdUsuario());
                }
            }
            
            //Se elimina y se guarda
            $moneda = MonedaDAO::getByPK($id_moneda);
            $moneda->setActiva(0);
            DAO::transBegin();
            try
            {
                MonedaDAO::save($moneda);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo eliminar la moneda: ".$e);
                throw new Exception("No se pudo eliminar la moneda");
            }
            DAO::transEnd();
            Logger::log("La moneda ha sido eliminada exitosamente");
	}
  
	/**
 	 *
 	 *Lista las monedas de una instancia
 	 *
 	 * @param orden json Valor que determinara el orden de la lista
 	 * @param activo bool Si este valor no es recibido, se listaran tanto activos como inactivos, si es verdadero, se listaran solo los activos, si es falso, se listaran solo los inactivos.
 	 * @return monedas json Lista de monedas
 	 **/
	public static function ListaMoneda
	(
		$activo = null,
		$orden = null
	)
	{  
            Logger::log("Listando las moendas");
            
            //Se validan los parametros
            $validar = self::validarParametrosMoneda(null, null, NULL, $activo);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            if
            (
                    !is_null($orden)        &&
                    $orden != "id_moneda"   &&
                    $orden != "nombre"      &&
                    $orden != "simbolo"     &&
                    $orden != "activa"
            )
            {
                Logger::error("La variable orden (".$orden.") no es valida");
                throw new Exception("La variable orden (".$orden.") no es valida");
            }
            
            $monedas = array();
            
            //Si se recibe el parametro activo, se usa el metodo search, de lo contrario se usa el get all
            if(!is_null($activo))
                $monedas = MonedaDAO::search ( new Moneda( array( "activa" => $activo ) ), $orden );
            else
                $monedas = MonedaDAO::getAll(null,null,$orden);
            
            Logger::log("Se obtuvieron ".count($monedas)." monedas");
            return $monedas;
            
	}




    /**
      *
      *
      * Returns:
      *     Object of type CorteDeSucursal which is the last time this happened.
      *     May return null otherwise.
      *
      ***/
    private static function UltimoCorteSucursal( $sucursal )
    {
        if( !$sucursal instanceof Sucursal )
        {
            throw new InvalidDataException( "Argument must be instance of Sucursal" );
        }
        else
        {
            $sucursal = SucursalDAO::getByPK( $sucursal );
        }

		if ( is_null( $sucursal ) )
        {
            throw new InvalidDataException( "Sucursal does not exist" );
        }

		$cortes  = CorteDeSucursalDAO::search( new CorteDeSucursal( $sucursal->AsArray( ) ), "fin", "desc"  );

		if( sizeof( $cortes ) == 0 ) return null;

		return $cortes[0];
	}


	private static function UltimoCorteCaja(VO $caja)
    {
		$c = CorteDeCajaDAO::search( new CorteDeCaja( array (
			"id_caja" => $caja->getIdCaja()
		)));

		return sizeof($c) == 0 ? NULL : $c[0];
	}



	public static function UltimoCorte( VO $empresa_sucursal_caja  )
    {

		if( $empresa_sucursal_caja instanceof Caja )
        {
			return self::UltimoCorteCaja( $empresa_sucursal_caja );
		}
        else if ( $empresa_sucursal_caja instanceof Sucursal)
        {
            return self::UltimoCorteSucursal( $empresa_sucursal_caja );
		}
        else if( $empresa_sucursal_caja instanceof Empresa )
        {

		}

		throw new InvalidDataException();
	}






    /**
      *
      *
      * Description:
      *     Logic to create a new 'CorteDeSucursal'.
      *     The start date is always the last CordeDeSucursal,
      *     or if this has never happened, then time() is used.
      *     One might create a 'CorteDeSucursal' to any given time
      *     as long as 'UltimoCordeDeSucursal()' < time() < TIME
      *
      * Returns:
      *
      ***/
	public static function NuevoCorteSucursal( $end_date = 0, $id_sucursal )
    {

		if ( $end_date > time( ) )
        {
            throw new BusinessLogicException( "You must give a time in the past." );
        }

		if ( $end_date == 0 )
        {
			$end_date = time( );
		}

        $suc = SucursalDAO::getByPK( $id_sucursal );

        if ( is_null( $suc ) )
        {
            throw new InvalidDataException( "'Sucursal' does not exist" );
        }

		$start_date = self::UltimoCorte( $suc );

		if ( is_null( $start_date ) )
        {
            //'CordeDeSucursal' has never happende, 
            //use the opening date.
			$start_date = $suc->getFechaApertura( );
		}

        ASSERT( $end_date < $start_date );

		$ingresos_por_tipo = array(
                                "BANCO" => 0.0,
                                "CAJA" => 0.0
                            );

		$ventas = VentasController::Lista( $start_date, $end_date );

		//esto regresa, total, subtotal, impuesto
		$ventasTotal = VentaDAO::TotalVentasNoCanceladasAContadoDesdeHasta( $start_date, $end_date );

		//$abonosTotal = AbonoVenta::TotalAbonosNoCanceladosDesdeHasta( $start_date, $end_date );

		/*
		foreach( $ventas as $v ){

			switch( $v->tipo_de_pago ){
				cash : 		$ingresos[ cash ] += $v->getTotal();
				banco :		$ingresos[ banco ] += $v->getTotal()
				cheque :
				default: throw new Exception();

			}
		}
		*/

        $corte = new CorteDeSucursal();
        $corte->setIdSucursal(   );
        $corte->setIdUsuario(   );
        $corte->setInicio(   );
        $corte->setFin(   );
        $corte->setFechaCorte(   );

        try
        {
            CorteDeSucursalDAO::save( $corte );
        }
        catch(Exception $e)
        {
            throw new InvalidDatabaseException($e);
        }
	}



}

