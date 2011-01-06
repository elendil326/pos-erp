/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package printer;

import java.awt.Font;
import java.awt.FontMetrics;
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.geom.Rectangle2D;
import java.awt.print.PageFormat;
import java.awt.print.Paper;
import java.awt.print.Printable;
import java.awt.print.PrinterException;
import java.awt.print.PrinterJob;
import java.text.NumberFormat;

/**
 *
 * @author manuel
 */
public class FormatoFactura implements Printable {

    Venta venta;
    Font normalFactura = new Font("Tahoma", Font.PLAIN, 9);
    //formato de moneda
    NumberFormat moneda = NumberFormat.getCurrencyInstance();


    public FormatoFactura(Venta venta) {
        this.venta = venta;
    }

    public int print(Graphics graphics, PageFormat pageFormat, int pageIndex) throws PrinterException {

        if (pageIndex != 0) {
            return NO_SUCH_PAGE;
        }
        Graphics2D factura = (Graphics2D) graphics;

       
        /*Paper paper = new Paper();
        paper.setSize(830, 1080);
        paper.setImageableArea(paper.getImageableX(), paper.getImageableY(), paper.getWidth(), paper.getHeight());*/

//        Paper paper = new Paper();
//        paper.setImageableArea(72, 72, Double.MAX_VALUE, Double.MAX_VALUE);
//        pageFormat.setPaper(paper);

        //pageFormat.setOrientation(1);

        factura.translate(pageFormat.getImageableX(), pageFormat.getImageableY());

 

       /* System.out.println("CONFIGURACION DEL PAPEL");
        System.out.println("paper.getHeight() : " + paper.getHeight());
        System.out.println("paper.getWidth() : " + paper.getWidth());
        System.out.println("paper.getImageablHeight() : " + paper.getImageableHeight());
        System.out.println("paper.getImageablWidth() : " + paper.getImageableWidth());
        System.out.println("paper.getImageableX() : " + paper.getImageableX());
        System.out.println("paper.getImageableY() : " + paper.getImageableY());*/
        System.out.println("");
        System.out.println("CONFIGURACION DEL PAGEFORMAT");
        System.out.println("paper.getHeight() : " + pageFormat.getHeight());
        System.out.println("paper.getWidth() : " + pageFormat.getWidth());
        System.out.println("paper.getImageablHeight() : " + pageFormat.getImageableHeight());
        System.out.println("paper.getImageablWidth() : " + pageFormat.getImageableWidth());
        System.out.println("paper.getImageableX() : " + pageFormat.getImageableX());
        System.out.println("paper.getImageableY() : " + pageFormat.getImageableY());
        System.out.println("");


        /*********/
        //objetos para obtener las propiedades de las fuentes
        FontMetrics fm_normalFactura = factura.getFontMetrics(this.normalFactura);

        //altura de las fuentes
        int h_normalFactura = fm_normalFactura.getHeight();


        Rectangle2D.Double border = new Rectangle2D.Double(0, 0, pageFormat.getImageableWidth(), pageFormat.getImageableHeight());

        factura.draw(border);

        //espacio entre lineas
        int y = h_normalFactura; //px
        int x = 0;

        System.out.println("Inicia la creacion del formato");

        //CABECERA DE LA FACTURA

        factura.setFont(this.normalFactura);

        factura.drawString(y + "012345678901234567890123456789012345678901234567890123456789", x, y);
        y += h_normalFactura;
        factura.drawString(y + "012345678901234567890123456789012345678901234567890123456789", x, y);
        y += h_normalFactura;
        factura.drawString(y + "012345678901234567890123456789012345678901234567890123456789", x, y);
        y += h_normalFactura;
        factura.drawString(y + "012345678901234567890123456789012345678901234567890123456789", x, y);
        y += h_normalFactura;
        factura.drawString(y + "012345678901234567890123456789012345678901234567890123456789", x, y);
        y += h_normalFactura;
        factura.drawString(y + "012345678901234567890123456789012345678901234567890123456789", x, y);
        y += h_normalFactura;
        factura.drawString(y + "012345678901234567890123456789012345678901234567890123456789", x, y);


        factura.drawString(this.venta.fecha, x + 100, y);
        y += h_normalFactura;

        factura.drawString(this.venta.cliente.nombre, x, y);
        y += h_normalFactura + 20;

        factura.drawString(this.venta.cliente.direccion, x, y);
        y += h_normalFactura + 20;

        factura.drawString(this.venta.cliente.ciudad, x, y);

        factura.drawString(this.venta.cliente.rfc, x + 620, y);
        y += h_normalFactura + 30;


        //DESCRIPCION DE LOS PRODUCTOS

        for (int j = 0; j < this.venta.productos.size(); j++) {
            factura.drawString("" + this.venta.productos.get(j).cantidad, x + 10, y);
            factura.drawString(this.venta.productos.get(j).descripcion, x + 75, y);
            factura.drawString(this.moneda.format(this.venta.productos.get(j).precioVenta), x + 550, y);
            factura.drawString(this.moneda.format(this.venta.productos.get(j).subTotal), x + 650, y);
            y += h_normalFactura + 20;
        }//for


        //PIE DE LA FACTURA


        factura.drawString(this.moneda.format(this.venta.subtotal), x + 138, y);
        y += h_normalFactura + 20;
        factura.drawString(this.moneda.format(this.venta.total), x + 138, y);
        y += h_normalFactura + 20;

        //DESCRIPCION CON LETRA

        factura.drawString(new Converter().getStringOfNumber(this.venta.total), x + 10, y);
        y += h_normalFactura + 20;

        System.out.println("Termia la creacion del formato y se regresa : " + PAGE_EXISTS);

        return PAGE_EXISTS;
    }//print
}//class

