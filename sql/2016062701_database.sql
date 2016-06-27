--
-- Create table `stores` to store information about merchant's stores
--

CREATE TABLE IF NOT EXISTS `stores` (`id` TINYINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    `name` VARCHAR( 40 ) NOT NULL ,
    `status` VARCHAR( 40 ) NOT NULL ,
    `owner` TINYINT NOT NULL ,
    `url` VARCHAR( 60 ) NOT NULL 
) ENGINE = InnoDB DEFAULT CHARSET=utf8 ;