<?php

	require_once('model/usuario.dao.php');
	$usuarios = UsuarioDAO::getAll();
	$header = array(
		'id_usuario' => 'id_usuario',
		'nombre' => 'Nombre',
		'id_sucursal' => 'id_sucursal',
		'activo' => 'Activo'
	);
	
	$tabla = new Tabla($header, $usuarios);
	$tabla->addOnClick('id_usuario','detalles');

	
?>

<script>function detalles(id){window.location = 'usuarios.php?action=detalles&id='+id;}</script>





<h1>Usuarios</h1>
<?php $tabla->render(); ?>


<script>
	function test()
	{
			if(jQuery('#p1').val() != jQuery('#p2').val()){
				alert('las contasenas no coinciden');
				return false;
			}

			send();
	}
	
	
	function send(){
		data = {
			RFC : jQuery('#rfc').val(),
			nombre : jQuery('#nombre').val(),
			contrasena : hex_md5( jQuery('#p2').val() ),
			salario : 0,
			telefono: "",
			direccion : "",
			grupo : jQuery('#gpo').val()
		};
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

                    alert(response.reason);
                    return;
                }


                reason = "Nuevo usuario creado exitosamente";
                window.location = 'usuarios.php?action=lista&success=true&reason=' + reason;
	      }
	    });
    }
</script>



<h1>Nuevo Usuario</h1>
<table border="0" cellspacing="5" cellpadding="5" >
	<tr><td><b>Nombre</b></td><td>						<input type='text' id='nombre' ></td></tr>
	<tr><td><b>RFC</b></td><td>							<input type='text' id='rfc' ></td></tr>
	<tr><td><b>GRUPO</b></td><td>						<input type='text' id='gpo' placeholder='0:Ingenieria, 1:Admin'></td></tr>
	<tr><td><b>Nueva contrase&ntilde;a</b></td><td>		<input type='password' id='p1' ></td></tr>
	<tr><td><b>Repetir contrase&ntilde;a</b></td><td>	<input type='password' id='p2' ></td></tr>
	<tr><td></td><td><input type='button' onClick='test()' value='Guardar'></td></tr>	
</table>
</form>
