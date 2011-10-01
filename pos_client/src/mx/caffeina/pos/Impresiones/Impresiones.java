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
	
	private static String 	to_print;
	private static String 	dispositivo_de_impresion_default ;
	private static String 	dispositivo_de_impresion;
	private static String 	request_json;
	private static Map  	jsonmap;



	public static String Print(String params)
	{

		try{
        	request_json = URLDecoder.decode( params );
			
		}catch( NullPointerException npe ){

			Logger.error("Imposible decodear este url" + npe);

			return "({\"success\": false });";
		}


		JSONParser parser = new JSONParser();
		jsonmap = null;

		try{
			jsonmap = (Map) parser.parse(request_json);	

		}catch(ParseException pe){
			Logger.error(pe);
			return "({\"success\": false });"; 

		}


		to_print = null;

		dispositivo_de_impresion_default = "EPSON";

		//buscar una impresora en el json
		buscarImpresoraEnJson();

		//buscar impresoras esa impresora en
		//las impresoras disponibles
		buscarImpresorasDisponibles();

		//si se encontro impresora en json
		//y esa impresora esta disponible
		//imprimir ahi, de lo contrario
		//imprimir en la estandar
		//si la estandar no esta disponible
		//regresar un error
		imprimir();

		return "hola";

	}





	private static void imprimir()
	{
	
        
        Iterator iter = jsonmap.entrySet().iterator();

        while (iter.hasNext()) 
        {

	        Map.Entry entry = (Map.Entry) iter.next();

	        if (entry.getKey().toString().equals("free_text")) 
	        {
	        	
				to_print = (String)entry.getValue();
				break;
	        }
        }


        //termine de buscar el nodo, si to_print es nulo
        //entonces no se encontro
        if(to_print == null) {
        	Logger.error("No se envio free_text a la impresora.");
        	return;
        }


        //let print this shit!
        PrinterJob job = PrinterJob.getPrinterJob();
        //job.setPrintService();

        //job.setPrintable((Printable) formato);

	}






	private static void buscarImpresoraEnJson()
	{

        Iterator iter = jsonmap.entrySet().iterator();

        while (iter.hasNext()) 
        {

                Map.Entry entry = (Map.Entry) iter.next();

                if (entry.getKey().toString().equals("dispositivo_de_impresion")) 
                {
                	Logger.log("Encontre un dispositivo de impresion en el json:" + entry.getValue());

					dispositivo_de_impresion = (String)entry.getValue();

                    return;

                }
        }

	}


	private static void buscarImpresorasDisponibles()
	{
		
        PrintService [] printServices = PrintServiceLookup.lookupPrintServices(null, null);
        
        PrintService selectedService = null;
		
        for (int i = 0; i < printServices.length; i++)
        {

        	Logger.log("-->"  + printServices[i].getName());

        	if(dispositivo_de_impresion == printServices[i].getName())
        	{
        		Logger.log("Encontre el dispositivo que andaba buscando ;)");
        		return;
        	}
            
        }

        Logger.warn("No encontre el dispositivo que venia en el json, imprimire en el default");

		dispositivo_de_impresion = dispositivo_de_impresion_default;

	}//buscarImpresorasDisponibles()

}//Impresiones