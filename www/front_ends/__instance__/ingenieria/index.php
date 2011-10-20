<?php

	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");
        $cargosyabonos = new CargosYAbonosController();
        $compras = new ComprasController();
        $empresas = new EmpresasController();
        $direcciones = new DireccionController();
        $sucursal_controller = new SucursalesController();

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
            0 => 1,
            1 => 2
        );

        $retenciones = array(
            0 => 1,
            1 => 2
        );

        $detalle_producto = array(
            array( "id_producto" => 1, "id_unidad" => 1, "cantidad" => 10, "descuento" => 0, "impuesto" => 0, "precio" => 10, "retencion" => 0 ),
            array( "id_producto" => 1, "id_unidad" => 2, "cantidad" => 10, "descuento" => 0, "impuesto" => 0, "precio" => 15, "retencion" => 0 )
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
        try
        {
            $sucursal_controller->VenderCaja(0, 2, 100, 0, 100, 0, "credito", NULL, $cheques, "tipo_pago", $billetes, $billetes_cambio, null, $detalle_producto, $detalle_orden, $detalle_paquete);
            /*
             *
             *
            $almacen=$sucursal_controller->ListaAlmacen(null,2);
            foreach($almacen as $a)
            {
                var_dump($a);
            }
             *
             *
            echo $sucursal_controller->NuevoAlmacen("Almacen 1", 3, 5, 1, NULL);
            *
            */

            /*
             * Pruebas para empresas
             *
            $empresas->Editar(5, 0, 0, $impuestos, $retenciones, "otra direccion web", 1, "otra razon social", "otro rfc", "otro codigo postal", "otra curp", "Otra calle", "un numero interno", "Un representante legal", "telefono", "Un numero exterio", "Colonia", "correo", "telefono2", "referencia");
             *
             *
            $empresas->Eliminar(1);
             *
             *
            echo $empresas->Nuevo("La joya", "38040", "curp", "razon social", "113", 1, "zefasdf", "zafiro", null, null, null, null, NULL, null, $retenciones, null, null, null,$impuestos);
             *
             *
             *
            $empresas->Agregar_sucursales(1, $sucursales);
            foreach($empresas->Lista(1) as $empresa)
            {
                var_dump($empresa);
            }
             * 
             *
            */

            /*
             * Pruebas para direcciones
             *
             
           echo $direcciones->NuevaDireccion("Zafiro", "113", "La Joya", 1, "38040", null, null, null, null);
             *
             *
            */


            /*
             *Pruebas para compras
             *
            $compras->Cancelar(1);
            echo $compras->Nueva_compra_arpilla(100, 10, 1000, 2, 1000, 0, 1, null, 1000, null, null);
            $c=$compras->Lista(null,null,null,null,null,null,null,null,null,null,null,0);
            foreach($c as $compra)
            {
                var_dump($compra);
            }
             *
             *
            */

            /*
             * Pruebas para Cargos y Abonos
             *
            $cargosyabonos->EditarAbono(2, "no se", "La nueva nota 2", 0, 0, 1);
              $ingresos=$cargosyabonos->ListaIngreso(1,"0000-00-00 00:00:00","2020-12-30 23:59:59",1,1,1,1,0,0,99999,1,"nota");
              foreach($ingresos as $ingreso)
              {
                    var_dump($ingreso);
              }
            echo $cargosyabonos->NuevoAbono(4000, "cheque", 2, null, $cheques, 0, 1, "NOTA", $billetes);
            $cargosyabonos->EditarIngreso(2, null, "descripcion", "folio", "nota", null, 100);
            $cargosyabonos->EditarGasto(3, "1999-05-01 00:00:00", 100, 5, "descripcion", "Nota", "FOLIO");
            echo $cargosyabonos->NuevoGasto("2011-10-01 00:00:00", 1, 0, 2, 1, 1, 5, 2, "folio", "nota");
            var_dump($cargosyabonos->ListaConceptoIngreso("nombre",1));
            var_dump($cargosyabonos->ListaConceptoGasto("monto",1));
            $cargosyabonos->EliminarConceptoIngreso(1);
            $cargosyabonos->EditarConceptoIngreso(8, "nombre 2", NULL, 10);
            echo $cargosyabonos->NuevoConceptoIngreso("nombre",null,"Descripciones");
            $cargosyabonos->EliminarConceptoGasto(3);
            $cargosyabonos->EditarConceptoGasto(3,"",1,"");
            echo $cargosyabonos->NuevoConceptoGasto("nombre");
            $cargosyabonos->EliminarIngreso(4, "Esto no existe");
              $gastos=$cargosyabonos->ListaGasto(null,null,null,null,null,null,null,null,null,null,null,null,1);
              foreach($gastos as $gasto)
              {
                    var_dump($gasto);
              }
              $cargosyabonos->EliminarGasto(1, "Que siempre no pagamos la luz dice el patron xD");
            $abonos= $cargosyabonos->ListaAbono(1, 0, 0, null, null, null, null, null, null, null, null, null, null, null, null, null, 0, null);
            if($abonos)
            foreach($abonos as $abono)
            {
                var_dump($abono);
            }
            $cargosyabonos->EliminarAbono(2, "se rajo el cliente", 0, 1, 0, 1, $billetes);
            $cargosyabonos->NuevoIngreso("2009-10-04", 1,1000);

             *
             *
             */
        }
        catch(Exception $e)
        {
            echo $e;
        }
        $c = new EmpresasController();
        $c->lista();
?>
