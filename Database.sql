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
    language VARCHAR(50) NOT NULL, -- Preferred language
    timesLogged INT NOT NULL, -- Times the user has logged in
    enabled BOOLEAN DEFAULT 0 NOT NULL,
    createAt DATETIME DEFAULT NOW() NOT NULL,
    updateAt DATETIME DEFAULT NOW() NOT NULL
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

-- History
CREATE TABLE history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(150),
    typeRecord ENUM("info", "warning", "critical") NOT NULL,
    createAt DATETIME DEFAULT NOW() NOT NULL,
    description VARCHAR(500),
    FOREIGN KEY (username) REFERENCES users(username) -- User id reference
) ENGINE=InnoDB;

-- Settings
CREATE TABLE settings (
    name VARCHAR(50) PRIMARY KEY, -- Setting name
    value VARCHAR(50) NOT NULL -- Setting value
) ENGINE=InnoDB;

-- PROCEDURES
DELIMITER $$

-- Creates an user
CREATE OR REPLACE PROCEDURE createUser(
    paramUsername VARCHAR(150), -- Username
    paramNickname VARCHAR(150), -- Nickname
    paramPassword CHAR(32), -- Password, in MD5
    paramUserType ENUM("administrator", "teacher", "student"), -- User type
    paramLanguage VARCHAR(50), -- Language
    paramTimesLogged INT, -- Times logged
    paramEnabled BOOLEAN,
    paramCreateAt DATETIME,
    paramUpdateAt DATETIME
)
BEGIN
    INSERT INTO users VALUES (
        paramUsername, paramNickname, paramPassword, paramUserType, paramLanguage,
        paramTimesLogged, paramEnabled, paramCreateAt, paramUpdateAt);
END $$

-- Set the enabled status of a user
CREATE OR REPLACE PROCEDURE setEnabledStatusUser(
    paramEnabled BOOLEAN,
    paramUsername VARCHAR(150)
)
BEGIN
    UPDATE users SET enabled = paramEnabled WHERE username = paramUsername;
END $$

-- Crea un registro de historia
CREATE or replace PROCEDURE createHistoryLog(
    paramUsername VARCHAR(150),
    paramTypeRecord ENUM("info", "warning", "critical"),
    paramDescription VARCHAR(500)
)
BEGIN
    INSERT INTO history (username, typeRecord, createAt, description) VALUES
        (paramUsername, paramTypeRecord, NOW(), paramDescription);
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
    SELECT username, nickname, userType, language, timesLogged,
        (SELECT COUNT(*) FROM publications WHERE student = username) AS publications
    FROM users;
END $$

-- Reset autoincrement publications
CREATE PROCEDURE resetAutoincrementPublications()
BEGIN
    SET @num := 0;
    UPDATE publications SET id = @num := (@num + 1);
    ALTER TABLE publications AUTO_INCREMENT = 1;
END $$

-- Get statistics
CREATE PROCEDURE getStatistics()
BEGIN
    SELECT COUNT(*) AS publications,
        (SELECT COUNT(*) FROM users WHERE userType = "student") AS students,
        (SELECT COUNT(*) FROM users)
    FROM publications;
END $$

-- Get quantity
CREATE PROCEDURE getQuantity()
BEGIN
    SELECT CONCAT(u.userType, "s") AS userType,
        COUNT(u.username) AS quantity
    FROM users u
    GROUP BY u.userType;
END $$

-- Increase a users logins by 1
CREATE PROCEDURE incrementTimesLogged(paramUsername VARCHAR(150))
BEGIN
    UPDATE users SET timesLogged = timesLogged + 1 WHERE username = paramUsername;
END $$

-- Get publications
CREATE PROCEDURE getPublications()
BEGIN
    SELECT id, student, title, accepted, SUBSTRING(content, 1, 20) AS content, image FROM publications;
END $$

-- Get accepted publications
CREATE PROCEDURE getAcceptedPublications()
BEGIN
    SELECT u.nickname AS student,
        p.title AS title,
        p.image AS image,
        p.content AS content
    FROM publications p INNER JOIN users u
    ON u.username = p.student
    WHERE p.accepted = 1;
END $$

-- FUNCTIONS

-- Function that returns if the post is accepted or not after having changed it
CREATE FUNCTION changeStatusPost(paramIDpost INT)
RETURNS INT
BEGIN
    UPDATE publications SET accepted = NOT accepted WHERE id = paramIDpost;
    RETURN (SELECT accepted FROM publications WHERE id = paramIDpost);
END $$

DELIMITER ;
