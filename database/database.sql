show databases;

CREATE DATABASE youcode_brief_8;

USE youcode_brief_8;

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE cards (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description text null,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    user_id INT NULL,
    CONSTRAINT fk_userid_cards FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
);

CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description text null,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    user_id INT NULL,
    CONSTRAINT fk_userid_categories FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
);

CREATE TABLE transactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    amount FLOAT NOT NULL,
    description text null,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    id_card_sender INT NULL,
    id_card_receiver INT NULL,
    CONSTRAINT fk_card_senderid_transaction FOREIGN KEY (id_card_sender) REFERENCES cards (id),
    CONSTRAINT fk_card_receiverid_transaction FOREIGN KEY (id_card_receiver) REFERENCES cards (id)
);

create table incomes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    amount INT NOT NULL,
    description VARCHAR(500) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    id_card INT NOT null ,
    CONSTRAINT fk_id_card_incomes FOREIGN KEY (id_card) REFERENCES cards(id)
);

create table expenses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    amount INT NOT NULL,
    description VARCHAR(500) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    category_id INT NULL,
    CONSTRAINT fk_categoryid FOREIGN KEY (category_id) REFERENCES categories (id)
);

SHOW TABLES ;
SELECT * FROM users ;
SELECT * FROM cards ;