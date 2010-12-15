<?php 

require_once("model/ingresos.dao.php");
require_once("model/gastos.dao.php");
require_once("model/autorizacion.dao.php");






/**
 *
 * 	listarGastosPorSucursal
 *
 *  Regresa un arreglo con objetos Gasto para esta sucursal
 *
 *  @access public
 *  @return array
 *	@params int [$id_sucursal] sucursal
 * 	
 **/
function listarGastosSucursal( $sid = null)
{
    if(!$sid) return null;

    $gastos = new Gastos();
    $gastos->setIdSucursal( $sid );

    return GastosDAO::search( $gastos );
}




/**
 *
 * 	listarIngresosPorSucursal
 *
 *  Regresa un arreglo con objetos Ingresos para esta sucursal
 *
 *  @access public
 *  @return array
 *	@params int [$id_sucursal] sucursal
 * 	
 **/
function listarIngresosSucursal( $sid = null)
{
    if(!$sid) return null;

    $Ingresos = new Ingresos(  );
    $Ingresos->setIdSucursal($sid);

    return IngresosDAO::search( $Ingresos );
}







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

 function insertarGasto( $args ) //600
 {

    //TODO:Falta contemplar el campo nota

    if( !isset($args['data']) )
    {
        die('{"success": false, "reason": "No hay parametros para ingresar." }');
    }

    try
    {
        $data = json_decode( $args['data'] );
    }
    catch(Exception $e)//autorizacionPendiate
    {
        die( '{"success": false, "reason": "Parametros invalidos." }' );
    }

    if( !isset( $data->folio ) || !isset( $data->concepto ) || !isset( $data->monto ) /*|| !isset( $data->fecha )*/)
    {
        die('{"success": false, "reason": "Faltan parametros." }');
    }

    if(!is_numeric( $data->monto ))
    {
        die( '{ "success" : "false" , "reason" : "No es una cantidad valida." }' ); 
    }

    $gasto = new Gastos();

    $gasto -> setFolio( $data->folio );
    $gasto->setConcepto( $data->concepto );
    $gasto->setMonto( $data->monto );

    if( isset( $data->fecha ) )
    {
        $gasto->setFechaIngreso( $data->fecha );
    }

    $gasto->setIdSucursal( $_SESSION['sucursal'] );
    $gasto->setIdUsuario( $_SESSION['userid'] );

	try
	{
        if( GastosDAO::save( $gasto ) > 0 )
        {
            printf( '{ "success" : "true" }' );
        }
        else
        {
            die('{"success": false, "reason": "No se guardo la el gasto." }');
        }
	}
	catch(Exception $e)
	{
		die('{"success": false, "reason": "No se pudo guardar el gasto. ' . $e . ' " }');
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
 function eliminarGasto( $args )
 {

    if( !isset( $args['id_gasto'] ) )
    {
        die('{ "success" : "false" , "reason" : "Faltan datos" }' );
    }

 	try
 	{
	 	$gasto= GastosDAO::getByPK( $args['id_gasto'] );

	 	if( is_null( $gasto ) )
        {
            die( '{ "succes" : "false" , "reason" : "El gasto que desea eliminar no existe."}' );
        }
	    
        if( GastosDAO::delete( $gasto ) > 0)
        {
            printf( '{ "succes" : true }' );
        }
        else
        {
            die( '{ "succes" : "false" , "reason" : "No se pudo eliminar el gasto."}' );
        }
 	}
 	catch (Exception $e)
 	{
 		die( '{ "succes" : "false" , "reason" : "Error al intentar borrar el gasto."}' );
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
 function actualizarGasto( $args )//602
 {

    if( !isset($args['data']) )
    {
        die('{"success": false, "reason": "No hay parametros para ingresar." }');
    }

    try
    {
        $data = json_decode( $args['data'] );
    }
    catch(Exception $e)
    {
        die( '{"success": false, "reason": "Parametros invalidos." }' );
    }

    if( !( $gasto = GastosDAO::getByPK( $data->id_gasto ) ) )
    {
        die( '{"success": false, "reason": "No se tiene registro de ese gasto." }' );
    }

    if(isset( $data->folio ))
    {
        $gasto->setFolio( $data->folio );
    }

    if(isset( $data->concepto ))
    {
        $gasto->setConcepto( $data->concepto );
    }

    if(isset( $data->monto ))
    {
        $gasto->setMonto( $data->monto );
    }

    if(isset( $data->fecha ))
    {
        $gasto->setFechaIngreso( $data->fecha );
    }

 	try
 	{
 	    if( GastosDAO::save( $gasto ) > 0 )
        {
            printf( '{ "succes" : true }' );
        }
        else
        {
             die( '{"success": false, "reason": "No se pudo actualizar el gasto, no ha modificado ningun valor." }' );
        }
 	}
 	catch (Exception $e)
 	{
 		die( '{ "succes" : "false" , "reason" : "Error al intentar actualizar el gasto."}' );
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
 function insertarIngreso( $args ) //603
 {

    if( !isset($args['data']) )
    {
        die('{"success": false, "reason": "No hay parametros para ingresar." }');
    }

    try
    {
        $data = json_decode( $args['data'] );
    }
    catch(Exception $e)
    {
        die( '{"success": false, "reason": "Parametros invalidos." }' );
    }

    if( !isset( $data->concepto ) || !isset( $data->monto ) || !isset( $data->fecha ))
    {
        die('{"success": false, "reason": "Faltan parametros." }');
    }

    if(!is_numeric( $data->monto ))
    {
        die( '{ "success" : "false" , "reason" : "No es una cantidad valida." }' ); 
    }

    $ingreso = new Ingresos();

    $ingreso->setConcepto( $data->concepto );
    $ingreso->setMonto( $data->monto );
    $ingreso->setFechaIngreso( $data->fecha );
    $ingreso->setIdSucursal( $_SESSION['sucursal'] );
    $ingreso->setIdUsuario( $_SESSION['userid'] );

    try
    {
        if( IngresosDAO::save( $ingreso ) > 0 )
        {
            printf( '{ "success" : "true" }' );
        }
        else
        {
            die('{"success": false, "reason": "No se guardo el Ingreso." }');
        }
    }
    catch(Exception $e)
    {
        die('{"success": false, "reason": "No se pudo guardae el Ingreso. ' . $e . ' " }');
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
 function eliminarIngreso( $args )//604
 {

    if( !isset( $args['id_ingreso'] ) )
    {
        die('{ "success" : "false" , "reason" : "Faltan datos" }' );
    }

    try
    {
        $ingreso = IngresosDAO::getByPK( $args['id_ingreso'] );

        if( is_null( $ingreso ) )
        {
            die( '{ "succes" : "false" , "reason" : "El gasto que desea eliminar no existe."}' );
        }
        
        if( GastosDAO::delete( $ingreso ) > 0)
        {
            printf( '{ "succes" : true }' );
        }
        else
        {
            die( '{ "succes" : "false" , "reason" : "No se pudo eliminar el gasto."}' );
        }
    }
    catch (Exception $e)
    {
        die( '{ "succes" : "false" , "reason" : "Error al intentar borrar el gasto."}' );
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
 function actualizarIngreso( $args )//605
 {

    if( !isset($args['data']) )
    {
        die('{"success": false, "reason": "No hay parametros para ingresar." }');
    }

    try
    {
        $data = json_decode( $args['data'] );
    }
    catch(Exception $e)
    {
        die( '{"success": false, "reason": "Parametros invalidos." }' );
    }

    if( !( $ingreso = GastosDAO::getByPK( $data->id_gasto ) ) )
    {
        die( '{"success": false, "reason": "No se tiene registro de ese ingreso." }' );
    }

    if(isset( $data->concepto ))
    {
        $ingreso->setConcepto( $data->concepto );
    }

    if(isset( $data->monto ))
    {
        $ingreso->setMonto( $data->monto );
    }

    if(isset( $data->fecha ))
    {
        $ingreso->setFechaIngreso( $data->fecha );
    }

    try
    {
        if( GastosDAO::save( $ingreso ) > 0 )
        {
            printf( '{ "succes" : true }' );
        }
        else
        {
             die( '{"success": false, "reason": "No se pudo actualizar el ingreso, no ha modificado ningun valor." }' );
        }
    }
    catch (Exception $e)
    {
        die( '{ "succes" : "false" , "reason" : "Error al intentar actualizar el ingreso."}' );
    }
 }
 



if(isset($args['action'])){
    switch( $args['action'] )
    {
        case '600':
            insertarGasto( $args );
        break;

        case '601':
            eliminarGasto( $args );
        break;

        case '602':
            actualizarGasto( $args );
        break;

        case '603':
            insertarIngreso( $args );
        break;

        case '604':
            eliminarIngreso( $args );
        break;

        case '605':
            actualizarIngreso( $args );
        break;

        case '606':
            printf( '{ "success" : true , "id_sucursal" : "%s" }', $_SESSION['sucursal'] );
        break;

        default:
            printf( '{ "success" : "false" }' );
        break;

    }//switch
}


?>
