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
import mx.caffeina.pos.PosClient;
import mx.caffeina.pos.Logger;
import java.util.Calendar;
import java.util.Date;
import java.text.DateFormat;
import java.text.SimpleDateFormat;





public class Impresiones{
	
	private static String 		to_print;
	private static String 		dispositivo_de_impresion_default ;
	private static String 		dispositivo_de_impresion ;
	private static String 		request_json;
	private static Map  		jsonmap;
	private static PrinterJob 	job;





	public static String Print(String params)
	{

		try{
        	request_json = URLDecoder.decode( params );
			
		}catch( NullPointerException npe ){

			Logger.error("Imposible decodear este url" + npe);

			return "({\"success\": false, reason : \"Imposible decodear este url\"});";
		}


		JSONParser parser = new JSONParser();
		jsonmap = null;

		try{
			jsonmap = (Map) parser.parse(request_json);	

		}catch(ParseException pe){
			Logger.error(pe);
			return "({\"success\": false });"; 

		}


		job = PrinterJob.getPrinterJob();

		to_print = null;

		dispositivo_de_impresion_default = "EPSON TM-U220 Receipt";

		//buscar una impresora en el json
		buscarImpresoraEnJson();

		//buscar impresoras esa impresora en
		//las impresoras disponibles
		try{
			buscarImpresorasDisponibles();	

		}catch(PrinterException pe){
			Logger.error(pe);
			return "(\"success\" : false )";

		}catch(Exception e){
			Logger.error(e);
			return "(\"success\" : false, \"reason\" : \""+ e +"\" )";			

		}
		

		//si se encontro impresora en json
		//y esa impresora esta disponible
		//imprimir ahi, de lo contrario
		//imprimir en la estandar
		//si la estandar no esta disponible
		//regresar un error
		imprimir();

		return "( \"success\": true )";

	}





	private static void imprimir()
	{
	
        
        Iterator iter = jsonmap.entrySet().iterator();

        while (iter.hasNext()) 
        {

	        Map.Entry entry = (Map.Entry) iter.next();

	        if (entry.getKey().toString().equals("free_text")) 
	        {
	        	
				to_print = String.valueOf( entry.getValue() );

				break;
	        }
        }


        //termine de buscar el nodo, si to_print es nulo
        //entonces no se encontro
        if(to_print == null) {
        	Logger.error("No se envio free_text a la impresora.");
        	return;
        }



		job.setPrintable((Printable) new ImpresionesPagina( to_print ));

        try {
            job.print();
        } catch (PrinterException exception) {
            Logger.error("La impresora no esta recibiendo documentos.");
        }

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


	private static void buscarImpresorasDisponibles() throws PrinterException, Exception
	{
		
        PrintService [] printServices = PrintServiceLookup.lookupPrintServices(null, null);

        PrintService selectedService = null;

        boolean found = false, found_default = false;
		
		PrintService service = null, defaultService = null;

        for (int i = 0; i < printServices.length; i++)
        {

        	Logger.log("-->"  + printServices[i].getName());

        	if(dispositivo_de_impresion == printServices[i].getName())
        	{
        		Logger.log("Encontre el dispositivo que andaba buscando");
        		found = true;
        		service = printServices[i];

        	}

        	if(dispositivo_de_impresion_default == printServices[i].getName())
        	{
        		Logger.log("Encontre el dispositivo default ");
        		found_default = true;
        		defaultService = printServices[i];
        	}
            
            if(printServices[i].getName().startsWith("EPSON")){
            	job.setPrintService( printServices[i] );
            	return;
            }
            	

        }

        if(found){
        	//usar el que me pidieron
        	job.setPrintService(service);

        }else if(found_default){
        	//usar el default
 			job.setPrintService(defaultService);

        }else {
        	//error
        	throw new Exception("No se encontro ni la enviada, ni la default");
        }

		

	}//buscarImpresorasDisponibles()

}//Impresiones

