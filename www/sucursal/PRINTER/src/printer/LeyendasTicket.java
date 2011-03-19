/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package printer;

import java.util.Iterator;
import java.util.Map;
import org.json.simple.parser.JSONParser;

/**
 * Clase que provee de los medios necesarios para llevar el control de las leyendas de los tickets
 *
 * @author manuel
 */
public class LeyendasTicket {

    /**
     * Json que contiene la informacion acerca de las leyendas
     */
    private String json = null;
    /**
     * Establece una cadena en formato de JSON que contiene informacion acerca de las leyendas
     * @param json
     */
    public void setJson(String json) {
        this.json = json;
    }
    /**
     * Cabecera del ticket
     */
    private static String cabeceraTicket = null;

    /**
     * Obtiene el valor de
     */
    public static String getCabeceraTicket() {
        return cabeceraTicket;
    }

    /**
     * Establece el valor de la cabecera del ticket
     * @param cabecera
     */
    public static void setCabeceraTicket(String cabecera) {
        cabeceraTicket = cabecera;
    }
    /**
     * RFC de la empresa
     */
    private static String rfc = null;

    /**
     * Obtiene el valor del RFC de la empresa
     * @return
     */
    public static String getRFC() {
        return rfc;
    }

    /**
     * Establece el valor del RFC de la empresa
     * @param rfc
     */
    public static void setRFC(String _rfc) {
        rfc = _rfc;
    }
    /**
     * Nombre de la empresa
     */
    private static String nombreEmpresa = null;

    /**
     * Obtiene el nombre de la empresa
     * @return
     */
    public static String getNombreEmpresa() {
        return nombreEmpresa;
    }

    /**
     * Establece el nombre de la empresa
     * @param nombre
     */
    public static void setNombreEmpresa(String nombre) {
        nombreEmpresa = nombre;
    }
    /**
     * Direccion de la empresa
     */
    private static String direccion = null;

    /**
     * Obtiene la direccion de la empresa
     * @return
     */
    public static String getDireccion() {
        return direccion;
    }

    /**
     * Estable la direccion de la empresa
     * @param direccion
     */
    public static void setDireccion(String _direccion) {
        direccion = _direccion;
    }
    /**
     * Telefono de la empresa
     */
    private static String telefono = null;

    /**
     * Obtiene el Telefono de la empresa
     * @return
     */
    public static String getTelefono() {
        return telefono;
    }

    /**
     * Establece el telefono de la empresa
     * @param telefono
     */
    public static void setTelefono(String _telefono) {
        telefono = _telefono;
    }
    /**
     * Nota fiscal del ticket (si es valido o no fiscalmente)
     */
    private static String notaFiscal = null;

    /**
     * Obtiene la nota fiscal del ticket
     * @return
     */
    public static String getNotaFiscal() {
        return notaFiscal;
    }

    public static void setNotaFiscal(String _nota) {
        notaFiscal = _nota;
    }
    /**
     * Titulo del Pagare
     */
    private static String cabeceraPagare = null;

    /**
     * Obtiene la cabecera del pagare
     * @return
     */
    public static String getCabeceraPagare() {
        return cabeceraPagare;
    }

    /**
     * Establece la cabecera del pagare
     * @param cabecera
     */
    public static void setCabeceraPagare(String _cabecera) {
        cabeceraPagare = _cabecera;
    }
    /**
     * Contenido del pagare
     */
    private static String pagare = null;

    /**
     * Obtiene el texto del pagare
     * @return
     */
    public static String getPagare() {
        return pagare;
    }

    /**
     * Establece el texto del pagare
     * @param pagare
     */
    public static void setPagare(String _pagare) {
        pagare = _pagare;
    }
    /**
     * Forma de contacto para quejas y sugerencias
     */
    private static String contacto = null;

    /**
     * Obtiene la forma de contacto
     * @return
     */
    public static String getContacto() {
        return contacto;
    }

    /**
     * Establece la forma de contacto
     * @param contacto
     */
    public static void setContacto(String _contacto) {
        contacto = _contacto;
    }
    /**
     * 
     */
    private static String gracias = null;

    /**
     * Obtiene la forma de contacto
     * @return
     */
    public static String getGracias() {
        return gracias;
    }

    /**
     * Establece el mensaje de gracias
     * @param gracias
     */
    public static void setGracias(String _gracias) {
        gracias = _gracias;
    }

    /**
     * Verifica que se hayan establecido correctamente todos los valores de las leyendas del ticket
     */
    public static void validator() {

        System.out.println("Iniciando proceso de validacion de leyendas ticket");

        int cont = 0;

        if (cabeceraTicket == null) {
            System.err.println("Error : No se definio la cabecera del ticket");
            cont++;
        }

        if (rfc == null) {
            System.err.println("Error : No de definio el rfc");
            cont++;
        }

        if (nombreEmpresa == null) {
            System.err.println("Error : No se definio el nombre de la empresa");
            cont++;
        }

        if (direccion == null) {
            System.err.println("Error : No se definio la direccion");
            cont++;
        }

        if (telefono == null) {
            System.err.println("Error : No se definio el telefono");
            cont++;
        }

        if (notaFiscal == null) {
            System.err.println("Error : No se definio la nota fiscal");
            cont++;
        }

        if (cabeceraPagare == null) {
            System.err.println("Error : No se definio la cabecera del pagare");
            cont++;
        }

        if (pagare == null) {
            System.err.println("Error : No se definio el texto del pagare");
            cont++;
        }

        if (contacto == null) {
            System.err.println("Error : No se definio el contacto");
            cont++;
        }

        if (gracias == null) {
            System.err.println("Error : No se definio el texto de gracias");
            cont++;
        }

        System.out.println("Terminado proceso de validacion de leyendas ticket. se encontraron " + cont + " errores.");

    }

    public LeyendasTicket(String json) {
        init(json);
    }

    private void init(String json){
        this.setJson(json);

        System.out.println("Iniciado proceso de construccion de LeyendasTicket");

        JSONParser parser = new JSONParser();

        try {

            Map jsonmap = (Map) parser.parse(this.json);
            Iterator iter = jsonmap.entrySet().iterator();

            while (iter.hasNext()) {
                Map.Entry entry = (Map.Entry) iter.next();

                if (entry.getKey().toString().equals("cabeceraTicket")) {

                    if (entry.getValue() != null) {

                        try{

                            LeyendasTicket.setCabeceraTicket(entry.getValue().toString());
                            System.out.println("Estableciendo la leyenda cabeceraTicket : " + LeyendasTicket.getCabeceraTicket());

                        }catch(Exception e){
                             System.err.println(e);
                        }

                    }

                }

                if (entry.getKey().toString().equals("rfc")) {

                   if (entry.getValue() != null) {

                        try{

                            LeyendasTicket.setRFC(entry.getValue().toString());
                            System.out.println("Estableciendo la leyenda rfc : " + LeyendasTicket.getRFC());

                        }catch(Exception e){
                             System.err.println(e);
                        }

                    }

                }
                if (entry.getKey().toString().equals("nombreEmpresa")) {

                    if (entry.getValue() != null) {

                        try{

                            LeyendasTicket.setNombreEmpresa(entry.getValue().toString());
                            System.out.println("Estableciendo la leyenda nombreEmpresat : " + LeyendasTicket.getNombreEmpresa());

                        }catch(Exception e){
                             System.err.println(e);
                        }

                    }

                }
                if (entry.getKey().toString().equals("direccion")) {

                    if (entry.getValue() != null) {

                        try{

                            LeyendasTicket.setDireccion(entry.getValue().toString());
                            System.out.println("Estableciendo la leyenda direccion : " + LeyendasTicket.getDireccion());

                        }catch(Exception e){
                             System.err.println(e);
                        }

                    }

                }
                if (entry.getKey().toString().equals("telefono")) {

                    if (entry.getValue() != null) {

                        try{

                            LeyendasTicket.setTelefono(entry.getValue().toString());
                            System.out.println("Estableciendo la leyenda telefono : " + LeyendasTicket.getTelefono());

                        }catch(Exception e){
                             System.err.println(e);
                        }

                    }

                }
                if (entry.getKey().toString().equals("notaFiscal")) {

                    if (entry.getValue() != null) {

                        try{

                            LeyendasTicket.setNotaFiscal(entry.getValue().toString());
                            System.out.println("Estableciendo la leyenda notaFiscal : " + LeyendasTicket.getNotaFiscal());

                        }catch(Exception e){
                             System.err.println(e);
                        }

                    }

                }
                if (entry.getKey().toString().equals("cabeceraPagare")) {

                    if (entry.getValue() != null) {

                        try{

                            LeyendasTicket.setCabeceraPagare(entry.getValue().toString());
                            System.out.println("Estableciendo la leyenda cabeceraPagare : " + LeyendasTicket.getCabeceraPagare());

                        }catch(Exception e){
                             System.err.println(e);
                        }

                    }

                }
                if (entry.getKey().toString().equals("pagare")) {

                    if (entry.getValue() != null) {

                        try{

                            LeyendasTicket.setPagare(entry.getValue().toString());
                            System.out.println("Estableciendo la leyenda pagare : " + LeyendasTicket.getPagare());

                        }catch(Exception e){
                             System.err.println(e);
                        }

                    }

                }
                if (entry.getKey().toString().equals("contacto")) {

                    if (entry.getValue() != null) {

                        try{

                            LeyendasTicket.setContacto(entry.getValue().toString());
                            System.out.println("Estableciendo la leyenda contacto : " + LeyendasTicket.getContacto());

                        }catch(Exception e){
                             System.err.println(e);
                        }

                    }

                }
                if (entry.getKey().toString().equals("gracias")) {

                    if (entry.getValue() != null) {

                        try{

                            LeyendasTicket.setGracias(entry.getValue().toString());
                            System.out.println("Estableciendo la leyenda gracias : " + LeyendasTicket.getGracias());

                        }catch(Exception e){
                             System.err.println(e);
                        }

                    }

                }

            }//while

            System.out.println("Termiando proceso de construccion de LeyendasTicket");
            

        } catch (Exception pe) {
            System.out.println(pe);
        }
    }
}
