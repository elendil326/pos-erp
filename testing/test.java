import java.io.*;
import java.net.*;
import com.google.gson.Gson;

/*


class Test{

	static BufferedReader br;

	
	
	
	private static boolean test( String in, String out ){
		String res = request(in);
		return true;
	}
	
	public static void main(String ... args) throws IOException {
	
		br = new BufferedReader(new FileReader('tests.txt'));
		
		while(hastMoreTests()){
			
		}
	}
	
	private static nextTest() throws IOException {
		String next = "";
		while((next = br.nextLint()	) != "#endTest"
	}
	
	private static nextTest() throws IOException {
		
	}
}


*/




class Test{

	private static BufferedReader br;
	
	public static void main(String ... args){
		try{
			br = new BufferedReader(new FileReader(args[0]));
		}catch(Exception e){
			System.out.println("Imposible leer el archivo de entrada.");
		}
		
		TestCase caso;
		while((caso = nextTest()) != null){
			System.out.println( caso.desc );
			
			test( caso );
		}
	}
	
	
	
	private static boolean test(TestCase t){
		try{
			String res = request(t.input);
			System.out.println(res);
			return true;
		}catch(Exception e){
			return false;
		}
	}
	
	
	private static TestCase nextTest(){
		try{
			TestCase foo = new TestCase();	
			String l;

			l = br.readLine().trim();

			if( l == null || !l.equals("#beginTest") ){
				return null;
			}

			
			//leer la descripcion
			br.readLine();
			while(!( l = br.readLine() ).trim().equals( "#endDesc" ) ){
				foo.desc += l.trim();
			}

		
			//leer la entrada
			br.readLine();
			while(!( l = br.readLine() ).trim().equals( "#endInput" ) ){
				foo.input += l.trim();
			}


			//leer la salida
			br.readLine();
			while(!( l = br.readLine() ).trim().equals( "#endOutput" ) ){
				foo.expected += l.trim();
			}
		
			br.readLine();
		
			return foo;
		}catch(Exception e){
			return null;
		}
	}




	private static String getCodeBase(){
		return "http://127.0.0.1/alan/trunk/www/";
	}
	
	public static String request ( String args ) throws IOException {
	
		URL                 url;
		URLConnection       urlConn;
		DataOutputStream    printout;
		DataInputStream     input;
		
		// URL of CGI-Bin script.
		url = new URL (getCodeBase().toString() + "proxy.php");
		
		urlConn = url.openConnection();
		
		urlConn.setDoInput (true);
		
		urlConn.setDoOutput (true);
		
		urlConn.setUseCaches (false);
		
		urlConn.setRequestProperty("Content-Type", "application/x-www-form-urlencoded");
		
		printout = new DataOutputStream ( urlConn.getOutputStream () );

		System.out.println("============= Request URL ================");
		System.out.println( args  );		
		String content = args; //URLEncoder.encode (args);
			
		printout.writeBytes (content);
		printout.flush ();
		printout.close ();
		
		// get response data
		input = new DataInputStream (urlConn.getInputStream ());
		
		System.out.println("============= Request Headers ================");
		System.out.println("User-Agent:" + urlConn.getRequestProperty("User-Agent"));
		
		System.out.println("============= Response Headers ================");
		for(int a = 0; ; a++){
			String r = urlConn.getHeaderField(a);
			if( r== null){
				break;
			}
			System.out.println( r );
		}
		
		System.out.println("============= Full Response ================");		
		String str, out = "";
		while (null != ((str = input.readLine())))
		{
			System.out.println (str);
			out += str;
		}
		
		input.close ();
		return out;
	}
}


class TestCase{

	public String desc;
	public String input;
	public String expected;
	
	public TestCase(){
		this.desc = "";
		this.input = "";
		this.expected = "";
	}
	
}
