<?php 

require_once("model/ingresos.dao.php");
require_once("model/gastos.dao.php");
require_once("model/autorizacion.dao.php");
require_once("model/pagos_compra.dao.php");
require_once("model/compra_sucursal.dao.php");



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

	$data = parseJSON( $args['data'] );


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
    //TODO: descomentar esta linea cuando este el nuevo DAO que incluya la nota
    $gasto->setNota( $data->nota );

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

	$data = parseJSON( $args['data'] );


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

	$data = parseJSON( $args['data'] );

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
    //TODO: descomentar esta linea cuando este el nuevo DAO que incluya la nota
    $ingreso->setNota( $data->nota );

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

	$data = parseJSON( $args['data'] );

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
 

/**
 *
 * 	insertarAbono
 *
 * 	Esta funcion nos regresa un JSON el resultado de la operacion de guardado de un abono en una sucursal
 *
 *  @access public
 *  @return json con el resultado del guardado
 *	@params String [$concepto] cadena que indica la causa de el gasto
 *	@params float [$monto] cantidad que se gasto
 *	@params timestamp [$fecha] fecha en que se realizo el gasto
 * 	@see GastosDAO::save() 
 * 	
 **/

 function insertarAbono( $args ) //600
 {

    if( !isset( $_SESSION['sucursal'] ) )
    {
        die( '{ "succes" : "false" , "reason" : "Sesion no iniciada."}' );
    }

    doInsertarAbono( $args['monto'] );
    
    //si todo salio bien llegara hasta aqui nuevamenten
    printf('{ "success": true}');
	
 }

function doInsertarAbono( $monto ){




    /*
    
     1.-Tratar de obtener la compra mas antigua sin liquidar
        si no se encontro:
            GOTO 2;
        si se encontro:
            la diferencia entre el monto y el saldo de la compra mas antigua es <= 0?
                si:
                    GOTO 3
                no: 
                    GOTO 4
     
     2.- Agregar el monto del abono directamente al saldo a favor de la sucursal
            GOTO 5;
            
     3.- Sumar a la compra mas antigua en el campo de pagado el monto
            GOTO 5
            
     4.- 
        a)Liquidar la compra mas antigua
        b)Igualar a monto con el resultado de la diferencia anterior 
        c)GO TO 1
         
     5.- End
     
    */


    //obtenemos todas las compras (de al mas antigua a la mas reciente)
    $c = new CompraSucursal();
    $c->setLiquidado('0');
    
    $compras = CompraSucursalDAO::search( $c );
//echo sizeof($compras)."  eee";
    $compra_a_abonar = null;
    
    $found = false;

    //obtenemos la compra mas reciente
    foreach( $compras as $compra )
    {
        $found = true;
        $compra_a_abonar = $compra;
        $saldo_compra = $compra->getTotal() - $compra->getPagado() ;
        break;
    }//foreach
    
    if( !$found )
    {
        //si no se encontro una compra sin liquidar se abona el monto directamente al saldo a favor de la sucursal
        $sucursal = SucursalDAO::getByPK($_SESSION['sucursal']);
        $sucursal->setSaldoAFavor( $sucursal->getSaldoAFavor() + monto );
        
        try
        {
            if( !( SucursalDAO::save( $sucursal ) > 0 ) )
            {
                die( '{ "succes" : "false" , "reason" : "No se registro el nuevo abono, no cambio el saldo a  favor en la sucursal."}' );
            }//if
            
            //si llegas aqui se abono directamente al saldo a favor de la sucursal
            //FIN DE LAS OPERACIONES
            return;
            
        }
        catch(Exception $e)
        {
            die( '{ "succes" : "false" , "reason" : "' . $e . '"}' );
        }//catch
        
    }//if
    
    
    /*
    
        si llega aqui es por que se encontro almenos una cuenta sin liquidar
    
    */
    
    
    $saldo = $monto - $saldo;
    /*
        si $saldo < 0 : el monto no liquida la cuenta
        si $saldo = 0 : si liquida la cuenta
        si $saldo > 0 : si liquida la cuenta y ademas sobra dinero
    */
    
        
        

    //actualizamos el campo de pagado en la compra
    $compra_a_abonar->setPagado( $compra_a_abonar->getPagado() + $monto );
    
    if($saldo >= 0)
    {
        //significa que la compra se liquido y hay que cambia su estado liquidada        
        $compra_a_abonar->setLiquidado(1);  
    }//if
        
    try
    {
        //intentamos guardar los cambios
        if( !( CompraSucursalDAO::save( $compra_a_abonar ) > 0 ) )
        {            
            die( '{ "success" : "false" , "reason" : "No se registro el nuevo abono"}' );
        }
            
        //ya que se actualizo el saldo de la compra, insertamos el abono en abonos compra
                
        $nuevo_abono = new PagosCompra();
        $nuevo_abono->setIdCompra( $compra_a_abonar->getIdCompra() );
        $nuevo_abono->setMonto( $monto );
        
        try
        {       
            //intentamos guardar el abono
            if( !( PagosCompraDAO::save( $nuevo_abono ) > 0 ) )
            {
                die( '{ "success" : "false" , "reason" : "No se registro el nuevo abono en pagos compra"}' );
            }
            
            //si se registro el abono entonces verificamos que haya sobrado dinero para abonarlo y repetir el algoritmo
            if( $saldo <= 0 )
            {
                //FIN DE LAS OPERACIONES
                return;
            }
            else
            {
                //llamamos nuevamente a este metodo y le pasamos el sobrante
                doInsertarAbono( $saldo );
            }
            
        }
        catch(Exception $e)
        {
            die( '{ "succes" : "false" , "reason" : "' . $e . '"}' );
        }

    }
    catch(Exception $e)
    {
         die( '{ "success" : "false" , "reason" : "Exception: No se registro el nuevo abono"}' );
    }//catch
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
            insertarAbono( $args );
        break;

        default:
            printf( '{ "success" : "false" }' );
        break;

    }//switch
}


?>
