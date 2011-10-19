<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class CajasController{

    public static function modificarCaja
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
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo actualizar la caja: ".$e);
                throw $e;
            }
            DAO::transEnd();
        }
}
?>
