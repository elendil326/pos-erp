<?php


require_once("controller/clientes.controller.php");


//obtener el cliente
$cliente = ClienteDAO::getByPK( $_REQUEST['id'] );



//titulo
?><h1><?php echo $cliente->getNombre(); ?></h1>



<script src="../frameworks/jquery/jquery-1.4.2.min.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/uniform/jquery.uniform.js" type="text/javascript" charset="utf-8"></script> 
<link rel="stylesheet" href="../frameworks/uniform/css/uniform.default.css" type="text/css" media="screen">

<script type="text/javascript" charset="utf-8">
	$(function(){
      $("input, select").uniform();
    });
</script>



<h2>Detalles personales</h2>
<form id="edit">
<table border="0" cellspacing="5" cellpadding="5">
	<tr><td>Nombre</td><td><input type="text"           id="nombre"     value="<?php echo $cliente->getNombre(); ?>" size="40"/></td></tr>
	<tr><td>Ciudad</td><td><input type="text"           id="ciudad"     value="<?php echo $cliente->getCiudad(); ?>" size="40"/></td></tr>
	<tr><td>Descuento</td><td><input type="text"        id="descuento"  value="<?php echo $cliente->getDescuento(); ?>" size="40"/></td></tr>
	<tr><td>Direccion</td><td><input type="text"        id="direccion"  value="<?php echo $cliente->getDireccion(); ?>" size="40"/></td></tr>
	<tr><td>E Mail</td><td><input type="text"           id="e_mail"     value="<?php echo $cliente->getEMail(); ?>" size="40"/></td></tr>
	<tr><td>Limite de credito</td><td><input type="text" id="limite_credito" value="<?php echo $cliente->getLimiteCredito(); ?>"  size="40"/></td></tr>
	<tr><td>RFC</td><td><input type="text"              id="rfc"        value="<?php echo $cliente->getRFC(); ?>" size="40"/></td></tr>
	<tr><td>Telefono</td><td><input type="text"         id="telefono"   value="<?php echo $cliente->getTelefono(); ?>" size="40"/></td></tr>
	<tr><td></td><td><input type="button" onClick="validar()" value="Guardar Cambios"/> </td></tr>
</table>
</form>



<script type="text/javascript" charset="utf-8">

    function validar(){

        if($('#nombre').val().length < 8){
            alert("El nombre es muy corto." );
            return;
        }


        if($('#direccion').val().length < 10){
            alert("La direccion es muy corta.");
            return;
        }

        if($('#rfc').val().length < 7){
            alert("El rfc es muy corto.");
            return;
        }

        if($('#telefono').val().length < 7){
            alert("El telefono es muy corto.");
            return;
        }


        if( isNaN($('#descuento').val()) || $('#descuento').val().length == 0){
            alert("El descuento debe ser un nuemero valido.");
            return;
        }

        if( ($('#descuento').val() >= 100) || ($('#descuento').val() < 0) ){
            alert("El descuento debe ser la taza porcentual de descuento, entre 0% y 100%");
            return;
        }

        if( isNaN($('#limite_credito').val()) || $('#limite_credito').val().length == 0){
            alert("El limite de credito debe ser un nuemero valido.");
            return;
        }

        if(  ($('#limite_credito').val() < 0) ){
            alert("El limite credito debe ser una cantidad en pesos mayor a 0.");
            return;
        }

        obj = {
            nombre : $("#nombre").val(),
            ciudad : $("#ciudad").val(),
            descuento : $("#descuento").val(),
            direccion : $("#direccion").val(),
            e_mail : $("#e_mail").val(),
            limite_credito : $("#limite_credito").val(),
            rfc : $("#rfc").val(),
            telefono : $("#telefono").val(),
            id_cliente : <?php echo $_REQUEST['id']; ?>
        };        

        guardar(obj);
    }






    function guardar( data )
    {

        jQuery.ajaxSettings.traditional = true;


        $.ajax({
	      url: "../proxy.php",
	      data: { 
            action : 302, 
            data : $.JSON.encode(data)
           },
	      cache: false,
	      success: function(data){
		        response = jQuery.parseJSON(data);

                if(response.success == false){
                    alert(response.reason);
                    return;
                }

                if(response.success == "false"){
                    alert(response.reason);
                    return;
                }

                alert("Los datos se han editado con exito !");
	      }
	    });
    }

</script>


<?php
