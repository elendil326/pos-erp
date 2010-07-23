SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `pos` ;
CREATE SCHEMA IF NOT EXISTS `pos` DEFAULT CHARACTER SET utf8 ;
USE `pos` ;

-- -----------------------------------------------------
-- Table `pos`.`cliente`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`cliente` ;

CREATE  TABLE IF NOT EXISTS `pos`.`cliente` (
  `id_cliente` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador del cliente' ,
  `rfc` VARCHAR(20) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL COMMENT 'rfc del cliente si es que tiene' ,
  `nombre` VARCHAR(100) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL COMMENT 'nombre del cliente' ,
  `direccion` VARCHAR(300) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL COMMENT 'domicilio del cliente calle, no, colonia' ,
  `telefono` VARCHAR(25) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL COMMENT 'Telefono del cliete' ,
  `e_mail` VARCHAR(60) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT '0' COMMENT 'dias de credito para que pague el cliente' ,
  `limite_credito` FLOAT NOT NULL DEFAULT '0' COMMENT 'Limite de credito otorgado al cliente' ,
  `descuento` TINYINT(4) NOT NULL DEFAULT '0' COMMENT 'Taza porcentual de descuento de 0 a 100' ,
  PRIMARY KEY (`id_cliente`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `pos`.`proveedor`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`proveedor` ;

CREATE  TABLE IF NOT EXISTS `pos`.`proveedor` (
  `id_proveedor` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador del proveedor' ,
  `rfc` VARCHAR(20) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL COMMENT 'rfc del proveedor' ,
  `nombre` VARCHAR(30) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL COMMENT 'nombre del proveedor' ,
  `direccion` VARCHAR(100) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL COMMENT 'direccion del proveedor' ,
  `telefono` VARCHAR(20) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL COMMENT 'telefono' ,
  `e_mail` VARCHAR(60) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL COMMENT 'email del provedor' ,
  PRIMARY KEY (`id_proveedor`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `pos`.`sucursal`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`sucursal` ;

CREATE  TABLE IF NOT EXISTS `pos`.`sucursal` (
  `id_sucursal` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador de cada sucursal' ,
  `descripcion` VARCHAR(100) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL COMMENT 'nombre o descripcion de sucursal' ,
  `direccion` VARCHAR(200) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL COMMENT 'direccion de la sucursal' ,
  PRIMARY KEY (`id_sucursal`) )
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `pos`.`usuario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`usuario` ;

CREATE  TABLE IF NOT EXISTS `pos`.`usuario` (
  `id_usuario` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador del usuario' ,
  `nombre` VARCHAR(100) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL COMMENT 'nombre del empleado' ,
  `usuario` VARCHAR(50) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `contrasena` VARCHAR(128) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `id_sucursal` INT(11) NOT NULL COMMENT 'Id de la sucursal a que pertenece' ,
  PRIMARY KEY (`id_usuario`) ,
  INDEX `fk_usuario_1` (`id_sucursal` ASC) ,
  CONSTRAINT `usuario_ibfk_1`
    FOREIGN KEY (`id_sucursal` )
    REFERENCES `pos`.`sucursal` (`id_sucursal` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `pos`.`compras`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`compras` ;

CREATE  TABLE IF NOT EXISTS `pos`.`compras` (
  `id_compra` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'id de la compra' ,
  `id_proveedor` INT(11) NOT NULL COMMENT 'PROVEEDOR AL QUE SE LE COMPRO' ,
  `tipo_compra` ENUM('credito','contado') CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL COMMENT 'tipo de compra, contado o credito' ,
  `fecha` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha de compra' ,
  `subtotal` FLOAT NOT NULL COMMENT 'subtotal de compra' ,
  `iva` FLOAT NOT NULL COMMENT 'iva de la compra' ,
  `id_sucursal` INT(11) NOT NULL COMMENT 'sucursal en que se compro' ,
  `id_usuario` INT(11) NOT NULL COMMENT 'quien realizo la compra' ,
  PRIMARY KEY (`id_compra`) ,
  INDEX `compras_proveedor` (`id_proveedor` ASC) ,
  INDEX `compras_sucursal` (`id_sucursal` ASC) ,
  INDEX `compras_usuario` (`id_usuario` ASC) ,
  CONSTRAINT `compras_ibfk_1`
    FOREIGN KEY (`id_proveedor` )
    REFERENCES `pos`.`proveedor` (`id_proveedor` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `compras_ibfk_2`
    FOREIGN KEY (`id_sucursal` )
    REFERENCES `pos`.`sucursal` (`id_sucursal` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `compras_ibfk_3`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `pos`.`usuario` (`id_usuario` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `pos`.`corte`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`corte` ;

CREATE  TABLE IF NOT EXISTS `pos`.`corte` (
  `num_corte` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'numero de corte' ,
  `anio` YEAR NOT NULL COMMENT 'año del corte' ,
  `inicio` DATE NOT NULL COMMENT 'año del corte' ,
  `fin` DATE NOT NULL COMMENT 'fecha de fin del corte' ,
  `ventas` FLOAT NOT NULL COMMENT 'ventas al contado en ese periodo' ,
  `abonosVentas` FLOAT NOT NULL COMMENT 'pagos de abonos en este periodo' ,
  `compras` FLOAT NOT NULL COMMENT 'compras realizadas en ese periodo' ,
  `AbonosCompra` FLOAT NOT NULL COMMENT 'pagos realizados en ese periodo' ,
  `gastos` FLOAT NOT NULL COMMENT 'gastos echos en ese periodo' ,
  `ingresos` FLOAT NOT NULL COMMENT 'ingresos obtenidos en ese periodo' ,
  `gananciasNetas` FLOAT NOT NULL COMMENT 'ganancias netas dentro del periodo' ,
  PRIMARY KEY (`num_corte`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `pos`.`cotizacion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`cotizacion` ;

CREATE  TABLE IF NOT EXISTS `pos`.`cotizacion` (
  `id_cotizacion` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'id de la cotizacion' ,
  `id_cliente` INT(11) NOT NULL COMMENT 'id del cliente' ,
  `fecha` DATE NOT NULL COMMENT 'fecha de cotizacion' ,
  `subtotal` FLOAT NOT NULL COMMENT 'subtotal de la cotizacion' ,
  `iva` FLOAT NOT NULL COMMENT 'iva sobre el subtotal' ,
  `id_sucursal` INT(11) NOT NULL ,
  `id_usuario` INT(11) NOT NULL ,
  PRIMARY KEY (`id_cotizacion`) ,
  INDEX `cotizacion_cliente` (`id_cliente` ASC) ,
  INDEX `fk_cotizacion_1` (`id_sucursal` ASC) ,
  INDEX `fk_cotizacion_2` (`id_usuario` ASC) ,
  CONSTRAINT `cotizacion_ibfk_1`
    FOREIGN KEY (`id_cliente` )
    REFERENCES `pos`.`cliente` (`id_cliente` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `cotizacion_ibfk_2`
    FOREIGN KEY (`id_sucursal` )
    REFERENCES `pos`.`sucursal` (`id_sucursal` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `cotizacion_ibfk_3`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `pos`.`usuario` (`id_usuario` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `pos`.`inventario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`inventario` ;

CREATE  TABLE IF NOT EXISTS `pos`.`inventario` (
  `id_producto` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'id del producto' ,
  `nombre` VARCHAR(90) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL COMMENT 'Descripcion o nombre del producto' ,
  `denominacion` VARCHAR(30) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL COMMENT 'es lo que se le mostrara a los clientes' ,
  PRIMARY KEY (`id_producto`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `pos`.`detalle_compra`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`detalle_compra` ;

CREATE  TABLE IF NOT EXISTS `pos`.`detalle_compra` (
  `id_compra` INT(11) NOT NULL COMMENT 'id de la compra' ,
  `id_producto` INT(11) NOT NULL COMMENT 'id del producto' ,
  `cantidad` FLOAT NOT NULL COMMENT 'cantidad comprada' ,
  `precio` FLOAT NOT NULL COMMENT 'costo de compra' ,
  PRIMARY KEY (`id_compra`, `id_producto`) ,
  INDEX `detalle_compra_producto` (`id_producto` ASC) ,
  CONSTRAINT `detalle_compra_ibfk_1`
    FOREIGN KEY (`id_compra` )
    REFERENCES `pos`.`compras` (`id_compra` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `detalle_compra_ibfk_2`
    FOREIGN KEY (`id_producto` )
    REFERENCES `pos`.`inventario` (`id_producto` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `pos`.`detalle_corte`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`detalle_corte` ;

CREATE  TABLE IF NOT EXISTS `pos`.`detalle_corte` (
  `num_corte` INT(11) NOT NULL COMMENT 'id del corte al que hace referencia' ,
  `nombre` VARCHAR(100) NOT NULL COMMENT 'nombre del encargado de sucursal al momento del corte' ,
  `total` FLOAT NOT NULL COMMENT 'total que le corresponde al encargado al momento del corte' ,
  `deben` FLOAT NOT NULL COMMENT 'lo que deben en la sucursal del encargado al momento del corte' ,
  PRIMARY KEY (`num_corte`, `nombre`) ,
  INDEX `corte_detalleCorte` (`num_corte` ASC) ,
  CONSTRAINT `corte_detalleCorte`
    FOREIGN KEY (`num_corte` )
    REFERENCES `pos`.`corte` (`num_corte` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `pos`.`detalle_cotizacion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`detalle_cotizacion` ;

CREATE  TABLE IF NOT EXISTS `pos`.`detalle_cotizacion` (
  `id_cotizacion` INT(11) NOT NULL COMMENT 'id de la cotizacion' ,
  `id_producto` INT(11) NOT NULL COMMENT 'id del producto' ,
  `cantidad` FLOAT NOT NULL COMMENT 'cantidad cotizado' ,
  `precio` FLOAT NOT NULL COMMENT 'precio al que cotizo el producto' ,
  PRIMARY KEY (`id_cotizacion`, `id_producto`) ,
  INDEX `detalle_cotizacion_producto` (`id_producto` ASC) ,
  CONSTRAINT `detalle_cotizacion_ibfk_1`
    FOREIGN KEY (`id_cotizacion` )
    REFERENCES `pos`.`cotizacion` (`id_cotizacion` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `detalle_cotizacion_ibfk_2`
    FOREIGN KEY (`id_producto` )
    REFERENCES `pos`.`inventario` (`id_producto` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `pos`.`detalle_inventario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`detalle_inventario` ;

CREATE  TABLE IF NOT EXISTS `pos`.`detalle_inventario` (
  `id_producto` INT(11) NOT NULL COMMENT 'id del producto al que se refiere' ,
  `id_sucursal` INT(11) NOT NULL COMMENT 'id de la sucursal' ,
  `precio_venta` FLOAT NOT NULL COMMENT 'precio al que se vendera al publico' ,
  `min` FLOAT NOT NULL DEFAULT '0' COMMENT 'cantidad minima que debe de haber del producto en almacen de esta sucursal' ,
  `existencias` FLOAT NOT NULL DEFAULT '0' COMMENT 'cantidad de producto que hay actualmente en almacen de esta sucursal' ,
  PRIMARY KEY (`id_producto`, `id_sucursal`) ,
  INDEX `id_sucursal` (`id_sucursal` ASC) ,
  CONSTRAINT `detalle_inventario_ibfk_1`
    FOREIGN KEY (`id_producto` )
    REFERENCES `pos`.`inventario` (`id_producto` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `detalle_inventario_ibfk_2`
    FOREIGN KEY (`id_sucursal` )
    REFERENCES `pos`.`sucursal` (`id_sucursal` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `pos`.`ventas`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`ventas` ;

CREATE  TABLE IF NOT EXISTS `pos`.`ventas` (
  `id_venta` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'id de venta' ,
  `id_cliente` INT(11) NOT NULL COMMENT 'cliente al que se le vendio' ,
  `tipo_venta` ENUM('credito','contado') CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL COMMENT 'tipo de venta, contado o credito' ,
  `fecha` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha de venta' ,
  `subtotal` FLOAT NOT NULL COMMENT 'subtotal de la venta' ,
  `iva` FLOAT NOT NULL COMMENT 'iva agregado por la venta' ,
  `id_sucursal` INT(11) NOT NULL COMMENT 'sucursal de la venta' ,
  `id_usuario` INT(11) NOT NULL COMMENT 'empleado que lo vendio' ,
  PRIMARY KEY (`id_venta`) ,
  INDEX `ventas_cliente` (`id_cliente` ASC) ,
  INDEX `ventas_sucursal` (`id_sucursal` ASC) ,
  INDEX `ventas_usuario` (`id_usuario` ASC) ,
  CONSTRAINT `ventas_ibfk_1`
    FOREIGN KEY (`id_cliente` )
    REFERENCES `pos`.`cliente` (`id_cliente` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_2`
    FOREIGN KEY (`id_sucursal` )
    REFERENCES `pos`.`sucursal` (`id_sucursal` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_3`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `pos`.`usuario` (`id_usuario` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `pos`.`detalle_venta`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`detalle_venta` ;

CREATE  TABLE IF NOT EXISTS `pos`.`detalle_venta` (
  `id_venta` INT(11) NOT NULL COMMENT 'venta a que se referencia' ,
  `id_producto` INT(11) NOT NULL COMMENT 'producto de la venta' ,
  `cantidad` FLOAT NOT NULL COMMENT 'cantidad que se vendio' ,
  `precio` FLOAT NOT NULL COMMENT 'precio al que se vendio' ,
  PRIMARY KEY (`id_venta`, `id_producto`) ,
  INDEX `detalle_venta_producto` (`id_producto` ASC) ,
  CONSTRAINT `detalle_venta_ibfk_1`
    FOREIGN KEY (`id_venta` )
    REFERENCES `pos`.`ventas` (`id_venta` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `detalle_venta_ibfk_2`
    FOREIGN KEY (`id_producto` )
    REFERENCES `pos`.`inventario` (`id_producto` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `pos`.`encargado`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`encargado` ;

CREATE  TABLE IF NOT EXISTS `pos`.`encargado` (
  `id_usuario` INT(11) NOT NULL COMMENT 'Este id es el del usuario encargado de su sucursal' ,
  `porciento` FLOAT NOT NULL COMMENT 'este es el porciento de las ventas que le tocan al encargado' ,
  PRIMARY KEY (`id_usuario`) ,
  INDEX `fk_encargado_1` (`id_usuario` ASC) ,
  INDEX `usuario_encargado` (`id_usuario` ASC) ,
  CONSTRAINT `usuario_encargado`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `pos`.`usuario` (`id_usuario` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `pos`.`factura_compra`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`factura_compra` ;

CREATE  TABLE IF NOT EXISTS `pos`.`factura_compra` (
  `folio` VARCHAR(15) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `id_compra` INT(11) NOT NULL COMMENT 'COMPRA A LA QUE CORRESPONDE LA FACTURA' ,
  PRIMARY KEY (`folio`) ,
  INDEX `factura_compra_compra` (`id_compra` ASC) ,
  CONSTRAINT `factura_compra_ibfk_1`
    FOREIGN KEY (`id_compra` )
    REFERENCES `pos`.`compras` (`id_compra` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `pos`.`factura_venta`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`factura_venta` ;

CREATE  TABLE IF NOT EXISTS `pos`.`factura_venta` (
  `folio` VARCHAR(15) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL COMMENT 'folio que tiene la factura' ,
  `id_venta` INT(11) NOT NULL COMMENT 'venta a la cual corresponde la factura' ,
  PRIMARY KEY (`folio`) ,
  INDEX `factura_venta_venta` (`id_venta` ASC) ,
  CONSTRAINT `factura_venta_ibfk_1`
    FOREIGN KEY (`id_venta` )
    REFERENCES `pos`.`ventas` (`id_venta` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `pos`.`gastos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`gastos` ;

CREATE  TABLE IF NOT EXISTS `pos`.`gastos` (
  `id_gasto` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'id para identificar el gasto' ,
  `concepto` VARCHAR(100) NOT NULL COMMENT 'concepto en lo que se gasto' ,
  `monto` FLOAT NOT NULL COMMENT 'lo que costo este gasto' ,
  `fecha` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha del gasto' ,
  `id_sucursal` INT(11) NOT NULL COMMENT 'sucursal en la que se hizo el gasto' ,
  `id_usuario` INT(11) NOT NULL COMMENT 'usuario que registro el gasto' ,
  PRIMARY KEY (`id_gasto`) ,
  INDEX `fk_gastos_1` (`id_usuario` ASC) ,
  INDEX `usuario_gasto` (`id_usuario` ASC) ,
  INDEX `sucursal_gasto` (`id_sucursal` ASC) ,
  CONSTRAINT `usuario_gasto`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `pos`.`usuario` (`id_usuario` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `sucursal_gasto`
    FOREIGN KEY (`id_sucursal` )
    REFERENCES `pos`.`sucursal` (`id_sucursal` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = MyISAM
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `pos`.`grupos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`grupos` ;

CREATE  TABLE IF NOT EXISTS `pos`.`grupos` (
  `id_grupo` INT(11) NOT NULL ,
  `nombre` VARCHAR(45) NOT NULL ,
  `descripcion` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id_grupo`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `pos`.`permisos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`permisos` ;

CREATE  TABLE IF NOT EXISTS `pos`.`permisos` (
  `id_permiso` INT(11) NOT NULL ,
  `nombre` VARCHAR(45) NOT NULL ,
  `descripcion` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id_permiso`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `pos`.`grupos_permisos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`grupos_permisos` ;

CREATE  TABLE IF NOT EXISTS `pos`.`grupos_permisos` (
  `id_grupo` INT(11) NOT NULL ,
  `id_permiso` INT(11) NOT NULL ,
  PRIMARY KEY (`id_grupo`, `id_permiso`) ,
  INDEX `fk_grupos_permisos_1` (`id_permiso` ASC) ,
  INDEX `fk_grupos_permisos_2` (`id_grupo` ASC) ,
  CONSTRAINT `grupos_permisos_ibfk_1`
    FOREIGN KEY (`id_grupo` )
    REFERENCES `pos`.`grupos` (`id_grupo` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `grupos_permisos_ibfk_2`
    FOREIGN KEY (`id_permiso` )
    REFERENCES `pos`.`permisos` (`id_permiso` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `pos`.`grupos_usuarios`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`grupos_usuarios` ;

CREATE  TABLE IF NOT EXISTS `pos`.`grupos_usuarios` (
  `id_grupo` INT(11) NOT NULL ,
  `id_usuario` INT(11) NOT NULL ,
  PRIMARY KEY (`id_grupo`, `id_usuario`) ,
  INDEX `fk_grupos_usuarios_1` (`id_grupo` ASC) ,
  INDEX `fk_grupos_usuarios_2` (`id_usuario` ASC) ,
  CONSTRAINT `grupos_usuarios_ibfk_1`
    FOREIGN KEY (`id_grupo` )
    REFERENCES `pos`.`grupos` (`id_grupo` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `grupos_usuarios_ibfk_2`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `pos`.`usuario` (`id_usuario` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `pos`.`impuesto`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`impuesto` ;

CREATE  TABLE IF NOT EXISTS `pos`.`impuesto` (
  `id_impuesto` INT(11) NOT NULL AUTO_INCREMENT ,
  `descripcion` VARCHAR(100) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `valor` INT(11) NOT NULL ,
  `id_sucursal` INT(11) NOT NULL ,
  PRIMARY KEY (`id_impuesto`) ,
  INDEX `fk_impuesto_1` (`id_sucursal` ASC) ,
  CONSTRAINT `impuesto_ibfk_1`
    FOREIGN KEY (`id_sucursal` )
    REFERENCES `pos`.`sucursal` (`id_sucursal` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `pos`.`ingresos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`ingresos` ;

CREATE  TABLE IF NOT EXISTS `pos`.`ingresos` (
  `id_ingreso` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'id para identificar el ingreso' ,
  `concepto` VARCHAR(100) NOT NULL COMMENT 'concepto en lo que se ingreso' ,
  `monto` FLOAT NOT NULL COMMENT 'lo que costo este ingreso' ,
  `fecha` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha del ingreso' ,
  `id_sucursal` INT(11) NOT NULL COMMENT 'sucursal en la que se hizo el ingreso' ,
  `id_usuario` INT(11) NOT NULL COMMENT 'usuario que registro el ingreso' ,
  PRIMARY KEY (`id_ingreso`) ,
  INDEX `fk_ingresos_1` (`id_usuario` ASC) ,
  INDEX `usuario_ingreso` (`id_usuario` ASC) ,
  INDEX `sucursal_ingreso` (`id_sucursal` ASC) ,
  CONSTRAINT `usuario_ingreso`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `pos`.`usuario` (`id_usuario` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `sucursal_ingreso`
    FOREIGN KEY (`id_sucursal` )
    REFERENCES `pos`.`sucursal` (`id_sucursal` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = MyISAM
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `pos`.`pagos_compra`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`pagos_compra` ;

CREATE  TABLE IF NOT EXISTS `pos`.`pagos_compra` (
  `id_pago` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador del pago' ,
  `id_compra` INT(11) NOT NULL COMMENT 'identificador de la compra a la que pagamos' ,
  `fecha` DATE NOT NULL COMMENT 'fecha en que se abono' ,
  `monto` FLOAT NOT NULL COMMENT 'monto que se abono' ,
  PRIMARY KEY (`id_pago`) ,
  INDEX `pagos_compra_compra` (`id_compra` ASC) ,
  CONSTRAINT `pagos_compra_ibfk_1`
    FOREIGN KEY (`id_compra` )
    REFERENCES `pos`.`compras` (`id_compra` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `pos`.`pagos_venta`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`pagos_venta` ;

CREATE  TABLE IF NOT EXISTS `pos`.`pagos_venta` (
  `id_pago` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'id de pago del cliente' ,
  `id_venta` INT(11) NOT NULL COMMENT 'id de la venta a la que se esta pagando' ,
  `fecha` DATE NOT NULL COMMENT 'fecha de pago' ,
  `monto` FLOAT NOT NULL COMMENT 'total de credito del cliente' ,
  PRIMARY KEY (`id_pago`) ,
  INDEX `pagos_venta_venta` (`id_venta` ASC) ,
  CONSTRAINT `pagos_venta_ibfk_1`
    FOREIGN KEY (`id_venta` )
    REFERENCES `pos`.`ventas` (`id_venta` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `pos`.`productos_proveedor`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`productos_proveedor` ;

CREATE  TABLE IF NOT EXISTS `pos`.`productos_proveedor` (
  `id_producto` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador del producto' ,
  `clave_producto` VARCHAR(20) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL COMMENT 'clave de producto para el proveedor' ,
  `id_proveedor` INT(11) NOT NULL COMMENT 'clave del proveedor' ,
  `id_inventario` INT(11) NOT NULL COMMENT 'clave con la que entra a nuestro inventario' ,
  `descripcion` VARCHAR(200) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL COMMENT 'Descripcion del producto que nos vende el proveedor' ,
  `precio` INT(11) NOT NULL COMMENT 'precio al que se compra el producto (sin descuento)' ,
  PRIMARY KEY (`id_producto`) ,
  UNIQUE INDEX `clave_producto` (`clave_producto` ASC, `id_proveedor` ASC) ,
  UNIQUE INDEX `id_proveedor` (`id_proveedor` ASC, `id_inventario` ASC) ,
  INDEX `productos_proveedor_proveedor` (`id_proveedor` ASC) ,
  INDEX `productos_proveedor_producto` (`id_inventario` ASC) ,
  CONSTRAINT `productos_proveedor_ibfk_1`
    FOREIGN KEY (`id_proveedor` )
    REFERENCES `pos`.`proveedor` (`id_proveedor` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `productos_proveedor_ibfk_2`
    FOREIGN KEY (`id_inventario` )
    REFERENCES `pos`.`inventario` (`id_producto` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- procedure mi_proc
-- -----------------------------------------------------

USE `pos`;
DROP procedure IF EXISTS `pos`.`mi_proc`;

DELIMITER $$
USE `pos`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `mi_proc`(venta INT)
SET @id_venta = venta$$

DELIMITER ;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
