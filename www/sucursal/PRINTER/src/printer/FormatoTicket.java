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

        ticket.drawString("========= ", 0, y);
        ticket.setFont(boldTicket);
        ticket.drawString(this.venta.tipoVenta, 72, y);
        ticket.setFont(normalTicket);
        ticket.drawString(" ==========", 120, y);
        y += h_normalTicket;

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
                String [] cadena=this.venta.productos.get(j).descripcion.split(" ");
                String prod=cadena[0];
                for (int k = 1; k < cadena.length; k++) {
                    //javax.swing.JOptionPane.showMessageDialog(null, cadena[k]);

                    
                    //prod+=" "+cadena[k];
                    if((prod.length()+" ".length()+cadena[k].length())<13){
                        prod+=" "+cadena[k];
                        
                    }else{
                        ticket.drawString(prod, 0, y);
                        //ticket.drawString(cadena[k], 0, y);
                    y+=h_normalTicket;
                        //javax.swing.JOptionPane.showMessageDialog(null, prod);
                        prod=cadena[k];
                        //k++;
                    }
                    //javax.swing.JOptionPane.showMessageDialog(null, prod);
/*                    if(prod.length()>13){
                        //producto de una sola palabra mayor a 13 caracteres
                    }else{
                    prod=cadena[k]+cadena[k+1];
                    }*/
                    //javax.swing.JOptionPane.showMessageDialog(null, prod);
                }
                ticket.drawString(prod, 0, y);
                //ticket.drawString(this.venta.productos.get(j).descripcion.substring(0, 13), 0, y);
            } else {
                ticket.drawString(this.venta.productos.get(j).descripcion, 0, y);
            }
            //ticket.drawString(this.venta.productos.get(j).descripcion, 0, y);
//javax.swing.JOptionPane.showMessageDialog(null, this.venta.productos.get(j).descripcion);
            ticket.drawString("" + this.venta.productos.get(j).cantidad, 75, y);
            ticket.drawString(this.moneda.format(this.venta.productos.get(j).precioVenta), 107, y);
            ticket.drawString(this.moneda.format(this.venta.productos.get(j).subTotal), 138, y);
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

        if (venta.tipoVenta.equals("contado")) {

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
            String [] fiscal=this.venta.ficalTicket.split(" ");
                String fistiq=fiscal[0];
                for (int k = 1; k < fiscal.length; k++) {
                    //javax.swing.JOptionPane.showMessageDialog(null, cadena[k]);


                    //prod+=" "+cadena[k];
                    if((fistiq.length()+" ".length()+fiscal[k].length())<limite_caracteres){
                        fistiq+=" "+fiscal[k];

                    }else{
                        ticket.drawString(fistiq, 0, y);
                        //ticket.drawString(cadena[k], 0, y);
                    y+=h_normalTicket;
                        //javax.swing.JOptionPane.showMessageDialog(null, prod);
                        fistiq=fiscal[k];
                        //k++;
                    }
                    //javax.swing.JOptionPane.showMessageDialog(null, prod);
/*                    if(prod.length()>13){
                        //producto de una sola palabra mayor a 13 caracteres
                    }else{
                    prod=cadena[k]+cadena[k+1];
                    }*/
                    //javax.swing.JOptionPane.showMessageDialog(null, prod);
                }
                ticket.drawString(fistiq, 0, y);
            ///////////////
            //y = ServidorImpresion.imprimeSinDesborde(limite_caracteres, this.venta.ficalTicket, ticket, 0, y, h_normalTicket);
            y += h_normalTicket;

            ticket.setFont(boldTicket);
            y = ServidorImpresion.imprimeSinDesborde(limite_caracteres, this.venta.tituloPagare, ticket, 70, y, h_normalTicket);

            ticket.setFont(normalTicket);


            String [] cadena=this.venta.leyendaPagare.split(" ");
                String leypag=cadena[0];
                for (int k = 1; k < cadena.length; k++) {
                    //javax.swing.JOptionPane.showMessageDialog(null, cadena[k]);


                    //prod+=" "+cadena[k];
                    if((leypag.length()+" ".length()+cadena[k].length())<limite_caracteres){
                        leypag+=" "+cadena[k];

                    }else{
                        ticket.drawString(leypag, 0, y);
                        //ticket.drawString(cadena[k], 0, y);
                    y+=h_normalTicket;
                        //javax.swing.JOptionPane.showMessageDialog(null, prod);
                        leypag=cadena[k];
                        //k++;
                    }
                    //javax.swing.JOptionPane.showMessageDialog(null, prod);
/*                    if(prod.length()>13){
                        //producto de una sola palabra mayor a 13 caracteres
                    }else{
                    prod=cadena[k]+cadena[k+1];
                    }*/
                    //javax.swing.JOptionPane.showMessageDialog(null, prod);
                }
                ticket.drawString(leypag, 0, y);



            //y = ServidorImpresion.imprimeSinDesborde(limite_caracteres, this.venta.leyendaPagare, ticket, 0, y, h_normalTicket);
y += h_normalTicket+10;
            ticket.drawString("_____________________________________________________________", 0, y);
            y += h_normalTicket;
            ticket.drawString("Firma(s)", 70, y);
            y += h_normalTicket + 15;

        }

        ticket.drawString(this.venta.sugerencias, 0, y);
        y += h_normalTicket;

        ticket.drawString(this.venta.graciasTicket, 30, y);
        y += h_normalTicket;

        /********/
        return PAGE_EXISTS;
    }
}
