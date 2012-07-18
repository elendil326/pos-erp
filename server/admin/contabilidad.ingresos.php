<?php
require_once("model/ingresos.dao.php");
require_once("controller/clientes.controller.php");

require_once('model/corte.dao.php');

function renderUsuario($var) {
    return UsuarioDAO::getByPK($var)->getNombre();
}
?>

<script>
    
    jQuery("#MAIN_TITLE").html("INGRESOS");
	
    function doNuevoIngreso(){

        //recuperacion
        var g = {
            concepto	: jQuery("#nuevoIngreso-Concepto").val(),
            monto 		: jQuery("#nuevoIngreso-Monto").val(),
            fecha		: jQuery("#nuevoIngreso-Fecha").val(),
            nota 		: jQuery("#nuevoIngreso-Nota").val()
        };		
                
        //validacion
        if(isNaN(g.monto) || g.monto.length == 0){
            window.scroll(0,0);                         
            jQuery("#ajax_failure").html("El monto no es un numero.").show();
            return;
        }
        
        if(g.concepto.length < 5){
            window.scroll(0,0);                         
            jQuery("#ajax_failure").html("Concepto demasiado corto.").show();
            return;
        }

        console.log(g);

        //hacer ajaxaso
        jQuery.ajaxSettings.traditional = true;

        jQuery("#submitIngreso").fadeOut("slow",function(){
            jQuery("#loader").fadeIn();

            jQuery.ajax({
                url: "../proxy.php",
                data: { 
                    action : 603, 
                    data : jQuery.JSON.encode( g )
                },
                cache: false,
                success: function(data){
                    try{
                        response = jQuery.parseJSON(data);
                        //console.log(response, data.responseText)
                    }catch(e){

                        jQuery("#loader").fadeOut('slow', function(){
                            jQuery("#submitIngreso").fadeIn();
                            window.scroll(0,0);                         
                            jQuery("#ajax_failure").html("Error en el servidor, porfavor intente de nuevo").show();
                            jQuery("#submitIngreso").fadeIn();
                        });                
                        return;                    
                    }


                    if(response.success === false){

                        jQuery("#loader").fadeOut('slow', function(){
                            //jQuery("#submitIngreso").fadeIn();    
                            window.scroll(0,0);
                            try{
                                jQuery("#ajax_failure").html(response.reason).show();
                            }catch(e){
                                jQuery("#ajax_failure").html("Error inesperado").show();
                            }

                            jQuery("#submitIngreso").fadeIn();
                        });                
                        return ;
                    }

                    var msg = "Gasto creado exitosamente.";
                    window.location = "contabilidad.php?action=ingresos&success=true&reason=" + msg;

                }
            });
        });
    }
</script>

<?php
$ingresos = IngresosDAO::getAll(1, 10, 'fecha', 'desc');

$header = array(
    "concepto" => "Concepto",
    "monto" => "Monto",
    "fecha" => "Fecha",
    "fecha_ingreso" => "Fecha Ingreso",
    "nota" => "Nota",
);
?>

<h2>Nuevo Ingreso</h2>
<table border="0" cellspacing="5" cellpadding="5">

    <tr>
        <td>Concepto</td><td><input type="text" size="40" id="nuevoIngreso-Concepto"/></td>  
        <td>Monto</td><td><input type="text" size="40" id="nuevoIngreso-Monto"/></td>  </tr>



    <tr><td>Fecha</td><td><input type="text" size="40" id="nuevoIngreso-Fecha" placeholder="dd/mm/aaaa" ></td><td>Nota</td><td><textarea cols ="40" rows ="6" id ="nuevoIngreso-Nota"></textarea></td></tr>                

</table>

<div id="loader" 		style="display: none;" align="center"  >
		Procesando <img src="../media/loader.gif">
</div>
<div id="submitIngreso">
    <h4><input type="button" value="CREAR EL NUEVO INGRESO" onClick="doNuevoIngreso()"></h4>
</div>

<h2>Egresos desde el ultimo corte</h2>


<?php
if (POS_MULTI_SUCURSAL) {
    $sucursal = SucursalDAO::getByPK($_REQUEST['id']);
} else {
    $sucursal = SucursalDAO::getByPK(0);
}


$flujo = array();


/* * *****************************************
 * Fecha desde el ultimo corte
 * ****************************************** */
$corte = new Corte();
$corte->setIdSucursal($sucursal->getIdSucursal());

$cortes = CorteDAO::getAll(1, 1, 'fecha', 'desc');



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

/* * *****************************************
 * Buscar los ingresos
 * Buscar todos los ingresos desde la fecha inicial
 * **************************************** */
$foo = new Ingresos();
$foo->setFecha($fecha);
$foo->setIdSucursal($sucursal->getIdSucursal());

$bar = new Ingresos();
$bar->setFecha($hoy);

$ingresos = IngresosDAO::byRange($foo, $bar);


foreach ($ingresos as $i) {
    array_push($flujo, array(
        "concepto" => $i->getConcepto(),
        "monto" => $i->getMonto(),
        "usuario" => $i->getIdUsuario(),
        "fecha" => $i->getFecha(),
        "nota" => $i->getNota()
    ));
}

/* * *****************************************
 * DIBUJAR LA GRAFICA
 * ****************************************** */
$header = array(
    "concepto" => "Concepto",
    "nota" => "Nota",
    "usuario" => "Usuario",
    "fecha" => "Fecha",
    "monto" => "Monto");

function renderMonto($monto) {
    if ($monto < 0)
        return "<div style='color:red;'>" . moneyFormat($monto) . "</div>";

    return "<div style='color:green;'>" . moneyFormat($monto) . "</div>";
}

function cmpFecha($a, $b) {
    if ($a["fecha"] == $b["fecha"]) {
        return 0;
    }
    return ($a["fecha"] < $b["fecha"]) ? -1 : 1;
}

usort($flujo, "cmpFecha");

$tabla = new Tabla($header, $flujo);
$tabla->addColRender("usuario", "renderUsuario");
$tabla->addColRender("fecha", "toDate");
$tabla->addColRender("monto", "renderMonto");
$tabla->addNoData("<div class='blue-rounded' >No hay ingresos de efectivo.</div>");
$tabla->render();

$enCaja = 0;

foreach ($flujo as $f) {
    $enCaja += $f['monto'];
}

if ($enCaja != 0) {
    ?>
    <div align=center style="margin-top: 15px">
        <div  class='blue-rounded' style='width: 300px;'>Ingresos totales <?php echo moneyFormat(abs($enCaja)); ?> pesos </div>
    </div>
    <?php
}




