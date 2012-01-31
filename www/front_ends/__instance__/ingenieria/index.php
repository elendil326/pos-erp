<?php

	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");
        $billetes= array(
            array( "id_billete" => 1, "cantidad" => 10 ),
            array( "id_billete" => 2, "cantidad" => 10 ),
            array( "id_billete" => 3, "cantidad" => 10 ),
            array( "id_billete" => 4, "cantidad" => 10 )
            );

        $cheques= array(
            array( "nombre_banco" => "Bancomer", "monto" => 1000, "numero" => "1234", "expedido" => 0),
            array( "nombre_banco" => "Bancomer", "monto" => 1000, "numero" => "1234", "expedido" => 0),
            array( "nombre_banco" => "Bancomer", "monto" => 1000, "numero" => "1234", "expedido" => 0),
            array( "nombre_banco" => "Bancomer", "monto" => 1000, "numero" => "1234", "expedido" => 0)
        );

        $sucursales= array(
            array( "id_sucursal" => 1, "descuento" => null, "margen_utilidad" => null),
            array( "id_sucursal" => 2, "descuento" => null, "margen_utilidad" => null),
            array( "id_sucursal" => 3, "descuento" => null, "margen_utilidad" => null)
        );

        $impuestos = array(
            0 => 2
        );

        $retenciones = array(
            0 => 2
        );

        $detalle_producto = array(
            array( "id_producto" => 1, "id_unidad" => 3, "cantidad" => 5, "descuento" => 0, "impuesto" => 0, "precio" => 10, "retencion" => 0 ),
            array( "id_producto" => 1, "id_unidad" => 4, "cantidad" => 5, "descuento" => 0, "impuesto" => 0, "precio" => 15, "retencion" => 0 )
        );

        $detalle_paquete = array(
            array( "id_paquete" => 1, "cantidad" => 1, "descuento" => 0, "precio" => 9),
            array( "id_paquete" => 2, "cantidad" => 1, "descuento" => 0, "precio" => 11)
        );

        $detalle_orden = array(
            array( "id_orden_de_servicio" => 1, "descuento" => 0, "impuesto" => 0, "precio" => 5, "retencion" => 0),
            array( "id_orden_de_servicio" => 2, "descuento" => 0, "impuesto" => 0, "precio" => 6, "retencion" => 0)
        );

        $billetes_cambio = array(
            array( "id_billete" => 5, "cantidad" => 10 ),
            array( "id_billete" => 6, "cantidad" => 10 )
        );

        $productos_almacen = array(
            new ProductoAlmacen(array("id_producto" => 10, "id_almacen" => 20, "id_unidad" => 15, "cantidad" => 0)),
            new ProductoAlmacen(array("id_producto" => 1, "id_almacen" => 200, "id_unidad" => 150, "cantidad" => 100)),
            new ProductoAlmacen(array("id_producto" => 100, "id_almacen" => 2, "id_unidad" => 5, "cantidad" => 10))
        );

        $empresa = array(
            array( "id_empresa" => 1, "descuento" => 10, "margen_utilidad" => 100 ),
            array( "id_empresa" => 2, "descuento" => 0, "margen_utilidad" => null )
        );

        $productos = array(
          array( "id_producto" => 6, "id_unidad" => 2, "cantidad" => 10 )
        );
        
        $servicios = array( 
            array( "id_servicio" => 1, "cantidad" => 20 ) 
        );

        $impresoras = array(1,2);
        
        $emp = array(
            array( "id_empresa" => 1, "precio_utilidad" => 100, "es_margen_utilidad" => 0 )  , 
            array( "id_empresa" => 3, "precio_utilidad" => 100, "es_margen_utilidad" => 0 )  
        );
        
        $suc = array(
            array( "id_sucursal" => 1, "precio_utilidad" => 100, "es_margen_utilidad" => 0 )  ,
            array( "id_sucursal" => 6, "precio_utilidad" => 100, "es_margen_utilidad" => 0 )  
        );
        
        $servicios_precios_utilidad = array (
            array( "id_servicio" => 1 , "precio_utilidad" => 10, "es_margen_utilidad" => 0 ),
            array( "id_servicio" => 2 , "precio_utilidad" => 100, "es_margen_utilidad" => 0 ),
            array( "id_servicio" => 3 , "precio_utilidad" => 10, "es_margen_utilidad" => 0 ),
        );
        
        $productos_precios_utilidad = array (
            array( "id_producto" => 1 , "precio_utilidad" => 10, "es_margen_utilidad" => 0 ),
            array( "id_producto" => 2 , "precio_utilidad" => 100, "es_margen_utilidad" => 0 ),
            array( "id_producto" => 4 , "precio_utilidad" => 10, "es_margen_utilidad" => 0 ),
        );
        
        $paquetes_precios_utilidad = array (
            array( "id_paquete" => 1 , "precio_utilidad" => 10, "es_margen_utilidad" => 0 ),
            array( "id_paquete" => 2 , "precio_utilidad" => 100, "es_margen_utilidad" => 0 ),
            array( "id_paquete" => 10 , "precio_utilidad" => 10, "es_margen_utilidad" => 0 ),
        );
        
        $formulas = array(
          array( "secuencia" => 10,"cantidad_minima" => 10 ),
          array( "secuencia" => 20,"cantidad_minima" => 20)
        );
        
        $serv = array( 2 );
        
        $prod = array( 1 );
        
        $paq = array( 2 );
        
        $clasificacion_producto = array( 1,2 );
        $clasificacion_servicio = array( 1,3 );
        try
        {
            
            var_dump(ClientesController::Nuevo("Andres"));
            
//            $tarifas = TarifaDAO::obtenerTarifasActuales("compra");
//            var_dump($tarifas[0]["reglas"]);
//            var_dump(ReglaDAO::aplicarReglas($tarifas[0]["reglas"],ProductoDAO::getByPK(17)));
            
//            $string="202cb962ac59075b964b07152d234b70()-.";
//             if(preg_match('/[^a-zA-Z0-9\(\)\-]/',$string))
//                     echo 0;
//             else
//                 echo 1;
        }
        catch(Exception $e)
        {
            echo $e;
        }
?>
