<?php 

class StdComponentPage extends StdPage{
	

	protected $components;
	

	function __construct()
	{
		parent::__construct();

		parent::addCss('http://api.caffeina.mx/ext-4.0.0/resources/css/ext-all.css');
		parent::addJs( 'http://api.caffeina.mx/ext-4.0.0/ext-all.js');

		$this->components = array();
	}


	private function dieWithError( $on_error_message ){
		$this->components = array(
			new TitleComponent("Error", 2),
			new TitleComponent( $on_error_message , 4)
		);
		
		
		
		if( isset($_SERVER['HTTP_REFERER'])){
			array_push(
					$this->components,
					new TitleComponent( "Regrese a <a href='".$_SERVER['HTTP_REFERER']."'>la pagina anterior.</a>", 4)
				);
		}
		
		$this->render();
		exit;
	}

	public function requireParam(  $param_name, $method = "GET", $on_error_message = "ERROR", $validator_fn = null ) 
	{
		switch( $method ){
			case "GET" : 
				if(	!isset($_GET[$param_name] ) ){
					self::dieWithError( $on_error_message );
				}
					
				if( !is_null( $validator_fn ) ){
					if(is_null( call_user_func( $validator_fn, $_GET[$param_name])  )){
						self::dieWithError( $on_error_message );							
					}
				}

			break;
			case "POST":
				if(!isset($_POST[$param_name])){
				
				}
			break;
			default:
				throw new Exception("Invalid method. Should be POST or GET");
		}

	}

	public function addComponent( $cmp )
	{

		if( $cmp instanceof GuiComponent ){
			//go ahead
			array_push( $this->components, $cmp );

		}else if( is_string($cmp)){
			array_push( $this->components, new FreeHtmlComponent( $cmp ) );			
			

		}else{
			throw new Exception("This is not a valid component.");

		}
	}


	public function addContent($html)
	{
		throw new Exception("You may not add HTML to this Component based Page. Please use the addComponent() method. ");
	}


	public function render()
	{
		
		?><!DOCTYPE html><html lang="es"><head><?php
		
		print( $this->js_urls );
		print( $this->css_urls );
		print( "<title>" . $this->page_title . "</title>");

		?></head><body style="background-color: rgb( 53, 98, 162 )" ><?php

		foreach( $this->components as $cmp )
		{
			echo $cmp->renderCmp();
		}
		
		?></body></html><?php
		

	}



}