<?php 

class StdPage implements Page{
	

	private $js_urls;
	private $css_urls;
	private $header;
	private $menu;
	private $content;
	private $footer;

	function __construct()
	{
		$this->js_urls 	= '';
		$this->css_urls = "";
		$this->header 	= "";
		$this->menu 	= "";
		$this->content 	= "";
		$this->footer 	= "";
	}

	public function addJs( $url )
	{
		$this->js_urls .= '<script type="text/javascript" src="' . $url . '"></script>';
	}

	public function addCss( $url )
	{
		$this->js_urls .= '<script type="text/javascript" src="' . $url . '"></script>';	
	}

	public function addHeader( $html )
	{
		$this->header = $html;
	}

	public function addMenu( $html )
	{
		$this->menu = $html;
	}

	public function addContent( $html )
	{
		$this->content = $html;
	}
	
	public function addFooter( $html )
	{
		$this->footer = $html;
	}


	public function render()
	{
		?><!DOCTYPE html><html lang="es"><head><?php
		
		print( $this->js_urls );
		print( $this->css_urls );

		?></head><body><?php

		print( $this->header );
		print( $this->menu );
		print( $this->content );
		print( $this->footer );
		
		?></html><?php		
	}
}