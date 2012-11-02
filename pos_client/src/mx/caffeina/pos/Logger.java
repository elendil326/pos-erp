package mx.caffeina.pos;

import mx.caffeina.pos.Http.HttpClient;
import java.io.FileWriter;
import java.io.PrintWriter;
import java.io.IOException;
import java.util.Date;
import java.text.DateFormat;
import java.text.SimpleDateFormat;

public class Logger{

	private static PrintWriter log_file = null;
	private static DateFormat dateFormat = new SimpleDateFormat("dd/MM/yyyy HH:mm:ss");
	
	private Logger()
	{
		//this class can not be instantiated
	}
	

	private static void open_log_file() throws IOException
	{
		log_file = new PrintWriter( new FileWriter("pos-client.log" , true) );
	}
	
		
	private static void close_log_file() throws IOException
	{
		log_file.flush();
		log_file.close();
	}
	
	
	public static void close()
	{
		try{
			close_log_file();
		}catch(IOException ioe){
			return;
		}
	}
	
	
	private static String getBaseString()
	{
	    return dateFormat.format(new Date()) + " | ";
	}
	
	
	public static void log(String text_to_log)
	{
		

				
		if( log_file == null )
		{
			//log file not opened, go ahead and do that
			try{
				open_log_file();				
			}catch(IOException ioe){
				return;
			}

		}

		System.out.println( getBaseString() + text_to_log);		
		log_file.println( getBaseString() + text_to_log );
		log_file.flush();			
	}
	
	public static void warn(String text_to_log)
	{

		//if its a warning then print to stdout
		System.out.println(getBaseString() + " WARNING: " + text_to_log);
		
		if( log_file == null )
		{
			//log file not opened, go ahead and do that
			try{
				open_log_file();				
			}catch(IOException ioe){
				return;
			}

		}
		
		log_file.println( getBaseString() + text_to_log );
		log_file.flush();
	}
	
	
	public static void error(String text_to_log)
	{
		System.out.println(getBaseString() + " ERROR: " + text_to_log);		
		
		if( log_file == null )
		{
			//log file not opened, go ahead and do that
			try{
				open_log_file();				
			}catch(IOException ioe){
				return;
			}

		}
		
		log_file.println( getBaseString() + text_to_log );
		log_file.flush();
	}
		
	
	public static void log(Exception exception_to_log)
	{
		error(exception_to_log);
	}

	public static void error(Exception exception_to_log)
	{
		System.out.println(getBaseString() + " WARNING: " + exception_to_log);
		if( log_file == null )
		{
			//log file not opened, go ahead and do that
			try{
				open_log_file();				
			}catch(IOException ioe){
				return;
			}

		}
		
		log_file.println( getBaseString() + exception_to_log );
		exception_to_log.printStackTrace( log_file ) ;
		log_file.flush();
	}
	
	public static void error(Error error_to_log)
	{
		if( log_file == null )
		{
			//log file not opened, go ahead and do that
			try{
				open_log_file();				
			}catch(IOException ioe){
				return;
			}

		}
		
		log_file.println( getBaseString() + error_to_log );
		error_to_log.printStackTrace( log_file ) ;
		log_file.flush();
	}
	
	public static void logToServer( String text_to_log )
	{
		Logger.log( "Logged to server:" + text_to_log );
		HttpClient.Request("http://192.168.1.66:80/trunk/www/proxy.php?i=1&action=1401&msg=" + text_to_log );
	}
	
}