<?php


    require_once('model/inventario.dao.php');
    require_once('model/actualizacion_de_precio.dao.php');
    require_once('model/detalle_inventario.dao.php');




    if(isset($_REQUEST['editar_detalles'])){
		
        //cambiar los detalles en el inventario maestro
        $prod = InventarioDAO::getByPK($_REQUEST['id']);
        $prod->setDescripcion( $_REQUEST['descripcion'] );
        $prod->setEscala( $_REQUEST['escala'] );
		$prod->setTratamiento( $_REQUEST['tratamiento'] );
        try{

            InventarioDAO::save($prod);

            echo "<div class='success'>Descripcion de producto actualizada correctamente.</div>";
			Logger::log("Editando producto " . $_REQUEST['id']. ": OK" ); 
        }catch(Exception $e){
            DAO::transRollback();
            echo "<div class='failure'>Error al actualizar</div>";
        }

    }


    if(isset($_REQUEST['editar'])){
        //cambiar el precio y costo del producto
        $na = new ActualizacionDePrecio();
        $na->setIdProducto($_REQUEST['id']);
        $na->setIdUsuario($_SESSION['userid']);
        $na->setPrecioVenta($_REQUEST['precio_venta']);
        $na->setPrecioVentaSinProcesar($_REQUEST['precio_venta_sin_procesar']);
        $na->setPrecioIntersucursal($_REQUEST['precio_interusucursal']);
        $na->setPrecioIntersucursalSinProcesar($_REQUEST['precio_intersucursal_sin_procesar']);
		
        
        //cambiar todos los detalles inventario        
        $di = new DetalleInventario();
        $di->setIdProducto( $_REQUEST['id'] );
        $inventariosSucursales = DetalleInventarioDAO::search( $di );

        foreach ($inventariosSucursales as $i)
        {
            $i->setPrecioVenta( $_REQUEST['precio_venta'] );
        }


        try{
            DAO::transBegin();

            ActualizacionDePrecioDAO::save( $na );

            foreach ($inventariosSucursales as $inv)
            {
                DetalleInventarioDAO::save($inv);
            }

            DAO::transEnd();
            echo "<div class='success'>Precio actualizado correctamente.</div>";
        }catch(Exception $e){
			Logger::log($e);
            DAO::transRollback();
            echo "<div class='failure'>Error al actualizar</div>";
        }

    }


    $producto = InventarioDAO::getByPK($_REQUEST['id']);

    //obtener la ultima actualizacion de precio para este producto
    //esos son sus detalles "generales"
    $a = new ActualizacionDePrecio();
    $a->setIdProducto($producto->getIdProducto());
    $actualizaciones = ActualizacionDePrecioDAO::search($a, 'fecha', 'desc');

    if(sizeof($actualizaciones) <= 0){
        Logger::log("No hay registro de actualizacion de precio para producto :" . $producto->getIdProducto());
    }
    
    $general = $actualizaciones[0];



?>




<h2>Editar descripcion</h2>
<form action="inventario.php?action=editar&id=<?php echo $general->getIdProducto(); ?>" method="POST">
<input type="hidden" name="editar_detalles" value="1">
<table border="0" cellspacing="5" cellpadding="5">
	<tr><td>Descripcion</td><td><input type="text" value="<?php echo $producto->getDescripcion();?>" size="40" name="descripcion"/></td></tr>
	<tr><td>Escala</td>
		<td>
			<?php
				$escala = $producto->getEscala();
				$e0 = $e1 = $e2 = $e3 = "";
				
				switch($escala){
					case "kilogramo" : $e0 = "selected"; break;
					case "pieza" : $e1 = "selected"; break;
					case "litro" : $e2 = "selected"; break;
					case "unidad" : $e3 = "selected"; break;					
				}
			?>
			<select name="escala">
				<option value='kilogramo' 	<?php echo $e0; ?>>Kilogramo(s)</option>
				<option value='pieza' 		<?php echo $e1; ?>>Pieza(s)</option>
				<option value='litro' 		<?php echo $e2; ?>>Litro(s)</option>
				<option value='unidad' 		<?php echo $e3; ?>>Unidad(es)</option>
	        </select>
		</td>
	</tr>
	<tr><td>Proceso</td>
		<td>
			<?php
				$proc = $producto->getTratamiento();
				$p0 = $p1 = "";
				
				switch( $proc ){
					case "": $p0 = "selected" ; break;
					case "limpia": $p1 = "selected" ; break;
				}
			?>
			
			<select name="tratamiento">
				<option value='null' 		<?php echo $p0; ?>>Sin tratamientos</option>
				<option value='limpia'		<?php echo $p1; ?>>Limpia/Original</option>
	        </select>
		</td>
	</tr>	

</table>

<h4><input type="submit" value="Guardar" size="40"/></h4>
</form>


<h2>Editar Precios</h2>
<form action="inventario.php?action=editar&id=<?php echo $general->getIdProducto(); ?>" method="POST">
<input type="hidden" name="editar" value="1">
<table border="0" cellspacing="5" cellpadding="5">
	
	<tr><td>Precio sugerido a la venta ( procesado )</td>
		<td><input type="text" name="precio_venta" value="<?php echo $general->getPrecioVenta(); ?>" size="40"/></td></tr>
		
	<tr><td>Precio sugerido a la venta ( sin procesar ) </td>
		<td>  <input type="text" name="precio_venta_sin_procesar" value="<?php echo $general->getPrecioVentaSinProcesar();?>" size="40"/></td></tr>

		<tr><td>Precio intersucursal ( procesado ) </td>
			<td>  <input type="text" name="precio_interusucursal" value="<?php echo $general->getPrecioIntersucursal();?>" size="40"/></td></tr>
		
	<tr><td>Precio intersucursal ( sin procesar ) </td>
		<td>  <input type="text" name="precio_intersucursal_sin_procesar" value="<?php echo $general->getPrecioIntersucursalSinProcesar();?>" size="40"/></td></tr>
	
</table>
 <h4> <input type="submit" value="Guardar nuevo precio" size="40"/> </h4>
</form>	





