<?php



    class ChequesController {

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
            $cheque=new Cheque();
            $cheque->setNombreBanco($nombre_banco);
            $cheque->setMonto($monto);
            $cheque->setNumero($numero);
            $cheque->setExpedido($expedido);
            if($expedido)
            {
                $id_usuario=LoginController::getCurrentUser();
                if($id_usuario==null)
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
                throw $e;
            }
            DAO::transEnd();
            Logger::log("Cheque creado exitosamente");
            return $cheque->getIdCheque();
        }
    }
