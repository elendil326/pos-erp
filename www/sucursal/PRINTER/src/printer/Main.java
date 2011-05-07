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
import org.json.simple.parser.ParseException;

/**
 *
 * @author manuel
 */
public class Main extends Applet {

    /**
     * Variable que sirve para controlar las impresiones que facilitan la depuracion
     */
    static boolean debug = false;

    static boolean debug_all = false;

    /**
     * Initialization method that will be called after the applet is loaded
     * into the browser.
     */
    public void init() {
        // TODO start asynchronous download of heavy resources
    }

    public void start() {

        //fijamos valores para depuracion
        Main.debug = (debug_all)? true : false;
        Cliente.debug = (debug_all)? true : false;
        FormatoTicket.debug = (debug_all)? true : false;
        Impresora.debug = (debug_all)? true : false;
        LeyendasTicket.debug = (debug_all)? true : false;
        Producto.debug = (debug_all)? true : false;
        ServidorImpresion.debug = (debug_all)? true : false;
        Sucursal.debug = (debug_all)? true : false;
        TicketAbonoVentaCliente.debug = (debug_all)? true : false;
        TicketPrestamoEfectivoSucursal.debug = (debug_all)? true : false;
        TicketRecepcionEmbarque.debug = (debug_all)? true : false;
        TicketVentaCliente.debug = (debug_all)? true : false;


        RepaintManager currentManager =
                RepaintManager.currentManager(this);
        currentManager.setDoubleBufferingEnabled(false);


        
        String json_dec = URLDecoder.decode(getParameter("json"));
        
        
        //String json_dec = "{\"tipo_venta\": \"credito\",\"impresora\":\"EPSON TM-U220 Receipt\",\"leyendasTicket\":{\"cabeceraTicket\":\"JUAN ANTONIO GARCIA TAPIA\",\"rfc\":\"GATJ680704DF2\",\"nombreEmpresa\":\"PAPAS SUPREMAS 1\",\"direccion\":\"JUAN MANUEL GARCIA CARMONA ING\",\"telefono\":\"(461) 61 28194\",\"notaFiscal\":\"Este comprobante no es valido para fines fiscales.\",\"cabeceraPagare\":\"PAGARE\",\"pagare\":\"DEBE(MOS) Y PAGARE(MOS) INCONDICIONALMENTE A LA ORDEN DE JUAN ANTONIO GARCIA TAPIA EN LA CIUDAD DE ____________________ EL ____________________ LA CANTIDAD DE ____________________ VALOR RECEBIDO A NUESTRA ENTERA SATISFACCION.\",\"contacto\":\"QUEJAS Y SUGERENCIAS (461) 61 72030\",\"gracias\":\"GRACIAS POR SU COMPRA\"},\"items\": [{\"descripcion\": \"papas primeras\",\"existencias\": \"2197\",\"existencias_procesadas\": \"271\",\"tratamiento\": \"limpia\",\"precioVenta\": \"11\",\"precioVentaSinProcesar\": \"10\",\"precio\": \"11\",\"id_producto\": 1,\"escala\": \"kilogramo\",\"precioIntersucursal\": \"10.5\",\"precioIntersucursalSinProcesar\": \"9.5\",\"procesado\": \"true\",\"cantidad\": 2,\"idUnique\": \"1_7\",\"descuento\": \"0\"}],\"cliente\": {\"id_cliente\": \"2\",\"rfc\": \"ALCB770612\",\"nombre\": \"BRENDA ALFARO CARMONA\",\"direccion\": \"MUTUALISMO #345, COL. CENTRO\",\"ciudad\": \"CELAYA\",\"telefono\": \"a\",\"e_mail\" : \" \",\"limite_credito\": \"20\",\"descuento\": \"2\",\"activo\": \"1\",\"id_usuario\": \"101\",\"id_sucursal\": \"1\",\"fecha_ingreso\": \"2011-01-12 18:05:59\",\"credito_restante\": 19.5},\"venta_preferencial\": {\"cliente\": null,\"id_autorizacion\": null},\"factura\": false,\"tipo_pago\": \"cheque\",\"subtotal\": 22,\"total\": 21.56,\"pagado\": \"21.56\",\"id_venta\": 230,\"empleado\": \"Alan gonzalez hernandez\",\"sucursal\": {\"id_sucursal\": \"1\",\"gerente\": \"102\",\"descripcion\": \"papas supremas 1\",\"direccion\": \"monte radiante #123 col centro celaya\",\"rfc\": \"alskdfjlasdj8787\",\"telefono\": \"1726376672\",\"token\": null,\"letras_factura\": \"c\",\"activo\": \"1\",\"fecha_apertura\": \"2011-01-09 01:38:26\",\"saldo_a_favor\": \"0\"},\"ticket\": \"venta_cliente\"}";
        //String json_dec = "{\"tipo_venta\": \"credito\",\"impresora\":\"Microsoft XPS Document Writer\",\"leyendasTicket\":{\"cabeceraTicket\":\"JUAN ANTONIO GARCIA TAPIA\",\"rfc\":\"GATJ680704DF2\",\"nombreEmpresa\":\"PAPAS SUPREMAS 1\",\"direccion\":\"JUAN MANUEL GARCIA CARMONA ING\",\"telefono\":\"(461) 61 28194\",\"notaFiscal\":\"Este comprobante no es valido para fines fiscales.\",\"cabeceraPagare\":\"PAGARE\",\"pagare\":\"DEBE(MOS) Y PAGARE(MOS) INCONDICIONALMENTE A LA ORDEN DE JUAN ANTONIO GARCIA TAPIA EN LA CIUDAD DE ____________________ EL ____________________ LA CANTIDAD DE ____________________ VALOR RECEBIDO A NUESTRA ENTERA SATISFACCION.\",\"contacto\":\"QUEJAS Y SUGERENCIAS (461) 61 72030\",\"gracias\":\"GRACIAS POR SU COMPRA\"},\"items\": [{\"descripcion\": \"papas primeras\",\"existencias\": \"2197\",\"existencias_procesadas\": \"271\",\"tratamiento\": \"limpia\",\"precioVenta\": \"11\",\"precioVentaSinProcesar\": \"10\",\"precio\": \"11\",\"id_producto\": 1,\"escala\": \"kilogramo\",\"precioIntersucursal\": \"10.5\",\"precioIntersucursalSinProcesar\": \"9.5\",\"procesado\": \"true\",\"cantidad\": 2,\"idUnique\": \"1_7\",\"descuento\": \"0\"}],\"cliente\": {\"id_cliente\": \"2\",\"rfc\": \"ALCB770612\",\"nombre\": \"BRENDA ALFARO CARMONA\",\"direccion\": \"MUTUALISMO #345, COL. CENTRO\",\"ciudad\": \"CELAYA\",\"telefono\": \"a\",\"e_mail\" : \" \",\"limite_credito\": \"20\",\"descuento\": \"2\",\"activo\": \"1\",\"id_usuario\": \"101\",\"id_sucursal\": \"1\",\"fecha_ingreso\": \"2011-01-12 18:05:59\",\"credito_restante\": 19.5},\"venta_preferencial\": {\"cliente\": null,\"id_autorizacion\": null},\"factura\": false,\"tipo_pago\": \"cheque\",\"subtotal\": 22,\"total\": 21.56,\"pagado\": \"21.56\",\"id_venta\": 230,\"empleado\": \"Alan gonzalez hernandez\",\"sucursal\": {\"id_sucursal\": \"1\",\"gerente\": \"102\",\"descripcion\": \"papas supremas 1\",\"direccion\": \"monte radiante #123 col centro celaya\",\"rfc\": \"alskdfjlasdj8787\",\"telefono\": \"1726376672\",\"token\": null,\"letras_factura\": \"c\",\"activo\": \"1\",\"fecha_apertura\": \"2011-01-09 01:38:26\",\"saldo_a_favor\": \"0\"},\"ticket\": \"venta_cliente\"}";
        //json_dec = URLDecoder.decode("%7B%22tipo_venta%22%3A%22credito%22%2C%22items%22%3A%5B%7B%22descripcion%22%3A%22PAPA%20PRIMERA%22%2C%22existencias%22%3A%22629.8%22%2C%22existencias_procesadas%22%3A%227209.36%22%2C%22tratamiento%22%3A%22limpia%22%2C%22precioVenta%22%3A%2212%22%2C%22precioVentaSinProcesar%22%3A%2212%22%2C%22precio%22%3A%2212%22%2C%22id_producto%22%3A1%2C%22escala%22%3A%22kilogramo%22%2C%22precioIntersucursal%22%3A%2212%22%2C%22precioIntersucursalSinProcesar%22%3A%2212%22%2C%22procesado%22%3A%22true%22%2C%22cantidad%22%3A1%2C%22idUnique%22%3A%221_31%22%2C%22descuento%22%3A%220%22%7D%2C%7B%22descripcion%22%3A%22PAPA%20SEGUNDA%22%2C%22existencias%22%3A%225545.04%22%2C%22existencias_procesadas%22%3A%2226418.3%22%2C%22tratamiento%22%3A%22limpia%22%2C%22precioVenta%22%3A%2211%22%2C%22precioVentaSinProcesar%22%3A%2211%22%2C%22precio%22%3A%2211%22%2C%22id_producto%22%3A2%2C%22escala%22%3A%22kilogramo%22%2C%22precioIntersucursal%22%3A%2211%22%2C%22precioIntersucursalSinProcesar%22%3A%2211%22%2C%22procesado%22%3A%22true%22%2C%22cantidad%22%3A1%2C%22idUnique%22%3A%222_32%22%2C%22descuento%22%3A%220%22%7D%2C%7B%22descripcion%22%3A%22PAPA%20TERCERA%22%2C%22existencias%22%3A%22800%22%2C%22existencias_procesadas%22%3A%22127342%22%2C%22tratamiento%22%3A%22limpia%22%2C%22precioVenta%22%3A%229%22%2C%22precioVentaSinProcesar%22%3A%229%22%2C%22precio%22%3A%229%22%2C%22id_producto%22%3A3%2C%22escala%22%3A%22kilogramo%22%2C%22precioIntersucursal%22%3A%229%22%2C%22precioIntersucursalSinProcesar%22%3A%229%22%2C%22procesado%22%3A%22true%22%2C%22cantidad%22%3A1%2C%22idUnique%22%3A%223_33%22%2C%22descuento%22%3A%220%22%7D%2C%7B%22descripcion%22%3A%22PAPA%20CUARTA%22%2C%22existencias%22%3A%220%22%2C%22existencias_procesadas%22%3A%2229130%22%2C%22tratamiento%22%3A%22limpia%22%2C%22precioVenta%22%3A%227%22%2C%22precioVentaSinProcesar%22%3A%227%22%2C%22precio%22%3A%227%22%2C%22id_producto%22%3A4%2C%22escala%22%3A%22kilogramo%22%2C%22precioIntersucursal%22%3A%227%22%2C%22precioIntersucursalSinProcesar%22%3A%227%22%2C%22procesado%22%3A%22true%22%2C%22cantidad%22%3A1%2C%22idUnique%22%3A%224_34%22%2C%22descuento%22%3A%220%22%7D%2C%7B%22descripcion%22%3A%22PAPA%20QUINTA%22%2C%22existencias%22%3A%220%22%2C%22existencias_procesadas%22%3A%22329.452%22%2C%22tratamiento%22%3A%22limpia%22%2C%22precioVenta%22%3A%225.5%22%2C%22precioVentaSinProcesar%22%3A%225.5%22%2C%22precio%22%3A%225.5%22%2C%22id_producto%22%3A5%2C%22escala%22%3A%22kilogramo%22%2C%22precioIntersucursal%22%3A%225.5%22%2C%22precioIntersucursalSinProcesar%22%3A%225.5%22%2C%22procesado%22%3A%22true%22%2C%22cantidad%22%3A1%2C%22idUnique%22%3A%225_35%22%2C%22descuento%22%3A%220%22%7D%2C%7B%22descripcion%22%3A%22PAPA%20VERDE%20GRANDE%22%2C%22existencias%22%3A%2210%22%2C%22existencias_procesadas%22%3A%22250%22%2C%22tratamiento%22%3Anull%2C%22precioVenta%22%3A%22300%22%2C%22precioVentaSinProcesar%22%3A%22300%22%2C%22precio%22%3A%22300%22%2C%22id_producto%22%3A6%2C%22escala%22%3A%22kilogramo%22%2C%22precioIntersucursal%22%3A%22300%22%2C%22precioIntersucursalSinProcesar%22%3A%22300%22%2C%22procesado%22%3A%22true%22%2C%22cantidad%22%3A1%2C%22idUnique%22%3A%226_36%22%2C%22descuento%22%3A%220%22%7D%2C%7B%22descripcion%22%3A%22PAPA%20VERDE%20CHICA%22%2C%22existencias%22%3A%224%22%2C%22existencias_procesadas%22%3A%22282%22%2C%22tratamiento%22%3Anull%2C%22precioVenta%22%3A%22200%22%2C%22precioVentaSinProcesar%22%3A%22200%22%2C%22precio%22%3A%22200%22%2C%22id_producto%22%3A7%2C%22escala%22%3A%22kilogramo%22%2C%22precioIntersucursal%22%3A%22180%22%2C%22precioIntersucursalSinProcesar%22%3A%22180%22%2C%22procesado%22%3A%22true%22%2C%22cantidad%22%3A1%2C%22idUnique%22%3A%227_37%22%2C%22descuento%22%3A%220%22%7D%5D%2C%22cliente%22%3A%7B%22id_cliente%22%3A%227%22%2C%22rfc%22%3A%2222027700%22%2C%22razon_social%22%3A%22BERNARDO%20PLATAS%22%2C%22calle%22%3A%22CIRCUITO%20SAN%20JUAN%2038%20COL.SOLARES%22%2C%22numero_exterior%22%3A%22%22%2C%22numero_interior%22%3Anull%2C%22colonia%22%3A%22%22%2C%22referencia%22%3Anull%2C%22localidad%22%3Anull%2C%22municipio%22%3A%22SAN%20JUAN%20DEL%20RIO%22%2C%22estado%22%3A%22%22%2C%22pais%22%3A%22%22%2C%22codigo_postal%22%3A%22%22%2C%22telefono%22%3A%2262*13*21549%22%2C%22e_mail%22%3A%22%22%2C%22limite_credito%22%3A%2215000%22%2C%22descuento%22%3A%220%22%2C%22activo%22%3A%221%22%2C%22id_usuario%22%3A%221%22%2C%22id_sucursal%22%3A%225%22%2C%22fecha_ingreso%22%3A%222011-01-13%2006%3A01%3A46%22%2C%22credito_restante%22%3A6839%2C%22nombre%22%3A%22%22%7D%2C%22venta_preferencial%22%3A%7B%22cliente%22%3Anull%2C%22id_autorizacion%22%3Anull%7D%2C%22factura%22%3Afalse%2C%22tipo_pago%22%3Anull%2C%22subtotal%22%3A544.5%2C%22total%22%3A544.5%2C%22pagado%22%3Anull%2C%22id_venta%22%3A3982%2C%22empleado%22%3A%22JUANA%20MARIA%20GARCIA%20TAPIA%22%2C%22sucursal%22%3A%7B%22id_sucursal%22%3A%221%22%2C%22gerente%22%3A%2211%22%2C%22descripcion%22%3A%22PAPAS%20SUPREMAS%201%22%2C%22razon_social%22%3A%22%22%2C%22rfc%22%3A%22GATJ740714F48%22%2C%22calle%22%3A%22MERCADO%20DE%20ABASTOS%20B.%20JUAREZ%20BODEGA%2049%2C%20CELAYA%2CGUA%22%2C%22numero_exterior%22%3A%22%22%2C%22numero_interior%22%3Anull%2C%22colonia%22%3A%22%22%2C%22localidad%22%3Anull%2C%22referencia%22%3Anull%2C%22municipio%22%3A%22%22%2C%22estado%22%3A%22%22%2C%22pais%22%3A%22%22%2C%22codigo_postal%22%3A%22%22%2C%22telefono%22%3A%22014616128194%22%2C%22token%22%3Anull%2C%22letras_factura%22%3A%22E%22%2C%22activo%22%3A%221%22%2C%22fecha_apertura%22%3A%222010-12-30%2022%3A12%3A02%22%2C%22saldo_a_favor%22%3A%220%22%7D%2C%22descuento%22%3Anull%2C%22ticket%22%3A%22venta_cliente%22%2C%22impresora%22%3A%22EPSON%20TM-U220%20Receipt%22%2C%22leyendasTicket%22%3A%7B%22cabeceraTicket%22%3A%22JUAN%20ANTONIO%20GARCIA%20TAPIA%22%2C%22rfc%22%3A%22GATJ740714F48%22%2C%22nombreEmpresa%22%3A%22PAPAS%20SUPREMAS%201%22%2C%22direccion%22%3A%22MERCADO%20DE%20ABASTOS%20B.%20JUAREZ%20BODEGA%2049%2C%20CELAYA%2CGUA%20%23%20col.%20%2C%20%20%22%2C%22telefono%22%3A%22014616128194%22%2C%22notaFiscal%22%3A%22Este%20comprobante%20no%20es%20valido%20para%20fines%20fiscales.%22%2C%22cabeceraPagare%22%3A%22PAGARE%22%2C%22pagare%22%3A%22DEBE(MOS)%20Y%20PAGARE(MOS)%20INCONDICIONALMENTE%20A%20LA%20ORDEN%20DE%20JUAN%20ANTONIO%20GARCIA%20TAPIA%20EN%20LA%20CIUDAD%20DE%20____________________%20EL%20____________________%20LA%20CANTIDAD%20DE%20____________________%20VALOR%20RECEBIDO%20A%20NUESTRA%20ENTERA%20SATISFACCION.%22%2C%22contacto%22%3A%22QUEJAS%20Y%20SUGERENCIAS%20(461)%2061%2072030%22%2C%22gracias%22%3A%22GRACIAS%20POR%20SU%20COMPRA%22%7D%7D");


        if (Main.debug) {
            System.out.println("====================================================================");
            System.out.println("Nuevo T&icket\n====================================================================");
        }

        String hora = URLDecoder.decode(getParameter("hora"));
        //String hora = "22:12:12";

        String fecha = URLDecoder.decode(getParameter("fecha"));
        //String fecha = "2011-12-12";

        if (Main.debug) {
            System.out.println("El JSON en bruto llego como :\n" + json_dec + "\nHora y Fecha : " + hora + " " + fecha);
        }

        //creamos el objeto servidor
        ServidorImpresion servidor = new ServidorImpresion();

        Object document = null;

        try {

            document = ServidorImpresion.getObjetoImpresion(json_dec, hora, fecha);

        } catch (ParseException ex) {

            Logger.getLogger(Main.class.getName()).log(Level.SEVERE, null, ex);

        }


        try {

            servidor.imprimirFormatoTicket(document);

        } catch (PrinterException ex) {

            Logger.getLogger(Main.class.getName()).log(Level.SEVERE, null, ex);

        }


    }//start
    // TODO overwrite start(), stop() and destroy() methods
}
