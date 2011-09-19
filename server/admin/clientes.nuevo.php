<?php

	/*$p = new StdComponentPage();

	$nuevo_cliente = new DAOFormComponent( new Cliente() );

	$nuevo_cliente->hideField( "id_cliente" );
	$nuevo_cliente->hideField( "id_usuario" );


	$p->addComponent($nuevo_cliente);

	$p->render();

	return;*/

?>

<h2>Detalles del nuevo cliente</h2>
<form id="newClient">
	<table border="0" cellspacing="5" cellpadding="5" style="width:100%">
		<tr>
			<td>Razon Social</td><td><input type="text" size="40" id="razon_social" placeholder="Obligatorio"/></td>	
			<td>RFC</td><td><input type="text" size="40" id="rfc" placeholder="Obligatorio" /></td>
		</tr>
		<tr>	
			<td>Limite de credito</td><td><input type="text" size="40" placeholder="Obligatorio" id="limite_credito"/></td>
			<td>Descuento</td><td><input type="text" size="40" id="descuento"/></td>		
		</tr>
		<tr>
			<td>Calle</td><td><input type="text" size="40" id="calle"/></td>
			<td>Numero Exterior</td><td><input type="text" size="40" id="numero_exterior"/></td>
		</tr>
			<td>Numero Interior</td><td><input type="text" size="40" id="numero_interior"/></td>
			<td>Colonia</td><td><input type="text" size="40" id="colonia"/></td>		
		<tr>	
		</tr>
			<td>Referencia</td><td><input type="text" size="40" id="referencia"/></td>
			<td>Localidad</td><td><input type="text" size="40" id="localidad"/></td>	
		<tr>	
		</tr>
			<td>Municipio</td><td><input type="text" size="40" id="municipio"/></td>
			<td>Estado</td><td><input type="text" size="40" id="estado"/></td>
		<tr>
		</tr>	
			<td>Pais</td><td><input type="text" size="40" id="pais"/></td>
			<td>Codigo Postal</td><td><input type="text" size="40" id="codigo_postal"/></td>		
		<tr>
		</tr>	
			<td>Telefono</td><td><input type="text" size="40" id="telefono"/></td>	
			<td>Email</td><td><input type="text" size="40" id="e_mail"/></td>					
		<tr>	


		<?php if(POS_MULTI_SUCURSAL){	?>
		<tr>
			<td>Sucursal</td><td>
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
		<?php } ?>
		<tr><td colspan=4>
			<h4><input type="button" onClick="validar()" value="Crear el nuevo cliente"/> </h4>
		</td>	
</tr>
	</table>
</form>


<script type="text/javascript" charset="utf-8">
	function error(title){
		jQuery('html,body').animate({scrollTop: 0 }, 1000);
        return jQuery("#ajax_failure").html(title).show();		
	}

    function validar(){

        if(jQuery('#razon_social').val().length < 8){
			return error("La razon social es muy corta");
        }


        if(jQuery('#rfc').val().length < 7){
			return error("El rfc es muy corto.");
        }


        guardar({
            razon_social : 	jQuery('#razon_social').val(), 
            rfc : jQuery("#rfc").val(), 
            calle : jQuery("#calle").val(), 
            numero_exterior : jQuery("#numero_exterior").val(), 
            numero_interior : jQuery("#numero_interior").val(), 
            colonia : jQuery("#colonia").val(), 
            referencia : jQuery("#referencia").val(), 
            localidad : jQuery("#localidad").val(), 
            municipio : jQuery("#municipio").val(), 
            estado : jQuery("#estado").val(), 
            pais : jQuery("#pais").val(), 
            codigo_postal : jQuery("#codigo_postal").val(), 
            telefono : jQuery("#telefono").val(), 
            e_mail : jQuery("#e_mail").val(), 
            limite_credito : jQuery("#limite_credito").val(), 
            descuento : jQuery("#descuento").val(), 
            id_sucursal : jQuery("#sucursal").val(), 
            id_usuario : <?php echo $_SESSION['userid']; ?>
        });  


    }






    function guardar( data )
    {

        jQuery.ajaxSettings.traditional = true;


        jQuery.ajax({
	      url: "../proxy.php",
	      data: { 
            action : 301, 
            data : jQuery.JSON.encode(data)
           },
	      cache: false,
	      success: function(data){
		        response = jQuery.parseJSON(data);

                if(response.success == false){
					jQuery('html,body').animate({scrollTop: 0 }, 1000);	
                    return jQuery("#ajax_failure").html(response.reason).show();
                }


                reason = "Se ha creado el nuevo cliente.";
                window.location = 'clientes.php?action=lista&success=true&reason=' + reason;
	      }
	    });
    }

</script>


<?php
