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
        if(is_nan($precio_base))
        {
            Logger::error("El precio base (".$precio_base.") no es un numero");
            throw new Exception("El precio base (".$precio_base.") no es un numero",901);
        }
        
        if($precio_base<0)
        {
            Logger::error("El precio base (".$precio_base.") es negativo");
            throw new Exception("El precio base (".$precio_base.") es negativo");
        }
        
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
            
            //Si
            if($metodo_redondeo>0)
            {
                $redondeo_1=$precio_final+$metodo_redondeo-$precio_final%$metodo_redondeo;
            }
        }
    }
}
