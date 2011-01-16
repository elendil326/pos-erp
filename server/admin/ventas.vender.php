<?php

	require_once("controller/inventario.controller.php");
	require_once("controller/sucursales.controller.php");
	require_once('controller/clientes.controller.php');
	require_once('model/cliente.dao.php');

?>

<script>
	jQuery("#MAIN_TITLE").html("Venta a cliente");
</script>

<h2>Detalles del Cliente</h2>


<?php
	if(!isset($_REQUEST['cid'])){
	    $clientes = listarClientes();
    
		if(sizeof($clientes ) > 0){
			echo '<select id="sucursal"> ';    
			foreach( $clientes as $c ){
				echo "<option value='" . $c['id_cliente'] . "' >" . $c['nombre']  . "</option>";
			}
			echo '</select>';    
		}else{
		
			echo "<h3>No hay clientes a quien realizarle la venta</h3>";
		}
	}else{
	
		$cliente = ClienteDAO::getByPK( $_REQUEST['cid'] );
		
		if($cliente === null){
			echo "<h3>Este cliente no existe</h3>";
		}else{
		
		?>
			<table border="0" cellspacing="1" cellpadding="1">
				<tr><td><b>Nombre</b></td><td><?php echo $cliente->getNombre(); ?></td><td rowspan=12><div id="map_canvas"></div></td></tr>
				<tr><td><b>RFC</b></td><td><?php echo $cliente->getRFC(); ?></td></tr>
				<tr><td><b>Limite de Credito</b></td><td><?php echo moneyFormat($cliente->getLimiteCredito()); ?></td></tr>	
				<tr><td><b>Descuento</b></td><td><?php echo percentFormat( $cliente->getDescuento() ); ?></td></tr>
			</table>
		
		<?php
		
		}
	
	}


?>
<style>
	.tabla-inventario{
		font-size: 12px;
	}
</style>

<script>
	var carrito = [];
	
	function remove(data){
		jQuery("#" + data).css("color", "");
		jQuery("#" + data).css("background-color", "");	
		renderCarrito();

	}
	
	function add(data){
	
		//buscar en el carrito
		
		for( a = 0; a < carrito.length; a++){
			if( carrito[a].tablaid == data ){
			
				//ya esta, hay que quitarlo
				carrito.splice( a, 1 );
				return remove(data);
			}
		}
		
		jQuery("#" + data).css("color", "#fff ");
		jQuery("#" + data).css("background-color", "#3F8CE9 !important");
		
		carrito.push( {
			qty : 1,
			tablaid : data
		});
		
		renderCarrito();
	}
	
	function doMath(){
	
		for(a = 0; a < carrito.length; a++){
			foo = jQuery("#" + carrito[a].tablaid).children();
			
			
		}
	}
	
	function renderCarrito (){
		html = "<table style='width:100%'>";
		html += "<tr align=left ><th>Remision</th><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Importe</th></tr>";
		//<tr id='prodsTableFooter'><td colspan=2 align="right"><b>Totales</b></td><td><div id='totales-arpillas'></div></td><td></td><td><div id='totales-peso'></div></td><td></td><td><div id='totales-importe'></div></td></tr>    	
		for(a = 0; a < carrito.length; a++){
		
			foo = jQuery("#" + carrito[a].tablaid).children();
			
			
			
			html += "<tr><td>" + 	foo[0].innerHTML +"</td>";
			html += "<td>" + 		foo[1].innerHTML +"</td>";
			html += "<td><input type='text' value='"+ carrito[a].qty +"'></td>";
			html += "<td><input type='text'>"  +"</td>";			
			
			html += "</tr>";
			
		}
		
		html += "</table>";
		
		doMath();
		
		jQuery("#cart").html(html);
		jQuery("#cart input:text").uniform();
	}
</script>




<h2>Inventario maestro</h2>
<div class="tabla-inventario">
<?php

function toUnit( $e )
{
	return "<b>" . $e . "</b>kg";
}

function toDateS( $d ){
	$foo = toDate($d);
	$bar = explode(" ", $foo);
	return $bar[0];
	 
}

$iMaestro = listarInventarioMaestro() ;

echo "<script>";
echo " var inventario_maestro = [";
foreach($iMaestro as $i){
	echo json_encode($i) . ",";
}
echo "];";
echo "</script>";

$header = array(
	"folio" 			=> "Remision",
	"producto_desc" 	=> "Producto",
	"variedad" 	 		=> "Variedad",
	"arpillas"			=> "Arpillas origen",
	"peso_por_arpilla"	=> "Kg/Arpilla",
	"productor"			=> "Productor",
	"fecha"				=> "Llegada",
	//"transporte"				=> "Transporte",
	"merma_por_arpilla"			=> "Merma",
	//"sitio_descarga_desc"		=> "Sitio de descarga",
	"existencias"				=> "Existencias",
	"existencias_procesadas"	=> "Limpias" );
	
$tabla = new Tabla( $header, $iMaestro );
$tabla->renderRowId("carrito"); //darle id's a las columnas
$tabla->addOnClick("folio", "add", false, true); //enviar el id de la columna al javascriptooor
$tabla->addColRender( "existencias", "toUnit" );
$tabla->addColRender( "existencias_procesadas", "toUnit" );
$tabla->addColRender( "fecha", "toDateS" );
$tabla->render();

?>
</div>




	<!-- -------------------------------
			TABALA DE PRODUCTOS SELECCIONADOS
	  ------------------------------- -->    
<h2>Productos a vender</h2>
    <div id='cart'>

    </div>
    
