
--
-- Change type of some columns
--

ALTER TABLE `stores` CHANGE `url` `url` BLOB NOT NULL;


--
-- Delete unnecessary columns
--

ALTER TABLE `stores` DROP COLUMN `logo`, 
    DROP COLUMN `logo_type`