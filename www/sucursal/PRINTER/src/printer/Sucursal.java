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

    /*
     {
     "id_sucursal": "1",
     "gerente": "102",
     "descripcion": "papas supremas 1",
     "direccion": "monte radiante #123 col centro, celaya",
     "rfc": "alskdfjlasdj8787",
     "telefono": "1726376672",
     "token": null,
     "letras_factura": "c",
     "activo": "1",
     "fecha_apertura": "2011-01-09 01:38:26",
     "saldo_a_favor": "0"
     }
     */

    /**
     * JSON que contiene la configuracion de la sucursal
     */
    private String json = null;
    /**
     * Descripcion de la sucursal
     */
    private String descripcion = null;
    /**
     *
     */
    public String getDescripcion(){
        return this.descripcion;
    }
    /**
     *
     */
    public void setDescripcion( String _descripcion ){
        this.descripcion = _descripcion;
    }
    /**
     * Direccion de la sucursal
     */
    private String direccion = null;
    /**
     *
     */
    public String getDireccion(){
        return this.direccion;
    }
    /**
     *
     */
    public void setDireccion( String _direccion ){
        this.direccion = _direccion;
    }
    /**
     * RFC de la sucursal
     */
    private String rfc = null;
    /**
     *
     */
    public String getRFC(){
        return this.rfc;
    }
    /**
     *
     */
    public void setRFC( String _rfc ){
        this.rfc = _rfc;
    }
    /**
     * Telefono de la sucursal
     */
    private String telefono = null;
    /**
     *
     */
    public String getTelefono(){
        return this.telefono;
    }
    /**
     *
     */
    public void setTelefono( String _telefono ){
        this.telefono = _telefono;
    }

    /**
     * Recibe un JSON que contiene la configuracion de la sucursal y construye un objeto de tipó Sucursal
     *
     * @param json
     */
    public Sucursal( String json ){

        this.json = json;

        System.out.println("Iniciado proceso de construccion de Sucursal");

        JSONParser parser = new JSONParser();

        try {

            Map jsonmap = (Map) parser.parse( this.json );
            Iterator iter = jsonmap.entrySet().iterator();

            while (iter.hasNext()) {
                Map.Entry entry = (Map.Entry) iter.next();

                System.out.println( entry.getKey() + " => " + entry.getValue());

                if ( entry.getKey().toString().equals( "descripcion" ) ) {

                    this.setDescripcion(entry.getValue().toString());
                    System.out.println("this.descripcion: " + this.descripcion  );

                }


                if( entry.getKey().toString().equals( "direccion" ) ){

                    this.setDireccion(entry.getValue().toString());
                    System.out.println("this.direccion: " + this.direccion  );

                }

                if( entry.getKey().toString().equals("rfc") ){

                    this.setRFC(entry.getValue().toString());
                    System.out.println("this.rfc: " + this.rfc  );
                }

                if( entry.getKey().toString().equals("telefono") ){

                    this.setTelefono(entry.getValue().toString());
                    System.out.println("this.telefono: " + this.telefono  );
                }

            }//while

            System.out.println("Terminado proceso de construccion de Sucursal");

        } catch (Exception pe) {
            System.out.println(pe);
        }
        
    }//Sucursal

}//class
