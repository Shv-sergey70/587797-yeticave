CREATE DATABASE yeticave DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;

USE YETICAVE;

CREATE TABLE categories (
id INT AUTO_INCREMENT PRIMARY KEY,
name CHAR(64)
);
CREATE TABLE lots (
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
CREATE TABLE bets (
id INT AUTO_INCREMENT PRIMARY KEY,
date_create DATETIME,
price INT,
user_id INT,
lot_id INT
);
CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY,
date_register DATETIME,
email CHAR(64),
name CHAR(64),
password CHAR(64),
avatar_url TEXT,
contacts TEXT
);
CREATE UNIQUE INDEX email ON users(email);
CREATE INDEX password ON users(password);

CREATE INDEX date_end ON lots(date_end);
CREATE INDEX date_create ON lots(date_create);
CREATE INDEX author_id ON lots(author_id);
CREATE INDEX winner_id ON lots(winner_id);
CREATE INDEX adv_category_id ON lots(adv_category_id);

CREATE INDEX user_id ON bets(user_id);
CREATE INDEX lot_id ON bets(lot_id);