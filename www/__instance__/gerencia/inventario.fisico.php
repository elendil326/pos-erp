<?php
define("BYPASS_INSTANCE_CHECK", false);

require_once("../../../server/bootstrap.php");

$page = new GerenciaComponentPage();

$page->addComponent(new TitleComponent("Inventario Fisico"));
$page->addComponent("<p>Este apartado sirve para recalcular la cantidad de producto que hay por Empresa, Almacen y Lote.</p>");
$page->partialRender();


$company = ProductosController::listarProductosLote();
?>

<?php

$productos = "var productos = [";

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
                    <th>Fisico</th>
                    <th>Unidad</th>
                </tr>
                <?php
                
                $index = 0;
                
                foreach ($lote->lotes_producto as $lote_producto) {
                    ?>



                    <tr>
                        <td><?php echo $lote_producto->id_producto; ?></td>    
                        <td><?php echo $lote_producto->id_lote; ?></td>    
                        <td><?php echo $lote_producto->codigo; ?></td>                        
                        <td><?php echo $lote_producto->nombre; ?></td>                                                                    
                        <td><div id="<?php echo "{$lote_producto->id_producto}-{$lote_producto->id_lote}-{$lote_producto->id_unidad}"; ?>"><?php echo $lote_producto->cantidad; ?></div></td>                        
                        <td><div style ="<?php echo $lote_producto->cantidad != $lote_producto->recalculo ? "color:red;" : ""; ?>"><?php echo $lote_producto->recalculo; ?><div></td>                                                 
                        <td><input type="text" id ="cantidad-<?php echo $index;?>" value="<?php echo $lote_producto->cantidad;?>" /></td>                        
                        <td><?php echo $lote_producto->unidad; ?></td>
                    </tr>

                    <?php
                    
                    $productos .= "{index : {$index}, id_producto : {$lote_producto->id_producto}, id_unidad : {$lote_producto->id_unidad}, id_lote : {$lote_producto->id_lote}, cantidad : {$lote_producto->cantidad}},";
                    $index++;
                }
                ?>
            </table>
            <?php
        }
        ?>        

            <div><center><input type="button" value="Realizar Inventario" onClick="actualizarInventario();"/></center></div>

        <?php
    }
}

if( substr($productos, -1) == "," ){
    $productos = substr($productos, 0, -1);
}


$productos .= "];";

?>
<script>      
    
    var actualizarInventario = function(){
        
        <?php
            echo $productos;
        ?>
        
        var inventario = [];
        
        var cantidad = null;
        
        for(var i = 0; i < productos.length; i++){
            
            cantidad = Ext.get("cantidad-" + productos[i].index).getValue();
            
            if( cantidad != productos[i].cantidad ){
                productos[i].cantidad = cantidad;;
            }
            
            inventario.push(productos[i]);
            
        }
        
        POS.API.POST(
        "api/inventario/fisico", 
        {
            inventario : Ext.JSON.encode(inventario)
        } , 
        {
            callback:function(response){
                  
                console.log("response : ", response );
                  
                productos = Ext.JSON.decode(response.productos);                                    
                
                if(producto.status == "ok"){
                    Ext.Msg.alert("Inventario Fisico","El c&aacute;lculo se realiz&oacute; correctamente");
                }                                                                 
                  
            }
        }
    );
    
        
    }
    
</script>

<?php
$page->render();