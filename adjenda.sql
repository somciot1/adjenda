-- create and select the database
drop database if exists adjendaDatabase;
create database adjendaDatabase;
use adjendaDatabase;

create table STUDENTS(
    email varchar(255),
    fname varchar(255),
    lname varchar(255),
    pass varchar(255),
    verified tinyint(1) DEFAULT 0,
    PRIMARY KEY(email)
);

create table INSTRUCTORS(
    email varchar(255),
    fname varchar(255),
    lname varchar(255),
    pass varchar(255),
    PRIMARY KEY(email)
);

-- Create a user named adjenda with password as adjendapass
GRANT SELECT, INSERT, DELETE, UPDATE
ON adjendaDatabase.*
TO adjenda@localhost
IDENTIFIED BY 'adjendapass';