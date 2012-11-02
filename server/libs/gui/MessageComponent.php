<?php 

class MessageComponent implements GuiComponent{
	
	private $msg;

	function __construct($msg){
		$this->msg = $msg;
	}
	
	function renderCmp(){
		return "<p >" . $this->msg . "</p>";
	}


}