<?php

require_once('model/usuario.dao.php');
require_once("controller/personal.controller.php");
require_once("controller/sucursales.controller.php");

?>


<h2>Asignar gerencias</h2><?php



//listar sucursales
$s = new Sucursal();
$s->setActivo(1);

$sucursales = SucursalDAO::search($s);


//listar todos los gerentes
$gerentes = listarGerentes();



//crear la tabla
?><table style="width: 100%;  "><tr style="text-align: left;"><th>ID Sucursal</th><th>Descripcion</th><th>Gerente</th></tr><?php

foreach ($sucursales as $sucursal)
{
	//omitir sucursal 0
	if($sucursal->getIdSucursal() == 0){
		continue;
	}
	
    echo "<tr>";
    echo "<td>" . $sucursal->getIdSucursal() . "</td>";
    echo "<td>" . $sucursal->getDescripcion() . "</td>";
    echo "<td><select id='sucursal_{$sucursal->getIdSucursal()}'>";

    if($sucursal->getGerente() === null)
        echo "<option value='-1' selected>Sin gerente</option>";
    else
        echo "<option value='-1' >Sin gerente</option>";    

    foreach ($gerentes as $gerente)
    {
        if($gerente['id_usuario'] == $sucursal->getGerente())
            echo "<option value='{$gerente['id_usuario']}' selected>{$gerente['nombre']}</option>";
        else
            echo "<option value='{$gerente['id_usuario']}' >{$gerente['nombre']}</option>";    
    }
        
    echo "</select></td>";
    echo "</tr>";    
}

echo "</table>";


//crear el javascript para enviar
?>
<script>
    function guardarCambios()
    {

        sucursales = [
            <?php
            foreach ($sucursales as $sucursal){
                echo "{";
                echo "id_sucursal : {$sucursal->getIdSucursal()}," ;
                echo "id_gerente : parseInt( jQuery('#sucursal_{$sucursal->getIdSucursal()}').val() )," ;
                echo "},";
            }  
            ?>
        ];

        //validar que no haya sucursales con el mismo gerente
        busyManangers = [];
        imSure = false;
        for ($a = 0; $a < sucursales.length; $a++)
        {
            if(sucursales[$a].id_gerente != -1){
                //buscar si no esta ocupado
                for (var m in busyManangers)
                {

                    if(busyManangers[m] == sucursales[$a].id_gerente ){
                        alert("Un gerente no puede ocuparse de mas de una sucursal.");
                        return;
                    }
                }

                busyManangers.push( sucursales[$a].id_gerente );

            }else{
                if(!imSure){
                    imSure = confirm("Esta seguro que desea dejar sucursales sin gerencia ?");
                    if(!imSure)return;
                }

            }
        }

        //enviar los resultados
       jQuery.ajaxSettings.traditional = true;

        jQuery.ajax({
	      url: "../proxy.php",
	      data: { 
            action : 506, 
            data : jQuery.JSON.encode(sucursales)
           },
	      cache: false,
	      success: function(data){
		        response = jQuery.parseJSON(data);

                if(response.success == false){
                    return jQuery("#ajax_failure").html(response.reason).show();
                }


                reason = "Los cambios se han guardado con exito.";
                window.location = 'gerentes.php?action=lista&success=true&reason=' + reason;
	      }
	    });
    }
</script>
<?php






if(sizeof($sucursales) > 1){
	?><h4><input type='button' value="Guardar cambios" onclick="guardarCambios()"></h4> <?php
}







