<?php

session_start();

if(!isset($_SESSION['userid'])){
	die('<script>window.location = "log.php"</script>');
}
