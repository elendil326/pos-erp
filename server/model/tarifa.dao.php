<?php

require_once ('Estructura.php');
require_once("base/tarifa.dao.base.php");
require_once("base/tarifa.vo.base.php");
/** Page-level DocBlock .
  * 
  * @author Andres
  * @package docs
  * 
  */
/** Tarifa Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Tarifa }. 
  * @author Andres
  * @access public
  * @package docs
  * 
  */
class TarifaDAO extends TarifaDAOBase
{

        public static function obtenerTarifasActuales($tipo_tarifa)
        {
	
            //Logger::log("Obteniendo tarifas de ".$tipo_tarifa);

			switch($tipo_tarifa){
				case "venta":
				case "compra": break;
				default:
					throw new Exception("`$tipo_tarifa` no es un tipo de tarifa valido.");
			}

            //Se obtienen todas las tarifas activas con su version activa, su version default, y la
            //fecha de inicio y de fin de la version activa.
            $sql = "select 
						t.nombre, t.id_tarifa, t.id_version_activa, t.id_version_default, v.fecha_inicio, v.fecha_fin 
					from 
						tarifa t, version v 
					where 
						t.activa = 1 and t.tipo_tarifa = ? and v.id_version = t.id_version_activa";
            
            $val = array($tipo_tarifa);
            
            global $conn;
            $rs = $conn->Execute($sql,$val);
            $tarifas_versiones = array();
            
            //Recorremos el resultado y guardamos el id de la version que se va a usar, si la version activa
            //ya caduco o aun no se activa entonces se toma la default.
            foreach($rs as $result)
            { 
                $tarifa_version = array();
                $tarifa_version["id_tarifa"] = $result["id_tarifa"];
                $tarifa_version["nombre"] = $result["nombre"];
                
                //Si la fecha de inicio o la fecha de fin es nula, entonces se da por hecho que la version
                //no caduca y se selecciona.
                if(is_null($result["fecha_inicio"]) || is_null($result["fecha_fin"]))
                {
                    $tarifa_version["id_version"] = $result["id_version_activa"];
					array_push($tarifas_versiones,$tarifa_version);
                    continue;
                }
                
                $fecha_inicio = strtotime($result["fecha_inicio"]);
                $fecha_fin = strtotime($result["fecha_fin"]);
                
                $now = time();
                
                if(($fecha_inicio > $now) && ($now < $fecha_fin))
                {
                    $tarifa_version["id_version"] = $result["id_version_activa"];
                }
                else
                {
                    $tarifa_version["id_version"] = $result["id_version_default"];
                }
				
                array_push($tarifas_versiones, $tarifa_version);
            }
            
            //Una vez que tenemos los ids de las tarifas y de las versiones, traemos
            //todas las reglas de la version y las guardamos en el arreglo final
            $reglas = array();
            
            foreach($tarifas_versiones as $tarifa_version)
            {
                $regla = array();
                $regla["id_tarifa"] = $tarifa_version["id_tarifa"];
                $regla["reglas"] = ReglaDAO::search( new Regla( array( "id_version" => $tarifa_version["id_version"] ) ) , "secuencia");
				$regla["nombre"] = $tarifa_version["nombre"];
                array_push($reglas,$regla);
            }
            
            //Logger::log("Se obtuvieron ".count($reglas)." tarifas con sus respectivas reglas");
            
            return $reglas;
        }
}
