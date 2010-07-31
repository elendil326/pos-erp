<?php
/**	 efectivo Controller.
*
* 	Este script contiene las funciones necesarias para realizar las operaciones
* 	a sobre el moviemiento de efectivo, es decir los gastos y los ingresos de las sucursale
*	@author Diego Emanuelle Ventura <diego@caffeina.mx>
*
*	@see Gastos, Ingresos
*/

/**
* Se importan los DAO para poder realizar las operaciones sobre la BD.
*/


require_once('../server/model/model.inc.php');
/*
//no se porque no funcionan
require_once('../server/model/ingresos.dao.php');
require_once('../server/model/gastos.dao.php');
*/

/**
*
* 	insertarGasto
*
* 	Esta funcion nos regresa un JSON el resultado de la operacion de guardado de un gasto en una sucursal
*
*       @access public
*       @return json con el resultado del guardado
*	@params String [$concepto] cadena que indica la causa de el gasto
*	@params float [$monto] cantidad que se gasto
*	@params timestamp [$fecha] fecha en que se realizo el gasto
*	@see GastosDAO::save() 
*	
*/
 function insertarGasto($concepto, $monto, $fecha)//1601
 {
	$id_sucursal=$_SESSION['sucursal'];
	$id_usuario=$_SESSION['usuario'];
	$gasto=new Gastos();
	$gasto->setConcepto($concepto);
	$gasto->setFecha($fecha);
	$gasto->setMonto($monto);
	$gasto->setIdSucursal($id_sucursal);
	$gasto->setIdUsuario($id_usuario);
	
	try
	{
		return (GastosDAO::save($gasto)>0)?'{ "success" : "true" }':'{ "success" : "false" , "reason" : "No se pudo guardar el gasto."}';
	}
	catch(Exception $e)
	{
		return '{ "success" : "false" , "reason" : "No se pudo guardar el gasto."}';
	}
	
 }




/**
*
* 	eliminarGasto
*
* 	Esta funcion nos regresa un JSON el resultado de la operacion de borrado de un gasto en una sucursal
*
*       @access public
*       @return json con el resultado del borrado
*	@params int [$idGasto] id del gasto a eliminar
*	@see GastosDAO::search(), GastosDAO::delete() 
*	
*/
 function eliminarGasto($idGasto)//1602
 {
 	try
 	{
	 	$gasto= GastosDAO::getByPK($idGasto);
	 	if(is_null($gasto))return '{ "succes" : "false" , "reason" : "El gasto que desea eliminar no existe."}';
	        return(GastosDAO::delete($gasto)>0)?'{ "succes" : "true" }':'{ "succes" : "false" , "reason" : "No se pudo eliminar el gasto."}';
 	}
 	catch (Exception $e)
 	{
 		return '{ "succes" : "false" , "reason" : "Error al intentar borrar el gasto."}';
 	}
 }
 
 

/**
*
* 	actualizarGasto
*
*
* 	Esta funcion nos regresa un JSON el resultado de la operacion de actualizado de un gasto en una sucursal
*
*       @access public
*       @return json con el resultado de la actualizacion
*	@params int [$idGasto] id del gasto a eliminar
*	@params String [$concepto] cadena que indica la causa de el gasto
*	@params float [$monto] cantidad que se gasto
*	@params timestamp [$fecha] fecha en que se realizo el gasto
*	@see GastosDAO::save() , GastosDAO::search()

*	
*/
 function actualizarGasto($idGasto,$concepto, $monto, $fecha)//1603
 {
 	try
 	{
	 	$gasto= GastosDAO::getByPK($idGasto);
	 	if(is_null($gasto))return '{ "succes" : "false" , "reason" : "El gasto que desea actualizar no existe."}';
	 	
		$id_sucursal=$_SESSION['sucursal'];
		$id_usuario=$_SESSION['usuario'];
		$gasto->setConcepto($concepto);
		$gasto->setFecha($fecha);
		$gasto->setMonto($monto);
		$gasto->setIdSucursal($id_sucursal);
		$gasto->setIdUsuario($id_usuario);
	        return(GastosDAO::save($gasto)>0)?'{ "succes" : "true" }':'{ "succes" : "false" , "reason" : "No se pudo actualizar el gasto."}';
 	}
 	catch (Exception $e)
 	{
 		return '{ "succes" : "false" , "reason" : "Error al intentar actualizar el gasto."}';
 	}
 }
 
 
 
/**
*
* 	insertarIngreso
*
* 	Esta funcion nos regresa un JSON el resultado de la operacion de guardado de un Ingreso en una sucursal
*
*       @access public
*       @return json con el resultado del guardado
*	@params String [$concepto] cadena que indica la causa de el Ingreso
*	@params float [$monto] cantidad que se Ingreso
*	@params timestamp [$fecha] fecha en que se realizo el Ingreso
*	@see IngresosDAO::save() 
*	
*/
 function insertarIngreso($concepto, $monto, $fecha)//1604
 {
	$id_sucursal=$_SESSION['sucursal'];
	$id_usuario=$_SESSION['usuario'];
	$ingreso=new Ingresos();
	$ingreso->setConcepto($concepto);
	$ingreso->setFecha($fecha);
	$ingreso->setMonto($monto);
	$ingreso->setIdSucursal($id_sucursal);
	$ingreso->setIdUsuario($id_usuario);
	
	try
	{
		return (IngresosDAO::save($ingreso)>0)?'{ "success" : "true" }':'{ "success" : "false" , "reason" : "No se pudo guardar el ingreso."}';
	}
	catch(Exception $e)
	{
		return '{ "success" : "false" , "reason" : "No se pudo guardar el ingreso."}';
	}
	
 }


 
 
/**
*
* 	eliminarIngreso
*
* 	Esta funcion nos regresa un JSON el resultado de la operacion de borrado de un Ingreso en una sucursal
*
*       @access public
*       @return json con el resultado del borrado
*	@params int [$ingreso] id del Ingreso a eliminar
*	@see IngresosDAO::search(), IngresosDAO::delete() 
*	
*/
 function eliminarIngreso($idIngreso)//1605
 {
 	try
 	{
	 	$ingreso= IngresosDAO::getByPK($idIngreso);
	 	if(is_null($ingreso))return '{ "succes" : "false" , "reason" : "El Ingreso que desea eliminar no existe."}';
	        return(IngresosDAO::delete($ingreso)>0)?'{ "succes" : "true" }':'{ "succes" : "false" , "reason" : "No se pudo eliminar el Ingreso."}';
 	}
 	catch (Exception $e)
 	{
 		return '{ "succes" : "false" , "reason" : "Error al intentar borrar el Ingreso."}';
 	}
 }
 
 
 
  

/**
*
* 	actualizarIngreso
*
*
* 	Esta funcion nos regresa un JSON el resultado de la operacion de actualizado de un Ingreso en una sucursal
*
*       @access public
*       @return json con el resultado de la actualizacion
*	@params int [$idIngreso] id del Ingreso a eliminar
*	@params String [$concepto] cadena que indica la causa de el Ingreso
*	@params float [$monto] cantidad que se Ingreso
*	@params timestamp [$fecha] fecha en que se realizo el Ingreso
*	@see IngresosDAO::save() , IngresosDAO::search()

*	
*/
 function actualizarIngreso($idIngreso,$concepto, $monto, $fecha)//1606
 {
 	try
 	{
	 	$ingreso= IngresosDAO::getByPK($idIngreso);
	 	if(is_null($ingreso))return '{ "succes" : "false" , "reason" : "El Ingreso que desea actualizar no existe."}';
	 	
		$id_sucursal=$_SESSION['sucursal'];
		$id_usuario=$_SESSION['usuario'];
		$ingreso->setConcepto($concepto);
		$ingreso->setFecha($fecha);
		$ingreso->setMonto($monto);
		$ingreso->setIdSucursal($id_sucursal);
		$ingreso->setIdUsuario($id_usuario);
	        return(IngresosDAO::save($ingreso)>0)?'{ "succes" : "true" }':'{ "succes" : "false" , "reason" : "No se pudo actualizar el Ingreso."}';
 	}
 	catch (Exception $e)
 	{
 		return '{ "succes" : "false" , "reason" : "Error al intentar actualizar el Ingreso."}';
 	}
 }
 
 
 
 
//efectivo dispatcher
switch($args['action'])
{

         case '1601':   //'insertarGasto':
        	if((!empty($args['concepto']))&&(!empty($args['monto']))&&(!empty($args['fecha'])))
        	{
        		$concepto=$args['concepto'];
        		$monto=$args['monto'];
        		$fecha=$args['fecha'];
        		
        		echo insertarGasto($concepto,$monto,$fecha);
        	}
        	else
        	{
        		echo '{ "success" : "false" , "reason" : "Faltan datos" }';
        	}
                break;
                
         case '1602':   //'eliminarGasto':
        	if(!empty($args['id_gasto']))
        	{
        		$IdGasto=$args['id_gasto'];
        		echo eliminarGasto($IdGasto);
        	}
        	else
        	{
        		echo '{ "success" : "false" , "reason" : "Faltan datos" }';
        	}
        	
                break;
         
         case '1603':   //'actualizarGasto':
        	if((!empty($args['id_gasto']))&&(!empty($args['concepto']))&&(!empty($args['monto']))&&(!empty($args['fecha'])))
        	{
        		$IdGasto=$args['id_gasto'];
        		$concepto=$args['concepto'];
        		$monto=$args['monto'];
        		$fecha=$args['fecha'];
        		
        		echo actualizarGasto($IdGasto,$concepto,$monto,$fecha);
        	}
        	else
        	{
        		echo '{ "success" : "false" , "reason" : "Faltan datos" }';
        	}
                break;

         case '1604':   //'insertarIngreso':
        	if((!empty($args['concepto']))&&(!empty($args['monto']))&&(!empty($args['fecha'])))
        	{
        		$concepto=$args['concepto'];
        		$monto=$args['monto'];
        		$fecha=$args['fecha'];
        		
        		echo insertarIngreso($concepto,$monto,$fecha);
        	}
        	else
        	{
        		echo '{ "success" : "false" , "reason" : "Faltan datos" }';
        	}
                break;
                
                     
         case '1605':   //'eliminarIngreso':
        	if(!empty($args['id_ingreso']))
        	{
        		$IdIngreso=$args['id_ingreso'];
        		echo eliminarIngreso($IdIngreso);
        	}
        	else
        	{
        		echo '{ "success" : "false" , "reason" : "Faltan datos" }';
        	}
        	
                break;
          
                                break;
         
         case '1606':   //'actualizarIngreso':
        	if((!empty($args['id_ingreso']))&&(!empty($args['concepto']))&&(!empty($args['monto']))&&(!empty($args['fecha'])))
        	{
        		$IdIngreso=$args['id_ingreso'];
        		$concepto=$args['concepto'];
        		$monto=$args['monto'];
        		$fecha=$args['fecha'];
        		
        		echo actualizarIngreso($IdIngreso,$concepto,$monto,$fecha);
        	}
        	else
        	{
        		echo '{ "success" : "false" , "reason" : "Faltan datos" }';
        	}
                break;               
                
         default:
         	echo '{ "success" : "false" }';
                break;
                
}

?>

