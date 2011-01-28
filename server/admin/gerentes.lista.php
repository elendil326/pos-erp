<?php

    require_once("controller/personal.controller.php");

	$gerentes = listarGerentes(true);


	$header = array(
		"id_usuario" => "ID",
		"nombre" => "Nombre",
		"RFC" => "RFC",
		"telefono" => "Telefono",
		"gerencia_sucursal_desc" => "Sucursal" );

	$tabla = new Tabla( $header, $gerentes );
	$tabla->addOnClick("id_usuario", "mostrarDetalles");
	$tabla->addNoData("No hay gerentes asignados.");
?>


<h2><img src='../media/icons/user_business_chart_32.png'>Gerentes asignados a una sucursal</h2>

<?php 

$tabla->render();

$gerentes = listarGerentes(false);

if(sizeof($gerentes)>0){
    ?><h2><img src='../media/icons/user_business_warning_32.png'>Gerentes no asignados a una sucursal</h2><?php



    //render the table
    $header = array(
        "id_usuario" => "ID",
        "nombre" => "Nombre",
        "telefono" => "Telefono",
        "RFC" => "RFC" );

    $tabla = new Tabla( $header, $gerentes );
    $tabla->addOnClick("id_usuario", "mostrarDetalles");
    $tabla->addNoData("No hay gerentes sin asignar.");
    $tabla->render();
}



$gru1 = new GruposUsuarios();
$gru1->setIdGrupo('2');
$result = GruposUsuariosDAO::search($gru1);

$fired = array();

foreach($result as $r)
{
	$usr = UsuarioDAO::getByPK( $r->getIdUsuario() );

	if( $usr->getActivo() == "0"){
	    array_push($fired, $usr );
	}
}

if(sizeof($fired) > 0){
	?><h2><img src='../media/icons/user_business_close_32.png'>Gerentes despedidos</h2><?php
	
    $header = array(
	    "id_usuario" => "ID",
	    "nombre" => "Nombre",
	    "telefono" => "Telefono",
	    "RFC" => "RFC" );
	$tabla = new Tabla( $header, $fired );
	$tabla->addOnClick("id_usuario", "mostrarDetalles");
	$tabla->render();
}


?>
<script type="text/javascript" charset="utf-8">
	function mostrarDetalles( a ){
		window.location = "gerentes.php?action=detalles&id=" + a;
	}
</script>










<?php

require_once('model/usuario.dao.php');
require_once("controller/personal.controller.php");
require_once("controller/sucursales.controller.php");

?><h1>Asignar gerentes</h1>


<h2>Asignacion mediante sucursales</h2><?php



//listar sucursales
$s = new Sucursal();
$s->setActivo(1);

$sucursales = SucursalDAO::search($s);


//listar todos los gerentes
$gerentes = listarGerentes();

//crear la tabla
?><table style="width: 100%"><tr style="text-align: left;"><th>ID Sucursal</th><th>Descripcion</th><th>Gerente</th></tr><?php

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







?>

<input type='button' value="Guardar cambios" onclick="guardarCambios()">




