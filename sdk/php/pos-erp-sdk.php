<?php


Class PosErp{

    private static $singleton_obj;
    private $waiting;
    private $instance_token;
    private $url;
    private $incoming_login_results;
    private $at;

    private function __construct($i_token){
        $this->waiting = false;
        $this->instance_token = $i_token;
        $this->url = "http://pos2.labs2.caffeina.mx/";
        $this->incoming_login_results = false;
        $this->at = null;
    }



    function __clone(){


    }



    function __destroy(){


    }




    public static function getInstance( $i_token = null ){

        if(self::$singleton_obj === null){

            if($i_token === null){
                throw new Exception("You must pass your instance token when calling getInstance() for the first time.");
            }

            self::$singleton_obj = new PosErp($i_token);
        }

        return self::$singleton_obj;  
    }




    private function postRequest($url, $data, $referer='') {
     
        // Convert the data array into URL Parameters like a=b&foo=bar etc.
        $data = http_build_query($data);
     
        // parse the given URL
        $url = parse_url($url);
     
        if ($url['scheme'] != 'http') { 
            die('Error: Only HTTP request are supported !');
        }
     
        // extract host and path:
        $host = $url['host'];
        $path = $url['path'];
     
        // open a socket connection on port 80 - timeout: 30 sec
        $fp = fsockopen($host, 80, $errno, $errstr, 30);
     
        if ($fp){
     
            // send the request headers:
            fputs($fp, "POST $path HTTP/1.1\r\n");
            fputs($fp, "Host: $host\r\n");
     
            if ($referer != '')
                fputs($fp, "Referer: $referer\r\n");
     
            fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
            fputs($fp, "Content-length: ". strlen($data) ."\r\n");
            fputs($fp, "Connection: close\r\n\r\n");
            fputs($fp, $data);
     
            $result = ''; 
            while(!feof($fp)) {
                // receive the results of the request
                $result .= fgets($fp, 128);
            }
        }
        else { 
            return array(
                'status' => 'err', 
                'error' => "$errstr ($errno)"
            );
        }
     
        // close the socket connection:
        fclose($fp);
     
        // split the result header from the content
        $result = explode("\r\n\r\n", $result, 2);
     
        $header = isset($result[0]) ? $result[0] : '';
        $content = isset($result[1]) ? $result[1] : '';
     
        // return as structured array:
        return array(
            'status' => 'ok',
            'header' => $header,
            'content' => $content
        );
    }

    



    private function BeforeCall( $api_name, $toSendParams ){
        if($api_name == "api/sesion/iniciar"){
            $this->incoming_login_results = true;

        } elseif( !is_null($this->at)){

            //there is an `at` associated with this session
            //lets check if there is at defined in the params
            if(array_key_exists("at", $toSendParams)){
                //he has his own at, send that one

            }else{
                //lets use the one we have
                $toSendParams["at"] = $this->at; 

            }
            
            
        }

        return $toSendParams;
    }




    private function AfterCall( $result ){

        //decode json
        $response = json_decode($result);

        if($this->incoming_login_results){
            if($response->login_succesful){
                $this->at = $response->auth_token;
            }
        }
        
        $this->incoming_login_results = false;

        return $response;

    }





    private function ApiCall($method, $address, $params ){

        $params = $this->BeforeCall($address, $params );

        if(is_null($this->instance_token)){
            throw new Exception("You have not supplied any instance token", 1);
        }



        $this->BeforeCall();




        if($method === "POST"){
           $r = $this->postRequest($this->url . "/front_ends/" . $this->instance_token . "/" . $address , $params );
            return $this->AfterCall( $r['content'] );


        }else if($method === "GET"){
            //convert params to ?x=y& format
           $params = http_build_query($params);
           $r = file_get_contents($this->url . "/front_ends/" . $this->instance_token . "/" . $address . "/?" . $params);
           return $this->AfterCall( $r );


        }else{
            throw new Exception("Http methdos must be POST or GET", 1);
            

        }

        
    }



    public function POST($api_name, $parameters){
        return $this->ApiCall( "POST", $api_name, $parameters );
    }


    public function GET($api_name, $parameters){
        return $this->ApiCall( "GET", $api_name, $parameters );
    }


}






/*



$api = PosErp::getInstance("1e65da5dbe04139ee8d810568f1fd406");


$api->POST("api/sesion/iniciar", array( 
        'password' => '123',
        'usuario' => '1'
     ));


$api->POST("api/cliente/nuevo", array( 
        'razon_social' => 'Alan Gonzalez'
     ));



exit;

*/
