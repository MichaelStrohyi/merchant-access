--
-- Change type of some columns
--

ALTER TABLE `coupons` CHANGE `label` `label` TEXT NOT NULL;

ALTER TABLE `coupons` CHANGE `link` `link` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;