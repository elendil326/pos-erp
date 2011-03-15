/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package printer;

import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.print.PageFormat;
import java.awt.print.Printable;
import java.awt.print.PrinterException;
import java.util.ArrayList;
import java.util.Iterator;
import java.util.Map;
import org.json.simple.JSONArray;
import org.json.simple.JSONValue;
import org.json.simple.parser.JSONParser;

/**
 *
 * @author manuel
 *
 * Esta clase se encarga de construir y formatear el Ticket de Venta
 */
public class TicketVentaCliente extends FormatoTicket implements Printable {

    //--------------------------------------------------------------------//
    //     Propiedades especificas de este ticket                         //
    //--------------------------------------------------------------------//
    /**
     * Sucursal en la cual se realizo al venta
     */
    private Sucursal sucursal = null;
    /**
     * Cliente al cual se le realizo la venta
     */
    private Cliente cliente = null;
    /**
     * Tipo de venta. "credito" | "contado"
     */
    private String tipoVenta = null;
    /**
     * Tipo de pago de la venta. "efectivo" | "cheque" | "targeta"
     */
    private String tipoPago = null;
    /**
     * Almacena de registro de cada producto vendido
     */
    private ArrayList<Producto> productos = new ArrayList<Producto>();
    /**
     * Subtotal de la venta
     */
    private float subtotal = -1;
    /**
     * Total de la venta
     */
    private float total = -1;
    /**
     *
     */
    private float pagado = -1;
    /**
     * ID de la venta actual
     */
    private String id_venta = null;
    /**
     * Responsable
     */
    private String empleado = null;
    //--------------------------------------------------------------------//
    //     Constructor de la clase                                        //
    //--------------------------------------------------------------------//

    /**
     *
     * @param json json en bruto que contiene toda la informacion del documento a imprimir
     * @param hora hora en el servidor al momento que se envio el documento
     * @param fecha fecha en el servidor al momento que se envio el documento
     */
    TicketVentaCliente(String json, String hora, String fecha) {

        this.json = json;
        this.hora = hora;
        this.fecha = fecha;

        //extraemos al informacion del JSON para establecer las propiedades de la venta al cliente
        this.ventaCliente();

        //valida que exista la informacion necesaria para proceguir con la construccion del ticket
        this.ventaClienteValidator();

    }

    /**
     * extrae toda la informacion necesaria del JSON y la almacena en propiedades
     * de esta clase, para que posteriormente se usen al momento de imprimir el ticket
     */
    private void ventaCliente() {

        System.out.println("Iniciado proceso de construccion de Venta a Cliente");

        JSONParser parser = new JSONParser();

        try {

            Map jsonmap = (Map) parser.parse(this.json);
            Iterator iter = jsonmap.entrySet().iterator();

            System.out.println("Se iterara a : " + iter.toString().toString());

            //recorremos cada propiedad del JSON
            while (iter.hasNext()) {

                Map.Entry entry = (Map.Entry) iter.next();

                System.out.println(entry.getKey() + " => " + entry.getValue());


                if (entry.getKey().toString().equals("tipo_venta")) {

                    if (entry.getValue() != null && entry.getValue() != "") {

                        try {
                            this.tipoVenta = entry.getValue().toString();
                            System.out.println("this.tipoVenta : " + this.tipoVenta);
                        } catch (Exception e) {
                            System.err.print(e);
                        }

                    }

                }

                if (entry.getKey().toString().equals("tipo_pago")) {

                    if (entry.getValue() != null && entry.getValue() != "") {

                        try {
                            this.tipoPago = entry.getValue().toString();
                            System.out.println("this.tipoPago : " + this.tipoPago);
                        } catch (Exception e) {
                            System.err.print(e);
                        }

                    }

                }

                if (entry.getKey().toString().equals("sucursal")) {

                    if (entry.getValue() != null) {

                        try {
                            this.sucursal = new Sucursal(entry.getValue().toString());
                            System.out.println("this.sucursal : " + this.sucursal);
                        } catch (Exception e) {
                            System.err.print(e);
                        }

                    }

                }//if

                if (entry.getKey().toString().equals("cliente")) {

                    if (entry.getValue() != null) {

                        try {
                            this.cliente = new Cliente(entry.getValue().toString());
                            System.out.println("this.cliente : " + this.cliente.nombre);
                        } catch (Exception e) {
                            System.err.print(e);
                        }

                    }

                }//if

                if (entry.getKey().toString().equals("items")) {

                    String json_items = entry.getValue().toString();
                    System.out.println("this.json_items: " + json_items);

                    Object obj = JSONValue.parse(json_items);
                    JSONArray array = (JSONArray) obj;

                    for (int i = 0; i < array.size(); i++) {

                        //metemos cada producto comprado a una coleccion de productos
                        this.productos.add(new Producto(array.get(i).toString()));
                        System.out.println(this.productos.get(i).getDescripcion() + ", Cantidad : " + this.productos.get(i).getCantidad() + ", Precio : " + this.productos.get(i).getPrecio() + ", Subtotal : " + this.productos.get(i).getSubTotal());

                    }//for

                }//if


                if (entry.getKey().toString().equals("id_venta")) {

                    if (entry.getValue() != null && entry.getValue() != "") {

                        try {
                            this.id_venta = entry.getValue().toString();
                            System.out.println("this.id_venta : " + this.id_venta);
                        } catch (Exception e) {
                            System.err.print(e);
                        }

                    }

                }

                if (entry.getKey().toString().equals("empleado")) {

                    if (entry.getValue() != null && entry.getValue() != "") {

                        try {
                            this.empleado = entry.getValue().toString();
                            System.out.println("this.responsable : " + this.empleado);
                        } catch (Exception e) {
                            System.err.print(e);
                        }

                    }

                }

                if (entry.getKey().toString().equals("subtotal")) {

                    if (entry.getValue() != null && entry.getValue() != "") {

                        try {
                            this.subtotal = Float.parseFloat(entry.getValue().toString());
                            System.out.println("this.subtotal : " + this.subtotal);
                        } catch (Exception e) {
                            System.err.print(e);
                        }

                    }

                }

                if (entry.getKey().toString().equals("total")) {

                    if (entry.getValue() != null && entry.getValue() != "") {

                        System.out.println("this.total : " + this.total);

                        try {
                            this.total = Float.parseFloat(entry.getValue().toString());
                        } catch (Exception e) {
                            System.err.print(e);
                        }

                    }

                }

                if (entry.getKey().toString().equals("pagado")) {

                    if (entry.getValue() != null && entry.getValue() != "") {

                        System.out.println("this.pagado : " + this.pagado);

                        try {
                            this.pagado = Float.parseFloat(entry.getValue().toString());
                        } catch (Exception e) {
                            System.err.print(e);
                        }

                    }

                }


            }//while

            System.out.println("Terminado proceso de construccion de Venta a Cliente");

        } catch (Exception pe) {
            System.out.println(pe);
        }

    }//ventaCliente

    /**
     * Verifica que se hayan establecedo correctamente todos los valores necesarios para construir el ticket de venta a cliente
     */
    private void ventaClienteValidator() {

        System.out.println("Iniciando proceso de validacion de venta a cliente");

        int cont = 0;

        if (this.sucursal == null) {
            System.err.println("Error : sucursal");
            cont++;
        }

        if (this.cliente == null) {
            System.err.println("Error : cliente");
            cont++;
        }

        if (this.tipoVenta == null || this.tipoVenta.equals("")) {
            System.err.println("Error : tipoVenta");
            cont++;
        }

        if (this.tipoPago == null || this.tipoPago.equals("")) {
            System.err.println("Error : tipoPago");
            cont++;
        }

        if (this.productos.size() <= 0) {
            System.err.println("Error : size de productos es <= 0");
            cont++;
        }

        if (this.subtotal == -1) {
            System.err.println("Error : No se definio el subtotal");
            cont++;
        }

        if (this.total == -1) {
            System.err.println("Error : No se definio el totañ");
            cont++;
        }

        if (this.pagado == -1) {
            System.err.print("Error : No se definio pagado");
            cont++;
        }

        if (this.id_venta == null) {
            System.err.println("Error : No se definio el id_venta");
            cont++;
        }

        if (this.empleado == null) {
            System.err.println("Error : No se definio el responsable");
            cont++;
        }

        System.out.println("Terminado proceso de validacion de venta a cliente. se encontraron " + cont + " errores.");

    }

    /**
     *
     * @param graphics Objeto grafico que se usara para imprimir en el lienzo.
     * @param pageFormat Objeto qeu contiene el formato de la hoja y las dimensiones del papel.
     * @param pageIndex Indice de la pagina a imprimir.
     * @return
     * @throws PrinterException
     */
    public int print(Graphics graphics, PageFormat pageFormat, int pageIndex) throws PrinterException {

        if (pageIndex != 0) {
            return NO_SUCH_PAGE;
        }

        //inicializamos el objeto grafico
        this.grafico = (Graphics2D) graphics;

        //le indicamos al objeto grafico donde comienza el lienzo
        this.grafico.translate(pageFormat.getImageableX(), pageFormat.getImageableY());

        /*
         *inicializamos las fuentes, esto se ahce en este momento por que para hacerlo se necesita
         *tener el objeto grafico
         */
        this.initFonts(this.grafico);

        //establecemos la fuente
        this.grafico.setFont(this.bold);


        this.grafico.drawString("Formato xtreme JUAN ANTONIO GARCIA TAPIA", this.x, this.y);

        //damos un espacio entre lineas
        this.incrementY(this.height_italic);
        //------------------------------------------------------------------------------------------------------





        this.grafico.drawString("R.F.C. " + LeyendasTicket.getRFC(), this.x, this.y);
        this.incrementY(this.height_normal);

        this.imprimeSinDesborde(this.sucursal.getDescripcion(), this.height_normal);

        this.imprimeSinDesborde(this.sucursal.getDireccion(), this.height_normal);

        this.grafico.drawString("Tel. " + this.sucursal.getTelefono(), this.x, this.y);

        this.incrementY(this.height_normal);

        this.grafico.drawString("========= ", this.x, this.y);

        this.grafico.setFont(this.bold);

        this.grafico.drawString(this.tipoVenta, this.x + 72, this.y);

        this.grafico.setFont(this.normal);

        this.grafico.drawString(" ==========", this.x + 120, this.y);

        this.incrementY(this.height_normal);

        this.grafico.setFont(this.bold);

        this.grafico.drawString(this.id_venta, this.x + 120, this.y);

        this.incrementY(this.height_normal);

        this.grafico.setFont(this.normal);

        this.grafico.drawString("==============================", this.x, this.y);

        this.incrementY(this.height_normal);

        this.grafico.drawString("Fecha : " + this.fecha + " " + this.hora, this.x, this.y);

        this.incrementY(this.height_normal);

        this.imprimeSinDesborde("Cajero : " + this.empleado, this.height_normal);

        if (this.cliente != null) {

            this.imprimeSinDesborde("Cliente : " + this.cliente.nombre, this.height_normal);

        }

        this.grafico.drawString("-------------------------------------------------------------------------", this.x, this.y);

        this.incrementY(this.height_normal);

        this.grafico.setFont(this.small);

        this.grafico.drawString("PRODUCTO", this.x, this.y);

        this.grafico.drawString("CANT", this.x + 75, this.y);

        this.grafico.drawString("P.U.", this.x + 107, this.y);

        this.grafico.drawString("SUBTOTAL", this.x + 138, this.y);

        this.incrementY(this.height_normal);

        this.grafico.setFont(this.normal);


        for (int j = 0; j < this.productos.size(); j++) {


            if (this.venta.productos.get(j).descripcion.length() > 13) {
                String[] cadena = this.venta.productos.get(j).descripcion.split(" ");
                String prod = cadena[0];
                for (int k = 1; k < cadena.length; k++) {
                    if ((prod.length() + " ".length() + cadena[k].length()) < 13) {
                        prod += " " + cadena[k];

                    } else {
                        ticket.drawString(prod, 0, y);
                        y += h_normalTicket;

                        prod = cadena[k];

                    }

                }
                ticket.drawString(prod, 0, y);

            } else {
                ticket.drawString(this.venta.productos.get(j).descripcion, 0, y);
            }

            ticket.drawString("" + this.venta.productos.get(j).cantidad, 75, y);
            ticket.drawString(this.moneda.format(this.venta.productos.get(j).precio), 107, y);

            float subtotal = this.venta.productos.get(j).cantidad * this.venta.productos.get(j).precio;

            ticket.drawString(this.moneda.format(subtotal), 138, y);
            y += h_normalSmallTicket;

        }//for



        ticket.setFont(normalTicket);
        ticket.drawString("------------------------------------------------------------------------", 0, y);
        y += h_normalTicket;

        ticket.setFont(boldSmallTicket);

        ticket.drawString("SUBTOTAL:", 63, y);
        ticket.drawString(this.moneda.format(this.venta.subtotal), 130, y);
        y += h_normalTicket;

        if (this.venta.descuento > 0) {
            ticket.drawString("DESCUENTO:", 63, y);
            ticket.drawString(this.moneda.format(this.venta.descuento), 130, y);
            y += h_normalTicket;
        }

        ticket.drawString("TOTAL:", 63, y);
        ticket.drawString(this.moneda.format(this.venta.total), 130, y);
        y += h_normalTicket;

        //javax.swing.JOptionPane.showMessageDialog(null, this.venta.tipoVenta);
        if (this.venta.tipoVenta.equals("contado")) {

            if (this.venta.tipo_pago == "cheque") {
                ticket.drawString("PAGO:", 63, y);
                ticket.drawString(this.moneda.format(this.venta.subtotal), 130, y);
            } else {

                if (!this.venta.reimpresion) {
                    ticket.drawString("PAGO:", 63, y);
                    ticket.drawString(this.moneda.format(this.venta.efectivo), 130, y);
                    y += h_normalTicket;
                }

            }


            if (!this.venta.reimpresion) {
                ticket.drawString("CAMBIO:", 63, y);
                ticket.drawString(this.moneda.format(this.venta.cambio), 130, y);
                y += h_normalTicket;
            }

        }

        ticket.setFont(normalTicket);
        ticket.drawString("------------------------------------------------------------------------", 0, y);
        y += h_normalTicket;

        y = ServidorImpresion.imprimeSinDesborde(limite_caracteres, new Converter().getStringOfNumber(this.venta.total), ticket, 0, y, h_normalTicket);

        if (!venta.tipoVenta.equals("contado")) {


            //////////////////
            String[] fiscal = this.venta.ficalTicket.split(" ");
            String fistiq = fiscal[0];
            for (int k = 1; k < fiscal.length; k++) {
                if ((fistiq.length() + " ".length() + fiscal[k].length()) < limite_caracteres) {
                    fistiq += " " + fiscal[k];

                } else {
                    ticket.drawString(fistiq, 0, y);
                    y += h_normalTicket;
                    fistiq = fiscal[k];
                }
            }
            ticket.drawString(fistiq, 0, y);
            y += h_normalTicket;

            ticket.setFont(boldTicket);
            y = ServidorImpresion.imprimeSinDesborde(limite_caracteres, this.venta.tituloPagare, ticket, 70, y, h_normalTicket);

            ticket.setFont(normalTicket);

            String[] cadena = this.venta.leyendaPagare.split(" ");
            String leypag = cadena[0];
            for (int k = 1; k < cadena.length; k++) {
                if ((leypag.length() + " ".length() + cadena[k].length()) < limite_caracteres) {
                    leypag += " " + cadena[k];

                } else {
                    ticket.drawString(leypag, 0, y);

                    y += h_normalTicket;

                    leypag = cadena[k];

                }
            }
            ticket.drawString(leypag, 0, y);
            y += h_normalTicket + 10;
            ticket.drawString("_____________________________________________________________", 0, y);
            y += h_normalTicket;
            ticket.drawString("Firma(s)", 70, y);
            y += h_normalTicket + 15;

        }

        ticket.drawString(this.venta.sugerencias, 0, y);
        y += h_normalTicket;

        ticket.drawString(this.venta.graciasTicket, 30, y);
        y += h_normalTicket;




        //------------------------------------------------------------------------------------------------------
        //termina con exito
        return PAGE_EXISTS;

    }
}//class

