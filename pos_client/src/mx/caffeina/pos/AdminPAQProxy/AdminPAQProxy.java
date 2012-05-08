package mx.caffeina.pos.AdminPAQProxy;

import java.io.*;
import com.linuxense.javadbf.*;
import java.util.ArrayList;
import mx.caffeina.pos.*;
import mx.caffeina.pos.MD5Checksum;
import java.util.*;



public class AdminPAQProxy extends HttpResponder{

	private String ruta;
	private DBFReader reader;
	private FileInputStream inputStream;
	private boolean explorer;
	private String sql;
	private String last_error;	


	public AdminPAQProxy(String [] path, String [] query){
		
		super(path, query);
		this.ruta = null;
		last_error = null;
	}






	public String getResponse(){
		//dispatch submodules

		if(( path.length > 2 )  && path[2].equals("dbdiff")){
			bootstrapJson();
			DBDiff dbdiff = new DBDiff(searchInQuery("path"));
			return	dbdiff.renderFrontEnd();
		}

		if(dataType.equals("json")) {
			bootstrapJson();

			if(searchInQuery("callback") != null){
				return (searchInQuery("callback") + "(" + getJson() + ");");

			}else{
				return getJson() ;

			}
			
		}

		bootstrapHtml();
		return getHtml();
	}







	private String getHtml(){

		if(last_error != null){
			return renderError("html");
		}

		String rawHtml = searchHtmlBase("AdminPAQProxy"),
				outHtml = "";

		outHtml = rawHtml.replaceAll("\\{sql\\}",  ""+searchInQuery("sql") );

		outHtml = outHtml.replaceAll("\\{path\\}", ""+searchInQuery("path") );


		if(searchInQuery("sql") != null){
			outHtml = outHtml.replaceAll("\\{tabla\\}", query(searchInQuery("sql")) );

		}else{
			outHtml = outHtml.replaceAll("\\{tabla\\}", "" );
		}

		return buildHtml( outHtml );
	}





	private String getJson(){
		if(last_error != null){
			return renderError("json");
		}
		return query(searchInQuery("sql"));
	}






	private String renderError(String type){
		if(type.equals("html")){
			return "<h1>Error</h1><p>" + last_error + "</p>";

		}else{
			return "{\"status\":\"error\", \"error\": \""+last_error+"\"}";

		}
	}




	private void bootstrapHtml(){
		if(
			(searchInQuery("sql") != null)
			&& 
			(searchInQuery("path") == null)
		){
			last_error = "Enviaste sql pero no la ruta";
			return;
		}


		this.ruta = searchInQuery("path");
	}


	private void bootstrapJson(){

		this.ruta = searchInQuery("path");

		if(this.ruta == null){
			last_error = "No enviaste la ruta";
			Logger.log("No enviaste la ruta, necesitas enviar path.");
			return;
		}


		File f = new File( this.ruta );

		if(!f.isDirectory()){
			last_error = "La ruta `"+f+"` no existe.";
			Logger.log(last_error);
			return;
		}

		return;
	}





























	public String explorer(  ){
		return createExplorer( );
	}
	

	private void startCon(String file){
		Logger.log( "AdminPAQProxy: Conectando con ... " + this.ruta + "" + file + ".dbf" );

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


		String out = "";

		//first level
		if(sql_tokens[0].equals("select")){
			int i = -1;
			while( !sql_tokens[++i].equals("from") );
			i++;
			startCon( sql_tokens[i] );

			out += select(sql_tokens);	
		} 
		
		if(sql_tokens[0].equals("update")) out += update(sql_tokens);
		
		if(sql_tokens[0].equals("insert")){
			int i = -1;
			while( !sql_tokens[++i].equals("into") );
			i++;
			startCon( sql_tokens[i] );

			out += insert(sql_tokens);	
		} 
		
		return out;
	}







	private String insert (String [] sql){


		String rawSql = "";
		for ( int a = 0; a < sql.length;  a++) {
			rawSql += sql [a] + " ";

		}

		Logger.log("looking for fields");

		int i = 0;
		while( i < rawSql.length() && (rawSql.charAt(i) != '(' )) i++;



		if((i+1) == rawSql.length()){
			
		}

		String [] fields = rawSql.substring( rawSql.indexOf("(")+1, rawSql.indexOf(")") ).split(",");
		for (int z = 0; z < fields.length ; z++ ){
			//System.out.println("--> " + fields[z].trim() );
		}

		String [] values = rawSql.substring( rawSql.lastIndexOf("(")+1, rawSql.lastIndexOf(")") ).split(",");
		for (int z = 0; z < fields.length ; z++ ){
			//System.out.println("--> " + values[z].trim() );
		}

		

		if(values.length != fields.length){
			return "NOPE";
		}
		
		Logger.log("found " + fields.length +  " values to insert..");



		Logger.log("retriving actual table structure...");

		//ok, vamos a buscar que tabla quieres, y vamos a leer sus 
		//Fields
		int numberOfFields = -1;

		try{
			numberOfFields = reader.getFieldCount();

		}catch( DBFException dbfe ){
			System.out.println( "E3:" + dbfe );

		}catch(NullPointerException npe){
			Logger.error("ADMINPAQPROXY:" + npe);

		}

		Logger.log("got " + numberOfFields + " fields on structure.");

		String fieldNames [] 	= new String[ numberOfFields ];
		Object fieldToInsert [] = new Object[ numberOfFields ];

		nextField:
		for( i=0; i<numberOfFields; i++) {

			DBFField field = null;

			try{

				//System.out.println("-->" + reader.getField(i).getName() );

				Logger.log("writing...");

				for( int j = 0; j < fields.length; j++ ){
					if(fields[j].trim().equals( reader.getField(i).getName().trim() )){
						Logger.log("found field in value in sql");
						fieldToInsert[ i ] = values[ j ].toString().trim().toString();
						
						if(
							( fieldToInsert[ i ].toString().charAt(0) == '\"' )
							&& 
							(fieldToInsert[ i ].toString().charAt( fieldToInsert[ i ].toString().length() -1  ) == '\"' )
						){

								Logger.log("its string !, removing quotes");
								fieldToInsert[ i ] = fieldToInsert[ i ].toString().substring( 1, fieldToInsert[ i ].toString().length() -1 );
						}
						
						continue nextField;
					}
				}
				
				Logger.log("writing default value");
				//no lo encontre, insertemos el valor default
				switch( reader.getField(i).getDataType() ){
					case 'D':
						fieldToInsert[ i ] = new Date();	
					break;
					case 'C':
						fieldToInsert[ i ] = "";
					break;
					case 'N':
					case 'B':
					case 'F':
						fieldToInsert[ i ] = "0.0";
					break;

					default:
						fieldToInsert[ i ] = "null";
				}

			}catch(com.linuxense.javadbf.DBFException dbfe){
				Logger.error(dbfe);
				System.out.println(dbfe);
				return "0";

			}



			/*
				//(char)reader.getField(i).getDataType()
				//reader.getField(i).getFieldLength()
				//reader.getField( i).getName( )
				fieldToInsert[ i ] = null;
			*/
		}



		Logger.log("Closing file for reading..");
		closeCon();

		for (int o = 0; o < fieldToInsert.length; o++ ) {
			System.out.println(" :::  " + fieldToInsert[ o]);
		}

		DBFWriter writer= null;
    	try{
			writer = new DBFWriter(new File("/Users/alanboy/win2008shared/Empresas/Caffeina/MGW10002.dbf" ));
			writer.addRecord( fieldToInsert);

		}catch(Exception e){
			System.out.println( "E6:" + e );
		}




		return "0";

	}

	private String update(String [] sql){


		//update 

		set( );
		return "0";
	}
	
	private String createExplorer(){
		String out = Dispatcher.searchModuleInHtml("AdminPAQProxy");

		return out;
	}
	

	private String selectField(){
		return "";
	}


	private String select(String [] sql){
		
		//Seleccionar que tablas?
		if(!sql[1].equals("*")){

		}


		StringBuilder output = new StringBuilder();
		
		
		if(dataType.equals("html")){
			output.append("<table style='font-size:10px'><tr style='background-color: green'>");

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

		String fieldNames [] = new String[ numberOfFields ];

		for( int i=0; i<numberOfFields; i++) {

			DBFField field = null;
			try{
				if(i>0) {
					if(dataType.equals("html")){
						output.append(" ");
					}else{
						output.append(",");						
					}

				}
				
				if(dataType.equals("html")){
					output.append( "<td>" 
						+ reader.getField( i).getName( ) 
						+ " ("+ (char)reader.getField(i).getDataType() + " " + reader.getField(i).getFieldLength() +")</td>" );
				}else{
					output.append( "\"" + reader.getField( i).getName( ) + "\"" );					
				}
				
				fieldNames[i] = reader.getField( i).getName( );
				
			}catch( DBFException dbfe ){
				System.out.println( "E4:" + dbfe );
				break;
			}
		}
		

		// Now, lets us start reading the rows
		Object []rowObjects = null;

		
		
		if(dataType.equals("html")){
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

 
			

			if(dataType.equals("html")){
				if(cRecord % 2 == 0){
					output.append( "<tr style='background-color: gray'>" );
				}else{
					output.append( "<tr>" );
				}

			}else{
				if(cRecord > 1) output.append(", ");
				output.append("{");
			}

			for( int i=0; i<rowObjects.length; i++) {
				if(i>0){
					
					
					if(dataType.equals("html")){

					}else{
						output.append(", ");
					}
				}
				
				
				if(dataType.equals("html")){
					output.append( " <td>" + String.valueOf(rowObjects[i]).replaceAll("\\p{Cntrl}", "").replaceAll("[^\\p{ASCII}]", "?") + "</td> ");	
				}else{
					
					output.append(" \""+fieldNames[i]+"\" : "
									+ " \"" + String.valueOf(rowObjects[i]).replaceAll("\\p{Cntrl}", "").replaceAll("[^\\p{ASCII}]", "?") + "\" ");
				}
				
			

			}

			
		 	if(dataType.equals("html")){
				output.append( "</tr>");
			}else{
				output.append( "}");
			}		
		}	

	 	if(dataType.equals("html")){
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