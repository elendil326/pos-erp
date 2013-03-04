<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

	$sOrdenes = SeguimientoDeServicioDAO::GetAll();
	$sClientes 	= ClienteSeguimientoDAO::GetAll();

	$sMerged = array( );

	// seguimiento_de_servicio
	for ($i=0; $i < sizeof($sOrdenes); $i++) { 
		array_push( $sMerged, array(
				"text"		=> "<div style='overflow:hidden; width: 250px'><pre>" . $sOrdenes[$i]->estado . "</pre></div>",
				"tipo"		=> "sOrdenes",
				"fecha"		=> $sOrdenes[$i]->fecha_seguimiento,
				"usuario"	=> R::UserFullNameFromId ( $sOrdenes[$i]->id_usuario )
			) );
	}

	// 
	for ($i=0; $i < sizeof($sClientes); $i++) { 
		array_push( $sMerged, array(
				"text"		=> "<div style='overflow:hidden;  width: 715px'><pre>" . $sClientes[$i]->texto . "</pre></div>",
				"tipo"		=> "sclientes",
				"fecha"		=> $sClientes[$i]->fecha,
				"usuario"	=> R::UserFullNameFromId ( $sClientes[$i]->id_usuario )
			) );
	}

	function fs( $a, $b ){
		return $b["fecha"] - $a["fecha"];
	}

	usort( $sMerged, "fs" );

	$sTabla = new TableComponent( array(
				"text"		=> "text"
				/*"tipo"		=> "tipo",
				"fecha"		=> "fecha",
				"usuario"	=> "usuario"*/
			),
			$sMerged
		);

	$sTabla->addColRender( "fecha", "R::FriendlyDateFromUnixTime" );

	$page->addComponent($sTabla);
		$page->render();
