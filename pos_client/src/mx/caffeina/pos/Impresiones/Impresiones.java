package mx.caffeina.pos.Impresiones;

import java.awt.print.Printable;

import java.awt.print.PrinterException;
import java.awt.print.PrinterJob;
import java.util.Iterator;
import java.util.Map;
import javax.print.PrintService;
import javax.print.PrintServiceLookup;
import org.json.simple.parser.JSONParser;
import org.json.simple.parser.ParseException;
import java.net.URLDecoder;
//import java.util.logging.Level;
//import java.util.logging.Logger;
import mx.caffeina.pos.PosClient;
import mx.caffeina.pos.Logger;
import java.util.Calendar;
import java.util.Date;
import java.text.DateFormat;
import java.text.SimpleDateFormat;





public class Impresiones{
	
	private static String dispositivo_de_impresion;
	private static String request_json;

	public static String Print(String params)
	{

		try{
        	request_json = URLDecoder.decode( params );
			
		}catch( NullPointerException npe ){

			Logger.error("Imposible decodear este url" + npe);

			return "({\"success\": false});";
		}

		dispositivo_de_impresion = "EPSON";

		buscarImpresoraEnJson();

		buscarImpresorasDisponibles();

		return "hola";

	}


	private static void buscarImpresoraEnJson()
	{
	

		JSONParser parser = new JSONParser();
		Map jsonmap = null;

		try{
			jsonmap = (Map) parser.parse(request_json);	
		}catch(ParseException pe){
			Logger.error(pe);	
		}
		
		
        
        Iterator iter = jsonmap.entrySet().iterator();

        while (iter.hasNext()) {

                Map.Entry entry = (Map.Entry) iter.next();

                if (entry.getKey().toString().equals("dispositivo_de_impresion")) 
                {
                	Logger.log("Encontre un dispositivo de impresion en el json:" + entry.getValue());
					dispositivo_de_impresion = entry.getValue();
                    

                }
        }
	}


	private static void buscarImpresorasDisponibles()
	{
		
        PrintService [] printServices = PrintServiceLookup.lookupPrintServices(null, null);
        PrintService selectedService = null;
		
        for (int i = 0; i < printServices.length; i++) {

        	Logger.log("-->"  + printServices[i].getName());
            
        }
	}//buscarImpresorasDisponibles()

}//Impresiones