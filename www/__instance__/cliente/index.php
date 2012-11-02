<?php

	if(!class_exists("PHPUnit_Runner_Version")){
		define("BYPASS_INSTANCE_CHECK", false);
		require_once("../../../server/bootstrap.php");
	}
	
	
	
	$page = new ClienteComponentPage("Bienvendio a POS ERP");
	$banner = new BannerComponent("POS ERP", "Bienvenido a POS ERP <br>un sistema de gestion empresarial", "../../../media/EAbydW1M_XR.png");
	$page->addComponent($banner);
	
	
	//buscar si tiene ordenes de servico a su nombre
	$sesion_actual = SesionController::Actual();
	
	
	$ordenes_array = new OrdenDeServicio( array("id_usuario_venta" => $sesion_actual["id_usuario"]));
	
	$ordenes = OrdenDeServicioDAO::search( $ordenes_array );
	
	if(sizeof($ordenes) > 0 ){
		
		$page->addComponent(new TitleComponent("Sus ordenes de servicio",2));
		
		$table = new TableComponent(array(
			"id_servicio" => "Servicio",
			"fecha_orden" => "Fecha"
		), $ordenes);
		
		function renderIdServicio($id){
			$serv = ServicioDAO::getByPK($id);
			return $serv->getNombreServicio();
		}
		
		$table->addColRender("fecha_orden", "FormatTime");
		$table->addOnClick("id_orden_de_servicio", "(function(a){ window.location = 'servicios.detalle.orden.php?oid='+a; })");

		$table->addColRender("id_servicio", "renderIdServicio");
		$page->addComponent($table);
	}
	
	
	$page->render();





