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

	
	function showProceso(){
		jQuery("#reportar_limpieza_boton").fadeOut("slow", function(){
			jQuery("#reportar_limpieza").fadeIn();
		});
	}
	
	function hideProceso(){
		jQuery("#reportar_limpieza").fadeOut("slow", function(){
			jQuery("#reportar_limpieza_boton").fadeIn();
		});	
	}
	

	
	function terminarProducto()
	{
		jQuery.facebox('<h1>Dar producto por terminado</h1> &iquest; Esta seguro que desea dar por terminado este producto ?'
			+ "<br><div align='center'>"
			+ "			<input type='button' onclick=\"terminar()\" value='Si'>"
			+ "&nbsp;	<input type='button' onclick=\"jQuery(document).trigger('close.facebox')\" value='No'></div>"
		);
	}
	
	function sendProceso(){
		
		jQuery("#loader").fadeIn('slow', function(){
			jQuery.ajax({
				url: "../proxy.php",
				data: { 
					action : 406,
					data : jQuery.JSON.encode( {
						id_producto: 		<?php echo $_REQUEST['producto']; ?>,
						id_compra:			<?php echo $_REQUEST['compra']; ?>,
						cantidad_procesada: jQuery("#cantidad_limpiada").val()					
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
					
					reason = "Se han actualizado las existencias";
					window.location = "inventario.php?action=detalleCompra&compra=<?php echo $_REQUEST['compra']; ?>&producto=<?php echo $_REQUEST['producto']; ?>&success=true&reason=" + reason;
		
				}
				});
		});
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


<table style="width: 100%">
	<tr><td>
	<h2>Compra</h2>
	<table border="0" cellspacing="1" cellpadding="1">
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
		
		<tr style="font-size: 20px;">
		<td colspan=2 > <?php printf("<b>%6.2f</b> %ss", $inventario->getExistencias (), $producto->getEscala() ); ?> sin procesar</td></tr>
		
		<tr style="font-size: 20px;">
		<td colspan=2 > <?php printf("<b>%6.2f</b> %ss", $inventario->getExistenciasProcesadas (), $producto->getEscala() ); ?> procesados</td></tr>
		
	</table>
	</td>
	<td valign='top' style="width: 300px">
	<h2>&nbsp;<img id="loader" style="display: none;" src="../media/loader.gif"></h2>
		<input type='button' value="Dar por terinado" onClick="terminarProducto()"><br>
		
		<div id="reportar_limpieza_boton">
			<input type='button' value="Reportar proceso"  onClick="showProceso()">
		</div>
		<div id="reportar_limpieza" style="display: none;">
			Cantidad limpiada <input type="text" id="cantidad_limpiada"> <br>
			<input type="button" value="Cancelar" onClick="hideProceso()"> <input type="button" value="Aceptar" onClick="sendProceso()">
		</div>
		
	</td></tr>
</table>





