<h1>Inventario por sucursal</h1><?php

require_once("controller/inventario.controller.php");
require_once("controller/sucursales.controller.php");
require_once('model/actualizacion_de_precio.dao.php');








    //get sucursales
    $sucursales = listarSucursales();

    foreach( $sucursales as $sucursal ){
	
	    print ("<h2>" . $sucursal["descripcion"] . "</h2>");
	
	    $inventario = listarInventario( $sucursal["id_sucursal"] );

	    $header = array( 
		    "productoID" => "ID",
		    "descripcion"=> "Descripcion",
		    "precioVenta"=> "Precio Venta",
		    "existenciasMinimas"=> "Minimas",
		    "existencias"=> "Existencias",
		    "medida"=> "Tipo" );
	
	    $tabla = new Tabla( $header, $inventario );
	    $tabla->addColRender( "precioVenta", "moneyFormat" ); 
	    $tabla->addNoData( "<h3>Esta sucursal no tiene inventario.</h3>"); 
        $tabla->addOnClick( "productoID", "detalles");
	    $tabla->render();
    }



?>
<script type="text/javascript" charset="utf-8">
	function detalles( a ){
		window.location = "inventario.php?action=detalle&id=" + a;
	}
</script>
