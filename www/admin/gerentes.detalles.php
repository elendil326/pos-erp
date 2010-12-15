<?php

require_once("model/usuario.dao.php");


$gerente = UsuarioDAO::getByPK($_REQUEST['id']);


?><h1><?php echo $gerente->getNombre(); ?></h1>

<table border="0" cellspacing="5" cellpadding="5">
	<tr><td><b>Nombre</b></td><td><?php echo $gerente->getNombre(); ?></td><td rowspan=12><div id="map_canvas"></div></td></tr>
	<tr><td><b>RFC</b></td><td><?php echo $gerente->getRFC(); ?></td></tr>
	<tr><td><b>Direccion</b></td><td><?php echo $gerente->getDireccion(); ?></td></tr>
	<tr><td><b>Telefono</b></td><td><?php echo $gerente->getTelefono(); ?></td></tr>
	<tr><td><b>Fecha Ingreso</b></td><td><?php echo $gerente->getFechaInicio() ; ?></td></tr>
	<tr><td><b>Salario Mensual</b></td><td><?php echo moneyFormat($gerente->getSalario()) ; ?></td></tr>



	<tr><td colspan=2><input type=button value="Editar detalles" onclick="editarGerente()"><input type=button value="Imprmir detalles"></td> </tr>
</table>
<script type="text/javascript" charset="utf-8">
	function editarGerente(){
		window.location = "gerentes.php?action=editar&id=<?php echo $_REQUEST['id']; ?>";
	}
</script>
<?php

