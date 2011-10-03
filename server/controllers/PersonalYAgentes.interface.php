<?php
/**
  *
  *
  *
  **/

  interaface IPersonal y agentes {
  
  
	/**
 	 *
 	 *Insertar un nuevo usuario. El usuario que lo crea sera tomado de la sesion actual y la fecha sera tomada del servidor. Un usuario no puede tener mas de un rol en una misma sucursal de una misma empresa.
 	 *
 	 **/
	protected function NuevoUsuario();  
  
  
  
  
	/**
 	 *
 	 *Listar a todos los usuarios del sistema. Se puede ordenar por los atributos del usuario y filtrar en activos e inactivos
 	 *
 	 **/
	protected function ListaUsuario();  
  
  
  
  
	/**
 	 *
 	 *Editar los detalles de un usuario.
 	 *
 	 **/
	protected function EditarUsuario();  
  
  
  
  
	/**
 	 *
 	 *Lista los roles, se puede filtrar por empresa y ordenar por sus atributos
 	 *
 	 **/
	protected function ListaRol();  
  
  
  
  
	/**
 	 *
 	 *Asigna uno o varios permisos especificos a un usuario. No se pueden asignar permisos que ya se tienen
 	 *
 	 **/
	protected function Asignar_permisosUsuario();  
  
  
  
  
	/**
 	 *
 	 *Este metodo asigna permisos a un rol. Cada vez que se llame a este metodo, se asignaran estos permisos a los usuarios que pertenezcan a este rol.
 	 *
 	 **/
	protected function Asignar_permisoRol();  
  
  
  
  
	/**
 	 *
 	 *Este metodo quita uno o varios permisos a un rol. Cuando este metodo es ejecutado, se quitan los permisos a todos los usuarios de este rol
 	 *
 	 **/
	protected function Quitar_permisoRol();  
  
  
  
  
	/**
 	 *
 	 *Quita uno o varios permisos a un usuario. No se puede negar un permiso que no se tiene
 	 *
 	 **/
	protected function Quitar_permisosUsuario();  
  
  
  
  
	/**
 	 *
 	 *Crea un nuevo grupo de usuarios. Se asignaran los permisos de este grupo al momento de su creacion.
 	 *
 	 **/
	protected function NuevoRol();  
  
  
  
  
	/**
 	 *
 	 *Edita la informacion de un grupo, puede usarse para editar los permisos del mismo
 	 *
 	 **/
	protected function EditarRol();  
  
  
  
  
	/**
 	 *
 	 *Este metodo desactiva un usuario, usese cuando un empleado ya no trabaje para usted.
 	 *
 	 **/
	protected function EliminarUsuario();  
  
  
  
  
	/**
 	 *
 	 *Este metodo desactiva un grupo, solo se podra desactivar un grupo si no hay ningun usuario que pertenezca a él.
 	 *
 	 **/
	protected function EliminarRol();  
  
  
  
  }
