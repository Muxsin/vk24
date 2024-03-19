<?php

use Muhsin\VK\Core\Database;

return Database::getInstance()->query(
    <<<SQL
CREATE TABLE Users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL UNIQUE,
    balance INT DEFAULT 0
)
SQL
);
