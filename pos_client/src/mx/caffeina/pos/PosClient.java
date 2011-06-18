package mx.caffeina.pos;

import mx.caffeina.pos.Http.*;


public class PosClient{
	
	public static void main( String ... args){
		System.out.println("Iniciando cliente de POS");
		
		System.out.println("Enviando saludo a pos.caffeina.mx !");
		
		String response = HttpClient.Request("http://127.0.0.1:80/trunk/www/proxy.php?i=1&action=2002");

		System.out.println(response);
		
		//iniciar el servidor web
		new HttpServer(8080);
		
	}

	
}