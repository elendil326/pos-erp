<?php 
    require_once( 'model/sucursal.dao.php' );
    $suc = SucursalDAO::getByPK($_REQUEST['sid']); 
?>



<h1>Editar detalles de sucursal</h1>

<script src="../frameworks/jquery/jquery-1.4.2.min.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/uniform/jquery.uniform.min.js" type="text/javascript" charset="utf-8"></script> 
<link rel="stylesheet" href="../frameworks/uniform/css/uniform.default.css" type="text/css" media="screen">

<script type="text/javascript" charset="utf-8">	$(function(){ $("input, select").uniform(); }); </script>




<h2>Detalles personales</h2>
<form id="edit">
<table border="0" cellspacing="5" cellpadding="5">
	<tr><td>Descripcion</td><td><input type="text"        id="descripcion"  value="<?php echo $suc->getDescripcion(); ?>" size="40"/></td></tr>
	<tr><td>Direccion</td><td><input type="text"        id="direccion"  value="<?php echo $suc->getDireccion(); ?>" size="40"/></td></tr>
	<tr><td>Prefijo Factura</td><td><input type="text" id="letras_factura" value="<?php echo $suc->getLetrasFactura(); ?>"  size="40"/></td></tr>
	<tr><td>RFC</td><td><input type="text"              id="rfc"        value="<?php echo $suc->getRFC(); ?>" size="40"/></td></tr>
	<tr><td>Telefono</td><td><input type="text"         id="telefono"   value="<?php echo $suc->getTelefono(); ?>" size="40"/></td></tr>
	<tr><td></td><td><input type="button" onClick="validar()" value="Guardar Cambios"/> </td></tr>
</table>
</form>



<h2>Cerrar Sucursal</h2>
<input type="button" onClick="cerrar()" value="Cerrar"/>


<script>

    function cerrar(){
        
        $.ajax({
	      url: "../proxy.php",
	      data: { 
            action : 703, 
            sid : <?php echo $_REQUEST['sid']; ?>,
           },
	      cache: false,
	      success: function(data){
		        response = jQuery.parseJSON(data);

                if(response.success == false){
                    alert(response.reason);
                    return;
                }

                if(response.success == "false"){
                    window.location = "sucursales.php?action=editar&sid=<?php echo $_REQUEST['sid']; ?>&success=true&reason=" + response.reason;
                    return;
                }

                reason = "La sucursal se ha cerrado con exito.";
                window.location = "sucursales.php?action=lista&success=true&reason=" + reason;
	      }
	    });
    }


    function validar(){
        obj = {
            descripcion : $('#descripcion').val(),
            direccion : $('#direccion').val(),
            letras_factura :  $('#letras_factura').val(),
            rfc : $('#rfc').val(),
            telefono : $('#telefono').val()
        };

        save(obj);
    }

    function save(data){
                jQuery.ajaxSettings.traditional = true;


        $.ajax({
	      url: "../proxy.php",
	      data: { 
            action : 702, 
            sid : <?php echo $_REQUEST['sid']; ?>,
            payload : $.JSON.encode(data)
           },
	      cache: false,
	      success: function(data){
		        response = jQuery.parseJSON(data);

                if(response.success == false || response.sucess == "false"){
                    window.location = 'sucursales.php?action=editar&id=<?php echo $_REQUEST['sid']; ?>&success=false&reason=' + response.reason;
                    return;
                }

                reason = "La sucursal se ha editado correctamente";
                window.location = "sucursales.php?action=detalles&id=<?php echo $_REQUEST['sid']; ?>&success=true&reason=" + reason;
	      }
	    });
    }
</script>
