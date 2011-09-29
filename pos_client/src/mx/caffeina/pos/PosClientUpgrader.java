package mx.caffeina.pos;

import mx.caffeina.pos.Http.*;
import mx.caffeina.pos.Dispatcher;
import mx.caffeina.pos.Bascula.*;
import mx.caffeina.pos.Networking.*;

import java.awt.TrayIcon;
import java.io.*;
import java.util.zip.*;

public class PosClientUpgrader{
	
	private static String getCurrentVersion(){

		try{
			
			BufferedReader br = new BufferedReader(new FileReader("VERSION"));
			String version = br.readLine();
			
			br.close();
			return version;
			
		}catch(Exception e){
			return null;
			
		}
		
	}
	
	public static void checkForUpdates(){
		
		Logger.log("Buscando updates de pos Client...");
		Logger.log("My actual version is : " + getCurrentVersion());
	
		String response = HttpClient.Request(
			// base url
			//"http://development.pos.caffeina.mx/proxy.php?"
			"http://labs.caffeina.mx/alanboy/proyectos/pos/pos/trunk/www/proxy.php?"
			
			// instance
			+ "&i=1"
			
			// action
			+ "&action=1400"
			
			// my version
			+ "&my_version=" + getCurrentVersion()
			
			// this client's token
			+ "&t=" + Networking.getMacAddd( ) ).toString();

		

		if(!response.trim().equals("PLEASE_UPGRADE_YOURSELF"))
		{
			Logger.log("No upgrade needed... carry on.");
			return ;
		}
		
		//i need some upgrading man !
		Logger.warn("I NEED TO UPGRADE !");
		
		upgrade();

		System.exit(1);
		
	}
	
	
	private static void upgrade(){
		
		PosClient.trayIcon.getTrayIcon().displayMessage("Actualizando", 
            "Descargando...",
            TrayIcon.MessageType.INFO);

		try{
			

			
			//PrintWriter pw = new PrintWriter(new FileWriter("new_version.zip"));
			
			//StringBuilder s = HttpClient.Request("http://labs.caffeina.mx/alanboy/proyectos/pos/pos/trunk/www/proxy.php?&i=1&action=1401&t=00-1E-52-87-A2-9E");
			//StringBuilder s = HttpClient.Request("http://labs.caffeina.mx/alanboy/proyectos/pos-trunk/www/proxy.php?&i=1&action=1401&t=00-1E-52-87-A2-9E");

			HttpClient.RequestBinToFile("http://labs.caffeina.mx/alanboy/proyectos/pos/pos/trunk/www/proxy.php?&i=1&action=1401&t=00-1E-52-87-A2-9E", "new_version.zip");

			Logger.log("Descargando nueva version...[OK]");
			

			/*pw.write( s.toString() );
			pw.flush();
			pw.close();*/

			/*Logger.log("Escribiendo archivo... [OK]");*/

			BufferedOutputStream dest = null;
			FileInputStream fis = new FileInputStream("new_version.zip");
			ZipInputStream zis = new ZipInputStream(new BufferedInputStream(fis));
			ZipEntry entry;
			
			int BUFFER = 2048; 
			
			while((entry = zis.getNextEntry()) != null) 
			{
				Logger.log("Extracting : " + entry);
				
				if(entry.isDirectory())
				{
					new File(entry.getName()).mkdir();
					continue;
				}
				
				int count;
				byte data [ ] = new byte[BUFFER];
				
				// write the files to the disk
				FileOutputStream fos = new FileOutputStream( entry.getName() );
				dest = new 	BufferedOutputStream(fos, BUFFER);
				
				while ((count = zis.read(data, 0, BUFFER)) != -1) 	{
			 		dest.write(data, 0, count);
				}
				
				dest.flush();
				dest.close();
			}

			zis.close();
			
			Logger.warn("Nueva version instalada !!!!");
			


		}catch(Exception e){

			Logger.error(e);
			Logger.error("--- cerrando cliente debido a exception ---");
			System.out.println(e);
			System.exit(1);
		}
		
		
		try{
			File f = new File("new_version.zip");
			f.delete();
		}catch(Exception e){
			Logger.error(e);
		}
		
	}
	
}