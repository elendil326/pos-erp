<?php



	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");

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

	$banner = new BannerComponent("POS ERP", "Bienvenido a POS ERP <br>un sistema de gestion empresarial", "../../../media/EAbydW1M_XR.png");
	
	
	
	$page->addComponent( $banner );
	
	$page->render();





