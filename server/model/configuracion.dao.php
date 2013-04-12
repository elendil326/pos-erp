<?php

require_once ('Estructura.php');
require_once("base/configuracion.dao.base.php");
require_once("base/configuracion.vo.base.php");
/** Page-level DocBlock .
  * 
  * @author Anonymous
  * @package docs
  * 
  */
/** Configuracion Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Configuracion }. 
  * @author Anonymous
  * @access public
  * @package docs
  * 
  */
class ConfiguracionDAO extends ConfiguracionDAOBase
{
    private static $descripcion = 'productos_visibles_en_vc';

    public static function GuardarConfigDeVC($mostrar, $id_usuario, $propiedades)
    {
        $configuracion = new Configuracion(array(
            'descripcion' => self::$descripcion
        ));

        $configuraciones = parent::search($configuracion);
        if (count($configuraciones) > 0)
        {
            $configuracion = $configuraciones[0];
        }

        $campos = array_keys(get_class_vars('Producto'));

        // if propiedades_array NO es subconjunto de campos
        if (!array_intersect($propiedades, $campos) == $propiedades)
        {
            throw new InvalidArgumentException("Conjunto de propiedades inválido");
        }

        $valor = array("mostrar" => $mostrar, "propiedades" => $propiedades);

        $configuracion->setValor(json_encode($valor));
        $configuracion->setIdUsuario($id_usuario);
        $configuracion->setFecha(time());
        
        parent::save($configuracion);
    }

    public static function MostrarProductos()
    {
        $configuracion = new Configuracion(array(
            'descripcion' => self::$descripcion
        ));

        $configuraciones = parent::search($configuracion);

        if (count($configuraciones) == 0)
        {
            return false;
        }

        $valor = json_decode($configuraciones[0]->getValor());
        if ($valor->mostrar == 0)
        {
            return false;
        }

        return true;
    }

    public static function Propiedades()
    {
        $configuracion = new Configuracion(array(
            'descripcion' => self::$descripcion
        ));

        $configuraciones = parent::search($configuracion);

        if (count($configuraciones) > 0)
        {
            $valor = json_decode($configuraciones[0]->getValor());
            return $valor->propiedades;
        }

        return null;
    }
}