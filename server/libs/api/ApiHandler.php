<?php


require_once("ApiOutputFormatter.php");
require_once("ApiExposedProperty.php");
require_once("ApiHttpErrors.php");
require_once("ApiException.php");


require_once("StringValidator.php");
require_once("NumericRangeValidator.php");
require_once("NumericValidator.php");
require_once("DateRangeValidator.php");
require_once("DateValidator.php");
require_once("EnumValidator.php");
require_once("HtmlValidator.php");
require_once("CustomValidator.php");


/*
 * Basic Abstraction of an API
 */

abstract class ApiHandler
{
    // Container of input parameters
    protected $request;
    
    // Containter of output parameters
    protected $response;
    
    // Cache of who calls the API
    protected $user_id;
    
    // Holder of error dispatcher
    protected $error_dispatcher;
    
    // Cache of auth token
    protected $auth_token;
    
    // Cache of user roles
    protected $user_roles;
    
    // Holder of roles of each api
    protected $api_roles;
     
    public function __construct() 
    {        
        
        // Get an error dispatcher
        $this->error_dispatcher = ApiHttpErrors::getInstance();
        
        // Declare response as an array
        $this->response = array();
                
                
    }
                      
    
    protected function CheckAuthorization()
    {

		//needs to be logged in
		if(!SesionController::isLoggedIn()){
        	throw new ApiException( $this->error_dispatcher->invalidAuthToken() );
		}
		
		return true;
		
		/*
		
		Logger::warn("Aqui hay un notice de php suprimido ApiHandler.php->CheckAuthorization()");
				
		$token = null;
		
		
		if( @isset($_GET["auth_token"] )  ){
			$token = $_GET["auth_token"];
		}
		
		if( @isset($_POST["auth_token"] )  ){
			$token = $_POST["auth_token"];
		}
		
		if(is_null($token)){
			Logger::log("No se ha enviado un auth_token");
        	throw new ApiException( $this->error_dispatcher->invalidAuthToken() );			
		}

		//buscar el token
		$r = SesionDAO::search( new Sesion( array( "auth_token" => $token ) ) );
		
		if(sizeof($r) == 1) return true;
		
		Logger::log("El auth_token no se encontro en la BD");
        throw new ApiException( $this->error_dispatcher->invalidAuthToken() );
		*/
    }

    
    protected function CheckPermissions()
    {                
        
        if ($this->api_roles === BYPASS)
        { 
            return true;
        }

		
        // Rol was not found
        throw new ApiException($this->error_dispatcher->forbiddenSite());
    }


    protected function ValidateRequest()
    {
     
        // If we didn't get any request, asume everything is OK.
        if(is_null($this->request)) {
            Logger::log("We didn't get any request, asume everything is OK");
            return;
        }
        
        // Validate all data 
        foreach($this->request as $parameter)
        {

            if ( !$parameter->validate() )
            {
                // In case of missing or validation failed parameters, send a BAD REQUEST 
                Logger::error( $parameter->getError() );
                throw new ApiException( $this->error_dispatcher->invalidParameter( $parameter->getError()) );   
            }
        }
    }
    
    protected abstract function DeclareAllowedRoles();    
    
    protected abstract function GetRequest();
    
    protected abstract function GenerateResponse();
        
    
    // This function should be called 
    public function ExecuteApi()
    {
        try
        {   
            $this->CheckAuthorization();
           
            // Each API should declare its allowed roles            
            $this->api_roles = $this->DeclareAllowedRoles();
            

            $this->CheckPermissions();
                        
            // Process input

            $this->GetRequest();       

            
            $this->ValidateRequest();

            // Generate output
            $this->GenerateResponse();

            $this->response["status"] = "ok";
            
            return $this->response;       
        }
        catch (ApiException $e)
        {
            // Propagate the exception
			Logger::error($e);
            throw $e;
        }
        
    }
}

?>
