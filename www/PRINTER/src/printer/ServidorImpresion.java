/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package printer;

import java.awt.Graphics;
import java.awt.PrintJob;
import java.awt.print.PageFormat;
import java.awt.print.PrinterException;
import java.awt.print.PrinterJob;
import javax.print.PrintService;
import javax.print.PrintServiceLookup;


/**
 *
 * @author manuel
 */
class ServidorImpresion {

    
   

    PrintJob pj;
    Graphics ticket;


    /********************************************************************
     *	A continuación el constructor de la clase. Aquí lo único que	*
     *	se hace es tomar un objeto de impresion.							*
     ********************************************************************/
    ServidorImpresion() {
       // pj = Toolkit.getDefaultToolkit().getPrintJob(new Frame(), "SCAT", null);
    }

    /********************************************************************
     *	A continuación el método "imprimir(String)", el encargado de 	*
     *	colocar en el objeto gráfico la cadena que se le pasa como 		*
     *	parámetro y se imprime.											*
     ********************************************************************/
    public void imprimirTicket(Venta venta) throws PrinterException {
        // try/catch PORQUE PUEDEN CANCELAR LA IMPRESION

        PrinterJob job = PrinterJob.getPrinterJob();
            PageFormat pf = job.defaultPage(); //job.pageDialog(aset)
            //job.setPrintable(new FormatoTicket(venta), pf);
            job.setPrintable(new FormatoTicket(venta));
            PrintService[] printServices = PrintServiceLookup.lookupPrintServices(null, null);
            PrintService selectedService = null;
            for (int i = 0; i < printServices.length; i++) {//EPSON TM-U220 Receipt

                //if (printServices[i].getName().equals("\\\\printserver\\usbhp")) {
                if (printServices[i].getName().equals("EPSON TM-U220 Receipt")) {
                    selectedService = printServices[i];
                    break;
                }
            }
            if (selectedService != null) {
                job.setPrintService(selectedService);
                job.print();
            }
    }//FIN DEL PROCEDIMIENTO imprimir(String...)

    public void imprimirFactura(Venta venta) throws PrinterException {

        PrinterJob job = PrinterJob.getPrinterJob();
            PageFormat pf = job.defaultPage(); //job.pageDialog(aset)
            job.setPrintable(new FormatoTicket(venta), pf);
            PrintService[] printServices = PrintServiceLookup.lookupPrintServices(null, null);
            PrintService selectedService = null;
            for (int i = 0; i < printServices.length; i++) {//EPSON TM-U220 Receipt

                //if (printServices[i].getName().equals("\\\\printserver\\usbhp")) {
                if (printServices[i].getName().equals("EPSON TM-U220 Receipt")) {
                    selectedService = printServices[i];
                    break;
                }
            }
            if (selectedService != null) {
                job.setPrintService(selectedService);
                job.print();
            }

    }//imprimirFactura

}//FIN DE LA CLASE ServidorImpresion
