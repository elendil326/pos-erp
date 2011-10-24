<?php 

class DAOFormComponent extends FormComponent
{

	

	function __construct( $vo )
	{

		parent::__construct();

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

	public function hideField( $field_name ){
		$sof = sizeof($this->form_fields);
		

		for ($i=0; $i < $sof; $i++) { 

			if( $this->form_fields[$i]->id == $field_name )
			{
				array_splice ( $this->form_fields, $i , 1  );
				
				return true;			
			}
		}
		

		throw new Exception("Field not found in the VO object.");
	}

}

