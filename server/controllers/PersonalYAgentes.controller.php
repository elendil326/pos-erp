<?php
require_once("interfaces/PersonalYAgentes.interface.php");
/**
  *
  *
  *
  **/
	
  class PersonalYAgentesController implements IPersonalYAgentes{
  
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
         * Valida los parametros de la tabla rol haciendo uso de las validaciones basicas de string y num,
         * el maximo y el minimo se toman de la tabla y se aplican otras validaciones de acuerdo al uso
         * Regresa true cuando son validos, un string con el error cuando no lo es
         */
      private static function ValidarParametrosRol
      (
                $id_rol=null,
                $descripcion=null,
                $nombre = null,
		$descuento = null,
		$salario = null
      )
      {
          //Valida si el rol existe en la base de datos
          if(!is_null($id_rol))
          {
              if(is_null(RolDAO::getByPK($id_rol)))
              {
                  return "El rol con id: ".$id_rol." no existe";
              }
          }
          //valida la descripcion
          if(!is_null($descripcion))
          {
              $e=self::validarString($descripcion, 255, "descripcion");
                    if(is_string($e))
                        return $e;
          }
          //valida el nombre
          if(!is_null($nombre))
          {
              $e=self::validarString($nombre, 30, "nombre");
              if(is_string($e))
                  return $e;
          }
          //valida el descuento, el descuento es un porcentaje asi que no puede ser mayor a 100
          if(!is_null($descuento))
          {
              $e=self::validarNumero($descuento, 100, "descuento");
                    if(is_string($e))
                        return $e;
          }
          //valida e salario
          if(!is_null($salario))
          {
              $e=self::validarNumero($salario, 1.8e200, "salario");
                    if(is_string($e))
                        return $e;
          }
          return true;
      }

      /*
       * Valida los parametros de la tabla usuario haciendo uso de las valdiaciones basicas de strng y num,
       * se aplican validaciones extras de acuerdo a su uso
       * Regresa true cuando son validos, un string con el error cuando no lo es
       */
      public static function validarParametrosUsuario
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
          //valida que el id del usuario exista en la base de datos
          if(!is_null($id_usuario))
          {
              if(is_null(UsuarioDAO::getByPK($id_usuario)))
                  return "El usuario con id: ".$id_usuario." no existe";
          }
          //valida que el id de la direccion exista en la base de datos
          if(!is_null($id_direccion))
          {
              if(is_null(DireccionDAO::getByPK($id_direccion)))
                  return "La direccion con id: ".$id_direccion." no existe";
          }
          //valida el id de la sucursal exista en la base de datos
          if(!is_null($id_sucursal))
          {
              if(is_null(SucursalDAO::getByPK($id_sucursal)))
                  return "La sucursal con id: ".$id_sucursal." no existe";
          }
          //valida que el id del rol exista en la base de datos
          if(!is_null($id_rol))
          {
              if(is_null(RolDAO::getByPK($id_rol)))
                  return "El rol con id: ".$id_rol." no existe";
          }
          //valida que la clasificacion del cliente exista en la base de datos
          if(!is_null($id_clasificacion_cliente))
          {
              if(is_null(ClasificacionClienteDAO::getByPK($id_clasificacion_cliente)))
                  return "La clasificacion cliente con id: ".$id_clasificacion_cliente." no existe";
          }
          //valida que la clasificacion del proveedor exista en la base de datos
          if(!is_null($id_clasificacion_proveedor))
          {
              if(is_null(ClasificacionProveedorDAO::getByPK($id_clasificacion_proveedor)))
                  return "La clasficiacion proveedor con id: ".$id_clasificacion_proveedor." no existe";
          }
          //valida que la moneda exista en la base de datos
          if(!is_null($id_moneda))
          {
              if(is_null(MonedaDAO::getByPK($id_moneda)))
                  return "La moneda con id: ".$id_moneda." no existe";
          }
          //valida el nombre
          if(!is_null($nombre))
          {
              $e=self::validarString($nombre, 100, "nombre");
              if(is_string($e))
                  return $e;
          }
          //valida el rfc, el rfc solo puede estar compuesto por Letras mayusculas y numeros
          if(!is_null($rfc))
          {
              $e=self::validarString($rfc, 30, "rfc");
              if(is_string($e))
                  return $e;
              if(preg_match('/[^A-Z0-9]/' ,$rfc))
                  return "El rfc ".$rfc." contiene caracteres fuera del rango A-Z y 0-9";
          }
          //valida el curp, el curp solo puede tener letras mayusculas y numeros
          if(!is_null($curp))
          {
              $e=self::validarString($curp, 30, "curp");
              if(is_string($e))
                  return $e;
              if(preg_match('/[^A-Z0-9]/' ,$curp))
                  return "El curp ".$curp." contiene caracteres fuera del rango A-Z y 0-9";
          }
          //valida la comision por ventas
          if(!is_null($comision_ventas))
          {
              $e=self::validarNumero($comision_ventas, 100, "comision de ventas");
              if(is_string($e))
                  return $e;
          }
          //valida el telefono. Los telefonos solo pueden tener numeros, guiones,parentesis,asteriscos y espacios en blanco
          if(!is_null($telefono_personal1))
          {
              $e=self::validarString($telefono_personal1, 20, "Telefono personal");
              if(is_string($e))
                  return $e;
              if(preg_match('/[^0-9\- \(\)\*]/',$telefono_personal1))
                  return "El telefono ".$telefono_personal1." tiene caracteres fuera del rango 0-9,-,(,),* o espacio vacío";
          }
          //valida el telefono. Los telefonos solo pueden tener numeros, guiones,parentesis,asteriscos y espacios en blanco
          if(!is_null($telefono_personal2))
          {
              $e=self::validarString($telefono_personal2, 20, "Telefono personal alterno");
              if(is_string($e))
                  return $e;
              if(preg_match('/[^0-9\- \(\)\*]/',$telefono_personal2))
                  return "El telefono ".$telefono_personal2." tiene caracteres fuera del rango 0-9,-,(,),* o espacio vacío";
          }
          //valida el activo. Activo es una variable booleana.
          if(!is_null($activo))
          {
              $e=self::validarNumero($activo, 1, "activo");
              if(is_string($e))
                  return $e;
          }
          //valida el limite de credito
          if(!is_null($limite_credito))
          {
              $e=self::validarNumero($limite_credito, 1.8e200, "limite de credito");
              if(is_string($e))
                  return $e;
          }
          //valida el descuento. El descuento es un porcentaje y no puede ser mayor a 100
          if(!is_null($descuento))
          {
              $e=self::validarNumero($descuento, 100, "descuento");
              if(is_string($e))
                  return $e;
          }
          //valida el password, El pasword tiene que tener una longitud mayor o igual a 4
          if(!is_null($password))
          {
              $e=self::validarString($password, 1.8e200, "password",3);
              if(is_string($e))
                  return $e;
          }
          //valida el salario
          if(!is_null($salario))
          {
              $e=self::validarNumero($salario, 1.8e200, "salario");
              if(is_string($e))
                  return $e;
          }
          //valida el correo electronico segun las especificaciones de php
          if(!is_null($correo_electronico))
          {
              $e=self::validarString($correo_electronico, 30, "correo electronico");
              if(is_string($e))
                  return $e;
              if(!is_string(filter_var($correo_electronico, FILTER_VALIDATE_EMAIL)))
                      return "El correo electronico ".$correo_electronico." no es valido";
          }
          //valida que una pagina web tenga un formato valido.
          if(!is_null($pagina_web))
          {
              $e=self::validarString($pagina_web, 30, "pagina web");
              if(is_string($e))
                  return $e;
              if(!preg_match('/^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,6}'.'((:[0-9]{1,5})?\/.*)?$/i' ,$pagina_web)&&
                    !preg_match('/^[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,6}'.'((:[0-9]{1,5})?\/.*)?$/i' ,$pagina_web))
                            return "La direccion web ".$pagina_web." no cumple el formato valido";
          }
          //valida el saldo del ejercicio
          if(!is_null($saldo_del_ejercicio))
          {
              $e=self::validarNumero($saldo_del_ejercicio, 1.8e200, $saldo_del_ejercicio, -1.8e200);
              if(is_string($e))
                  return $e;
          }
          //valida las ventas a credito
          if(!is_null($ventas_a_credito))
          {
              $e=self::validarNumero($ventas_a_credito, PHP_INT_MAX, "ventas a credito");
              if(is_string($e))
                  return $e;
          }
          //valida el represnetante legal
          if(!is_null($representante_legal))
          {
              $e=self::validarString($representante_legal, 100, "representante legal");
              if(is_string($e))
                  return $e;
          }
          //valida la facturacion a terceros. Es un boleano
          if(!is_null($facturar_a_terceros))
          {
              $e=self::validarNumero($facturar_a_terceros, 1, "facturar a terceros");
              if(is_string($e))
                  return $e;
          }
          //valida los dias de pago
          if(!is_null($dia_de_pago))
          {
              $e=self::validarString($dia_de_pago, strlen("YYYY-mm-dd HH:ii:ss"), "dia de pago");
              if(is_string($e))
                  return $e;
          }
          //valida el boleano mensajeria
          if(!is_null($mensajeria))
          {
              $e=self::validarNumero($mensajeria, 1, "mensajeria");
              if(is_string($e))
                  return $e;
          }
          //valida los intereses moratorios
          if(!is_null($intereses_moratorios))
          {
              $e=self::validarNumero($intereses_moratorios, 1.8e200, "intereses moratorios");
              if(is_string($e))
                  return $e;
          }
          //valida la denominacion comercial
          if(!is_null($denominacion_comercial))
          {
              $e=self::validarString($denominacion_comercial, 100, "denominacion comercial");
              if(is_string($e))
                  return $e;
          }
          //valida los dias de credito
          if(!is_null($dias_de_credito))
          {
              $e=self::validarNumero($dias_de_credito, PHP_INT_MAX, "dias de credito");
              if(is_string($e))
                  return $e;
          }
          //valida la cuenta de mensajeria
          if(!is_null($cuenta_de_mensajeria))
          {
              $e=self::validarString($cuenta_de_mensajeria, 50, "cuenta de mensajeria");
              if(is_string($e))
                  return $e;
          }
          //valida lso dias de revision
          if(!is_null($dia_de_revision))
          {
              $e=self::validarString($dia_de_revision, strlen("YYYY-mm-dd HH:ii:ss"), "dia de revision");
              if(is_string($e))
                  return $e;
          }
          //valida el codigo de usuario
          if(!is_null($codigo_usuario))
          {
              $e=self::validarString($codigo_usuario, 50, "codigo de usuario");
              if(is_string($e))
                  return $e;
              if(preg_match('/[^a-zA-Z0-9]/', $codigo_usuario))
                      return "El codigo de usuario ".$codigo_usuario." no tiene solo caracteres alfanumericos";
          }
          //valida los dias de embarque
          if(!is_null($dias_de_embarque))
          {
              $e=self::validarNumero($dias_de_embarque, PHP_INT_MAX, "dias de embarque");
              if(is_string($e))
                  return $e;
          }
          //valida el tiempo de entrega
          if(!is_null($tiempo_entrega))
          {
              $e=self::validarNumero($tiempo_entrega, PHP_INT_MAX, "tiempo de entrega");
              if(is_string($e))
                  return $e;
          }
          //valida la cuenta bancaria
          if(!is_null($cuenta_bancaria))
          {
              $e=self::validarString($cuenta_bancaria, 50, "cuenta bancaria");
              if(is_string($e))
                  return $e;
          }
          return true;
      }


      /*
       * Valida los campos de la tabla Impuesto_usuario
       * Regresa true cuando son validos, un string con el error cuando no lo es
       */
      private static function validarParametrosImpuestoUsuario
      (
              $id_impuesto = null,
              $id_usuario = null
      )
      {
          //valida que el impuesto exista en la base de datos
        if(!is_null($id_impuesto))
        {
            if(is_null(ImpuestoDAO::getByPK($id_impuesto)))
            {
                return "El impuesto con id: ".$id_impuesto." no existe";
            }
        }
        //valida que el usuario exista en la base de datos
        if(!is_null($id_usuario))
        {
            if(is_null(UsuarioDAO::getByPK($id_usuario)))
            {
                return "El usuario con id: ".$id_usuario." no existe";
            }
        }
        return true;
      }


      /*
       * Valida los campos de al tabla retencion_usuario
       * Regresa true cuando son validos, un string con el error cuando no lo es
       */
      private static function validarParametrosRetencionUsuario
      (
              $id_retencion = null,
              $id_usuario = null
      )
      {
          //valida que la retencion exista en la base de datos
          if(!is_null($id_retencion))
          {
              if(is_null(RetencionDAO::getByPK($id_retencion)))
              {
                  return "La retencion con id: ".$id_retencion." no existe";
              }
          }
          //valida que el usuario exista en la base de datos
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
                $dias_de_embarque = null,
                $facturar_a_terceros = null,
                $id_sucursal = null,
                $mensajeria = null,
                $dia_de_pago = null,
                $impuestos = null,
                $representante_legal = null,
                $cuenta_bancaria = null,
                $saldo_del_ejercicio = null,
                $salario = null,
                $intereses_moratorios = null,
                $ventas_a_credito = null,
                $telefono_personal1 = null,
                $pagina_web = null,
                $descuento = null,
                $limite_credito = null,
                $telefono_personal2 = null,
                $telefono1_2 = null,
                $telefono2_2 = null,
                $codigo_postal = null,
                $codigo_postal_2 = null,
                $texto_extra_2 = null,
                $numero_interior_2 = null,
                $id_ciudad = null,
                $calle = null,
                $colonia_2 = null,
                $numero_interior = null,
                $id_ciudad_2 = null,
                $correo_electronico = null,
                $texto_extra = null,
                $telefono2 = null,
                $denominacion_comercial = null,
                $dias_de_credito = null,
                $calle_2 = null,
                $numero_exterior_2 = null,
                $telefono1 = null,
                $cuenta_mensajeria = null,
                $comision_ventas = null,
                $rfc = null,
                $id_clasificacion_proveedor = null,
                $colonia = null,
                $retenciones = null
	)
	{  
            Logger::log("Creando nuevo usuario");
            $validar = self::validarParametrosUsuario( null, null, $id_sucursal, $id_rol,
                    $id_clasificacion_cliente, $id_clasificacion_proveedor, $id_moneda,
                    null, $nombre, $rfc, $curp, $comision_ventas, $telefono_personal1,
                    $telefono_personal2, $limite_credito, $descuento, $password, $salario,
                    $correo_electronico,$pagina_web,$saldo_del_ejercicio,$ventas_a_credito,
                    $representante_legal,$facturar_a_terceros,$dia_de_pago,$mensajeria,
                    $intereses_moratorios,$denominacion_comercial,$dias_de_credito,
                    $cuenta_mensajeria,$dia_de_revision,$codigo_usuario,$dias_de_embarque,$tiempo_entrega,$cuenta_bancaria);

            //se verifica que la validacion haya sido correcta
            if(is_string($validar))
            {
                Logger::error("Imposible crear a nuevo usuario:" . $validar);

                throw new Exception($validar,901);
            }

            //se verifica que el codigo de usuario no sea repetido
            $usuarios=UsuarioDAO::search(new Usuario(array( "codigo_usuario" => $codigo_usuario )));
            foreach($usuarios as $usuario)
            {
                if($usuario->getActivo())
                {
                    Logger::error("El codigo de usuario ".$codigo_usuario." ya esta en uso");
                    throw new Exception("El codigo de usuario ".$codigo_usuario." ya esta en uso",901);
                }
            }

            //se verifica que el rfc no sea repetido
            $usuarios=UsuarioDAO::search(new Usuario(array( "rfc" => $rfc )));
            foreach($usuarios as $usuario)
            {
                if($usuario->getActivo())
                {
                    Logger::error("El rfc ".$rfc." ya existe");
                    throw new Exception("El rfc ".$rfc." ya existe",901);
                }
            }

            //se verifica que la curp no sea repetida
            $usuarios=UsuarioDAO::search(new Usuario(array( "curp" => $curp )));
            foreach($usuarios as $usuario)
            {
                if($usuario->getActivo())
                {
                    Logger::error("La curp ".$curp." ya existe");
                    throw new Exception("La curp ".$curp." ya existe",901);
                }
            }

            //se verifica que los telefonos no sean iguales
            if(!is_null($telefono_personal1)&&$telefono_personal1==$telefono_personal2)
            {
                Logger::error("El telefono personal es igual al telefno personal alterno: ".$telefono_personal1."  ".$telefono_personal2);
                throw new Exception("El telefono personal es igual al telefno personal alterno: ".$telefono_personal1."  ".$telefono_personal2,901);
            }

            //se verifica que el correo electronico no se repita
            if(!is_null($correo_electronico))
            {
                $usuarios=UsuarioDAO::search(new Usuario( array( "correo_electronico" => $correo_electronico ) ));
                foreach($usuarios as $usuario)
                {
                    if($usuario->getActivo())
                    {
                        Logger::error("El correo electronico ".$correo_electronico." ya esta en uso");
                        throw new Exception("El correo electronico ".$correo_electronico." ya esta en uso",901);
                    }
                }
            }

            //se verifica como medida de seguridad que el password no sea igual al codigo de usaurio ni al correo electronico
            if($password==$codigo_usuario||$password==$correo_electronico)
            {
                Logger::error("El password (".$password.") no puede ser igual al codigo de usuario
                    (".$codigo_usuario.") ni al correo electronico (".$correo_electronico.")");
                throw new Exception("El password (".$password.") no puede ser igual al codigo de usuario
                    (".$codigo_usuario.") ni al correo electronico (".$correo_electronico.")",901);
            }

            //se ponen los valores por default en limite de credito y saldo del ejercicio
            if(is_null($limite_credito))
                $limite_credito=0;
            if(is_null($saldo_del_ejercicio))
                $saldo_del_ejercicio=0;

            //se crea el objeto usuario con todos los parametros
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

            //se inicializan los ids de las direcciones
            $id_direccion1=-1;
            $id_direccion2=-1;
            DAO::transBegin();
            try
            {
                //si se paso un parametro obligatorio de la direccion, se intenta crear la misma
                if(!is_null($calle))
                {
                    $id_direccion1 = DireccionController::NuevaDireccion($calle, $numero_exterior, $colonia,
                            $id_ciudad, $codigo_postal, $numero_interior, $texto_extra, $telefono1, $telefono2);
                }

                //si se paso un parametro obligatorio de la direccion alterna, se intenta crear la misma
                if(!is_null($calle_2))
                {
                    $id_direccion2 = DireccionController::NuevaDireccion($calle_2, $numero_exterior_2, $colonia_2,
                            $id_ciudad_2, $codigo_postal_2, $numero_interior_2, $texto_extra_2, $telefono1_2, $telefono2_2);
                }

                //si se crearon las direcciones se asignan al usuario
                if($id_direccion1>0)
                    $usuario->setIdDireccion($id_direccion1);
                if($id_direccion2>0)
                    $usuario->setIdDireccionAlterna($id_direccion2);

                //Se guarda el usuario creado.
                UsuarioDAO::save($usuario);

                //si se pasaron impuestos, se validan y se agregan a la tabla impuesto_usuario
                if(!is_null($impuestos))
                {
                    foreach($impuestos as $id_impuesto)
                    {
                        $validar=self::validarParametrosImpuestoUsuario($id_impuesto);
                        if(is_string($validar))
                            throw new Exception($validar,901);
                        ImpuestoUsuarioDAO::save(new ImpuestoUsuario(array( "id_impuesto" => $id_impuesto , "id_usuario" => $usuario->getIdUsuario())));
                    }
                }

                //si se pasaron retenciones, se validan y se agregan a la tabla retencion_usuario
                if(!is_null($retenciones))
                {
                    foreach($retenciones as $id_retencion)
                    {
                        $validar=self::validarParametrosRetencionUsuario($id_retencion);
                        if(is_string($validar))
                            throw new Exception($validar,901);
                        RetencionUsuarioDAO::save(new RetencionUsuario(array( "id_retencion" => $id_retencion , "id_usuario" => $usuario->getIdUsuario() )));
                    }
                }

                //Se buscan los permisos de este rol y se le asignan a este usuario
                $permisos_rol = PermisoRolDAO::search( new PermisoRol( array( "id_rol" => $id_rol ) ) );
                foreach($permisos_rol as $permiso_rol)
                {
                    PermisoUsuarioDAO::save( new PermisoUsuario( array( "id_usuario" => $usuario->getIdUsuario() , "id_permiso" => $permiso_rol->getIdPermiso() ) ) );
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo crear al usuario: ".$e);
                if($e->getCode()==901)
                    throw new Exception("No se pudo crear al usuario: ".$e->getMessage(),901);
                throw new Exception("No se pudo crear al usuario, consulte a su administrador de sistema",901);
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
            Logger::log("Listando a los usuarios");
            //valida el parametro activo.
            $validar=self::validarParametrosUsuario(null, null, null, null, null, null, null, $activo);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar,901);
            }
            //inicializamos el arreglo que contendra la lista.
            $usuarios=array();

            //valida el parametro ordenar.
            //Ordenar tiene que ser un string que corresponda al nombre de un campo de la tabla usuario
            if(!is_null($ordenar))
            {
                $e=self::validarString($ordenar, 30, "ordenar");
                if(is_string($e))
                {
                    Logger::error($e);
                    throw new Exception($e,901);
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
                    Logger::error("El parametro ordenar: ".$ordenar." no es una columna de la tabla usuario");
                    throw new Exception("El parametro ordenar es invalido",901);
                }
            }
            //Si se paso el parametro activo, se llama al metodo search
            if(!is_null($activo))
            {
                $usuarios=UsuarioDAO::search(new Usuario( array( "activo" => $activo ) ),$ordenar);
            }

            //Si no, se llama al metodo getAll.
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

            //valida los parametros de la tabla usuario
            $validar=self::validarParametrosUsuario($id_usuario, null, $id_sucursal, $id_rol,
                    $id_clasificacion_cliente, $id_clasificacion_proveedor, $id_moneda,
                    null, $nombre, $rfc, $curp, $comision_ventas, $telefono_personal_1,
                    $telefono_personal_2, $limite_de_credito, $descuento, $password, $salario,
                    $correo_electronico,$pagina_web,$saldo_del_ejercicio,$ventas_a_credito,
                    $representante_legal,$facturar_a_terceros,$dia_de_pago,$mensajeria,
                    $intereses_moratorios,$denominacion_comercial,$dias_de_credito,
                    $cuenta_mensajeria,$dia_de_revision,$codigo_usuario,$dias_de_embarque,$tiempo_entrega,$cuenta_bancaria);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar,901);
            }

            //valida los parametros correspondientes a direccion
            $validar=DireccionController::validarParametrosDireccion(null, $calle, $numero_exterior, $numero_interior,
                    $texto_extra, $colonia, $id_ciudad, $codigo_postal, $telefono1, $telefono2);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar,901);
            }

            //valida los parametros correspondientes a direccion
            $validar=DireccionController::validarParametrosDireccion(null, $calle_2, $numero_exterior_2, $numero_interior_2, $texto_extra_2, $colonia_2, $id_ciudad_2, $codigo_postal_2, $telefono1_2, $telefono2_2);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar,901);
            }

            //Se trae el registro con el id obtenido
            $usuario = UsuarioDAO::getByPK($id_usuario);

            //Se intenta traer las direcciones actualmente registradas del usuario
            $direccion1 = DireccionDAO::getByPK($usuario->getIdDireccion());
            $direccion2 = DireccionDAO::getByPK($usuario->getIdDireccionAlterna());

            //banderas que indican si se cambió un campo de una direccion
            //o si la direccion es nueva
            $cambio_direccion1 = false;
            $cambio_direccion2 = false;
            $nueva_direccion1 = false;
            $nueva_direccion2 = false;

            //bandera que indica si el rol se cambio o no.
            $cambio_rol = false;

            //Si no se obtuvo direccion 1, se crea
            if(is_null($direccion1))
            {
                $nueva_direccion1=true;
                $direccion1=new Direccion();
            }

            //Si no se obtuvo direccion 2, se crea
            if(is_null($direccion2))
            {
                $nueva_direccion2=true;
                $direccion2=new Direccion();
            }

            // se validan los campos, si no son nulos, se cambia el registro.
          if(!is_null($id_sucursal))
          {
              $usuario->setIdSucursal($id_sucursal);
          }
          if(!is_null($id_rol))
          {
              if($usuario->getIdRol()!=$id_rol)
              {
                $usuario->setIdRol($id_rol);
                $usuario->setFechaAsignacionRol(date("Y-m-d H:i:s"));
                $cambio_rol=true;
              }
          }
          if(!is_null($id_clasificacion_cliente))
          {
              $usuario->setIdClasificacionCliente($id_clasificacion_cliente);
          }
          if(!is_null($id_clasificacion_proveedor))
          {
              $usuario->setIdClasificacionProveedor($id_clasificacion_proveedor);
          }
          if(!is_null($id_moneda))
          {
              $usuario->setIdMoneda($id_moneda);
          }
          if(!is_null($nombre))
          {
              $usuario->setNombre($nombre);
          }
          if(!is_null($rfc))
          {
              $usuario->setRfc($rfc);
          }
          if(!is_null($curp))
          {
              $usuario->setCurp($curp);
          }
          if(!is_null($comision_ventas))
          {
              $usuario->setComisionVentas($comision_ventas);
          }
          if(!is_null($telefono_personal_1))
          {
              $usuario->setTelefonoPersonal1($telefono_personal_1);
          }
          if(!is_null($telefono_personal_2))
          {
              $usuario->setTelefonoPersonal2($telefono_personal_2);
          }
          if(!is_null($limite_de_credito))
          {
              $usuario->setLimiteCredito($limite_de_credito);
          }
          if(!is_null($descuento))
          {
              $usuario->setDescuento($descuento);
          }
          if(!is_null($password))
          {
              $usuario->setPassword(hash("md5",$password));
          }
          if(!is_null($salario))
          {
              $usuario->setSalario($salario);
          }
          if(!is_null($correo_electronico))
          {
              $usuario->setCorreoElectronico($correo_electronico);
          }
          if(!is_null($pagina_web))
          {
              $usuario->setPaginaWeb($pagina_web);
          }
          if(!is_null($saldo_del_ejercicio))
          {
              $usuario->setSaldoDelEjercicio($saldo_del_ejercicio);
          }
          if(!is_null($ventas_a_credito))
          {
              $usuario->setVentasACredito($ventas_a_credito);
          }
          if(!is_null($representante_legal))
          {
              $usuario->setRepresentanteLegal($representante_legal);
          }
          if(!is_null($facturar_a_terceros))
          {
              $usuario->setFacturarATerceros($facturar_a_terceros);
          }
          if(!is_null($dia_de_pago))
          {
              $usuario->setDiaDePago($dia_de_pago);
          }
          if(!is_null($mensajeria))
          {
              $usuario->setMensajeria($mensajeria);
          }
          if(!is_null($intereses_moratorios))
          {
              $usuario->setInteresesMoratorios($intereses_moratorios);
          }
          if(!is_null($denominacion_comercial))
          {
              $usuario->setDenominacionComercial($denominacion_comercial);
          }
          if(!is_null($dias_de_credito))
          {
              $usuario->setDiasDeCredito($dias_de_credito);
          }
          if(!is_null($cuenta_mensajeria))
          {
              $usuario->setCuentaDeMensajeria($cuenta_mensajeria);
          }
          if(!is_null($dia_de_revision))
          {
              $usuario->setDiaDeRevision($dia_de_revision);
          }
          if(!is_null($codigo_usuario))
          {
              $usuario->setCodigoUsuario($codigo_usuario);
          }
          if(!is_null($dias_de_embarque))
          {
              $usuario->setDiasDeEmbarque($dias_de_embarque);
          }
          if(!is_null($tiempo_entrega))
          {
              $usuario->setTiempoEntrega($tiempo_entrega);
          }
          if(!is_null($cuenta_bancaria))
          {
              $usuario->setCuentaBancaria($cuenta_bancaria);
          }
            if(!is_null($calle))
            {
                $direccion1->setCalle($calle);
                $cambio_direccion1=true;
            }
            if(!is_null($numero_exterior))
            {
                $direccion1->setNumeroExterior($numero_exterior);
                $cambio_direccion1=true;
            }
            if(!is_null($numero_interior))
            {
                $direccion1->setNumeroInterior($numero_interior);
                $cambio_direccion1=true;
            }
            if(!is_null($texto_extra))
            {
                $direccion1->setReferencia($texto_extra);
                $cambio_direccion1=true;
            }
            if(!is_null($colonia))
            {
                $direccion1->setColonia($colonia);
                $cambio_direccion1=true;
            }
            if(!is_null($id_ciudad))
            {
                $direccion1->setIdCiudad($id_ciudad);
                $cambio_direccion1=true;
            }
            if(!is_null($codigo_postal))
            {
                $direccion1->setCodigoPostal($codigo_postal);
                $cambio_direccion1=true;
            }
            if(!is_null($telefono1))
            {
                $direccion1->setTelefono($telefono1);
                $cambio_direccion1=true;
            }
            if(!is_null($telefono2))
            {
                $direccion1->setTelefono2($telefono2);
                $cambio_direccion1=true;
            }
            if(!is_null($calle_2))
            {
                $direccion2->setCalle($calle_2);
                $cambio_direccion2=true;
            }
            if(!is_null($numero_exterior_2))
            {
                $direccion2->setNumeroExterior($numero_exterior_2);
                $cambio_direccion2=true;
            }
            if(!is_null($numero_interior_2))
            {
                $direccion2->setNumeroInterior($numero_interior_2);
                $cambio_direccion2=true;
            }
            if(!is_null($texto_extra_2))
            {
                $direccion2->setReferencia($texto_extra_2);
                $cambio_direccion2=true;
            }
            if(!is_null($colonia_2))
            {
                $direccion2->setColonia($colonia_2);
                $cambio_direccion2=true;
            }
            if(!is_null($id_ciudad_2))
            {
                $direccion2->setIdCiudad($id_ciudad_2);
                $cambio_direccion2=true;
            }
            if(!is_null($codigo_postal_2))
            {
                $direccion2->setCodigoPostal($codigo_postal_2);
                $cambio_direccion2=true;
            }
            if(!is_null($telefono1_2))
            {
                $direccion2->setTelefono($telefono1_2);
                $cambio_direccion2=true;
            }
            if(!is_null($telefono2_2))
            {
                $direccion2->setTelefono2($telefono2_2);
                $cambio_direccion2=true;
            }
            DAO::transBegin();
            try
            {
                //Si hubo un cambio en la direccion 1, se realiza el cambio
                if($cambio_direccion1)
                {
                    $direccion1->setUltimaModificacion(date("Y-m-d H:i:s"));

                    //Se busca el id del usuario loggeado. Si no hay ningun muestra un error.
                    $id_u=LoginController::getCurrentUser();
                    if(is_null($id_u))
                    {
                        throw new Exception("No se pudo obtener el usuario de la sesion, ya inicio sesion?",901);
                    }
                    $direccion1->setIdUsuarioUltimaModificacion($id_u);
                    DireccionDAO::save($direccion1);

                    //Si la direccion es nueva, se relaciona con el usuario.
                    if($nueva_direccion1)
                    {
                        $usuario->setIdDireccion($direccion1->getIdDireccion());
                    }
                }

                //Si hubo un cambio en la direccion alterna, se realiza el cambio
                if($cambio_direccion2)
                {
                    $direccion2->setUltimaModificacion(date("Y-m-d H:i:s"));

                    //Se busca el id del usuario loggeado. Si no hay ningun muestra un error.
                    $id_u=LoginController::getCurrentUser();
                    if(is_null($id_u))
                    {
                        throw new Exception("No se pudo obtener el usuario de la sesion, ya inicio sesion?",901);
                    }
                    $direccion2->setIdUsuarioUltimaModificacion($id_u);
                    DireccionDAO::save($direccion2);

                    //Si la direccion es nueva, se relaciona con el usuario.
                    if($nueva_direccion2)
                    {
                        $usuario->setIdDireccion($direccion2->getIdDireccion());
                    }
                }

                //guarda los cambios en el usuario
                UsuarioDAO::save($usuario);

                //si se han obtenido nuevos impuestos se llama al metodo save para que actualice
                //los ya existentes y almacene los nuevos
                if(!is_null($impuestos))
                {
                    foreach($impuestos as $id_impuesto)
                    {
                        ImpuestoUsuarioDAO::save(new ImpuestoUsuario( array( "id_impuesto" => $id_impuesto , "id_usuario" => $id_usuario ) ));
                    }

                    //Se obtiene la lista de impuestos correspondientes a este usuario y se buscan aquellos
                    //que no esten incluidos en la nueva lista obtenida de impuestos para eliminarlos.
                    $impuestos_usuario = ImpuestoUsuarioDAO::search( new ImpuestoUsuario( array( "id_usuario" => $id_usuario ) ));
                    foreach($impuestos_usuario as $impuesto_usuario)
                    {
                        $encontrado=false;
                        foreach($impuestos as $id_impuesto)
                        {
                            if($id_impuesto == $impuesto_usuario->getIdImpuesto())
                            {
                                $encontrado = true;
                            }
                        }
                        if(!$encontrado)
                        {
                            ImpuestoUsuarioDAO::delete($impuesto_usuario);
                        }
                    }
                }

                //si se han obtenido nuevas retenciones se llama al metodo save para que actualice
                //las ya existentes y almacene las nuevas
                if(!is_null($retenciones))
                {
                    foreach($retenciones as $id_retencion)
                    {
                        RetencionUsuarioDAO::save(new RetencionUsuario( array( "id_retencion" => $id_retencion , "id_usuario" => $id_usuario ) ));
                    }

                    //Se obtiene la lista de retenciones correspondientes a este usuario y se buscan aquellas
                    //que no esten incluidas en la nueva lista obtenida de retenciones para eliminarlas.
                    $retenciones_usuario = RetencionUsuarioDAO::search( new RetencionUsuario( array( "id_usuario" => $id_usuario ) ));
                    foreach($retenciones_usuario as $retencion_usuario)
                    {
                        $encontrado=false;
                        foreach($retenciones as $id_retencion)
                        {
                            if($id_retencion == $retencion_usuario->getIdRetencion())
                            {
                                $encontrado = true;
                            }
                        }
                        if(!$encontrado)
                        {
                            RetencionUsuarioDAO::delete($retencion_usuario);
                        }
                    }
                }

                //Si cambio el rol, se borran todos los permisos del usuario y se le asignan los
                //permisos del nuevo rol.
                $permisos_usuario = PermisoUsuarioDAO::search( new PermisoUsuario( array( "id_usuario" => $id_usuario ) ) );
                foreach($permisos_usuario as $permiso_usuario)
                {
                    PermisoUsuarioDAO::delete($permiso_usuario);
                }
                $permisos_rol = PermisoRolDAO::search( new PermisoRol( array( "id_rol" => $id_rol ) ) );
                foreach($permisos_rol as $permiso_rol)
                {
                    PermisoUsuarioDAO::save( new PermisoUsuario( array( "id_usuario" => $id_usuario , "id_permiso" => $permiso_rol->getIdPermiso() ) ) );
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("El usuario ".$id_usuario." no ha podido se editado: ".$e);
                if($e->getCode()==901)
                    throw new Exception("No se pudo editar al usuario: ".$e->getMessage(),901);
                throw new Exception("No se pudo editar al usuario, consulte a su administrador de sistema",901);
            }
            DAO::transEnd();
            Logger::log("Usuario ".$id_usuario." editado exitosamente");
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
		$orden = null
	)
	{
                Logger::log("Listando roles con orden: ".$orden);

                //Se valida si el orden es valido. Orden tiene que ser el nombre de un campo
                //de la tabla rol
                if
                (
                        $orden != "id_rol" &&
                        $orden != "nombre" &&
                        $orden != "descripcion" &&
                        $orden != "descuento" &&
                        $orden != "salario" &&
                        !is_null($orden)
                )
                {
                    Logger::log("La variable orden: ".$orden." no es una columna de la tabla rol");
                    throw new Exception("La variable orden no es valida",901);
                }

                //Se traen todos los roles d ela base de datos.
		$roles = RolDAO::getAll(null,null,$orden);
                Logger::log("Lista de roles obtenida con ".count($roles)." elementos");
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
            Logger::log("Asignando permiso ".$id_permiso." al usuario ".$id_usuario);

            //Se valida que el permiso exista en la base de datos.
            if(is_null(PermisoDAO::getByPK($id_permiso)))
            {
                Logger::error("El permiso con id:".$id_permiso." no existe");
                throw new Exception("El permiso no existe",901);
            }

            //Se valida que el usuario exista en la base de datos.
            $usuario=UsuarioDAO::getByPK($id_usuario);
            if(is_null($usuario))
            {
                Logger::error("El usuario con id:".$id_usuario." no existe");
                throw new Exception("El usuario no existe",901);
            }

            //Si el usuario no esta activo no se le pueden asignar permisos
            if(!$usuario->getActivo())
            {
                Logger::error("El usuario con id:".$id_usuario." esta inactivo");
                throw new Exception("El usuario esta inactivo y no se puede modificar",901);
            }
            DAO::transBegin();
            try
            {
                //se guarda el permiso con el usuario
                PermisoUsuarioDAO::save(new PermisoUsuario( array( "id_permiso" => $id_permiso , "id_usuario" => $id_usuario ) ));
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo asignar el permiso al usuario: ".$e);
                throw new Exception("No se pudo asignar el permiso al usuario, consulte a su administrador de sistema",901);
            }
            DAO::transEnd();
            Logger::log("Permiso asignado exitosamente");
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
            Logger::log("Asignando permiso ".$id_permiso." al rol ".$id_rol);

            //Se valida que el permiso exista en la base de datos
            if(is_null(PermisoDAO::getByPK($id_permiso)))
            {
                Logger::error("El permiso con id: ".$id_permiso." no existe");
                throw new Exception("El permiso no existe",901);
            }

            //Se valida que el rol exista en la base de datos
            if(is_null(RolDAO::getByPK($id_rol)))
            {
                Logger::error("El rol con id: ".$id_rol." no existe");
                throw new Exception("El rol no existe",901);
            }

            //Se obtiene la lisa de usuarios que pertenecen a este rol
            $usuarios=UsuarioDAO::search(new Usuario( array( "id_rol" => $id_rol ) ));
            DAO::transBegin();
            try
            {
                //Por cada uno de los usuarios como usuario, se valida si el usuario este activo.
                //Si lo esta, se le asigna el permiso que se le esta asignando al rol.
                foreach($usuarios as $usuario)
                {
                    if($usuario->getActivo())
                        PermisoUsuarioDAO::save(new PermisoUsuario( array( "id_permiso" => $id_permiso , "id_usuario" => $usuario->getIdUsuario() ) ));
                }

                //Se guarda el permiso al rol.
                PermisoRolDAO::save(new PermisoRol( array( "id_permiso" => $id_permiso , "id_rol" => $id_rol ) ));
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo asignar el permiso al rol: ".$e);
                throw new Excpetion("No se pudo asignar el permiso al rol, consulte a su administrador de sistema");
            }
            DAO::transEnd();
            Logger::log("Permiso asignado exitosamente");
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
            Logger::log("Quitando permiso ".$id_permiso." al rol ".$id_rol);

            //Se valida que ese rol tenga ese permiso
            $permiso_rol = PermisoRolDAO::getByPK($id_permiso, $id_rol);
            if(is_null($permiso_rol))
            {
                Logger::error("El rol ".$id_rol." no tiene el permiso ".$id_permiso);
                throw new Exception("El rol no tiene ese permiso",901);
            }

            //Se obtienen los usuarios de ese rol
            $usuarios = UsuarioDAO::search( new Usuario( array( "id_rol" => $id_rol ) ) );
            DAO::transBegin();
            try
            {
                //Por cada uno de los usuarios como usuario, se busca que el usuario
                //tenga el permiso a remover y que este activo. Si el usuario cumple, se le quita el permiso.
                foreach($usuarios as $usuario)
                {
                    if(!is_null(PermisoUsuarioDAO::getByPK($id_permiso, $usuario->getIdUsuario()))&&$usuario->getActivo())
                    {
                        PermisoUsuarioDAO::delete(new PermisoUsuario( array( "id_permiso" => $id_permiso , "id_usuario" => $usuario->getIdUsuario() ) ));
                    }
                }

                //Se quita el permiso al rol
                PermisoRolDAO::delete($permiso_rol);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
            }
            DAO::transEnd();
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
            Logger::log("Quitando permiso ".$id_permiso." a usuario ".$id_usuario);

            //Se valida que el usuario tenga ese permiso
            $permiso_usuario = PermisoUsuarioDAO::getByPK($id_permiso, $id_usuario);
            if(is_null($permiso_usuario))
            {
                Logger::error("El usuario ".$id_usuario." no tiene el permiso ".$id_permiso);
                throw new Exception("El usuario no tiene ese permiso",901);
            }

            //Se valida que el usuario exista en la base de datos.
            $usuario=UsuarioDAO::getByPK($id_usuario);
            if(is_null($usuario))
            {
                Logger::error("El usuario con id:".$id_usuario." no existe");
                throw new Exception("El usuario no existe",901);
            }

            //Si el usuario no esta activo no se puede cambiar su relacion.
            if(!$usuario->getActivo())
            {
                Logger::error("El usuario con id:".$id_usuario." esta inactivo");
                throw new Exception("El usuario esta inactivo y no se puede modificar",901);
            }
            DAO::transBegin();
            try
            {
                //Borra el permiso del usuario
                PermisoUsuarioDAO::delete($permiso_usuario);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo eliminar el permiso del usuario: ".$e);
                throw new Exception("No se pudo quitar el permiso del usuario, consulte a su administrador de sistema",901);
            }
            DAO::transEnd();
            Logger::log("Permiso eliminado exitosamente");
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

            //Se validan lso parametros del rol
            $validar=self::ValidarParametrosRol(null, $descripcion, $nombre, $descuento, $salario);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar,901);
            }

            //Se inicializa el nuevo rol con los parametros obtenidos
            $rol=new Rol(
                    array(
                        "nombre"        => trim($nombre),
                        "descripcion"   => $descripcion,
                        "descuento"     => $descuento,
                        "salario"       => $salario
                    )
                    );

            //Se busca el nombre obtenido en la base de datos. Si existe
            //se manda un error pues los nombres no se pueden repetir.
            //Se usa trim para validar casos como "gerente" y "  gerente ".
            $roles=RolDAO::search(new Rol(array( "nombre" => trim($nombre) )));
            if(!empty($roles))
            {
                Logger::error("No se puede crear un rol con el mismo nombre que uno ya existente: ".$roles[0]->getNombre());
                throw new Exception("No se puede crear un rol con el mismo nombre que uno ya existente: ".$roles[0]->getNombre(),901);
            }
            DAO::transBegin();
            try
            {
                //Se guarda el rol.
                RolDAO::save($rol);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("Error al crear el nuevo rol: ".$e);
                throw new Exception("Error al crear el nuevo rol, consulte a su administrador de sistema",901);
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
		$nombre = null,
		$descuento = null, 
		$descripcion = null
	)
	{  
            Logger::log("Editando rol ".$id_rol);

            //Se validan los parametros obtenidos.
            $validar = self::ValidarParametrosRol($id_rol, $descripcion, $nombre, $descuento, $salario);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar,901);
            }

            //Se obtiene el rol de la base de datos.
            $rol = RolDAO::getByPK($id_rol);

            //Se verifican los parametros. Los que no son nulos se actualizan.
            if(!is_null($salario))
            {
                $rol->setSalario($salario);
            }
            if(!is_null($nombre))
            {
                $rol->setNombre($nombre);
            }
            if(!is_null($descuento))
            {
                $rol->setDescuento($descuento);
            }
            if(!is_null($descripcion))
            {
                $rol->setDescripcion($descripcion);
            }
            DAO::transBegin();
            try
            {
                //Se actualiza el rol.
                RolDAO::save($rol);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo editar el rol: ".$e);
                throw new Exception("No se pudo editar el rol, consulte a su administrador de sistema",901);
            }
            DAO::transEnd();
            Logger::log("Rol editado exitosamente");
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
            Logger::log("Eliminando usuario ".$id_usuario);

            //Se obtiene y se verifica que el usuario exista en la base de datos.
            $usuario=UsuarioDAO::getByPK($id_usuario);
            if(is_null($usuario))
            {
                Logger::error("El usuario con id ".$id_usuario." no existe");
                throw new Exception("El usuario no existe",901);
            }

            //Si el usuario ya esta inactivo, no se le hacen cambios.
            if(!$usuario->getActivo())
            {
                Logger::warn("El usuario con id: ".$id_usuario." ya esta inactivo");
                throw new Exception("El usuario ya esta inactivo",901);
            }

            //Si el saldo del ejercicio del usuario no es cero, siginifica que debe o que
            //se le debe, entonces no se puede eliminar.
            if($usuario->getSaldoDelEjercicio()!=0)
            {
                Logger::error("El usuario con id: ".$id_usuario." no tiene un saldo en ceros");
                throw new Exception("El usuario no tiene un saldo en ceros",901);
            }

            //Se cambia su estado activo a falso y se le asigna como hoy la fecha de baja.
            $usuario->setActivo(0);
            $usuario->setFechaBaja(date("Y-m-d H:i:s"));
            DAO::transBegin();
            try
            {
                //Se actualiza el usuario
                UsuarioDAO::save($usuario);

                //Se eliminan los registros de la tabla permiso_usuario que contengan a este usuario
                $permisos_usuario = PermisoUsuarioDAO::search(new PermisoUsuario( array( "id_usuario" => $id_usuario ) ));
                foreach($permisos_usuario as $permiso_usuario)
                {
                    PermisoUsuarioDAO::delete($permiso_usuario);
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo eliminar el usuario: ".$e);
                throw new Exception("No se pudo eliminar el usuario, consulte a su administrador de sistema",901);
            }
            DAO::transEnd();
            Logger::log("Usuario eliminado exitosamente");
	}
  
	/**
 	 *
 	 *Este metodo desactiva un grupo, solo se podra desactivar un grupo si no hay ningun usuario que pertenezca a el.
 	 *
 	 * @param id_rol int Id del grupo a eliminar
 	 **/
	public static function EliminarRol
	(
		$id_rol
	)
	{  

            Logger::log("Eliminando rol: ".$id_rol);

            //valida que el rol exista en la base de datos.
            $validar = self::ValidarParametrosRol($id_rol);
            
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar,901);
            }

            //Se obtiene la lista de usuarios con este rol. Si almenos uno aun sigue activo,
            //entonces no se puede eliminar el rol.
            $usuarios = UsuarioDAO::search(new Usuario(array( "id_rol" => $id_rol )));
            
            foreach($usuarios as $usuario)
            {
                if($usuario->getActivo())
                {
                    Logger::error("No se puede eliminar este rol pues el usuario ".$usuario->getIdUsuario." lo tiene asignado");
                    throw new Exception("No se puede eliminar este rol pues hay almenos un usuario asignado a el",901);
                }
            }

            DAO::transBegin();

            try{
            	//Se elimina el rol
            	$to_delete = RolDAO::getByPK( $id_rol );
                RolDAO::delete( $to_delete );

                //Se eliminan todos los registros de la tabla permiso_rol que contengan este rol
                $permisos_rol = PermisoRolDAO::search(new PermisoRol( array( "id_rol" => $id_rol ) ));
                foreach($permisos_rol as $permiso_rol)
                {
                    PermisoRolDAO::delete($permiso_rol);
                }

            }catch(Exception $e){
                DAO::transRollback();
                Logger::error("Error al eliminar el rol: ".$e);
                throw new Exception("Error al eliminar el rol, consulte a su administrador de sistema",901);

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
            Logger::log("Listando roles con sus permisos");

            //Si se ha obtenido alguno de los parametros, se llama al metodo search.
            //Si no, se llama a getAll.
            if(!is_null($id_rol) || !is_null($id_permiso))
                $permisos_roles = PermisoRolDAO::search(new PermisoRol(array( "id_rol" => $id_rol , "id_permiso" => $id_permiso )));
            else
                $permisos_roles = PermisoRolDAO::getAll();
            Logger::log("Lista de roles con sus permisos obtenida exitosamente con ".count($permisos_roles)." elementos");
            return array( "permisos_roles" => $permisos_roles);
	}

        public static function ListaPermisoUsuario
        (
                $id_usuario = null,
                $id_permiso = null
        )
        {
            Logger::log("Listando usuarios con sus permisos");

            //Si se ha obtenido alguno de los parametros, se llama al metodo search,
            //si no, se llama a getAll.
            if(!is_null($id_usuario) || !is_null($id_permiso))
                $permisos_usuario = PermisoUsuarioDAO::search(new PermisoUsuario(array( "id_usuario" => $id_usuario , "id_permiso" => $id_permiso )));
            else
                $permisos_usuario = PermisoUsuarioDAO::getAll();
            Logger::log("Lista de usuarios con sus permisos obtenida exitosamente con ".count($permisos_usuario)." elementos");
            return array( "permisos_usuario" => $permisos_usuario);
        }
  }
