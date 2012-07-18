<?php

require_once("model/usuario.dao.php");
require_once("model/sucursal.dao.php");
require_once("model/grupos.dao.php");
require_once("model/grupos_usuarios.dao.php");

$gerente = UsuarioDAO::getByPK($_REQUEST['id']);

?>


<script>
	
<?php  if($gerente->getActivo() != 0) {  ?>
  jQuery("#MAIN_TITLE").html("<?php echo $gerente->getNombre(); ?>");
<?php  }else{  ?>
  jQuery("#MAIN_TITLE").html("<?php echo $gerente->getNombre(); ?> (Despedido)");	
<?php  }  ?>
</script>


<h2>Detalles</h2>
<table border="0" cellspacing="2" cellpadding="2">
	<tr><td><b>Nombre</b></td><td><?php echo $gerente->getNombre(); ?></td><td rowspan=12><div id="map_canvas"></div></td></tr>
	<tr><td><b>RFC</b></td><td><?php echo $gerente->getRFC(); ?></td></tr>
	<tr><td><b>Direccion</b></td><td><?php echo $gerente->getDireccion(); ?></td></tr>
	<tr><td><b>Telefono</b></td><td><?php echo $gerente->getTelefono(); ?></td></tr>
	<tr><td><b>Fecha Ingreso</b></td><td><?php echo toDate( $gerente->getFechaInicio() ) ; ?></td></tr>
	<?php
		switch(POS_PERIODICIDAD_SALARIO){
			case POS_SEMANA : 
					echo "<tr><td><b>Salario Semanal</b></td><td>" . moneyFormat($gerente->getSalario()). "</td></tr>";
				break;
			case POS_MES : 		
					echo "<tr><td><b>Salario Mensual</b></td><td>" . moneyFormat($gerente->getSalario()). "</td></tr>";
				break;
		}
	?>
</table>
<h4>
<?php  if($gerente->getActivo() != 0) {  ?>
	<input type=button value="Editar detalles" onclick="editarGerente()"><input type=button onclick="despedir()" value="Despedir">
<?php  }else{  ?>
	<input type=button value="Recontratar" onclick="recontratar()">
<?php  }  ?>
</h4>


<?php $aCargo = false; ?>

<?php  if($gerente->getActivo() != 0) {  ?>
<h2>Sucursal a cargo</h2><?php
        $suc = new Sucursal();
        $suc->setGerente( $gerente->getIdUsuario() );
        $sucursal = SucursalDAO::search($suc);

        if(count($sucursal) == 0){
            echo "Este gerente no tiene a su cargo ninguna sucursal.";
        }else{
            $sucursal = $sucursal[0];
            echo "Actualmente <b>" . $gerente->getNombre() . "</b> es gerente de <a href='sucursales.php?action=detalles&id=" . $sucursal->getIdSucursal() . "'>" . $sucursal->getDescripcion() . "</a>.";
            $aCargo = true;
        }
?>
<?php  }  ?>


<?php
if($aCargo){

    ?><h2>Personal a cargo</h2><?php

        $empleados = new Usuario();
        $empleados->setIdSucursal( $sucursal->getIdSucursal() );
        $empleados->setActivo("1"); 


        $empleados = UsuarioDAO::search($empleados);



        //calcular totales, y agregar el campo de puesto al array de objetos
        $total = 0;
        $empleadosArray = array();

        foreach($empleados as $e){

            //si es el gerente, a la verga

            $foo = $e->asArray();

            $grupo = new GruposUsuarios();
            $grupo->setIdUsuario( $e->getIdUsuario() );

            $searchGrupo = GruposUsuariosDAO::search( $grupo );

            if(count($searchGrupo) == 0){
                //no esta asignado
                $foo['puesto'] = "No asignado";

            }else{

                if($searchGrupo[0]->getIdGrupo() <= 2){
                    //no motrar administradores ni gerentes
                    continue;
                }

                $foo['puesto'] = GruposDAO::getByPK( $searchGrupo[0]->getIdGrupo() )->getDescripcion();;
            }

            $total += $e->getSalario();
            array_push( $empleadosArray, $foo );
            
        }

        $header = array(
            "id_usuario" => "ID",
            "nombre" => "Nombre",
            "puesto" => "Puesto",
            "RFC" => "RFC",
//            "direccion" => "Direccion",
            "telefono" => "Telefono",
            "fecha_inicio" => "Inicio",
            "salario" => "Salario" );


        $tabla = new Tabla( $header, $empleadosArray );
        $tabla->addColRender( 'salario', "moneyFormat" );
        $tabla->render();




        echo "Total de salarios mensuales : <b>" . moneyFormat($total) . "</b>";

}?>



<script type="text/javascript" charset="utf-8">
	function editarGerente(){
		window.location = "gerentes.php?action=editar&id=<?php echo $_REQUEST['id']; ?>";
	}




    function despedir()
    {
        var r = confirm("Esta seguro que desea despedir a <?php echo $gerente->getNombre(); ?> ?");
        
        if(r){
            //enviar confirmacion
            jQuery.ajaxSettings.traditional = true;


        jQuery.ajax({
	      url: "../proxy.php",
	      data: { 
                action : 503, 
                id_empleado : <?php echo $gerente->getIdUsuario(); ?>, 
                activo : 0
           },
	      cache: false,
	      success: function(data){
		        response = jQuery.parseJSON(data);

                if(response.success == false){
                    return jQuery("#ajax_failure").html(response.reason).show();
                }


                alert(response.info);
          		window.location = "gerentes.php?action=lista";
	      }
	    });
        }
    }



</script>
<?php

