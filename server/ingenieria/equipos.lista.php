<?php

    require_once('model/equipo.dao.php');
    require_once('model/equipo_sucursal.dao.php');
    require_once('model/sucursal.dao.php');

    if(isset($_REQUEST['fua']) && isset($_REQUEST['desc'])){

	    $foo = new Equipo();
	    $foo->setFullUA($_REQUEST['fua']);
	    $res = EquipoDAO::search($foo);
	
	    if( strlen($_REQUEST['fua']) == 0 || strlen($_REQUEST['desc']) == 0 ){
             echo "<div class='failure'>No puede insertar campos vacios</div>" ;

	    }elseif( count($res) > 0 ){
             echo "<div class='failure'>Ya existe un equipo con este UA</div>" ;

        }else{
           try{
                $e = new Equipo();
                $e->setFullUa(  $_REQUEST['fua']);
                $e->setDescripcion(  $_REQUEST['desc']);
                $e->setLocked(false);
                EquipoDAO::save( $e );

                $es = new EquipoSucursal();
                $es->setIdEquipo( $e->getIdEquipo() );
                $es->setIdSucursal($_REQUEST['sucursal']);
                EquipoSucursalDAO::save( $es );
	            echo "<div class='success'>Equipo agregado correctamente</div>" ;
            }catch(Exception $e){
	            echo "<div class='failure'>" . $e . "</div>" ;
            }
        }
    }


    ?><h2>Todos los equipos</h2><?php

    $todos = EquipoDAO::getAll();

    function rLocked($l){
       return $l ? "<img src='../media/icons/lock_32.png'>" : "<img src='../media/icons/lock_open_32.png'>";
    }

    function rUA( $ua ){
        return "<div style='font-size: 12px; overflow: hidden;'>" . $ua . "</div>";
    }
    
    //render the table
    $header = POS_SUCURSAL_TEST_TOKEN == 'FULL_UA' ? array(  
        	"locked" => "locked",
        	"id_equipo" => "EID", 
        	"descripcion"=> "Desc" ,
        	"full_ua" => "Full UA")
        : array(  
        	"locked" => "locked",
        	"id_equipo" => "ID Equipo", 
        	"token" => "SID Token",
        	"descripcion"=> "Descripcion" ,
        	"full_ua" => "Full UA");
    	
    $tabla = new Tabla( $header, $todos );
    $tabla->addOnClick( 'id_equipo', 'e' );
    $tabla->addColRender( 'locked', 'rLocked' );
    $tabla->addColRender( 'full_ua', 'rUA' );
    $tabla->render();

    ?>

    <script type="text/javascript" charset="utf-8">function e(a){window.location="equipos.php?action=editar&id="+a;}</script>



   <h2>Equipos asignados a sucursales</h2><?php

    function rSuc($sid){
        $foo = SucursalDAO::getByPK( $sid );
        return $foo->getDescripcion();
    }

    $todos = EquipoSucursalDAO::getAll();

    //render the table
    $header = array(  "id_equipo" => "ID Equipo", "id_sucursal" => "ID Sucursal");
    $tabla = new Tabla( $header, $todos );
    $tabla->addColRender( 'id_sucursal', 'rSuc' );
    $tabla->render();

    ?>




   <h2>Nuevo equipo</h2>
<?php
	$ua = $_SERVER['HTTP_USER_AGENT'];
	$foo = new Equipo();
	$foo->setFullUA($ua);
	$res = EquipoDAO::search($foo);
	
	if(count($res) > 0){
		?><img src="../media/icons/warning_16.png">Ya existe un equipo registrado con <b><i><?php  echo $_SERVER['HTTP_USER_AGENT'];  ?></i></b><?php
	}
?>    

    <form action="equipos.php?action=lista" method="POST">
    <table border="0" cellspacing="1" cellpadding="1" width=100%>
    	<tr><td>Full User-Agent</td><td><input style="width: 90%" type="text" value="<?php echo $_SERVER['HTTP_USER_AGENT']; ?>" name="fua"  /></td></tr>
    	<!-- <tr><td>SID Token</td><td><input type="text" value="" name="token" size="40"/></td></tr> -->
        <tr><td>Descripcion</td><td><input placeholder="Descripcion de este equipo" type="text" value="" name="desc" size="40"/></td></tr>

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
    	<tr><td></td><td align=center><h4><input type="submit" value="Guardar"/></h4></td></tr>
    </table>
    </form>



