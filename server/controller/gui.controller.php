<?php



class GuiController{



	static function getHtmlHead()
	{
		?>
			<META http-equiv="Content-Type" content="text/html; charset=UTF-8">

			<title>POS</title>
			<script src="http://api.caffeina.mx/jquery/jquery-1.4.2.min.js" type="text/javascript"></script>

			<script>
				$.noConflict();
			</script>
			
			<script type="text/javascript" charset="utf-8" src="http://api.caffeina.mx/prototype/prototype.js"></script>		

			<script src="http://api.caffeina.mx/uniform/jquery.uniform.min.js" type="text/javascript" charset="utf-8"></script> 
			<link rel="stylesheet" href="http://api.caffeina.mx/uniform/css/uniform.default.css" type="text/css" media="screen">
			<script type="text/javascript" charset="utf-8">jQuery(function(){jQuery("input, select").uniform();});</script>
				
			<link rel="stylesheet" type="text/css" href="./../getResource.php?mod=admin&type=css">
			<script type="text/javascript" src="./../getResource.php?mod=admin&type=js"></script>

			<link href="http://api.caffeina.mx/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
			<script src="http://api.caffeina.mx/facebox/facebox.js" type="text/javascript"></script>
		<?php
	}





	protected function printLoginBox()
	{
		?>
			<div align='center' class="">
				<form id="login">

					<table border="1" cellspacing="5" cellpadding="5">

					<tr>
						<td rowspan='5'>
							<img width='256' height='256' src='../media/safe.png'>
						</td>
					</tr>

					<tr>
						<td></td>
						<td colspan=2>
							<h2>Inicio de sesion</h2>
						</td>
					</tr>

					<tr>
						<td></td>
						<td align='right'>
							 Usuario
						</td>
						<td>
							<input type="text" id="usr" size="40"/>
						</td>
					</tr>
					<tr>
						<td></td>
						<td align='right'>
							 Contrase&ntilde;a
						</td>
						<td>
							<input type="password" id="pass" size="40" onkeypress="handleKeyPress(event)"/>
						</td>
					</tr>
					<tr valign='top'>
						<td></td>
						<td>
						</td>
						<td align='right'>
							<input type="button" onclick="doStart()" value="Ingresar" onkeypress="handleKeyPress(event)"/>
						</td>
					</tr>
					</table>
				</form>
		    </div>
		<?php
	}


}






class AdminGuiController extends GuiController
{
	



}




class IngenieroGuiController extends GuiController
{
	

	

}




class JediGuiController extends GuiController
{
	


	protected function printLoginBox( )
	{
		parent::printLoginBox(  );

	}


	public function printLoginWindow()
	{

		self::printLoginBox();
	}

}