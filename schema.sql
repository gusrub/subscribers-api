-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mailerlite
-- -----------------------------------------------------
-- Sample database for code excercise for subscribers API
DROP SCHEMA IF EXISTS `mailerlite` ;

-- -----------------------------------------------------
-- Schema mailerlite
--
-- Sample database for code excercise for subscribers API
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mailerlite` DEFAULT CHARACTER SET utf8 ;
USE `mailerlite` ;

-- -----------------------------------------------------
-- Table `mailerlite`.`campaigns`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mailerlite`.`campaigns` ;

CREATE TABLE IF NOT EXISTS `mailerlite`.`campaigns` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mailerlite`.`subscribers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mailerlite`.`subscribers` ;

CREATE TABLE IF NOT EXISTS `mailerlite`.`subscribers` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(255) NOT NULL,
  `status` TINYINT(3) NULL DEFAULT 0,
  `campaign_id` INT NOT NULL,
  PRIMARY KEY (`id`, `campaign_id`),
  CONSTRAINT `fk_subscribers_campaigns1`
    FOREIGN KEY (`campaign_id`)
    REFERENCES `mailerlite`.`campaigns` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE UNIQUE INDEX `idx_email` ON `mailerlite`.`subscribers` (`email` ASC, `campaign_id` ASC);

CREATE INDEX `fk_subscribers_campaigns1_idx` ON `mailerlite`.`subscribers` (`campaign_id` ASC);


-- -----------------------------------------------------
-- Table `mailerlite`.`fields`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mailerlite`.`fields` ;

CREATE TABLE IF NOT EXISTS `mailerlite`.`fields` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `data_type` TINYINT(3) NOT NULL DEFAULT 0,
  `subscriber_id` INT NOT NULL,
  PRIMARY KEY (`id`, `subscriber_id`),
  CONSTRAINT `fk_fields_subscribers`
    FOREIGN KEY (`subscriber_id`)
    REFERENCES `mailerlite`.`subscribers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_fields_subscribers_idx` ON `mailerlite`.`fields` (`subscriber_id` ASC);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `mailerlite`.`campaigns`
-- -----------------------------------------------------
START TRANSACTION;
USE `mailerlite`;
INSERT INTO `mailerlite`.`campaigns` (`id`, `title`) VALUES (DEFAULT, 'A demo campaign');
INSERT INTO `mailerlite`.`campaigns` (`id`, `title`) VALUES (DEFAULT, 'The other campaign');

COMMIT;


-- -----------------------------------------------------
-- Data for table `mailerlite`.`subscribers`
-- -----------------------------------------------------
START TRANSACTION;
USE `mailerlite`;
INSERT INTO `mailerlite`.`subscribers` (`id`, `email`, `full_name`, `status`, `campaign_id`) VALUES (DEFAULT, 'john@example.com', 'John Doe', 0, 1);
INSERT INTO `mailerlite`.`subscribers` (`id`, `email`, `full_name`, `status`, `campaign_id`) VALUES (DEFAULT, 'jane@example.com', 'Jane Doe', 0, 1);
INSERT INTO `mailerlite`.`subscribers` (`id`, `email`, `full_name`, `status`, `campaign_id`) VALUES (DEFAULT, 'mark@example.com', 'Mark Doe', 1, 1);
INSERT INTO `mailerlite`.`subscribers` (`id`, `email`, `full_name`, `status`, `campaign_id`) VALUES (DEFAULT, 'lisa@example.com', 'Lisa Doe', 0, 2);
INSERT INTO `mailerlite`.`subscribers` (`id`, `email`, `full_name`, `status`, `campaign_id`) VALUES (DEFAULT, 'john@example.com', 'John Doe', 1, 2);

COMMIT;


-- -----------------------------------------------------
-- Data for table `mailerlite`.`fields`
-- -----------------------------------------------------
START TRANSACTION;
USE `mailerlite`;
INSERT INTO `mailerlite`.`fields` (`id`, `title`, `data_type`, `subscriber_id`) VALUES (DEFAULT, 'salutation', 0, 1);
INSERT INTO `mailerlite`.`fields` (`id`, `title`, `data_type`, `subscriber_id`) VALUES (DEFAULT, 'reward_points', 1, 1);
INSERT INTO `mailerlite`.`fields` (`id`, `title`, `data_type`, `subscriber_id`) VALUES (DEFAULT, 'member_since', 2, 1);
INSERT INTO `mailerlite`.`fields` (`id`, `title`, `data_type`, `subscriber_id`) VALUES (DEFAULT, 'premium_customer', 3, 1);

COMMIT;

