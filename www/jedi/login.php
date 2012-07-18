<?php

	define("I_AM_JEDI", true);

	define("I_AM_LOGIN", true);

	
	require_once("../../server/jedi/check_session.php");

	require_once("../../server/jedi/bootstrap.php");
	
	//intentare iniciar sesion
	if(isset($_POST["request_login"]) && $_POST["request_login"]){
		
		if(JediLogin::login( $_POST["nickname"] , $_POST["pass"])){
			//login correcto !
			die(header("Location: index.php"));
		}else{
			//login fallido !
			define("WRONG_CREDS", true);
			
		}
	}
	
	//intentare cerrar sesion
	if(isset($_GET["request_logout"]) && $_GET["request_logout"]){
		
		JediLogin::logout(  );
		
	}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" >
<head>
	<META http-equiv="Content-Type" content="text/html; charset=UTF-8">

	<title>POS | Jedi login</title>

</head>


<body>
	
<p>
  	<?php
		echo "hola " . getip();
	?>
</p>
	
  	<?php
	if(defined("WRONG_CREDS") && WRONG_CREDS){
		ECHO "ups";
	}
	?>
	<form action="login.php" method="post" accept-charset="utf-8">
		<label for="nickname">nickname</label><input type="text" name="nickname" value="" id="nickname">
		<label for="pass">pass</label><input type="password" name="pass" value="" id="pass">
		<input type="hidden" name="request_login" value="1" id="request_login">
		<p><input type="submit" value="Continue &rarr;"></p>
	</form>
</body>
</HTML>
