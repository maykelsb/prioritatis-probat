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
