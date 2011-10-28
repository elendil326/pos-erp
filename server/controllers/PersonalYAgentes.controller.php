<?php
require_once("interfaces/PersonalYAgentes.interface.php");
/**
  *
  *
  *
  **/
	
  class PersonalYAgentesController implements IPersonalYAgentes{
  
  	
    private static function validarString($string, $max_length, $nombre_variable,$min_length=0)
	{
		if(strlen($string)<=$min_length||strlen($string)>$max_length)
		{
		    return "La longitud de la variable ".$nombre_variable." proporcionada (".$nombre_variable.") no esta en el rango de ".$min_length." - ".$max_length;
		}
		return true;
    }



	private static function validarNumero($num, $max_length, $nombre_variable, $min_length=0)
	{
	    if($num<$min_length||$num>$max_length)
	    {
	        return "La variable ".$nombre_variable." proporcionada (".$num.") no esta en el rango de ".$min_length." - ".$max_length;
	    }
	    return true;
	}

      private static function ValidarParametrosRol
      (
                $id_rol=null,
                $descripcion=null,
                $nombre = null,
		$descuento = null,
		$salario = null
      )
      {
          if(!is_null($id_rol))
          {
              if(is_null(RolDAO::getByPK($id_rol)))
              {
                  return "El rol con id: ".$id_rol." no existe";
              }
          }
          if(!is_null($descripcion))
          {
              $e=self::validarString($descripcion, 255, "descripcion");
                    if(is_string($e))
                        return $e;
          }
          if(!is_null($nombre))
          {
              $e=self::validarString($nombre, 30, "nombre");
              if(is_string($e))
                  return $e;
          }
          if(!is_null($descuento))
          {
              $e=self::validarNumero($descuento, 100, "descuento");
                    if(is_string($e))
                        return $e;
          }
          if(!is_null($salario))
          {
              $e=self::validarNumero($salario, 1.8e200, "salario");
                    if(is_string($e))
                        return $e;
          }
          return true;
      }

      private static function validarParametrosUsuario
      (
              $id_usuario = null,
              $id_direccion = null,
              $id_sucursal = null,
              $id_rol = null,
              $id_clasificacion_cliente = null,
              $id_clasificacion_proveedor = null,
              $id_moneda = null,
              $activo = null,
              $nombre = null,
              $rfc = null,
              $curp = null,
              $comision_ventas = null,
              $telefono_personal1 = null,
              $telefono_personal2 = null,
              $limite_credito = null,
              $descuento = null,
              $password = null,
              $salario = null,
              $correo_electronico = null,
              $pagina_web = null,
              $saldo_del_ejercicio = null,
              $ventas_a_credito = null,
              $representante_legal = null,
              $facturar_a_terceros = null,
              $dia_de_pago = null,
              $mensajeria = null,
              $intereses_moratorios = null,
              $denominacion_comercial = null,
              $dias_de_credito = null,
              $cuenta_de_mensajeria = null,
              $dia_de_revision = null,
              $codigo_usuario = null,
              $dias_de_embarque = null,
              $tiempo_entrega = null,
              $cuenta_bancaria = null
      )
      {
          if(!is_null($id_usuario))
          {
              if(is_null(UsuarioDAO::getByPK($id_usuario)))
                  return "El usuario con id: ".$id_usuario." no existe";
          }
          if(!is_null($id_direccion))
          {
              if(is_null(DireccionDAO::getByPK($id_direccion)))
                  return "La direccion con id: ".$id_direccion." no existe";
          }
          if(!is_null($id_sucursal))
          {
              if(is_null(SucursalDAO::getByPK($id_sucursal)))
                  return "La sucursal con id: ".$id_sucursal." no existe";
          }
          if(!is_null($id_rol))
          {
              if(is_null(RolDAO::getByPK($id_rol)))
                  return "El rol con id: ".$id_rol." no existe";
          }
          if(!is_null($id_clasificacion_cliente))
          {
              if(is_null(ClasificacionClienteDAO::getByPK($id_clasificacion_cliente)))
                  return "La clasificacion cliente con id: ".$id_clasificacion_cliente." no existe";
          }
          if(!is_null($id_clasificacion_proveedor))
          {
              if(is_null(ClasificacionProveedorDAO::getByPK($id_clasificacion_proveedor)))
                  return "La clasficiacion proveedor con id: ".$id_clasificacion_proveedor." no existe";
          }
          if(!is_null($id_moneda))
          {
              if(is_null(MonedaDAO::getByPK($id_moneda)))
                  return "La moneda con id: ".$id_moneda." no existe";
          }
          if(!is_null($nombre))
          {
              $e=self::validarString($nombre, 100, "nombre");
              if(is_string($e))
                  return $e;
          }
          if(!is_null($rfc))
          {
              $e=self::validarString($rfc, 30, "rfc");
              if(is_string($e))
                  return $e;
              if(preg_match('/[^A-Z0-9]/' ,$rfc))
                  return "El rfc ".$rfc." contiene caracteres fuera del rango A-Z y 0-9";
          }
          if(!is_null($curp))
          {
              $e=self::validarString($curp, 30, "curp");
              if(is_string($e))
                  return $e;
              if(preg_match('/[^A-Z0-9]/' ,$curp))
                  return "El curp ".$curp." contiene caracteres fuera del rango A-Z y 0-9";
          }
          if(!is_null($comision_ventas))
          {
              $e=self::validarNumero($comision_ventas, 100, "comision de ventas");
              if(is_string($e))
                  return $e;
          }
          if(!is_null($telefono_personal1))
          {
              $e=self::validarString($telefono_personal1, 20, "Telefono personal");
              if(is_string($e))
                  return $e;
              if(preg_match('/[^0-9\- \(\)\*]/',$telefono_personal1))
                  return "El telefono ".$telefono_personal1." tiene caracteres fuera del rango 0-9,-,(,),* o espacio vacío";
          }
          if(!is_null($telefono_personal2))
          {
              $e=self::validarString($telefono_personal2, 20, "Telefono personal alterno");
              if(is_string($e))
                  return $e;
              if(preg_match('/[^0-9\- \(\)\*]/',$telefono_personal2))
                  return "El telefono ".$telefono_personal2." tiene caracteres fuera del rango 0-9,-,(,),* o espacio vacío";
          }
          if(!is_null($activo))
          {
              $e=self::validarNumero($activo, 1, "activo");
              if(is_string($e))
                  return $e;
          }
          if(!is_null($limite_credito))
          {
              $e=self::validarNumero($limite_credito, 1.8e200, "limite de credito");
              if(is_string($e))
                  return $e;
          }
          if(!is_null($descuento))
          {
              $e=self::validarNumero($descuento, 1.8e200, "descuento");
              if(is_string($e))
                  return $e;
          }
          if(!is_null($password))
          {
              $e=self::validarString($password, 1.8e200, "password",3);
              if(is_string($e))
                  return $e;
          }
          if(!is_null($salario))
          {
              $e=self::validarNumero($salario, 1.8e200, "salario");
              if(is_string($e))
                  return $e;
          }
          if(!is_null($correo_electronico))
          {
              $e=self::validarString($correo_electronico, 30, "correo electronico");
              if(is_string($e))
                  return $e;
              if(!is_string(filter_var($email, FILTER_VALIDATE_EMAIL)))
                      return "El correo electronico ".$correo_electronico." no es valido";
          }
          if(!is_null($pagina_web))
          {
              $e=self::validarString($pagina_web, 30, "pagina web");
              if(is_string($e))
                  return $e;
              if(!preg_match('/^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,6}'.'((:[0-9]{1,5})?\/.*)?$/i' ,$pagina_web)&&
                    !preg_match('/^[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,6}'.'((:[0-9]{1,5})?\/.*)?$/i' ,$pagina_web))
                            return "La direccion web ".$pagina_web." no cumple el formato valido";
          }
          if(!is_null($saldo_del_ejercicio))
          {
              $e=self::validarNumero($saldo_del_ejercicio, 1.8e200, $saldo_del_ejercicio, -1.8e200);
              if(is_string($e))
                  return $e;
          }
          if(!is_null($ventas_a_credito))
          {
              $e=self::validarNumero($ventas_a_credito, PHP_INT_MAX, "ventas a credito");
              if(is_string($e))
                  return $e;
          }
          if(!is_null($representante_legal))
          {
              $e=self::validarString($representante_legal, 100, "representante legal");
              if(is_string($e))
                  return $e;
          }
          if(!is_null($facturar_a_terceros))
          {
              $e=self::validarNumero($facturar_a_terceros, 1, "facturar a terceros");
              if(is_string($e))
                  return $e;
          }
          if(!is_null($dia_de_pago))
          {
              $e=self::validarString($dia_de_pago, strlen("YYYY-mm-dd HH:ii:ss"), "dia de pago");
              if(is_string($e))
                  return $e;
          }
          if(!is_null($mensajeria))
          {
              $e=self::validarNumero($mensajeria, 1, "mensajeria");
              if(is_string($e))
                  return $e;
          }
          if(!is_null($intereses_moratorios))
          {
              $e=self::validarNumero($intereses_moratorios, 1.8e200, "intereses moratorios");
              if(is_string($e))
                  return $e;
          }
          if(!is_null($denominacion_comercial))
          {
              $e=self::validarString($denominacion_comercial, 100, "denominacion comercial");
              if(is_string($e))
                  return $e;
          }
          if(!is_null($dias_de_credito))
          {
              $e=self::validarNumero($dias_de_credito, PHP_INT_MAX, "dias de credito");
              if(is_string($e))
                  return $e;
          }
          if(!is_null($cuenta_de_mensajeria))
          {
              $e=self::validarString($cuenta_de_mensajeria, 50, "cuenta de mensajeria");
              if(is_string($e))
                  return $e;
          }
          if(!is_null($dia_de_revision))
          {
              $e=self::validarString($dia_de_revision, strlen("YYYY-mm-dd HH:ii:ss"), "dia de revision");
              if(is_string($e))
                  return $e;
          }
          if(!is_null($codigo_usuario))
          {
              $e=self::validarString($codigo_usuario, 50, "codigo de usuario");
              if(is_string($e))
                  return $e;
              if(preg_match('/[^a-zA-Z0-9]/', $codigo_usuario))
                      return "El codigo de usuario ".$codigo_usuario." no tiene solo caracteres alfanumericos";
          }
          if(!is_null($dias_de_embarque))
          {
              $e=self::validarNumero($dias_de_embarque, PHP_INT_MAX, "dias de embarque");
              if(is_string($e))
                  return $e;
          }
          if(!is_null($tiempo_entrega))
          {
              $e=self::validarNumero($tiempo_entrega, PHP_INT_MAX, "tiempo de entrega");
              if(is_string($e))
                  return $e;
          }
          if(!is_null($cuenta_bancaria))
          {
              $e=self::validarString($cuenta_bancaria, 50, "cuenta bancaria");
              if(is_string($e))
                  return $e;
          }
          return true;
      }

      private static function validarParametrosImpuestoUsuario
      (
              $id_impuesto = null,
              $id_usuario = null
      )
      {
        if(!is_null($id_impuesto))
        {
            if(is_null(ImpuestoDAO::getByPK($id_impuesto)))
            {
                return "El impuesto con id: ".$id_impuesto." no existe";
            }
        }
        if(!is_null($id_usuario))
        {
            if(is_null(UsuarioDAO::getByPK($id_usuario)))
            {
                return "El usuario con id: ".$id_usuario." no existe";
            }
        }
        return true;
      }

      private static function validarParametrosRetencionUsuario
      (
              $id_retencion = null,
              $id_usuario = null
      )
      {
          if(!is_null($id_retencion))
          {
              if(is_null(RetencionDAO::getByPK($id_retencion)))
              {
                  return "La retencion con id: ".$id_retencion." no existe";
              }
          }
          if(!is_null($id_usuario))
          {
              if(is_null(UsuarioDAO::getByPK($id_usuario)))
              {
                  return "El usuario con id: ".$id_usuario." no existe";
              }
          }
          return true;
      }

	/**
 	 *
 	 *Insertar un nuevo usuario. El usuario que lo crea sera tomado de la sesion actual y la fecha sera tomada del servidor. Un usuario no puede tener mas de un rol en una misma sucursal de una misma empresa.
 	 *
 	 * @param password string Password del usuario
 	 * @param id_rol int Id del rol del usuario en la instnacia
 	 * @param nombre string Nombre del usuario
 	 * @param codigo_usuario string Codigo interno del usuario
 	 * @param facturar_a_terceros bool Si el usuario puede facturar a terceros
 	 * @param id_sucursal int Id de la sucursal donde fue creado el usuario o donde labora
 	 * @param mensajeria bool Si el cliente tiene una cuenta de mensajeria
 	 * @param mpuestos json Objeto que contendra los impuestos que afectan a este usuario
 	 * @param dia_de_pago string Fecha de pago del cliente
 	 * @param cuenta_bancaria string Cuenta bancaria del usuario
 	 * @param representante_legal string Nombre del representante legal del usuario
 	 * @param saldo_del_ejercicio float Saldo del ejercicio del cliente
 	 * @param salario float Si el usuario contara con un salario especial no especificado por el rol
 	 * @param intereses_moratorios float Intereses moratorios del cliente
 	 * @param ventas_a_credito int Ventas a credito del cliente
 	 * @param telefono_personal1 string Telefono personal del usuario
 	 * @param descuento float El porcentaje de descuento que se le hara al usuario al venderle
 	 * @param pagina_web string Pagina web del usuario
 	 * @param limite_credito float El limite de credito del usuario
 	 * @param telefno_personal2 string Telefono personal del usuario
 	 * @param telefono1_2 string Telefono de la direccion alterna del usuario
 	 * @param codigo_postal string Codigo postal del Agente
 	 * @param telefono2_2 string Telefono 2 de la direccion al terna del usuario
 	 * @param codigo_postal_2 string Codigo postal de la direccion alterna del usuario
 	 * @param texto_extra_2 string Texto extra para ubicar la direccion alterna del usuario
 	 * @param numero_interior_2 string Numero interior de la direccion alterna del usuario
 	 * @param id_ciudad int ID de la ciudad donde vive el agente
 	 * @param colonia_2 string Colonia de la direccion alterna del usuario
 	 * @param calle string Calle donde vive el agente
 	 * @param numero_interior string Numero interior del agente
 	 * @param id_ciudad_2 int Id de la ciudad de la direccion alterna del usuario
 	 * @param correo_electronico string Correo Electronico del agente
 	 * @param texto_extra string Comentario sobre la direccion del agente
 	 * @param telefono2 string Otro telefono del agente
 	 * @param denominacion_comercial string Denominacion comercial del cliente
 	 * @param dias_de_credito int Dias de credito del cliente
 	 * @param calle_2 string Calle de la direccion alterna del usuario
 	 * @param numero_exterior_2 string Numero exterior de la direccion alterna del usuario
 	 * @param telefono1 string Telefono principal del agente
 	 * @param dias_de_embarque int Dias de embarque del proveedor ( Lunes, Miercoles, Viernes, etc)
 	 * @param numero_exterior string Numero exterior del agente
 	 * @param id_clasificacion_cliente int Id de la clasificacion del cliente
 	 * @param curp string CURP del agente
 	 * @param dia_de_revision string Fecha de revision del cliente
 	 * @param cuenta_mensajeria string Cuenta de mensajeria del usuario
 	 * @param comision_ventas float El porcentaje de la comision que ganara este usuario especificamente por ventas
 	 * @param rfc string RFC del agente
 	 * @param id_clasificacion_proveedor int Id de la clasificacion del proveedor
 	 * @param colonia string Colonia donde vive el agente
 	 * @param retenciones json Ids de las retenciones que afectan a este usuario
 	 * @return id_usuario int Id generado por la inserci�n del usuario
 	 **/
	public static function NuevoUsuario
	(
		$codigo_usuario,
		$password,
		$id_rol,
		$nombre,
		$curp = null,
		$dia_de_revision = null,
		$id_clasificacion_cliente = null,
		$numero_exterior = null,
		$facturar_a_terceros = null,
		$id_sucursal = null,
		$dias_de_embarque = null,
		$saldo_del_ejercicio = 0,
		$representante_legal = null,
		$dia_de_pago = null,
		$impuestos = null,
		$mensajeria = null,
		$salario = null,
		$cuenta_bancaria = null,
		$intereses_moratorios = null,
		$ventas_a_credito = null,
		$pagina_web = null,
		$telefono_personal1 = "",
		$descuento = null,
		$telefono2_2 = null,
		$limite_credito = 0,
		$telefono_personal2 = null,
		$telefono1_2 = null,
		$codigo_postal = null,
		$texto_extra_2 = null,
		$codigo_postal_2 = null,
		$calle = null,
		$numero_interior_2 = null,
		$id_ciudad = null,
		$colonia_2 = null,
		$id_ciudad_2 = null,
		$numero_interior = null,
		$correo_electronico = null,
		$telefono2 = null,
		$dias_de_credito = null,
		$texto_extra = "",
		$calle_2 = null,
		$denominacion_comercial = null,
		$numero_exterior_2 = null,
		$comision_ventas = 0,
		$telefono1 = null,
		$cuenta_mensajeria = null,
		$rfc = "",
		$id_clasificacion_proveedor = null,
		$retenciones = null,
		$colonia = "",
		$id_moneda = null,
		$tiempo_entrega = null
	)
	{  
            Logger::log("Creando usuario nuevo");
            $validar=self::validarParametrosUsuario(null, null, $id_sucursal, $id_rol,
                    $id_clasificacion_cliente, $id_clasificacion_proveedor, $id_moneda,
                    null, $nombre, $rfc, $curp, $comision_ventas, $telefono_personal1,
                    $telefono_personal2, $limite_credito, $descuento, $password, $salario,
                    $correo_electronico,$pagina_web,$saldo_del_ejercicio,$ventas_a_credito,
                    $representante_legal,$facturar_a_terceros,$dia_de_pago,$mensajeria,
                    $intereses_moratorios,$denominacion_comercial,$dias_de_credito,
                    $cuenta_mensajeria,$dia_de_revision,$codigo_usuario,$dias_de_embarque,$tiempo_entrega,$cuenta_bancaria);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            $usuarios=UsuarioDAO::search(new Usuario(array( "codigo_usuario" => $codigo_usuario )));
            foreach($usuarios as $usuario)
            {
                if($usuario->getActivo())
                {
                    Logger::error("El codigo de usuario ".$codigo_usuario." ya esta en uso");
                    throw new Exception("El codigo de usuario ".$codigo_usuario." ya esta en uso");
                }
            }
            $usuarios=UsuarioDAO::search(new Usuario(array( "rfc" => $rfc )));
            foreach($usuarios as $usuario)
            {
                if($usuario->getActivo())
                {
                    Logger::error("El rfc ".$rfc." ya existe");
                    throw new Exception("El rfc ".$rfc." ya existe");
                }
            }
            $usuarios=UsuarioDAO::search(new Usuario(array( "curp" => $curp )));
            foreach($usuarios as $usuario)
            {
                if($usuario->getActivo())
                {
                    Logger::error("La curp ".$curp." ya existe");
                    throw new Exception("La curp ".$curp." ya existe");
                }
            }
            if(!is_null($telefono_personal1)&&$telefono_personal1==$telefono_personal2)
            {
                Logger::error("El telefono personal es igual al telefno personal alterno: ".$telefono_personal1."  ".$telefono_personal2);
                throw new Exception("El telefono personal es igual al telefno personal alterno: ".$telefono_personal1."  ".$telefono_personal2);
            }
            if(!is_null($correo_electronico))
            {
                $usuarios=UsuarioDAO::search(new Usuario( array( "correo_electronico" => $correo_electronico ) ));
                foreach($usuarios as $usuario)
                {
                    if($usuario->getActivo())
                    {
                        Logger::error("El correo electronico ".$correo_electronico." ya esta en uso");
                        throw new Exception("El correo electronico ".$correo_electronico." ya esta en uso");
                    }
                }
            }
            if($password==$codigo_usuario||$password==$correo_electronico)
            {
                Logger::error("El password (".$password.") no puede ser igual al codigo de usuario
                    (".$codigo_usuario.") ni al correo electronico (".$correo_electronico.")");
                throw new Exception("El password (".$password.") no puede ser igual al codigo de usuario
                    (".$codigo_usuario.") ni al correo electronico (".$correo_electronico.")");
            }
            if(is_null($limite_credito))
                $limite_credito=0;
            if(is_null($saldo_del_ejercicio))
                $saldo_del_ejercicio=0;
            $usuario = new Usuario(
                        array
                            (
                                "id_sucursal"               => $id_sucursal,
                                "id_rol"                    => $id_rol,
                                "id_clasificacion_cliente"  => $id_clasificacion_cliente,
                                "id_clasificacion_proveedor"=> $id_clasificacion_proveedor,
                                "id_moneda"                 => $id_moneda,
                                "fecha_asignacion_rol"      => date("Y-m-d H:i:s"),
                                "nombre"                    => $nombre,
                                "rfc"                       => $rfc,
                                "curp"                      => $curp,
                                "comision_ventas"           => $comision_ventas,
                                "telefono_personal1"        => $telefono_personal1,
                                "telefono_personal2"        => $telefono_personal2,
                                "fecha_alta"                => date("Y-m-d H:i:s"),
                                "activo"                    => 1,
                                "limite_credito"            => $limite_credito,
                                "descuento"                 => $descuento,
                                "password"                  => hash("md5",$password),
                                "salario"                   => $salario,
                                "correo_electronico"        => $correo_electronico,
                                "pagina_web"                => $pagina_web,
                                "saldo_del_ejercicio"       => $saldo_del_ejercicio,
                                "ventas_a_credito"          => $ventas_a_credito,
                                "representante_legal"       => $representante_legal,
                                "facturar_a_terceros"       => $facturar_a_terceros,
                                "mensajeria"                => $mensajeria,
                                "intereses_moratorios"      => $intereses_moratorios,
                                "denominacion_comercial"    => $denominacion_comercial,
                                "dias_de_credito"           => $dias_de_credito,
                                "cuenta_de_mensajeria"      => $cuenta_mensajeria,
                                "codigo_usuario"            => $codigo_usuario,
                                "dias_de_embarque"          => $dias_de_embarque,
                                "tiempo_entrega"            => $tiempo_entrega,
                                "cuenta_bancaria"           => $cuenta_bancaria,
                                "consignatario"             => 0
                            )
                        );
            $id_direccion1=-1;
            $id_direccion2=-1;
            DAO::transBegin();
            try
            {
                if(!is_null($calle))
                {
                    $id_direccion1 = DireccionController::NuevaDireccion($calle, $numero_exterior, $colonia,
                            $id_ciudad, $codigo_postal, $numero_interior, $texto_extra, $telefono1, $telefono2);
                }
                if(!is_null($calle_2))
                {
                    $id_direccion2 = DireccionController::NuevaDireccion($calle_2, $numero_exterior_2, $colonia_2,
                            $id_ciudad_2, $codigo_postal_2, $numero_interior_2, $texto_extra_2, $telefono1_2, $telefono2_2);
                }
                if($id_direccion1>0)
                    $usuario->setIdDireccion($id_direccion1);
                if($id_direccion2>0)
                    $usuario->setIdDireccionAlterna($id_direccion2);
                UsuarioDAO::save($usuario);
                if(!is_null($impuestos))
                {
                    foreach($impuestos as $id_impuesto)
                    {
                        $validar=self::validarParametrosImpuestoUsuario($id_impuesto);
                        if(is_string($validar))
                            throw $validar;
                        ImpuestoUsuarioDAO::save(new ImpuestoUsuario(array( "id_impuesto" => $id_impuesto , "id_usuario" => $usuario->getIdUsuario())));
                    }
                }
                if(!is_null($retenciones))
                {
                    foreach($retenciones as $id_retencion)
                    {
                        $validar=self::validarParametrosRetencionUsuario($id_retencion);
                        if(is_string($validar))
                            throw $validar;
                        RetencionEmpresaDAO::save(new RetencionEmpresa(array( "id_retencion" => $id_retencion , "id_usuario" => $usuario->getIdUsuario() )));
                    }
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo crear al usuario: ".$e);
                throw new Exception("No se pudo crear al usuario");
            }
            DAO::transEnd();
            Logger::log("Usuario creado exitosamente con id".$usuario->getIdUsuario());
            return array( "id_usuario" => $usuario->getIdUsuario() );
	}
  
	/**
 	 *
 	 *Listar a todos los usuarios del sistema. Se puede ordenar por los atributos del usuario y filtrar en activos e inactivos
 	 *
 	 * @param activo bool True si se mostrarn solo los usuarios activos, false si solo se mostrarn los usuarios inactivos
 	 * @param ordenar json Valor numrico que indicar la forma en que se ordenar la lista
 	 * @return usuarios json Arreglo de objetos que contendr� la informaci�n de los usuarios del sistema
 	 **/
	public static function ListaUsuario
	(
		$activo = null, 
		$ordenar = null
	)
	{  
            $validar=self::validarParametrosUsuario(null, null, null, null, null, null, null, $activo);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            $usuarios=array();
            if(!is_null($ordenar))
            {
                $e=self::validarString($ordenar, 30, "ordenar");
                if(is_string($e))
                {
                    Logger::error($e);
                    throw new Exception($e);
                }
                if
                (
                        $ordenar != "id_usuario" &&
                        $ordenar != "id_direccion" &&
                        $ordenar != "id_direccion_alterna" &&
                        $ordenar != "id_sucursal" &&
                        $ordenar != "id_rol" &&
                        $ordenar != "id_clasificacion_cliente" &&
                        $ordenar != "id_clasificacion_proveedor" &&
                        $ordenar != "id_moneda" &&
                        $ordenar != "fecha_asignacion_rol" &&
                        $ordenar != "nombre" &&
                        $ordenar != "rfc" &&
                        $ordenar != "curp" &&
                        $ordenar != "comision_ventas" &&
                        $ordenar != "telefono_personal1" &&
                        $ordenar != "telefono_personal2" &&
                        $ordenar != "fecha_alta" &&
                        $ordenar != "fecha_baja" &&
                        $ordenar != "activo" &&
                        $ordenar != "limite_credito" &&
                        $ordenar != "descuento" &&
                        $ordenar != "password" &&
                        $ordenar != "last_login" &&
                        $ordenar != "consignatario" &&
                        $ordenar != "salario" &&
                        $ordenar != "correo_electronico" &&
                        $ordenar != "pagina_web" &&
                        $ordenar != "saldo_del_ejercicio" &&
                        $ordenar != "ventas_a_credito" &&
                        $ordenar != "representante_legal" &&
                        $ordenar != "facturar_a_terceros" &&
                        $ordenar != "dia_de_pago" &&
                        $ordenar != "mensajeria" &&
                        $ordenar != "intereses_moratorios" &&
                        $ordenar != "denominacion_comercial" &&
                        $ordenar != "dias_de_credito" &&
                        $ordenar != "cuenta_de_mensajeria" &&
                        $ordenar != "dia_de_revision" &&
                        $ordenar != "codigo_usuario" &&
                        $ordenar != "dias_de_embarque" &&
                        $ordenar != "tiempo_entrega" &&
                        $ordenar != "cuenta_bancaria"
                )
                {
                    Logger::error("El parametro ordenar :".$ordenar." no es una columna de la tabla usuario");
                    throw new Exception("El parametro ordenar es invalido");
                }
            }
            if(!is_null($activo))
            {
                $usuarios=UsuarioDAO::search(new Usuario( array( "activo" => $activo ) ),$ordenar);
            }
            else
            {
                $usuarios=UsuarioDAO::getAll(null,null,$ordenar);
            }
            Logger::log("Se obtuvo la lista de usuarios exitosamente con ".count($usuarios)." elementos");
            return $usuarios;
	}
  
	/**
 	 *
 	 *Editar los detalles de un usuario.
 	 *
 	 * @param id_usuario int Usuario a editar
 	 * @param colonia_2 string Colonia de la direccion alterna del usuario
 	 * @param id_rol int Id rol del usuario
 	 * @param salario float Si el usuario contara con un salario no establecido por el rol
 	 * @param descuento float Descuento que se le hara al usuario al venderle
 	 * @param telefono_personal_1 string telefono personal del usuario
 	 * @param limite_de_credito float Limite de credito del usuario
 	 * @param pagina_web string Pagina web del usuario
 	 * @param telefono2_2 string telefono2 de la direccion alterna del usuario
 	 * @param facturar_a_terceros bool Si el usuario puede facturar a terceros
 	 * @param mensajeria bool Si el usuario tiene una cuenta de mensajeria
 	 * @param telefono_personal_2 string Telefono personal alterno del usuario
 	 * @param ventas_a_credito int Ventas a credito del cliente
 	 * @param texto_extra_2 string Texto extra para ubicar la direccion alterna del usuario
 	 * @param impuestos json Objeto que contendra los ids de los impuestos que afectan a este usuario
 	 * @param retenciones json Ids de las retenciones que afectan a este usuario
 	 * @param saldo_del_ejercicio float Saldo del ejercicio del cliente
 	 * @param id_ciudad_2 int Id de la ciudad de la direccion alterna del usuario
 	 * @param dia_de_pago string Fecha de pago del cliente
 	 * @param calle string calle del domicilio del usuario
 	 * @param numero_interior_2 string Numero interior de la direccion alterna del usuario
 	 * @param codigo_postal string Codigo Postal del domicilio del usuario
 	 * @param texto_extra string Referencia del domicilio del usuario
 	 * @param numero_interior string Numero interior del domicilio del usuario
 	 * @param id_ciudad int Id de la ciudad del domicilio del usuario
 	 * @param password string Password del usuario
 	 * @param id_clasificacion_proveedor int Id de la clasificacion del proveedor
 	 * @param codigo_usuario string Codigo interno del usuario
 	 * @param nombre string Nombre del usuario
 	 * @param colonia string Colonia donde vive el usuario
 	 * @param comision_ventas float El porcentaje que gana como comision por ventas este usuario
 	 * @param correo_electronico string correo electronico del usuario
 	 * @param representante_legal string Nombre del representante legal del usuario
 	 * @param calle_2 string Calle de la direccion alterna del usuario
 	 * @param dias_de_embarque int Dias de emabrque del proveedor ( Lunes, Miercoles, etc)
 	 * @param telefono2 string Otro telefono de la direccion del usuario
 	 * @param dias_de_credito int Dias de credito del cliente
 	 * @param rfc string RFC del usuario
 	 * @param curp string CURP del usuario
 	 * @param numero_exterior_2 string Numero exterior de la direccion alterna del usuario
 	 * @param numero_exterior string Numero exterior del domicilio del usuario
 	 * @param denominacion_comercial string Denominacion comercial del cliente
 	 * @param descuento_es_porcentaje bool Si el descuento es un porcentaje o es un valor fijo
 	 * @param id_clasificacion_cliente int Id de la clasificacion del cliente
 	 * @param cuenta_bancaria string Cuenta bancaria del usuario
 	 * @param dia_de_revision string Fecha de revision del cliente
 	 * @param cuenta_mensajeria string Cuenta de mensajeria del usuario
 	 * @param telefono1 string Telefono del usuario
 	 * @param codigo_postal_2 string Codigo postal de la direccion alterna del usuario
 	 * @param id_sucursal int Id de la sucursal en la que fue creada este usuario o donde labora.
 	 * @param telefono1_2 string Telefono de la direccion alterna del usuario
 	 * @param intereses_moratorios float Intereses moratorios del cliente
 	 **/
	public static function EditarUsuario
	(
		$id_usuario, 
		$colonia_2 = null, 
		$id_rol = null, 
		$salario = null, 
		$descuento = null, 
		$telefono_personal_1 = null, 
		$limite_de_credito = null, 
		$pagina_web = null, 
		$telefono2_2 = null, 
		$facturar_a_terceros = null, 
		$mensajeria = null, 
		$telefono_personal_2 = null, 
		$ventas_a_credito = null, 
		$texto_extra_2 = null, 
		$impuestos = null, 
		$retenciones = null, 
		$saldo_del_ejercicio = null, 
		$id_ciudad_2 = null, 
		$dia_de_pago = null, 
		$calle = null, 
		$numero_interior_2 = null, 
		$codigo_postal = null, 
		$texto_extra = null, 
		$numero_interior = null, 
		$id_ciudad = null, 
		$password = null, 
		$id_clasificacion_proveedor = null, 
		$codigo_usuario = null, 
		$nombre = null, 
		$colonia = null, 
		$comision_ventas = null, 
		$correo_electronico = null, 
		$representante_legal = null, 
		$calle_2 = null, 
		$dias_de_embarque = null, 
		$telefono2 = null, 
		$dias_de_credito = null, 
		$rfc = null, 
		$curp = null, 
		$numero_exterior_2 = null, 
		$numero_exterior = null, 
		$denominacion_comercial = null, 
		$descuento_es_porcentaje = null, 
		$id_clasificacion_cliente = null, 
		$cuenta_bancaria = null, 
		$dia_de_revision = null, 
		$cuenta_mensajeria = null, 
		$telefono1 = null, 
		$codigo_postal_2 = null, 
		$id_sucursal = null, 
		$telefono1_2 = null, 
		$intereses_moratorios = null,
                $id_moneda = null,
                $tiempo_entrega = null
	)
	{  
            Logger::log("Editando usuario: ".$id_usuario);
            $validar=self::validarParametrosUsuario($id_usuario, null, $id_sucursal, $id_rol,
                    $id_clasificacion_cliente, $id_clasificacion_proveedor, $id_moneda, null,
                    $nombre, $rfc, $curp, $comision_ventas, $telefono_personal_1, $telefono_personal_2,
                    $limite_de_credito, $descuento, $password, $salario, $correo_electronico);
  
	}
  
	/**
 	 *
 	 *Lista los roles, se puede filtrar por empresa y ordenar por sus atributos
 	 *
 	 * @param orden json Objeto que determinara el orden de la lista
 	 * @return roles json Objeto que contendra la lista de los roles
 	 **/
	public static function ListaRol
	(
		$orden = ""
	)
	{  
		$roles = RolDAO::getAll();
		
  		return array( "roles" => $roles );
	}
  
	/**
 	 *
 	 *Asigna uno o varios permisos especificos a un usuario. No se pueden asignar permisos que ya se tienen
 	 *
 	 * @param id_usuario int Id del usuario al que se le asignara el permiso
 	 * @param id_permiso int Id del permiso que se le asignaran a este usuario en especial
 	 **/
	public static function AsignarPermisoUsuario
	(
		$id_usuario, 
		$id_permiso
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Este metodo asigna permisos a un rol. Cada vez que se llame a este metodo, se asignaran estos permisos a los usuarios que pertenezcan a este rol.
 	 *
 	 * @param id_permiso int Arreglo de ids de los permisos que seran asignados al rol
 	 * @param id_rol int Id del rol al que se le asignaran los permisos
 	 **/
	public static function AsignarPermisoRol
	(
		$id_permiso, 
		$id_rol
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Este metodo quita un permiso de un rol. Al remover este permiso de un rol, los permisos que un usuario especifico tiene gracias a una asignacion especial se mantienen. 
 	 *
 	 * @param id_permiso int Id del permiso a remover
 	 * @param id_rol int Id del rol al que se le quitaran los permisos
 	 **/
	public static function RemoverPermisoRol
	(
		$id_permiso, 
		$id_rol
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Quita uno o varios permisos a un usuario. No se puede negar un permiso que no se tiene
 	 *
 	 * @param id_permiso int Id del permiso a quitar de este usuario
 	 * @param id_usuario int Id del usuario al que se le niegan los permisos
 	 **/
	public static function RemoverPermisoUsuario
	(
		$id_permiso, 
		$id_usuario
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Crea un nuevo grupo de usuarios. Se asignaran los permisos de este grupo al momento de su creacion.
 	 *
 	 * @param descripcion string Descripcion larga del grupo
 	 * @param nombre string Nombre del grupo
 	 * @param descuento float El procentaje de descuento que este grupo gozara al comprar cualquier producto
 	 * @param salario float El salario de este rol
 	 * @return id_rol int Id del grupo que se genero
 	 **/
	public static function NuevoRol
	(
		$nombre, 
		$descripcion, 
		$descuento = 0, 
		$salario = 0
	)
	{  
            Logger::log("Creando nuevo rol");
            $validar=self::ValidarParametrosRol(null, $descripcion, $nombre, $descuento, $salario);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            $rol=new Rol(
                    array(
                        "nombre"        => $nombre,
                        "descripcion"   => $descripcion,
                        "descuento"     => $descuento,
                        "salario"       => $salario
                    )
                    );
            $roles=RolDAO::search(new Rol(array( "nombre" => trim($nombre) )));
            if(!empty($roles))
            {
                Logger::error("No se puede crear un rol con el mismo nombre que uno ya existente: ".$roles[0]->getNombre());
                throw new Exception("No se puede crear un rol con el mismo nombre que uno ya existente: ".$roles[0]->getNombre());
            }
            DAO::transBegin();
            try
            {
                RolDAO::save($rol);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("Error al crear el nuevo rol: ".$e);
                throw $e;
            }
            DAO::transEnd();
            Logger::log("Rol creado exitosamente con id: ".$rol->getIdRol());
            return array( "id_rol" => $rol->getIdRol());
	}
  
	/**
 	 *
 	 *Edita la informacion de un grupo, puede usarse para editar los permisos del mismo
 	 *
 	 * @param id_rol int Id del rol a editar
 	 * @param salario float Salario base para este rol
 	 * @param nombre string Nombre del grupo
 	 * @param descuento float Descuento que se le hara a este rol
 	 * @param descripcion string Descripcion larga del grupo
 	 **/
	public static function EditarRol
	(
		$id_rol, 
		$salario = null, 
		$nombre = "", 
		$descuento = null, 
		$descripcion = ""
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Este metodo desactiva un usuario, usese cuando un empleado ya no trabaje para usted. Que pasa cuando el usuario tiene cuentas abiertas o ventas a credito con saldo.
 	 *
 	 * @param id_usuario int Id del usuario a eliminar
 	 **/
	public static function EliminarUsuario
	(
		$id_usuario
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Este metodo desactiva un grupo, solo se podra desactivar un grupo si no hay ningun usuario que pertenezca a ?l.
 	 *
 	 * @param id_rol int Id del grupo a eliminar
 	 **/
	public static function EliminarRol
	(
		$id_rol
	)
	{  

            Logger::log("Eliminando rol: ".$id_rol);

            $validar = self::ValidarParametrosRol($id_rol);
            
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }

            $usuarios = UsuarioDAO::search(new Usuario(array( "id_rol" => $id_rol )));
            
            if(!empty($usuarios))
            {
                Logger::error("No se puede eliminar este rol pues hay ".sizeof( $usuarios )." usuarios asigandos a el. ");

                throw new Exception("No se puede eliminar este rol pues hay usuarios asigandos a el");
            }

            DAO::transBegin();

            try{
            	//@andres: http://the-stickman.com/web-development/php/php-505-fatal-error-only-variables-can-be-passed-by-reference/
            	$to_delete = RolDAO::getByPK( $id_rol );
                RolDAO::delete( $to_delete );

            }catch(Exception $e){
                DAO::transRollback();
                Logger::error("Error al eliminar el rol: ".$e);
                throw $e;

            }

            DAO::transEnd();
            Logger::log("Rol eliminado correctamente");
	}
  
	/**
 	 *
 	 *Regresa un alista de permisos, nombres y ids de los permisos del sistema.
 	 *
 	 **/
	public static function ListaPermisoRol
	(
                $id_rol = null,
                $id_permiso = null
	)
	{  

  
	}

        public static function ListaPermisoUsuario
        (
                $id_usuario = null,
                $id_permiso = null
        )
        {

        }
  }
