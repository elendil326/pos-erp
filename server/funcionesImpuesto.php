<?	include_once("AddAllClass.php");
	
	
	function addImpuesto(){
		if((isset($_REQUEST['descripcion']))&&(isset($_REQUEST['valor']))){
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
	
	function deleteImpuesto(){
		if(isset($_REQUEST['id_impuesto'])){
			$id=$_REQUEST['id_impuesto'];
			$impuesto=new Impuesto_existente($id);
			if($impuesto->existe()){
				if($impuesto->borra())		ok();
				else						fail("Error al borrar Impuesto.");
			}else 							fail("El Impuesto que desea eliminar no existe.");
		}else fail("faltan datos.");
		return;
	}
	
	function cambiaDatos(){
		if((isset($_REQUEST['id_impuesto']))&&(isset($_REQUEST['descripcion']))&&(isset($_REQUEST['valor']))){
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
	
	if(isset($_REQUEST['method']))
	{
		switch($_REQUEST["method"]){
			case "addImpuesto" : 			addImpuesto(); break;
			case "deleteImpuesto" : 		deleteImpuesto(); break;
			case "cambiaDatos" : 			cambiaDatos(); break;
			default: echo "-1"; 
		}
	}
?>