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
     * Obtiene el json en bruto que contiene la configuracion de la sucursal
     * @return
     */
    public String getJSON() {
        return this.json;
    }

    /**
     * Establece el json que contiene la configuracion del ticket
     * @param _json
     */
    public void setJSON(String _json) {
        this.json = _json;
    }
    /**
     * Descripcion de la sucursal
     */
    private String descripcion = null;

    /**
     *
     */
    public String getDescripcion() {
        return this.descripcion;
    }

    /**
     *
     */
    public void setDescripcion(String _descripcion) {
        this.descripcion = _descripcion;
    }
    /**
     * Direccion de la sucursal
     */
    private String direccion = null;

    /**
     *
     */
    public String getDireccion() {
        return this.direccion;
    }

    /**
     *
     */
    public void setDireccion(String _direccion) {
        this.direccion = _direccion;
    }
    /**
     * RFC de la sucursal
     */
    private String rfc = null;

    /**
     *
     */
    public String getRFC() {
        return this.rfc;
    }

    /**
     *
     */
    public void setRFC(String _rfc) {
        this.rfc = _rfc;
    }
    /**
     * Telefono de la sucursal
     */
    private String telefono = null;

    /**
     *
     */
    public String getTelefono() {
        return this.telefono;
    }

    /**
     *
     */
    public void setTelefono(String _telefono) {
        this.telefono = _telefono;
    }

    /**
     * Recibe un JSON que contiene la configuracion de la sucursal y construye un objeto de tipó Sucursal
     *
     * @param json
     */
    public Sucursal(String json) {

        this.init(json);

    }//Sucursal

    private void init(String json) {
        this.json = json;

        System.out.println("Iniciado proceso de construccion de Sucursal");

        JSONParser parser = new JSONParser();

        try {

            Map jsonmap = (Map) parser.parse(this.json);
            Iterator iter = jsonmap.entrySet().iterator();

            while (iter.hasNext()) {
                Map.Entry entry = (Map.Entry) iter.next();

                if (entry.getKey().toString().equals("descripcion")) {

                    try {
                        this.setDescripcion(entry.getValue().toString());
                        System.out.println("descripcion : " + this.getDescripcion());
                    } catch (Exception e) {
                        System.err.println(e);
                    }

                }


                if (entry.getKey().toString().equals("direccion")) {

                    try {
                        this.setDireccion(entry.getValue().toString());
                        System.out.println("direccion : " + this.getDireccion());
                    } catch (Exception e) {
                        System.err.println(e);
                    }

                }

                if (entry.getKey().toString().equals("rfc")) {

                    try {
                        this.setRFC(entry.getValue().toString());
                        System.out.println("rfc : " + this.getRFC());
                    } catch (Exception e) {
                        System.err.println(e);
                    }

                }

                if (entry.getKey().toString().equals("telefono")) {

                    try {
                        this.setTelefono(entry.getValue().toString());
                        System.out.println("telefono : " + this.getTelefono());
                    } catch (Exception e) {
                        System.err.println(e);
                    }

                }

            }//while

            System.out.println("Terminado proceso de construccion de Sucursal");

        } catch (Exception pe) {
            System.out.println(pe);
        }
    }

    private void validator() {

        System.out.println("Iniciando proceso de validacion de sucursal");

        int cont = 0;

        if( this.getDescripcion() != null ){
            System.out.println("descripcion : ok - " + this.getDescripcion());
        }else{
            System.err.println("descripcion : fail");
            cont++;
        }

        if( this.getDireccion() != null ){
            System.out.println("direccion : ok - " + this.getDireccion());
        }else{
            System.err.println("direccion : fail - " + this.getDireccion());
            cont++;
        }

       
        if( this.getRFC() != null ){
            System.out.println("rfc : ok - " + this.getRFC());
        }else{
            System.err.println("rfc : fail - " + this.getRFC());
            cont++;
        }

        if( this.getTelefono() != null ){
            System.out.println("telefono : ok - " + this.getTelefono());
        }else{
            System.err.println("telefono : fail - " + this.getTelefono());
            cont++;
        }

        System.out.println("Terminado proceso de validacion de sucursal. se encontraron " + cont + " errores.");

    }
}//class