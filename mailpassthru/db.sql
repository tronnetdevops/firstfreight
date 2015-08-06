CREATE DATABASE `firstfreight` COLLATE = `utf8_unicode_ci`;

use `firstfreight`;

CREATE USER 'ffagent'@'localhost' IDENTIFIED BY 'ff1234';

GRANT USAGE ON `firstfreight`.* TO 'ffagent'@'localhost' IDENTIFIED BY 'ff1234';

GRANT ALL PRIVILEGES ON `firstfreight`.* TO 'ffagent'@'localhost' IDENTIFIED BY 'ff1234';

CREATE TABLE IF NOT EXISTS `firstfreight`.`messages` (
    `id` INT(11) NOT NULL AUTO_INCREMENT, 
    `date` TIMESTAMP NOT NULL DEFAULT NOW(),
	
    `user_id` INT(11) NOT NULL DEFAULT 0,
    `conversation_id` INT(11) NOT NULL DEFAULT 0,
	
    `subject` VARCHAR(255) NOT NULL DEFAULT 0,	
    `message` TEXT,
    `data` TEXT,	
	
    PRIMARY KEY (`id`)

) ENGINE=`InnoDB` DEFAULT CHARSET=`utf8` COLLATE=`utf8_unicode_ci`  AUTO_INCREMENT=1;


INSERT INTO `messages` (`user_id`, `conversation_id`, `subject`, `message`, `data`) VALUES (1, 1, 'Cool Info', 'This is a ramp up message', '{}');
INSERT INTO `messages` (`user_id`, `conversation_id`, `subject`, `message`, `data`) VALUES (2, 1, 'RE: Cool Info', 'example reply...', '{}');
INSERT INTO `messages` (`user_id`, `conversation_id`, `subject`, `message`, `data`) VALUES (2, 2, 'Another Convo', 'Second conversation', '{}');
INSERT INTO `messages` (`user_id`, `conversation_id`, `subject`, `message`, `data`) VALUES (1, 2, 'RE: Another Convo', 'This is a ramp up message', '{}');
