package mx.caffeina.pos.Http;

import java.io.*;
import java.net.*;
import java.util.*;
import mx.caffeina.pos.*;

import java.awt.*;
import java.awt.event.*;
import javax.swing.ImageIcon;
import javax.imageio.ImageIO;
import java.net.URL;



/**
  * 
  * 
  * 
  **/
public class HttpServer 
{         

	
    ServerSocket m_ServerSocket; 
	private boolean needToShutDown = false;
	
	public HttpServer()  {
		 new HttpServer( 8080 );
	}
	
	public void shutDown( ){
		Logger.log("Trying to shut-down webserver.");
		needToShutDown = true;
	}
	
    public HttpServer(int port){ 
	
        try{ 
            // Create the server socket. 
            m_ServerSocket = new ServerSocket(port); 

        }catch(IOException ioe){ 
            Logger.warn("Could not create server socket at "+port+". Quitting."); 
            System.exit(0); 

        } 
		
		PosClient.trayIcon.getTrayIcon().displayMessage("POS Listo", 
            "Pos Client iniciado !",
            TrayIcon.MessageType.INFO);

        Logger.log("Listening for clients on "+port+"..."); 
		
        // Successfully created Server Socket. Now wait for connections. 
        int id = 0; 
        while(!needToShutDown) 
        {                         
            try 
            { 
                // Accept incoming connections. 
                Socket clientSocket = m_ServerSocket.accept(); 
				
                // accept() will block until a client connects to the server. 
                // If execution reaches this point, then it means that a client 
                // socket has been accepted. 
				
                // For each client, we will start a service thread to 
                // service the client requests. This is to demonstrate a 
                // multithreaded server, although not required for such a 
                // trivial application. Starting a thread also lets our 
                // EchoServer accept multiple connections simultaneously. 
				
                // Start a service thread 
                ClientServiceThread cliThread = new ClientServiceThread( clientSocket, id++ ); 

                cliThread.start(); 

            }catch(IOException ioe){ 
                Logger.error("Exception encountered on accept. Ignoring. Stack Trace :"); 
                ioe.printStackTrace(); 

            } 
        } 

		

    }

	
    class ClientServiceThread extends Thread 
    { 
        Socket 	m_clientSocket;         
        int 	m_clientID 		= -1; 
        boolean m_bRunThread 	= true; 

		
        ClientServiceThread(Socket s, int clientID) 
        { 
	
			//Logger.log( "Connection recieved from " + s.getRemoteSocketAddress() );
            m_clientSocket = s;
            m_clientID = clientID; 
        } 
        


        public void run() 
        {             
            // Obtain the input stream and the output stream for the socket 
            // A good practice is to encapsulate them with a BufferedReader 
            // and a PrintWriter as shown below. 
            BufferedReader in = null;  
            PrintWriter out = null; 
			
            // Print out details of this connection 
            //Logger.log("Accepted Client : ID - " + m_clientID + " : Address - " +  m_clientSocket.getInetAddress()); 
			
			String  Get_Request = null,
					raw_request = "",
					Host_Request = null;

            HttpResponder response = null;

			try{ 
	
				in = new BufferedReader(new InputStreamReader(m_clientSocket.getInputStream())); 
				out = new PrintWriter(new OutputStreamWriter(m_clientSocket.getOutputStream())); 

				// At this point, we can read for input 
				// and reply with appropriate output. 

				// Retrive headers from request
				String r ;
			
				
				while
				( 
					(( r = in.readLine() ) != null) && (r.length() > 0)
				) 
				{                     
					if(r.startsWith("GET"))
					{
						Get_Request = r.substring(5, r.indexOf("HTTP/1.1"));
					}


					if(r.startsWith("Host"))
					{
						Host_Request = r.substring(6);
					}
				} 
				

				if(Get_Request == null){
					throw new Exception("Solo se aceptan metodos GET por ahora.");
				}

				//send the response
				Dispatcher d = new Dispatcher();
				raw_request = "http://" + Host_Request + "/" + Get_Request;

				try{
					//response = d.dispatch(Get_Request);
					response = d.dispatch( new URL( raw_request ));
					

				}catch( MalformedURLException m ){
					Logger.error(m + " " + Get_Request);


				}catch(Exception e){
					Logger.error(  e);


				}




				
			}catch(java.lang.StringIndexOutOfBoundsException sioobe){
				out.println("HTTP/1.1 500 SERVER ERROR");
				Logger.error("HttpServer:" + sioobe);
				

            }catch(Exception e){
				out.println("HTTP/1.1 500 SERVER ERROR");
				Logger.error("HttpServer:" + e);
				

            }finally{ 

            	

				if(response == null){
					out.println("HTTP/1.1 404 NOT FOUND");

				}else{
					//send the headers
					out.println("HTTP/1.1 200 OK");

				}

				out.println("Date: " + new Date());
				out.println("Server: POSWebServer/0.0.4");
				out.println("Accept-Ranges: bytes");


				if(response == null){
					out.println("Content-Length: 0" );
				}else{
					out.println("Content-Length: " + response.getResponse().length() );	
				}
				
				out.println("Connection: close");

				if(response == null){
					out.println("Content-Type: text/html; charset=UTF-8");
				}else{
					out.println("Content-Type: "+response.getContentType()+"; charset=UTF-8");
				}

				
				out.println("");
				
				//write and flush the response
				if(response == null){
					out.println("" );

				}else{
					out.println(response.getResponse());

				}

				out.flush();

				Logger.log( 
					m_clientSocket.getInetAddress().toString().substring(1)
					+ " | " 
					+raw_request 
					+ "");
                


                // clean up 
                try{                     
                    in.close(); 
                    out.close(); 
                    m_clientSocket.close(); 
                    //Logger.log("closing server socket..."); 
					
                }catch(IOException ioe){ 
					Logger.error("while closing socket:"+ioe);
					 
                }
            } 

        }// void run


    }// class ClientServiceThread

}// class HttpServer 



