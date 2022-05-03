-- create and select the database
drop database if exists adjendaDatabase;
create database adjendaDatabase;
use adjendaDatabase;

create table STUDENTS(
    email varchar(255) NOT NULL,
    fName varchar(255) NOT NULL,
    lName varchar(255) NOT NULL,
    pass varchar(255) NOT NULL,
    verified tinyint(1) DEFAULT 0 NOT NULL,
    PRIMARY KEY(email)
);

create table INSTRUCTORS(
    email varchar(255) NOT NULL,
    fName varchar(255) NOT NULL,
    lName varchar(255) NOT NULL,
    pass varchar(255) NOT NULL,
    PRIMARY KEY(email)
);

create table COURSES(
    id int(4) NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    instrEmail varchar(255) NOT NULL,
    days varchar(255) NOT NULL,
    sTime time NOT NULL,
    eTime time NOT NULL,
    FOREIGN KEY(instrEmail) REFERENCES INSTRUCTORS(email),
    PRIMARY KEY(id)
);

create table ROSTERS(
    courseID int(4) NOT NULL,
    stuEmail varchar(255) NOT NULL,
    fName varchar(255) NOT NULL,
    lName varchar(255) NOT NULL,
    enrolled tinyint(1) DEFAULT 0 NOT NULL,
    CONSTRAINT rPK PRIMARY KEY(courseID, stuEmail),
    FOREIGN KEY(courseID) REFERENCES COURSES(id),
    FOREIGN KEY(stuEmail) REFERENCES STUDENTS(email)
);

create table LESSONS(
    courseID int(4) NOT NULL,
    lDate date NOT NULL,
    INDEX(lDate), --testing for foreign key requirements
    startTime time NOT NULL,
    endTime time NOT NULL,
    code varchar(255),
    CONSTRAINT lPK PRIMARY KEY(courseID, lDate),
    FOREIGN KEY(courseID) REFERENCES COURSES(id)
);

create table ATTENDANCES(
    courseID int(4) NOT NULL,
    stuEmail varchar(255) NOT NULL,
    lessonDate date NOT NULL,
    INDEX(lessonDATE), --testing for foreign key requirements
    attended tinyint(1) DEFAULT 0,
    CONSTRAINT aPK PRIMARY KEY(courseID, stuEmail, lessonDate),
    FOREIGN KEY(courseID) REFERENCES COURSES(id),
    FOREIGN KEY(stuEmail) REFERENCES STUDENTS(email),
    FOREIGN KEY(lessonDate) REFERENCES LESSONS(lDate)
);

-- Create a user named adjenda with password as adjendapass
GRANT SELECT, INSERT, DELETE, UPDATE
ON adjendaDatabase.*
TO adjenda@localhost
IDENTIFIED BY 'adjendapass';