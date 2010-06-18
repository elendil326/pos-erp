<?php	include_once("AddAllClass.php");
	
	
	function insertarInventario(){
		if((!empty($_REQUEST['nombre']))&&(!empty($_REQUEST['denominacion']))){
			$nombre=$_REQUEST['nombre'];
			$denominacion=$_REQUEST['denominacion'];
			$inventario=new inventario($nombre,$denominacion);
			if(!$inventario->existe()){
				if($inventario->inserta()){		ok();
				}else							fail("Error al guardar el producto.");
			}else 								fail("Ya existe este producto.");
		}else									fail("Faltan datos.");
		return;
	}
	
	function eliminarInventario(){
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
	
	function actualizarInventario(){
		if((!empty($_REQUEST['id_inventario']))&&(!empty($_REQUEST['nombre']))&&(!empty($_REQUEST['denominacion']))){
			$id=$_REQUEST['id_inventario'];
			$nombre=$_REQUEST['nombre'];
			$denominacion=$_REQUEST['denominacion'];
			$inventario=new Inventario_existente($id);
			if($inventario->existe()){
				$inventario->nombre=$nombre;
				$inventario->denominacion=$denominacion;
				if($inventario->actualiza())	ok();
				else							fail("Error al modificar el producto.");
			}else								fail("El producto que desea modificar no existe.");
		}else									fail("Faltan datos.");
		return;
	}
	
	function listarProductosInventario(){
		$listar = new listar("select inventario.id_producto, inventario.denominacion, detalle_inventario.precio_venta from inventario inner join detalle_inventario on inventario.id_producto = detalle_inventario.id_producto where detalle_inventario.id_sucursal=1",array());
		echo $listar->lista();
		return;
	}
	if(!empty($_REQUEST['method']))
	{
		switch($_REQUEST["method"]){
			case "insertarInventario" : 			insertarInventario(); break;
			case "eliminarInventario" : 			eliminarInventario(); break;
			case "actualizarInventario" : 			actualizarInventario(); break;
			case "listarProductosInventario" : 		listarProductosInventario(); break;
			default: echo "-1"; 
		}
	}
?>