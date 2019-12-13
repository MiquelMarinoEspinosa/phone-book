CREATE DATABASE hostaway;

USE hostaway;

CREATE TABLE phone_book (
    id varchar(255),
    first_name varchar(255) NOT NULL,
    last_name varchar(255),
    phone_number varchar(100) NOT NULL,
    country_code char(2),
    time_zone varchar(100),
    inserted_on DATE NOT NULL,
    updated_on DATE NOT NULL
);