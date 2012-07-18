<?php 

$CONFIG_FILE = "../../server/config.php";

$contents = file_get_contents( $CONFIG_FILE );
$contents = str_replace( "<?php", "", $contents );
$contents = htmlspecialchars($contents);



?>



<h2>Contenido del archivo</h2><br>
<textarea style='overflow: scroll; padding: 5px; width: 100%; background: whiteSmoke; height: 1000px' id="raw">

<?php echo $contents; ?>

</textarea>

<script>
	function fill(){

		jQuery( "#contents" ).val(jQuery( "#raw" ).val());
		return true;
	}
</script>
<h4>
	<form action="configuracion.php?action=save" method="post" onsubmit="return fill();">
		<input type=hidden name="contents" id="contents">
		<input type=submit  value="guardar" >
	</form>
</h4>