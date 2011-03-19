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

    //EJEMPLO : "{\"tipo_venta\": \"credito\",\"impresora\":\"EPSON TM-U220 Receipt\",\"leyendasTicket\":{\"cabeceraTicket\":\"JUAN ANTONIO GARCIA TAPIA\",\"rfc\":\"GATJ680704DF2\",\"nombreEmpresa\":\"PAPAS SUPREMAS 1\",\"direccion\":\"JUAN MANUEL GARCIA CARMONA ING\",\"telefono\":\"(461) 61 28194\",\"notaFiscal\":\"Este comprobante no es valido para fines fiscales.\",\"cabeceraPagare\":\"PAGARE\",\"pagare\":\"DEBE(MOS) Y PAGARE(MOS) INCONDICIONALMENTE A LA ORDEN DE JUAN ANTONIO GARCIA TAPIA EN LA CIUDAD DE ____________________ EL ____________________ LA CANTIDAD DE ____________________ VALOR RECEBIDO A NUESTRA ENTERA SATISFACCION.\",\"contacto\":\"QUEJAS Y SUGERENCIAS (461) 61 72030\",\"gracias\":\"GRACIAS POR SU COMPRA\"},\"items\": [{\"descripcion\": \"papas primeras\",\"existencias\": \"2197\",\"existencias_procesadas\": \"271\",\"tratamiento\": \"limpia\",\"precioVenta\": \"11\",\"precioVentaSinProcesar\": \"10\",\"precio\": \"11\",\"id_producto\": 1,\"escala\": \"kilogramo\",\"precioIntersucursal\": \"10.5\",\"precioIntersucursalSinProcesar\": \"9.5\",\"procesado\": \"true\",\"cantidad\": 2,\"idUnique\": \"1_7\",\"descuento\": \"0\"}],\"cliente\": {\"id_cliente\": \"2\",\"rfc\": \"ALCB770612\",\"nombre\": \"BRENDA ALFARO CARMONA\",\"direccion\": \"MUTUALISMO #345, COL. CENTRO\",\"ciudad\": \"CELAYA\",\"telefono\": \"a\",\"e_mail\" : \" \",\"limite_credito\": \"20\",\"descuento\": \"2\",\"activo\": \"1\",\"id_usuario\": \"101\",\"id_sucursal\": \"1\",\"fecha_ingreso\": \"2011-01-12 18:05:59\",\"credito_restante\": 19.5},\"venta_preferencial\": {\"cliente\": null,\"id_autorizacion\": null},\"factura\": false,\"tipo_pago\": \"cheque\",\"subtotal\": 22,\"total\": 21.56,\"pagado\": \"21.56\",\"id_venta\": 230,\"empleado\": \"Alan gonzalez hernandez\",\"sucursal\": {\"id_sucursal\": \"1\",\"gerente\": \"102\",\"descripcion\": \"papas supremas 1\",\"direccion\": \"monte radiante #123 col centro celaya\",\"rfc\": \"alskdfjlasdj8787\",\"telefono\": \"1726376672\",\"token\": null,\"letras_factura\": \"c\",\"activo\": \"1\",\"fecha_apertura\": \"2011-01-09 01:38:26\",\"saldo_a_favor\": \"0\"},\"ticket\": \"venta_cliente\"}"

    //--------------------------------------------------------------------//
    //     Propiedades especificas de este ticket                         //
    //--------------------------------------------------------------------//
    /**
     * Obtiene el estado del ticket. TRUE si es una reimpresion de este, FALSE de lo contrario
     */
    private boolean reimpresion = false;

    /**
     * Obtiene el estado del ticket. TRUE si es una reimpresion de este, FALSE de lo contrario
     * @return
     */
    public boolean getReimpresion() {
        return this.reimpresion;
    }

    /**
     * Establece el estado del ticket. TRUE si es una reimpresion de este, FALSE de lo contrario
     * @param _reimpresion
     */
    private void setReimpresion(boolean _reimpresion) {
        this.reimpresion = _reimpresion;
    }
    /**
     * Sucursal en la cual se realizo al venta
     */
    private Sucursal sucursal = null;

    /**
     * Obtiene el objeto de tipo Sucursal de esta venta
     * @return
     */
    public Sucursal getSucursal() {
        return this.sucursal;
    }

    /**
     * Establece el objeto tipo sucursal de esta venta
     * @param _sucursal
     */
    private void setSucursal(Sucursal _sucursal) {
        this.sucursal = _sucursal;
    }
    /**
     * Cliente al cual se le realizo la venta
     */
    private Cliente cliente = null;

    /**
     * Obtiene el objeto de tipo Cliente de esta venta
     * @return
     */
    public Cliente getCliente() {
        return this.cliente;
    }

    /**
     * Establece el objeto tipo Cliente de esta venta
     * @param _sucursal
     */
    private void setTipoSucursal(Cliente _cliente) {
        this.cliente = _cliente;
    }
    /**
     * Tipo de venta. "credito" | "contado"
     */
    private String tipoVenta = null;

    /**
     * Obtiene el tipo de venta
     * @return
     */
    public String getTipoVenta() {
        return this.tipoVenta;
    }

    /**
     * Establece el tipo de venta
     * @param _tipoVenta
     */
    private void setTipoVenta(String _tipoVenta) {
        this.tipoVenta = _tipoVenta;
    }
    /**
     * Tipo de pago de la venta. "efectivo" | "cheque" | "targeta"
     */
    private String tipoPago = null;

    /**
     * Obtiene el tipo de pago de esta venta
     * @return
     */
    public String getTipoPago() {
        return this.tipoVenta;
    }

    /**
     * Establece el tipo de pago de esta venta
     * @param _tipoVenta
     */
    private void setTipoPago(String _tipoPago) {
        this.tipoPago = _tipoPago;
    }
    /**
     * Almacena de registro de cada producto vendido
     */
    private ArrayList<Producto> productos = new ArrayList<Producto>();
    /**
     * Subtotal de la venta
     */
    private float subtotal = -1;

    /**
     * Obtiene el subtotal de la venta
     * @return
     */
    public float getSubTotal() {
        return this.subtotal;
    }

    /**
     * Establece el subtotal de la venta
     * @param _subtotal
     */
    private void setSubTotal(float _subtotal) {
        this.subtotal = _subtotal;
    }
    /**
     * Total de la venta
     */
    private float total = -1;

    /**
     * Obtiene el total de la venta
     * @return
     */
    public float getTotal() {
        return this.total;
    }

    /**
     * Establece el total de la venta
     * @param _total
     */
    private void setTotal(float _total) {
        this.total = _total;
    }
    /**
     * Cantidad de dinero con la que se pago al cajero
     */
    private float dineroRecibido = -1;

    /**
     * Obtiene la cantidad de dinero con la que se pago al cajero
     * @return
     */
    public float getDineroRecibido() {
        return this.dineroRecibido;
    }

    /**
     * Establece la cantidad de dinero con la que se pago al cajero
     * @param _pagado
     */
    private void setDineroRecibido(float _dineroRecibido) {
        this.dineroRecibido = _dineroRecibido;
    }
    /**
     * ID de la venta actual
     */
    private String idVenta = null;

    /**
     * Obtiene el id de la venta
     * @return
     */
    public String getIdVenta() {
        return this.idVenta;
    }

    /**
     * Establece el id de la venta
     * @param _id_venta
     */
    private void setIdVenta(String _id_venta) {
        this.idVenta = _id_venta;
    }
    /**
     * Cajero que realizo la operacion de la venta
     */
    private String empleado = null;

    /**
     * Obtiene el nombre del empleado que realizo venta
     * @return
     */
    public String getEmpleado() {
        return this.idVenta;
    }

    /**
     * Establece el nombre del empleado que realizo venta
     * @param _id_venta
     */
    private void setEmpleado(String _empleado) {
        this.empleado = _empleado;
    }

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

        //extraemos al informacion del JSON para establecer las propiedades de la venta al cliente

        init(json, hora, fecha);

    }

    /**
     * Extrae toda la informacion necesaria del JSON y la almacena en propiedades
     * de esta clase, para que posteriormente se usen al momento de imprimir el ticket
     * @param json
     * @param hora
     * @param fecha
     */
    void init(String json, String hora, String fecha) {

        System.out.println("Iniciado proceso de construccion de Venta a Cliente");

        this.setJSON(json);

        this.setHora(hora);

        this.setFecha(fecha);

        JSONParser parser = new JSONParser();

        try {

            Map jsonmap = (Map) parser.parse(this.json);
            Iterator iter = jsonmap.entrySet().iterator();

            System.out.println("Se iterara a : " + iter.toString().toString());

            //recorremos cada propiedad del JSON

            while (iter.hasNext()) {

                Map.Entry entry = (Map.Entry) iter.next();

                System.out.println(entry.getKey().toString() + " => " + entry.getValue().toString());


                if (entry.getKey().toString().equals("tipo_venta")) {

                    if (entry.getValue() != null && entry.getValue() != "") {

                        try {
                            this.setTipoVenta(entry.getValue().toString());
                        } catch (Exception e) {
                            System.err.print(e);
                        }

                    }

                }

                if (entry.getKey().toString().equals("tipo_pago")) {

                    if (entry.getValue() != null && entry.getValue() != "") {

                        try {
                            this.setTipoPago(entry.getValue().toString());
                        } catch (Exception e) {
                            System.err.print(e);
                        }

                    }

                }

                if (entry.getKey().toString().equals("sucursal")) {

                    if (entry.getValue() != null) {

                        try {
                            this.sucursal = new Sucursal(entry.getValue().toString());
                        } catch (Exception e) {
                            System.err.print(e);
                        }

                    }

                }//if

                if (entry.getKey().toString().equals("cliente")) {

                    if (entry.getValue() != null) {

                        try {
                            this.cliente = new Cliente(entry.getValue().toString());
                        } catch (Exception e) {
                            System.err.print(e);
                        }

                    }

                }//if

                if (entry.getKey().toString().equals("items")) {

                    String json_items = entry.getValue().toString();

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
                            this.setIdVenta(entry.getValue().toString());
                        } catch (Exception e) {
                            System.err.print(e);
                        }

                    }

                }

                if (entry.getKey().toString().equals("empleado")) {

                    if (entry.getValue() != null && entry.getValue() != "") {

                        try {
                            this.setEmpleado(entry.getValue().toString());
                        } catch (Exception e) {
                            System.err.print(e);
                        }

                    }

                }

                if (entry.getKey().toString().equals("subtotal")) {

                    if (entry.getValue() != null && entry.getValue() != "") {

                        try {
                            this.setSubTotal(Float.parseFloat(entry.getValue().toString()));
                        } catch (Exception e) {
                            System.err.print(e);
                        }

                    }

                }

                if (entry.getKey().toString().equals("total")) {

                    if (entry.getValue() != null && entry.getValue() != "") {

                        try {
                            this.setTotal(Float.parseFloat(entry.getValue().toString()));
                        } catch (Exception e) {
                            System.err.print(e);
                        }

                    }

                }

                if (entry.getKey().toString().equals("pagado")) {

                    if (entry.getValue() != null && entry.getValue() != "") {

                        try {
                            this.setDineroRecibido(Float.parseFloat(entry.getValue().toString()));
                        } catch (Exception e) {
                            System.err.print(e);
                        }

                    }

                }

                if (entry.getKey().toString().equals("reimpresion")) {

                    if (entry.getValue() != null && entry.getValue() != "") {

                        try {
                            this.setReimpresion(Boolean.parseBoolean(entry.getValue().toString()));
                        } catch (Exception e) {
                            System.err.print(e);
                        }

                    }

                }

            }//while

            System.out.println("Terminado proceso de construccion de Venta a Cliente");

            this.validator();

        } catch (Exception pe) {
            System.out.println(pe);
        }

    }//init

    /**
     * Verifica que se hayan establecedo correctamente todos los valores necesarios para construir el ticket de venta a cliente
     */
    void validator() {

        System.out.println("Iniciando proceso de validacion de venta a cliente");

        int cont = 0;

        if (this.getSucursal() == null) {
            System.err.println("Error : sucursal");
            cont++;
        }

        if (this.getCliente() == null) {
            System.err.println("Error : cliente");
            cont++;
        }

        if (this.getTipoVenta() == null || this.getTipoVenta().equals("")) {
            System.err.println("Error : tipoVenta");
            cont++;
        }

        if (this.getTipoPago() == null || this.getTipoPago().equals("")) {
            System.err.println("Error : tipoPago");
            cont++;
        }

        if (this.productos.size() <= 0) {
            System.err.println("Error : size de productos es <= 0");
            cont++;
        }

        if (this.getSubTotal() == -1) {
            System.err.println("Error : No se definio el subtotal");
            cont++;
        }

        if (this.getTotal() == -1) {
            System.err.println("Error : No se definio el total");
            cont++;
        }

        if (this.getDineroRecibido() == -1) {
            System.err.print("Error : No se definio dineroRecibido (pagado)");
            cont++;
        }

        if (this.getIdVenta() == null) {
            System.err.println("Error : No se definio el id_venta");
            cont++;
        }

        if (this.getEmpleado() == null) {
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

        System.out.println("Iniciando proceso de impresion de venta a cliente");

        //inicializamos el objeto grafico
        this.grafico = (Graphics2D) graphics;

        //le indicamos al objeto grafico donde comienza el lienzo
        this.grafico.translate(pageFormat.getImageableX(), pageFormat.getImageableY());

        /*
         *inicializamos las fuentes, esto se hace en este momento por que para hacerlo se necesita
         *tener el objeto grafico
         */
        this.initFonts(this.grafico);

        this.grafico.setFont(this.bold);

        this.grafico.drawString(LeyendasTicket.getCabeceraTicket(), this.x, this.y);

        this.incrementY(this.height_italic);

        this.grafico.setFont(this.normal);

        this.grafico.drawString("R.F.C. " + LeyendasTicket.getRFC(), this.x, this.y);

        this.incrementY(this.height_normal);

        this.imprimeSinDesborde(this.grafico, this.sucursal.getDescripcion(), " ", this.height_normal);

        this.imprimeSinDesborde(this.grafico, this.sucursal.getDireccion(), " ", this.height_normal);

        this.grafico.drawString("Tel. " + this.sucursal.getTelefono(), this.x, this.y);

        this.incrementY(this.height_normal);

        this.grafico.drawString("========= ", this.x, this.y);

        this.grafico.setFont(this.bold);

        this.grafico.drawString(this.tipoVenta, this.x + 72, this.y);

        this.grafico.setFont(this.normal);

        this.grafico.drawString(" ==========", this.x + 120, this.y);

        this.incrementY(this.height_normal);

        this.grafico.setFont(this.bold);

        this.grafico.drawString(this.idVenta, this.x + 80, this.y);

        this.incrementY(this.height_normal);

        this.grafico.setFont(this.normal);

        this.grafico.drawString("==============================", this.x, this.y);

        this.incrementY(this.height_normal);

        this.grafico.drawString("Fecha : " + this.getFecha() + " " + this.hora, this.x, this.y);

        this.incrementY(this.height_normal);

        this.imprimeSinDesborde(this.grafico, "Cajero : " + this.empleado, this.height_normal);

        if (this.cliente != null) {

            this.imprimeSinDesborde(this.grafico, "Cliente : " + this.cliente.getNombre(), this.height_normal);

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

            //cortamos la descripcion  del producto en caso de que sobrepase le limite permitido
            if (this.productos.get(j).getDescripcion().length() > this.getLimiteDescripcion()) {
                this.productos.get(j).setDescripcion(this.productos.get(j).getDescripcion().substring(0, this.getLimiteDescripcion()));
            }

            this.grafico.drawString(this.productos.get(j).getDescripcion(), this.x, this.y);

            this.grafico.drawString(String.valueOf(this.productos.get(j).getCantidad()), this.x + 75, this.y);

            this.grafico.drawString(this.formatoDinero.format(this.productos.get(j).getPrecio()), this.x + 107, this.y);

            this.grafico.drawString(this.formatoDinero.format(this.productos.get(j).getCantidad() * this.productos.get(j).getPrecio()), this.x + 138, this.y);

            this.incrementY(this.height_normal);

        }//for

        this.grafico.setFont(this.normal);

        this.grafico.drawString("------------------------------------------------------------------------", this.x, this.y);

        this.incrementY(this.height_normal);

        this.grafico.setFont(this.boldSmall);

        this.grafico.drawString("SUBTOTAL:", this.x + 63, this.y);

        this.grafico.drawString(this.formatoDinero.format(this.getSubTotal()), this.x + 130, this.y);

        this.incrementY(this.height_normal);

        if (this.getCliente() != null && this.getCliente().getDescuento() > 0) {

            this.grafico.drawString("DESCUENTO:", this.x + 63, this.y);

            this.grafico.drawString(this.formatoDinero.format((this.getCliente().getDescuento() * this.getSubTotal() / 100)), this.x + 130, this.y);

            this.incrementY(this.height_normal);

        }

        this.grafico.drawString("TOTAL:", this.x + 63, this.y);

        this.grafico.drawString(this.formatoDinero.format(this.getTotal()), this.x + 130, this.y);

        this.incrementY(this.height_normal);

        if (this.getTipoVenta().equals("contado")) {

            //entra si el pago es de contado

            this.grafico.drawString("PAGO:", this.x + 63, this.y);

            //verifica si se pago con un cheque

            if (this.getTipoPago().equals("cheque")) {

                this.grafico.drawString(this.formatoDinero.format(this.getTotal()), this.x + 130, this.y);

                this.incrementY(this.height_normal);

            } else {

                //entra si se pago de contado pero no con cheque

                if (!this.getReimpresion()) {

                    //entra si no es una reimpresion del ticket

                    this.grafico.drawString(this.formatoDinero.format(this.getDineroRecibido()), this.x + 130, this.y);

                    this.incrementY(this.height_normal);

                }

            }


            if (!this.getReimpresion()) {

                //entra si no es una reimpresion

                this.grafico.drawString("CAMBIO:", this.x + 63, this.y);

                this.grafico.drawString(this.formatoDinero.format(this.getDineroRecibido() - this.getTotal()), this.x + 130, this.y);

                this.incrementY(this.height_normal);

            }

        }

        this.grafico.setFont(this.normal);

        this.grafico.drawString("------------------------------------------------------------------------", this.x + 63, this.y);

        this.incrementY(this.height_normal);

        this.imprimeSinDesborde(this.grafico, this.getCantidadEnLetra(this.getTotal()), this.height_normal);

        if (this.getTipoVenta().equals("credito")) {

            //entra si el tipo de venta es a credito

            this.imprimeSinDesborde(this.grafico, LeyendasTicket.getNotaFiscal(), " ", this.height_normal);

            this.grafico.setFont(this.bold);

            this.imprimeSinDesborde(this.grafico, LeyendasTicket.getCabeceraPagare(), " ", this.height_normal);

            this.grafico.setFont(this.normal);

            this.imprimeSinDesborde(this.grafico, LeyendasTicket.getPagare(), " ", this.height_normal);

            this.grafico.drawString("_____________________________________________________________", this.x, this.y);

            this.incrementY(this.height_normal);

            this.grafico.drawString("Firma(s)", this.x + 70, this.y);

            this.incrementY(this.height_normal + 15);

        }

        this.grafico.drawString(LeyendasTicket.getContacto(), this.x, this.y);
        this.incrementY(this.height_normal);

        this.grafico.drawString(LeyendasTicket.getGracias(), this.x + 30, this.y);
        this.incrementY(this.height_normal);


        System.out.println("Terminado proceso de impresion de venta a cliente");

        return PAGE_EXISTS;

    }
}//class

