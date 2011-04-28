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
public final class TicketPrestamoEfectivoSucursal extends FormatoTicket implements Printable {

    /**
     * Variable que sirve para controlar las impresiones que facilitan la depuracion
     */
    static boolean debug = false;
    /**
     * Id del prestamo de la sucursal origen a la sucursal destino
     */
    private int idPrestamo = -1;

    /**
     * Obtiene el Id del prestamo
     * @return
     */
    public int getIdPrestamo() {
        return idPrestamo;
    }

    /**
     * Establece el Id del prestamo
     * @param idPrestamo
     */
    private void setIdPrestamo(int idPrestamo) {
        this.idPrestamo = idPrestamo;
    }
    /**
     * Nombre del empleado qeu realizo el prestamo
     */
    private String empleado = null;

    /**
     * Obtiene el nombre del emplado qeu realizo el prestamo
     * @return
     */
    public String getEmpleado() {
        return empleado;
    }

    /**
     * Establece el nombre del emplado qeu realizo el prestamo
     * @param empleado
     */
    private void setEmpleado(String empleado) {
        this.empleado = empleado;
    }
    /**
     * Concepto del prestamo
     */
    private String concepto = null;

    /**
     * Obtiene el concepto del prestamo
     * @return
     */
    public String getConcepto() {
        return concepto;
    }

    /**
     * Establece el concepto del prestamo
     * @param concepto
     */
    private void setConcepto(String concepto) {
        this.concepto = concepto;
    }
    /**
     * Saldo del prestamo
     * @return
     */
    private float monto = -1;

    /**
     * Obtiene el monto del prestamo
     * @return
     */
    public float getMonto() {
        return monto;
    }

    /**
     * Establece el monto del prestamo
     * @param monto
     */
    private void setMonto(float monto) {
        this.monto = monto;
    }
    /**
     * Contiene informacion hacerca de la sucursal que esta recibiendo el prestamo
     */
    private Sucursal sucursalDestino = null;

    /**
     * Obtiene informacion ahcerca de la sucursal que esta recibiendo el prestamo
     * @return
     */
    public Sucursal getSucursalDestino() {
        return sucursalDestino;
    }

    /**
     * Establece la informacion hacerca de la sucursal que esta recibiendo el prestamo
     * @param sucursalDestino
     */
    private void setSucursalDestino(Sucursal sucursalDestino) {
        this.sucursalDestino = sucursalDestino;
    }
    /**
     * Contiene informacion hacerca de la sucursal que esta realizando el prestamo
     */
    private Sucursal sucursalOrigen = null;

    /**
     * Establece informacion hacerca de la sucursal que esta realizando el prestamo
     * @return
     */
    public Sucursal getSucursalOrigen() {
        return sucursalOrigen;
    }

    /**
     * Estblece informacion hacerca de la sucursal que esta realizando el prestamo
     * @param sucursalOrigen
     */
    private void setSucursalOrigen(Sucursal sucursalOrigen) {
        this.sucursalOrigen = sucursalOrigen;
    }

    TicketPrestamoEfectivoSucursal(String json, String hora, String fecha) {

        init(json, hora, fecha);

    }

    /**
     * Extrae toda la informacion necesaria del JSON y la almacena en propiedades
     * de esta clase, para que posteriormente se usen al momento de imprimir el ticket
     *
     * @param json
     * @param hora
     * @param fecha
     */
    @Override
    void init(String json, String hora, String fecha) {

        if (debug) {
            System.out.println("Iniciado proceso de construccion de Prestamo de Efectivo a Sucursal");
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


                if (entry.getKey().toString().equals("sucursal_origen")) {

                    if (entry.getValue() != null) {

                        try {
                            this.sucursalOrigen = new Sucursal(entry.getValue().toString());
                        } catch (Exception e) {
                            System.err.print(e);
                        }

                    }

                }//if

                if (entry.getKey().toString().equals("sucursal_destino")) {

                    if (entry.getValue() != null) {

                        try {
                            this.sucursalDestino = new Sucursal(entry.getValue().toString());
                        } catch (Exception e) {
                            System.err.print(e);
                        }

                    }

                }//if

                if (entry.getKey().toString().equals("id_prestamo")) {

                    if (entry.getValue() != null && entry.getValue() != "") {

                        try {
                            this.setIdPrestamo(Integer.parseInt(entry.getValue().toString()));
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

                if (entry.getKey().toString().equals("concepto")) {

                    if (entry.getValue() != null && entry.getValue() != "") {

                        try {
                            this.setConcepto(entry.getValue().toString());
                        } catch (Exception e) {
                            System.err.print(e);
                        }

                    }

                }


                if (entry.getKey().toString().equals("monto")) {

                    if (entry.getValue() != null && entry.getValue() != "") {

                        try {
                            this.setMonto(Float.parseFloat(entry.getValue().toString()));
                        } catch (Exception e) {
                            System.err.print(e);
                        }

                    }

                }



            }//while

            if (debug) {
                System.out.println("Terminado proceso de construccion de Prestamo de Efectivo a Sucursal");
            }

            this.validator();

        } catch (Exception pe) {
            if (debug) {
                System.out.println(pe);
            }
        }

    }

    /**
     * Verifica que se hayan establecedo correctamente todos los valores necesarios para construir el ticket de venta a cliente
     */
    @Override
    void validator() {

        if (debug) {
            System.out.println("Iniciado proceso de validacion de Prestamo de Efectivo a Sucursal");
        }

        int cont = 0;

        if (this.getSucursalOrigen() == null) {
            System.err.println("Error : sucursal origen");
            cont++;
        }

        if (this.getSucursalDestino() == null) {
            System.err.println("Error : sucursal destino");
            cont++;
        }

        if (this.getConcepto() == null || this.getConcepto().equals("")) {
            System.err.println("Error : concepto");
            cont++;
        }

        if (this.getEmpleado() == null || this.getEmpleado().equals("")) {
            System.err.println("Error : empleado");
            cont++;
        }


        if (this.getIdPrestamo() == -1) {
            System.err.println("Error : No se definio el id del prestamo");
            cont++;
        }

        if (this.getMonto() == -1) {
            System.err.println("Error : No se definio el monto");
            cont++;
        }

        if (cont > 0) {
            System.err.println("Termiando proceso de validacion de Prestamo de Efectivo a Sucursal. se encontraron " + cont + " errores.");
        }

    }

    public int print(Graphics graphics, PageFormat pageFormat, int pageIndex) throws PrinterException {

        if (debug) {
            System.out.println("Iniciando proceso de impresion de prestamo de efectivo a sucursal");
        }

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

        this.imprimeSinDesborde(this.grafico, this.sucursalOrigen.getDescripcion(), " ", this.height_normal);

        this.imprimeSinDesborde(this.grafico, this.sucursalOrigen.getDireccion(), " ", this.height_normal);

        this.grafico.drawString("Tel. " + this.sucursalOrigen.getTelefono(), this.x, this.y);

        this.incrementY(this.height_normal);

        this.grafico.drawString("==============================", this.x, this.y);

        this.grafico.setFont(this.bold);

        this.grafico.drawString("Prestamo " + this.getIdPrestamo(), this.x + 65, this.y);

        this.grafico.setFont(this.normal);

        this.grafico.drawString("==============================", this.x + 120, this.y);

        this.incrementY(this.height_normal);

        this.grafico.setFont(this.normal);

        this.grafico.drawString("==============================", this.x, this.y);

        this.incrementY(this.height_normal);

        this.grafico.drawString("Fecha : " + this.getFecha() + " " + this.hora, this.x, this.y);

        this.incrementY(this.height_normal);

        this.imprimeSinDesborde(this.grafico, "Cajero : " + this.empleado, " ", this.height_normal);

        this.imprimeSinDesborde(this.grafico, "Deudor : " + this.getSucursalDestino().getDescripcion(), " ", this.height_normal);

        this.grafico.drawString("-------------------------------------------------------------------------", this.x, this.y);

        this.incrementY(this.height_normal);

        this.grafico.setFont(this.small);

        this.grafico.drawString("CONCEPTO", this.x, this.y);

        this.grafico.drawString("MONTO", this.x + 138, this.y);

        this.incrementY(this.height_normal);

        this.grafico.setFont(this.normal);

        this.imprimeSinDesborde(this.grafico, this.getConcepto(), " ", this.height_normal);

        this.grafico.drawString(this.formatoDinero.format(this.getMonto()), this.x + 138, this.y);

        this.incrementY(this.height_normal);

        this.grafico.drawString("------------------------------------------------------------------------", this.x, this.y);

        this.incrementY(this.height_normal);

        this.grafico.drawString("_____________________________________________________________", this.x, this.y);

        this.incrementY(this.height_normal);

        this.grafico.drawString("Firma(s)", this.x + 70, this.y);

        this.incrementY(this.height_normal + 15);

        this.grafico.drawString(LeyendasTicket.getContacto(), this.x, this.y);
        this.incrementY(this.height_normal);

        this.grafico.drawString(LeyendasTicket.getContacto(), this.x + 30, this.y);
        this.incrementY(this.height_normal);


        if (debug) {
            System.out.println("Terminado proceso de impresion de prestamo de efectivo a sucursal");
        }

        return PAGE_EXISTS;

    }
}
