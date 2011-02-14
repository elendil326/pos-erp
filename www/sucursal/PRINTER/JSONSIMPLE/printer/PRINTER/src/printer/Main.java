/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package printer;

import java.applet.Applet;
import java.awt.print.PrinterException;

import java.net.URLDecoder;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.swing.RepaintManager;

/**
 *
 * @author manuel
 */
public class Main extends Applet {

    /**
     * Initialization method that will be called after the applet is loaded
     * into the browser.
     */
    public void init() {
        // TODO start asynchronous download of heavy resources
    }

    public void start() {

        RepaintManager currentManager =
   RepaintManager.currentManager(this);
 currentManager.setDoubleBufferingEnabled (false);

        String json_dec = URLDecoder.decode(getParameter("json"));

        String hora = URLDecoder.decode(getParameter("hora"));

        String fecha = URLDecoder.decode(getParameter("hora"));

        //crea un objeto venta apartir del JSON enviado desde el applet
        Venta venta = new Venta(json_dec, hora, fecha);

        //creamos el objeto impresora
        ServidorImpresion impresora = new ServidorImpresion();
        try {
            //mandamos imprimir el ticket de la venta
            //if( venta.ticket ){
            //impresora.imprimirTicket( venta );
            //}
            //mandamos imprimir la factura
            if (venta.factura) {
                //System.out.println("manda llamar imp fact");
                impresora.imprimirFactura(venta);
                //System.out.println("regreso");
            } else {
                impresora.imprimirTicket( venta );
            }
        } catch (PrinterException ex) {
            Logger.getLogger(Main.class.getName()).log(Level.SEVERE, null, ex);
        }

    }//start
    // TODO overwrite start(), stop() and destroy() methods
}
