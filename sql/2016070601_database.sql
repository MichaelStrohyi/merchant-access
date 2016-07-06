--
-- Create table `images` to store images
--

CREATE TABLE IF NOT EXISTS `images` (`id` INT NOT NULL AUTO_INCREMENT,
    `width` INT NOT NULL ,
    `height` INT NOT NULL ,
    `mime` VARCHAR( 20 ) NOT NULL ,
    `size` INT  NOT NULL ,
    `type` VARCHAR( 20 ) NOT NULL ,
    `name` VARCHAR( 60 ) NOT NULL ,
    `content` BLOB NOT NULL ,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8 ;

--
-- Create table `store_images` for relations store=>image
--

CREATE TABLE IF NOT EXISTS `store_logos` (`store_id` INT NOT NULL,
    `logo_id` INT NOT NULL,
    PRIMARY KEY (`store_id`, `logo_id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8 ;