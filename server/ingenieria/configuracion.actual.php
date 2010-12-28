<h1>Archivo de Configuracion</h1>

<h2>Contenido del archivo</h2>
<?php 
$CONFIG_FILE = "../../server/config.php";

$contents = file_get_contents( $CONFIG_FILE );
$contents = htmlspecialchars($contents);
echo "<pre style='overflow: scroll; width: 800px'>";
echo $contents;
echo "</pre>";

