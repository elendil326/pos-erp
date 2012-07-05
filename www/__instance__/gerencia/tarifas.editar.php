<?php
define("BYPASS_INSTANCE_CHECK", false);

require_once("../../../../server/bootstrap.php");

$page = new GerenciaComponentPage();

$tarifa = TarifaDAO::getByPK($_GET["tid"]);

if (is_null($tarifa)) {
    die;
}
//
// Parametros necesarios
// 
$page->requireParam("tid", "GET", "Esta tarifa no existe.");


//
// Titulo de la pagina
// 

$page->addComponent(new TitleComponent("Detalle Tarifa"));

$page->addComponent(new TitleComponent(utf8_decode($tarifa->getNombre()), 2));

$page->partialRender();
?>

<table style ="width:100%;">
    <tr>
        <td style = "border-width:0px;" valign="middle">
            <input class="POS Boton OK"  type = "button" value = "Guardar" onClick = "" />
        </td>
    </tr>
</table>

<form>
    <table width = 100% border = 0 >
        <tr>
            <td><label>Tipo Tarifa</label></td>
            <td>                
                <select>
                    <option value="compra" >Compra</option>
                    <option value="venta" >Venta</option>
                </select>                
            </td>
            <td>
                <label>Activa</label>
            </td>
            <td>
                <select>
                    <option <?php echo $tarifa->getActiva() ? " SELECTED" : "" ?> >S&iacute;</option>
                    <option <?php echo $tarifa->getActiva() ? "" : " SELECTED" ?> >No</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><label>Moneda</label></td>
            <td>          
                <select name = "id_moneda_tarifa" id = "id_moneda_tarifa" onChange = "" >
                    <?php
                    //$options = "<option value = null>-------</option>";
                    $options = "";
                    foreach (MonedaDAO::getAll() as $moneda) {
                        $options .= "<option value = \"{$moneda->getIdMoneda()}\"" .  ($tarifa->getIdMoneda() == $moneda->getIdMoneda()? " SELECTED " : "") . ">{$moneda->getNombre()}</option>";
                    }

                    echo $options;
                    ?>
                </select>
                                              
            </td>
            <td><label>Default Sistema</label></td>
            <td>
                <input type="Radio" name="default_tarifa" value="true" <?php echo $tarifa->getDefault() ? " CHECKED" : "" ?> /> S&iacute;
                <input type="Radio" name="default_tarifa" value="false" <?php echo $tarifa->getDefault() ? "" : " CHECKED" ?> /> No                
            </td>        
        </tr>        
    </table>
</form>

<?php
$page->addComponent(new TitleComponent("Vers&iacute;on Tarifa", 2));
$page->partialRender();
?>

<table style ="width:100%;">
    <tr>
        <td style = "border-width:0px;" valign="middle">
            <input class="POS Boton OK"  type = "button" value = "Nueva Versi&oacute;n" onClick = "" />
        </td>
    </tr>
</table>

<?php
$tabla = new TableComponent(
                array(
                    "nombre" => "Nombre",
                    "activa" => "Activa",
                    "fecha_inicio" => "Fecha Inicial",
                    "fecha_fin" => "Fecha Final",
                    "default" => "Default"
                ),
                VersionDAO::search( new Version( array("id_tarifa" => $tarifa->getIdTarifa()) ) )
);

function getCheck($activa) {
    return $activa == 1 ? "<input type=\"checkbox\" checked disabled>" : "<input type=\"checkbox\" disabled>";
}

$tabla->addColRender("activa", "getCheck");

$tabla->addColRender("default", "getCheck");

$tabla->addOnClick( "id_version", "(function(a){ window.location = 'tarifas.version.ver.php?vid=' + a; })" );

$page->addComponent($tabla);

$page->render();
