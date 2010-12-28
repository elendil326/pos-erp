<h1>Productos en transito</h1>



<?php

    require_once('model/autorizacion.dao.php');
    require_once('model/usuario.dao.php');
    require_once('model/sucursal.dao.php');

    $aut = new Autorizacion();
    $aut->setEstado(3);
    $autorizaciones = AutorizacionDAO::search( $aut );
    

    foreach($autorizaciones as $transito){
        $sucName = SucursalDAO::getByPK($transito->getIdSucursal());

        if($sucName === null)
            $sucName = "Error";
        else
            $sucName = $sucName->getDescripcion();

        echo "<h2>Destino : ". $sucName ."</h2>";


        if($transito->getIdUsuario() == -1){
        ?>
            <table> 
                <tr><td><b>Id autorizacion</b></td><td><?php echo $transito->getIdAutorizacion(); ?></td></tr>
                <tr><td><b>Fecha enbarque</b></td><td><?php echo $transito->getFechaPeticion(); ?></td></tr>
                <tr><td><b>Usuario</b></td><td>Administrador</td></tr>
                <tr><td><b>Sucursal</b></td><td><?php echo $sucName; ?></td></tr>
            </table>
        <?php
        }else{
        //resultado de una peticion
        ?>
            <table> 
                <tr><td><b>Id autorizacion</b></td><td><?php echo $transito->getIdAutorizacion(); ?></td></tr>
                <tr><td><b>Fecha peticion</b></td><td><?php echo $transito->getFechaPeticion(); ?></td></tr>
                <tr><td><b>Usuario</b></td><td><?php echo $transito->getIdUsuario(); ?></td></tr>
                <tr><td><b>Sucursal</b></td><td><?php echo $sucName; ?></td></tr>
            </table>
        <?php
        }


        
        //detalles de los productos
        $items = json_decode($transito->getParametros());

        $items = $items->productos;
        echo "<h3>Detalles de los productos</h3>";
        echo "<table>";
        echo "<tr><td>Producto</td><td>Descripcion</td><td>Cantidad en transito</td></tr>";
        foreach ($items as $i)
        {
          echo "<tr><td>".$i->id_producto."</td><td>".null."</td><td>".$i->cantidad."</td></tr>";
        }
        echo "</table>";
    }
 





    
