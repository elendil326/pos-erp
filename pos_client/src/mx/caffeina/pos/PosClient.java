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
		/*
		Runtime.getRuntime().addShutdownHook( new ShutDown() );

        trayIcon = new PosSystemTray();
		
		String response = null;
		
		//iniciar el servidor web
		httpServer = new HttpServer( 16001 );
		*/

		//vamos a ver si hay una nueva version del cliente

//		System.out.println( "\n\n>>" + HttpClient.Request("http://127.0.0.1/proyectos/pos-trunk/www/proxy.php?") + "<<" );	
//		System.out.println( "\n\n>>" + HttpClient.Request("http://www.httpwatch.com/httpgallery/chunked/") + "<<");
		System.out.println( "\n\n>>" + HttpClient.Request("http://development.pos.caffeina.mx/proxy.php?action=2005") + "<<"); 
		
		//PosClientUpgrader.checkForUpdates(  );
		
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
			
			Logger.log("Cerrando cliente...");
            System.out.println("Shutting down...");
			Logger.close();			

        }
    }


}//PosClient




