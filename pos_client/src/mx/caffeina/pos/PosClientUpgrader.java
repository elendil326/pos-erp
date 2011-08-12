package mx.caffeina.pos;

import mx.caffeina.pos.Http.*;
import mx.caffeina.pos.Dispatcher;
import mx.caffeina.pos.Bascula.*;
import java.awt.TrayIcon;


public class PosClientUpgrader{
	
	
	public static void checkForUpdates(){
		
		Logger.log("Buscando updates de pos Client...");
		
		String response = HttpClient.Request("http://127.0.0.1/proyectos/pos-trunk/www/proxy.php?i=1&action=1107" );
		
		System.out.println(response);
		
	}
	
	
}