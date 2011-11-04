<?php



    class ChequesController {
        
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
         * Valida los parametros de la tabla cheque. Si alguno es erroneo, entonces regresa un string con el error
         * Si no encuentra error regresa verdadero
         */
        
        private static function validarParametrosCheque
        (
                $id_cheque = null,
                $nombre_banco = null,
                $monto = null,
                $numero = null,
                $expedido = null,
                $id_usuario = null
        )
        {
            //valida que el cheque exista en l abase de datos
            if(!is_null($id_cheque))
            {
                if(is_null(ChequeDAO::getByPK($id_cheque)))
                        return "El cheque con id ".$id_cheque." no existe";
            }
            
            //valida que el nombre del banco no tenga mas de 100 caracteres
            if(!is_null($nombre_banco))
            {
                $e = self::validarString($nombre_banco, 100, "nombre de banco");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el monto este en rango
            if(!is_null($monto))
            {
                $e = self::validarNumero($monto, 1.8e200, "monto");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el numero tenag 4 y solo 4 caracteres numericos
            if(!is_null($numero))
            {
                $e = self::validarString($numero, 4, "numero del cheque",3);
                if(is_string($e))
                    return $e;
                 if(preg_match('/[^0-9]/' ,$numero))
                         return "el numero de cheque (".$numero.") tiene caracteres invalidos, solo se permiten de 0-9";
            }
            
            //valida el boleano expedido
            if(!is_null($expedido))
            {
                $e = self::validarNumero($expedido, 1, "expedido");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el usuario exista en la base de datos y que no este inactivo
            if(!is_null($id_usuario))
            {
                $usuario = UsuarioDAO::getByPK($id_usuario);
                if(is_null($usuario))
                        return "El usuario con id: ".$id_usuario." no existe";
                if(!$usuario->getActivo())
                    return "El usuario esta inactivo y no puede expedir cheques";
            }
        }

        public static function NuevoCheque
        (
                $nombre_banco,
                $monto,
                $numero,
                $expedido,
                $id_usuario=null
        )
        {
            Logger::log("creando cheque");
            //Se validan los parametros obtenidos
            $validar = self::validarParametrosCheque(null, $nombre_banco, $monto, $numero, $expedido);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            $cheque=new Cheque();
            $cheque->setNombreBanco($nombre_banco);
            $cheque->setMonto($monto);
            $cheque->setNumero($numero);
            $cheque->setExpedido($expedido);
            if($expedido)
            {
                $id_usuario=LoginController::getCurrentUser();
                if(is_null($id_usuario))
                {
                    Logger::error("No se pudo obtener el usuario de la sesion, ya inicio sesion?");
                    throw new Exception("No se pudo obtener el usuario de la sesion, ya inicio sesion?");
                }
            }
            $cheque->setIdUsuario($id_usuario);
            DAO::transBegin();
            try
            {
                ChequeDAO::save($cheque);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo crear el cheque: ".$e);
                throw "No se pudo crear el cheque";
            }
            DAO::transEnd();
            Logger::log("Cheque creado exitosamente");
            return $cheque->getIdCheque();
        }
    }
