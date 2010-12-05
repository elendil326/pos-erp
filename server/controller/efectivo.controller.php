<?php 

require_once("../server/model/ingresos.dao.php");
require_once("../server/model/gastos.dao.php");
require_once("../server/model/autorizacion.dao.php");

/**
 *
 * 	insertarGasto
 *
 * 	Esta funcion nos regresa un JSON el resultado de la operacion de guardado de un gasto en una sucursal
 *
 *  @access public
 *  @return json con el resultado del guardado
 *	@params String [$concepto] cadena que indica la causa de el gasto
 *	@params float [$monto] cantidad que se gasto
 *	@params timestamp [$fecha] fecha en que se realizo el gasto
 * 	@see GastosDAO::save() 
 * 	
 **/

 function insertarGasto($folio, $concepto, $monto, $fecha) //601
 {
	$id_sucursal = $_SESSION['sucursal'];
	$id_usuario = $_SESSION['userid'];
	
	$gasto = new Gastos();
	
	$gasto -> setFolio($folio);
	$gasto -> setConcepto($concepto);
	$gasto -> setMonto($monto);
	$gasto -> setFecha($fecha);	
	$gasto -> setIdSucursal($id_sucursal);
	$gasto -> setIdUsuario($id_usuario);
	
	try
	{
		return (GastosDAO::save($gasto) > 0)?'{ "success" : "true" }':'{ "success" : "false" , "reason" : "No se pudo guardar el gasto."}';
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
 function eliminarGasto($idGasto)//602
 {
 	try
 	{
	 	$gasto= GastosDAO::getByPK($idGasto);

	 	if(is_null($gasto))
        {
            return '{ "succes" : "false" , "reason" : "El gasto que desea eliminar no existe."}';
        }
	    
        return(GastosDAO::delete($gasto) > 0)?'{ "succes" : true }':'{ "succes" : "false" , "reason" : "No se pudo eliminar el gasto."}';
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
 function actualizarGasto($idGasto,$folio,$concepto, $monto, $fecha)//603
 {
 	try
 	{
 	
 	    $id_sucursal=$_SESSION['sucursal'];
		$id_usuario=$_SESSION['userid'];
 	    
	 	$gasto= GastosDAO::getByPK($idGasto);
	 	
	 	if(is_null($gasto))
        {
            return '{ "succes" : "false" , "reason" : "El gasto que desea actualizar no existe."}';
        }
		
		$gasto->setFolio($folio);		
		$gasto->setConcepto($concepto);
		$gasto->setMonto($monto);
		$gasto->setFecha($fecha);
		
		//TODO falta ingresar la fecha en la cual se ingreso el gasto, no se permite modificar el id de la sucursal ni del usuario para
		//conservar la integridad de la informacion

	        return(GastosDAO::save($gasto)>0)?'{ "succes" : true }':'{ "succes" : "false" , "reason" : "No se pudo actualizar el gasto."}';
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
 function insertarIngreso($concepto, $monto, $fecha)//604
 {
	$id_sucursal=$_SESSION['sucursal'];
	$id_usuario=$_SESSION['userid'];
	
	$ingreso=new Ingresos();
	$ingreso->setConcepto($concepto);
	$ingreso->setMonto($monto);
	$ingreso->setFecha($fecha);	
	$ingreso->setIdSucursal($id_sucursal);
	$ingreso->setIdUsuario($id_usuario);
	
	try
	{
		return (IngresosDAO::save($ingreso) > 0)?'{ "success" : true }':'{ "success" : "false" , "reason" : "No se pudo guardar el ingreso."}';
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
 function eliminarIngreso($idIngreso)//605
 {
 	try
 	{
	 	$ingreso= IngresosDAO::getByPK($idIngreso);

	 	if(is_null($ingreso))
        {
            return '{ "succes" : "false" , "reason" : "El Ingreso que desea eliminar no existe."}';
        }
	     
        return(IngresosDAO::delete($ingreso) > 0)?'{ "succes" : true }':'{ "succes" : "false" , "reason" : "No se pudo eliminar el Ingreso."}';
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
 function actualizarIngreso($idIngreso,$concepto, $monto, $fecha)//606
 {
 	try
 	{
	 	$ingreso= IngresosDAO::getByPK($idIngreso);
	 	if(is_null($ingreso))
        {
            return '{ "succes" : "false" , "reason" : "El Ingreso que desea actualizar no existe."}';
        }
	 	
		$id_sucursal=$_SESSION['sucursal'];
		$id_usuario=$_SESSION['userid'];
		$ingreso->setConcepto($concepto);
		$ingreso->setFecha($fecha);
		$ingreso->setMonto($monto);
		$ingreso->setIdSucursal($id_sucursal);
		$ingreso->setIdUsuario($id_usuario);
	        
        return(IngresosDAO::save($ingreso) > 0)?'{ "succes" : true }':'{ "succes" : "false" , "reason" : "No se pudo actualizar el Ingreso."}';
 	}
 	catch (Exception $e)
 	{
 		return '{ "succes" : "false" , "reason" : "Error al intentar actualizar el Ingreso."}';
 	}
 }
 
 
switch($args['action'])
{
    case '601':   //'insertarGasto':
       	
        if((!empty($args['folio']))&&(!empty($args['concepto']))&&(!empty($args['monto']))&&(!empty($args['fecha'])))
      	{
      	    $folio = $args['folio'];
       		$concepto = $args['concepto'];
       		$monto = $args['monto'];
       		$fecha = $args['fecha'];        	        					
								
       		if(!is_numeric($monto))
       		{
       			echo '{ "success" : "false" , "reason" : "No es una cantidad valida." }'; 
       			return;
       		}
       		echo insertarGasto($folio,$concepto,$monto,$fecha);
       	}
       	else
       	{
       		echo '{ "success" : "false" , "reason" : "Faltan datos" }';
       	}
    break;
    
    case '602':   //'eliminarGasto':
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
         
    case '603':   //'actualizarGasto':
       	if((!empty($args['id_gasto']))&&(!empty($args['folio']))&&(!empty($args['concepto']))&&(!empty($args['monto']))&&(!empty($args['fecha'])))
       	{
       		$IdGasto = $args['id_gasto'];
       		$folio = $args['folio'];
       		$concepto = $args['concepto'];
       		$monto = $args['monto'];
       		$fecha = $args['fecha'];
				
       		if(!is_numeric($monto))
       		{
       			echo '{ "success" : "false" , "reason" : "No es una cantidad valida." }'; 
       			return;
       		}
       		echo actualizarGasto($IdGasto, $folio, $concepto, $monto, $fecha);
       	}
       	else
       	{
       		echo '{ "success" : "false" , "reason" : "Faltan datos" }';
       	}
    break;

    case '604':   //'insertarIngreso':
       	if((!empty($args['concepto']))&&(!empty($args['monto']))&&(!empty($args['fecha'])))
       	{
       		$concepto=$args['concepto'];
       		$monto=$args['monto'];
       		$fecha=$args['fecha'];

			
       		if(!is_numeric($monto))
       		{
       			echo '{ "success" : "false" , "reason" : "No es una cantidad valida." }'; 
       			return;
      		}
       		echo insertarIngreso($concepto,$monto,$fecha);
       	}
       	else
       	{
       		echo '{ "success" : "false" , "reason" : "Faltan datos" }';
       	}
    break;
                
    case '605':   //'eliminarIngreso':
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
          
    case '606':   //'actualizarIngreso':
        if((!empty($args['id_ingreso']))&&(!empty($args['concepto']))&&(!empty($args['monto']))&&(!empty($args['fecha'])))
        {
        	$IdIngreso=$args['id_ingreso'];
        	$concepto=$args['concepto'];
        	$monto=$args['monto'];
        	$fecha=$args['fecha'];

				
        	if(!is_numeric($monto))
        	{
        		echo '{ "success" : "false" , "reason" : "No es una cantidad valida." }'; 
        		return;
        	}
        	echo actualizarIngreso($IdIngreso,$concepto,$monto,$fecha);
        }
        else
        {
        	echo '{ "success" : "false" , "reason" : "Faltan datos" }';
        }
    break;
         
    case '607':   //'obtenerSucursalUsuario':
        echo '{ "success" : true , "id_sucursal" : "'.$_SESSION['sucursal'].'" }';
    break;

    default:
        echo '{ "success" : "false" }';
    break;

}//switch

?>
