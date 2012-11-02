<?php
define("BYPASS_INSTANCE_CHECK", false);

require_once("../../../server/bootstrap.php");

$page = new GerenciaComponentPage();

$page->addComponent(new TitleComponent("Recalcular Existencias"));
$page->addComponent("<p>Este apartado sirve para recalcular la cantidad de producto que hay por Empresa, Almacen y Lote.</p>");
$page->partialRender();


$company = ProductosController::listarProductosLote();
?>

<div><center><input type="button" value="Recalcular Todo" onClick="recalculaTodo();"/></center></div>

<?php

$productos = "[";

foreach ($company->empresas as $empresa) {
    ?>

    <h1>Empresa : </h1> 
    <h2>-<?php echo utf8_decode($empresa->nombre); ?>-</h2>

    <?php
    foreach ($empresa->almacenes as $almacen) {

        foreach ($almacen->lotes as $lote) {
            ?>
            <h3><?php echo $almacen->nombre . " -> " . $lote->folio ?></h3> 
            <table>

                <tr>
                    <th>Id</th>
                    <th>Id Lote</th>
                    <th>Codigo</th>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Recalculo</th>
                    <th>Unidad</th>
                    <th></th>
                </tr>
                <?php
                foreach ($lote->lotes_producto as $lote_producto) {
                    ?>



                    <tr>
                        <td><?php echo $lote_producto->id_producto; ?></td>    
                        <td><?php echo $lote_producto->id_lote; ?></td>    
                        <td><?php echo $lote_producto->codigo; ?></td>                        
                        <td><?php echo $lote_producto->nombre; ?></td>                                                                    
                        <td><div id="<?php echo "{$lote_producto->id_producto}-{$lote_producto->id_lote}-{$lote_producto->id_unidad}"; ?>"><?php echo $lote_producto->cantidad; ?></div></td>                        
                        <td><?php echo $lote_producto->recalculo; ?></td>                     
                        <td><?php echo $lote_producto->unidad; ?></td>    
                        <td><input type="button" value="Recalcular" onClick="recalcula(<?php echo "{$lote_producto->id_producto}, {$lote_producto->id_lote}, {$lote_producto->id_unidad}" ?>)"/></td>                        
                    </tr>

                    <?php
                    
                    $productos .= "{id_producto : {$lote_producto->id_producto}, id_unidad : {$lote_producto->id_unidad}, id_lote : {$lote_producto->id_lote}},";
                    
                }
                ?>
            </table>
            <?php
        }
        ?>        




        <?php
    }
}

if( substr($productos, -1) == "," ){
    $productos = substr($productos, 0, -1);
}


$productos .= "]";

?>
<script>
    
    var recalcula = function(id_producto, id_lote, id_unidad){
        
        POS.API.POST(
        "api/inventario/recalcular_existencias", 
        {
            productos : Ext.JSON.encode(
            [
                {
                    id_producto:id_producto, 
                    id_unidad:id_unidad, 
                    id_lote:id_lote
                }
            ]
        ) 
        } , 
        {
            callback:function(response){
                  
                console.log("response : ", response );
                  
                productos = Ext.JSON.decode(response.productos);                                    
                  
                Ext.get(id_producto + "-" + id_lote + "-" + id_unidad).update(productos[0].cantidad);
                  
                Ext.Msg.alert("Recalculo de Existencias","El c&aacute;lculo se realiz&oacute; correctamente");
                  
            }
        }
    );
        
    };
    
    var recalculaTodo = function(){
        
        POS.API.POST(
        "api/inventario/recalcular_existencias", 
        {
            productos : Ext.JSON.encode(<?php echo $productos;?>)
        } , 
        {
            callback:function(response){
                  
                console.log("response : ", response );
                  
                productos = Ext.JSON.decode(response.productos);                                    
                
                for (var i = 0; i < productos.length; i++)
                {
                    Ext.get(productos[i].id_producto + "-" + productos[i].id_lote + "-" + productos[i].id_unidad).update(productos[i].cantidad);
                }                                  
                  
                Ext.Msg.alert("Recalculo de Existencias","El c&aacute;lculo se realiz&oacute; correctamente");
                  
            }
        }
    );
    
        
    }
    
</script>

<?php
$page->render();