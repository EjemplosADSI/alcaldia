-- MySQL Script generated by MySQL Workbench
-- Wed Jul 21 19:12:26 2021
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema alcaldia
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema alcaldia
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `alcaldia` DEFAULT CHARACTER SET utf8 ;
USE `alcaldia` ;

-- -----------------------------------------------------
-- Table `alcaldia`.`departamentos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `alcaldia`.`departamentos` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(90) NOT NULL,
  `region` ENUM('Caribe', 'Centro Oriente', 'Centro Sur', 'Eje Cafetero - Antioquia', 'Llano', 'Pacífico') NOT NULL,
  `estado` ENUM('Activo', 'Inactivo') NOT NULL DEFAULT 'Activo',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `departamentos_nombre_unique` (`nombre` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 100
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `alcaldia`.`municipios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `alcaldia`.`municipios` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(90) NOT NULL,
  `departamento_id` BIGINT(20) UNSIGNED NOT NULL,
  `acortado` VARCHAR(40) NULL DEFAULT NULL,
  `estado` ENUM('Activo', 'Inactivo') NOT NULL DEFAULT 'Activo',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `municipios_nombre_index` (`nombre` ASC),
  INDEX `municipios_departamento_id_index` (`departamento_id` ASC),
  CONSTRAINT `municipios_departamento_id_foreign`
    FOREIGN KEY (`departamento_id`)
    REFERENCES `alcaldia`.`departamentos` (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 99774
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin;


-- -----------------------------------------------------
-- Table `alcaldia`.`Usuarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `alcaldia`.`Usuarios` (
  `id` INT NOT NULL,
  `nombres` VARCHAR(30) NOT NULL,
  `Apellidos` VARCHAR(30) NOT NULL,
  `TipoDocumento` ENUM('Cedula de Ciudadania', 'Cedula de  Extranjeria', 'Pasaporte', 'Nit') NOT NULL,
  `Documento` BIGINT NOT NULL,
  `Telefono` BIGINT NOT NULL,
  `Direccion` VARCHAR(30) NOT NULL,
  `municipios_id` BIGINT(20) UNSIGNED NOT NULL,
  `Correo` VARCHAR(60) NOT NULL,
  `User` VARCHAR(30) NOT NULL,
  `Password` VARCHAR(30) NOT NULL,
  `Rol` ENUM('Administrador', 'Ventanilla Unica', 'Contratista') NOT NULL,
  `Estado` ENUM('Activo', 'Inactivo') NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Usuarios_municipios1_idx` (`municipios_id` ASC),
  CONSTRAINT `fk_Usuarios_municipios1`
    FOREIGN KEY (`municipios_id`)
    REFERENCES `alcaldia`.`municipios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alcaldia`.`Contratos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `alcaldia`.`Contratos` (
  `id` INT NOT NULL,
  `Tipo` ENUM('Contratacion Directa', 'Minima Cuantia', 'Seleccion Abreviada de Menor Cuantia', 'Seleccion Abreviada Subasta Inversa', 'Licitacion Publica') NOT NULL,
  `Siglas` VARCHAR(45) NOT NULL,
  `Objeto` TEXT NOT NULL,
  `Obligaciones` TEXT NOT NULL,
  `FechaInicio` DATE NOT NULL,
  `FechaFinal` DATE NOT NULL,
  `Valor` DECIMAL NOT NULL,
  `Enlace_Secop` VARCHAR(100) NOT NULL,
  `Estado` ENUM('Activo', 'Suspendido', 'Liquidado', 'Cancelado') NOT NULL,
  `Usuarios_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Contratos_Usuarios_idx` (`Usuarios_id` ASC),
  CONSTRAINT `fk_Contratos_Usuarios`
    FOREIGN KEY (`Usuarios_id`)
    REFERENCES `alcaldia`.`Usuarios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alcaldia`.`Pagos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `alcaldia`.`Pagos` (
  `id` INT NOT NULL,
  `Numero` BIGINT NOT NULL,
  `Valor` DECIMAL NOT NULL,
  `Tipo` ENUM('Parcial', 'Total', 'Avance') NOT NULL,
  `Fecha` DATE NOT NULL,
  `Contratos_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Pagos_Contratos1_idx` (`Contratos_id` ASC),
  CONSTRAINT `fk_Pagos_Contratos1`
    FOREIGN KEY (`Contratos_id`)
    REFERENCES `alcaldia`.`Contratos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alcaldia`.`Conservaciones`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `alcaldia`.`Conservaciones` (
  `id` INT NOT NULL,
  `NumeroCaja` DECIMAL NOT NULL,
  `Carpeta` VARCHAR(45) NOT NULL,
  `Folio` BIGINT NOT NULL,
  `Tomo` VARCHAR(150) NOT NULL,
  `MedioEntrega` ENUM('fisico', 'digital', 'magnetico') NOT NULL,
  `Notas` NCHAR(150) NOT NULL,
  `TipoVisibilidad` ENUM('Privado', 'Publico') NOT NULL,
  `Contratos_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Conservaciones_Contratos1_idx` (`Contratos_id` ASC),
  CONSTRAINT `fk_Conservaciones_Contratos1`
    FOREIGN KEY (`Contratos_id`)
    REFERENCES `alcaldia`.`Contratos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
