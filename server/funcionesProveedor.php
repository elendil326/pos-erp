<?php	include_once("AddAllClass.php");
 
	function addproducto(){
		if((!empty($_REQUEST['clave_producto']))&&(!empty($_REQUEST['id_proveedor']))&&(!empty($_REQUEST['id_inventario']))&&(!empty($_REQUEST['descripcion']))&&(!empty($_REQUEST['precio']))){
			$clave_producto=$_REQUEST['clave_producto'];
			$id_proveedor=$_REQUEST['id_proveedor'];
			$id_inventario=$_REQUEST['id_inventario'];
			$descripcion=$_REQUEST['descripcion'];
			$precio=$_REQUEST['precio'];
			$producto=new productos_proveedor($clave_producto,$id_proveedor,$id_inventario,$descripcion,$precio);
			$verifica_proveedor=new proveedor_existente($id_proveedor);
			if($verifica_proveedor->existe()){
				$verifica_inventario=new inventario_existente($id_inventario);
				if($verifica_inventario->existe()){
					if(!$producto->existe_producto_proveedor()){
						if(!$producto->existe_proveedor_inventario()){
							if(!$producto->existe()){
								if($producto->inserta()){		ok();
								}else							fail("Error al guardar el producto.");
							}else 								fail("Ya existe este producto.");
						}else 									fail("El proveedor ya nos vende un producto con esta clave interna.");
					}else 										fail("El proveedor ya tiene un producto con esta clave.");
				}else 											fail("El producto no esta dado de alta en almacen.");
			}else 												fail("No existe el proveedor del producto.");
		}else													fail("Faltan datos.");
		return;
	}
	
	function deleteproducto(){
		if(!empty($_REQUEST['id_producto'])){
			$id=$_REQUEST['id_producto'];
			$producto=new productos_proveedor_existente($id);
			if($producto->existe()){
				if($producto->borra())	ok();
				else						fail("Error al borrar el producto.");
			}else 							fail("El producto que desea eliminar no existe.");
		}else fail("faltan datos.");
		return;
	}
	
	function cambiaDatosProducto(){
		if((!empty($_REQUEST['id_producto']))&&(!empty($_REQUEST['clave_producto']))&&(!empty($_REQUEST['id_proveedor']))&&(!empty($_REQUEST['id_inventario']))&&(!empty($_REQUEST['descripcion']))&&(!empty($_REQUEST['precio']))){
			$id_producto=$_REQUEST['id_producto'];
			$clave_producto=$_REQUEST['clave_producto'];
			$id_proveedor=$_REQUEST['id_proveedor'];
			$id_inventario=$_REQUEST['id_inventario'];
			$descripcion=$_REQUEST['descripcion'];
			$precio=$_REQUEST['precio'];
			$producto=new productos_proveedor_existente($id_producto);
			$producto->clave_producto=$clave_producto;
			$producto->id_proveedor=$id_proveedor;
			$producto->id_inventario=$id_inventario;
			$producto->descripcion=$descripcion;
			$producto->precio=$precio;
			$verifica_proveedor=new proveedor_existente($id_proveedor);
			if($verifica_proveedor->existe()){
				$verifica_inventario=new inventario_existente($id_inventario);
				if($verifica_inventario->existe()){
					if(!$producto->existe_producto_proveedor_id()){
						if(!$producto->existe_proveedor_inventario_id()){
							if($producto->existe()){
								if($producto->actualiza()){		ok();
								}else							fail("Error al guardar el producto.");
							}else 								fail("El producto no existe.");
						}else 									fail("El proveedor ya nos vende un producto con esta clave interna.");
					}else 										fail("El proveedor ya tiene un producto con esta clave.");
				}else 											fail("El producto no esta dado de alta en almacen.");
			}else 												fail("No existe el proveedor del producto.");
		}else													fail("Faltan datos.");
		return;
	}
	
	function listarProveedores(){
		$listar = new listar("select * from proveedor",array());
		echo $listar->lista();
		return;
	}
	
	if(!empty($_REQUEST['method']))
	{
		switch($_REQUEST["method"]){
			case "addProducto" : 				addProducto(); break;
			case "deleteProducto" : 			deleteProducto(); break;
			case "cambiaDatosProducto" : 		cambiaDatosProducto(); break;
			case "listarProveedores" : 			listarProveedores(); break;
			default: echo "-1"; 
		}
	}
?>