<?php
	require_once('model/proveedor.dao.php');
    require_once('model/inventario.dao.php');
    require_once('model/compra_sucursal.dao.php');
    require_once('model/detalle_compra_sucursal.dao.php');
    require_once('model/inventario_maestro.dao.php');

	require_once("controller/inventario.controller.php");

    $compra 	= 	CompraSucursalDAO::getByPK		( $_REQUEST['cid'] );
?>



<script type="text/javascript" charset="utf-8">


	
</script>

<h2>Detalles de la compra de esta sucursal</h2>
	<table border="0" cellspacing="5" cellpadding="5" style="width: 100%">
		<tr><td>Fecha de compra</td>			<td><?php echo toDate($compra->getFecha()); ?></td></tr>
		<tr><td>Proveedor</td>					<td>Centro de distribucion</td></tr>

		<tr><td>Sucursal</td>	<td><?php echo $compra->getIdSucursal();?></td></tr>
		<tr><td>Total</td>	<td><?php echo moneyFormat( $compra->getTotal() ); ?></td></tr>
		<tr><td>Pagado</td>	<td><?php echo moneyFormat( $compra->getPagado() );?></td></tr>
		<tr><td>Saldo</td>	<td> - <b><?php echo moneyFormat($compra->getTotal()  - $compra->getPagado()); ?></b></td></tr>

	</table>



<h2>Detalles de esta compra </h2>
<?php



function toUnit( $e )
{
	return "<b>" . number_format($e, 2) . "</b>kg";
}

	
	$query = new DetalleCompraSucursal();
	$query->setIdCompra( $_REQUEST["cid"] );

	$detalles = DetalleCompraSucursalDAO::search( $query );
	
	function renderProd($pid){
		
		$foo = InventarioDAO::getByPK( $pid );
		return $foo->getDescripcion();
	}

	$header = array(
		"id_producto" => "Producto",
		"cantidad" => "Cantidad",
		"precio" => "Precio",
		"descuento" => "Descuento",
		"procesadas" => "procesada" );

	$tabla = new Tabla($header, $detalles);
	$tabla->addColRender("precio", "moneyFormat");
	$tabla->addColRender("cantidad", "toUnit");
	$tabla->addColRender("descuento", "toUnit");	
	$tabla->addColRender("procesada", "toUnit");	
	$tabla->render();
?>






		<?php
		
		return;
		
		if($inventario->getExistencias () == 0){
			?>
				<div style="font-size: 20px;" align="center">
					<h1>Embarque agotado</h2>
				</div>
			<?php
	
		}else{
			if($producto->getTratamiento()){
		
				/* PRODUCTO CON TRATAMIENTO */
				?>
				<div style="font-size: 20px;" align="center">
					<?php printf("<b>%6.2f</b> %ss", ( $inventario->getExistencias () - $inventario->getExistenciasProcesadas ()), $producto->getEscala() ); ?> sin procesar
				</div>
		
				<div style="font-size: 20px;" align="center">
					<?php printf("<b>%6.2f</b> %ss", $inventario->getExistenciasProcesadas (), $producto->getEscala() ); ?> procesados
				</div>
			
				<?php
			}else{
		
				/* PRODUCTO SIN TRATAMIENTO */
				?>
				<div style="font-size: 20px;" align="center">
					<?php printf("<b>%6.2f</b> %ss", ( $inventario->getExistencias () ), $producto->getEscala() ); ?> en existencia.
				</div>
				<?php
			}
		}


		?>	

<?php



if($inventario->getExistencias() != 0){
	?>
	<h2>Dar por terminado</h2>
					Mover a <select id="movetoselector">
					
							<option value="-1">
								Descartar restante
							</option>
					<?php
					$foo = new InventarioMaestro();
					$foo->setIdProducto( $producto->getIdProducto() );
					$moveto = InventarioMaestroDAO::search( $foo );
				
					foreach( $moveto as $i ){
						// tengo la compra
						// tengo el inventario maestro
						// tengo el inventario
						$compra = CompraProveedorDAO::getByPK	( $i->getIdCompraProveedor() );
						$producto = InventarioDAO::getByPK		( $i->getIdProducto() );
						
						?>
						<option value='{ "id_compra_proveedor" : <?php echo $compra->getIdCompraProveedor(); ?>, "id_producto" : <?php echo $producto->getIdProducto(); ?> }'><?php echo $producto->getDescripcion(); ?> / <?php echo $compra->getFolio(); ?></option>
						<?php
					}	
					?>				
				</select>
		<h4 align="center">
			<input type='button' value="Dar por terinado" onClick="terminarProducto()"><img src="../media/loader.gif" id="loader" style="display: none;">
		</h4>

	<?php
}





if($inventario->getExistencias() != 0 && $producto->getTratamiento()){
	/* PRODUCTO CON TRATAMIENTO */
	?>		
	
	<h2>Procesar</h2>
	<h3>Este producto puede ser procesado como <i>Limpio/Orginial</i></h3>
	
	
	<table width="100%">

		<tr>
			<td>Resultante procesada</td>
			<td><input class="wrong" type="text" style="width:75px" id="procesada" onKeyUp="doMath()" >&nbsp;<?php echo $producto->getEscala(); ?>s</td>
		</tr>
		
		
		<tr>
			<td>Desecho resultante</td>
			<td><input class="wrong" type="text" style="width:75px" id="desecho" onKeyUp="doMath()">&nbsp;<?php echo $producto->getEscala(); ?>s</td>
		</tr>		
		
		<tr id="totals-a-procesar">
			<td>Cantidad total tomada a procesar </td>
			<td><input type="text" style="width:75px" disabled id="total">&nbsp;<?php echo $producto->getEscala(); ?>s</td>
		</tr>

		
		<tr>
			<td colspan=2 align=left style="padding-top: 15px ">
			*El proceso ha resultado en otro producto:<br/>
			
			
			Cantidad:<input type="text" style="width: 50px" id="subprodqty">
				

			Mover a:<select id="subprodselector">
				<?php
				//$inventario = InventarioMaestroDAO::getAll();
				$inventario = listarInventarioMaestro(200, POS_SOLO_ACTIVOS);
				
			    foreach( $inventario as $i ){
				    // tengo la compra
				    // tengo el inventario maestro
				    // tengo el inventario
			    	$compra = CompraProveedorDAO::getByPK	( $i['id_compra_proveedor'] );//->getIdCompraProveedor() );
			    	$producto = InventarioDAO::getByPK		( $i['id_producto'] );//->getIdProducto() );
			    	
				    echo "<option value='{ \"id_compra_proveedor\" : " . $compra->getIdCompraProveedor() . ", ";
					echo "\"descripcion\" : \"". $producto->getDescripcion() ."\", ";
					echo "\"folio\" : \"". $compra->getFolio() ."\", ";
					echo "\"id_producto\" : ". $producto->getIdProducto() ;

				    echo " }' >" ;
				    echo  $producto->getDescripcion() ." / ". $compra->getFolio() . "</option>";
			    }	
			    ?>				
			</select>
			
			<input type="button" value="Agregar" onclick="addSubProd()">
			
			</td>
		</tr>
		
	</table>
	
	<h4 align="center">
		<input type="button" value="Aceptar proceso" onClick="ask()"><img src="../media/loader.gif" id="loader2" style="display: none;">
	</h4>
	
<!--	<div id="reportar_limpieza" style="display: none;">
	
		</div>
-->
	
	
	
	<?php
}	
?>

<script>
	var subprods = [];
	

	
	function addSubProd(){
	
		if(isNaN(jQuery("#subprodqty").val()) || jQuery("#subprodqty").val().length == 0){
			jQuery("#subprodqty").addClass("wrong");
			return;
		}else{
			jQuery("#subprodqty").removeClass("wrong");		
		}

		
	
		prod = jQuery.JSON.decode( jQuery("#subprodselector").val() );
		qty = jQuery("#subprodqty").val();
		code = "<tr><td><b>*</b>"+prod.descripcion+" <b>/</b> " +prod.folio+"</td><td><input type='text' value='"+qty+"' disabled></td></tr>";
		
		jQuery("#totals-a-procesar").before(code);
		jQuery("input:text").uniform();
		
		subprods.push({
			id_producto : prod.id_producto,
			id_compra_proveedor : prod.id_compra_proveedor,
			cantidad_procesada: qty
		});
		
		jQuery("#subprodqty").val("");
		
		doMath();
	}
	
	function doMath(){
		jQuery("#procesada").val(jQuery("#procesada").val().replace(/^\s*|\s*$/g,''));
		jQuery("#desecho").val(jQuery("#desecho").val().replace(/^\s*|\s*$/g,''));
	
		if(jQuery("#procesada").val().length == 0 || isNaN(jQuery("#procesada").val()) ){
			jQuery("#procesada").addClass("wrong");
			jQuery("#procesada").removeClass("ok");
		}else{
			jQuery("#procesada").addClass("ok");
			jQuery("#procesada").removeClass("wrong");		
		}

		if( jQuery("#desecho").val().length == 0 || isNaN(jQuery("#desecho").val()) ){
			jQuery("#desecho").addClass("wrong");
			jQuery("#desecho").removeClass("ok");
		}else{
			jQuery("#desecho").addClass("ok");
			jQuery("#desecho").removeClass("wrong");		
		}
	
		t = 0;
		for(a= 0; a < subprods.length; a++){
			t += parseFloat( subprods[a].cantidad_procesada );
		}
		
		t += parseFloat( jQuery("#procesada").val() );
		t += parseFloat( jQuery("#desecho").val() );
		
		if(!isNaN(t)){
			jQuery("#total").val(parseFloat(t));
		}else{
			jQuery("#total").val("");
		}
		
	}



	function ask()
	{
		jQuery.facebox('<h1>Procesar producto</h1> &iquest; Esta seguro que desea procesar el producto ?'
			+ "<br><div align='center'>"
			+ "			<input type='button' onclick=\"doSendProc()\" value='Si'>"
			+ "&nbsp;	<input type='button' onclick=\"jQuery(document).trigger('close.facebox')\" value='No'></div>"
		);
	}
	
	

	
	function doSendProc(){
		jQuery(document).trigger('close.facebox');
		
		endobj = {
			id_compra_proveedor : <?php echo $_REQUEST['compra']; ?>,
			id_producto: <?php echo $_REQUEST['producto']; ?> ,
			resultante: parseFloat( jQuery("#procesada").val() ),
			desecho: parseFloat ( jQuery("#desecho").val() ),
			subproducto : subprods
		};
		
		jQuery("#loader2").fadeIn('slow', function(){
			jQuery.ajax({
				url: "../proxy.php",
				data: { 
					action : 406,
					data : jQuery.JSON.encode( endobj )

				},
				cache: false,
				success: function(data){
					try{
				  		response = jQuery.parseJSON(data);
					}catch(e){
				
						jQuery("#loader2").fadeOut('slow', function(){
							jQuery("#ajax_failure").html("Error en el servidor, porfavor intente de nuevo").show();
							window.scroll(0,0);
						});                
						return;                    
					}
		

					if(response.success === false){
						jQuery("#loader2").fadeOut('slow', function(){
							jQuery("#ajax_failure").html(response.reason).show();
							window.scroll(0,0);							
						});                
						return ;
					}
					
					reason = "Se han actualizado las existencias correctamente";
					window.location = "inventario.php?action=detalleCompra&compra=<?php echo $_REQUEST['compra']; ?>&producto=<?php echo $_REQUEST['producto']; ?>&success=true&reason=" + reason;
		
				}
				});
		});
	}
</script>


