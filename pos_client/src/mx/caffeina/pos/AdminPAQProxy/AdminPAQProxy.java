package mx.caffeina.pos.AdminPAQProxy;

import java.io.*;
import com.linuxense.javadbf.*;
import java.util.ArrayList;
import mx.caffeina.pos.*;

import java.util.*;


public class AdminPAQProxy{

	private String ruta;
	private DBFReader reader;
	private FileInputStream inputStream;
	private boolean explorer;
	private String sql;
	
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
		try{
			inputStream.close();
		}catch(Exception e){
			System.out.println(e);
		}
	}
	
	public String query(String sql ){
		
		Logger.log("Doing query:" + sql);
		this.sql = sql;
		String [] sql_tokens = sql.trim().split( " " );

		//buscar el from
		int i = -1;
		while( !sql_tokens[++i].equals("from") );
		
		i++;
		
		startCon( sql_tokens[i] );

		String out = "";
		
		if( this.explorer ){
			out = createExplorer();
		}

		//first level
		if(sql_tokens[0].equals("select")) out += select(sql_tokens);
		
		if(sql_tokens[0].equals("update")) out += update(sql_tokens);
		
		
		
		
		return out;



	}

	private String update(String [] sql){
		set( );
		return "0";
	}
	
	private String createExplorer(){
		//query builder
		String s = "", out = "";
		try{
			BufferedReader br = new BufferedReader(new FileReader("html"));			
			while((s = br.readLine()) != null){
				
				if(s.equals("{path}")){
					s = "<input type=\"hidden\" name=\"path\" value=\""+ this.ruta +"\">";
				}
				
				if(s.equals("{sql}")){
					s = "<textarea name=\"sql\" cols=\"40\" rows=\"6\" dir=\"ltr\">"+this.sql+"</textarea>";
				}
				
				out += s;
			}
		}catch(Exception e){
			System.out.println(e);
		}
		
		
		
		
		return out;
	}
	
	private String select(String [] sql){
		
		StringBuilder output = new StringBuilder();
		
		
		if(this.explorer){

			output.append("<table><tr style='background-color: green'>");
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
					output.append( "<td>" 
						+ reader.getField( i).getName( ) 
						+ " ("+ (char)reader.getField(i).getDataType() + " " + reader.getField(i).getFieldLength() +")</td>" );
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

 
			

			if(this.explorer){
				if(cRecord % 2 == 0){
					output.append( "<tr style='background-color: gray'>" );
				}else{
					output.append( "<tr>" );
				}

			}else{
				if(cRecord > 1) output.append(", ");
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
					output.append( " <td>" + String.valueOf(rowObjects[i]).replaceAll("\\p{Cntrl}", "").replaceAll("[^\\p{ASCII}]", "?") + "</td> ");	
				}else{
					output.append( " \"" + String.valueOf(rowObjects[i]).replaceAll("\\p{Cntrl}", "").replaceAll("[^\\p{ASCII}]", "?") + "\" ");	
				}
				
			

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
		/*


		String stringRowData2[] = {
			"1",
            "codigo",
            "alan gonzalezzzzzzzzzzzzzzzzzzzdddddddhdhdhdhdhdhdhdhdhdhdhdhdhdhhdhdhdhdhdh",
            "",//fecha
            "GOHA880317",
            "CURP",
            "denominacion_comerrcial",
            "representante legal",
            "9",
            "10",
            "11.0", // B
            "12.0", // B
            "13",
            "14",
            "15",
            "16",
            "17",
            "18",
            "19",
            "20",
            "21",
            "", //fecha
            "", //fecha
            "22.0", //B
            "23",
            "24.0", //B
            "25",
            "26",
            "27.0", //B
            "28",
            "29.0", //B
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
		
*/
		Object row[] = {
			1.0,
			"codigo",
			"nombre",
			new Date(),
			"rfc",
			"curp",
			"asdfadf",
			"den",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			new Date(),
			new Date(),
			"1.0",
			"1.0",
			"1.0",
			"1.0",			
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",						
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			new Date(),
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0",
			"1.0"
		};
		
		DBFWriter writer= null;
    	try{
			writer = new DBFWriter(new File("/Users/alanboy/win2008shared/Empresas/Caffeina/MGW10002.dbf" ));
			writer.addRecord( row);

		}catch(Exception e){
			System.out.println( "E6:" + e );
		}
		

    	return "OK";
	}


	private String fillLeft(Object ss, int t){

		String s = String.valueOf(ss);
		if(s.length() == t) return s;
		
		if(s.length() > t) {
			String foo = s.substring(0, t);

			return foo;
		}
		
		
		

		
		while(s.length() != t){
			s = " " + s;
		}
		return s;
	}

}