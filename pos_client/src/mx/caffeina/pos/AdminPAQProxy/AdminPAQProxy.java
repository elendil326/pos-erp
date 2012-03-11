package mx.caffeina.pos.AdminPAQProxy;

import java.io.*;
import com.linuxense.javadbf.*;
import java.util.ArrayList;

public class AdminPAQProxy{

	private String ruta;
	private DBFReader reader;
	private FileInputStream inputStream;

	public AdminPAQProxy(String ruta){
		//crear el nuevo proxy
		this.ruta = ruta;
		this.reader = null;
	}

	private void startCon(String file){
		System.out.println( "AdminPAQProxy: Conectando con ... " + this.ruta + "" + file + ".dbf" );

		try {

			// create a DBFReader object
			//
			inputStream = new FileInputStream( this.ruta + "" + file + ".dbf");
			reader = new DBFReader( inputStream ); 

		}catch( DBFException e){
			System.out.println( "E1:" + e.getMessage());

		}catch( IOException e){
			System.out.println("E2:" + e.getMessage());

		}
	}

	public String query(String tabla , String data_structure){

		if(tabla == "clientes"){
			tabla = "MGW10002" ;
		}

		startCon( tabla );

		if(data_structure == "data"){
			return getData();
		}

		if(data_structure == "structure"){
			return getStructure(  );	
		}

		return "{ error }";
	}


	private String getStructure(  ){
		int numberOfFields = -1;

		try{
			numberOfFields = reader.getFieldCount();
			System.out.println( reader.getFieldCount() + " fields.");
			System.out.println( reader.getRecordCount() + " records.");

		}catch( DBFException dbfe ){
			System.out.println( "E3:" + dbfe );

		}


		// use this count to fetch all field information
		// if required
		//
		ArrayList <String>tFields = new ArrayList<String>();

		String output = "{ \"estructura\" : [ ";

		for( int i=0; i<numberOfFields; i++) {

			DBFField field = null;

			try{
				field = reader.getField( i);

			}catch( DBFException dbfe ){
				System.out.println( "E4:" + dbfe );
				break;
			}

			// do something with it if you want
			// refer the JavaDoc API reference for more details
			//
			//System.out.println( "F: " + field.getName() );
			if(i>0){
				output += ",";
			}
			output += " \"" + field.getName( ) + "\" ";

			tFields.add(  field.getName( ) );
			
		}

		output += " }";

		try{
			inputStream.close();	

		}catch(Exception e){
			System.out.println( "E6:" + e );
		}

		return output;
	}


	private String getData(){

		int numberOfFields = -1;

		try{
			numberOfFields = reader.getFieldCount();

		}catch( DBFException dbfe ){
			System.out.println( "E3:" + dbfe );

		}

		String fieldNames [] = new String[ numberOfFields ];


		for( int i=0; i<numberOfFields; i++) {

			DBFField field = null;

			try{
				fieldNames[i] = reader.getField( i).getName( );

			}catch( DBFException dbfe ){
				System.out.println( "E4:" + dbfe );
				break;
			}

			
		}


		// Now, lets us start reading the rows
		//
		Object []rowObjects = null;

		String output = "{ \"datos\" : [ ";;

		int cRecord = 0;
		while( true ) {

			cRecord ++;
			
			try{
				//System.out.println("Leyendo record "  + cRecord);
				rowObjects = reader.nextRecord(  );

			}catch( DBFException dbfe ){
				System.out.println( "E5:" + dbfe.getMessage() );

			}

			if(rowObjects == null){
				break;
			}

			if(cRecord > 1) output += ", ";

			output += "{ "; 

			for( int i=0; i<rowObjects.length; i++) {
				if(i>0) output+=",";
				output += "\"" +  fieldNames[i] + "\" : \"" + rowObjects[i] + "\"";
			}

			output += " }"; 

			/* *********** **/
			
		}	

		output += "]}"; 		

		// By now, we have itereated through all of the rows

		try{
			inputStream.close();	

		}catch(Exception e){
			System.out.println( "E6:" + e );
		}
		
		return output;

	}





}