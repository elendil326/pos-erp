package mx.caffeina.pos.AdminPAQProxy;

import java.io.*;
import mx.caffeina.pos.*;

public class DBDiff {
	
	private String path;
	
	public static String renderFrontEnd(){
		return Dispatcher.searchModuleInHtml("DbDiff");
	}


	
	public DBDiff( String path ){

		
		System.out.println("Path for dbdiff : " + path);
		
		this.path = path.trim();
		
		File dir  = new File(this.path);
		
		try{
			
			if(!dir.getCanonicalFile().isDirectory()){
				Logger.error( dir + " is not a directory");
				this.path = null;
			}
			
		}catch(IOException ioe){
			Logger.log(ioe);
			
		}

		
		
	}
	
	public String queryDB(  ) {
		
		if(this.path == null){
			Logger.error( "there is no path... quitting." );
			return "{ \"success\" : false, \"reason\" : \"Something is wrong with your request.\" }";
		}
		
		StringBuilder sb = new StringBuilder();

		// List all the contents of the directory
		File dir = new File( this.path );


		File fileList[ ] = dir.listFiles();
		
		if(fileList == null){
			Logger.error("fileList returned null");
			return "";
		}
		
		sb.append("[");
		// Loop through the list of files/directories
		for(int index = 0; index < fileList.length; index++) {

			// Get the current file object.
			File file = fileList[ index ];

			// Call deleteDir function once again for deleting all the directory contents or
			// sub directories if any present.
			
			try{
				
				if(file.isDirectory()) continue;
				
				if( index > 0 ){
					sb.append(" , ");
				}
				
				sb.append( "{ \"file\" : \""+ file.getName() +"\" ," 
							+ "\"md5\" : \""+ MD5Checksum.getMD5Checksum(file.toString()) +"\" }");

			}catch(Exception e){
				Logger.error(e);
			}

		}
		sb.append("]");		
		
		return sb.toString();
	}
	
	
	
}