<?php
/*
	FUNCIONES RELACIONADAS A LA SESION DEL USUARIO
*/
?>
<?php

	function estaLoggeado(){
	
		if(!isset($_SESSION['user']))
		{
			fail('Usuario no esta loggeado');
		}
		else
		{
			ok();
		}
		
		
		return; //no deberia llegar hasta aca
	}

?>
