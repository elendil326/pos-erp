<?php
require_once("interfaces/PersonalYAgentes.interface.php");
/**
  *
  *
  *
  **/
	
  class PersonalYAgentesController implements IPersonalYAgentes{
  

        private static function validarString($string, $max_length, $nombre_varibale,$min_length=0)
          {
                $error="";
                if(strlen($string)<=$min_length||strlen($string)>$max_length)
                {
                    $error="La longitud de la variable ".$nombre_variable." proporcionada no esta en el rango de ".$min_length." - ".$max_length;
                    return $error;
                }
                return true;
          }

          private static function validarNumero($num, $max_length, $nombre_variable, $min_length=0)
          {
                $error="";
                if($num<=$min_length||$num>$max_length)
                {
                    $error="La variable ".$nombre_variable." proporcionada no esta en el rango de ".$min_length." - ".$max_length;
                    return $error;
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
          $error="";
          if(!is_null($id_rol))
          {
              if(is_null(RolDAO::getByPK($id_rol)))
              {
                  $error="El rol con id: ".$id_rol." no existe";
                  return $error;
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
		$password, 
		$id_rol, 
		$nombre, 
		$codigo_usuario, 
		$facturar_a_terceros = null, 
		$id_sucursal = null, 
		$mensajeria = null, 
		$mpuestos = null, 
		$dia_de_pago = null, 
		$cuenta_bancaria = null, 
		$representante_legal = null, 
		$saldo_del_ejercicio = null, 
		$salario = null, 
		$intereses_moratorios = null, 
		$ventas_a_credito = null, 
		$telefono_personal1 = "", 
		$descuento = null, 
		$pagina_web = null, 
		$limite_credito = 0, 
		$telefno_personal2 = null, 
		$telefono1_2 = null, 
		$codigo_postal = null, 
		$telefono2_2 = null, 
		$codigo_postal_2 = null, 
		$texto_extra_2 = null, 
		$numero_interior_2 = null, 
		$id_ciudad = null, 
		$colonia_2 = null, 
		$calle = null, 
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
		$dias_de_embarque = null, 
		$numero_exterior = null, 
		$id_clasificacion_cliente = null, 
		$curp = null, 
		$dia_de_revision = null, 
		$cuenta_mensajeria = null, 
		$comision_ventas = null, 
		$rfc = null, 
		$id_clasificacion_proveedor = null, 
		$colonia = null, 
		$retenciones = null
	)
	{  
  
  
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
		$intereses_moratorios = null
	)
	{  
  
  
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
		$descripcion, 
		$nombre, 
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
            $validar=self::ValidarParametrosRol($id_rol);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            $usuarios=UsuarioDAO::search(new Usuario(array( "id_rol" => $id_rol )));
            if(!empty($usuarios))
            {
                Logger::error("No se puede eliminar este rol pues hay usuarios asigandos a el");
                throw new Exception("No se puede eliminar este rol pues hay usuarios asigandos a el");
            }
            DAO::transBegin();
            try
            {
                RolDAO::delete(RolDAO::getByPK($id_rol));
            }
            catch(Exception $e)
            {
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
	)
	{  
  
  
	}
  }
