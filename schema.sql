-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema subscribers_api
-- -----------------------------------------------------
-- Sample database for code excercise for subscribers API
DROP SCHEMA IF EXISTS `subscribers_api` ;

-- -----------------------------------------------------
-- Schema subscribers_api
--
-- Sample database for code excercise for subscribers API
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `subscribers_api` ;
USE `subscribers_api` ;

-- -----------------------------------------------------
-- Table `subscribers_api`.`campaigns`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `subscribers_api`.`campaigns` ;

CREATE TABLE IF NOT EXISTS `subscribers_api`.`campaigns` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `subscribers_api`.`subscribers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `subscribers_api`.`subscribers` ;

CREATE TABLE IF NOT EXISTS `subscribers_api`.`subscribers` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `fullName` VARCHAR(255) NOT NULL,
  `status` TINYINT(3) NULL DEFAULT 0,
  `campaignId` INT NOT NULL,
  PRIMARY KEY (`id`, `campaignId`),
  CONSTRAINT `fk_subscribers_campaigns1`
    FOREIGN KEY (`campaignId`)
    REFERENCES `subscribers_api`.`campaigns` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE UNIQUE INDEX `idx_email` ON `subscribers_api`.`subscribers` (`email` ASC, `campaignId` ASC);

CREATE INDEX `fk_subscribers_campaigns1_idx` ON `subscribers_api`.`subscribers` (`campaignId` ASC);


-- -----------------------------------------------------
-- Table `subscribers_api`.`fields`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `subscribers_api`.`fields` ;

CREATE TABLE IF NOT EXISTS `subscribers_api`.`fields` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `dataType` TINYINT(3) NOT NULL DEFAULT 0,
  `subscriberId` INT NOT NULL,
  PRIMARY KEY (`id`, `subscriberId`),
  CONSTRAINT `fk_fields_subscribers`
    FOREIGN KEY (`subscriberId`)
    REFERENCES `subscribers_api`.`subscribers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_fields_subscribers_idx` ON `subscribers_api`.`fields` (`subscriberId` ASC);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `subscribers_api`.`campaigns`
-- -----------------------------------------------------
START TRANSACTION;
USE `subscribers_api`;
INSERT INTO `subscribers_api`.`campaigns` (`id`, `title`) VALUES (DEFAULT, 'Test campaign');
INSERT INTO `subscribers_api`.`campaigns` (`id`, `title`) VALUES (DEFAULT, 'Another campaign');

COMMIT;


-- -----------------------------------------------------
-- Data for table `subscribers_api`.`subscribers`
-- -----------------------------------------------------
START TRANSACTION;
USE `subscribers_api`;
INSERT INTO `subscribers_api`.`subscribers` (`id`, `email`, `fullName`, `status`, `campaignId`) VALUES (DEFAULT, 'john@example.com', 'John Doe', 0, 1);
INSERT INTO `subscribers_api`.`subscribers` (`id`, `email`, `fullName`, `status`, `campaignId`) VALUES (DEFAULT, 'jane@example.com', 'Jane Doe', 0, 1);
INSERT INTO `subscribers_api`.`subscribers` (`id`, `email`, `fullName`, `status`, `campaignId`) VALUES (DEFAULT, 'mark@example.com', 'Mark Doe', 1, 1);
INSERT INTO `subscribers_api`.`subscribers` (`id`, `email`, `fullName`, `status`, `campaignId`) VALUES (DEFAULT, 'lisa@example.com', 'Lisa Doe', 0, 2);
INSERT INTO `subscribers_api`.`subscribers` (`id`, `email`, `fullName`, `status`, `campaignId`) VALUES (DEFAULT, 'john@example.com', 'John Doe', 1, 2);

COMMIT;


-- -----------------------------------------------------
-- Data for table `subscribers_api`.`fields`
-- -----------------------------------------------------
START TRANSACTION;
USE `subscribers_api`;
INSERT INTO `subscribers_api`.`fields` (`id`, `title`, `dataType`, `subscriberId`) VALUES (DEFAULT, 'salutation', 0, 1);
INSERT INTO `subscribers_api`.`fields` (`id`, `title`, `dataType`, `subscriberId`) VALUES (DEFAULT, 'reward_points', 1, 1);
INSERT INTO `subscribers_api`.`fields` (`id`, `title`, `dataType`, `subscriberId`) VALUES (DEFAULT, 'member_since', 2, 1);
INSERT INTO `subscribers_api`.`fields` (`id`, `title`, `dataType`, `subscriberId`) VALUES (DEFAULT, 'premium_customer', 3, 1);

COMMIT;

