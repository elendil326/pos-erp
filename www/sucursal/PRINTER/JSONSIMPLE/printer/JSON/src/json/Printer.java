/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package json;

import java.applet.*;
import java.awt.*;
import java.io.IOException;
import javax.swing.*;

import org.json.simple.*;
import org.json.simple.parser.*;

import java.util.List;
import java.util.Map;
import java.util.*;

/**
 *
 * @author manuel
 */
public class Printer extends Applet {

    String inputFromPage;

    /**
     * Initialization method that will be called after the applet is loaded
     * into the browser.
     */
    public void init() {
    }//init

    public void start() {
        //verificar si es un ticket


        String type = getParameter("type");

        if (type.equals("ticket")) {
            //contruimos el ticket
            buildTicket();
        } else {
            //construimos una factura
            JOptionPane.showMessageDialog(null, "not: " + type);
        }

    }//start

    public void buildTicket() {

        //Contendra todo el JSON
        JSONObject obj = new JSONObject();

        obj.put("TipoVenta", getParameter("tipoVenta"));
        obj.put("cliente", getParameter("cliente"));
        obj.put("factura", getParameter("factura"));
        obj.put("subtotalCompra", getParameter("subtotal"));
        obj.put("totalCompra", getParameter("total"));
        obj.put("efectivo", getParameter("efectivo"));

        //contendra en un arreglo todos los articulos
        List l1 = new LinkedList();

        //obtenemos el numero de articulos
        int numArt = Integer.parseInt(getParameter("numArt"));

        //iteramos cada articulo
        for (int i = 0; i < numArt; i++) {

            //crea un mini JSON con las caracteristicas de cada articulo
            Map m1 = new LinkedHashMap();

            m1.put("Id", getParameter("id" + i));
            m1.put("Descripcion", getParameter("descripcion" + i));
            m1.put("Precio", getParameter("precio" + i));
            m1.put("Cantidad", getParameter("cantidad" + i));
            float subtotal = Float.parseFloat(getParameter("precio" + i)) * Float.parseFloat(getParameter("cantidad" + i));
            m1.put("Subtotal", subtotal);

            //agregamos cada articulo a la lista de los articulos
            l1.add(m1);

        }

        //ya que se agregaron todos los articulos a la lista, agregamos la lista al JSON
        obj.put("Articulos", l1);

        //guarda en un string el JSON
        String jsonText = obj.toString();

        //para fines de prueba lo madamos a imprimir como cadena
        new Impresora().imprimir( jsonText );

        //iterator(jsonText);

    }

    public void iterator(String js) {

        JSONParser parser = new JSONParser();
        // js   = "{\"first\": 123, \"second\": [4, 5, 6], \"third\": 789}";

        try {
            Map json = (Map) parser.parse(js);
            Iterator iter = json.entrySet().iterator();
            System.out.println("==iterate result==");
            while (iter.hasNext()) {
                Map.Entry entry = (Map.Entry) iter.next();

                JOptionPane.showMessageDialog(null, entry.getKey() + "=>" + entry.getValue());

                //PRUEBA
                {

                    if (entry.getKey().toString().equals("Articulos")) {
                        //ahora iteramos los articulos

                        String arrayArticulos = entry.getValue().toString();

                        Object obj = JSONValue.parse(arrayArticulos);
                        JSONArray array = (JSONArray) obj;

                        JOptionPane.showMessageDialog(null,"hay " + array.size() + " articulos");

                       // System.out.println(array.get(1));

                        //por cada propiedad del arreglo de articulos
                        for (int i = 0; i < array.size(); i++) {

                            try {


                                Map jsonArt = (Map) parser.parse(array.get(i).toString());
                                Iterator iterArt = jsonArt.entrySet().iterator();
                                System.out.println("==iterate result==");

                                while (iterArt.hasNext()) {
                                    Map.Entry entryArt = (Map.Entry) iterArt.next();

                                    JOptionPane.showMessageDialog(null, entryArt.getKey() + "=>" + entryArt.getValue());
                                }//while

                            } catch (Exception e) {
                                System.out.println(e);
                            }

                        }//for


                    }//if


                }
                //PRUEBA

            }

        } catch (Exception pe) {
            System.out.println(pe);
        }

    }//iterator

}//Printer

/********************************************************************
 *	La siguiente clase llamada "Impresora", es la encargada de  	*
 *	establecer la fuente con que se va a imprimir, de obtener el	*
 *	trabajo de impresion, la página. En esta clase hay un método	*
 *	llamado imprimir, el cual recibe una cadena y la imprime.		*
 ********************************************************************/
class Impresora {

    Font fuente = new Font("Dialog", Font.PLAIN, 9);
    PrintJob pj;
    Graphics pagina;

    /********************************************************************
     *	A continuación el constructor de la clase. Aquí lo único que	*
     *	hago es tomar un objeto de impresion.							*
     ********************************************************************/
    Impresora() {
        pj = Toolkit.getDefaultToolkit().getPrintJob(new Frame(), "SCAT", null);
    }

    /********************************************************************
     *	A continuación el método "imprimir(String)", el encargado de 	*
     *	colocar en el objeto gráfico la cadena que se le pasa como 		*
     *	parámetro y se imprime.											*
     ********************************************************************/
    public void imprimir(String Cadena) {
        //LO COLOCO EN UN try/catch PORQUE PUEDEN CANCELAR LA IMPRESION
        try {
            pagina = pj.getGraphics();
            pagina.setFont(fuente);
            pagina.setColor(Color.black);

            int y = 20;
            
            //pagina.drawString(Cadena, 10, 20);

            ///////////////////////////////



            JSONParser parser = new JSONParser();
        // js   = "{\"first\": 123, \"second\": [4, 5, 6], \"third\": 789}";

        try {
            Map json = (Map) parser.parse(Cadena);
            Iterator iter = json.entrySet().iterator();
            System.out.println("==iterate result==");
            while (iter.hasNext()) {
                Map.Entry entry = (Map.Entry) iter.next();

                JOptionPane.showMessageDialog(null, entry.getKey() + "=>" + entry.getValue());

                y+=10;
                pagina.drawString(entry.getKey().toString() + ":" + entry.getValue().toString(), 10, y);

                //PRUEBA
                {

                    if (entry.getKey().toString().equals("Articulos")) {
                        //ahora iteramos los articulos

                        String arrayArticulos = entry.getValue().toString();

                        Object obj = JSONValue.parse(arrayArticulos);
                        JSONArray array = (JSONArray) obj;

                        //JOptionPane.showMessageDialog(null,"hay " + array.size() + " articulos");

                       // System.out.println(array.get(1));

                        //por cada propiedad del arreglo de articulos
                        for (int i = 0; i < array.size(); i++) {

                            try {


                                Map jsonArt = (Map) parser.parse(array.get(i).toString());
                                Iterator iterArt = jsonArt.entrySet().iterator();
                                System.out.println("==iterate result==");

                                String ss="";

                                while (iterArt.hasNext()) {
                                    Map.Entry entryArt = (Map.Entry) iterArt.next();

                                    //JOptionPane.showMessageDialog(null, entryArt.getKey() + "=>" + entryArt.getValue());

                                    ss+=entryArt.getKey().toString() + ":" + entryArt.getValue().toString() + " - ";
                                    
                                    //pagina.drawString(entryArt.getKey().toString() + ":" + entryArt.getValue().toString(), 10, y);
                                }//while
                                    y+=20;
                                pagina.drawString(ss, 10, y);

                            } catch (Exception e) {
                                System.out.println(e);
                            }

                        }//for


                    }//if


                }
                //PRUEBA

            }

        } catch (Exception pe) {
            System.out.println(pe);
        }




            ////////////////////////////




            pagina.dispose();
            pj.end();
        } catch (Exception e) {
            System.out.println("LA IMPRESION HA SIDO CANCELADA...");
        }
    }//FIN DEL PROCEDIMIENTO imprimir(String...)
}//FIN DE LA CLASE Impresora

/**
 * Container factory for creating containers for JSON object and JSON array.
 *
 * @see org.json.simple.parser.JSONParser#parse(java.io.Reader, ContainerFactory)
 *
 * @author FangYidong<fangyidong@yahoo.com.cn>
 */
interface ContainerFactory {

    /**
     * @return A Map instance to store JSON object, or null if you want to use org.json.simple.JSONObject.
     */
    Map createObjectContainer();

    /**
     * @return A List instance to store JSON array, or null if you want to use org.json.simple.JSONArray.
     */
    List creatArrayContainer();
}

class KeyFinder implements ContentHandler {

    private Object value;
    private boolean found = false;
    private boolean end = false;
    private String key;
    private String matchKey;

    public void setMatchKey(String matchKey) {
        this.matchKey = matchKey;
    }

    public Object getValue() {
        return value;
    }

    public boolean isEnd() {
        return end;
    }

    public void setFound(boolean found) {
        this.found = found;
    }

    public boolean isFound() {
        return found;
    }

    public void startJSON() throws ParseException, IOException {
        found = false;
        end = false;
    }

    public void endJSON() throws ParseException, IOException {
        end = true;
    }

    public boolean primitive(Object value) throws ParseException, IOException {
        if (key != null) {
            if (key.equals(matchKey)) {
                found = true;
                this.value = value;
                key = null;
                return false;
            }
        }
        return true;
    }

    public boolean startArray() throws ParseException, IOException {
        return true;
    }

    public boolean startObject() throws ParseException, IOException {
        return true;
    }

    public boolean startObjectEntry(String key) throws ParseException, IOException {
        this.key = key;
        return true;
    }

    public boolean endArray() throws ParseException, IOException {
        return false;
    }

    public boolean endObject() throws ParseException, IOException {
        return true;
    }

    public boolean endObjectEntry() throws ParseException, IOException {
        return true;
    }
}
