--
-- Create table `tickets` to store information about tickets
--

CREATE TABLE IF NOT EXISTS `tickets` (`id` INT NOT NULL AUTO_INCREMENT,
    `merchantId` INT NOT NULL ,
    `theme` VARCHAR(200) NOT NULL ,
    `timeCreated` DATETIME NOT NULL ,
    `timeUpdated` DATETIME NOT NULL ,
    `status` VARCHAR(60) NOT NULL ,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8 ;

--
-- Create table `ticket_messages` to store all messages for tickets
--

CREATE TABLE IF NOT EXISTS `ticket_messages` (`id` INT NOT NULL AUTO_INCREMENT,
    `ticketId` INT NOT NULL ,
    `body` TEXT NOT NULL ,
    `userId` INT NOT NULL ,
    `timeCreated` DATETIME NOT NULL ,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8 ;