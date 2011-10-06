<?php

	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");
        $cargosyabonos = new CargosYAbonosController();
        $billetes= array(
            array( "id_billete" => 1, "cantidad" => 10 ),
            array( "id_billete" => 2, "cantidad" => 10 ),
            array( "id_billete" => 3, "cantidad" => 10 ),
            array( "id_billete" => 4, "cantidad" => 10 ),
            );
        try
        {
              $cargosyabonos->EliminarGasto(1, "Que siempre no pagamos la luz dice el patron xD");
//            $abonos= $cargosyabonos->ListaAbono(1, 1, 1, null, null, "id_receptor", null, null, null, null, null, null, null, null, null,null,null,100);
//            if($abonos)
//            foreach($abonos as $abono)
//            {
//                var_dump($abono);
//            }
//            $cargosyabonos->EliminarAbono(1, null, 1, 0, 0, 1, $billetes);
//            $cargosyabonos->NuevoIngreso("2009-10-04", 1,1000);
        }
        catch(Exception $e)
        {
            echo $e;
        }
        $c = new EmpresasController();
        $c->lista();
?>
