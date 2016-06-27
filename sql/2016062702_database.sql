--
-- Change type of some columns
--

ALTER TABLE `merchants` CHANGE `id` `id` INT NOT NULL AUTO_INCREMENT;

ALTER TABLE `stores` CHANGE `id` `id` INT NOT NULL AUTO_INCREMENT;

ALTER TABLE `stores` CHANGE `url` `url` TEXT NOT NULL;