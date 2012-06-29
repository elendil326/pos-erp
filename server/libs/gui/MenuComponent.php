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
    
    function addMenuItem($menu_item)
    {
        if($menu_item instanceof MenuItem){
            array_push($this->items, $menu_item);    
        }else{
            throw new Exception("arg is not instance of MenuItem");
        }
        
    }
    
    
    function renderCmp()
    {


        $out = "<div style='margin-bottom: 5px'>";
        
        foreach ($this->items as $item) {
        
        	if($item->css_classes === null) $item->css_classes = "";

            if (is_null($item->url)) {
                $out .= "<div class='POS Boton ". $item->css_classes ."' ";
                if (!is_null($item->on_click))
                    $out .= "onclick='" . $item->on_click["caption"] . "();'";
                $out .= ">" . $item->caption . "</div>";

            }else{
            	$out .= "<a href=" . $item->url . "><div class='POS Boton ".$item->css_classes."' >" . $item->caption . "</div></a>";

            }
                
            $out .= "<script>";
            
            if (!is_null($item->on_click)) {
                $out .= $item->on_click["function"];
            }


            if (!is_null($item->send_to_api)) {
                $out .= "function sendToApi_" . $item->name . "( params ){";
                $out .= "	POS.API." . $item->send_to_api_http_method . "(\"" . $item->send_to_api . "\", params, ";
                $out .= "	{";
                $out .= "		callback : function( a ){ ";
                $out .= "			";
                $out .= "			/* remove unload event */";
                $out .= "			window.onbeforeunload = function(){ return;	};";
                $out .= "				/* console.log('OKAY'); */ ";
                
                if (!is_null($item->send_to_api_callback))
                    $out .= "			" . $item->send_to_api_callback . "( a );";
                
                if (!is_null($item->send_to_api_redirect))
                    $out .= "			window.location = '" . $item->send_to_api_redirect . "';";
                
                $out .= "			";
                $out .= "			";
                $out .= "	 	}";
                $out .= "	});";
                $out .= "}";
            }
            $out .= "</script>";
        }
        $out .= "</div>";
        return $out;
    }
    
}



















class MenuItem
{
    public $caption;
    public $url;
    public $send_to_api;
    public $send_to_api_http_method;
    public $send_to_api_callback;
    public $send_to_api_redirect;
    public $api_param;
    public $on_click;
    public $name;
    public $css_classes;
    
    function __construct($caption, $url)
    {
        $this->caption     = $caption;
        $this->url         = $url;
        $this->css_classes = null;
    }
    
    public function addName($name)
    {
        $this->name = $name;
    }
    
    
    public function addClass($css)
    {
        $this->css_classes = $css;
    }
    
    
    
    public function addOnClick($caption, $js_function)
    {
        $this->on_click = array(
            "caption" => $caption,
            "function" => $js_function
        );
    }
    
    public function addApiCall($method_name, $http_method = "POST")
    {
        if (!($http_method === "POST" || $http_method === "GET")) {
            throw new Exception("Http method must be POST or GET");
        }
        
        $this->send_to_api             = $method_name;
        $this->send_to_api_http_method = $http_method;
        
    }
    
    /**
     * Esta es una funcion en js que se llamara 
     * cuando la llamada al api sea exitosa.
     *
     * */
    public function onApiCallSuccess($jscallback)
    {
        $this->send_to_api_callback = $jscallback;
    }
    
    /**
     * 
     * Redirect to a new page on apicall sucess
     * 
     * */
    public function onApiCallSuccessRedirect($url, $send_param = null)
    {
        $this->send_to_api_redirect = $url;
    }
    
}