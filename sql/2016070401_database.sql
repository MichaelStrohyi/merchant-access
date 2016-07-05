--
-- Add column `search_keywods` into table `stores` to store keywords for search engines
--

ALTER TABLE `stores` ADD `keywords` TEXT AFTER `reg_date`;

--
-- Add column `search_description` into table `stores` to store description for search engines
--

ALTER TABLE `stores` ADD `description` TEXT AFTER `keywords`;

--
-- Change type of some columns
--

ALTER TABLE `stores` CHANGE `owner` `owner` INT NOT NULL;