<?php
	require_once('model/proveedor.dao.php');
    require_once('model/inventario.dao.php');
    require_once('model/compra_proveedor.dao.php');
    require_once('model/inventario_maestro.dao.php');
    
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
		jQuery("#loader").fadeIn('slow', function(){
			jQuery.ajax({
				url: "../proxy.php",
				data: { 
					action : 407, 
					data : jQuery.JSON.encode( {
						id_producto: 		<?php echo $_REQUEST['producto']; ?>,
						id_compra:			<?php echo $_REQUEST['compra']; ?>					
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
		<tr><td>Fecha de llegada</td>			<td><?php echo toDate($compra->getFecha()); ?></td></tr>
		<tr><td>Descripcion</td>				<td><?php echo $producto->getDescripcion();?></td></tr>
		<tr><td>Proveedor</td>					<td>
			<?php 
			
				$p = ProveedorDAO::getByPK($compra->getIdProveedor());
				echo $p->getNombre();
			
			?></td></tr>
		<tr><td>Productor</td>					<td><?php echo $compra->getProductor();?></td></tr>
		<tr><td>Folio</td>						<td><?php echo $compra->getFolio();?></td></tr>
		<tr><td>Arpillas de este producto</td>	<td><?php echo $compra->getArpillas();?></td></tr>
		<tr><td>Merma por arpilla</td>			<td><?php echo $compra->getMermaPorArpilla();?></td></tr>
		<tr><td>Promedio por arpilla</td>		<td><?php echo $compra->getPesoPorArpilla();?></td></tr>
		<tr><td>&nbsp;</td></tr>
	</table>

		<?php
	
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



		?>		

<?php




?>
<h2>Dar por terminado</h2>
		
	<h4 align="center">
		<input type='button' value="Dar por terinado" onClick="terminarProducto()">
	</h4>

<?php
if($producto->getTratamiento()){
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
			El proceso ha resultado en otro producto:<br/>
			
			
			Cantidad:<input type="text" style="width: 50px" id="subprodqty">
				

			Mover a:<select id="subprodselector">
				<?php
				$inventario = InventarioMaestroDAO::getAll();
				
			    foreach( $inventario as $i ){
				    // tengo la compra
				    // tengo el inventario maestro
				    // tengo el inventario
			    	$compra = CompraProveedorDAO::getByPK	( $i->getIdCompraProveedor() );
			    	$producto = InventarioDAO::getByPK		( $i->getIdProducto() );
			    	
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
	
		if( isNaN(jQuery("#procesada").val()) ){
			jQuery("#procesada").addClass("wrong");
			jQuery("#procesada").removeClass("ok");
		}else{
			jQuery("#procesada").addClass("ok");
			jQuery("#procesada").removeClass("wrong");		
		}

		if( isNaN(jQuery("#desecho").val()) ){
			jQuery("#procesada").addClass("wrong");
			jQuery("#procesada").removeClass("ok");
		}else{
			jQuery("#procesada").addClass("ok");
			jQuery("#procesada").removeClass("wrong");		
		}
	
		t = 0;
		for(a= 0; a < subprods.length; a++){
			t += parseFloat( subprods[a].cantidad_procesada );
		}
		
		t += parseFloat( jQuery("#procesada").val() );
		t += parseFloat( jQuery("#desecho").val() );
		
		jQuery("#total").val(parseFloat(t));
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
						});                
						return;                    
					}
		

					if(response.success === false){
						jQuery("#loader2").fadeOut('slow', function(){
							jQuery("#ajax_failure").html(response.reason).show();
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


