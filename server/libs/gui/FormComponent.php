<?php 

class FormComponent implements GuiComponent
{

	protected $form_fields;
	protected $submit_form;
	protected $on_click;
	protected $send_to_api;

	function __construct(  )
	{
		
		$this->send_to_api 		= null;
		$this->on_click 		= null;
	 	$this->submit_form 		= null;
		$this->form_fields      = array(  );
	}


	function addField( $id, $caption, $type, $value = "", $name = null )
	{
		array_push( $this->form_fields, new FormComponentField($id, $caption, $type, $value, $name ) );
	}


	function renderCmp()
	{
		$html = "";
		
		if( !is_null($this->send_to_api)){
			
			$html.= "<script>";
			$html .= "function sendToApi( ){";
			$html.= "	POS.API.POST(\"". $this->send_to_api ."\", ";
			$html.= "	{" ;
			
			foreach( $this->form_fields as $f )
			{
				$html .= "	" . $f->id . " : Ext.get('". $f->id . "').getValue(),\n" ;
			}
			
			$html.= "	},{";
			$html.= 		"callback : function( a ){ console.log(a ); }";
			$html.= "	});";
			$html.= "}";
			$html.= "</script>";			
			
		}
		

		
		$html .= "<table>";

		if( !is_null ( $this->submit_form ) ){
			$html .= "<form method='". $this->submit_form["method"] . "' action='". $this->submit_form["submit_form_url"] . "'>";

		}else{
			$html .= "<form >";	
			
		}
		

		foreach( $this->form_fields as $f )
		{
			if($f->type !== "hidden"){
				$html .= "<tr><td>";
				
				if($f->obligatory === true) echo "<b>";
				$html .= $f->caption;
				if($f->obligatory === true) echo "</b>";
								
				$html .= "</td><td>";				
			}


			$html .= "<input id='" . $f->id .  "' name='" . $f->name .  "' value='" . $f->value .  "' type='". $f->type ."' >";

			
			if($f->type !== "hidden"){
				$html .= "</td></tr>";	
			}
		}


		if( !is_null ( $this->submit_form ) ){
			$html .= "<tr><td>";
			$html .= "</td><td align=right>";
			$html .= "<input value='" . $this->submit_form["caption"] .  "' type='submit'  >";
			$html .= "</td></tr>";
		}




		if( !is_null ( $this->on_click ) ){

			$html .= "<tr><td>";
			$html .= "</td><td align=right>";
			$html .= "<input value='" . $this->on_click["caption"] .  "' type='button' onClick='". $this->on_click["function"] ."' >";
			$html .= "</td></tr>";
		}

		if( !is_null($this->send_to_api)){
			
			$html .= "<tr><td>";
			$html .= "</td><td align=right>";
			$html .= "<input value='Aceptar' type='button' onClick='sendToApi()' >";
			$html .= "</td></tr>";			
			
		}

		$html .= "</form>";
		$html .= "</table>";

		return $html;

	}


	public function addSubmit( $caption, $submit_form_url = "", $method = "GET"){
		$this->submit_form = array( "caption" => $caption, "submit_form_url" => $submit_form_url, "method" => $method );
	}


	public function addOnClick( $caption, $js_function){
		$this->on_click = array( "caption" => $caption, "function" => $js_function );
	}


	public function addApiCall( $method_name ){
		$this->send_to_api = $method_name;
		
	}



	public function renameField( $field_array ){
		
		$found = false;
		foreach ($field_array as $old_name => $new_name) {
			$found = false;
			$sof = sizeof( $this->form_fields );

			for ($i=0; $i < $sof; $i++) { 
				
				if( $this->form_fields[$i]->id === $old_name )
				{
					$this->form_fields[$i]->id = $new_name;
					$this->form_fields[$i]->caption = ucwords(str_replace ( "_" , " " , $new_name ));

					
					$found = true;
					break;
				}//if

			}//for
			
			if($found === false) throw new Exception("Field `".$old_name."` not found in the VO object.");
			
		}//foreach field in the array
	}




	public function makeObligatory( $field_array ){
		
		foreach ($field_array as $field) {
			
			$sof = sizeof( $this->form_fields );

			for ($i=0; $i < $sof; $i++) { 

				if( $this->form_fields[$i]->id === $field )
				{
					$this->form_fields[$i]->obligatory = true;
				}//if

			}//for
		}
	}
}




class FormComponentField{

	public $id;
	public $caption;
	public $type;
	public $value;
	public $name;
	public $obligatory;

	public function __construct( $id, $caption, $type, $value = "", $name = null, $obligatory = false ){
			$this->id 		= $id;
			$this->caption 	= $caption;
			$this->type 	= $type;
			$this->value 	= $value;
			$this->name 	= $name;
			$this->obligatory 	= $obligatory;
	}
}


