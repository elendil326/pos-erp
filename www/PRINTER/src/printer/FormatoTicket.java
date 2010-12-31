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
class FormatoTicket implements Printable {

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

    public FormatoTicket(Venta venta){
        this.venta = venta;
    }


    public int print(Graphics graphics, PageFormat pageFormat, int pageIndex) throws PrinterException {


        if (pageIndex != 0) {
            return NO_SUCH_PAGE;
        }
        Graphics2D ticket = (Graphics2D) graphics;
        System.out.println("ImageableX : " + pageFormat.getImageableX() + " ImageableY : " + pageFormat.getImageableY() + " getImageableHeight : " + pageFormat.getImageableHeight() + " getImageableWidth : " + pageFormat.getImageableWidth());
        ticket.translate ( pageFormat.getImageableX () , pageFormat.getImageableY ()) ;
        //ticket.drawString("some text....", 10, 10);

        /*********/


         //objetos para obtener las propiedades de las fuentes
            FontMetrics fm_normalTicket = ticket.getFontMetrics( normalTicket );
            FontMetrics fm_normalSmallTicket = ticket.getFontMetrics( normalSmallTicket );
            FontMetrics fm_smallTicket = ticket.getFontMetrics( smallTicket );
            FontMetrics fm_boldTicket = ticket.getFontMetrics( boldTicket );
            FontMetrics fm_italicTicket = ticket.getFontMetrics( italicTicket );

            //altura de las fuentes
            int h_normalTicket = fm_normalTicket.getHeight();
            int h_normalSmallTicket = fm_normalSmallTicket.getHeight();
            int h_smallTicket = fm_smallTicket.getHeight();
            int h_boldTicket = fm_boldTicket.getHeight();
            int h_italicTicket = fm_italicTicket.getHeight();

            //espacio entre lineas
            int y = 20; //px

            ticket.setFont( boldTicket );
            ticket.drawString("JUAN ANTONIO GARCIA TAPIA", 20, y);
            y += h_italicTicket + 5 ;

            ticket.setFont( normalTicket );

            ticket.drawString("R.F.C. " + this.venta.sucursal.rfc, -50, y);
            y += h_normalTicket;

            ticket.drawString(this.venta.sucursal.descripcion, 0, y);
            y += h_normalTicket;

            ticket.drawString(this.venta.sucursal.direccion, 0, y);
            y += h_normalTicket;

            ticket.drawString("Tel. " + this.venta.sucursal.telefono, 0, y);
            y += h_normalTicket;

            ticket.drawString("========= " , 0, y);
            ticket.setFont(boldTicket );
            ticket.drawString( this.venta.tipoVenta, 72, y);
            ticket.setFont( normalTicket );
            ticket.drawString( " ==========", 120, y);
            y += h_normalTicket;

            ticket.setFont(boldTicket );
            ticket.drawString("venta  " + this.venta.id_venta, 65, y);
            y += h_normalTicket;

            ticket.setFont(normalTicket );
            ticket.drawString( "==============================", 0, y);
            y += h_normalTicket;

            ticket.drawString("Fecha : " + this.venta.hora, 0, y);
            y += h_normalTicket;

            ticket.drawString("Cajero : " + this.venta.responsable, 0, y);
            y += h_normalTicket;

            if( this.venta.cliente != null ){

                ticket.drawString("Cliente : " + this.venta.cliente.nombre, 0, y);
                y += h_normalTicket;

            }

            if( this.venta.tipoVenta.equals("credito") ){

                ticket.drawString("Limite de credito : " + this.moneda.format(this.venta.cliente.limite_credito), 0, y);
                y += h_normalTicket;

                ticket.drawString("Credito restante : " + this.moneda.format(this.venta.cliente.credito_restante), 0, y);
                y += h_normalTicket;

            }

            ticket.drawString("-------------------------------------------------------------------------", 0, y);
            y += h_normalTicket;

            ticket.setFont(smallTicket);
            ticket.drawString("PRODUCTO", 0, y);

            ticket.drawString("CANT", 75, y);

            ticket.drawString("P.U.",107, y);

            ticket.drawString("SUBTOTAL", 138, y);
            y += h_normalTicket;

            ticket.setFont(normalTicket);

            for (int j = 0; j < this.venta.productos.size(); j++) {
                ticket.drawString(this.venta.productos.get(j).descripcion , 0, y);
                ticket.drawString(""+this.venta.productos.get(j).cantidad, 75, y);
                ticket.drawString(this.moneda.format(this.venta.productos.get(j).precioVenta), 107, y);
                ticket.drawString(this.moneda.format(this.venta.productos.get(j).subTotal), 138, y);
                y += h_normalSmallTicket;

            }//for

            ticket.setFont(normalTicket);
            ticket.drawString("------------------------------------------------------------------------", 0, y);
            y += h_normalTicket;

            ticket.setFont(boldSmallTicket);
            ticket.drawString("TOTAL:", 78, y);
            ticket.drawString(this.moneda.format(this.venta.total), 130, y);
            y += h_normalTicket;

            if( venta.tipoVenta.equals("contado") ){

                ticket.drawString("PAGO:", 81, y);
                ticket.drawString(this.moneda.format(this.venta.efectivo), 130, y);
                y += h_normalTicket;
                ticket.drawString("CAMBIO:", 72, y);
                ticket.drawString(this.moneda.format(this.venta.cambio), 130, y);
                y += h_normalTicket;

            }


        /********/

        return PAGE_EXISTS;
    }
}
