--
-- Create table `coupons` to store information about coupons for stores
--

CREATE TABLE IF NOT EXISTS `coupons` (`id` INT NOT NULL AUTO_INCREMENT,
    `store_id` INT NOT NULL ,
    `label` BLOB NOT NULL ,
    `code` VARCHAR(20) ,
    `link` BLOB NOT NULL ,
    `image` INT ,
    `start_date` DATE ,
    `expire_date` DATE ,
    `position` TINYINT NOT NULL ,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8 ;
