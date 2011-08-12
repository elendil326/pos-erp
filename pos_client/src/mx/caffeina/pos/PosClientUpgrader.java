package mx.caffeina.pos;

import mx.caffeina.pos.Http.*;
import mx.caffeina.pos.Dispatcher;
import mx.caffeina.pos.Bascula.*;
import mx.caffeina.pos.Networking.*;

import java.awt.TrayIcon;
import java.io.*;


public class PosClientUpgrader{
	
	private static String getCurrentVersion(){
		try{
			
			BufferedReader br = new BufferedReader(new FileReader("VERSION"));
			return br.readLine();
			
		}catch(Exception e){
			return null;
			
		}
		
	}
	
	public static void checkForUpdates(){
		
		Logger.log("Buscando updates de pos Client...");

	
		String response = HttpClient.Request(
			// base url
			"http://127.0.0.1/proyectos/pos-trunk/www/proxy.php?"
			
			// instance
			+ "&i=1"
			
			// action
			+ "&action=1400"
			
			// my version
			+ "&my_version=" + getCurrentVersion()
			
			// this client's token
			+ "&t=" + Networking.getMacAddd( ) );

		if(!response.trim().equals("PLEASE_UPGRADE_YOURSELF")){
			Logger.log("No upgrade needed... carry on.");
			return ;
		}
		
		//i need some upgrading man !
		Logger.warn("I NEED TO UPGRADE !");
		
		upgrade();
		
	}
	
	
	private static void upgrade(){

		System.out.println(  );				System.out.println(  );				System.out.println(  );		
		System.out.println( HttpClient.Request("http://development.pos.caffeina.mx/proxy.php?action=2005") );
	}
	
}