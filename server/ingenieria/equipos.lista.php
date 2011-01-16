<?php

    require_once('model/equipo.dao.php');


    if(isset($_REQUEST['fua']) && isset($_REQUEST['token'])){
        $e = new Equipo();
        $e->setFullUa(  $_REQUEST['fua']);
        $e->setToken(  $_REQUEST['token']);

        $status = "Equipo registrado correctamente.";

        try{
            EquipoDAO::save( $e );
	        echo "<div class='success'>Equipo agregado correctamente</div>" ;            
        }catch(Exception $e){
	        echo "<div class='failure'>" . $e . "</div>" ;
        }

    }


    ?><h2>Todos los equipos</h2><?php

    $todos = EquipoDAO::getAll();

    
        
    //render the table
    $header = array(  
    	"id_equipo" => "ID Equipo", 
    	"token" => "SID Token",
    	"descripcion"=> "Descripcion" ,
    	"locked" => "locked",
    	"full_ua" => "Full UA");
    	
    $tabla = new Tabla( $header, $todos );
    $tabla->addOnClick( 'id_equipo', 'e' );
    $tabla->render();

    ?>

    <script type="text/javascript" charset="utf-8">function e(a){window.location="equipos.php?action=editar&id="+a;}</script>



   <h2>Equipos asignados a sucursales</h2><?php

    require_once('model/equipo_sucursal.dao.php');

    $todos = EquipoSucursalDAO::getAll();


        
    //render the table
    $header = array(  "id_equipo" => "ID Equipo", "id_sucursal" => "ID Sucursal");
    $tabla = new Tabla( $header, $todos );
    $tabla->render();

    ?>




   <h2>Nuevo equipo</h2>
    <form action="equipos.php?action=lista" method="POST">
    <table border="0" cellspacing="5" cellpadding="5">
    	<tr><td>Full User-Agent</td><td><input type="text" value="" name="fua"  size="40"/></td></tr>
    	<tr><td>SID Token</td><td><input type="text" value="" name="token" size="40"/></td></tr>
    	<tr><td></td><td><input type="submit" value="Guardar"/> </td></tr>
    </table>
    </form>



