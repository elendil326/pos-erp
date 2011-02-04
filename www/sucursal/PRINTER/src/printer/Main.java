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

       
 /*String val="{\"tipoDeVenta\":\"credito\",\"tipo_venta\":\"credito\",\"items\":[{\"descripcion\":"+
 "\"PAPA PRIMERA ORIGINAL\",\"existencias\":\"2847\",\"existenciasMinimas\":\"100\","+
         "\"precioVenta\":\"7.5\",\"productoID\":1,\"medida\":\"fraccion\","+
         "\"precioIntersucursal\":\"7.5\",\"cantidad\":1},{\"descripcion\":"+
 "\"PAPA PRIMERA PROCESADA\",\"existencias\":\"2847\",\"existenciasMinimas\":\"100\","+
         "\"precioVenta\":\"7.5\",\"productoID\":1,\"medida\":\"fraccion\","+
         "\"precioIntersucursal\":\"7.5\",\"cantidad\":1}],\"cliente\":null,"+
         "\"factura\":false,\"subtotal\":7.5,\"total\":7.5,\"pagado\":\"10\","+
         "\"id_venta\":998,\"empleado\":\"JUANA ESCOBAR MARTINEZ\",\"sucursal\":{"+
         "\"id_sucursal\":\"1\",\"gerente\":\"11\",\"descripcion\":"+
         "\"PAPAS SUPREMAS 1\",\"direccion\":\"MERCADO DE ABASTOS    BODEGA 49"+
         ", CELAYA,GUANAJUATO\",\"rfc\":\"GATJ740714F48\",\"telefono\":"+
         "\"014616128194\",\"token\":null,\"letras_factura\":\"A\","+
         "\"activo\":\"1\",\"fecha_apertura\":\"2010-12-30 22:12:02\","+
         "\"saldo_a_favor\":\"0\"},\"ticket\":true}";
        String json_dec = URLDecoder.decode(val);*/

        //{"tipoDeVenta":"contado","items":[{"descripcion":"PAPA PRIMERA","existencias":"2847","existenciasMinimas":"100","precioVenta":"7.5","productoID":1,"medida":"fraccion","precioIntersucursal":"7.5","cantidad":1}],"cliente":null,"factura":false,"subtotal":7.5,"total":7.5,"pagado":"10","id_venta":998,"empleado":"JUANA ESCOBAR MARTINEZ","sucursal":{"id_sucursal":"1","gerente":"11","descripcion":"PAPAS SUPREMAS 1","direccion":"MERCADO DE ABASTOS    BODEGA 49, CELAYA,GUANAJUATO","rfc":"GATJ740714F48","telefono":"014616128194","token":null,"letras_factura":"A","activo":"1","fecha_apertura":"2010-12-30 22:12:02","saldo_a_favor":"0"},"ticket":true}
        String hora = URLDecoder.decode(getParameter("hora"));
        //String hora = "22:12:02";
//String hora="22:12:02";
        String fecha = URLDecoder.decode(getParameter("fecha"));
        //String fecha = "2010-12-30";
//String fecha="2010-12-30";
        //crea un objeto venta apartir del JSON enviado desde el applet

       String json_dec = URLDecoder.decode(getParameter("json"));
       
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
                impresora.imprimirFactura( venta );
                //System.out.println("regreso");
            } else {
                impresora.imprimirTicket( venta );
//imp(venta);
            }
        } catch (PrinterException ex) {
            Logger.getLogger(Main.class.getName()).log(Level.SEVERE, null, ex);
        }

    }//start
    // TODO overwrite start(), stop() and destroy() methods
   
}
