<?php

    require_once("controller/personal.controller.php");

	$gerentes = listarGerentes(true);


	$header = array(
		"id_usuario" => "ID",
		"nombre" => "Nombre",
		"RFC" => "RFC",
		"telefono" => "Telefono",
		"gerencia_sucursal_desc" => "Sucursal" );

	$tabla = new Tabla( $header, $gerentes );
	$tabla->addOnClick("id_usuario", "mostrarDetalles");
	$tabla->addNoData("No hay gerentes asignados.");
?>

<script>
	jQuery("#MAIN_TITLE").html("Gerencias	");
</script>
<h2><img src='../media/icons/user_business_chart_32.png'>Gerentes asignados a una sucursal</h2>

<?php 

$tabla->render();

$gerentes = listarGerentes(false);

if(sizeof($gerentes)>0){
    ?><h2><img src='../media/icons/user_business_warning_32.png'>Gerentes no asignados a una sucursal</h2><?php



    //render the table
    $header = array(
        "id_usuario" => "ID",
        "nombre" => "Nombre",
        "telefono" => "Telefono",
        "RFC" => "RFC" );

    $tabla = new Tabla( $header, $gerentes );
    $tabla->addOnClick("id_usuario", "mostrarDetalles");
    $tabla->addNoData("No hay gerentes sin asignar.");
    $tabla->render();
}



$gru1 = new GruposUsuarios();
$gru1->setIdGrupo('2');
$result = GruposUsuariosDAO::search($gru1);

$fired = array();

foreach($result as $r)
{
	$usr = UsuarioDAO::getByPK( $r->getIdUsuario() );

	if( $usr->getActivo() == "0"){
	    array_push($fired, $usr );
	}
}

if(sizeof($fired) > 0){
	?><h2><img src='../media/icons/user_business_close_32.png'>Gerentes despedidos</h2><?php
	
    $header = array(
	    "id_usuario" => "ID",
	    "nombre" => "Nombre",
	    "telefono" => "Telefono",
	    "RFC" => "RFC" );
	$tabla = new Tabla( $header, $fired );
	$tabla->addOnClick("id_usuario", "mostrarDetalles");
	$tabla->render();
}


?>
<script type="text/javascript" charset="utf-8">
	function mostrarDetalles( a ){
		window.location = "gerentes.php?action=detalles&id=" + a;
	}
</script>










<?php

require_once('admin/gerentes.asignar.php');


require_once('admin/gerentes.nuevo.php');
