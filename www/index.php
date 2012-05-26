<?php

	if(!class_exists("PHPUnit_Runner_Version")){
		define("BYPASS_INSTANCE_CHECK", true);
		require_once("../server/bootstrap.php");
	}
	
?><html>
	<head>
	<title>POS ERP</title>
	</head>
	<link rel="stylesheet" type="text/css" href="media/main_site/s.css">

	
	<body>


	    <div class="topbar js-topbar">
	      <div class="global-nav" data-section-term="top_nav">
	        <div class="global-nav-inner">
	          <div class="container">
				
	            <ul class="nav js-global-actions">
	              <li class="home">
					<img class="" style="width: 40px; float:left" src="media/main_site/caffeina-logo.png">
	                <a href="http://caffeina.mx">
	                  <span><i class="nav-home-logged-out"></i></span>
	                </a>
	              </li>
	            </ul>
	            <div class="pull-right">
	              <ul class="nav secondary-nav">
	                <li><a href="//caffeina.com" id="homeLink">Home </a></li>
	              </ul>
	            </div>
	          </div>
	        </div>
	      </div>
	    </div>


	    <div class="blue-sky">
	      <div class="body-content">
		
		
			<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - -  -->
			
			<?php
			 	if(isset($_GET["t"])){
					$t = $_GET["t"];
				}else{
					$t = "start";
				}
				
				switch($t){ 
					 case "thanks" : ?>	
					
					
					<?php
					
					if(!isset($_POST["email"])){
						//error;
						?>
						<h1>Whoops</h1>
						<?php
						break;
					}
					
					
					$r = InstanciasController::requestDemo( $_POST["email"] );
					
					if($r === false){
						//
						?>
						<h1>Whoops</h1>
						<?php
						break;
					}
					
					
					?>
					<table border='0'>
					<tr>
						<td rowspan='2'>
							<img class="" style="width: 100px; float:left" src="media/main_site/caffeina-logo.png">
						</td>
						<td>
							<h1>Caffeina POS ERP</h1>
						</td>
					</tr>
					<tr>
						<td>
							<p>
								&nbsp;&nbsp;Planificaci&oacute;n de recursos empresariales en la nube.
							</p>
						</td>
					</tr>
					</table>
					<div style="margin-left: 130px">
						<h2><strong>&#161; Muchas gracias por su interes en POS ERP !</strong></h2>
						<p>Por favor revise su bandeja de correo electronico para continuar con la instalacion de su nueva instancia.</p>

					</div>					
				<?php break; ?><!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
				
				
				
				<?php case "by_email" : ?>
				<?php
					$r = InstanciasController::validateDemo( $_GET["key"] );
				?>
				<?php break; ?><!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
				
				
				
				<?php case "signup" : ?>
				<table border='0'>
				<tr>
					<td rowspan='2'>
						<img class="" style="width: 100px; float:left" src="media/main_site/caffeina-logo.png">
					</td>
					<td>
						<h1>Caffeina POS ERP</h1>
					</td>
				</tr>
				<tr>
					<td>
						<p>
							&nbsp;&nbsp;Planificaci&oacute;n de recursos empresariales en la nube.
						</p>
					</td>
				</tr>
				</table>
				<div style="margin-left: 130px">
					<h2><strong>Solicitar una instancia de 30 dias</strong></h2>
					<form action="?t=thanks" class="signup" method="post">
						<div class="placeholding-input">
							<input type="text" class="text-input" autocomplete="off" name="email" maxlength="20" placeholder="Correo electronico">
							<button type="submit" class="btn signup-btn">
							Solicitar instancia </button>
						</div>
					</form>
				</div>
				<?php break; ?><!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
				
				
				
				
				
				
				<?php case "welcome" : ?>	
				<?php default : ?>	
				<table border=0>
		          <tr>
		            <td rowspan=2>
		              <img 
						class="" 
						style="width: 150px; float:left" 
						src="media/main_site/caffeina-logo.png">
		            </td>
		            <td>
		              <h1>Caffeina POS ERP</h1>
		            </td>
		          </tr>
		          <tr>
		            <td>
						<p>&nbsp;&nbsp;Planificaci&oacute;n de recursos empresariales en la nube.</p>
					</td>
		          </tr>
		        </table>


		        <div class="">
		          <a href="?t=signup"><img class="" src="media/main_site/oferta.png"></a>
		        </div>
				<?php break; ?><!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
				<?php }?>







	        <div class="footer" style="margin-top:30px;">

	          <ul class="links">
	            <li class="first">&copy; 2012 Caffeina Software |</li>
	            <li><a href="/about">Acerca de caffeina</a></li>
	            <li><a href="/about">Contacto</a></li>
	            <li><a href="/help">Ayuda</a></li>
	          <li><a href="http://status.caffeina.com/">Status</a></li>
	          </ul>
	        </div>
	      </div>
	    </div>


	</body>
	</html>