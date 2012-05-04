<?php



define("BYPASS_INSTANCE_CHECK", false);

require_once("../../../../server/bootstrap.php");

$page = new GerenciaTabPage();

//
// Parametros necesarios
// 
$page->requireParam("pid", "GET", "Este producto no existe.");


$este_producto = ProductoDAO::getByPK($_GET["pid"]);

$precios = TarifasController::_CalcularTarifa( $este_producto, "venta" );

$precios_html = "<table><tr><td colspan=2><h3>Tarifas</h3></td></tr>	";
for ($i=0; $i < sizeof($precios); $i++) { 
	$precios_html .= "<tr><td>".$precios[$i]["descripcion"] . "</td><td>" . FormatMoney($precios[$i]["precio"]) . "</td>";
}
$precios_html .= "</table>";

$page->addComponent("
<table>
    <tr>
        <td rowspan=2><div id=\"gimg\"></div></td>
        <td><h2>" . $este_producto->getNombreProducto() . "</h2></td>
    </tr>
    <tr>
        <td>" .  $precios_html . "</td>
    </tr>
</table>
<script type=\"text/javascript\">
    function gimgcb(a,b,c){
        if(a.responseData.results.length > 0)
            document.getElementById(\"gimg\").innerHTML = \"<img src='\" + a.responseData.results[0].tbUrl + \"'>\";
    }
</script>
<script 
        type=\"text/javascript\" 
        src=\"https://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=".  $este_producto->getCodigoProducto() . "&callback=gimgcb\">
</script>");




$page->nextTab("General");

//
// Forma de producto
// 
$form = new DAOFormComponent($este_producto);
$form->setEditable(false);

$form->hideField(array(
    "id_producto",
	"foto_del_producto",
	"precio",
	"id_unidad_compra",
	"control_de_existencia",
	"activo"
	));

$form->createComboBoxJoinDistintName("id_unidad_compra","id_unidad_medida" ,"abreviacion", UnidadMedidaDAO::getAll(), $este_producto->getIdUnidadCompra());

$form->createComboBoxJoinDistintName("id_unidad", "id_unidad_medida", "abreviacion", UnidadMedidaDAO::getAll(), $este_producto->getIdUnidad());

$page->addComponent($form);


$page->nextTab("Existencias");

$entrada_lote = new FormComponent(  );

$entrada_lote->addField("id_lote", "lote", "combobox" );
$entrada_lote->createComboBoxJoin("id_lote", "id_lote", LoteDAO::getAll(   ) );
$entrada_lote->addField("cantidad", "Cantidad", "text");
$entrada_lote->addField("productos", "", "text", "\"   [ { \\\"id_producto\\\" : ". $_GET["pid"] .", \\\"cantidad\\\"    : 40 } ]   \"");
$entrada_lote->sendHidden("productos");
$entrada_lote->addApiCall("api/almacen/lote/entrada", "POST");

$page->addComponent( $entrada_lote );


$page->nextTab("Configuracion");

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

$page->render();
