<?php
require_once("controller/compras.controller.php");
require_once("controller/sucursales.controller.php");
require_once("controller/ventas.controller.php");
require_once("controller/personal.controller.php");
require_once("controller/efectivo.controller.php");
require_once("controller/inventario.controller.php");
require_once("controller/contabilidad.controller.php");

require_once('model/pagos_venta.dao.php');
require_once('model/corte.dao.php');
require_once('model/usuario.dao.php');


$sucursal = SucursalDAO::getByPK($_REQUEST['id']);
?>
<style type="text/css" media="screen">
    #map_canvas { 
        height: 200px;
        width: 400px;
    }
</style>

<script src="../frameworks/humblefinance/flotr/flotr.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/excanvas.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/canvastext.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/canvas2image.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/base64.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8" src="../frameworks/humblefinance/humble/HumbleFinance.js"></script>
<link rel="stylesheet" href="../frameworks/humblefinance/humble/finance.css" type="text/css" media="screen" title="no title" charset="utf-8">


<h2>Detalles</h2>
<table style="width:100%" border="0" cellspacing="1" cellpadding="1">
    <tr><td><b>Descripcion</b></td><td>		<?php echo $sucursal->getDescripcion(); ?></td><td valign="top" align="right" rowspan=9><div  id="map_canvas"></div></td></tr>
    <tr><td><b>Direccion</b></td><td>		<?php echo $sucursal->getCalle() . " " . $sucursal->getNumeroExterior() . " " . $sucursal->getColonia() . " " . $sucursal->getMunicipio(); ?></td></tr>
    <tr><td><b>Apertura</b></td><td>		<?php echo toDate($sucursal->getFechaApertura()); ?></td></tr>
    <tr><td><b>Gerente</b></td><td>
            <?php
            $gerente = UsuarioDAO::getByPK($sucursal->getGerente());
            if ($gerente === null) {
                echo "Esta sucursa no tiene gerente !";
            } else {
                echo "<a href='gerentes.php?action=detalles&id=" . $sucursal->getGerente() . "'>";
                echo $gerente->getNombre();
                echo "</a>";
            }
            ?>
        </td></tr>
    <tr><td><b>ID</b></td><td>				<?php echo $sucursal->getIdSucursal(); ?></td></tr>
    <tr><td><b>Letras factura</b></td><td>	<?php echo $sucursal->getLetrasFactura(); ?></td></tr>
    <tr><td><b>RFC</b></td><td>				<?php echo $sucursal->getRfc(); ?></td></tr>	
    <tr><td><b>Telefono</b></td><td>		<?php echo $sucursal->getTelefono(); ?></td></tr>	

    <tr><td colspan=2><input type=button value="Editar detalles" onclick="editar()"></td> </tr>
</table>

<?php
$balance = ContabilidadController::getBalancePorSucursal($_REQUEST['id']);
?>
<style>
    .pr{
        font-size: 2.6em;
        font-weight: bold;
    }
    .ch {
        font-size: 1.8em;
        vertical-align: text-top;
    }

    .tiny-text{
        font-size: 11px;
        color:gray;
    }
</style>

<table border=0>
    <tr>
        <td class="tiny-text">
			Balance actual:
        </td>
        <td class="tiny-text">
			Con respecto al 
            <?php
            if ((sizeof($balance) - 2) < 0) {
                echo "";
            } else {
                $date = toDate($balance[sizeof($balance) - 2]["fecha"]);
                $foo = explode(" ", $date);
                echo $foo[0];
            }
            ?> :
        </td>	
    </tr>
    <tr>
        <td>
            <?php echo "<div class='pr'>" . moneyFormat($balance[sizeof($balance) - 1]["value"]) . "</div>"; ?>
        </td>
        <td>
            <?php
            if ((sizeof($balance) - 2) >= 0) {
                $change = $balance[sizeof($balance) - 1]["value"] - $balance[sizeof($balance) - 2]["value"];
                $change_pct = ($change * 100) / $balance[sizeof($balance) - 2]["value"];
                $change_pct = round($change_pct, 4);
                if ($change > 0) {
                    $change = moneyFormat($change);
                    echo "<div class='ch' style='color:green;'>+" . $change . " ( " . $change_pct . " % )</div>";
                } else {
                    $change = moneyFormat($change);
                    echo "<div class='ch' style='color:#A03;'>" . $change . " ( " . $change_pct . " % )</div>";
                }
            }
            ?>
        </td>		
    </tr>
    <tr>
        <td style="text-align:right">

        </td>

    </tr>
</table>

<?php
$ingreos_darios_g = new Reporte();
$ingreos_darios_g->agregarMuestra("Balance general", $balance, false);
$ingreos_darios_g->fechaDeInicio(strtotime($sucursal->getFechaApertura()));
$ingreos_darios_g->setEscalaEnY("pesos");
$ingreos_darios_g->graficar("Mapa");
?><br><hr><?php
$ingresos_diarios = ContabilidadController::getIngresosDiarios($_REQUEST['id']);
$gastos_diarios = ContabilidadController::getGastosDiarios($_REQUEST['id']);

$asdf = new Reporte();
$asdf->agregarMuestra("Ingresos", $ingresos_diarios, true);
$asdf->agregarMuestra("Egresos", $gastos_diarios, true);
$asdf->fechaDeInicio(strtotime($sucursal->getFechaApertura()));
$asdf->setEscalaEnY("pesos");
$asdf->graficar("Flujo diario");
?><br>

<script type="text/javascript"> 

    jQuery("#MAIN_TITLE").html( "<?php echo $sucursal->getDescripcion(); ?>" )

    var drawMap = function ( result, status ) {
        if(result.length == 0){
            document.getElementById("map_canvas").innerHTML = "<div align='center'> Imposible localizar esta direccion. </div>"; 
            return;
        }

        var myLatlng = result[0].geometry.location;

        var myOptions = {
            zoom: 18,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.HYBRID,
            navigationControl : true
        };

        try{
            var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
        }catch(e){
            document.getElementById("map_canvas").innerHTML = "<div align='center'> Imposible crear el mapa.</div>";
            return;
        }


        m = new google.maps.Marker({
            map: map,
            position: myLatlng
        });
    }


    function startMap(){

        GeocoderRequest = {
            address : "<?php echo $sucursal->getCalle() . " " . $sucursal->getNumeroExterior() . ", " . $sucursal->getColonia() . ", " . $sucursal->getMunicipio(); ?>, Mexico"
        };
        try{

            gc = new google.maps.Geocoder( );

            gc.geocode(GeocoderRequest,  drawMap);
		
        }catch(e){
            console.log(e)
        }


    }


    function mostrarDetalles( a ){
        window.location = "clientes.php?action=detalles&id=" + a;
    }
</script>


<script type="text/javascript" charset="utf-8">
    function mostrarDetallesVenta (vid){ window.location = "ventas.php?action=detalles&id=" + vid; }
    function editar(){ window.location = "sucursales.php?action=editar&sid=<?php echo $_REQUEST['id'] ?>"; }
</script>
<?php
/*
 * Buscar el numero de ventas de esta sucursal, versus las ventas de todas las sucursales en la empresa
 * 
 * */
if (VentasDAO::getByPK(1) != null) {
    $numeroDeVentasDiarias = new Reporte();
    $numeroDeVentasDiarias->agregarMuestra("Esta sucursal", VentasDAO::contarVentasPorDia($sucursal->getIdSucursal(), -1));
    $numeroDeVentasDiarias->agregarMuestra("Todas las sucursales", VentasDAO::contarVentasPorDia(null, -1));
    $numeroDeVentasDiarias->fechaDeInicio(strtotime(VentasDAO::getByPK(1)->getFecha()));
    $numeroDeVentasDiarias->setEscalaEnY("ventas");
    $numeroDeVentasDiarias->graficar("Ventas de esta sucursal");
}
?>

<h2><img src='../media/icons/basket_go_32.png'>&nbsp;Ventas en el ultimo dia</h2>

<?php
$date = new DateTime("now");
$date->setTime(0, 0, 1);

$v1 = new Ventas();
$v1->setFecha($date->format('Y-m-d H:i:s'));
$v1->setIdSucursal($_REQUEST['id']);

$date->setTime(23, 59, 59);
$v2 = new Ventas();
$v2->setFecha($date->format('Y-m-d H:i:s'));

$ventas = VentasDAO::byRange($v1, $v2);

//render the table
$header = array(
    "id_venta" => "Venta",
    //"id_sucursal"=>  "Sucursal",
    "id_cliente" => "Cliente",
    "tipo_venta" => "Tipo",
    "fecha" => "Hora",
    "subtotal" => "Subtotal",
    //"iva"=>  "IVA",
    "descuento" => "Descuento",
    "total" => "Total",
        //"pagado"=>  "Pagado" 
);

function getNombrecliente($id) {
    if ($id < 0) {
        return "Caja Comun";
    }
    return ClienteDAO::getByPK($id)->getRazonSocial();
}

function pDate($fecha) {

    $foo = toDate($fecha);
    $bar = explode(" ", $foo);
    return $bar[1] . " " . $bar[2];
}

function setTipocolor($tipo) {
    if ($tipo == "credito")
        return "<b>Credito</b>";
    return "Contado";
}

$tabla = new Tabla($header, $ventas);
$tabla->addColRender("subtotal", "moneyFormat");
$tabla->addColRender("saldo", "moneyFormat");
$tabla->addColRender("total", "moneyFormat");
$tabla->addColRender("pagado", "moneyFormat");
$tabla->addColRender("tipo_venta", "setTipoColor");
$tabla->addColRender("id_cliente", "getNombreCliente");
$tabla->addColRender("fecha", "pDate");
$tabla->addOnClick("id_venta", "mostrarDetallesVenta");
$tabla->addColRender("descuento", "percentFormat");
$tabla->addNoData("Esta sucursal no ha realizado ventas este dia.");
$tabla->render();
?>



<h2>Compras no saldadas de esta sucursal</h2><?php

function rSaldo($pagado) {
    return moneyFormat($pagado);
}

function toDateS($d) {
    $foo = toDate($d);
    $bar = explode(" ", $foo);
    return $bar[0];
}

$compras = comprasDeSucursalSinSaldar($_REQUEST['id'], false);
##########################################################################
?>
<script>
    var compras_no_saldadas = [];

    var store_para_compras =  new Ext.data.ArrayStore({
        fields: [
            { name : 'id_compra', 		type : 'int' },
            { name : 'fecha', 			type : 'date', dateFormat: 'Y-m-d H:i:s' },
            { name : 'subtotal', 		type : 'float' },
            { name : 'id_usuario', 		type : 'int' },
            { name : 'pagado', 			type : 'float' },
            { name : 'liquidado', 		type : 'float' },
            { name : 'total', 			type : 'float' }
        ]
    });
			
<?php
foreach ($compras as $c) {
    ?>
            compras_no_saldadas.push([
    <?php echo $c->getIdCompra(); ?>,
                "<?php echo $c->getFecha(); ?>",
    <?php echo $c->getSubtotal(); ?>,
    <?php echo $c->getIdUsuario(); ?>,
    <?php echo $c->getPagado(); ?>,
    <?php echo $c->getLiquidado(); ?>,
    <?php echo $c->getTotal(); ?>
            ]);
            //compras_no_saldadas.push( <?php echo json_encode($c->asArray()) ?> );
    <?php
}
?>





    Ext.onReady(function(){
        Ext.QuickTips.init();
        store_para_compras.loadData(compras_no_saldadas);
        // create the Grid
        var tabla_compras_no_saldadas = new Ext.grid.GridPanel({
            store: store_para_compras,
            header : false,
            columns: [
                {
                    header   : 'Fecha', 
                    width    : 75, 
                    sortable : true, 
                    renderer : Ext.util.Format.dateRenderer('d/m/Y'),  
                    dataIndex: 'fecha'
                },
                {
                    header   : 'ID Compra', 
                    width    : 75, 
                    sortable : true, 
                    dataIndex: 'id_compra'
                },	
                {
                    header   : 'Subtotal', 
                    width    : 180, 
                    renderer : 'usMoney',
                    sortable : true, 
                    dataIndex: 'subtotal'
                },
                {
                    header   : 'Total', 
                    width    : 85, 
                    sortable : true, 
                    renderer : 'usMoney',
                    dataIndex: 'total'
                },
                {
                    header   : 'Pagado', 
                    width    : 85, 
                    sortable : true, 
                    renderer : 'usMoney',
                    dataIndex: 'pagado'
                }],
            stripeRows: false,
            //autoExpandColumn: 'total',
            height: 500,
            minHeight : 300,
            width: "100%",
            stateful: false,
            stateId: 'sucursales_compras_no_saldadas_cookie',
            listeners : {
                "rowclick" : function (grid, rowIndex, e){
							
                    var datos = grid.getStore().getAt( rowIndex );

							
                    window.location = "inventario.php?action=detalleCompraSucursal&cid=" + datos.get("id_compra" );
                }
            }

        });
        tabla_compras_no_saldadas.render("tabla_compras_no_saldadas_holder");
    });


</script>

<div id="tabla_compras_no_saldadas_holder" style="padding: 5px;">
</div>

<?php
##########################################################################
?>

<h2><img src='../media/icons/window_app_list_search_32.png'>&nbsp;Gastos de esta sucursal</h2>
<?php
$gastos = listarGastosSucursal($_REQUEST['id']);

$array_gastos = array();

foreach ($gastos as $gasto) {

    $empleado = UsuarioDAO::getByPK($gasto->getIdUsuario());

    array_push($array_gastos, array("folio" => $gasto->getFolio(),
        "concepto" => $gasto->getConcepto(),
        "monto" => $gasto->getMonto(),
        "fecha" => $gasto->getFecha(),
        "fecha_ingreso" => $gasto->getFechaIngreso(),
        "empleado" => $empleado->getNombre(),
        "nota" => $gasto->getNota()
            )
    );
}

$header = array(
    "folio" => "Folio",
    "concepto" => "Concepto",
    "monto" => "Monto",
    "fecha" => "Se Ingreso",
    "fecha_ingreso" => "Fecha del Gasto",
    "empleado" => "Registro",
    "nota" => "Nota");


$tabla = new Tabla($header, $array_gastos);
$tabla->addNoData("Esta sucursal no cuenta con nigun gasto.");
$tabla->addRow("folio");
$tabla->addRow("concepto");
$tabla->addColRender("monto", "moneyFormat");
$tabla->addColRender("fecha", "toDate");
$tabla->addColRender("fecha_ingreso", "toDateS");
//$tabla->addOnClick("empleado", "(function(id){window.location='personal.php?action=detalles&uid=' + id;})");
$tabla->addRow("empleado");
$tabla->addRow("nota");
$tabla->render();
?>

<h2><img src='../media/icons/window_app_list_add_32.png'>&nbsp;Ingresos de esta sucursal</h2>
<?php
$ingresos = listarIngresosSucursal($_REQUEST['id']);

$array_ingresos = array();

foreach ($ingresos as $ingreso) {

    $empleado = UsuarioDAO::getByPK($ingreso->getIdUsuario());

    array_push($array_ingresos, array("concepto" => $ingreso->getConcepto(),
        "monto" => $ingreso->getMonto(),
        "fecha" => $ingreso->getFecha(),
        "fecha_ingreso" => $ingreso->getFechaIngreso(),
        "empleado" => $empleado->getNombre(),
        "nota" => $ingreso->getNota()
            )
    );
}

$header = array(
    "concepto" => "Concepto",
    "monto" => "Monto",
    "fecha" => "Se Ingreso",
    "fecha_ingreso" => "Fecha del Gasto",
    "empleado" => "Registro",
    "nota" => "Nota");


$tabla = new Tabla($header, $array_ingresos);
$tabla->addNoData("Esta sucursal no cuenta con nigun ingreso.");

$tabla->addRow("concepto");
$tabla->addColRender("monto", "moneyFormat");
$tabla->addColRender("fecha", "toDate");
$tabla->addColRender("fecha_ingreso", "toDateS");
//$tabla->addOnClick("empleado", "(function(id){window.location='personal.php?action=detalles&uid=' + id;})");
$tabla->addRow("empleado");
$tabla->addRow("nota");
$tabla->render();
?>

<h2><img src='../media/icons/users_business_32.png'>&nbsp;Personal</h2><?php
$empleados = listarEmpleados($_REQUEST['id'], true);


switch (POS_PERIODICIDAD_SALARIO) {
    case POS_SEMANA : $periodicidad = "semanal";
        break;
    case POS_MES : $periodicidad = "menusal";
        break;
}

$header = array(
    "id_usuario" => "ID",
    "nombre" => "Nombre",
    "_activo" => "Activo",
    "puesto" => "Puesto",
    "RFC" => "RFC",
    //"direccion" => "Direccion",
    //"telefono" => "Telefono",
    "fecha_inicio" => "Inicio",
    "salario" => "Salario " . $periodicidad);


$tabla = new Tabla($header, $empleados);
$tabla->addColRender("salario", "moneyFormat");
$tabla->addColRender("fecha_inicio", "toDateS");
$tabla->addNoData("Esta sucursal no cuenta con nigun empleado.");
$tabla->addOnClick("id_usuario", "(function(id){window.location='personal.php?action=detalles&uid=' + id;})");
$tabla->render();


$totalEmpleados = 0;

foreach ($empleados as $e) {
    
    if($e['activo'] == "1"){
        $totalEmpleados += $e['salario'];
    }
}

$salarioGerente = 0;
if ($gerente !== null)
    $salarioGerente = $gerente->getSalario();
?>
<div align="center">
    <table width="100%">
        <tr>
            <td width ="50%">
                <table align="center">
                    <tr rowspan="3"><td colspan="2"><input type ="button" value ="Agregar Nuevo Empleado" onClick ="window.location='personal.php?action=nuevo&id=' + <?php echo $_REQUEST['id'] ?>"/></td><td>&nbsp;</td><td colspan="2"><input type ="button" value ="Editar Puestos" onClick ="window.location='sucursales.php?action=editarPuestos&id=' + <?php echo $_REQUEST['id'] ?>"/></td></tr>
                </table>
            </td>
            <td width ="50%">
                <table align="right">
                    <tr><td>Salarios empleados</td><td><b><?php echo moneyFormat($totalEmpleados); ?></b></td></tr>
                    <tr><td>Salario gerente</td><td><b><?php echo moneyFormat($salarioGerente); ?></b></td></tr>
                    <tr><td>Total</td><td><b><?php echo moneyFormat($totalEmpleados + $salarioGerente); ?></b></td></tr>
                </table>
            </td>
        </tr>        
    </table>
</div>











<h2><img src='../media/icons/window_app_list_chart_32.png'>&nbsp;Inventario actual</h2><?php
//obtener los clientes del controller de clientes
$inventario = listarInventario($_REQUEST['id']);

function colorExistencias($n) {
    //buscar en el arreglo
    if ($n < 10) {
        return "<div style='color:red;'>" . $n . "</div>";
    }

    return $n;
}

function toUnit($e, $row) {
    //$row["tratamiento"]
    switch ($row["medida"]) {
        case "kilogramo" : $escala = "Kgs";
            break;
        case "pieza" : $escala = "Pzas";
            break;
    }

    return "<b>" . number_format($e / 60, 2) . "</b>Arp. / <b>" . number_format($e, 2) . "</b>" . $escala;
}

function toUnitProc($e, $row) {
    if ($row["tratamiento"] == null) {
        return "<i>NA</i>";
    }

    switch ($row["medida"]) {
        case "kilogramo" : $escala = "Kgs";
            break;
        case "pieza" : $escala = "Pzas";
            break;
    }

    return "<b>" . number_format($e / 60, 2) . "</b>Arp. / <b>" . number_format($e, 2) . "</b>" . $escala;
}

//render the table
$header = array(
    "productoID" => "ID",
    "descripcion" => "Descripcion",
    "precioVenta" => "Precio sugerido",
    "existenciasOriginales" => "Existencias Originales",
    "existenciasProcesadas" => "Existencias Procesadas");



$tabla = new Tabla($header, $inventario);
$tabla->addColRender("precioVenta", "moneyFormat");
$tabla->addColRender("existenciasOriginales", "toUnit");
$tabla->addColRender("existenciasProcesadas", "toUnitProc");
$tabla->addNoData("Esta sucursal no cuenta con ningun producto en su inventario.");
$tabla->render();
?><h2><img src='../media/icons/email_forward_32.png'>&nbsp;Autorizaciones pendientes</h2><?php
    $autorizacion = new Autorizacion();
    $autorizacion->setIdSucursal($_REQUEST['id']);
    $autorizacion->setEstado("0");
    $autorizaciones = AutorizacionDAO::search($autorizacion);


    $header = array(
        "id_autorizacion" => "ID",
        "fecha_peticion" => "Fecha",
        "id_usuario" => "Usuario que realizo la peticion",
        "parametros" => "Descripcion");

    function renderParam($json) {
        $obj = json_decode($json);
        return $obj->descripcion;
    }

    function renderUsuario($uid) {
        $usuario = UsuarioDAO::getByPK($uid);
        return $usuario->getNombre();
    }

    $tabla = new Tabla($header, $autorizaciones);
    $tabla->addColRender("parametros", "renderParam");
    $tabla->addColRender("id_usuario", "renderUsuario");
    $tabla->addOnClick("id_autorizacion", "detalle");
    $tabla->addNoData("No hay autorizaciones pendientes");
    $tabla->render();
?><h2><img src='../media/icons/user_add_32.png'>&nbsp;Clientes que se registraron en esta sucursal</h2><?php
    $foo = new Cliente();
    $foo->setActivo(1);
    $foo->setIdCliente(1);
    $foo->setIdSucursal($_REQUEST['id']);

    $bar = new Cliente();
    $bar->setIdCliente(999);

    $clientes = ClienteDAO::byRange($foo, $bar);

//render the table
    $header = array("razon_social" => "Razon Social", "rfc" => "RFC", /* "direccion" => "Direccion", */ "municipio" => "Municipio");
    $tabla = new Tabla($header, $clientes);
    $tabla->addOnClick("id_cliente", "mostrarDetalles");
    $tabla->addNoData("Ningun cliente se ha registrado en esta sucursal.");
    $tabla->render();
?><br> <h2>Estado</h2> <?php
    $flujo = array();


    /*     * *****************************************
     * Fecha desde el ultimo corte
     * ****************************************** */
    $corte = new Corte();
    $corte->setIdSucursal($sucursal->getIdSucursal());

    $cortes = CorteDAO::getAll(1, 1, 'fecha', 'desc');

    /*     * **** Este Corte ********** */
    $esteCorte = new Corte();

    /*     * **** Este Corte ********** */
    $esteCorte->setIdSucursal($sucursal->getIdSucursal());


    if (sizeof($cortes) == 0) {
        if (POS_MULTI_SUCURSAL) {
            echo "<div class='light-blue-rounded' >No se han hecho cortes en esta sucursal. Mostrando flujo desde la apertura de sucursal.</div><br>";
        } else {
            echo "<div class='light-blue-rounded' >No se han hecho cortes. Mostrando flujo desde la apertura.</div><br>";
        }

        $fecha = $sucursal->getFechaApertura();
    } else {

        $corte = $cortes[0];
        echo "Fecha de ultimo corte: <b>" . $corte->getFecha() . "</b><br>";
        $fecha = $corte->getFecha();
    }


    $now = new DateTime("now");
    $hoy = $now->format("Y-m-d H:i:s");



    /*     * *****************************************
     * Total de ventas
     * total de activo realizado en ventas para 
     * esta sucursal incluyendo ventas a credito 
     * y ventas a contado aunque no esten saldadas
     * **************************************** */

    /*     * **** Este Corte ********** */
    $esteCorte->setTotalVentas(
            VentasDAO::totalVentasDesdeFecha($sucursal->getIdSucursal(), $fecha)
    );


    /*     * *****************************************
     * Total de ventas Abonado
     * total de efectivo adquirido gracias a ventas, 
     * incluye ventas a contado y los abonos de las 
     * ventas a credito
     * **************************************** */

    //obtener todas la ventas a contado
    $ventas_a_contado = 0;

    $foo = new Ventas();
    $foo->setFecha($fecha);
    $foo->setIdSucursal($sucursal->getIdSucursal());
    $foo->setTipoVenta('contado');

    $bar = new Ventas();
    $bar->setFecha($hoy);

    $ventas = VentasDAO::byRange($foo, $bar);

    foreach ($ventas as $i) {
        $ventas_a_contado += $i->getPagado();
    }


    //obtener todos los abonos
    $abonos_a_creditos = 0;

    $query = new PagosVenta();
    $query->setIdSucursal($sucursal->getIdSucursal());
    $query->setFecha($fecha);

    $queryE = new PagosVenta();
    $queryE->setFecha($hoy);

    $results = PagosVentaDAO::byRange($query, $queryE);

    foreach ($results as $pago) {
        $abonos_a_creditos += $pago->getMonto();
    }

    /*     * **** Este Corte ********** */
    $esteCorte->setTotalVentasAbonado(
            $abonos_a_creditos + $ventas_a_contado
    );


    /*     * *****************************************
     * total ventas saldo
     * total de dinero que se le debe a esta sucursal
     * por ventas a credito
     * **************************************** */
    $foo = new Ventas();
    $foo->setIdSucursal($sucursal->getIdSucursal());
    $foo->setTipoVenta("credito");
    $foo->setFecha($fecha);
    $foo->setLiquidada(0);

    $bar = new Ventas();
    $bar->setFecha($hoy);

    $res = VentasDAO::byRange($foo, $bar);

    $saldo_pendiente = 0;

    foreach ($res as $venta) {
        $saldo_pendiente += ( $venta->getTotal() - $venta->getPagado());
    }


    /*     * **** Este Corte ********** */
    $esteCorte->setTotalVentasSaldo(
            $saldo_pendiente
    );

    /*     * *****************************************
     * Total Compras
     * total de gastado en compras
     * **************************************** */
    $foo = new CompraSucursal();
    $foo->setFecha($fecha);
    $foo->setIdSucursal($sucursal->getIdSucursal());

    $bar = new CompraSucursal();
    $bar->setFecha($hoy);

    $compras = CompraSucursalDAO::byRange($foo, $bar);

    $total_compras = 0;

    //las compras
    foreach ($compras as $i) {
        $total_compras += $i->getTotal();
    }

    /*     * **** Este Corte ********** */
    $esteCorte->setTotalCompras(
            $total_compras
    );


    /*     * *****************************************
     * Total Compras Abonado
     * total de abonado en compras
     * **************************************** */
    $foo = new PagosCompra();
    $foo->setFecha($fecha);
    //$foo->setIdSucursal($sucursal->getIdSucursal());

    $bar = new PagosCompra();
    $bar->setFecha($hoy);

    $compras = PagosCompraDAO::byRange($foo, $bar);

    $total_compras_pagadas = 0;

    //las compras
    foreach ($compras as $i) {
        $total_compras_pagadas += $i->getMonto();
    }

    /*     * **** Este Corte ********** */
    $esteCorte->setTotalComprasAbonado(
            $total_compras_pagadas
    );




    /*     * *****************************************
     * Total Gastos
     * total de gastos con saldo o sin salgo
     * **************************************** */
    $foo = new Gastos();
    $foo->setFecha($fecha);
    $foo->setIdSucursal($sucursal->getIdSucursal());

    $bar = new Gastos();
    $bar->setFecha($hoy);

    $gastos = GastosDAO::byRange($foo, $bar);

    $total_gastos = 0;

    foreach ($gastos as $g) {
        $total_gastos += $g->getMonto();
    }

    /*     * **** Este Corte ********** */
    $esteCorte->setTotalGastos(
            $total_gastos
    );


    /*     * **** Este Corte ********** */
    $esteCorte->setTotalGastosAbonado(0);


    /*     * **** Este Corte ********** */
    $esteCorte->setTotalIngresos(0);


    /*     * **** Este Corte ********** */
    $esteCorte->setTotalGananciaNeta(0);
?>
<table style="margin-top:25px; width:100%" border=1>
    <tr>
        <td>total_ventas</td>
        <td>total de activo realizado en ventas para esta sucursal incluyendo ventas a credito y ventas a contado aunque no esten saldadas</td>
        <td><?php echo moneyFormat($esteCorte->getTotalVentas()); ?></td>
    </tr>
    <tr>
        <td>total_ventas_abonado</td>
        <td>total de efectivo adquirido gracias a ventas, incluye ventas a contado y los abonos de las ventas a credito</td>
        <td><?php echo moneyFormat($esteCorte->getTotalVentasAbonado()); ?></td>
    </tr>
    <tr>
        <td>total_ventas_saldo</td>
        <td>total de dinero que se le debe a esta sucursal por ventas a credito</td>		
        <td><?php echo moneyFormat($esteCorte->getTotalVentasSaldo()); ?></td>
    </tr>
    <tr>
        <td>total_compras</td>
        <td>total de gastado en compras</td>		
        <td><?php echo moneyFormat($esteCorte->getTotalCompras()); ?></td>
    </tr>
    <tr>
        <td>total_compras_abonado</td>
        <td>total de abonado en compras</td>		
        <td><?php echo moneyFormat($esteCorte->getTotalComprasAbonado()); ?></td>
    </tr>			
    <tr>
        <td>total_gastos</td>
        <td>total de gastos con saldo o sin salgo</td>		
        <td><?php echo moneyFormat($esteCorte->getTotalGastos()); ?></td>
    </tr>
    <tr>
        <td>total_ingresos</td>
        <td>total de ingresos para esta sucursal desde el ultimo corte</td>		
        <td><?php echo moneyFormat($esteCorte->getTotalIngresos()); ?></td>
    </tr>
    <tr>
        <td>total_ganancia_neta</td>
        <td>calculo de ganancia neta</td>		
        <td><?php echo moneyFormat($esteCorte->getTotalGananciaNeta()); ?></td>
    </tr>				
</table>


<h4><input type="button" 
           value= "Realizar corte" 
           onClick="window.location = 'sucursales.php?action=realizarCorte&id_sucursal=<?php echo $sucursal->getIdSucursal(); ?>';">
</h4>


<?php
if (POS_ENABLE_GMAPS) {
    ?><script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script><?php
}
