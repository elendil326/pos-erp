<?php 

class MenuComponent implements GuiComponent
{

	private $items;

	function __construct()
	{
		$this->items = array();
	}


	function addItem($caption, $url)
	{
		array_push($this->items, new MenuItem($caption, $url));
	}


	function renderCmp()
	{
		$out = "<div>";
		foreach($this->items as $item)
		{
			$out .= "<a href=". $item->url ."><div class='POS Boton' >" . $item->caption . "</div></a>";
		}
		$out .= "</div>";
		return $out;
	}

}


class MenuItem{
	public $caption;
	public $url;

	function __construct($caption, $url)
	{
		$this->caption = $caption;
		$this->url = $url;
	}
}