<?php

	if(!class_exists("PHPUnit_Runner_Version")){
		define("BYPASS_INSTANCE_CHECK", false);
		require_once("../../../../server/bootstrap.php");
	}
	
	
	
	$page = new ClienteComponentPage();
	$banner = new BannerComponent("POS ERP", "Bienvenido a POS ERP <br>un sistema de gestion empresarial", "../../../media/EAbydW1M_XR.png");
	$page->addComponent($banner);
	
	
	//buscar si tiene ordenes de servico a su nombre
	$sesion_actual = SesionController::Actual();
	
	
	$ordenes_array = new OrdenDeServicio( array("id_usuario_venta" => $sesion_actual["id_usuario"]));
	
	$ordenes = OrdenDeServicioDAO::search( $ordenes_array );
	
	if(sizeof($ordenes) > 0 ){
		$page->addComponent("Sus ordenes");
		
		$table = new TableComponent(array(
			"activa" => "activa",
			"id_servicio" => "id_servicio",
			"fecha_orden" => "fecha de Orden"
		), $ordenes);
		
		function renderIdServicio($id){
			$serv = ServicioDAO::getByPK($id);
			return $serv->getNombreServicio();
		}
		
		$table->addColRender("id_servicio", "renderIdServicio");
		$page->addComponent($table);
	}
	
	
	$page->render();





