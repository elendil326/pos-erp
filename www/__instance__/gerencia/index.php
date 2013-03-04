<?php

	if(!class_exists("PHPUnit_Runner_Version")){
		define("BYPASS_INSTANCE_CHECK", false);
		require_once("../../../server/bootstrap.php");
	}

	$page = new GerenciaComponentPage();
	$page->partialRender();

	?>
		<table>
			<tr>
				<td><img src="http://cdn1.iconfinder.com/data/icons/musthave/48/Stock%20Index%20Up.png"></td>
				<td><strong>Su configuracion esta incompleta.</strong><br>De una vuelta por la configuracion de Caffeina POS para importar sus clientes y productos.</td>
			</tr>

		</table>
	<?php


	$banner = new BannerComponent("POS ERP", "Bienvenido a POS ERP <br>un sistema de gestion empresarial", "../../media/EAbydW1M_XR.png");
	$page->addComponent( $banner );




	/* *************************************************
	 * Orden de Servicio Pendientes
	 * ************************************************* */
	$s = SesionController::Actual( );

	$ordenes_mias = OrdenDeServicioDAO::search(new OrdenDeServicio(array( 
							"id_usuario_asignado" => $s["id_usuario"],
							"activa" 			=> true
						)));

	switch(sizeof($ordenes_mias)){
		case 0: break;

		case 1:
			$page->addComponent(new TitleComponent("Tienes 1 orden de servicio pendiente.", 3));
		break;

		default:
			$page->addComponent(new TitleComponent("Tienes " . sizeof($ordenes_mias) . " ordenes de servicio pendientes.", 3));
	}

	$page->render();

