<?php
require_once("../../server/bootstrap.php");

class CategoriasUdmTest extends PHPUnit_Framework_TestCase {
	public static function setUpBeforeClass() {
        SesionController::Iniciar(123, 1, true);
    }

    // - - Buscar - - //
    public function testBuscarTodas() {
    	$resultado = ProductosController::BuscarUnidadUdm();
    	$this->assertEquals(count($resultado['resultados']), 7);
    }

    public function testBuscarConQuery() {
    	$resultado = ProductosController::BuscarUnidadUdm('m');
        $unidades = $resultado['resultados'];
        $this->assertEquals(count($unidades), 6);
    }

    // - - Nueva - - //
    /**
    * @expectedException InvalidArgumentException
    */
    public function testNuevaAbreviacionVacia() {
        ProductosController::NuevaUnidadUdm('', 'desc', 1, 1, "Referencia UdM para esta categoria");
    }

    /**
    * @expectedException InvalidArgumentException
    */
    public function testNuevaDescripcionVacia() {
        ProductosController::NuevaUnidadUdm('abre', '', 1, 1, "Referencia UdM para esta categoria");
    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaAbreviacionYaExiste() {
        ProductosController::NuevaUnidadUdm('m', 'd', 1, 1, "Referencia UdM para esta categoria");
    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaDescripcionYaExiste() {
        ProductosController::NuevaUnidadUdm('z', 'Metro', 1, 1, "Referencia UdM para esta categoria");
    }

    /**
    * @expectedException InvalidDatabaseOperationException
    */
    public function testNuevaCategoriaNoExiste() {
        ProductosController::NuevaUnidadUdm('a', 'd', 1, 90, "Referencia UdM para esta categoria");
    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaFactorCero() {
        ProductosController::NuevaUnidadUdm('a', 'd', 0, 1, "Referencia UdM para esta categoria");
    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaFactorNegativo() {
        ProductosController::NuevaUnidadUdm('a', 'd', -1, 1, "Referencia UdM para esta categoria");
    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaTipoInvalido() {
        ProductosController::NuevaUnidadUdm('a', 'd', 1, 1, "XD");
    }

    public function testNuevaReferencia() {
        $resultado = ProductosController::NuevaUnidadUdm('a', 'd', 90, 1, "Referencia UdM para esta categoria");
        $id_unidad = $resultado['id_unidad_medida'];
        $unidad = UnidadMedidaDAO::getByPK($id_unidad);
        $this->assertEquals($unidad->getFactorConversion(), 1);
        UnidadMedidaDAO::delete($unidad);
    }

    public function testNuevaMenor() {
        $resultado = ProductosController::NuevaUnidadUdm('a', 'd', 10, 1, "Menor que la UdM de referencia");
        $id_unidad = $resultado['id_unidad_medida'];
        $unidad = UnidadMedidaDAO::getByPK($id_unidad);
        $this->assertEquals($unidad->getFactorConversion(), 0.1);
        UnidadMedidaDAO::delete($unidad);
    }

    public function testDetalles() {
        $resultado = ProductosController::DetallesUnidadUdm(1);
        $this->assertNotNull($resultado['unidad_medida']);
    }
}