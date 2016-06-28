--
-- Add column `email` into table `stores` to store email for store
--

ALTER TABLE `stores` ADD `email` VARCHAR( 60 ) NOT NULL AFTER `url` ;

--
-- Add column `reg_date` into table `stores` to store date when store was added
--

ALTER TABLE `stores` ADD `reg_date` DATETIME NOT NULL AFTER `email` ;