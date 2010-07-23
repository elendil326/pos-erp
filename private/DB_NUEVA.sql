SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `mydb` ;
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
SHOW WARNINGS;
DROP SCHEMA IF EXISTS `POS` ;
CREATE SCHEMA IF NOT EXISTS `POS` ;
SHOW WARNINGS;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`grupos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`grupos` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `mydb`.`grupos` (
  `id_grupo` INT NOT NULL ,
  `nombre` VARCHAR(45) NOT NULL ,
  `descripcion` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id_grupo`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `mydb`.`permisos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`permisos` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `mydb`.`permisos` (
  `id_permiso` INT NOT NULL ,
  `nombre` VARCHAR(45) NOT NULL ,
  `descripcion` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id_permiso`) ,
  INDEX `fk_permisos_1` () )
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `mydb`.`grupos_permisos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`grupos_permisos` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `mydb`.`grupos_permisos` (
  `id_grupo` INT NOT NULL ,
  `id_permiso` INT NOT NULL ,
  PRIMARY KEY (`id_grupo`, `id_permiso`) ,
  INDEX `fk_grupos_permisos_1` (`id_permiso` ASC) ,
  INDEX `fk_grupos_permisos_2` (`id_grupo` ASC) ,
  CONSTRAINT `fk_grupos_permisos_1`
    FOREIGN KEY (`id_permiso` )
    REFERENCES `mydb`.`permisos` (`id_permiso` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_grupos_permisos_2`
    FOREIGN KEY (`id_grupo` )
    REFERENCES `mydb`.`grupos` (`id_grupo` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `POS`.`sucursal`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `POS`.`sucursal` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `POS`.`sucursal` (
  `id_sucursal` INT NOT NULL AUTO_INCREMENT COMMENT 'Identificador de cada sucursal' ,
  `descripcion` VARCHAR(100) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT NULL COMMENT 'nombre o descripcion de sucursal' ,
  `direccion` VARCHAR(200) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT NULL COMMENT 'direccion de la sucursal' ,
  PRIMARY KEY (`id_sucursal`) )
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `POS`.`usuario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `POS`.`usuario` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `POS`.`usuario` (
  `id_usuario` INT NOT NULL AUTO_INCREMENT COMMENT 'identificador del usuario' ,
  `nombre` VARCHAR(100) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL COMMENT 'nombre del empleado' ,
  `usuario` VARCHAR(50) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `contrasena` VARCHAR(128) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `sucursal_id` INT NOT NULL COMMENT 'Id de la sucursal a que pertenece' ,
  PRIMARY KEY (`id_usuario`) ,
  INDEX `fk_usuario_1` (`sucursal_id` ASC) ,
  CONSTRAINT `fk_usuario_1`
    FOREIGN KEY (`sucursal_id` )
    REFERENCES `POS`.`sucursal` (`id_sucursal` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `mydb`.`grupos_usuarios`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`grupos_usuarios` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `mydb`.`grupos_usuarios` (
  `id_grupo` INT NOT NULL ,
  `id_usuario` INT NOT NULL ,
  PRIMARY KEY (`id_grupo`, `id_usuario`) ,
  INDEX `fk_grupos_usuarios_1` (`id_grupo` ASC) ,
  INDEX `fk_grupos_usuarios_2` (`id_usuario` ASC) ,
  CONSTRAINT `fk_grupos_usuarios_1`
    FOREIGN KEY (`id_grupo` )
    REFERENCES `mydb`.`grupos` (`id_grupo` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_grupos_usuarios_2`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `POS`.`usuario` (`id_usuario` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

SHOW WARNINGS;
USE `POS` ;

-- -----------------------------------------------------
-- Table `POS`.`cliente`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `POS`.`cliente` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `POS`.`cliente` (
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
AUTO_INCREMENT = 54
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `POS`.`proveedor`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `POS`.`proveedor` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `POS`.`proveedor` (
  `id_proveedor` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador del proveedor' ,
  `rfc` VARCHAR(20) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL COMMENT 'rfc del proveedor' ,
  `nombre` VARCHAR(30) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL COMMENT 'nombre del proveedor' ,
  `direccion` VARCHAR(100) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL COMMENT 'direccion del proveedor' ,
  `telefono` VARCHAR(20) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL COMMENT 'telefono' ,
  `e_mail` VARCHAR(60) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL COMMENT 'email del provedor' ,
  PRIMARY KEY (`id_proveedor`) )
ENGINE = InnoDB
AUTO_INCREMENT = 10
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `POS`.`compras`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `POS`.`compras` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `POS`.`compras` (
  `id_compra` INT NOT NULL AUTO_INCREMENT COMMENT 'id de la compra' ,
  `id_proveedor` INT NOT NULL COMMENT 'PROVEEDOR AL QUE SE LE COMPRO' ,
  `tipo_compra` ENUM('credito', 'contado') NOT NULL COMMENT 'tipo de compra, contado o credito' ,
  `fecha` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha de compra' ,
  `subtotal` FLOAT NOT NULL COMMENT 'subtotal de compra' ,
  `iva` FLOAT NOT NULL COMMENT 'iva de la compra' ,
  `sucursal` INT NOT NULL COMMENT 'sucursal en que se compro' ,
  `id_usuario` INT NOT NULL COMMENT 'quien realizo la compra' ,
  PRIMARY KEY (`id_compra`) ,
  INDEX `compras_proveedor` (`id_proveedor` ASC) ,
  INDEX `compras_sucursal` (`sucursal` ASC) ,
  INDEX `compras_usuario` (`id_usuario` ASC) ,
  CONSTRAINT `compras_ibfk_1`
    FOREIGN KEY (`id_proveedor` )
    REFERENCES `POS`.`proveedor` (`id_proveedor` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `compras_ibfk_2`
    FOREIGN KEY (`sucursal` )
    REFERENCES `POS`.`sucursal` (`id_sucursal` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `compras_ibfk_3`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `POS`.`usuario` (`id_usuario` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `POS`.`cotizacion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `POS`.`cotizacion` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `POS`.`cotizacion` (
  `id_cotizacion` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'id de la cotizacion' ,
  `id_cliente` INT(11) NOT NULL COMMENT 'id del cliente' ,
  `fecha` DATE NOT NULL COMMENT 'fecha de cotizacion' ,
  `subtotal` FLOAT NOT NULL COMMENT 'subtotal de la cotizacion' ,
  `iva` FLOAT NOT NULL COMMENT 'iva sobre el subtotal' ,
  `id_sucursal` INT NOT NULL ,
  `id_usuario` INT NOT NULL ,
  PRIMARY KEY (`id_cotizacion`) ,
  INDEX `cotizacion_cliente` (`id_cliente` ASC) ,
  INDEX `fk_cotizacion_1` (`id_sucursal` ASC) ,
  INDEX `fk_cotizacion_2` (`id_usuario` ASC) ,
  CONSTRAINT `fk_cotizacion_1`
    FOREIGN KEY (`id_sucursal` )
    REFERENCES `POS`.`sucursal` (`id_sucursal` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_cotizacion_2`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `POS`.`usuario` (`id_usuario` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = latin1;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `POS`.`inventario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `POS`.`inventario` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `POS`.`inventario` (
  `id_producto` INT NOT NULL AUTO_INCREMENT COMMENT 'id del producto' ,
  `nombre` VARCHAR(90) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL COMMENT 'Descripcion o nombre del producto' ,
  `denominacion` VARCHAR(30) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL COMMENT 'es lo que se le mostrara a los clientes' ,
  PRIMARY KEY (`id_producto`) )
ENGINE = InnoDB
AUTO_INCREMENT = 10
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `POS`.`detalle_compra`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `POS`.`detalle_compra` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `POS`.`detalle_compra` (
  `id_compra` INT NOT NULL COMMENT 'id de la compra' ,
  `id_producto` INT NOT NULL COMMENT 'id del producto' ,
  `cantidad` FLOAT NOT NULL COMMENT 'cantidad comprada' ,
  `precio` FLOAT NOT NULL COMMENT 'costo de compra' ,
  PRIMARY KEY (`id_compra`, `id_producto`) ,
  INDEX `detalle_compra_producto` (`id_producto` ASC) ,
  CONSTRAINT `detalle_compra_ibfk_1`
    FOREIGN KEY (`id_compra` )
    REFERENCES `POS`.`compras` (`id_compra` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `detalle_compra_ibfk_2`
    FOREIGN KEY (`id_producto` )
    REFERENCES `POS`.`inventario` (`id_producto` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `POS`.`detalle_cotizacion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `POS`.`detalle_cotizacion` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `POS`.`detalle_cotizacion` (
  `id_cotizacion` INT(11) NOT NULL COMMENT 'id de la cotizacion' ,
  `id_producto` INT(11) NOT NULL COMMENT 'id del producto' ,
  `cantidad` FLOAT NOT NULL COMMENT 'cantidad cotizado' ,
  `precio` FLOAT NOT NULL COMMENT 'precio al que cotizo el producto' ,
  PRIMARY KEY (`id_cotizacion`, `id_producto`) ,
  INDEX `detalle_cotizacion_producto` (`id_producto` ASC) ,
  CONSTRAINT `detalle_cotizacion_ibfk_1`
    FOREIGN KEY (`id_cotizacion` )
    REFERENCES `POS`.`cotizacion` (`id_cotizacion` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `detalle_cotizacion_ibfk_2`
    FOREIGN KEY (`id_producto` )
    REFERENCES `POS`.`inventario` (`id_producto` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `POS`.`detalle_inventario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `POS`.`detalle_inventario` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `POS`.`detalle_inventario` (
  `id_producto` INT NOT NULL COMMENT 'id del producto al que se refiere' ,
  `id_sucursal` INT NOT NULL COMMENT 'id de la sucursal' ,
  `precio_venta` FLOAT NOT NULL COMMENT 'precio al que se vendera al publico' ,
  `min` FLOAT NOT NULL DEFAULT '0' COMMENT 'cantidad minima que debe de haber del producto en almacen de esta sucursal' ,
  `existencias` FLOAT NOT NULL DEFAULT '0' COMMENT 'cantidad de producto que hay actualmente en almacen de esta sucursal' ,
  PRIMARY KEY (`id_producto`, `id_sucursal`) ,
  INDEX `id_sucursal` (`id_sucursal` ASC) ,
  CONSTRAINT `detalle_inventario_ibfk_1`
    FOREIGN KEY (`id_producto` )
    REFERENCES `POS`.`inventario` (`id_producto` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `detalle_inventario_ibfk_2`
    FOREIGN KEY (`id_sucursal` )
    REFERENCES `POS`.`sucursal` (`id_sucursal` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `POS`.`ventas`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `POS`.`ventas` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `POS`.`ventas` (
  `id_venta` INT NOT NULL AUTO_INCREMENT COMMENT 'id de venta' ,
  `id_cliente` INT NOT NULL COMMENT 'cliente al que se le vendio' ,
  `tipo_venta` ENUM('credito', 'contado') NOT NULL COMMENT 'tipo de venta, contado o credito' ,
  `fecha` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha de venta' ,
  `subtotal` FLOAT NOT NULL COMMENT 'subtotal de la venta' ,
  `iva` FLOAT NOT NULL COMMENT 'iva agregado por la venta' ,
  `sucursal` INT NOT NULL COMMENT 'sucursal de la venta' ,
  `id_usuario` INT NOT NULL COMMENT 'empleado que lo vendio' ,
  PRIMARY KEY (`id_venta`) ,
  INDEX `ventas_cliente` (`id_cliente` ASC) ,
  INDEX `ventas_sucursal` (`sucursal` ASC) ,
  INDEX `ventas_usuario` (`id_usuario` ASC) ,
  CONSTRAINT `ventas_ibfk_1`
    FOREIGN KEY (`id_cliente` )
    REFERENCES `POS`.`cliente` (`id_cliente` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_2`
    FOREIGN KEY (`sucursal` )
    REFERENCES `POS`.`sucursal` (`id_sucursal` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_3`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `POS`.`usuario` (`id_usuario` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 15
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `POS`.`detalle_venta`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `POS`.`detalle_venta` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `POS`.`detalle_venta` (
  `id_venta` INT(11) NOT NULL COMMENT 'venta a que se referencia' ,
  `id_producto` INT(11) NOT NULL COMMENT 'producto de la venta' ,
  `cantidad` FLOAT NOT NULL COMMENT 'cantidad que se vendio' ,
  `precio` FLOAT NOT NULL COMMENT 'precio al que se vendio' ,
  PRIMARY KEY (`id_venta`, `id_producto`) ,
  INDEX `detalle_venta_producto` (`id_producto` ASC) ,
  CONSTRAINT `detalle_venta_ibfk_1`
    FOREIGN KEY (`id_venta` )
    REFERENCES `POS`.`ventas` (`id_venta` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `detalle_venta_ibfk_2`
    FOREIGN KEY (`id_producto` )
    REFERENCES `POS`.`inventario` (`id_producto` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `POS`.`encargado`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `POS`.`encargado` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `POS`.`encargado` (
  `id_usuario` INT NOT NULL COMMENT 'Este id es el del usuario encargado de su sucursal' ,
  `porciento` FLOAT NOT NULL COMMENT 'este es el porciento de las ventas que le tocan al encargado' ,
  PRIMARY KEY (`id_usuario`) ,
  INDEX `fk_encargado_1` (`id_usuario` ASC) ,
  CONSTRAINT `fk_encargado_1`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `POS`.`usuario` (`id_usuario` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `POS`.`factura_compra`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `POS`.`factura_compra` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `POS`.`factura_compra` (
  `folio` VARCHAR(15) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `id_compra` INT(11) NOT NULL COMMENT 'COMPRA A LA QUE CORRESPONDE LA FACTURA' ,
  INDEX `factura_compra_compra` (`id_compra` ASC) ,
  PRIMARY KEY (`folio`) ,
  CONSTRAINT `factura_compra_ibfk_1`
    FOREIGN KEY (`id_compra` )
    REFERENCES `POS`.`compras` (`id_compra` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `POS`.`factura_venta`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `POS`.`factura_venta` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `POS`.`factura_venta` (
  `folio` VARCHAR(15) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL COMMENT 'folio que tiene la factura' ,
  `id_venta` INT(11) NOT NULL COMMENT 'venta a la cual corresponde la factura' ,
  INDEX `factura_venta_venta` (`id_venta` ASC) ,
  PRIMARY KEY (`folio`) ,
  CONSTRAINT `factura_venta_ibfk_1`
    FOREIGN KEY (`id_venta` )
    REFERENCES `POS`.`ventas` (`id_venta` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `POS`.`gastos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `POS`.`gastos` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `POS`.`gastos` (
  `id_gasto` INT NOT NULL AUTO_INCREMENT COMMENT 'id para identificar el gasto' ,
  `concepto` VARCHAR(100) NOT NULL COMMENT 'concepto en lo que se gasto' ,
  `monto` FLOAT NOT NULL COMMENT 'lo que costo este gasto' ,
  `fecha` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha del gasto' ,
  `id_sucursal` INT NOT NULL COMMENT 'sucursal en la que se hizo el gasto' ,
  `id_usuario` INT NOT NULL COMMENT 'usuario que registro el gasto' ,
  PRIMARY KEY (`id_gasto`) ,
  INDEX `fk_gastos_1` (`id_usuario` ASC) ,
  CONSTRAINT `fk_gastos_1`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `POS`.`sucursal` (`id_sucursal` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = MyISAM
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = latin1;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `POS`.`impuesto`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `POS`.`impuesto` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `POS`.`impuesto` (
  `id_impuesto` INT(11) NOT NULL AUTO_INCREMENT ,
  `descripcion` VARCHAR(100) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `valor` INT(11) NOT NULL ,
  `id_sucursal` INT NOT NULL ,
  PRIMARY KEY (`id_impuesto`) ,
  INDEX `fk_impuesto_1` (`id_sucursal` ASC) ,
  CONSTRAINT `fk_impuesto_1`
    FOREIGN KEY (`id_sucursal` )
    REFERENCES `POS`.`sucursal` (`id_sucursal` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `POS`.`ingresos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `POS`.`ingresos` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `POS`.`ingresos` (
  `id_ingreso` INT NOT NULL AUTO_INCREMENT COMMENT 'id para identificar el ingreso' ,
  `concepto` VARCHAR(100) NOT NULL COMMENT 'concepto en lo que se ingreso' ,
  `monto` FLOAT NOT NULL COMMENT 'lo que costo este ingreso' ,
  `fecha` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha del ingreso' ,
  `id_sucursal` INT NOT NULL COMMENT 'sucursal en la que se hizo el ingreso' ,
  `id_usuario` INT NOT NULL COMMENT 'usuario que registro el ingreso' ,
  PRIMARY KEY (`id_ingreso`) ,
  INDEX `fk_ingresos_1` (`id_usuario` ASC) ,
  CONSTRAINT `fk_ingresos_1`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `POS`.`sucursal` (`id_sucursal` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = MyISAM
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = latin1;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `POS`.`pagos_compra`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `POS`.`pagos_compra` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `POS`.`pagos_compra` (
  `id_pago` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador del pago' ,
  `id_compra` INT(11) NOT NULL COMMENT 'identificador de la compra a la que pagamos' ,
  `fecha` DATE NOT NULL COMMENT 'fecha en que se abono' ,
  `monto` FLOAT NOT NULL COMMENT 'monto que se abono' ,
  PRIMARY KEY (`id_pago`) ,
  INDEX `pagos_compra_compra` (`id_compra` ASC) ,
  CONSTRAINT `pagos_compra_ibfk_1`
    FOREIGN KEY (`id_compra` )
    REFERENCES `POS`.`compras` (`id_compra` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `POS`.`pagos_venta`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `POS`.`pagos_venta` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `POS`.`pagos_venta` (
  `id_pago` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'id de pago del cliente' ,
  `id_venta` INT(11) NOT NULL COMMENT 'id de la venta a la que se esta pagando' ,
  `fecha` DATE NOT NULL COMMENT 'fecha de pago' ,
  `monto` FLOAT NOT NULL COMMENT 'total de credito del cliente' ,
  PRIMARY KEY (`id_pago`) ,
  INDEX `pagos_venta_venta` (`id_venta` ASC) ,
  CONSTRAINT `pagos_venta_ibfk_1`
    FOREIGN KEY (`id_venta` )
    REFERENCES `POS`.`ventas` (`id_venta` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 63
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `POS`.`productos_proveedor`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `POS`.`productos_proveedor` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `POS`.`productos_proveedor` (
  `id_producto` INT NOT NULL AUTO_INCREMENT COMMENT 'identificador del producto' ,
  `clave_producto` VARCHAR(20) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL COMMENT 'clave de producto para el proveedor' ,
  `id_proveedor` INT NOT NULL COMMENT 'clave del proveedor' ,
  `id_inventario` INT NOT NULL COMMENT 'clave con la que entra a nuestro inventario' ,
  `descripcion` VARCHAR(200) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL COMMENT 'Descripcion del producto que nos vende el proveedor' ,
  `precio` INT NOT NULL COMMENT 'precio al que se compra el producto (sin descuento)' ,
  PRIMARY KEY (`id_producto`) ,
  UNIQUE INDEX `clave_producto` (`clave_producto` ASC, `id_proveedor` ASC) ,
  UNIQUE INDEX `id_proveedor` (`id_proveedor` ASC, `id_inventario` ASC) ,
  INDEX `productos_proveedor_proveedor` (`id_proveedor` ASC) ,
  INDEX `productos_proveedor_producto` (`id_inventario` ASC) ,
  CONSTRAINT `productos_proveedor_ibfk_1`
    FOREIGN KEY (`id_proveedor` )
    REFERENCES `POS`.`proveedor` (`id_proveedor` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `productos_proveedor_ibfk_2`
    FOREIGN KEY (`id_inventario` )
    REFERENCES `POS`.`inventario` (`id_producto` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 14
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

SHOW WARNINGS;

-- -----------------------------------------------------
-- procedure mi_proc
-- -----------------------------------------------------

USE `POS`;
DROP procedure IF EXISTS `POS`.`mi_proc`;
SHOW WARNINGS;

DELIMITER $$
USE `POS`$$
DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `mi_proc`(venta INT)
SET @id_venta = venta
$$
DELIMITER ;

$$

DELIMITER ;SHOW WARNINGS;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
