<?php

use Muhsin\VK\Core\Database;

return Database::getInstance()->query(
    <<<SQL
CREATE TABLE Quests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL UNIQUE,
    cost INT NOT NULL
)
SQL
);
