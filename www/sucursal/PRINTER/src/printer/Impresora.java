/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package printer;

/**
 * Provee los medios necesarios para el manejo de las impresoras
 *
 * @author manuel
 */
public class Impresora {

    /**
     * Nombre o descripcion de la impresora
     */
    private static String descripcion = null;
    /**
     * Obtiene la descripcion o nombre de la impresora
     * @return
     */
    public static String getDescripcion(){
        return descripcion;
    }
    /**
     * Establece el nombre o descripcion de la impresora
     * @param _descripcion
     */
    public static void setDescripcion( String _descripcion ){
        descripcion = _descripcion;
    }
    /**
     * Verifica que se ahyan establecido correctamte todos los valores necesarios para la impresion
     */
    public static void validator(){

        System.out.println("Iniciando proceso de validacion de impresora");

        int cont = 0;

        if (descripcion == null) {
            System.err.println("Error : No se definio la descripciond e la impresora");
            cont++;
        }

        System.out.println("Terminado proceso de validacion de impresora. se encontraron " + cont + " errores.");

    }

}
