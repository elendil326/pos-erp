<?php

        //require_once('db/DBConnection.php');
	require_once('db/adodb5/adodb.inc.php');
	require_once('db/adodb5/adodb-exceptions.inc.php');

	global $conn;
	
	try{
	    $conn = ADONewConnection('mysql');
	    $conn->debug = false;
	    $conn->PConnect('localhost', 'root', '', 'pos');
	    if(!$conn) {
		throw new Exception("Error en la conexión a la base de datos.");
	    }
	} catch (exception $e) {
	    echo $e->getMessage();
	}

        /*******************************************************************************
         REPORTES DEL TIPO "MEJOR X DE LA SUCURSAL Y" O "MEJOR X DE TODAS LAS SUCURSALES"
        *******************************************************************************/

        /*
        *       UTILS PARA LOS REPORTES
        */ 

        //Obtenemos dos fechas dentro de un rango (semana, mes, año). Se refiere la ultima semana, mes, año.
        function getDateRange($dateInterval){

                $datesArray = array();
                $currentDate = getdate();
                $dateToday = $currentDate['year'].'-'.$currentDate['mon'].'-'.$currentDate['mday'];

                switch($dateInterval)
                        {
                                case 'semana':  $fecha = date_create( $dateToday );
                                                //$fecha = date_create("2010-07-14");
                                                date_sub($fecha, date_interval_create_from_date_string('7 days'));
                                                $dateWeekBefore = date_format($fecha, 'Y-m-d');
                                                array_push($datesArray, $dateWeekBefore);
                                                array_push($datesArray, $dateToday);
        
                                                return($datesArray);

                                                break;
                                /************************************************************************/

                                case 'mes':     $fecha = date_create( $dateToday );
                                                //$fecha = date_create("2010-07-14");
                                                date_sub($fecha, date_interval_create_from_date_string('1 month'));
                                                $dateWeekBefore = date_format($fecha, 'Y-m-d');
                                                array_push($datesArray, $dateWeekBefore);
                                                array_push($datesArray, $dateToday);

                                                return($datesArray);

                                                break;
                                /************************************************************************/

                                case 'year':    $fecha = date_create( $dateToday );
                                                //$fecha = date_create("2010-07-14");
                                                date_sub($fecha, date_interval_create_from_date_string('1 year'));
                                                $dateWeekBefore = date_format($fecha, 'Y-m-d');
                                                array_push($datesArray, $dateWeekBefore);
                                                array_push($datesArray, $dateToday);

                                                return($datesArray);

                                                break;
                                /************************************************************************/

                                default:        return false;
                        }

        }

        /*
        *       VENDEDOR MAS PRODUCTIVO EN GENERAL
        */

        function vendedorMasProductivo($conn){


                $dateRange = "";
                $params = array();

                //$qry_select = "SELECT `ventas`.`id_usuario`, `usuario`.`nombre`, SUM(`ventas`.`subtotal`) AS `Vendido` FROM `ventas`, `usuario` WHERE `ventas`.`id_usuario` = `usuario`.`id_usuario` ";                         
		$qry_select = "SELECT `usuario`, SUM(`subtotal`) AS `vendido` FROM `view_ventas`";


                if( isset( $_REQUEST['dateRange']) )
                {
                        $dateInterval = $_REQUEST['dateRange'];


                        //Escogemos el rango de tiempo para los datos (Semana, Mes, Año, Todos)        
                        $datesArray = getDateRange($dateInterval);      

                        if( $datesArray != false )
                        {
                                
                                array_push($params, $datesArray[0]);
                                array_push($params, $datesArray[1]);

                                $qry_select .= " AND date(`view_ventas`.`fecha`) BETWEEN ? AND ?";
                        }
                        
                        
                }


                //Si existen los rangos de fechas, agregamos una linea al query para filtrar los resultados dentro de ese rango
                if ( isset($_REQUEST['fecha-inicio']) && isset($_REQUEST['fecha-final']) && !isset($_REQUEST['dateRange']) )
                {
                        array_push($params, $_REQUEST['fecha-inicio']);
                        array_push($params, $_REQUEST['fecha-final']);
                        $dateRange .= " AND date(`view_ventas`.`fecha`) BETWEEN ? AND ?";
                        $qry_select .= $dateRange;
                }

                $qry_select .= " GROUP BY `usuario` ORDER BY `vendido` DESC LIMIT 1";

                //$listar = new listar($qry_select, $params);
                //echo $listar->lista();
		try{	
	    	$result_vendedor = $conn->Execute($qry_select, $params);
		}catch(Exception $e){
		  echo $e->getMessage();
		  return;
		}

		$array_result = array();
		while($obj_user = $result_vendedor->fetchNextObject(false)) {
		    array_push($array_result, $obj_user);
		}

		echo "{ success: true, datos: ".json_encode($array_result)."}";
                return;

        }

        /*
        *       PRODUCTO MAS VENDIDO EN GENERAL
        */      
        
        function productoMasVendido(){
                
                
                $dateRange = "";
                $params = array();

                        
                //$qry_select = " SELECT `inventario`.`denominacion` AS `nombre`, SUM(`detalle_venta`.`cantidad`) AS `Cantidad` FROM `inventario`, `detalle_venta`, `ventas`  WHERE `inventario`.`id_producto` = `detalle_venta`.`id_producto` AND `detalle_venta`.`id_venta` = `ventas`.`id_venta`";
		$qry_select = "SELECT `denominacion` AS `nombre`, SUM(`cantidad`) AS `cantidad` FROM `view_detalle_venta`";

                if( isset( $_REQUEST['dateRange']) )
                {
                        $dateInterval = $_REQUEST['dateRange'];


                        //Escogemos el rango de tiempo para los datos (Semana, Mes, Año, Todos)        
                        $datesArray = getDateRange($dateInterval);      

                        if( $datesArray != false )
                        {
                                array_push($params, $datesArray[0]);
                                array_push($params, $datesArray[1]);

                                $qry_select .= " AND date(`fecha`) BETWEEN ? AND ?";
                        }
                        
                        
                }


                if ( isset($_REQUEST['fecha-inicio']) && isset($_REQUEST['fecha-final']) && !isset($_REQUEST['dateRange']))
                {
                        array_push($params, $_REQUEST['fecha-inicio']);
                        array_push($params, $_REQUEST['fecha-final']);
                        $dateRange .= " AND date(`fecha`) BETWEEN ? AND ?";
                        $qry_select .= $dateRange;
                }

                $qry_select .= " GROUP BY `id_producto` ORDER BY `Cantidad` DESC LIMIT 1";

                $listar = new listar($qry_select, $params);
                echo $listar->lista();

                return;

        }

        /*
        *       SUCURSAL QUE VENDE MAS 
        */

        function sucursalVentasTop(){
                
                $dateRange = "";
                $params = array();

                        
                //$qry_select = " SELECT `sucursal`.`descripcion` AS `nombre`, SUM(`ventas`.`subtotal`) AS `Cantidad` FROM `ventas`, `sucursal` WHERE `sucursal`.`id_sucursal` = `ventas`.`sucursal` ";
		$qry_select = "SELECT `sucursal`, SUM(`subtotal`) AS `cantidad` FROM `view_ventas`";

                if( isset( $_REQUEST['dateRange']) )
                {
                        $dateInterval = $_REQUEST['dateRange'];


                        //Escogemos el rango de tiempo para los datos (Semana, Mes, Año, Todos)        
                        $datesArray = getDateRange($dateInterval);      

                        if( $datesArray != false )
                        {
                                array_push($params, $datesArray[0]);
                                array_push($params, $datesArray[1]);

                                $qry_select .= " AND date(`fecha`) BETWEEN ? AND ?";
                        }

                        
                }

                if ( isset($_REQUEST['fecha-inicio']) && isset($_REQUEST['fecha-final']) && !isset($_REQUEST['dateRange']))
                {
                        array_push($params, $_REQUEST['fecha-inicio']);
                        array_push($params, $_REQUEST['fecha-final']);
                        $dateRange .= " AND date(`fecha`) BETWEEN ? AND ?";
                        $qry_select .= $dateRange;
                }

                $qry_select .= " GROUP BY `id_sucursal` ORDER BY `cantidad` DESC LIMIT 1";

                $listar = new listar($qry_select, $params);
                echo $listar->lista();

                return;
                
        }

        /*
        *       CLIENTE QUE COMPRA MAS
        */

        function clienteComprasTop(){
        
                $dateRange = "";
                $params = array();

                        
                //$qry_select = " SELECT `cliente`.`nombre`, SUM(`ventas`.`subtotal`) AS `Cantidad` FROM `ventas`, `cliente` WHERE `cliente`.`id_cliente` = `ventas`.`id_cliente` ";
		$qry_select = "SELECT `cliente`, SUM(`subtotal`) AS `cantidad` FROM `view_ventas`";

                if( isset( $_REQUEST['dateRange']) )
                {
                        $dateInterval = $_REQUEST['dateRange'];


                        //Escogemos el rango de tiempo para los datos (Semana, Mes, Año, Todos)        
                        $datesArray = getDateRange($dateInterval);      

                        if( $datesArray != false )
                        {
                                array_push($params, $datesArray[0]);
                                array_push($params, $datesArray[1]);

                                $qry_select .= " AND date(`fecha`) BETWEEN ? AND ?";
                        }
                        
                        
                }


                if ( isset($_REQUEST['fecha-inicio']) && isset($_REQUEST['fecha-final']) && !isset($_REQUEST['dateRange']))
                {
                        array_push($params, $_REQUEST['fecha-inicio']);
                        array_push($params, $_REQUEST['fecha-final']);
                        $dateRange .= " AND date(`fecha`) BETWEEN ? AND ?";
                        $qry_select .= $dateRange;
                }

                $qry_select .= " GROUP BY `id_cliente` ORDER BY `cantidad` DESC LIMIT 1";

                $listar = new listar($qry_select, $params);
                echo $listar->lista();

                return;
        }


        /*
        *       VENDEDOR MAS PRODUCTIVO EN UNA SUCURSAL ESPECIFICA
        */

        function vendedorMasProductivoSucursal(){

                if ( !isset($_REQUEST['id_sucursal']) )
                {
                        fail("Faltan parametros");
                        return;
                }
                
                $id_sucursal = $_REQUEST['id_sucursal'];
                $dateRange = "";
                $params = array($id_sucursal);

                //$qry_select = "SELECT `ventas`.`id_usuario`, `usuario`.`nombre`, SUM(`ventas`.`subtotal`) AS `Vendido` FROM `ventas`, `usuario` WHERE `ventas`.`id_usuario` = `usuario`.`id_usuario` AND `ventas`.`sucursal` = ?";                              
		$qry_select = "SELECT `usuario`, SUM(`subtotal`) AS `vendido` FROM `view_detalles`";


                if( isset( $_REQUEST['dateRange']) )
                {
                        $dateInterval = $_REQUEST['dateRange'];


                        //Escogemos el rango de tiempo para los datos (Semana, Mes, Año, Todos)        
                        $datesArray = getDateRange($dateInterval);      

                        if( $datesArray != false )
                        {
                                array_push($params, $datesArray[0]);
                                array_push($params, $datesArray[1]);

                                $qry_select .= " AND date(`fecha`) BETWEEN ? AND ?";
                        }
                        
                        
                }

                //Si existen los rangos de fechas, agregamos una linea al query para filtrar los resultados dentro de ese rango
                if ( isset($_REQUEST['fecha-inicio']) && isset($_REQUEST['fecha-final']) && !isset($_REQUEST['dateRange']))
                {
                        array_push($params, $_REQUEST['fecha-inicio']);
                        array_push($params, $_REQUEST['fecha-final']);
                        $dateRange .= " AND date(`fecha`) BETWEEN ? AND ?";
                        $qry_select .= $dateRange;
                }

                $qry_select .= " GROUP BY `id_usuario` ORDER BY `vendido` DESC LIMIT 1";

                $listar = new listar($qry_select, $params);
                echo $listar->lista();

                return;

        }

        /*
        *       PRODUCTO MAS VENDIDO EN UNA SUCURSAL
        */

        function productoMasVendidoSucursal(){
                

                if ( !isset($_REQUEST['id_sucursal']) )
                {
                        fail("Faltan parametros");
                        return;
                }
                
                $id_sucursal = $_REQUEST['id_sucursal'];
                
                $dateRange = "";
                $params = array($id_sucursal);

                        
                $qry_select = " SELECT `denominacion`, SUM(`cantidad`) AS `cantidad` FROM `view_detalle_venta` WHERE `id_sucursal` = ? ";


                if( isset( $_REQUEST['dateRange']) )
                {
                        $dateInterval = $_REQUEST['dateRange'];


                        //Escogemos el rango de tiempo para los datos (Semana, Mes, Año, Todos)        
                        $datesArray = getDateRange($dateInterval);      

                        if( $datesArray != false )
                        {
                                array_push($params, $datesArray[0]);
                                array_push($params, $datesArray[1]);

                                $qry_select .= " AND date(`fecha`) BETWEEN ? AND ?";
                        }

                        
                }

                if ( isset($_REQUEST['fecha-inicio']) && isset($_REQUEST['fecha-final']) && !isset($_REQUEST['dateRange']) )
                {
                        array_push($params, $_REQUEST['fecha-inicio']);
                        array_push($params, $_REQUEST['fecha-final']);
                        $dateRange .= " AND date(`fecha`) BETWEEN ? AND ?";
                        $qry_select .= $dateRange;
                }

                $qry_select .= " GROUP BY `id_producto` ORDER BY `cantidad` DESC LIMIT 1";

                $listar = new listar($qry_select, $params);
                echo $listar->lista();

                return;

        }

        
        /*
        *       CLIENTE QUE COMPRA MAS EN UNA SUCURSAL
        */

        function clienteComprasTopSucursal(){
        
                if ( !isset($_REQUEST['id_sucursal']) )
                {
                        fail("Faltan parametros");
                        return;
                }
                
                $id_sucursal = $_REQUEST['id_sucursal'];
                
                $dateRange = "";
                $params = array($id_sucursal);

                        
                //$qry_select = " SELECT `cliente`.`nombre`, SUM(`ventas`.`subtotal`) AS `Cantidad` FROM `ventas`, `cliente` WHERE `cliente`.`id_cliente` = `ventas`.`id_cliente` AND `ventas`.`sucursal` = ? ";
		$qry_select = "SELECT `cliente`, SUM(`subtotal`) AS `cantidad` FROM `view_ventas` WHERE `id_sucursal` = ? ";

                if( isset( $_REQUEST['dateRange']) )
                {
                        $dateInterval = $_REQUEST['dateRange'];


                        //Escogemos el rango de tiempo para los datos (Semana, Mes, Año, Todos)        
                        $datesArray = getDateRange($dateInterval);      

                        if( $datesArray != false )
                        {
                                array_push($params, $datesArray[0]);
                                array_push($params, $datesArray[1]);

                                $qry_select .= " AND date(`fecha`) BETWEEN ? AND ?";
                        }

                        
                }

                if ( isset($_REQUEST['fecha-inicio']) && isset($_REQUEST['fecha-final']) && !isset($_REQUEST['dateRange']) )
                {
                        array_push($params, $_REQUEST['fecha-inicio']);
                        array_push($params, $_REQUEST['fecha-final']);
                        $dateRange .= " AND date(`fecha`) BETWEEN ? AND ?";
                        $qry_select .= $dateRange;
                }

                $qry_select .= " GROUP BY `id_cliente` ORDER BY `cantidad` DESC LIMIT 1";

                $listar = new listar($qry_select, $params);
                echo $listar->lista();

                return;
        }

	/* CLIENTE QUE COMPRA MAS A CREDITO */

	function clienteComprasCreditoTop($conn)
	{

		$dateRange = "";
                $params = array();

                        
                //$qry_select = " SELECT `cliente`.`nombre`, SUM(`ventas`.`subtotal`) AS `Cantidad` FROM `ventas`, `cliente` WHERE `cliente`.`id_cliente` = `ventas`.`id_cliente` AND `ventas`.`sucursal` = ? ";
		$qry_select = "SELECT `cliente`, SUM(`subtotal`) AS `cantidad` FROM `view_ventas` WHERE `tipo_venta` = 'credito' ";

                if( isset( $_REQUEST['dateRange']) )
                {
                        $dateInterval = $_REQUEST['dateRange'];


                        //Escogemos el rango de tiempo para los datos (Semana, Mes, Año, Todos)        
                        $datesArray = getDateRange($dateInterval);      

                        if( $datesArray != false )
                        {
                                array_push($params, $datesArray[0]);
                                array_push($params, $datesArray[1]);

                                $qry_select .= " AND date(`fecha`) BETWEEN ? AND ?";
                        }

                        
                }

                if ( isset($_REQUEST['fecha-inicio']) && isset($_REQUEST['fecha-final']) && !isset($_REQUEST['dateRange']) )
                {
                        array_push($params, $_REQUEST['fecha-inicio']);
                        array_push($params, $_REQUEST['fecha-final']);
                        $dateRange .= " AND date(`fecha`) BETWEEN ? AND ?";
                        $qry_select .= $dateRange;
                }

                $qry_select .= " GROUP BY `id_cliente` ORDER BY `cantidad` DESC LIMIT 1";

                //$listar = new listar($qry_select, $params);
                //echo $listar->lista();

                try{	
	    	$result_vendedor = $conn->Execute($qry_select, $params);
		}catch(Exception $e){
		  echo $e->getMessage();
		  return;
		}

		$array_result = array();
		while($obj_user = $result_vendedor->fetchNextObject(false)) {
		    array_push($array_result, $obj_user);
		}

		echo "{ success: true, datos: ".json_encode($array_result)."}";
                return;	
	
	}


	/* CLIENTE QUE COMPRAS MAS AL CONTADO */
	function clienteComprasContadoTop($conn)
	{

		$dateRange = "";
                $params = array();

                        
                //$qry_select = " SELECT `cliente`.`nombre`, SUM(`ventas`.`subtotal`) AS `Cantidad` FROM `ventas`, `cliente` WHERE `cliente`.`id_cliente` = `ventas`.`id_cliente` AND `ventas`.`sucursal` = ? ";
		$qry_select = "SELECT `cliente`, SUM(`subtotal`) AS `cantidad` FROM `view_ventas` WHERE `tipo_venta` = 'contado' ";

                if( isset( $_REQUEST['dateRange']) )
                {
                        $dateInterval = $_REQUEST['dateRange'];


                        //Escogemos el rango de tiempo para los datos (Semana, Mes, Año, Todos)        
                        $datesArray = getDateRange($dateInterval);      

                        if( $datesArray != false )
                        {
                                array_push($params, $datesArray[0]);
                                array_push($params, $datesArray[1]);

                                $qry_select .= " AND date(`fecha`) BETWEEN ? AND ?";
                        }

                        
                }

                if ( isset($_REQUEST['fecha-inicio']) && isset($_REQUEST['fecha-final']) && !isset($_REQUEST['dateRange']) )
                {
                        array_push($params, $_REQUEST['fecha-inicio']);
                        array_push($params, $_REQUEST['fecha-final']);
                        $dateRange .= " AND date(`fecha`) BETWEEN ? AND ?";
                        $qry_select .= $dateRange;
                }

                $qry_select .= " GROUP BY `id_cliente` ORDER BY `cantidad` DESC LIMIT 1";

                //$listar = new listar($qry_select, $params);
                //echo $listar->lista();

		try{	
	    	$result_vendedor = $conn->Execute($qry_select, $params);
		}catch(Exception $e){
		  echo $e->getMessage();
		  return;
		}

		$array_result = array();
		while($obj_user = $result_vendedor->fetchNextObject(false)) {
		    array_push($array_result, $obj_user);
		}

		echo "{ success: true, datos: ".json_encode($array_result)."}";
                return;	


	}

	/* PRODUCTO MAS QUE GENERA MAS INGRESOS */


	function productoIngresosTop($conn)
	{

		
		$dateRange = "";
                $params = array();

                //Consulta para obtener el producto con el mayor numero de cantidad * precio        
		$qry_select = "SELECT `denominacion` AS `producto`, ROUND(SUM(`cantidad` * `precio`), 2) AS `ingresos` FROM `view_detalle_venta` ";

                if( isset( $_REQUEST['dateRange']) )
                {
                        $dateInterval = $_REQUEST['dateRange'];


                        //Escogemos el rango de tiempo para los datos (Semana, Mes, Año, Todos)        
                        $datesArray = getDateRange($dateInterval);      

                        if( $datesArray != false )
                        {
                                array_push($params, $datesArray[0]);
                                array_push($params, $datesArray[1]);

                                $qry_select .= " AND date(`fecha`) BETWEEN ? AND ?";
                        }

                        
                }

                if ( isset($_REQUEST['fecha-inicio']) && isset($_REQUEST['fecha-final']) && !isset($_REQUEST['dateRange']) )
                {
                        array_push($params, $_REQUEST['fecha-inicio']);
                        array_push($params, $_REQUEST['fecha-final']);
                        $dateRange .= " AND date(`fecha`) BETWEEN ? AND ?";
                        $qry_select .= $dateRange;
                }

                $qry_select .= " GROUP BY `id_producto` ORDER BY `ingresos` DESC LIMIT 1";

                //$listar = new listar($qry_select, $params);
                //echo $listar->lista();

		try{	
	    	$result_vendedor = $conn->Execute($qry_select, $params);
		}catch(Exception $e){
		  echo $e->getMessage();
		  return;
		}

		$array_result = array();
		while($obj_user = $result_vendedor->fetchNextObject(false)) {
		    array_push($array_result, $obj_user);
		}

		echo "{ success: true, datos: ".json_encode($array_result)."}";
                return;	
		

	}

	/* PRODUCTO EN EL QUE MAS SE GASTA (COMPRA) */

	function productoGastosTop()
	{

		
		$dateRange = "";
                $params = array();

                //Consulta para obtener el producto con el mayor numero de cantidad * precio        
		$qry_select = "SELECT `denominacion` AS `producto`, ROUND(SUM(`cantidad` * `precio`), 2) AS `gastos` FROM `view_detalle_compra` ";

                if( isset( $_REQUEST['dateRange']) )
                {
                        $dateInterval = $_REQUEST['dateRange'];


                        //Escogemos el rango de tiempo para los datos (Semana, Mes, Año, Todos)        
                        $datesArray = getDateRange($dateInterval);      

                        if( $datesArray != false )
                        {
                                array_push($params, $datesArray[0]);
                                array_push($params, $datesArray[1]);

                                $qry_select .= " AND date(`fecha`) BETWEEN ? AND ?";
                        }

                        
                }

                if ( isset($_REQUEST['fecha-inicio']) && isset($_REQUEST['fecha-final']) && !isset($_REQUEST['dateRange']) )
                {
                        array_push($params, $_REQUEST['fecha-inicio']);
                        array_push($params, $_REQUEST['fecha-final']);
                        $dateRange .= " AND date(`fecha`) BETWEEN ? AND ?";
                        $qry_select .= $dateRange;
                }

                $qry_select .= " GROUP BY `id_producto` ORDER BY `gastos` DESC LIMIT 1";

                //$listar = new listar($qry_select, $params);
                //echo $listar->lista();

		try{	
	    	$result_vendedor = $conn->Execute($qry_select, $params);
		}catch(Exception $e){
		  echo $e->getMessage();
		  return;
		}

		$array_result = array();
		while($obj_user = $result_vendedor->fetchNextObject(false)) {
		    array_push($array_result, $obj_user);
		}

		echo "{ success: true, datos: ".json_encode($array_result)."}";
                return;	
		
	
	}


        /***************************************************************************************************
                                REPORTES PARA GRAFICAS
        ***************************************************************************************************/

        /*
        *       UTILS PARA LOS REPORTES
        */ 

        //Obtenemos dos fechas dentro de un rango (semana, mes, año). Se refiere la ultima semana, mes, año.
        function getDateRangeGraphics($dateInterval){

                $datesArray = array();
                $params = array();
                $currentDate = getdate();
                $dateToday = $currentDate['year'].'-'.$currentDate['mon'].'-'.$currentDate['mday'];


                //**** ESTE SWITCH NOS REGRESA UN ARREGLO CON TODAS LAS FECHAS QUE QUEREMOS ANALIZAR******//
                switch($dateInterval)
                        {
                                case 'semana':  
                                                                                                
                                                //Hacemos esto porque el valor numerico para el domingo es 0, nosotros lo tomamos como 7
                                                if ( $currentDate['wday'] == 0)
                                                {
                                                        $weekControl = 7;
                                                }
                                                else
                                                {
                                                        $weekControl = $currentDate['wday'];
                                                }

                                                $daysDelta = $weekControl - 1; //Diferencia de dias que hay entre hoy y el lunes(1)
                                                
                                                $fecha = date_create( $dateToday );
                                                //Obtenemos la fecha del lunes de esa semana usando $daysDelta
                                                date_sub( $fecha, date_interval_create_from_date_string( $daysDelta.' days') ); 
                                                $dateMonday = date_format($fecha, 'Y-m-d');
                                                array_push($datesArray, $dateMonday);

                                                //Obtenemos la fecha de hoy y la mandamos a nuestro arreglo de fechas
                                                $fecha = date_create( $dateToday );
                                                $today = date_format($fecha, 'Y-m-d');
                                                array_push($datesArray, $today);


                                                break;
                                /************************************************************************/

                                case 'mes':     
                                                //Obtenemos la fecha del inicio de mes
                                                $startOfMonth = date_create( $currentDate['year'].'-'.$currentDate['mon'].'-01' );
                                                $startDate = date_format($startOfMonth, 'Y-m-d');
                                                array_push($datesArray, $startDate);

                                                //Obtenemos la fecha de hoy y la agregamos a nuestro arreglo de fechas
                                                $fecha = date_create( $dateToday );
                                                $today = date_format($fecha, 'Y-m-d');
                                                array_push($datesArray, $today);
                                                
                                                break;

                                /************************************************************************/

                                case 'year':    
                                                //Obtenemos la fecha del primer dia del año y lo metemos a un arreglo de fechas
                                                $fecha = date_create($currentDate['year'].'-01-01'); 
                                                $dateStart = date_format($fecha, 'Y-m-d');
                                                array_push($datesArray, $dateStart);


                                                $fecha = date_create( $dateToday );
                                                array_push($datesArray, date_format($fecha, 'Y-m-d')); //Agregamos la fecha de hoy porque sacara lo que lleva del mes actual
                                                
                                                

                                                break;
                                /************************************************************************/

                                default:        $datesArray = false;

                        }
                return $datesArray;
}


	//Genera la parte final de nuestro query, la parte donde filtra entre 2 fechas
        function betweenDatesQueryPart( $dateInterval, $table )
        {


                                /*
                                *       FORMAMOS EL QUERY PARA LA SEMANA
                                */
                                if ( $dateInterval == 'semana')
                                {
                                        $qry_select = " date(`fecha`) BETWEEN ? AND ?";
        
                                        $qry_select .= " GROUP BY DAYOFWEEK(`fecha` )";
                                }

                                /*
                                *       FORMAMOS EL QUERY PARA EL MES
                                */

                                if ( $dateInterval == 'mes')
                                {
                                        $qry_select = " date(`fecha`) BETWEEN ? AND ?";

                                        $qry_select .= " GROUP BY DAYOFMONTH(`fecha` )";


                                }
                                //var_dump($params);

                                if ($dateInterval == 'year')
                                {
                                        $qry_select = " date(`fecha`) BETWEEN ? AND ?";

                                        $qry_select .= " GROUP BY MONTH(`fecha` )";
                                }

                                
                                return $qry_select;


}

        /**
        * @params: interval
        *
        */
        
        function selectIntervalo( $interval, $table )
        {
                
                //La clausula SELECT, FROM y WHERE la agregamos nosotros, esta es la que cambia
                        switch( $interval ) 
                        {
                                case 'semana'   : $qry = "SELECT DAYOFWEEK(`fecha`) AS `x`, SUM(`subtotal`) AS `y`, DAYNAME(`fecha`) AS `label` FROM `".$table."`  ";
                                                break;
                                case 'mes'      : $qry = "SELECT DAYOFMONTH(`fecha`) AS `x`, SUM(`subtotal`) AS `y`, DAYOFMONTH(`fecha`) AS `label` FROM `".$table."` ";
                                                break;
                                case 'year'     : $qry = "SELECT MONTH(`fecha`) AS `x`, SUM(`subtotal`) AS `y`, MONTHNAME(`fecha`) AS `label` FROM `".$table."` ";
                                                break;
                                default: break;
                        }

                return $qry;
        
        }


        function selectEfectivoIntervalo( $interval, $table )
        {
                
                //La clausula SELECT, FROM y WHERE la agregamos nosotros, esta es la que cambia
                        switch( $interval ) 
                        {
                                case 'semana'   : $qry = "SELECT DAYOFWEEK(`fecha`) AS `x`, SUM(`monto`) AS `y`, DAYNAME(`fecha`) AS `label` FROM `".$table."`  ";
                                                break;
                                case 'mes'      : $qry = "SELECT DAYOFMONTH(`fecha`) AS `x`, SUM(`monto`) AS `y`, DAYOFMONTH(`fecha`) AS `label` FROM `".$table."` ";
                                                break;
                                case 'year'     : $qry = "SELECT MONTH(`fecha`) AS `x`, SUM(`monto`) AS `y`, MONTHNAME(`fecha`) AS `label` FROM `".$table."` ";
                                                break;
                                default: break;
                        }

                return $qry;
        
        }

	//Funcion que obtiene el inicio de una consulta para filtrar por semana, mes o año
	function selectDetallesIntervalo( $interval, $table )
        {
                
                //La clausula SELECT, FROM y WHERE la agregamos nosotros, esta es la que cambia
                        switch( $interval ) 
                        {
                                case 'semana'   : $qry = "SELECT DAYOFWEEK(`fecha`) AS `x`, ROUND(SUM(`cantidad` * `precio`), 2) AS `y`, DAYNAME(`fecha`) AS `label` FROM `".$table."`  ";
                                                break;
                                case 'mes'      : $qry = "SELECT DAYOFMONTH(`fecha`) AS `x`, ROUND(SUM(`cantidad` * `precio`), 2) AS `y`, DAYOFMONTH(`fecha`) AS `label` FROM `".$table."` ";
                                                break;
                                case 'year'     : $qry = "SELECT MONTH(`fecha`) AS `x`, ROUND(SUM(`cantidad` * `precio`), 2) AS `y`, MONTHNAME(`fecha`) AS `label` FROM `".$table."` ";
                                                break;
                                default: break;
                        }

                return $qry;
        
        }
	


        /***************************************
        *       TERMINA UTILS
        ****************************************/




        /*
        *       GRAFICA DE VENTAS EN GENERAL
        *
        *       params:
        *               'dateRange' puede ser ['semana', 'mes', year']
                        opcional 'fecha-inicio' y 'fecha-final' : tienen que ir juntos
                        opcional 'id_sucursal' : la sucursal de donde se sacaran los datos
        */
        function graficaVentas($conn){

                
                //$array = getDateRangeGraphics('semana');
                $params = array();

                if( isset( $_REQUEST['dateRange']) )
                {
                        $dateInterval = $_REQUEST['dateRange'];
                        $params = array();
                        //Obtenemos el select del query para obtener las ventas
                        $functionQuery = selectIntervalo($dateInterval, 'view_ventas');

                        //getDateRangeGraphics nos regresa un arreglo con todas las fechas para la consulta, y el ultimo dato es la consulta
                        $datesArray = getDateRangeGraphics($dateInterval);
                        $qry_select = betweenDatesQueryPart($dateInterval, 'view_ventas');
                        

                        //Si se escogieron las fechas manualmente, se sobreescriben
                        if ( isset( $_REQUEST['fecha-inicio']) && isset( $_REQUEST['fecha-final']) )
                        {
                                $datesArray[0] = $_REQUEST['fecha-inicio'];
                                $datesArray[1] = $_REQUEST['fecha-final'];
                        }
                        
                        if ( isset( $_REQUEST['id_sucursal'] ) )
                        {
                                $functionQuery .= " WHERE `id_sucursal` = ? AND ";
                                array_push( $params, $_REQUEST['id_sucursal'] );
                                array_push( $params, $datesArray[0] );
                                array_push( $params, $datesArray[1] );
                        }
                        else
                        {
                                $functionQuery .= " WHERE ";
                                array_push( $params, $datesArray[0] );
                                array_push( $params, $datesArray[1] );
                        }

                        //Formamos nuestro query completo
                        $completeQuery = $functionQuery . $qry_select ;

                        
                        if ( $qry_select != false )
                        {                       
                                //echo $qry_select;
                                
                                /*$listar = new listar( $completeQuery, $params);
                                echo $listar->lista();*/
				try{	
			    	$result_vendedor = $conn->Execute($completeQuery, $params);
				}catch(Exception $e){
				  echo $e->getMessage();
				  return;
				}

				$array_result = array();
				while($obj_user = $result_vendedor->fetchNextObject(false)) {
				    array_push($array_result, $obj_user);
				}

				echo "{ success: true, datos: ".json_encode($array_result)."}";


                                return;
                                
                        }
                        else
                        {
                                fail("Bad Request: dateRange");
                                return;
                        }

                }
                else
                {
                        fail('Faltan parametros');
                        return;
                }

        }
        

        /*
        *       GRAFICA DE VENTAS AL CONTADO 
        *
        *       params:
        *               'dateRange' puede ser ['semana', 'mes', year']
                        opcional 'fecha-inicio' y 'fecha-final' : tienen que ir juntos
                        opcional 'id_sucursal' : la sucursal de donde se sacaran los datos

        */
        function graficaVentasContado(){

                //$array = getDateRangeGraphics('semana');
                $params = array();

                if( isset( $_REQUEST['dateRange']) )
                {
                        $dateInterval = $_REQUEST['dateRange'];
                        $params = array();
                        //Obtenemos el select del query para obtener las ventas
                        $functionQuery = selectIntervalo($dateInterval, 'view_ventas');

                        //getDateRangeGraphics nos regresa un arreglo con todas las fechas para la consulta, y el ultimo dato es la consulta
                        $datesArray = getDateRangeGraphics($dateInterval);
                        $qry_select = betweenDatesQueryPart($dateInterval, 'view_ventas');

                        

                        //Si se escogieron las fechas manualmente, se sobreescriben
                        if ( isset( $_REQUEST['fecha-inicio']) && isset( $_REQUEST['fecha-final']) )
                        {
                                $datesArray[0] = $_REQUEST['fecha-inicio'];
                                $datesArray[1] = $_REQUEST['fecha-final'];
                        }
                        
                        //Si se mando el parametro id_sucursal lo agregamos a la clausula where
                        if ( isset( $_REQUEST['id_sucursal'] ) )
                        {
                                $functionQuery .= " WHERE `id_sucursal` = ? AND ";
                                array_push( $params, $_REQUEST['id_sucursal'] );
                                array_push( $params, $datesArray[0] );
                                array_push( $params, $datesArray[1] );
                        }
                        else
                        {
                                $functionQuery .= " WHERE ";
                                array_push( $params, $datesArray[0] );
                                array_push( $params, $datesArray[1] );
                        }

                        //Formamos nuestro query completo
                        $completeQuery = $functionQuery . " `tipo_venta` = 'contado' AND " . $qry_select ;

                        
                        if ( $qry_select != false )
                        {                       
                                //echo $qry_select;
                                
                                $listar = new listar( $completeQuery, $params);
                                echo $listar->lista();

                                return;
                                
                        }
                        else
                        {
                                fail("Bad Request: dateRange");
                                return;
                        }

                }
                else
                {
                        fail('Faltan parametros');
                        return;
                }

        }


        /*
        *       GRAFICA DE VENTAS A CREDITO
        *
        *       params:
        *               'dateRange' puede ser ['semana', 'mes', year']
                        opcional 'fecha-inicio' y 'fecha-final' : tienen que ir juntos
                        opcional 'id_sucursal' : la sucursal de donde se sacaran los datos

        */
        function graficaVentasCredito(){

                //$array = getDateRangeGraphics('semana');
                $params = array();

                if( isset( $_REQUEST['dateRange']) )
                {
                        $dateInterval = $_REQUEST['dateRange'];
                        $params = array();
                        //Obtenemos el select del query para obtener las ventas
                        $functionQuery = selectIntervalo($dateInterval, 'view_ventas');

                        //getDateRangeGraphics nos regresa un arreglo con todas las fechas para la consulta, y el ultimo dato es la consulta
                        $datesArray = getDateRangeGraphics($dateInterval);
                        $qry_select = betweenDatesQueryPart($dateInterval, 'view_ventas');
                        

                        //Si se escogieron las fechas manualmente, se sobreescriben
                        if ( isset( $_REQUEST['fecha-inicio']) && isset( $_REQUEST['fecha-final']) )
                        {
                                $datesArray[0] = $_REQUEST['fecha-inicio'];
                                $datesArray[1] = $_REQUEST['fecha-final'];
                        }
                        
                        //Si se mando el parametro id_sucursal lo agregamos a la clausula where
                        if ( isset( $_REQUEST['id_sucursal'] ) )
                        {
                                $functionQuery .= " WHERE `id_sucursal` = ? AND ";
                                array_push( $params, $_REQUEST['id_sucursal'] );
                                array_push( $params, $datesArray[0] );
                                array_push( $params, $datesArray[1] );
                        }
                        else
                        {
                                $functionQuery .= " WHERE ";
                                array_push( $params, $datesArray[0] );
                                array_push( $params, $datesArray[1] );
                        }

                        //Formamos nuestro query completo
                        $completeQuery = $functionQuery . " `tipo_venta` = 'credito' AND " . $qry_select ;

                        
                        if ( $qry_select != false )
                        {                       
                                //echo $qry_select;
                                
                                $listar = new listar( $completeQuery, $params);
                                echo $listar->lista();

                                return;
                                
                        }
                        else
                        {
                                fail("Bad Request: dateRange");
                                return;
                        }

                }
                else
                {
                        fail('Faltan parametros');
                        return;
                }

        }

        /*
        *       GRAFICA DE COMPRAS EN GENERAL
        *
        *       params:
        *               'dateRange' puede ser ['semana', 'mes', year']
                        opcional 'fecha-inicio' y 'fecha-final' : tienen que ir juntos
                        opcional 'id_sucursal' : la sucursal de donde se sacaran los datos

        */

        function graficaCompras()
        {

                $params = array();

                if( isset( $_REQUEST['dateRange']) )
                {
                        $dateInterval = $_REQUEST['dateRange'];
                        $params = array();
                        //Obtenemos el select del query para obtener las compras
                        $functionQuery = selectIntervalo($dateInterval, 'view_compras');

                        //getDateRangeGraphics nos regresa un arreglo con todas las fechas para la consulta, y el ultimo dato es la consulta
                        $datesArray = getDateRangeGraphics($dateInterval);
                        $qry_select = betweenDatesQueryPart($dateInterval, 'view_compras');
                        

                        //Si se escogieron las fechas manualmente, se sobreescriben
                        if ( isset( $_REQUEST['fecha-inicio']) && isset( $_REQUEST['fecha-final']) )
                        {
                                $datesArray[0] = $_REQUEST['fecha-inicio'];
                                $datesArray[1] = $_REQUEST['fecha-final'];
                        }
                        
                        if ( isset( $_REQUEST['id_sucursal'] ) )
                        {
                                $functionQuery .= " WHERE `id_sucursal` = ? AND ";
                                array_push( $params, $_REQUEST['id_sucursal'] );
                                array_push( $params, $datesArray[0] );
                                array_push( $params, $datesArray[1] );
                        }
                        else
                        {
                                $functionQuery .= " WHERE ";
                                array_push( $params, $datesArray[0] );
                                array_push( $params, $datesArray[1] );
                        }

                        //Formamos nuestro query completo
                        $completeQuery = $functionQuery . $qry_select ;

                        
                        if ( $qry_select != false )
                        {                       
                                //echo $qry_select;
                                
                                $listar = new listar( $completeQuery, $params);
                                echo $listar->lista();

                                return;
                                
                        }
                        else
                        {
                                fail("Bad Request: dateRange");
                                return;
                        }

                }
                else
                {
                        fail('Faltan parametros');
                        return;
                }
                
                
        }


        /*
        *       GRAFICA DE COMPRAS AL CONTADO 
        *
        *       params:
        *               'dateRange' puede ser ['semana', 'mes', year']
                        opcional 'fecha-inicio' y 'fecha-final' : tienen que ir juntos
                        opcional 'id_sucursal' : la sucursal de donde se sacaran los datos

        */
        function graficaComprasContado(){

                //$array = getDateRangeGraphics('semana');
                $params = array();

                if( isset( $_REQUEST['dateRange']) )
                {
                        $dateInterval = $_REQUEST['dateRange'];
                        $params = array();
                        //Obtenemos el select del query para obtener las ventas
                        $functionQuery = selectIntervalo($dateInterval, 'view_compras');

                        //getDateRangeGraphics nos regresa un arreglo con todas las fechas para la consulta, y el ultimo dato es la consulta
                        $datesArray = getDateRangeGraphics($dateInterval);
                        $qry_select = betweenDatesQueryPart($dateInterval, 'view_compras');

                        

                        //Si se escogieron las fechas manualmente, se sobreescriben
                        if ( isset( $_REQUEST['fecha-inicio']) && isset( $_REQUEST['fecha-final']) )
                        {
                                $datesArray[0] = $_REQUEST['fecha-inicio'];
                                $datesArray[1] = $_REQUEST['fecha-final'];
                        }
                        
                        //Si se mando el parametro id_sucursal lo agregamos a la clausula where
                        if ( isset( $_REQUEST['id_sucursal'] ) )
                        {
                                $functionQuery .= " WHERE `id_sucursal` = ? AND ";
                                array_push( $params, $_REQUEST['id_sucursal'] );
                                array_push( $params, $datesArray[0] );
                                array_push( $params, $datesArray[1] );
                        }
                        else
                        {
                                $functionQuery .= " WHERE ";
                                array_push( $params, $datesArray[0] );
                                array_push( $params, $datesArray[1] );
                        }

                        //Formamos nuestro query completo
                        $completeQuery = $functionQuery . " `tipo_compra` = 'contado' AND " . $qry_select ;

                        
                        if ( $qry_select != false )
                        {                       
                                //echo $qry_select;
                                
                                $listar = new listar( $completeQuery, $params);
                                echo $listar->lista();

                                return;
                                
                        }
                        else
                        {
                                fail("Bad Request: dateRange");
                                return;
                        }

                }
                else
                {
                        fail('Faltan parametros');
                        return;
                }

        }


        /*
        *       GRAFICA DE COMPRAS A CREDITO
        *
        *       params:
        *               'dateRange' puede ser ['semana', 'mes', year']
                        opcional 'fecha-inicio' y 'fecha-final' : tienen que ir juntos
                        opcional 'id_sucursal' : la sucursal de donde se sacaran los datos

        */
        function graficaComprasCredito(){

                //$array = getDateRangeGraphics('semana');
                $params = array();

                if( isset( $_REQUEST['dateRange']) )
                {
                        $dateInterval = $_REQUEST['dateRange'];
                        $params = array();
                        //Obtenemos el select del query para obtener las ventas
                        $functionQuery = selectIntervalo($dateInterval, 'view_compras');

                        //getDateRangeGraphics nos regresa un arreglo con todas las fechas para la consulta, y el ultimo dato es la consulta
                        $datesArray = getDateRangeGraphics($dateInterval);
                        $qry_select = betweenDatesQueryPart($dateInterval, 'view_compras');
                        

                        //Si se escogieron las fechas manualmente, se sobreescriben
                        if ( isset( $_REQUEST['fecha-inicio']) && isset( $_REQUEST['fecha-final']) )
                        {
                                $datesArray[0] = $_REQUEST['fecha-inicio'];
                                $datesArray[1] = $_REQUEST['fecha-final'];
                        }
                        
                        //Si se mando el parametro id_sucursal lo agregamos a la clausula where
                        if ( isset( $_REQUEST['id_sucursal'] ) )
                        {
                                $functionQuery .= " WHERE `id_sucursal` = ? AND ";
                                array_push( $params, $_REQUEST['id_sucursal'] );
                                array_push( $params, $datesArray[0] );
                                array_push( $params, $datesArray[1] );
                        }
                        else
                        {
                                $functionQuery .= " WHERE ";
                                array_push( $params, $datesArray[0] );
                                array_push( $params, $datesArray[1] );
                        }

                        //Formamos nuestro query completo
                        $completeQuery = $functionQuery . " `tipo_compra` = 'credito' AND " . $qry_select ;

                        
                        if ( $qry_select != false )
                        {                       
                                //echo $qry_select;
                                
                                $listar = new listar( $completeQuery, $params);
                                echo $listar->lista();

                                return;
                                
                        }
                        else
                        {
                                fail("Bad Request: dateRange");
                                return;
                        }

                }
                else
                {
                        fail('Faltan parametros');
                        return;
                }

        }

        /*
        *       GRAFICA DE GASTOS EN GENERAL
        *
        *       params:
        *               'dateRange' puede ser ['semana', 'mes', year']
                        opcional 'fecha-inicio' y 'fecha-final' : tienen que ir juntos
                        opcional 'id_sucursal' : la sucursal de donde se sacaran los datos

        */

        function graficaGastos()
        {

                $params = array();

                if( isset( $_REQUEST['dateRange']) )
                {
                        $dateInterval = $_REQUEST['dateRange'];
                        $params = array();
                        //Obtenemos el select del query para obtener el gasto
                        $functionQuery = selectEfectivoIntervalo($dateInterval, 'gastos');

                        //getDateRangeGraphics nos regresa un arreglo con todas las fechas para la consulta, y el ultimo dato es la consulta
                        $datesArray = getDateRangeGraphics($dateInterval);
                        $qry_select = betweenDatesQueryPart($dateInterval, 'gastos');
                        

                        //Si se escogieron las fechas manualmente, se sobreescriben
                        if ( isset( $_REQUEST['fecha-inicio']) && isset( $_REQUEST['fecha-final']) )
                        {
                                $datesArray[0] = $_REQUEST['fecha-inicio'];
                                $datesArray[1] = $_REQUEST['fecha-final'];
                        }
                        
                        if ( isset( $_REQUEST['id_sucursal'] ) )
                        {
                                $functionQuery .= " WHERE `id_sucursal` = ? AND ";
                                array_push( $params, $_REQUEST['id_sucursal'] );
                                array_push( $params, $datesArray[0] );
                                array_push( $params, $datesArray[1] );
                        }
                        else
                        {
                                $functionQuery .= " WHERE ";
                                array_push( $params, $datesArray[0] );
                                array_push( $params, $datesArray[1] );
                        }

                        //Formamos nuestro query completo
                        $completeQuery = $functionQuery . $qry_select ;

                        
                        if ( $qry_select != false )
                        {                       
                                //echo $qry_select;
                                
                                $listar = new listar( $completeQuery, $params);
                                echo $listar->lista();

                                return;
                                
                        }
                        else
                        {
                                fail("Bad Request: dateRange");
                                return;
                        }

                }
                else
                {
                        fail('Faltan parametros');
                        return;
                }
                
                
        }


        /*
        *       GRAFICA DE INGRESOS EN GENERAL
        *
        *       params:
        *               'dateRange' puede ser ['semana', 'mes', year']
                        opcional 'fecha-inicio' y 'fecha-final' : tienen que ir juntos
                        opcional 'id_sucursal' : la sucursal de donde se sacaran los datos

        */

        function graficaIngresos()
        {

                $params = array();

                if( isset( $_REQUEST['dateRange']) )
                {
                        $dateInterval = $_REQUEST['dateRange'];
                        $params = array();
                        //Obtenemos el select del query para obtener el gasto
                        $functionQuery = selectEfectivoIntervalo($dateInterval, 'ingresos');

                        //getDateRangeGraphics nos regresa un arreglo con todas las fechas para la consulta, y el ultimo dato es la consulta
                        $datesArray = getDateRangeGraphics($dateInterval);
                        $qry_select = betweenDatesQueryPart($dateInterval, 'ingresos');
                        

                        //Si se escogieron las fechas manualmente, se sobreescriben
                        if ( isset( $_REQUEST['fecha-inicio']) && isset( $_REQUEST['fecha-final']) )
                        {
                                $datesArray[0] = $_REQUEST['fecha-inicio'];
                                $datesArray[1] = $_REQUEST['fecha-final'];
                        }
                        
                        if ( isset( $_REQUEST['id_sucursal'] ) )
                        {
                                $functionQuery .= " WHERE `id_sucursal` = ? AND ";
                                array_push( $params, $_REQUEST['id_sucursal'] );
                                array_push( $params, $datesArray[0] );
                                array_push( $params, $datesArray[1] );
                        }
                        else
                        {
                                $functionQuery .= " WHERE ";
                                array_push( $params, $datesArray[0] );
                                array_push( $params, $datesArray[1] );
                        }

                        //Formamos nuestro query completo
                        $completeQuery = $functionQuery . $qry_select ;

                        
                        if ( $qry_select != false )
                        {                       
                                //echo $qry_select;
                                
                                $listar = new listar( $completeQuery, $params);
                                echo $listar->lista();

                                return;
                                
                        }
                        else
                        {
                                fail("Bad Request: dateRange");
                                return;
                        }

                }
                else
                {
                        fail('Faltan parametros');
                        return;
                }
                
                
        }

	
	/*
        *       GRAFICA DE LAS VENTAS HECHAS A ALGUN CLIENTE
        *       params:
        *               'dateRange' puede ser ['semana', 'mes', year']
			'id_cliente'
                        opcional 'fecha-inicio' y 'fecha-final' : tienen que ir juntos

        */

	function graficaVentasCliente($conn)
	{
		//$array = getDateRangeGraphics('semana');
                $params = array();

                if( isset( $_REQUEST['dateRange']) )
                {
                        $dateInterval = $_REQUEST['dateRange'];
                        $params = array();
                        //Obtenemos el select del query para obtener las ventas
                        $functionQuery = selectIntervalo($dateInterval, 'view_ventas');

                        //getDateRangeGraphics nos regresa un arreglo con todas las fechas para la consulta, y el ultimo dato es la consulta
                        $datesArray = getDateRangeGraphics($dateInterval);
                        $qry_select = betweenDatesQueryPart($dateInterval, 'view_ventas');
                        

                        //Si se escogieron las fechas manualmente, se sobreescriben
                        if ( isset( $_REQUEST['fecha-inicio']) && isset( $_REQUEST['fecha-final']) )
                        {
                                $datesArray[0] = $_REQUEST['fecha-inicio'];
                                $datesArray[1] = $_REQUEST['fecha-final'];
                        }
                        
                        if ( isset( $_REQUEST['id_cliente'] ) )
                        {
                                $functionQuery .= " WHERE `id_cliente` = ? AND ";
                                array_push( $params, $_REQUEST['id_cliente'] );
                                array_push( $params, $datesArray[0] );
                                array_push( $params, $datesArray[1] );
                        }
                        else
                        {
                                echo "{ success: false, error: 'Faltan parametros: Cliente'}";
				return;
                        }

                        //Formamos nuestro query completo
                        $completeQuery = $functionQuery . $qry_select ;

                        
                        if ( $qry_select != false )
                        {                       
                                //echo $qry_select;
                                
                                /*$listar = new listar( $completeQuery, $params);
                                echo $listar->lista();*/
				try{	
			    	$result_vendedor = $conn->Execute($completeQuery, $params);
				}catch(Exception $e){
				  echo $e->getMessage();
				  return;
				}

				$array_result = array();
				while($obj_user = $result_vendedor->fetchNextObject(false)) {
				    array_push($array_result, $obj_user);
				}

				echo "{ success: true, datos: ".json_encode($array_result)."}";


                                return;
                                
                        }
                        else
                        {
                                fail("Bad Request: dateRange");
                                return;
                        }

                }
                else
                {
                        fail('Faltan parametros');
                        return;
                }
	}



	/*
        *       GRAFICA DE LAS COMPRAS HECHAS A ALGUN PROVEEDOR
        *       params:
        *               'dateRange' puede ser ['semana', 'mes', year']
			'id_proveedor'
                        opcional 'fecha-inicio' y 'fecha-final' : tienen que ir juntos

        */

	function graficaComprasProveedor($conn)
	{
		//$array = getDateRangeGraphics('semana');
                $params = array();

                if( isset( $_REQUEST['dateRange']) )
                {
                        $dateInterval = $_REQUEST['dateRange'];
                        $params = array();
                        //Obtenemos el select del query para obtener las compras
                        $functionQuery = selectIntervalo($dateInterval, 'view_compras');

                        //getDateRangeGraphics nos regresa un arreglo con todas las fechas para la consulta, y el ultimo dato es la consulta
                        $datesArray = getDateRangeGraphics($dateInterval);
                        $qry_select = betweenDatesQueryPart($dateInterval, 'view_compras');
                        

                        //Si se escogieron las fechas manualmente, se sobreescriben
                        if ( isset( $_REQUEST['fecha-inicio']) && isset( $_REQUEST['fecha-final']) )
                        {
                                $datesArray[0] = $_REQUEST['fecha-inicio'];
                                $datesArray[1] = $_REQUEST['fecha-final'];
                        }
                        
                        if ( isset( $_REQUEST['id_proveedor'] ) )
                        {
                                $functionQuery .= " WHERE `id_proveedor` = ? AND ";
                                array_push( $params, $_REQUEST['id_cliente'] );
                                array_push( $params, $datesArray[0] );
                                array_push( $params, $datesArray[1] );
                        }
                        else
                        {
                                echo "{ success: false, error: 'Faltan parametros: Proveedor'}";
				return;
                        }

                        //Formamos nuestro query completo
                        $completeQuery = $functionQuery . $qry_select ;

                        
                        if ( $qry_select != false )
                        {                       
                                //echo $qry_select;
                                
                                /*$listar = new listar( $completeQuery, $params);
                                echo $listar->lista();*/
				try{	
			    	$result_vendedor = $conn->Execute($completeQuery, $params);
				}catch(Exception $e){
				  echo $e->getMessage();
				  return;
				}

				$array_result = array();
				while($obj_user = $result_vendedor->fetchNextObject(false)) {
				    array_push($array_result, $obj_user);
				}

				echo "{ success: true, datos: ".json_encode($array_result)."}";


                                return;
                                
                        }
                        else
                        {
                                fail("Bad Request: dateRange");
                                return;
                        }

                }
                else
                {
                        fail('Faltan parametros');
                        return;
                }
	}


	/*
        *       GRAFICA DE LAS VENTAS DE UN PRODUCTO
        *       params:
        *               'dateRange' puede ser ['semana', 'mes', year']
			'id_producto'
                        opcional 'fecha-inicio' y 'fecha-final' : tienen que ir juntos
			opcional 'tipo_venta'

        */

	function graficaVentasProducto($conn)
	{
		//$array = getDateRangeGraphics('semana');
                $params = array();

                if( isset( $_REQUEST['dateRange']) )
                {
                        $dateInterval = $_REQUEST['dateRange'];
                        $params = array();
                        //Obtenemos el select del query para obtener las ventas
                       $functionQuery = selectDetallesIntervalo($dateInterval, 'view_detalle_venta');
			
                        //getDateRangeGraphics nos regresa un arreglo con todas las fechas para la consulta, y el ultimo dato es la consulta
                        $datesArray = getDateRangeGraphics($dateInterval);
                        $qry_select = betweenDatesQueryPart($dateInterval, 'view_detalle_venta');
                        

                        //Si se escogieron las fechas manualmente, se sobreescriben
                        if ( isset( $_REQUEST['fecha-inicio']) && isset( $_REQUEST['fecha-final']) )
                        {
                                $datesArray[0] = $_REQUEST['fecha-inicio'];
                                $datesArray[1] = $_REQUEST['fecha-final'];
                        }
                        
			//Si se mando el id del producto se agrega a los parametros y se agrega la parte que corresponde a la consulta
			//sino se envia un error ya que este parametro es obligatorio
                        if ( isset( $_REQUEST['id_producto'] ) )
                        {
                                $functionQuery .= " WHERE `id_producto` = ? AND ";
                                array_push( $params, $_REQUEST['id_producto'] );
				
				//Si se mando el tipo de venta se agrega a la consulta y a los parametros
				if ( isset( $_REQUEST['tipo_venta'] ) )
				{
					$functionQuery .= " `tipo_venta` = ? AND ";
					array_push( $params, $_REQUEST['tipo_venta'] );
				}

                                array_push( $params, $datesArray[0] );
                                array_push( $params, $datesArray[1] );
                        }
                        else
                        {
                                echo "{ success: false, error: 'Faltan parametros: Producto'}";
				return;
                        }

			
                        //Formamos nuestro query completo
                        $completeQuery = $functionQuery . $qry_select ;

                        
                        if ( $qry_select != false )
                        {                       
                                //echo $qry_select;
                                
                                /*$listar = new listar( $completeQuery, $params);
                                echo $listar->lista();*/
				try{	
			    	$result_vendedor = $conn->Execute($completeQuery, $params);
				}catch(Exception $e){
				  echo $e->getMessage();
				  return;
				}

				$array_result = array();
				while($obj_user = $result_vendedor->fetchNextObject(false)) {
				    array_push($array_result, $obj_user);
				}

				echo "{ success: true, datos: ".json_encode($array_result)."}";


                                return;
                                
                        }
                        else
                        {
                                fail("Bad Request: dateRange");
                                return;
                        }

                }
                else
                {
                        fail('Faltan parametros');
                        return;
                }
	}

	
	/*
        *       GRAFICA DE LOS PRODUCTOS MAS VENDIDOS
        *       params:
        *               'dateRange' puede ser ['semana', 'mes', year']
			opcional 'id_sucursal'
                        opcional 'fecha-inicio' y 'fecha-final' : tienen que ir juntos
			opcional 'tipo_venta'

        */

	function graficaProductosMasVendidos($conn)
	{
		//$array = getDateRangeGraphics('semana');
                $params = array();

                if( isset( $_REQUEST['dateRange']) )
                {
                        $dateInterval = $_REQUEST['dateRange'];
                        $params = array();
                        //Obtenemos el select del query para obtener las ventas
                       $functionQuery = "SELECT `id_producto` AS `x`, ROUND(SUM(`cantidad` * `precio`)) AS `y`, `denominacion` AS `label` FROM `view_detalle_venta`  WHERE ";
			
                        //getDateRangeGraphics nos regresa un arreglo con todas las fechas para la consulta, y el ultimo dato es la consulta
                        $datesArray = getDateRangeGraphics($dateInterval);
                        //$qry_select = betweenDatesQueryPart($dateInterval, 'view_detalle_venta');
			$qry_select = " date(`fecha`) BETWEEN ? AND ? GROUP BY `id_producto` DESC";
                        

                        //Si se escogieron las fechas manualmente, se sobreescriben
                        if ( isset( $_REQUEST['fecha-inicio']) && isset( $_REQUEST['fecha-final']) )
                        {
                                $datesArray[0] = $_REQUEST['fecha-inicio'];
                                $datesArray[1] = $_REQUEST['fecha-final'];
                        }
                        
			//Si se mando el id del producto se agrega a los parametros y se agrega la parte que corresponde a la consulta
			//sino se envia un error ya que este parametro es obligatorio
                        if ( isset( $_REQUEST['id_sucursal'] ) )
                        {
                                $functionQuery .= " `id_sucursal` = ? AND ";
                                array_push( $params, $_REQUEST['id_sucursal'] );
				
                        }


			//Si se mando el tipo de venta se agrega a la consulta y a los parametros
			if ( isset( $_REQUEST['tipo_venta'] ) )
			{
				$functionQuery .= " `tipo_venta` = ? AND ";
				array_push( $params, $_REQUEST['tipo_venta'] );
			}

			//Agregamos las dos fechas devueltas a los parametros de la consulta
			array_push( $params, $datesArray[0] );
			array_push( $params, $datesArray[1] );

			
                        //Formamos nuestro query completo
                        $completeQuery = $functionQuery . $qry_select ;

                        
                        if ( $qry_select != false )
                        {                       
                                //echo $qry_select;
                                
                                /*$listar = new listar( $completeQuery, $params);
                                echo $listar->lista();*/
				try{	
			    	$result_vendedor = $conn->Execute($completeQuery, $params);
				}catch(Exception $e){
				  echo $e->getMessage();
				  return;
				}

				$array_result = array();
				while($obj_user = $result_vendedor->fetchNextObject(false)) {
				    array_push($array_result, $obj_user);
				}

				echo "{ success: true, datos: ".json_encode($array_result)."}";


                                return;
                                
                        }
                        else
                        {
                                fail("Bad Request: dateRange");
                                return;
                        }

                }
                else
                {
                        fail('Faltan parametros');
                        return;
                }
	}


	
	/*
        *       GRAFICA DE LOS PRODUCTOS MAS COMPRADOS
        *       params:
        *               'dateRange' puede ser ['semana', 'mes', year']
			opcional 'id_sucursal'
                        opcional 'fecha-inicio' y 'fecha-final' : tienen que ir juntos
			opcional 'tipo_compra'

        */

	function graficaProductosMasComprados($conn)
	{
		//$array = getDateRangeGraphics('semana');
                $params = array();

                if( isset( $_REQUEST['dateRange']) )
                {
                        $dateInterval = $_REQUEST['dateRange'];
                        $params = array();
                        //Obtenemos el select del query para obtener las ventas
                       $functionQuery = "SELECT `id_producto` AS `x`, ROUND(SUM(`cantidad` * `precio`)) AS `y`, `denominacion` AS `label` FROM `view_detalle_compra`  WHERE ";
			
                        //getDateRangeGraphics nos regresa un arreglo con todas las fechas para la consulta, y el ultimo dato es la consulta
                        $datesArray = getDateRangeGraphics($dateInterval);
                        //$qry_select = betweenDatesQueryPart($dateInterval, 'view_detalle_venta');
			$qry_select = " date(`fecha`) BETWEEN ? AND ? GROUP BY `id_producto` DESC";
                        

                        //Si se escogieron las fechas manualmente, se sobreescriben
                        if ( isset( $_REQUEST['fecha-inicio']) && isset( $_REQUEST['fecha-final']) )
                        {
                                $datesArray[0] = $_REQUEST['fecha-inicio'];
                                $datesArray[1] = $_REQUEST['fecha-final'];
                        }
                        
			//Si se mando el id del producto se agrega a los parametros y se agrega la parte que corresponde a la consulta
			//sino se envia un error ya que este parametro es obligatorio
                        if ( isset( $_REQUEST['id_sucursal'] ) )
                        {
                                $functionQuery .= " `id_sucursal` = ? AND ";
                                array_push( $params, $_REQUEST['id_sucursal'] );
				
                        }


			//Si se mando el tipo de venta se agrega a la consulta y a los parametros
			if ( isset( $_REQUEST['tipo_venta'] ) )
			{
				$functionQuery .= " `tipo_venta` = ? AND ";
				array_push( $params, $_REQUEST['tipo_venta'] );
			}

			//Agregamos las dos fechas devueltas a los parametros de la consulta
			array_push( $params, $datesArray[0] );
			array_push( $params, $datesArray[1] );

			
                        //Formamos nuestro query completo
                        $completeQuery = $functionQuery . $qry_select ;

                        
                        if ( $qry_select != false )
                        {                       
                                //echo $qry_select;
                                
                                /*$listar = new listar( $completeQuery, $params);
                                echo $listar->lista();*/
				try{	
			    	$result_vendedor = $conn->Execute($completeQuery, $params);
				}catch(Exception $e){
				  echo $e->getMessage();
				  return;
				}

				$array_result = array();
				while($obj_user = $result_vendedor->fetchNextObject(false)) {
				    array_push($array_result, $obj_user);
				}

				echo "{ success: true, datos: ".json_encode($array_result)."}";


                                return;
                                
                        }
                        else
                        {
                                fail("Bad Request: dateRange");
                                return;
                        }

                }
                else
                {
                        fail('Faltan parametros');
                        return;
                }
	}


	switch( $_REQUEST['method'] ){
	
			case 'vendedorMasProductivo':	vendedorMasProductivo($conn); break;
			case 'graficaVentas':		graficaVentas($conn); break;
			case 'clienteComprasContadoTop': clienteComprasContadoTop($conn); break;
			case 'clienteComprasCreditoTop' : clienteCompraCreditoTop($conn); break;
			case 'productoIngresosTop'	: productoIngresosTop($conn); break;
			case 'graficaVentasCliente'	: graficaVentasCliente($conn); break;
			case 'graficaVentasProducto'	: graficaVentasProducto($conn); break;
			case 'graficaProductosMasVendidos' : graficaProductosMasVendidos($conn); break;
			default:	echo 'Bad Request';

	}

?>
