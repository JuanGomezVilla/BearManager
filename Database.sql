-- DATABASE SETUP
CREATE DATABASE BearManager CHARACTER SET utf8 COLLATE utf8_general_ci;

-- SELECT THE DATABASE
USE BearManager;

-- TABLES

-- Users
CREATE TABLE users (
    username VARCHAR(150) PRIMARY KEY, -- Username, primary value
    nickname VARCHAR(150) NOT NULL, -- Nickname, not to be confused with the user value
    password CHAR(32)  NOT NULL, -- Password
    userType ENUM("administrator", "teacher", "student") NOT NULL, -- Type of user
    language VARCHAR(50) NOT NULL -- Preferred language
) ENGINE=InnoDB;

-- Publications
CREATE TABLE publications (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Primary post identifier
    student VARCHAR(150) NOT NULL, -- User identifier (username)
    title VARCHAR(150) NOT NULL, -- Post title
    accepted BOOLEAN DEFAULT 0 NOT NULL, -- Publication accepted, by default no
    image VARCHAR(150) NOT NULL, -- Image path
    content VARCHAR(9999) NOT NULL, -- Content
    FOREIGN KEY (student) REFERENCES users(username) -- User id reference
) ENGINE=InnoDB;

-- Settings
CREATE TABLE settings (
    name VARCHAR(50) PRIMARY KEY, -- Setting name
    value VARCHAR(50) NOT NULL -- Setting value
) ENGINE=InnoDB;

-- PROCEDURES
DELIMITER $$

-- Creates an user
CREATE PROCEDURE createUser(
    paramUsername VARCHAR(150), -- Username
    paramNickname VARCHAR(150), -- Nickname
    paramPassword CHAR(32), -- Password, in MD5
    paramUserType ENUM("administrator", "teacher", "student"), -- User type
    paramLanguage VARCHAR(50) -- Language
)
BEGIN
    INSERT INTO users VALUES (paramUsername, paramNickname, paramPassword, paramUserType, paramLanguage);
END $$

-- Creates a post
CREATE PROCEDURE createPublication(
    paramStudent VARCHAR(150), -- User identifier (username)
    paramTitle VARCHAR(150), -- Post title
    paramAccepted BOOLEAN, -- Publication accepted
    paramImage VARCHAR(150), -- Image path
    paramContent VARCHAR(9999) -- Content
)
BEGIN
    INSERT INTO publications (student, title, accepted, image, content) VALUES (
        paramStudent, paramTitle, paramAccepted, paramImage, paramContent
    );
END $$

-- Get all users
CREATE PROCEDURE getUsers()
BEGIN
    SELECT username, nickname, userType, language,
        (SELECT COUNT(*) FROM publications WHERE student = username) AS publications
    FROM users;
END $$

-- Reset autoincrement publications
CREATE OR REPLACE PROCEDURE resetAutoincrementPublications()
BEGIN
    SET @num := 0;
    UPDATE publications SET id = @num := (@num + 1);
    ALTER TABLE publications AUTO_INCREMENT = 1;
END $$

DELIMITER ;


-- DEFAULT VALUES

-- Users
CALL createUser("admin", "Admin", MD5("admin"), "administrator", "en");
CALL createUser("teacher1", "Teacher 1", MD5("123"), "teacher", "en");
CALL createUser("student1", "Student 1", MD5("123"), "student", "en");

-- Publications
CALL createPublication("student1", "Title 1", 0, "image 1", "Content 1");
CALL createPublication("student1", "Title 2", 0, "image 2", "Content 2");
CALL createPublication("student1", "Title 3", 0, "image 3", "Content 3");
CALL createPublication("student1", "Title 4", 1, "image 4", "Content 4");
CALL createPublication("student1", "Title 5", 1, "image 5", "Content 5");

-- Settings
INSERT INTO settings VALUES ("max_publications", "3");

-- RESULTS
SELECT * FROM users; -- Users
SELECT * FROM publications; -- Publications
SELECT * FROM settings; -- Settings

-- Finish