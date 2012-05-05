package mx.caffeina.pos.Welcome;

import mx.caffeina.pos.HttpResponder;

public class Welcome extends HttpResponder{

	public Welcome(String [] path, String [] query){
		super(path, query);
	}

	public String getResponse(){
		if(dataType.equals("json")){
			return "{ \"status\" : \"ok\" }";

		}
		
		return buildHtml("<h1>hola</h1>");

	}	
}