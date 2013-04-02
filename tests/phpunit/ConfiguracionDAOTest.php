<?php
require_once("../../server/bootstrap.php");

class ConfiguracionDAOTest extends PHPUnit_Framework_TestCase
{
    private $configuracion;
    private $registros;

    protected function setUp()
    {
        SesionController::Iniciar(123, 1, true);
        $this->configuracion = new Configuracion(array(
            'descripcion' => 'productos_visibles_en_vc'
        ));
        ConfiguracionDAO::GuardarConfigDeVC(true, array(), 1);
        $this->registros = ConfiguracionDAO::search($this->configuracion);
    }

    public function testCrearSiNoExiste()
    {
        $this->assertEquals(count($this->registros), 1);
    }

    public function testActualizarSiYaExiste()
    {
        $conf1 = $this->registros[0];

        ConfiguracionDAO::GuardarConfigDeVC(false, array(), 1);
        $this->registros = ConfiguracionDAO::search($this->configuracion);
        $conf2 = $this->registros[0];

        $this->assertEquals(count($this->registros), 1);
        $this->assertNotEquals($conf1->getValor(), $conf2->getValor());
    }

    public function testJsonValido()
    {
        $configuracion = $this->registros[0];
        $json = $configuracion->getValor();
        $this->assertNotNull(json_decode($json));
    }

    public function testPropiedadMostrarEnElJson()
    {
        $conf = $this->registros[0];
        $valor = json_decode($conf->getValor());
        $this->assertObjectHasAttribute('mostrar', $valor);
        $this->assertInternalType('int', $valor->mostrar);
    }

    protected function tearDown()
    {
        $configuraciones = ConfiguracionDAO::search($this->configuracion);
        $configuracion = $configuraciones[0];
        ConfiguracionDAO::delete($configuracion);
    }
}
?>