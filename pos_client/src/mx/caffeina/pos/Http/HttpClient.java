package mx.caffeina.pos.Http;

import java.io.*;
import java.net.*;
import java.util.*;




// A client for our multithreaded EchoServer. 
public class HttpClient
{ 
	
	
	
	/**
	*  The socket for this conection
	* */
	private static Socket s = null; 
	private static URL url = null;




	/**
	* 
	* 
	**/
	public static String Request( String host){
		if( !createSocket( host ) ){
			//error while creating host
			return null;
		}
		
		return doRequest();
	}
	
	
	
	
	/**
	* 
	* 
	**/
	private static boolean createSocket( String host_add ){
		
		try{
			url = new URL(host_add);			
		}catch(java.net.MalformedURLException mue){
			//MalformedURLException
			return false;
		}

		
        try{
	 		//create the socket
            s = new Socket(url.getHost(), url.getPort()); 

        }catch(UnknownHostException uhe){ 
            // Host unreachable 
			return false;
			
        }catch(IOException ioe){ 
            // Cannot connect to port on given host 
			return false;
			
        }

		return true;
	}
	
	
	
	
	/**
	* 
	* 
	**/
	private static String doRequest(  ){
		
		
		BufferedReader 	in 			= null; 
        PrintWriter 	out 		= null; 
		String 			response 	= "";
		
        try{ 
            // Create the streams to send and receive information 
            in = new BufferedReader(new InputStreamReader(s.getInputStream())); 
            out = new PrintWriter(new OutputStreamWriter(s.getOutputStream())); 
			
			//send petition
			out.println("GET /"+ url.getFile() +" HTTP/1.1");
			out.println("Host: 127.0.0.1:80\n\n");
			
			out.flush(); 
			
			// receive the reply. 
			String r ;
			boolean headerEnded = false;
			int contentLength = 0;
			
			while( (r = in.readLine() ) != null ){

				if(headerEnded){

					while(--contentLength >= 0){
						response += (char)in.read();
					}
					
					break;
				}
					

				if(r.startsWith("Content-Length")){
					contentLength = Integer.parseInt((r.split(":")[1]).trim());

				}

				if(r.startsWith("Content-Type")){
					headerEnded = true;
				}
			}



        }catch(IOException ioe){ 
            System.out.println("Exception during communication. Server probably closed connection."); 

        }finally{ 
			System.out.println( "Closing buffers...");
			
            try{ 
                // Close the streams 
                out.close(); 
                in.close(); 

                // Close the socket before quitting 
                s.close();

            }catch(Exception e){ 
                e.printStackTrace(); 

            }                 
        }

		return response;
	}
	
 
} 




