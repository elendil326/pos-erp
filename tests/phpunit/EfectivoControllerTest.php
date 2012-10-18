<?php

    /**
      * Description:
      *     Unit tests for EfectivoController
      *
      * Author:
      *     Alan Gonzalez (alan)
      *
      ***/

require_once("../../server/bootstrap.php");


class ClientesControllerTests extends PHPUnit_Framework_TestCase 
{

    protected function setUp( )
    {
        ScenarioMaker::createUserAndLogin();
    }


    public function testAlgo( )
    {
        $this->assertNotEquals(1,1);
    }


}




class ScenarioMaker
{
    public static function createUserAndLogin( )
    {
        $r = SesionController::Iniciar( 123, 1, true );

        if ( $r["login_succesful"] == false )
        {
            global $POS_CONFIG;

            $POS_CONFIG["INSTANCE_CONN"]->Execute("INSERT INTO `usuario` (`id_usuario`, `id_direccion`, `id_direccion_alterna`, `id_sucursal`, `id_rol`, `id_clasificacion_cliente`, `id_clasificacion_proveedor`, `id_moneda`, `fecha_asignacion_rol`, `nombre`, `rfc`, `curp`, `comision_ventas`, `telefono_personal1`, `telefono_personal2`, `fecha_alta`, `fecha_baja`, `activo`, `limite_credito`, `descuento`, `password`, `last_login`, `consignatario`, `salario`, `correo_electronico`, `pagina_web`, `saldo_del_ejercicio`, `ventas_a_credito`, `representante_legal`, `facturar_a_terceros`, `dia_de_pago`, `mensajeria`, `intereses_moratorios`, `denominacion_comercial`, `dias_de_credito`, `cuenta_de_mensajeria`, `dia_de_revision`, `codigo_usuario`, `dias_de_embarque`, `tiempo_entrega`, `cuenta_bancaria`, `id_tarifa_compra`, `tarifa_compra_obtenida`, `id_tarifa_venta`, `tarifa_venta_obtenida`) VALUES
                (1, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2011-10-24 18:28:24', 'Administrador', NULL, NULL, NULL, NULL, NULL, '2011-10-24 18:28:34', NULL, 1, 0, NULL, '202cb962ac59075b964b07152d234b70', NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, 0, 'rol', 0, 'rol');");
            
            $r = SesionController::Iniciar( 123, 1, true );
        }
    }
}
