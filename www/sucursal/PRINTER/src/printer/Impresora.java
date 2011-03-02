/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package printer;

import java.awt.print.PageFormat;
import java.awt.print.PrinterException;
import java.awt.print.PrinterJob;
import javax.print.PrintService;
import javax.print.PrintServiceLookup;

/**
 *
 * @author manuel
 */
class Impresora {

    /********************************************************************
     *	A continuación el constructor de la clase. Aquí lo único que	*
     *	se hace es tomar un objeto de impresion.							*
     ********************************************************************/
    Impresora() {
    }

    /********************************************************************
     *	A continuación el método "imprimir(String)", el encargado de 	*
     *	colocar en el objeto gráfico la cadena que se le pasa como 		*
     *	parámetro y se imprime.											*
     ********************************************************************/
    public void imprimirTicket(Venta venta) throws PrinterException {

        PrinterJob pj = PrinterJob.getPrinterJob();

        PageFormat pf = pj.defaultPage();//pj.pageDialog ( pageFormat )

        pj.setPrintable(new FormatoTicket(venta), pf);
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
            pj.setPrintService(selectedService);
            pj.print();
        }


    }//FIN DEL PROCEDIMIENTO imprimir(String...)

    public void imprimirFactura(Venta venta) {
    }
}//FIN DE LA CLASE Impresora

