CREATE TABLE `pprobat`.`new_table` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(45) NOT NULL,
  `name` VARCHAR(200) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `roles` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC));
---
ALTER TABLE `pprobat`.`new_table` 
RENAME TO  `pprobat`.`user` ;
---
ALTER TABLE `pprobat`.`user` 
ADD COLUMN `affiliation` DATETIME NOT NULL AFTER `roles`,
ADD COLUMN `creation` TIMESTAMP NOT NULL DEFAULT current_timestamp AFTER `affiliation`;
---
ALTER TABLE `pprobat`.`user` 
ADD COLUMN `about` TEXT NULL AFTER `creation`;
---
CREATE TABLE `pprobat`.`meetup` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `publicnumber` INT NULL COMMENT 'Meetup’s number for admin control',
  `local` VARCHAR(45) NOT NULL,
  `localinstructions` TEXT NULL COMMENT 'Instructions to reach the local',
  `when` DATETIME NOT NULL,
  `report` TEXT NULL COMMENT 'Report about what happened at the meetup',
  `meetuptype` CHAR(1) NOT NULL DEFAULT 'P' COMMENT 'Meetup’s type: (P)rimary, (S)pecial or (O)ther',
  `creation` TIMESTAMP NULL,
  PRIMARY KEY (`id`));
---
ALTER TABLE `pprobat`.`meetup` 
CHANGE COLUMN `creation` `creation` TIMESTAMP NOT NULL DEFAULT current_timestamp ;
---
ALTER TABLE `pprobat`.`meetup` 
CHANGE COLUMN `localinstructions` `notes` TEXT NULL DEFAULT NULL COMMENT 'free text for observations' ;
---
ALTER TABLE `pprobat`.`meetup` 
CHANGE COLUMN `publicnumber` `title` VARCHAR(255) NOT NULL COMMENT 'Meetup’s number for admin control' ;
---
ALTER TABLE `pprobat`.`meetup` 
CHANGE COLUMN `when` `happening` DATETIME NOT NULL COMMENT 'When the meet up will take place' ;
---
CREATE TABLE `pprobat`.`game` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL COMMENT 'Game’s name',
  `description` TEXT NOT NULL COMMENT 'Game’s description',
  PRIMARY KEY (`id`));
---
CREATE TABLE `pprobat`.`user_game` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user` INT NOT NULL COMMENT 'reference user table',
  `game` INT NOT NULL COMMENT 'reference game table',
  PRIMARY KEY (`id`),
  INDEX `ug_fk_user_idx` (`user` ASC),
  INDEX `ug_fk_game_idx` (`game` ASC),
  CONSTRAINT `ug_fk_user`
    FOREIGN KEY (`user`)
    REFERENCES `pprobat`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `ug_fk_game`
    FOREIGN KEY (`game`)
    REFERENCES `pprobat`.`game` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
---
ALTER TABLE `pprobat`.`user` 
RENAME TO  `pprobat`.`member` ;
---
ALTER TABLE `pprobat`.`user_game` 
RENAME TO  `pprobat`.`member_game` ;
---
ALTER TABLE `pprobat`.`member_game` 
DROP FOREIGN KEY `ug_fk_user`;
ALTER TABLE `pprobat`.`member_game` 
CHANGE COLUMN `user` `member` INT(11) NOT NULL COMMENT 'reference member table' ;
ALTER TABLE `pprobat`.`member_game` 
ADD CONSTRAINT `ug_fk_user`
  FOREIGN KEY (`member`)
  REFERENCES `pprobat`.`member` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;
---
