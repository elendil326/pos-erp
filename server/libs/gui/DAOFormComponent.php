<?php 

class DAOFormComponent extends FormComponent{

	private $api_method_to_call;

	function __construct( $vo ){ 
		if(is_null($vo)){
			
			throw new InvalidDataException();
		}
		
		parent::__construct();
		
		$this->api_method_to_call = NULL;
		
		if(is_array($vo)){
			for ($a=0; $a < sizeof( $vo ); $a++) 
			{ 
				$fields = json_decode( $vo[$a]->__toString() );

				foreach($fields as $k => $v)
				{

					$caption = ucwords(str_replace ( "_" , " " , $k ));

					parent::addField( $k, $caption, "text", $v, $k );
					
				}							
			}
				
		}else{


			$fields = json_decode( $vo->__toString() );
			
			foreach($fields as $k => $v)
			{

				$caption = ucwords(str_replace ( "_" , " " , $k ));

				parent::addField( $k, $caption, "text", $v, $k );
				
			}
		}
		



	}



}