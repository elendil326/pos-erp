/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package printer;

import java.applet.Applet;

import java.net.URLDecoder;
import javax.swing.JOptionPane;

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
        Impresora impresora = new Impresora( );

        //mandamos imprimir el ticket de la venta
        if( venta.ticket ){
            impresora.imprimirTicket( venta );
        }

        //mandamos imprimir la factura
        if( venta.factura ){
            impresora.imprimirFactura( venta );
        }

    }//start

    // TODO overwrite start(), stop() and destroy() methods
}
