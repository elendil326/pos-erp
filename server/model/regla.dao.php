<?php

require_once ('Estructura.php');
require_once("base/regla.dao.base.php");
require_once("base/regla.vo.base.php");
/** Page-level DocBlock .
  * 
  * @author Andres
  * @package docs
  * 
  */
/** Regla Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Regla }. 
  * @author Andres
  * @access public
  * @package docs
  * 
  */
class ReglaDAO extends ReglaDAOBase
{
    public static function aplicarReglas( array $reglas, VO $obj, $cantidad = null,$id_unidad = null)
    {
        
        $precio_base = 0;
        
        if(!($obj instanceof Paquete))
        {
            if($obj->getMetodoCosteo()=="costo")
            {
                $precio_base = $obj->getCostoEstandar();
            }
            else if($obj->getMetodoCosteo()=="precio")
            {
                $precio_base = $obj->getPrecio();
            }
            else
            {
                Logger::error("El producto o servicio tiene un metodo de costeo invalido. USANDO `metodo_de_costeo` = PRECIO");
                $precio_base = $obj->getPrecio();
                //throw new Exception("El producto o servicio tiene un metodo de costeo invalido", 901);
            }
        }
        else
        {
            $precio_base = $obj->getPrecio();
        }
        
        Logger::log(" El precio base recibido es ".$precio_base);
        
        $precio_final = 0;
        
        $regla = new Regla();
        
        foreach($reglas as $regla)
        {
            if(!($regla instanceof VO))
            {
                Logger::error("La regla recibida no es un VO valido");
                throw new Exception("La regla recibida no es un VO valido");
            }
            
            //Si la regla especifica que actuara sobre algun tipo especial, se evalua si el objeto recibido entra en esa clasificacion
            if
            (
                    !is_null($regla->getIdClasificacionProducto())      ||
                    !is_null($regla->getIdClasificacionServicio())      ||
                    !is_null($regla->getIdPaquete())                    ||
                    !is_null($regla->getIdProducto())                   ||
                    !is_null($regla->getIdRegla())                      ||
                    !is_null($regla->getIdServicio())                           
            )
            {
                
                $encontrado = false;  //Bandera que indicara si se encontro que la regla es aplicable o no
                
                //Si la regla especifica una clasificacion de producto y el objeto es un producto,
                //entonces se busca la clasificacion especificada por la regla en las clasificaciones del producto.
                //Si es encontrada se cambia a verdadero la bandera.
                if
                (
                        !is_null($regla->getIdClasificacionProducto())  &&
                        $obj instanceof Producto                        
                )
                {
                    $clasificaciones_producto = ProductoClasificacionDAO::search( 
                            new ProductoClasificacion( array( "id_producto" => $obj->getIdProducto() ) ) );
                    foreach($clasificaciones_producto as $clasificacion_producto)
                    {
                        if($clasificacion_producto->getIdClasificacionProducto()==$regla->getIdClasificacionProducto())
                        {
                            $encontrado = true;
                            break;
                        }
                    }
                }
                //Si aun no esta activa la bandera, la regla especifica una clasificacion de servicio y el objeto es un servicio,
                //entonces se busca la clasificacion esecificada por la regla en las clasificaciones del servicio.
                //Si es encontrada se cambia a verdadero la bandera
                if
                (
                        !$encontrado                                    &&
                        !is_null($regla->getIdClasificacionServicio())  &&
                        $obj instanceof Servicio
                )
                {
                    $clasificaciones_servicio = ServicioClasificacionDAO::search( 
                            new ServicioClasificacion( array( "id_servicio" => $obj->getIdServicio() ) ) );
                    foreach($clasificaciones_servicio as $clasificacion_servicio)
                    {
                        if($clasificacion_servicio->getIdClasificacionServicio()==$regla->getIdClasificacionServicio())
                        {
                            $encontrado = true;
                            break;
                        }
                    }
                }
                //Si aun no esta activa la bandera, la regla especifica un producto y el objeto es un producto,
                //entonces se compara el id especificado por la regla con el id del producto. Si son iguales, 
                //verifica si la regla especifica una unidad. De ser asi, verifica que la unidad especificada
                //por la regla sea igual a la obtenida, si lo son, entonces se cambia la bandera a verdadero.
                //Si no se especifica una unidad en la regla, entonces se cambia la bandera a verdadero
                if
                (
                        !$encontrado                        &&
                        !is_null($regla->getIdProducto())   &&
                        $obj instanceof Producto            
                )
                {
                    if($obj->getIdProducto()==$regla->getIdProducto())
                    {
                        if(!is_null($regla->getIdUnidad()))
                        {
                            if($regla->getIdUnidad()==$id_unidad)
                            {
                                $encontrado = true;
                            }
                        }
                        else
                        {
                            $encontrado = true;
                        }
                    }
                }
                //Si la bandera aun no esta activa, la regla especifica un servicio y el objeto es un servicio,
                //entonces se verifica que el id especificado por la regla sea igual al del servicio. Si son
                //iguales entonces se cambia la bandera a verdadero.
                if
                (
                        !$encontrado                        &&
                        !is_null($regla->getIdServicio())   &&
                        $obj instanceof Servicio
                )
                {
                    if($obj->getIdServicio()==$regla->getIdServicio())
                    {
                        $encontrado = true;
                    }
                }
                //Si la bandera aun no esta activa, la regla especifica un paquete y el objeto es un paquete,
                //entonces se verifica que el id especificado por al regla sea igual al del paquete. Si son
                //iguales entonces se cambia la bandera a verdadero.
                if
                (
                        !$encontrado                        &&
                        !is_null($regla->getIdPaquete())    &&
                        $obj instanceof Paquete
                )
                {
                    if($obj->getIdPaquete()==$regla->getIdPaquete())
                    {
                        $encontrado = true;
                    }
                }
            }
            
            if(!$encontrado)
            {
                continue;
            }
            
            $precio_final = $precio_base * (1+ $regla->getPorcentajeUtilidad());
            
            $metodo_redondeo = $regla->getMetodoRedondeo();
            
            /*
             * Para saber el precio final despues del redondeo se utilizan dos valores de redondeo,
             * un redoden superior y un redonde inferior. Por ejemplo, si el valor de precio final
             * es 57 y el metodo de redondeo es 10, existen dos posibles redondeos, 50 y 60, siendo 50
             * el redondeo inferior y 60 el redondeo superior.
             * 
             * Para calcula estos redondeos se usa la siguiente formula
             * 
             *    residuo = precio_final % metodo_redondeo
             * 
             * El residuo es lo que le sobra al precio final para ser un multiplo del metodo de redondeo,
             * entonces el redondeo inferior siempre sera el precio final menos el residuo. En nuestro ejemplo
             * 
             *   precio_final - residuo = 57 - 7 = 50
             * 
             * El redondeo superior es el precio final mas la resta del metodo de redondeo con el residuo. En
             * nuestro ejemplo
             * 
             *  precio_final + ( metodo_redondeo - residuo) = 57 + ( 10 - 7) = 60
             */
            if($metodo_redondeo>0)
            {
                $redondeo_superior=$precio_final+$metodo_redondeo-$precio_final%$metodo_redondeo;
                $redondeo_inferior=$precio_final-$precio_final%$metodo_redondeo;
                
                if($redondeo_superior-$precio_final < $precio_final-$redondeo_inferior)
                {
                    $precio_final = $redondeo_superior;
                }
                else
                {
                    $precio_final = $redondeo_inferior;
                }
            }
            
            //Si el precio final es menor que el margen minimo y el margen minimo esta especificado
            //( No es cero), entonces se sobreescribe el precio final con el del margen minimo
            $precio_final += $regla->getUtilidadNeta();
            
            if($precio_final<$regla->getMargenMin() && $regla->getMargenMin()!=0)
            {
                $precio_final = $regla->getMargenMin();
            }
            
            //Si el precio final es mayor que el margen maximo y el margen maximo esta especificado
            //( No es cero), entonces se sobreescribe el precio final con el del margen maximo
            if($precio_final>$regla->getMargenMax() && $regla->getMargenMax()!=0 )
            {
                $precio_final = $regla->getMargenMax();
            }
            
            $precio_base = $precio_final;
            
        }
        Logger::log("El precio despues de todas las reglas es ".$precio_base);
        
        return $precio_base;
    }
}
