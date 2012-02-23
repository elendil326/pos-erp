<?php

class PosComponentPage extends StdComponentPage{


	private $main_menu_json;
	private $title;
	
	function __construct( $title = "Gerencia"){
		$this->title = $title;
		parent::__construct( $title );
		
	}

	private function _renderWrapper()	{
		?>
		<!DOCTYPE html>
		<html xmlns="http://www.w3.org/1999/xhtml" lang="en" >
		<head>
		<title><?php echo $this->title; ?></title>

			<link rel="stylesheet" type="text/css" href="http://api.caffeina.mx/ext-4.0.0/resources/css/ext-all.css" /> 
		    <script type="text/javascript" src="http://api.caffeina.mx/ext-4.0.0/ext-all-debug.js"></script>
			<link type="text/css" rel="stylesheet" href="../../css/basic.css"/>
			<link type="text/css" rel="stylesheet" href="css/basic.css"/>
			<script type="text/javascript" charset="utf-8" src="http://api.caffeina.mx/ext-4.0.0/examples/ux/grid/TransformGrid.js"></script>
			
			<script type="text/javascript" charset="utf-8">
				if(HtmlEncode===undefined){var HtmlEncode=function(a){var b=a.length,c=[];while(b--){var d=a[b].charCodeAt();if(d>127||d>90&&d<97){c[b]="&#"+d+";"}else{c[b]=a[b]}}return c.join("")}} 
			</script>
		</head>
		<body class="">
		<!-- <div id="FB_HiddenContainer" style="position:absolute; top:-10000px; width:0px; height:0px;"></div> -->
		<div class="devsitePage">
			<div class="menu">
				<div class="content">
					<a class="logo" href="index.php">
						
						<!--<img class="img" src="../../../media/N2f0JA5UPFU.png" alt="" width="166" height="17"/>-->
						<div style="width:166px; height: 17px">
							
						</div>
					</a>

					<?php echo $this->_renderTopMenu(); ?>
					


					<a class="l">
						<img style="margin-top:8px; display: none;" id="ajax_loader" src="../../../media/loader.gif">
					</a>

					<!--
					<div class="search">
						<form method="get" action="/search">
							<div class="uiTypeahead" id="u272751_1">
								<div class="wrap">
									<input type="hidden" autocomplete="off" class="hiddenInput" name="path" value=""/>
									<div class="innerWrap">
										<span class="uiSearchInput textInput">
										<span>
										
										<input 
											type="text" 
											class="inputtext DOMControl_placeholder" 
											name="selection" 
											placeholder="Buscar" 
											autocomplete="off" 
											onfocus="" 
											spellcheck="false"
											title="Search Documentation / Apps"/>
										<button type="submit" title="Search Documentation / Apps">
										<span class="hidden_elem">
										</span>
										</button>
										</span>
										</span>
									</div>
								</div>
											
								


							</div>
						</form>
					</div>
					-->
					<div class="clear">
					</div>
				</div>
			</div>
			<div class="body nav">
				<div class="content">

					<!-- ----------------------------------------------------------------------
									MENU
						 ---------------------------------------------------------------------- -->
					<div id="bodyMenu" class="bodyMenu"><div class="toplevelnav">
						<?php $this->_renderMenu(); ?>
					</div></div>
					

					
					<!-- ----------------------------------------------------------------------
									CONTENIDO
						 ---------------------------------------------------------------------- -->
					<div id="bodyText" class="bodyText">
						<div class="header">
							<div class="content">
								<?php  $this->_renderComponents(); ?>
							</div>
						</div>


						<div class="mtm pvm uiBoxWhite topborder">
							<div class="mbm"></div>
							<!--<abbr class="timestamp">Generado <?php echo date("r",time()); ?></abbr>-->
						</div>
					</div>

					<div class="clear"></div>

				</div>
			</div>
			<div class="footer">
				<div class="content">
					
					<div class="copyright">
					Caffeina Software
					</div>

					<div class="links">
						<a href="">Admin</a>
						<a href="">API Publica</a>
						<a href="">Ayuda</a>
					</div>
				</div>
			</div>

			
		</div>

		</body>
		</html>
	
		<?php
	}

	protected function _renderTopMenu( ){ return ""; }
	
	protected function _renderMenu( )	{ return ""; }
	
	private function _renderComponents(){

			foreach( $this->components as $cmp )
			{
				echo $cmp->renderCmp();
			}

	}

	public function render(){
		$this->_renderWrapper();
		exit;
	}


}