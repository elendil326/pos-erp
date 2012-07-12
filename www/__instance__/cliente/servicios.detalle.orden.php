<?php



define("BYPASS_INSTANCE_CHECK", false);

require_once("../../../server/bootstrap.php");

$page = new ClienteTabPage();


//
// Parametros necesarios
// 
$page->requireParam("oid", "GET", "Esta orden de servicio no existe.");
$esta_orden = OrdenDeServicioDAO::getByPK($_GET["oid"]);

if(is_null($esta_orden)){
	$page->addComponent("Esta orden no existe");
	$page->render();
	exit;
}

//
// Titulo de la pagina
// 



$customer         = UsuarioDAO::getByPK( $esta_orden->getIdUsuarioVenta() );

if(!is_null($customer)){
	$link_to_customer = "<a href='clientes.ver.php?cid=" . $esta_orden->getIdUsuarioVenta() . "'>";
	$link_to_customer .= $customer->getNombre();
	$link_to_customer .= "</a>";
}else{
	$link_to_customer = "<span style='color:gray'>este cliente ya no existe</span>.";
}





$page->addComponent(new TitleComponent("Orden de servicio " . $_GET["oid"] . " para " . $link_to_customer, 2));

//
// Menu de opciones
// 

$page->nextTab("General");

$menu = new MenuComponent();




$imp = new MenuItem(" <img src='../../media/iconos/printer.png'> Imprimir", null);
$imp->addOnClick("_p", "function _p(){ window.open('servicios.detalle.orden.impresion.php?oid=". $_GET["oid"] ."'); }");
$menu->addMenuItem($imp);


	
$page->addComponent($menu);


//
// Forma de producto
// 

$esta_orden->setFechaOrden( FormatTime(  ($esta_orden->getFechaOrden(  ) )) );


$a = $esta_orden->getIdUsuarioAsignado( );



$asignado = UsuarioDAO::getByPK($a);

if(!is_null($asignado)){
	$esta_orden->setIdUsuarioAsignado($asignado->getNombre());	
}else{
	$esta_orden->setIdUsuarioAsignado("<img src='../../media/iconos/user_delete.png'> Nadie esta asignado");	
}


//$form->createComboBoxJoin("id_servicio", "nombre_servicio", ServicioDAO::getAll(), $esta_orden->getIdServicio());
//$form->createComboBoxJoin("id_usuario", "nombre", UsuarioDAO::getAll(), $esta_orden->getIdUsuario());


$servicio = ServicioDAO::getByPK($esta_orden->getIdServicio());
$esta_orden->setIdServicio($servicio->getNombreServicio());


$agente = UsuarioDAO::getByPK($esta_orden->getIdUsuario());
$esta_orden->setIdUsuario( $agente->getNombre() );

$esta_orden->setPrecio(FormatMoney($esta_orden->getPrecio()));

$form = new DAOFormComponent($esta_orden);
$form->setEditable(false);

$form->hideField(array(
	"id_orden_de_servicio",
	"id_usuario_venta",
	"extra_params",
	"motivo_cancelacion",
	"fecha_entrega",
	"cancelada",
	"adelanto",
	"activa",
	"descripcion"
));




$form->setCaption("id_usuario", "Agente de venta");

$form->setCaption("id_usuario_asignado", "Tecnico asignado");

$form->setCaption("id_servicio", "Servicio");

//$form->createComboBoxJoinDistintName("id_usuario_venta", "id_usuario", "nombre", UsuarioDAO::getAll(), $esta_orden->getIdUsuarioVenta());

$page->addComponent($form);




$eP = $esta_orden->getExtraParams();

if(!is_null($eP)){
	$ePObj = json_decode($eP);
	$page->addComponent( "<table width=100%>" );
	foreach ($ePObj as $obj) {

		$page->addComponent( "<tr><td style='width:30%'><b>" . utf8_decode( $obj->desc ) . "</b></td>");
		$page->addComponent( "<td>".$obj->value ."</td></tr>");		

	}
	$page->addComponent( "</table>" );	
}

$page->addComponent(new TitleComponent("Seguimientos de esta orden", 2));

$seguimientos = SeguimientoDeServicioDAO::seguimientosPorServicio($_GET["oid"]);

$header = array(
	"estado" => "Estado",
	"fecha_seguimiento" => "Fecha",
	"id_usuario" => "Usuario que registro"
);


//
// 
// 
// 
// 
// 
// 
// 
// 
$table = new TableComponent($header, $seguimientos);
$table->renderRowId("comments");
$table->addNoData("");
function funcion_sucursal($id_sucursal){
	return (SucursalDAO::getByPK($id_sucursal) ? SucursalDAO::getByPK($id_sucursal)->getRazonSocial() : "---------");
}

function funcion_usuario($id_usuario){
	if( is_null( $u = UsuarioDAO::getByPK($id_usuario) ) ){
		return "ERROR";
	}
	
	return $u->getNombre();
}



$table->addColRender("id_localizacion", "funcion_sucursal");
$table->addColRender("id_usuario", "funcion_usuario");
$table->addColRender("id_usuario_venta", "funcion_usuario");
$table->addColRender("fecha_seguimiento", "FormatTime");


$page->addComponent($table);


//
// 
// 
// 
// 
//
//
// 
// 
$form = new DAOFormComponent( new SeguimientoDeServicio( array("id_orden_de_servicio"=> $_GET["oid"]) ) );

$form->hideField( array( 
        "id_seguimiento_de_servicio",
        "id_usuario",
        "id_sucursal",
        "fecha_seguimiento",
		"id_localizacion"

));

$form->sendHidden		( "id_orden_de_servicio" );
$form->addApiCall		( "api/servicios/orden/seguimiento/" );
$form->setPlaceholder		( "estado", "Escriba aqui" );
$form->setType			( "estado" , "textarea" );
$form->onApiCallSuccess		( "comment_success" );
$form->renameField		( array( "estado" => "nota" ) );
$page->addComponent		( $form ); 
//$page->partialRender	( );

$js = '
<script type="text/javascript" charset="utf-8">

	var comment_success =  function( a, b, c ){

		var guiComponentId = "<?php echo $form->getGuiComponentId(); ?>";
		
		var comment = Ext.get(guiComponentId+"nota").getValue();
		
		document.getElementById(guiComponentId+"nota").value = "";
		

		
		var tabla = Ext.get("comments0")

		//si la tabla no existe, vamos a refrescar
		if(tabla == null) window.location = window.location;
		
		tabla = tabla.parent();
		
		var child = tabla.createChild({tag:"tr"});
		
		var tds1 = child.createChild({ tag:"td" }).update( comment );

		var tds2 = child.createChild({ tag:"td" }).update("Justo ahora");
		
		var tds3 = child.createChild({ tag:"td" });
				
		child.highlight();
		

	}
</script>
';
$page->addComponent($js);

$page->nextTab("Camara");


$v_html = '
	<div class="canvas" style="width:648px">
		<!-- http://www.axis.com/techsup/cam_servers/dev/cam_http_api_2.php#api_blocks_image_video_mjpg_video -->
		<!--<img src="http://bass-celaya.no-ip.org/axis-cgi/jpg/image.cgi">-->
		<img src="http://bass-celaya.no-ip.org/mjpg/1/video.mjpg">
		
	</div>';

$page->addComponent($v_html);

$page->render();
