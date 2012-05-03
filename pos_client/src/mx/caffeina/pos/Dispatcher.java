package mx.caffeina.pos;

import mx.caffeina.pos.Printer.*;
import mx.caffeina.pos.Impresiones.*;
import mx.caffeina.pos.Bascula.*;
import mx.caffeina.pos.Networking.*;
import mx.caffeina.pos.AdminPAQProxy.*;
import java.net.URLDecoder;

import java.net.URL;

import java.util.List;
import org.json.simple.parser.JSONParser;

import java.io.*;

public class Dispatcher{
	
	String action = null, data = null, callback = null;
	
	
	public static String searchModuleInHtml(String mod){
		
		System.out.println("searchModuleInHtml:" + mod);
		
		String s, out = "";
		
		try{
			BufferedReader br = new BufferedReader( new FileReader("html"));
			
			
			while((s=br.readLine()) != null){
				if( s.trim().equals("-- "+mod+" --") ){
					System.out.println("found it !");
					while( ((s=br.readLine()) != null )
							&& !s.equals("-- /" +mod + " --"))
					{
						out += s + "\n";
					}
					System.out.println("found the end of it !");
					break;
				}
			}

			
		

		}catch(IOException ioe){
			Logger.error(ioe);
		}
		return out;		
	}
	
	public static String DispatchError(){
		return searchModuleInHtml("Index") ;
	}
	
	public String returnResponse(String r){
		if( callback == null ){
			return r;
		}else{
			return callback + "("+ r +");";
		}
	
	}
	
	
	
	public String dispatch(URL url){
		
		
		
		return "ok";
		
	}
	
	public String dispatch( String request ){

		request = request.replace('?', '&');
		
		String [  ] args = request.split("&");
		
		//buscar el action
		for ( int i = 0; i < args.length ; i++) {
			if( args[i].startsWith("action=") )
				action = args[i].substring(7);
				
			if( args[i].startsWith("data=") )
				data = args[i].substring(5);
				
			if( args[i].startsWith("callback=") )
				callback = args[i].substring(9);								
		}

		if(request.equals("/favicon.ico")){
			return "";
		}

		Logger.log("Request: " + request + "");
		Logger.log("Dispatching module " + action);

		if(action == null){
			return searchModuleInHtml("Index");
		}
		
		/**
		* 
		* 	El saludo para saber que el cliente esta vivo
		* 
		* 
		* */
		if(action.equals("handshake")){
			returnResponse("{\"success\": true, \"payload\": \"Hi !\" }");
			
		}
		
		/**
		* 
		* 	Despachar a impresiones
		* 
		* 
		* */
		if(action.equals("Printer")){
			return returnResponse(ServidorImpresion.Print( data )) ;	
		}


		/**
		* 
		* 	Despachar a AdminPAQProxy
		* 
		* 
		* */
		if(action.equals("AdminPAQProxy")){

			String  path = null,
					sql = null;
			
			boolean explorer = false,
					dbdiff	= false;
					
			//buscar argumentos
			for ( int i = 0; i < args.length ; i++) 
			{
				
				if( args[i].startsWith("path=") ){
					path 	= URLDecoder.decode(args[i].substring( args[i].indexOf("=") +1));
					Logger.log("found `path` = " + path);
				}

				if( args[i].startsWith("dbdiff=") ){
					dbdiff 	= true;
					Logger.log("found `dbdiff` = " + 1);
				}
				
				
				if( args[i].startsWith("sql=") ){
					sql 	= URLDecoder.decode(args[i].substring( args[i].indexOf("=") +1));
					
					//remove url decoding
					Logger.log("found `sql` = " + sql);
					
				}
				
				if( args[i].startsWith("explorer=") ){

					//remove url decoding
					Logger.log("found `explorer` = " + URLDecoder.decode(args[i].substring( args[i].indexOf("=") +1)));
					explorer = true;
				}

			}

			if(dbdiff){
				System.out.println("AdminPAQProxy: DBDIFF");
				
				
				if(explorer){
					return DBDiff.renderFrontEnd();
				}
				
				if(path == null){
					Logger.warn("Falto el path a los archivos del admin.");
					return returnResponse("{\"success\": false,  \"response\" : \"Falto el path a los archivos del admin.\"}");
				}
				
				DBDiff x = new DBDiff( path );
				return x.queryDB();

				
			}else if(explorer){

				System.out.println("AdminPAQProxy: EXPLORER");
								
				AdminPAQProxy aproxy = new AdminPAQProxy(   );

				Logger.log("Termine de ejecutar AdminPAQProxy()");

				return returnResponse( aproxy.explorer( ) );
				
			}else{
				
				
				
				if(path == null){
					Logger.warn("Falto el path a los archivos del admin.");
					return returnResponse("{\"success\": false,  \"response\" : \"Falto el path a los archivos del admin.\"}");
				}

				if(sql == null){
					Logger.warn("No enviaste la consulta sql.");
					return returnResponse("{\"success\": false,  \"response\" : \"No enviaste la consulta sql.\"}");							
				}
				
				AdminPAQProxy aproxy = new AdminPAQProxy(  path, explorer );

				Logger.log("Termine de ejecutar AdminPAQProxy()");

				return returnResponse(aproxy.query(sql));				
			}




		}


		


		/**
		* 
		* 	Despachar a impresiones
		* 
		* 
		* */
		if(action.equals("Impresiones")){
			return returnResponse(Impresiones.Print( data ) );
				
		}		

		/**
		* 
		* 	Despachar a networking
		* 
		* 
		* */
		if(action.equals("networking")){
			String response = Networking.getMacAddd( );			
			return returnResponse	("{\"success\": true,  \"response\" : \""+response+"\"}");		
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
					read_random		= "false";
			
			

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
			
			/*
			Logger.log("    get_free_ports =" 	+ get_free_ports 	+ ";");
			Logger.log("    port           =" 	+ port 				+ ";");
			Logger.log("    send_command   ="	+ send_command 		+ ";");
			Logger.log("    discard_first  ="	+ discard_first 	+ ";");						
			Logger.log("    read_next      ="   + read_next 		+ ";");
			Logger.log("    read_random    ="   + read_random 		+ ";");
			*/

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

				if(Integer.parseInt(discard_first) > 0)
					b.getRawData(Integer.parseInt(discard_first));
					
					
				String rawValue = b.getRawData(Integer.parseInt(read_next));
				
				b.close();
						
				return callback + "({\"success\": true, \"reading\" : \""+ rawValue +"\"});";
				
			}catch(java.lang.UnsatisfiedLinkError usle){
				Logger.error("BASCULA:" +usle);	
				return callback + "({\"success\": false, \"reason\" : \"Imposible cargar las librerias para este sistema operativo.\"});";
				
			}catch(Exception e){
				Logger.error("BASCULA:" + e);					
				return callback + "({\"success\": false, \"reason\" : \"La bascula no responde, revise que este conectada correctamente\"});";			
				
			}
		}


		
		return DispatchError();
	}
	
	
	
	String returnError(){
		
		return returnResponse("{\"success\": false, \"reason\" : \"Internal error\"}");
	}
	
}



