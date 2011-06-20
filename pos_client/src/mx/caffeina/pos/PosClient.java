package mx.caffeina.pos;


import mx.caffeina.pos.Http.*;
import mx.caffeina.pos.Dispatcher;
import java.awt.TrayIcon;

import mx.caffeina.pos.Bascula.*;

public class PosClient{
	
	static final boolean PRODUCTION = false;
	
	public static HttpServer httpServer = null;
	
	
	public static void main( String ... args){
		
		new PosClient();

	}

	

	PosClient(){
		System.out.println("Iniciando cliente de POS");
		
		Runtime.getRuntime().addShutdownHook(new ShutDown());

        PosSystemTray trayIcon = new PosSystemTray();
		
		System.out.println("Enviando saludo a pos.caffeina.mx !");
		
		String response = null;

		if(PRODUCTION)
			response = HttpClient.Request("http://pos.caffeina.mx:80/trunk/www/proxy.php?i=1&action=1400");
		else
			response = HttpClient.Request("http://192.168.1.66:80/trunk/www/proxy.php?i=1&action=1400");

		//System.out.println( response );
		
		//iniciar el servidor web
		//httpServer = new HttpServer(8080);
		
		Bascula b = new Bascula();
		b.getRawData(16);
		b.close();

	}


	public static void sendLogToServer( String msg ){
		if(PRODUCTION)
			HttpClient.Request("http://pos.caffeina.mx:80/trunk/www/proxy.php?i=1&action=1401&msg=" + msg );
		else
			HttpClient.Request("http://192.168.1.66:80/trunk/www/proxy.php?i=1&action=1401&msg=" + msg );
	}





 	public class ShutDown extends Thread {
	
        public void run() {
	
			try{
				//@todo this shit dont work
				PosClient.sendLogToServer( "Cerrando cliente !" );
				//PosClient.httpServer.shutDown();
				
			}catch(Exception e){
				System.out.println(e);
			}

			
            System.out.println("Shutting down...");

        }
    }


}//PosClient




