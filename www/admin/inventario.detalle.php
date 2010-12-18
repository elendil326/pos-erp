<h1>Detalles del producto</h1><?php


    require_once('model/inventario.dao.php');
    require_once('model/actualizacion_de_precio.dao.php');
    
    $producto = InventarioDAO::getByPK($_REQUEST['id']);

?><h2>Detalles</h2><?php

	



?>

<h2>Mapa de ventas</h2>




<h2>ACtualizaciones de precio</h2><?php


	


?>





