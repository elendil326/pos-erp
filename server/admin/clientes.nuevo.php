<?php

	require_once("model/sucursal.dao.php");
	require_once("controller/clientes.controller.php");

?>



<script src="../frameworks/jquery/jquery-1.4.2.min.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/uniform/jquery.uniform.min.js" type="text/javascript" charset="utf-8"></script> 
<link rel="stylesheet" href="../frameworks/uniform/css/uniform.default.css" type="text/css" media="screen">

<script type="text/javascript" charset="utf-8">$(function(){$("input, select").uniform();});</script>

<script>document.getElementById("MAIN_TITLE").innerHTML = "Nuevo cliente";</script>

<h2>Detalles del nuevo cliente</h2>
<form id="newClient">
	<table border="0" cellspacing="5" cellpadding="5">
		<tr><td>Nombre</td><td><input type="text"               id="nombre" size="40"/></td></tr>
		<tr><td>RFC</td><td><input type="text"                  id="rfc" size="40"/></td></tr>
		<tr><td>Direccion</td><td><input type="text"            id="direccion" size="40"/></td></tr>
		<tr><td>Ciudad</td><td><input type="text"            	id="ciudad" size="40"/></td></tr>	
		<tr><td>Telefono</td><td><input type="text"             id="telefono" size="40"/></td></tr>
		<tr><td>Descuento</td><td><input type="text"            id="descuento" size="40"/></td></tr>
		<tr><td>Limite de credito</td><td><input type="text"    id="limite_credito" size="40"/></td></tr>
		
		<tr><td>Sucursal</td><td>
			    <select id="sucursal"> 
			    <?php
			
				    $sucursales = SucursalDAO::getAll();
				    foreach( $sucursales as $suc ){
					    echo "<option value='" . $suc->getIdSucursal() . "' >" .  $suc->getDescripcion()  . "</option>";
				    }
			
			    ?>
	
	            </select>
		    </td>
		</tr>
		<tr><td></td><td><input type="button" onClick="validar()" value="Crear el nuevo cliente"/> </td></tr>
	</table>
</form>


<script type="text/javascript" charset="utf-8">
    function validar(){

        if($('#nombre').val().length < 8){
            return $("#ajax_failure").html("El nombre es muy corto.").show();
        }


        if($('#direccion').val().length < 10){
            return $("#ajax_failure").html("La direccion es muy corta.").show();            
        }

        if($('#rfc').val().length < 7){
            return $("#ajax_failure").html("El RFC es muy corto.").show();
            return;
        }


        obj = {
            nombre : 	$('#nombre').val(), 
            direccion : $("#direccion").val(), 
            rfc : 		$("#rfc").val(), 
            descuento:	$("#descuento").val(),
            telefono : 	$("#telefono").val(),
            ciudad :	$("#ciudad").val(),
            limite_credito:	$("#limite_credito").val(),
            id_sucursal:	$('#sucursal').val(),
            id_usuario : <?php echo $_SESSION['userid']; ?>
        };        

        guardar(obj);
    }






    function guardar( data )
    {

        jQuery.ajaxSettings.traditional = true;


        $.ajax({
	      url: "../proxy.php",
	      data: { 
            action : 301, 
            data : $.JSON.encode(data)
           },
	      cache: false,
	      success: function(data){
		        response = jQuery.parseJSON(data);

                if(response.success == false){
                    return $("#ajax_failure").html(response.reason).show();
                }


                reason = "Los datos se han editado con exito !";
                window.location = 'clientes.php?action=lista&success=true&reason=' + reason;
	      }
	    });
    }

</script>


<?php
