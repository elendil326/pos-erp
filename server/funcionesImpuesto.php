<?php	
	function insertarImpuesto(){
		if((!empty($_REQUEST['descripcion']))&&(!empty($_REQUEST['valor']))){
			$descripcion=$_REQUEST['descripcion'];
			$valor=$_REQUEST['valor'];
			$impuesto=new Impuesto($descripcion,$valor);
			if(!$impuesto->existe()){
				if($impuesto->inserta()){
					ok();
				}else				fail("Error al guardar el Impuesto.");
			}else 					fail("Ya existe este Impuesto.");
		}else						fail("Faltan datos.");
		return;
	}
	
	function eliminarImpuesto(){
		if(!empty($_REQUEST['id_impuesto'])){
			$id=$_REQUEST['id_impuesto'];
			$impuesto=new Impuesto_existente($id);
			if($impuesto->existe()){
				if($impuesto->borra())		ok();
				else						fail("Error al borrar Impuesto.");
			}else 							fail("El Impuesto que desea eliminar no existe.");
		}else fail("faltan datos.");
		return;
	}
	
	function actualizarImpuesto(){
		if((!empty($_REQUEST['id_impuesto']))&&(!empty($_REQUEST['descripcion']))&&(!empty($_REQUEST['valor']))){
			$id=$_REQUEST['id_impuesto'];
			$descripcion=$_REQUEST['descripcion'];
			$valor=$_REQUEST['valor'];
			$impuesto=new Impuesto_existente($id);
			
			if($impuesto->existe()){
				
				$impuesto->descripcion=$descripcion;
				$impuesto->valor=$valor;
				
				if($impuesto->actualiza())
					ok();
				else							
					fail("Error al modificar el Impuesto.");
					
			}else{
				fail("El Impuesto que desea modificar no existe.");
			}
				
		}else{
			fail("Faltan datos.");
		}									
		return;
	}
	
	function listarImpuesto(){
		$listar = new listar("select * from impuesto",array());
		echo $listar->lista();
		return;
	}
?>