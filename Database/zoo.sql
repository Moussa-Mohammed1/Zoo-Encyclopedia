CREATE DATABASE zoo;
USE zoo;

CREATE TABLE habitat(
    habitat_ID INT PRIMARY KEY AUTO_INCREMENT,
    habitat_name VARCHAR(100),
    habitat_desc TEXT
);

CREATE TABLE animal(
    ID INT PRIMARY KEY AUTO_INCREMENT,
    animal_name VARCHAR(100),
    animal_type VARCHAR(100),
    animal_img VARCHAR(200),
    habitat_ID INT,
    FOREIGN KEY (habitat_ID) REFERENCES habitat(habitat_ID)
);
