<?php

	require_once("model/sucursal.dao.php");
	require_once("controller/clientes.controller.php");

?>


<h2>Nuevo Gerente</h2>
<form id="newClient">
<table border="0" cellspacing="5" cellpadding="5">
	<tr><td>Nombre</td><td><input type="text"               id="nombre" size="40"/></td></tr>
	<tr><td>RFC</td><td><input type="text"                  id="rfc" size="40"/></td></tr>
	<tr><td>Direccion</td><td><input type="text"            id="direccion" size="40"/></td></tr>
	<tr><td>Telefono</td><td><input type="text"             id="telefono" size="40"/></td></tr>
	
	<?php
		switch(POS_PERIODICIDAD_SALARIO){
			case POS_SEMANA : 
					echo '<tr><td>Salario Semanal</td><td><input type="text"  id="salario" size="40"/></td></tr>';
				break;
			case POS_MES : 		
					echo '<tr><td>Salario Mensual</td><td><input type="text"  id="salario" size="40"/></td></tr>';
				break;
		}
	?>

	
	<tr><td>Contrase&ntilde;a</td><td><input type="password"    id="pass1" size="40"/></td></tr>
	<tr><td>Repetir contrase&ntilde;a</td><td><input type="password" id="pass2" size="40"/></td><td></td></tr>

</table>
</form>
<h4><input type="button" onClick="validar()" value="Crear el nuevo gerente"/></h4>


<script type="text/javascript" charset="utf-8">
    function validar(){

        if(jQuery('#nombre').val().length < 8){
            alert("El nombre es muy corto." );
            return;
        }


        if(jQuery('#direccion').val().length < 10){
            alert("La direccion es muy corta.");
            return;
        }

        if(jQuery('#rfc').val().length < 7){
            alert("El rfc es muy corto.");
            return;
        }

        if(jQuery('#telefono').val().length < 7){
            alert("El telefono es muy corto.");
            return;
        }


        if( isNaN(jQuery('#salario').val()) || jQuery('#salario').val().length == 0){
            alert("El salario debe ser un nuemero valido.");
            return;
        }

        if( jQuery('#salario').val() >= 10000){
            alert("El salario mensual debe ser menor a $10,000.00");
            return;
        }

        if( jQuery('#pass1').val() != jQuery('#pass1').val() ){
            alert("Las claves no coinciden");
            return;
        }

            obj = {
                nombre : jQuery('#nombre').val(), 
                direccion : jQuery("#direccion").val(), 
                RFC : jQuery("#rfc").val(), 
                telefono : jQuery("#telefono").val(),
                id_usuario : null,
                contrasena :  hex_md5( jQuery("#pass1").val() ),
                salario : jQuery("#salario").val(),
                grupo : 2
        };        

        guardar(obj);
    }






    function guardar( data )
    {

        jQuery.ajaxSettings.traditional = true;


        jQuery.ajax({
	      url: "../proxy.php",
	      data: { 
            action : 500, 
            data : jQuery.JSON.encode(data)
           },
	      cache: false,
	      success: function(data){
		        response = jQuery.parseJSON(data);

                if(response.success == false){
                    return jQuery("#ajax_failure").html(response.reason).show();
                    return;
                }


                reason = "Los datos se han editado con exito !";
                window.location = 'gerentes.php?action=lista&success=true&reason=' + reason;
	      }
	    });
    }

</script>


<?php
