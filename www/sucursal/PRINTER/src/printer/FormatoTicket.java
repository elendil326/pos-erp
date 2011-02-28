/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package printer;

import java.awt.Font;
import java.awt.FontMetrics;
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.print.PageFormat;
import java.awt.print.Printable;
import java.awt.print.PrinterException;
import java.text.NumberFormat;
import java.util.*;

/**
 *
 * @author manuel
 */
class FormatoTicket implements Printable {

    Font normalTicket = new Font("Tahoma", Font.PLAIN, 9);
    Font normalSmallTicket = new Font("Tahoma", Font.PLAIN, 8);
    Font italicTicket = new Font("Tahoma", Font.ITALIC, 9);
    Font boldTicket = new Font("Tahoma", Font.BOLD, 9);
    Font boldSmallTicket = new Font("Tahoma", Font.BOLD, 9);
    Font smallTicket = new Font("Tahoma", Font.CENTER_BASELINE, 8);
    int limite_caracteres = 33;
    //formato de moneda
    NumberFormat moneda = NumberFormat.getCurrencyInstance();
    Venta venta;
    float cantidad = 0;
    float preciounitario = 0;
    float costo = 0;
    float subtotal = 0;
    float total = 0;

    public FormatoTicket(Venta venta) {
        this.venta = venta;
    }

    public int print(Graphics graphics, PageFormat pageFormat, int pageIndex) throws PrinterException {

        if (pageIndex != 0) {
            return NO_SUCH_PAGE;
        }
        Graphics2D ticket = (Graphics2D) graphics;
        System.out.println("ImageableX : " + pageFormat.getImageableX() + " ImageableY : " + pageFormat.getImageableY() + " getImageableHeight : " + pageFormat.getImageableHeight() + " getImageableWidth : " + pageFormat.getImageableWidth());
        ticket.translate(pageFormat.getImageableX(), pageFormat.getImageableY());
        //ticket.drawString("some text....", 10, 10);

        /*********/
        //objetos para obtener las propiedades de las fuentes
        FontMetrics fm_normalTicket = ticket.getFontMetrics(normalTicket);
        FontMetrics fm_normalSmallTicket = ticket.getFontMetrics(normalSmallTicket);
        FontMetrics fm_smallTicket = ticket.getFontMetrics(smallTicket);
        FontMetrics fm_boldTicket = ticket.getFontMetrics(boldTicket);
        FontMetrics fm_italicTicket = ticket.getFontMetrics(italicTicket);

        //altura de las fuentes
        int h_normalTicket = fm_normalTicket.getHeight();
        int h_normalSmallTicket = fm_normalSmallTicket.getHeight();
        int h_smallTicket = fm_smallTicket.getHeight();
        int h_boldTicket = fm_boldTicket.getHeight();
        int h_italicTicket = fm_italicTicket.getHeight();

        //espacio entre lineas
        int y = 20; //px



        ticket.setFont(boldTicket);
        ticket.drawString("JUAN ANTONIO GARCIA TAPIA", 20, y);
        y += h_italicTicket + 5;

        ticket.setFont(normalTicket);
        if (this.venta.ticket) {


            ticket.drawString("R.F.C. " + this.venta.sucursal.rfc.toString(), 0, y);
            y += h_normalTicket;
            //javax.swing.JOptionPane.showMessageDialog(null, this.venta.sucursal.rfc);

            y = ServidorImpresion.imprimeSinDesborde(limite_caracteres, this.venta.sucursal.descripcion, ticket, 0, y, h_normalTicket);
            //ticket.drawString(this.venta.sucursal.descripcion, 0, y);
            //y += h_normalTicket;

            y = ServidorImpresion.imprimeSinDesborde(limite_caracteres, this.venta.sucursal.direccion, ticket, 0, y, h_normalTicket);
            //ticket.drawString(this.venta.sucursal.direccion, 0, y);
            //y += h_normalTicket;

            ticket.drawString("Tel. " + this.venta.sucursal.telefono, 0, y);
            y += h_normalTicket;

            ticket.drawString("========= ", 0, y);
            ticket.setFont(boldTicket);
            ticket.drawString(this.venta.tipoVenta, 72, y);
            ticket.setFont(normalTicket);
            ticket.drawString(" ==========", 120, y);
            y += h_normalTicket;
            //javax.swing.JOptionPane.showMessageDialog(null, this.venta.id_venta);
            ticket.setFont(boldTicket);
            ticket.drawString("venta  " + this.venta.id_venta, 65, y);
            y += h_normalTicket;

            ticket.setFont(normalTicket);
            ticket.drawString("==============================", 0, y);
            y += h_normalTicket;

            ticket.drawString("Fecha : " + this.venta.fecha + " " + this.venta.hora, 0, y);
            y += h_normalTicket;

            y = ServidorImpresion.imprimeSinDesborde(limite_caracteres, "Cajero : " + this.venta.responsable, ticket, 0, y, h_normalTicket);
            /*
            String[] cadenaCajero = ("Cajero : " + this.venta.responsable).split(" ");
                String acomodaCadena = cadenaCajero[0];
                for (int k = 1; k < cadenaCajero.length; k++) {
                    if ((acomodaCadena.length() + " ".length() + cadenaCajero[k].length()) < limite_caracteres) {
                        acomodaCadena += " " + cadenaCajero[k];

                    } else {
                        ticket.drawString(acomodaCadena, 0, y);

                        y += h_normalTicket;

                        acomodaCadena = cadenaCajero[k];

                    }
                }
             * 
             */
            //ticket.drawString("Cajero : " + this.venta.responsable, 0, y);
            //y += h_normalTicket;

            if (this.venta.cliente != null) {
                /*
                String[] cadena = ("Cliente : " + this.venta.cliente.nombre).split(" ");
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
                */
                y = ServidorImpresion.imprimeSinDesborde(limite_caracteres, "Cliente : " + this.venta.cliente.nombre, ticket, 0, y, h_normalTicket);

            }

            ticket.drawString("-------------------------------------------------------------------------", 0, y);
            y += h_normalTicket;

            ticket.setFont(smallTicket);
            ticket.drawString("PRODUCTO", 0, y);

            ticket.drawString("CANT", 75, y);

            ticket.drawString("P.U.", 107, y);

            ticket.drawString("SUBTOTAL", 138, y);
            y += h_normalTicket;

            ticket.setFont(normalTicket);


            for (int j = 0; j < this.venta.productos.size(); j++) {
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

                float subtotal = this.venta.productos.get(j).cantidad * this.venta.productos.get(j).precio ;

                ticket.drawString(this.moneda.format( subtotal ), 138, y);
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

                ticket.drawString("PAGO:", 63, y);
                ticket.drawString(this.moneda.format(this.venta.efectivo), 130, y);
                y += h_normalTicket;
                ticket.drawString("CAMBIO:", 63, y);
                ticket.drawString(this.moneda.format(this.venta.cambio), 130, y);
                y += h_normalTicket;

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

        }


        ////abono venta
        if (this.venta.abono_venta) {
            //          this.venta.abono_prestamo=false;

//this.venta.tipoVenta="contado";
            ticket.drawString("R.F.C. " + this.venta.sucursal_origen.rfc.toString(), 0, y);
            y += h_normalTicket;
            //javax.swing.JOptionPane.showMessageDialog(null, this.venta.sucursal.rfc);

            y = ServidorImpresion.imprimeSinDesborde(limite_caracteres, this.venta.sucursal_origen.descripcion, ticket, 0, y, h_normalTicket);
            //ticket.drawString(this.venta.sucursal.descripcion, 0, y);
            //y += h_normalTicket;

            y = ServidorImpresion.imprimeSinDesborde(limite_caracteres, this.venta.sucursal_origen.direccion, ticket, 0, y, h_normalTicket);
            //ticket.drawString(this.venta.sucursal.direccion, 0, y);
            //y += h_normalTicket;

            ticket.drawString("Tel. " + this.venta.sucursal_origen.telefono, 0, y);
            y += h_normalTicket;

            ticket.drawString("========= ", 0, y);
            ticket.setFont(boldTicket);
            ticket.drawString("Abono", 72, y);
            ticket.setFont(normalTicket);
            ticket.drawString(" ==========", 120, y);
            y += h_normalTicket;
            //javax.swing.JOptionPane.showMessageDialog(null, this.venta.id_venta);
            ticket.setFont(boldTicket);
            ticket.drawString("Venta  " + this.venta.id_venta, 65, y);
            y += h_normalTicket;

            ticket.setFont(normalTicket);
            ticket.drawString("==============================", 0, y);
            y += h_normalTicket;

            ticket.drawString("Fecha : " + this.venta.fecha + " " + this.venta.hora, 0, y);
            y += h_normalTicket;

            y = ServidorImpresion.imprimeSinDesborde(limite_caracteres, "Cajero : " + this.venta.responsable, ticket, 0, y, h_normalTicket);
            //ticket.drawString("Cajero : " + this.venta.responsable, 0, y);
            //y += h_normalTicket;

            //if (this.venta.cliente != null) {

            //y = ServidorImpresion.imprimeSinDesborde(limite_caracteres, "", ticket, 0, y, h_normalTicket);
            //ticket.drawString("Cliente : " + this.venta.cliente.nombre, 0, y);
            //y += h_normalTicket;

            //}

            ticket.drawString("-------------------------------------------------------------------------", 0, y);
            y += h_normalTicket;

            ticket.setFont(smallTicket);
            ticket.drawString("CONCEPTO", 0, y);

            //ticket.drawString("CANT", 75, y);

            //ticket.drawString("P.U.", 107, y);

            ticket.drawString("MONTO", 138, y);
            y += h_normalTicket;

            ticket.setFont(normalTicket);
            String conceptoAbonoVenta = "Abono a venta";

            if (conceptoAbonoVenta.length() > 13) {
                String[] cadena = conceptoAbonoVenta.split(" ");
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
                ticket.drawString(conceptoAbonoVenta, 0, y);
            }

            ticket.drawString(this.moneda.format(this.venta.monto_abono), 138, y);
            y += h_normalTicket;
            ticket.setFont(normalTicket);
            ticket.drawString("------------------------------------------------------------------------", 0, y);
            y += h_normalTicket;

            ticket.setFont(boldSmallTicket);

            ticket.drawString("SALDO :", 63, y);
            if (this.venta.saldo_prestamo == 0) {
                ticket.drawString("Pagado", 130, y);
            } else {
                ticket.drawString(this.moneda.format(this.venta.saldo_prestamo), 130, y);
            }
            y += h_normalTicket;

            ticket.setFont(normalTicket);
            ticket.drawString("------------------------------------------------------------------------", 0, y);
            y += h_normalTicket;

            y = ServidorImpresion.imprimeSinDesborde(limite_caracteres, new Converter().getStringOfNumber(this.venta.monto_abono), ticket, 0, y, h_normalTicket);



            ticket.drawString(this.venta.sugerencias, 0, y);
            y += h_normalTicket;

            ticket.drawString(this.venta.graciasTicket, 30, y);
            y += h_normalTicket;

        }
        ////abono prestamo
        if (this.venta.abono_prestamo) {

            this.venta.tipoVenta = "contado";
            ticket.drawString("R.F.C. " + this.venta.sucursal_origen.rfc.toString(), 0, y);
            y += h_normalTicket;
            //javax.swing.JOptionPane.showMessageDialog(null, this.venta.sucursal.rfc);

            y = ServidorImpresion.imprimeSinDesborde(limite_caracteres, this.venta.sucursal_origen.descripcion, ticket, 0, y, h_normalTicket);
            //ticket.drawString(this.venta.sucursal.descripcion, 0, y);
            //y += h_normalTicket;

            y = ServidorImpresion.imprimeSinDesborde(limite_caracteres, this.venta.sucursal_origen.direccion, ticket, 0, y, h_normalTicket);
            //ticket.drawString(this.venta.sucursal.direccion, 0, y);
            //y += h_normalTicket;

            ticket.drawString("Tel. " + this.venta.sucursal_origen.telefono, 0, y);
            y += h_normalTicket;

            ticket.drawString("========= ", 0, y);
            ticket.setFont(boldTicket);
            ticket.drawString("Abono", 72, y);
            ticket.setFont(normalTicket);
            ticket.drawString(" ==========", 120, y);
            y += h_normalTicket;
            //javax.swing.JOptionPane.showMessageDialog(null, this.venta.id_venta);
            ticket.setFont(boldTicket);
            ticket.drawString("Prestamo  " + this.venta.id_prestamo, 65, y);
            y += h_normalTicket;

            ticket.setFont(normalTicket);
            ticket.drawString("==============================", 0, y);
            y += h_normalTicket;

            ticket.drawString("Fecha : " + this.venta.fecha + " " + this.venta.hora, 0, y);
            y += h_normalTicket;

            y = ServidorImpresion.imprimeSinDesborde(limite_caracteres, "Cajero : " + this.venta.responsable, ticket, 0, y, h_normalTicket);
            //ticket.drawString("Cajero : " + this.venta.responsable, 0, y);
            //y += h_normalTicket;

            //if (this.venta.cliente != null) {

            y = ServidorImpresion.imprimeSinDesborde(limite_caracteres, "Cliente : " + this.venta.sucursal_destino.descripcion, ticket, 0, y, h_normalTicket);
            //ticket.drawString("Cliente : " + this.venta.cliente.nombre, 0, y);
            //y += h_normalTicket;

            //}

            ticket.drawString("-------------------------------------------------------------------------", 0, y);
            y += h_normalTicket;

            ticket.setFont(smallTicket);
            ticket.drawString("CONCEPTO", 0, y);

            //ticket.drawString("CANT", 75, y);

            //ticket.drawString("P.U.", 107, y);

            ticket.drawString("MONTO", 138, y);
            y += h_normalTicket;

            ticket.setFont(normalTicket);


            if (this.venta.concepto_prestamo.length() > 13) {
                String[] cadena = this.venta.concepto_prestamo.split(" ");
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
                ticket.drawString(this.venta.concepto_prestamo, 0, y);
            }

            ticket.drawString(this.moneda.format(this.venta.monto_abono), 138, y);
            y += h_normalTicket;
            ticket.setFont(normalTicket);
            ticket.drawString("------------------------------------------------------------------------", 0, y);
            y += h_normalTicket;

            ticket.setFont(boldSmallTicket);

            ticket.drawString("SALDO :", 63, y);
            if (this.venta.saldo_prestamo == 0) {
                ticket.drawString("Pagado", 130, y);
            } else {
                ticket.drawString(this.moneda.format(this.venta.saldo_prestamo), 130, y);
            }
            y += h_normalTicket;

            //ticket.drawString("TOTAL:", 63, y);
            //ticket.drawString(this.moneda.format(this.venta.monto_abono), 130, y);
            //y += h_normalTicket;

            ticket.setFont(normalTicket);
            ticket.drawString("------------------------------------------------------------------------", 0, y);
            y += h_normalTicket;

            y = ServidorImpresion.imprimeSinDesborde(limite_caracteres, new Converter().getStringOfNumber(this.venta.monto_abono), ticket, 0, y, h_normalTicket);



            ticket.drawString(this.venta.sugerencias, 0, y);
            y += h_normalTicket;

            ticket.drawString(this.venta.graciasTicket, 30, y);
            y += h_normalTicket;

        }

        ////generar prestamo
        if (this.venta.ticket_prestamo) {

            this.venta.tipoVenta = "contado";
            ticket.drawString("R.F.C. " + this.venta.sucursal_origen.rfc.toString(), 0, y);
            y += h_normalTicket;
            //javax.swing.JOptionPane.showMessageDialog(null, this.venta.sucursal.rfc);

            y = ServidorImpresion.imprimeSinDesborde(limite_caracteres, this.venta.sucursal_origen.descripcion, ticket, 0, y, h_normalTicket);
            //ticket.drawString(this.venta.sucursal.descripcion, 0, y);
            //y += h_normalTicket;

            y = ServidorImpresion.imprimeSinDesborde(limite_caracteres, this.venta.sucursal_origen.direccion, ticket, 0, y, h_normalTicket);
            //ticket.drawString(this.venta.sucursal.direccion, 0, y);
            //y += h_normalTicket;

            ticket.drawString("Tel. " + this.venta.sucursal_origen.telefono, 0, y);
            y += h_normalTicket;

            ticket.drawString("==============================", 0, y);
            y += h_normalTicket;
            //javax.swing.JOptionPane.showMessageDialog(null, this.venta.id_venta);
            ticket.setFont(boldTicket);
            ticket.drawString("Prestamo  " + this.venta.id_prestamo, 65, y);
            y += h_normalTicket;

            ticket.setFont(normalTicket);
            ticket.drawString("==============================", 0, y);
            y += h_normalTicket;

            ticket.drawString("Fecha : " + this.venta.fecha + " " + this.venta.hora, 0, y);
            y += h_normalTicket;

            y = ServidorImpresion.imprimeSinDesborde(limite_caracteres, "Cajero : " + this.venta.responsable, ticket, 0, y, h_normalTicket);
            //ticket.drawString("Cajero : " + this.venta.responsable, 0, y);
            //y += h_normalTicket;

            //if (this.venta.cliente != null) {

            y = ServidorImpresion.imprimeSinDesborde(limite_caracteres, "Cliente : " + this.venta.sucursal_destino.descripcion, ticket, 0, y, h_normalTicket);
            //ticket.drawString("Cliente : " + this.venta.cliente.nombre, 0, y);
            //y += h_normalTicket;

            //}

            ticket.drawString("-------------------------------------------------------------------------", 0, y);
            y += h_normalTicket;

            ticket.setFont(smallTicket);
            ticket.drawString("CONCEPTO", 0, y);

            //ticket.drawString("CANT", 75, y);

            //ticket.drawString("P.U.", 107, y);

            ticket.drawString("MONTO", 138, y);
            y += h_normalTicket;

            ticket.setFont(normalTicket);


            if (this.venta.concepto_prestamo.length() > 13) {
                String[] cadena = this.venta.concepto_prestamo.split(" ");
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
                ticket.drawString(this.venta.concepto_prestamo, 0, y);
            }

            ticket.drawString(this.moneda.format(this.venta.monto_abono), 138, y);
            y += h_normalTicket;
            ticket.setFont(normalTicket);
            ticket.drawString("------------------------------------------------------------------------", 0, y);
            y += h_normalTicket;

            /*ticket.setFont(boldSmallTicket);

            ticket.drawString("SALDO :", 63, y);
            if(this.venta.saldo_prestamo==0){
            ticket.drawString("Pagado", 130, y);
            }else{
            ticket.drawString(this.moneda.format(this.venta.saldo_prestamo), 130, y);
            }
            y += h_normalTicket;

            //ticket.drawString("TOTAL:", 63, y);
            //ticket.drawString(this.moneda.format(this.venta.monto_abono), 130, y);
            //y += h_normalTicket;

            ticket.setFont(normalTicket);
            ticket.drawString("------------------------------------------------------------------------", 0, y);
            y += h_normalTicket;*/

            y = ServidorImpresion.imprimeSinDesborde(limite_caracteres, new Converter().getStringOfNumber(this.venta.monto_abono), ticket, 0, y, h_normalTicket) + 20;

            ticket.drawString("_____________________________________________________________", 0, y);
            y += h_normalTicket;
            ticket.drawString("Firma", 75, y);
            y += h_normalTicket;

            ticket.drawString(this.venta.sugerencias, 0, y);
            y += h_normalTicket;

            ticket.drawString(this.venta.graciasTicket, 30, y);
            y += h_normalTicket;

        }

        ///surtir sucursal
        if (this.venta.ticket_surtir) {


            ticket.drawString("R.F.C. " + this.venta.sucursal.rfc.toString(), 0, y);
            y += h_normalTicket;
            //javax.swing.JOptionPane.showMessageDialog(null, this.venta.sucursal.rfc);

            y = ServidorImpresion.imprimeSinDesborde(limite_caracteres, this.venta.sucursal.descripcion, ticket, 0, y, h_normalTicket);
            //ticket.drawString(this.venta.sucursal.descripcion, 0, y);
            //y += h_normalTicket;

            y = ServidorImpresion.imprimeSinDesborde(limite_caracteres, this.venta.sucursal.direccion, ticket, 0, y, h_normalTicket);
            //ticket.drawString(this.venta.sucursal.direccion, 0, y);
            //y += h_normalTicket;

            ticket.drawString("Tel. " + this.venta.sucursal.telefono, 0, y);
            y += h_normalTicket;

            ticket.drawString("========= ", 0, y);
            ticket.setFont(boldTicket);
            ticket.drawString("Surtir", 72, y);
            ticket.setFont(normalTicket);
            ticket.drawString(" ==========", 120, y);
            y += h_normalTicket;
            //javax.swing.JOptionPane.showMessageDialog(null, this.venta.id_venta);
            ticket.setFont(boldTicket);
            ticket.drawString("Sucursal", 65, y);
            y += h_normalTicket;

            ticket.setFont(normalTicket);
            ticket.drawString("==============================", 0, y);
            y += h_normalTicket;

            ticket.drawString("Fecha : " + this.venta.fecha + " " + this.venta.hora, 0, y);
            y += h_normalTicket;

            y = ServidorImpresion.imprimeSinDesborde(limite_caracteres, "Cajero : " + this.venta.responsable, ticket, 0, y, h_normalTicket);
            //ticket.drawString("Cajero : " + this.venta.responsable, 0, y);
            //y += h_normalTicket;

            if (this.venta.cliente != null) {
                //   y = ServidorImpresion.imprimeSinDesborde(limite_caracteres, "Cliente : " + this.venta.cliente.nombre, ticket, 0, y, h_normalTicket);
                //ticket.drawString("Cliente : " + this.venta.cliente.nombre, 0, y);
                //y += h_normalTicket;
            }

            ticket.drawString("-------------------------------------------------------------------------", 0, y);
            y += h_normalTicket;

            ticket.setFont(smallTicket);
            ticket.drawString("PRODUCTO", 0, y);

            ticket.drawString("CANT", 75, y);

            ticket.drawString("P.U.", 107, y);

            ticket.drawString("SUBTOTAL", 138, y);
            y += h_normalTicket;

            ticket.setFont(normalTicket);

            cantidad = 0;
            preciounitario = 0;

            float total = 0; //subtotal de toda la venta

            for (int j = 0; j < this.venta.productos.size(); j++) {
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

                total += this.venta.productos.get(j).cantidad * this.venta.productos.get(j).precio;

                ticket.drawString(this.moneda.format( this.venta.productos.get(j).cantidad * this.venta.productos.get(j).precio ), 138, y);
                y += h_normalSmallTicket;

            }//for



            ticket.setFont(normalTicket);
            ticket.drawString("------------------------------------------------------------------------", 0, y);
            y += h_normalTicket;

            ticket.setFont(boldSmallTicket);           

            ticket.drawString("TOTAL:", 63, y);
            ticket.drawString(this.moneda.format(total), 130, y);
            y += h_normalTicket;

            //javax.swing.JOptionPane.showMessageDialog(null, this.venta.tipoVenta);

            ticket.setFont(normalTicket);
            ticket.drawString("------------------------------------------------------------------------", 0, y);
            y += h_normalTicket;

            y = ServidorImpresion.imprimeSinDesborde(limite_caracteres, new Converter().getStringOfNumber(total), ticket, 0, y, h_normalTicket);



            ticket.drawString(this.venta.sugerencias, 0, y);
            y += h_normalTicket;

            ticket.drawString(this.venta.graciasTicket, 30, y);
            y += h_normalTicket;

        }

        /********/
        return PAGE_EXISTS;
    }
}
