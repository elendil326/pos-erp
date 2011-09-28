package mx.caffeina.pos;

import mx.caffeina.pos.Http.*;
import mx.caffeina.pos.Dispatcher;
import mx.caffeina.pos.Bascula.*;
import java.awt.TrayIcon;

public class PosClient{
	
	
	public static PosSystemTray trayIcon 	= null;
	public static HttpServer 	httpServer 	= null;

	public static void main( String ... args)
	{
		
		Logger.log("Iniciando cliente...");
		System.out.println("Iniciando cliente...");
		new PosClient();

	}

	

	PosClient()
	{

		Runtime.getRuntime().addShutdownHook( new ShutDown() );

        trayIcon = new PosSystemTray();
		
		String response = null;
				
		//vamos a ver si hay una nueva version del cliente
		//PosClientUpgrader.checkForUpdates(  );

		//iniciar el servidor web
		httpServer = new HttpServer( 16001 );

	}


 	public class ShutDown extends Thread {
	
        public void run() {
			
			try{
				//@todo this shit dont work
				//PosClient.sendLogToServer( "Cerrando cliente !" );
				//PosClient.httpServer.shutDown();
				
			}catch(Exception e){
				Logger.error(e);
			}
			
			
			Logger.log("Cerrando cliente...");
            System.out.println("Shutting down...");
			Logger.close();			

        }
    }


}//PosClient




