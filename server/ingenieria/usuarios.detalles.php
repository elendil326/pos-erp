<?php

require_once("model/usuario.dao.php");


if(isset($_REQUEST['pass'])){
	$usr = UsuarioDAO::getByPK( $_REQUEST['id'] );
	$reason = 'Cambio exitoso';
	$success = true;
	
	try{
		$usr->setContrasena( $_REQUEST['pkey'] );
		UsuarioDAO::save( $usr );
	}catch(Exception $e){
		$success = false;
		$reason = $e;
	}
}


$usr = UsuarioDAO::getByPK( $_REQUEST['id'] );

?>

<script src="../frameworks/jquery/jquery-1.4.2.min.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/uniform/jquery.uniform.min.js" type="text/javascript" charset="utf-8"></script> 
<link rel="stylesheet" href="../frameworks/uniform/css/uniform.default.css" type="text/css" media="screen">
<script type="text/javascript" charset="utf-8"> $(function(){ $("input, select").uniform(); }); </script>
<script>
	function testP()
	{
			if($('#p1').val() != $('#p2').val()){
				alert('las contasenas no coinciden');
				return false;
			}
			$('#pkey').val( hex_md5( $('#p2').val() ) );
			return true;
	}
</script>

<?php
	if(isset($success) ){
		if($success){
			echo "<div class='success'>{$reason}</div>";
		}else{
			echo "<div class='failure'>{$reason}</div>";		
		}
	}
?>

<h1><?php echo $usr->getNombre(); ?></h1>

<h2>Detalles del usuario</h2>
<table border="0" cellspacing="5" cellpadding="5">
	<tr><td><b>Nombre</b></td><td><?php echo $usr->getNombre(); ?></td></tr>
	<tr><td><b>RFC</b></td><td><?php echo $usr->getRFC(); ?></td></tr>
	<tr><td><b>Direccion</b></td><td><?php echo $usr->getDireccion(); ?></td></tr>
	<tr><td><b>Telefono</b></td><td><?php echo $usr->getTelefono(); ?></td></tr>	
</table>


<h2>Editar detalles</h2>
<table border="0" cellspacing="5" cellpadding="5">
	<tr><td><b>Nombre</b></td><td><input type='text' value='<?php echo $usr->getNombre(); ?>'></td></tr>
	<tr><td><b>RFC</b></td><td><input type='text' value='<?php echo $usr->getRFC(); ?>'></td></tr>
	<tr><td><b>Direccion</b></td><td><input type='text' value='<?php echo $usr->getDireccion(); ?>'></td></tr>
	<tr><td><b>Telefono</b></td><td><input type='text' value='<?php echo $usr->getTelefono(); ?>'></td></tr>	
	<tr><td></td><td><input type='submit' value='Guardar'></td></tr>	
</table>


<h2>Editar contrase&ntilde;a</h2>
<form onSubmit='return testP()' action='usuarios.php?action=detalles&id=<?php echo $_REQUEST['id']?>' method='POST'>
<input type='hidden' name='pass' value='edit'>
<input type='hidden' name='pkey' value='' id='pkey'>
<table border="0" cellspacing="5" cellpadding="5">
	<tr><td><b>Actual</b></td><td><?php echo $usr->getContrasena(); ?></td></tr>
	<tr><td><b>Nueva</b></td><td><input type='password' id='p1' ></td></tr>
	<tr><td><b>Repetir</b></td><td><input type='password' id='p2' ></td></tr>
	<tr><td></td><td><input type='submit' value='Guardar'></td></tr>	
</table>
</form>
