<?php


Class PosErp{

    private static $singleton_obj;
    private $waiting;
    private $instance_token;
    private $url;


    private function __construct(){
        $this->waiting = false;
        $this->instance_token = null;
        $this->url = "asdf";
    }

    function __clone(){}

    function __destroy(){}




    public static function getInstance(){
        if(self::$singleton_obj === null){
            self::$singleton_obj = new PosErp();
        }

        return self::$singleton_obj;  
    }


    public function setInstance($token){
        $this->instance_token = $token;
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


    private function ApiCall($method, $address){

        if(is_null($this->instance_token)){
            throw new Exception("You have not supplied any instance token", 1);
        }

        if($method === "POST"){

        }else if($method === "GET"){

        }else{
            throw new Exception("Http methdos must be POST or GET", 1);
            
        }

    }



    public function NuevoCliente($uno, $dos){

    }

}










$api = PosErp::getInstance("aslkdfjaslkdfjlkasjdfs");
$api->SesionIniciar(23, 425435);
$api->NuevoCliente(2,4);



exit;








$url_base = "http://127.0.0.1/caffeina/v1_5/www/front_ends/1e65da5dbe04139ee8d810568f1fd406";

$post_data = array(
    'password' => '123',
    'usuario' => '1',
	'request_token' => true
);

$result = post_request($url_base . '/api/sesion/iniciar', $post_data);
 
if ($result['status'] != 'ok'){

    echo 'A error occured: ' . $result['error'];  
	exit;

 
}



$response = json_decode($result['content']);




if(!$response->login_succesful){
	echo "invalida";
	exit;
}



define("AUTH_TOKEN", $response->auth_token);





$post_data = array(
    'at' => AUTH_TOKEN,
    'razon_social' => $_POST["rs"]
);


$result = post_request($url_base . '/api/cliente/nuevo', $post_data);

if ($result['status'] != 'ok'){

    echo 'A error occured: ' . $result['error'];  
	exit;

 
}





/*
var obj = {
		razon_scocial 		: $("#rs"),
		representante_legal	: $("#rl"),
		rfc					: $("#rfc"),
		telefono1			: $("#t1"),
		telefono2			: $("#t2"),
		email 				: $("#email"),
		calle				: $("#calle"),
		numero_interior		: $("#ni"),
		numero_exterior		: $("#ne"),
		colonia				: $("#colonia"),
		ciudad				: $("#ciudad"),
		codigo_postal 		: $("#cp")
};

$.ajax({
  url: 'nuevo_socio.php',
  data : obj,
  method : 'post',
  success: function(data) {
	console.log(data);
  }
});


*/