<?php

session_start();

if(!isset($_SESSION['userid'])){
	die("Accesso denegado");
}