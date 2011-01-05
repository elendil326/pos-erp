<h1>Asignar gerentes</h1>


<h2>Asignacion mediante sucursales</h2><?php


require_once('model/usuario.dao.php');
require_once("controller/personal.controller.php");
require_once("controller/sucursales.controller.php");






//listar sucursales
$s = new Sucursal();
$s->setActivo(1);

$sucursales = SucursalDAO::search($s);


//listar todos los gerentes
$gerentes = listarGerentes();

//crear la tabla
?><table><tr><th>ID Sucursal</th><th>Descripcion</th><th>Gerente</th></tr><?php

foreach ($sucursales as $sucursal)
{
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
                echo "id_gerente : parseInt( $('#sucursal_{$sucursal->getIdSucursal()}').val() )," ;
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

        $.ajax({
	      url: "../proxy.php",
	      data: { 
            action : 506, 
            data : $.JSON.encode(sucursales)
           },
	      cache: false,
	      success: function(data){
		        response = jQuery.parseJSON(data);

                if(response.success == false){
                    return $("#ajax_failure").html(response.reason).show();
                }


                reason = "Los cambios se han guardado con exito.";
                window.location = 'gerentes.php?action=lista&success=true&reason=' + reason;
	      }
	    });
    }
</script>
<?php







?>

<input type='button' value="Guardar cambios" onclick="guardarCambios()">


<script src="../frameworks/jquery/jquery-1.4.2.min.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/uniform/jquery.uniform.min.js" type="text/javascript" charset="utf-8"></script> 
<link rel="stylesheet" href="../frameworks/uniform/css/uniform.default.css" type="text/css" media="screen">

<script type="text/javascript" charset="utf-8">
	$(function(){
      $("input, select").uniform();
    });
</script>


