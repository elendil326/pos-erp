<?	include_once("AddAllClass.php");
	
	
	function addInventario(){
		if((!empty($_REQUEST['nombre']))&&(!empty($_REQUEST['precio']))&&(!empty($_REQUEST['minimo']))){
			$nombre=$_REQUEST['nombre'];
			$precio=$_REQUEST['precio'];
			$minimo=$_REQUEST['minimo'];
			$inventario=new inventario($nombre,$precio,$minimo);
			if(!$inventario->existe()){
				if($inventario->inserta()){		ok();
				}else							fail("Error al guardar el producto.");
			}else 								fail("Ya existe este producto.");
		}else									fail("Faltan datos.");
		return;
	}
	
	function deleteInventario(){
		if(!empty($_REQUEST['id_inventario'])){
			$id=$_REQUEST['id_inventario'];
			$inventario=new Inventario_existente($id);
			if($inventario->existe()){
				if($inventario->borra())	ok();
				else						fail("Error al borrar el producto.");
			}else 							fail("El producto que desea eliminar no existe.");
		}else fail("faltan datos.");
		return;
	}
	
	function cambiaDatos(){
		if((!empty($_REQUEST['id_inventario']))&&(!empty($_REQUEST['nombre']))&&(!empty($_REQUEST['precio']))&&(!empty($_REQUEST['minimo']))){
			$id=$_REQUEST['id_inventario'];
			$nombre=$_REQUEST['nombre'];
			$precio=$_REQUEST['precio'];
			$minimo=$_REQUEST['minimo'];
			$inventario=new Inventario_existente($id);
			if($inventario->existe()){
				$inventario->nombre=$nombre;
				$inventario->precio_venta=$precio;
				$inventario->minimo=$minimo;
				if($inventario->actualiza())	ok();
				else							fail("Error al modificar el producto.");
			}else								fail("El producto que desea modificar no existe.");
		}else									fail("Faltan datos.");
		return;
	}
	
	function listarProductos(){
		$listar = new listar("select * from inventario",array());
		echo $listar->lista();
		return;
	}
	if(!empty($_REQUEST['method']))
	{
		switch($_REQUEST["method"]){
			case "addInventario" : 			addinventario(); break;
			case "deleteInventario" : 		deleteinventario(); break;
			case "cambiaDatos" : 			cambiaDatos(); break;
			case "listarProductos" : 		listarProductos(); break;
			default: echo "-1"; 
		}
	}
?>