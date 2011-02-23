<script>
	jQuery("#MAIN_TITLE").html( "Detalles de Ingreso");
</script><?php

require_once("controller/sucursales.controller.php");
require_once("model/ingresos.dao.php");

$detalles = detalleIngreso($_REQUEST['id']);
if($detalles==null){echo "Este ingreso no existe";}else{
$ingreso = $detalles[0];
?><h2>Detalles</h2>


<table cellspacing="2" cellpadding="2" border=0 style="width:100%">
    <tr>
        <td ><b>ID Gasto</b></td>
        <td><?php echo $ingreso['id_ingreso'] ?></td>
        <td><b>ID Sucursal</b></td>
        <td><?php echo $ingreso['id_sucursal']; ?></td>
    </tr>

    <tr>
        <td><b>ID Usuario</b></td>
        <td><?php echo $ingreso['id_usuario']; ?></td>
		    <td><b>Concepto</b></td>
	        <td><?php echo $ingreso['concepto']; ?></td>
    </tr>

   <tr>
        <td><b>Fecha</b></td>
        <td><?php echo toDate($ingreso['fecha']); ?></td>
        <td><b>Fecha Ingreso</b></td>
        <td><?php echo toDate($ingreso['fecha_ingreso']); ?></td>
    </tr>

    <tr>
        <td><b>Nota</b></td>
        <td><?php echo $ingreso['nota']; ?></td>
        <td><b>Monto</b></td>
        <td><?php echo moneyFormat($ingreso['monto']); ?></td>
    </tr>

</table>
<? }?>