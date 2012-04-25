<?php



define("BYPASS_INSTANCE_CHECK", false);

require_once("../../../../server/bootstrap.php");

$page = new GerenciaComponentPage();


//
// Parametros necesarios
// 
$page->requireParam("oid", "GET", "Esta orden de servicio no existe.");
$esta_orden = OrdenDeServicioDAO::getByPK($_GET["oid"]);


//
// Titulo de la pagina
// 


$customer         = UsuarioDAO::getByPK($esta_orden->getIdUsuarioVenta());
$link_to_customer = "<a href='clientes.ver.php?cid=" . $esta_orden->getIdUsuarioVenta() . "'>";
$link_to_customer .= $customer->getNombre();
$link_to_customer .= "</a>";



$page->addComponent(new TitleComponent("Orden de servicio " . $_GET["oid"] . " para " . $link_to_customer, 2));





//
// Forma de producto
// 

$esta_orden->setFechaOrden( FormatTime( strtotime ($esta_orden->getFechaOrden(  ) )) );
$form = new DAOFormComponent($esta_orden);
$form->setEditable(true);

$form->hideField(array(
	"id_usuario_venta",
	"extra_params",
	"motivo_cancelacion",
	"fecha_entrega",
	"cancelada",
	"adelanto",
	"activa",
	"id_usuario",
	"descripcion",
	"fecha_orden"
));

$form->renameField(array("id_orden_de_servicio" => "id_orden"));
$form->sendHidden( "id_orden" );

$form->addApiCall( "api/servicios/orden/editar", "POST" );
$form->createComboBoxJoin("id_servicio", "nombre_servicio", ServicioDAO::getAll(), $esta_orden->getIdServicio());

//$form->createComboBoxJoin("id_usuario_asignado", "nombre", UsuarioDAO::getAll(), $esta_orden->getIdUsuarioAsignado());

$personal = UsuarioDAO::search(new Usuario(  ));

$form->createComboBoxJoinDistintName(	$field_name 			= "id_usuario_asignado", 
										$table_name				= "id_usuario", 
										$field_name_in_values	= "nombre", 
										$values_array 			= UsuarioDAO::getAll(), 
										$selected_value 		= null);
										
$form->createComboBoxJoin("id_usuario", "nombre", UsuarioDAO::getAll(), $esta_orden->getIdUsuario());

//$form->createComboBoxJoinDistintName("id_usuario_venta", "id_usuario", "nombre", UsuarioDAO::getAll(), $esta_orden->getIdUsuarioVenta());
$page->addComponent($form);




$page->render();
