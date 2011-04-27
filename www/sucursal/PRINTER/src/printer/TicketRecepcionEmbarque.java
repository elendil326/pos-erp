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

/*
 *     D) Recepcion de embarque

{
"productos": [
{
"id_producto": 1,
"procesado": false,
"cantidad_procesada": 0,
"precio_procesada": 0,
"cantidad": 6,
"precio": 10.5,
"descripcion": "papas primeras",
"escala": "kilogramo"
},
{
"id_producto": 2,
"procesado": false,
"cantidad_procesada": 0,
"precio_procesada": 0,
"cantidad": 2,
"precio": 12,
"descripcion": "papa segunda",
"escala": "kilogramo"
}
],
"ticket_surtir": true,
"sucursal": {
"id_sucursal": "1",
"gerente": "102",
"descripcion": "papas supremas 1",
"direccion": "monte radiante #123 col centro, celaya",
"rfc": "alskdfjlasdj8787",
"telefono": "1726376672",
"token": null,
"letras_factura": "c",
"activo": "1",
"fecha_apertura": "2011-01-09 01:38:26",
"saldo_a_favor": "0"
},
"empleado": "Alan gonzalez hernandez"
}
 *
 */
/**
 *
 * @author metalFranckie
 */
public class TicketRecepcionEmbarque extends FormatoTicket implements Printable {

    /**
     * Variable que sirve para controlar las impresiones que facilitan la depuracion
     */
    private boolean debug = false;

    private ArrayList<Producto> productos = new ArrayList<Producto>();
    private Sucursal sucursal = null;
    private String empleado = null;
    private float total = 0;

    TicketRecepcionEmbarque(String json, String hora, String fecha) {
        init(json, hora, fecha);
    }

    void init(String json, String hora, String fecha) {

        if(debug)
        System.out.println("Iniciado proceso de construccion de Recepción Embarque");

        this.setJSON(json);

        this.setHora(hora);

        this.setFecha(fecha);

        JSONParser parser = new JSONParser();

        try {

            Map jsonmap = (Map) parser.parse(this.json);
            Iterator iter = jsonmap.entrySet().iterator();

            if(debug)
            System.out.println("Se iterara a : " + iter.toString().toString());

            //recorremos cada propiedad del JSON

            while (iter.hasNext()) {

                Map.Entry entry = (Map.Entry) iter.next();

                if(debug)
                System.out.println(entry.getKey().toString() + " => " + entry.getValue().toString());

                if (entry.getKey().toString().equals("sucursal")) {
                    if (entry.getValue() != null) {
                        try {
                            this.setSucursal(new Sucursal(entry.getValue().toString()));
                        } catch (Exception e) {
                            System.err.print(e);
                        }
                    }
                }

                if (entry.getKey().toString().equals("productos")) {

                    String json_items = entry.getValue().toString();

                    Object obj = JSONValue.parse(json_items);

                    JSONArray array = (JSONArray) obj;

                    for (int i = 0; i < array.size(); i++) {

                        //metemos cada producto comprado a una coleccion de productos

                        this.productos.add(new Producto(array.get(i).toString()));

                        if(debug)
                        System.out.println(this.productos.get(i).getDescripcion() + ", Cantidad : " + this.productos.get(i).getCantidad() + ", Precio : " + this.productos.get(i).getPrecio());

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

            }

            if(debug)
            System.out.println("Terminado proceso de construccion de Recepción Embarque");

            this.validator();

        } catch (Exception pe) {            
            System.err.println(pe);
        }

    }

    void validator() {

        if(debug)
        System.out.println("Iniciando proceso de validacion de Recepción Embarque");

        int cont = 0;

        if (this.getSucursal() == null) {
            System.err.println("Error : sucursal");
            cont++;
        }

        if (this.productos.size() <= 0) {
            System.err.println("Error : size de productos es <= 0");
            cont++;
        }

        if (this.getEmpleado() == null) {
            System.err.println("Error : No se definio el responsable");
            cont++;
        }

        if(debug)
        System.out.println("Terminado proceso de validacion de recepcion de embarque. se encontraron " + cont + " errores.");

    }

    public int print(Graphics graphics, PageFormat pageFormat, int pageIndex) throws PrinterException {
        if (pageIndex != 0) {
            return NO_SUCH_PAGE;
        }

        if(debug)
        System.out.println("Iniciando proceso de impresion de recepcion de embarque");

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

        this.grafico.drawString("Surtir", this.x + 72, this.y);

        this.grafico.setFont(this.normal);

        this.grafico.drawString("==========", this.x + 120, this.y);

        this.incrementY(this.height_normal);

        this.grafico.setFont(this.bold);

        this.grafico.drawString("Surcursal", this.x + 65, this.y);

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

            this.setTotal(this.getTotal() + this.productos.get(j).getCantidad() * this.productos.get(j).getPrecio());

            this.incrementY(this.height_normal);

        }//for

        this.grafico.setFont(this.normal);

        this.grafico.drawString("------------------------------------------------------------------------", this.x, this.y);

        this.incrementY(this.height_normal);

        this.grafico.setFont(this.boldSmall);

        this.grafico.drawString("TOTAL:", this.x + 63, this.y);

        this.grafico.drawString(this.formatoDinero.format(this.getTotal()), this.x + 130, this.y);

        this.incrementY(this.height_normal);

        this.grafico.setFont(this.normal);

        this.grafico.drawString("------------------------------------------------------------------------", this.x + 63, this.y);

        this.incrementY(this.height_normal);

        this.imprimeSinDesborde(this.grafico, this.getCantidadEnLetra(this.getTotal()), this.height_normal);

        this.grafico.drawString(LeyendasTicket.getContacto(), this.x, this.y);
        this.incrementY(this.height_normal);

        this.grafico.drawString(LeyendasTicket.getGracias(), this.x + 30, this.y);
        this.incrementY(this.height_normal);

        if(debug)
        System.out.println("Terminado proceso de impresion de recepcion de embarque");

        return PAGE_EXISTS;

    }

    /**
     * @return the sucursal
     */
    public Sucursal getSucursal() {
        return sucursal;
    }

    /**
     * @param sucursal the sucursal to set
     */
    public void setSucursal(Sucursal sucursal) {
        this.sucursal = sucursal;
    }

    /**
     * @return the empleado
     */
    public String getEmpleado() {
        return empleado;
    }

    /**
     * @param empleado the empleado to set
     */
    public void setEmpleado(String empleado) {
        this.empleado = empleado;
    }

    /**
     * @return the total
     */
    public float getTotal() {
        return total;
    }

    /**
     * @param total the total to set
     */
    public void setTotal(float total) {
        this.total = total;
    }
}
