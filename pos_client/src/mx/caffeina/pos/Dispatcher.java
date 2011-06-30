package mx.caffeina.pos;

import mx.caffeina.pos.Printer.*;
import mx.caffeina.pos.Bascula.*;

import org.json.simple.parser.JSONParser;

public class Dispatcher{
	
	static String action = null, data = null, callback = null;
	
	public static String dispatch( String request ){
		
		//the request looks like this
		// unique=0.03196072019636631&callback=Ext.util.JSONP.callback

		Logger.log("Raw request: " + request);

		if(request == null)
		{
			Logger.log("Request vacio !");
			return returnError();
		}
		
		String [] args = request.split("&");
		
		//buscar el action
		for ( int i = 0; i < args.length ; i++) {
			if( args[i].startsWith("action=") )
				action = args[i].substring(7);
				
			if( args[i].startsWith("data=") )
				data = args[i].substring(5);
				
			if( args[i].startsWith("callback=") )
				callback = args[i].substring(9);								
		}
		
		Logger.log("Dispatching module " + action);
		
		
		/**
		* 
		* 	Despachar a impresiones
		* 
		* 
		* */
		if(action.equals("Printer")){
			ServidorImpresion.Print( data );			
			return callback + "({\"success\": true});";			
		}




		/**
		* 
		* 	Despachar a bascula
		* 
		* 
		* */
		if(action.equals("bascula")){
			try{
				Bascula b = new Bascula();
				b.getRawData(1);				
				String rawValue = b.getRawData(9);
				b.close();				
				return callback + "({\"success\": true, \"reading\" : \""+ rawValue +"\"});";
				
			}catch(java.lang.UnsatisfiedLinkError usle){
				Logger.error(usle);	
				return callback + "({\"success\": false, \"reason\" : \"Imposible cargar las librerias para este sistema operativo.\"});";
				
			}catch(Exception e){
				Logger.error(e);					
				return callback + "({\"success\": false, \"reason\" : \"La bascula no responde, revise que este conectada correctamente\"});";			
				
			}
		}


		
		return returnError();
	}
	
	
	static String returnError(){
		return callback + "({\"success\": false, \"reason\" : \"Bad dispatching\"});";
	}
	
}



