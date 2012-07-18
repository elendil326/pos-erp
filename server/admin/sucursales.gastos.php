<script>
	jQuery("#MAIN_TITLE").html( "Detalles de gasto");
</script><?php

require_once("controller/sucursales.controller.php");
require_once("model/gastos.dao.php");

$detalles = detalleGasto($_REQUEST['id']);
if($detalles==null){echo "Este gasto no existe";}else{
$gasto = $detalles[0];
?><h2>Detalles</h2>


<table cellspacing="2" cellpadding="2" border=0 style="width:100%">
    <tr>
        <td ><b>ID Gasto</b></td>
        <td><?php echo $gasto['id_gasto'] ?></td>
        <td><b>ID Sucursal</b></td>
        <td><?php echo $gasto['id_sucursal']; ?></td>
    </tr>

    <tr>
        <td><b>ID Usuario</b></td>
        <td><?php echo $gasto['id_usuario']; ?></td>
		    <td><b>Concepto</b></td>
	        <td><?php echo $gasto['concepto']; ?></td>
    </tr>

   <tr>
        <td><b>Fecha</b></td>
        <td><?php echo toDate($gasto['fecha']); ?></td>
        <td><b>Fecha Ingreso</b></td>
        <td><?php echo toDate($gasto['fecha_ingreso']); ?></td>
    </tr>

    <tr>
        <td><b>Folio</b></td>
        <td><?php echo $gasto['folio']; ?></td>
        <td><b>Monto</b></td>
        <td><?php echo moneyFormat($gasto['monto']); ?></td>
    </tr>

    <tr>
        <td><b>Nota</b></td>
        <td><?php echo $gasto['nota']; ?></td>
    </tr>



</table>
<? }?>