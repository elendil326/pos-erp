
<h1>Editar Puestos</h1>

<h2>Detalles del puesto</h2>


<script type="text/javascript" charset="utf-8">

    function validarNuevoPuesto(){

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

    function validarPuestoEspecifico(id){

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

        modificarPuesto(obj);

    }


    function guardar( data )
    {

        jQuery.ajaxSettings.traditional = true;


        jQuery.ajax({
            url: "../proxy.php",
            data: {
                action : 714,
                data : jQuery.JSON.encode(data)
            },
            cache: false,
            success: function(data){
                response = jQuery.parseJSON(data);

                if(response.success == false){
                    return jQuery("#ajax_failure").html(response.reason).show();
                }

                Ext.Msg.alert("Nuevo Puesto","Se ha creado correctamente el nuevo puesto",function(){
                    reason = 'Se ha registrado correctamente el nuevo puesto.';
                    window.location = "sucursales.php?action=detalles&id=<?php echo $_REQUEST['id'] ?>&success=true&reason=" + reason;
                });

            }
        });
    }

    function modificarPuesto( data )
    {

        jQuery.ajaxSettings.traditional = true;


        jQuery.ajax({
            url: "../proxy.php",
            data: {
                action : 715,
                data : jQuery.JSON.encode(data)
            },
            cache: false,
            success: function(data){
                response = jQuery.parseJSON(data);

                if(response.success == false){
                    return jQuery("#ajax_failure").html(response.reason).show();
                }

                Ext.Msg.alert("Editar Puesto","Se ha modificado correctamente el puesto");

            }
        });
    }

</script>

<form action=""  id="editdetalles">
    <table border="0" cellspacing="5" cellpadding="5">        
        <tr><td>Nombre</td><td><input type="text"           id="nombre" size="40" value=""/></td></tr>
        <tr><td>Descripción</td><td><input type="text"              id="descripcion" size="40" value=""/></td></tr>
        <tr><td></td><td><input type="button" onClick="validarNuevoPuesto();" value="Crear"/> </td></tr>
    </table>
</form>

<h2>Editar Puesto</h2>
<form action=""  id="editdetalles">
    <table border="0" cellspacing="5" cellpadding="5">
        <?php
        $p1 = new Grupos();
        $p1->setIdGrupo('3');
        $p2 = new Grupos();
        $p2->setIdGrupo('100');

        $grupos = GruposDAO::byRange($p1, $p2);

        foreach($grupos as $grupo){
            echo '<tr><td>Nombre : &nbsp;</td><td><input type="text" id="nombre' . $grupo->getIdGrupo() . '" size="40" value="' . $grupo->getNombre() . '"/></td><td>&nbsp;</td><td>Descripción : &nbsp;</td><td><input type="text"              id="descripcion' . $grupo->getIdGrupo() . '" size="40" value="' . $grupo->getDescripcion() . '"/></td><td></td><td>&nbsp;</td><td><input type="button" onClick="validarPuestoEspecifico(' . $grupo->getIdGrupo() . ');" value="Cambiar"/> </td></tr>';
        }

        ?>
        
    </table>
</form>




