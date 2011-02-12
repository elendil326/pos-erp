<?php

    require_once('model/autorizacion.dao.php');
    require_once('model/usuario.dao.php');
    require_once('model/sucursal.dao.php');
    require_once('model/inventario.dao.php');

    $aut = new Autorizacion();
    $aut->setEstado(3);
    $autorizaciones = AutorizacionDAO::search( $aut );

	?><script>jQuery("#MAIN_TITLE").html("Embarques en transito");</script><?php    

    foreach($autorizaciones as $transito){
        $sucName = SucursalDAO::getByPK($transito->getIdSucursal());

        if($sucName === null)
            $sucName = "Error";
        else
            $sucName = $sucName->getDescripcion();

        echo "<h2>Destino ". $sucName ."</h2>";


        if($transito->getIdUsuario() == -1){
        ?>
            <table> 
                <tr><td><b>Id autorizacion</b></td><td><?php echo $transito->getIdAutorizacion(); ?></td></tr>
                <tr><td><b>Fecha enbarque</b></td><td><?php echo $transito->getFechaPeticion(); ?></td></tr>
                <tr><td><b>Usuario</b></td><td>Administrador</td></tr>
                <!-- <tr><td><b>Sucursal Destino</b></td><td><?php echo $sucName; ?></td></tr> -->
				
            </table>
        <?php
        }else{
        //resultado de una peticion
        ?>
            <table style="width:100%" border=0> 
                <tr align = left>
					<td rowspan=2 width=68>
						<a href="autorizaciones.php?action=detalle&id=<?php echo $transito->getIdAutorizacion(); ?>">
							<img src="../media/TruckYellow64.png">
						</a>
					</td>
					<th>ID Autorizacion</th>
					<th>Fecha envio</th>
				</tr>
				<tr valign=top>

                	<td ><?php echo $transito->getIdAutorizacion(); ?></td>
					<td><?php 	echo toDate( $transito->getFechaPeticion() ); ?></td></tr>
                	<!--<tr><td><b>Sucursal Destino</b></td><td><?php 	echo $sucName; ?></td></tr> -->
            </table>
        <?php
        }


/*        
        //detalles de los productos
        $items = json_decode($transito->getParametros());

        $items = $items->productos;
        echo "<h2>Detalles de envio</h2>";
        echo "<table width='100%'>";
        echo "<tr><th>Producto</th><th>Descripcion</th><th>Cantidad en transito</th></tr>";
        foreach ($items as $i)
        {
          $_desc = InventarioDAO::getByPK($i->id_producto);
          echo "<tr><td>".$i->id_producto."</td><td>". $_desc->getDescripcion() ."</td><td>".$i->cantidad."</td></tr>";
        }
        echo "</table>";
        */
    }
 





    
