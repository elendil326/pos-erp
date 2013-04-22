<?php
/** Value Object file for table cuenta_contable.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Anonymous
  * @access public
  * @package docs
  * 
  */

class CuentaContable extends VO
{
	/**
	  * Constructor de CuentaContable
	  * 
	  * Para construir un objeto de tipo CuentaContable debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return CuentaContable
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_cuenta_contable']) ){
				$this->id_cuenta_contable = $data['id_cuenta_contable'];
			}
			if( isset($data['clave']) ){
				$this->clave = $data['clave'];
			}
			if( isset($data['nivel']) ){
				$this->nivel = $data['nivel'];
			}
			if( isset($data['consecutivo_en_nivel']) ){
				$this->consecutivo_en_nivel = $data['consecutivo_en_nivel'];
			}
			if( isset($data['nombre_cuenta']) ){
				$this->nombre_cuenta = $data['nombre_cuenta'];
			}
			if( isset($data['tipo_cuenta']) ){
				$this->tipo_cuenta = $data['tipo_cuenta'];
			}
			if( isset($data['naturaleza']) ){
				$this->naturaleza = $data['naturaleza'];
			}
			if( isset($data['clasificacion']) ){
				$this->clasificacion = $data['clasificacion'];
			}
			if( isset($data['cargos_aumentan']) ){
				$this->cargos_aumentan = $data['cargos_aumentan'];
			}
			if( isset($data['abonos_aumentan']) ){
				$this->abonos_aumentan = $data['abonos_aumentan'];
			}
			if( isset($data['es_cuenta_orden']) ){
				$this->es_cuenta_orden = $data['es_cuenta_orden'];
			}
			if( isset($data['es_cuenta_mayor']) ){
				$this->es_cuenta_mayor = $data['es_cuenta_mayor'];
			}
			if( isset($data['afectable']) ){
				$this->afectable = $data['afectable'];
			}
			if( isset($data['id_cuenta_padre']) ){
				$this->id_cuenta_padre = $data['id_cuenta_padre'];
			}
			if( isset($data['activa']) ){
				$this->activa = $data['activa'];
			}
			if( isset($data['id_catalogo_cuentas']) ){
				$this->id_catalogo_cuentas = $data['id_catalogo_cuentas'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto CuentaContable en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_cuenta_contable" => $this->id_cuenta_contable,
			"clave" => $this->clave,
			"nivel" => $this->nivel,
			"consecutivo_en_nivel" => $this->consecutivo_en_nivel,
			"nombre_cuenta" => $this->nombre_cuenta,
			"tipo_cuenta" => $this->tipo_cuenta,
			"naturaleza" => $this->naturaleza,
			"clasificacion" => $this->clasificacion,
			"cargos_aumentan" => $this->cargos_aumentan,
			"abonos_aumentan" => $this->abonos_aumentan,
			"es_cuenta_orden" => $this->es_cuenta_orden,
			"es_cuenta_mayor" => $this->es_cuenta_mayor,
			"afectable" => $this->afectable,
			"id_cuenta_padre" => $this->id_cuenta_padre,
			"activa" => $this->activa,
			"id_catalogo_cuentas" => $this->id_catalogo_cuentas
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_cuenta_contable
	  * 
	  *  [Campo no documentado]<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access public
	  * @var int(11)
	  */
	public $id_cuenta_contable;

	/**
	  * clave
	  * 
	  * La clave que se le darÃ¡ a la nueva cuenta contable<br>
	  * @access public
	  * @var varchar(45)
	  */
	public $clave;

	/**
	  * nivel
	  * 
	  * Nivel de profundidad que tendra la cuenta en el arbol de cuentas<br>
	  * @access public
	  * @var int(11)
	  */
	public $nivel;

	/**
	  * consecutivo_en_nivel
	  * 
	  * Dependiendo del nivel de profundidad de la cuenta contable, este valor indicara dentro de su nivel que numero consecutivo le corresponde con respecto a las mismas que estan en su mismo nivel<br>
	  * @access public
	  * @var int(11)
	  */
	public $consecutivo_en_nivel;

	/**
	  * nombre_cuenta
	  * 
	  * El nombre de la cuenta<br>
	  * @access public
	  * @var varchar(100)
	  */
	public $nombre_cuenta;

	/**
	  * tipo_cuenta
	  * 
	  * Si la cuenta es de Balance o Estado de Resultados<br>
	  * @access public
	  * @var enum('Balance','Estado
	  */
	public $tipo_cuenta;

	/**
	  * naturaleza
	  * 
	  * Si es deudora o acreedora<br>
	  * @access public
	  * @var enum('Acreedora','Deudora')
	  */
	public $naturaleza;

	/**
	  * clasificacion
	  * 
	  * Clasificacion a la que pertenecera la cuenta<br>
	  * @access public
	  * @var enum('Activo
	  */
	public $clasificacion;

	/**
	  * cargos_aumentan
	  * 
	  * Si es igual 1 significa que en los movimientos cuando se cargue a esta cuenta los cargos aumentaran<br>
	  * @access public
	  * @var tinyint(1)
	  */
	public $cargos_aumentan;

	/**
	  * abonos_aumentan
	  * 
	  * si abonos aumentan es igual a 1 significa que en los movimientos los abonos aumentantaran<br>
	  * @access public
	  * @var tinyint(1)
	  */
	public $abonos_aumentan;

	/**
	  * es_cuenta_orden
	  * 
	  * si la cuenta no se contemplara en los estados financieros<br>
	  * @access public
	  * @var tinyint(1)
	  */
	public $es_cuenta_orden;

	/**
	  * es_cuenta_mayor
	  * 
	  * Indica si la cuenta es de mayor<br>
	  * @access public
	  * @var tinyint(1)
	  */
	public $es_cuenta_mayor;

	/**
	  * afectable
	  * 
	  * indica si sobre esta cuenta ya se pueden realizar operaciones<br>
	  * @access public
	  * @var tinyint(1)
	  */
	public $afectable;

	/**
	  * id_cuenta_padre
	  * 
	  * id de la cuenta de la que depende<br>
	  * @access public
	  * @var int(11)
	  */
	public $id_cuenta_padre;

	/**
	  * activa
	  * 
	  * Indica si la cuenta estÃ¡ disponible para su uso o no.<br>
	  * @access public
	  * @var tinyint(1)
	  */
	public $activa;

	/**
	  * id_catalogo_cuentas
	  * 
	  * Id del catalogo de cuentas al que pertenece esta cuenta<br>
	  * @access public
	  * @var int(11)
	  */
	public $id_catalogo_cuentas;

	/**
	  * getIdCuentaContable
	  * 
	  * Get the <i>id_cuenta_contable</i> property for this object. Donde <i>id_cuenta_contable</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	final public function getIdCuentaContable()
	{
		return $this->id_cuenta_contable;
	}

	/**
	  * setIdCuentaContable( $id_cuenta_contable )
	  * 
	  * Set the <i>id_cuenta_contable</i> property for this object. Donde <i>id_cuenta_contable</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_cuenta_contable</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es de <b>Auto Incremento</b> !<br>
	  * No deberias usar setIdCuentaContable( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdCuentaContable( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdCuentaContable( $id_cuenta_contable )
	{
		$this->id_cuenta_contable = $id_cuenta_contable;
	}

	/**
	  * getClave
	  * 
	  * Get the <i>clave</i> property for this object. Donde <i>clave</i> es La clave que se le darÃ¡ a la nueva cuenta contable
	  * @return varchar(45)
	  */
	final public function getClave()
	{
		return $this->clave;
	}

	/**
	  * setClave( $clave )
	  * 
	  * Set the <i>clave</i> property for this object. Donde <i>clave</i> es La clave que se le darÃ¡ a la nueva cuenta contable.
	  * Una validacion basica se hara aqui para comprobar que <i>clave</i> es de tipo <i>varchar(45)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(45)
	  */
	final public function setClave( $clave )
	{
		$this->clave = $clave;
	}

	/**
	  * getNivel
	  * 
	  * Get the <i>nivel</i> property for this object. Donde <i>nivel</i> es Nivel de profundidad que tendra la cuenta en el arbol de cuentas
	  * @return int(11)
	  */
	final public function getNivel()
	{
		return $this->nivel;
	}

	/**
	  * setNivel( $nivel )
	  * 
	  * Set the <i>nivel</i> property for this object. Donde <i>nivel</i> es Nivel de profundidad que tendra la cuenta en el arbol de cuentas.
	  * Una validacion basica se hara aqui para comprobar que <i>nivel</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setNivel( $nivel )
	{
		$this->nivel = $nivel;
	}

	/**
	  * getConsecutivoEnNivel
	  * 
	  * Get the <i>consecutivo_en_nivel</i> property for this object. Donde <i>consecutivo_en_nivel</i> es Dependiendo del nivel de profundidad de la cuenta contable, este valor indicara dentro de su nivel que numero consecutivo le corresponde con respecto a las mismas que estan en su mismo nivel
	  * @return int(11)
	  */
	final public function getConsecutivoEnNivel()
	{
		return $this->consecutivo_en_nivel;
	}

	/**
	  * setConsecutivoEnNivel( $consecutivo_en_nivel )
	  * 
	  * Set the <i>consecutivo_en_nivel</i> property for this object. Donde <i>consecutivo_en_nivel</i> es Dependiendo del nivel de profundidad de la cuenta contable, este valor indicara dentro de su nivel que numero consecutivo le corresponde con respecto a las mismas que estan en su mismo nivel.
	  * Una validacion basica se hara aqui para comprobar que <i>consecutivo_en_nivel</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setConsecutivoEnNivel( $consecutivo_en_nivel )
	{
		$this->consecutivo_en_nivel = $consecutivo_en_nivel;
	}

	/**
	  * getNombreCuenta
	  * 
	  * Get the <i>nombre_cuenta</i> property for this object. Donde <i>nombre_cuenta</i> es El nombre de la cuenta
	  * @return varchar(100)
	  */
	final public function getNombreCuenta()
	{
		return $this->nombre_cuenta;
	}

	/**
	  * setNombreCuenta( $nombre_cuenta )
	  * 
	  * Set the <i>nombre_cuenta</i> property for this object. Donde <i>nombre_cuenta</i> es El nombre de la cuenta.
	  * Una validacion basica se hara aqui para comprobar que <i>nombre_cuenta</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	final public function setNombreCuenta( $nombre_cuenta )
	{
		$this->nombre_cuenta = $nombre_cuenta;
	}

	/**
	  * getTipoCuenta
	  * 
	  * Get the <i>tipo_cuenta</i> property for this object. Donde <i>tipo_cuenta</i> es Si la cuenta es de Balance o Estado de Resultados
	  * @return enum('Balance','Estado
	  */
	final public function getTipoCuenta()
	{
		return $this->tipo_cuenta;
	}

	/**
	  * setTipoCuenta( $tipo_cuenta )
	  * 
	  * Set the <i>tipo_cuenta</i> property for this object. Donde <i>tipo_cuenta</i> es Si la cuenta es de Balance o Estado de Resultados.
	  * Una validacion basica se hara aqui para comprobar que <i>tipo_cuenta</i> es de tipo <i>enum('Balance','Estado</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param enum('Balance','Estado
	  */
	final public function setTipoCuenta( $tipo_cuenta )
	{
		$this->tipo_cuenta = $tipo_cuenta;
	}

	/**
	  * getNaturaleza
	  * 
	  * Get the <i>naturaleza</i> property for this object. Donde <i>naturaleza</i> es Si es deudora o acreedora
	  * @return enum('Acreedora','Deudora')
	  */
	final public function getNaturaleza()
	{
		return $this->naturaleza;
	}

	/**
	  * setNaturaleza( $naturaleza )
	  * 
	  * Set the <i>naturaleza</i> property for this object. Donde <i>naturaleza</i> es Si es deudora o acreedora.
	  * Una validacion basica se hara aqui para comprobar que <i>naturaleza</i> es de tipo <i>enum('Acreedora','Deudora')</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param enum('Acreedora','Deudora')
	  */
	final public function setNaturaleza( $naturaleza )
	{
		$this->naturaleza = $naturaleza;
	}

	/**
	  * getClasificacion
	  * 
	  * Get the <i>clasificacion</i> property for this object. Donde <i>clasificacion</i> es Clasificacion a la que pertenecera la cuenta
	  * @return enum('Activo
	  */
	final public function getClasificacion()
	{
		return $this->clasificacion;
	}

	/**
	  * setClasificacion( $clasificacion )
	  * 
	  * Set the <i>clasificacion</i> property for this object. Donde <i>clasificacion</i> es Clasificacion a la que pertenecera la cuenta.
	  * Una validacion basica se hara aqui para comprobar que <i>clasificacion</i> es de tipo <i>enum('Activo</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param enum('Activo
	  */
	final public function setClasificacion( $clasificacion )
	{
		$this->clasificacion = $clasificacion;
	}

	/**
	  * getCargosAumentan
	  * 
	  * Get the <i>cargos_aumentan</i> property for this object. Donde <i>cargos_aumentan</i> es Si es igual 1 significa que en los movimientos cuando se cargue a esta cuenta los cargos aumentaran
	  * @return tinyint(1)
	  */
	final public function getCargosAumentan()
	{
		return $this->cargos_aumentan;
	}

	/**
	  * setCargosAumentan( $cargos_aumentan )
	  * 
	  * Set the <i>cargos_aumentan</i> property for this object. Donde <i>cargos_aumentan</i> es Si es igual 1 significa que en los movimientos cuando se cargue a esta cuenta los cargos aumentaran.
	  * Una validacion basica se hara aqui para comprobar que <i>cargos_aumentan</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setCargosAumentan( $cargos_aumentan )
	{
		$this->cargos_aumentan = $cargos_aumentan;
	}

	/**
	  * getAbonosAumentan
	  * 
	  * Get the <i>abonos_aumentan</i> property for this object. Donde <i>abonos_aumentan</i> es si abonos aumentan es igual a 1 significa que en los movimientos los abonos aumentantaran
	  * @return tinyint(1)
	  */
	final public function getAbonosAumentan()
	{
		return $this->abonos_aumentan;
	}

	/**
	  * setAbonosAumentan( $abonos_aumentan )
	  * 
	  * Set the <i>abonos_aumentan</i> property for this object. Donde <i>abonos_aumentan</i> es si abonos aumentan es igual a 1 significa que en los movimientos los abonos aumentantaran.
	  * Una validacion basica se hara aqui para comprobar que <i>abonos_aumentan</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setAbonosAumentan( $abonos_aumentan )
	{
		$this->abonos_aumentan = $abonos_aumentan;
	}

	/**
	  * getEsCuentaOrden
	  * 
	  * Get the <i>es_cuenta_orden</i> property for this object. Donde <i>es_cuenta_orden</i> es si la cuenta no se contemplara en los estados financieros
	  * @return tinyint(1)
	  */
	final public function getEsCuentaOrden()
	{
		return $this->es_cuenta_orden;
	}

	/**
	  * setEsCuentaOrden( $es_cuenta_orden )
	  * 
	  * Set the <i>es_cuenta_orden</i> property for this object. Donde <i>es_cuenta_orden</i> es si la cuenta no se contemplara en los estados financieros.
	  * Una validacion basica se hara aqui para comprobar que <i>es_cuenta_orden</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setEsCuentaOrden( $es_cuenta_orden )
	{
		$this->es_cuenta_orden = $es_cuenta_orden;
	}

	/**
	  * getEsCuentaMayor
	  * 
	  * Get the <i>es_cuenta_mayor</i> property for this object. Donde <i>es_cuenta_mayor</i> es Indica si la cuenta es de mayor
	  * @return tinyint(1)
	  */
	final public function getEsCuentaMayor()
	{
		return $this->es_cuenta_mayor;
	}

	/**
	  * setEsCuentaMayor( $es_cuenta_mayor )
	  * 
	  * Set the <i>es_cuenta_mayor</i> property for this object. Donde <i>es_cuenta_mayor</i> es Indica si la cuenta es de mayor.
	  * Una validacion basica se hara aqui para comprobar que <i>es_cuenta_mayor</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setEsCuentaMayor( $es_cuenta_mayor )
	{
		$this->es_cuenta_mayor = $es_cuenta_mayor;
	}

	/**
	  * getAfectable
	  * 
	  * Get the <i>afectable</i> property for this object. Donde <i>afectable</i> es indica si sobre esta cuenta ya se pueden realizar operaciones
	  * @return tinyint(1)
	  */
	final public function getAfectable()
	{
		return $this->afectable;
	}

	/**
	  * setAfectable( $afectable )
	  * 
	  * Set the <i>afectable</i> property for this object. Donde <i>afectable</i> es indica si sobre esta cuenta ya se pueden realizar operaciones.
	  * Una validacion basica se hara aqui para comprobar que <i>afectable</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setAfectable( $afectable )
	{
		$this->afectable = $afectable;
	}

	/**
	  * getIdCuentaPadre
	  * 
	  * Get the <i>id_cuenta_padre</i> property for this object. Donde <i>id_cuenta_padre</i> es id de la cuenta de la que depende
	  * @return int(11)
	  */
	final public function getIdCuentaPadre()
	{
		return $this->id_cuenta_padre;
	}

	/**
	  * setIdCuentaPadre( $id_cuenta_padre )
	  * 
	  * Set the <i>id_cuenta_padre</i> property for this object. Donde <i>id_cuenta_padre</i> es id de la cuenta de la que depende.
	  * Una validacion basica se hara aqui para comprobar que <i>id_cuenta_padre</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdCuentaPadre( $id_cuenta_padre )
	{
		$this->id_cuenta_padre = $id_cuenta_padre;
	}

	/**
	  * getActiva
	  * 
	  * Get the <i>activa</i> property for this object. Donde <i>activa</i> es Indica si la cuenta estÃ¡ disponible para su uso o no.
	  * @return tinyint(1)
	  */
	final public function getActiva()
	{
		return $this->activa;
	}

	/**
	  * setActiva( $activa )
	  * 
	  * Set the <i>activa</i> property for this object. Donde <i>activa</i> es Indica si la cuenta estÃ¡ disponible para su uso o no..
	  * Una validacion basica se hara aqui para comprobar que <i>activa</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setActiva( $activa )
	{
		$this->activa = $activa;
	}

	/**
	  * getIdCatalogoCuentas
	  * 
	  * Get the <i>id_catalogo_cuentas</i> property for this object. Donde <i>id_catalogo_cuentas</i> es Id del catalogo de cuentas al que pertenece esta cuenta
	  * @return int(11)
	  */
	final public function getIdCatalogoCuentas()
	{
		return $this->id_catalogo_cuentas;
	}

	/**
	  * setIdCatalogoCuentas( $id_catalogo_cuentas )
	  * 
	  * Set the <i>id_catalogo_cuentas</i> property for this object. Donde <i>id_catalogo_cuentas</i> es Id del catalogo de cuentas al que pertenece esta cuenta.
	  * Una validacion basica se hara aqui para comprobar que <i>id_catalogo_cuentas</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdCatalogoCuentas( $id_catalogo_cuentas )
	{
		$this->id_catalogo_cuentas = $id_catalogo_cuentas;
	}

}
