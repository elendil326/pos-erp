package mx.caffeina.pos;

import mx.caffeina.pos.Http.*;
import mx.caffeina.pos.Dispatcher;
import mx.caffeina.pos.Bascula.*;
import mx.caffeina.pos.AdminPAQProxy.*;
import java.awt.TrayIcon;

public class PosClient{
	
	
	public static PosSystemTray trayIcon 	= null;

	public static HttpServer 	httpServer 	= null;

	public static void main( String ... args )
	{
	


		Logger.log("----------------------------------");
		Logger.log("     Iniciando cliente");
		Logger.log("----------------------------------");

		/**
		  *
		  * Cargar argumentos
		  **/
		if( args.length > 0 )
		{

			//no-upgrade
			if( args[0].indexOf("n") != -1 ) 
			{
					
			}

		}

		String upgrade_url = "http://pos.caffeina.mx/";

		for (int i = 0; i < args.length; i++) 
		{
			if( args[i].equals("--upgrade-url") )
			{
				
				try{
					upgrade_url = args[i+1];

				}catch(Exception e){
					Logger.error("Missing upgrade url");

				}

			}
		}

		new PosClient();
	}
	




	PosClient()
	{

		Runtime.getRuntime().addShutdownHook( new ShutDown() );
        try{
            trayIcon = new PosSystemTray();
        }catch(Exception e){
            Logger.error(e);
        }
		
		
		String response = null;
				
		//vamos a ver si hay una nueva version del cliente
		//PosClientUpgrader.checkForUpdates( );

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




