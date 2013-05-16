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
            $v_sucursal = SucursalDAO::getByPK( $sucursal->getIdSucursal( ) );
        }

		if ( is_null( $v_sucursal ) )
        {
            throw new InvalidDataException( "Sucursal does not exist" );
        }

		$cortes  = CorteDeSucursalDAO::search( new CorteDeSucursal( $v_sucursal->AsArray( ) ), "fin", "desc"  );

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

       
	}
    
    /**
    *
    * Obtendrá los tipos de cambio desde Yahoo o Google según sea el caso
    **/
    public static function ObtenerTiposCambioDesdeServicio()
    {

        global $POS_CONFIG;

        $vals = false;

        $sql = "select * from tipos_cambio";

        $res = $POS_CONFIG["CORE_CONN"]->GetArray();

        $servicio="Google";//inicialmente se elige google

        foreach ($res as $ins) {
            $json = json_decode($ins['json_equivalencias']);
            $servicio = $json->servicio;
            break;
        }
        /*
        VER COMO SACAR LOS ID:MONEDA BASE DE LAS DIFERENTES EMPRESAS DADO QUE MANE YA LO SEPARÓ EN DIFERENTES BD (instancias)
        $id_y_codigo_monedas_base_empresas
        */
        $id_y_codigo_monedas_base_empresas;//las diferente monedas base en un array ya lleno
        $jsons_respuestas = array();

        if($servicio=="Google"){
            foreach ($id_y_codigo_monedas_base_empresas as $moneda) {
                $json_resp = self::ObtenerTiposCambioDesdeServicioGoogle($moneda["codigo"]);
                array_push($jsons_respuestas,"id_moneda_base"=>$moneda["id_moneda_base"],"codigo_moneda_base"=>$moneda["codigo"],"JSON"=> $json_resp);
            }
        }
        else {
            foreach ($id_y_codigo_monedas_base_empresas as $moneda) {
                $json_resp = self::ObtenerTiposCambioDesdeServicioYahoo($moneda["codigo"]);
                array_push($jsons_respuestas,"id_moneda_base"=>$moneda["id_moneda_base"],"codigo_moneda_base"=>$moneda["codigo"],"JSON"=> $json_resp);
            }
        }

        foreach ($jsons_respuestas as $resp) {
            $sql = "Select * from tipos_cambio where id_moneda_base = ".$resp["id_moneda_base"]." and codigo_moneda_base='".$resp["codigo_moneda_base"]."';";
            $res = $POS_CONFIG["CORE_CONN"]->GetRow($sql);
            $sql2="";
            if (count($res) === 0) 
            {
                $sql2 = "INSERT INTO `tipos_cambio` (`json_equivalencias`) VALUES ( ? );";
                $POS_CONFIG["CORE_CONN"]->Execute($sql2, array($resp["JSON"]));
            } else {
                $sql2 = "UPDATE  `tipos_cambio` SET  `json_equivalencias` =  ? Where `id_moneda_base` = ? AND `codigo_moneda_base` = ?;";
                $POS_CONFIG["CORE_CONN"]->Execute($sql2, array($resp["JSON"], $res["id_moneda_base"], $res["codigo_moneda_base"]));
            }
        }
    }

    /**
    *
    * Obtendrá los tipos de cambio desde Google
    **/
    public static function ObtenerTiposCambioDesdeServicioGoogle($moneda_origen)
    {
        $default_targets = array(
            "AED" => "United Arab Emirates Dirham (AED)",
            "ANG" => "Netherlands Antillean Guilder (ANG)",
            "ARS" => "Argentine Peso (ARS)",
            "AUD" => "Australian Dollar (A$)",
            "BDT" => "Bangladeshi Taka (BDT)",
            "BGN" => "Bulgarian Lev (BGN)",
            "BHD" => "Bahraini Dinar (BHD)",
            "BND" => "Brunei Dollar (BND)",
            "BOB" => "Bolivian Boliviano (BOB)",
            "BRL" => "Brazilian Real (R$)",
            "BWP" => "Botswanan Pula (BWP)",
            "CAD" => "Canadian Dollar (CA$)",
            "CHF" => "Swiss Franc (CHF)",
            "CLP" => "Chilean Peso (CLP)",
            "CNY" => "Chinese Yuan (CN¥)",
            "COP" => "Colombian Peso (COP)",
            "CRC" => "Costa Rican Colón (CRC)",
            "CZK" => "Czech Republic Koruna (CZK)",
            "DKK" => "Danish Krone (DKK)",
            "DOP" => "Dominican Peso (DOP)",
            "DZD" => "Algerian Dinar (DZD)",
            "EEK" => "Estonian Kroon (EEK)",
            "EGP" => "Egyptian Pound (EGP)",
            "EUR" => "Euro (€)",
            "FJD" => "Fijian Dollar (FJD)",
            "GBP" => "British Pound Sterling (£)",
            "HKD" => "Hong Kong Dollar (HK$)",
            "HNL" => "Honduran Lempira (HNL)",
            "HRK" => "Croatian Kuna (HRK)",
            "HUF" => "Hungarian Forint (HUF)",
            "IDR" => "Indonesian Rupiah (IDR)",
            "ILS" => "Israeli New Sheqel (₪)",
            "INR" => "Indian Rupee (Rs.)",
            "JMD" => "Jamaican Dollar (JMD)",
            "JOD" => "Jordanian Dinar (JOD)",
            "JPY" => "Japanese Yen (¥)",
            "KES" => "Kenyan Shilling (KES)",
            "KRW" => "South Korean Won (₩)",
            "KWD" => "Kuwaiti Dinar (KWD)",
            "KYD" => "Cayman Islands Dollar (KYD)",
            "KZT" => "Kazakhstani Tenge (KZT)",
            "LBP" => "Lebanese Pound (LBP)",
            "LKR" => "Sri Lankan Rupee (LKR)",
            "LTL" => "Lithuanian Litas (LTL)",
            "LVL" => "Latvian Lats (LVL)",
            "MAD" => "Moroccan Dirham (MAD)",
            "MDL" => "Moldovan Leu (MDL)",
            "MKD" => "Macedonian Denar (MKD)",
            "MUR" => "Mauritian Rupee (MUR)",
            "MVR" => "Maldivian Rufiyaa (MVR)",
            "MXN" => "Mexican Peso (MX$)",
            "MYR" => "Malaysian Ringgit (MYR)",
            "NAD" => "Namibian Dollar (NAD)",
            "NGN" => "Nigerian Naira (NGN)",
            "NIO" => "Nicaraguan Córdoba (NIO)",
            "NOK" => "Norwegian Krone (NOK)",
            "NPR" => "Nepalese Rupee (NPR)",
            "NZD" => "New Zealand Dollar (NZ$)",
            "OMR" => "Omani Rial (OMR)",
            "PEN" => "Peruvian Nuevo Sol (PEN)",
            "PGK" => "Papua New Guinean Kina (PGK)",
            "PHP" => "Philippine Peso (Php)",
            "PKR" => "Pakistani Rupee (PKR)",
            "PLN" => "Polish Zloty (PLN)",
            "PYG" => "Paraguayan Guarani (PYG)",
            "QAR" => "Qatari Rial (QAR)",
            "RON" => "Romanian Leu (RON)",
            "RSD" => "Serbian Dinar (RSD)",
            "RUB" => "Russian Ruble (RUB)",
            "SAR" => "Saudi Riyal (SAR)",
            "SCR" => "Seychellois Rupee (SCR)",
            "SEK" => "Swedish Krona (SEK)",
            "SGD" => "Singapore Dollar (SGD)",
            "SKK" => "Slovak Koruna (SKK)",
            "SLL" => "Sierra Leonean Leone (SLL)",
            "SVC" => "Salvadoran Colón (SVC)",
            "THB" => "Thai Baht (฿)",
            "TND" => "Tunisian Dinar (TND)",
            "TRY" => "Turkish Lira (TRY)",
            "TTD" => "Trinidad and Tobago Dollar (TTD)",
            "TWD" => "New Taiwan Dollar (NT$)",
            "TZS" => "Tanzanian Shilling (TZS)",
            "UAH" => "Ukrainian Hryvnia (UAH)",
            "UGX" => "Ugandan Shilling (UGX)",
            "USD" => "US Dollar ($)",
            "UYU" => "Uruguayan Peso (UYU)",
            "UZS" => "Uzbekistan Som (UZS)",
            "VEF" => "Venezuelan Bolívar (VEF)",
            "VND" => "Vietnamese Dong (₫)",
            "XOF" => "CFA Franc BCEAO (CFA)",
            "YER" => "Yemeni Rial (YER)",
            "ZAR" => "South African Rand (ZAR)",
            "ZMK" => "Zambian Kwacha (ZMK)"
        );
        /*
        * @unset: Remove home currency from targets
        */
        if( array_key_exists( $moneda_origen, $default_targets ) ) {
            unset( $default_targets[$moneda_origen] );
        }

        $res = new stdClass();
        $res->servicio ="Google";
        $res->fecha= date("Y-m-d H:i:s");
        $res->moneda_origen=$moneda_origen;
        $res->tipos_cambio = array();

        foreach( $default_targets as $code => $name ) {

            $url = "http://www.google.com/ig/calculator?hl=en&q=1$moneda_origen=?$code";
            $ch = curl_init();
            $timeout = 0;
            curl_setopt ($ch, CURLOPT_URL, $url);
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch,  CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
            curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $rawdata = curl_exec($ch);
            curl_close($ch);
            $data = explode('"', $rawdata);
            $data = explode(' ', $data['3']);
            $var = $data['0'];

            $obj = new stdClass();
        $obj->moneda = $code;
        $obj->equivalencia = round($var,3);
        array_push($res->tipos_cambio,$obj);
        }

        $json2 = json_encode($res);

        return $json2;
    }

    /**
    *
    * Obtendrá los tipos de cambio desde Yahoo
    **/
    public static function ObtenerTiposCambioDesdeServicioYahoo($home_currency, $convert_to = '')
    {
        $default_targets = array(
            "AED" => "United Arab Emirates Dirham (AED)",
            "ANG" => "Netherlands Antillean Guilder (ANG)",
            "ARS" => "Argentine Peso (ARS)",
            "AUD" => "Australian Dollar (A$)",
            "BDT" => "Bangladeshi Taka (BDT)",
            "BGN" => "Bulgarian Lev (BGN)",
            "BHD" => "Bahraini Dinar (BHD)",
            "BND" => "Brunei Dollar (BND)",
            "BOB" => "Bolivian Boliviano (BOB)",
            "BRL" => "Brazilian Real (R$)",
            "BWP" => "Botswanan Pula (BWP)",
            "CAD" => "Canadian Dollar (CA$)",
            "CHF" => "Swiss Franc (CHF)",
            "CLP" => "Chilean Peso (CLP)",
            "CNY" => "Chinese Yuan (CN¥)",
            "COP" => "Colombian Peso (COP)",
            "CRC" => "Costa Rican Colón (CRC)",
            "CZK" => "Czech Republic Koruna (CZK)",
            "DKK" => "Danish Krone (DKK)",
            "DOP" => "Dominican Peso (DOP)",
            "DZD" => "Algerian Dinar (DZD)",
            "EEK" => "Estonian Kroon (EEK)",
            "EGP" => "Egyptian Pound (EGP)",
            "EUR" => "Euro (€)",
            "FJD" => "Fijian Dollar (FJD)",
            "GBP" => "British Pound Sterling (£)",
            "HKD" => "Hong Kong Dollar (HK$)",
            "HNL" => "Honduran Lempira (HNL)",
            "HRK" => "Croatian Kuna (HRK)",
            "HUF" => "Hungarian Forint (HUF)",
            "IDR" => "Indonesian Rupiah (IDR)",
            "ILS" => "Israeli New Sheqel (₪)",
            "INR" => "Indian Rupee (Rs.)",
            "JMD" => "Jamaican Dollar (JMD)",
            "JOD" => "Jordanian Dinar (JOD)",
            "JPY" => "Japanese Yen (¥)",
            "KES" => "Kenyan Shilling (KES)",
            "KRW" => "South Korean Won (₩)",
            "KWD" => "Kuwaiti Dinar (KWD)",
            "KYD" => "Cayman Islands Dollar (KYD)",
            "KZT" => "Kazakhstani Tenge (KZT)",
            "LBP" => "Lebanese Pound (LBP)",
            "LKR" => "Sri Lankan Rupee (LKR)",
            "LTL" => "Lithuanian Litas (LTL)",
            "LVL" => "Latvian Lats (LVL)",
            "MAD" => "Moroccan Dirham (MAD)",
            "MDL" => "Moldovan Leu (MDL)",
            "MKD" => "Macedonian Denar (MKD)",
            "MUR" => "Mauritian Rupee (MUR)",
            "MVR" => "Maldivian Rufiyaa (MVR)",
            "MXN" => "Mexican Peso (MX$)",
            "MYR" => "Malaysian Ringgit (MYR)",
            "NAD" => "Namibian Dollar (NAD)",
            "NGN" => "Nigerian Naira (NGN)",
            "NIO" => "Nicaraguan Córdoba (NIO)",
            "NOK" => "Norwegian Krone (NOK)",
            "NPR" => "Nepalese Rupee (NPR)",
            "NZD" => "New Zealand Dollar (NZ$)",
            "OMR" => "Omani Rial (OMR)",
            "PEN" => "Peruvian Nuevo Sol (PEN)",
            "PGK" => "Papua New Guinean Kina (PGK)",
            "PHP" => "Philippine Peso (Php)",
            "PKR" => "Pakistani Rupee (PKR)",
            "PLN" => "Polish Zloty (PLN)",
            "PYG" => "Paraguayan Guarani (PYG)",
            "QAR" => "Qatari Rial (QAR)",
            "RON" => "Romanian Leu (RON)",
            "RSD" => "Serbian Dinar (RSD)",
            "RUB" => "Russian Ruble (RUB)",
            "SAR" => "Saudi Riyal (SAR)",
            "SCR" => "Seychellois Rupee (SCR)",
            "SEK" => "Swedish Krona (SEK)",
            "SGD" => "Singapore Dollar (SGD)",
            "SKK" => "Slovak Koruna (SKK)",
            "SLL" => "Sierra Leonean Leone (SLL)",
            "SVC" => "Salvadoran Colón (SVC)",
            "THB" => "Thai Baht (฿)",
            "TND" => "Tunisian Dinar (TND)",
            "TRY" => "Turkish Lira (TRY)",
            "TTD" => "Trinidad and Tobago Dollar (TTD)",
            "TWD" => "New Taiwan Dollar (NT$)",
            "TZS" => "Tanzanian Shilling (TZS)",
            "UAH" => "Ukrainian Hryvnia (UAH)",
            "UGX" => "Ugandan Shilling (UGX)",
            "USD" => "US Dollar ($)",
            "UYU" => "Uruguayan Peso (UYU)",
            "UZS" => "Uzbekistan Som (UZS)",
            "VEF" => "Venezuelan Bolívar (VEF)",
            "VND" => "Vietnamese Dong (₫)",
            "XOF" => "CFA Franc BCEAO (CFA)",
            "YER" => "Yemeni Rial (YER)",
            "ZAR" => "South African Rand (ZAR)",
            "ZMK" => "Zambian Kwacha (ZMK)"
        );

        /*
        * @unset: Remove home currency from targets
        */
        $target_currencies;
        if( array_key_exists( $home_currency, $target_currencies ) ) {
            unset( $target_currencies[$home_currency] );
        }

        $res = new stdClass();
        $res->servicio ="Yahoo";
        $res->fecha= date("Y-m-d H:i:s");
        $res->moneda_origen=$home_currency;
        $res->tipos_cambio = array();
        /*
        * @loop: Loop through the targets and perform lookup on Yahoo! Finance
        */
        foreach( $target_currencies as $code => $name ) {
            /*
            * @url: Get the URL for csv file at Yahoo API, based on 'convert_to' option
            */
            switch( strtoupper( $convert_to ) ) {
                case 'H': /* Converts targest to home */
                    $url = sprintf( "http://finance.yahoo.com/d/quotes.csv?s=%s%s=X&f=sl1d1t1", $code, $home_currency );
                break;
                case 'T': /* Converts home to targets */
                default:
                    $url = sprintf( "http://finance.yahoo.com/d/quotes.csv?s=%s%s=X&f=sl1d1t1", $home_currency, $code );
                break;
            }

            /*
            * @fopen: open and read API files
            */
            $handle = @fopen( $url, 'r' );
            if ( $handle ) {
                $result = fgets( $handle, 4096 );
                fclose( $handle );
            }

            $res_explode = explode( ',', $result );
        
            $obj = new stdClass();
            $obj->moneda = $code;
            $obj->equivalencia = $res_explode[1];
            array_push($res->tipos_cambio,$obj);
        }

        $json2 = json_encode($res);
        return $json2;
    }
    /**
     *
     *Mostrar? un comparativo de valores entre las diferentes monedas con respecto a la moneda base, esto para ver si estan diferentes los valores y hacer una actualizacion de tipos de cambio.
     *
     **/
    public static function MostrarEquivalenciasActualizar()
    {

    }

/**
     *
     *Actualizar? los tipo de cambio con respecto a la moneda base de la empresa.
     *
     * @param id_empresa int El id de la empresa
     * @param monedas json Los valores de las equivalencias de las monedas activas con respecto a la moneda base
     * @param moneda_base string El codigo de la moneda base, una cadena de tres caracteres: "MXN"
     **/
    public static function ActualizarTiposCambio($id_empresa, $monedas, $moneda_base)
    {

    }

    /**
     *
     *Regresar? la equivalencia de esa moneda con respecto a la moneda base de la empresa que se le indique.
     *
     * @param id_empresa int El id de la empresa
     * @param id_moneda int El id de la moneda a la que se le desea sacar la equivalencia
     **/
    public static function ObtenerEquivalenciaMoneda($id_empresa, $id_moneda)
    {

    }

    /**
     *
     *Realizar? una consulta a la BD ?pos? en la tabla tipos_cambio y regresar? las equivalencias de la moneda base con respecto a las monedas activas en esa instancia.
     *
     * @param id_moneda_base int El id de la moneda a la que se le desea sacar las equivalencias de tipo de cambio
     **/
    public static function ObtenerEquivalenciasServicio($id_moneda_base)
    {
        
    }
}

