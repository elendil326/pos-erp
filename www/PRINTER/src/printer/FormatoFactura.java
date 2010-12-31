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
public class FormatoFactura implements Printable {

    Venta venta;
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

    public FormatoFactura(Venta venta){
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


        return PAGE_EXISTS;
    }

}//class
