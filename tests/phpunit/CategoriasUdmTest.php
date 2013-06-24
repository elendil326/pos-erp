<?php
require_once("../../server/bootstrap.php");

class CategoriasUdmTest extends PHPUnit_Framework_TestCase {
	protected function setUp()
    {
        SesionController::Iniciar(123, 1, true);
        CategoriaUnidadMedidaDAOBase::save(
            new CategoriaUnidadMedida(array(
                'descripcion' => 'Peso',
                'activa' => true
            ))
        );
        CategoriaUnidadMedidaDAOBase::save(
            new CategoriaUnidadMedida(array(
                'descripcion' => 'Tiempo',
                'activa' => false
            ))
        );
        CategoriaUnidadMedidaDAOBase::save(
            new CategoriaUnidadMedida(array(
                'descripcion' => 'Distancia',
                'activa' => true
            ))
        );
        CategoriaUnidadMedidaDAOBase::save(
            new CategoriaUnidadMedida(array(
                'descripcion' => 'Omo',
                'activa' => false
            ))
        );
    }

    // - - Buscar - - //
    public function testBuscarTodos() {
        $this->buscar($activa=null, $query=null, $qty=4);
    }

    public function testBuscarActivos() {
        $this->buscar($activa=true, $query=null, $qty=2);
    }

    public function testBuscarInactivos() {
        $this->buscar($activa=false, $query=null, $qty=2);
    }

    public function testBuscarDescripcionCoincideUna() {
        $this->buscar($activa=null, $query='Peso', $qty=1);
    }

    public function testBuscarDescripcionCoincidenVarias() {
        $this->buscar($activa=null, $query='e', $qty=2);
    }

    public function testBuscarDescripcionNoCoincide() {
        $this->buscar($activa=null, $query='X', $qty=0);
    }

    public function testBuscarActivosCoincideUna() {
        $this->buscar($activa=true, $query='Peso', $qty=1);
    }

    public function testBuscarActivosCoincidenVarias() {
        $this->buscar($activa=true, $query='s', $qty=2);
    }

    public function testBuscarActivosNoCoincide() {
        $this->buscar($activa=true, $query='X', $qty=0);
    }

    public function testBuscarInactivosCoincideUna() {
        $this->buscar($activa=false, $query='Omo', $qty=1);
    }

    public function testBuscarInactivosCoincidenVarias() {
        $this->buscar($activa=false, $query='m', $qty=2);
    }

    public function testBuscarInactivosNoCoincide() {
        $this->buscar($activa=false, $query='X', $qty=0);
    }

    private function buscar($activa, $query, $qty) {
        $resultado = ProductosController::BuscarCategoriaUdm($activa=$activa, $descripcion=$query);
        $categorias = $resultado['resultados'];
        $this->assertEquals(count($categorias), $qty);
    }

    protected function tearDown()
    {
        $categorias = CategoriaUnidadMedidaDAO::getAll();
        foreach ($categorias as $categoria) {
            CategoriaUnidadMedidaDAO::delete($categoria);
        }
    }
}