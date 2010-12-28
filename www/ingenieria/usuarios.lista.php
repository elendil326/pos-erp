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

