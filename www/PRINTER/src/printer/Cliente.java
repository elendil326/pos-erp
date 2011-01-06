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

public class Cliente {

    String json = null;
    String nombre = null;
    String direccion = null;
    String ciudad = null;
    String rfc = null;
    float limite_credito = 0;
    float credito_restante = 0;
    float descuento = 0;

    public Cliente( String json ){
        
         this.json = json;

        JSONParser parser = new JSONParser();

        try {

            Map jsonmap = (Map) parser.parse( this.json );
            Iterator iter = jsonmap.entrySet().iterator();

            System.out.println("-------------------INICIO Cliente--------------------");

            while (iter.hasNext()) {
                Map.Entry entry = (Map.Entry) iter.next();

                //System.out.println( entry.getKey() + "=>" + entry.getValue());

                if( entry.getKey().toString().equals("nombre") ){

                    this.nombre = entry.getValue().toString();
                    System.out.println("this.nombre: " + this.nombre  );
                }

                if( entry.getKey().toString().equals("rfc") ){

                    this.rfc = entry.getValue().toString();
                    System.out.println("this.rfc: " + this.rfc  );
                }


                if( entry.getKey().toString().equals( "direccion" ) ){

                    this.direccion = entry.getValue().toString();
                    System.out.println("this.direccion: " + this.direccion  );

                }


                if( entry.getKey().toString().equals( "ciudad" ) ){

                    this.ciudad = entry.getValue().toString();
                    System.out.println("this.ciudad: " + this.ciudad  );

                }

                if( entry.getKey().toString().equals( "limite_credito" ) ){

                    this.limite_credito = Float.parseFloat(entry.getValue().toString());
                    System.out.println("limite_credito: " + this.limite_credito  );

                }

                if( entry.getKey().toString().equals( "credito_restante" ) ){

                    this.credito_restante = Float.parseFloat(entry.getValue().toString());
                    System.out.println("credito_restante: " + this.credito_restante  );

                }

                if (entry.getKey().toString().equals("descuento")) {

                    this.descuento = Float.parseFloat(entry.getValue().toString());
                    System.out.println("this.descuento: " + this.descuento);

                }

            }//while

            System.out.println("-------------------FIN Cliente--------------------");

        } catch (Exception pe) {
            System.out.println(pe);
        }
        
    }//Cliente

}//class
