<?php
require_once("model/usuario.dao.php");
require_once("model/sucursal.dao.php");
require_once("model/grupos.dao.php");
?>

<script type="text/javascript" charset="utf-8">

    function validarNuevoEmpleado(){

        if(jQuery('#nombre').val().length < 8){
            jQuery("#ajax_failure").html("El nombre es muy corto.").show();
            return;
        }


        if(jQuery('#direccion').val().length < 10){
            jQuery("#ajax_failure").html("La direccion es muy corta.").show();
            return;
        }

        if(jQuery('#rfc').val().length < 7){
            jQuery("#ajax_failure").html("El RFC es muy corto.").show();
            return;            

        }

        if(jQuery('#telefono').val().length < 7){
            jQuery("#ajax_failure").html("El telefono es muy corto.").show();
            return;
        }


        if( isNaN(jQuery('#salario').val()) || jQuery('#salario').val().length == 0){
            jQuery("#ajax_failure").html("El salario debe ser un nuemero.").show();
            return;
        }

        if( jQuery('#salario').val() >= 10000){
            return jQuery("#ajax_failure").html("El salario mensual debe ser menor a $10,000.00").show();
        }       

        if( jQuery('#grupo').val() == 0){
            return jQuery("#ajax_failure").html("Seleccione un puesto para el empleado").show();
        }   

        if (jQuery("#grupo").val() <= 3 && jQuery("#grupo").val() > 0){
            
            if( jQuery('#contrasena').val() != jQuery('#contrasena2').val()){
                return jQuery("#ajax_failure").html("Verifique que las contraseñas sean iguales").show();
            } 
            
        }

          

        obj = {
            nombre : jQuery('#nombre').val(), 
            direccion : jQuery("#direccion").val(),
            contrasena : hex_md5(jQuery("#contrasena").val()),
            RFC : jQuery("#rfc").val(), 
            telefono : jQuery("#telefono").val(),
            grupo : jQuery("#grupo").val(),
            salario : jQuery("#salario").val(),
            sucursal : <?php echo $_REQUEST['id'] ?>
        };        

        guardar(obj);
    }






    function guardar( data )
    {

        jQuery.ajaxSettings.traditional = true;


        jQuery.ajax({
            url: "../proxy.php",
            data: { 
                action : 500, 
                data : jQuery.JSON.encode(data)
            },
            cache: false,
            success: function(data){
                response = jQuery.parseJSON(data);

                if(response.success == false){
                    return jQuery("#ajax_failure").html(response.reason).show();
                }
				
                reason = 'Se ha registrado correctamente el nuevo empleado.';
                window.location = "sucursales.php?action=detalles&id=<?php echo $_REQUEST['id'] ?>&success=true&reason=" + reason;
                
                
            }
        });
    }
    
    function verificaPuesto(puesto){
        if(puesto <= 3 && puesto > 0){
            jQuery("#password").css("display", "table-row");
            jQuery("#password2").css("display", "table-row");                
        }
        else{
            jQuery("#password").css("display", "none");
            jQuery("#password2").css("display", "none");
        }
    }
</script>

<h1>Nuevo Empleado</h1>

<h2>Detalles personales</h2>
<form id="editdetalles">
    <table border="0" cellspacing="5" cellpadding="5">
        <tr><td>Puesto</td>
            <td>
                <select id ="grupo" onChange ="verificaPuesto(this.value);">
                    <option value="0">Seleccione su puesto</option>
                    <?php
                    foreach (GruposDAO::getAll() as $grupo) {
                        if ($grupo->getIdGrupo() > 1) {
                            echo "<option value = {$grupo->getIdGrupo()}>{$grupo->getNombre()}</option>";
                        }
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr><td>Nombre</td><td><input type="text"           id="nombre" size="40" value=""/></td></tr>
        <tr><td>RFC</td><td><input type="text"              id="rfc" size="40" value=""/></td></tr>
        <tr><td>Direccion</td><td><input type="text"        id="direccion" size="40" value=""/></td></tr>
        <tr><td>Telefono</td><td><input type="text"         id="telefono" size="40" value=""/></td></tr>
        <tr><td>Salario</td><td><input type="text"  id="salario" size="40" value=""/></td></tr>
        <tr style ="display: none" id ="password"><td>Contraseña</td><td><input type="password"  id="contrasena" size="40" value=""/></td></tr>
        <tr style ="display: none" id ="password2"><td>Repetir Contraseña</td><td><input type="password"  id="contrasena2" size="40" value=""/></td></tr>        
        <tr><td></td><td><input type="button" onClick="validarNuevoEmpleado();" value="Guardar"/> </td></tr>

    </table>
</form>




