<?php 

$CONFIG_FILE = "../../server/config.php";

$contents = file_get_contents( $CONFIG_FILE );
$contents = htmlspecialchars($contents);

?>



<h2>Contenido del archivo</h2>
<pre style='overflow: hidden; padding: 5px; width: 100%; background: whiteSmoke;' >

<?php echo $contents; ?>

</pre>

