<?php
	function insertarProveedor(){
		$id =null;
		$rfc=$_REQUEST['rfcP'];
		$nombre=$_REQUEST['nombreP'];
		$direccion=$_REQUEST['direccionP'];
		$telefono=$_REQUEST['telefonoP'];
		$e_mail=$_REQUEST['e_mailP'];

		$proveedor = new proveedor($rfc,$nombre,$direccion,$telefono,$e_mail);
		
		if ($proveedor->inserta()){
			$cuenta_proveedor = new cuenta_proveedor($proveedor->id_proveedor,0);
			if($cuenta_proveedor->inserta()){
				echo "{success: true , id_proveedor: ".$proveedor->id_proveedor."}";
			}else{
				echo "{success: false }";
			}
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
			echo "{success: true }";
		}else{
			echo "{success: false }";
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
	
	
	
	
		//esta funcion nos regresa el total de las compras realizadas por los Proveedors
		//revisa por periodo si se el envia de y al
		//revisa por sucursal si se le envia id_sucursal
	function reporteCompraProveedor()
	{
		//asignamos los datos recibidos a las variables (en caso de que se reciban)
		$id_sucursal=$_REQUEST['id_sucursal'];
		$de=$_REQUEST['de'];
		$al=$_REQUEST['al'];
		//asignamos variables que seran booleanos para saber si nos enviaron parametros de sucursal y/o periodo
		$sucursal=!empty($id_sucursal);
		$fecha=(!empty($de)&&!empty($al));
		//inicializamos arreglo de periodo vacio
		$params=array();
		//inicializamos la consulta, esta sera final si no se enviaron parametros
		$query="select p.nombre,sum(c.subtotal+c.iva) as total
				from compras c natural join Proveedor p";
		//verificamos si se enviaron fechas
		if($fecha)
		{
				//agregamos la condicion que cuente los que esten en las fechas
				$query.=" where c.fecha BETWEEN ? AND ? ";
				//agrega los parametros a la pila
				array_push($params,$de,$al);
		}//if fechas
		$query.=" group by p.nombre ";
		//verificamos el booleano de sucursal
		if($sucursal){
			//agregamos having para que solo cuente los de la sucursal deseada
			$query.=" ,c.sucursal
					having c.sucursal=? ";
			//agregamos parametro al arreglo
			array_push($params,$id_sucursal);
		}//if sucursal
		//agregamos el ; final
		$query.=";";
		//creamos objeto de la clase listar y le pasamos el arreglo de parametros
		$listar = new listar($query,$params);
		//imprimimos el resultado
		echo $listar->lista();
		return;
	}
	//reporte compras Pr€€oveedor
	
	
	
	function listarProveedor(){
		$listar = new listar("select * from proveedor",array());
		echo $listar->lista();
		return;
	}
	
	//esta funcion me regresara cuanto le debo a mis proveedores cliente
	function listarProveedorSaldo(){
		$query="";
		$listar = new listar($query,array());
		echo $listar->lista();
		return $listar->lista();
	}
	//listarProveedorSaldo
	
	
	
	
	
	
	//esta funcion regresa un reporte de compras,
	//si se le envian fechas agrega un periodo
	//si se le agrega un id de sucursal tambien lo busca
	function reporteCompras()
	{
		//asignamos los datos recibidos a las variables (en caso de que se reciban)
		$id_sucursal=$_REQUEST['id_sucursal'];
		$de=$_REQUEST['de'];
		$al=$_REQUEST['al'];
		//asignamos variables que seran booleanos para saber si nos enviaron parametros de sucursal y/o periodo
		$sucursal=!empty($id_sucursal);
		$fecha=(!empty($de)&&!empty($al));
		//inicializamos arreglo de periodo vacio
		$params=array();
		//inicializamos la consulta, esta sera final si no se enviaron parametros
		$query="SELECT IF( c.tipo_compra =1,  'Contado',  'Credito' ) AS  'Tipo', DATE( c.fecha ) AS  'Fecha', c.subtotal AS  'Subtotal', c.iva AS  'Iva', (
					c.subtotal + c.iva
					) AS  'Total', p.rfc AS  'RFC', p.nombre AS  'Nombre', u.nombre as empleado
					FROM compras c
					NATURAL JOIN proveedor p
					join usuario u on(u.id_usuario=c.id_usuario)";
		//verificamos si se enviaron fechas
		if($fecha)
		{
				//agregamos la condicion que cuente los que esten en las fechas
				//si se agrego sucursal lo pone con and, de lo contrario pone el having
				$query.="where date(c.fecha) BETWEEN ? AND ? ";
				//agrega los parametros a la pila
				array_push($params,$de,$al);
		}//if fechas
		
		//verificamos el booleano de sucursal
		if($sucursal){
			//agregamos having para que solo cuente los de la sucursal deseada
			$query.=(($fecha)?" and ":" where ")." sucursal=? ";
			//agregamos parametro al arreglo
			array_push($params,$id_sucursal);
		}//if sucursal
		//agregamos el ; final
		$query.=";";
		//creamos objeto de la clase listar y le pasamos el arreglo de parametros
		$listar = new listar($query,$params);
		//imprimimos el resultado
		echo $listar->lista();
		return;
	}
	//reporte compras
?>
