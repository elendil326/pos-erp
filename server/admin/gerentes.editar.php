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
        if($('#pass1').val() != $('#pass2').val()){
            alert("Las claves no coinciden.");
            return;
        }

        if($('#pass1').val().length < 4){
            alert("La nueva clave debe ser por lo menos mayor a 4 caracteres.");
            return;
        }        
        

       obj = {
            contrasena : hex_md5($('#pass1').val()),
            id_usuario : <?php echo $_REQUEST['id']; ?>
        };      

        guardar(obj);
    }



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



            obj = {
                nombre : $('#nombre').val(), 
                direccion : $("#direccion").val(), 
                RFC : $("#rfc").val(), 
                telefono : $("#telefono").val(),
                id_usuario : <?php echo $_REQUEST['id']; ?>,
                salario : $("#salario").val()
        };        

        guardar(obj);
    }






    function guardar( data )
    {

        jQuery.ajaxSettings.traditional = true;


        $.ajax({
	      url: "../proxy.php",
	      data: { 
            action : 502, 
            data : $.JSON.encode(data)
           },
	      cache: false,
	      success: function(data){
		        response = jQuery.parseJSON(data);

                if(response.success == false){
                    window.location = "gerentes.php?action=editar&success=false&reason=" + response.reason;
                    return;
                }

				reason = 'Los detalles del gerente se han modificado correctamente.';
                window.location = "gerentes.php?action=detalles&id=<?php echo $_REQUEST['id'] ?>&success=true&reason=" + reason;
	      }
	    });
    }
</script>


<?php
