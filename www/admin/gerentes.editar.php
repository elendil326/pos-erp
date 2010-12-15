<?php

require_once("model/usuario.dao.php");
require_once("model/sucursal.dao.php");

$gerente = UsuarioDAO::getByPK($_REQUEST['id']);

?><h1>Editar datos de <?php echo $gerente->getNombre(); ?></h1><?php



?>

<script src="../frameworks/jquery/jquery-1.4.2.min.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/uniform/jquery.uniform.js" type="text/javascript" charset="utf-8"></script> 
<link rel="stylesheet" href="../frameworks/uniform/css/uniform.default.css" type="text/css" media="screen">

<script type="text/javascript" charset="utf-8">
	$(function(){
      $("input, select").uniform();
    });
</script>






<h2>Detalles personales</h2>
<form id="editdetalles">
<table border="0" cellspacing="5" cellpadding="5">
	<tr><td>Nombre</td><td><input type="text"           id="nombre" size="40" value="<?php echo $gerente->getNombre(); ?>"/></td></tr>
	<tr><td>RFC</td><td><input type="text"              id="rfc" size="40" value="<?php echo $gerente->getRFC(); ?>"/></td></tr>
	<tr><td>Direccion</td><td><input type="text"        id="direccion" size="40" value="<?php echo $gerente->getDireccion(); ?>"/></td></tr>
	<tr><td>Telefono</td><td><input type="text"         id="telefono" size="40" value="<?php echo $gerente->getTelefono(); ?>"/></td></tr>
	<tr><td>Salario Mensual</td><td><input type="text"  id="salario" size="40" value="<?php echo $gerente->getSalario(); ?>"/></td></tr>
	
	<tr><td></td><td><input type="button" onClick="validar()" value="Guardar"/> </td></tr>

</table>
</form>









<h2>Editar Contrase&ntilde;a</h2>
<form id="editpass">
<table border="0" cellspacing="5" cellpadding="5">
	<tr><td>Nueva Contrase&ntilde;a</td><td><input type="password" name="pass1" size="40" /></td></tr>
	<tr><td>Repetir Contrase&ntilde;a</td><td><input type="password" name="pass2" size="40" /></td></tr>
	<tr><td></td><td><input type="button" onClick="cambiarPass()" value="Cambiar Contrase&ntilde;a"/> </td></tr>
</table>
</form>












<h2>Editar Gerencia</h2>
<form id="editsucursal">
    <?php
        //ver si tiene una sucursal a su cargo
        


    ?>
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


        if( isNaN($('#salario').val()) || $('#salario').val().length == 0){
            alert("El salario debe ser un nuemero valido.");
            return;
        }

        if( $('#salario').val() >= 10000){
            alert("El salario mensual debe ser menor a $10,000.00");
            return;
        }


        guardar();
        

    }

    function limpiar(){
        $('#telefono').val("");
        $('#direccion').val("");
        $('#nombre').val("");
        $('#rfc').val("");
    }

    //TODO !!!
    function guardar(  )
    {
        $.ajax({
	      url: "../proxy.php",
	      data: { 
            action : 502, 
            data : {
                nombre : $('#nombre').val(), 
                direccion : $("#direccion").val(), 
                rfc : $("#rfc").val(), 
                telefono : $("#telefono").val(),
                id_usuario : <?php echo $_REQUEST['id']; ?>
            }
           },
	      cache: false,
	      success: function(data){
		        response = jQuery.parseJSON(data);

                if(!response.success){
                    alert(response.reason);
                    return;
                }

                limpiar();
                alert("Los datos se han editado con exito !");
	      }
	    });
    }
</script>


<?php
