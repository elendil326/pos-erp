
<h1>Nuevo Gerente</h1><?php

/*
 * Lista de Clientes
 */ 

	require_once("model/sucursal.dao.php");
	require_once("controller/clientes.controller.php");


?>

<script src="../frameworks/jquery/jquery-1.4.2.min.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/uniform/jquery.uniform.min.js" type="text/javascript" charset="utf-8"></script> 
<link rel="stylesheet" href="../frameworks/uniform/css/uniform.default.css" type="text/css" media="screen">

<script type="text/javascript" charset="utf-8">
	$(function(){
      $("input, select").uniform();
    });
</script>



<h2>Detalles del nuevo gerente</h2>
<form id="newClient">
<table border="0" cellspacing="5" cellpadding="5">
	<tr><td>Nombre</td><td><input type="text"               id="nombre" size="40"/></td></tr>
	<tr><td>RFC</td><td><input type="text"                  id="rfc" size="40"/></td></tr>
	<tr><td>Direccion</td><td><input type="text"            id="direccion" size="40"/></td></tr>
	<tr><td>Telefono</td><td><input type="text"             id="telefono" size="40"/></td></tr>
	<tr><td>Salario</td><td><input type="text"              id="salario" size="40"/></td></tr>		
	<tr><td>Contrase&ntilde;a</td><td><input type="text"    id="pass1" size="40"/></td></tr>
	<tr><td>Repetir contrase&ntilde;a</td><td><input type="text" id="pass2" size="40"/></td></tr>

	<tr><td></td><td><input type="button" onClick="validar()" value="Crear el nuevo gerente"/> </td></tr>
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


        if( isNaN($('#salario').val()) || $('#salario').val().length == 0){
            alert("El salario debe ser un nuemero valido.");
            return;
        }

        if( $('#salario').val() >= 10000){
            alert("El salario mensual debe ser menor a $10,000.00");
            return;
        }

        if( $('#pass1').val() != $('#pass1').val() ){
            alert("Las claves no coinciden");
            return;
        }

            obj = {
                nombre : $('#nombre').val(), 
                direccion : $("#direccion").val(), 
                RFC : $("#rfc").val(), 
                telefono : $("#telefono").val(),
                id_usuario : null,
                contrasena :  hex_md5( $("#pass1").val() ),
                salario : $("#salario").val(),
                grupo : 2
        };        

        guardar(obj);
    }






    function guardar( data )
    {

        jQuery.ajaxSettings.traditional = true;


        $.ajax({
	      url: "../proxy.php",
	      data: { 
            action : 500, 
            data : $.JSON.encode(data)
           },
	      cache: false,
	      success: function(data){
		        response = jQuery.parseJSON(data);

                if(response.success == false){
                    window.location = "gerentes.php?action=nuevo&success=false&reason=" + response.reason;
                    return;
                }


                reason = "Los datos se han editado con exito !";
                window.location = 'gerentes.php?action=lista&success=true&reason=' + reason;
	      }
	    });
    }

</script>


<?php
