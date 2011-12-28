<?php
require_once("interfaces/Sucursales.interface.php");
/**
  *
  *
  *
  **/


	
  class SucursalesController implements ISucursales{

      //funcion para pruebas que obtiene el id de la caja de la sesion.
      private static function getCaja()
      {
          return 1;
      }

      //funcion para pruebas que obtiene el id de la sucursal de la sesion.
      private static function getSucursal()
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
         * Valida los parametros de la tabla sucursal. Cuando algun parametro es incorrecto regresa
         * un string, cuando no hay error regresa true
         */
        private static function validarParametrosSucursal
        (
                $id_sucursal = null,
                $id_direccion = null,
                $rfc = null,
                $razon_social = null,
                $descripcion = null,
                $id_gerente = null,
                $saldo_a_favor = null,
                $fecha_apertura = null,
                $activa = null,
                $fecha_baja = null,
                $margen_utilidad = null,
                $descuento = null
        )
        {
            //Se valida que la sucursal exista en la base de datos
            if(!is_null($id_sucursal))
            {
                if(is_null(SucursalDAO::getByPK($id_sucursal)))
                {
                    return "La sucursal con id: ".$id_sucursal." no existe";
                }
            }
            
            //Se valida que la direccion exista en la base de dats
            if(!is_null($id_direccion))
            {
                if(is_null(DireccionDAO::getByPK($id_direccion)))
                {
                    return "La direccion con id: ".$id_direccion." no existe";
                }
            }
            
            //Se valida que el rfc solo tenga letras de la A-Z y 0-9. No se debe repetir el rfc
            if(!is_null($rfc))
            {
                $e=self::validarString($rfc, 30, "rfc");
                if(is_string($e))
                    return $e;
                if(preg_match('/[^A-Z0-9]/' ,$rfc))
                        return "El rfc ".$rfc." contiene caracteres fuera del rango A-Z y 0-9";
                
                $sucursales = SucursalDAO::search( new Sucursal( array( "rfc" => $rfc )) );
                foreach($sucursales as $sucursal)
                {
                    if($sucursal->getActiva())
                        return "El rfc (".$rfc.") ya esta en uso por la sucursal ".$sucursal->getIdSucursal();
                }
            }
            
            //Se valida que la razon social tenga una longitud maxima de 100, no se puede repetir
            if(!is_null($razon_social))
            {
                $e=self::validarString($razon_social, 100, "razon social");
                if(is_string($e))
                    return $e;
                
                $sucursales = SucursalDAO::search( new Sucursal( array( "razon_social" => trim($razon_social) )) );
                foreach($sucursales as $sucursal)
                {
                    if($sucursal->getActiva())
                        return "La razon social (".$razon_social.") ya esta en uso por la sucursal ".$sucursal->getIdSucursal();
                }
            }
            
            //Se valida que la descripcion tenga una longitud maxima de 255
            if(!is_null($descripcion))
            {
                $e=self::validarString($descripcion, 255, "descripcion");
                if(is_string($e))
                    return $e;
            }
            
            //Se valida que el usuario gerente exista y que tenga el rol de gerente
            if(!is_null($id_gerente))
            {
                $gerente = UsuarioDAO::getByPK($id_gerente);
                if(is_null($gerente))
                    return "El usuario con id: ".$id_gerente." no existe";
                if($gerente->getIdRol()!=2)
                {
                    return "El usuario con id: ".$id_gerente." no es un gerente";
                }
            }
            
            //Se valida que el saldo a favor este en el rango
            if(!is_null($saldo_a_favor))
            {
                $e=self::validarNumero($saldo_a_favor, 1.8e200, "saldo a favor");
                if(is_string($e))
                    return $e;
            }
            
            //Se valida el boleano activa
            if(!is_null($activa))
            {
                $e=self::validarNumero($activa, 1, "activa");
                if(is_string($e))
                    return $e;
            }
            
            //Se valida el margen de utilidad
            if(!is_null($margen_utilidad))
            {
                $e=self::validarNumero($margen_utilidad, 1.8e200, "margen de utilidad");
                if(is_string($e))
                    return $e;
            }
            
            //Se valida el descuento. El descuento e sun porcentaje y no puede ser mayor a 100
            if(!is_null($descuento))
            {
                $e=self::validarNumero($descuento, 100, "descuento");
                if(is_string($e))
                    return $e;
            }
            
            //No se encontro error, regresa true
            return true;
        }
        
        /*
         * Valida los parametros de la tabla almacen. Cuando algun parametro es incorrecto
         * regresa un string. Cuando no hay error regresa true.
         */
        private static function validarParametrosAlmacen
        (
                $id_almacen = null,
                $id_sucursal = null,
                $id_empresa = null,
                $id_tipo_almacen = null,
                $nombre = null,
                $descripcion = null,
                $activo = null
        )
        {
            //Valida que el almacen exista en la base de datos
            if(!is_null($id_almacen))
            {
                if(is_null(AlmacenDAO::getByPK($id_almacen)))
                        return "El almacen con id: ".$id_almacen." no existe";
            }
            
            //Valida que la sucursal exista en la base de datos
            if(!is_null($id_sucursal))
            {
                if(is_null(SucursalDAO::getByPK($id_sucursal)))
                        return "La sucursal con id: ".$id_sucursal." no existe";
            }
            
            //Valida que la empresa exista en la base de datos
            if(!is_null($id_empresa))
            {
                if(is_null(EmpresaDAO::getByPK($id_empresa)))
                        return "La empresa con id: ".$id_empresa." no existe";
            }
            
            //Valida que el tipo de almacen exista en la base de datos
            if(!is_null($id_tipo_almacen))
            {
                if(is_null(TipoAlmacenDAO::getByPK($id_tipo_almacen)))
                        return "El tipo de almacen con id: ".$id_tipo_almacen." no existe";
            }
            
            //Valida que el nombre tenga una longitud maxima de 100
            if(!is_null($nombre))
            {
                $e = self::validarString($nombre, 100, "nombre");
                if(is_string($e))
                    return $e;
            }
            
            //Valida que la descripcion tenga una longitud maxima de 255
            if(!is_null($descripcion))
            {
                $e = self::validarString($descripcion, 255, "descripcion");
                if(is_string($e))
                    return $e;
            }
            
            //Valida el boleano activo
            if(!is_null($activo))
            {
                $e = self::validarNumero($activo, 1, "activo");
                if(is_string($e))
                    return $e;
            }
            
            //No se encontro error, regresa true
            return true;
        }
        
        /*
         * Valida los parametros de la tabla caja. Cuando algun parametro es incorrecto
         * regresa un string. Cuando no hay error, regresa verdadero
         */
        private static function validarParametrosCaja
        (
                $id_caja = null,
                $id_sucursal = null,
                $token = null,
                $descripcion = null,
                $abierta = null,
                $saldo = null,
                $control_billetes = null,
                $activa = null
        )
        {
            //Valida que la caja exista en la base de datos
            if(!is_null($id_caja))
            {
                if(is_null(CajaDAO::getByPK($id_caja)))
                        return "La caja con id: ".$id_caja." no existe";
            }
            
            //Valida que la sucursal exista en l abase de datos
            if(!is_null($id_sucursal))
            {
                if(is_null(SucursalDAO::getByPK($id_sucursal)))
                        return "La sucursal con id: ".$id_sucursal." no existe";
            }
            
            //Valida que el token tenga un maximo de 32 caracteres y que solo tenga letras mayusculas, minusculas y numeros.
            //El token no pede ser repetido
            if(!is_null($token))
            {
                $e = self::validarString($token, 32, "token");
                if(is_string($e))
                    return $e;
                if(preg_match('/[^a-zA-Z0-9]/' ,$token))
                            return "El token (".$token.") contiene caracteres que no son alfanumericos";
                if(!is_null($id_caja))
                {
                    $cajas = array_diff(CajaDAO::search( new Caja( array("token" => $token) ) ), array(CajaDAO::getByPK($id_caja)));
                }
                else
                {
                    $cajas = CajaDAO::search( new Caja( array("token" => $token) ) );
                }
                foreach($cajas as $caja)
                {
                    if($caja->getActiva())
                        return "El token (".$token.") ya esta en uso por la caja ".$caja->getIdCaja();
                }
            }
            
            //Valida que la descripcion tenga un maximo de 32 caracteres
            if(!is_null($descripcion))
            {
                $e = self::validarString($descripcion, 32, "descripcion");
                if(is_string($e))
                    return $e;
            }
            
            //Valida el boleano abierta
            if(!is_null($abierta))
            {
                $e = self::validarNumero($abierta, 1, "abierta");
                if(is_string($e))
                    return $e;
            }
            
            //Valida que el saldo este en rango
            if(!is_null($saldo))
            {
                $e = self::validarNumero($saldo, 1.8e200, "saldo");
                if(is_string($e))
                    return $e;
            }
            
            //Valida el boleano control de billetes
            if(!is_null($control_billetes))
            {
                $e = self::validarNumero($control_billetes, 1, "control de billetes");
                if(is_string($e))
                    return $e;
            }
            
            //Valida el boleano activa
            if(!is_null($activa))
            {
                $e = self::validarNumero($activa, 1, "activa");
                if(is_string($e))
                    return $e;
            }
            
            //No se encontro error, regresa true
            return true;
        }
        
        /*
         * Valida los parametros de la tabla traspaso. Cuando algun parametro es erroneo
         * regresa un string. Si no hay error, regresa true.
         */
        private static function validarParametrosTraspaso
        (
                $id_traspaso = null,
                $id_usuario = null,
                $id_almacen = null,
                $fecha = null
        )
        {
            //Verifica que el traspaso exista en la base de datos
            if(!is_null($id_traspaso))
            {
                if(is_null(TraspasoDAO::getByPK($id_traspaso)))
                        return "El traspaso con id: ".$id_traspaso." no existe";
            }
            
            //Verifica que el usuario exista en la base de datos
            if(!is_null($id_usuario))
            {
                if(is_null(UsuarioDAO::getByPK($id_usuario)))
                        return "El usuario con id: ".$id_usuario." no existe";
            }
            
            //Verifica que el almacen exista en la base de datos
            if(!is_null($id_almacen))
            {
                if(is_null(AlmacenDAO::getByPK($id_almacen)))
                    return "El almacen con id: ".$id_almacen." no existe";
            }
            
            //Verifica que la fecha este en el rango
            if(!is_null($fecha))
            {
                $e = self::validarString($fecha, strlen("YYYY-mm-dd HH:ii:ss"), "fecha");
                if(is_string($e))
                    return $e;
            }
            
            //No se encontro error, regresa true
            return true;
        }
        
        /*
         * Valida los parametros para la tabla venta. Cuando algun parametro es erroneo
         * regres aun string con el error. Cuando no hay error, regresa verdadero.
         */
        private static function validarParametrosVenta
        (
                $id_venta = null,
                $id_venta_caja = null,
                $id_comprador_venta = null,
                $tipo_de_venta = null,
                $subtotal = null,
                $impuesto = null,
                $descuento = null,
                $total = null,
                $saldo = null,
                $cancelada = null,
                $tipo_de_pago = null,
                $retencion = null
        )
        {
            //valida que la venta exista en la base de datos
            if(!is_null($id_venta))
            {
                if(is_null(VentaDAO::getByPK($id_venta)))
                {
                    return "La venta con id: ".$id_venta." no existe";
                }
            }
            
            //valida que la venta caja este en el rango
            if(!is_null($id_venta_caja))
            {
                $e = self::validarNumero($id_venta_caja, PHP_INT_MAX, "id venta caja");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el comprador de la venta exista en la base de datos y sea un cliente
            if(!is_null($id_comprador_venta))
            {
                $usuario = UsuarioDAO::getByPK($id_comprador_venta);
                if(is_null($usuario))
                {
                    return "El usuario con id ".$id_comprador_venta." no existe";
                }
                if(is_null($usuario->getIdClasificacionCliente()))
                {
                    return "El usuario no es un cliente pues no tiene una clasificacion como tal.";
                }     
                if(!$usuario->getActivo())
                {
                    return "El usuario no esta activo, no se le puede vender a un usuario inactivo";
                }
            }
            
            //valida que el tipo de venta sea contado o a credito
            if(!is_null($tipo_de_venta))
            {
                if($tipo_de_venta!="contado"&&$tipo_de_venta!="credito")
                    return "El tipo de venta (".$tipo_de_venta.") no es valido, tiene que ser 'credito' o 'contado'";
            }
            
            //valida que el subtotal este en el rango
            if(!is_null($subtotal))
            {
                $e=self::validarNumero($subtotal, 1.8e200, "subtotal");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el impuesto este en el rango
            if(!is_null($impuesto))
            {
                $e = self::validarNumero($impuesto, 1.8e200, "impuesto");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el descuento este en el rango
            if(!is_null($descuento))
            {
                $e = self::validarNumero($descuento, 1.8e200, "descuento");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el total este en el rango
            if(!is_null($total))
            {
                $e = self::validarNumero($total, 1.8e200, "total");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el saldo este en el rango
            if(!is_null($saldo))
            {
                $e = self::validarNumero($saldo, 1.8e200, "saldo");
                if(is_string($e))
                    return $e;
            }
            
            //valida el boleano cancelada
            if(!is_null($cancelada))
            {
                $e = self::validarNumero($cancelada, 1, "cancelada");
                if(is_string($e))
                    return $e;
            }
            
            //valida el tipo de pago
            if(!is_null($tipo_de_pago))
            {
                if($tipo_de_pago!="cheque"&&$tipo_de_pago!="tarjeta"&&$tipo_de_pago!="efectivo")
                    return "El tipo de pago (".$tipo_de_pago.") no es valido. Solo se permite 'cheque', 'tarjeta' y 'efectivo'";
            }
            
            //valida que la retencion este en rango
            if(!is_null($retencion))
            {
                $e = self::validarNumero($retencion, 1.8e200, "retencion");
                if(is_string($e))
                    return $e;
            }
            
            //No se encontro ningun error, regresa true
            return true;
        }
            
        /*
         * Valida los parametros de la tabla venta_orden. Cuando un parametro es erroneo regresa
         * un string con el error. Cuando no hay error, regresa verdadero.
         */
        private static function validarParametrosVentaOrden
        (
                $id_venta = null,
                $id_orden_de_servicio = null,
                $precio = null,
                $descuento = null,
                $impuesto = null,
                $retencion = null
        )
        {
            //valida que la venta exista en la base de datos
            if(!is_null($id_venta))
            {
                if(is_null(VentaDAO::getByPK($id_venta)))
                        return "La venta con id ".$id_venta." no existe";
            }
            
            //valida que la orden de servicio exista en la base de datos
            if(!is_null($id_orden_de_servicio))
            {
                if(is_null(OrdenDeServicioDAO::getByPK($id_orden_de_servicio)))
                        return "La orden de servicio con id ".$id_orden_de_servicio." no existe";
            }
            
            //valida que el precio este en el rango
            if(!is_null($precio))
            {
                $e = self::validarNumero($precio, 1.8e200, "precio");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el descuento este en rango
            if(!is_null($descuento))
            {
                $e = self::validarNumero($descuento, 1.8e200, "descuento");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el impuesto este en rango
            if(!is_null($impuesto))
            {
                $e = self::validarNumero($impuesto, 1.8e200, "impuesto");
                if(is_string($e))
                    return $e;
            }
            
            //valida que la retencion este en rango
            if(!is_null($retencion))
            {
                $e = self::validarNumero($retencion, 1.8e200, "retencion");
                if(is_string($e))
                    return $e;
            }
            
            //No se encontro error, regresa true
            return true;
        }
        
        /*
         * Valida los parametros de la tabla venta_paquete. Cuando algun parametro es erroneo
         * regresa un string con el error. Si no se encontro error regresa verdadero
         */
        private static function validarParametrosVentaPaquete
        (
                $id_venta = null,
                $id_paquete = null,
                $cantidad = null,
                $precio = null,
                $descuento = null
        )
        {
            //validar que la venta exista en la base de datos
            if(!is_null($id_venta))
            {
                if(is_null(VentaDAO::getByPK($id_venta)))
                        return "La venta con id ".$id_venta." no existe";
            }
            
            //validar que el paquete exista en la base de datos
            if(!is_null($id_paquete))
            {
                if(is_null(PaqueteDAO::getByPK($id_paquete)))
                        return "El paquete con id ".$id_paquete." no existe";
            }
            
            //validar que la cantidad este en rango
            if(!is_null($cantidad))
            {
                $e = self::validarNumero($cantidad, 1.8e200, "cantidad");
                if(is_string($e))
                    return $e;
            }
            
            //validar que el precio este en rango
            if(!is_null($precio))
            {
                $e = self::validarNumero($precio, 1.8e200, "precio");
                if(is_string($e))
                    return $e;
            }
            
            //validar que el descuento este en rango
            if(!is_null($descuento))
            {
                $e = self::validarNumero($descuento, 1.8e200, "descuento");
                if(is_string($e))
                    return $e;
            }
            
            //No se encontraron errores, regresa true
            return true;
        }
        
        /*
         * Valida los parametros de la tabla venta_paquete. Cuando algun parametro es erroneo
         * regresa un string con el error. Cuando no encuentra error, regresa verdadero.
         */
        private static function validarParametrosVentaProducto
        (
                $id_venta = null,
                $id_producto = null,
                $precio = null,
                $cantidad = null,
                $descuento = null,
                $impuesto = null,
                $retencion = null,
                $id_unidad = null
        )
        {
            //valida que la venta exista en la base de datos
            if(!is_null($id_venta))
            {
                if(is_null(VentaDAO::getByPK($id_venta)))
                {
                    return "La venta con id ".$id_venta." no existe";
                }
            }
            
            //valida que el producto exista en la base de datos
            if(!is_null($id_producto))
            {
                $producto =ProductoDAO::getByPK($id_producto) ;
                if(is_null($producto))
                {
                    return "El producto con id ".$id_producto." no existe";
                }
                
                if(!$producto->getActivo())
                {
                    return "El producto ".$id_producto." no esta activo";
                }
            }
            
            //valida que el precio este en el rango
            if(!is_null($precio))
            {
                $e = self::validarNumero($precio,1.8e200,"precio");
                if(is_string($e))
                    return $e;
            }
            
            //valida que la cantidad este en el rango
            if(!is_null($cantidad))
            {
                $e = self::validarNumero($cantidad, PHP_INT_MAX, "cantidad");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el descuento este en el rango
            if(!is_null($descuento))
            {
                $e = self::validarNumero($descuento, 1.8e200, "descuento");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el impuesto este en rango
            if(!is_null($impuesto))
            {
                $e = self::validarNumero($impuesto, 1.8e200, "impuesto");
                if(is_string($e))
                    return $e;
            }
            
            //valida que la retencion este en rango
            if(!is_null($retencion))
            {
                $e = self::validarNumero($retencion, 1.8e200, "retencion");
                if(is_string($e))
                    return $e;
            }
            
            //valida que la unidad exista en la base de datos
            if(!is_null($id_unidad))
            {
                $unidad = UnidadDAO::getByPK($id_unidad);
                if(is_null($unidad))
                {
                        return "La unidad con id ".$id_unidad." no existe";
                }
                if(!$unidad->getActiva())
                {
                    return "La unidad ".$id_unidad." no esta activa";
                }
            }
            
            //no se encontro error, regresa true
            return true;
        }
        
        /*
         * Valida los parametros para la tabla compra. Cuando algun parametro es erroneo
         * regresa un string con el error. Cuando no hay error, regresa verdadero.
         */
        private static function validarParametrosCompra
        (
                $id_compra = null,
                $id_compra_caja = null,
                $id_vendedor_compra = null,
                $tipo_de_compra = null,
                $subtotal = null,
                $impuesto = null,
                $descuento = null,
                $total = null,
                $id_empresa = null,
                $saldo = null,
                $cancelada = null,
                $tipo_de_pago = null,
                $retencion = null
        )
        {
            //valida que la compra exista en la base de datos
            if(!is_null($id_compra))
            {
                if(is_null(CompraDAO::getByPK($id_compra)))
                {
                    return "La compra con id: ".$id_compra." no existe";
                }
            }
            
            //valida que la compra caja este en el rango
            if(!is_null($id_compra_caja))
            {
                $e = self::validarNumero($id_compra_caja, PHP_INT_MAX, "id compra caja");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el vendedor de la compra exista en la base de datos y sea un proveedor
            if(!is_null($id_vendedor_compra))
            {
                $usuario = UsuarioDAO::getByPK($id_vendedor_compra);
                if(is_null($usuario))
                {
                    return "El usuario con id ".$id_vendedor_compra." no existe";
                }
                if(is_null($usuario->getIdClasificacionProveedor()))
                {
                    return "El usuario no es un proveedor pues no tiene una clasificacion como tal.";
                }     
                if(!$usuario->getActivo())
                {
                    return "El usuario no esta activo, no se le puede comprar a un usuario inactivo";
                }
            }
            
            //valida que el tipo de compra sea contado o a credito
            if(!is_null($tipo_de_compra))
            {
                if($tipo_de_compra!="contado"&&$tipo_de_compra!="credito")
                    return "El tipo de compra (".$tipo_de_compra.") no es valido, tiene que ser 'credito' o 'contado'";
            }
            
            //valida que el subtotal este en el rango
            if(!is_null($subtotal))
            {
                $e=self::validarNumero($subtotal, 1.8e200, "subtotal");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el impuesto este en el rango
            if(!is_null($impuesto))
            {
                $e = self::validarNumero($impuesto, 1.8e200, "impuesto");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el descuento este en el rango
            if(!is_null($descuento))
            {
                $e = self::validarNumero($descuento, 1.8e200, "descuento");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el total este en el rango
            if(!is_null($total))
            {
                $e = self::validarNumero($total, 1.8e200, "total");
                if(is_string($e))
                    return $e;
            }
            
            //valida que la empresa exista en la base de datos
            if(!is_null($id_empresa))
            {
                $empresa = EmpresaDAO::getByPK($id_empresa);
                if(is_null($empresa))
                    return "La empresa con id ".$id_empresa." no existe";
                if(!$empresa->getActivo())
                    return "La empresa esta cancelada, no se pueden comprar productos en nombre de una empresa desactivada";
            }
            
            //valida que el saldo este en el rango
            if(!is_null($saldo))
            {
                $e = self::validarNumero($saldo, 1.8e200, "saldo");
                if(is_string($e))
                    return $e;
            }
            
            //valida el boleano cancelada
            if(!is_null($cancelada))
            {
                $e = self::validarNumero($cancelada, 1, "cancelada");
                if(is_string($e))
                    return $e;
            }
            
            //valida el tipo de pago
            if(!is_null($tipo_de_pago))
            {
                if($tipo_de_pago!="cheque"&&$tipo_de_pago!="tarjeta"&&$tipo_de_pago!="efectivo")
                    return "El tipo de pago (".$tipo_de_pago.") no es valido. Solo se permite 'cheque', 'tarjeta' y 'efectivo'";
            }
            
            //valida que la retencion este en rango
            if(!is_null($retencion))
            {
                $e = self::validarNumero($retencion, 1.8e200, "retencion");
                if(is_string($e))
                    return $e;
            }
            
            //No se encontro ningun error, regresa true
            return true;
        }
        
        /*
         * Valida los parametros de la tabla CompraProducto
         */
        
        private static function validarParametrosCompraProducto
        (
                $id_compra = null,
                $id_producto = null,
                $precio = null,
                $cantidad = null,
                $descuento = null,
                $impuesto = null,
                $retencion = null,
                $id_unidad = null
        )
        {
            //valida que la compra exista en la base de datos
            if(!is_null($id_compra))
            {
                if(is_null(CompraDAO::getByPK($id_compra)))
                {
                    return "La compra con id ".$id_compra." no existe";
                }
            }
            
            //valida que el producto exista en la base de datos
            if(!is_null($id_producto))
            {
                if(is_null(ProductoDAO::getByPK($id_producto)))
                {
                    return "El producto con id ".$id_producto." no existe";
                }
            }
            
            //valida que el precio este en el rango
            if(!is_null($precio))
            {
                $e = self::validarNumero($precio,1.8e200,"precio");
                if(is_string($e))
                    return $e;
            }
            
            //valida que la cantidad este en el rango
            if(!is_null($cantidad))
            {
                $e = self::validarNumero($cantidad, PHP_INT_MAX, "cantidad");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el descuento este en el rango
            if(!is_null($descuento))
            {
                $e = self::validarNumero($descuento, 1.8e200, "descuento");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el impuesto este en rango
            if(!is_null($impuesto))
            {
                $e = self::validarNumero($impuesto, 1.8e200, "impuesto");
                if(is_string($e))
                    return $e;
            }
            
            //valida que la retencion este en rango
            if(!is_null($retencion))
            {
                $e = self::validarNumero($retencion, 1.8e200, "retencion");
                if(is_string($e))
                    return $e;
            }
            
            //valida que la unidad exista en la base de datos
            if(!is_null($id_unidad))
            {
                if(is_null(UnidadDAO::getByPK($id_unidad)))
                        return "La unidad con id ".$id_unidad." no existe";
            }
            
            //no se encontro error, regresa true
            return true;
        }
        
        
        /*
         * Valida los parametros de la tabla sucursal_empresa. Regresa un string con el error
         * en caso de que un parametro sea erroneo. Regresa verdadero si no se ha encontrado error
         */
        private static function validarParametrosSucursalEmpresa
        (
                $id_sucursal = null,
                $id_empresa = null,
                $margen_utilidad = null,
                $descuento = null
        )
        {
            //valida que la sucursal exista en la base de datos
            if(!is_null($id_sucursal))
            {
                if(is_null(SucursalDAO::getByPK($id_sucursal)))
                        return "La sucursal con id ".$id_sucursal." no existe";
            }
            
            //valida que la empresa exista en la base de datos
            if(!is_null($id_empresa))
            {
                if(is_null(EmpresaDAO::getByPK($id_empresa)))
                        return "La empresa con id ".$id_empresa." no existe";
            }
            
            //valida el margen utilida
            if(!is_null($margen_utilidad))
            {
                $e = self::validarNumero($margen_utilidad, 1.8e200, "margen de utilidad");
                if(is_string($e))
                    return $e;
            }
            
            //valida el descuento, el descuento no puede ser mayor a 100
            if(!is_null($descuento))
            {
                $e = self::validarNumero($descuento, 100, "descuento");
                if(is_string($e))
                    return $e;
            }
            
            //no se encontro error, regres true;
            return true;
        }
        
        /**
 	 *
 	 *Creara un nuevo almacen en una sucursal, este almacen contendra lotes.
 	 *
 	 * @param nombre string nombre del almacen
 	 * @param id_sucursal int El id de la sucursal a la que pertenecera este almacen.
 	 * @param id_empresa int Id de la empresa a la que pertenecen los productos de este almacen
 	 * @param id_tipo_almacen int Id del tipo de almacen 
 	 * @param descripcion string Descripcion extesa del almacen
 	 * @return id_almacen int el id recien generado
 	 **/
	public static function NuevoAlmacen
	(
		$id_empresa, 
		$id_sucursal, 
		$id_tipo_almacen, 
		$nombre, 
		$descripcion = null
	)
	{
            Logger::log("Creando nuevo almacen");
            
            //Se validan los parametros obtenidos del almacen
            $validar = self::validarParametrosAlmacen(null, $id_sucursal, $id_empresa, $id_tipo_almacen, $nombre, $descripcion, null);
            if(is_string($validar))
            {
                Logger::log($validar);
                throw new Exception($validar);
            }
            
            //Se valida si hay un almacen con ese mimso nombre en esta sucursal
            $almacenes = AlmacenDAO::search(new Almacen( array( "id_sucursal" => $id_sucursal ) ) );
            foreach($almacenes as $almacen)
            {
                if($almacen->getNombre()==trim($nombre)&&$almacen->getActivo())
                {
                    Logger::log("El nombre (".$nombre.") ya esta siendo usado por el almacen: ".$almacen->getIdAlmacen());
                    throw new Exception("El nombre ya esta en uso");
                }
            }
            
            //Si se recibe un tipo de almacen de consignacion, se manda una excepcion, pues no se puede crear un almacen
            //de consignacion con este metodo.
            if($id_tipo_almacen==2)
            {
                Logger::error("No se puede crear un almacen de consignacion con este metodo");
                throw new Exception("No se puede crear un almacen de consignacion con este metodo");
            }
            
            //Solo puede haber un almacen por tipo por cada empresa en una sucursal.
            $almacenes = AlmacenDAO::search( new Almacen( array( "id_sucursal" => $id_sucursal ,
                "id_empresa" => $id_empresa , "id_tipo_almacen" => $id_tipo_almacen ) ) );
            if(!empty($almacenes))
            {
                Logger::error("Ya existe un almacen (".$almacenes[0]->getIdAlmacen().") de este tipo (".$id_tipo_almacen.") en esta sucursal (".$id_sucursal.") para esta empresa (".$id_empresa.")");
                throw new Exception("Ya existe un almacen de este tipo en esta sucursal para esta empresa");
            }
            
            //Se inicializa el registro a guardar con los datos obtenidos.
            $almacen=new Almacen();
            $almacen->setNombre(trim($nombre));
            $almacen->setDescripcion($descripcion);
            $almacen->setIdSucursal($id_sucursal);
            $almacen->setIdEmpresa($id_empresa);
            $almacen->setIdTipoAlmacen($id_tipo_almacen);
            $almacen->setActivo(1);
            DAO::transBegin();
            try
            {
                //Se guarda el almacen
                AlmacenDAO::save($almacen);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo crear el nuevo almacen");
                throw new Exception("No se pudo crear el nuevo almacen");
            }
            DAO::transEnd();
            Logger::log("Almacen creado exitosamente");
            return array( "id_almacen" => $almacen->getIdAlmacen());
	}
  
	/**
 	 *
 	 *listar almacenes de la isntancia. Se pueden filtrar por empresa, por sucursal, por tipo de almacen, por activos e inactivos y ordenar por sus atributos.
 	 *
 	 * @param id_empresa int Id de la empresa de la cual se listaran sus almacenes
 	 * @param id_sucursal int el id de la sucursal de la cual se listaran sus almacenes
 	 * @param id_tipo_almacen int Se listaran los almacenes de este tipo
 	 * @param activo bool Si este valor no es obtenido, se mostraran almacenes tanto activos como inactivos. Si es verdadero, solo se lsitaran los activos, si es falso solo se lsitaran los inactivos.
 	 * @return almacenes json Almacenes de esta sucursal
 	 **/
	public static function ListaAlmacen
	(
		$activo = null, 
		$id_empresa = null, 
		$id_sucursal = null, 
		$id_tipo_almacen = null
	)
	{
            Logger::log("Listando Almacenes");
            $parametros=false;
            if
            (
                    !is_null($id_empresa)  ||
                    !is_null($id_sucursal) ||
                    !is_null($id_tipo_almacen) ||
                    !is_null($activo)
            )
                $parametros=true;
            $almacenes=null;
            
            //Si se recibieron parametros, se buscan los almacenes con dichos parametros, si no
            //se imprimen todos los almacenes
            if($parametros)
            {
                Logger::log("Se recibieron parametros, se listan las almacenes en rango");
                
                //Se valida el parametro activo
                $validar = self::validarParametrosAlmacen(null, null, null, null, null, null, $activo);
                if(is_string($validar))
                {
                    Logger::error($validar);
                    throw new Exception($validar);
                }
                
                $almacen_criterio_1=new Almacen();
                $almacen_criterio_1->setActivo($activo);
                $almacen_criterio_1->setIdEmpresa($id_empresa);
                $almacen_criterio_1->setIdSucursal($id_sucursal);
                $almacen_criterio_1->setIdTipoAlmacen($id_tipo_almacen);
                $almacenes=AlmacenDAO::search($almacen_criterio_1);
            }
            else
            {
                Logger::log("No se recibieron parametros, se listan todos los almacenes");
                $almacenes=AlmacenDAO::getAll();
            }
            Logger::log("Almacenes obtenidos exitosamente");
            return $almacenes;
	}
  
	/**
 	 *
 	 *Vender productos desde el mostrador de una sucursal. Cualquier producto vendido aqui sera descontado del inventario de esta sucursal. La fecha ser?omada del servidor, el usuario y la sucursal ser?tomados del servidor. La ip ser?omada de la m?ina que manda a llamar al m?do. El valor del campo liquidada depender?e los campos total y pagado. La empresa se tomara del alamcen de donde salieron los productos
 	 *
 	 * @param detalle json Objeto que contendr los id de los productos, sus cantidades, su precio y su descuento.
 	 * @param retencion float Cantidad sumada por retenciones
 	 * @param id_comprador int Id del cliente al que se le vende.
 	 * @param subtotal float El total de la venta antes de cargarle impuestos
 	 * @param impuesto float Cantidad sumada por impuestos
 	 * @param total float El total de la venta
 	 * @param descuento float La cantidad que ser descontada a la compra
 	 * @param tipo_venta string Si la venta es a credito o a contado
 	 * @param saldo float La cantidad que ha sido abonada hasta el momento de la venta
 	 * @param cheques json Si el tipo de pago es con cheque, se almacena el nombre del banco, el monto y los ultimos 4 numeros del o de los cheques
 	 * @param tipo_pago string Si el pago ser efectivo, cheque o tarjeta.
 	 * @param billetes_pago json Ids de los billetes que se recibieron 
 	 * @param billetes_cambio json Ids de billetes que se entregaron como cambio
 	 * @return id_venta int Id autogenerado de la inserci�n de la venta.
 	 **/
	public static function VenderCaja
	( 
		$descuento, 
		$id_comprador, 
		$impuesto, 
		$retencion, 
		$subtotal, 
		$tipo_venta, 
		$total, 
		$billetes_cambio = null, 
		$billetes_pago = null, 
		$cheques = null, 
		$detalle_orden = null, 
		$detalle_paquete = null, 
		$detalle_producto = null, 
		$id_caja = null, 
		$id_sucursal = null, 
		$id_venta_caja = null, 
		$saldo = 0, 
		$tipo_pago = null
	)
	{
            Logger::log("Realizando la venta");
            
            //Se obtiene el id del usuario actualmente logueado
            $id_usuario=SesionController::getCurrentUser();
            if(is_null($id_usuario))
            {
                Logger::error("No se pudo obtener al usuario de la sesion actual, ya inicio sesion?");
                throw new Exception("No se pudo obtener al usuario de la sesion actual, ya inicio sesion?");
            }
            
            //Se validan los parametros de la venta
            $validar = self::validarParametrosVenta(null,$id_venta_caja,$id_comprador,$tipo_venta,$subtotal,$impuesto,$descuento,$total,$saldo,null,$tipo_pago,$retencion);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            //Se busca al usuario comprador
            $usuario=UsuarioDAO::getByPK($id_comprador);
            
            //Si no se recibe una sucursal, se toma la de la sesion
            if(is_null($id_sucursal))
            {
                $sucursal = SucursalDAO::getByPK($id_sucursal); 
                if(is_null( $sucursal
                        ))
                {
                    Logger::error("La sucursal ".$id_sucursal." no existe");
                    throw new Exception("La sucursal no existe",901);
                }
                
                if(!$sucursal->getActiva())
                {
                    Logger::error("La sucursal ".$id_sucursal." esta desactivada");
                    throw new Exception("La sucursal esta desactivada",901);
                }
                
                //Si la venta que se realiza es de otra sucursal, o no se tiene que recibir una caja, o la caja que se reciba
                //tiene que ser parte de la sucursal
                if(!is_null($id_caja))
                {
                    $caja = CajaDAO::getByPK($id_caja);
                    if(is_null($caja))
                    {
                        Logger::error("La caja ".$id_caja." no existe");
                        throw new Exception("La caja no existe",901);
                    }
                    
                    if($caja->getIdSucursal()!=$id_sucursal)
                    {
                        Logger::error("La caja (".$id_caja.") recibida para realizar la venta no pertenece a la sucursal (".$id_sucursal.")  elegida ");
                        throw new Exception("La caja recibida para realizar la venta no pertenece a la sucursal elegida ",901);
                    }
                }
                $id_sucursal = self::getSucursal();
            }
            
            //Si no se recibe otra caja, se toma la de la sesion
            if(is_null($id_caja))
            {
                $id_caja = self::getCaja();
            }
            
            //Se inicializa la venta con los parametros obtenidos
            $venta=new Venta();
            $venta->setRetencion($retencion);
            $venta->setIdCompradorVenta($id_comprador);
            $venta->setSubtotal($subtotal);
            $venta->setImpuesto($impuesto);
            $venta->setTotal($total);
            $venta->setDescuento($descuento);
            $venta->setTipoDeVenta($tipo_venta);
            $venta->setIdCaja($id_caja);
            $venta->setIdSucursal($id_sucursal);
            $venta->setIdUsuario($id_usuario);
            $venta->setIdVentaCaja($id_venta_caja);
            $venta->setCancelada(0);
            $venta->setTipoDePago($tipo_pago);
            $venta->setFecha(date("Y-m-d H:i:s",time()));
            DAO::transBegin();
            try
            {
                //Si la venta es de contado, se verifica el tipo de pago para realizar modificaciones
                if($tipo_venta==="contado")
                {   
                    //Si se recibe un saldo registra una advertencia
                    if(!is_null($saldo))
                    {
                        Logger::warn("Se recibio un saldo cuando la venta es de contado, el saldo se tomara del total");
                    }
                    
                    //El saldo de la venta se toma del total, pues siendo de contado, se tiene que pagar todo al momento de la venta;
                    //y se guarda la venta
                    $venta->setSaldo($total);
                    VentaDAO::save($venta);
                    
                    //Si el tipo de pago es con cheque se realizan movimientos extras con los cheques
                    if($tipo_pago==="cheque")
                    {
                        //Si no se recibe informacion de los cheques manda error
                        if(is_null($cheques))
                        {
                            throw new Exception("El tipo de pago es con cheque pero no se recibio informacion del mismo",901);
                        }
                        
                        //Se inicializa un registro de la tabla cheque_venta con el id de la venta guardada
                        //Se guarda un cheque por cada uno de los recibidos y se usa el id de la insercion para
                        //guardar el registro cheque_venta.
                        $cheque_venta = new ChequeVenta();
                        $cheque_venta->setIdVenta($venta->getIdVenta());
                        foreach($cheques as $cheque)
                        {
                            $id_cheque=ChequesController::NuevoCheque($cheque["nombre_banco"], $cheque["monto"], $cheque["numero"], 0);
                            $cheque_venta->setIdCheque($id_cheque);
                            ChequeVentaDAO::save($cheque_venta);
                        }
                    }
                    
                    //Si el tipo de pago es con efectivo, se realizan movimientos extras con la caja
                    else if($tipo_pago==="efectivo")
                    {
                        //Se modifica la caja en la cual se realiza la transaccion, si la caja lleva un control de billetes
                        //se le pasan los billetes que se recibieron como pago. Si se entrega cambio y se lleva control de
                        //billetes solo se pasan lso billetes que salieron por concepto del cambio.
                        CajasController::modificarCaja($venta->getIdCaja(), 1, $billetes_pago, $total);
                        if(!is_null($billetes_cambio))
                        {
                            CajasController::modificarCaja($venta->getIdCaja(), 0, $billetes_cambio, 0);
                        }
                    }
                }/*Fin if de tipo de venta igual a contado*/
                
                //Si la venta es a credito, se modifica el saldo del ejercicio del usuario comprador.
                else if($tipo_venta=="credito")
                {
                    if($usuario->getLimiteCredito()< $usuario->getSaldoDelEjercicio()*-1 + $total)
                    {
                        throw new Exception("Esta venta no se puede realizar a credito pues supera el limite de credito del usuario",901);
                    }
                    
                    if(is_null($saldo))
                    {
                        Logger::warn("No se recibio un saldo, se tomara 0 como saldo");
                        $saldo=0;
                    }
                    else if($saldo>$total)
                    {
                        throw new Exception("El saldo es mayor al total, no se puede pagar más por una venta que su total.",901);
                    }
                    $venta->setSaldo($saldo);
                    VentaDAO::save($venta);
                    $usuario->setSaldoDelEjercicio($usuario->getSaldoDelEjercicio()-$total+$saldo);
                    $usuario->setVentasACredito($usuario->getVentasACredito()+1);
                    UsuarioDAO::save($usuario);
                }
                
                //Si el detalle de las ordenes compradas, el detalle de los paquetes y el detalle de los productos
                //son nulos, manda error.
                if(is_null($detalle_orden)&&is_null($detalle_paquete)&&is_null($detalle_producto))
                {
                    throw new Exception ("No se recibieron ni paquetes ni productos ni servicios para esta venta",901);
                }
                
                //Por cada detalle, se valida la informacion recibida, se guarda en un registro
                //que contiene el id de la venta generada y se guarda el detalle en su respectiva tabla.
                
                if(!is_null($detalle_paquete))
                {
                    $d_paquete=new VentaPaquete();
                    $d_paquete->setIdVenta($venta->getIdVenta());
                    foreach($detalle_paquete as $d_p)
                    {
                        $validar = self::validarParametrosVentaPaquete(null,$d_p["id_paquete"],$d_p["cantidad"],$d_p["precio"],$d_p["descuento"]);
                        if(is_string($validar))
                            throw new Exception($validar,901);
                        $d_paquete->setCantidad($d_p["cantidad"]);
                        $d_paquete->setDescuento($d_p["descuento"]);
                        $d_paquete->setIdPaquete($d_p["id_paquete"]);
                        $d_paquete->setPrecio($d_p["precio"]);
                        VentaPaqueteDAO::save($d_paquete);
                    }
                }
                
                if(!is_null($detalle_producto))
                {

                    $d_producto=new VentaProducto();
                    $d_producto->setIdVenta($venta->getIdVenta());
					
					$detalle_producto = json_decode($detalle_producto);
					$detalle_producto = object_to_array($detalle_producto);

                    foreach($detalle_producto as $d_p)
                    {
                        $validar = self::validarParametrosVentaProducto(null,$d_p["id_producto"],$d_p["precio"],$d_p["cantidad"],$d_p["descuento"],$d_p["impuesto"],$d_p["retencion"],$d_p["id_unidad"]);
                        if(is_string($validar))
                            throw new Exception($validar,901);
                        $producto=ProductoDAO::getByPK($d_p["id_producto"]);
                        $d_producto->setCantidad($d_p["cantidad"]);
                        $d_producto->setDescuento($d_p["descuento"]);
                        $d_producto->setIdProducto($d_p["id_producto"]);
                        $d_producto->setIdUnidad($d_p["id_unidad"]);
                        $d_producto->setImpuesto($d_p["impuesto"]);
                        $d_producto->setPrecio($d_p["precio"]);
                        $d_producto->setRetencion($d_p["retencion"]);
                        VentaProductoDAO::save($d_producto);
                        
                        //Se descuentan los productos especificados de los almacenes.
                        self::DescontarDeAlmacenes($d_producto, self::getSucursal());
                    }
                }/* Fin de if para detalle_producto */
                
                
                if(!is_null($detalle_orden))
                {
                    $d_orden = new VentaOrden();
                    $d_orden->setIdVenta($venta->getIdVenta());
                    foreach($detalle_orden as $d_p)
                    {
                        $validar = self::validarParametrosVentaOrden(null,$d_p["id_orden_de_servicio"],$d_p["precio"],$d_p["descuento"],$d_p["impuesto"],$d_p["retencion"]);
                        if(is_string($validar))
                            throw new Exception($validar,901);
                        $d_orden->setDescuento($d_p["descuento"]);
                        $d_orden->setIdOrdenDeServicio($d_p["id_orden_de_servicio"]);
                        $d_orden->setImpuesto($d_p["impuesto"]);
                        $d_orden->setPrecio($d_p["precio"]);
                        $d_orden->setRetencion($d_p["retencion"]);
                        VentaOrdenDAO::save($d_orden);
                    }
                }
                
                //Se obtiene la relacion de empresas a las que pertenecera esta venta.
                $id_empresas=self::ObtenerEmpresasParaAsignacionVenta($detalle_producto, $detalle_paquete, $detalle_orden);
                
                //Se genera un registro de la tabla venta_empresa, se le asigna el id de la venta generada
                //y se recorren las empresas obtenidas para guardar nuevos registros en la tabla venta_empresa
                $venta_empresa=new VentaEmpresa(array("id_venta" => $venta->getIdVenta()));
                $n=count($id_empresas["id"]);
                for($i = 0 ; $i < $n ; $i++)
                {
                    $venta_empresa->setIdEmpresa($id_empresas["id"][$i]);
                    $venta_empresa->setTotal($id_empresas["total"][$i]);
                    if($venta->getSaldo()==$venta->getTotal())
                        $venta_empresa->setSaldada(1);
                    else
                        $venta_empresa->setSaldada(0);
                    VentaEmpresaDAO::save($venta_empresa);
                }
            } /* Fin del try */
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo realizar la venta: ".$e);
                if($e->getCode()==901)
                    throw new Exception("No se pudo realizar la venta: ".$e->getMessage(),901);
                throw new Exception("No se pudo realizar la venta",901);
            }
            DAO::transEnd();
            Logger::log("venta realizada exitosamente");
            return array ("id_venta" => $venta->getIdVenta());
	}

        /*
         * Este metodo analiza los arreglos del detalle de una venta y regresa
         * un arreglo de dos dimensiones donde un renglon cuenta con los ids
         * de las empresas y el segundo renglon cuenta con el total correspondiente
         * a esa empresa del total de la venta.
         */
        private static function ObtenerEmpresasParaAsignacionVenta
        (
                $detalle_producto=null,
                $detalle_paquete=null,
                $detalle_orden=null
        )
        {
            //inicializa los arreglos base
            $empresas=array();
            $id_empresas=array( "id" => array(), "total" => array());
            
            //bandera que indica si se recibio alguno de los 3 detalles
            $parametro=false;
            
            //Para todos los detalles, se recorre el detalle recibido, se validan sus datos
            //y se buscan las empresas que tengan ese elemento. Despues se llama al metodo
            //InsertarIdEmpresa que se encarga de acomodar el arreglo de empresas y su total
            //correspondiente
            
            if(!is_null($detalle_producto))
            {
                $parametro=true;
                $producto_empresa=new ProductoEmpresa();
                foreach($detalle_producto as $d_p)
                {
                    $producto_empresa->setIdProducto($d_p["id_producto"]);
                    $productos_empresa=ProductoEmpresaDAO::search($producto_empresa);
                    foreach($productos_empresa as $p_e)
                    {
                        self::InsertarIdEmpresa($p_e, $id_empresas, $d_p["precio"]*$d_p["cantidad"]);
                    }
                }
            }
            if(!is_null($detalle_paquete))
            {
                $parametro=true;
                $paquete_empresa=new PaqueteEmpresa();
                foreach($detalle_paquete as $d_p)
                {
                    $paquete_empresa->setIdPaquete($d_p["id_paquete"]);
                    $paquetes_empresa=PaqueteEmpresaDAO::search($paquete_empresa);
                    foreach($paquetes_empresa as $p_e)
                    {
                        self::InsertarIdEmpresa($p_e, $id_empresas, $d_p["precio"]*$d_p["cantidad"]);
                    }
                }
            }
            if(!is_null($detalle_orden))
            {
                $parametro=true;
                $servicio_empresa=new ServicioEmpresa();
                foreach($detalle_orden as $orden)
                {
                    $orden_de_servicio=OrdenDeServicioDAO::getByPK($orden["id_orden_de_servicio"]);
                    $servicio_empresa->setIdServicio($orden_de_servicio->getIdServicio());
                    $servicios_empresa=ServicioEmpresaDAO::search($servicio_empresa);
                    foreach($servicios_empresa as $s_e)
                    {
                        self::InsertarIdEmpresa($s_e, $id_empresas, $orden["precio"]);
                    }
                }
            }
            
            //Si no se recibio ningun detalle se arroja una excepcion
            if(!$parametro)
            {
                throw new Exception("No se recibio un id_producto ni un id_paquete ni un id_orden");
            }
            
            return $id_empresas;
        }
        
        /*
         * Este metodo Inserta un id de una empresa con su precio a un arreglo.
         * En el arreglo solo puede haber una ocurrencia de un id de empresa
         */
        private static function InsertarIdEmpresa
        (
                $objeto,
                &$id_empresas,
                $precio
        )
        {
            //bandera que indica si el id de la empresa se repite
            $repetido=false;
            
            //Se busca el id de la empresa proveniente del objeto en el arreglo actal de ids
            $n=count($id_empresas["id"]);
            for($i = 0; $i < $n; $i++)
            {
                if($id_empresas["id"][$i] == $objeto->getIdEmpresa())
                {
                    $repetido=true;
                    break;
                }
            }
            
            //Si no esta repetido se inserta el nuevo id con su total correspondiente.
            if(!$repetido)
            {
                array_push($id_empresas["id"],$objeto->getIdEmpresa());
                array_push($id_empresas["total"],$precio);
            }
            
            //si la variable i no llego a n quiere decir que el id esta repetido.
            //La variable i se quedo en la posicion donde se encontro el id de la empresa,
            //solo se accede a el y se incrementa su total.
            else if($i!=$n)
            {
                $id_empresas["total"][$i]+=$precio;
            }
        }
        
        
        /*
         * Este metodo recibe un registro de la tabla venta_producto y la sucursal,
         * de tal forma que descontara de los almacenes de esa sucursal de manera uniforme
         * el producto recibido en el registro de venta_producto
         */

        private static function DescontarDeAlmacenes
        (
                VentaProducto $detalle_producto,
                $id_sucursal
        )
        {
            //se buscan los almacenes de la sucursal
            $almacenes=AlmacenDAO::search(new Almacen(array("id_sucursal" => $id_sucursal)));
            
            //Arreglo que contendra los almacenes con el producto que buscamos.
            $productos_almacen=array();
            
            //El total de productos en existencia en todos los almacenes
            $total=0;
            
            //por cada almacen en la sucursal que no sea de consignacion se busca el producto en dicho almacen,
            //si el producto fue encontrado se ingresa en el arreglo de productos_almacen y si su existencia es mayor a 0
            //se lleva incrementa el total de productos en existencia por parte de todos los almacenes
            foreach($almacenes as $almacen)
            {
                if($almacen->getIdTipoAlmacen()==2)
                    continue;
                $producto_almacen=ProductoAlmacenDAO::getByPK($detalle_producto->getIdProducto(), $almacen->getIdAlmacen(), $detalle_producto->getIdUnidad());
                if(!is_null($producto_almacen))
                {
                    if($producto_almacen->getCantidad()>0)
                        $total+=$producto_almacen->getCantidad();
                    array_push($productos_almacen,$producto_almacen);
                }
            }
            
            //Si productos_almacen queda vacío, quiere decir que no se encontro el producto en ningún almacen de esta sucursal
            if(empty ($productos_almacen))
            {
                throw new Exception("No se encontro el producto en los almacenes de esta sucursal");
            }
            
            //La cantidad de producto que estamos buscando vender
            $n=$detalle_producto->getCantidad();
            
            //El numero de almacenes que cuentan con el producto
            $n_almacenes=count($productos_almacen);
            
            //La unidad en la que se encuentra el producto, si no existe ocurre un error fatal.
            $unidad=UnidadDAO::getByPK($detalle_producto->getIdUnidad());
            if(is_null($unidad))
            {
                throw new Exception("FATAL!!! este producto no tiene unidad");
            }
            
            /*
             * Este algoritmo se basa en dos casos posibles
             * 
             * 1. Que el numero de elementos vendidos sea mayor o igual al total de elementos encontrados en los almacenes
             * 
             * 2. Que el numero de elementos vendidos sean menor al total de elementos encontrados
             * 
             * Caso 1:
             * 
             *    Dejamos la posibilidad abierta de que los almacenes queden con cantidad negativa, pues como es una
             *    venta en mostrador, para vender el producto tiene que estar fisicamente ahi, y no se pretende evitar la venta
             *    de un producto que fisicamente esta presente pero no lo esta en el sistema.
             * 
             *    Así pues, como de cualqueir manera todos los almacenes van a quedar con ese elemento en cantidades negativas o en ceros
             *    cuando el numero de elementos sea igual al total, dejamos en ceros las existencias y empezamos a restar uniformemente
             *    en cada almacen hasta cubrir la diferencia entre el numero de elementos vendidos con el numero de elementos encontrados.
             * 
             * Caso 2:
             * 
             *    Se tiene que empezar a descontar del almacen que cuenta con mas existencia del elemento vendido
             *    hasta que su existencia sea igual a la del segundo almacen que cuenta con mas existencia. A partir de ahi
             *    se descontara de dos almacenes en lugar de uno, y asi hasta llegar al almacen co nmenos existencia.
             * 
             *    Para lograr esto, se ordena el arreglo de almacenes con sus productos y se guarda un arreglo de diferencias,
             *    que indicara cuantas veces podremos restar de un almacen hasta tener que pasar al siguiente y restar de ambos.
             *    Un contador nos indica a cuantos almacenes le estamso restando producto hasta que se cubre la demanda de elementos.
             * 
             * En ambos casos se mantiene la uniformidad de existencia del elemento en los almacenes. El metodo funciona tanto para 
             * las unidades que aceptan decimales como aquellas que solo son enteros.
             */
            
            //caso 1
            if($n>=$total)
            {
                $n-=$total;
                if($unidad->getEsEntero())
                {
                    $mod=$n%$n_almacenes;
                    $cantidad=floor($n/$n_almacenes);
                }
                else
                {
                    $mod=0;
                    $cantidad=$n/$n_almacenes;
                }
                DAO::transBegin();
                try
                {
                    foreach($productos_almacen as $p)
                    {
                        $temp=$cantidad;
                        if($mod>0)
                        {
                            $temp++;
                            $mod--;
                        }
                        $p->setCantidad(0-$temp);
                        ProductoAlmacenDAO::save($p);
                    }
                }
                catch(Exception $e)
                {
                    DAO::transRollback();
                    Logger::error("No se pudo actualizar la cantidad de producto en almacen: ".$e);
                    throw new Exception("No se pudo actualizar la cantidad de producto en almacen");
                }
                DAO::transEnd();
            } /* fin del caso 1 */
            
            //caso 2
            else
            {
                $productos_almacen=self::OrdenarProductosAlmacen($productos_almacen);
                $diferencia=array();
                for($i=0;$i<$n_almacenes-1;$i++)
                {
                    $diferencia[$i]=$productos_almacen[$i]->getCantidad()-$productos_almacen[$i+1]->getCantidad();
                }
                $diferencia[$i]=$productos_almacen[$i]->getCantidad();
                for($i=0;$n>0&&$i<$n_almacenes;$i++)
                {
                    if($n/($i+1)<=$diferencia[$i]*($i+1))
                    {
                        if($unidad->getEsEntero())
                        {
                            $mod=$n%($i+1);
                            $cantidad=floor($n/($i+1));
                        }
                        else
                        {
                            $mod=0;
                            $cantidad=$n/($i+1);
                        }
                        DAO::transBegin();
                        try
                        {
                            for($j=$i;$j>=0;$j--)
                            {
                                $temp=$cantidad;
                                if($mod>0)
                                {
                                    $temp++;
                                    $mod--;
                                }
                                $productos_almacen[$j]->setCantidad($productos_almacen[$j]->getCantidad()-$temp);
                                ProductoAlmacenDAO::save($productos_almacen[$j]);
                                
                            }
                        }
                        catch(Exception $e)
                        {
                            DAO::transRollback();
                            Logger::error("No se pudo actualizar la cantidad de producto en almacen: ".$e);
                            throw new Exception("No se pudo actualizar la cantidad de producto en almacen");
                        }
                        DAO::transEnd();
                    }
                    else
                    {
                        for($j=$i;$j>=0;$j--)
                        {
                            $productos_almacen[$j]->setCantidad($productos_almacen[$j]->getCantidad()-$diferencia[$i]);
                        }
                    }
                    $n-=$diferencia[$i]*($i+1);
                } /* Fin del for */
            } /* Fin del caso 2 */

        }
        
        /*
         * Este metodo recibe un arreglo de registros de la tabla producto_almacen y los ordena
         * de acuerdo a su cantidad en el almacen.
         * Regresa un arreglo rodenado de registros de la tabla producto_almacen
         */
        
        public static function OrdenarProductosAlmacen
        (
                $productos_almacen
        )
        {
            $cantidades=array();
            $productos_almacen_ordenado=array();
            foreach($productos_almacen as $p)
            {
                $productos_almacen_ordenado[]=$p;
                $cantidades[]=$p->getCantidad();
            }
            array_multisort($cantidades,SORT_DESC,$productos_almacen_ordenado);
            return $productos_almacen_ordenado;
        }


	/**
 	 *
 	 *Comprar productos en mostrador. No debe confundirse con comprar productos a un proveedor. Estos productos se agregaran al inventario de esta sucursal de manera automatica e instantanea. La IP ser?omada de la m?ina que realiza la compra. El usuario y la sucursal ser?tomados de la sesion activa. El estado del campo liquidada ser?omado de acuerdo al campo total y pagado.
 	 *
 	 * @param retencion float Cantidad sumada por retenciones
 	 * @param detalle json Objeto que contendr la informacin de los productos comprados, sus cantidades, sus descuentos, y sus precios
 	 * @param id_vendedor int Id del cliente al que se le compra
 	 * @param total float Total de la compra despues de impuestos y descuentos
 	 * @param tipo_compra string Si la compra es a credito o de contado
 	 * @param subtotal float Total de la compra antes de incluirle impuestos.
 	 * @param id_empresa int Empresa a nombre de la cual se realiza la compra
 	 * @param descuento float Cantidad restada por descuento
 	 * @param impuesto float Cantidad sumada por impuestos
 	 * @param billetes_pago json Ids de billetes que se usaron para pagar
 	 * @param billetes_cambio json Ids de billetes que se recibieron como cambio
 	 * @param tipo_pago string Si el pago ser en efectivo, con tarjeta o con cheque
 	 * @param saldo float Saldo de la compra
 	 * @param cheques json Si el tipo de pago es con cheque, se almacena el nombre del banco, el monto y los ultimos 4 numeros del o de los cheques
 	 * @return id_compra_cliente string Id de la nueva compra
 	 **/
	public static function ComprarCaja
	(
		$descuento, 
		$detalle, 
		$id_empresa, 
		$id_vendedor, 
		$impuesto, 
		$retencion, 
		$subtotal, 
		$tipo_compra, 
		$total, 
		$billetes_cambio = null, 
		$billetes_pago = null, 
		$cheques = null, 
		$id_caja = null, 
		$id_compra_caja = null, 
		$id_sucursal = null, 
		$saldo = 0, 
		$tipo_pago = null
	)
	{  
            Logger::log("Realizando la compra");
            
            //Se obtiene el id del usuario de la sesion actual
            $id_usuario=SesionController::getCurrentUser();
            if(is_null($id_usuario))
            {
                Logger::error("No se pudo obtener al usuario de la sesion actual, ya inicio sesion?");
                throw new Exception("No se pudo obtener al usuario de la sesion actual, ya inicio sesion?");
            }
            
            //Se validan los parametros recibidos
            $validar = self::validarParametrosCompra(null,$id_compra_caja,$id_vendedor,$tipo_compra,$subtotal,$impuesto,$descuento,$total,$id_empresa,$saldo,null,$tipo_pago,$retencion);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            //Se inicializa el usuario con los parametros recibidos.
            $usuario=UsuarioDAO::getByPK($id_vendedor);
            
            $compra=new Compra();
            $compra->setRetencion($retencion);
            $compra->setIdVendedorCompra($id_vendedor);
            $compra->setSubtotal($subtotal);
            $compra->setImpuesto($impuesto);
            $compra->setTotal($total);
            $compra->setDescuento($descuento);
            $compra->setTipoDeCompra($tipo_compra);
            $compra->setIdCaja(self::getCaja());
            $compra->setIdSucursal(self::getSucursal());
            $compra->setIdUsuario($id_usuario);
            $compra->setIdCompraCaja($id_compra_caja);
            $compra->setCancelada(0);
            $compra->setTipoDePago($tipo_pago);
            $compra->setFecha(date("Y-m-d H:i:s",time()));
            $compra->setIdEmpresa($id_empresa);
            DAO::transBegin();
            try
            {
                //Si la compra es a contado, se realizan operaciones dependiendo del tipo de pago
                if($tipo_compra==="contado")
                {
                    //Si se recibe un saldo, se loguea una advertencia indicando que se ignorará
                    if(!is_null($saldo))
                    {
                        Logger::warn("Se recibio un saldo cuando la venta es de contado, el saldo se tomara del total");
                    }
                    $compra->setSaldo($total);
                    CompraDAO::save($compra);
                    
                    //Si el tipo de pago es cheque, se crean los nuevos cheques con la informacion obtenida
                    //y se almacenan registros por cada cheque para esta compra en la tabla cheque_compra
                    if($tipo_pago==="cheque")
                    {
                        //Si no se recibe informacion de los cheques se lanza una excepcion
                        if(is_null($cheques))
                        {
                            throw new Exception("El tipo de pago es con cheque pero no se recibio informacion del mismo");
                        }
                        $cheque_compra = new ChequeCompra();
                        $cheque_compra->setIdCompra($compra->getIdCompra());
                        foreach($cheques as $cheque)
                        {
                            $id_cheque=ChequesController::NuevoCheque($cheque["nombre_banco"], $cheque["monto"], $cheque["numero"], 1);
                            $cheque_compra->setIdCheque($id_cheque);
                            ChequeCompraDAO::save($cheque_compra);
                        }
                    }
                    
                    //Si el tipo de pago es efectivo, se modifica el saldo de la caja en la que se realiza la operacion
                    //y si lleva control de billetes, se pasa la informacion de los mismos. Si salieron billetes a causa del cambio
                    //se vuelve a modificar la caja sin modificar su saldo.
                    else if($tipo_pago==="efectivo")
                    {
                        CajasController::modificarCaja($compra->getIdCaja(), 0, $billetes_pago, $total);
                        if(!is_null($billetes_cambio))
                        {
                            CajasController::modificarCaja($compra->getIdCaja(), 1, $billetes_cambio, 0);
                        }
                    }
                }
                
                //Si la compra ha sido a credito, se modifica el saldo del ejercicio del usuario al que se le compra
                else if($tipo_compra=="credito")
                {
                    if(is_null($saldo))
                    {
                        Logger::warn("No se recibio un saldo, se tomara 0 como saldo");
                        $saldo=0;
                    }
                    
                    //El saldo no puede ser mayor que la cantidad a comprar
                    if($saldo>$total)
                    {
                        throw new Exception("El saldo no puede ser mayor que el total de la compra");
                    }
                    $compra->setSaldo($saldo);
                    CompraDAO::save($compra);
                    $usuario->setSaldoDelEjercicio($usuario->getSaldoDelEjercicio()+$total-$saldo);
                    UsuarioDAO::save($usuario);
                }
                
                //Si se recibio detalle de productos, se agregan al almacen correspondiente a la empresa
                //dentro de esta sucursal. Tambien se guarda el detalle en la tabla compra_producto
                if(!is_null($detalle))
                {
                    //Se inicializan variables para el almacenamiento de los registros.
                    $d_producto=new CompraProducto();
                    $d_producto->setIdCompra($compra->getIdCompra());
                    
                    //se buscan los almacenes de esta empresa para esta sucursal y se ignoran los de consginacion
                    $almacenes=AlmacenDAO::search(new Almacen(array("id_sucursal" => self::getSucursal(), "id_empresa" => $id_empresa)));
                    $id_almacen=null;
                    foreach($almacenes as $a)
                    {
                        if($a->getIdTipoAlmacen()==2)
                                continue;
                        $id_almacen=$a->getIdAlmacen();
                    }
                    
                    //Si no se encontro un almacen, se arroja una excepcion
                    if(is_null($id_almacen))
                    {
                        throw new Exception("No existe un almacen para esta empresa en esta sucursal");
                    }
                    
                    //Por cada producto en el detalle se almacena en la tabla compra_producto y se agregan al
                    //almacen de la empresa
                    foreach($detalle as $d_p)
                    {
                        $validar = self::validarParametrosCompraProducto(null,$d_p["id_producto"],$d_p["precio"],$d_p["cantidad"],$d_p["descuento"],$d_p["impuesto"],$d_p["retencion"],$d_p["id_unidad"]);
                        if(is_string($validar))
                        {
                            throw $validar;
                        }
                        $producto=ProductoDAO::getByPK($d_p["id_producto"]);
                        //Si el producto no puede ser comprado en mostrador arroja una excepcion
                        if(!$producto->getCompraEnMostrador())
                        {
                            throw new Exception("No se puede comprar el producto con id ".$d_p["id_producto"]." en mostrador");
                        }
                        //Si el producto no pertenece a la empresa que quiere hacer la compra, arroja una excepcion
                        if(is_null(ProductoEmpresaDAO::getByPK($d_p["id_producto"], $id_empresa)))
                        {
                            throw new Exception("El producto no pertenece a la empresa seleccionada");
                        }
                        
                        //Se incializa ys e guarda el nuevo registro de la tabla compra_producto
                        $d_producto->setCantidad($d_p["cantidad"]);
                        $d_producto->setDescuento($d_p["descuento"]);
                        $d_producto->setIdProducto($d_p["id_producto"]);
                        $d_producto->setIdUnidad($d_p["id_unidad"]);
                        $d_producto->setImpuesto($d_p["impuesto"]);
                        $d_producto->setPrecio($d_p["precio"]);
                        $d_producto->setRetencion($d_p["retencion"]);
                        CompraProductoDAO::save($d_producto);
                        
                        //Se busca el producto en el almacen en la unidad obtenida, si no existe aun
                        //se crea uno nuevo y al final se guarda.
                        $producto_almacen=ProductoAlmacenDAO::getByPK($d_p["id_producto"], $id_almacen, $d_p["id_unidad"]);
                        if(is_null($producto_almacen))
                        {
                            $producto_almacen=new ProductoAlmacen(array("id_producto" => $d_p["id_producto"], "id_almacen" => $id_almacen, "id_unidad" => $d_p["id_unidad"]));
                        }
                        $producto_almacen->setCantidad($producto_almacen->getCantidad() + $d_p["cantidad"]);
                        ProductoAlmacenDAO::save($producto_almacen);
                    }
                }
                else
                {
                    throw new Exception ("No se recibieron productos para esta compra");
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo realizar la compra: ".$e);
                throw new Exception("No se pudo realizar la compra");
            }
            DAO::transEnd();
            Logger::log("compra realizada exitosamente");
            return array( "id_compra_cliente" => $compra->getIdCompra());
	}
  
	/**
 	 *
 	 *Lista las sucursales relacionadas con esta instancia. Se puede filtrar por empresa,  saldo inferior o superior a, fecha de apertura, ordenar por fecha de apertura u ordenar por saldo. Se agregar?n link en cada una para poder acceder a su detalle.
 	 *
 	 * @param activo bool Si este valor no es pasado, se listaran sucursales tanto activas como inactivas, si su valor es true, solo se mostrarn las sucursales activas, si es false, solo se mostraran las sucursales inactivas.
 	 * @param id_empresa int Id de la empresa de la cual se listaran sus sucursales.
 	 * @param saldo_inferior_que float Si este valor es obtenido, se mostrar�n las sucursales que tengan un saldo inferior a este
 	 * @param saldo_igual_que float Si este valor es obtenido, se mostrar�n las sucursales que tengan un saldo igual a este
 	 * @param saldo_superior_que float Si este valor es obtenido, se mostrar�n las sucursales que tengan un saldo superior a este
 	 * @param fecha_apertura_inferior_que string Si este valor es pasado, se mostraran las sucursales cuya fecha de apertura sea inferior a esta.
 	 * @param fecha_apertura_igual_que string Si este valor es pasado, se mostraran las sucursales cuya fecha de apertura sea igual a esta.
 	 * @param fecha_apertura_superior_que string Si este valor es pasado, se mostraran las sucursales cuya fecha de apertura sea superior a esta.
 	 * @return sucursales json Objeto que contendra la lista de sucursales.
 	 **/
	public static function Lista
	(
		$activo = null, 
		$fecha_apertura_inferior_que = null, 
		$fecha_apertura_superior_que = null, 
		$id_empresa = null, 
		$saldo_inferior_que = null, 
		$saldo_superior_que = null
	)
	{
            Logger::log("Listando sucursales");
            
            //bandera para saber si se recibieron parametros o no
            $parametros=false;
            if
            (
                    !is_null($activo) ||
                    !is_null($saldo_inferior_que) ||
                    !is_null($saldo_superior_que) ||
                    !is_null($fecha_apertura_inferior_que) ||
                    !is_null($fecha_apertura_superior_que)
            )
                $parametros=true;
            
            //obejtos que almacenaran las comparaciones
            $sucursales=array();
            $sucursales1=array();
            
            //Si se recibieron parametros, se almacenan los distintos parametros en los objetos.
            //Los parametros de comparacion como saldo inferior que o superior que proponen limites
            //y cuando solo es pasado alguno de ellos, el otro objeto almacena el mayor o menor posible
            //para conseguir la mejor comparacion.
            if($parametros)
            {
                Logger::log("se recibieron parametros, se listan las sucursales en rango");
                
                //Cuando se recibe el parametro id_empresa, ademas de traer las sucursales con en el rango de los demas parametros
                //se traen todas las sucursales de esa empresa y se hace una interseccion.
                if(!is_null($id_empresa))
                {
                    $sucursales_empresa=SucursalEmpresaDAO::search(new SucursalEmpresa(array( "id_empresa" => $id_empresa )));
                    foreach($sucursales_empresa as $sucursal_empresa)
                    {
                        array_push($sucursales1,SucursalDAO::getByPK($sucursal_empresa->getIdSucursal()));
                    }
                }
                else
                {
                    $sucursales1=SucursalDAO::getAll();
                }
                $sucursal_criterio1=new Sucursal();
                $sucursal_criterio2=new Sucursal();

                $sucursal_criterio1->setActiva($activo);
                if(!is_null($saldo_superior_que))
                {
                    $sucursal_criterio1->setSaldoAFavor($saldo_superior_que);
                    if(!is_null($saldo_inferior_que))
                        $sucursal_criterio2->setSaldoAFavor($saldo_inferior_que);
                    else
                        $sucursal_criterio2->setSaldoAFavor(1.8e100);
                }
                else if(!is_null($saldo_inferior_que))
                {
                    $sucursal_criterio1->setSaldoAFavor($saldo_inferior_que);
                    $sucursal_criterio2->setSaldoAFavor(0);
                }
                if(!is_null($fecha_apertura_superior_que))
                {
                    $sucursal_criterio1->setFechaApertura($fecha_apertura_superior_que);
                    if(!is_null($fecha_apertura_inferior_que))
                        $sucursal_criterio2->setFechaApertura($fecha_apertura_inferior_que);
                    else
                        $sucursal_criterio2->setFechaApertura(date("Y-m-d H:i:s",time()));
                }
                else if(!is_null($fecha_apertura_inferior_que))
                {
                    $sucursal_criterio1->setFechaApertura($fecha_apertura_inferior_que);
                    $sucursal_criterio2->setFechaApertura("1001-01-01 00:00:00");
                }
                $sucursales2=SucursalDAO::byRange($sucursal_criterio1, $sucursal_criterio2);
                $sucursales=array_intersect($sucursales1, $sucursales2);
            } /* fin if de parametros*/
            
            //Si no se recibieron parametros, se listan todas las sucursales
            else
            {
                Logger::log("No se recibieron parametros, se listan todas las sucursales");
                if(!is_null($id_empresa))
                {
                    $sucursales_empresa = SucursalEmpresaDAO::search( new SucursalEmpresa( array( "id_empresa" => $id_empresa ) ) );
                    foreach($sucursales_empresa as $sucursal_empresa)
                    {
                        array_push($sucursales, SucursalDAO::getByPK($sucursal_empresa->getIdSucursal()));
                    }
                }
                else
                    $sucursales=SucursalDAO::getAll();
            }
            Logger::log("Sucursales obtenidos con exitos");
            return $sucursales;
	}
  
	/**
 	 *
 	 *Valida si una maquina que realizara peticiones al servidor pertenece a una sucursal.
 	 *
 	 * @param billetes json Ids de billetes y sus cantidades con los que inicia esta caja
 	 * @param saldo float Saldo con el que empieza a funcionar la caja
 	 * @param client_token string El token generado por el POS client
 	 * @param control_billetes bool Si se quiere llevar el control de billetes en la caja
 	 * @param id_cajero int Id del cajero que iniciara en esta caja en caso de que no sea este el que abre la caja
 	 * @return detalles_sucursal json Si esta es una sucursal valida, detalles sucursal contiene un objeto con informacion sobre esta sucursal.
 	 **/
	public static function AbrirCaja
	(
		$client_token, 
		$control_billetes, 
		$id_caja, 
		$saldo, 
		$billetes = null, 
		$id_cajero = null
	)
	{
            Logger::log("Abriendo caja");
            
            //Se validan los parametros obtenidos
            $validar = self::validarParametrosCaja($id_caja,null,$client_token,null,null,$saldo,$control_billetes);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar,901);
            }
            $caja=CajaDAO::getByPK($id_caja);
            
            //verifica que la caja este activa
            if(!$caja->getActiva())
            {
                Logger::error("La caja no esta activa y no puede ser abierta");
                throw new Exception("La caja no esta activa y no puede ser abierta",901);
            }
            
            //verifica que la caja no este abierta
            if($caja->getAbierta())
            {
                Logger::warn("La caja ya ha sido abierta");
                throw new Exception("La caja ya ha sido abierta",901);
            }
            
            //verifica que el id cajero exista en la base de datos
            if(!is_null($id_cajero))
            {
                $cajero = UsuarioDAO::getByPK($id_cajero);
                if(is_null($cajero))
                {
                    Logger::error("El cajero con id ".$id_cajero." no existe");
                    throw new Excetion("El cajero no existe",901);
                }
                if($cajero->getIdRol()!=3)
                {
                    Logger::error("El usuario obtenido como cajero no es un cajero, no tiene rol 3");
                    throw new Exception("El usuario obtenido como cajero no es un cajero",901);
                }
            }
            
            //Se declara la apertura de la caja
            $apertura_caja=new AperturaCaja();
            $apertura_caja->setIdCaja($id_caja);
            $apertura_caja->setFecha(date("Y-m-d H:i:s",time()));
            $apertura_caja->setIdCajero($id_cajero);
            $apertura_caja->setSaldo($saldo);
            $caja->setAbierta(1);
            $caja->setSaldo($saldo);
            $caja->setControlBilletes($control_billetes);
            DAO::transBegin();
            try
            {
                //Inserta los billetes en la caja y guarda los cambios en apetura caja y caja
                //El acto de abrir una caja da por hecho que no tiene ningun billete, pues al cerrar la caja se vaciaron.
                CajaDAO::save($caja);
                CajasController::modificarCaja($id_caja, 1, $billetes, 0);
                AperturaCajaDAO::save($apertura_caja);
                if($control_billetes)
                {
                    $billete_apertura_caja=new BilleteAperturaCaja(array( "id_apertura_caja" => $apertura_caja->getIdAperturaCaja()));
                    foreach($billetes as $billete)
                    {
                        $billete_apertura_caja->setIdBillete($billete["id_billete"]);
                        $billete_apertura_caja->setCantidad($billete["cantidad"]);
                        BilleteAperturaCajaDAO::save($billete_apertura_caja);
                    }
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo abrir la caja: ".$e,901);
                if($e->getCode()==901)
                    throw new Exception("No se pudo abrir la caja: ".$e->getMessage(),901);
                throw new Exception("No se pudo abrir la caja",901);
            }
            DAO::transEnd();
            Logger::log("Caja abierta exitosamente");
            return array( "detalles_sucursal" => $apertura_caja->getIdAperturaCaja());
	}
  
	/**
 	 *
 	 *Metodo que crea una nueva sucursal
 	 *
 	 * @param codigo_postal string Codigo postal de la empresa
 	 * @param rfc string RFC de la sucursal
 	 * @param activo bool Si esta sucursal estara activa inmediatamente despues de ser creada
 	 * @param colonia string Colonia de la sucursal
 	 * @param razon_social string Razon social de la sucursal
 	 * @param calle string Calle de la sucursal
 	 * @param empresas json Objeto que contendra los ids de las empresas a las que esta sucursal pertenece, por lo menos tiene que haber una empresa. En este JSON, opcionalmente junto con el id de la empresa, aapreceran dos campos que seran margen_utilidad y descuento, que indicaran que todos los productos de esa empresa ofrecidos en esta sucursal tendran un margen de utilidad y/o un descuento con los valores en esos campos
 	 * @param numero_exterior string Numero exterior de la sucursal
 	 * @param id_ciudad int Id de la ciudad donde se encuentra la sucursal
 	 * @param saldo_a_favor float Saldo a favor de la sucursal.
 	 * @param id_gerente int ID del usuario que sera gerente de esta sucursal. Para que sea valido este usuario debe tener el nivel de acceso apropiado.
 	 * @param margen_utilidad float Margen de utilidad que se le ganara a todos los productos ofrecidos por esta sucursal
 	 * @param numero_interior string numero interior
 	 * @param telefono2 string Telefono2 de la sucursal
 	 * @param telefono1 string Telefono1 de la sucursal
 	 * @param descripcion string Descripcion de la sucursal
 	 * @param impuestos json Objeto que contendra el arreglo de impuestos que afectan a esta sucursal
 	 * @param descuento float Descuento que tendran todos los productos ofrecidos por esta sucursal
 	 * @return id_sucursal int Id autogenerado de la sucursal que se creo.
 	 **/
	public static function Nueva
	(
		$activo, 
		$calle, 
		$codigo_postal, 
		$colonia, 
		$id_ciudad, 
		$numero_exterior, 
		$razon_social, 
		$rfc, 
		$saldo_a_favor, 
		$descripcion = null, 
		$descuento = null, 
		$id_gerente = null, 
		$impuestos = null, 
		$margen_utilidad = null, 
		$numero_interior = null, 
		$referencia = null, 
		$retenciones = null, 
		$telefono1 = null, 
		$telefono2 = null
	)
	{
            Logger::log("Creando nueva sucursal");
            
            //Se validan los parametros obtenidos
            $validar = self::validarParametrosSucursal(null,null,$rfc,$razon_social,$descripcion,
                    $id_gerente,$saldo_a_favor,null,$activo,null,$margen_utilidad,$descuento);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            //Se inicializa el objeto sucursal con los parametros obtenidos
            $sucursal=new Sucursal();
            $sucursal->setRfc($rfc);
            $sucursal->setActiva($activo);
            $sucursal->setRazonSocial(trim($razon_social));
            $sucursal->setSaldoAFavor($saldo_a_favor);
            $sucursal->setIdGerente($id_gerente);
            $sucursal->setMargenUtilidad($margen_utilidad);
            $sucursal->setDescripcion($descripcion);
            $sucursal->setDescuento($descuento);
            $sucursal->setFechaApertura(date("Y-m-d H:i:s",time()));
            DAO::transBegin();
            try
            {
                //Se crea la nueva direccion y se le asigna a la nueva sucursal
                $id_direccion=DireccionController::NuevaDireccion($calle,$numero_exterior,$colonia,$id_ciudad,$codigo_postal,$numero_interior,$referencia,$telefono1,$telefono2);
                $sucursal->setIdDireccion($id_direccion);
                SucursalDAO::save($sucursal);
                
                
                //Si se recibieron impuestos, se crea el registro correspondiente en la tabla impuesto sucursal
                if(!is_null($impuestos))
                {
                    $impuesto=new ImpuestoSucursal(array( "id_sucursal" => $sucursal->getIdSucursal()));
                    foreach($impuestos as $i)
                    {
                        if(is_null(ImpuestoDAO::getByPK($i)))
                        {
                            throw new Exception("El impuesto con id: ".$i." no existe");
                        }
                        $impuesto->setIdImpuesto($i);
                        ImpuestoSucursalDAO::save($impuesto);
                    }
                }
                
                //Si se recibieron retenciones, se crea el registro correspondiente en la tabla retencion sucursal
                if(!is_null($retenciones))
                {
                    $retencion= new RetencionSucursal(array( "id_sucursal" => $sucursal->getIdSucursal()));
                    foreach($retenciones as $r)
                    {
                        if(!is_null(RetencionDAO::getByPK($r)))
                        {
                            throw new Exception("La retencion con id: ".$r." no existe");
                        }
                        $retencion->setIdRetencion($r);
                        RetencionSucursalDAO::save($retencion);
                    }
                }
            }/* Fin del try */
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo crear la nueva sucursal ".$e);
                throw new Exception("No se pudo crear la nueva sucursal");
            }
            DAO::transEnd();
            Logger::log("Sucursal creada con exito");
            return array( "id_sucursal" => $sucursal->getIdSucursal());
	}
  
	/**
 	 *
 	 *Edita los datos de una sucursal
 	 *
 	 * @param id_sucursal int Id de la sucursal a modificar
 	 * @param descuento float Descuento que tendran los productos ofrecidos por esta sucursal
 	 * @param margen_utilidad float Porcentaje del margen de utilidad que se obtendra de los productos vendidos en esta sucursal
 	 * @param descripcion string Descripcion de la sucursal
 	 * @param empresas json Objeto que contendra los ids de las empresas a las que esta sucursal pertenece, por lo menos tiene que haber una empresa. En este JSON, opcionalmente junto con el id de la empresa, aapreceran dos campos que seran margen_utilidad y descuento, que indicaran que todos los productos de esa empresa ofrecidos en esta sucursal tendran un margen de utilidad y/o un descuento con los valores en esos campos
 	 * @param telefono1 string telefono 1 de la sucursal
 	 * @param telefono2 string telefono 2 de la sucursal
 	 * @param numero_exterior string Numero exterior de la sucursal
 	 * @param razon_social string Razon social de la sucursal
 	 * @param id_gerente int Id del gerente de la sucursal
 	 * @param municipio int Municipio de la sucursal
 	 * @param impuestos json Objeto que contendra los ids de los impuestos que afectana esta sucursal
 	 * @param rfc string Rfc de la sucursal
 	 * @param saldo_a_favor float Saldo a favor de la sucursal
 	 * @param numero_interior string Numero interior de la sucursal
 	 * @param colonia string Colonia de la sucursal
 	 * @param calle string Calle de la sucursal
 	 * @param coidgo_postal string Codigo Postal de la sucursal
 	 **/
	public static function Editar
	(
		$id_sucursal, 
		$calle = null, 
		$coidgo_postal = null, 
		$colonia = null, 
		$descripcion = null, 
		$descuento = null, 
		$empresas = null, 
		$id_gerente = null, 
		$impuestos = null, 
		$margen_utilidad = null, 
		$municipio = null, 
		$numero_exterior = null, 
		$numero_interior = null, 
		$razon_social = null, 
		$retenciones = null, 
		$rfc = null, 
		$saldo_a_favor = null, 
		$telefono1 = null, 
		$telefono2 = null
	)
	{
            Logger::log("Editando sucursal ".$id_sucursal);
            
            //Se obtiene la sucursal a editar y se valida que exista
            $sucursal=SucursalDAO::getByPK($id_sucursal);
            if(is_null($sucursal))
            {
                Logger::error("La sucursal con id: ".$id_sucursal." no existe");
                throw new Exception("La sucursal con id: ".$id_sucursal." no existe");
            }
            if(!$sucursal->getActiva())
            {
                Logger::error("La sucursal no esta activa, no se puede editar una sucursal inactiva");
                throw new Exception("La sucursal no esta activa, no se puede editar una sucursal inactiva");
            }
            //bandera que indica si cambia algun parametro de la direccion
            $cambio_direccion=false;
            
            //Se validan los demas parametros
            $validar = self::validarParametrosSucursal(null,$sucursal->getIdDireccion(),$rfc,$razon_social,
                    $descripcion,$id_gerente,$saldo_a_favor,null,null,null,$margen_utilidad,$descuento);
            
            $direccion=DireccionDAO::getByPK($sucursal->getIdDireccion());
            
            //Se valida cada parametro, si se recibe, se toma como actualizacion
            if(!is_null($descuento))
            {
                $sucursal->setDescuento($descuento);
            }
            if(!is_null($margen_utilidad))
            {
                $sucursal->setMargenUtilidad($margen_utilidad);
            }
            if(!is_null($descripcion))
            {
                $sucursal->setDescripcion($descripcion);
            }
            if(!is_null($telefono1))
            {
                $cambio_direccion=true;
                $direccion->setTelefono($telefono1);
            }
            if(!is_null($telefono2))
            {
                $cambio_direccion=true;
                $direccion->setTelefono2($telefono2);
            }
            if(!is_null($numero_exterior))
            {
                $cambio_direccion=true;
                $direccion->setNumeroExterior($numero_exterior);
            }
            if(!is_null($razon_social))
            {
                $sucursal->setRazonSocial(trim($razon_social));
            }
            if(!is_null($id_gerente))
            {
                $sucursal->setIdGerente($id_gerente);
            }
            if(!is_null($municipio))
            {
                $cambio_direccion=true;
                $direccion->setIdCiudad($municipio);
            }
            if(!is_null($rfc))
            {
                $sucursal->setRfc($rfc);
            }
            if(!is_null($saldo_a_favor))
            {
                $sucursal->setSaldoAFavor($saldo_a_favor);
            }
            if(!is_null($numero_interior))
            {
                $cambio_direccion=true;
                $direccion->setNumeroInterior($numero_interior);
            }
            if(!is_null($colonia))
            {
                $cambio_direccion=true;
                $direccion->setColonia($colonia);
            }
            if(!is_null($calle))
            {
                $cambio_direccion=true;
                $direccion->setCalle($calle);
            }
            if(!is_null($coidgo_postal))
            {
                $cambio_direccion=true;
                $direccion->setCodigoPostal($coidgo_postal);
            }
            
            //Si cambio algun parametro de direccion, se actualiza el usuario que modifica y la fecha
            if($cambio_direccion)
            {
                $direccion->setUltimaModificacion(date("Y-m-d H:i:s",time()));
                $id_usuario=SesionController::getCurrentUser();
                if(is_null($id_usuario))
                {
                    Logger::error("No se pudo obtener al usuario de la sesion, ya inicio sesion?");
                    throw new Exception("No se pudo obtener al usuario de la sesion, ya inicio sesion?");
                }
                $direccion->setIdUsuarioUltimaModificacion($id_usuario);
            }
            DAO::transBegin();
            try
            {
                //Se guardan los cambios hechos
                DireccionDAO::save($direccion);
                SucursalDAO::save($sucursal);
                
                
                //Si se recibieron impuestos, se insertan y actualizan los que se encuentran
                //en la lista obtenida. Al final, se recorren las relaciones ya existentes y
                //las que no esten incluidas en la lista obtenida son eliminadas.
                if(!is_null($impuestos))
                {
                    //Se inertan y actualizan las que se encuentran en la lista
                    foreach($impuestos as $impuesto)
                    {
                        if(is_null(ImpuestoDAO::getByPK($impuesto)))
                        {
                            throw new Exception("El impuesto con id: ".$impuesto." no existe");
                        }
                        ImpuestoSucursalDAO::save(new ImpuestoSucursal(array( "id_sucursal" => $id_sucursal, "id_impuesto" => $impuesto)));
                    }
                    $impuestos_sucursal_actual = ImpuestoSucursalDAO::search(new ImpuestoSucursal(array( "id_sucursal" => $id_sucursal)));
                    
                    //Se recorren las relaciones actuales y se eliminan aquellas que no esten en la lista obtenida
                    foreach($impuestos_sucursal_actual as $i)
                    {
                        $encontrado=false;
                        foreach($impuestos as $impuesto)
                        {
                            if($impuesto==$i->getIdImpuesto())
                            {
                                $encontrado=true;
                            }
                        }
                        if(!$encontrado)
                        {
                            ImpuestoSucursalDAO::delete($i);
                        }
                    }
                }
                
                //Si se recibieron retenciones, se insertan y actualizan las que se encuentran
                //en la lista obtenida. Al final, se recorren las relaciones ya existentes y
                //las que no esten incluidas en la lista obtenida son eliminadas.
                if(!is_null($retenciones))
                {
                    //Se insertan o actualizan las retenciones obtenidas en la lista
                    foreach($retenciones as $retencion)
                    {
                        if(is_null(RetencionDAO::getByPK($retencion)))
                        {
                            throw new Exception("La retencion con id: ".$retencion." no existe");
                        }
                        RetencionSucursalDAO::save(new RetencionSucursal(array( "id_sucursal" => $id_sucursal, "id_retencion" => $retencion)));
                    }
                    $retenciones_sucursal_actual = RetencionSucursalDAO::search(new RetencionSucursal(array( "id_sucursal" => $id_sucursal)));
                    
                    //Se eliminan las retenciones que no esten en la lista
                    foreach($retenciones_sucursal_actual as $r)
                    {
                        $encontrado=false;
                        foreach($retenciones as $retencion)
                        {
                            if($retencion==$r->getIdRetencion())
                            {
                                $encontrado=true;
                            }
                        }
                        if(!$encontrado)
                            RetencionSucursalDAO::delete($r);
                    }
                } /* Fin  if retenciones */
            } /* Fin try */
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo actualizar la sucursal: ".$e);
                throw new Exception("No se pudo actualizar la sucursal");
            }
            DAO::transEnd();
            Logger::log("Sucursal actualizada exitosamente");
	}
  
	/**
 	 *
 	 *Edita la gerencia de una sucursal
 	 *
 	 * @param id_sucursal int Id de la sucursal de la cual su gerencia sera cambiada
 	 * @param id_gerente string Id del nuevo gerente
 	 **/
	public static function EditarGerencia
	(
		$id_gerente, 
		$id_sucursal
	)
	{
            Logger::log("Editando gerencia de sucursal");
            
            //Se validan los parametros
            $validar = self::validarParametrosSucursal($id_sucursal,null,null,null,null,$id_gerente);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            $sucursal=SucursalDAO::getByPK($id_sucursal);
            
            if(!$sucursal->getActiva())
            {
                Logger::error("La sucursal no esta activa");
                throw new Exception("La sucursal no esta activa");
            }
            $gerente=UsuarioDAO::getByPK($id_gerente);
            
            //Se verifica que el usuario realmente tenga rol de gerente
            if($gerente->getIdRol()!=2)
            {
                Logger::error("El usuario no tiene rol de gerente");
                throw new Exception("El usuario no tiene rol de gerente");
            }
            
            //Se asigna el cambio y se guarda en la base de datos.
            $sucursal->setIdGerente($id_gerente);
            DAO::transBegin();
            try
            {
                SucursalDAO::save($sucursal);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("Error al editar la gerencia de la sucursal: ".$e);
                throw new Exception("Error al editar la gerencia de la sucursal");
            }
            DAO::transEnd();
            Logger::log("Gerencia editada correctamente");
	}
  
	/**
 	 *
 	 *Hace un corte en los flujos de dinero de la sucursal. El Id de la sucursal se tomara de la sesion actual. La fehca se tomara del servidor.
 	 *
 	 * @param saldo_real float Saldo que hay actualmente en la caja
 	 * @param billetes json Ids de billetes y sus cantidades encontrados en la caja al hacer el cierre
 	 * @param id_cajero int Id del cajero en caso de que no sea este el que realiza el cierre
 	 * @return id_cierre int Id del cierre autogenerado.
 	 **/
	public static function CerrarCaja
	(
                $id_caja, 
		$saldo_real, 
		$billetes = null, 
		$id_cajero = null
	)
	{
            Logger::log("Cerrando caja ".$id_caja);
            
            //Se valida la caja obtenida, que exista, que este activa y que este abierta
            $caja=CajaDAO::getByPK($id_caja);
            if(is_null($caja))
            {
                Logger::error("La caja con id:".$id_caja." no existe");
                throw new Exception("La caja con id:".$id_caja." no existe");
            }
            if(!$caja->getActiva())
            {
                Logger::error("La caja proporcionada no esta activa, no se puede cerrar");
                throw new Exception("La caja proporcionada no esta activa, no se puede cerrar");
            }
            if(!$caja->getAbierta())
            {
                Logger::warn("La caja proporcionada ya esta cerrada");
                throw new Exception("La caja proporcionada ya esta cerrada");
            }
            
            //Se valida que el cajero exista y tenga rol de cajero
            if(!is_null($id_cajero))
            {
                $cajero = UsuarioDAO::getByPK($id_cajero);
                if(is_null($cajero))
                {
                    Logger::error("El cajero con id: ".$id_cajero." no existe");
                    throw new Exception("El cajero no existe");
                }
                if($cajero->getIdRol()!=3)
                {
                    Logger::error("El usuario ".$id_cajero." no tiene rol de cajero");
                    throw new Exception("El usuario no tiene rol de cajero");
                }
            }
            
            //Se valida el parametro saldo real.
            $validar = self::validarNumero($saldo_real, 1.8e200, "saldo real");
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            //Se crea el registro de la tabla cierre_caja
            $cierre_caja=new CierreCaja(
                    array(
                        "id_caja" => $id_caja,
                        "id_cajero" => $id_cajero,
                        "fecha" => date("Y-m-d H:i:s",time()),
                        "saldo_real" => $saldo_real,
                        "saldo_esperado" => $caja->getSaldo()
                    )
                    );
            
            //Se realizan los movimientos de la caja antes de cerrarla, pues al cerrar la caja
            //ya no se pueden realizar movimientos sobre la misma.
            try
            {
                CajasController::modificarCaja($id_caja, 0, $billetes, $caja->getSaldo());
            }
            catch(Exception $e)
            {
                throw new Exception("No se pudo modificar la caja");
            }
            DAO::transBegin();
            try
            {
                //Se guardan los cambios en caja y cierre_caja
                $caja->setAbierta(0);
                CierreCajaDAO::save($cierre_caja);
                CajaDAO::save($caja);
                
                //Si la caja lleva control de los billetes, se crea un registro de la tabla billete_cierre_caja
                //y se registran cuantos billetes fueron encontrados, cuantos sobraron y cuantos faltaron.
                if($caja->getControlBilletes())
                {
                    //Se regitran los billetes recibidos como cantidad encontrada.
                    $billete_cierre_caja=new BilleteCierreCaja(array( "id_cierre_caja" => $cierre_caja->getIdCierreCaja() ));
                    $billete_cierre_caja->setCantidadFaltante(0);
                    $billete_cierre_caja->setCantidadSobrante(0);
                    foreach($billetes as $b)
                    {
                        $billete_cierre_caja->setIdBillete($b["id_billete"]);
                        $billete_cierre_caja->setCantidadEncontrada($b["cantidad"]);
                        BilleteCierreCajaDAO::save($billete_cierre_caja);
                    }
                    
                    //Se buscan los billetes de la caja y se buscan en la tabla billete_cierre_caja
                    //Si no se encuentra, se crea un registro nuevo
                    //Si despues de haber modificado la caja, quedan billetes en ella, significa que
                    //esos billetes estan faltando.
                    //En cambio, si esos billetes tienen cantidad negativa, significa que estan sobrando
                    $billetes_caja=BilleteCajaDAO::search(new BilleteCaja(array( "id_caja" => $id_caja )));
                    foreach($billetes_caja as $b_c)
                    {
                        $billete_cierre_caja=BilleteCierreCajaDAO::getByPK($b_c->getIdBillete(), $cierre_caja->getIdCierreCaja());
                        if(is_null($billete_cierre_caja))
                            $billete_cierre_caja=new BilleteCierreCaja(array(
                                                    "id_billete" => $b_c->getIdBillete(),
                                                    "id_cierre_caja" => $cierre_caja->getIdCierreCaja(),
                                                    "cantidad_encontrada" => 0,
                                                    "cantidad_sobrante" => 0,
                                                    "cantidad_faltante" => 0
                                                    )
                                                        );
                        if($b_c->getCantidad()<0)
                        {
                            $billete_cierre_caja->setCantidadSobrante($b_c->getCantidad());
                        }
                        else if($b_c->getCantidad()>0)
                        {
                            $billete_cierre_caja->setCantidadFaltante($b_c->getCantidad()*-1);
                        }
                        else
                            continue;
                        //Al final pone su cantidad en cero, pues al cerrar una caja esta debe quedar vacía.
                        $b_c->setCantidad(0);
                        BilleteCierreCajaDAO::save($billete_cierre_caja);
                        BilleteCajaDAO::save($b_c);
                    }/* Fin foreach bil;etes_caja */
                } /* Fin if contrik billetes */
            } /* Fin try */
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("Error al cerrar la caja: ".$e);
                throw new Exception("Error al cerrar la caja");
            }
            DAO::transEnd();
            Logger::log("Caja cerrada exitosamente");
            return array( "id_cierre" => $cierre_caja->getIdCierreCaja());
	}


	/**
 	 *
 	 *Metodo que surte una sucursal por parte de un proveedor. La sucursal sera tomada de la sesion actual.

Update
Creo que este metodo tiene que estar bajo sucursal.
 	 *
 	 * @param productos json Objeto que contendr los ids de los productos, sus unidades y sus cantidades
 	 * @param id_almacen int Id del almacen que se surte
 	 * @param motivo string Motivo del movimiento
 	 * @return id_surtido string Id generado por el registro de surtir
 	 **/
	public static function EntradaAlmacen
	(
		$id_almacen, 
		$productos, 
		$motivo = null
	)
	{
            Logger::log("Resgitrando entrada a almacen");
            $entrada_almacen = new EntradaAlmacen();
            
            //Se obtiene el usuario de la sesion
            $id_usuario=SesionController::getCurrentUser();
            if(is_null($id_usuario))
            {
                Logger::error("No se puede obtener al usuario de la sesion, ya inicio sesion?");
                throw new Exception("No se puede obtener al usuario de la sesion, ya inicio sesion?");
            }
            
            //valida que el almacen exista
            $almacen = AlmacenDAO::getByPK($id_almacen);
            if(is_null($almacen))
            {
                Logger::error("El almacen con id: ".$id_almacen." no existe");
                throw new Exception("El almacen con id: ".$id_almacen." no existe");
            }
            
            if(!$almacen->getActivo())
            {
                Logger::error("El almacen no esta activo, no se le pueden ingresar productos");
                throw new Exception("El almacen no esta activo, no se le pueden ingresar productos");
            }
            
            //valida que el motivo sea un string valido
            if(!is_null($motivo))
            {
                $validar = self::validarString($motivo, 255, "motivo");
                if(is_string($validar))
                {
                    Logger::error($validar);
                    throw new Exception($validar);
                }
            }
            //Se inicializa el registro de la tabla entrada_almacen
            $entrada_almacen->setIdAlmacen($id_almacen);
            $entrada_almacen->setMotivo($motivo);
            $entrada_almacen->setIdUsuario($id_usuario);
            $entrada_almacen->setFechaRegistro(date("Y-m-d H:i:s",time()));
            DAO::transBegin();
            try
            {
                //se guarda el registro de entrada_almacen
                EntradaAlmacenDAO::save($entrada_almacen);
                
                //Por cada producto recibido se crea un registro en  la tabla producto_entrada_almacen.
                //Cada producto ingresado incrementa su cantidad en el almacen. Si aun no existe,
                //se crea su registro y se guarda.
                $producto_entrada_almacen=new ProductoEntradaAlmacen(array( "id_entrada_almacen" => $entrada_almacen->getIdEntradaAlmacen() ));
                
                $productos = object_to_array($productos);
                
                if(!is_array($productos))
                {
                    throw new Exception("Los productos fueron recibidos incorrectamente",901);
                }
                
                foreach($productos as $p)
                {
                    //valida que el producto a ingresar pertenezca a la misma empresa que el almacen
                    //pues un almacen solo puede ocntener producto de la empresa a la que pertenece.
                    $productos_empresa = ProductoEmpresaDAO::search( new ProductoEmpresa( array( "id_producto" => $p["id_producto"] ) ) );
                    $encontrado = false;
                    foreach($productos_empresa as $p_e)
                    {
                        if($p_e->getIdEmpresa() == $almacen->getIdEmpresa())
                        {
                            $encontrado = true;
                        }
                    }
                    if(!$encontrado)
                    {
                        throw new Exception("Se quiere agregar un producto que no es de la empresa de este almacen");
                    }
                    
                    if(is_null(ProductoDAO::getByPK($p["id_producto"])))
                        throw new Exception("El producto con id: ".$p["id_producto"]." no existe");

                    $producto_entrada_almacen->setIdProducto($p["id_producto"]);
                    if(is_null(UnidadDAO::getByPK($p["id_unidad"])))
                        throw new Exception("La unidad con id: ".$p["id_unidad"]." no existe");

                    $producto_entrada_almacen->setIdUnidad($p["id_unidad"]);
                    $producto_entrada_almacen->setCantidad($p["cantidad"]);
                    $producto_almacen=ProductoAlmacenDAO::getByPK($p["id_producto"], $id_almacen, $p["id_unidad"]);
                    
                    if(is_null($producto_almacen))
                        $producto_almacen=new ProductoAlmacen(array( "id_producto" => $p["id_producto"] , "id_almacen" => $id_almacen , "id_unidad" => $p["id_unidad"] ));

                    $producto_almacen->setCantidad($producto_almacen->getCantidad()+$p["cantidad"]);
                    ProductoEntradaAlmacenDAO::save($producto_entrada_almacen);
                    ProductoAlmacenDAO::save($producto_almacen);
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo registrar la entrada al almacen: ".$e);
                throw new Exception("No se pudo registrar la entrada al almacen");
            }
            DAO::transEnd();
            Logger::log("Entrada a almacen registrada exitosamente");
            return array("id_surtido" => $entrada_almacen->getIdEntradaAlmacen());
	}
  
	/**
 	 *
 	 *Este metodo creara una caja asociada a una sucursal. Debe haber una caja por CPU. 
 	 *
 	 * @param token string el token que pos_client otorga por equipo
 	 * @param codigo_caja string El codigo de uso interno de la caja
 	 * @param impresoras json Un objeto con las impresoras asociadas a esta sucursal.
 	 * @param basculas json Un objeto con las basculas conectadas a esta caja.
 	 * @param descripcion string Descripcion de esta caja
 	 * @return id_caja int Id de la caja generada por la isnercion
 	 **/
	public static function NuevaCaja
	(
		$token, 
		$basculas = null, 
		$control_billetes = 0, 
		$descripcion = null, 
		$id_sucursal = null, 
		$impresoras = null
	)
	{
            Logger::log("Creando nueva caja");
            
            //Se validan los parametros de caja, si no se recibe sucursal, se intenta
            //tomar de la sesion
            $validar = self::validarParametrosCaja(null,$id_sucursal,$token,$descripcion);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            if(is_null($id_sucursal))
            {
                $id_sucursal=self::getSucursal();
                if(is_null($id_sucursal))
                {
                    Logger::error("No se pudo obtener la sucursal actual y no se obtuvo ninguna sucursal");
                    throw new Exception("No se pudo obtener la sucursal actual y no se obtuvo ninguna sucursal");
                }
            }
            
            //si no recibimos control de billetes, lo ponemos en cero.
            if(is_null($control_billetes))
            {
                $control_billetes = 0;
            }
            
            //Se inicializa el registro de caja
            $caja = new Caja();
            $caja->setIdSucursal($id_sucursal);
            $caja->setAbierta(0);
            $caja->setActiva(1);
            $caja->setControlBilletes($control_billetes);
            $caja->setDescripcion($descripcion);
            $caja->setIdSucursal($id_sucursal);
            $caja->setSaldo(0);
            $caja->setToken($token);
            DAO::transBegin();
            try
            {
                //Se guarda el registro de caja y si se recibieron impresoras se registran con la caja
                //en la tabla impresora_caja.
                CajaDAO::save($caja);
                if(!is_null($impresoras))
                {
                    $impresora_caja = new ImpresoraCaja(array( "id_caja" => $caja->getIdCaja() ));
                    foreach($impresoras as $id_impresora)
                    {
                        if(is_null(ImpresoraDAO::getByPK($id_impresora)))
                        {
                            throw new Exception("La impresora con id: ".$id_impresora." no existe");
                        }
                        $impresora_caja->setIdImpresora($id_impresora);
                        ImpresoraCajaDAO::save($impresora_caja);
                    }
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo crear la caja: ".$e);
                throw new Exception("No se pudo crear la caja");
            }
            DAO::transEnd();
            Logger::log("caja creada exitosamente");
            return array("id_caja" => $caja->getIdCaja());
	}
  
	/**
 	 *
 	 *Envia productos fuera del almacen. Ya sea que sea un traspaso de un alamcen a otro o por motivos de inventarios fisicos.
 	 *
 	 * @param productos json Objeto que contendra los ids de los productos que seran sacados del alamcen con sus cantidades y sus unidades
 	 * @param id_almacen int Id del almacen del cual se hace el movimiento
 	 * @param motivo string Motivo de la salida del producto
 	 * @return id_salida int ID de la salida del producto
 	 **/
	public static function SalidaAlmacen
	(
		$id_almacen, 
		$productos, 
		$motivo = null
	)
	{  
            Logger::log("Registrando salida de almacen");
            
            //Se obtiene al usuario de la sesion
            $id_usuario=SesionController::getCurrentUser();
            if(is_null($id_usuario))
            {
                Logger::error("No se pudo obtener al usuario de la sesion, ya inicio sesion?");
                throw new Exception("No se pudo obtener al usuario de la sesion, ya inicio sesion?");
            }
            
            //valida si el almacen existe y si esta activo
            $almacen = AlmacenDAO::getByPK($id_almacen);
            if(is_null($almacen))
            {
                Logger::error("El almacen con id: ".$id_almacen." no existe");
                throw new Exception("El almacen con id: ".$id_almacen." no existe");
            }
            if(!$almacen->getActivo())
            {
                Logger::error("El almacen no esta activo, no puede salir producto de el");
                throw new Exception("El almacen no esta activo, no puede salir producto de el");
            }
            
            //Valida que el motivo sea un string valido
            if(!is_null($motivo))
            {
                $validar = self::validarString($motivo, 255, "motivo");
                if(is_string($validar))
                {
                    Logger::error($validar);
                    throw new Exception($validar);
                }
            }
            //Se inicializa el registro de la tabla salida_almacen
            $salida_almacen = new SalidaAlmacen(array(
                            "id_almacen" => $id_almacen,
                            "id_usuario" => $id_usuario,
                            "fecha_registro" => date("Y-m-d H:i:s",time()),
                            "motivo" => $motivo
                                    )
                                );
            DAO::transBegin();
            try
            {
                //Se guarda el registro de la salida almacen y se sacan los productos solicitados
                //del almacen
                SalidaAlmacenDAO::save($salida_almacen);
                $producto_salida_almacen=new ProductoSalidaAlmacen(array( "id_salida_almacen" => $salida_almacen->getIdSalidaAlmacen() ));
                foreach($productos as $p)
                {
                    //Se busca en el almacen el producto solicitado, si no es encontrado, se manda una excepcion
                    $producto_almacen=ProductoAlmacenDAO::getByPK($p["id_producto"], $id_almacen, $p["id_unidad"]);
                    if(is_null($producto_almacen))
                    {
                        throw new Exception("El producto: ".$p["id_producto"]." en la unidad: ".$p["id_unidad"]."
                            no se encuentra en el almacen: ".$id_almacen.". No se puede registrar la salida");
                    }
                    //Se analiza la cantidad actual del producto en el almacen. Si la solicitada
                    //es mayor que la cantidad actual, se arroja una excepcion.
                    $cantidad_actual=$producto_almacen->getCantidad();
                    if($cantidad_actual<$p["cantidad"])
                    {
                        throw new Exception("Se busca sacar mas cantidad de producto de la que hay actualmente. Actual: ".$cantidad_actual." - Salida: ".$p["cantidad"]);
                    }
                    $producto_almacen->setCantidad($cantidad_actual-$p["cantidad"]);
                    $producto_salida_almacen->setIdProducto($p["id_producto"]);
                    $producto_salida_almacen->setIdUnidad($p["id_unidad"]);
                    $producto_salida_almacen->setCantidad($p["cantidad"]);
                    ProductoAlmacenDAO::save($producto_almacen);
                    ProductoSalidaAlmacenDAO::save($producto_salida_almacen);
                }/* Fin de foreach */
            } /* Fin de try */
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo registrar la salida de producto: ".$e);
                throw new Exception("No se pudo registrar la salida de producto");
            }
            DAO::transEnd();
            Logger::log("Salida de almacen registrada correctamente");
            return array("id_salida" => $salida_almacen->getIdSalidaAlmacen());
	}
  
	/**
 	 *
 	 *Realiza un corte de caja. Este metodo reduce el dinero de la caja y va registrando el dinero acumulado de esa caja. Si faltase dinero se carga una deuda al cajero. La fecha sera tomada del servidor. El usuario sera tomado de la sesion.
 	 *
 	 * @param saldo_final float Saldo que se dejara en la caja para que continue realizando sus operaciones.
 	 * @param id_caja int Id de la caja a la que se le hace el corte
 	 * @param saldo_real float Saldo real encontrado en la caja
 	 * @param billetes_encontrados json Ids de billetes encontrados en la caja al hacer el corte
 	 * @param billetes_dejados json Ids de billetes dejados en la caja despues de hacer el corte
 	 * @param id_cajero int Id del cajero en caso de que no sea este el que realiza el corte
 	 * @param id_cajero_nuevo int Id del cajero que entrara despues de realizar el corte
 	 * @return id_corte_caja int Id generado por la insercion del nuevo corte
 	 **/
	public static function CorteCaja
	(
		$id_caja, 
		$saldo_final, 
		$saldo_real, 
		$billetes_dejados = null, 
		$billetes_encontrados = null, 
		$id_cajero = null, 
		$id_cajero_nuevo = null
	)
	{
            Logger::log("Realizando corte de caja");
            
            //valida que la caja exista, que este abierta y que este activada
            $caja=CajaDAO::getByPK($id_caja);
            if(is_null($caja))
            {
                Logger::error("La caja con id: ".$id_caja." no existe");
                throw new Exception("La caja con id: ".$id_caja." no existe");
            }
            if(!$caja->getActiva())
            {
                Logger::error("La caja proporcionada no esta activa, no se le puede hacer un corte");
                throw new Exception("La caja proporcionada no esta activa, no se le puede hacer un corte");
            }
            if(!$caja->getAbierta())
            {
                Logger::error("La caja proporcionada esta cerrada, no se pueden realizar movimientos a una caja cerrada");
                throw new Exception("La caja proporcionada esta cerrada, no se pueden realizar movimientos a una caja cerrada");
            }
            
            //se inicializa el registro de la tabla corte de caja
            $corte_de_caja= new CorteDeCaja(array(
                                "id_caja" => $id_caja,
                                "id_cajero" => $id_cajero,
                                "id_cajero_nuevo" => $id_cajero_nuevo,
                                "fecha" => date("Y-m-d H:i:s",time()),
                                "saldo_real" => $saldo_real,
                                "saldo_esperado" => $caja->getSaldo(),
                                "saldo_final" => $saldo_final
                                )
                            );
            DAO::transBegin();
            try
            {
                //Se guarda el corte de caja y se modifica la caja, restandole los billetes encontrados y el saldo de la caja.
                CorteDeCajaDAO::save($corte_de_caja);
                CajasController::modificarCaja($id_caja, 0, $billetes_encontrados, $caja->getSaldo());
                
                //Si se lleva control de billetes, se hac eun registro por cada tipo de billete encontrado.
                //Despues, se buscan los billetes que quedan en la caja entre los tipos de billetes encontrados,
                //si no se encuentran, se crea su registro.
                //Como los billetes ya han sido restados de la caja, los que queden con numeros positivos seran
                //aquellos que hagan falta, y los que queden en numeros negativos seran los que sobraran.
                if($caja->getControlBilletes())
                {
                    $billete_corte_caja = new BilleteCorteCaja(array( 
                                        "id_corte_caja" => $corte_de_caja->getIdCorteDeCaja(), 
                                        "cantidad_dejada" => 0,
                                        "cantidad_sobrante" => 0,
                                        "cantidad_faltante" => 0
                                                )
                                            );
                    foreach($billetes_encontrados as $billete)
                    {
                        $billete_corte_caja->setIdBillete($billete["id_billete"]);
                        $billete_corte_caja->setCantidadEncontrada($billete["cantidad"]);
                        BilleteCorteCajaDAO::save($billete_corte_caja);
                    }
                    $billetes_caja=BilleteCajaDAO::search(new BilleteCaja(array( "id_caja" => $id_caja )));
                    foreach($billetes_caja as $b_c)
                    {
                        $billete_corte_caja=BilleteCorteCajaDAO::getByPK($b_c->getIdBillete(), $corte_de_caja->getIdCorteDeCaja());
                        if(is_null($billete_corte_caja ))
                            $billete_corte_caja = new BilleteCorteCaja(array(
                                                    "id_billete" => $b_c->getIdBillete(),
                                                    "id_corte_caja" => $corte_de_caja->getIdCorteDeCaja(),
                                                    "cantidad_encontrada" => 0,
                                                    "cantidad_dejada" => 0,
                                                    "cantidad_sobrante" => 0,
                                                    "cantidad_faltante" => 0
                                                    )
                                                            );
                        if($b_c->getCantidad()<0)
                        {
                            $billete_corte_caja->setCantidadSobrante($b_c->getCantidad());
                        }
                        else if($b_c->getCantidad()>0)
                        {
                            $billete_corte_caja->setCantidadFaltante($b_c->getCantidad()*-1);
                        }
                        else
                            continue;
                        $b_c->setCantidad(0);
                        BilleteCajaDAO::save($b_c);
                        BilleteCorteCajaDAO::save($billete_corte_caja);
                    }/* Fin del foreach */
                    
                    //Si los billetes dejados despues del corte no son obtenidos y el saldo de la caja
                    //no es cero, se arroja una excepcion.
                    if(is_null($billetes_dejados)&&$saldo_final!=0)
                    {
                        throw new Exception("No se encontro el parametro billetes_dejados cuando se esta llevando control de los billetes en esta caja y su saldo no quedara en 0");
                    }
                    
                    //Por cada billete dejado se busca su registro en la tabla billete_corte_caja, si no existe se crea,
                    //si existe, se actualiza su parametro cantidad_dejada
                    foreach($billetes_dejados as $b_d)
                    {
                         $billete_corte_caja=BilleteCorteCajaDAO::getByPK($b_d["id_billete"], $corte_de_caja->getIdCorteDeCaja());
                         if(is_null($billete_corte_caja ))
                            $billete_corte_caja = new BilleteCorteCaja(array(
                                                    "id_billete" => $b_d["id_billete"],
                                                    "id_corte_caja" => $corte_de_caja->getIdCorteDeCaja(),
                                                    "cantidad_encontrada" => 0,
                                                    "cantidad_dejada" => 0,
                                                    "cantidad_sobrante" => 0,
                                                    "cantidad_faltante" => 0
                                                    )
                                                            );
                         $billete_corte_caja->setCantidadDejada($b_d["cantidad"]);
                         BilleteCorteCajaDAO::save($billete_corte_caja);
                    }
                }/* Fin if control billetes*/
                CajasController::modificarCaja($id_caja, 1, $billetes_dejados, $saldo_final);
            } /* Fin try */
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo realizar el corte de caja: ".$e);
                throw new Exception("No se pudo realizar el corte de caja");
            }
            DAO::transEnd();
            Logger::log("Corte de caja realizado correctamente");
            return array( "id_corte_caja" => $corte_de_caja->getIdCorteDeCaja());
	}
  
	/**
 	 *
 	 *Descativa un almacen. Para poder desactivar un almacen, este tiene que estar vac?
 	 *
 	 * @param id_almacen int Id del almacen a desactivar
 	 **/
	public static function EliminarAlmacen
	(
		$id_almacen
	)
	{
            Logger::log("Eliminando almacen");
            $almacen=AlmacenDAO::getByPK($id_almacen);
            
            //verifica que el almacen exista, que este activo y que su tipo no sea de consignacion
            if(is_null($almacen))
            {
                Logger::error("El almacen con id: ".$id_almacen." no existe");
                throw new Exception("El almacen con id: ".$id_almacen." no existe");
            }
            if(!$almacen->getActivo())
            {
                Logger::warn("El almacen ya esta inactivo");
                throw new Exception("El almacen ya esta inactivo");
            }
            if($almacen->getIdTipoAlmacen()==2)
            {
                Logger::error("No se puede eliminar con este metodo un almacen de tipo consignacion");
                throw new Exception("No se puede eliminar con este metodo un almacen de tipo consignacion");
            }
           
            $almacen->setActivo(0);
            
            //Busca los productos del almacen, si alguno tiene una cantidad distinta a cero,
            //el almacen no puede ser eliminado
            $productos_almacen=ProductoAlmacenDAO::search(new ProductoAlmacen(array( "id_almacen" => $id_almacen )));
            foreach($productos_almacen as $producto_almacen)
            {
                if($producto_almacen->getCantidad()!=0)
                {
                    Logger::error("El almacen no puede ser borrado pues aun contiene productos");
                    throw new Exception("El almacen no puede ser borrado pues aun contiene productos");
                }
            }
            
            //Se cancelaran todos los traspasos que este almacen tenga programados ya sea para enviar o para recibir
            $traspasos_enviados = TraspasoDAO::search( new Traspaso( array( "id_almacen_envia" => $id_almacen ) ) );
            $traspasos_recibidos = TraspasoDAO::search( new Traspaso( array( "id_almacen_recibe" => $id_almacen ) ) );
            
            DAO::transBegin();
            try
            {
                AlmacenDAO::save($almacen);
                foreach($traspasos_enviados as $t_e)
                {
                    if($t_e->getEstado()=="Envio programado")
                        self::CancelarTraspasoAlmacen ($t_e->getIdTraspaso());
                }
                foreach($traspasos_recibidos as $t_r)
                {
                    if(!$t_r->getCancelado()&&!$t_r->getCompleto())
                        self::CancelarTraspasoAlmacen ($t_r->getIdTraspaso());
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo eliminar el almacen: ".$e);
                throw new Exception("No se pudo eliminar el almacen");
            }
            DAO::transEnd();
            Logger::log("Almacen eliminado exitosamente");
	}
  
	/**
 	 *
 	 *Edita la informacion de un almacen
 	 *
 	 * @param id_almacen int Id del almacen a editar
 	 * @param descripcion string Descripcion del almacen
 	 * @param nombre string Nombre del almacen
 	 **/
	public static function EditarAlmacen
	(
		$id_almacen, 
		$descripcion = null, 
		$id_tipo_almacen = null, 
		$nombre = null
	)
	{
            Logger::log("Editando almacen");
            
            //valida los parametros de editar almacen
            $validar = self::validarParametrosAlmacen($id_almacen,null,null,$id_tipo_almacen,$nombre,$descripcion);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            $almacen=AlmacenDAO::getByPK($id_almacen);
            if(!$almacen->getActivo())
            {
                Logger::error("El almacen no esta activo, no puede ser editado");
                throw new Exception("El almacen no esta activo, no puede ser editado");
            }
            
            //Si un parametro no es nulo, se toma como actualizacion
            if(!is_null($descripcion))
            {
                $almacen->setDescripcion($descripcion);
            }
            if(!is_null($nombre))
            {
                //Se verifica que el nombre del almacen no se repita
                $almacenes = AlmacenDAO::search(new Almacen( array( "id_sucursal" => $almacen->getIdSucursal() ) ) );
                foreach($almacenes as $a)
                {
                    if($a->getNombre()==trim($nombre) && $a->getIdAlmacen()!=$id_almacen)
                    {
                        Logger::log("El nombre (".$nombre.") ya esta siendo usado por el almacen: ".$a->getIdAlmacen());
                        throw new Exception("El nombre ya esta en uso");
                    }
                }
                $almacen->setNombre(trim($nombre));
            }
            
            //El tipo de almacen no puede ser cambiado a un almacen de consignacion, 
            //ni un almacen de consignacion puede ser cambiado a otro tipo de almacen.
            if(!is_null($id_tipo_almacen))
            {
                if($id_tipo_almacen==2)
                {
                    Logger::warn("Se busca cambiar el tipo de almacen para volverse un almacen de consignacion, no se hara nada pues esto no esta permitido");
                }
                else if($almacen->getIdTipoAlmacen()==2)
                {
                    Logger::warn("Se busca editar el tipo de almacen a un almacen de consignacion, no se hara nada pues esto no esta permitido");
                }
                else
                {
                    $almacen->setIdTipoAlmacen($id_tipo_almacen);
                }
            }
            DAO::transBegin();
            try
            {
                AlmacenDAO::save($almacen);
            }
            catch(Exception $e)
            {
                DAO::transRollBack();
                Logger::error("No se pudo editar el almacen: ".$e);
                throw new Exception("No se pudo editar el almacen");
            }
            DAO::transEnd();
            Logger::log("Almacen editado exitosamente");
	}
  
	/**
 	 *
 	 *Edita la informacion de una caja
 	 *
 	 * @param id_caja int Id de la caja a editar
 	 * @param descripcion string Descripcion de la caja
 	 * @param token string Token generado por el pos client
 	 **/
	public static function EditarCaja
	(
		$id_caja, 
		$control_billetes = null, 
		$descripcion = null, 
		$token = null
	)
	{
            Logger::log("Editando caja");
            
            //Valida los parametros de la caja y que la caja este activa
            $validar = self::validarParametrosCaja($id_caja,null,$token,$descripcion);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            $caja=CajaDAO::getByPK($id_caja);
            if(!$caja->getActiva())
            {
                Logger::error("La caja no esta activa, no se puede editar");
                throw new Exception("La caja no esta activa, no se puede editar");
            }
            
            //Si un parametro no es nulo, se toma como actualizacion
            if(!is_null($descripcion))
            {
                $caja->setDescripcion($descripcion);
            }
            if(!is_null($token))
            {
                $caja->setToken($token);
            }
            if(!is_null($control_billetes))
            {
                $caja->setControlBilletes($control_billetes);
            }
            
            DAO::transBegin();
            try
            {
                CajaDAO::save($caja);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo editar la caja: ".$e);
                throw new Exception("No se pudo editar la caja");
            }
            DAO::transEnd();
            Logger::log("Caja editada exitosamente");
	}
  
	/**
 	 *
 	 *Desactiva una caja, para que la caja pueda ser desactivada, tieneq ue estar cerrada
 	 *
 	 * @param id_caja int Id de la caja a eliminar
 	 **/
	public static function EliminarCaja
	(
		$id_caja
	)
	{  
            Logger::log("Eliminando caja ".$id_caja);
            
            //verifica que la caja exista y que este activa
            $caja=CajaDAO::getByPK($id_caja);
            if(is_null($caja))
            {
                Logger::error("La caja con id: ".$id_caja." no existe");
                throw new Exception("La caja con id: ".$id_caja." no existe");
            }
            if(!$caja->getActiva())
            {
                Logger::warn("La caja ya ha sido eliminada");
                throw new Exception("La caja ya ha sido eliminada");
            }
            
            //Si la caja esta abierta, mandas una excepcion, pues solo se pueden eliminar cajas cerradas
            if($caja->getAbierta())
            {
                Logger::error("La caja esta abierta y no puede ser eliminada");
                throw new Exception("La caja esta abierta y no puede ser eliminada");
            }
            
            //Si el saldo de la caja no es cero, no se puede eliminar
            if($caja->getSaldo()!=0)
            {
                Logger::error("El saldo de la caja no esta en 0, no se puede eliminar");
                throw new Exception("El saldo de la caja no esta en 0, no se puede eliminar");
            }
            $caja->setActiva(0);
            DAO::transBegin();
            try
            {
                CajaDAO::save($caja);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("Error al eliminar la caja ".$e);
                throw new Exception("Error al eliminar la caja");
            }
            DAO::transEnd();
            Logger::log("Caja eliminada exitosamente");
	}
  
	/**
 	 *
 	 *Desactiva una sucursal. Para poder desactivar una sucursal su saldo a favor tiene que ser mayor a cero y sus almacenes tienen que estar vacios.
 	 *
 	 * @param id_sucursal int Id de la sucursal a desactivar
 	 **/
	public static function Eliminar
	(
		$id_sucursal
	)
	{
            Logger::log("Eliminando sucursal ".$id_sucursal);
            
            //verifica que la caja exista y este activa
            $sucursal=SucursalDAO::getByPK($id_sucursal);
            if(is_null($sucursal))
            {
                Logger::error("La sucursal con id :".$id_sucursal." no existe");
                throw new Exception("La sucursal con id :".$id_sucursal." no existe");
            }
            if(!$sucursal->getActiva())
            {
                Logger::warn("La sucursal ya ha sido eliminada");
                throw new Exception("La sucursal ya ha sido eliminada");
            }
            
            //Si el saldo a favor de la sucursal no es cero, no se puede eliminar
            if($sucursal->getSaldoAFavor()!=0)
            {
                Logger::error("La sucursal no tiene un saldo de 0 y no puede ser eliminada");
                throw new Exception("La sucursal no tiene un saldo de 0 y no puede ser eliminada");
            }
            
            $almacenes = AlmacenDAO::search( new Almacen( array( "id_sucursal" => $id_sucursal ) ) );
            
            $sucursal->setFechaBaja(date("Y-m-d H:i:s"));
            $sucursal->setActiva(0);
            DAO::transBegin();
            try
            {
                SucursalDAO::save($sucursal);
                
                //busca las cajas asociadas con esta sucursal y las cierra con el metodo EliminarCaja,
                //si alguna presenta un error habra un rollback y no se eliminara la sucursal
                $cajas=CajaDAO::search(new Caja(array( "id_sucursal" => $id_sucursal )));
                foreach($cajas as $c)
                {
                    self::EliminarCaja($c->getIdCaja());
                }
                
                //Elimina la relacion con los impuestos, paquetes, retenciones y servicios referidos a esta sucursal
                $impuestos_sucursal=ImpuestoSucursalDAO::search(new ImpuestoSucursal(array( "id_sucursal" => $id_sucursal )));
                foreach($impuestos_sucursal as $i_e)
                {
                    ImpuestoSucursalDAO::delete($i_e);
                }
                $paquetes_sucursal=PaqueteSucursalDAO::search(new PaqueteSucursal(array( "id_sucursal" => $id_sucursal )));
                foreach($paquetes_sucursal as $p_s)
                {
                    PaqueteSucursalDAO::delete($p_s);
                }
                $retencion_sucursal=RetencionSucursalDAO::search(new RetencionSucursal(array( "id_sucursal" => $id_sucursal )));
                foreach($retencion_sucursal as $r_s)
                {
                    RetencionSucursalDAO::delete($r_s);
                }
                $servicio_sucursal=ServicioSucursalDAO::search(new ServicioSucursal(array( "id_sucursal" => $id_sucursal )));
                foreach($servicio_sucursal as $s_s)
                {
                    ServicioSucursalDAO::delete($s_s);
                }
                
                //Se eliminan los almacenes de esta sucursal
                foreach($almacenes as $almacen)
                {
                    if($almacen->getActivo())
                    {
                         $flag = false;
                        if($almacen->getIdTipoAlmacen()==2)
                        {
                            $flag=true;
                            $almacen->setIdTipoAlmacen(1);
                            AlmacenDAO::save($almacen);
                        }
                        self::EliminarAlmacen($almacen->getIdAlmacen());
                        if($flag)
                        {
                            $flag = false;
                            $almacen->setIdTipoAlmacen(2);
                            AlmacenDAO::save($almacen);
                        }
                    }
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("La sucursal no pudo ser eliminada ".$e);
                throw new Exception("La sucursal no pudo ser eliminada");
            }
            DAO::transEnd();
            Logger::log("Sucursal eliminada exitosamente");
	}
  
	/**
 	 *
 	 *Crea un registro de traspaso de producto de un almacen a otro. El usuario que envia sera tomada de la sesion.
 	 *
 	 * @param id_almacen_recibe int Id del almacen al que se envia el producto
 	 * @param id_almacen_envia int Id del almacen que envia el producto
 	 * @param fecha_envio_programada string Fecha de envio programada para este traspaso
 	 * @param productos json Productos a ser enviados con sus cantidades
 	 * @return id_traspaso int Id del traspaso autogenerado
 	 **/
	public static function ProgramarTraspasoAlmacen
	(
		$fecha_envio_programada, 
		$id_almacen_envia, 
		$id_almacen_recibe, 
		$productos
	)
	{  
            Logger::log("Creando traspaso");
            
            //Se obtiene al usuario de la sesion actual
            $id_usuario=SesionController::getCurrentUser();
            if(is_null($id_usuario))
            {
                Logger::error("No se pudo obtener al usuario de la sesion, ya inicio sesion?");
                throw new Exception("No se pudo obtener al usuario de la sesion, ya inicio sesion?");
            }
            
            //verifica que los almacenes no sean iguales, que existan en la base de datos, que esten abiertos
            if($id_almacen_envia == $id_almacen_recibe)
            {
                Logger::error("El almacen que envia es el mismo que el que recibe");
                throw new Exception("El almacen que envia es el mismo que el que recibe");
            }
            $validar = self::validarParametrosTraspaso(null, NULL, $id_almacen_recibe, $fecha_envio_programada);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            $validar = self::validarParametrosTraspaso(null,null,$id_almacen_envia);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            $almacen_recibe = AlmacenDAO::getByPK($id_almacen_recibe);
            if(!$almacen_recibe->getActivo())
            {
                Logger::error("El almacen que recibe no esta activo, no se le puede programar un traspaso");
                throw new Exception("El almacen que recibe no esta activo, no se le puede programar un traspaso");
            }
            $almacen_envia = AlmacenDAO::getByPK($id_almacen_envia);
            if(!$almacen_envia->getActivo())
            {
                Logger::error("El almacen que envia no esta activo, no se le puede programar un traspaso");
                throw new Exception("El almacen que envia no esta activo, no se le puede programar un traspaso");
            }
            
            //Inicializa el registro de traspaso
            $traspaso=new Traspaso(array(
                            "id_usuario_programa"   => $id_usuario,
                            "id_usuario_envia"      => 0,
                            "id_almacen_envia"      => $id_almacen_envia,
                            "fecha_envio_programada"=> $fecha_envio_programada,
                            "fecha_envio"           => "0000-00-00 00:00:00",
                            "id_usuario_recibe"     => 0,
                            "id_almacen_recibe"     => $id_almacen_recibe,
                            "fecha_recibo"          => "0000-00-00 00:00:00",
                            "estado"                => "Envio programado",
                            "cancelado"             => 0,
                            "completo"              => 0
                            )
                        );
            DAO::transBegin();
            try
            {
                //Se guarda el registro de traspaso y se insertan los registros de traspaso_producto.
                TraspasoDAO::save($traspaso);
                $traspaso_producto=new TraspasoProducto(array(
                                    "id_traspaso"       => $traspaso->getIdTraspaso(),
                                    "cantidad_recibida" => 0
                                        )
                                    );
                foreach($productos as $p)
                {
                    //Se validan los parametros de producto
                    if(is_null(ProductoDAO::getByPK($p["id_producto"])))
                    {
                        throw new Exception("El producto con id: ".$p["id_producto"]." no existe");
                    }
                    if(is_null(UnidadDAO::getByPK($p["id_unidad"])))
                    {
                        throw new Exception("La unidad con id: ".$p["id_unidad"]." no existe");
                    }
                    $validar = self::validarNumero($p["cantidad"], 1.8e200, "cantidad");
                    if(is_string($validar))
                    {
                        throw new Exception($validar);
                    }
                    
                    //Se valida que los productos enviados pertenezcan a la empresa del almacen que recibe,
                    //pues un almacen no puede tener producto de una empresa que no es la suya.
                    $productos_empresa = ProductoEmpresaDAO::search( new ProductoEmpresa( array( "id_producto" => $p["id_producto"] ) ) );
                    $encontrado = false;
                    foreach($productos_empresa as $p_e)
                    {
                        if($p_e->getIdEmpresa() == $almacen_recibe->getIdEmpresa())
                        {
                            $encontrado = true;
                            break;
                        }
                    }
                    if(!$encontrado)
                    {
                        throw new Exception("Se quiere enviar producto que pertenece a otra empresa");
                    }
                        
                    $traspaso_producto->setIdProducto($p["id_producto"]);
                    $traspaso_producto->setIdUnidad($p["id_unidad"]);
                    $traspaso_producto->setCantidadEnviada($p["cantidad"]);
                    TraspasoProductoDAO::save($traspaso_producto);
                }/* Fin de foreach */
            }/* Fin de try */
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("Error al crear el traspaso de producto: ".$e);
                throw new Exception("Error al crear el traspaso de producto");
            }
            DAO::transEnd();
            Logger::log("traspaso creado exitosamente");
            return array("id_traspaso" => $traspaso->getIdTraspaso());
	}
  
	/**
 	 *
 	 *Cambia el estado del traspaso a enviado y captura la fecha de envio del servidor. El usuario que envia sera tomado del servidor.
 	 *
 	 * @param id_traspaso int Id del traspaso a enviar
 	 **/
	public static function EnviarTraspasoAlmacen
	(
		$id_traspaso
	)
	{  
            Logger::log("Enviando traspaso: ".$id_traspaso);
            
            //Se obtiene al usuario de la sesion
            $id_usuario=SesionController::getCurrentUser();
            if(is_null($id_usuario))
            {
                Logger::error("No se puede obtener al usuario de la sesion, ya inicio sesion?");
                throw new Exception("No se puede obtener al usuario de la sesion, ya inicio sesion?");
            }
            
            //Valida que el traspaso exista, que no haya sido cancelado ni completado ni que si estado sea el de enviado
            $validar = self::validarParametrosTraspaso($id_traspaso);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            $traspaso=TraspasoDAO::getByPK($id_traspaso);
            if($traspaso->getCancelado())
            {
                Logger::error("El traspaso ya ha sido cancelado, no se puede enviar");
                throw new Exception("El traspaso ya ha sido cancelado, no se puede enviar");
            }
            if($traspaso->getCompleto())
            {
                Logger::error("El traspaso ya ha sido completado, no se puede enviar");
                throw new Exception("El traspaso ya ha sido completado, no se puede enviar");
            }
            if($traspaso->getEstado()==="Enviado")
            {
                Logger::warn("El traspaso ya ha sido enviado");
                throw new Exception("El traspaso ya ha sido enviado");
            }
            
            //Actualiza el registro del traspaso
            $traspaso->setFechaEnvio(date("Y-m-d H:i:s"));
            $traspaso->setIdUsuarioEnvia($id_usuario);
            $traspaso->setEstado("Enviado");
            
            //Verifica que el almacen programado para el envio siga existiendo
            if(is_null(AlmacenDAO::getByPK($traspaso->getIdAlmacenEnvia())))
            {
                Logger::error("FATAL!!! el traspaso no cuenta con un almacen q envia");
                throw new Exception("FATAL!!! el traspaso no cuenta con un almacen q envia");
            }
            
            //Se obtienen los productos programados a enviarse para este traspaso
            $productos_traspaso=TraspasoProductoDAO::search(new TraspasoProducto(array( "id_traspaso" => $id_traspaso )));
            DAO::transBegin();
            try
            {
                TraspasoDAO::save($traspaso);
                //verifica que se puedan eliminar los productos a enviar del almacen que envia.
                //es decir, si alguno no cuenta con suficiente cantidad o simplemente no existe
                //el traslado no puede ser efectuado.
                foreach($productos_traspaso as $p_t)
                {
                    $producto_almacen=ProductoAlmacenDAO::getByPK($p_t->getIdProducto(), $traspaso->getIdAlmacenEnvia(), $p_t->getIdUnidad());
                    if(is_null($producto_almacen))
                    {
                        throw new Exception("No se puede enviar el producto de este almacen ya que no hay existencias");
                    }
                    if($producto_almacen->getCantidad()<$p_t->getCantidadEnviada())
                    {
                        throw new Exception("No se puede enviar esta cantidad de producto de este almacen ya que no hay existencias");
                    }
                    $producto_almacen->setCantidad($producto_almacen->getCantidad()-$p_t->getCantidadEnviada());
                    ProductoAlmacenDAO::save($producto_almacen);
                }/* Fin de foreach */
            } /* Fin del try */
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo enviar el traspaso ".$e);
                throw new Exception("No se pudo enviar el traspaso");
            }
            DAO::transEnd();
            Logger::log("Traspaso enviado exitosamente");
	}
  
	/**
 	 *
 	 *Cambia el estado de un traspaso a recibido. La  bandera de completo se prende si los productos enviados son los mismos que los recibidos. La fecha de recibo es tomada del servidor. El usuario que recibe sera tomada de la sesion actual.
 	 *
 	 * @param productos json Productos que se reciben con sus cantidades
 	 * @param id_traspaso int Id del traspaso que se recibe
 	 **/
	public static function RecibirTraspasoAlmacen
	(
		$id_traspaso,
		$productos
	)
	{  
            Logger::log("Recibiendo traspaso ".$id_traspaso);
            
            //Se obtiene al usuario de la sesion
            $id_usuario=SesionController::getCurrentUser();
            if(is_null($id_usuario))
            {
                Logger::error("El usuario no puede ser obtenido de la sesion, ya inicio sesion?");
                throw new Exception("El usuario no puede ser obtenido de la sesion, ya inicio sesion?");
            }
            //Valida que el traspaso exista, que no haya sido cancelado ni completado y que su estado sea el de enviado
            $validar = self::validarParametrosTraspaso($id_traspaso);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            $traspaso=TraspasoDAO::getByPK($id_traspaso);
            if($traspaso->getCancelado())
            {
                Logger::error("El traspaso ya ha sido cancelado, no puede ser recibido");
                throw new Exception("El traspaso ya ha sido cancelado, no puede ser recibido");
            }
            if($traspaso->getCompleto())
            {
                Logger::error("El traspaso ya ha sido completado, no puede volver a ser recibido");
                throw new Exception("El traspaso ya ha sido completado, no puede volver a ser recibido");
            }
            if($traspaso->getEstado()!== "Enviado")
            {
                Logger::error("El traspaso no ha sido enviado");
                throw new Exception("El traspaso no ha sido enviado");
            }
            
            //Actualiza el registro de traspaso
            $traspaso->setIdUsuarioRecibe($id_usuario);
            $traspaso->setFechaRecibo(date("Y-m-d H:i:s"));
            $traspaso->setEstado("Recibido");
            $traspaso->setCompleto(1);
            DAO::transBegin();
            try
            {
                //Guarda el traspaso e inserta los productos en el almacen que recibe
                TraspasoDAO::save($traspaso);
                foreach($productos as $p)
                {
                    $producto_traspaso=TraspasoProductoDAO::getByPK($id_traspaso, $p["id_producto"],$p["id_unidad"]);
                    
                    //Si el producto que recibe no esta en el registro de productos enviados, se crea su registro
                    if(is_null($producto_traspaso))
                    {
                        $producto_traspaso= new TraspasoProducto(array(
                                                        "id_traspaso"       => $id_traspaso,
                                                        "id_producto"       => $p["id_producto"],
                                                        "id_unidad"         => $p["id_unidad"],
                                                        "cantidad_enviada"  => 0
                                                        )
                                                    );
                    }
                    
                    //Se busca el producto que sera isnertado en el almacen, si no existe se crea su registro
                    $producto_almacen=ProductoAlmacenDAO::getByPK($p["id_producto"], $traspaso->getIdAlmacenRecibe(), $p["id_unidad"]);
                    if(is_null($producto_almacen))
                    {
                        $producto_almacen= new ProductoAlmacen(array(
                                                    "id_producto"   => $p_t->getIdProducto(),
                                                    "id_almacen"    => $traspaso->getIdAlmacenEnvia(),
                                                    "id_unidad"     => $p_t->getIdUnidad(),
                                                    "cantidad"      => 0
                                                    )
                                                );
                    }
                    //Se incrementa la cantidad del producto y se guarda el registro.
                    $producto_almacen->setCantidad($producto_almacen->getCantidad()+$p["cantidad"]);
                    $producto_traspaso->setCantidadRecibida($p["cantidad"]);
                    ProductoAlmacenDAO::save($producto_almacen);
                    TraspasoProductoDAO::save($producto_traspaso);
                }/* Fin de foreach  */
            } /* Fin del try */
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo recibir el traspaso: ".$e);
                throw new Exception("No se pudo recibir el traspaso");
            }
            DAO::transEnd();
            Logger::log("El traspaso ha sido recibido exitosamente");
	}
  
	/**
 	 *
 	 *Para poder cancelar un traspaso, este no tuvo que haber sido enviado aun.
 	 *
 	 * @param id_traspaso int Id del traspaso a cancelar
 	 **/
	public static function CancelarTraspasoAlmacen
	(
		$id_traspaso
	)
	{  
           Logger::log("Cancelando traspaso: ".$id_traspaso);
           
           //verifica que el traspaso exista, que no haya sido cancelado, o que no hayan efectuado cambios sobre el
           $traspaso=TraspasoDAO::getByPK($id_traspaso);
           if(is_null($traspaso))
           {
               Logger::error("El traspaso con id: ".$id_traspaso." no existe");
               throw new Exception("El traspaso con id: ".$id_traspaso." no existe");
           }
           if($traspaso->getCancelado())
           {
               Logger::warn("El traspaso ya ha sido cancelado");
               throw new Exception("El traspaso ya ha sido cancelado");
           }
           if($traspaso->getCompleto()||$traspaso->getEstado()!=="Envio programado")
           {
               Logger::error("El traspaso no puede ser cancelado pues ya se han realizado acciones sobre el");
               throw new Exception("El traspaso no puede ser cancelado pues ya se han realizado acciones sobre el");
           }
           
           //Actualiza el registro de traspaso
           $traspaso->setCancelado(1);
           $traspaso->setEstado("Cancelado");
           DAO::transBegin();
           try
           {
               TraspasoDAO::save($traspaso);
           }
           catch(Exception $e)
           {
               DAO::transRollback();
               Logger::error("No se pudo cancelar el traspaso: ".$e);
               throw new Exception("No se pudo cancelar el traspaso");
           }
           DAO::transEnd();
           Logger::log("Traspaso cancelado exitosamente");
	}
  
	/**
 	 *
 	 *Lista los traspasos de almacenes. Puede filtrarse por empresa, por sucursal, por almacen, por producto, cancelados, completos, estado
 	 *
 	 * @param cancelado bool Si este valor no es obtenido, se listaran los traspasos tanto cancelados como no cancelados. Si su valor es verdadero se listaran solo los traspasos cancelados, si su valor es falso, se listaran los traspasos no cancelados
 	 * @param completo bool Si este valor no es obtenido, se listaran los traspasos tanto completos como no completos. Si su valor es verdadero, se listaran los traspasos completos, si es falso, se listaran los traspasos no completos
 	 * @param id_producto int Se listaran los traspasos que incluyan este producto
 	 * @param id_almacen int Se listaran los traspasos enviados y/o recibidos por este almacen
 	 * @param enviados bool Si este valor no es obtenido, se listaran los traspasos tanto enviados como recibidos de este almacen (campo id_almacen). Si su valor es verdader, se listaran los traspasos enviados por este almacen, si su valor es falso, se listaran los traspasos recibidos por este almacen
 	 * @param id_sucursal int Se listaran los traspasos de los almacenes de esta sucursal
 	 * @param id_empresa int Se listaran los traspasos de los almacenes de esta empresa
 	 * @param estado string Se listaran los traspasos cuyo estado sea este, si no es obtenido este valor, se listaran los traspasos de cualqueir estado
 	 * @param ordenar json Determina el orden de la lista
 	 * @return traspasos json Lista de traspasos
 	 **/
	public static function ListaTraspasoAlmacen
	(
		$cancelado = null, 
		$completo = null, 
		$estado = null, 
		$id_almacen_envia = null, 
		$id_almacen_recibe = null, 
		$ordenar = null
	)
	{  
            Logger::log("Listando traspaso de almacenes");
            
            //verifica si se recibieron parametros
            $parametros=false;
            if(
                    !is_null($cancelado)        ||
                    !is_null($completo)         ||
                    !is_null($id_almacen_recibe)||
                    !is_null($id_almacen_envia) ||
                    !is_null($estado)
            )
                $parametros=true;
            $traspasos_almacen=null;
            
            //Si se reciberon parametros, se crea una variable criterio y en base a esa variable se buscan
            //en la base de datos con el metodo search().
            //Si no se reciben parametros se listan todos los traspasos con el metodo getAll().
            if($parametros)
            {
                Logger::log("Se recibieron parametros, se listan los traspasos en rango");
                $traspaso_criterio=new Traspaso(array(
                                            "cancelado"         => $cancelado,
                                            "completo"          => $completo,
                                            "id_almacen_envia"  => $id_almacen_envia,
                                            "id_almacen_recibe" => $id_almacen_recibe,
                                            "estado"            => $estado
                                            )
                                        );
                $traspasos_almacen=TraspasoDAO::search($traspaso_criterio);
            }
            else
            {
                Logger::log("No se recibieron parametros, se listan todos los traspasos");
                $traspasos_almacen=TraspasoDAO::getAll(null,null,$ordenar);
            }
            Logger::log("lista de traspasos recibida con ".count($traspasos_almacen)." elementos");
            return $traspasos_almacen;
	}
  
	/**
 	 *
 	 *Para poder editar un traspaso,este no tuvo que haber sido enviado aun
 	 *
 	 * @param id_traspaso int Id del traspaso a editar
 	 * @param productos json Productos a enviar con sus cantidades
 	 * @param fecha_envio_programada string Fecha de envio programada
 	 **/
	public static function EditarTraspasoAlmacen
	(
		$id_traspaso, 
		$fecha_envio_programada = null, 
		$productos = null
	)
	{  
            Logger::log("Editando traspaso ".$id_traspaso);
            
            //valida que el traspaso exista, que no haya sido cancelado y que no se hayan efectuado operaciones sobre el.
            $validar = self::validarParametrosTraspaso($id_traspaso, null, null, $fecha_envio_programada);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            $traspaso=TraspasoDAO::getByPK($id_traspaso);
            if($traspaso->getCancelado())
            {
                Logger::error("El traspaso ya ha sido cancelado, no puede ser editado");
                throw new Exception("El traspaso ya ha sido cancelado, no puede ser editado");
            }
            if($traspaso->getCompleto()||$traspaso->getEstado()!=="Envio programado")
            {
                Logger::error("No se puede editar el traspaso de almacen pues ya se han realizado acciones sobre el");
                throw new Exception("No se puede editar el traspaso de almacen pues ya se han realizado acciones sobre el");
            }
            
            //Si se recibe el parametro fecha de envio se toma como actualizacion.
            if(!is_null($fecha_envio_programada))
            {
                $traspaso->setFechaEnvioProgramada($fecha_envio_programada);
            }
            DAO::transBegin();
            try
            {
                TraspasoDAO::save($traspaso);
                if(!is_null($productos))
                {
                    //Se actualiza la cantidad de cada producto programado para este traspaso, si el producto
                    //no se encuentra, se verifica que su empresa concuerde con la del almacen de recibo y 
                    //se crea el nuevo registro.
                    //
                    //Despues, se recorren los productos que se encuentran actualmente programados a enviarse en el traspaso,
                    //los productos que no se encuentre en la nueva lista obtenida seran eliminados.
                    foreach($productos as $p)
                    {
                        $traspaso_producto=TraspasoProductoDAO::getByPK($id_traspaso, $p["id_producto"],$p["id_unidad"]);
                        if(is_null($traspaso_producto))
                        {
                            $almacen_recibe = AlmacenDAO::getByPK($traspaso->getIdAlmacenRecibe());
                            $productos_empresa = ProductoEmpresaDAO::search( new ProductoEmpresa( array( "id_producto" => $p["id_producto"] ) ) );
                            $encontrado = false;
                            foreach($productos_empresa as $p_e)
                            {
                                if($p_e->getIdEmpresa() == $almacen_recibe->getIdEmpresa())
                                {
                                    $encontrado = true;
                                }
                            }
                            if(!$encontrado)
                            {
                                throw new Exception("Se busca enviar un producto que no es de la empresa del almacen que recibe");
                            }
                            $traspaso_producto=new TraspasoProducto(array(
                                                            "id_traspaso"       => $id_traspaso,
                                                            "id_producto"       => $p["id_producto"],
                                                            "id_unidad"         => $p["id_unidad"],
                                                            "cantidad_recibida" => 0
                                                            )
                                                        );
                        }
                        $traspaso_producto->setCantidadEnviada($p["id_unidad"]);
                        TraspasoProductoDAO::save($traspaso_producto);
                    }/* Fin de foreach de productos*/
                    $traspasos_producto=TraspasoProductoDAO::search(new TraspasoProducto(array( "id_traspaso" => $id_traspaso )));

                    foreach($traspasos_producto as $t_p)
                    {
                        $encontrado=false;
                        foreach($productos as $p)
                        {
                            if($t_p->getIdProducto()==$p["id_producto"]&&$t_p->getIdUnidad()==$p["id_unidad"])
                            {
                                $encontrado=true;
                            }
                        }
                        if(!$encontrado)
                        {
                            TraspasoProductoDAO::delete($t_p);
                        }
                    }/* Fin de foreach de traspaso_producto*/
                } /* Fin de if de productos */
            } /* Fin de try */
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo editar el traspaso: ".$e);
                throw new Exception("No se pudo editar el traspaso");
            }
            DAO::transEnd();
            Logger::log("Traspaso editado correctamente");
	}
  
	/**
 	 *
 	 *Lista las cajas. Se puede filtrar por la sucursal a la que pertenecen.
 	 *
 	 * @param id_sucursal int Sucursal de la cual se listaran sus cajas
 	 * @param activa bool Valor de activa de las cajas que se listaran
 	 * @return cajas json Objeto que contendra la lista de cajas
 	 **/
	public static function ListaCaja
	(
		$activa = null,		
		$id_sucursal = null
	)
	{  
            Logger::log("Listando cajas");
            //Se validan los parametros
            $validar = self::validarParametrosCaja(null,$id_sucursal,null,null,null,null,null,$activa);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            //Si no se reciben parametros, se llama a todas las cajas
            if(is_null($id_sucursal) && is_null($activa) )
                $cajas = CajaDAO::getAll ();
            else
                $cajas = CajaDAO::search(new Caja( array("id_sucursal" => $id_sucursal, "activa" => $activa) ));
            Logger::log("Se encontraron ".count($cajas)." de cajas");
            return $cajas;
	}
        
        
	/**
 	 *
 	 *Edita un tipo de almacen
 	 *
 	 * @param id_tipo_almacen int Id del tipo de almacen a editar
 	 * @param descripcion string Descripcion del tipo de almacen
 	 **/
        public static function EditarTipo_almacen
	(
		$id_tipo_almacen, 
		$descripcion = null
	)
        {
            Logger::log("Editando tipo de almacen ".$id_tipo_almacen);
            
            //Se valida que el tipo de almacen exista
            $tipo_almacen = TipoAlmacenDAO::getByPK($id_tipo_almacen);
            if(is_null($tipo_almacen))
            {
                Logger::error("El tipo de almacen ".$id_tipo_almacen." no existe");
                throw new Exception("El tipo de almacen ".$id_tipo_almacen." no existe");
            }
            
            //Se valida el parametro descripcion
            if(!is_null($descripcion))
            {
                $validar = self::validarString($descripcion, 64, "descripcion");
                if(is_string($validar))
                {
                    Logger::error($validar);
                    throw new Exception($validar,901);
                }
                
                $tipos_almacen = array_diff(TipoAlmacenDAO::search(
                        new TipoAlmacen( array( "descripcion" => trim($descripcion) ) )), array( TipoAlmacenDAO::getByPK($id_tipo_almacen) ) );
                if(!empty($tipos_almacen))
                {
                    Logger::error("La descripcion (".$descripcion.") es repetida");
                    throw new Exception("La descripcion esta repetida");
                }
                
                $tipo_almacen->setDescripcion($descripcion);
            }
            
            DAO::transBegin();
            try
            {
                TipoAlmacenDAO::save($tipo_almacen);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo editar el tipo de almacen ".$e);
                throw new Exception("No se pudo editar el tipo de almacen ",901);
            }
            DAO::transEnd();
            Logger::log("Tipo de almacen editado correctamente");
            
        }
  
  
	
  
	/**
 	 *
 	 *Elimina un tipo de almacen
 	 *
 	 * @param id_tipo_almacen int Id del tipo de almacen a editar
 	 **/
        public static function EliminarTipo_almacen
	(
		$id_tipo_almacen
	)
        {
            Logger::log("Eliminando tipo de almacen ".$id_tipo_almacen);
            
            //El almacen de consignacion no se puede borrar
            if($id_tipo_almacen==2)
            {
                Logger::error("Se intento eliminar el almacen de consginacion");
                throw new Exception("El almacen de consignacion no puede ser eliminado",901);
            }
            
            //Se valida que el tipo de almacen exista
            $tipo_almacen = TipoAlmacenDAO::getByPK($id_tipo_almacen);
            if(is_null($tipo_almacen))
            {
                Logger::error("El tipo de almacen ".$id_tipo_almacen." no existe");
                throw new Exception("El tipo de almacen ".$id_tipo_almacen." no existe",901);
            }
            
            //Si un almacen activo aun pertenece a este tipo de almacen, no se podra eliminar
            $almacenes = AlmacenDAO::search( new Almacen( array( "id_tipo_almacen" => $id_tipo_almacen ) ) );
            foreach($almacenes as $almacen)
            {
                if($almacen->getActivo())
                {
                    Logger::error("No se puede eliminar el tipo de almacen ".$id_tipo_almacen." pues aun es usado por almacenes activos");
                    throw new Exception("No se puede eliminar este tipo de almacen pues aun hay almacenes activos con este tipo",901);
                }
            }
            
            
            DAO::transBegin();
            try
            {
                TipoAlmacenDAO::delete($tipo_almacen);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo eliminar el tipo de almacen: ".$e);
                throw new Exception("No se pudo eliminar el tipo de almacen, consulte a su administrador de sistema");
            }
            DAO::transEnd();
            Logger::log("Tipo de almacen eliminado exitosamente");
            
        }
  
  
	
  
	/**
 	 *
 	 *Imprime la lista de tipos de almacen
 	 *
 	 * @return lista_tipos_almacen json Arreglo con la lista de almacenes
 	 **/
        public static function ListaTipo_almacen
	(
	)
        {
            Logger::log("Listando tipos de almacen");
            return TipoAlmacenDAO::getAll();
        }
  
  
	
  
	/**
 	 *
 	 *Crea un nuevo tipo de almacen
 	 *
 	 * @param descripcion string Descripcion de este tipo de almacen
 	 * @return id_tipo_almacen int Id del tipo de almacen
 	 **/
        public static function NuevoTipo_almacen
	(
		$descripcion
	)
        {
            Logger::log("Creando nuevo tipo de almacen");
            
            $validar = self::validarString($descripcion, 64, "descripcion");
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar,901);
            }
            
            //No se puede repetir la descripcion del tipo de almacen
            $tipos_almacen = TipoAlmacenDAO::search(new TipoAlmacen( array( "descripcion" => trim($descripcion) ) ));
            if(!empty($tipos_almacen))
            {
                Logger::error("La descripcion (".$descripcion.") es repetida");
                throw new Exception("La descripcion esta repetida");
            }
            
            $tipo_almacen = new TipoAlmacen( array( "descripcion" => trim($descripcion) ) );
            
            DAO::transBegin();
            try
            {
                TipoAlmacenDAO::save($tipo_almacen);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo crear el nuevo tipo de almacen: ".$e);
                throw new Exception("No se pudo crear el nuevo tipo de almacen, contacte a su administrador de sistema");
            }
            DAO::transEnd();
            Logger::log("Tipo de almacen creado exitosamente");
            
            return array( "id_tipo_almacen" =>  $tipo_almacen->getIdTipoAlmacen());
            
        }
  }
