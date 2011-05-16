<?php


    require_once('model/inventario.dao.php');
    require_once('model/actualizacion_de_precio.dao.php');
    require_once('model/detalle_inventario.dao.php');




    if(isset($_REQUEST['editar_detalles'])){
		
        //cambiar los detalles en el inventario maestro
        $prod = InventarioDAO::getByPK($_REQUEST['id']);

        $prod->setDescripcion( 			$_REQUEST['descripcion'] );
        $prod->setEscala( 				$_REQUEST['escala'] );
		$prod->setTratamiento( 			$_REQUEST['tratamiento'] == "null" ? null : $_REQUEST['tratamiento'] );

		$prod->setAgrupacion( 			$_REQUEST['agrupacion'] == "null" ? null : $_REQUEST['agrupacion']);
		$prod->setAgrupacionTam( 		$_REQUEST['agrupacion'] == "null" ? null : $_REQUEST['agrupacionTam'] );

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
        //CAMBIAR PRECIOS DEL PRODCUTO
        $na = new ActualizacionDePrecio();
        $na->setIdProducto($_REQUEST['id']);
        $na->setIdUsuario($_SESSION['userid']);
        $na->setPrecioVenta($_REQUEST['precio_venta']);

		
		
		$producto = InventarioDAO::getByPK( $_REQUEST['id'] );
		if( $_REQUEST["tipo_de_precio"] == "escala" ){
			//el precio sera por escala ! es decir, por kilo, o metro o asi
			$producto->setPrecioPorAgrupacion(0);
		}else{
			//el precio sera por agrupacion, como arpilla, o caja o asi
			$producto->setPrecioPorAgrupacion(1);
		}
		try{
			InventarioDAO::save( $producto );
		}catch(Exception $e){
			Logger::log($e);
		}


		//si hay que editar precio a compra a clientes
		if(POS_COMPRA_A_CLIENTES)
        	$na->setPrecioCompra($_REQUEST['precio_compra']);

		if(POS_MULTI_SUCURSAL){
			$na->setPrecioIntersucursal($_REQUEST['precio_interusucursal']);
		}else{
			$na->setPrecioIntersucursal(0);
		}
			
			
		if( isset( $_REQUEST['precio_venta_sin_procesar'] ) ){
	        $na->setPrecioVentaSinProcesar( $_REQUEST['precio_venta_sin_procesar'] );
		}else{
	        $na->setPrecioVentaSinProcesar( $_REQUEST['precio_venta'] );			
		}
		
		if(POS_MULTI_SUCURSAL){
			if( isset( $_REQUEST['precio_intersucursal_sin_procesar'] ) ){
		        $na->setPrecioIntersucursalSinProcesar($_REQUEST['precio_intersucursal_sin_procesar']);	
			}else{
				$na->setPrecioIntersucursalSinProcesar($_REQUEST['precio_interusucursal']);	
			}
		}else{
			$na->setPrecioIntersucursalSinProcesar(0);
		}


		
        
        //cambiar todos los detalles inventario        
        $di = new DetalleInventario();
        $di->setIdProducto( $_REQUEST['id'] );
        $inventariosSucursales = DetalleInventarioDAO::search( $di );

        foreach ($inventariosSucursales as $i)
        {
            $i->setPrecioVenta( $_REQUEST['precio_venta'] );

			if(POS_COMPRA_A_CLIENTES)
            	$i->setPrecioCompra( $_REQUEST['precio_compra'] );
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


<script>
	jQuery("#MAIN_TITLE").html("Editando <?php echo ucwords(strtolower($producto->getDescripcion()));?>");
	function agrupacionSeleccionada(val){
		if(val != "null"){
			//mostrar la caja
			if(!jQuery( "#agrupacionBox" ).is(":visible")){
				jQuery( "#agrupacionBox" ).fadeIn();	
			}

			jQuery( "#agrupacionCaption" ).html(" " + jQuery("#escala").val() + "s por " + val);

		}else{
			//ocultar la caja
			jQuery( "#agrupacionBox" ).fadeOut();
		}
		
	}
	
	function escalaSeleccionada(val){
		agrupacionSeleccionada( jQuery("#agrupacion").val() );
	}
	
	jQuery(function(){

		<?php
			if($producto->getAgrupacion()){
				echo  "escalaSeleccionada();";
			}else{
				
			}
		?>
	});
	
</script>

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
			<select name="escala" id="escala" onChange="escalaSeleccionada(this.value)">
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
	<tr><td>Agrupacion</td>
		<td>
			<select id="agrupacion" name="agrupacion" onChange="agrupacionSeleccionada(this.value)">
				<option value='null' 		<?php if($producto->getAgrupacion() == null) echo "selected"; ?>>Sin agrupacion</option>
				<option value='arpilla'		<?php if($producto->getAgrupacion() == "arpilla") echo "selected"; ?>>Arpillas</option>
				<option value='bulto'		<?php if($producto->getAgrupacion() == "bulto") echo "selected"; ?>>Bultos</option>
				<option value='costal'		<?php if($producto->getAgrupacion() == "costal") echo "selected"; ?>>Costales</option>
				<option value='caja'		<?php if($producto->getAgrupacion() == "caja") echo "selected"; ?>>Cajas</option>
	        </select>
		</td>
		<td id="agrupacionBox" style="display:none"> 
			<input type="text" id="agrupacionTam" name="agrupacionTam" size="20" value="<?php echo $producto->getAgrupacionTam(); ?>"/><span id="agrupacionCaption"></span>
		</td>
	</tr>
</table>

<h4><input type="submit" value="Guardar" size="40"/></h4>
</form>


<h2>Editar precios</h2>
<form action="inventario.php?action=editar&id=<?php echo $general->getIdProducto(); ?>" method="POST">
<input type="hidden" name="editar" value="1">

<table border="0" cellspacing="5" cellpadding="5" style="width:100%">
	
	<?php 
		if($producto->getTratamiento()) { 
		//con tratamientos
	?>
		<tr style="text-align:left"><th></th><th>Original</th><th>Procesado</th></tr>
		<tr>
			<td>Precio Sugerido</td>
			<td><input type="text" name="precio_venta" value="<?php echo $general->getPrecioVenta(); ?>" size="40"/></td>
			<td><input type="text" name="precio_venta_sin_procesar" value="<?php echo $general->getPrecioVentaSinProcesar();?>" size="40"/></td>
		</tr>
		
		<?php if(POS_MULTI_SUCURSAL){ ?>
		<tr>
			<td>Precio Intersucursal</td>	
			<td> <input type="text" name="precio_interusucursal" value="<?php echo $general->getPrecioIntersucursal();?>" size="40"/></td>
			<td> <input type="text" name="precio_intersucursal_sin_procesar" value="<?php echo $general->getPrecioIntersucursalSinProcesar();?>" size="40"/></td>		
		</tr>
		<?php }	?>
		
		<?php if(POS_COMPRA_A_CLIENTES){ ?>
		<tr>
			<td>Precio a la compra</td>	
			<td> <input type="text" name="precio_compra" value="<?php echo $general->getPrecioCompra();?>" size="40"/></td>
			<td> <input type="text" name="precio_compra_sin_procesar" value="<?php echo $general->getPrecioCompra();?>" size="40"/></td>		
		</tr>
		<?php }	?>

	<?php 
		} else { 
		//sin tratamiento
	?>
		<tr style="text-align:left"><th></th><th></th></tr>
		<tr>
			<td>Precio Sugerido</td>
			<td><input type="text" name="precio_venta" value="<?php echo $general->getPrecioVenta(); ?>" size="40"/></td>
		</tr>
		
		<?php if(POS_MULTI_SUCURSAL){ ?>
		<tr>
			<td>Precio Intersucursal</td>	
			<td> <input type="text" name="precio_interusucursal" value="<?php echo $general->getPrecioIntersucursal();?>" size="40"/></td>
		</tr>
		<?php }	?>
		
		
		<?php if(POS_COMPRA_A_CLIENTES){ ?>
		<tr>
			<td>Precio a la compra</td>	
			<td> <input type="text" name="precio_compra" value="<?php echo $general->getPrecioCompra();?>" size="40"/></td>
		</tr>
		<?php }	?>
		
	<?php } ?>
	<tr>
		<td>
			Precio
		</td>
		<td>
			<select name="tipo_de_precio">
				<option value="escala" 
					<?php 
						//si no hay agrupacion, o bien, el precio por agrupacion esta en falso
						if( ($producto->getAgrupacion() == null) || ( $producto->getPrecioPorAgrupacion() == false )) 
							echo " selected "; 
					?>>Precio por <?php echo $producto->getEscala(); ?></option>
				<?php
					if( $producto->getAgrupacion() != null ){
						?>
							<option value="agrupacion"
							<?php 
								//si no hay agrupacion, o bien, el precio por agrupacion esta en falso
								if(  $producto->getPrecioPorAgrupacion() == true ) 
									echo " selected "; 
							?>>Precio por <?php echo $producto->getAgrupacion(); ?></option>						
						<?php
					}
				?>
			</select>			
		</td>		
	</tr>
</table>
 <h4> <input type="submit" value="Guardar nuevo precio" size="40"/> </h4>
</form>	
