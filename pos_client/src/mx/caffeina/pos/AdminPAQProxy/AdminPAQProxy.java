package mx.caffeina.pos.AdminPAQProxy;

import java.io.*;
import com.linuxense.javadbf.*;

public class AdminPAQProxy{

	public static void test(){
		InputStream inputStream = null;
		DBFReader reader = null;

		try {

			// create a DBFReader object
			//
			inputStream  = new FileInputStream( "BTT2012/MGW10002.dbf");
			reader = new DBFReader( inputStream); 

		}catch( DBFException e){
			System.out.println( "E1:" + e.getMessage());

		}catch( IOException e){
			System.out.println("E2:" + e.getMessage());

		}

			// get the field count if you want for some reasons like the following
			//
		int numberOfFields = -1;

		try{
			numberOfFields = reader.getFieldCount();

		}catch( DBFException dbfe ){
System.out.println( "E3:" + dbfe );
		}


		// use this count to fetch all field information
		// if required
		//
		for( int i=0; i<numberOfFields; i++) {

			DBFField field = null;

			try{
				field = reader.getField( i);

			}catch( DBFException dbfe ){
System.out.println( "E4:" + dbfe );
			}

			// do something with it if you want
			// refer the JavaDoc API reference for more details
			//
			System.out.println( field.getName() );
			
			
		}

		// Now, lets us start reading the rows
		//
		Object []rowObjects;

		try{
			while( (rowObjects = reader.nextRecord()) != null) {
				for( int i=0; i<rowObjects.length; i++) {
					System.out.println( rowObjects[i]);
				}
			}	
		}catch( DBFException dbfe ){
			System.out.println( "E5:" + dbfe );
		}
		

		// By now, we have itereated through all of the rows

		try{
			inputStream.close();	

		}catch(Exception e){
System.out.println( "E6:" + e );
		}
		



	}


}