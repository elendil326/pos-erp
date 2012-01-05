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
                Logger::error("El producto o servicio tiene un metodo de costeo invalido");
                throw new Exception("El producto o servicio tiene un metodo de costeo invalido", 901);
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
            $precio_final = $precio_base * (1+ $regla->getPorcentajeUtilidad());
            
            $metodo_redondeo = $regla->getMetodoRedondeo();
            
            /*
             * 
             */
            if($metodo_redondeo>0)
            {
                $redondeo_1=$precio_final+$metodo_redondeo-$precio_final%$metodo_redondeo;
                $redondeo_2=$precio_final-$precio_final%$metodo_redondeo;
                
                if($redondeo_1-$precio_final < $precio_final-$redondeo_2)
                {
                    $precio_final = $redondeo_1;
                }
                else
                {
                    $precio_final = $redondeo_2;
                }
            }
            
            $precio_final += $regla->getUtilidadNeta();
            
            if($precio_final<$regla->getMargenMin() && $regla->getMargenMin()!=0)
            {
                $precio_final = $regla->getMargenMin();
            }
            
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
