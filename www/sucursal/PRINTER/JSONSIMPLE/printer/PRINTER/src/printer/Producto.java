/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package printer;

import java.util.Iterator;
import java.util.Map;
import org.json.simple.parser.JSONParser;

/**
 *
 * @author manuel
 */
public class Producto {

    /*{
        "descripcion":"papas fritas 330",
        "existencias":"335",
        "existenciasMinimas":"100",
        "precioVenta":"9.5",
        "productoID":1,
        "medida":"unidad",
        "precioIntersucursal":"7.5",
        "cantidad":3
     }*/

    String json = null;
    String descripcion = null;
    float precioVenta = 0;
    int id = 0;
    float cantidad = 0;
    float subTotal = 0;

    public Producto( String json){
        
        this.json = json;

        System.out.println("------------- Producto, llego : " + this.json + " ----------------");

        JSONParser parser = new JSONParser();

        try {

            Map jsonmap = (Map) parser.parse( this.json );
            Iterator iter = jsonmap.entrySet().iterator();

          
            while (iter.hasNext()) {
                Map.Entry entry = (Map.Entry) iter.next();

                //System.out.println( entry.getKey() + "=>" + entry.getValue());

                if ( entry.getKey().toString().equals( "descripcion" ) ) {

                    this.descripcion = entry.getValue().toString();
                    System.out.println("this.descripcion: " + this.descripcion  );

                }

                
                if( entry.getKey().toString().equals("precioVenta") ){

                    this.precioVenta = Float.parseFloat( entry.getValue().toString() );
                    System.out.println("this.precioVenta: " + this.precioVenta  );

                }

                if( entry.getKey().toString().equals("productoID") ){

                    this.id = Integer.parseInt( entry.getValue().toString() );
                    System.out.println("this.id: " + this.id  );
                }

                if( entry.getKey().toString().equals("cantidad") ){

                    this.cantidad = Float.parseFloat( entry.getValue().toString() );
                    System.out.println("this.cantidad: " + this.cantidad  );
                }

            }//while

            this.subTotal = this.cantidad * this.precioVenta;

            System.out.println("-------------------FIN PRODUCTO--------------------");
            
        } catch (Exception pe) {
            System.out.println(pe);
        }

    }//Producto

}//class Producto
