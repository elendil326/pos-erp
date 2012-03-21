<?php
define("BYPASS_INSTANCE_CHECK", false);

require_once("../../../../server/bootstrap.php");

$page = new GerenciaComponentPage();

$page->addComponent(new TitleComponent("Nueva Tarifa"));
$page->addComponent(new MessageComponent("Crea una nueva tarifa en el sistema"));

$page->partialRender();
?>

<form>

    <table style ="width:100%;">
        <tr>
            <td>
                Nombre :    
            </td>
            <td>
                <input type = "text" name = "nombre_tarifa" id = "nombre_tarifa" value = "" style ="width:100%;"/>
            </td>
            <td>
                Moneda :    
            </td>
            <td>
                <select name = "moneda_tarifa" id = "moneda_tarifa" onChange = "" >
                    <?php
                    $options = "<option value = null>-------</option>";

                    foreach (ServicioDAO::getAll() as $servicio) {
                        $options .= "<option value = \"{$servicio->getIdServicio()}-{$servicio->getMetodoCosteo()}-{$servicio->getCostoEstandar()}-{$servicio->getPrecio()}\">{$servicio->getNombreServicio()}</option>";
                    }

                    echo $options;
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                Tipo :    
            </td>
            <td>
                <select name = "tipo_tarifa" id = "tipo_tarifa" onChange = "" >
                    <?php
                    $options = "<option value = null>-------</option>";

                    foreach (ServicioDAO::getAll() as $servicio) {
                        $options .= "<option value = \"{$servicio->getIdServicio()}-{$servicio->getMetodoCosteo()}-{$servicio->getCostoEstandar()}-{$servicio->getPrecio()}\">{$servicio->getNombreServicio()}</option>";
                    }

                    echo $options;
                    ?>
                </select>
            </td>
            <td>
                Tarifa default del sistema:    
            </td>
            <td>
                <input type="Radio" name="default_tarifa" value="true"> S&iacute;
                <input type="Radio" name="default_tarifa" value="false" checked> No
            </td>
        </tr>
    </table>

</form>

<?php
$page->addComponent(new TitleComponent("Versi&oacute;n", 2));
$page->addComponent(new MessageComponent("Cuando se crea una nueva tarifa automaticamente se crea una version por default, indique apartir de que fechas entrara en vigor la version de la tarifa, si no se indica la fecha de inicio, se considera que entra en vigor inmediatamente, si no se indica  una fecha de finalizaci&oacute;n se considera que no tendra vigencia"));

$page->partialRender();
?>

<form>
    <table style ="width:100%;">
        <tr>
            <td>
                Fecha Inicio :    
            </td>
            <td>
                <div id ="fecha_inicio_tarifa">&nbsp;</div>
            </td>
            <td>
                Fecha Vigencia :    
            </td>
            <td>
                <div id ="fecha_fin_tarifa">&nbsp;</div>
            </td>
        </tr>
    </table>
</form>

<div id ="btn_nueva_regla"class="POS Boton" onClick = "showNuevaRegla()">Asignar Nueva Regla</div>

<div name ="form_nueva_regla" id ="form_nueva_regla" style ="margin-top: 10px;">
    <form>
        <table style ="width:100%;">
            <tr>
                <td>
                    Nombre :    
                </td>
                <td colspan ="3">
                    <input type = "text" name = "nombre_regla" id = "nombre_regla" value = "" style ="width:100%;"/>
                </td>

            </tr>
            <tr>
                <td>
                    Secuencia :    
                </td>
                <td>
                    <input type = "text" name = "secuencia_regla" id = "secuencia_regla" value = "" style ="width:100%;"/>
                </td>
                <td>
                    Cantidad Min :    
                </td>
                <td>
                    <input type = "text" name = "cantidad_minima_regla" id = "cantidad_minima_regla" value = "" style ="width:100%;"/>
                </td>
            </tr>  
            <tr>
                <td colspan = "4"  align="right" style = "border-width:0px;">
                    <input class="POS Boton OK" style = "position:relative; float:right;" type = "button" value = "Aceptar" onClick = "nuevaOrdenServicio()" /> <input class="POS Boton" style = "position:relative; float:right;" type = "reset" value = "Cancelar" />
                </td>
            </tr> 
        </table>
    </form>
</div>
<?php
$page->addComponent(new TitleComponent("Reglas", 2));
$page->addComponent(new MessageComponent("Listado de reglas que componen esta versi&oacute;n"));

$page->partialRender();
?>

<table name ="table_reglas" id ="table_reglas" style ="width:100%; margin-top: 10px;">
    <tr>
        <th> Secuencia </th>
        <th> Nombre </th>
        <th> Producto </th>
        <th> Categor&iacute;a </th>
        <th> Servicio </th>
        <th> Cant Min </th>
    </tr>
</table>

<script>

    var showNuevaRegla = function(){
        new Ext.fx.Anim({
            target: Ext.get('btn_nueva_regla'),
            duration: 1000,
            from: {
                opacity: 1
            },
            to: {
                opacity: 0,
                height: 0, // end width 300
                cursor:'help'
            },listeners: {
                afteranimate: {
                    element: 'el', //bind to the underlying el property on the panel
                    fn: function(){ 
                        Ext.get('form_nueva_regla').fadeOut();
                    }
                },
                beforeanimate: {
                    element: 'el', //bind to the underlying el property on the panel
                    fn: function(){ 
                        Ext.get('form_nueva_regla').fadeOut();
                    }
                }
            }
        });
    }

    var regla = function(config){
                          
        this.nombre = config.nombre; 
        this.secuencia = config.secuencia;
        this.cantidad_minima = config.cantidad_minima? config.cantidad_minima : null;
        this.id_clasificacion_producto = config.id_clasificacion_producto? config.id_clasificacion_producto : null;
        this.id_clasificacion_servicio = config.id_clasificacion_servicio? config.id_clasificacion_servicio : null;
        this.id_paquete = config.id_paquete? config.id_paquete : null;
        this.id_producto = config.id_producto? config.id_producto : null;
        this.id_servicio = config.id_servicio? config.id_servicio : null;
        this.id_tarifa = config.id_tarifa? config.id_tarifa : null;
        this.id_unidad = config.id_unidad? config.id_unidad : null;
        this.margen_max = config.margen_max? config.margen_max : 0;
        this.margen_min = config.margen_min? config.margen_min : 0; 
        this.metodo_redondeo = config.metodo_redondeo? config.metodo_redondeo : 0; 
        this.porcentaje_utilidad = config.porcentaje_utilidad? config.porcentaje_utilidad : 0; 
        this.utilidad_neta = config.utilidad_neta? config.utilidad_neta : 0; 
        
    }

    var reglas = function(){
    
        this.store = [];
            
        this.index = 0;    
            
        this.addRegla = function(regla){            
            this.store[this.index] = regla;
            this.index++;
        }
    
        this.render = function(){
            Ext.Array.forEach( this.store, function(c){
                //agregar un nuevo hijo a la tabla
            });
        }
    
    }
    
    var fecha_inicio = Ext.create('Ext.form.field.Date', {
        name : 'fecha_inicio',         
        style : {
            marginTop : '-10px'
        },
        anchor: '100%',       
        value: new Date(),  // defaults to today           
        renderTo: "fecha_inicio_tarifa"
    });
        
    var fecha_fin = Ext.create('Ext.form.field.Date', {
        name : 'fecha_fin',         
        style : {
            marginTop : '-10px'
        },
        anchor: '100%',       
        value: new Date(),  // defaults to today           
        renderTo: "fecha_fin_tarifa"
    });

</script>

<?php
$page->render();
