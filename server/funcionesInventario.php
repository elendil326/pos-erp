<?php
	
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
		}else 								fail("faltan datos.");
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
		$listar = new listar("select inventario.id_producto, inventario.denominacion, detalle_inventario.precio_venta,detalle_inventario.existencias,detalle_inventario.id_sucursal,detalle_inventario.min from inventario inner join detalle_inventario on inventario.id_producto = detalle_inventario.id_producto",array());
		echo $listar->lista();
		return;
	}
	
	function listarProductosInventarioSucursal(){
	if(!empty($_REQUEST['id_sucursal'])){
		$id=$_REQUEST['id_sucursal'];
			$listar = new listar("select inventario.id_producto, inventario.denominacion, detalle_inventario.precio_venta,detalle_inventario.existencias,detalle_inventario.min from inventario inner join detalle_inventario on inventario.id_producto = detalle_inventario.id_producto where detalle_inventario.id_sucursal=?",array($id));
			echo $listar->lista();
			return;
		}											fail("faltan datos.");
		return;
	}
	
	function existenciaProductoSucursal(){
	if((!empty($_REQUEST['id_producto']))&&(!empty($_REQUEST['id_sucursal']))){
		$id_producto=$_REQUEST['id_producto'];
		$id_sucursal=$_REQUEST['id_sucursal'];
			$prod=new inventario_existente($id_producto);
			if($prod->existe()){
				$sucursal=new sucursal_existente($id_sucursal);
				if($sucursal->existe()){		
					$producto=new detalle_inventario_existente($id_producto,$id_sucursal);
					if($producto->existe()){
						$query="SELECT id_producto, nombre, denominacion, precio_venta, existencias
								FROM detalle_inventario
								NATURAL JOIN inventario
								where id_producto=? and id_sucursal=?";
								$listar = new listar($query,array($id_producto,$id_sucursal));
								echo $listar->lista();
					}else																	fail("Este producto no existe en esta sucursal.");	
				}else																		fail("La sucursal no existe");
			}else																			fail("El producto no existe");
		}else																				fail("faltan datos.");	
		return;
	}
	
	
?>
