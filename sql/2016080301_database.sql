--
-- Add column `activity` into table `coupon` to store coupon active(1)/deativated(0) status
--

ALTER TABLE `coupons` ADD `activity` TINYINT NOT NULL DEFAULT '1';