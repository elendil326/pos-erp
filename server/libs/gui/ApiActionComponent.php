<?php

/**
  * (C) 2012 Caffeina Software
  *
  *
  * Description:
  *     Component plugable into GuiComponent Pages which
  *     wraps the ajax roundtrip JS involved.
  *
  * Author:
  *     Alan Gonzalez (alan)
  *
  ***/
class ApiActionComponent implements GuiComponent
{
    private static $s_ComponentIndex = 0;

    private $caption;
    private $http_method;
    private $callback;
    private $redirection;
    private $api_name;
    private $arguments;

    function __construct( $api_name = null, $http_method = "POST" )
    {
        $this->caption = null;
        $this->redirection = null;
        $this->api_name = $api_name;
        $this->setHttpMethod( $http_method );
        $this->arguments = array( );
        self::$s_ComponentIndex ++;
    }

    public function  setSuccessRedirection(
        $redirection
        )
    {
        $this->redirection = $redirection;
    }

    public function  setArguments(
        $argument_array
        )
    {
        $this->arguments = $argument_array;
    }

    public function  setArgument(
        $argument,
        $value
        )
    {
        $this->arguments[ $argument ] = $value;
    }

    public function setApiName(
        $api_name
        )
    {
        $this->api_name = $api_name;
    }

    public function setCaption(
        $caption
        )
    {
        $this->caption = $caption;
    }

    public function setHttpMethod(
        $http_method
        )
    {
        $this->http_method = $http_method;
    }

    function renderCmp(
        )
    {
        ?>
        <script>
        function aac<?php echo self::$s_ComponentIndex; ?>sbmt( )
        {
            POS.API.<?php echo $this->http_method; ?>(
                "<?php echo $this->api_name; ?>", 
                {
                    <?php 
                    foreach ( $this->arguments as $a => $v )
                    {
                        echo "\"$a\" : $v ,";
                    }
                    ?>
                },
                {
                    callback : function(a)
                    {
                        <?php if ( $this->redirection ) { ?>
                        window.location = "<?php echo $this->redirection; ?>";
                        <?php } ?>
                    }
                }
            ); 
        }
        </script>
        <div onClick='aac<?php echo self::$s_ComponentIndex; ?>sbmt()' class='POS Boton'><?php echo $this->caption; ?></div>
        <?php
    }
    
}













