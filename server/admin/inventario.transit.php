<?php

    require_once('model/autorizacion.dao.php');
    require_once('model/usuario.dao.php');
    require_once('model/sucursal.dao.php');
    require_once('model/inventario.dao.php');

    $aut = new Autorizacion();
    $aut->setEstado(3);
    $autorizaciones = AutorizacionDAO::search( $aut );

	//inveritir el orden del arreglo para que se muestre
	//la ultima autorizacion en el primer lugar
	rsort($autorizaciones);

	?><script>jQuery("#MAIN_TITLE").html("Embarques en transito");</script><?php    
	
	
	
    foreach($autorizaciones as $transito){
        $sucName = SucursalDAO::getByPK($transito->getIdSucursal());

        if($sucName === null)
            $sucName = "Error";
        else
            $sucName = $sucName->getDescripcion();

        echo "<h2>Destino ". $sucName ."</h2>";


        if($transito->getIdUsuario() == -1){
		//surtir de la nada
        ?>
            <table> 
                <tr><td><b>Id autorizacion</b></td><td><?php echo $transito->getIdAutorizacion(); ?></td></tr>
                <tr><td><b>Fecha enbarque</b></td><td><?php echo $transito->getFechaPeticion(); ?></td></tr>
                <tr><td><b>Usuario</b></td><td>Administrador</td></tr>
			
            </table>
        <?php
        }else{
        //surtido resultado de una peticion
        ?>
            <table style="width:100%" border=0> 
                <tr align = left>
					<td rowspan=2 width=68>
						<a href="autorizaciones.php?action=detalle&id=<?php echo $transito->getIdAutorizacion(); ?>">
							<img src="../media/TruckYellow64.png">
						</a>
					</td>
					<th>ID Autorizacion</th>
					<th>Fecha de envio</th>
					<th></th>
				</tr>
				<tr valign=top>

                	<td><?php echo $transito->getIdAutorizacion(); ?></td>
					<td>
							<?php
								//calcular hace cuanto se hizo esta cosa
								$enviado = strtotime($transito->getFechaPeticion());
								$hoy 	 = time();
								
								if( date( "Ymd", $enviado) == date("Ymd")){
									//fue enviado hoy, mostrar horas o minutos
									
									if(date( "G", $enviado) == date("G")){
										//fue enviado hace unos minutos, mostrar minutos
										echo "Hace " . (date( "i") - date("i", $enviado)) . " minutos.";
									}else{
										//fue enviado hace mas de una hora, mostrar horas
										echo "Hace " . (date( "G") - date("G", $enviado)) . " horas.";
									}
									
								}else{
									//no fue enviado hoy, enviar dias o meses
									if(date( "m", $enviado) == date("m")){
										//enviado este mes, mostrar dias
										echo "Hace " . (date( "d") - date("d", $enviado)) . " dias.";
									}else{
										//enviado hace mas de un mes
										echo "Hace " . (date( "m") - date("m", $enviado)) . " meses.";
										
									}

								}

							?>
						<i>
							<?php echo toDate( $transito->getFechaPeticion() ); ?> 
						</i>
					</td>
					<td>
						<a href="autorizaciones.php?action=detalle&id=<?php echo $transito->getIdAutorizacion(); ?>">Ver detalles</a>
					</td></tr>
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
 





    
