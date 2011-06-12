<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once("controller/inventario.controller.php");
require_once("controller/sucursales.controller.php");

require_once('model/actualizacion_de_precio.dao.php');
require_once('model/compra_proveedor.dao.php');
require_once('model/compra_proveedor_flete.dao.php');
require_once('model/compra_proveedor_fragmentacion.dao.php');
require_once('model/proveedor.dao.php');
require_once('model/inventario.dao.php');

$_REQUEST["id_compra_proveedor"];

$compra_proveedor = CompraProveedorDAO::getByPK($_REQUEST["id_compra_proveedor"]);

$compra_proveedor_flete = CompraProveedorFleteDAO::getByPK($compra_proveedor->getIdCompraProveedor());

$proveedor = ProveedorDAO::getByPK($compra_proveedor->getIdProveedor());

$detalle_compra_proveedor = DetalleCompraProveedorDAO::search(new DetalleCompraProveedor(array("id_compra_proveedor"=>$compra_proveedor->getIdProveedor())));

$compra_proveedor_fragmentacion = CompraProveedorFragmentacionDAO::search(new CompraProveedorFragmentacion(array("id_compra_proveedor"=>$compra_proveedor->getIdProveedor())));

?>

<h2>Datos del Embarque</h2>
<table border="0" cellspacing="5" cellpadding="5" style="width:100%">                               
    <tr>
        <td><b>Folio</b></td><td><?php echo $compra_proveedor->getFolio(); ?></td>
        <td><b>Numero de Viaje</b></td><td><?php echo $compra_proveedor->getNumeroDeViaje(); ?></td>
    </tr>
    <tr>
        <td><b>Fecha de Origen</b></td><td><?php echo toDate($compra_proveedor->getFechaOrigen()); ?></td>
        <td><b>Fecha de Recepcion</b></td><td><?php echo toDate($compra_proveedor->getFecha()); ?></td>
    </tr>
    <tr>
        <td><b>Conductor</b></td><td><?php echo $compra_proveedor_flete->getChofer(); ?></td>
        <td><b>Placas</b></td><td><?php echo $compra_proveedor_flete->getPlacasCamion(); ?></td>
    </tr>
    <tr>        
        <td><b>Peso de Origen</b></td><td><?php echo $compra_proveedor->getPesoOrigen(); ?> KG</td>
        <td><b>Peso Recibido</b></td><td><?php echo $compra_proveedor->getPesoRecibido(); ?> KG</td>
    </tr>
    <tr>        
        <td><b>Numero de Arpillas</b></td><td><?php echo $compra_proveedor->getArpillas(); ?></td>
        <td><b>Peso por Arpilla</b></td><td><?php echo $compra_proveedor->getPesoPorArpilla(); ?> KG</td>

    </tr>
    <tr>
        <td><b>Merma por Arpilla</b></td><td><?php echo $compra_proveedor->getMermaPorArpilla(); ?> KG</td>
        <td><b>Calidad</b></td><td><?php echo $compra_proveedor->getCalidad(); ?></td>
    </tr>
    <tr>        
        <td><b>Proveedor</b></td><td><?php echo $proveedor->getNombre(); ?></td>
        <td><b>Productor</b></td><td><?php echo $compra_proveedor->getProductor(); ?></td>
    </tr>
    <tr>        
        <td><b>Total Origen</b></td><td><?php echo moneyFormat($compra_proveedor->getTotalOrigen()); ?></td>
        
    </tr>
</table>

<h2>Productos que Componen el Embarque</h2>

<table border="0" cellspacing="5" cellpadding="5" style="width:100%">  
    
<?php

    echo "<tr align = center><th><b>Producto </b></th><th><b>Variedad</b></th><th><b>Arpillas</b></td><th><b>Peso Total</b></th><th><b>Precio por KG</b></th><th><b>Sitio de Descarga</b></th></tr>";

    foreach($detalle_compra_proveedor as $detalle_producto){
        
        $producto = InventarioDAO::getByPK($detalle_producto->getIdProducto());
        
        $inventario_maestro = InventarioMaestroDAO::getByPK($detalle_producto->getIdProducto(), $detalle_producto->getIdCompraProveedor());
        
        $sucursal = SucursalDAO::getByPK($inventario_maestro->getSitioDescarga());
        
        echo "<tr>";
        echo "  <td>{$producto->getDescripcion()}</td><td>{$detalle_producto->getVariedad()}</td><td>{$detalle_producto->getArpillas()}</td><td>{$detalle_producto->getKg()} KG</td><td>" . moneyFormat($detalle_producto->getPrecioPorKg()) . "</td><td>{$sucursal->getDescripcion()}</td>";
        echo "</tr>";
    }
   

?>
    
</table>

<h2>Movimientos Efectuados</h2>

<table border="0" cellspacing="5" cellpadding="5" style="width:100%">  
    
<?php

    echo "<tr align = center><th><b>Producto </b></th><th><b>Fecha</b></th><th><b>Cantidad</b></td><th><b>Procesado</b></th><th><b>Precio</b></th></tr>";

    foreach($compra_proveedor_fragmentacion as $fragmentacion){
        
        $producto = InventarioDAO::getByPK($fragmentacion->getIdProducto());
                
        echo "<tr>";
        echo "  <td>{$producto->getDescripcion()}</td><td>" . toDate($fragmentacion->getFecha()) . "</td><td>{$fragmentacion->getCantidad()}</td><td>" . ($fragmentacion->getProcesada() ? "Si" : "No") . "</td><td>" . moneyFormat($fragmentacion->getPrecio()) . "</td>";
        echo "</tr>";
        echo "<tr>";
        echo "  </td><td colspan = '4' style = 'font-size:12px;'>{$fragmentacion->getDescripcion()}</td>";
        echo "</tr>";
        
        
    }
   

?>
    
</table>
