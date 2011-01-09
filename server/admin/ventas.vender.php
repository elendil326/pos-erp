<?php


	require_once('controller/clientes.controller.php');

?>

<h1>Realizar venta</h1>

<h2>Cliente</h2>


<?php

    $clientes = listarClientes();
    
    if(sizeof($clientes ) > 0){
		echo '<select id="sucursal"> ';    
		foreach( $clientes as $c ){
			echo "<option value='" . $c['id_cliente'] . "' >" . $c['nombre']  . "</option>";
		}
		echo '</select>';    
    }else{
    
    	echo "<h3>No hay clientes a quien realizarle la venta</h3>";
    }

?>


