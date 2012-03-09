<?php



define("BYPASS_INSTANCE_CHECK", false);

require_once("../../../../server/bootstrap.php");

$page = new GerenciaComponentPage();


//
// Parametros necesarios
// 
$page->requireParam("pid", "GET", "Este producto no existe.");

$este_producto = ProductoDAO::getByPK($_GET["pid"]);


//
// Titulo de la pagina
// 
//$page->addComponent(new TitleComponent( $este_producto->getNombreProducto(), 1));

$page->partialRender();


?>
<table>
	<tr>
		<td rowspan=2><div id="gimg"></div></td>
		<td><h2><?php echo $este_producto->getNombreProducto(); ?></h2></td>
	</tr>
	<tr>
		<td><?php echo $este_producto->getPrecio(); ?></td>
	</tr>
</table>

<script type="text/javascript">
	function gimgcb(a,b,c){
		if(a.responseData.results.length > 0)
			document.getElementById("gimg").innerHTML = "<img src='" + a.responseData.results[0].tbUrl + "'>";
	}
</script>
<script type="text/javascript" 
		src="https://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=<?php echo $este_producto->getCodigoProducto(); ?>&callback=gimgcb">
</script>
<?php

//
// Menu de opciones
//
if ($este_producto->getActivo()) {
    $menu = new MenuComponent();
    $menu->addItem("Editar este producto", "productos.editar.php?pid=" . $_GET["pid"]);
    //$menu->addItem("Desactivar este producto", null);
    
    $btn_eliminar = new MenuItem("Desactivar este producto", null);
    $btn_eliminar->addApiCall("api/producto/desactivar", "GET");
    $btn_eliminar->onApiCallSuccessRedirect("productos.lista.php");
    $btn_eliminar->addName("eliminar");
    
    $funcion_eliminar = " function eliminar_producto(btn){" . "if(btn == 'yes')" . "{" . "var p = {};" . "p.id_producto = " . $_GET["pid"] . ";" . "sendToApi_eliminar(p);" . "}" . "}" . "      " . "function confirmar(){" . " Ext.MessageBox.confirm('Desactivar', 'Desea eliminar este producto?', eliminar_producto );" . "}";
    
    $btn_eliminar->addOnClick("confirmar", $funcion_eliminar);
    
    $menu->addMenuItem($btn_eliminar);
    
    $page->addComponent($menu);
}

//
// Forma de producto
// 
$form = new DAOFormComponent($este_producto);
$form->setEditable(false);

$form->hideField(array(
    "id_producto"
));

$form->makeObligatory(array(
    "compra_en_mostrador",
    "costo_estandar",
    "nombre_producto",
    "id_empresas",
    "codigo_producto",
    "metodo_costeo",
    "activo"
));

$form->createComboBoxJoin("id_unidad", "nombre", UnidadDAO::getAll(), $este_producto->getIdUnidad());

$page->addComponent($form);



$page->render();
