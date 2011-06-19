package mx.caffeina.pos;

import mx.caffeina.pos.Printer.*;
import org.json.simple.parser.JSONParser;

public class Dispatcher{
	
	public static String dispatch( String request ){
		
		//the request looks like this
		// unique=0.03196072019636631&callback=Ext.util.JSONP.callback
		
		String [] args = request.split("&");
		
		String action = null, data = null;

		//buscar el action
		for ( int i = 0; i < args.length ; i++) {
			if( args[i].startsWith("action=") )
				action = args[i].substring(7);
				
			if( args[i].startsWith("data=") )
				data = args[i].substring(5);				
		}
		
		System.out.println("Dispatching module " + action);

		ServidorImpresion.Print( data );
		
		return returnError();
	}
	
	
	static String returnError(){
		return "Ext.util.JSONP.callback({\"success\": false, \"reason\" : \"Bad dispatching\"});";
	}
	
}



