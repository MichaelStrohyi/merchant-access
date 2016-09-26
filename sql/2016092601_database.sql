--
-- Change name of some columns
--

ALTER TABLE `coupons` CHANGE `start_date` `startDate` DATE NULL DEFAULT NULL;

ALTER TABLE `coupons` CHANGE `expire_date` `expireDate` DATE NULL DEFAULT NULL;