<h1>Detalles de autorizacion</h1><?php


require_once('model/autorizacion.dao.php');


$autorizacion = AutorizacionDAO::getByPK( $_REQUEST['id'] );

$autorizacionDetalles = json_decode( $autorizacion->getParametros() );

?>
<h2>Detalles de la autorizacion</h2>

<table border="0" cellspacing="5" cellpadding="5">
	<tr><td><b>ID Autorizacion</b></td><td><?php echo $autorizacion->getIdAutorizacion(); ?></td><td rowspan=12><div id="map_canvas"></div></td></tr>
	<tr><td><b>Usuario</b></td><td><?php echo $autorizacion->getIdUsuario(); ?></td></tr>
	<tr><td><b>Sucursal</b></td><td><?php echo $autorizacion->getIdSucursal(); ?></td></tr>
	<tr><td><b>Fecha de peticion</b></td><td><?php echo $autorizacion->getFechaPeticion(); ?></td></tr>
	<tr><td><b>Descripcion</b></td><td><?php echo $autorizacionDetalles->descripcion; ?></td></tr>	

</table>


<?php
switch( $autorizacionDetalles->clave ){

    case "201": 
        //solicitud de autorizcion de gasto
        ?>
            <h2>Solicitud de gasto</h2>
            <table>
                <tr><td>Concepto</td><td><?php echo $autorizacionDetalles->concepto; ?></td></tr>
                <tr><td>Monto</td><td><?php echo $autorizacionDetalles->monto; ?></td></tr>
                <tr><td></td><td><input type=button value="Autorizar" onClick="contestar(<?php echo $_REQUEST['id'] ?>, true)"><input onClick="contestar(<?php echo $_REQUEST['id'] ?>, false)" type=button value="Rechazar"></td></tr>
            </table>
        <?php
        

    break;

    case "202": 
        //cambio de limite de credito
        ?>
            <h2>Solicitud de limite de credito</h2>
            <table>
                <tr><td>Cliente</td><td><?php echo $autorizacionDetalles->id_cliente; ?></td></tr>
                <tr><td>Cantidad</td><td><?php echo $autorizacionDetalles->cantidad; ?></td></tr>
                <tr><td></td><td><input type=button value="Autorizar" onClick="contestar(<?php echo $_REQUEST['id'] ?>, true)"><input onClick="contestar(<?php echo $_REQUEST['id'] ?>, false)" type=button value="Rechazar"></td></tr>
            </table>
        <?php
    break;

    case "203": 
        //devoluciones

        echo $autorizacionDetalles->id_venta;
        echo $autorizacionDetalles->id_producto;
        echo $autorizacionDetalles->cantidad;
    break;

    case "204": 
        //cambio de precio
        ?>
            <h2>Solicitud de cambio de precio a producto</h2>
            <table>
                <tr><td>Cliente</td><td><?php echo $autorizacionDetalles->id_producto; ?></td></tr>
                <tr><td>Cantidad</td><td><?php echo $autorizacionDetalles->precio; ?></td></tr>
                <tr><td></td><td><input type=button value="Autorizar" onClick="contestar(<?php echo $_REQUEST['id'] ?>, true)"><input onClick="contestar(<?php echo $_REQUEST['id'] ?>, false)" type=button value="Rechazar"></td></tr>
            </table>
        <?php

    break;

    case "205": 
        //merma
        ?>
            <h2>Solicitud de merma</h2>
            <table>
                <tr><td>Cliente</td><td><?php echo $autorizacionDetalles->id_compra; ?></td></tr>
                <tr><td>Cantidad</td><td><?php echo $autorizacionDetalles->id_producto; ?></td></tr>
                <tr><td>Cantidad</td><td><?php echo $autorizacionDetalles->cantidad; ?></td></tr>
                <tr><td></td><td><input type=button value="Autorizar" onClick="contestar(<?php echo $_REQUEST['id'] ?>, true)"><input onClick="contestar(<?php echo $_REQUEST['id'] ?>, false)" type=button value="Rechazar"></td></tr>
            </table>
        <?php
    break;

    case "209": 
        //solicitud de surtir
        ?>
            <h2>Solicitud de merma</h2>
            <table>
                <tr><td>Producto</td><td>Cantidad solicitada</td></tr><?php
                <?php
                foreach ($autorizacionDetalles->productos as $producto)
                {
                    ?><tr><td><?php echo $autorizacionDetalles->id_producto; ?></td><td><?php echo $autorizacionDetalles->cantidad; ?></td></tr><?php
                }
                ?>
                <tr><td></td><td><input type=button value="Surtir sucursal" ></td></tr>
            </table>
        <?php

        
    break;


    default: 
}
?>




<script>
function contestar(id, response){

        //hacer ajaxaso
        jQuery.ajaxSettings.traditional = true;

        $.ajax({
	      url: "../proxy.php",
	      data: { 
                action : 208, 
                id_autorizacion : id,
                reply : response ? "1" : "2"

           },
	      cache: false,
	      success: function(data){
		        response = jQuery.parseJSON(data);

                if(response.success == false){
                    alert(response.reason);
                    return;
                }


                alert("Autorizacion respondida con exito.");
                window.location = "autorizaciones.php?action=pendientes";
	      }
	    });
}
</script>
