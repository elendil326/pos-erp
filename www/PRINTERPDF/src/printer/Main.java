/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package printer;

import java.applet.Applet;

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
     * Variable que sirve para controlar las impresiones que facilitan la depuracion
     */
    static boolean debug = false;
    /**
     *
     */
    static boolean debug_all = true;

    /**
     * Initialization method that will be called after the applet is loaded
     * into the browser.
     */
    public void init() {
        // TODO start asynchronous download of heavy resources
    }

    public void start() {

        //fijamos valores para depuracion
        Main.debug = (debug_all) ? true : false;
        ServidorImpresion.debug = (debug_all) ? true : false;

        RepaintManager currentManager =
                RepaintManager.currentManager(this);
        currentManager.setDoubleBufferingEnabled(false);

        String file = URLDecoder.decode(getParameter("file"));
        //String file = "prueba.pdf";

        String printer = URLDecoder.decode(getParameter("printer"));
        //String printer = "HP Photosmart D110 series";

        try {
            new ServidorImpresion(file, printer);
        } catch (Exception ex) {
            Logger.getLogger(Main.class.getName()).log(Level.SEVERE, null, ex);
        }

    }//start
}
