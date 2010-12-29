/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package printer;

import java.awt.Color;
import java.awt.Font;
import java.awt.FontMetrics;
import java.awt.Frame;
import java.awt.Graphics;
import java.awt.PrintJob;
import java.awt.Toolkit;
import java.text.NumberFormat;
import java.util.*;


/**
 *
 * @author manuel
 */
class Impresora {

    
    Font normalTicket = new Font("Tahoma", Font.PLAIN, 9);
    Font normalSmallTicket = new Font("Tahoma", Font.PLAIN, 8);
    Font italicTicket = new Font("Tahoma", Font.ITALIC, 9);
    Font boldTicket = new Font("Tahoma", Font.BOLD, 9);
    Font boldSmallTicket = new Font("Tahoma", Font.BOLD, 9);
    Font smallTicket = new Font("Tahoma", Font.CENTER_BASELINE,8);

    
    //configuracion de la factura
    int anchoFactura= 836; //px

    //formato de moneda
    NumberFormat moneda = NumberFormat.getCurrencyInstance();

    PrintJob pj;
    Graphics ticket;


    /********************************************************************
     *	A continuación el constructor de la clase. Aquí lo único que	*
     *	se hace es tomar un objeto de impresion.							*
     ********************************************************************/
    Impresora() {
        pj = Toolkit.getDefaultToolkit().getPrintJob(new Frame(), "SCAT", null);
    }

    /********************************************************************
     *	A continuación el método "imprimir(String)", el encargado de 	*
     *	colocar en el objeto gráfico la cadena que se le pasa como 		*
     *	parámetro y se imprime.											*
     ********************************************************************/
    public void imprimirTicket(Venta venta) {
        // try/catch PORQUE PUEDEN CANCELAR LA IMPRESION
        try {

            ticket = pj.getGraphics();            
            ticket.setColor(Color.black);

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

            //margen izquierdo
            int x = 20; //px

            
            ticket.setFont( boldTicket );
            ticket.drawString("JUAN ANTONIO GARCIA TAPIA", 20, y);
            y += h_italicTicket + 5 ;

            ticket.setFont( normalTicket );

            ticket.drawString("R.F.C. " + venta.sucursal.rfc, 0, y);
            y += h_normalTicket;

            ticket.drawString(venta.sucursal.descripcion, 0, y);
            y += h_normalTicket;
            
            ticket.drawString(venta.sucursal.direccion, 0, y);
            y += h_normalTicket;
            
            ticket.drawString("Tel. " + venta.sucursal.telefono, 0, y);
            y += h_normalTicket;

            ticket.drawString("========= " , 0, y);            
            ticket.setFont(boldTicket );
            ticket.drawString( venta.tipoVenta, 72, y);
            ticket.setFont( normalTicket );
            ticket.drawString( " ==========", 120, y);            
            y += h_normalTicket;

            ticket.setFont(boldTicket );
            ticket.drawString("venta  " + venta.id_venta, 65, y);
            y += h_normalTicket;

            ticket.setFont(normalTicket );
            ticket.drawString( "==============================", 0, y);            
            y += h_normalTicket;

            ticket.drawString("Fecha : " + venta.hora, 0, y);
            y += h_normalTicket;

            ticket.drawString("Cajero : " + venta.responsable, 0, y);
            y += h_normalTicket;

            if( venta.cliente != null ){

                ticket.drawString("Cliente : " + venta.cliente.nombre, 0, y);
                y += h_normalTicket;

            }

            if( venta.tipoVenta.equals("credito") ){

                ticket.drawString("Limite de credito : " + this.moneda.format(venta.cliente.limite_credito), 0, y);
                y += h_normalTicket;

                ticket.drawString("Credito restante : " + this.moneda.format(venta.cliente.credito_restante), 0, y);
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

            for (int j = 0; j < venta.productos.size(); j++) {
                ticket.drawString(venta.productos.get(j).descripcion , 0, y);
                ticket.drawString(""+venta.productos.get(j).cantidad, 75, y);
                ticket.drawString(this.moneda.format(venta.productos.get(j).precioVenta), 107, y);
                ticket.drawString(this.moneda.format(venta.productos.get(j).subTotal), 138, y);
                y += h_normalSmallTicket;

            }//for

            ticket.setFont(normalTicket);
            ticket.drawString("------------------------------------------------------------------------", 0, y);
            y += h_normalTicket;

            ticket.setFont(boldSmallTicket);
            ticket.drawString("TOTAL:", 78, y);
            ticket.drawString(this.moneda.format(venta.total), 130, y);
            y += h_normalTicket;

            if( venta.tipoVenta.equals("contado") ){

                ticket.drawString("PAGO:", 81, y);
                ticket.drawString(this.moneda.format(venta.efectivo), 130, y);
                y += h_normalTicket;
                ticket.drawString("CAMBIO:", 72, y);
                ticket.drawString(this.moneda.format(venta.cambio), 130, y);
                y += h_normalTicket;

            }
            ticket.dispose();
            pj.end();

        } catch (Exception e) {
            System.out.println("LA IMPRESION HA SIDO CANCELADA...");
        }
    }//FIN DEL PROCEDIMIENTO imprimir(String...)

    public void imprimirFactura(Venta venta) {
        
    }

}//FIN DE LA CLASE Impresora
