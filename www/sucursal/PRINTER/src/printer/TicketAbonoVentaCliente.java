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
import java.util.Iterator;
import java.util.Map;
import org.json.simple.parser.JSONParser;

/**
 *
 * @author manuel
 */
public class TicketAbonoVentaCliente extends FormatoTicket implements Printable {

    /**
     * Variable que sirve para controlar las impresiones que facilitan la depuracion
     */
    private boolean debug = false;
    /**
     * Id de la venta a la cual re sealizara el abono
     */
    private String idVenta = null;

    /**
     * Obtiene el Id de la venta a la cual se realizara el abono
     * @return
     */
    public String getIdVenta() {
        return this.idVenta;
    }

    /**
     * Establece el Id de la venta a la cual se realizara el abono
     * @param _idVenta
     */
    private void setIdVenta(String _idVenta) {
        this.idVenta = _idVenta;
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
    /**
     * Saldo que falta por cubrir de esa venta a credito
     */
    private float saldoPrestamo = -1;

    /**
     * Obtiene el saldo que falta por cubrir de esa venta a credito
     * @return
     */
    public float getSaldoPrestamo() {
        return this.saldoPrestamo;
    }

    /**
     * Establece el saldo que falta por cubrir de esa venta a credito
     * @param _saldoPrestamo
     */
    private void setSaldoPrestamo(float _saldoPrestamo) {
        this.saldoPrestamo = _saldoPrestamo;
    }
    /**
     * Cantidad a abonar
     */
    private float montoAbono = -1;

    /**
     * Obtiene el monto del abono
     * @return
     */
    public float getMontoAbono() {
        return this.montoAbono;
    }

    /**
     * Establece el monto del abono
     * @param _montoAbono
     */
    private void setMontoAbono(float _montoAbono) {
        this.montoAbono = _montoAbono;
    }
    /**
     * Sucursal en la cual se realizo el abono
     */
    private Sucursal sucursal = null;

    /**
     * Obtiene el objeto de tipo sucursal
     * @return
     */
    public Sucursal getSucursal() {
        return this.sucursal;
    }

    /**
     * Establece el objeto tipo sucursal
     * @param _sucursal
     */
    private void setSucursal(Sucursal _sucursal) {
        this.sucursal = _sucursal;
    }

    /**
     * @param json
     * @param hora
     * @param fecha
     */
    TicketAbonoVentaCliente(String json, String hora, String fecha) {

        init(json, hora, fecha);

    }

    /**
     * extrae toda la informacion necesaria del JSON y la almacena en propiedades
     * de esta clase, para que posteriormente se usen al momento de imprimir el ticket
     * @param json
     * @param hora
     * @param fecha
     */
    void init(String json, String hora, String fecha) {

        if (debug) {
            System.out.println("Iniciado proceso de construccion de Abono a Venta a Cliente");
        }

        this.setJSON(json);

        this.setHora(hora);

        this.setFecha(fecha);

        JSONParser parser = new JSONParser();

        try {

            Map jsonmap = (Map) parser.parse(this.json);
            Iterator iter = jsonmap.entrySet().iterator();

            if (debug) {
                System.out.println("Se iterara a : " + iter.toString().toString());
            }

            //recorremos cada propiedad del JSON

            while (iter.hasNext()) {

                Map.Entry entry = (Map.Entry) iter.next();


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

                if (entry.getKey().toString().equals("monto_abono")) {

                    if (entry.getValue() != null && entry.getValue() != "") {

                        try {
                            this.setMontoAbono(Float.parseFloat(entry.getValue().toString()));
                        } catch (Exception e) {
                            System.err.print(e);
                        }

                    }

                }

                if (entry.getKey().toString().equals("saldo_prestamo")) {

                    if (entry.getValue() != null && entry.getValue() != "") {

                        try {
                            this.setSaldoPrestamo(Float.parseFloat(entry.getValue().toString()));
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



            }//while

            if (debug) {
                System.out.println("Terminado proceso de construccion de Abono a Venta a Cliente");
            }

            this.validator();

        } catch (Exception pe) {
                System.err.println(pe);
        }

    }

    void validator() {

        if (debug) {
            System.out.println("Iniciando proceso de validacion de abono a venta a cliente");
        }

        int cont = 0;

        if (this.getSucursal() == null) {
            System.err.println("Error : sucursal");
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

        if (this.getMontoAbono() == -1) {
            System.err.println("Error : No se definio el monto del abono");
            cont++;
        }

        if (this.getSaldoPrestamo() == -1) {
            System.err.println("Error : No se definio el saldo de la deuda");
            cont++;
        }

        if (debug) {
            System.out.println("Terminado proceso de validacion de abono a venta a cliente. se encontraron " + cont + " errores.");
        }

    }

    public int print(Graphics graphics, PageFormat pageFormat, int pageIndex) throws PrinterException {

        if (pageIndex != 0) {
            return NO_SUCH_PAGE;
        }

        if (debug) {
            System.out.println("Iniciando proceso de impresion de abono a venta a cliente");
        }

        //inicializamos el objeto grafico
        this.grafico = (Graphics2D) graphics;

        //le indicamos al objeto grafico donde comienza el lienzo
        this.grafico.translate(pageFormat.getImageableX(), pageFormat.getImageableY());

        /*
         *inicializamos las fuentes, esto se hace en este momento por que para hacerlo se necesita
         *tener el objeto grafico
         */
        this.initFonts(this.grafico);

        //--------------------------------------------------------------


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

        this.grafico.drawString("Abono", this.x + 72, this.y);

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

        this.grafico.drawString("-------------------------------------------------------------------------", this.x, this.y);

        this.incrementY(this.height_normal);

        this.grafico.setFont(this.small);

        this.grafico.drawString("CONCEPTO", this.x, this.y);

        this.grafico.drawString("MONTO", this.x + 138, this.y);

        this.incrementY(this.height_normal);

        this.grafico.setFont(this.normal);

        this.grafico.drawString("Abono a venta", this.x, this.y);

        this.grafico.drawString(this.formatoDinero.format(this.getMontoAbono()), this.x + 138, this.y);

        this.incrementY(this.height_normal);

        this.grafico.setFont(this.normal);

        this.grafico.drawString("------------------------------------------------------------------------", this.x, this.y);

        this.incrementY(this.height_normal);

        this.grafico.setFont(this.boldSmall);

        this.grafico.drawString("SALDO", this.x + 63, this.y);

        if (this.getSaldoPrestamo() == 0) {

            this.grafico.drawString("Pagado", this.x + 130, this.y);

        } else {

            this.grafico.drawString(this.formatoDinero.format(this.getSaldoPrestamo()), this.x + 130, this.y);

        }

        this.incrementY(this.height_normal);

        this.grafico.setFont(this.normal);

        this.grafico.drawString("------------------------------------------------------------------------", this.x, this.y);

        this.incrementY(this.height_normal);

        this.imprimeSinDesborde(this.grafico, this.getCantidadEnLetra(this.getMontoAbono()), this.height_normal);

        this.grafico.drawString(LeyendasTicket.getContacto(), this.x, this.y);

        this.incrementY(this.height_normal);

        this.grafico.drawString(LeyendasTicket.getGracias(), this.x + 30, this.y);

        this.incrementY(this.height_normal);

        if (debug) {
            System.out.println("Terminado proceso de impresion de abono a venta a cliente");
        }

        return PAGE_EXISTS;

    }
}
/*
 *

 */
