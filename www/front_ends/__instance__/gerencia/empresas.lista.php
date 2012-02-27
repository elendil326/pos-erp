<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
		
		$page->addComponent( new TitleComponent( "Empresas" ) );

		$results = EmpresasController::Buscar();

		$tabla = new TableComponent( 
			array(
				"razon_social"=> "Razon Social",
				"rfc"=> "RFC",
				"representante_legal"=> "Representante Legal",
				"fecha_alta" => "Fecha Alta",
				"activo"=> "Activa"
			),
			$results["resultados"]
		);
		
		function funcion_activa($activa){
			return $activa ? "Activa" : "Inactiva";
		}

		$tabla->addColRender("activo", "funcion_activa");
                
		$tabla->addOnClick( "id_empresa", "(function(a){window.location = 'empresas.ver.php?eid='+a;})" );
		
		$tabla->addNoData("No hay ninguna empresa registada. <a href='empresas.nuevo.php'>&iquest; Tal vez desee crear una ahora ?</a>");
		
		$page->addComponent( $tabla );
		
		$page->render();
