<?php

define("BYPASS_INSTANCE_CHECK", false);

require_once("../../../../server/bootstrap.php");

$page = new GerenciaComponentPage();


//
// Parametros necesarios
// 
$page->requireParam("oid", "GET", "Esta orden de servicio no existe.");

$esta_orden = OrdenDeServicioDAO::getByPK($_GET["oid"]);

if(!$esta_orden->getActiva()){
	$page->addComponent(new TitleComponent("Esta orden ya ha sido terminada."));
	$page->render();
	exit;
}


$customer         = UsuarioDAO::getByPK($esta_orden->getIdUsuarioVenta());
$link_to_customer = "<a href='clientes.ver.php?cid=" . $esta_orden->getIdUsuarioVenta() . "'>" . $customer->getNombre() . "</a>";


$page->addComponent(new TitleComponent("Terminar orden de servicio " . $_GET["oid"] . " para " . $link_to_customer, 2));

//desactivarla
$esta_orden->setActiva(0);

$form = new DAOFormComponent($esta_orden);

$form->addField("id_orden", "id_orden", "text", $_GET["oid"]);

$form->setEditable(true);

$form->hideField(array(
	"id_usuario_venta",
	"extra_params",
	"motivo_cancelacion",
	"id_orden_de_servicio",
	"fecha_entrega",
	"cancelada",
	"adelanto",
	"activa",
	"id_usuario",
	"descripcion",
	"fecha_orden",
	"id_servicio",
	"id_usuario_asignado"
));

$form->setCaption("precio", "Precio final");

$form->sendHidden( array("id_orden", "activa") );

$form->addApiCall( "api/servicios/orden/terminar", "POST" );

//$form->onApiCallSuccessRedirect("servicios.detalle.orden.php?oid=" . $_GET["oid"]);

//$page->addComponent($form);


$page->partialRender();
	?>
		<div style="border:1px solid #99BBE8;	;box-sizing: border-box;
		-moz-box-sizing: border-box;
		-ms-box-sizing: border-box;
		-webkit-box-sizing: border-box; 
		margin-bottom: 20px" id="ext-gen1079">


		<table style="margin-bottom: 0px;margin-left: 0px;" border=1>
			<tbody>
			<tr colspan=2>
				<td	rowspan=2 style="width:300px">
				<?php
					$este_servicio = ServicioDAO::getByPK( $esta_orden->getIdServicio() );
					echo "<h3> " . $este_servicio->getNombreServicio(  ) . "</h3>";	
					echo "<strong>Precio final</strong><div id='editarPrecioT1'> " . FormatMoney( $esta_orden->getPrecio() ) . "</div><br>";
					
					$duracion = time() - $esta_orden->getFechaOrden();
					$duracion /= 60;
					$duracion = (int)$duracion;
					$tiempos = "minutos";
					
					if($duracion > 60){
						$duracion /= 60;
						$duracion = (int)$duracion;
						$tiempos = "horas";
					}
					
					echo "<strong>Duracion de la orden</strong><div>" . $duracion . " ". $tiempos ." </div>";
				?>
				</td>
				<td >
				
					<!-- ------------------------------
					GLOBALS
					------------------------------ -->
					<script>
					function hideAll(){
						Ext.get("ventaNuevaB1").hide(); 
						Ext.get("ventaExistenteB1").hide(); 
						Ext.get("editarPrecioB1").hide(); 
					}


					function showAll(){
						Ext.get("ventaNuevaB1").show(); 
						Ext.get("ventaExistenteB1").show(); 
						Ext.get("editarPrecioB1").show();						
					}
					</script>					
					
					
					
					
				
					<!-- ------------------------------
				
					------------------------------ -->
					<script>
						function nuevaVenta(){
							hideAll();
							
							POS.API.POST("api/servicios/orden/terminar", {
								id_orden : <?php echo $esta_orden->getIdOrdenDeServicio(); ?>
							},{
								callback : function(o){
									window.location = "ventas.detalle.php?vid=" + o.id_venta;
								}
							});
						}
					</script>
					<div class="POS Boton" style="width:100%" id="ventaNuevaB1" onClick="nuevaVenta()">Agregar a nueva nota de venta</div>
					
					
					
					
					
					
					
					
					<!-- ------------------------------
						A VENTA EXISTENTE
					------------------------------ -->
					<script>
						function ventaExistente(){
							 hideAll();
							Ext.get("ventaExistenteD1").show(); 							
							Ext.get("ventaExistenteD1").fadeIn({opacity:1 });
						}
						function ventaExistenteCancelar(){
							Ext.get("ventaExistenteD1").hide(); 
							showAll();							
						}
						
						function ventaExistenteAceptar(){
							//validar
							var newValue = Ext.get("ventaExistenteTextInput").getValue();
							
							//enviar API CALL
							Ext.get("editarPrecioD1").fadeOut({opacity: .25});
							POS.API.POST("api/servicios/orden/terminar", {
								id_orden : <?php echo $esta_orden->getIdOrdenDeServicio(); ?>,
								id_venta : newValue
							},{
								callback : function(){
									//ok
									window.location = "ventas.detalle.php?vid=" + newValue;
								}
							});
						}
					</script>					
					<div class="POS Boton" style="width:100%" id="ventaExistenteB1" onClick="ventaExistente()">Agregar a nota de venta existente</div>
					<div id="ventaExistenteD1" style="display:none">
						<input 
							type="text" 
							id="ventaExistenteTextInput" 
							style="height: 25px; margin-right: 17px; font-size: 12px; " 
							placeholder="Folio de la venta">
							
						<div 
							class="POS Boton" 
							style="width:70px" 
							onClick="ventaExistenteCancelar()">Cancelar</div>
							
						<div 
							class="POS Boton OK" 
							onClick="ventaExistenteAceptar()"
							style="width:70px">Aceptar</div>
					</div>
					<script>
					Ext.get('ventaExistenteD1').setVisibilityMode(Ext.Element.DISPLAY);
					Ext.get('ventaExistenteB1').setVisibilityMode(Ext.Element.DISPLAY);
					</script>
					
					
					
					
					
					
					
					<!-- -----------------------------    -
						EDITAR PRECIO
					------------------------------ -->
					<script>
						function editarPrecio(){
 							hideAll();
							Ext.get("editarPrecioD1").show(); 							
							Ext.get("editarPrecioD1").fadeIn({opacity:1 });


						}
						
						function editarPrecioCancelar(){
							showAll();
							Ext.get("editarPrecioD1").hide();
						}
						
						function editarePrecioAjax(){
							//validar
							var newValue = Ext.get("editarPrecioTextInput").getValue();
							
							//enviar API CALL
							Ext.get("editarPrecioD1").fadeOut({opacity: .25});
							POS.API.POST("api/servicios/orden/editar", {
								precio : newValue,
								id_orden : <?php echo $esta_orden->getIdOrdenDeServicio(); ?>
							},{
								callback : function(){
									showAll();
									Ext.get("editarPrecioD1").hide();
									Ext.get("editarPrecioT1").update( FormatMoney(newValue) );
									Ext.get("editarPrecioT1").highlight()
								}
							});
						}
					</script>
					
					<div id="editarPrecioB1" class="POS Boton" style="width:100%" onClick="editarPrecio()">Editar el precio </div>
					
					<div id="editarPrecioD1" style="display:none">
						<input 
							type="text" 
							id="editarPrecioTextInput" 
							style="height: 25px; margin-right: 17px; font-size: 12px; " 
							placeholder="<?php echo $esta_orden->getPrecio(); ?>">
							
						<div 
							class="POS Boton" 
							style="width:70px" 
							onClick="editarPrecioCancelar()">Cancelar</div>
							
						<div 
							class="POS Boton OK" 
							onClick="editarePrecioAjax()"
							style="width:70px">Editar</div>
					</div>
					<script>
					Ext.get('editarPrecioB1').setVisibilityMode(Ext.Element.DISPLAY);
					Ext.get('editarPrecioD1').setVisibilityMode(Ext.Element.DISPLAY);
					</script>
					
				</td>
			</tr>
	
		</tbody></table>
	</div>
		
				
	<?php


$page->render();

