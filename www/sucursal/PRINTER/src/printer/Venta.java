/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package printer;

import java.util.ArrayList;
import java.util.Iterator;
import java.util.Map;
import org.json.simple.JSONArray;
import org.json.simple.JSONValue;
import org.json.simple.parser.*;

/**
 *
 * @author manuel
 */
public class Venta {

    //contiene el json enviado por el applet en forma de cadena
    String json = null;
    //contendra el tipo de venta
    String tipoVenta = null;
    //contendra el tipo de pago
    String tipo_pago = null;
    //indicara si se requiere factura
    boolean factura = false;
    //indica si se requiere un ticket
    boolean ticket = false;
    //contendra el tipo id del prestamo
    String id_prestamo= null;
    //contendra el concepto de prestamo
    String concepto_prestamo= null;
    //contendra el concepto de venta
    String concepto_venta= null;
    //contendra el saldo de prestamo
    float saldo_prestamo= 0;
    //contendra el saldo de venta
    float saldo_venta= 0;
    //contendra el monto abonado
    float monto_abono= 0;
    //indica si se requiere un ticket de surtir sucursal
    boolean ticket_surtir = false;
    //indica si se requiere un ticket prestamo
    boolean ticket_prestamo = false;
    //indica si se requiere un ticket de abono a prestamo
    boolean abono_prestamo = false;
    //indica si se requiere un ticket de abono a venta
    boolean abono_venta = false;
    //indica si re requiere un ticket de abono
    boolean ticket_abono = false;
    //descuento
    float descuento = 0;
    //subtotal
    float subtotal = 0;
    //total
    float total = 0;
    //efectivo
    float efectivo = 0;
    //cambio
    float cambio = 0;
    //String que contiene todos los productos que compro
    String json_items = null;
    //Coleccion de productos
    ArrayList<Producto> productos = new ArrayList<Producto>();
    //sucursal
    Sucursal sucursal = null;
    Sucursal sucursal_origen = null;
    Sucursal sucursal_destino = null;

    ;
    //cliente
    Cliente cliente = null;
    //hora de la venta
    String hora = null;
    //fecha
    String fecha = null;
    //id de la venta
    String id_venta = null;
    //vendedor
    String responsable = null;
    //abono a venta
    float abono = 0;
    //abonado a venta
    float abonado = 0;
    //saldo
    float saldo = 0;
    //pie de ticket
    static String graciasTicket = "GRACIAS POR SU COMPRA";
    //pagare
    static String tituloPagare = "PAGARE";
    //leyenda
    static String leyendaPagare = "DEBE(MOS) Y PAGARE(MOS) INCONDICIONALMENTE  A LA ORDEN DE JUAN ANTONIO GARCIA TAPIA LA CANTIDAD TOTAL ARRIBA ESPECIFICADA, VALOR DE LAS MERCANCIAS DETALLADAS Y RECIBIDAS A MI(NUESTRA)ENTERA SATISFACCION, SI LAS MERCANCIAS NO SON PAGADAS SEGUN LAS CONDICIONES CONVENIDAS CAUSARAN INTERES MORATORIOS DEL X.X% DIARIO HASTA SU TOTAL LIQUIDACION.";
    //advertencia fiscal
    static String ficalTicket = "Este comprobante no es valido para Fines Fiscales";
    //sugerencias
    static String sugerencias = "QUEJAS Y SUGERENCIAS (461)61 7 20 30";

    //constructor
    public Venta(String json, String hora, String fecha) {

        this.hora = hora;
        this.fecha = fecha;

        this.json = json;

        System.out.println("llego a la venta: " + this.json);

        JSONParser parser = new JSONParser();

        try {

            Map jsonmap = (Map) parser.parse(this.json);
            Iterator iter = jsonmap.entrySet().iterator();

            //System.out.println("iter: " + iter.toString().toString());


            while (iter.hasNext()) {
                Map.Entry entry = (Map.Entry) iter.next();

                System.out.println(entry.getKey() + "=====>" + entry.getValue());

                if (entry.getKey().toString().equals("tipo_venta")) {

                    this.tipoVenta = entry.getValue().toString();
                    System.out.println("this.tipoVenta: " + this.tipoVenta);

                }

                //if(this.tipoVenta.equals("credito")){
                if (entry.getKey().toString().equals("tipo_pago")) {
                    if (entry.getValue() == null) {
                        this.tipo_pago = "";
                    } else {

                        this.tipoVenta = entry.getValue().toString();
                    }

                    System.out.println("this.tipo_pago: " + this.tipo_pago);

                }
                //}

                if (entry.getKey().toString().equals("factura")) {

                    this.factura = entry.getValue().toString().equals("true") ? true : false;
                    System.out.println("this.factura: " + this.factura);

                }

                if (entry.getKey().toString().equals("ticket")) {

                    this.ticket = entry.getValue().toString().equals("true") ? true : false;
                    System.out.println("this.ticket: " + this.ticket);

                }

                if (entry.getKey().toString().equals("subtotal")) {

                    this.subtotal = Float.parseFloat(entry.getValue().toString());
                    System.out.println("this.subtotal: " + this.subtotal);

                }

                if (entry.getKey().toString().equals("total")) {

                    this.total = Float.parseFloat(entry.getValue().toString());
                    System.out.println("this.total: " + this.total);

                }
////
                // if(this.tipoVenta.equals("contado")){
                if (entry.getKey().toString().equals("pagado")) {

                    this.efectivo = Float.parseFloat(entry.getValue().toString());
                    System.out.println("this.efectivo: " + this.efectivo);
                }
                //}
///
                if (entry.getKey().toString().equals("abono")) {

                    this.abono = Float.parseFloat(entry.getValue().toString());
                    System.out.println("this.abonol: " + this.abono);

                }

                if (entry.getKey().toString().equals("abonado")) {

                    this.abonado = Float.parseFloat(entry.getValue().toString());
                    System.out.println("this.abonado: " + this.abonado);

                }

                if (entry.getKey().toString().equals("saldo")) {

                    this.saldo = Float.parseFloat(entry.getValue().toString());
                    System.out.println("this.saldo: " + this.saldo);

                }

                if (entry.getKey().toString().equals("items")) {

                    this.json_items = entry.getValue().toString();
                    System.out.println("this.json_items: " + this.json_items);

                    Object obj = JSONValue.parse(this.json_items);
                    JSONArray array = (JSONArray) obj;

                    for (int i = 0; i < array.size(); i++) {

                        //metemos cada producto comprado a una coleccion de productos
                        this.productos.add(new Producto(array.get(i).toString()));

                    }//for
                }//if
                if (entry.getKey().toString().equals("productos")) {

                    this.json_items = entry.getValue().toString();
                    System.out.println("this.json_items: " + this.json_items);

                    Object obj = JSONValue.parse(this.json_items);
                    JSONArray array = (JSONArray) obj;

                    for (int i = 0; i < array.size(); i++) {

                        //metemos cada producto comprado a una coleccion de productos
                        this.productos.add(new Producto(array.get(i).toString()));

                    }//for
                }

                if (entry.getKey().toString().equals("sucursal")) {

                    if (entry.getValue() != null) {

                        this.sucursal = new Sucursal(entry.getValue().toString());

                    }

                }//if
                if (entry.getKey().toString().equals("sucursal_origen")) {

                    if (entry.getValue() != null) {

                        this.sucursal_origen = new Sucursal(entry.getValue().toString());

                    }

                }
                if (entry.getKey().toString().equals("sucursal_destino")) {

                    if (entry.getValue() != null) {

                        this.sucursal_destino = new Sucursal(entry.getValue().toString());

                    }

                }

                if (entry.getKey().toString().equals("cliente")) {

                    if (entry.getValue() != null) {

                        this.cliente = new Cliente(entry.getValue().toString());

                    }

                }//if

                if (entry.getKey().toString().equals("id_venta")) {

                    this.id_venta = entry.getValue().toString();
                    System.out.println("this.id_venta : " + this.id_venta);
                }//if

                if (entry.getKey().toString().equals("empleado")) {

                    this.responsable = entry.getValue().toString();
                    System.out.println("this.responsable : " + this.responsable);
                }//if

                //abono a prestamo
                if (entry.getKey().toString().equals("abono_prestamo")) {

                    this.abono_prestamo = entry.getValue().toString().equals("true") ? true : false;
                    System.out.println("this.abono_prestamo: " + this.abono_prestamo);

                }
                //abono a venta
                if (entry.getKey().toString().equals("abono_venta")) {

                    this.abono_venta = entry.getValue().toString().equals("true") ? true : false;
                    System.out.println("this.abono_venta: " + this.abono_venta);

                }

                //genera prestamo
                if (entry.getKey().toString().equals("ticket_prestamo")) {

                    this.ticket_prestamo = entry.getValue().toString().equals("true") ? true : false;
                    System.out.println("this.ticket_prestamo: " + this.ticket_prestamo);

                }

                //surtir sucursal
                if (entry.getKey().toString().equals("ticket_surtir")) {

                    this.ticket_surtir = entry.getValue().toString().equals("true") ? true : false;
                    System.out.println("this.ticket_surtir: " + this.ticket_surtir);

                }
            
                if (entry.getKey().toString().equals("id_prestamo")) {

                    this.id_prestamo = entry.getValue().toString();
                    System.out.println("this.responsable : " + this.id_prestamo);
                }
                if (entry.getKey().toString().equals("concepto_prestamo")) {

                    this.concepto_prestamo = entry.getValue().toString();
                    System.out.println("this.responsable : " + this.concepto_prestamo);
                }
                if (entry.getKey().toString().equals("concepto_venta")) {

                    this.concepto_venta = entry.getValue().toString();
                    System.out.println("this.responsable : " + this.concepto_venta);
                }

                if (entry.getKey().toString().equals("saldo_prestamo")) {

                    this.saldo_prestamo = Float.parseFloat(entry.getValue().toString());
                    System.out.println("this.responsable : " + this.saldo_prestamo);
                }
                if (entry.getKey().toString().equals("saldo_venta")) {

                    this.saldo_venta = Float.parseFloat(entry.getValue().toString());
                    System.out.println("this.responsable : " + this.saldo_venta);
                }
                if (entry.getKey().toString().equals("monto_abono")) {

                    this.monto_abono = Float.parseFloat(entry.getValue().toString());
                    System.out.println("this.responsable : " + this.monto_abono);
                }
            //abono a prestamo
                
            }//while

            this.descuento = this.subtotal - this.total;

            if (this.descuento > 0) {
                this.descuento = this.subtotal - this.total;
            }

            System.out.println("this.subtotal : " + this.subtotal);
            System.out.println("this.total : " + this.total);
            System.out.println("this.descuento : " + this.descuento);

            this.cambio = this.efectivo - this.total;

            System.out.println("this.cambio: " + this.cambio);

        } catch (Exception pe) {
            System.out.println(pe);
        }


    }//Venta
}//Class Venta

