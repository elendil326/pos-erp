<?php
	require_once('model/proveedor.dao.php');
    require_once('model/inventario.dao.php');
    require_once('model/compra_proveedor.dao.php');
    require_once('model/inventario_maestro.dao.php');

	require_once("controller/inventario.controller.php");
    
    if( !( isset($_REQUEST['producto']) && isset($_REQUEST['compra'])) ){
    	echo "<h1>Error</h1>Estos datos no existen.";
    	return;
    }
    
    $producto 	= 	InventarioDAO::getByPK			( $_REQUEST['producto'] );
    $compra 	= 	CompraProveedorDAO::getByPK		( $_REQUEST['compra'] );
    $inventario = 	InventarioMaestroDAO::getByPK	( $_REQUEST['producto'], $_REQUEST['compra'] );

	if( $producto == null || $compra == null){
		echo "<h1>Error</h1>Existe un problema.";
		return ;
	}

?>



<script type="text/javascript" charset="utf-8">


	jQuery("#MAIN_TITLE").html("REMISION <?php echo $compra->getFolio();?> : <?php echo $producto->getDescripcion();?>");

	
	function terminarProducto()
	{
		jQuery.facebox('<h1>Dar producto por terminado</h1> &iquest; Esta seguro que desea dar por terminado este producto ?'
			+ "<br><div align='center'>"
			+ "			<input type='button' onclick=\"terminar()\" value='Si'>"
			+ "&nbsp;	<input type='button' onclick=\"jQuery(document).trigger('close.facebox')\" value='No'></div>"
		);
	}
		
	
	function terminar(){
		jQuery(document).trigger('close.facebox');
		
		//ver que voy a hacer con los restantes
		w = jQuery("#movetoselector").val();
		
		restante = null;
		
		if( w == '-1'){
			//descartalos
			restante = null;
			
		}else{

			restante = jQuery.JSON.decode(w);

		}
		

		
		jQuery("#loader").fadeIn('slow', function(){
		
			jQuery.ajax({
				url: "../proxy.php",
				data: { 
					action : 407, 
					data : jQuery.JSON.encode( {
						id_producto: 		 <?php echo $_REQUEST['producto']; ?>,
						id_compra_proveedor: <?php echo $_REQUEST['compra']; ?>,
						restante : 	restante
					})
				},
				cache: false,
				success: function(data){
					try{
				  		response = jQuery.parseJSON(data);
					}catch(e){
				
						jQuery("#loader").fadeOut('slow', function(){
							jQuery("#ajax_failure").html("Error en el servidor, porfavor intente de nuevo").show();
						});                
						return;                    
					}
		

					if(response.success === false){
						jQuery("#loader").fadeOut('slow', function(){
							jQuery("#ajax_failure").html(response.reason).show();
						});                
						return ;
					}

					reason = "El producto se ha dado por terminado";
					window.location = "inventario.php?action=maestro&success=true&reason=" + reason;
		
				}
				});
		});
		
	}
</script>

<h2>Detalles</h2>
	<table border="0" cellspacing="1" cellpadding="1" style="width: 100%">
		<tr>
			<td>Fecha de llegada</td>			<td><?php echo toDate($compra->getFecha()); ?></td>
			<td>Remision</td>						<td><?php echo $compra->getFolio();?></td>
		</tr>
			
		<tr>
			<td>Descripcion</td>				<td><?php echo $producto->getDescripcion();?></td>
			<td>Arpillas de origen</td>	<td><?php echo $compra->getArpillas(); /*mostrar arpillas de este producto, no totales*/ ?></td>
		</tr>
			
		<tr>
			<td>Proveedor</td>					<td>
			<?php 
			
				$p = ProveedorDAO::getByPK($compra->getIdProveedor());
				echo $p->getNombre();
			
			?></td>
			<td>Merma por arpilla</td>			<td><?php echo $compra->getMermaPorArpilla();?></td>
		</tr>
			
		<tr><td>Productor</td>					<td><?php echo $compra->getProductor();?></td>
			<td>Promedio por arpilla</td>		<td><?php echo $compra->getPesoPorArpilla();?></td>
			</tr>
	
		<tr><td>&nbsp;</td></tr>
	</table>

		<?php
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
				<div align=center style="font-size: 20px;" >
					<table border=0>
						<tr>
							<td>Original</td>
							<td>&nbsp;</td>
							<td>Procesado</td>
						</tr>
						<tr>
							<?php
								//originales
								$o = $inventario->getExistencias () - $inventario->getExistenciasProcesadas ();
								//procesadas
								$p = $inventario->getExistenciasProcesadas();
								//escala
								$e = $producto->getEscala();
							
							?>
							<td><?php echo "<b>". number_format( $o / $compra->getPesoPorArpilla(), 2 ) ."</b> arpillas"; ?></td>
							<td>&nbsp;</td>
							<td><?php echo "<b>". number_format( $p / 60, 2  ) ."</b> arpillas"; ?></td>
						</tr>
						<tr style="font-size: 16px;">
							<td><?php echo "<b>". number_format( $o , 2 ) ."</b> " . $e . "s"; ?></td>
							<td>&nbsp;</td>
							<td><?php echo "<b>". number_format( $p , 2 ) ."</b> " . $e . "s"; ?></td>
						</tr>					
					</table>
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

<h2>Historial de Movimientos</h2>
	<h3>Puede ver cada movimiento realizado con esta remisión.</h3>
	<h4 align="center">
		<input type="button" value="Ir al Historial" onClick="window.location = 'inventario.php?action=fragmentacion&id_compra_proveedor=<?php echo $_REQUEST['compra'] ?>'"><img src="../media/loader.gif" id="loader2" style="display: none;">
	</h4>

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


