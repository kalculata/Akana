
CREATE DATABASE `akana_example_db`;

CREATE TABLE `akana_example_db`.`users__user` ( 
    `pk` int(11) NOT NULL AUTO_INCREMENT , 
    `first_name` VARCHAR(50) NOT NULL , 
    `last_name` VARCHAR(50) NOT NULL , 
    `email` VARCHAR(50) NOT NULL , 
    `phone` INT(8) NULL,
    `password` VARCHAR(100) NOT NULL , 
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , 
    PRIMARY KEY (`pk`)
);

CREATE TABLE `akana_example_db`.`users__adress` ( 
    `pk` int(11) NOT NULL AUTO_INCREMENT , 
    `country` VARCHAR(50) NOT NULL DEFAULT 'Burundi' , 
    `province` VARCHAR(50) NOT NULL DEFAULT 'Bujumbura' , 
    `town` VARCHAR(50) NOT NULL , 
    `area` VARCHAR(50) NULL , 
    `quarter` VARCHAR(50) NOT NULL , 
    `street` VARCHAR(50) NULL , 
    `added_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , 
    PRIMARY KEY (`pk`)
);

CREATE TABLE `akana_example_db`.`products__product` ( 
    `pk` int(11) NOT NULL AUTO_INCREMENT , 
    `title` VARCHAR(100) NOT NULL , 
    `image` VARCHAR(255) NOT NULL,
    `size` VARCHAR(50) NULL , 
    `color` VARCHAR(50) NULL , 
    `price` INT(11) NOT NULL , 
    `added_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , 
    PRIMARY KEY (`pk`)
);

CREATE TABLE `akana_example_db`.`orders__order` (
    `pk` int(11) NOT NULL AUTO_INCREMENT,
    `size` varchar(50) NOT NULL,
    `color` varchar(50) NOT NULL,
    `price` int(11) NOT NULL,
    `quantity` int(11) NOT NULL,
    `total_amount` int(11) NOT NULL,
    `status` varchar(50) NOT NULL,
    `ordered_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `excepted_at` datetime NOT NULL,
    PRIMARY KEY (`pk`)
);