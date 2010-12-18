<h1>Detalles del producto</h1>



<script src="../frameworks/jquery/jquery-1.4.2.min.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/uniform/jquery.uniform.js" type="text/javascript" charset="utf-8"></script> 
<link rel="stylesheet" href="../frameworks/uniform/css/uniform.default.css" type="text/css" media="screen">

<script type="text/javascript" charset="utf-8">
	$(function(){
      $("input, select").uniform();
    });
</script>
<?php


    require_once('model/inventario.dao.php');
    require_once('model/actualizacion_de_precio.dao.php');
    
    $producto = InventarioDAO::getByPK($_REQUEST['id']);

?><h2>Detalles</h2>

	
<form id="detalles">
<table border="0" cellspacing="5" cellpadding="5">
	<tr><td>ID Producto</td><td><?php echo $producto->getIdProducto();?></td></tr>
	<tr><td>Descripcion</td><td><?php echo $producto->getDescripcion();?></td></tr>
	<tr><td>Costo</td><td><?php echo $producto->getPrecioIntersucursal();?></td></tr>
	<tr><td>Medida</td><td><?php echo $producto->getMedida();?></td></tr>
	<tr><td><input type="button" onclick="window.location='inventario.php?action=editar&id=<?php echo $producto->getIdProducto();?>' " value="Editar" id="" /></td></tr>
</table>
</form>


<h2>Mapa de fluctuaciones de precio</h2>


	

<h2>Mapa de ventas</h2>





