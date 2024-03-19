<?php

$sql = "CREATE TABLE Quests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL UNIQUE,
    cost INT NOT NULL
);";
