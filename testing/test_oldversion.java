import java.io.*;
import java.net.*;
import com.google.gson.*;
import com.google.gson.stream.*;

/*
 javac -cp gson-1.6.jar test.java && java -cp .:gson-1.6.jar Test test.txt
*/
//esto es un comentario

class Test{

	private static BufferedReader br;
	private static String cookie = null;
	private static final boolean verbose = false;
	private static String configCodeBase;
	private static String configFileName;
	private static int currentLine = 0;
	private static int testOnLine;	
	private static boolean normalVerbose = true;		
	
	public static void main(String ... args) throws Exception{
	
		
		try{
			br = new BufferedReader(new FileReader(args[0]));
		}catch(Exception e){
			System.out.println("Imposible leer el archivo de entrada.");
		}
		
		boolean exitonerror = true;
		
		try{
			if( args[1].equals("--noexit") ){
				exitonerror = false;
			}
			
			if( args[1].equals("--noverbose") ){
				normalVerbose = false;
			}			
		}catch(Exception e){
		
		}
		
		TestCase caso;
		while((caso = nextTest()) != null){

			if(!test( caso )){
				if(exitonerror){
					return;
				}	
			}
			
		}
	}
	
	
	

	
	
	private static TestCase nextTest(){
		try{
			TestCase foo = new TestCase();	
			String l;

			while(true){
			
				l = br.readLine();
				currentLine ++;			
				
				if(l == null) return null;

				if( l.trim().startsWith("/*") ){
					
					while(!(l = br.readLine()).trim().startsWith("*/")){
						currentLine ++;
					}

				}


				if( l.trim().startsWith("#startConfig") ){

					while(!(l = br.readLine()).trim().startsWith("#endConfig")){
						currentLine ++;
						
						String configOption = l.trim().substring( 0, l.indexOf(' ') );
						
						if( configOption.startsWith("#codeBase") ){
							configCodeBase = l.trim().substring( l.indexOf(' ') ).trim();

						}

						if( configOption.startsWith("#fileName") ){
							configFileName = l.trim().substring( l.indexOf(' ') ).trim();

						}
					}
				}
				
				if( l.trim().equals("#beginTest") ){
					testOnLine = currentLine;
					break;
				}
			}
			
						
			//leer la descripcion
			while(!(l = br.readLine()).trim().equals("#endTest")){
				currentLine ++;
				
				if( l.trim().startsWith("#beginOutput") ){
					foo.expected = "";

					while(!(l = br.readLine()).trim().startsWith("#endOutput")){
						currentLine ++;					
						foo.expected += l + '\n';
					}
					
				}
				
				if(l.indexOf(' ') == -1){
					continue;
				}
				
				String configOption = l.trim().substring( 0, l.indexOf(' ') );

				if( configOption.startsWith("#Desc") ){
					foo.desc = l.trim().substring( l.indexOf(' ') ).trim();
				}

				if( configOption.startsWith("#Input") ){
					foo.input = l.trim().substring( l.indexOf(' ') ).trim();
				}
				
				if( configOption.startsWith("#Output") ){
					foo.expected = l.trim().substring( l.indexOf(' ') ).trim();
				}
				
				if( configOption.startsWith("#JSONOutput") ){
					foo.expected = l.trim().substring( l.indexOf(' ') ).trim();
					foo.expectedJson = true;
				}				
					
				
				if( configOption.startsWith("#fileName") ){
					foo.fileName = l.trim().substring( l.indexOf(' ') ).trim();
				}
				
			}
			
			if(foo.codeBase == null){
				foo.codeBase = configCodeBase;
			}

			if(foo.fileName == null){
				foo.fileName = configFileName;
			}			
			
			return foo;

		}catch(Exception e){
			System.out.println(e);
			return null;
		}
	}



	
	public static boolean test ( TestCase caso ) throws IOException {
	

		URL                 url;
		URLConnection       urlConn;
		DataOutputStream    printout;
		DataInputStream     input;
		
		// URL of CGI-Bin script.
		url = new URL (caso.codeBase + caso.fileName + "?i=1");
			
		urlConn = url.openConnection();
		urlConn.setDoInput (true);
		urlConn.setDoOutput (true);
		urlConn.setUseCaches (false);
		
		urlConn.setRequestProperty("Content-Type", "application/x-www-form-urlencoded");
		urlConn.setRequestProperty("User-Agent", "thisial98789897");
		
		if(cookie != null)
			urlConn.setRequestProperty("Cookie", cookie );

		
		printout = new DataOutputStream ( urlConn.getOutputStream () );


		String content = caso.input; //URLEncoder.encode (args);
		//String content = URLEncoder.encode (caso.input);
					
		printout.writeBytes (content);
		printout.flush ();
		printout.close ();
		

		
		// get response data
		try{
			input = new DataInputStream ( urlConn.getInputStream() );			
		}catch( java.io.FileNotFoundException fnfe ){
			System.out.println( fnfe );
			return false;
		}

		
		if(verbose){
			System.out.println("============= Request Headers ================");
			System.out.println("User-Agent:" + urlConn.getRequestProperty("User-Agent"));
			System.out.println("Cookie:" + urlConn.getRequestProperty("Cookie"));
		}
		
		if(verbose){
			System.out.println("============= Response Headers ================");
		}
		
		for(int a = 0; ; a++){
			String r = urlConn.getHeaderField(a);
			
			if( r== null){
				break;
			}
			
			if(r.startsWith("POS_ID=")){
				cookie = r;
			}
			
			if(verbose){
				System.out.println( r );
			}
			
		}		
		

		

		String str, out = "";
		while (null != ((str = input.readLine())))
		{
			out += str + '\n';
		}
		
		input.close ();
		
		boolean ok = false;		
		
		if(caso.expectedJson){
			//ok comparar los dos jsons		
			ok = jsonCompare(caso.expected, out);
		}else{
		
			ok = out.trim().equals(caso.expected.trim());
		}
		
		
		if( !ok ){

			System.out.println		("============= Test failed ! ================");		
			System.out.println		(  caso.desc )  ;		
			
			System.out.println		("============= Request URL ==================");
			System.out.println		( url + "?" + caso.input );

			if(caso.expectedJson)
				System.out.println	("============= Expected JSON ================");					
			else
				System.out.println	("============= Expected response ============");					
							
			System.out.println( caso.expected );

			System.out.println		("============= Full Response ================");					
			System.out.println( out );
			
			System.out.println(  );						
		}else{
			if(normalVerbose)
				System.out.println(  "[PASSED] " + caso.desc + " on line " + testOnLine )  ;
		}
		
		return out.trim().equals(caso.expected.trim());

	}
	
	
	
	
	static boolean jsonCompare(String a, String b){
	
		JsonObject one = null, two = null;
		
		try{
			one = new Gson().fromJson(a, JsonObject.class);
		}catch(Exception mje){
			System.out.println("Malformed JSON !");
			return false;
		}


		try{
			two = new Gson().fromJson(b, JsonObject.class);
		}catch(Exception mje){
			System.out.println("Malformed JSON !");
			return false;
		}		


				
		return a.trim().equals( b.trim());
	}
}





class TestCase{

	public String desc;
	public String input;	//action=2099
	public String expected;
	public boolean expectedJson;
	public String codeBase; //http://127.0.0.1/pos/trunk/www/
	public String fileName; //proxy.php
	
	
	public TestCase(){
		this.desc = "";
		this.input = "";
		this.expected = "";
		this.codeBase = null;
		this.fileName = null;
		this.expectedJson = false;
	}
	
}


