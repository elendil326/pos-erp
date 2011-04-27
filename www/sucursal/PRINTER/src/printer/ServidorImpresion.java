/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package printer;

import java.awt.print.Printable;

import java.awt.print.PrinterException;
import java.awt.print.PrinterJob;
import java.util.Iterator;
import java.util.Map;
import javax.print.PrintService;
import javax.print.PrintServiceLookup;
import org.json.simple.parser.JSONParser;
import org.json.simple.parser.ParseException;

/**
 *
 * @author manuel
 */
class ServidorImpresion {

    /**
     * Variable que sirve para controlar las impresiones que facilitan la depuracion
     */
    static private boolean debug = false;

    /**
     * se encarga de colocar en el objeto gráfico la cadena que se le pasa como
     * parámetro y se imprime.
     * @param formato
     * @throws PrinterException
     */
    public void imprimirFormatoTicket(Object formato) throws PrinterException {
        // try/catch PORQUE PUEDEN CANCELAR LA IMPRESION

        PrinterJob job = PrinterJob.getPrinterJob();
        job.setPrintable((Printable) formato);
        PrintService[] printServices = PrintServiceLookup.lookupPrintServices(null, null);
        PrintService selectedService = null;
        for (int i = 0; i < printServices.length; i++) {
            //javax.swing.JOptionPane.showMessageDialog(null, printServices[i].getName());
            //HP Photosmart D110 series
            //EPSON TM-U220 Receipt
            //HP Photosmart Prem C310 series
            //Microsoft XPS Document Writer
            if (printServices[i].getName().equals(Impresora.getDescripcion())) {
                selectedService = printServices[i];
                break;
            }

        }
        if (selectedService != null) {
            job.setPrintService(selectedService);

            try {
                job.print();
                //System.exit(0);
            } catch (PrinterException exception) {
                System.err.println("La impresora no esta recibiendo documentos.");
            }

        } else {
            System.err.print("Error : No se tiene disponible la impresora : " + Impresora.getDescripcion());
        }
    }//imprimirTicket

    /**
     * Hace un analisis del json que se envio en bruto
     * para establecer la impresora con la cual se va a imprimir el ticket,
     * establece las leyenda a imprimir en el ticket,
     * se encarga de crear un objeto Ticket
     * y regresa un Objeto con toda la informacion necesaria
     * para imprimir un ticket especifico.
     *
     * @param json
     * @return
     * @throws ParseException
     */
    static Object getObjetoImpresion(String json, String hora, String fecha) throws ParseException {


        String ticket = null;

        Object obj = null;

        JSONParser parser = new JSONParser();

        try {

            Map jsonmap = (Map) parser.parse(json);
            Iterator iter = jsonmap.entrySet().iterator();

            while (iter.hasNext()) {

                Map.Entry entry = (Map.Entry) iter.next();

                if (entry.getKey().toString().equals("impresora")) {

                    try {
                        Impresora.setDescripcion(entry.getValue().toString());
                    } catch (Exception e) {
                        System.err.println(e);
                    }

                }

                if (entry.getKey().toString().equals("leyendasTicket")) {

                    try {

                        new LeyendasTicket(entry.getValue().toString());

                    } catch (Exception e) {
                        System.err.println(e);
                    }

                }

                if (entry.getKey().toString().equals("ticket")) {

                    try {
                        ticket = entry.getValue().toString();
                        if (debug) {
                            System.out.println("El documento a imprimir es : " + ticket);
                        }

                        if (ticket.equals("venta_cliente")) {
                            if (debug) {
                                System.out.println("Creando objeto FormatoTicketVentaCliente");
                            }
                            obj = new TicketVentaCliente(json, hora, fecha);
                        }

                        if (ticket.equals("prestamo_efectivo_sucursal")) {
                            if (debug) {
                                System.out.println("Creando objeto FormatoTicketPrestamoEfectivoSucursal");
                            }
                            obj = new TicketPrestamoEfectivoSucursal(json, hora, fecha);
                        }

                        if (ticket.equals("recepcion_embarque")) {
                            if (debug) {
                                System.out.println("Creando objeto FormatoTicketRecepcionEmbarque");
                            }
                            obj = new TicketRecepcionEmbarque(json, hora, fecha);
                        }

                        if (ticket.equals("abono_venta_cliente")) {
                            if (debug) {
                                System.out.println("Creando objeto FormatoTicketAbonoVentaCliente");
                            }
                            obj = new TicketAbonoVentaCliente(json, hora, fecha);
                        }
                    } catch (Exception e) {
                        System.err.println(e);
                    }

                }//if

            }//while

            //verificamos que se hayan establecido correctamente los valores de la impresora
            Impresora.validator();

            //verificamos que se hayan establecido correctamente los valores de las leyenda
            LeyendasTicket.validator();

            //verificamos que se haya encontrado el tipo de ticket
            if (ticket == null) {
                System.err.println("Error : No se definio el tipo de ticket");
            } else {
                if (obj == null) {
                    System.err.println("Error : El tipo de ticket : " + ticket + " no es valido");
                }
            }

        } catch (Exception pe) {
            System.err.println(pe);
        }

        return obj;
    }
}
