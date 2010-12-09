
<h2>Clientes activos</h2><?php

/*
 * Lista de Clientes
 */ 


require_once("controller/clientes.controller.php");


//obtener los clientes del controller de clientes
$clientes = listarClientes();

/*
["id_cliente"]=> string(3) "204" 
["rfc"]=> string(12) "DIL34534DFFs" 
["nombre"]=> string(29) "Dilba Monica del Moral Cuevas" 
["direccion"]=> string(38) "Gardenias #123 Rosalinda, 2da Seccion." 
["ciudad"]=> string(6) "Celaya" 
["telefono"]=> string(13) "0444611744149"
["e_mail"]=> string(19) "dilbis_@hotmail.com" 
["limite_credito"]=> string(4) "1000" 
["descuento"]=> string(2) "50" 
["activo"]=> string(1) "1" 
["id_usuario"]=> string(2) "37" 
["id_sucursal"]=> string(2) "54"
["credito_restante"]=> float(365.5) 
*/

//render the table
$header = array(  "nombre" => "Nombre", "rfc" => "RFC", "direccion" => "Direccion", "ciudad" => "Ciudad"  );
$tabla = new Tabla( $header, $clientes );
$tabla->addOnClick("id_cliente", "mostrarDetalles");
$tabla->render();

?>
<script type="text/javascript" charset="utf-8">
	function mostrarDetalles( a ){
		window.location = "clientes.php?action=detalles&id=" + a;
	}
</script>
