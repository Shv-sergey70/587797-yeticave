CREATE DATABASE YETICAVE DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;

USE YETICAVE;

CREATE TABLE CATEGORIES (
id INT AUTO_INCREMENT PRIMARY KEY,
name CHAR(64)
);
CREATE TABLE LOTS (
id INT AUTO_INCREMENT PRIMARY KEY,
date_create DATETIME,
name CHAR(64),
description TEXT,
image_url TEXT,
start_price INT,
date_end DATETIME,
bet_step INT,
author_id INT,
winner_id INT,
adv_category_id INT
);
CREATE TABLE BETS (
id INT AUTO_INCREMENT PRIMARY KEY,
date_create DATETIME,
price INT,
user_id INT,
lot_id INT
);
CREATE TABLE USERS (
id INT AUTO_INCREMENT PRIMARY KEY,
date_register DATETIME,
email CHAR(64),
name CHAR(64),
password CHAR(64),
avatar_url TEXT,
contacts TEXT,
created_lots_id INT,
bets_id INT
);