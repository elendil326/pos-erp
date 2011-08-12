package mx.caffeina.pos;

import mx.caffeina.pos.Http.*;
import mx.caffeina.pos.Dispatcher;
import mx.caffeina.pos.Bascula.*;
import mx.caffeina.pos.Networking.*;

import java.awt.TrayIcon;



public class PosClientUpgrader{
	
	
	public static void checkForUpdates(){
		
		Logger.log("Buscando updates de pos Client...");

		String response = HttpClient.Request("http://127.0.0.1/proyectos/pos-trunk/www/proxy.php?i=1&action=1400&t=" + Networking.getMacAddd( ) );
		
		System.out.println(response);
		
	}
	
	
}