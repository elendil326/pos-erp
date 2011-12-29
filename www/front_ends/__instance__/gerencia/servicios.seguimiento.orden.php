<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

		//
		// Parametros necesarios
		// 
		$page->requireParam(  "oid", "GET", "Esta orden de servicio no existe." );
		$esta_orden = OrdenDeServicioDAO::getByPK( $_GET["oid"] );

        if(is_null($esta_orden)){
			$page->addComponent( new TitleComponent("Ups", 2) );
			$page->addComponent( new TitleComponent("La orden ". $_GET["oid"] ." no existe", 3) );
			$page->render();
		}

		//
		// Titulo de la pagina
		// 
		$link = "<a href='servicios.detalle.orden.php?oid=". $_GET["oid"] ."'>". $_GET["oid"] ."</a>";
		$page->addComponent( new TitleComponent( "Seguimiento a la orden de servicio " . $link, 2 ));
		
		
		//
		// Forma de usuario
		// 
		$form = new DAOFormComponent( new SeguimientoDeServicio( array("id_orden_de_servicio"=> $_GET["oid"]) ) );
		
		$form->hideField( array( 
                "id_seguimiento_de_servicio",
                "id_usuario",
                "id_sucursal",
                "fecha_seguimiento"               
		));
        
		$form->makeObligatory( array("estado", "id_localizacion") );

		$form->sendHidden( "id_orden_de_servicio" );
        
		$form->addApiCall( "api/servicios/orden/seguimiento/" );
                $form->onApiCallSuccessRedirect("servicios.detalle.orden.php?oid=".$_GET["oid"]);
                
                $form->renameField( array( "estado" => "nota" ) );

                
        
                
                $form->createComboBoxJoinDistintName( "id_localizacion", "id_sucursal" , "razon_social", SucursalDAO::search(new Sucursal( array( "activa" => 1 ) )) );

		$page->addComponent( $form );
                
		$page->render();
