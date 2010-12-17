<?php

session_start();


if(!isset($_SESSION['userid'])){
	die('<script>window.location = "log.php"</script>');
}


if(!isset($_SESSION['grupo']) || $_SESSION['grupo'] != 1){
	unset( $_SESSION['token'] ); 
	unset( $_SESSION['userid'] );
	unset( $_SESSION['sucursal'] );
	unset( $_SESSION['grupo'] );
	unset( $_SESSION['timeout'] );
	unset( $_SESSION['token'] );
	unset( $_SESSION['HTTP_USER_AGENT'] );
  	die('<script>window.location = "../."</script>');
}

