<?php
define("BYPASS_INSTANCE_CHECK", false);

require_once("../../../../server/bootstrap.php");

$page = new GerenciaComponentPage();

$page->addComponent(new TitleComponent("Nueva Tarifa"));
$page->addComponent(new MessageComponent("Crea una nueva tarifa en el sistema"));

$page->partialRender();
?>

<form id ="form_tarifa">

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
                <select name = "id_moneda_tarifa" id = "id_moneda_tarifa" onChange = "" >
                    <?php
                    //$options = "<option value = null>-------</option>";
                    $options = "";
                    foreach (MonedaDAO::getAll() as $moneda) {
                        $options .= "<option value = \"{$moneda->getIdMoneda()}\">{$moneda->getNombre()}</option>";
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
                    <option value = "venta">Venta</option>                    
                    <option value = "compra">Compra</option>                    
                </select>
            </td>
            <td>
                Tarifa default del sistema:    
            </td>
            <td>
                <input type="Radio" name="default_tarifa" value="true" /> S&iacute;
                <input type="Radio" name="default_tarifa" value="false" checked /> No
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

<?php
$page->addComponent(new TitleComponent("Nueva Regla", 2));
$page->addComponent(new MessageComponent("Ingrese los valores para crer una nuava regla"));

$page->partialRender();
?>


<form name ="form_nueva_regla" id ="form_nueva_regla">
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
                <input id ="btn_nueva_regla" class="POS Boton OK" style = "position:relative; float:right;" type = "button" value = "Aceptar" /> <input  id ="btn_cancelar_regla" class="POS Boton" style = "position:relative; float:right;" type = "reset" value = "Cancelar" />
            </td>
        </tr> 
    </table>
</form>

<?php
$page->addComponent(new TitleComponent("Reglas", 2));
$page->addComponent(new MessageComponent("Listado de reglas que componen esta versi&oacute;n"));

$page->partialRender();
?>

<div id ="content_table_rules">
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
</div>

<table style ="width:100%; margin-top: 50px;">
    <tr>
        <td style = "border-width:0px; background:#EDEFF4;"  valign="middle">
            <input class="POS Boton OK" style = "left : 250px; width:200px;" type = "button" value = "Crear Tarifa" onClick = "crearNuevaTarifa();" />
        </td>
    </tr>
</table>

<script>
          
    var btn_nueva_regla = Ext.get('btn_nueva_regla');          

    btn_nueva_regla.on('click',function(){                          
        
        var nombre = Ext.get('nombre_regla').getValue().replace(/^\s+|\s+$/g,"");
        var secuencia = Ext.get('secuencia_regla').getValue().replace(/^\s+|\s+$/g,""); 
        var cantidad_minima = Ext.get('cantidad_minima_regla').getValue().replace(/^\s+|\s+$/g,"");
        
        var error = "";
        
        if(cantidad_minima == ""){
            cantidad_minima = 1;
        }
        
        if(!nombre.length){
            error += "Verifique el nombre de la regla.<br/>";
        }
        
        if(isNaN(secuencia) || !secuencia.length){
            error += "Indique un numero de secuencia valido.<br/>";
        }
        
        if(isNaN(cantidad_minima)){
            error += "Indique un numero de cantidad minima valido.<br/>";
        }                
        
        if(error.length > 0){
            
            Ext.MessageBox.show({
                title: "Nueva regla",
                msg: error,
                buttons: Ext.MessageBox.OK,
                icon: Ext.MessageBox.ERROR
            });
            
            return;
        }             
        
        r.addRegla(Ext.get('content_table_rules'), new regla({
            nombre:nombre,
            secuencia:secuencia,
            cantidad_minima:cantidad_minima
        }));                
        
    });

    var regla = function(config){
                          
        this.nombre = config.nombre; 
        this.secuencia = config.secuencia;
        this.cantidad_minima = config.cantidad_minima? config.cantidad_minima : 0;
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
            
        this.addRegla = function(element, regla){  
            
            var error = this.review(regla);
            
            if(error.length){
                Ext.MessageBox.show({
                    title: "Nueva regla",
                    msg: error,
                    buttons: Ext.MessageBox.OK,
                    icon: Ext.MessageBox.ERROR
                });
            
                return;
            }
            
            Ext.get('form_nueva_regla').dom.reset();
            
            this.store[this.index] = regla;
            this.index++;
            this.render(element);
        }
    
        this.render = function(element){
            
            var html = "";
        
            html += "<table name =\"table_reglas\" style =\"width:100%; margin-top: 10px;\">";
            html += "    <tr>";
            html += "        <th> Secuencia </th>";
            html += "        <th> Nombre </th>";
            html += "        <th> Producto </th>";
            html += "        <th> Categor&iacute;a </th>";
            html += "        <th> Servicio </th>";
            html += "        <th> Cant Min </th>";
            html += "    </tr>";                        
            
            Ext.Array.forEach( this.store, function(c){
                html += "    <tr>";
                html += "        <td> " + c.secuencia + " </td>";
                html += "        <td> " + c.nombre + " </td>";
                html += "        <td> " + (c.id_producto == null? "-":c.id_producto) + " </td>";
                html += "        <td> " + (c.id_clasificacion_producto == null? "-":c.id_clasificacion_producto) + " </td>";
                html += "        <td> " + (c.id_clasificacion_servicio == null? "-":c.id_clasificacion_servicio) + " </td>";
                html += "        <td> " + c.cantidad_minima + " </td>";
                html += "    </tr>";
            });
            
            html += "</table>";
            
            element.update(html);
            
        }
        
        this.review = function(regla){
        
            var error = "";
        
            Ext.Array.forEach( this.store, function(c){

                error += regla.secuencia < 1 ? "Indique un numero valido de secuencia.<br/>":"";

                error += c.secuencia == regla.secuencia ? "Ya se cuenta con ese numero de secuencia.<br/>":"";
                error += c.nombre == regla.nombre ? "Ya existe una regla con ese nombre.<br/>":"";
                
            });
            
            return error;
        
        }                
        
        this.getSize = function(){
            return this.store.length;
        }
    
        this.getReglas = function(){
            return this.store;
        }
    
    }
    
    var start_date = Ext.create('Ext.form.field.Date', {
        name : 'fecha_inicio',         
        style : {
            marginTop : '-10px'
        },
        anchor: '100%',       
        value: new Date(),  // defaults to today           
        renderTo: "fecha_inicio_tarifa"
    });
        
    var end_date = Ext.create('Ext.form.field.Date', {
        name : 'fecha_fin',         
        style : {
            marginTop : '-10px'
        },
        anchor: '100%',       
        value: new Date(),  // defaults to today           
        renderTo: "fecha_fin_tarifa"
    });
    
    var r = new reglas();

    function crearNuevaTarifa(){
        
        Ext.Msg.confirm("Nueva Tarifa", "Ha terminado de definir la nueva tarifa?", function(e){
            if(e == 'yes'){
                
                var error = "";
                
                var nombre = Ext.get('nombre_tarifa').getValue().replace(/^\s+|\s+$/g,"");                                
                
                if(!nombre.length){
                    error += "Verifique el nombre de la tarifa.<br/>";
                }
                
                if(error.length){
                    
                    Ext.MessageBox.show({
                        title: "Nueva Tarifa",
                        msg: error,
                        buttons: Ext.MessageBox.OK,
                        icon: Ext.MessageBox.ERROR
                    });
            
                    return;
                }
                
                POS.API.POST(
                "api/tarifa/nueva", 
                {
                    "id_moneda":Ext.get('id_moneda_tarifa').getValue(),
                    "nombre":nombre,
                    "tipo_tarifa":Ext.get('tipo_tarifa').getValue(),
                    "default":Ext.get('form_tarifa').dom.default_tarifa[0].checked == true ? true : null,
                    "fecha_fin":Ext.Date.format(end_date.getValue(), 'Y-m-d') + " 00:00:00",
                    "fecha_inicio":Ext.Date.format(start_date.getValue(), 'Y-m-d') + " 00:00:00",
                    "formulas":Ext.JSON.encode(r.getReglas())
                }, 
                {

                    callback : function(a){ 

                        window.onbeforeunload = function(){}

                        window.location = "tarifas.lista.php";

                    }
                }
            );
                
            }
        });
    }


</script>

<?php
$page->render();
