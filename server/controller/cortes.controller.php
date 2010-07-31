<?php
/**	 Cortes Controller.
*
* 	Este script contiene las funciones necesarias para realizar las operaciones
* 	a realizar sobre un corte de periodo (reparticion de ganancias) como es realizar el corte
* 	ver un reporte de corte pero sin guardarlo o bien revisar un corte realizado.
*	@author Diego Emanuelle Ventura <diego@caffeina.mx>
*
*	@see Corte, DetalleCorte
*/

/**
* Se importan los DAO para poder realizar las operaciones sobre la BD.
*/


require_once('../server/model/model.inc.php');
/*
//no se porque no funcionan :S
require_once('../server/model/corte.dao.php');
require_once('../server/model/detalle_corte.dao.php');
/*
/**
*
* 	obtener_cortes
*
* 	Esta funcion nos regresa un JSON con los datos de un los cortes realizados
* 	nos devuelve el id_corte, el ano, la fecha de inicio y la fecha final.
*
*       @access public
*       @return json con los datos mas importantes de todos los cortes
*	@see CorteDAO::getAll()
*/
 function obtener_cortes()//1001
 {
        $cortes = CorteDAO::getAll();
        if( count($cortes) == 0 )return '{ "success" : "false" }';
        $result='{ "success" : "true" , "datos" :[';
        $i=0;
        foreach ($cortes as $corte)
        {
                $result.='{ "id_corte" : "'.$corte->getNumCorte().'" , "anio" : "'.$corte->getAnio().'", "inicio" : "'.$corte->getInicio().'", "fin" : "'.$corte->getFin().'"},';
        }
        $result.=']}';
        return str_replace("},]", "}]",$result);
 }


/**
*
* 	reporte_corte
*
* 	Esta funcion nos regresa un JSON con los datos de un corte realizado
* 	consultado a la base de datos, lo unico que se le envia es el num_corte correspondiente
*       @access public
*       @return json con los datos y detalles del corte del numCorte enviado
*	@param int [$numCorte] fnumero de corte que del que se desea obtener 
*	@see CorteDAO::search(), DetalleCorteDAO::search()
*/
	
function reporte_corte($numCorte)//1002
{
	if(empty($numCorte))return '{ "success" : "false" , "reason" , "Faltan datos." }';
        $corte=CorteDAO::getByPK($numCorte);
        if(is_null($corte))return '{ "succes" : "false" , "reason" : "El corte que desea consultar no existe."}';
        $resul='{ "success" : "true" "datos" : ';
        $resul.= CorteDAO::search($corte,true);
	//quitamos la ultima llave para ahi añadir los detalles.
        $resul= str_replace("}]]", "",$resul);
        $detalles=new DetalleCorte();
        $detalles->setNumCorte($numCorte);
        $resul.= ' "detalles" :'.DetalleCorteDAO::search($detalles,true);
        //quitamos los dobles corchetes
        $resul= str_replace("[[", "[",$resul);
        $resul= str_replace("]]", "]",$resul);
        $resul.="]}";
        return $resul;
}



/**
* 	genera_corte
*
*	Funcion que genera el corte recibiendo fecha de inicio y fecha de fin
*	en caso de que se le envie el parametro guardar en true, entonces guardara el corte,
*	de lo contrario solo nos regresara los datos del corte simulado
*
*	@access public
*	@return json con los datos y detalles del corte simulado o bien con el resultado del intento de guardar
* 	@param date [$inicio] fecha de inicio del corte
* 	@param date [$fin] fecha de fin del corte
* 	@param bool [$guardar] verdadero si se desea guardar el corte, de lo contrario simula un corte
*	@see CorteDAO::reparticionGanancias()
*/

function genera_corte($inicio,$fin,$guardar)//1003
{
	if(empty($inicio)||empty($fin)) return '{ "success" : "false" , "reason" : "faltan datos" }';
        return ($guardar)?CorteDAO::reparticionGanancias($inicio,$fin,true):CorteDAO::reparticionGanancias($inicio,$fin);
}

/**
*
* 	eliminar_corte
*
* 	Esta funcion nos regresa un JSON con el resultado del intento de eliminacion y recibe el numero de corte a eliminar
*       @access public
*       @return json con el resultado de intento de borrado
*	@param int [$numCorte] fnumero de corte que del que se desea eliminar 
*	@see CorteDAO::search(), DetalleCorteDAO::search() , DetalleCorteDAO::delete()
*/
	
function eliminar_corte($numCorte)//1004
{
	if(empty($numCorte))return '{ "success" : "false" , "reason" , "Faltan datos." }';
        $corte=CorteDAO::getByPK($numCorte);
        if(is_null($corte))return '{ "succes" : "false" , "reason" : "El corte que desea eliminar no existe."}';
       	$detalleBuscar=new DetalleCorte();
        $detalleBuscar->setNumCorte($numCorte);
        try
        {
		$detalles=DetalleCorteDAO::search($detalleBuscar);
		foreach ($detalles as $detalle)
		{
        		DetalleCorteDAO::delete($detalle);
		}
        	CorteDAO::delete($corte);
        }
        catch(Exception $e)
        {
        	return '{ "succes" : "false" , "reason" : "El corte no se pudo eliminar."}';	
        }
        return '{ "success" : "true"}';
}

//cortes dispatcher
switch($args['action'])
{

         case '1001':   //'obtener_cortes':
                echo obtener_cortes();
                break;

         case '1002':   //'reporte_corte':

                $ncorte=$args['num_corte'];
                echo reporte_corte($ncorte);
                break;


         case '1003':   //'genera_corte':

                $inicio=$args['inicio'];
                $fin=$args['fin'];
                $guarda=($args['guardar'] === 'true')?true:false;
                echo genera_corte($inicio,$fin,$guarda);
                break;
                
         case '1004':   //'eliminar_corte':

                $ncorte=$args['num_corte'];
                echo eliminar_corte($ncorte);
                break;
                
         default:
         	echo '{ "success" : "false" }';
                break;
                
}



?>

