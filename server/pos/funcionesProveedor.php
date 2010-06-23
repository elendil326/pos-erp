﻿<?php


	function insertarProveedor(){
		$id =null;
		$rfc=$_REQUEST['rfcP'];
		$nombre=$_REQUEST['nombreP'];
		$direccion=$_REQUEST['direccionP'];
		$telefono=$_REQUEST['telefonoP'];
		$e_mail=$_REQUEST['e_mailP'];

		$proveedor = new proveedor($rfc,$nombre,$direccion,$telefono,$e_mail);
		
		if ($proveedor->inserta()){
			echo "{success: true }";
		}else{
			echo "{success: false }";
		}
	}
	function actualizarProveedor(){
		
		$proveedor = new proveedor_existente($_REQUEST['idP']); 
		$proveedor->id_proveedor=$_REQUEST['idP'];
		$proveedor->rfc=$_REQUEST['rfcP'];
		$proveedor->nombre=$_REQUEST['nombreP'];
		$proveedor->direccion=$_REQUEST['direccionP'];
		$proveedor->telefono=$_REQUEST['telefonoP'];
		$proveedor->e_mail=$_REQUEST['e_mailP'];
	
		
		if($proveedor->actualiza()){
			echo "{success: true}";
		}else{
			echo "{success: false}";
		}
	}
	
	function eliminarProveedor(){
		$proveedor = new proveedor_existente($_REQUEST['idP']); 
		$proveedor->id_proveedor =$_REQUEST['idP'];
		if($proveedor->borra()){
			echo "{success: true }";
		}else{
			echo "{success : false }";
		}
	}
	
	function listarProveedores(){
		$listar = new listar("select * from proveedor",array());
		echo $listar->lista();
		return $listar->lista();
	}
	
	function mostrarProveedor(){
		$id=$_REQUEST['idP'];
		$proveedor = new proveedor_existente($id);
		$proveedor->id_proveedor=$id;
		echo "{ success: true , \"datos\":".$proveedor->json()."}";
	}
	function insertarProductoProveedor(){
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
	
	function eliminarProductoProveedor(){
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
	
	function actualizarProductoProveedor(){
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
	
	function listarProveedor(){
		$listar = new listar("select * from proveedor",array());
		echo $listar->lista();
		return;
	}

	
?>