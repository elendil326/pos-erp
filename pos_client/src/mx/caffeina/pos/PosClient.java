package mx.caffeina.pos;


import mx.caffeina.pos.Http.*;
import mx.caffeina.pos.Dispatcher;
import java.awt.TrayIcon;

import mx.caffeina.pos.Bascula.*;

public class PosClient{
	
	
	static final boolean 		PRODUCTION 	= false;
	public static PosSystemTray trayIcon 	= null;
	public static HttpServer 	httpServer 	= null;

	public static void main( String ... args)
	{
		
		Logger.log("Iniciando cliente...");
		
		new PosClient();

	}

	

	PosClient()
	{
		
		System.out.println("Iniciando cliente de POS, nevermind...");
		
		Runtime.getRuntime().addShutdownHook( new ShutDown() );

        trayIcon = new PosSystemTray();
		
		//Logger.log("Enviando saludo a pos.caffeina.mx !");
		
		String response = null;
		
		//iniciar el servidor web
		httpServer = new HttpServer( 16001 );

	}


 	public class ShutDown extends Thread {
	
        public void run() {
			/*
			try{
				//@todo this shit dont work
				//PosClient.sendLogToServer( "Cerrando cliente !" );
				//PosClient.httpServer.shutDown();
				
			}catch(Exception e){
				Logger.error(e);
			}
			*/
			
			Logger.log("Cerrando cliente.");
			Logger.close();			
            System.out.println("Shutting down...");

        }
    }


}//PosClient




