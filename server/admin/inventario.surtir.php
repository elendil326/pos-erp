<?php


	require_once("model/sucursal.dao.php");
    require_once('model/autorizacion.dao.php');
	require_once("controller/clientes.controller.php");
	require_once("controller/sucursales.controller.php");
	require_once("controller/inventario.controller.php");

    if(isset( $_REQUEST['aut'])){
        $autorizacion = AutorizacionDAO::getByPK( $_REQUEST['aut'] );
        $autorizacionDetalles = json_decode( $autorizacion->getParametros() );
    }


?>

<script src="../frameworks/jquery/jquery-1.4.2.min.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/uniform/jquery.uniform.min.js" type="text/javascript" charset="utf-8"></script> 
<link rel="stylesheet" href="../frameworks/uniform/css/uniform.default.css" type="text/css" media="screen">

<script type="text/javascript" charset="utf-8">
	$(function(){

        $("input, select").uniform();

        <?php 
            if(isset($_REQUEST['sid'])) { 
                echo "seleccionarSucursal();";
        }?>
    });

    var currentSuc = null;

	function seleccionarSucursal(){


        if(currentSuc !== null){
    		$("#actual" + currentSuc).slideUp();
        }


        <?php 
            if(isset($_REQUEST['aut'])) { 
                echo '$("#Solicitud").slideDown();';
        }?>            

		$("#actual" + $('#sucursal').val()).slideDown();
		$("#InvMaestro").slideDown();
		$("#ASurtir").slideDown();
        currentSuc = $('#sucursal').val();		
	}

    carrito = [];

    function agregarProducto(pid){


        if($("#ASurtirItem"+pid).length === 0){
            $("#ASurtirTabla").append('<tr id="ASurtirItem'+pid+'"><td>'+pid+'</td><td><input type="text" id="ASurtirItemQty'+pid+'"></td></tr>');
            carrito.push( pid );
        }

    }


    function doSurtir(){
        //valida campos
        if(carrito.length === 0){
               alert("Debe seleccionar al menos un proudcto para surtir.");
               return;
        }

        var peticion = [];

        for(i = 0; i < carrito.length; i++ ){
             item = carrito[i];
            //console.log("revisando " + item)
            if( isNaN($("#ASurtirItemQty"+item ).val()) || $("#ASurtirItemQty"+item ).val().length == 0){
                alert("La cantidad a surtir del producto " + item + " debe ser un numero." );
                return;
            }

            peticion.push({
                id_producto : item,
                cantidad : $("#ASurtirItemQty"+item ).val()
            });
        }

        //hacer ajaxaso
        jQuery.ajaxSettings.traditional = true;


        

        $.ajax({
	      url: "../proxy.php",
	      data: { 
                action : 214, 
                data : $.JSON.encode( peticion ),
                id_sucursal : currentSuc,
                responseToAut : <?php echo isset( $_REQUEST['aut'] ) ? $_REQUEST['aut'] : "null"; ?>
           },
	      cache: false,
	      success: function(data){
		        response = jQuery.parseJSON(data);

                if(response.success === false){
                    window.location = "inventario.php?action=transit&success=false&reason=" + response.reason;
                    return;
                }


                reason = "El pedido se enuentra ahora en transito.";
                window.location = "inventario.php?action=transit&success=true&reason=" + reason;
                
	      }
	    });

    }

</script>



<h1>Surtir una sucursal</h1>




<?php if(!isset($_REQUEST['sid'])) { ?>
    <h2>Seleccione la sucursal que desea surtir</h2>
    <form id="newClient">
    <table border="0" cellspacing="5" cellpadding="5">
	    <tr><td>Sucursal</td>
		    <td>
			    <select id="sucursal"> 
			    <?php
			
				    $sucursales = SucursalDAO::getAll();
				    foreach( $sucursales as $suc ){
					    echo "<option value='" . $suc->getIdSucursal() . "' >" .  $suc->getDescripcion()  . "</option>";
				    }
			
			    ?>
	
	            </select>
		    </td>
            <td><input type="button" onClick="seleccionarSucursal()" value="Seleccionar"/> </td>
	    </tr>
    </table>
    </form>
<?php }else{ ?>
    <input type="hidden" value="<?php echo $_REQUEST['sid']; ?>" id="sucursal" />
<?php } ?>






<?php

//get sucursales
$sucursales = listarSucursales();

foreach( $sucursales as $sucursal ){
	
	print ("<div id='actual" . $sucursal["id_sucursal"] . "' style='display: none'>");
	print ("<h2>Inventario actual de " . $sucursal["descripcion"] . "</h2>");
	
	//obtener los clientes del controller de clientes
	$inventario = listarInventario( $sucursal["id_sucursal"] );

	//render the table
	$header = array( 
		"productoID" => "ID",
		"descripcion"=> "Descripcion",
		"precioVenta"=> "Precio Venta",
		"existenciasMinimas"=> "Minimas",
		"existencias"=> "Existencias",
		"medida"=> "Tipo",
		"precioIntersucursal"=> "Precio Intersucursal" );
		

	
	$tabla = new Tabla( $header, $inventario );
	$tabla->addColRender( "precioVenta", "moneyFormat" ); 
	$tabla->addColRender( "precioIntersucursal", "moneyFormat" ); 
    $tabla->addNoData("Esta sucursal no tiene nigun registro de productos en su inventario");
	$tabla->render();
	printf("</div>");
}

?>




<div id="Solicitud" style="display: none;">
<h2>Solicitud de producto</h2>
<h3>Esta es la lista de productos solicitados.</h3>

            <table>
                <tr><td>Producto solicitado</td><td>Cantidad solicitada</td></tr>
                <?php
                foreach ($autorizacionDetalles->productos as $producto)
                {
                    ?><tr><td><?php echo $producto->id_producto; ?></td><td><?php echo $producto->cantidad; ?></td></tr><?php
                }
                ?>
                <tr><td></td><td></td></tr>
            </table>

</div>





<div id="InvMaestro" style="display: none;">
<h2>Productos disponibles</h2><h3>Seleccione los productos que desee surtir a esta sucursal.</h3><?php

	//obtener los clientes del controller de clientes
	$inventario = listarInventarioMaestro( );

	//render the table
	$header = array( 
		"id_producto" => "ID",
		"descripcion"=> "Descripcion",
		"precio_intersucursal"=> "Precio Intersucursal",
		"costo"=> "Costo",
		"medida"=> "Medida");
		

	
	$tabla = new Tabla( $header, $inventario );
	$tabla->addColRender( "precioVenta", "moneyFormat" ); 
	$tabla->addColRender( "precioIntersucursal", "moneyFormat" ); 
    $tabla->addOnClick( "id_producto", "agregarProducto");
    $tabla->addNoData("No existe ningun producto en el inventario maestro.");
	$tabla->render();

?> 
</div>







<div id="ASurtir" style="display: none;">
<h2>Productos a surtir</h2><h3>Seleccione la cantidad del proucto que desea surtir.</h3>

<table id="ASurtirTabla">
    <tr><th>Descripcion</th><th>Cantidad</th></tr>

</table>

<input type="button" value="Surtir" onclick="doSurtir()">
</div>


