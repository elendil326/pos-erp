<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
                
                 //titulos
	$page->addComponent( new TitleComponent( "Nueva caja" ) );

	//forma de nueva caja
	$form = new DAOFormComponent( array( new Caja() ) );
	
	$form->hideField( array( 
			"id_caja",
                        "abierta",
                        "saldo",
                        "activa"
		 ));

	
	$form->addApiCall( "api/sucursal/caja/nueva/" );
        $form->onApiCallSuccessRedirect("sucursales.lista.caja.php");
	
	$form->makeObligatory(array( 
			"token"
		));
        
        $form->createComboBoxJoin("control_billetes", "control_billetes", 
                        array(
                            array( "id" => 1, "caption" => "Llevar control" ),
                            array( "id" => 0, "caption" => "No llevar control"  ) 
                            )
                        );
	
	$form->createComboBoxJoin( "id_sucursal", "razon_social", SucursalDAO::search( new Sucursal( array( "activa" => 1 ) ) ) );
	
	$page->addComponent( $form );


	//render the page
		$page->render();
