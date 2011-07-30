<h1>Editar equipo</h1><?php


	require_once("model/equipo.dao.php");
	require_once("model/equipo_sucursal.dao.php");
    require_once('model/sucursal.dao.php');
    

    $equipo = EquipoDAO::getByPK($_REQUEST['id']);

    $save = true;
    
    if(isset($_REQUEST['fua'])){
        $equipo->setFullUa($_REQUEST['fua']);
    
    }else{
	    $save = false;
	}

    if(isset($_REQUEST['token'])){
        $equipo->setToken($_REQUEST['token']);

    }else{
	    $save = false;
	}
	
    if(isset($_REQUEST['descripcion'])){
        $equipo->setDescripcion($_REQUEST['descripcion']);

    }else{
	    $save = false;
	}

    if($save){
	
		$success = true;
        $es = new EquipoSucursal();
        $es->setIdEquipo($_REQUEST['id']);
        $es->setIdSucursal($_REQUEST['sucursal']);
		

        try{
            EquipoSucursalDAO::save( $es );
            EquipoDAO::save( $equipo );
        }catch(Exception $e){
        	$success = false;
            $status = $e;
        }
    }

?>


<script type="text/javascript" charset="utf-8">
    function i(){jQuery('#fua').val("<?php echo $_SERVER['HTTP_USER_AGENT']; ?>");}
</script>

<?php 
    if($save){
    	if($success){
	    	echo "<div class='success'>OK</div>";
    	}else{
	    	echo "<div class='failure'>".$status."</div>";    	
    	}
        
    }
?>


<h2>Editar detalles</h2>
<form action="equipos.php?action=editar&id=<?php echo $_GET['id']; ?>" method="POST">
<table border="0" cellspacing="5" cellpadding="5" width="100%">
	<tr><td>ID Equipo</td>
		<td><input type="text" value="<?php echo $equipo->getIdEquipo(); ?>" id="id" disabled="true" size="40"/></td></tr>
		
	<tr><td>Full User-Agent</td>
		<td><input type="text" value="<?php echo $equipo->getFullUa(); ?>" name="fua" id="fua" size="40"/></td></tr>
		
	<tr><td>Token</td>
		<td><input type="text" value="<?php echo $equipo->getToken(); ?>" name="token" id="token" size="40"/></td></tr>
		
	<tr><td>Descripcion</td><td>
		<input type="text" value="<?php echo $equipo->getDescripcion(); ?>" name="descripcion" id="descripcion" size="40"/></td></tr>

			
	<tr><td>Sucursal asociada</td>
        <td>
			
   			<select name="sucursal"> 
			<?php
				//buscar a que sucursal pertenece este equipo
				$equipo_sucursal = EquipoSucursalDAO::getByPK( $equipo->getIdEquipo()  );
				$equipo_sucursal_suc = $equipo_sucursal->getIdSucursal();
				
				$sucursales = SucursalDAO::getAll();
				foreach( $sucursales as $suc ){
					
					/*if($suc->getIdSucursal() == 0)
						continue;*/
						
					echo "<option value='" . $suc->getIdSucursal() . "' ";
					if($equipo_sucursal_suc == $suc->getIdSucursal()) echo " selected ";
					echo ">" .  $suc->getDescripcion()  . "</option>";
				}
			
			?>
	
	        </select>

        </td></tr>
	<tr><td></td><td><input type="submit" value="Guardar"/> </td></tr>
</table>
</form>



<h2>Insertar UA de esta maquina</h2>
<?php
	$ua = $_SERVER['HTTP_USER_AGENT'];
	$foo = new Equipo();
	$foo->setFullUA($ua);
	$res = EquipoDAO::search($foo);
	
	if(count($res) > 0){
		?>Ya existe un equipo registrado con el UA <b><i><?php  echo $_SERVER['HTTP_USER_AGENT'];  ?></i></b><?php
	}else{
		?>
		UA Actual: <b><?php echo $_SERVER['HTTP_USER_AGENT']; ?></b><br><br>
		<input type="button" onClick="i()" value="Insertar"/>	
		<?php
	}
?>



<h2>Eliminar este equipo</h2>
<input type="button" onClick="i()" value="Eliminar"/>
