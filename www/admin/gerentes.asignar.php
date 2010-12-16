<h1>Asignar gerentes</h1><?php


require_once('model/usuario.dao.php');
require_once("controller/personal.controller.php");

//listar todos los gerentes
$gerentes = listarGerentes();




function doMenu($s, $gid)
{

    $h = '<select id="gerente_'.$gid.'">';
	$sucursales = SucursalDAO::getAll();


		$h .= "<option value='null'";
        if($s === null) $h .= ' selected ';
        $h .= " >Sin sucursal</option>";

	foreach( $sucursales as $suc ){
		$h .= "<option value='" . $suc->getIdSucursal() . "'";
        if($suc->getIdSucursal() == $s) $h .= ' selected ';
        $h .= " >" .  $suc->getDescripcion()  . "</option>";
	}
    $h .= '</select>';

    return $h;

}




?><table><tr><th>ID Gerente</th><th>Nombre</th><th>Sucursal a cargo</th></tr><?php

$lookForThese = array();

foreach ($gerentes as $gerente)
{
    echo "<tr>";
    echo "<td>" . $gerente['id_usuario'] . "</td><td>" . $gerente['nombre'] . "</td><td>";
    echo doMenu($gerente['gerencia_sucursal_id'], $gerente['id_usuario']) ."</td>";
    echo "</tr>";
    
    array_push($lookForThese, "gerente_" . $gerente['id_usuario'] );
}
echo "</table>";








?>

<input type='button' value="Guardar cambios" onclick="save()">













<script>
    function save(){
        obj = {
        <?php
            foreach ($lookForThese as $option )
            {
                echo $option . " : $('#". $option . "').val(),";
            }
        ?>
        };




        jQuery.ajaxSettings.traditional = true;


        $.ajax({
	      url: "../proxy.php",
	      data: { 
            action : 506, 
            data : $.JSON.encode(obj)
           },
	      cache: false,
	      success: function(data){
		        response = jQuery.parseJSON(data);

                if(response.success == false){
                    alert("Se ha sucidato un error intente de nuevo.");
                    return;
                }


                alert("Los datos se han editado con exito !");
	      }
	    });




    }
</script>

<script src="../frameworks/jquery/jquery-1.4.2.min.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/uniform/jquery.uniform.js" type="text/javascript" charset="utf-8"></script> 
<link rel="stylesheet" href="../frameworks/uniform/css/uniform.default.css" type="text/css" media="screen">

<script type="text/javascript" charset="utf-8">
	$(function(){
      $("input, select").uniform();
    });
</script>


