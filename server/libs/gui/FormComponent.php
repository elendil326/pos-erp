<?php 

class FormComponent implements GuiComponent{

	protected 	$form_fields;
	protected	$submit_form;
	protected 	$on_click;
	protected 	$send_to_api;
	private 	$send_to_api_http_method;

	function __construct(  ){
		
		$this->send_to_api 		= null;
		$this->on_click 		= null;
	 	$this->submit_form 		= null;
		$this->form_fields      = array(  );
	}

	function addField( $id, $caption, $type, $value = "", $name = null ){
		array_push( $this->form_fields, new FormComponentField($id, $caption, $type, $value, $name ) );
	}

	private function removeDuplicates(){
		usort( $this->form_fields, array( "FormComponentField", "idSort"  ));
		$top_i = 0;

		
		for ($i=1; $i < sizeof( $this->form_fields ); $i++) {
			if( ( $this->form_fields[$i]->id != $this->form_fields[$top_i]->id) ){
				$this->form_fields[++$top_i] = $this->form_fields[$i];
			}
		}
		
		$this->form_fields =  array_slice( $this->form_fields, 0,  $top_i+1, true);
		
	}

	function renderCmp(){
		
		//remove fields with the same id
		$this->removeDuplicates();
		
		//sort fields by the necesary attribute
		usort( $this->form_fields, array( "FormComponentField", "obligatorySort"  ));
		
		$html = "";
		
		if( !is_null($this->send_to_api)){
			
			$html.= "<script>";
			$html.= "var obligatory = [];";
			foreach( $this->form_fields as $f )
			{
				if( $f->obligatory )
					$html .= "obligatory.push( '". $f->id . "' );";
			}
				
			$html .= "function getParams(){";
			
			$html .= "var p = {};";
				foreach( $this->form_fields as $f )
				{
					//$html .= "console.log('". $f->id . "', Ext.get('". $f->id . "').getValue() );";
					$html .= "if( Ext.get('". $f->id . "').getValue().length > 0 ){ p." . $f->id . " = Ext.get('". $f->id . "').getValue() ; } else{" ;
						//else si no esta lleno de datos, vamos a buscarlo en los obligatorios, 
						//si esta en los obligatorios entonces mandamos el error
						$html .= "for (var i = obligatory.length - 1; i >= 0; i--){";
						$html .= "	if(obligatory[i] == '". $f->id . "') {";
						$html .= "		/*alert('campo obligatorio' + obligatory[i]);*/";
						$html .= "		Ext.get('". $f->id . "').highlight('#ee0000');";
						$html .= "		return;";
						$html .= "	}";
						$html .= "};";
					$html .= "}" ;
				}
			$html .= "sendToApi(p);";
			$html .= "}";
			
			$html .= "function sendToApi( params ){";
			$html.= "	POS.API.". $this->send_to_api_http_method ."(\"". $this->send_to_api ."\", params, ";
			$html.= "	{";
			$html.= "		callback : function( a ){ ";
			$html.= "			";
			$html.= "			console.log('OKAY');";
			$html.= "			";
			$html.= "			";									
			$html.= "	 	}";
			$html.= "	});";
			$html.= "}";
			$html.= "</script>";			
			
		}
			
		$html .= "<table width=100%>";

		if( !is_null ( $this->submit_form ) ){
			$html .= "<form method='". $this->submit_form["method"] . "' action='". $this->submit_form["submit_form_url"] . "'>";

		}else{
			$html .= "<form >";	
			
		}
		
		$new_row = 0;
		$html .= "<tr>";
		foreach( $this->form_fields as $f )
		{
			//incrementar el calculo de la fila actual
			$new_row++;
			
	
			if($f->type !== "hidden"){
				$html .= "<td>";
				if($f->obligatory === true) $html .= "<b>";
				$html .= $f->caption;
				if($f->obligatory === true) $html .= "</b>";
				$html .= "</td><td>";				
			}

			switch( $f->type ){
				case "combo" :
					$html .= "<select id='". $f->id  ."'>";
					
					foreach($f->value as $o)
						$html .= "<option value='".$o["id"]."'>".$o["caption"]."</option>";
					
					$html .= "</select>";
					//$this->form_fields[$i]->value
				break;
                                
                                case "listbox" :
                                        $html .= "<select multiple='true' id='". $f->id ."' name='".$f->name."'>";
                                        
                                        foreach($f->value as $o)
                                                $html .= "<option value='".$o["id"]."'>".$o["caption"]."</option>";
                                        
                                        $html .= "</select>";
                                break;
				
				default:
					$html .= "<input id='" . $f->id .  "' name='" . $f->name .  "' value='" . $f->value .  "' type='". $f->type ."' >";				
			}

			
			if($f->type !== "hidden"){
				$html .= "</td>";
			}
			
			if($new_row == 2){
				$html .= "</tr><tr>";
				$new_row = 0;
			}
		}
		
		$html .= "</tr><tr>";

		$html .= "<td></td><td></td>";

			

		if( !is_null ( $this->submit_form 	) ){
			$html .= "<td align=right>";
			$html .= "<input value='" . $this->submit_form["caption"] .  "' type='submit'  >";
			$html .= "</td></tr>";
		}

		if( !is_null ( $this->on_click 		) ){
			$html .= "<td>";
			$html .= "</td><td align=right>";
			$html .= "<input value='" . $this->on_click["caption"] .  "' type='button' onClick='". $this->on_click["function"] ."' >";
			$html .= "</td></tr>";
		}

		if( !is_null ( $this->send_to_api	) ){
			$html .= "<td>";
			$html .= "</td><td align=right>";
			$html .= "<input value='Aceptar' type='button' onClick='getParams()' >";
			$html .= "</td></tr>";			
			
		}

		$html .= "</form></table>";

		return $html;

	}

	public function addSubmit( $caption, $submit_form_url = "", $method = "POST"){
		$this->submit_form = array( "caption" => $caption, "submit_form_url" => $submit_form_url, "method" => $method );
	}

	public function addOnClick( $caption, $js_function){
		$this->on_click = array( "caption" => $caption, "function" => $js_function );
	}

	public function addApiCall( $method_name, $http_method = "POST" ){
		if( !($http_method === "POST" || $http_method === "GET") ){
			throw new Exception("Http method must be POST or GET");
		}
		
		$this->send_to_api = $method_name;
		$this->send_to_api_http_method = $http_method;		
		
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
					//no break since there could be plenty of same id's
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

	public function createComboBoxJoin( $field_name, $field_name_in_values, $values_array ){
		if( sizeof( $values_array ) == 0 ){
			//do something
		}

		$sof = sizeof( $this->form_fields );

		for ($i=0; $i < $sof; $i++) { 
			
			if( $this->form_fields[$i]->id === $field_name )
			{
				$this->form_fields[$i]->type  = "combo";
				
				$end_values = array();

				foreach ($values_array as $v ){
					$v = $v->asArray();
					array_push( $end_values,  array( "id" => $v["$field_name"], "caption" => $v["$field_name_in_values"] ) );

				}
				
				$this->form_fields[$i]->value =  $end_values;

				break;
			}//if
		}//for

	}
        
        public function createListBoxJoin( $field_name, $field_name_in_values, $values_array ){
		if( sizeof( $values_array ) == 0 ){
			//do something
		}

		$sof = sizeof( $this->form_fields );

		for ($i=0; $i < $sof; $i++) { 
			
			if( $this->form_fields[$i]->id === $field_name )
			{
				$this->form_fields[$i]->type  = "listbox";
				
				$end_values = array();

				foreach ($values_array as $v ){
					$v = $v->asArray();
					array_push( $end_values,  array( "id" => $v["$field_name"], "caption" => $v["$field_name_in_values"] ) );

				}
				
				$this->form_fields[$i]->value =  $end_values;

				break;
			}//if
		}//for

	}

	public function createComboBox( $field_name, $values ){
		
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
	
	
	public static function obligatorySort( $f1, $f2 ){
	
		if ($f1->obligatory == $f2->obligatory) {
			return 0;
		}
		
		if( $f1->obligatory ) return -1;

		return 1;
	}
	
	public static function idSort( $f1, $f2 ){
		if ($f1->id == $f2->id) {
			return 0;
		}
		
		return strcmp( $f1->id, $f2->id );
	}
}


