import java.io.*;

class Main{

	public static void main(String [] args) throws IOException {
	
		BufferedReader br = new BufferedReader(new FileReader("salida"));	
		PrintWriter pw = new PrintWriter(new FileWriter("salida2.js"));
		
		String s, n;
		int w;
		while( (s = br.readLine()) != null ){

			if( (w = s.indexOf("if(DEBUG)")) != -1 ){
			
				n = s.substring( 0, w );
				
				if( s.charAt( w + "if(DEBUG)".length()  ) == '{' ){
					while( s.charAt(w++) != '}' );
				}else{
					while( s.charAt(w++) != ';' );				
				}
				
				pw.println( n +" "+ s.substring( w, s.length() ));			
			}else{
				pw.print(s + ' ' );
			}
			
		}
		
		pw.flush();
		pw.close();
	}

}

