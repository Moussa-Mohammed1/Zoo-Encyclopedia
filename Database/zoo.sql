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

-- insert data into habitat 
INSERT INTO habitat(habitat_name, habitat_desc)
VALUES 
('Savane', 'Habitat de savane, riche en herbes et grands espaces'),
('Jungle', 'Habitat de jungle, dense et tropical'),
('Desert', 'Habitat desertique avec climat sec'),
('Ocean', 'Habitat marin avec une grande biodiversite');

--insert data into animal
INSERT INTO animal(animal_name, animal_type, animal_img, habitat_ID)
VALUES 
('Lion', 'Carnivore', 'lion.jpg', 1),
('Elephant', 'Herbivore', 'elephant.jpg', 1),
('Tiger', 'Carnivore', 'tiger.jpg', 2),
('Camel', 'Herbivore', 'camel.jpg', 3);

-- Edit habitat 
UPDATE habitat
SET habitat_name = 'Savane Africaine',
    habitat_desc = 'Grande savane avec herbes et quelques arbres'
WHERE habitat_ID = 1;

-- Edit animal 
UPDATE animal
SET animal_name = 'African Lion',
    animal_type = 'Carnivore',
    animal_img = 'african_lion.jpg',
    habitat_ID = 1
WHERE ID = 1;