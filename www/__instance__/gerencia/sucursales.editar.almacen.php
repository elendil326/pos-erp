<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                //
		// Parametros necesarios
		// 
		$page->requireParam(  "aid", "GET", "Este almacen no existe." );
		$este_almacen = AlmacenDAO::getByPK( $_GET["aid"] );
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Editar almacen " . $este_almacen->getNombre() , 2 ));

		//
		// Forma de usuario
		// 
		$form = new DAOFormComponent( $este_almacen );
		$form->hideField( array( 
                                "id_almacen",
                                "id_empresa",
                                "id_sucursal",
                                "activo"
			 ));
                
                $form->addApiCall( "api/sucursal/almacen/editar/", "GET" );
                $form->onApiCallSuccessRedirect("sucursales.lista.almacen.php");
                $form->sendHidden("id_almacen");
                
            
                $form->createComboBoxJoin("id_tipo_almacen", "descripcion", array_diff(TipoAlmacenDAO::getAll(), TipoAlmacenDAO::search( new TipoAlmacen( array( "id_tipo_almacen" => 2 ) ) ) ), $este_almacen->getIdTipoAlmacen() );
                
		$page->addComponent( $form );
                
		$page->render();
