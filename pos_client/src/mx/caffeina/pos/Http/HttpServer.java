package mx.caffeina.pos.Http;

import java.io.*;
import java.net.*;
import java.util.*;
import mx.caffeina.pos.Dispatcher;
import mx.caffeina.pos.Logger;

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
	
    public HttpServer(int port)  
    { 
        try{ 
            // Create the server socket. 
            m_ServerSocket = new ServerSocket(port); 

        }catch(IOException ioe){ 
            Logger.warn("Could not create server socket at "+port+". Quitting."); 
            System.exit(-1); 

        } 
		
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
	
			Logger.log( "Connection recieved !" );
						
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
			
            try{ 
	
				in = new BufferedReader(new InputStreamReader(m_clientSocket.getInputStream())); 
				out = new PrintWriter(new OutputStreamWriter(m_clientSocket.getOutputStream())); 

				// At this point, we can read for input 
				// and reply with appropriate output. 

				// Retrive headers from request
				String r ;
				String Get_Request = null;
				
				while
				( 
					(( r = in.readLine() ) != null)
					&& r.length() > 0
				) 
				{                     
					if(r.startsWith("GET")){
						Get_Request = r.substring(6, r.indexOf(" HTTP/1.1"));
					}
				} 


				//send the response
				String response = Dispatcher.dispatch( Get_Request ); 
				
			
				//send the headers
				out.println("HTTP/1.1 200 OK");
				out.println("Date: Mon, 23 May 2005 22:38:34 GMT");
				out.println("Server: POSWebServer/0.0.1");
				//out.println("Last-Modified: Wed, 08 Jan 2003 23:11:55 GMT");
				out.println("Accept-Ranges: bytes");
				out.println("Content-Length: " + response.length());
				out.println("Connection: close");
				out.println("Content-Type: text/javascript; charset=UTF-8");
				out.println("");
				
				//write and flush the response
				out.println(response);
				out.flush();
			
				
            }catch(Exception e){ 
                e.printStackTrace(); 

            }finally{ 
	
                // clean up 
                try{                     
                    in.close(); 
                    out.close(); 
                    m_clientSocket.close(); 
                    Logger.log("...Stopped"); 
					
                }catch(IOException ioe){ 
                    ioe.printStackTrace(); 

                }
            } 

        }// void run


    }// class ClientServiceThread

}// class HttpServer 



