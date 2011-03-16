/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package printer;

import java.awt.Font;
import java.awt.FontMetrics;
import java.awt.Graphics2D;
import java.text.NumberFormat;

/**
 * Clase que contiene funciones para formatear texto en los diferentes tipos de tickets, 
 * funciones de impresion y manipulacion de cantidades.
 *
 * @author manuel
 */
public class FormatoTicket {
    //--------------------------------------------------------------------//
    //     Propiedades de los graficos                                    //
    //--------------------------------------------------------------------//

    /**
     * Objeto grafico empleado para imprimir en el lienzo
     */
    Graphics2D grafico;
    /**
     * Indica al objeto grafico donde comenzara a imprimir respecto a la parte izquierda del ticket.
     * default : 0
     */
    public int x = 0;

    /**
     * Establece la posicion en x donde se comenzara a imprimir
     */
    public void setX(int x) {
        this.x = x;
    }
    /**
     * Indica al objeto grafico donde comenzara a imprimir respecto a la parte superior del ticket.
     * default : 20
     */
    public int y = 20;

    /**
     * Recibe una cantidad de pixeles que se le sumaran a la posicion actual de "y" para que la
     * proxima impresion comience en "y" + incremento
     */
    public void incrementY(int incremento) {
        this.y += incremento;
    }

    /**
     * Funcion que imprime una cadena cuidando de que no se pierda
     * informacion al momento de la impresion en el ticket.
     * (puede llegar a cortar las palabras)
     *     
     * @param cadena Cadena a imprimir.
     * @param interlineado Espacio que se tomara entre lineas (pixeles)
     * @return
     */
    public void imprimeSinDesborde(String cadena, int interlineado) {

        while (cadena.length() > this.limiteCaracteres) {

            this.grafico.drawString(cadena.substring(0, this.limiteCaracteres), x, y);

            cadena = cadena.substring(this.limiteCaracteres, cadena.length());

            this.incrementY(this.y + interlineado);

        }

        this.grafico.drawString(cadena, x, y);

        this.incrementY(this.y + interlineado);

    }

    /**
     * Funcion que imprime una cadena cuidando de que no se pierda
     * informacion al momento de la impresion en el ticket, separando las cadenas de acuerdo
     * a un separador especificado, cuidando de que no se corten las palabras y ademas que no exceda
     * el limite de caracteres permitidos.
     *     
     * @param cadena Cadena a imprimir.
     * @param interlineado Espacio que se tomara entre lineas (pixeles)
     * @return
     */
    public void imprimeSinDesborde(String _cadena, String separador, int interlineado) {

        String[] texto = _cadena.split(separador);

        String cadena = texto[0];

        for (int k = 1; k < texto.length; k++) {

            if ((cadena.length() + separador.length() + texto[k].length()) < this.getLimiteCaracteres()) {

                cadena += " " + texto[k];

            } else {

                this.grafico.drawString(cadena, this.x, this.y);

                cadena = texto[k];

                this.incrementY(this.y + interlineado);

            }

            this.grafico.drawString(cadena, this.x, this.y);

            this.incrementY(this.y + interlineado);

        }
    }
    /**
     * Numero maximo de caracteres a imprimir por renglon
     * default : 33
     */
    private int limiteCaracteres = 33;

    /**
     * Establece el limite maximo de caracteres a imprimir por renglon.
     */
    public void setLimiteCaracteres(int num) {
        this.limiteCaracteres = num;
    }

    /**
     * Obtiene el limite maximo de caracteres a imprimir por renglon.
     * @return
     */
    public int getLimiteCaracteres() {
        return this.limiteCaracteres;
    }
    /**
     * Numero maximo de caracteres que pueden describir un producto
     */
    private int limiteDescripcion = 13;

    /**
     * Establece el limite maximo de caracteres a imprimir para describir un producto.
     */
    public void setLimiteDescripcion(int num) {
        this.limiteDescripcion = num;
    }

    /**
     * Obtiene el limite maximo de caracteres a imprimir para describir un producto.
     * @return
     */
    public int getLimiteDescripcion() {
        return this.limiteDescripcion;
    }
    //--------------------------------------------------------------------//
    //     Propiedades de las fuentes                                     //
    //--------------------------------------------------------------------//
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
    public void initFonts(Graphics2D g) {

        this.fontMetrics = g.getFontMetrics(this.normal);
        this.height_normal = this.fontMetrics.getHeight();

        this.fontMetrics = g.getFontMetrics(this.normalSmall);
        this.height_normalSmall = this.fontMetrics.getHeight();

        this.fontMetrics = g.getFontMetrics(this.small);
        this.height_small = this.fontMetrics.getHeight();

        this.fontMetrics = g.getFontMetrics(this.bold);
        this.height_bold = this.fontMetrics.getHeight();

        this.fontMetrics = g.getFontMetrics(this.italic);
        this.height_italic = this.fontMetrics.getHeight();
    }
    //--------------------------------------------------------------------//
    //     Propiedades de la moneda                                       //
    //--------------------------------------------------------------------//
    /**
     * Tipo de moneda usada para los tickets, ejemplo : PESOS, DOLARES, ETC..
     * default : PESOS
     */
    private String tipoMoneda = "PESOS";
    /**
     * Abreviacion de la moneda
     * default : MN
     */
    private String abreviacionMoneda = "MN";
    /**
     * Objeto empleado para formatear los numeros a la moneda local.
     */
    NumberFormat formatoDinero = NumberFormat.getCurrencyInstance();

    /**
     * Funcion que establece el tipo de tipoMoneda que usara
     *
     * @param tipoMoneda Tipo de moenda que se empleara en el ticket, ejemplo : PESOS, DOLARES, ETC..
     * @param abreviacion abreviacion de la tipoMoneda , ejemplo : MN
     */
    public void setTipoMoneda(String tipoMoneda, String abreviacion) {
        this.tipoMoneda = tipoMoneda;
        this.abreviacionMoneda = abreviacion;
    }

    //-----------------------------------------------------------------------//
    //     Metodos para transformar cantidades a su descripcion en letras    //
    //-----------------------------------------------------------------------//
    /**
     * Regresa la cantidad en letra de una cantidad.
     *
     * @param num
     * @return
     */
    public String getCantidadEnLetra(Integer num) {
        return transformaCantidadEnLetra(num);
    }

    /**
     * Regresa la cantidad en letra de una cantidad con formato de centavos/100MN.
     * @param $num
     * @return
     */
    public String getCantidadEnLetra(float num) {

        int _counter = (int) num;

        //Almaceno la parte decimal
        float resto = num - _counter;

        //Redondeo y convierto a entero puedo tener problemas
        int fraccion = Math.round(resto * 100);

        return transformaCantidadEnLetra(_counter) + " " + this.tipoMoneda + " " + fraccion + "/100 " + this.abreviacionMoneda;

    }

    /**
     * Metodo recursivo encargadode recibir una cantidad numerica y regresar su
     * descripcion en letra.
     *
     * @param _counter
     * @return
     */
    private String transformaCantidadEnLetra(Integer _counter) {

        switch (_counter) {
            case 0:
                return "CERO";
            case 1:
                return "UN"; //UNO
            case 2:
                return "DOS";
            case 3:
                return "TRES";
            case 4:
                return "CUATRO";
            case 5:
                return "CINCO";
            case 6:
                return "SEIS";
            case 7:
                return "SIETE";
            case 8:
                return "OCHO";
            case 9:
                return "NUEVE";
            case 10:
                return "DIEZ";
            case 11:
                return "ONCE";
            case 12:
                return "DOCE";
            case 13:
                return "TRECE";
            case 14:
                return "CATORCE";
            case 15:
                return "QUINCE";
            case 20:
                return "VEINTE";
            case 30:
                return "TREINTA";
            case 40:
                return "CUARENTA";
            case 50:
                return "CINCUENTA";
            case 60:
                return "SESENTA";
            case 70:
                return "SETENTA";
            case 80:
                return "OCHENTA";
            case 90:
                return "NOVENTA";
            case 100:
                return "CIEN";

            case 200:
                return "DOSCIENTOS";
            case 300:
                return "TRESCIENTOS";
            case 400:
                return "CUATROCIENTOS";
            case 500:
                return "QUINIENTOS";
            case 600:
                return "SEISCIENTOS";
            case 700:
                return "SETECIENTOS";
            case 800:
                return "OCHOCIENTOS";
            case 900:
                return "NOVECIENTOS";

            case 1000:
                return "MIL";

            case 1000000:
                return "UN MILLON";
            case 2000000:
                return "DOS MILLONES";
        }

        //El numero maximo que manejara el tiquet es de 2 millones, pasando esta cantidad, solo regresara DOS MILLONES
        if (_counter > 2000000) {
            return "DOS MILLONES";
        }

        if (_counter < 20) {
//System.out.println(">15");
            return "DIECI" + transformaCantidadEnLetra(_counter - 10);
        }
        if (_counter < 30) {
//System.out.println(">20");
            return "VEINTI" + transformaCantidadEnLetra(_counter - 20);
        }
        if (_counter < 100) {
//System.out.println("<100");
            return transformaCantidadEnLetra((int) (_counter / 10) * 10) + " Y " + transformaCantidadEnLetra(_counter % 10);
        }
        if (_counter < 200) {
//System.out.println("<200");
            return "CIENTO " + transformaCantidadEnLetra(_counter - 100);
        }
        if (_counter < 1000) {
//System.out.println("<1000");
            return transformaCantidadEnLetra((int) (_counter / 100) * 100) + " " + transformaCantidadEnLetra(_counter % 100);
        }
        if (_counter < 2000) {
//System.out.println("<2000");
            return "MIL " + transformaCantidadEnLetra(_counter % 1000);
        }
        if (_counter < 1000000) {
            String var = "";
//System.out.println("<1000000");
            var = transformaCantidadEnLetra((int) (_counter / 1000)) + " MIL";
            if (_counter % 1000 != 0) {
//System.out.println(var);
                var += " " + transformaCantidadEnLetra(_counter % 1000);
            }
            return var;
        }
        if (_counter < 2000000) {
            return "UN MILLON " + transformaCantidadEnLetra(_counter % 1000000);
        }
        return "";
    }
    //--------------------------------------------------------------------//
    //     Propiedades Generales                                          //
    //--------------------------------------------------------------------//
    /**
     * Descripcion del JSON en bruto que llego.
     */
    public String json = null;
    /**
     * Hora que saldra impresa en el ticket.
     */
    public String hora = null;
    /**
     * Fecha que saldra impresa en el ticket.
     */
    public String fecha = null;
    /**
     * Impresora con la cual se imprimira el documento
     */
    public Impresora impresora;
    /**
     * Leyendas que se manejaran en el ticket
     */
    public LeyendasTicket leyendas;
}
