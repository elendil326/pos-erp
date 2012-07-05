<?php
define("BYPASS_INSTANCE_CHECK", false);

require_once("../../../server/bootstrap.php");

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

<table style ="width:100%; margin-top: 50px;">
    <tr>
        <td style = "border-width:0px;" valign="middle">
            <input class="POS Boton"  type = "button" value = "Editar Tarifa" onClick = " window.location = 'tarifas.editar.php?tid=<?php echo $_GET["tid"]; ?>'; " />
        </td>
    </tr>
</table>

<form>
    <table width = 100% border = 0 >
        <tr>
            <td><label>Tipo Tarifa</label></td>
            <td>                
                <select disabled="disabled" >
                    <option><?php echo $tarifa->getTipoTarifa() ?></option>
                </select>                
            </td>
            <td>
                <label>Activa</label>
            </td>
            <td>
                <select disabled="disabled" >
                    <option <?php echo $tarifa->getActiva() ? " SELECTED" : "" ?> >S&iacute;</option>
                    <option <?php echo $tarifa->getActiva() ? "" : " SELECTED" ?> >No</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><label>Moneda</label></td>
            <td>                
                <input type="text" value ="<?php echo MonedaDAO::getByPK($tarifa->getIdMoneda())->getSimbolo(); ?> ($)" DISABLED />                
            </td>
            <td><label>Default Sistema</label></td>
            <td>
                <input type="Radio" DISABLED name="default_tarifa" value="true" <?php echo $tarifa->getDefault() ? " CHECKED" : "" ?> /> S&iacute;
                <input type="Radio" DISABLED name="default_tarifa" value="false" <?php echo $tarifa->getDefault() ? "" : " CHECKED" ?> /> No                
            </td>        
        </tr>        
    </table>
</form>

<?php
$page->addComponent(new TitleComponent("Vers&iacute;on Tarifa", 2));

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
