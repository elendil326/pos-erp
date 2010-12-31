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
public class Sucursal {

    String json = null;
    String descripcion = null;
    String direccion = null;
    String rfc = null;
    String telefono = null;

    public Sucursal( String json ){

        this.json = json;

        JSONParser parser = new JSONParser();

        try {

            Map jsonmap = (Map) parser.parse( this.json );
            Iterator iter = jsonmap.entrySet().iterator();

            System.out.println("-------------------INICIO Sucursal--------------------");

            while (iter.hasNext()) {
                Map.Entry entry = (Map.Entry) iter.next();

                //System.out.println( entry.getKey() + "=>" + entry.getValue());

                if ( entry.getKey().toString().equals( "descripcion" ) ) {

                    this.descripcion = entry.getValue().toString();
                    System.out.println("this.descripcion: " + this.descripcion  );

                }


                if( entry.getKey().toString().equals( "direccion" ) ){

                    this.direccion = entry.getValue().toString();
                    System.out.println("this.direccion: " + this.direccion  );

                }

                if( entry.getKey().toString().equals("rfc") ){

                    this.rfc = entry.getValue().toString();
                    System.out.println("this.rfc: " + this.rfc  );
                }

                if( entry.getKey().toString().equals("telefono") ){

                    this.telefono = entry.getValue().toString();
                    System.out.println("this.telefono: " + this.telefono  );
                }

            }//while

            System.out.println("-------------------FIN Sucursal--------------------");

        } catch (Exception pe) {
            System.out.println(pe);
        }
        
    }//Sucursal

}//class
