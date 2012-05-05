package mx.caffeina.pos;

import java.io.*;
import java.net.URLDecoder;


public abstract class HttpResponder{
	
	protected String path  [ ];
	protected String query [ ];
	protected String dataType;

	public HttpResponder(String [] path, String [] query){
		setQuery(query);
		setPath(path);		
	}



	public void setPath(String [] path){
		this.path = path;
		this.dataType = path[0];
	}


	public void setQuery(String [] query){
		this.query = query;

	}


	public void setDataType(String json_html){
		this.dataType = json_html;
	}


	public String getContentType(){
		if(dataType.equals("html")){
			return "text/html";
		}

		if(dataType.equals("json")){
			return "application/json";
		}

		return "text/html";
	}

	public String searchInQuery(String key){
		for (int i = 0; i<query.length ; i++) {

			if(query[i].startsWith(key+"=")){
				return URLDecoder.decode(query[i].substring(query[i].indexOf("=")+1));
			}
		}
		return null;

	}

	public String searchHtmlBase( String mod ){
				
		String s, out = "";
		
		try{
			BufferedReader br = new BufferedReader( new FileReader("html"));
			
			
			while((s=br.readLine()) != null){
				if( s.trim().equals("-- "+mod+" --") ){

					while( ((s=br.readLine()) != null )
							&& !s.equals("-- /" +mod + " --"))
					{
						out += s + "\n";
					}
					
					break;
				}
			}

			
		

		}catch(IOException ioe){
			Logger.error(ioe);
			return "";
		}

		return out;		
	}


	public String buildHtml(String html){
		return searchHtmlBase("html_header") 
				+ html
				+ searchHtmlBase("html_footer");

	}

	public abstract String getResponse();

}