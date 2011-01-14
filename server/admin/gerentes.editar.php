<?php

require_once("model/usuario.dao.php");
require_once("model/sucursal.dao.php");

$gerente = UsuarioDAO::getByPK($_REQUEST['id']);

?>


<h1>Editar datos de <?php echo $gerente->getNombre(); ?></h1>

<h2>Detalles personales</h2>
<form id="editdetalles">
<table border="0" cellspacing="5" cellpadding="5">
	<tr><td>Nombre</td><td><input type="text"           id="nombre" size="40" value="<?php echo $gerente->getNombre(); ?>"/></td></tr>
	<tr><td>RFC</td><td><input type="text"              id="rfc" size="40" value="<?php echo $gerente->getRFC(); ?>"/></td></tr>
	<tr><td>Direccion</td><td><input type="text"        id="direccion" size="40" value="<?php echo $gerente->getDireccion(); ?>"/></td></tr>
	<tr><td>Telefono</td><td><input type="text"         id="telefono" size="40" value="<?php echo $gerente->getTelefono(); ?>"/></td></tr>
	
	<?php
		switch(POS_PERIODICIDAD_SALARIO){
			case POS_SEMANA : 
					echo '<tr><td>Salario Semanal</td><td><input type="text"  id="salario" size="40" value="' . $gerente->getSalario(). '"/></td></tr>';
				break;
			case POS_MES : 		
					echo '<tr><td>Salario Mensual</td><td><input type="text"  id="salario" size="40" value="' . $gerente->getSalario(). '"/></td></tr>';
				break;
		}
	?>

	
	<tr><td></td><td><input type="button" onClick="validar()" value="Guardar"/> </td></tr>

</table>
</form>









<h2>Editar Contrase&ntilde;a</h2>
<h3>Puede utilizar numeros, signos y letras mayusculas</h3>
<form id="editpass">
<table border="0" cellspacing="5" cellpadding="5">
	<tr><td>Nueva Contrase&ntilde;a</td><td><input type="password" id="pass1" size="40" /></td></tr>
	<tr><td>Repetir Contrase&ntilde;a</td><td><input type="password" id="pass2" size="40" /></td></tr>
	<tr><td></td><td><input type="button" onClick="editPass()" value="Cambiar Contrase&ntilde;a"/> </td></tr>
</table>
</form>












<h2>Editar Gerencia</h2>
<form id="editsucursal">
    <?php
        //ver si tiene una sucursal a su cargo
        //$gerente = UsuarioDAO::getByPK($_REQUEST['id']);
        
        $suc = new Sucursal();
        $suc->setGerente( $gerente->getIdUsuario() );
        $sucursal = SucursalDAO::search($suc);

        if(count($sucursal) == 0){
            echo "Este gerente no tiene a su cargo ninguna sucursal.";
            
        }else{
            $sucursal = $sucursal[0];
            echo "Actualmente <b>" . $gerente->getNombre() . "</b> es gerente de <b>" . $sucursal->getDescripcion() . "</b>.";

        }


       $suc = new Sucursal();
       $suc->setActivo( "1" );
       $sucursal = SucursalDAO::search($suc);


       foreach($sucursal as $s){
                        
       }

    ?>
</form>




<script type="text/javascript" charset="utf-8">

    function editPass()
    {
        if(jQuery('#pass1').val() != jQuery('#pass2').val()){
            alert("Las claves no coinciden.");
            return;
        }

        if(jQuery('#pass1').val().length < 4){
            alert("La nueva clave debe ser por lo menos de 5 caracteres.");
            return;
        }        
        

       obj = {
            contrasena : hex_md5(jQuery('#pass1').val()),
            id_usuario : <?php echo $_REQUEST['id']; ?>
        };      

        guardar(obj);
    }



    function validar(){

        if(jQuery('#nombre').val().length < 8){
             jQuery("#ajax_failure").html("El nombre es muy corto.").show();
            return;
        }


        if(jQuery('#direccion').val().length < 10){
             jQuery("#ajax_failure").html("La direccion es muy corta.").show();
            return;
        }

        if(jQuery('#rfc').val().length < 7){
             jQuery("#ajax_failure").html("El RFC es muy corto.").show();
            return;            

        }

        if(jQuery('#telefono').val().length < 7){
             jQuery("#ajax_failure").html("El telefono es muy corto.").show();
            return;
        }


        if( isNaN(jQuery('#salario').val()) || jQuery('#salario').val().length == 0){
             jQuery("#ajax_failure").html("El salario debe ser un nuemero.").show();
            return;
        }

        if( jQuery('#salario').val() >= 10000){
			return jQuery("#ajax_failure").html("El salario mensual debe ser menor a $10,000.00").show();
        }



		obj = {
			nombre : jQuery('#nombre').val(), 
			direccion : jQuery("#direccion").val(), 
			RFC : jQuery("#rfc").val(), 
			telefono : jQuery("#telefono").val(),
			id_usuario : <?php echo $_REQUEST['id']; ?>,
			salario : jQuery("#salario").val()
		};        

        guardar(obj);
    }






    function guardar( data )
    {

        jQuery.ajaxSettings.traditional = true;


        jQuery.ajax({
	      url: "../proxy.php",
	      data: { 
            action : 502, 
            data : jQuery.JSON.encode(data)
           },
	      cache: false,
	      success: function(data){
		        response = jQuery.parseJSON(data);

                if(response.success == false){
                    return jQuery("#ajax_failure").html(response.reason).show();
                }
				
				reason = 'Los detalles del gerente se han modificado correctamente.';
                window.location = "gerentes.php?action=detalles&id=<?php echo $_REQUEST['id'] ?>&success=true&reason=" + reason;
	      }
	    });
    }
</script>


