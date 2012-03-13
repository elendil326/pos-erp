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

		startCon( tabla );

		if(data_structure == "data"){
			return getData();
		}

		if(data_structure == "structure"){
			return getStructure(  );	
		}

		return "{ error }";
	}

	public String queryRow(String table, String key, String val){
		
		startCon(table);
		

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

		String output = "{ \"datos\" :  ";;

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

			

			

			for( int i=0; i<rowObjects.length; i++) {


				

				if( fieldNames[i].equals(key) && rowObjects[i].equals(val) ){

					output += "[";

					for( int j=0; j<rowObjects.length; j++) {
						if(j>0)
							output+=",";

						output += "\"" + rowObjects[j] + "\"";	
					}

					output += "]"; 
				}

				
			}
			

			

			
		}	

		output += " }"; 		

		// By now, we have itereated through all of the rows

		try{
			inputStream.close();	

		}catch(Exception e){
			System.out.println( "E6:" + e );
		}
		
		return output;

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
		

		// Now, lets us start reading the rows
		//
		Object []rowObjects = null;

		StringBuilder output = new StringBuilder("{ \"datos\" : [ ");


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

			if(cRecord > 1) output.append(", ");

			
			output.append("[");

			for( int i=0; i<rowObjects.length; i++) {

				if(i>0)
					output.append(",");

				output.append("\"" + rowObjects[i] + "\"");	
				
				
			}

			output.append( "]"); 

			/* *********** **/
			
		}	

		output.append("]}");

		// By now, we have itereated through all of the rows

		try{
			inputStream.close();	

		}catch(Exception e){
			System.out.println( "E6:" + e );
		}
		
		return output.toString();
	}


	public String set( 
		//String file, String tabla, String [] values 
	){
		
		System.out.println("set() call");

		startCon( "MGW10002" );

		String stringRowData[] = {
			"99.2",
            "(Ninguno)",
            "alan gonzalez",
            "",
            "",
            "",
            "",
            "",
            "0.0",
            "0.0",
            "null",
            "null",
            "0.0",
            "0.0",
            "0.0",
            "0.0",
            "0.0",
            "0.0",
            "0.0",
            "0.0",
            "0.0",
            "null",
            "null",
            "null",
            "0.0",
            "0.0",
            "null",
            "0.0",
            "null",
            "0.0",
            "0.0",
            "",
            "",
            "0.0",
            "0.0",
            "0.0",
            "0.0",
            "0.0",
            "null",
            "null",
            "null",
            "null",
            "null",
            "0.0",
            "0.0",
            "0.0",
            "0.0",
            "0.0",
            "0.0",
            "null",
            "0.0",
            "0.0",
            "0.0",
            "null",
            "null",
            "null",
            "null",
            "null",
            "0.0",
            "null",
            "null",
            "0.0",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "null",
            "null",
            "null",
            "null",
            "null",
            "0.0",
            "0.0",
            "0.0",
            "0.0",
            "0.0",
            "0.0",
            "",
            "0.0",
            "null",
            "null",
            "0.0",
            "",
            "",
            "",
            "0.0",
            "0.0",
            "0.0",
            "0.0",
            "0.0",
            "0.0",
            "",
            "",
            "null",
            "0.0",
            "0.0",
            "0.0",
            "",
            "",
            "0.0",
            "0.0",
            "-1.0",
            "0.0",
            "0.0",
            "",
            ""};

		Object rowData[]  = new Object[ stringRowData.length  ];          

		int numberOfFields = -1;

		try{
			numberOfFields = reader.getFieldCount();

		}catch( DBFException dbfe ){
			System.out.println( "E3:" + dbfe );

		}

		
		System.out.println("new DBFFields");
		DBFField fields[] = new DBFField[ numberOfFields];

		for( int i=0; i<numberOfFields; i++){

			DBFField field = null;

			try{

				fields[i] = reader.getField( i );

				switch(fields[i].getDataType( )){
					case 'C': rowData[ i ] =  stringRowData[i] ; break;
					case 'D': rowData[ i ] =  new java.util.Date() ; break;
					case 'L': rowData[ i ] = Boolean.parseBoolean(stringRowData[i]); break;
					case 'N': 
					case 'F': 
					
						try{
							rowData[ i ] = Double.parseDouble(stringRowData[i]);

						}catch(Exception e){
							System.out.println(e);
							rowData[i] = 0.0;
						}
						
					break;
				}


				System.out.println("-----------------------------------------------");
				System.out.println(" i           :" +  i );
				System.out.println(" name        :" + fields[i].getName( ));
				System.out.println(" datatype    :" + (char)fields[i].getDataType( )+ "," +fields[i].getDataType( ));
				System.out.println(" fieldlength :" + fields[i].getFieldLength( ));
				System.out.println(" data        :" + stringRowData[i]);


			}catch( DBFException dbfe ){
				System.out.println( "E4:" + dbfe );
				break;
			}
		}

		System.out.println("rowdata[] = ");

		

        System.out.println("new writer()");

		DBFWriter writer= null;
    	try{
			writer = new DBFWriter(new File("/Users/alanboy/Desktop/BTT2012/MGW10002.dbf" ));

		}catch(Exception e){
			System.out.println( "E6:" + e );
		}
		

		try{
			inputStream.close();	

		}catch(Exception e){
			System.out.println( "E6:" + e );
		}

		try{

			//System.out.println("fields");
			//writer.setFields( fields);

			//System.out.println("FileOutputStream");
			//FileOutputStream fos = new FileOutputStream( "/Users/alanboy/Desktop/BTT2012/MGW10002.dbf" );

			System.out.println("addRecord");
    		//writer.write( fos);
			writer.addRecord( rowData);

			System.out.println("write");
			//writer.write( );
    		//System.out.println("close");
    		//fos.close();

		}catch (Exception e ){
			System.out.println(e);

		}

    	




    	return "OK";
	}


}