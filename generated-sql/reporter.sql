
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- accounts
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `accounts`;

CREATE TABLE `accounts`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(128) NOT NULL,
    `password` VARCHAR(32) NOT NULL,
    `type` INTEGER NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- category
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(32) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- metric
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `metric`;

CREATE TABLE `metric`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `key` VARCHAR(64) NOT NULL,
    `description` TEXT,
    `ignoreuntil` INTEGER NOT NULL,
    `missingalert` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `key` (`key`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- metric_data
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `metric_data`;

CREATE TABLE `metric_data`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `metric_id` INTEGER NOT NULL,
    `time` INTEGER NOT NULL,
    `success` INTEGER(1) NOT NULL,
    `data` TEXT NOT NULL,
    `checked` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `metric_id` (`metric_id`),
    CONSTRAINT `metric_data_to_metric`
        FOREIGN KEY (`metric_id`)
        REFERENCES `metric` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- account_to_category
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `account_to_category`;

CREATE TABLE `account_to_category`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `account_id` INTEGER NOT NULL,
    `category_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `account_id` (`account_id`),
    INDEX `category_id` (`category_id`),
    CONSTRAINT `account_to_category_ibfk_2`
        FOREIGN KEY (`category_id`)
        REFERENCES `category` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `account_to_category_ibfk_1`
        FOREIGN KEY (`account_id`)
        REFERENCES `accounts` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- category_to_metric
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `category_to_metric`;

CREATE TABLE `category_to_metric`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `category_id` INTEGER NOT NULL,
    `metric_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `category_id` (`category_id`),
    INDEX `metric_id` (`metric_id`),
    CONSTRAINT `category_to_metric_ibfk_2`
        FOREIGN KEY (`metric_id`)
        REFERENCES `metric` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `category_to_metric_ibfk_1`
        FOREIGN KEY (`category_id`)
        REFERENCES `category` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
