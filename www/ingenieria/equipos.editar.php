<h1>Editar equipo</h1><?php


	require_once("model/equipo.dao.php");
	require_once("model/equipo_sucursal.dao.php");
    require_once('model/sucursal.dao.php');
    

    $equipo = EquipoDAO::getByPK($_REQUEST['id']);

    $save = false;
    if(isset($_REQUEST['fua'])){
        $equipo->setFullUa($_REQUEST['fua']);
        $save = true;
    }

    if(isset($_REQUEST['token'])){
        $equipo->setToken($_REQUEST['token']);
        $save = true;
    }


    if($save){
        $status = "Datos guardados correctamente";
        

        $es = new EquipoSucursal();
        $es->setIdEquipo($_REQUEST['id']);
        $es->setIdSucursal($_REQUEST['sucursal']);


        try{
            EquipoSucursalDAO::save( $es );
            EquipoDAO::save( $equipo );
        }catch(Exception $e){
            $status = $e;    
        }
    }

?>

<script src="../frameworks/jquery/jquery-1.4.2.min.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/uniform/jquery.uniform.js" type="text/javascript" charset="utf-8"></script> 
<link rel="stylesheet" href="../frameworks/uniform/css/uniform.default.css" type="text/css" media="screen">

<script type="text/javascript" charset="utf-8">
	$(function(){$("input, select").uniform();});
    function i(){$('#fua').val("<?php echo $_SERVER['HTTP_USER_AGENT']; ?>");}
</script>

<?php 
    if($save){
        echo $status;
    }
?>


<h2>Editar detalles</h2>
<form action="equipos.php?action=editar&id=<?php echo $_GET['id']; ?>" method="POST">
<table border="0" cellspacing="5" cellpadding="5">
	<tr><td>ID Equipo</td><td><input type="text" value="<?php echo $equipo->getIdEquipo(); ?>" id="id" disabled="true" size="40"/></td></tr>
	<tr><td>Full User-Agent</td><td><input type="text" value="<?php echo $equipo->getFullUa(); ?>" name="fua" id="fua" size="40"/></td></tr>
	<tr><td>SID Token</td><td><input type="text" value="<?php echo $equipo->getToken(); ?>" name="token" id="token" size="40"/></td></tr>
	<tr><td>Sucursal asociada</td>
        <td>

   			<select name="sucursal"> 
			<?php
			
				$sucursales = SucursalDAO::getAll();
				foreach( $sucursales as $suc ){
					echo "<option value='" . $suc->getIdSucursal() . "' >" .  $suc->getDescripcion()  . "</option>";
				}
			
			?>
	
	        </select>

        </td></tr>
	<tr><td></td><td><input type="submit" value="Guardar"/> </td></tr>
</table>
</form>



<h2>Insertar UA Actual</h2>
UA Actual: <b><?php echo $_SERVER['HTTP_USER_AGENT']; ?></b><br><br>
<input type="button" onClick="i()" value="Insertar"/>


