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
