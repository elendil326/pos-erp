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

/**
 *
 * @author manuel
 */
public class FormatoAbono implements Printable {

    Font normalTicket = new Font("Tahoma", Font.PLAIN, 9);
    Font normalSmallTicket = new Font("Tahoma", Font.PLAIN, 8);
    Font italicTicket = new Font("Tahoma", Font.ITALIC, 9);
    Font boldTicket = new Font("Tahoma", Font.BOLD, 9);
    Font boldSmallTicket = new Font("Tahoma", Font.BOLD, 9);
    Font smallTicket = new Font("Tahoma", Font.CENTER_BASELINE, 8);
    //configuracion de la factura
    int anchoFactura = 836; //px
    //formato de moneda
    NumberFormat moneda = NumberFormat.getCurrencyInstance();
    Venta venta;

    public FormatoAbono(Venta venta) {
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
        int limite_caracteres = 33;


        ticket.setFont(boldTicket);
        ticket.drawString("JUAN ANTONIO GARCIA TAPIA", 20, y);
        y += h_italicTicket + 5;

        ticket.setFont(normalTicket);

        ticket.drawString("R.F.C. " + this.venta.sucursal.rfc, 0, y);
        y += h_normalTicket;


        y = ServidorImpresion.imprimeSinDesborde(limite_caracteres, this.venta.sucursal.descripcion, ticket, 0, y, h_normalTicket);
        //ticket.drawString(this.venta.sucursal.descripcion, 0, y);
        //y += h_normalTicket;

        y = ServidorImpresion.imprimeSinDesborde(limite_caracteres, this.venta.sucursal.direccion, ticket, 0, y, h_normalTicket);
        //ticket.drawString(this.venta.sucursal.direccion, 0, y);
        //y += h_normalTicket;

        ticket.drawString("Tel. " + this.venta.sucursal.telefono, 0, y);
        y += h_normalTicket;

        ticket.drawString("==============================", 0, y);


        ticket.setFont(boldTicket);
        ticket.drawString("venta  " + this.venta.id_venta, 65, y);
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

            y = ServidorImpresion.imprimeSinDesborde(limite_caracteres, "Cliente : " + this.venta.cliente.nombre, ticket, 0, y, h_normalTicket);
            //ticket.drawString("Cliente : " + this.venta.cliente.nombre, 0, y);
            //y += h_normalTicket;

        }


        y += h_normalTicket;

        ticket.setFont(boldSmallTicket);

        ticket.drawString("ABONO:", 0, y);
        ticket.drawString(this.moneda.format(this.venta.abono), 52, y);
        y += h_normalTicket;

        ticket.drawString("CAMBIO:", 0, y);
        ticket.drawString(this.moneda.format(this.venta.cambio), 52, y);
        y += h_normalTicket;

        ticket.drawString("ABONADO:", 0, y);
        ticket.drawString(this.moneda.format(this.venta.abonado), 52, y);
        y += h_normalTicket;

        ticket.drawString("SALDO:", 0, y);
        ticket.drawString(this.moneda.format(this.venta.saldo), 52, y);


        /********/
        return PAGE_EXISTS;
    }
}
