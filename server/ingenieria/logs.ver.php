<?php

?>

<h1>Log</h1>

<h2>Ultimas 100 lineas del log</h2>
<?php
	$lines =  Logger::read() ;
	echo "<div style='font-size:9px'>";
	
	for($a = sizeof($lines) - 1; $a >= 0 ; $a-- ){
		echo $lines[$a]."<br>";
	}
	echo "</div>";
