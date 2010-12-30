<?php

?>

<h1>Log</h1>

<h2>Ultimas 100 lineas del log</h2>
<?php
	$lines =  Logger::read() ;
	echo "<div >";
	
	for($a = sizeof($lines) - 1; $a >= 0 ; $a-- ){
		echo "<div style='margin-bottom:5px; font-size:9px'>" . $lines[$a]."</div>";
	}
	echo "</div>";
