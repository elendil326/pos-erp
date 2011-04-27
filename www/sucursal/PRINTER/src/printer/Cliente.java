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

    /*
    {
    "id_cliente": "2",
    "rfc": "ALCB770612",
    "razonSocial": "BRENDA ALFARO CARMONA",
    direccion": "MUTUALISMO #345, COL. CENTRO",
    "ciudad": "CELAYA",
    "telefono": "a",
    "e_mail": "",
    "limite_credito": "20",
    "descuento": "2",
    "activo": "1",
    "id_usuario": "101",
    "id_sucursal": "1",
    "fecha_ingreso": "2011-01-12 18:05:59",
    "credito_restante": 19.5
    }
     */
    /**
     * Variable que sirve para controlar las impresiones que facilitan la depuracion
     */
    private boolean debug = false;
    /**
     * JSON que contiene la configuracion del producto
     */
    private String json = null;
    /**
     * Nombre del cliente
     */
    private String razonSocial = null;

    /**
     * Obtiene el razonSocial del cliente
     * @return
     */
    public String getRazonSocial() {
        return this.razonSocial;
    }

    /**
     * Establece el razonSocial del cliente
     * @param _nombre
     */
    private void setRazonSocial(String _nombre) {
        this.razonSocial = _nombre;
    }
    /**
     * Direccion del cliente
     */
    private String direccion = null;

    /**
     * Obtiene la direccion del cliente
     * @return
     */
    public String getDireccion() {
        return this.direccion;
    }

    /**
     * Establece la direccion del cliente
     * @param _direccion
     */
    private void setDireccion(String _direccion) {
        this.direccion = _direccion;
    }
    /**
     * Ciudad del Cliente
     */
    private String ciudad = null;

    /**
     * Obtiene la ciudad del cliente
     * @return
     */
    public String getCiudad() {
        return this.ciudad;
    }

    /**
     * Establece la ciudad del cliente
     * @param _ciudad
     */
    private void setCiudad(String _ciudad) {
        this.ciudad = _ciudad;
    }
    /**
     * RFC del Cliente
     */
    private String rfc = null;

    /**
     * Obtiene el rfc del cliente
     * @return
     */
    public String getRFC() {
        return this.rfc;
    }

    /**
     * establece el rfc del cliente
     * @param _rfc
     */
    private void setRFC(String _rfc) {
        this.rfc = _rfc;
    }
    /**
     * Limite de credito del Cliente
     */
    private float limiteCredito = 0;

    /**
     * Obtiene el limite de credito del cliente
     * @return
     */
    public float getLimiteCredito() {
        return this.limiteCredito;
    }

    /**
     * Establece el limite de credito del cliente
     * @param _limiteCredito
     */
    private void setLimiteCredito(float _limiteCredito) {
        this.limiteCredito = _limiteCredito;
    }
    /**
     * Credito Restante del Cliente
     */
    private float creditoRestante = 0;

    /**
     * Obtiene el credito restante del cliente
     * @return
     */
    public float getCreditoRestante() {
        return this.creditoRestante;
    }

    /**
     * Esatblece el credito restante del cliente
     * @param _creditoRestante
     */
    private void setCreditoRestante(float _creditoRestante) {
        this.creditoRestante = _creditoRestante;
    }
    /**
     * Descuento del Cliente
     */
    private float descuento = 0;

    /**
     * Obtiene el porcentage de descuento de la venta a ese cliente
     * @return
     */
    public float getDescuento() {
        return this.descuento;
    }

    /**
     * Establece el porcentage de descuento de la venta a ese cleinte
     * @param _descuento
     */
    private void setDescuento(float _descuento) {
        this.descuento = _descuento;
    }

    /**
     * Recibe un JSON que contiene la configuracion del cliente y construye un objeto de tipó Cliente
     *
     * @param json
     */
    public Cliente(String json) {

        this.json = json;

        if (debug) {
            System.out.println("Iniciado proceso de construccion de Cliente");
        }

        JSONParser parser = new JSONParser();

        try {

            Map jsonmap = (Map) parser.parse(this.json);
            Iterator iter = jsonmap.entrySet().iterator();

            while (iter.hasNext()) {
                Map.Entry entry = (Map.Entry) iter.next();

                if (entry.getKey().toString().equals("razon_social")) {

                    this.setRazonSocial(entry.getValue().toString());
                    if (debug) {
                        System.out.println("nombre : " + this.getRazonSocial());
                    }

                }

                if (entry.getKey().toString().equals("rfc")) {

                    this.setRFC(entry.getValue().toString());
                    if (debug) {
                        System.out.println("rfc : " + this.getRFC());
                    }
                }


                if (entry.getKey().toString().equals("direccion")) {

                    this.setDireccion(entry.getValue().toString());
                    if (debug) {
                        System.out.println("direccion : " + this.getDireccion());
                    }

                }


                if (entry.getKey().toString().equals("ciudad")) {

                    this.setCiudad(entry.getValue().toString());
                    if (debug) {
                        System.out.println("ciudad : " + this.getCiudad());
                    }

                }

                if (entry.getKey().toString().equals("limite_credito")) {

                    this.setLimiteCredito(Float.parseFloat(entry.getValue().toString()));
                    if (debug) {
                        System.out.println("limite_credito : " + this.getLimiteCredito());
                    }

                }

                if (entry.getKey().toString().equals("credito_restante")) {

                    this.setCreditoRestante(Float.parseFloat(entry.getValue().toString()));
                    if (debug) {
                        System.out.println("credito_restante : " + this.getCreditoRestante());
                    }

                }

                if (entry.getKey().toString().equals("descuento")) {

                    this.setDescuento(Float.parseFloat(entry.getValue().toString()));
                    if (debug) {
                        System.out.println("this.descuento : " + this.getDescuento());
                    }

                }

            }//while

            if (debug) {
                System.out.println("Termiando proceso de construccion de Cliente");
            }

        } catch (Exception pe) {
            System.err.println(pe);
        }

    }//Cliente
}//class

