
<?php
require_once('model/gastos_fijos.dao.php');
?>

<h1>Editar Conceptos de Gastos</h1>

<h2>Detalles del Nuevo Gasto</h2>


<script type="text/javascript" charset="utf-8">


    function validarNuevoGasto(){

        if(jQuery('#nombre').val().length < 4){
            jQuery("#ajax_failure").html("El nombre es muy corto.").show();
            return;
        }

        if(jQuery('#descripcion').val().length < 4){
            jQuery("#ajax_failure").html("La descripción es muy corta.").show();
            return;
        }

        obj = {
            nombre : jQuery('#nombre').val(),
            descripcion : jQuery("#descripcion").val()
        };

        guardar(obj);

    }

    function validarGastoEspecifico(id){

        if(jQuery('#nombre'+id).val().length < 4){
            jQuery("#ajax_failure").html("El nombre es muy corto.").show();
            return;
        }

        if(jQuery('#descripcion'+id).val().length < 4){
            jQuery("#ajax_failure").html("La descripción es muy corta.").show();
            return;
        }

        obj = {
            nombre : jQuery('#nombre'+id).val(),
            descripcion : jQuery("#descripcion"+id).val(),
            id : id
        };

        modificarGasto(obj);

    }


    function guardar( data )
    {

        jQuery.ajaxSettings.traditional = true;


        jQuery.ajax({
            url: "../proxy.php",
            data: {
                action : 611,
                data : jQuery.JSON.encode(data)
            },
            cache: false,
            success: function(data){
                response = jQuery.parseJSON(data);

                if(response.success == false){
                    return jQuery("#ajax_failure").html(response.reason).show();
                }

                Ext.Msg.alert("Nuevo Gasto","Se ha creado correctamente el nuevo gasto",function(){
                    reason = 'Se ha registrado correctamente el nuevo gasto.';
                    window.location = "sucursales.php?action=detalles&id=<?php echo $_REQUEST['id'] ?>&success=true&reason=" + reason;
                });

            }
        });
    }

    function modificarGasto( data )
    {
        jQuery.ajaxSettings.traditional = true;

        jQuery.ajax({
            url: "../proxy.php",
            data: {
                action : 612,
                data : jQuery.JSON.encode(data)
            },
            cache: false,
            success: function(data){
                response = jQuery.parseJSON(data);

                if(response.success == false){
                    return jQuery("#ajax_failure").html(response.reason).show();
                }

                Ext.Msg.alert("Editar Puesto","Se ha modificado correctamente el gasto");

            }
        });
    }

</script>

<form action=""  id="editdetalles">
    <table border="0" cellspacing="5" cellpadding="5">        
        <tr><td>Nombre : &nbsp;</td><td><input type="text"           id="nombre" size="40" value=""/></td></tr>
        <tr><td>Descripción : &nbsp;</td><td><input type="text"              id="descripcion" size="40" value=""/></td></tr>
        <tr><td></td><td><input type="button" onClick="validarNuevoGasto();" value="Definir Nuevo Gasto"/> </td></tr>
    </table>
</form>

<h2>Editar Conceptos de Gasto</h2>
<form action=""  id="editdetalles">
    <table border="0" cellspacing="5" cellpadding="5">
<?php
$gastos = GastosFijosDAO::getAll();

foreach ($gastos as $gasto) {
    echo '<tr><td>Nombre : &nbsp;</td><td><input type="text" id="nombre' . $gasto->getIdGastoFijo() . '" size="40" value="' . $gasto->getNombre() . '"/></td><td>&nbsp;</td><td>Descripción : &nbsp;</td><td><input type="text"              id="descripcion' . $gasto->getIdGastoFijo() . '" size="40" value="' . $gasto->getDescripcion() . '"/></td><td></td><td>&nbsp;</td><td><input type="button" onClick="validarGastoEspecifico(' . $gasto->getIdgastoFijo() . ');" value="Cambiar"/> </td></tr>';
}
?>

    </table>
</form>




