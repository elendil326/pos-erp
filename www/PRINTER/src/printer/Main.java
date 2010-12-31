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

        String json_dec = URLDecoder.decode( getParameter( "json" ) );
        
        String hora = URLDecoder.decode( getParameter( "hora" ) );
        
        //crea un objeto venta apartir del JSON enviado desde el applet
        Venta venta = new Venta( json_dec, hora );

        //creamos el objeto impresora
        ServidorImpresion impresora = new ServidorImpresion( );
        try {
            //mandamos imprimir el ticket de la venta
            //if( venta.ticket ){
            System.out.println("manda llamar imp ticket");
              impresora.imprimirTicket( venta );
              System.out.println("saliendo de imp ticket");
            //}
            //mandamos imprimir la factura
            //if( venta.factura ){
            //System.out.println("manda llamar imp fact");
            //impresora.imprimirFactura(venta);
            //System.out.println("regreso");
            //}
        } catch (PrinterException ex) {
            Logger.getLogger(Main.class.getName()).log(Level.SEVERE, null, ex);
        }

    }//start

    // TODO overwrite start(), stop() and destroy() methods
}
