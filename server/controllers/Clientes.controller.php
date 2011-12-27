<?php
require_once("interfaces/Clientes.interface.php");
/**
  *
  *
  *
  **/
	
  class ClientesController implements IClientes{
        
        //Metodo para pruebas que simula la obtencion del id de la sucursal actual
        private static function getSucursal()
        {
            return NULL;
        }
        
        //metodo para pruebas que simula la obtencion del id de la caja actual
        private static function getCaja()
        {
            return NULL;
        }
      
        
        /*
         *Se valida que un string tenga longitud en un rango de un maximo inclusivo y un minimo exclusvio.
         *Regresa true cuando es valido, y un string cuando no lo es.
         */
          private static function validarString($string, $max_length, $nombre_variable,$min_length=0)
	{
		if(strlen($string)<=$min_length||strlen($string)>$max_length)
		{
		    return "La longitud de la variable ".$nombre_variable." proporcionada (".$string.") no esta en el rango de ".$min_length." - ".$max_length;
		}
		return true;
        }


        /*
         * Se valida que un numero este en un rango de un maximo y un minimo inclusivos
         * Regresa true cuando es valido, y un string cuando no lo es
         */
	private static function validarNumero($num, $max_length, $nombre_variable, $min_length=0)
	{
	    if($num<$min_length||$num>$max_length)
	    {
	        return "La variable ".$nombre_variable." proporcionada (".$num.") no esta en el rango de ".$min_length." - ".$max_length;
	    }
	    return true;
	}
        
        /*
         * valida los parametros para los clientes. Regresa un string con el error en caso de encontrase
         * alguno, de lo contrario regresa verdadero. Hace uso del metodo validarParametrosUsuario
         */
        private static function validarParametrosCliente
        (
                $id_cliente = null,
                $codigo_cliente = null, 
		$razon_social = null, 
		$direccion_web = null, 
		$clasificacion_cliente = null, 
		$rfc = null,  
		$curp = null, 
		$mensajeria = null, 
		$password = null,
		$denominacion_comercial = null, 
		$cuenta_de_mensajeria = null, 
		$representante_legal = null, 
		$moneda_del_cliente = null, 
		$email = null,
                $telefono_personal1 = null,
                $telefono_personal2 = null
        )
        {
            //valida los parametros llamando al metodo validarParametrosUsuario
            $validar = PersonalYAgentesController::validarParametrosUsuario($id_cliente,null,null,null,$clasificacion_cliente,null,$moneda_del_cliente,
                    null,$razon_social,$rfc,$curp,null,$telefono_personal1,$telefono_personal2,null,null,$password,null,$email,$direccion_web,
                    null,null,$representante_legal,null,null,$mensajeria,null,$denominacion_comercial,null,$cuenta_de_mensajeria,null,$codigo_cliente);
            if(is_string($validar))
            {
                return $validar;
            }
            
            //valida que el cliente este activo y sea un cliente realmente
            if(!is_null($id_cliente))
            {
                $cliente = UsuarioDAO::getByPK($id_cliente);
                if(!$cliente->getActivo())
                    return "El cliente ".$id_cliente." no esta activo";
                
                if(is_null($cliente->getIdClasificacionCliente()))
                        return "El cliente ".$id_cliente." no es un cliente";
            }
            
            //No se encontro error
            return true;
        }
        
        /*
         * Valida los parametros de la tabla clasificacion_cliente. Regresa un string con el error en caso
         * de encontrarse alguno. De lo contrario regresa verdader
         */
        private static function validarParametrosClasificacionCliente
        (
                $id_clasificacion_cliente = null,
                $clave_interna = null,
                $nombre = null,
                $descripcion = null,
                $margen_utilidad = null,
                $descuento = null
        )
        {
            //valida que la clasificacion exista 
            if(!is_null($id_clasificacion_cliente))
            {
                if(is_null(ClasificacionClienteDAO::getByPK($id_clasificacion_cliente)))
                        return "La clasificacion de cliente ".$id_clasificacion_cliente." no existe";
            }
            
            //valida que la clave interna sea valida y que no se repita
            if(!is_null($clave_interna))
            {
                $e = self::validarString($clave_interna, 20, "clave interna");
                if(is_string($e))
                    return $e;
                
                if(!is_null($id_clasificacion_cliente))
                {
                    $clasificaciones_cliente = array_diff(ClasificacionClienteDAO::search( 
                        new ClasificacionCliente( array( "clave_interna" => trim($clave_interna) ) ) ), array(ClasificacionClienteDAO::getByPK($id_clasificacion_cliente)));
                }
                else
                {
                    $clasificaciones_cliente = ClasificacionClienteDAO::search( 
                        new ClasificacionCliente( array( "clave_interna" => trim($clave_interna) ) ) );
                }
                if(!empty($clasificaciones_cliente))
                    return "La clave interna (".$clave_interna.") ya esta en uso";
            }
            
            //valida que el nombre sea valido y que no se repita
            if(!is_null($nombre))
            {
                $e = self::validarString($nombre, 16, "nombre");
                if(is_string($e))
                    return $e;
                
                if(!is_null($id_clasificacion_cliente))
                {
                    $clasificaciones_cliente = array_diff(ClasificacionClienteDAO::search( 
                        new ClasificacionCliente( array( "nombre" => trim($nombre) ) ) ) , array( ClasificacionClienteDAO::getByPK($id_clasificacion_cliente) ));
                }
                else
                {
                    $clasificaciones_cliente = ClasificacionClienteDAO::search( 
                        new ClasificacionCliente( array( "nombre" => trim($nombre) ) ) );
                }
                
                if(!empty($clasificaciones_cliente))
                    return "El nombre (".$nombre.") ya esta en uso";
            }
            
            //valida que la descripcion este en rango
            if(!is_null($descripcion))
            {
                $e = self::validarString($descripcion, 255, "descripcion");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el margen de utilidad este en rango
            if(!is_null($margen_utilidad))
            {
                $e = self::validarNumero($margen_utilidad, 1.8e200, "margen de utilidad");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el descuento este en rango
            if(!is_null($descuento))
                
            {
                $e = self::validarNumero($descuento, 100, "descuento");
                if(is_string($e))
                    return $e;
            }
            
            //No se encontro error
            return true;
        }
        
      
      
      
      
  
	/**
 	 *
 	 *Regresa una lista de clientes. Puede filtrarse por empresa, sucursal, activos, as?omo ordenarse seg?us atributs con el par?tro orden. Es posible que algunos clientes sean dados de alta por un admnistrador que no les asigne algun id_empresa, o id_sucursal.

Update :  �Es correcto que contenga el argumento id_sucursal? Ya que as?omo esta entiendo que solo te regresara los datos de los clientes de una sola sucursal.
 	 *
 	 * @param orden json Valor que definir la forma de ordenamiento de la lista. 
 	 * @param id_empresa int Filtrara los resultados solo para los clientes que se dieron de alta en la empresa dada.
 	 * @param id_sucursal int Filtrara los resultados solo para los clientes que se dieron de alta en la sucursal dada.
 	 * @param mostrar_inactivos bool Si el valor es obtenido, cuando sea true, mostrar solo los clientes que estn activos, false si solo mostrar clientes inactivos.
 	 * @return clientes json Arreglo de objetos que contendr� la informaci�n de los clientes.
 	 **/
	public static function Lista
	(
		$orden = null, 
		$id_sucursal = null, 
		$activo = null,
                $id_clasificacion_cliente = null
	)
	{  
  		Logger::log("Listando clientes");
                
                //valida que el parametro orden sea valido
                if
                (
                        !is_null($orden)                        &&
                        $orden != "id_usuario"                  &&
                        $orden != "id_direccion"                &&
                        $orden != "id_direccion_alterna"        &&
                        $orden != "id_sucursal"                 &&
                        $orden != "id_rol"                      &&
                        $orden != "id_clasificacion_cliente"    &&
                        $orden != "id_clasificacion_proveedor"  &&
                        $orden != "id_moneda"                   &&
                        $orden != "fecha_asignacion_rol"        &&
                        $orden != "nombre"                      &&
                        $orden != "rfc"                         &&
                        $orden != "curp"                        &&
                        $orden != "comision_ventas"             &&
                        $orden != "telefono_personal1"          &&
                        $orden != "telefono_personal2"          &&
                        $orden != "fecha_alta"                  &&
                        $orden != "fecha_baja"                  &&
                        $orden != "activo"                      &&
                        $orden != "limite_credito"              &&
                        $orden != "descuento"                   &&
                        $orden != "password"                    &&
                        $orden != "last_login"                  &&
                        $orden != "consignatario"               &&
                        $orden != "salario"                     &&
                        $orden != "correo_electronico"          &&
                        $orden != "pagina_web"                  &&
                        $orden != "saldo_del_ejercicio"         &&
                        $orden != "ventas_a_credito"            &&
                        $orden != "representante_legal"         &&
                        $orden != "facturar_a_terceros"         &&
                        $orden != "dia_de_pago"                 &&
                        $orden != "mensajeria"                  &&
                        $orden != "intereses_moratorios"        &&
                        $orden != "denominacion_comercial"      &&
                        $orden != "dias_de_credito"             &&
                        $orden != "cuenta_de_mensajeria"        &&
                        $orden != "dia_de_revision"             &&
                        $orden != "codigo_usuario"              &&
                        $orden != "dias_de_embarque"            &&
                        $orden != "tiempo_entrega"              &&
                        $orden != "cuenta_bancaria"
                )
                {
                    Logger::error("La variable orden (".$orden.") no es valida");
                    throw new Exception("La variable orden (".$orden.") no es valida",901);
                }
                $clientes = array();
                
                //Solo se obtendran los usuarios cuya clasificacion de cliente no sea nula.
                $usuario_clientes = UsuarioDAO::byRange(new Usuario( array( "id_clasificacion_cliente" => 1 ) ),
                        new Usuario( array( "id_clasificacion_cliente" => PHP_INT_MAX) ),$orden);
                
                //Si no se reciben parametros, la lista final sera la variable usuario_clientes,
                //pero si se reciben parametros se hace una interseccion y se regresa lal ista
                if(!is_null($id_sucursal) || !is_null($id_clasificacion_cliente) || !is_null($activo))
                {
                    $clientes_rango = UsuarioDAO::search(new Usuario( array( 
                                                    "id_clasificacion_cliente"  => $id_clasificacion_cliente, 
                                                    "id_sucursal"               => $id_sucursal,
                                                    "activo"                    => $activo
                                                                            )
                                                                    ),
                                                        $orden);
                    $clientes = array_intersect($usuario_clientes, $clientes_rango);
                }
                else
                {
                    $clientes = $usuario_clientes;
                }
                Logger::log("La lista de clientes fue obtenida exitosamente con ".count($clientes)." elementos");
                return $clientes;
	}
  
	/**
 	 *
 	 *Crear un nuevo cliente. Para los campos de Fecha_alta y Fecha_ultima_modificacion se usar?a fecha actual del servidor. El campo Agente y Usuario_ultima_modificacion ser?tomados de la sesi?ctiva. Para el campo Sucursal se tomar?a sucursal activa donde se est?reando el cliente. 

Al crear un cliente se le creara un usuario para la interfaz de cliente y pueda ver sus facturas y eso, si tiene email. Al crearse se le enviara un correo electronico con el url.
 	 *
 	 * @param razon_social string Nombre o razon social del cliente.
 	 * @param password string Password del cliente
 	 * @param codigo_cliente string Codigo interno del cliente
 	 * @param codigo_postal string Codigo postal del cliente
 	 * @param direccion_web string Direccin web del cliente.
 	 * @param rfc string RFC del cliente.
 	 * @param clasificacion_cliente int Id de la clasificacin del cliente.
 	 * @param calle string Calle del cliente
 	 * @param curp string CURP del cliente.
 	 * @param telefono2 string Segundo telfono del cliente.
 	 * @param mensajeria bool Si el cliente cuenta con un cliente de mensajera y paquetera.
 	 * @param numero_exterior string Nmero exterior del cliente
 	 * @param cuenta_de_mensajeria string Este parmetro se vuelve obligatorio si el parmetro Mensajera es true. Especifica la cuenta de mensajera instantanea del cliente.
 	 * @param denominacion_comercial string Nombre comercial del cliente.
 	 * @param representante_legal string Nombre del representante legal del cliente.
 	 * @param colonia string Colonia del cliente
 	 * @param numero_interior string Nmero interior del cliente.
 	 * @param moneda_del_cliente int Moneda que maneja el cliente.
 	 * @param telefono1 string Telefono del cliente
 	 * @param id_ciudad int id de la ciudad
 	 * @param retenciones json Objeto que contendra los ids de las retenciones que afectan a este cliente
 	 * @param impuestos json Objeto que contendra los impuestos que afectan a este cliente
 	 * @param email string E-mail del cliente
 	 * @param texto_extra string Comentario sobre la direccin del cliente.
 	 * @return id_cliente int Id autogenerado del cliente que se insert�
 	 **/
	public static function Nuevo
	(
		$codigo_cliente, 
		$razon_social, 
		$clasificacion_cliente, 
                $password,
		$rfc = null, 
		$telefono2 = null, 
		$curp = null, 
		$mensajeria = null,  
		$numero_exterior = null, 
		$colonia = null, 
		$denominacion_comercial = null, 
		$cuenta_de_mensajeria = null, 
		$representante_legal = null, 
		$texto_extra = null, 
		$telefono1 = null,  
		$codigo_postal = null, 
		$id_ciudad = null, 
		$retenciones = null, 
		$impuestos = null, 
		$moneda_del_cliente = null, 
		$numero_interior = null, 
		$calle = null, 
		$email = null, 
		$direccion_web = null,
                $telefono_personal1 = null,
                $telefono_personal2 = null,
                $descuento = null,
                $limite_credito = null
	)
	{
            Logger::log("Creando nuevo cliente");
            
            //se crea la cliente utilizando el metodo Nuevo usuario, este se encarga de la validacion
            //y se toma como rol de cliente el 5
            try 
            {
                $cliente = PersonalYAgentesController::NuevoUsuario($codigo_cliente,5,$razon_social,$password,
                        $calle,null,$codigo_postal,null,$colonia,null,null,$email,null,$cuenta_de_mensajeria,
                        $curp,$denominacion_comercial,$descuento,null,null,null,null,0,$id_ciudad,null,
                        $clasificacion_cliente,null,$moneda_del_cliente,self::getSucursal(),$impuestos,null,
                        $limite_credito,$mensajeria,$numero_exterior,null,$numero_interior,null,$direccion_web,
                        $representante_legal,$retenciones,$rfc,null,null,$telefono1,null,$telefono2,null,
                        $telefono_personal1,$telefono_personal2,$texto_extra);
            }
            catch(Exception $e)
            {
                Logger::error("No se pudo crear al cliente: ".$e);
                if($e->getCode()==901)
                    throw new Exception("No se pudo crear al cliente: ".$e->getMessage());
                throw new Exception("No se pudo crear al cliente, consulte a su administrador de sistema");
            }
            
            Logger::log("El cliente fue creado exitosamente");
            return array( "id_cliente" => $cliente["id_usuario"]);
            
	}
  
	/**
 	 *
 	 *Edita la informaci?e un cliente. El campo fecha_ultima_modificacion ser?lenado con la fecha actual del servidor. El campo Usuario_ultima_modificacion ser?lenado con la informaci?e la sesi?ctiva.
 	 *
 	 * @param password string Password del cliente
 	 * @param razon_social string Nombre o razon social del cliente.
 	 * @param id_cliente int Id del cliente a modificar.
 	 * @param codigo_cliente string Codigo interno del cliente
 	 * @param moneda_del_cliente int Moneda que maneja el cliente
 	 * @param numero_exterior string Nmero exterior del cliente
 	 * @param numero_interior string Nmero interior del cliente.
 	 * @param telefono1 string Telefono del cliente
 	 * @param rfc string RFC del cliente.
 	 * @param representante_legal string Nombre del representante legal del cliente.
 	 * @param curp string CURP del cliente.
 	 * @param cuenta_de_mensajeria string Este parmetro se vuelve obligatorio si el parmetro Mensajera es true. Especifica la cuenta de mensajera y paquetera del cliente.
 	 * @param codigo_postal string Codigo postal del cliente
 	 * @param direccion_web string Direccin web del cliente.
 	 * @param mensajeria bool Si el cliente cuenta con un cliente de mensajera y paquetera.
 	 * @param telefono2 string Segundo telfono del cliente.
 	 * @param denominacion_comercial string Nombre comercial del cliente.
 	 * @param estatus string Estatus del cliente.
 	 * @param calle string Calle del cliente
 	 * @param municipio int Municipio del cliente
 	 * @param clasificacion_cliente int La clasificacin del cliente.
 	 * @param email string E-mail del cliente.
 	 * @param texto_extra string Comentario sobre la direccin del cliente.
 	 * @param colonia string Colonia del cliente
 	 **/
	public static function Editar_perfil
	(
                $id_cliente, 
		$calle =  null, 
		$clasificacion_cliente =  null, 
		$codigo_cliente = null, 
		$codigo_postal =  null, 
		$colonia =  null, 
		$cuenta_de_mensajeria =  null, 
		$curp =  null, 
		$denominacion_comercial =  null, 
		$descuento = null, 
		$direccion_web =  null, 
		$email =  null, 
		$mensajeria =  null, 
		$moneda_del_cliente =  null, 
		$municipio =  null, 
		$numero_exterior =  null, 
		$numero_interior =  null, 
		$password =  null, 
		$razon_social =  null, 
		$representante_legal =  null, 
		$rfc =  null, 
		$telefono1 =  null, 
		$telefono2 =  null, 
		$telefono_personal1 = null, 
		$telefono_personal2 = null, 
		$texto_extra =  null
	)
	{  
            Logger::log("Editando perfil de cliente ".$id_cliente);
            
            //Se usa el metodo editar usuario para editar al cliente
            try
            {
                PersonalYAgentesController::EditarUsuario($id_cliente,$calle,null,$codigo_postal,null,
                        $codigo_cliente,$colonia,null,null,$email,null,$cuenta_de_mensajeria,$curp,
                        $denominacion_comercial,$descuento,null,null,null,null,null,null,$municipio,null,
                        $clasificacion_cliente,null,$moneda_del_cliente,null,null,null,null,null,
                        $mensajeria,$razon_social,$numero_exterior,null,$numero_interior,null,$direccion_web,
                        $password,$representante_legal,null,$rfc,null,null,$telefono1,null,$telefono2,null,
                        $telefono_personal1,$telefono_personal2,$texto_extra);
            }
            catch(Exception $e)
            {
                Logger::error("No se pudo editar al cliente: ".$e);
                if($e->getCode()==901)
                    throw new Exception("No se pudo editar al cliente: ".$e->getMessage());
                throw new Exception("No se pudo editar al cliente, consulte a su administrador de sistema");
            }
            Logger::log("Cliente editado exitosamente");
	}
  
	/**
 	 *
 	 *Edita la informaci?e un cliente. Se diferenc?del m?do editar_perfil en qu?st??do modifica informaci??sensible del cliente. El campo fecha_ultima_modificacion ser?lenado con la fecha actual del servidor. El campo Usuario_ultima_modificacion ser?lenado con la informaci?e la sesi?ctiva.

Si no se envia alguno de los datos opcionales del cliente. Entonces se quedaran los datos que ya tiene.
 	 *
 	 * @param id_cliente int Id del cliente a modificar.
 	 * @param telefono1 string Telefono del cliente
 	 * @param impuestos json Objeto que contendra los ids de los impuestos que afecan a este cliente
 	 * @param codigo_cliente string Codigo interno del cliente
 	 * @param retenciones json Objeto que contendra los ids de las retenciones que afectan a este cliente
 	 * @param direccion_web string Direccin web del cliente.
 	 * @param cuenta_de_mensajeria string Este parmetro se vuelve obligatorio si el parmetro Mensajera es true. Especifica la cuenta de mensajera y paquetera del cliente.
 	 * @param numero_exterior string Nmero exterior del cliente
 	 * @param telefono2 string Segundo telfono del cliente.
 	 * @param saldo_del_ejercicio float Saldo actual del ejercicio del cliente.
 	 * @param municipio int Municipio del cliente
 	 * @param clasificacion_cliente int La clasificacin del cliente.
 	 * @param denominacion_comercial string Nombre comercial del cliente.
 	 * @param moneda_del_cliente string Moneda que maneja el cliente
 	 * @param curp string CURP del cliente.
 	 * @param calle string Calle del cliente
 	 * @param representante_legal string Nombre del representante legal del cliente.
 	 * @param ventas_a_credito int Nmero de ventas a crdito realizadas a este cliente.
 	 * @param password string Password del cliente
 	 * @param facturar_a_terceros bool Si el cliente puede facturar a terceros.
 	 * @param sucursal int Si se desea cambiar al cliente de sucursal, se pasa el id de la nueva sucursal.
 	 * @param colonia string Colonia del cliente
 	 * @param rfc string RFC del cliente.
 	 * @param texto_extra string Comentario sobre la direccin  del cliente.
 	 * @param lim_credito float Valor asignado al lmite del crdito para este cliente.
 	 * @param razon_social string Nombre o razon social del cliente.
 	 * @param estatus string Estatus del cliente.
 	 * @param dias_de_credito int Das de crdito que se le darn al cliente.
 	 * @param mensajeria bool Si el cliente cuenta con un cliente de mensajera y paquetera.
 	 * @param dia_de_pago string Fecha de pago del cliente.
 	 * @param email string E-mail del cliente.
 	 * @param intereses_moratorios float Interes por incumplimiento de pago.
 	 * @param codigo_postal string Codigo postal del cliente
 	 * @param numero_interior string Nmero interior del cliente.
 	 * @param dia_de_revision string Fecha de revisin del cliente.
 	 **/
	public static function Editar
	(
		$id_cliente, 
		$calle = null, 
		$clasificacion_cliente = null, 
		$codigo_cliente = null, 
		$codigo_postal = null, 
		$colonia = null, 
		$cuenta_de_mensajeria = null, 
		$curp = null, 
		$denominacion_comercial = null, 
		$descuento = null, 
		$dias_de_credito = null, 
		$dia_de_pago = null, 
		$dia_de_revision = null, 
		$direccion_web = null, 
		$email = null, 
		$facturar_a_terceros = null, 
		$impuestos = null, 
		$intereses_moratorios = null, 
		$lim_credito = null, 
		$mensajeria = null, 
		$moneda_del_cliente = null, 
		$municipio = null, 
		$numero_exterior = null, 
		$numero_interior = null, 
		$password = null, 
		$razon_social = null, 
		$representante_legal = null, 
		$retenciones = null, 
		$rfc = null, 
		$saldo_del_ejercicio = null, 
		$sucursal = null, 
		$telefono1 = null, 
		$telefono2 = null, 
		$telefono_personal1 = null, 
		$telefono_personal2 = null, 
		$texto_extra = null, 
		$ventas_a_credito = null
	)
	{  
            Logger::log("Editando cliente ".$id_cliente);
            
            //Se llama al metodo Editar usuario
            try
            {
                PersonalYAgentesController::EditarUsuario($id_cliente,$calle,null,$codigo_postal,null,
                        $codigo_cliente,$colonia,null,null,$email,null,$cuenta_de_mensajeria,$curp,
                        $denominacion_comercial,$descuento,null,$dias_de_credito,null,$dia_de_pago,
                        $dia_de_revision,$facturar_a_terceros,$municipio,null,$clasificacion_cliente,
                        null,$moneda_del_cliente,null,$sucursal,$impuestos,$intereses_moratorios,
                        $lim_credito,$mensajeria,$razon_social,$numero_exterior,null,$numero_interior,
                        null,$direccion_web,$password,$representante_legal,$retenciones,$rfc,null,
                        $saldo_del_ejercicio,$telefono1,null,$telefono2,null,$telefono_personal1,
                        $telefono_personal2,$texto_extra);
            }
            catch( Exception $e)
            {
                Logger::error("El cliente no pudo ser modificado: ".$e);
                if($e->getCode()==901)
                    throw new Exception("El cliente no pudo ser modificado: ".$e->getMessage());
                throw new Exception("El cliente no pudo ser modificado, consulte a su administrador de sistema");
            }
            Logger::log("Cliente editado exitosamente");
	}
  
	/**
 	 *
 	 *Obtener los detalles de un cliente.
 	 *
 	 * @param id_cliente int Id del cliente del cual se listarn sus datos.
 	 * @return cliente json Arreglo que contendr� la informaci�n del cliente. 
 	 **/
	public static function Detalle
	(
		$id_cliente
	)
	{  
            Logger::log("Listando los detalles del cliente");
            
            //valida que el cliente exista, que sea cliente y que este activo
            $validar = self::validarParametrosCliente($id_cliente);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar,901);
            }
            
            //Se regresa un arreglo que contendra en el primer campo el cliente en si, en segundo campo estara
            //su direccion, el tercero sera su direccion alterna, el cuarto sera la sucursal en la que fue dado de alta,
            //la quinta sera el rol que tiene, la sexta sera su clasificacion, la septima la moneda que prefiere.
            
            $cliente = array();
            $c = UsuarioDAO::getByPK($id_cliente);
            
            array_push($cliente, $c);
            
            array_push($cliente, DireccionDAO::getByPK($c->getIdDireccion()));
            
            array_push($cliente, DireccionDAO::getByPK($c->getIdDireccionAlterna()));
            
            array_push($cliente, SucursalDAO::getByPK($c->getIdSucursal()));
            
            array_push($cliente, RolDAO::getByPK($c->getIdRol()));
            
            array_push($cliente, ClasificacionClienteDAO::getByPK($c->getIdClasificacionCliente()));
            
            array_push($cliente, MonedaDAO::getByPK($c->getIdMoneda()));
            
            return $cliente;
	}
  
	/**
 	 *
 	 *Los cliente forzosamente pertenecen a una categoria. En base a esta categoria se calcula el precio que se le dara en una venta, o el descuento, o el credito.
 	 *
 	 * @param clave_interna string Una clave interna para darle a este tipo de clientes. Y buscarlos de manera mas rapida.
 	 * @param nombre string Nombre de la clasificacion
 	 * @param impuestos json Impuestos que afectan especificamente a este tipo de clientes
 	 * @param descripcion string Una descripcion para este tipo de cliente
 	 * @param descuento float Porcentaje de descuento que tendra este tipo de cliente sobre todos los productos
 	 * @param retenciones json Retenciones que afectan a este tipo de cliente
 	 * @param utilidad float Utilidad que se ganara a todos los productos que no cuenten con este campo. Se utiliza para calcular el precio al que se le venden los productos a este tipo de cliente.
 	 * @return id_categoria_cliente int El id para esta nueva categoria de cliente.
 	 **/
	public static function NuevaClasificacion
	(
		$clave_interna, 
		$nombre, 
		$impuestos = null, 
		$descripcion = null, 
		$descuento = null, 
		$retenciones = null, 
		$utilidad = null
	)
	{  
            Logger::log("Creando nueva clasificacion de clientes");
            
            //Se validan los parametros recibidos
            $validar = self::validarParametrosClasificacionCliente(null,$clave_interna,$nombre,$descripcion,$utilidad,$descuento);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar,901);
            }
            
            $clasificacion_cliente = new ClasificacionCliente( array( 
                                            "clave_interna"     => $clave_interna,
                                            "nombre"            => trim($nombre),
                                            "descripcion"       => $descripcion,
                                            "margen_utilidad"   => $utilidad,
                                            "descuento"         => $descuento
                                                                    )
                                                             );
            DAO::transBegin();
            try
            {
                ClasificacionClienteDAO::save($clasificacion_cliente);
                if(!is_null($impuestos))
                {
                    $impuesto_clasificacion_cliente = new ImpuestoClasificacionCliente(
                            array( "id_clasificacion_cliente" => $clasificacion_cliente->getIdClasificacionCliente() ));
                    foreach ($impuestos as $impuesto)
                    {
                        if(is_null(ImpuestoDAO::getByPK($impuesto)))
                                throw new Exception ("El impuesto ".$impuesto." no existe",901);
                        $impuesto_clasificacion_cliente->setIdImpuesto($impuesto);
                        ImpuestoClasificacionClienteDAO::save($impuesto_clasificacion_cliente);
                    }
                }/* Fin if de impuestos */
                if(!is_null($retenciones))
                {
                    $retencion_clasificacion_cliente = new RetencionClasificacionCliente(
                            array ( "id_clasificacion_cliente" => $clasificacion_cliente->getIdClasificacionCliente() ) );
                    foreach( $retenciones as $retencion )
                    {
                        if(is_null(RetencionDAO::getByPK($retencion)))
                                throw new Exception("La retencion ".$retencion." no existe",901);
                        $retencion_clasificacion_cliente->setIdRetencion($retencion);
                        RetencionClasificacionClienteDAO::save($retencion_clasificacion_cliente);
                    }
                }/* Fin if de retenciones */
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo crear la nueva clasificacion de cliente: ".$e);
                if($e->getCode()==901)
                    throw new Exception("No se pudo crear la nueva clasificacion de cliente: ".$e->getMessage());
                throw new Exception("No se pudo crear la nueva clasificacion de cliente, consulte a su administrador de sistema");
            }
            DAO::transEnd();
            Logger::log("Clasificacion de cliente creada exitosamente");
            return array( "id_categoria_cliente" => $clasificacion_cliente->getIdClasificacionCliente() );
	}
  
	/**
 	 *
 	 *Obtener una lista de las categorias de clientes actuales en el sistema. Se puede ordenar por sus atributos
 	 *
 	 * @param orden json Objeto que determinara el orden de la lista
 	 * @return clasifciaciones_cliente json Objeto que contendra la lista de clasificaciones de cliente
 	 **/
	public static function ListaClasificacion
	(
		$orden = null
	)
	{  
            Logger::log("Listando las clasificaciones de cliente");
            
            //Se valida el parametro orden
            if
            (
                    !is_null($orden)                        &&
                    $orden != "id_clasificacion_cliente"    &&
                    $orden != "clave_interna"               &&
                    $orden != "nombre"                      &&
                    $orden != "descripcion"                 &&
                    $orden != "margen_utilidad"             &&
                    $orden != "descuento"
            )
            {
                Logger::error("La variable orden (".$orden.") es invalida");
                throw new Exception("La variable orden (".$orden.") es invalida",901);
            }
            
            $clasificaciones_cliente = ClasificacionClienteDAO::getAll(null,null,$orden);
            Logger::log("Se obtuvieron ".count($clasificaciones_cliente)." clasificaciones de cliente");
            return $clasificaciones_cliente;
            
	}
  
	/**
 	 *
 	 *Edita la informacion de la clasificacion de cliente
 	 *
 	 * @param id_clasificacion_cliente int Id de la clasificacion del cliente a modificar
 	 * @param impuestos json Ids de los impuestos que afectan a esta clasificacion
 	 * @param descuento float Descuento que se le aplicara a los productos 
 	 * @param retenciones json Ids de las retenciones que afectan esta clasificacion
 	 * @param clave_interna string Clave interna de la clasificacion
 	 * @param nombre string Nombre de la clasificacion
 	 * @param descripcion string Descripcion larga de la clasificacion
 	 * @param margen_de_utilidad float Margen de utilidad que se le obtendra a todos los productos al venderle a este tipo de cliente
 	 **/
	public static function EditarClasificacion
	(
		$id_clasificacion_cliente, 
		$impuestos = null, 
		$descuento = null, 
		$retenciones = null, 
		$clave_interna = null, 
		$nombre = null, 
		$descripcion = null, 
		$margen_de_utilidad = null
	)
	{  
            Logger::log("Editando clasificacion de cliente ".$id_clasificacion_cliente);
            
            //Se validan los parametros recibidos
            $validar = self::validarParametrosClasificacionCliente($id_clasificacion_cliente,$clave_interna,$nombre,$descripcion,$margen_de_utilidad,$descuento);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar,901);
            }
            
            //Los parametros que no sean nulos seran tomados como actualizacion
            $clasificacion_cliente = ClasificacionClienteDAO::getByPK($id_clasificacion_cliente);
            if(!is_null($descuento))
            {
                $clasificacion_cliente->setDescuento($descuento);
            }
            if(!is_null($clave_interna))
            {
                $clasificacion_cliente->setClaveInterna($clave_interna);
            }
            if(!is_null($nombre))
            {
                $clasificacion_cliente->setNombre(trim($nombre));
            }
            if(!is_null($descripcion))
            {
                $clasificacion_cliente->setDescripcion($descripcion);
            }
            if(!is_null($margen_de_utilidad))
            {
                $clasificacion_cliente->setMargenUtilidad($margen_de_utilidad);
            }
            
            //Se actualiza el registro. Si se recibe una lista de impuestos y/o retenciones, se almacenan los
            //registros recibidos, despues, se recorren los registro de la base de datos y se buscan en la lista recibida.
            //Aquellos que no sean encontrados seran eliminados
            
            DAO::transBegin();
            try
            {
                ClasificacionClienteDAO::save($clasificacion_cliente);
                if(!is_null($impuestos))
                {
                    $impuesto_clasificacion_cliente = new ImpuestoClasificacionCliente(
                            array( "id_clasificacion_cliente" => $clasificacion_cliente->getIdClasificacionCliente() ));
                    foreach ($impuestos as $impuesto)
                    {
                        if(is_null(ImpuestoDAO::getByPK($impuesto)))
                                throw new Exception ("El impuesto ".$impuesto." no existe",901);
                        $impuesto_clasificacion_cliente->setIdImpuesto($impuesto);
                        ImpuestoClasificacionClienteDAO::save($impuesto_clasificacion_cliente);
                    }
                    
                    $impuestos_clasificacion_cliente = ImpuestoClasificacionClienteDAO::search( 
                            new ImpuestoClasificacionCliente( array( "id_clasificacion_cliente" => $id_clasificacion_cliente ) ) );
                    foreach($impuestos_clasificacion_cliente as $impuesto_clasificacion_cliente)
                    {
                        $encontrado = false;
                        foreach($impuestos as $impuesto)
                        {
                            if($impuesto == $impuesto_clasificacion_cliente->getIdImpuesto())
                                $encontrado = true;
                        }
                        if(!$encontrado)
                            ImpuestoClasificacionClienteDAO::delete ($impuesto_clasificacion_cliente);
                    }
                }/* Fin if de impuestos */
                if(!is_null($retenciones))
                {
                    $retencion_clasificacion_cliente = new RetencionClasificacionCliente(
                            array ( "id_clasificacion_cliente" => $clasificacion_cliente->getIdClasificacionCliente() ) );
                    foreach( $retenciones as $retencion )
                    {
                        if(is_null(RetencionDAO::getByPK($retencion)))
                                throw new Exception("La retencion ".$retencion." no existe",901);
                        $retencion_clasificacion_cliente->setIdRetencion($retencion);
                        RetencionClasificacionClienteDAO::save($retencion_clasificacion_cliente);
                    }
                    
                    $retenciones_clasificacion_cliente = RetencionClasificacionClienteDAO::search( 
                            new RetencionClasificacionCliente( array( "id_clasificacion_cliente" => $id_clasificacion_cliente ) ) );
                    foreach($retenciones_clasificacion_cliente as $retencion_clasificacion_cliente)
                    {
                        $encontrado = false;
                        foreach($retenciones as $retencion)
                        {
                            if($retencion == $retencion_clasificacion_cliente->getIdRetencion())
                                $encontrado = true;
                        }
                        if(!$encontrado)
                            RetencionClasificacionClienteDAO::delete ($retencion_clasificacion_cliente);
                    }
                }/* Fin if de retenciones */
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se ha podido editar la clasificacion de cliente ".$id_clasificacion_cliente." : ".$e);
                if($e->getCode()==901)
                    throw new Exception("No se ha podido editar la clasificacion de cliente: ".$e->getMessage());
                throw new Exception("No se ha podido editar la clasificacion de cliente, consulte a su administrador de sistema");
            }
            DAO::transEnd();
	}
	
	
	
	

	
	/**
	  * Buscar clientes
	  *
	  **/
	public static function Buscar($query){
		$resultados = UsuarioDAO::buscarClientes( $query );
		return array( 
			"resultados" => $resultados ,
			"numero_de_resultados" => sizeof($resultados)
			);
		
	}
	
	
}
