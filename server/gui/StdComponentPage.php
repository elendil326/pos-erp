<?php 

class StdComponentPage extends StdPage{
	

	private $components;
	

	function __construct()
	{
		$this->components = array();
	}

	public function addComponent( $cmp )
	{
		if( $cmp instanceof GuiComponent ){
			//go ahead
			array_push( $this->components, $cmp );

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
		
		$html = "";

		foreach( $this->components as $cmp ){
			$html .= $cmp->renderCmp();
		}

		parent::addContent( $html );

		parent::render();
	}



}