<?php 


	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();

	//
	// Parametros necesarios
	// 
	$page->requireParam(  "uid", "GET", "Este usuario no existe." );
	$este_usuario = UsuarioDAO::getByPK( $_GET["uid"] );
	$esta_direccion = DireccionDAO::getByPK($este_usuario->getIdDireccion());

	//
	// Titulo de la pagina
	// 
	$page->addComponent( new TitleComponent( "Editar usuario " . $este_usuario->getNombre() , 2 ));

	//
	// Forma de usuario
	// 
	if(is_null($esta_direccion)){
		$esta_direccion = new Direccion();
	}
	

	$este_usuario->setPassword("");

	$form = new DAOFormComponent( $este_usuario  );

	$form->hideField( array( 
		"id_usuario",
		"id_direccion",
		"id_direccion_alterna",
		"id_sucursal",
		"fecha_asignacion_rol",
		"fecha_alta",
		"fecha_baja",
		"activo",
		"last_login",
		"consignatario",
		"id_clasificacion_cliente",
		"id_clasificacion_proveedor",
		"tarifa_venta_obtenida",
		"tarifa_compra_obtenida",
		"id_tarifa_compra",
		"id_tarifa_venta",
		"saldo_del_ejercicio",
		"intereses_moratorios",
		"representante_legal",
		"pagina_web",
		"mensajeria",
		"denominacion_comercial",
		"dias_de_credito",
		"facturar_a_terceros",
		"limite_credito",
		"token_recuperacion_pass"
	));


	$form->sendHidden("id_usuario");

	

	$form->addApiCall( "api/personal/usuario/editar/" );
	
	//$form->onApiCallSuccessRedirect("personal.lista.usuario.php");

	$form->createComboBoxJoin( "id_rol", "nombre", RolDAO::getAll(), $este_usuario->getIdRol() );

	$form->createComboBoxJoin( "id_moneda", "nombre", MonedaDAO::search( new Moneda( array( "activa" => 1 ) ) ),$este_usuario->getIdMoneda() );

	$form->setType("password", "password");

	$page->addComponent('
		<script>
			function beforeEdit(p){
				console.log(p);
				return p;
			}
		</script>');

	$form->beforeSend("beforeEdit");

	$page->addComponent( $form );

	$page->render();
