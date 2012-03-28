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
	$page->partialRender();


	//clientes

	//empresas

	//sucursales

	//productos

	//personal


	?>
	<h3>Al parecer aun no esta aprovechando todo el potencial de Caffeina POS.</h3>
		<table>
			<tr>
				<td><img src="../../../media/iconos/1332931020_Photomanipulation.png"></td>
				<td>
				<table>
					<tr>
						<td><img src="../../../media/iconos/1332931344_cross.png"></td>
						<td>De de alta a su empresa.</td>
						
					</tr>
					<tr>
						<td><img src="../../../media/iconos/1332931344_cross.png"></td>
						<td>De de alta sus sucursales.</td>
						
					</tr>
					<tr>
						<td><img src="../../../media/iconos/1332931349_tick.png"></td>
						<td></td>
						
					</tr>										
					
				</table>
				</td>
			</tr>
		</table>
		



	<?php
	
	
	
	$page->render();





