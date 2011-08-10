package mx.caffeina.pos;

import mx.caffeina.pos.Printer.*;
import mx.caffeina.pos.Bascula.*;
import mx.caffeina.pos.Networking.*;

import java.util.List;

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

		if(request.equals("avicon.ico"))
		{
			Logger.log("Omitinedo request de favicon.");
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
		* 	El saludo para saber que el cliente esta vivo
		* 
		* 
		* */
		if(action.equals("handshake")){
			return callback + "({\"success\": true, \"payload\": \"Hi !\" });";	
		}
		
		/**
		* 
		* 	Despachar a impresiones
		* 
		* 
		* */
		if(action.equals("Printer")){
			return callback + ServidorImpresion.Print( data );	
		}

		/**
		* 
		* 	Despachar a networking
		* 
		* 
		* */
		if(action.equals("networking")){
			String response = Networking.getMacAddd( );			
			return callback + "({\"success\": true,  \"response\" : \""+response+"\"});";			
		}


		/**
		* 
		* 	Despachar a bascula
		* 
		* 
		* */
		if(action.equals("bascula")){
			/**
			* 	###### PORT STATUS ########
			*	get_free_ports=true
			*	
			*
			*   ###### READ RANDOM DATA ########
			*	read_random=true
			*
			* 
			*   ###### SEND AND READ DATA ########
			*	port=(COM1)|[String]
			*	send_command=(NULL)|P|Z|T|G|N|C
			*   discard_first=(1)|[integer]
			*   read_next=(9)|[integer]
			* 
			* */
			String  get_free_ports 	= "false",
					port 			= "COM1",
					send_command 	= "",
					discard_first 	= "1",
					read_next 		= "9",
					read_random		= "true";
			
			

			//buscar los argumentos, y parsearlos
			for ( int i = 0; i < args.length ; i++) 
			{
				
				if( args[i].startsWith("read_random=") )
					read_random 	= args[i].substring( args[i].indexOf("=") +1);
									
				if( args[i].startsWith("get_free_ports=") )
					get_free_ports 	= args[i].substring( args[i].indexOf("=") +1);

				if( args[i].startsWith("port=") )
					port 			= args[i].substring( args[i].indexOf("=") +1);

				if( args[i].startsWith("send_command=") )
					send_command 	= args[i].substring( args[i].indexOf("=") +1);

				if( args[i].startsWith("discard_first=") )
					discard_first 	= args[i].substring( args[i].indexOf("=") +1);
					
				if( args[i].startsWith("read_next=") )
					read_next 		= args[i].substring( args[i].indexOf("=") +1);											
			}
			
			Logger.log("    get_free_ports =" 	+ get_free_ports 	+ ";");
			Logger.log("    port           =" 	+ port 				+ ";");
			Logger.log("    send_command   ="	+ send_command 		+ ";");
			Logger.log("    discard_first  ="	+ discard_first 	+ ";");						
			Logger.log("    read_next      ="   + read_next 		+ ";");
			Logger.log("    read_random    ="   + read_random 		+ ";");


			/** ****************************
			* 	Read random data
			*   **************************** */			
			if(read_random.equals("true")){
				return callback + "({\"success\": true, \"reading\" : \""+ ( (int)(Math.random() * 100) ) +"KG\"});";
			}
			
			
			
			
			/** ****************************
			* 	Get free ports
			*   **************************** */
			if(get_free_ports.equals("true"))
			{

				try{

					List<String> freePorts = Bascula.getFreePorts();
					
					String out = "[";
					
					for (String free : freePorts) {
						out += "\"" + free + "\", ";
					}
					
					out = out.substring( 0 , out.length() - 1 );
					
					out += "]";

					return callback + "({\"success\": true, \"basculas\" : "+ out +" });";

				}catch(java.lang.UnsatisfiedLinkError usle){
					Logger.error(usle);	
					return callback + "({\"success\": false, \"reason\" : \"Imposible cargar las librerias para este sistema operativo.\"});";

				}catch(Exception e){
					Logger.error(e);					
					return callback + "({\"success\": false, \"reason\" : \"La bascula no responde, revise que este conectada correctamente\"});";			

				}
				
			}
			
			
			/** ****************************
			* 	The other shit
			*   **************************** */
			try{
				Bascula b = new Bascula( port );
				
				if(b == null) 
					return callback + "({\"success\": false, \"reason\" : \"Error al conectarse con la bascula default.\"});";
				
				if(send_command.length() != 0){
					b.sendCommand(send_command);
				}

				b.getRawData(Integer.parseInt(discard_first));
					
					
				String rawValue = b.getRawData(Integer.parseInt(read_next));
				
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
		return callback + "({\"success\": false, \"reason\" : \"Internal error\"});";
	}
	
}



