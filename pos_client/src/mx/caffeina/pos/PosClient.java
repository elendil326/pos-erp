package mx.caffeina.pos;


import mx.caffeina.pos.Http.*;


public class PosClient{
	
	static final boolean PRODUCTION = false;
	
	public static HttpServer httpServer = null;
	
	
	public static void main( String ... args){
		
		new PosClient();

	}

	

	PosClient(){
		System.out.println("Iniciando cliente de POS");
		
		Runtime.getRuntime().addShutdownHook(new ShutDown());
		
		System.out.println("Enviando saludo a pos.caffeina.mx !");
		
		String response = null;

		if(PRODUCTION)
			response = HttpClient.Request("http://pos.caffeina.mx:80/trunk/www/proxy.php?i=1&action=1400");
		else
			response = HttpClient.Request("http://127.0.0.1:80/trunk/www/proxy.php?i=1&action=1400");

		System.out.println( response);

		
		//iniciar el servidor web
		httpServer = new HttpServer(8080);
	}



 	public class ShutDown extends Thread {
	
        public void run() {
	
			try{
				//@todo this shit dont work
				PosClient.httpServer.shutDown();				
			}catch(Exception e){
				//System.out.println(e);
			}

			
            System.out.println("Shutting down...");

			//perform 
			
        }
    }


}//PosClient