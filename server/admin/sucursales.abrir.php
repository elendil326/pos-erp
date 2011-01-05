
<h1>Abrir una sucursal</h1><?php

	require_once("model/sucursal.dao.php");
	require_once("model/grupos_usuarios.dao.php");
	require_once("controller/clientes.controller.php");


?>

<script src="../frameworks/jquery/jquery-1.4.2.min.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/uniform/jquery.uniform.min.js" type="text/javascript" charset="utf-8"></script> 
<link rel="stylesheet" href="../frameworks/uniform/css/uniform.default.css" type="text/css" media="screen">

<script type="text/javascript" charset="utf-8">
	$(function(){
      $("input, select").uniform();
    });


    function validar(){

        if($('#descripcion').val().length < 8){
            $('#descripcion_helper').html("La descricpcion es muy corta." );
            return;
        }else{
            $('#descripcion_helper').html( "" );
        }


        if($('#direccion').val().length < 10){
            $('#direccion_helper').html("La direccion es muy corta. Sea mas especifico");
            return;
        }else{
            $('#direccion_helper').html("");
        }


        if($('#telefono').val().length < 7){
            $('#telefono_helper').html("El telefono es muy corto.");
            return;
        }else{
            $('#telefono_helper').html("");
        }


        guardar();
        

    }

    function limpiar(){
        $('#telefono').val("");
        $('#direccion').val("");
        $('#descripcion').val("");
        $('#rfc').val("");
    }
    

    function guardar(){

		datos = {
			descripcion : $('#descripcion').val(), 
            direccion : $("#direccion").val(), 
            rfc : $("#rfc").val(), 
            prefijo_factura : $("#letras_factura").val(), 
            telefono : $("#telefono").val(),
            gerente : $('#gerente').val()
        };
        
	    $.ajax({
	      url: "../proxy.php",
	      data: { 
            action : 701, 
            data : $.JSON.encode( datos )

           },
	      cache: false,
	      success: function(data){
		        response = jQuery.parseJSON(data);

                if(!response.success){
                    window.location = "sucursales.php?action=abrir&success=true&reason=" + response.reason;
                    return;
                }
                reason = "La nueva sucursal se ha creado con exito.";
                window.location = "sucursales.php?action=lista&success=false&reason=" + reason;
	      }
	    });

    }

</script>


<h2>Gerencia</h2>
<?php
    $posiblesGerentes = 0;
    $html = "";
	$grp = new GruposUsuarios();
    $grp->setIdGrupo("2");

    $gerentes = GruposUsuariosDAO::search( $grp );

	foreach( $gerentes as $usuario ){

        //ya tengo el gerente
        $gerente = UsuarioDAO::getByPK( $usuario->getIdUsuario() );

        //reviar que siga en activo
        if($gerente->getActivo() == 0){
            continue;                    
        }

        //revisar que no sea gerente ya de una sucursal
        $suc = new Sucursal();
        $suc->setGerente( $gerente->getIdUsuario() );

        if( sizeof(SucursalDAO::search( $suc )) > 0 ){
            continue;
        }

        $posiblesGerentes++;
		$html .= "<option value='" . $gerente->getIdUsuario() . "' >" .  $gerente->getNombre()  . "</option>";
	}


    if($posiblesGerentes > 0 ){

        ?><form id="gerencia">
        <table border="0" cellspacing="5" cellpadding="5">
	        <tr><td>Gerente</td>
		        <td>
			        <select id="gerente"> 
	                    <?php echo $html; ?>
	                </select>
		        </td>
	        </tr>
        </table>
        </form>
        <?php

    }else{

        ?>No existe  ningun gerente disponible. Para abrir la sucursal, haga click <a href="gerentes.php?action=nuevo">aqui</a> para crear un nuevo gerente.<?php
    
    }

?>





<?php

if($posiblesGerentes > 0 ){
    ?>
    <h2>Detalles de la sucursal</h2>
	<form id="detalles">
	<table border="0" cellspacing="5" cellpadding="5">
		<tr><td>Descripcion</td><td><input type="text" id="descripcion" size="40"/></td><td><div id="descripcion_helper"></div></td></tr>
		<tr><td>Direccion</td><td><input type="text" id="direccion" size="40"/></td><td><div id="direccion_helper"></div></td></tr>
		<tr><td>Telefono</td><td><input type="text" id="telefono" size="40"/></td><td><div id="telefono_helper"></div></td></tr>
		<tr><td>RFC</td><td><input type="text" id="rfc" size="40"/></td><td><div id="rfc_helper"></div></td></tr>
		<tr><td>Prefijo Factura</td><td><input type="text" id="letras_factura" size="40"/></td><td><div id="letras_factura_helper"></div></td></tr>

	</table>
	</form>
	<div align="center"><input type="button" onClick="validar()" value="Abrir esta sucursal"/></div>
<?php
}

