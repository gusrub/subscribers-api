-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema subscribers_api
-- -----------------------------------------------------
-- Sample database for code excercise for subscribers API
DROP SCHEMA IF EXISTS `subscribers_api_test` ;

-- -----------------------------------------------------
-- Schema subscribers_api
--
-- Sample database for code excercise for subscribers API
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `subscribers_api_test` ;
USE `subscribers_api_test` ;

-- -----------------------------------------------------
-- Table `campaigns`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `campaigns` ;

CREATE TABLE IF NOT EXISTS `campaigns` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `subscribers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `subscribers` ;

CREATE TABLE IF NOT EXISTS `subscribers` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `fullName` VARCHAR(255) NOT NULL,
  `status` VARCHAR(50) NULL DEFAULT 'active',
  `campaignId` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_subscribers_campaigns1`
    FOREIGN KEY (`campaignId`)
    REFERENCES `campaigns` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE UNIQUE INDEX `idx_email` ON `subscribers` (`email` ASC, `campaignId` ASC);

CREATE INDEX `fk_subscribers_campaigns1_idx` ON `subscribers` (`campaignId` ASC);


-- -----------------------------------------------------
-- Table `fields`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fields` ;

CREATE TABLE IF NOT EXISTS `fields` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `dataType` VARCHAR(50) NOT NULL DEFAULT 'string',
  `subscriberId` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_fields_subscribers`
    FOREIGN KEY (`subscriberId`)
    REFERENCES `subscribers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_fields_subscribers_idx` ON `fields` (`subscriberId` ASC);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `campaigns`
-- -----------------------------------------------------
START TRANSACTION;
USE `subscribers_api_test`;
INSERT INTO `campaigns` (`id`, `title`) VALUES (DEFAULT, 'Test campaign');
INSERT INTO `campaigns` (`id`, `title`) VALUES (DEFAULT, 'Another campaign');

COMMIT;


-- -----------------------------------------------------
-- Data for table `subscribers`
-- -----------------------------------------------------
START TRANSACTION;
USE `subscribers_api_test`;
INSERT INTO `subscribers` (`id`, `email`, `fullName`, `status`, `campaignId`) VALUES (DEFAULT, 'john@example.com', 'John Doe', 'active', 1);
INSERT INTO `subscribers` (`id`, `email`, `fullName`, `status`, `campaignId`) VALUES (DEFAULT, 'jane@example.com', 'Jane Doe', 'active', 1);
INSERT INTO `subscribers` (`id`, `email`, `fullName`, `status`, `campaignId`) VALUES (DEFAULT, 'mark@example.com', 'Mark Doe', 'unsubscribed', 1);
INSERT INTO `subscribers` (`id`, `email`, `fullName`, `status`, `campaignId`) VALUES (DEFAULT, 'lisa@example.com', 'Lisa Doe', 'active', 2);
INSERT INTO `subscribers` (`id`, `email`, `fullName`, `status`, `campaignId`) VALUES (DEFAULT, 'john@example.com', 'John Doe', 'unsubscribed', 2);

COMMIT;


-- -----------------------------------------------------
-- Data for table `fields`
-- -----------------------------------------------------
START TRANSACTION;
USE `subscribers_api_test`;
INSERT INTO `fields` (`id`, `title`, `dataType`, `subscriberId`) VALUES (DEFAULT, 'salutation', 'string', 1);
INSERT INTO `fields` (`id`, `title`, `dataType`, `subscriberId`) VALUES (DEFAULT, 'reward_points', 'number', 1);
INSERT INTO `fields` (`id`, `title`, `dataType`, `subscriberId`) VALUES (DEFAULT, 'member_since', 'date', 1);
INSERT INTO `fields` (`id`, `title`, `dataType`, `subscriberId`) VALUES (DEFAULT, 'premium_customer', 'boolean', 1);

COMMIT;

