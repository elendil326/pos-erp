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
     * Initialization method that will be called after the applet is loaded
     * into the browser.
     */
    public void init() {
        // TODO start asynchronous download of heavy resources
    }

    public void start() {

        RepaintManager currentManager =
                RepaintManager.currentManager(this);
        currentManager.setDoubleBufferingEnabled(false);


        //String json_dec = URLDecoder.decode(getParameter("json"));
        String json_dec = "{\"tipo_venta\": \"credito\",\"impresora\":\"EPSON TM-U220 Receipt\",\"leyendasTicket\":{\"cabeceraTicket\":\"JUAN ANTONIO GARCIA TAPIA\",\"rfc\":\"GATJ680704DF2\",\"nombreEmpresa\":\"PAPAS SUPREMAS 1\",\"direccion\":\"JUAN MANUEL GARCIA CARMONA ING\",\"telefono\":\"(461) 61 28194\",\"notaFiscal\":\"Este comprobante no es valido para fines fiscales.\",\"cabeceraPagare\":\"PAGARE\",\"pagare\":\"DEBE(MOS) Y PAGARE(MOS) INCONDICIONALMENTE A LA ORDEN DE JUAN ANTONIO GARCIA TAPIA EN LA CIUDAD DE ____________________ EL ____________________ LA CANTIDAD DE ____________________ VALOR RECEBIDO A NUESTRA ENTERA SATISFACCION.\",\"contacto\":\"QUEJAS Y SUGERENCIAS (461) 61 72030\",\"gracias\":\"GRACIAS POR SU COMPRA\"},\"items\": [{\"descripcion\": \"papas primeras\",\"existencias\": \"2197\",\"existencias_procesadas\": \"271\",\"tratamiento\": \"limpia\",\"precioVenta\": \"11\",\"precioVentaSinProcesar\": \"10\",\"precio\": \"11\",\"id_producto\": 1,\"escala\": \"kilogramo\",\"precioIntersucursal\": \"10.5\",\"precioIntersucursalSinProcesar\": \"9.5\",\"procesado\": \"true\",\"cantidad\": 2,\"idUnique\": \"1_7\",\"descuento\": \"0\"}],\"cliente\": {\"id_cliente\": \"2\",\"rfc\": \"ALCB770612\",\"nombre\": \"BRENDA ALFARO CARMONA\",\"direccion\": \"MUTUALISMO #345, COL. CENTRO\",\"ciudad\": \"CELAYA\",\"telefono\": \"a\",\"e_mail\" : \" \",\"limite_credito\": \"20\",\"descuento\": \"2\",\"activo\": \"1\",\"id_usuario\": \"101\",\"id_sucursal\": \"1\",\"fecha_ingreso\": \"2011-01-12 18:05:59\",\"credito_restante\": 19.5},\"venta_preferencial\": {\"cliente\": null,\"id_autorizacion\": null},\"factura\": false,\"tipo_pago\": \"cheque\",\"subtotal\": 22,\"total\": 21.56,\"pagado\": \"21.56\",\"id_venta\": 230,\"empleado\": \"Alan gonzalez hernandez\",\"sucursal\": {\"id_sucursal\": \"1\",\"gerente\": \"102\",\"descripcion\": \"papas supremas 1\",\"direccion\": \"monte radiante #123 col centro celaya\",\"rfc\": \"alskdfjlasdj8787\",\"telefono\": \"1726376672\",\"token\": null,\"letras_factura\": \"c\",\"activo\": \"1\",\"fecha_apertura\": \"2011-01-09 01:38:26\",\"saldo_a_favor\": \"0\"},\"ticket\": \"venta_cliente\"}";
        //String json_dec = "{\"tipo_venta\": \"credito\",\"impresora\":\"Microsoft XPS Document Writer\",\"leyendasTicket\":{\"cabeceraTicket\":\"JUAN ANTONIO GARCIA TAPIA\",\"rfc\":\"GATJ680704DF2\",\"nombreEmpresa\":\"PAPAS SUPREMAS 1\",\"direccion\":\"JUAN MANUEL GARCIA CARMONA ING\",\"telefono\":\"(461) 61 28194\",\"notaFiscal\":\"Este comprobante no es valido para fines fiscales.\",\"cabeceraPagare\":\"PAGARE\",\"pagare\":\"DEBE(MOS) Y PAGARE(MOS) INCONDICIONALMENTE A LA ORDEN DE JUAN ANTONIO GARCIA TAPIA EN LA CIUDAD DE ____________________ EL ____________________ LA CANTIDAD DE ____________________ VALOR RECEBIDO A NUESTRA ENTERA SATISFACCION.\",\"contacto\":\"QUEJAS Y SUGERENCIAS (461) 61 72030\",\"gracias\":\"GRACIAS POR SU COMPRA\"},\"items\": [{\"descripcion\": \"papas primeras\",\"existencias\": \"2197\",\"existencias_procesadas\": \"271\",\"tratamiento\": \"limpia\",\"precioVenta\": \"11\",\"precioVentaSinProcesar\": \"10\",\"precio\": \"11\",\"id_producto\": 1,\"escala\": \"kilogramo\",\"precioIntersucursal\": \"10.5\",\"precioIntersucursalSinProcesar\": \"9.5\",\"procesado\": \"true\",\"cantidad\": 2,\"idUnique\": \"1_7\",\"descuento\": \"0\"}],\"cliente\": {\"id_cliente\": \"2\",\"rfc\": \"ALCB770612\",\"nombre\": \"BRENDA ALFARO CARMONA\",\"direccion\": \"MUTUALISMO #345, COL. CENTRO\",\"ciudad\": \"CELAYA\",\"telefono\": \"a\",\"e_mail\" : \" \",\"limite_credito\": \"20\",\"descuento\": \"2\",\"activo\": \"1\",\"id_usuario\": \"101\",\"id_sucursal\": \"1\",\"fecha_ingreso\": \"2011-01-12 18:05:59\",\"credito_restante\": 19.5},\"venta_preferencial\": {\"cliente\": null,\"id_autorizacion\": null},\"factura\": false,\"tipo_pago\": \"cheque\",\"subtotal\": 22,\"total\": 21.56,\"pagado\": \"21.56\",\"id_venta\": 230,\"empleado\": \"Alan gonzalez hernandez\",\"sucursal\": {\"id_sucursal\": \"1\",\"gerente\": \"102\",\"descripcion\": \"papas supremas 1\",\"direccion\": \"monte radiante #123 col centro celaya\",\"rfc\": \"alskdfjlasdj8787\",\"telefono\": \"1726376672\",\"token\": null,\"letras_factura\": \"c\",\"activo\": \"1\",\"fecha_apertura\": \"2011-01-09 01:38:26\",\"saldo_a_favor\": \"0\"},\"ticket\": \"venta_cliente\"}";

        System.out.println("====================================================================");
        System.out.println("Nuevo T&icket\n====================================================================");

        
        //String hora = URLDecoder.decode(getParameter("hora"));
        String hora = "22:12:12";

        //String fecha = URLDecoder.decode(getParameter("fecha"));
        String fecha = "2011-12-12";

        System.out.println("El JSON en bruto llego como :\n" + json_dec + "\nHora y Fecha : " + hora + " " + fecha);

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
