package mx.caffeina.pos.AdminPAQProxy;

import java.io.*;
import com.linuxense.javadbf.*;
import java.util.ArrayList;
import mx.caffeina.pos.*;



public class AdminPAQProxy{

	private String ruta;
	private DBFReader reader;
	private FileInputStream inputStream;
	private boolean explorer;

	public AdminPAQProxy(String ruta, boolean explorer){
		this.ruta = ruta;
		this.reader = null;
		this.explorer = explorer;
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
			Logger.log(e.getMessage());
			
		}catch( IOException e){
			System.out.println("E2:" + e.getMessage());
			Logger.log(e.getMessage());
		}
	}
	
	private void closeCon(){
		
	}

	
	public String query(String sql ){

		Logger.log("Doing query:" + sql);

		String [] sql_tokens = sql.trim().split( " " );

		//buscar el from
		int i = -1;
		while( !sql_tokens[++i].equals("from") );
		
		i++;
		
		startCon( sql_tokens[i] );

		//first level
		if(sql_tokens[0].equals("select")) return select(sql_tokens);
		
		if(sql_tokens[0].equals("update")) return update(sql_tokens);
		
		return "{error}";


	}

	private String update(String [] sql){
		
		return "0";
	}
	
	private String select(String [] sql){
		
		StringBuilder output = new StringBuilder();
		
		
		if(this.explorer){
			output.append("<table><tr>");
		}else{
			output.append("{ \"estructura\" : [ ");						
		}
		
		
		int numberOfFields = -1;

		try{
			numberOfFields = reader.getFieldCount();

		}catch( DBFException dbfe ){
			System.out.println( "E3:" + dbfe );

		}catch(NullPointerException npe){
			Logger.error("ADMINPAQPROXY:" + npe);
		}

		//String fieldNames [] = new String[ numberOfFields ];

		for( int i=0; i<numberOfFields; i++) {

			DBFField field = null;
			try{
				if(i>0) {
					if(this.explorer){
						output.append(" ");
					}else{
						output.append(",");						
					}

				}
				
				if(this.explorer){
					output.append( "<td>" + reader.getField( i).getName( ) + "</td>" );
				}else{
					output.append( "\"" + reader.getField( i).getName( ) + "\"" );					
				}
				
				
			}catch( DBFException dbfe ){
				System.out.println( "E4:" + dbfe );
				break;
			}
		}
		

		// Now, lets us start reading the rows
		Object []rowObjects = null;

		
		
		if(this.explorer){
			output.append( "</tr>" );
		}else{
			output.append( "] ,  \"datos\" : [");
		}

		
		int cRecord = 0;
		while( true ) {

			cRecord ++;
			
			try{
				rowObjects = reader.nextRecord(  );

			}catch( DBFException dbfe ){
				System.out.println( "E5:" + dbfe.getMessage() );

			}

			if(rowObjects == null){
				break;
			}

			if(cRecord > 1) output.append(", ");
			

			if(this.explorer){
				output.append( "<tr>" );
			}else{
				output.append("[");
			}

			for( int i=0; i<rowObjects.length; i++) {
				if(i>0){
					
					
					if(this.explorer){

					}else{
						output.append(", ");
					}
				}
				
				
				if(this.explorer){
					output.append( " <td>" + String.valueOf(rowObjects[i]).replaceAll("\\p{Cntrl}", "").replaceAll("[^\\p{ASCII}]", "") + "</td> ");	
				}else{
					output.append( " \"" + String.valueOf(rowObjects[i]).replaceAll("\\p{Cntrl}", "").replaceAll("[^\\p{ASCII}]", "") + "\" ");	
				}
				
				/*try{
					output.append( " \"" + String.valueOf(rowObjects[i]).replaceAll("\\p{Cntrl}", "").replaceAll("[^\\p{ASCII}]", "") + "\" ");	
					//output.append( "\""+reader.getField( i).getName( ) + "\"" + ": \"" + String.valueOf(rowObjects[i]).replaceAll("\\p{Cntrl}", "").replaceAll("[^\\p{ASCII}]", "") + "\" ");	

				}catch(DBFException dbfe){
					Logger.error("While reading record " + i + ":" + dbfe );
					
				}*/


			}

			
		 	if(this.explorer){
				output.append( "</tr>");
			}else{
				output.append( "]");
			}		
		}	

	 	if(this.explorer){
			output.append( "</table>");
		}else{
			output.append("]}");
		}


		// By now, we have itereated through all of the rows
		try{
			inputStream.close();	

		}catch(Exception e){
			System.out.println( "E6:" + e );
		}
		
		return output.toString();
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



	public String set( ){
		
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