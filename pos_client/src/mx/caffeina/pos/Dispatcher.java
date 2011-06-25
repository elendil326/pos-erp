package mx.caffeina.pos;

import mx.caffeina.pos.Printer.*;
import mx.caffeina.pos.Bascula.*;

import org.json.simple.parser.JSONParser;

public class Dispatcher{
	
	public static String dispatch( String request ){
		
		//the request looks like this
		// unique=0.03196072019636631&callback=Ext.util.JSONP.callback
		
		String [] args = request.split("&");
		System.out.println("Raw request: " + request);
		String action = null, data = null;

		//buscar el action
		for ( int i = 0; i < args.length ; i++) {
			if( args[i].startsWith("action=") )
				action = args[i].substring(7);
				
			if( args[i].startsWith("data=") )
				data = args[i].substring(5);				
		}
		
		System.out.println("Dispatching module " + action);
		
		
		/**
		* 
		* 	Despachar a impresiones
		* 
		* 
		* */
		if(action.equals("Printer")){
			ServidorImpresion.Print( data );			
			return "Ext.util.JSONP.callback({\"success\": true});";			
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
				String rawValue = b.getRawData(16);
				b.close();				
				return "Ext.util.JSONP.callback({\"success\": true, \"reading\" : \""+ rawValue +"\"});";
				
			}catch(java.lang.UnsatisfiedLinkError usle){	
				return "Ext.util.JSONP.callback({\"success\": false, \"reason\" : \"Imposible cargar las librerias para este sistema operativo.\"});";
				
			}catch(Exception e){
				return "Ext.util.JSONP.callback({\"success\": false, \"reason\" : \"La bascula no responde, revise que este conectada correctamente\"});";			
			}
		}


		
		return returnError();
	}
	
	
	static String returnError(){
		return "Ext.util.JSONP.callback({\"success\": false, \"reason\" : \"Bad dispatching\"});";
	}
	
}



