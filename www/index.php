<?php


	if(is_file("../server/config.php")){
		//installed
		?>
			<div align=center><img src="media/intro.png"></div>
		<?php
	}else{
		//not installed
		?>
			<h1>Bienvenido a POS</h1>
			<h2>Proceso de instalacion</h2>
		<?php
	}