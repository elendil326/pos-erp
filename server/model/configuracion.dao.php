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
    public function GuardarConfigDeVC($mostrar, $propiedades, $id_usuario)
    {
        $configuracion = new Configuracion(array(
            'descripcion' => 'productos_visibles_en_vc'
        ));

        $configuraciones = ConfiguracionDAO::search($configuracion);
        if (count($configuraciones) > 0)
        {
            $configuracion = $configuraciones[0];
        }

        // construir el JSON para el valor
        $json = '{"mostrar":'.$mostrar.'}';

        $configuracion->setValor($json);
        $configuracion->setIdUsuario($id_usuario);
        $configuracion->setFecha(time());
        
        parent::save($configuracion);
    }
}