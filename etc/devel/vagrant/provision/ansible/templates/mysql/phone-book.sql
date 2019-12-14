CREATE DATABASE IF NOT EXISTS hostaway;

USE hostaway;

CREATE TABLE IF NOT EXISTS phone_book (
    id varchar(255) NOT NULL,
    firstName varchar(255) NOT NULL,
    lastName varchar(255),
    phoneNumber varchar(100) NOT NULL,
    countryCode char(2),
    timeZone varchar(100),
    insertedOn DATETIME NOT NULL,
    updatedOn DATETIME NOT NULL,
    PRIMARY KEY (id)
);