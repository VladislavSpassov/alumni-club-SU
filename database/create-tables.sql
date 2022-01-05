DROP DATABASE if EXISTS alumniclub;

CREATE DATABASE alumniclub;

USE alumniclub;

CREATE TABLE users (
  id              INT NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'primary key',
  username        VARCHAR(20) NOT NULL UNIQUE,
  password        VARCHAR(255) NOT NULL,
  firstName       VARCHAR(20), -- NOT NULL,
  lastName        VARCHAR(20), -- NOT NULL,
  email           VARCHAR(30) NOT NULL UNIQUE,
  role            ENUM('admin', 'user'), -- NOT NULL
  speciality      VARCHAR(50) NOT NULL,
  graduationYear  YEAR(4) DEFAULT 1970,
  groupUni        INT(10) NOT NULL,
  faculty         VARCHAR(50) NOT NULL,
  longitude       DECIMAL(10,7),
  latitude        DECIMAL(10,7)
) default charset utf8 comment '';

CREATE TABLE contacts (
  id              INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  firstUserId     INT NOT NULL,
  secondUserId    INT NOT NULL,
  createTime      DATETIME COMMENT 'create time'
) default charset utf8 comment '';

CREATE TABLE posts (
  id              INT NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'primary key',
  privacy         ENUM(
                      'all',
                      'group',
                      'speciality',
                      'faculty',
                      'private' -- a.k.a people in your contacts
                  ) NOT NULL,
  userId          INT, -- NOT NULL,
  occasion        VARCHAR(255) NOT NULL,
  -- locationId      INT NOT NULL,
  location        VARCHAR(255) NOT NULL,
  content         VARCHAR(255) NOT NULL,
  occasionDate    DATETIME COMMENT 'event time' NOT NULL,
  createTime      DATETIME DEFAULT NOW() COMMENT 'create time' -- NOT NULL,
) default charset utf8 comment '';

CREATE TABLE user_post (
  userId          INT NOT NULL,
  postId          INT NOT NULL,
  isAccepted      BOOLEAN
) default charset utf8 comment '';

CREATE TABLE comments (
  id              INT NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'primary key',
  userId          INT NOT NULL,
  postId          INT NOT NULL,
  content         VARCHAR(255) NOT NULL,
  createTime      DATETIME COMMENT 'create time'
) default charset utf8 comment '';

ALTER TABLE
  user_post
ADD
  CONSTRAINT FK_Contact_User_Post_1 FOREIGN KEY (userId) REFERENCES users(id);

ALTER TABLE
  user_post
ADD
  CONSTRAINT FK_Contact_User_Post_2 FOREIGN KEY (postId) REFERENCES posts(id);

ALTER TABLE
  contacts
ADD
  CONSTRAINT FK_Contact_User_1 FOREIGN KEY (firstUserId) REFERENCES users(id);

ALTER TABLE
  contacts
ADD
  CONSTRAINT FK_Contact_User_2 FOREIGN KEY (secondUserId) REFERENCES users(id);

ALTER TABLE
  posts
ADD
  CONSTRAINT FK_Posts_Users FOREIGN KEY (userId) REFERENCES users(id);

ALTER TABLE
  comments
ADD
  CONSTRAINT FK_Comments_Post FOREIGN KEY (postId) REFERENCES posts(id);

ALTER TABLE
  comments
ADD
  CONSTRAINT FK_Comments_Users FOREIGN KEY (userId) REFERENCES users(id);