<?php

    if(!POS_LOG_TO_FILE){
        ?>
            <h4><img src="../media/icons/close_16.png">&nbsp;El sistema no esta logeando actualmente !</h4>
        <?php
    }
?>

<h2>Archivo de log</h2>
<table>
    <tr><td>Archivo</td>        <td><?php echo POS_LOG_TO_FILE_FILENAME; ?></td></tr>
    <tr><td>Tama&ntilde;o</td>  <td><?php echo filesize(POS_LOG_TO_FILE_FILENAME) / 1024; ?> Kb</td></tr>
    <tr><td colspan=2 align=center><h4><input type=button value="Descargar archivo" onClick="window.location = 'logs.php?action=descargar';"></h4></td></tr> 
</table>

<h2>Ultimas 100 lineas del log</h2>
<?php

$lines =  Logger::read() ;
echo "<pre style='overflow: hidden; padding: 5px; width: 100%; background: whiteSmoke; margin-bottom:5px; font-size:9.5px;'>";

for($a = sizeof($lines) - 1; $a >= 0 ; $a-- ){

	echo $lines[$a] . "\n" ;
}
echo "</pre>";




