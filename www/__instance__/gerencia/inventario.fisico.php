<?php



	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../server/bootstrap.php");

	$page = new GerenciaTabPage();
	$page->partialRender();



	?>
	
	<table>
		<tr>
			<td><div id="fecha_inicio"></div></td>
			<td><div id="fecha_fin"></div></td>
			<td><div class="POS Boton">Actualizar</div></td>
		</tr>
	</table>
	
	
	
	
	
	<script>
	store_component.addExtComponent(
	 Ext.create('Ext.form.field.Date',{
		name: 'fecha_inicio',
		id: 'fecha_inicio',
		value: new Date()                                   
	 }), 'fecha_inicio');

	store_component.addExtComponent( 
	 Ext.create('Ext.form.field.Date',{
		name: 'fecha_fin',
		id: 'fecha_fin',
		value: new Date()                                   
	 }), 'fecha_fin');
	</script>

	<?php
	
	//Ext.getCmp("fecha_fin").getValue();
	//Ext.getCmp("fecha_inicio").getValue();
    


function e($t){
	echo "&nbsp;<span style='color: red'>" . $t . "</span>";
}

function w($t){
	echo "&nbsp;<span style='color: blue'>" . $t . "</span>";
}








//listemos las compras
if(isset($_GET ["inicio"]) && isset($_GET ["fin"])){
	$compras = CompraDAO::byRange(
							new Compra(array( "fecha" => $_GET ["inicio"])),
							new Compra(array( "fecha" => $_GET ["fin"])));

	$ventas = VentaDAO::byRange(
							new Venta(array( "fecha" => $_GET ["inicio"])),
							new Venta(array( "fecha" => $_GET ["fin"])));	

} else if(isset($_GET ["inicio"])){
	$compras = CompraDAO::byRange(
							new Compra(array( "fecha" => $_GET ["inicio"])),
							new Compra(array( "fecha" => time())));

	$ventas = VentaDAO::byRange(
							new Venta(array( "fecha" => $_GET ["inicio"])),
							new Venta(array( "fecha" => time())));	

} else if(isset($_GET ["fin"])){
	$compras = CompraDAO::byRange(
							new Compra(array( "fecha" => 0)),
							new Compra(array( "fecha" => $_GET ["fin"])));	

	$ventas = VentaDAO::byRange(
							new Venta(array( "fecha" => 0)),
							new Venta(array( "fecha" => $_GET ["fin"])));	

	
} else {	
	$compras = CompraDAO::getAll();	
	$ventas = VentaDAO::getAll();
}




$totales_compras = array();


for ($i=0; $i < sizeof($compras); $i++) { 
	
	//echo "<hr>";

	if($compras[$i]->getCancelada()){
		//echo "<div style='background-color:yellow'>";
		//echo "procesando <strike>compra " . $compras[$i]->getIdCompra() . "</strike> CANCELADA <br>";

	}else{
		//echo "procesando compra " . $compras[$i]->getIdCompra() . "<br>";
	}

	//buscar sus productos
	$productos = CompraProductoDAO::search( new CompraProducto(  array( "id_compra" =>  $compras[$i]->getIdCompra()  )) );
	
	if(sizeof($productos) == 0) e ("NO HAY PRODUCTOS, WTF");
	
	for ($p=0; $p < sizeof($productos); $p++) { 
		$producto_original = ProductoDAO::getByPK($productos[$p]->getIdProducto());

		
		
		//echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;producto=" . $productos[$p]->getIdProducto() . " compradoen=" . $productos[$p]->getIdUnidad() ;

		//echo "&nbsp;cantidad=" . $productos[$p]->getCantidad();

		if(null === $producto_original->getIdUnidadCompra()){
			//w("No hay unidad compra en producto.");

			//vamos a ver si concide con la otra


		} else if($producto_original->getIdUnidadCompra() != $productos[$p]->getIdUnidad()){
			//e("El producto se compra en ".$producto_original->getIdUnidadCompra() .".");
		}
		

		if( is_null( $producto_original->getIdUnidad() ) ){
			//e("No hay unidad default.");


		} else if($producto_original->getIdUnidad() != $productos[$p]->getIdUnidad()){
			//e("La unidad default (".$producto_original->getIdUnidad().") difiere de la comprada.");
		}

		///echo "<br>";
		if($compras[$i]->getCancelada()) continue;


		if(!isset($totales_compras[ $productos[$p]->getIdProducto() ] ) ) {
			$totales_compras[ $productos[$p]->getIdProducto() ]	 = array();
		}

		if(!isset($totales_compras[ $productos[$p]->getIdProducto() ][ $productos[$p]->getIdUnidad() ] ) ) {
			$totales_compras[ $productos[$p]->getIdProducto() ][ $productos[$p]->getIdUnidad() ] = 0;
		}		
		
		$totales_compras[ $productos[$p]->getIdProducto() ][ $productos[$p]->getIdUnidad() ] += $productos[$p]->getCantidad();

 	
		

	}

	//echo "</div>";
}


//echo "<hr><h1>Ventas</h1>";



$totales_ventas = array();


for ($i=0; $i < sizeof($ventas); $i++) { 
	
	//echo "<hr>";

	if($ventas[$i]->getCancelada()){
		//echo "<div style='background-color:yellow'>";
		//echo "procesando <strike>venta " . $ventas[$i]->getIdVenta() . "</strike> CANCELADA <br>";

	}else if($ventas[$i]->getEsCotizacion()){
		//echo "<div style='background-color:yellow'>";
		//echo "procesando <strike>venta " . $ventas[$i]->getIdVenta() . "</strike> COTIZACION <br>";

	}else{

		//echo "procesando venta " . $ventas[$i]->getIdVenta() . "<br>";
	}

	//buscar sus productos
	$productos = VentaProductoDAO::search( new VentaProducto(  array( "id_venta" =>  $ventas[$i]->getIdVenta()  )) );

	//if(sizeof($productos) == 0) e ("NO HAY PRODUCTOS, WTF");


	for ($p=0; $p < sizeof($productos); $p++) { 

		$totales_ventas[ $productos[$p]->getIdProducto() ][ $productos[$p]->getIdUnidad() ] += $productos[$p]->getCantidad();


		

	}

	//echo "</div>";
}


echo "<table border=1>";


echo "<tr><td colspan=2>idprod</td><td colspan=3>compras</td><td colspan=3>ventas</td><td >warnings</td><td >total</td></tr>";



foreach ($totales_ventas as $key => $value ) {

	echo "<tr><td>producto " . $key . "</td>";

	$p = ProductoDAO::getByPK( $key );

	echo "<td>". $p->getNombreProducto() ."</td>";
	
	$total = 0;
	if(isset($totales_compras[$key])){
		//si tiene compras
		echo "<td><span style='color:green'>". (isset($totales_compras[$key][1]) ? $totales_compras[$key][1] : "") ."</span></td>";
		echo "<td><span style='color:green'>". (isset($totales_compras[$key][2]) ? $totales_compras[$key][2] : "") ."</span></td>";
		echo "<td><span style='color:green'>". (isset($totales_compras[$key][3]) ? $totales_compras[$key][3] : "") ."</span></td>";

		$total += isset($totales_compras[$key][1]) ? $totales_compras[$key][1] : 0;
		$total += isset($totales_compras[$key][2]) ? $totales_compras[$key][2] : 0;
		$total += isset($totales_compras[$key][3]) ? $totales_compras[$key][3] : 0;

	}else{
		echo "<td></td><td></td><td></td>";
	}

	echo "<td><span style='color:red'>". (isset($value[1]) ? $value[1] : "") ."</span></td>";
	echo "<td><span style='color:red'>". (isset($value[2]) ? $value[2] : "") ."</span></td>";
	echo "<td><span style='color:red'>". (isset($value[3]) ? $value[3] : "") ."</span></td>";

	$total -= isset($value[1]) ? $value[1] : 0;
	$total -= isset($value[2]) ? $value[2] : 0;
	$total -= isset($value[3]) ? $value[3] : 0;


	$foo = 0;
	for ($i=0; $i < 4; $i++) { 
		if(isset($value[$i])) $foo++;
	}

	$bar = 0;
	for ($i=0; $i < 4; $i++) { 
		if(isset($totales_compras[$key][$i])) $bar++;
	}

	if(($foo > 1) ||($bar > 1) ){
		echo "<td><div style='color:red'> <---- distintas unidades</div></td>";

	}else{
		echo "<td></td>";

	}
	if($total < 0){
		echo "<td><strong style='color:red'>" . $total . "</strong></td>";	
	}else{
		echo "<td><strong>" . $total . "</strong></td>";
	}
	
	$nexistencias = ProductoDAO::ExistenciasTotales(  $key );
	
	if($nexistencias != $total) {
		echo "<td><div style='background-color:red'>".$nexistencias."</div></td>";
	}else{
		echo "<td><div style='background-color:green'>".$nexistencias."</div></td>";
	}

		echo "<td><div ><input type='text'></div></td>";
	echo "</tr>";
}


echo "</table>";



































/*
SucursalesController::DescontarDeAlmacenes( $d_producto, $id_sucursal );
SucursalesController::IncrementarDeAlmacenes( $d_producto, $id_sucursal );
*/

/*
TRUNCATE `lote_entrada`;
TRUNCATE `lote_entrada_producto`;
TRUNCATE `lote_producto`;
TRUNCATE `lote_salida`;
TRUNCATE `lote_salida_producto`;
*/




	$page->render();