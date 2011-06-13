<?php

    if(!POS_LOG_TO_FILE){
        ?>
            <h4><img src="../media/icons/close_16.png">&nbsp;El sistema no esta logeando actualmente !</h4>
        <?php
    }
?>

<h2>Archivo de log</h2>
<table border=0 width=100%>
    <tr><td>Archivo</td>        <td><?php echo POS_LOG_TO_FILE_FILENAME; ?></td></tr>
    <tr><td>Tama&ntilde;o</td>  <td><?php echo filesize(POS_LOG_TO_FILE_FILENAME) / 1024 / 1024; ?> Mb</td></tr>
    <tr><td colspan=2 align=center><h4><input type=button value="Descargar archivo" onClick="window.location = 'logs.php?action=descargar';"></h4></td></tr> 
</table>

<h2>Ultima actividad en el log</h2>
<?php

$lines =  Logger::read(1500);

$this_ip = getip();

echo "<pre style='overflow: hidden; padding: 5px; width: 100%; background: whiteSmoke; margin-bottom:5px; font-size:9.5px;'>";

for($a = sizeof($lines) - 1; $a >= 0 ; $a-- ){
	
    $linea = explode(  "|", $lines[$a] );

	if( sizeof($linea) > 1 ){
		
		$ip = $linea[1];
		
		$octetos = explode(".", $ip);
		
		if(trim($this_ip) == trim($ip)){
			echo "<div style='color: BLACK; background-color: rgb( " . $octetos[1] . " , " . $octetos[2] . " , " . $octetos[3] . ")'><strike>" . $lines[$a] . "</strike>\n</div>" ;					
		}else{
			echo "<div style='color: black; background-color: rgb( " . $octetos[1] . " , " . $octetos[2] . " , " . $octetos[3] . ")'>" . $lines[$a] . "\n</div>" ;					
		}
				
	}else{
		
		echo "<div>   ->" . $lines[$a] . "\n</div>" ;		
	}
}
echo "</pre>";




