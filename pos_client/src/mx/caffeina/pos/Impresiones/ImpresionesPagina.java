package mx.caffeina.pos.Impresiones;

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
import java.awt.Font;
import java.awt.FontMetrics;
import java.awt.Graphics2D;
import java.text.NumberFormat;

import mx.caffeina.pos.*;


class ImpresionesPagina implements Printable{
	
	Graphics2D grafico;

    /**
     * Tahoma Font Plain  9
     */
    public final Font normal = new Font("Tahoma", Font.PLAIN, 9);


    /**
     * Tahoma Font Plain  8
     */
    public final Font normalSmall = new Font("Tahoma", Font.PLAIN, 8);


    /**
     * Tahoma Font Italic  9
     */
    public final Font italic = new Font("Tahoma", Font.ITALIC, 9);


    /**
     * Tahoma Font Plain  9
     */
    public final Font bold = new Font("Tahoma", Font.BOLD, 9);


    /**
     * Tahoma Font Bold  8
     */
    public final Font boldSmall = new Font("Tahoma", Font.BOLD, 8);


    /**
     * Tahoma Font Center Baseline  8
     */
    public final Font small = new Font("Tahoma", Font.CENTER_BASELINE, 8);


    /**
     * Objeto empleado para extraer las dimensiones de una fuente
     */
    private FontMetrics fontMetrics = null;


    /**
     * Alto en pixeles de la fuente "normal"
     */
    public int height_normal = 0;


    /**
     * Alto en pixeles de la fuente "normalSmall"
     */
    public int height_normalSmall = 0;


    /**
     * Alto en pixeles de la fuente "small"
     */
    public int height_small = 0;


    /**
     * Alto en pixeles de la fuente "bold"
     */
    public int height_bold = 0;


    /**
     * Alto en pixeles de la fuente "italic"
     */
    public int height_italic = 0;


    /**
     * Inicializa los valores de las metricas de las fuentes
     *
     * @param g
     */
    public void initFonts(Graphics2D g){

        this.fontMetrics 	= g.getFontMetrics(this.normal);
        this.height_normal 	= this.fontMetrics.getHeight();

        this.fontMetrics 	= g.getFontMetrics(this.normalSmall);
        this.height_normalSmall = this.fontMetrics.getHeight();

        this.fontMetrics 	= g.getFontMetrics(this.small);
        this.height_small 	= this.fontMetrics.getHeight();

        this.fontMetrics 	= g.getFontMetrics(this.bold);
        this.height_bold 	= this.fontMetrics.getHeight();

        this.fontMetrics 	= g.getFontMetrics(this.italic);
        this.height_italic 	= this.fontMetrics.getHeight();
    }

    String to_print;


    ImpresionesPagina(String free_text)
    {
    	to_print = free_text;
    }

	public int print(Graphics graphics, PageFormat pageFormat, int pageIndex) throws PrinterException {

        if (pageIndex != 0) 
        {
            return NO_SUCH_PAGE;
        }
        

        //inicializamos el objeto grafico
        this.grafico = (Graphics2D) graphics;


        //le indicamos al objeto grafico donde comienza el lienzo
        this.grafico.translate( pageFormat.getImageableX(), pageFormat.getImageableY() );


        //inicializamos las fuentes, esto se hace en este momento por que para hacerlo se necesita
        //tener el objeto grafico
        this.initFonts( this.grafico );

		this.grafico.setFont(this.normal);

        String [] lines = to_print.split("\n");

        int y = 0;


        for ( int nl = 0; nl < lines.length; nl++ ) 
        {

			this.grafico.drawString( lines[nl] , 0, y);	
        	y += this.height_normal;       	

        }
	

       

        return PAGE_EXISTS;

    }//print()
}