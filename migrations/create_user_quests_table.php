<?php

$sql = "CREATE TABLE UserQuests (
    user_id INT,
    quest_id INT,
    completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, quest_id),
    FOREIGN KEY (user_id) REFERENCES Users(id),
    FOREIGN KEY (quest_id) REFERENCES Quests(id)
);";
