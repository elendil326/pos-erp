<?php



interface ICheques {

    public function NuevoCheque
        (
                $nombre_banco,
                $monto,
                $numero,
                $expedido,
                $id_usuario=null
        );
 }

?>
