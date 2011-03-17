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

    /*
     * EJEMPLO DE JSON DE PRODUCTO
     *
     *{
     * "descripcion": "papas primeras",
     * "existencias": "2197",
     * "existencias_procesadas": "271",
     * "tratamiento": "limpia",
     * "precioVenta": "11",
     * "precioVentaSinProcesar": "10",
     * "precio": "11",
     * "id_producto": 1,
     * "escala": "kilogramo",
     * "precioIntersucursal": "10.5",
     * "precioIntersucursalSinProcesar": "9.5",
     * "procesado": "true",
     * "cantidad": 2,
     * "idUnique": "1_7",
     * "descuento": "0"
     * }
     */
    /**
     * Cadena con formato de JSON que contiene la configuracion del producto
     */
    private String json = null;

    /**
     * Obtiene la cadena con formato de JSON qeu contiene la configuracion del producto
     * @return
     */
    private String getJSON() {
        return this.json;
    }

    /**
     * Establece la cadena con formato de JSON qeu contiene la configuracion del producto
     * @param _json
     */
    private void setJSON(String _json) {
        this.json = _json;
    }
    /**
     * Descripcion del producto
     */
    private String descripcion = null;

    /**
     * Obtiene la descripcion del producto
     * @return
     */
    public String getDescripcion() {
        return this.descripcion;
    }

    /**
     * Establece la descripcion del producto
     * @param _descripcion
     */
    public void setDescripcion(String _descripcion) {
        this.descripcion = _descripcion;
    }
    /**
     * Tratamiento del producto "null" | "limpia" | "original"
     */
    private String tratamiento = null;

    /**
     * Obtiene la descripcion del tratamiento
     * @return
     */
    public String getTratamiento() {
        return this.tratamiento;
    }

    /**
     * Establece el tratamiento del producto
     * @param _descripcion
     */
    private void setTratamiento(String _tratamiento) {
        this.tratamiento = _tratamiento;
    }
    /**
     * Precio de venta la publico del producto procesado
     */
    private float precioVenta = 0;

    /**
     * Obtiene el precio de venta del producto
     * @return
     */
    public float getPrecioVenta() {
        return this.precioVenta;
    }

    /**
     * Establece el precio de venta del producto
     * @param _descripcion
     */
    private void setPrecioVenta(float _precioventa) {
        this.precioVenta = _precioventa;
    }
    /**
     * Precio de venta la publico del producto original
     */
    private float precioVentaSinProcesar = 0;

    /**
     * Obtiene el precio de venta del producto sin procesar
     * @return
     */
    public float getPrecioVentaSinProcesar() {
        return this.precioVentaSinProcesar;
    }

    /**
     * Establece el precio de venta del producto sin procesar
     * @param _descripcion
     */
    private void setPrecioVentaSinProcesar(float _precioventasinprocesar) {
        this.precioVentaSinProcesar = _precioventasinprocesar;
    }
    /**
     * Precio de venta intersucursal del producto procesado
     */
    private float precioVentaIntersucursal = 0;

    /**
     * Obtiene el precio de venta intersucursal del producto procesado
     * @return
     */
    public float getPrecioVentaIntersucursal() {
        return this.precioVentaIntersucursal;
    }

    /**
     * Establece el precio de venta intersucursal del producto
     * @param _descripcion
     */
    private void setPrecioVentaIntersucursal(float _precioventaintersucursal) {
        this.precioVentaIntersucursal = _precioventaintersucursal;
    }
    /**
     * Precio de venta intersucursal del producto original
     */
    private float precioVentaIntersucursalSinProcesar = 0;

    /**
     * Obtiene el precio de venta intersucursal del producto sin procesar
     * @return
     */
    public float getPrecioVentIntersucursalSinProcesar() {
        return this.precioVentaIntersucursalSinProcesar;
    }

    /**
     * Establece el precio de venta intersucursal del producto sin procesar
     * @param _descripcion
     */
    private void setPrecioVentaIntersucursalSinProcesar(float _precioventaintersucursalsinprocesar) {
        this.precioVentaIntersucursalSinProcesar = _precioventaintersucursalsinprocesar;
    }
    /**
     * Precio real al cual se vendio el producto
     */
    private float precio = 0;

    /**
     * Obtiene el precio de venta real del producto
     * @return
     */
    public float getPrecio() {
        return this.precioVentaIntersucursalSinProcesar;
    }

    /**
     * Establece el precio de venta real del producto
     * @param _descripcion
     */
    private void setPrecio(float _precio) {
        this.precio = _precio;
    }
    /**
     * bandera que indica si el producto esta procesado o sin procesar.
     * default : false
     */
    private boolean procesado = false;

    /**
     * Obtiene el estado del producto. TRUE si es procesado, de lo contrario FALSE
     * @return
     */
    public boolean getProcesado() {
        return this.procesado;
    }

    /**
     * Establece el estado del producto. TRUE si es procesado, de lo contrario FALSE
     * @param _procesado
     */
    private void setProcesado(boolean _procesado) {
        this.procesado = _procesado;
    }
    /**
     * ID del producto
     */
    private int id = 0;

    /**
     * Obtiene el id del producto
     * @return
     */
    public int getId() {
        return this.id;
    }

    private void setId(int _id) {
        this.id = _id;
    }
    /**
     * Cantidad de producto vendido
     */
    private float cantidad = 0;

    /**
     * Obtenemos la cantidad vendida de este producto
     * @return
     */
    public float getCantidad() {
        return this.cantidad;
    }

    /**
     * Establece la cantidad vendida de este producto
     * @param _cantidad
     */
    private void setCantidad(float _cantidad) {
        this.cantidad = _cantidad;
    }
    /**
     * Cantidad descontada del producto vendido
     */
    private float descuento = 0;

    /**
     * Obtiene la cantidad de producto descontado. Ejemplo : Si se venden 100 productos
     * pero 20 estan en mal estado, esos 20 productos representan el descuento
     * @return
     */
    public float getDescuento() {
        return this.descuento;
    }

    /**
     * Establece la cantidad de descuento de este producto
     * @param _descuento
     */
    private void setDescuento(float _descuento) {
        this.descuento = _descuento;
    }
    /**
     * Subtotal de la venta de ese producto
     */
    private float subTotal = 0;

    /**
     * Obtiene el subtotal de venta de este producto
     * @return
     */
    public float getSubTotal() {
        return this.subTotal;
    }

    /**
     * Establece el subtotal de venta de este producto
     * @param _subTotal
     */
    private void setSubTotal(float _subTotal) {
        this.subTotal = _subTotal;
    }
    /**
     * Escala en la que se mide el producto. Ejemplo : piezas, kilogramos, litros
     */
    private String escala = null;

    /**
     * Obtiene la escala que se maneja en este producto
     * @return
     */
    public String getEscala() {
        return this.escala;
    }

    /**
     * Establece la escala que se maneja en este producto
     * @param _escala
     */
    private void setEscala(String _escala) {
        this.escala = _escala;
    }

    /**
     * Recibe un JSON que contiene la configuracion de un producto de la venta y construye un objeto de tipó Producto
     *
     * @param json
     */
    public Producto(String json) {

        this.init(json);

    }//Producto

    private void init(String json) {
        System.out.println("Iniciado proceso de construccion de Producto");

        this.setJSON(json);

        JSONParser parser = new JSONParser();

        try {

            Map jsonmap = (Map) parser.parse(this.getJSON());
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

                if (entry.getKey().toString().equals("tratamiento")) {

                    try {
                        this.setTratamiento(entry.getValue().toString());
                        System.out.println("tratamiento : " + this.getTratamiento());
                    } catch (Exception e) {
                        System.err.println(e);
                    }
                }


                if (entry.getKey().toString().equals("precioVenta")) {

                    try {
                        this.setPrecioVenta(Float.parseFloat(entry.getValue().toString()));
                        System.out.println("precioVenta : " + this.getPrecioVenta());
                    } catch (Exception e) {
                        System.err.println(e);
                    }
                }

                if (entry.getKey().toString().equals("precioVentaSinProcesar")) {

                    try {
                        this.setPrecioVentaSinProcesar(Float.parseFloat(entry.getValue().toString()));
                        System.out.println("precioVentaSinProcesar : " + this.getPrecioVentaSinProcesar());
                    } catch (Exception e) {
                        System.err.println(e);
                    }
                }

                if (entry.getKey().toString().equals("precio")) {

                    try {
                        this.setPrecio(Float.parseFloat(entry.getValue().toString()));
                        System.out.println("precio : " + this.getPrecio());
                    } catch (Exception e) {
                        System.err.println(e);
                    }
                }

                if (entry.getKey().toString().equals("precioIntersucursal")) {
                    try {
                        this.setPrecioVentaIntersucursal(Float.parseFloat(entry.getValue().toString()));
                        System.out.println("precioVentaIntersucursal : " + this.getPrecioVentaIntersucursal());
                    } catch (Exception e) {
                        System.err.println(e);
                    }
                }

                if (entry.getKey().toString().equals("precioIntersucursalSinProcesar")) {
                    try {
                        this.setPrecioVentaIntersucursalSinProcesar(Float.parseFloat(entry.getValue().toString()));
                        System.out.println("precioVentaIntersucursalSinProcesar : " + this.getPrecioVentaIntersucursal());
                    } catch (Exception e) {
                        System.err.println(e);
                    }
                }

                if (entry.getKey().toString().equals("productoID")) {
                    try {
                        this.setId(Integer.parseInt(entry.getValue().toString()));
                        System.out.println("id : " + this.getId());
                    } catch (Exception e) {
                        System.err.println(e);
                    }
                }

                if (entry.getKey().toString().equals("escala")) {

                    try {
                        this.setEscala(entry.getValue().toString());
                        System.out.println("escala : " + this.getEscala());
                    } catch (Exception e) {
                        System.err.println(e);
                    }
                }

                if (entry.getKey().toString().equals("cantidad")) {

                    try {
                        this.setCantidad(Float.parseFloat(entry.getValue().toString()));
                        System.out.println("cantidad : " + this.getCantidad());
                    } catch (Exception e) {
                        System.err.println(e);
                    }
                }

            }//while

            this.setSubTotal(this.getCantidad() * this.getPrecio());

            System.out.println("Terminado proceso de construccion de Producto");

            //iniciamos al validacion de al construccion de producto
            this.validator();

        } catch (Exception pe) {
            System.out.println(pe);
        }
    }

     /**
     * Verifica que se hayan establecido correctamente todos los valores que describen al producto
     */
    private void validator() {

        System.out.println("Iniciando proceso de validacion de Producto");

        int cont = 0;

        if( this.getDescripcion() != null ){
            System.out.println("descripcion : ok - " + this.getDescripcion());
        }else{
            System.err.println("descripcion : fail - " + this.getDescripcion());
            cont++;
        }


        if( this.getTratamiento() != null ){
            System.out.println("tratamiento : ok - " + this.getTratamiento());
        }else{
            System.err.println("tratamiento : fail - " + this.getTratamiento());
            cont++;
        }


        if( this.getPrecioVenta() > 0 ){
            System.out.println("precioVenta : ok - " + this.getPrecioVenta());
        }else{
            System.err.println("precioVenta : fail - " + this.getPrecioVenta());
            cont++;
        }


        if( this.getPrecioVentaSinProcesar() > 0 ){
            System.out.println("precioVentaSinProcesar : ok - " + this.getPrecioVentaSinProcesar());
        }else{
            System.err.println("precioVentaSinProcesar : fail - " + this.getPrecioVentaSinProcesar());
            cont++;
        }


        if( this.getPrecio() > 0 ){
            System.out.println("precio : ok - " + this.getPrecio());
        }else{
            System.err.println("precio : fail - " + this.getPrecio());
        }


        if( this.getPrecioVentaIntersucursal() > 0 ){
            System.out.println("precioVentaIntersucursal : ok - " + this.getPrecioVentaIntersucursal());
        }else{
            System.err.println("IntersprecioVentaIntersucursal : fail - " + this.getPrecioVentaIntersucursal());
            cont++;
        }

        
        if( this.getId() >= 0 ){
            System.out.println("ID : ok - " + this.getId());
        }else{
            System.err.println("ID : fail - " + this.getId());
            cont++;
        }
        

        if( this.getEscala() != null ){
            System.out.println("escala : ok - " + this.getEscala());
        }else{
            System.err.println("escala : fail - " + this.getEscala());
            cont++;
        }

        if( this.getCantidad() >= 0 ){
            System.out.println("cantidad : ok - " + this.getCantidad());
        }else{
            System.err.println("cantidad : fail - " + this.getCantidad());
            cont++;
        }
        
        System.out.println("Terminado proceso de validacion de Producto. se encontraron " + cont + " errores.");

    }
}//class Producto

