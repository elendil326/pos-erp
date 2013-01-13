<?php

	require_once("../../server/bootstrap.php");

	//queremos hacer un login a un sitio externo?
	if( isset($_GET["extern_login"]) )
	{
		//tenemos datos?
		$extern_url = $_GET["who"];

		
		$p = new PosComponentPage();

		$p->addComponent(new Titlecomponent( $_GET["extern_login"] /* $_SERVER["HTTP_REFERER"] */
								. "&nbsp;desea utilizar tu cuenta de Caffeina POS. " , 2  ));

		if( !SesionController::isLoggedIn() )
		{
			$login = new LoginComponent();
			$login->setLoginApiCall("api/sesion/iniciar/");
			$login->setExternLoginUrl( $_GET["extern_login"] );
			$p->addComponent( $login );

		}else{
			$p->addComponent('<div class="POS Boton">No permitir</div>');
			$p->addComponent('<div style="margin-right:0px" class="POS Boton OK" onclick="window.location = \'' .$_GET["extern_login"] . '?au=\' + Ext.util.Cookies.get(\'at\')" >Permitir</div>');
		}

		//var_dump(SesionController::Actual());

		$p->render();
		die;
	}





	if(isset($_GET["cs"]) && ($_GET["cs"] == 1)){
		SesionController::Cerrar();
	}

	//antes de crear el componente de login,
	//vamos a revisar si el usuario tiene una
	//sesion iniciada, y lo adentramos
	if(SesionController::isLoggedIn()){
		die(header( "Location: g/" ));
	}

	$page = new PosComponentPage("Iniciar sesion");
	$page->addComponent("<style>
		.devsitePage .nav > .content{
			background:white;	
		}
		.devsitePage .menu a.logo {
			border-right: 0px;
			padding: 0px;
		}
		.devsitePage .bodyText{
			margin-left: 0px;
		}</style>");

	$login = new LoginComponent();
	$login->setLoginApiCall("api/sesion/iniciar/");
	$page->addComponent( $login );
	$page->render();

