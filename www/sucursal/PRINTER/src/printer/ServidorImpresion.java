/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package printer;

import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.PrintJob;
import java.awt.print.PageFormat;
import java.awt.print.Paper;
import java.awt.print.Printable;

import java.awt.print.PrinterException;
import java.awt.print.PrinterJob;
import javax.print.PrintService;
import javax.print.PrintServiceLookup;
import javax.print.attribute.HashPrintRequestAttributeSet;
import javax.print.attribute.standard.MediaPrintableArea;
import javax.swing.RepaintManager;

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
        PageFormat pf = job.defaultPage();
        job.setPrintable(new FormatoTicket(venta));
        PrintService[] printServices = PrintServiceLookup.lookupPrintServices(null, null);
        PrintService selectedService = null;
        for (int i = 0; i < printServices.length; i++) {
            //javax.swing.JOptionPane.showMessageDialog(null, printServices[i].getName());
            //HP Photosmart D110 series
            //EPSON TM-U220 Receipt
            //HP Photosmart Prem C310 series
            //Microsoft XPS Document Writer
            if (printServices[i].getName().equals("EPSON TM-U220 Receipt")) {
                selectedService = printServices[i];
                break;
            }
        }
        if (selectedService != null) {
            job.setPrintService(selectedService);
            job.print();
            
            //imprimir segundo ticket en caso de ser a credito
            if(venta.tipoVenta==null){

            }else{
                if( venta.tipoVenta.equals("credito")){
                    job.print();
                }
            }

        }
    }//imprimirTicket

    public void imprimirFactura(Venta venta) throws PrinterException {



        PrinterJob job = PrinterJob.getPrinterJob();

        System.out.println("Creacion de la pagina");

        PageFormat format = new PageFormat();
        Paper paper = new Paper();
        paper.setSize(830, 1080);
        paper.setImageableArea(paper.getImageableX(), paper.getImageableY(), paper.getWidth(), paper.getHeight());
        format.setPaper(paper);


        System.out.println("Se asigna al JOB el FormatoFactura");
        //job.defaultPage(format);
        //job.print(null);


        //job.defaultPage(format);

        job.setPrintable(new FormatoFactura(venta) /*new Printable() {



                public int print(Graphics graphics, PageFormat pageFormat,
                int pageIndex) throws PrinterException {
                if (pageIndex > 1) {
                return Printable.NO_SUCH_PAGE;
                }

                System.out.println(pageFormat.getImageableX() + " "
                + pageFormat.getImageableY());

                Graphics2D factura = (Graphics2D) graphics;
                factura.drawString("hola", 50, 50);

                return Printable.PAGE_EXISTS;
                }
                }*/, format);

        System.out.println("Se termia la asignacion de el FormatoFactura al JOB");
        PrintService[] printServices = PrintServiceLookup.lookupPrintServices(null, null);
        PrintService selectedService = null;
        System.out.println("Inicia la busqueda de la impresora");
        for (int i = 0; i < printServices.length; i++) {
            //EPSON TM-U220 Receipt
            //HP Photosmart Prem C310 series

            if (printServices[i].getName().equals("HP Photosmart D110 series")) {
                System.out.println("Se encontro la impresora");
                selectedService = printServices[i];
                break;
            }
        }
        if (selectedService != null) {
            System.out.println("Se asigna la impresora al JOB");
            job.setPrintService(selectedService);
            System.out.println("Se manda imprimir");

            //HashPrintRequestAttributeSet pattribs=new HashPrintRequestAttributeSet();
//pattribs.add(new MediaPrintableArea(72, 72, 830, 900, MediaPrintableArea.INCH));

            job.print(/*pattribs*/);
            System.out.println("Regresa de imprimir");
        }





    }//imprimirFactura

    static int imprimeSinDesborde(int maxLenght, String cadena, Graphics2D g, int x, int y, int inc) {

        while (cadena.length() > maxLenght) {

            g.drawString(cadena.substring(0, maxLenght), x, y);
            

            cadena = cadena.substring(maxLenght, cadena.length() );

            y += inc;

        }

        g.drawString(cadena, x, y);

        y += inc;

        return y;

    }
}//FIN DE LA CLASE ServidorImpresion

