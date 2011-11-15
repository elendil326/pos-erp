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
            return 1;
        }
        
        //metodo para pruebas que simula la obtencion del id de la caja actual
        private static function getCaja()
        {
            return 1;
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
                    Logger::error("La variable orden (".$orden.") no es valido");
                    throw new Exception("La variable orden (".$orden.") no es valido");
                }
                $clientes = array();
                
                //Solo se obtendran los usuarios cuya clasificacion de cliente no sea nula.
                $usuario_clientes = UsuarioDAO::byRange(new Usuario( array( "id_clasificacion_cliente" => 1 ) ),
                        new Usuario( array( "id_clasificacion_cliente" => PHP_INT_MAX) ));
                
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
		$direccion_web = null, 
		$calle = null,  
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
		$numero_interior = null, 
		$moneda_del_cliente = null, 
		$id_ciudad = null, 
		$retenciones = null, 
		$impuestos = null, 
		$codigo_postal = null, 
		$email = null, 
		$referencia = null,
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
                $cliente = PersonalYAgentesController::NuevoUsuario($codigo_cliente,$password,5,$razon_social,
                        $curp,null,$clasificacion_cliente,$numero_exterior,null,self::getSucursal(),null,null,
                        $representante_legal,null,$impuestos,$mensajeria,null,null,null,null,$direccion_web,
                        $telefono_personal1,$descuento,null,$limite_credito,$telefono_personal2,null,$codigo_postal,null,null,
                        $calle,null,$id_ciudad,null,null,$numero_interior,$email,$telefono2,null,$texto_extra,null,
                        $denominacion_comercial,null,null,$telefono1,$cuenta_de_mensajeria,$rfc,null,$retenciones,
                        $colonia,$moneda_del_cliente);
            }
            catch(Exception $e)
            {
                Logger::error("No se pudo crear al cliente: ".$e);
                throw new Exception("No se pudo crear al cliente: ".$e);
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
		$password = null, 
		$razon_social = null, 
		$codigo_cliente = null, 
		$moneda_del_cliente = null, 
		$numero_exterior = null, 
		$numero_interior = null, 
		$telefono1 = null, 
		$rfc = null, 
		$representante_legal = null, 
		$curp = null, 
		$cuenta_de_mensajeria = null, 
		$codigo_postal = null, 
		$direccion_web = null, 
		$mensajeria = null, 
		$telefono2 = null, 
		$denominacion_comercial = null, 
		$calle = null, 
		$municipio = null, 
		$clasificacion_cliente = null, 
		$email = null, 
		$texto_extra = null, 
		$colonia = null,
                $telefono_personal1 = null,
                $telefono_personal2 = null,
                $descuento = null
	)
	{  
            Logger::log("Editando perfil de cliente");
            
            //Se usa el metodo editar usuario para editar al cliente
            try
            {
                PersonalYAgentesController::EditarUsuario($id_cliente,null,null,
                        null,$descuento,$telefono_personal1,null,$direccion_web,
                        null,null,$mensajeria,$telefono_personal2,null,null,null,
                        null,null,null,null,$calle,null,$codigo_postal,$texto_extra,
                        $numero_interior,$municipio,$password,null,$codigo_cliente,
                        $razon_social,$colonia,null,$email,$representante_legal,null,
                        null,$telefono2,null,$rfc,$curp,null,$numero_exterior,$denominacion_comercial,
                        $clasificacion_cliente,null,null,$cuenta_de_mensajeria,$telefono1,null,null,null,null,$moneda_del_cliente);
            }
            catch(Exception $e)
            {
                Logger::error("No se pudo editar al cliente: ".$e);
                throw new Exception("No se pudo editar al cliente");
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
		$telefono1 = null, 
		$impuestos = null, 
		$codigo_cliente = null, 
		$retenciones = null, 
		$direccion_web = null, 
		$cuenta_de_mensajeria = null, 
		$numero_exterior = null, 
		$telefono2 = null, 
		$saldo_del_ejercicio = null, 
		$municipio = null, 
		$clasificacion_cliente = null, 
		$denominacion_comercial = null, 
		$moneda_del_cliente = null, 
		$curp = null, 
		$calle = null, 
		$representante_legal = null, 
		$ventas_a_credito = null, 
		$password = null, 
		$facturar_a_terceros = null, 
		$sucursal = null, 
		$colonia = null, 
		$rfc = null, 
		$texto_extra = null, 
		$lim_credito = null, 
		$razon_social = null, 
		$dias_de_credito = null, 
		$mensajeria = null, 
		$dia_de_pago = null, 
		$email = null, 
		$intereses_moratorios = null, 
		$codigo_postal = null, 
		$numero_interior = null, 
		$dia_de_revision = null,
                $telefono_personal1 = null,
                $telefono_personal2 = null,
                $descuento = null
	)
	{  
            Logger::log("Editando cliente");
            
            //Se llama al metodo Editar usuario
            try
            {
                PersonalYAgentesController::EditarUsuario($id_cliente,null,null,
                        null,$descuento,$telefono_personal1,$lim_credito,$direccion_web,
                        null,$facturar_a_terceros,$mensajeria,$telefono_personal2,$ventas_a_credito,null,$impuestos,
                        $retenciones,$saldo_del_ejercicio,null,$dia_de_pago,$calle,null,$codigo_postal,$texto_extra,
                        $numero_interior,$municipio,$password,null,$codigo_cliente,
                        $razon_social,$colonia,null,$email,$representante_legal,null,
                        null,$telefono2,$dias_de_credito,$rfc,$curp,null,$numero_exterior,$denominacion_comercial,
                        $clasificacion_cliente,null,$dia_de_revision,$cuenta_de_mensajeria,$telefono1,null,$sucursal,null,$intereses_moratorios,$moneda_del_cliente);
            }
            catch( Exception $e)
            {
                Logger::error("El cliente no pudo ser modificado: ".$e);
                throw new Exception("El cliente no pudo er modificado");
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
                throw new Exception($validar);
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
  
  
	}
  }
