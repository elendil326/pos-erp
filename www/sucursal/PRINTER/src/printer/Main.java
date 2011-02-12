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

       
 /*       String json_dec="{\"tipoDeVenta\":\"contado\",\"tipo_venta\":\"contado\",\"items\":[{\"descripcion\":"+
         "\"PAPA PRIMERA ORIGINAL\",\"existencias\":\"2847\",\"existenciasMinimas\":\"100\","+
         "\"precioVenta\":\"7.5\",\"productoID\":1,\"medida\":\"fraccion\","+
         "\"precioIntersucursal\":\"7.5\",\"cantidad\":1},{\"descripcion\":"+
        "\"PAPA PRIMERA PROCESADA\",\"existencias\":\"2847\",\"existenciasMinimas\":\"100\","+
         "\"precioVenta\":\"7.5\",\"productoID\":1,\"medida\":\"fraccion\","+
         "\"precioIntersucursal\":\"7.5\",\"cantidad\":1}],\"cliente\":null,"+
         "\"factura\":false,\"subtotal\":7.5,\"total\":7.5,\"pagado\":\"10\","+
         "\"id_venta\":998,\"empleado\":\"RAUL ALEJANDRO LEMUS FLORES\",\"sucursal\":{"+
         "\"id_sucursal\":\"1\",\"gerente\":\"11\",\"descripcion\":"+
         "\"PAPAS SUPREMAS 1\",\"direccion\":\"MERCADO DE ABASTOS    BODEGA 49"+
         ", CELAYA,GUANAJUATO\",\"rfc\":\"GATJ740714F48\",\"telefono\":"+
         "\"014616128194\",\"token\":null,\"letras_factura\":\"A\","+
         "\"activo\":\"1\",\"fecha_apertura\":\"2010-12-30 22:12:02\","+
         "\"saldo_a_favor\":\"0\"},\"ticket\":true}";*/
        
        //String json_dec="{\"tipo_venta\":\"credito\",\"items\":[{\"descripcion\":\"PAPAPRIMERA\",\"existencias\":\"0\",\"existencias_procesadas\":\"388\",\"tratamiento\":\"limpia\",\"precioVenta\":\"7.5\",\"precioVentaSinProcesar\":\"7.5\",\"precio\":\"7.5\",\"id_producto\":1,\"escala\":\"kilogramo\",\"precioIntersucursal\":\"7.5\",\"precioIntersucursalSinProcesar\":\"7.5\",\"procesado\":\"true\",\"cantidad\":1,\"idUnique\":\"1_1\"}],\"cliente\":{\"id_cliente\":\"8\",\"rfc\":\"CAAI6012142Q6\",\"nombre\":\"IGNACIOCARRANZAALMANZA\",\"direccion\":\"RAFAELMARTINEZ#704ACOL.ELVERGELC.P.38070\",\"ciudad\":\"CELAYAGUANAJUATO\",\"telefono\":\"00\",\"e_mail\":\"NO\",\"limite_credito\":\"5000\",\"descuento\":\"0\",\"activo\":\"1\",\"id_usuario\":\"11\",\"id_sucursal\":\"1\",\"fecha_ingreso\":\"2011-01-1316:33:07\",\"credito_restante\":4992.5},\"cliente_preferencial\":null,\"factura\":false,\"tipo_pago\":null,\"subtotal\":7.5,\"total\":7.5,\"id_venta\":38,\"empleado\":\"RAULALEJANDROLEMUSFLORES\",\"sucursal\":{\"id_sucursal\":\"5\",\"gerente\":\"95\",\"descripcion\":\"PAPASSUPREMAS3\",\"direccion\":\"CENTRALDEABASTOSANDENEBODEGA43APASEOELGRANDEGUANAJUATO\",\"rfc\":\"GATJ740714F48\",\"telefono\":\"014616172030\",\"token\":null,\"letras_factura\":\"C\",\"activo\":\"1\",\"fecha_apertura\":\"2011-01-0314:56:30\",\"saldo_a_favor\":\"0\"},\"ticket\":true}";
 //abono_prestamo y abono_venta
 //String json_dec="{    \"abono_prestamo\": true,\"empleado\": \"RAUL ALEJANDRO LEMUS FLORES\",    \"id_prestamo\": 1,    \"concepto_prestamo\": \"asdasdasdas dasdasdasd\",    \"saldo_prestamo\": 123,    \"monto_abono\": 54,    \"sucursal_origen\": {        \"descripcion\": \"PAPAS SUPREMAS 3\",        \"direccion\": \"CENTRAL DE ABASTOS ANDEN E BODEGA 43  APASEO EL GRANDE GUANAJUATO\",        \"rfc\": \"GATJ740714F48\",        \"telefono\": \"014616172030\"     },    \"sucursal_destino\": {        \"descripcion\": \"PAPAS SUPREMAS 3\",        \"direccion\": \"CENTRAL DE ABASTOS ANDEN E BODEGA 43  APASEO EL GRANDE GUANAJUATO\",        \"rfc\": \"GATJ740714F48\",        \"telefono\": \"014616172030\"     }}";
 String json_dec = URLDecoder.decode(getParameter("json"));
        //{"tipoDeVenta":"contado","items":[{"descripcion":"PAPA PRIMERA","existencias":"2847","existenciasMinimas":"100","precioVenta":"7.5","productoID":1,"medida":"fraccion","precioIntersucursal":"7.5","cantidad":1}],"cliente":null,"factura":false,"subtotal":7.5,"total":7.5,"pagado":"10","id_venta":998,"empleado":"JUANA ESCOBAR MARTINEZ","sucursal":{"id_sucursal":"1","gerente":"11","descripcion":"PAPAS SUPREMAS 1","direccion":"MERCADO DE ABASTOS    BODEGA 49, CELAYA,GUANAJUATO","rfc":"GATJ740714F48","telefono":"014616128194","token":null,"letras_factura":"A","activo":"1","fecha_apertura":"2010-12-30 22:12:02","saldo_a_favor":"0"},"ticket":true}
        
 String hora = URLDecoder.decode(getParameter("hora"));
//       String hora = "22:12:02";
        String fecha = URLDecoder.decode(getParameter("fecha"));
//        String fecha = "2010-12-30";
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
                                    impresora.imprimirFactura( venta );
                                }
                                if(!venta.factura){
                                    impresora.imprimirTicket( venta );
                                }
                                if(venta.abono_prestamo){
                                    impresora.imprimirTicket( venta );
                                }
                            } catch (PrinterException ex) {
                                Logger.getLogger(Main.class.getName()).log(Level.SEVERE, null, ex);
                            }
    }//start
    // TODO overwrite start(), stop() and destroy() methods
   
}
